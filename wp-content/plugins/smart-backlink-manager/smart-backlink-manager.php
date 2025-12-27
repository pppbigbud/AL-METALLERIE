<?php
/**
 * Plugin Name: Smart Backlink Manager
 * Description: Gestion avancée des backlinks avec suivi automatique et analyse SEO
 * Version: 1.0.0
 * Author: AL Métallerie
 * Text Domain: smart-backlink-manager
 * Domain Path: /languages
 */

// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

// Définition des constantes
define('SBM_VERSION', '1.0.0');
define('SBM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SBM_PLUGIN_URL', plugin_dir_url(__FILE__));

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'SBM_';
    $base_dir = SBM_PLUGIN_DIR . 'includes/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . 'class-' . strtolower(str_replace('_', '-', $relative_class)) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Initialisation du plugin
class SBM_Main {
    private static $instance = null;
    
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);
    }
    
    public function init() {
        // Charger les traductions
        load_plugin_textdomain('smart-backlink-manager', false, dirname(plugin_basename(__FILE__)) . '/languages');
        
        // Initialiser les classes
        $this->init_classes();
        
        // Enregistrer les hooks
        $this->define_admin_hooks();
        
        // Initialiser l'API REST
        $this->init_rest_api();
    }
    
    private function init_classes() {
        // Initialiser les classes avec leurs hooks
        $dashboard = new SBM_Dashboard();
        $dashboard->init();
        
        $internal_links = new SBM_Internal_Links();
        $internal_links->init();
        
        $backlinks = new SBM_Backlinks();
        $backlinks->init();
        
        $opportunities = new SBM_Opportunities();
        $opportunities->init();
        
        $settings = new SBM_Settings();
        $settings->init();
    }
    
    private function define_admin_hooks() {
        // Menu d'administration
        add_action('admin_menu', [$this, 'add_admin_menu']);
        
        // Scripts et styles
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
    }
    
    private function init_rest_api() {
        $rest_api = new SBM_REST_API();
        $rest_api->register_routes();
    }
    
    public function add_admin_menu() {
        // Dashboard principal
        add_menu_page(
            __('Smart Backlink Manager', 'smart-backlink-manager'),
            __('Backlinks', 'smart-backlink-manager'),
            'manage_options',
            'sbm-dashboard',
            [new SBM_Dashboard(), 'render_dashboard'],
            'dashicons-admin-links',
            25
        );
        
        // Sous-menus
        add_submenu_page(
            'sbm-dashboard',
            __('Liens Internes', 'smart-backlink-manager'),
            __('Liens Internes', 'smart-backlink-manager'),
            'manage_options',
            'sbm-internal-links',
            [new SBM_Internal_Links(), 'render_page']
        );
        
        add_submenu_page(
            'sbm-dashboard',
            __('Backlinks', 'smart-backlink-manager'),
            __('Backlinks', 'smart-backlink-manager'),
            'manage_options',
            'sbm-backlinks',
            [new SBM_Backlinks(), 'render_page']
        );
        
        add_submenu_page(
            'sbm-dashboard',
            __('Opportunités', 'smart-backlink-manager'),
            __('Opportunités', 'smart-backlink-manager'),
            'manage_options',
            'sbm-opportunities',
            [new SBM_Opportunities(), 'render_page']
        );
        
        add_submenu_page(
            'sbm-dashboard',
            __('Réglages', 'smart-backlink-manager'),
            __('Réglages', 'smart-backlink-manager'),
            'manage_options',
            'sbm-settings',
            [new SBM_Settings(), 'render_page']
        );
    }
    
    public function enqueue_admin_assets($hook) {
        // Vérifier si nous sommes sur une page du plugin
        // Les pages du plugin ont des hooks comme : smart-backlink-manager_page_sbm-dashboard, smart-backlink-manager_page_sbm-settings, etc.
        if (strpos($hook, 'smart-backlink-manager_page_') === false) {
            return;
        }
        
        wp_enqueue_style(
            'sbm-admin-style',
            SBM_PLUGIN_URL . 'admin/css/admin-style.css',
            [],
            SBM_VERSION
        );
        
        wp_enqueue_script(
            'sbm-admin-script',
            SBM_PLUGIN_URL . 'admin/js/admin-script.js',
            ['jquery'],
            SBM_VERSION,
            true
        );
        
        // Localiser le script
        wp_localize_script('sbm-admin-script', 'sbmData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('sbm_ajax_nonce'),
            'i18n' => [
                'confirmDelete' => __('Êtes-vous sûr de vouloir supprimer ?', 'smart-backlink-manager'),
                'loading' => __('Chargement...', 'smart-backlink-manager'),
                'error' => __('Une erreur est survenue', 'smart-backlink-manager'),
                'success' => __('Opération réussie', 'smart-backlink-manager')
            ]
        ]);
    }
    
    public function activate() {
        // Créer les tables nécessaires
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Table des backlinks
        $table_backlinks = $wpdb->prefix . 'sbm_backlinks';
        $sql_backlinks = "CREATE TABLE IF NOT EXISTS $table_backlinks (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            source_url varchar(500) NOT NULL,
            target_url varchar(500) NOT NULL,
            anchor_text varchar(255) DEFAULT NULL,
            status varchar(20) DEFAULT 'active',
            domain_authority int(11) DEFAULT NULL,
            page_authority int(11) DEFAULT NULL,
            spam_score decimal(5,2) DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY source_url (source_url(100)),
            KEY status (status)
        ) $charset_collate;";
        
        // Table des opportunités
        $table_opportunities = $wpdb->prefix . 'sbm_opportunities';
        $sql_opportunities = "CREATE TABLE IF NOT EXISTS $table_opportunities (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            site_name varchar(255) NOT NULL,
            site_url varchar(500) NOT NULL,
            contact_email varchar(255) DEFAULT NULL,
            description text DEFAULT NULL,
            priority varchar(20) DEFAULT 'medium',
            status varchar(20) DEFAULT 'new',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY site_url (site_url(100)),
            KEY status (status),
            KEY priority (priority)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_backlinks);
        dbDelta($sql_opportunities);
        
        // Planifier les tâches cron
        if (!wp_next_scheduled('sbm_check_backlinks')) {
            wp_schedule_event(time(), 'daily', 'sbm_check_backlinks');
        }
        
        if (!wp_next_scheduled('sbm_find_opportunities')) {
            wp_schedule_event(time(), 'weekly', 'sbm_find_opportunities');
        }
    }
    
    public function deactivate() {
        // Désactiver les tâches cron
        wp_clear_scheduled_hook('sbm_check_backlinks');
        wp_clear_scheduled_hook('sbm_find_opportunities');
    }
}

// Initialiser le plugin
SBM_Main::get_instance();
