<?php
/**
 * Tracker - Collecte des données de visite
 * 
 * @package Almetal_Analytics
 */

if (!defined('ABSPATH')) {
    exit;
}

class Almetal_Analytics_Tracker {
    
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
     * Enregistrer une visite
     */
    public function track_visit($data) {
        global $wpdb;
        
        // Vérifier le consentement
        if (!$this->has_analytics_consent()) {
            return array('success' => false, 'message' => 'No consent');
        }
        
        // Anonymiser l'IP
        $ip_anonymized = $this->anonymize_ip($this->get_client_ip());
        
        // Détecter le device
        $device_info = $this->detect_device();
        
        // Parser les UTM
        $utm = $this->parse_utm($data['url'] ?? '');
        
        // Générer/récupérer visitor_id
        $visitor_id = $data['visitor_id'] ?? $this->generate_visitor_id();
        $session_id = $data['session_id'] ?? $this->generate_session_id();
        
        // Vérifier si nouveau visiteur
        $is_new = $this->is_new_visitor($visitor_id);
        
        // Insérer la visite
        $result = $wpdb->insert(
            $wpdb->prefix . 'almetal_analytics_visits',
            array(
                'visitor_id' => $visitor_id,
                'session_id' => $session_id,
                'page_url' => sanitize_text_field($data['url'] ?? ''),
                'page_title' => sanitize_text_field($data['title'] ?? ''),
                'referrer' => sanitize_text_field($data['referrer'] ?? ''),
                'utm_source' => $utm['source'],
                'utm_medium' => $utm['medium'],
                'utm_campaign' => $utm['campaign'],
                'utm_term' => $utm['term'],
                'utm_content' => $utm['content'],
                'device_type' => $device_info['type'],
                'browser' => $device_info['browser'],
                'browser_version' => $device_info['browser_version'],
                'os' => $device_info['os'],
                'os_version' => $device_info['os_version'],
                'screen_width' => intval($data['screen_width'] ?? 0),
                'screen_height' => intval($data['screen_height'] ?? 0),
                'viewport_width' => intval($data['viewport_width'] ?? 0),
                'viewport_height' => intval($data['viewport_height'] ?? 0),
                'ip_anonymized' => $ip_anonymized,
                'is_new_visitor' => $is_new ? 1 : 0,
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%d', '%d', '%s', '%d')
        );
        
        if ($result) {
            $visit_id = $wpdb->insert_id;
            
            // Mettre à jour ou créer la session
            $this->update_session($session_id, $visitor_id, $data['url'] ?? '');
            
            return array(
                'success' => true,
                'visit_id' => $visit_id,
                'visitor_id' => $visitor_id,
                'session_id' => $session_id,
                'is_new' => $is_new
            );
        }
        
        return array('success' => false, 'message' => 'Database error');
    }
    
    /**
     * Enregistrer un événement
     */
    public function track_event($data) {
        global $wpdb;
        
        if (!$this->has_analytics_consent()) {
            return array('success' => false, 'message' => 'No consent');
        }
        
        $result = $wpdb->insert(
            $wpdb->prefix . 'almetal_analytics_events',
            array(
                'visit_id' => intval($data['visit_id'] ?? 0),
                'visitor_id' => sanitize_text_field($data['visitor_id'] ?? ''),
                'session_id' => sanitize_text_field($data['session_id'] ?? ''),
                'event_type' => sanitize_text_field($data['event_type'] ?? 'custom'),
                'event_category' => sanitize_text_field($data['category'] ?? ''),
                'event_action' => sanitize_text_field($data['action'] ?? ''),
                'event_label' => sanitize_text_field($data['label'] ?? ''),
                'event_value' => floatval($data['value'] ?? 0),
                'element_selector' => sanitize_text_field($data['selector'] ?? ''),
                'element_text' => sanitize_text_field(substr($data['text'] ?? '', 0, 255)),
                'position_x' => intval($data['x'] ?? 0),
                'position_y' => intval($data['y'] ?? 0),
            ),
            array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%f', '%s', '%s', '%d', '%d')
        );
        
        return array('success' => (bool) $result);
    }
    
    /**
     * Mettre à jour la durée et le scroll
     */
    public function update_visit($data) {
        global $wpdb;
        
        if (empty($data['visit_id'])) {
            return array('success' => false, 'message' => 'Missing visit_id');
        }
        
        $visit_id = intval($data['visit_id']);
        $duration = intval($data['duration'] ?? 0);
        $scroll_depth = intval($data['scroll_depth'] ?? 0);
        $is_bounce = intval($data['is_bounce'] ?? 1);
        
        // Mettre à jour la visite
        $result = $wpdb->update(
            $wpdb->prefix . 'almetal_analytics_visits',
            array(
                'duration' => $duration,
                'scroll_depth' => $scroll_depth,
                'is_bounce' => $is_bounce,
            ),
            array('id' => $visit_id),
            array('%d', '%d', '%d'),
            array('%d')
        );
        
        // Récupérer le session_id de cette visite pour mettre à jour la session
        $session_id = $wpdb->get_var($wpdb->prepare(
            "SELECT session_id FROM {$wpdb->prefix}almetal_analytics_visits WHERE id = %d",
            $visit_id
        ));
        
        if ($session_id) {
            // Mettre à jour la session (ended_at pour le temps réel)
            $wpdb->update(
                $wpdb->prefix . 'almetal_analytics_sessions',
                array(
                    'ended_at' => current_time('mysql'),
                    'duration' => $duration,
                    'is_bounce' => $is_bounce,
                ),
                array('session_id' => $session_id),
                array('%s', '%d', '%d'),
                array('%s')
            );
        }
        
        return array('success' => $result !== false, 'updated' => $result);
    }
    
    /**
     * Mettre à jour ou créer une session
     */
    private function update_session($session_id, $visitor_id, $page_url) {
        global $wpdb;
        
        $existing = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}almetal_analytics_sessions WHERE session_id = %s",
            $session_id
        ));
        
        if ($existing) {
            $wpdb->update(
                $wpdb->prefix . 'almetal_analytics_sessions',
                array(
                    'page_views' => $existing->page_views + 1,
                    'exit_page' => $page_url,
                    'is_bounce' => 0,
                    'ended_at' => current_time('mysql'),
                    'duration' => time() - strtotime($existing->started_at),
                ),
                array('session_id' => $session_id)
            );
        } else {
            $wpdb->insert(
                $wpdb->prefix . 'almetal_analytics_sessions',
                array(
                    'session_id' => $session_id,
                    'visitor_id' => $visitor_id,
                    'entry_page' => $page_url,
                    'exit_page' => $page_url,
                )
            );
        }
    }
    
    /**
     * Vérifier si nouveau visiteur
     */
    private function is_new_visitor($visitor_id) {
        global $wpdb;
        
        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}almetal_analytics_visits WHERE visitor_id = %s",
            $visitor_id
        ));
        
        return $count == 0;
    }
    
    /**
     * Générer un visitor ID
     */
    private function generate_visitor_id() {
        return 'v_' . bin2hex(random_bytes(16));
    }
    
    /**
     * Générer un session ID
     */
    private function generate_session_id() {
        return 's_' . bin2hex(random_bytes(16));
    }
    
    /**
     * Anonymiser l'IP (RGPD)
     */
    private function anonymize_ip($ip) {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            // IPv4: masquer le dernier octet
            return preg_replace('/\.\d+$/', '.0', $ip);
        } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            // IPv6: masquer les 80 derniers bits
            return substr($ip, 0, strrpos($ip, ':')) . ':0000:0000:0000:0000:0000';
        }
        return '';
    }
    
    /**
     * Obtenir l'IP du client
     */
    private function get_client_ip() {
        $ip_keys = array(
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'REMOTE_ADDR'
        );
        
        foreach ($ip_keys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                // Prendre la première IP si plusieurs
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
     * Détecter le device
     */
    private function detect_device() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $result = array(
            'type' => 'desktop',
            'browser' => 'Unknown',
            'browser_version' => '',
            'os' => 'Unknown',
            'os_version' => '',
        );
        
        // Détecter le type
        if (preg_match('/Mobile|Android|iPhone|iPad|iPod/i', $user_agent)) {
            $result['type'] = preg_match('/iPad|Tablet/i', $user_agent) ? 'tablet' : 'mobile';
        }
        
        // Détecter le navigateur
        if (preg_match('/Firefox\/(\d+)/i', $user_agent, $m)) {
            $result['browser'] = 'Firefox';
            $result['browser_version'] = $m[1];
        } elseif (preg_match('/Edg\/(\d+)/i', $user_agent, $m)) {
            $result['browser'] = 'Edge';
            $result['browser_version'] = $m[1];
        } elseif (preg_match('/Chrome\/(\d+)/i', $user_agent, $m)) {
            $result['browser'] = 'Chrome';
            $result['browser_version'] = $m[1];
        } elseif (preg_match('/Safari\/(\d+)/i', $user_agent, $m) && !preg_match('/Chrome/i', $user_agent)) {
            $result['browser'] = 'Safari';
            $result['browser_version'] = $m[1];
        }
        
        // Détecter l'OS
        if (preg_match('/Windows NT (\d+\.\d+)/i', $user_agent, $m)) {
            $result['os'] = 'Windows';
            $result['os_version'] = $m[1];
        } elseif (preg_match('/Mac OS X (\d+[._]\d+)/i', $user_agent, $m)) {
            $result['os'] = 'macOS';
            $result['os_version'] = str_replace('_', '.', $m[1]);
        } elseif (preg_match('/Android (\d+)/i', $user_agent, $m)) {
            $result['os'] = 'Android';
            $result['os_version'] = $m[1];
        } elseif (preg_match('/iPhone OS (\d+)/i', $user_agent, $m)) {
            $result['os'] = 'iOS';
            $result['os_version'] = $m[1];
        } elseif (preg_match('/Linux/i', $user_agent)) {
            $result['os'] = 'Linux';
        }
        
        return $result;
    }
    
    /**
     * Parser les paramètres UTM
     */
    private function parse_utm($url) {
        $utm = array(
            'source' => '',
            'medium' => '',
            'campaign' => '',
            'term' => '',
            'content' => '',
        );
        
        $parsed = parse_url($url);
        if (isset($parsed['query'])) {
            parse_str($parsed['query'], $params);
            $utm['source'] = sanitize_text_field($params['utm_source'] ?? '');
            $utm['medium'] = sanitize_text_field($params['utm_medium'] ?? '');
            $utm['campaign'] = sanitize_text_field($params['utm_campaign'] ?? '');
            $utm['term'] = sanitize_text_field($params['utm_term'] ?? '');
            $utm['content'] = sanitize_text_field($params['utm_content'] ?? '');
        }
        
        return $utm;
    }
    
    /**
     * Vérifier le consentement analytics
     */
    private function has_analytics_consent() {
        // Vérifier le cookie de consentement
        if (isset($_COOKIE['almetal_consent'])) {
            $consent = json_decode(stripslashes($_COOKIE['almetal_consent']), true);
            return isset($consent['categories']['analytics']) && $consent['categories']['analytics'] === true;
        }
        return false;
    }
}
