<?php
/**
 * Plugin Name: AL Métallerie Analytics
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
        require_once ALMETAL_ANALYTICS_PATH . 'includes/class-seo-improver.php';
        
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
     * Initialisation
     */
    public function init() {
        // Charger les traductions
        load_plugin_textdomain('almetal-analytics', false, dirname(ALMETAL_ANALYTICS_BASENAME) . '/languages');
        
        // Initialiser les composants
        Almetal_Analytics_Tracker::get_instance();
        Almetal_Analytics_Consent::get_instance();
        Almetal_Analytics_REST_API::get_instance();
        
        if (is_admin()) {
            Almetal_Analytics_Admin::get_instance();
        }
    }
    
    /**
     * Scripts frontend
     */
    public function enqueue_frontend_scripts() {
        // Cookie Banner
        wp_enqueue_script(
            'almetal-cookie-banner',
            ALMETAL_ANALYTICS_URL . 'assets/js/cookie-banner.js',
            array(),
            ALMETAL_ANALYTICS_VERSION,
            true
        );
        
        // Tracker (chargé après consentement)
        wp_enqueue_script(
            'almetal-tracker',
            ALMETAL_ANALYTICS_URL . 'assets/js/tracker.js',
            array('almetal-cookie-banner'),
            ALMETAL_ANALYTICS_VERSION,
            true
        );
        
        // Heatmap (optionnel)
        if (get_option('almetal_analytics_heatmap_enabled', false)) {
            wp_enqueue_script(
                'almetal-heatmap',
                ALMETAL_ANALYTICS_URL . 'assets/js/heatmap.js',
                array('almetal-tracker'),
                ALMETAL_ANALYTICS_VERSION,
                true
            );
        }
        
        // CSS
        wp_enqueue_style(
            'almetal-cookie-banner',
            ALMETAL_ANALYTICS_URL . 'assets/css/cookie-banner.css',
            array(),
            ALMETAL_ANALYTICS_VERSION
        );
        
        // Passer les données au JS
        wp_localize_script('almetal-tracker', 'almetalAnalytics', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'restUrl' => rest_url('almetal-analytics/v1/'),
            'nonce' => wp_create_nonce('almetal_analytics_nonce'),
            'siteId' => get_option('almetal_analytics_site_id', $this->generate_site_id()),
            'cookieExpiry' => 365,
            'dataRetention' => get_option('almetal_analytics_retention_months', 13),
            'heatmapEnabled' => get_option('almetal_analytics_heatmap_enabled', false),
            'privacyUrl' => get_privacy_policy_url(),
        ));
    }
    
    /**
     * Générer un ID de site unique
     */
    private function generate_site_id() {
        $site_id = get_option('almetal_analytics_site_id');
        if (!$site_id) {
            $site_id = 'site_' . wp_generate_password(16, false);
            update_option('almetal_analytics_site_id', $site_id);
        }
        return $site_id;
    }
    
    /**
     * Activation du plugin
     */
    public function activate() {
        // Créer les tables
        Almetal_Analytics_Database::create_tables();
        
        // Options par défaut
        $defaults = array(
            'almetal_analytics_enabled' => true,
            'almetal_analytics_retention_months' => 13,
            'almetal_analytics_anonymize_ip' => true,
            'almetal_analytics_heatmap_enabled' => false,
            'almetal_analytics_track_logged_users' => false,
            'almetal_analytics_exclude_roles' => array('administrator'),
        );
        
        foreach ($defaults as $key => $value) {
            if (get_option($key) === false) {
                update_option($key, $value);
            }
        }
        
        // Planifier le cron de nettoyage
        if (!wp_next_scheduled('almetal_analytics_cleanup')) {
            wp_schedule_event(time(), 'daily', 'almetal_analytics_cleanup');
        }
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Désactivation du plugin
     */
    public function deactivate() {
        // Supprimer le cron
        wp_clear_scheduled_hook('almetal_analytics_cleanup');
    }
    
    /**
     * Nettoyage des anciennes données (RGPD)
     */
    public function cleanup_old_data() {
        $retention_months = get_option('almetal_analytics_retention_months', 13);
        Almetal_Analytics_Database::delete_old_data($retention_months);
    }
    
    /**
     * Handler AJAX pour le tracking
     */
    public function ajax_track_event() {
        check_ajax_referer('almetal_analytics_nonce', 'nonce');
        
        $tracker = Almetal_Analytics_Tracker::get_instance();
        $result = $tracker->track_event($_POST);
        
        wp_send_json($result);
    }
}

// Initialiser le plugin
function almetal_analytics() {
    return Almetal_Analytics::get_instance();
}

// Lancer le plugin
add_action('plugins_loaded', 'almetal_analytics');
