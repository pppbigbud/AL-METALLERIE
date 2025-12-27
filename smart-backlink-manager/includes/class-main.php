<?php
/**
 * Main class for Smart Backlink Manager
 */

if (!defined('ABSPATH')) {
    exit;
}

class SBM_Main {
    
    public function __construct() {
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        
        // Initialiser toutes les classes
        $this->init_classes();
    }
    
    private function init_classes(): void {
        // Initialiser les classes avec leurs hooks AJAX
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
    
    private function load_dependencies(): void {
        require_once SBM_PLUGIN_DIR . 'includes/class-link-suggester.php';
        require_once SBM_PLUGIN_DIR . 'includes/class-rest-api.php';
        require_once SBM_PLUGIN_DIR . 'includes/class-dashboard.php';
        require_once SBM_PLUGIN_DIR . 'includes/class-internal-links.php';
        require_once SBM_PLUGIN_DIR . 'includes/class-backlinks.php';
        require_once SBM_PLUGIN_DIR . 'includes/class-opportunities.php';
        require_once SBM_PLUGIN_DIR . 'includes/class-settings.php';
    }
    
    private function set_locale(): void {
        add_action('plugins_loaded', function() {
            load_plugin_textdomain(
                'smart-backlink-manager',
                false,
                dirname(SBM_PLUGIN_BASENAME) . '/languages/'
            );
        });
    }
    
    private function define_admin_hooks(): void {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_gutenberg_assets']);
        
        add_action('rest_api_init', function() {
            $api = new SBM_REST_API();
            $api->register_routes();
        });
        
        add_action('admin_init', function() {
            if (get_transient('sbm_activation_redirect')) {
                delete_transient('sbm_activation_redirect');
                wp_safe_redirect(admin_url('admin.php?page=sbm-dashboard'));
                exit;
            }
        });
        
        add_action('sbm_check_backlinks_cron', function() {
            $backlinks = new SBM_Backlinks();
            $backlinks->check_all_backlinks();
        });
    }
    
    private function define_public_hooks(): void {
        // Rien côté front pour le moment
    }
    
    public function add_admin_menu(): void {
        add_menu_page(
            __('Backlink Manager', 'smart-backlink-manager'),
            __('Backlink Manager', 'smart-backlink-manager'),
            'manage_options',
            'sbm-dashboard',
            [new SBM_Dashboard(), 'render_dashboard'],
            'dashicons-networking',
            30
        );
        
        add_submenu_page(
            'sbm-dashboard',
            __('Dashboard', 'smart-backlink-manager'),
            __('Dashboard', 'smart-backlink-manager'),
            'manage_options',
            'sbm-dashboard',
            [new SBM_Dashboard(), 'render_dashboard']
        );
        
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
    
    public function enqueue_admin_assets(string $hook): void {
        // Vérifier si nous sommes sur une page du plugin
        if (strpos($hook, 'sbm-') === false && strpos($hook, 'smart-backlink-manager') === false) {
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
        
        wp_enqueue_script(
            'chartjs',
            'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js',
            [],
            '4.4.0',
            true
        );
        
        wp_localize_script('sbm-admin-script', 'sbmData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('sbm_ajax_nonce'),
            'siteUrl' => get_option('sbm_site_url', home_url())
        ]);
    }
    
    public function enqueue_gutenberg_assets(): void {
        wp_enqueue_script(
            'sbm-gutenberg-panel',
            SBM_PLUGIN_URL . 'admin/js/gutenberg-panel.js',
            [
                'wp-plugins',
                'wp-edit-post',
                'wp-element',
                'wp-components',
                'wp-data',
                'wp-api-fetch',
                'wp-notices'
            ],
            SBM_VERSION,
            true
        );
        
        wp_enqueue_style(
            'sbm-gutenberg-style',
            SBM_PLUGIN_URL . 'admin/css/gutenberg-panel.css',
            [],
            SBM_VERSION
        );
    }
    
    public function run(): void {
        // Le plugin est chargé via les hooks
    }
}
