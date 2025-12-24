<?php
/**
 * Plugin Name: AL Métallerie Analytics (Version Temporaire)
 * Plugin URI: https://al-metallerie.fr
 * Description: Solution d'analytics RGPD complète avec dashboard intégré, cookie banner, heatmaps et tracking anonymisé.
 * Version: 1.0.0
 * Author: AL Métallerie
 * Author URI: https://al-metallerie.fr
 * License: Proprietary
 * Text Domain: almetal-analytics
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 8.0
 */

// Sécurité : empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

// Définir les constantes du plugin
define('ALMETAL_ANALYTICS_VERSION', '1.0.1');
define('ALMETAL_ANALYTICS_PATH', plugin_dir_path(__FILE__));
define('ALMETAL_ANALYTICS_URL', plugin_dir_url(__FILE__));
define('ALMETAL_ANALYTICS_BASENAME', plugin_basename(__FILE__));

/**
 * Classe principale du plugin
 */
class Almetal_Analytics {
    
    /**
     * Instance unique (Singleton)
     */
    private static $instance = null;
    
    /**
     * Obtenir l'instance unique
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructeur
     */
    private function __construct() {
        $this->load_dependencies();
        $this->init_hooks();
    }
    
    /**
     * Charger les dépendances
     */
    private function load_dependencies() {
        // Classes principales
        require_once ALMETAL_ANALYTICS_PATH . 'includes/class-database.php';
        require_once ALMETAL_ANALYTICS_PATH . 'includes/class-tracker.php';
        require_once ALMETAL_ANALYTICS_PATH . 'includes/class-consent.php';
        require_once ALMETAL_ANALYTICS_PATH . 'includes/class-gdpr.php';
        require_once ALMETAL_ANALYTICS_PATH . 'includes/class-heatmap.php';
        require_once ALMETAL_ANALYTICS_PATH . 'includes/class-optin.php';
        require_once ALMETAL_ANALYTICS_PATH . 'includes/class-export.php';
        require_once ALMETAL_ANALYTICS_PATH . 'includes/class-seo.php';
        // class-seo-improver.php temporairement désactivé
        
        // Admin
        if (is_admin()) {
            require_once ALMETAL_ANALYTICS_PATH . 'admin/class-admin.php';
            require_once ALMETAL_ANALYTICS_PATH . 'admin/class-dashboard.php';
            require_once ALMETAL_ANALYTICS_PATH . 'admin/class-settings.php';
        }
        
        // API REST
        require_once ALMETAL_ANALYTICS_PATH . 'includes/class-rest-api.php';
    }
    
    /**
     * Initialiser les hooks
     */
    private function init_hooks() {
        // Activation/Désactivation
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        // Init
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
        
        // Cron pour suppression auto des données (RGPD 13 mois)
        add_action('almetal_analytics_cleanup', array($this, 'cleanup_old_data'));
        
        // AJAX handlers
        add_action('wp_ajax_almetal_track_event', array($this, 'ajax_track_event'));
        add_action('wp_ajax_nopriv_almetal_track_event', array($this, 'ajax_track_event'));
    }
    
    /**
     * Initialisation du plugin
     */
    public function init() {
        // Charger les textdomains
        load_plugin_textdomain('almetal-analytics', false, dirname(ALMETAL_ANALYTICS_BASENAME) . '/languages');
        
        // Initialiser les classes
        Almetal_Analytics_Database::get_instance();
        Almetal_Analytics_Tracker::get_instance();
        Almetal_Analytics_Consent::get_instance();
        Almetal_Analytics_GDPR::get_instance();
        Almetal_Analytics_Heatmap::get_instance();
        Almetal_Analytics_Optin::get_instance();
        Almetal_Analytics_SEO::get_instance();
        
        if (is_admin()) {
            Almetal_Analytics_Admin::get_instance();
            Almetal_Analytics_Dashboard::get_instance();
            Almetal_Analytics_Settings::get_instance();
        }
        
        Almetal_Analytics_REST_API::get_instance();
    }
    
    /**
     * Scripts frontend
     */
    public function enqueue_frontend_scripts() {
        // Tracker
        wp_enqueue_script(
            'almetal-analytics-tracker',
            ALMETAL_ANALYTICS_URL . 'assets/js/tracker.js',
            array(),
            ALMETAL_ANALYTICS_VERSION,
            true
        );
        
        // Cookie banner
        if (!isset($_COOKIE['almetal_consent'])) {
            wp_enqueue_style(
                'almetal-cookie-banner',
                ALMETAL_ANALYTICS_URL . 'assets/css/cookie-banner.css',
                array(),
                ALMETAL_ANALYTICS_VERSION
            );
            
            wp_enqueue_script(
                'almetal-cookie-banner',
                ALMETAL_ANALYTICS_URL . 'assets/js/cookie-banner.js',
                array(),
                ALMETAL_ANALYTICS_VERSION,
                true
            );
        }
        
        // Localiser le script tracker
        wp_localize_script('almetal-analytics-tracker', 'almetalAnalytics', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('almetal_analytics'),
            'trackingEnabled' => get_option('almetal_analytics_enabled', true),
            'anonymizeIp' => get_option('almetal_analytics_anonymize_ip', true),
            'trackLoggedIn' => get_option('almetal_analytics_track_logged_users', false),
            'excludeRoles' => get_option('almetal_analytics_exclude_roles', array()),
        ));
    }
    
    /**
     * Activation du plugin
     */
    public function activate() {
        // Créer les tables
        Almetal_Analytics_Database::create_tables();
        
        // Planifier le cron de nettoyage
        if (!wp_next_scheduled('almetal_analytics_cleanup')) {
            wp_schedule_event(time(), 'daily', 'almetal_analytics_cleanup');
        }
        
        // Options par défaut
        add_option('almetal_analytics_enabled', true);
        add_option('almetal_analytics_retention_months', 13);
        add_option('almetal_analytics_anonymize_ip', true);
        add_option('almetal_analytics_heatmap_enabled', false);
        add_option('almetal_analytics_track_logged_users', false);
        add_option('almetal_analytics_exclude_roles', array('administrator'));
    }
    
    /**
     * Désactivation du plugin
     */
    public function deactivate() {
        // Supprimer le cron
        wp_clear_scheduled_hook('almetal_analytics_cleanup');
        
        // Nettoyer les transients
        wp_cache_flush();
    }
    
    /**
     * Nettoyage des anciennes données (RGPD)
     */
    public function cleanup_old_data() {
        global $wpdb;
        
        $retention_months = get_option('almetal_analytics_retention_months', 13);
        $cutoff_date = date('Y-m-d H:i:s', strtotime("-{$retention_months} months"));
        
        // Supprimer les anciennes visites
        $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->prefix}almetal_analytics_visits WHERE created_at < %s",
            $cutoff_date
        ));
        
        // Supprimer les anciens événements
        $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->prefix}almetal_analytics_events WHERE created_at < %s",
            $cutoff_date
        ));
        
        // Nettoyer la table des consentements
        $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->prefix}almetal_analytics_consent WHERE created_at < %s",
            $cutoff_date
        ));
    }
    
    /**
     * Handler AJAX pour le tracking d'événements
     */
    public function ajax_track_event() {
        check_ajax_referer('almetal_analytics', 'nonce');
        
        $tracker = Almetal_Analytics_Tracker::get_instance();
        $tracker->track_event($_POST);
        
        wp_die();
    }
}

/**
 * Fonction d'initialisation du plugin
 */
function almetal_analytics() {
    return Almetal_Analytics::get_instance();
}

// Lancer le plugin
almetal_analytics();
