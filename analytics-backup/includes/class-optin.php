<?php
/**
 * Gestion des Opt-ins (collecte volontaire)
 * 
 * @package Almetal_Analytics
 */

if (!defined('ABSPATH')) {
    exit;
}

class Almetal_Analytics_Optin {
    
    /**
     * Enregistrer un opt-in
     */
    public static function create_optin($data) {
        global $wpdb;
        
        // Générer le token de double opt-in
        $token = bin2hex(random_bytes(32));
        
        // Chiffrer les données sensibles
        $email_encrypted = !empty($data['email']) ? Almetal_Analytics_GDPR::encrypt($data['email']) : null;
        $phone_encrypted = !empty($data['phone']) ? Almetal_Analytics_GDPR::encrypt($data['phone']) : null;
        $name_encrypted = !empty($data['name']) ? Almetal_Analytics_GDPR::encrypt($data['name']) : null;
        
        // Anonymiser l'IP
        $ip = self::get_client_ip();
        $ip_anonymized = self::anonymize_ip($ip);
        
        $result = $wpdb->insert(
            $wpdb->prefix . 'almetal_analytics_optins',
            array(
                'email_encrypted' => $email_encrypted,
                'phone_encrypted' => $phone_encrypted,
                'name_encrypted' => $name_encrypted,
                'source' => sanitize_text_field($data['source'] ?? ''),
                'form_id' => sanitize_text_field($data['form_id'] ?? ''),
                'consent_marketing' => !empty($data['consent_marketing']) ? 1 : 0,
                'consent_newsletter' => !empty($data['consent_newsletter']) ? 1 : 0,
                'double_optin_token' => $token,
                'ip_anonymized' => $ip_anonymized,
                'visitor_id' => sanitize_text_field($data['visitor_id'] ?? ''),
            ),
            array('%s', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s', '%s')
        );
        
        if ($result) {
            $optin_id = $wpdb->insert_id;
            
            // Envoyer l'email de confirmation si email fourni
            if (!empty($data['email'])) {
                self::send_double_optin_email($data['email'], $token);
            }
            
            return array(
                'success' => true,
                'optin_id' => $optin_id,
                'token' => $token,
            );
        }
        
        return array('success' => false, 'message' => 'Database error');
    }
    
    /**
     * Confirmer le double opt-in
     */
    public static function confirm_optin($token) {
        global $wpdb;
        
        $optin = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}almetal_analytics_optins WHERE double_optin_token = %s",
            $token
        ));
        
        if (!$optin) {
            return array('success' => false, 'message' => 'Token invalide');
        }
        
        if ($optin->double_optin_confirmed) {
            return array('success' => true, 'message' => 'Déjà confirmé');
        }
        
        $result = $wpdb->update(
            $wpdb->prefix . 'almetal_analytics_optins',
            array(
                'double_optin_confirmed' => 1,
                'double_optin_confirmed_at' => current_time('mysql'),
            ),
            array('double_optin_token' => $token),
            array('%d', '%s'),
            array('%s')
        );
        
        return array('success' => (bool) $result);
    }
    
    /**
     * Envoyer l'email de double opt-in
     */
    private static function send_double_optin_email($email, $token) {
        $site_name = get_bloginfo('name');
        $confirm_url = add_query_arg(array(
            'almetal_confirm_optin' => $token,
        ), home_url('/'));
        
        $subject = sprintf(__('Confirmez votre inscription - %s', 'almetal-analytics'), $site_name);
        
        $message = sprintf(
            __("Bonjour,\n\nMerci de votre intérêt pour %s.\n\nPour confirmer votre inscription, veuillez cliquer sur le lien ci-dessous :\n\n%s\n\nSi vous n'avez pas demandé cette inscription, vous pouvez ignorer cet email.\n\nCordialement,\nL'équipe %s", 'almetal-analytics'),
            $site_name,
            $confirm_url,
            $site_name
        );
        
        $headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            'From: ' . $site_name . ' <' . get_option('admin_email') . '>',
        );
        
        return wp_mail($email, $subject, $message, $headers);
    }
    
    /**
     * Obtenir les statistiques des opt-ins
     */
    public static function get_optin_stats($period = '30days') {
        global $wpdb;
        
        $date_from = date('Y-m-d H:i:s', strtotime('-30 days'));
        if ($period === '7days') {
            $date_from = date('Y-m-d H:i:s', strtotime('-7 days'));
        } elseif ($period === '90days') {
            $date_from = date('Y-m-d H:i:s', strtotime('-90 days'));
        }
        
        $total = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}almetal_analytics_optins WHERE created_at >= %s",
            $date_from
        ));
        
        $confirmed = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}almetal_analytics_optins WHERE created_at >= %s AND double_optin_confirmed = 1",
            $date_from
        ));
        
        $by_source = $wpdb->get_results($wpdb->prepare(
            "SELECT source, COUNT(*) as count 
             FROM {$wpdb->prefix}almetal_analytics_optins 
             WHERE created_at >= %s 
             GROUP BY source 
             ORDER BY count DESC",
            $date_from
        ), ARRAY_A);
        
        return array(
            'total' => (int) $total,
            'confirmed' => (int) $confirmed,
            'pending' => (int) ($total - $confirmed),
            'confirmation_rate' => $total > 0 ? round(($confirmed / $total) * 100, 1) : 0,
            'by_source' => $by_source,
        );
    }
    
    /**
     * Exporter les opt-ins (pour CRM)
     */
    public static function export_optins($format = 'csv', $confirmed_only = true) {
        global $wpdb;
        
        $where = $confirmed_only ? "WHERE double_optin_confirmed = 1" : "";
        
        $optins = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}almetal_analytics_optins {$where} ORDER BY created_at DESC",
            ARRAY_A
        );
        
        // Déchiffrer les données
        $data = array();
        foreach ($optins as $optin) {
            $data[] = array(
                'id' => $optin['id'],
                'email' => $optin['email_encrypted'] ? Almetal_Analytics_GDPR::decrypt($optin['email_encrypted']) : '',
                'phone' => $optin['phone_encrypted'] ? Almetal_Analytics_GDPR::decrypt($optin['phone_encrypted']) : '',
                'name' => $optin['name_encrypted'] ? Almetal_Analytics_GDPR::decrypt($optin['name_encrypted']) : '',
                'source' => $optin['source'],
                'form_id' => $optin['form_id'],
                'consent_marketing' => $optin['consent_marketing'],
                'consent_newsletter' => $optin['consent_newsletter'],
                'confirmed' => $optin['double_optin_confirmed'],
                'confirmed_at' => $optin['double_optin_confirmed_at'],
                'created_at' => $optin['created_at'],
            );
        }
        
        return $data;
    }
    
    /**
     * Anonymiser l'IP
     */
    private static function anonymize_ip($ip) {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return preg_replace('/\.\d+$/', '.0', $ip);
        }
        return '';
    }
    
    /**
     * Obtenir l'IP du client
     */
    private static function get_client_ip() {
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
}

// Hook pour confirmer le double opt-in via URL
add_action('init', function() {
    if (isset($_GET['almetal_confirm_optin'])) {
        $token = sanitize_text_field($_GET['almetal_confirm_optin']);
        $result = Almetal_Analytics_Optin::confirm_optin($token);
        
        if ($result['success']) {
            wp_redirect(add_query_arg('optin_confirmed', '1', home_url('/')));
        } else {
            wp_redirect(add_query_arg('optin_error', '1', home_url('/')));
        }
        exit;
    }
});
