<?php
/**
 * Fonctionnalités RGPD
 * 
 * @package Almetal_Analytics
 */

if (!defined('ABSPATH')) {
    exit;
}

class Almetal_Analytics_GDPR {
    
    /**
     * Chiffrer des données (AES-256)
     */
    public static function encrypt($data) {
        $key = self::get_encryption_key();
        $iv = openssl_random_pseudo_bytes(16);
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $encrypted);
    }
    
    /**
     * Déchiffrer des données
     */
    public static function decrypt($data) {
        $key = self::get_encryption_key();
        $data = base64_decode($data);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        return openssl_decrypt($encrypted, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    }
    
    /**
     * Obtenir la clé de chiffrement
     */
    private static function get_encryption_key() {
        $key = get_option('almetal_analytics_encryption_key');
        if (!$key) {
            $key = bin2hex(random_bytes(32));
            update_option('almetal_analytics_encryption_key', $key);
        }
        return hex2bin($key);
    }
    
    /**
     * Exporter les données d'un utilisateur (RGPD Art. 15)
     */
    public static function export_user_data($identifier) {
        global $wpdb;
        
        $data = array(
            'export_date' => current_time('mysql'),
            'identifier' => $identifier,
            'visits' => array(),
            'events' => array(),
            'consents' => array(),
        );
        
        // Chercher par visitor_id ou email
        if (strpos($identifier, '@') !== false) {
            // C'est un email - chercher dans les opt-ins
            $optin = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}almetal_analytics_optins WHERE email_encrypted LIKE %s",
                '%' . self::encrypt($identifier) . '%'
            ), ARRAY_A);
            
            if ($optin && !empty($optin['visitor_id'])) {
                $identifier = $optin['visitor_id'];
            }
        }
        
        // Visites
        $data['visits'] = $wpdb->get_results($wpdb->prepare(
            "SELECT page_url, page_title, referrer, device_type, browser, os, created_at 
             FROM {$wpdb->prefix}almetal_analytics_visits 
             WHERE visitor_id = %s 
             ORDER BY created_at DESC 
             LIMIT 1000",
            $identifier
        ), ARRAY_A);
        
        // Événements
        $data['events'] = $wpdb->get_results($wpdb->prepare(
            "SELECT event_type, event_category, event_action, event_label, created_at 
             FROM {$wpdb->prefix}almetal_analytics_events 
             WHERE visitor_id = %s 
             ORDER BY created_at DESC 
             LIMIT 1000",
            $identifier
        ), ARRAY_A);
        
        // Consentements
        $data['consents'] = $wpdb->get_results($wpdb->prepare(
            "SELECT consent_id, consent_categories, consent_given, consent_version, created_at, updated_at 
             FROM {$wpdb->prefix}almetal_analytics_consents 
             WHERE visitor_id = %s 
             ORDER BY created_at DESC",
            $identifier
        ), ARRAY_A);
        
        return $data;
    }
    
    /**
     * Supprimer les données d'un utilisateur (RGPD Art. 17 - Droit à l'oubli)
     */
    public static function delete_user_data($identifier) {
        global $wpdb;
        
        $deleted = array(
            'visits' => 0,
            'events' => 0,
            'consents' => 0,
            'optins' => 0,
            'heatmap' => 0,
            'sessions' => 0,
        );
        
        // Supprimer les visites
        $deleted['visits'] = $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->prefix}almetal_analytics_visits WHERE visitor_id = %s",
            $identifier
        ));
        
        // Supprimer les événements
        $deleted['events'] = $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->prefix}almetal_analytics_events WHERE visitor_id = %s",
            $identifier
        ));
        
        // Supprimer les consentements
        $deleted['consents'] = $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->prefix}almetal_analytics_consents WHERE visitor_id = %s",
            $identifier
        ));
        
        // Supprimer les opt-ins
        $deleted['optins'] = $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->prefix}almetal_analytics_optins WHERE visitor_id = %s",
            $identifier
        ));
        
        // Supprimer les sessions
        $deleted['sessions'] = $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->prefix}almetal_analytics_sessions WHERE visitor_id = %s",
            $identifier
        ));
        
        return $deleted;
    }
    
    /**
     * Générer un rapport de conformité RGPD
     */
    public static function get_compliance_report() {
        global $wpdb;
        
        $report = array(
            'generated_at' => current_time('mysql'),
            'data_retention_months' => get_option('almetal_analytics_retention_months', 13),
            'ip_anonymization' => get_option('almetal_analytics_anonymize_ip', true),
            'encryption_enabled' => true,
            'consent_stats' => Almetal_Analytics_Consent::get_consent_stats('30days'),
            'data_counts' => array(),
            'oldest_data' => array(),
        );
        
        // Compter les données
        $tables = array(
            'visits' => 'almetal_analytics_visits',
            'events' => 'almetal_analytics_events',
            'consents' => 'almetal_analytics_consents',
            'optins' => 'almetal_analytics_optins',
            'heatmap' => 'almetal_analytics_heatmap',
            'sessions' => 'almetal_analytics_sessions',
        );
        
        foreach ($tables as $key => $table) {
            $report['data_counts'][$key] = $wpdb->get_var(
                "SELECT COUNT(*) FROM {$wpdb->prefix}{$table}"
            );
            
            $oldest = $wpdb->get_var(
                "SELECT MIN(created_at) FROM {$wpdb->prefix}{$table}"
            );
            $report['oldest_data'][$key] = $oldest;
        }
        
        return $report;
    }
}
