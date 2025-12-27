<?php
/**
 * Plugin Name: Auto Content SEO Publisher
 * Plugin URI: https://al-metallerie.fr
 * Description: Génération automatique d'articles de blog optimisés SEO pour AL Métallerie & Soudure
 * Version: 1.0.0
 * Author: AL Métallerie
 * Author URI: https://al-metallerie.fr
 * License: GPL v2 or later
 * Text Domain: auto-content-seo-publisher
 * Domain Path: /languages
 */

// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

// Constantes du plugin
define('ACSP_VERSION', '1.0.0');
define('ACSP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ACSP_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Classe principale du plugin
 */
class ACSP_Main {
    
    /**
     * Instance unique
     */
    private static $instance = null;
    
    /**
     * Obtenir l'instance unique
     */
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructeur
     */
    private function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);
    }
    
    /**
     * Initialisation du plugin
     */
    public function init() {
        // Charger les fichiers de traduction
        load_plugin_textdomain('auto-content-seo-publisher', false, dirname(plugin_basename(__FILE__)) . '/languages');
        
        // Charger les classes
        $this->load_classes();
        
        // Initialiser les classes
        $this->init_classes();
        
        // Ajouter le menu admin
        add_action('admin_menu', [$this, 'add_admin_menu']);
        
        // Enregistrer les assets
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        
        // Initialiser le cron
        $this->init_cron();
    }
    
    /**
     * Charger les classes du plugin
     */
    private function load_classes() {
        require_once ACSP_PLUGIN_DIR . 'includes/class-activator.php';
        require_once ACSP_PLUGIN_DIR . 'includes/class-content-generator.php';
        require_once ACSP_PLUGIN_DIR . 'includes/class-seo-optimizer.php';
        require_once ACSP_PLUGIN_DIR . 'includes/class-image-manager.php';
        require_once ACSP_PLUGIN_DIR . 'includes/class-scheduler.php';
        require_once ACSP_PLUGIN_DIR . 'includes/class-knowledge-base.php';
        require_once ACSP_PLUGIN_DIR . 'includes/class-article-templates.php';
    }
    
    /**
     * Initialiser les classes
     */
    private function init_classes() {
        // Les classes seront initialisées au besoin
    }
    
    /**
     * Ajouter le menu admin
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Auto Content SEO', 'auto-content-seo-publisher'),
            __('Auto Content SEO', 'auto-content-seo-publisher'),
            'manage_options',
            'acsp-dashboard',
            [$this, 'render_dashboard'],
            'dashicons-edit-page',
            30
        );
        
        add_submenu_page(
            'acsp-dashboard',
            __('Tableau de bord', 'auto-content-seo-publisher'),
            __('Tableau de bord', 'auto-content-seo-publisher'),
            'manage_options',
            'acsp-dashboard',
            [$this, 'render_dashboard']
        );
        
        add_submenu_page(
            'acsp-dashboard',
            __('Réglages', 'auto-content-seo-publisher'),
            __('Réglages', 'auto-content-seo-publisher'),
            'manage_options',
            'acsp-settings',
            [$this, 'render_settings']
        );
        
        add_submenu_page(
            'acsp-dashboard',
            __('Historique', 'auto-content-seo-publisher'),
            __('Historique', 'auto-content-seo-publisher'),
            'manage_options',
            'acsp-history',
            [$this, 'render_history']
        );
    }
    
    /**
     * Rendre la page dashboard
     */
    public function render_dashboard() {
        include ACSP_PLUGIN_DIR . 'admin/views/dashboard.php';
    }
    
    /**
     * Rendre la page réglages
     */
    public function render_settings() {
        include ACSP_PLUGIN_DIR . 'admin/views/settings.php';
    }
    
    /**
     * Rendre la page historique
     */
    public function render_history() {
        include ACSP_PLUGIN_DIR . 'admin/views/history.php';
    }
    
    /**
     * Charger les assets admin
     */
    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'acsp-') === false) {
            return;
        }
        
        wp_enqueue_style(
            'acsp-admin-style',
            ACSP_PLUGIN_URL . 'admin/css/admin-style.css',
            [],
            ACSP_VERSION
        );
        
        wp_enqueue_script(
            'acsp-admin-script',
            ACSP_PLUGIN_URL . 'admin/js/admin-script.js',
            ['jquery'],
            ACSP_VERSION,
            true
        );
        
        wp_localize_script('acsp-admin-script', 'acspData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('acsp_ajax_nonce'),
            'i18n' => [
                'confirm' => __('Êtes-vous sûr ?', 'auto-content-seo-publisher'),
                'loading' => __('Chargement...', 'auto-content-seo-publisher'),
                'error' => __('Une erreur est survenue', 'auto-content-seo-publisher'),
                'success' => __('Opération réussie', 'auto-content-seo-publisher')
            ]
        ]);
    }
    
    /**
     * Initialiser le cron
     */
    private function init_cron() {
        // Planifier la génération hebdomadaire
        if (!wp_next_scheduled('acsp_generate_weekly_article')) {
            wp_schedule_event(
                strtotime('next Monday 08:00:00'),
                'weekly',
                'acsp_generate_weekly_article'
            );
        }
        
        // Hook pour le cron
        add_action('acsp_generate_weekly_article', [$this, 'generate_scheduled_article']);
    }
    
    /**
     * Générer un article planifié
     */
    public function generate_scheduled_article() {
        $generator = new ACSP_Content_Generator();
        $generator->generate_and_publish_article();
    }
    
    /**
     * Activation du plugin
     */
    public function activate() {
        $activator = new ACSP_Activator();
        $activator->activate();
    }
    
    /**
     * Désactivation du plugin
     */
    public function deactivate() {
        wp_clear_scheduled_hook('acsp_generate_weekly_article');
    }
}

// Initialiser le plugin
ACSP_Main::get_instance();
