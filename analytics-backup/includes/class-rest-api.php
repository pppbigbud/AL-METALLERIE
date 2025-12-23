<?php
/**
 * API REST pour le tracking et le dashboard
 * 
 * @package Almetal_Analytics
 */

if (!defined('ABSPATH')) {
    exit;
}

class Almetal_Analytics_REST_API {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('rest_api_init', array($this, 'register_routes'));
    }
    
    /**
     * Enregistrer les routes API
     */
    public function register_routes() {
        $namespace = 'almetal-analytics/v1';
        
        // === TRACKING (public) ===
        
        // Enregistrer une visite
        register_rest_route($namespace, '/track/visit', array(
            'methods' => 'POST',
            'callback' => array($this, 'track_visit'),
            'permission_callback' => '__return_true',
        ));
        
        // Enregistrer un événement
        register_rest_route($namespace, '/track/event', array(
            'methods' => 'POST',
            'callback' => array($this, 'track_event'),
            'permission_callback' => '__return_true',
        ));
        
        // Mettre à jour une visite (durée, scroll)
        register_rest_route($namespace, '/track/update', array(
            'methods' => 'POST',
            'callback' => array($this, 'update_visit'),
            'permission_callback' => '__return_true',
        ));
        
        // Heatmap click
        register_rest_route($namespace, '/track/heatmap', array(
            'methods' => 'POST',
            'callback' => array($this, 'track_heatmap'),
            'permission_callback' => '__return_true',
        ));
        
        // Enregistrer le consentement
        register_rest_route($namespace, '/consent/log', array(
            'methods' => 'POST',
            'callback' => array($this, 'log_consent'),
            'permission_callback' => '__return_true',
        ));
        
        // Opt-in
        register_rest_route($namespace, '/optin', array(
            'methods' => 'POST',
            'callback' => array($this, 'create_optin'),
            'permission_callback' => '__return_true',
        ));
        
        // === DASHBOARD (authentifié) ===
        
        // Statistiques globales
        register_rest_route($namespace, '/stats', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_stats'),
            'permission_callback' => array($this, 'check_admin_permission'),
        ));
        
        // Visites par jour
        register_rest_route($namespace, '/stats/visits', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_visits_by_day'),
            'permission_callback' => array($this, 'check_admin_permission'),
        ));
        
        // Top pages
        register_rest_route($namespace, '/stats/pages', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_top_pages'),
            'permission_callback' => array($this, 'check_admin_permission'),
        ));
        
        // Sources de trafic
        register_rest_route($namespace, '/stats/sources', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_traffic_sources'),
            'permission_callback' => array($this, 'check_admin_permission'),
        ));
        
        // Devices
        register_rest_route($namespace, '/stats/devices', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_devices'),
            'permission_callback' => array($this, 'check_admin_permission'),
        ));
        
        // Heatmap data (par URL encodée ou par hash)
        register_rest_route($namespace, '/heatmap', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_heatmap_data'),
            'permission_callback' => array($this, 'check_admin_permission'),
        ));
        
        // Consent stats
        register_rest_route($namespace, '/consent/stats', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_consent_stats'),
            'permission_callback' => array($this, 'check_admin_permission'),
        ));
        
        // Optin stats
        register_rest_route($namespace, '/optin/stats', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_optin_stats'),
            'permission_callback' => array($this, 'check_admin_permission'),
        ));
        
        // Export
        register_rest_route($namespace, '/export/(?P<type>[a-z]+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'export_data'),
            'permission_callback' => array($this, 'check_admin_permission'),
        ));
        
        // RGPD - Export données utilisateur
        register_rest_route($namespace, '/gdpr/export', array(
            'methods' => 'POST',
            'callback' => array($this, 'gdpr_export'),
            'permission_callback' => array($this, 'check_admin_permission'),
        ));
        
        // RGPD - Supprimer données utilisateur
        register_rest_route($namespace, '/gdpr/delete', array(
            'methods' => 'POST',
            'callback' => array($this, 'gdpr_delete'),
            'permission_callback' => array($this, 'check_admin_permission'),
        ));
        
        // === SEO IMPROVEMENTS ===
        
        // Obtenir les améliorations suggérées
        register_rest_route($namespace, '/seo-improvements/(?P<post_id>\d+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_seo_improvements'),
            'permission_callback' => array($this, 'check_admin_permission'),
        ));
        
        // Appliquer les améliorations
        register_rest_route($namespace, '/seo-improvements/(?P<post_id>\d+)/apply', array(
            'methods' => 'POST',
            'callback' => array($this, 'apply_seo_improvements'),
            'permission_callback' => array($this, 'check_admin_permission'),
        ));
        
        // Temps réel
        register_rest_route($namespace, '/realtime', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_realtime'),
            'permission_callback' => array($this, 'check_admin_permission'),
        ));
    }
    
    /**
     * Obtenir les améliorations SEO suggérées
     */
    public function get_seo_improvements($request) {
        $post_id = intval($request['post_id']);
        
        if (!current_user_can('edit_post', $post_id)) {
            return new WP_Error('permission_denied', 'Vous n\'avez pas les permissions pour modifier ce post', array('status' => 403));
        }
        
        $seo_improver = Almetal_Analytics_SEO_Improver::get_instance();
        $improvements = $seo_improver->get_suggested_improvements($post_id);
        
        return rest_ensure_response($improvements);
    }
    
    /**
     * Appliquer les améliorations SEO
     */
    public function apply_seo_improvements($request) {
        $post_id = intval($request['post_id']);
        $improvements = $request['improvements'];
        $create_draft = $request['create_draft'];
        
        if (!current_user_can('edit_post', $post_id)) {
            return new WP_Error('permission_denied', 'Vous n\'avez pas les permissions pour modifier ce post', array('status' => 403));
        }
        
        $seo_improver = Almetal_Analytics_SEO_Improver::get_instance();
        $result = $seo_improver->improve_page($post_id, $improvements, $create_draft);
        
        if (isset($result['error'])) {
            return new WP_Error('improvement_failed', $result['error'], array('status' => 400));
        }
        
        $message = $create_draft ? 
            'Brouillon créé avec les améliorations SEO appliquées' : 
            'Améliorations SEO appliquées directement';
        
        return rest_ensure_response(array(
            'success' => true,
            'message' => $message,
            'edit_url' => isset($result['edit_url']) ? $result['edit_url'] : null,
            'post_url' => isset($result['post_url']) ? $result['post_url'] : null
        ));
    }
    
    /**
     * Vérifier les permissions admin
     */
    public function check_admin_permission() {
        return current_user_can('manage_options');
    }
    
    // === TRACKING ENDPOINTS ===
    
    public function track_visit($request) {
        $tracker = Almetal_Analytics_Tracker::get_instance();
        return rest_ensure_response($tracker->track_visit($request->get_params()));
    }
    
    public function track_event($request) {
        $tracker = Almetal_Analytics_Tracker::get_instance();
        return rest_ensure_response($tracker->track_event($request->get_params()));
    }
    
    public function update_visit($request) {
        $tracker = Almetal_Analytics_Tracker::get_instance();
        return rest_ensure_response($tracker->update_visit($request->get_params()));
    }
    
    public function track_heatmap($request) {
        return rest_ensure_response(Almetal_Analytics_Heatmap::track_click($request->get_params()));
    }
    
    public function log_consent($request) {
        $consent = Almetal_Analytics_Consent::get_instance();
        return rest_ensure_response($consent->log_consent($request->get_params()));
    }
    
    public function create_optin($request) {
        return rest_ensure_response(Almetal_Analytics_Optin::create_optin($request->get_params()));
    }
    
    // === DASHBOARD ENDPOINTS ===
    
    public function get_stats($request) {
        $period = $request->get_param('period') ?: '30days';
        return rest_ensure_response(Almetal_Analytics_Database::get_stats($period));
    }
    
    public function get_visits_by_day($request) {
        $period = $request->get_param('period') ?: '30days';
        return rest_ensure_response(Almetal_Analytics_Database::get_visits_by_day($period));
    }
    
    public function get_top_pages($request) {
        $period = $request->get_param('period') ?: '30days';
        $limit = $request->get_param('limit') ?: 10;
        return rest_ensure_response(Almetal_Analytics_Database::get_top_pages($period, $limit));
    }
    
    public function get_traffic_sources($request) {
        $period = $request->get_param('period') ?: '30days';
        return rest_ensure_response(Almetal_Analytics_Database::get_traffic_sources($period));
    }
    
    public function get_devices($request) {
        $period = $request->get_param('period') ?: '30days';
        return rest_ensure_response(Almetal_Analytics_Database::get_devices($period));
    }
    
    public function get_heatmap_data($request) {
        $page_url = urldecode($request->get_param('page_url') ?: '');
        $device = $request->get_param('device') ?: 'all';
        $period = $request->get_param('period') ?: '30days';
        return rest_ensure_response(Almetal_Analytics_Heatmap::get_heatmap_data($page_url, $device, $period));
    }
    
    public function get_consent_stats($request) {
        $period = $request->get_param('period') ?: '30days';
        return rest_ensure_response(Almetal_Analytics_Consent::get_consent_stats($period));
    }
    
    public function get_optin_stats($request) {
        $period = $request->get_param('period') ?: '30days';
        return rest_ensure_response(Almetal_Analytics_Optin::get_optin_stats($period));
    }
    
    public function export_data($request) {
        $type = $request->get_param('type');
        $format = $request->get_param('format') ?: 'csv';
        $period = $request->get_param('period') ?: '30days';
        
        switch ($type) {
            case 'analytics':
                Almetal_Analytics_Export::export_analytics_report($period, $format);
                break;
            case 'optins':
                $data = Almetal_Analytics_Optin::export_optins($format);
                if ($format === 'json') {
                    Almetal_Analytics_Export::to_json($data, 'optins-' . date('Y-m-d') . '.json');
                } else {
                    Almetal_Analytics_Export::to_csv($data, 'optins-' . date('Y-m-d') . '.csv');
                }
                break;
            default:
                return new WP_Error('invalid_type', 'Type d\'export invalide', array('status' => 400));
        }
    }
    
    public function gdpr_export($request) {
        $identifier = $request->get_param('identifier');
        if (empty($identifier)) {
            return new WP_Error('missing_identifier', 'Identifiant requis', array('status' => 400));
        }
        return rest_ensure_response(Almetal_Analytics_GDPR::export_user_data($identifier));
    }
    
    public function gdpr_delete($request) {
        $identifier = $request->get_param('identifier');
        if (empty($identifier)) {
            return new WP_Error('missing_identifier', 'Identifiant requis', array('status' => 400));
        }
        return rest_ensure_response(Almetal_Analytics_GDPR::delete_user_data($identifier));
    }
    
    public function get_realtime($request) {
        global $wpdb;
        
        // Visiteurs actifs (dernières 5 minutes) - basé sur les sessions actives
        $five_minutes_ago = date('Y-m-d H:i:s', strtotime('-5 minutes'));
        $fifteen_minutes_ago = date('Y-m-d H:i:s', strtotime('-15 minutes'));
        
        // Compter les sessions actives (ended_at récent ou NULL avec started_at récent)
        $active_visitors = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(DISTINCT visitor_id) FROM {$wpdb->prefix}almetal_analytics_sessions 
             WHERE (ended_at >= %s OR (ended_at IS NULL AND started_at >= %s))",
            $five_minutes_ago, $fifteen_minutes_ago
        ));
        
        // Si pas de sessions, fallback sur les visites récentes (15 min pour plus de données)
        if ($active_visitors == 0) {
            $active_visitors = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(DISTINCT visitor_id) FROM {$wpdb->prefix}almetal_analytics_visits WHERE created_at >= %s",
                $fifteen_minutes_ago
            ));
        }
        
        // Visites récentes (dernières 30 minutes pour avoir plus de données)
        $thirty_minutes_ago = date('Y-m-d H:i:s', strtotime('-30 minutes'));
        $recent_visits = $wpdb->get_results($wpdb->prepare(
            "SELECT page_url, page_title, device_type, browser, country, created_at 
             FROM {$wpdb->prefix}almetal_analytics_visits 
             WHERE created_at >= %s 
             ORDER BY created_at DESC 
             LIMIT 20",
            $thirty_minutes_ago
        ), ARRAY_A);
        
        return rest_ensure_response(array(
            'active_visitors' => (int) $active_visitors,
            'recent_visits' => $recent_visits,
            'timestamp' => current_time('mysql'),
        ));
    }
}
