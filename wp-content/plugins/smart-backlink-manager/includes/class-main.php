<?php
/**
 * Classe principale du plugin Smart Backlink Manager
 */

class SBM_Main {
    
    public function __construct() {
        $this->init();
    }
    
    private function init(): void {
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
    
    /**
     * Enregistrer les hooks d'administration
     */
    public function define_admin_hooks(): void {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
    }
    
    /**
     * Ajouter le menu d'administration
     */
    public function add_admin_menu(): void {
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
    
    /**
     * Charger les scripts et styles de l'admin
     */
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
        
        // Localiser le script avec les données nécessaires
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
}
