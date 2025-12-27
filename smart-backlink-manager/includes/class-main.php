<?php
/**
 * Main class for Smart Backlink Manager
 */

if (!defined('ABSPATH')) {
    exit;
}

class SBM_Main {
    
    private $link_suggester;
    private $rest_api;
    private $dashboard;
    private $internal_links;
    private $backlinks;
    private $opportunities;
    private $settings;
    
    public function __construct() {
        $this->load_dependencies();
        $this->init_classes();
    }
    
    private function load_dependencies(): void {
        require_once SBM_PLUGIN_DIR . 'includes/class-link-suggester.php';
        require_once SBM_PLUGIN_DIR . 'includes/class-rest-api.php';
        require_once SBM_PLUGIN_DIR . 'includes/class-dashboard.php';
        require_once SBM_PLUGIN_DIR . 'includes/class-internal-links.php';
        require_once SBM_PLUGIN_DIR . 'includes/class-backlinks.php';
        require_once SBM_PLUGIN_DIR . 'includes/class-opportunities.php';
        require_once SBM_PLUGIN_DIR . 'includes/class-settings.php';
    }
    
    private function init_classes(): void {
        $this->settings = new SBM_Settings();
        $this->link_suggester = new SBM_Link_Suggester();
        $this->rest_api = new SBM_REST_API();
        $this->dashboard = new SBM_Dashboard();
        $this->internal_links = new SBM_Internal_Links();
        $this->backlinks = new SBM_Backlinks();
        $this->opportunities = new SBM_Opportunities();
    }
    
    public function run(): void {
        // Initialiser les hooks WordPress
        add_action('init', [$this, 'init_plugin']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_scripts']);
        
        // Initialiser Gutenberg
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_gutenberg_assets']);
        
        // Menu admin
        add_action('admin_menu', [$this, 'add_admin_menu']);
        
        // Redirection après activation
        add_action('admin_init', [$this, 'activation_redirect']);
        
        // Cron jobs
        add_action('sbm_check_backlinks_cron', [$this->backlinks, 'check_all_backlinks']);
    }
    
    public function init_plugin(): void {
        // Charger les traductions
        load_plugin_textdomain(
            'smart-backlink-manager',
            false,
            dirname(SBM_PLUGIN_BASENAME) . '/languages'
        );
        
        // Initialiser les classes
        $this->settings->init();
        $this->link_suggester->init();
        $this->rest_api->init();
        $this->dashboard->init();
        $this->internal_links->init();
        $this->backlinks->init();
        $this->opportunities->init();
    }
    
    public function enqueue_admin_scripts($hook): void {
        if (strpos($hook, 'smart-backlink-manager') !== false) {
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
            
            wp_localize_script('sbm-admin-script', 'sbm_ajax', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('sbm_nonce')
            ]);
        }
    }
    
    public function enqueue_frontend_scripts(): void {
        // Scripts frontend si nécessaire
    }
    
    public function enqueue_gutenberg_assets(): void {
        if (get_option('sbm_gutenberg_panel_enabled', 1)) {
            wp_enqueue_style(
                'sbm-gutenberg-style',
                SBM_PLUGIN_URL . 'admin/css/gutenberg-panel.css',
                [],
                SBM_VERSION
            );
            
            wp_enqueue_script(
                'sbm-gutenberg-script',
                SBM_PLUGIN_URL . 'admin/js/gutenberg-panel.js',
                ['wp-blocks', 'wp-element', 'wp-data', 'wp-components'],
                SBM_VERSION,
                true
            );
            
            wp_localize_script('sbm-gutenberg-script', 'sbm_gutenberg', [
                'api_url' => rest_url('smart-backlink-manager/v1/'),
                'nonce' => wp_create_nonce('wp_rest')
            ]);
        }
    }
    
    public function add_admin_menu(): void {
        add_menu_page(
            __('Smart Backlink Manager', 'smart-backlink-manager'),
            __('Backlinks', 'smart-backlink-manager'),
            'manage_options',
            'smart-backlink-manager',
            [$this->dashboard, 'render_dashboard'],
            'dashicons-admin-links',
            25
        );
        
        add_submenu_page(
            'smart-backlink-manager',
            __('Dashboard', 'smart-backlink-manager'),
            __('Dashboard', 'smart-backlink-manager'),
            'manage_options',
            'smart-backlink-manager',
            [$this->dashboard, 'render_dashboard']
        );
        
        add_submenu_page(
            'smart-backlink-manager',
            __('Liens Internes', 'smart-backlink-manager'),
            __('Liens Internes', 'smart-backlink-manager'),
            'manage_options',
            'sbm-internal-links',
            [$this->internal_links, 'render_page']
        );
        
        add_submenu_page(
            'smart-backlink-manager',
            __('Backlinks', 'smart-backlink-manager'),
            __('Backlinks', 'smart-backlink-manager'),
            'manage_options',
            'sbm-backlinks',
            [$this->backlinks, 'render_page']
        );
        
        add_submenu_page(
            'smart-backlink-manager',
            __('Opportunités', 'smart-backlink-manager'),
            __('Opportunités', 'smart-backlink-manager'),
            'manage_options',
            'sbm-opportunities',
            [$this->opportunities, 'render_page']
        );
        
        add_submenu_page(
            'smart-backlink-manager',
            __('Réglages', 'smart-backlink-manager'),
            __('Réglages', 'smart-backlink-manager'),
            'manage_options',
            'sbm-settings',
            [$this->settings, 'render_page']
        );
    }
    
    public function activation_redirect(): void {
        if (get_transient('sbm_activation_redirect')) {
            delete_transient('sbm_activation_redirect');
            if (!is_network_admin() && !isset($_GET['activate-multi'])) {
                wp_safe_redirect(admin_url('admin.php?page=smart-backlink-manager'));
                exit;
            }
        }
    }
}
