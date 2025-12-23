<?php
/**
 * Gestion du consentement RGPD
 * 
 * @package Almetal_Analytics
 */

if (!defined('ABSPATH')) {
    exit;
}

class Almetal_Analytics_Consent {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Hooks
    }
    
    /**
     * Enregistrer un consentement
     */
    public function log_consent($data) {
        global $wpdb;
        
        $consent_id = sanitize_text_field($data['consent_id'] ?? $this->generate_consent_id());
        $visitor_id = sanitize_text_field($data['visitor_id'] ?? '');
        
        // Anonymiser l'IP
        $ip = $this->get_client_ip();
        $ip_anonymized = $this->anonymize_ip($ip);
        
        // Vérifier si ce consentement existe déjà
        $existing = $wpdb->get_row($wpdb->prepare(
            "SELECT id FROM {$wpdb->prefix}almetal_analytics_consents WHERE consent_id = %s",
            $consent_id
        ));
        
        $categories = $data['categories'] ?? array();
        $consent_given = $this->has_any_consent($categories);
        
        if ($existing) {
            // Mettre à jour
            $result = $wpdb->update(
                $wpdb->prefix . 'almetal_analytics_consents',
                array(
                    'consent_categories' => wp_json_encode($categories),
                    'consent_given' => $consent_given ? 1 : 0,
                    'consent_version' => sanitize_text_field($data['version'] ?? '2.0'),
                ),
                array('consent_id' => $consent_id),
                array('%s', '%d', '%s'),
                array('%s')
            );
        } else {
            // Créer
            $result = $wpdb->insert(
                $wpdb->prefix . 'almetal_analytics_consents',
                array(
                    'consent_id' => $consent_id,
                    'visitor_id' => $visitor_id,
                    'ip_anonymized' => $ip_anonymized,
                    'user_agent' => sanitize_text_field(substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 512)),
                    'consent_categories' => wp_json_encode($categories),
                    'consent_given' => $consent_given ? 1 : 0,
                    'consent_version' => sanitize_text_field($data['version'] ?? '2.0'),
                    'page_url' => esc_url_raw($data['url'] ?? ''),
                ),
                array('%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s')
            );
        }
        
        return array(
            'success' => (bool) $result,
            'consent_id' => $consent_id
        );
    }
    
    /**
     * Récupérer un consentement
     */
    public function get_consent($consent_id) {
        global $wpdb;
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}almetal_analytics_consents WHERE consent_id = %s",
            $consent_id
        ), ARRAY_A);
    }
    
    /**
     * Vérifier si au moins un consentement non-nécessaire est donné
     */
    private function has_any_consent($categories) {
        $non_required = array('analytics', 'marketing', 'preferences');
        foreach ($non_required as $cat) {
            if (isset($categories[$cat]) && $categories[$cat] === true) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Générer un ID de consentement
     */
    private function generate_consent_id() {
        return 'consent_' . time() . '_' . bin2hex(random_bytes(8));
    }
    
    /**
     * Anonymiser l'IP
     */
    private function anonymize_ip($ip) {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return preg_replace('/\.\d+$/', '.0', $ip);
        } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return substr($ip, 0, strrpos($ip, ':')) . ':0000:0000:0000:0000:0000';
        }
        return '';
    }
    
    /**
     * Obtenir l'IP du client
     */
    private function get_client_ip() {
        $ip_keys = array('HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR');
        foreach ($ip_keys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        return '';
    }
    
    /**
     * Statistiques des consentements
     */
    public static function get_consent_stats($period = '30days') {
        global $wpdb;
        
        $date_from = date('Y-m-d H:i:s', strtotime('-30 days'));
        if ($period === '7days') {
            $date_from = date('Y-m-d H:i:s', strtotime('-7 days'));
        } elseif ($period === '90days') {
            $date_from = date('Y-m-d H:i:s', strtotime('-90 days'));
        }
        
        $total = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}almetal_analytics_consents WHERE created_at >= %s",
            $date_from
        ));
        
        $accepted = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}almetal_analytics_consents WHERE created_at >= %s AND consent_given = 1",
            $date_from
        ));
        
        $refused = $total - $accepted;
        
        return array(
            'total' => (int) $total,
            'accepted' => (int) $accepted,
            'refused' => (int) $refused,
            'acceptance_rate' => $total > 0 ? round(($accepted / $total) * 100, 1) : 0,
        );
    }
}
