<?php
/**
 * Gestion de la base de données Analytics
 * 
 * @package Almetal_Analytics
 */

if (!defined('ABSPATH')) {
    exit;
}

class Almetal_Analytics_Database {
    
    /**
     * Créer les tables nécessaires
     */
    public static function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Table des visites
        $table_visits = $wpdb->prefix . 'almetal_analytics_visits';
        $sql_visits = "CREATE TABLE IF NOT EXISTS $table_visits (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            visitor_id VARCHAR(64) NOT NULL,
            session_id VARCHAR(64) NOT NULL,
            page_url VARCHAR(2048) NOT NULL,
            page_title VARCHAR(512) DEFAULT '',
            referrer VARCHAR(2048) DEFAULT '',
            utm_source VARCHAR(255) DEFAULT '',
            utm_medium VARCHAR(255) DEFAULT '',
            utm_campaign VARCHAR(255) DEFAULT '',
            utm_term VARCHAR(255) DEFAULT '',
            utm_content VARCHAR(255) DEFAULT '',
            device_type ENUM('desktop', 'mobile', 'tablet') DEFAULT 'desktop',
            browser VARCHAR(100) DEFAULT '',
            browser_version VARCHAR(50) DEFAULT '',
            os VARCHAR(100) DEFAULT '',
            os_version VARCHAR(50) DEFAULT '',
            screen_width INT DEFAULT 0,
            screen_height INT DEFAULT 0,
            viewport_width INT DEFAULT 0,
            viewport_height INT DEFAULT 0,
            country VARCHAR(2) DEFAULT '',
            city VARCHAR(100) DEFAULT '',
            ip_anonymized VARCHAR(45) DEFAULT '',
            is_new_visitor TINYINT(1) DEFAULT 1,
            is_bounce TINYINT(1) DEFAULT 1,
            duration INT DEFAULT 0,
            scroll_depth INT DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY visitor_id (visitor_id),
            KEY session_id (session_id),
            KEY created_at (created_at),
            KEY page_url (page_url(191))
        ) $charset_collate;";
        
        // Table des événements
        $table_events = $wpdb->prefix . 'almetal_analytics_events';
        $sql_events = "CREATE TABLE IF NOT EXISTS $table_events (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            visit_id BIGINT(20) UNSIGNED NOT NULL,
            visitor_id VARCHAR(64) NOT NULL,
            session_id VARCHAR(64) NOT NULL,
            event_type VARCHAR(50) NOT NULL,
            event_category VARCHAR(100) DEFAULT '',
            event_action VARCHAR(100) DEFAULT '',
            event_label VARCHAR(255) DEFAULT '',
            event_value DECIMAL(10,2) DEFAULT 0,
            element_selector VARCHAR(255) DEFAULT '',
            element_text VARCHAR(255) DEFAULT '',
            position_x INT DEFAULT 0,
            position_y INT DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY visit_id (visit_id),
            KEY visitor_id (visitor_id),
            KEY event_type (event_type),
            KEY created_at (created_at)
        ) $charset_collate;";
        
        // Table des consentements (preuve RGPD)
        $table_consents = $wpdb->prefix . 'almetal_analytics_consents';
        $sql_consents = "CREATE TABLE IF NOT EXISTS $table_consents (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            consent_id VARCHAR(64) NOT NULL UNIQUE,
            visitor_id VARCHAR(64) NOT NULL,
            ip_anonymized VARCHAR(45) DEFAULT '',
            user_agent VARCHAR(512) DEFAULT '',
            consent_categories TEXT NOT NULL,
            consent_given TINYINT(1) DEFAULT 0,
            consent_version VARCHAR(10) DEFAULT '1.0',
            page_url VARCHAR(2048) DEFAULT '',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY consent_id (consent_id),
            KEY visitor_id (visitor_id),
            KEY created_at (created_at)
        ) $charset_collate;";
        
        // Table des opt-ins (formulaires)
        $table_optins = $wpdb->prefix . 'almetal_analytics_optins';
        $sql_optins = "CREATE TABLE IF NOT EXISTS $table_optins (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            email_encrypted VARBINARY(512) DEFAULT NULL,
            phone_encrypted VARBINARY(256) DEFAULT NULL,
            name_encrypted VARBINARY(256) DEFAULT NULL,
            source VARCHAR(100) DEFAULT '',
            form_id VARCHAR(100) DEFAULT '',
            consent_marketing TINYINT(1) DEFAULT 0,
            consent_newsletter TINYINT(1) DEFAULT 0,
            double_optin_token VARCHAR(64) DEFAULT NULL,
            double_optin_confirmed TINYINT(1) DEFAULT 0,
            double_optin_confirmed_at DATETIME DEFAULT NULL,
            ip_anonymized VARCHAR(45) DEFAULT '',
            visitor_id VARCHAR(64) DEFAULT '',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY double_optin_token (double_optin_token),
            KEY created_at (created_at)
        ) $charset_collate;";
        
        // Table des heatmaps
        $table_heatmap = $wpdb->prefix . 'almetal_analytics_heatmap';
        $sql_heatmap = "CREATE TABLE IF NOT EXISTS $table_heatmap (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            page_url VARCHAR(2048) NOT NULL,
            page_hash VARCHAR(64) NOT NULL,
            click_x INT NOT NULL,
            click_y INT NOT NULL,
            viewport_width INT NOT NULL,
            viewport_height INT NOT NULL,
            element_selector VARCHAR(255) DEFAULT '',
            device_type ENUM('desktop', 'mobile', 'tablet') DEFAULT 'desktop',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY page_hash (page_hash),
            KEY device_type (device_type),
            KEY created_at (created_at)
        ) $charset_collate;";
        
        // Table des sessions
        $table_sessions = $wpdb->prefix . 'almetal_analytics_sessions';
        $sql_sessions = "CREATE TABLE IF NOT EXISTS $table_sessions (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            session_id VARCHAR(64) NOT NULL UNIQUE,
            visitor_id VARCHAR(64) NOT NULL,
            started_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            ended_at DATETIME DEFAULT NULL,
            duration INT DEFAULT 0,
            page_views INT DEFAULT 1,
            is_bounce TINYINT(1) DEFAULT 1,
            entry_page VARCHAR(2048) DEFAULT '',
            exit_page VARCHAR(2048) DEFAULT '',
            PRIMARY KEY (id),
            KEY session_id (session_id),
            KEY visitor_id (visitor_id),
            KEY started_at (started_at)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        dbDelta($sql_visits);
        dbDelta($sql_events);
        dbDelta($sql_consents);
        dbDelta($sql_optins);
        dbDelta($sql_heatmap);
        dbDelta($sql_sessions);
        
        // Sauvegarder la version de la DB
        update_option('almetal_analytics_db_version', ALMETAL_ANALYTICS_VERSION);
    }
    
    /**
     * Supprimer les données anciennes (RGPD - 13 mois)
     */
    public static function delete_old_data($months = 13) {
        global $wpdb;
        
        $date_limit = date('Y-m-d H:i:s', strtotime("-{$months} months"));
        
        // Supprimer les visites
        $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->prefix}almetal_analytics_visits WHERE created_at < %s",
            $date_limit
        ));
        
        // Supprimer les événements
        $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->prefix}almetal_analytics_events WHERE created_at < %s",
            $date_limit
        ));
        
        // Supprimer les heatmaps
        $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->prefix}almetal_analytics_heatmap WHERE created_at < %s",
            $date_limit
        ));
        
        // Supprimer les sessions
        $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->prefix}almetal_analytics_sessions WHERE started_at < %s",
            $date_limit
        ));
        
        // Log de la suppression
        error_log("Almetal Analytics: Données antérieures au {$date_limit} supprimées (RGPD)");
        
        return true;
    }
    
    /**
     * Obtenir les statistiques globales
     */
    public static function get_stats($period = '30days') {
        global $wpdb;
        
        $date_from = self::get_date_from_period($period);
        
        $stats = array();
        
        // Total visites
        $stats['total_visits'] = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}almetal_analytics_visits WHERE created_at >= %s",
            $date_from
        ));
        
        // Visiteurs uniques
        $stats['unique_visitors'] = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(DISTINCT visitor_id) FROM {$wpdb->prefix}almetal_analytics_visits WHERE created_at >= %s",
            $date_from
        ));
        
        // Pages vues
        $stats['page_views'] = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}almetal_analytics_visits WHERE created_at >= %s",
            $date_from
        ));
        
        // Taux de rebond
        $total_sessions = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}almetal_analytics_sessions WHERE started_at >= %s",
            $date_from
        ));
        $bounce_sessions = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}almetal_analytics_sessions WHERE started_at >= %s AND is_bounce = 1",
            $date_from
        ));
        $stats['bounce_rate'] = $total_sessions > 0 ? round(($bounce_sessions / $total_sessions) * 100, 1) : 0;
        
        // Durée moyenne session
        $stats['avg_duration'] = $wpdb->get_var($wpdb->prepare(
            "SELECT AVG(duration) FROM {$wpdb->prefix}almetal_analytics_sessions WHERE started_at >= %s AND duration > 0",
            $date_from
        )) ?: 0;
        
        // Nouveaux vs retours
        $stats['new_visitors'] = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(DISTINCT visitor_id) FROM {$wpdb->prefix}almetal_analytics_visits WHERE created_at >= %s AND is_new_visitor = 1",
            $date_from
        ));
        $stats['returning_visitors'] = $stats['unique_visitors'] - $stats['new_visitors'];
        
        return $stats;
    }
    
    /**
     * Obtenir les visites par jour
     */
    public static function get_visits_by_day($period = '30days') {
        global $wpdb;
        
        $date_from = self::get_date_from_period($period);
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT DATE(created_at) as date, 
                    COUNT(*) as visits,
                    COUNT(DISTINCT visitor_id) as unique_visitors
             FROM {$wpdb->prefix}almetal_analytics_visits 
             WHERE created_at >= %s
             GROUP BY DATE(created_at)
             ORDER BY date ASC",
            $date_from
        ), ARRAY_A);
    }
    
    /**
     * Obtenir les top pages
     */
    public static function get_top_pages($period = '30days', $limit = 10) {
        global $wpdb;
        
        $date_from = self::get_date_from_period($period);
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT page_url, page_title,
                    COUNT(*) as views,
                    COUNT(DISTINCT visitor_id) as unique_visitors,
                    AVG(duration) as avg_duration,
                    AVG(scroll_depth) as avg_scroll
             FROM {$wpdb->prefix}almetal_analytics_visits 
             WHERE created_at >= %s
             GROUP BY page_url, page_title
             ORDER BY views DESC
             LIMIT %d",
            $date_from, $limit
        ), ARRAY_A);
    }
    
    /**
     * Obtenir les sources de trafic
     */
    public static function get_traffic_sources($period = '30days') {
        global $wpdb;
        
        $date_from = self::get_date_from_period($period);
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT 
                CASE 
                    WHEN utm_source != '' THEN utm_source
                    WHEN referrer = '' THEN 'Direct'
                    WHEN referrer LIKE '%google%' THEN 'Google'
                    WHEN referrer LIKE '%facebook%' OR referrer LIKE '%fb.%' THEN 'Facebook'
                    WHEN referrer LIKE '%instagram%' THEN 'Instagram'
                    WHEN referrer LIKE '%linkedin%' THEN 'LinkedIn'
                    WHEN referrer LIKE '%twitter%' OR referrer LIKE '%t.co%' THEN 'Twitter'
                    ELSE 'Referral'
                END as source,
                COUNT(*) as visits,
                COUNT(DISTINCT visitor_id) as unique_visitors
             FROM {$wpdb->prefix}almetal_analytics_visits 
             WHERE created_at >= %s
             GROUP BY source
             ORDER BY visits DESC",
            $date_from
        ), ARRAY_A);
    }
    
    /**
     * Obtenir les devices
     */
    public static function get_devices($period = '30days') {
        global $wpdb;
        
        $date_from = self::get_date_from_period($period);
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT device_type, COUNT(*) as count
             FROM {$wpdb->prefix}almetal_analytics_visits 
             WHERE created_at >= %s
             GROUP BY device_type
             ORDER BY count DESC",
            $date_from
        ), ARRAY_A);
    }
    
    /**
     * Obtenir les navigateurs
     */
    public static function get_browsers($period = '30days') {
        global $wpdb;
        
        $date_from = self::get_date_from_period($period);
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT browser, COUNT(*) as count
             FROM {$wpdb->prefix}almetal_analytics_visits 
             WHERE created_at >= %s
             GROUP BY browser
             ORDER BY count DESC
             LIMIT 10",
            $date_from
        ), ARRAY_A);
    }
    
    /**
     * Obtenir les pays
     */
    public static function get_countries($period = '30days') {
        global $wpdb;
        
        $date_from = self::get_date_from_period($period);
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT country, COUNT(*) as count
             FROM {$wpdb->prefix}almetal_analytics_visits 
             WHERE created_at >= %s AND country != ''
             GROUP BY country
             ORDER BY count DESC
             LIMIT 10",
            $date_from
        ), ARRAY_A);
    }
    
    /**
     * Convertir la période en date
     */
    private static function get_date_from_period($period) {
        switch ($period) {
            case 'today':
                return date('Y-m-d 00:00:00');
            case '7days':
                return date('Y-m-d H:i:s', strtotime('-7 days'));
            case '30days':
                return date('Y-m-d H:i:s', strtotime('-30 days'));
            case '90days':
                return date('Y-m-d H:i:s', strtotime('-90 days'));
            case '12months':
                return date('Y-m-d H:i:s', strtotime('-12 months'));
            default:
                return date('Y-m-d H:i:s', strtotime('-30 days'));
        }
    }
}
