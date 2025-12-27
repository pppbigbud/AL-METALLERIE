<?php
/**
 * REST API class for Smart Backlink Manager
 */

if (!defined('ABSPATH')) {
    exit;
}

class SBM_REST_API {
    
    public function init(): void {
        add_action('rest_api_init', [$this, 'register_routes']);
    }
    
    public function register_routes(): void {
        // Route pour les suggestions de liens (déjà dans class-link-suggester.php)
        
        // Route pour les statistiques
        register_rest_route('smart-backlink-manager/v1', '/stats', [
            'methods' => 'GET',
            'callback' => [$this, 'get_stats'],
            'permission_callback' => [$this, 'check_admin_permissions']
        ]);
        
        // Route pour ajouter un lien interne
        register_rest_route('smart-backlink-manager/v1', '/internal-links', [
            'methods' => 'POST',
            'callback' => [$this, 'add_internal_link'],
            'permission_callback' => [$this, 'check_edit_permissions']
        ]);
        
        // Route pour récupérer les liens internes
        register_rest_route('smart-backlink-manager/v1', '/internal-links/(?P<post_id>\d+)', [
            'methods' => 'GET',
            'callback' => [$this, 'get_internal_links'],
            'permission_callback' => [$this, 'check_edit_permissions']
        ]);
        
        // Route pour les backlinks
        register_rest_route('smart-backlink-manager/v1', '/backlinks', [
            [
                'methods' => 'GET',
                'callback' => [$this, 'get_backlinks'],
                'permission_callback' => [$this, 'check_admin_permissions']
            ],
            [
                'methods' => 'POST',
                'callback' => [$this, 'add_backlink'],
                'permission_callback' => [$this, 'check_admin_permissions']
            ]
        ]);
        
        // Route pour vérifier un backlink
        register_rest_route('smart-backlink-manager/v1', '/backlinks/(?P<id>\d+)/check', [
            'methods' => 'POST',
            'callback' => [$this, 'check_backlink'],
            'permission_callback' => [$this, 'check_admin_permissions']
        ]);
        
        // Route pour les opportunités
        register_rest_route('smart-backlink-manager/v1', '/opportunities', [
            [
                'methods' => 'GET',
                'callback' => [$this, 'get_opportunities'],
                'permission_callback' => [$this, 'check_admin_permissions']
            ],
            [
                'methods' => 'POST',
                'callback' => [$this, 'add_opportunity'],
                'permission_callback' => [$this, 'check_admin_permissions']
            ]
        ]);
        
        // Route pour mettre à jour une opportunité
        register_rest_route('smart-backlink-manager/v1', '/opportunities/(?P<id>\d+)', [
            'methods' => 'PUT',
            'callback' => [$this, 'update_opportunity'],
            'permission_callback' => [$this, 'check_admin_permissions']
        ]);
        
        // Route pour supprimer une opportunité
        register_rest_route('smart-backlink-manager/v1', '/opportunities/(?P<id>\d+)', [
            'methods' => 'DELETE',
            'callback' => [$this, 'delete_opportunity'],
            'permission_callback' => [$this, 'check_admin_permissions']
        ]);
        
        // Route pour les réglages
        register_rest_route('smart-backlink-manager/v1', '/settings', [
            [
                'methods' => 'GET',
                'callback' => [$this, 'get_settings'],
                'permission_callback' => [$this, 'check_admin_permissions']
            ],
            [
                'methods' => 'POST',
                'callback' => [$this, 'update_settings'],
                'permission_callback' => [$this, 'check_admin_permissions']
            ]
        ]);
    }
    
    public function check_admin_permissions(): bool {
        return current_user_can('manage_options');
    }
    
    public function check_edit_permissions(): bool {
        return current_user_can('edit_posts');
    }
    
    public function get_stats(): WP_REST_Response {
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
        
        return new WP_REST_Response(['stats' => $stats], 200);
    }
    
    public function add_internal_link($request): WP_REST_Response {
        $from_post_id = $request->get_param('from_post_id');
        $to_post_id = $request->get_param('to_post_id');
        $anchor_text = $request->get_param('anchor_text');
        
        if (!$from_post_id || !$to_post_id) {
            return new WP_REST_Response(['error' => 'Paramètres manquants'], 400);
        }
        
        global $link_suggester;
        if (!$link_suggester) {
            $link_suggester = new SBM_Link_Suggester();
        }
        
        $result = $link_suggester->add_internal_link($from_post_id, $to_post_id, $anchor_text);
        
        if ($result) {
            return new WP_REST_Response(['success' => true], 200);
        } else {
            return new WP_REST_Response(['error' => 'Erreur lors de l\'ajout du lien'], 500);
        }
    }
    
    public function get_internal_links($request): WP_REST_Response {
        $post_id = $request->get_param('post_id');
        
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_internal_links';
        
        $links = $wpdb->get_results($wpdb->prepare("
            SELECT * FROM $table 
            WHERE from_post_id = %d OR to_post_id = %d 
            ORDER BY date_added DESC
        ", $post_id, $post_id));
        
        return new WP_REST_Response(['links' => $links], 200);
    }
    
    public function get_backlinks($request): WP_REST_Response {
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_backlinks';
        
        $page = $request->get_param('page') ?: 1;
        $per_page = $request->get_param('per_page') ?: 20;
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
        
        $total = $wpdb->get_var("SELECT COUNT(*) FROM $table $where");
        
        return new WP_REST_Response([
            'backlinks' => $backlinks,
            'total' => intval($total),
            'pages' => ceil($total / $per_page)
        ], 200);
    }
    
    public function add_backlink($request): WP_REST_Response {
        $source_url = $request->get_param('source_url');
        $target_url = $request->get_param('target_url');
        $anchor_text = $request->get_param('anchor_text');
        $backlink_type = $request->get_param('backlink_type') ?: 'autre';
        
        if (!$source_url || !$target_url) {
            return new WP_REST_Response(['error' => 'URL source et cible requises'], 400);
        }
        
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_backlinks';
        
        $result = $wpdb->insert(
            $table,
            [
                'source_url' => $source_url,
                'target_url' => $target_url,
                'anchor_text' => $anchor_text,
                'backlink_type' => $backlink_type,
                'status' => 'active',
                'date_added' => current_time('mysql')
            ],
            ['%s', '%s', '%s', '%s', '%s', '%s']
        );
        
        if ($result) {
            return new WP_REST_Response(['success' => true, 'id' => $wpdb->insert_id], 200);
        } else {
            return new WP_REST_Response(['error' => 'Erreur lors de l\'ajout du backlink'], 500);
        }
    }
    
    public function check_backlink($request): WP_REST_Response {
        $id = $request->get_param('id');
        
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_backlinks';
        
        $backlink = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $id));
        
        if (!$backlink) {
            return new WP_REST_Response(['error' => 'Backlink non trouvé'], 404);
        }
        
        // Vérifier le statut du backlink
        $response = wp_remote_head($backlink->source_url);
        
        if (is_wp_error($response)) {
            $status = 'dead';
            $http_code = 0;
        } else {
            $http_code = wp_remote_retrieve_response_code($response);
            
            if ($http_code >= 200 && $http_code < 300) {
                $status = 'active';
            } elseif ($http_code >= 300 && $http_code < 400) {
                $status = 'redirect';
            } else {
                $status = 'dead';
            }
        }
        
        // Mettre à jour le statut
        $wpdb->update(
            $table,
            [
                'status' => $status,
                'http_code' => $http_code,
                'last_check' => current_time('mysql')
            ],
            ['id' => $id],
            ['%s', '%d', '%s'],
            ['%d']
        );
        
        return new WP_REST_Response([
            'status' => $status,
            'http_code' => $http_code,
            'last_check' => current_time('mysql')
        ], 200);
    }
    
    public function get_opportunities($request): WP_REST_Response {
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_opportunities';
        
        $status = $request->get_param('status');
        $type = $request->get_param('type');
        
        $where = 'WHERE 1=1';
        $params = [];
        
        if ($status) {
            $where .= ' AND status = %s';
            $params[] = $status;
        }
        
        if ($type) {
            $where .= ' AND type = %s';
            $params[] = $type;
        }
        
        $sql = "SELECT * FROM $table $where ORDER BY date_added DESC";
        
        if (!empty($params)) {
            $sql = $wpdb->prepare($sql, $params);
        }
        
        $opportunities = $wpdb->get_results($sql);
        
        return new WP_REST_Response(['opportunities' => $opportunities], 200);
    }
    
    public function add_opportunity($request): WP_REST_Response {
        $site_name = $request->get_param('site_name');
        $url = $request->get_param('url');
        $type = $request->get_param('type') ?: 'autre';
        $notes = $request->get_param('notes');
        
        if (!$site_name || !$url) {
            return new WP_REST_Response(['error' => 'Nom du site et URL requis'], 400);
        }
        
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_opportunities';
        
        $result = $wpdb->insert(
            $table,
            [
                'site_name' => $site_name,
                'url' => $url,
                'type' => $type,
                'status' => 'to_contact',
                'notes' => $notes,
                'date_added' => current_time('mysql')
            ],
            ['%s', '%s', '%s', '%s', '%s', '%s']
        );
        
        if ($result) {
            return new WP_REST_Response(['success' => true, 'id' => $wpdb->insert_id], 200);
        } else {
            return new WP_REST_Response(['error' => 'Erreur lors de l\'ajout de l\'opportunité'], 500);
        }
    }
    
    public function update_opportunity($request): WP_REST_Response {
        $id = $request->get_param('id');
        $status = $request->get_param('status');
        $notes = $request->get_param('notes');
        
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_opportunities';
        
        $update_data = ['date_updated' => current_time('mysql')];
        $update_format = ['%s'];
        
        if ($status) {
            $update_data['status'] = $status;
            $update_format[] = '%s';
        }
        
        if ($notes !== null) {
            $update_data['notes'] = $notes;
            $update_format[] = '%s';
        }
        
        $result = $wpdb->update(
            $table,
            $update_data,
            ['id' => $id],
            $update_format,
            ['%d']
        );
        
        if ($result !== false) {
            return new WP_REST_Response(['success' => true], 200);
        } else {
            return new WP_REST_Response(['error' => 'Erreur lors de la mise à jour'], 500);
        }
    }
    
    public function delete_opportunity($request): WP_REST_Response {
        $id = $request->get_param('id');
        
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_opportunities';
        
        $result = $wpdb->delete(
            $table,
            ['id' => $id],
            ['%d']
        );
        
        if ($result) {
            return new WP_REST_Response(['success' => true], 200);
        } else {
            return new WP_REST_Response(['error' => 'Erreur lors de la suppression'], 500);
        }
    }
    
    public function get_settings(): WP_REST_Response {
        $settings = [
            'sbm_site_name' => get_option('sbm_site_name'),
            'sbm_site_url' => get_option('sbm_site_url'),
            'sbm_site_niche' => get_option('sbm_site_niche'),
            'sbm_suggestions_limit' => get_option('sbm_suggestions_limit'),
            'sbm_gutenberg_panel_enabled' => get_option('sbm_gutenberg_panel_enabled'),
            'sbm_check_frequency' => get_option('sbm_check_frequency'),
            'sbm_custom_keywords' => json_decode(get_option('sbm_custom_keywords', '[]'), true)
        ];
        
        return new WP_REST_Response(['settings' => $settings], 200);
    }
    
    public function update_settings($request): WP_REST_Response {
        $settings = $request->get_param('settings');
        
        if (!$settings || !is_array($settings)) {
            return new WP_REST_Response(['error' => 'Réglages invalides'], 400);
        }
        
        foreach ($settings as $key => $value) {
            if (strpos($key, 'sbm_') === 0) {
                if ($key === 'sbm_custom_keywords' && is_array($value)) {
                    $value = json_encode($value);
                }
                update_option($key, $value);
            }
        }
        
        return new WP_REST_Response(['success' => true], 200);
    }
}
