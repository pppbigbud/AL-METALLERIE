<?php
/**
 * Administration du plugin
 * 
 * @package Almetal_Analytics
 */

if (!defined('ABSPATH')) {
    exit;
}

class Almetal_Analytics_Admin {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('admin_init', array($this, 'register_settings'));
        
        // Handlers AJAX pour les améliorations SEO
        add_action('wp_ajax_almetal_get_seo_improvements', array($this, 'ajax_get_seo_improvements'));
        add_action('wp_ajax_almetal_apply_seo_improvements', array($this, 'ajax_apply_seo_improvements'));
    }
    
    /**
     * Ajouter le menu admin
     */
    public function add_admin_menu() {
        // Capacité pour accéder au menu Analytics (éditeurs et admins)
        $analytics_cap = 'edit_pages';
        // Capacité pour les réglages (admins uniquement)
        $settings_cap = 'manage_options';
        
        // Menu principal
        add_menu_page(
            __('Analytics', 'almetal-analytics'),
            __('Analytics', 'almetal-analytics'),
            $analytics_cap,
            'almetal-analytics',
            array($this, 'render_dashboard_page'),
            'dashicons-chart-area',
            30
        );
        
        // Sous-menus accessibles aux éditeurs
        add_submenu_page(
            'almetal-analytics',
            __('Dashboard', 'almetal-analytics'),
            __('Dashboard', 'almetal-analytics'),
            $analytics_cap,
            'almetal-analytics',
            array($this, 'render_dashboard_page')
        );
        
        add_submenu_page(
            'almetal-analytics',
            __('Temps réel', 'almetal-analytics'),
            __('Temps réel', 'almetal-analytics'),
            $analytics_cap,
            'almetal-analytics-realtime',
            array($this, 'render_realtime_page')
        );
        
        add_submenu_page(
            'almetal-analytics',
            __('Heatmaps', 'almetal-analytics'),
            __('Heatmaps', 'almetal-analytics'),
            $analytics_cap,
            'almetal-analytics-heatmaps',
            array($this, 'render_heatmaps_page')
        );
        
        add_submenu_page(
            'almetal-analytics',
            __('Opt-ins', 'almetal-analytics'),
            __('Opt-ins', 'almetal-analytics'),
            $analytics_cap,
            'almetal-analytics-optins',
            array($this, 'render_optins_page')
        );
        
        add_submenu_page(
            'almetal-analytics',
            __('RGPD', 'almetal-analytics'),
            __('RGPD', 'almetal-analytics'),
            $analytics_cap,
            'almetal-analytics-gdpr',
            array($this, 'render_gdpr_page')
        );
        
        add_submenu_page(
            'almetal-analytics',
            __('SEO', 'almetal-analytics'),
            __('SEO', 'almetal-analytics'),
            $analytics_cap,
            'almetal-analytics-seo',
            array($this, 'render_seo_page')
        );
        
        // Réglages - réservé aux administrateurs
        add_submenu_page(
            'almetal-analytics',
            __('Réglages', 'almetal-analytics'),
            __('Réglages', 'almetal-analytics'),
            $settings_cap,
            'almetal-analytics-settings',
            array($this, 'render_settings_page')
        );
    }
    
    /**
     * Enqueue des scripts admin
     */
    public function enqueue_admin_scripts($hook) {
        // Seulement sur nos pages
        if (strpos($hook, 'almetal-analytics') === false) {
            return;
        }
        
        // Chart.js
        wp_enqueue_script(
            'chartjs',
            'https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js',
            array(),
            '4.4.1',
            true
        );
        
        // Admin JS
        wp_enqueue_script(
            'almetal-analytics-admin',
            ALMETAL_ANALYTICS_URL . 'admin/js/admin.js',
            array('jquery', 'chartjs'),
            ALMETAL_ANALYTICS_VERSION,
            true
        );
        
        // Admin CSS
        wp_enqueue_style(
            'almetal-analytics-admin',
            ALMETAL_ANALYTICS_URL . 'admin/css/admin.css',
            array(),
            ALMETAL_ANALYTICS_VERSION
        );
        
        // SEO Improver assets (uniquement sur la page SEO)
        if ($hook === 'analytics_page_almetal-analytics-seo') {
            wp_enqueue_script(
                'almetal-seo-improver',
                ALMETAL_ANALYTICS_URL . 'admin/js/seo-improver.js',
                array('jquery'),
                ALMETAL_ANALYTICS_VERSION,
                true
            );
            
            wp_enqueue_style(
                'almetal-seo-improver',
                ALMETAL_ANALYTICS_URL . 'admin/css/seo-improver.css',
                array(),
                ALMETAL_ANALYTICS_VERSION
            );
            
            // Localiser le script pour AJAX
            wp_localize_script('almetal-seo-improver', 'almetalAnalytics', array(
                'nonce' => wp_create_nonce('almetal_seo_improvements'),
                'ajaxUrl' => admin_url('admin-ajax.php')
            ));
        }
        
        // Passer les données au JS
        wp_localize_script('almetal-analytics-admin', 'almetalAnalyticsAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'restUrl' => rest_url('almetal-analytics/v1/'),
            'nonce' => wp_create_nonce('wp_rest'),
            'strings' => array(
                'loading' => __('Chargement...', 'almetal-analytics'),
                'error' => __('Erreur', 'almetal-analytics'),
                'noData' => __('Aucune donnée', 'almetal-analytics'),
            ),
        ));
    }
    
    /**
     * Enregistrer les réglages
     */
    public function register_settings() {
        register_setting('almetal_analytics_settings', 'almetal_analytics_enabled');
        register_setting('almetal_analytics_settings', 'almetal_analytics_retention_months');
        register_setting('almetal_analytics_settings', 'almetal_analytics_anonymize_ip');
        register_setting('almetal_analytics_settings', 'almetal_analytics_heatmap_enabled');
        register_setting('almetal_analytics_settings', 'almetal_analytics_track_logged_users');
        register_setting('almetal_analytics_settings', 'almetal_analytics_exclude_roles');
        register_setting('almetal_analytics_settings', 'almetal_analytics_webhook_url');
        register_setting('almetal_analytics_settings', 'almetal_analytics_webhook_secret');
    }
    
    /**
     * Page Dashboard
     */
    public function render_dashboard_page() {
        include ALMETAL_ANALYTICS_PATH . 'admin/views/dashboard.php';
    }
    
    /**
     * Page Temps réel
     */
    public function render_realtime_page() {
        include ALMETAL_ANALYTICS_PATH . 'admin/views/realtime.php';
    }
    
    /**
     * Page Heatmaps
     */
    public function render_heatmaps_page() {
        include ALMETAL_ANALYTICS_PATH . 'admin/views/heatmaps.php';
    }
    
    /**
     * Page Opt-ins
     */
    public function render_optins_page() {
        include ALMETAL_ANALYTICS_PATH . 'admin/views/optins.php';
    }
    
    /**
     * Page RGPD
     */
    public function render_gdpr_page() {
        include ALMETAL_ANALYTICS_PATH . 'admin/views/gdpr.php';
    }
    
    /**
     * Page SEO
     */
    public function render_seo_page() {
        include ALMETAL_ANALYTICS_PATH . 'admin/views/seo.php';
    }
    
    /**
     * Handler AJAX - Obtenir les améliorations SEO
     */
    public function ajax_get_seo_improvements() {
        check_ajax_referer('almetal_seo_improvements', 'nonce');
        
        $post_id = intval($_POST['post_id']);
        $is_taxonomy = isset($_POST['is_taxonomy']) && $_POST['is_taxonomy'] === 'true';
        
        if ($is_taxonomy) {
            if (!current_user_can('edit_term', $post_id)) {
                wp_die(__('Permission denied', 'almetal-analytics'));
            }
        } else {
            if (!current_user_can('edit_post', $post_id)) {
                wp_die(__('Permission denied', 'almetal-analytics'));
            }
        }
        
        $seo_improver = Almetal_Analytics_SEO_Improver::get_instance();
        $improvements = $seo_improver->get_suggested_improvements($post_id, $is_taxonomy);
        
        wp_send_json_success($improvements);
    }
    
    /**
     * Handler AJAX - Appliquer les améliorations SEO
     */
    public function ajax_apply_seo_improvements() {
        check_ajax_referer('almetal_seo_improvements', 'nonce');
        
        $post_id = intval($_POST['post_id']);
        $is_taxonomy = isset($_POST['is_taxonomy']) && $_POST['is_taxonomy'] === 'true';
        $improvements = isset($_POST['improvements']) ? array_map('sanitize_text_field', $_POST['improvements']) : array();
        $custom_values = isset($_POST['custom_values']) ? array_map('sanitize_textarea_field', $_POST['custom_values']) : array();
        $create_draft = isset($_POST['create_draft']) ? boolval($_POST['create_draft']) : true;
        
        if ($is_taxonomy) {
            if (!current_user_can('edit_term', $post_id)) {
                wp_die(__('Permission denied', 'almetal-analytics'));
            }
        } else {
            if (!current_user_can('edit_post', $post_id)) {
                wp_die(__('Permission denied', 'almetal-analytics'));
            }
        }
        
        $seo_improver = Almetal_Analytics_SEO_Improver::get_instance();
        $result = $seo_improver->improve_page($post_id, $improvements, $create_draft, $custom_values, $is_taxonomy);
        
        if (isset($result['error'])) {
            wp_send_json_error($result['error']);
        }
        
        $message = $create_draft ? 
            __('Brouillon créé avec les améliorations SEO appliquées', 'almetal-analytics') : 
            __('Améliorations SEO appliquées directement', 'almetal-analytics');
        
        wp_send_json_success(array(
            'message' => $message,
            'edit_url' => isset($result['edit_url']) ? $result['edit_url'] : null,
            'post_url' => isset($result['post_url']) ? $result['post_url'] : null
        ));
    }
    
    /**
     * Page Réglages
     */
    public function render_settings_page() {
        include ALMETAL_ANALYTICS_PATH . 'admin/views/settings.php';
    }
}
