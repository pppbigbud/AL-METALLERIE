<?php
/**
 * REST API class for Smart Backlink Manager
 */

if (!defined('ABSPATH')) {
    exit;
}

class SBM_REST_API extends WP_REST_Controller {
    
    public function __construct() {
        $this->namespace = 'sbm/v1';
    }
    
    public function register_routes(): void {
        // Route pour obtenir les liens internes
        register_rest_route($this->namespace, '/internal-links', [
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [$this, 'get_internal_links'],
                'permission_callback' => [$this, 'check_permissions']
            ],
            [
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => [$this, 'create_internal_link'],
                'permission_callback' => [$this, 'check_permissions']
            ]
        ]);
        
        // Route pour obtenir les backlinks
        register_rest_route($this->namespace, '/backlinks', [
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [$this, 'get_backlinks'],
                'permission_callback' => [$this, 'check_permissions']
            ],
            [
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => [$this, 'create_backlink'],
                'permission_callback' => [$this, 'check_permissions']
            ]
        ]);
        
        // Route pour vérifier un backlink
        register_rest_route($this->namespace, '/backlinks/(?P<id>\d+)/check', [
            [
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => [$this, 'check_backlink'],
                'permission_callback' => [$this, 'check_permissions']
            ]
        ]);
        
        // Route pour obtenir les opportunités
        register_rest_route($this->namespace, '/opportunities', [
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [$this, 'get_opportunities'],
                'permission_callback' => [$this, 'check_permissions']
            ],
            [
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => [$this, 'create_opportunity'],
                'permission_callback' => [$this, 'check_permissions']
            ]
        ]);
        
        // Route pour obtenir les statistiques du dashboard
        register_rest_route($this->namespace, '/stats', [
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [$this, 'get_stats'],
                'permission_callback' => [$this, 'check_permissions']
            ]
        ]);
    }
    
    public function check_permissions(): bool {
        return current_user_can('manage_options');
    }
    
    public function get_internal_links(WP_REST_Request $request): WP_REST_Response {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sbm_internal_links';
        $page = $request->get_param('page') ?: 1;
        $per_page = $request->get_param('per_page') ?: 50;
        $offset = ($page - 1) * $per_page;
        
        $links = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table ORDER BY date_added DESC LIMIT %d OFFSET %d",
            $per_page,
            $offset
        ));
        
        $total = intval($wpdb->get_var("SELECT COUNT(*) FROM $table"));
        
        foreach ($links as $link) {
            $link->source_post = get_post($link->from_post_id);
            $link->target_post = get_post($link->to_post_id);
        }
        
        return new WP_REST_Response([
            'links' => $links,
            'total' => $total,
            'pages' => ceil($total / $per_page)
        ]);
    }
    
    public function create_internal_link(WP_REST_Request $request): WP_REST_Response {
        $from_post_id = intval($request->get_param('from_post_id'));
        $to_post_id = intval($request->get_param('to_post_id'));
        $anchor_text = sanitize_text_field($request->get_param('anchor_text'));
        
        if (empty($from_post_id) || empty($to_post_id)) {
            return new WP_Error('missing_params', 'Les paramètres from_post_id et to_post_id sont requis', ['status' => 400]);
        }
        
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_internal_links';
        
        // Vérifier si le lien existe déjà
        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE from_post_id = %d AND to_post_id = %d",
            $from_post_id,
            $to_post_id
        ));
        
        if ($exists) {
            return new WP_Error('link_exists', 'Ce lien existe déjà', ['status' => 409]);
        }
        
        // Insérer le nouveau lien
        $result = $wpdb->insert(
            $table,
            [
                'from_post_id' => $from_post_id,
                'to_post_id' => $to_post_id,
                'anchor_text' => $anchor_text ?: get_the_title($to_post_id),
                'date_added' => current_time('mysql')
            ],
            ['%d', '%d', '%s', '%s']
        );
        
        if ($result === false) {
            return new WP_Error('db_error', 'Erreur lors de l\'ajout du lien', ['status' => 500]);
        }
        
        return new WP_REST_Response([
            'success' => true,
            'message' => 'Lien interne ajouté avec succès',
            'id' => $wpdb->insert_id
        ]);
    }
    
    public function get_backlinks(WP_REST_Request $request): WP_REST_Response {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sbm_backlinks';
        $page = $request->get_param('page') ?: 1;
        $per_page = $request->get_param('per_page') ?: 50;
        $status = $request->get_param('status');
        $offset = ($page - 1) * $per_page;
        
        $where = '';
        if ($status) {
            $where = $wpdb->prepare("WHERE status = %s", $status);
        }
        
        $backlinks = $wpdb->get_results("
            SELECT * FROM $table 
            $where
            ORDER BY date_added DESC 
            LIMIT $per_page OFFSET $offset
        ");
        
        $total = intval($wpdb->get_var("SELECT COUNT(*) FROM $table $where"));
        
        return new WP_REST_Response([
            'backlinks' => $backlinks,
            'total' => $total,
            'pages' => ceil($total / $per_page)
        ]);
    }
    
    public function create_backlink(WP_REST_Request $request): WP_REST_Response {
        $source_url = esc_url_raw($request->get_param('source_url'));
        $target_url = esc_url_raw($request->get_param('target_url'));
        $anchor_text = sanitize_text_field($request->get_param('anchor_text'));
        $backlink_type = sanitize_text_field($request->get_param('backlink_type'));
        
        if (empty($source_url) || empty($target_url)) {
            return new WP_Error('missing_params', 'Les URLs source et cible sont requises', ['status' => 400]);
        }
        
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_backlinks';
        
        // Vérifier si le backlink existe déjà
        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE source_url = %s AND target_url = %s",
            $source_url,
            $target_url
        ));
        
        if ($exists) {
            return new WP_Error('backlink_exists', 'Ce backlink existe déjà', ['status' => 409]);
        }
        
        // Insérer le nouveau backlink
        $result = $wpdb->insert(
            $table,
            [
                'source_url' => $source_url,
                'target_url' => $target_url,
                'anchor_text' => $anchor_text,
                'backlink_type' => $backlink_type ?: 'autre',
                'status' => 'active',
                'date_added' => current_time('mysql')
            ],
            ['%s', '%s', '%s', '%s', '%s', '%s']
        );
        
        if ($result === false) {
            return new WP_Error('db_error', 'Erreur lors de l\'ajout du backlink', ['status' => 500]);
        }
        
        return new WP_REST_Response([
            'success' => true,
            'message' => 'Backlink ajouté avec succès',
            'id' => $wpdb->insert_id
        ]);
    }
    
    public function check_backlink(WP_REST_Request $request): WP_REST_Response {
        $backlink_id = intval($request->get_param('id'));
        
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_backlinks';
        $backlink = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $backlink_id));
        
        if (!$backlink) {
            return new WP_Error('not_found', 'Backlink non trouvé', ['status' => 404]);
        }
        
        // Vérifier si le backlink est actif
        $response = wp_remote_get($backlink->source_url, ['timeout' => 10]);
        
        if (is_wp_error($response)) {
            $status = 'dead';
        } else {
            $body = wp_remote_retrieve_body($response);
            $status = (strpos($body, $backlink->target_url) !== false) ? 'active' : 'lost';
        }
        
        // Mettre à jour le statut
        $wpdb->update(
            $table,
            ['status' => $status, 'last_checked' => current_time('mysql')],
            ['id' => $backlink_id],
            ['%s', '%s'],
            ['%d']
        );
        
        return new WP_REST_Response([
            'success' => true,
            'message' => 'Backlink vérifié',
            'status' => $status
        ]);
    }
    
    public function get_opportunities(WP_REST_Request $request): WP_REST_Response {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sbm_opportunities';
        $page = $request->get_param('page') ?: 1;
        $per_page = $request->get_param('per_page') ?: 50;
        $status = $request->get_param('status');
        $offset = ($page - 1) * $per_page;
        
        $where = '';
        if ($status) {
            $where = $wpdb->prepare("WHERE status = %s", $status);
        }
        
        $opportunities = $wpdb->get_results("
            SELECT * FROM $table 
            $where
            ORDER BY priority DESC, created_at DESC 
            LIMIT $per_page OFFSET $offset
        ");
        
        $total = intval($wpdb->get_var("SELECT COUNT(*) FROM $table $where"));
        
        return new WP_REST_Response([
            'opportunities' => $opportunities,
            'total' => $total,
            'pages' => ceil($total / $per_page)
        ]);
    }
    
    public function create_opportunity(WP_REST_Request $request): WP_REST_Response {
        $site_name = sanitize_text_field($request->get_param('site_name'));
        $url = esc_url_raw($request->get_param('url'));
        $type = sanitize_text_field($request->get_param('type'));
        $contact_info = sanitize_textarea_field($request->get_param('contact_info'));
        $notes = sanitize_textarea_field($request->get_param('notes'));
        
        if (empty($site_name) || empty($url)) {
            return new WP_Error('missing_params', 'Le nom du site et l\'URL sont requis', ['status' => 400]);
        }
        
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_opportunities';
        
        // Insérer la nouvelle opportunité
        $result = $wpdb->insert(
            $table,
            [
                'site_name' => $site_name,
                'url' => $url,
                'type' => $type ?: 'autre',
                'contact_info' => $contact_info,
                'notes' => $notes,
                'status' => 'new',
                'priority' => 'medium',
                'created_at' => current_time('mysql')
            ],
            ['%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s']
        );
        
        if ($result === false) {
            return new WP_Error('db_error', 'Erreur lors de l\'ajout de l\'opportunité', ['status' => 500]);
        }
        
        return new WP_REST_Response([
            'success' => true,
            'message' => 'Opportunité ajoutée avec succès',
            'id' => $wpdb->insert_id
        ]);
    }
    
    public function get_stats(WP_REST_Request $request): WP_REST_Response {
        global $wpdb;
        
        $stats = [];
        
        // Statistiques des liens internes
        $internal_table = $wpdb->prefix . 'sbm_internal_links';
        $stats['internal_links'] = [
            'total' => intval($wpdb->get_var("SELECT COUNT(*) FROM $internal_table")),
            'this_month' => intval($wpdb->get_var("SELECT COUNT(*) FROM $internal_table WHERE date_added >= DATE_FORMAT(NOW(), '%Y-%m-01')"))
        ];
        
        // Statistiques des backlinks
        $backlinks_table = $wpdb->prefix . 'sbm_backlinks';
        $stats['backlinks'] = [
            'total' => intval($wpdb->get_var("SELECT COUNT(*) FROM $backlinks_table")),
            'active' => intval($wpdb->get_var("SELECT COUNT(*) FROM $backlinks_table WHERE status = 'active'")),
            'dead' => intval($wpdb->get_var("SELECT COUNT(*) FROM $backlinks_table WHERE status = 'dead'"))
        ];
        
        // Statistiques des opportunités
        $opportunities_table = $wpdb->prefix . 'sbm_opportunities';
        $stats['opportunities'] = [
            'total' => intval($wpdb->get_var("SELECT COUNT(*) FROM $opportunities_table")),
            'to_contact' => intval($wpdb->get_var("SELECT COUNT(*) FROM $opportunities_table WHERE status = 'to_contact'")),
            'in_progress' => intval($wpdb->get_var("SELECT COUNT(*) FROM $opportunities_table WHERE status = 'in_progress'")),
            'obtained' => intval($wpdb->get_var("SELECT COUNT(*) FROM $opportunities_table WHERE status = 'obtained'"))
        ];
        
        // Score SEO
        $internal_links_score = min(40, $stats['internal_links']['total'] * 2);
        $backlinks_score = min(40, $stats['backlinks']['active'] * 3);
        $opportunities_score = min(20, $stats['opportunities']['obtained'] * 4);
        $stats['seo_score'] = $internal_links_score + $backlinks_score + $opportunities_score;
        
        return new WP_REST_Response($stats);
    }
}
