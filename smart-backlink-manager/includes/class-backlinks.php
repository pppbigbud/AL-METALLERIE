<?php
/**
 * Backlinks class for Smart Backlink Manager
 */

if (!defined('ABSPATH')) {
    exit;
}

class SBM_Backlinks {
    
    public function init(): void {
        // Hooks AJAX
        add_action('wp_ajax_sbm_add_backlink', [$this, 'ajax_add_backlink']);
        add_action('wp_ajax_sbm_check_backlink', [$this, 'ajax_check_backlink']);
        add_action('wp_ajax_sbm_check_all_backlinks', [$this, 'ajax_check_all_backlinks']);
        add_action('wp_ajax_sbm_delete_backlink', [$this, 'ajax_delete_backlink']);
        
        // Hook pour vérification automatique (cron)
        add_action('sbm_check_backlinks_cron', [$this, 'check_all_backlinks']);
    }
    
    public function render_page(): void {
        ?>
        <div class="wrap sbm-backlinks">
            <h1 class="wp-heading-inline">
                <?php _e('Backlinks', 'smart-backlink-manager'); ?>
            </h1>
            <a href="#" class="page-title-action" id="sbm-add-backlink-btn">
                <?php _e('Ajouter un backlink', 'smart-backlink-manager'); ?>
            </a>
            <a href="#" class="page-title-action" id="sbm-check-all-btn">
                <span class="dashicons dashicons-update"></span>
                <?php _e('Tout vérifier', 'smart-backlink-manager'); ?>
            </a>
            <hr class="wp-header-end">
            
            <!-- Formulaire d'ajout -->
            <div id="sbm-add-backlink-form" class="sbm-form-card" style="display: none;">
                <h2><?php _e('Ajouter un backlink', 'smart-backlink-manager'); ?></h2>
                <form id="sbm-backlink-form">
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="source_url"><?php _e('URL source', 'smart-backlink-manager'); ?></label>
                            </th>
                            <td>
                                <input type="url" name="source_url" id="source_url" class="regular-text" required>
                                <p class="description">
                                    <?php _e('L\'URL complète de la page contenant le lien vers votre site', 'smart-backlink-manager'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="target_url"><?php _e('URL cible', 'smart-backlink-manager'); ?></label>
                            </th>
                            <td>
                                <input type="url" name="target_url" id="target_url" class="regular-text" required>
                                <p class="description">
                                    <?php _e('L\'URL de votre site vers laquelle pointe le backlink', 'smart-backlink-manager'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="anchor_text"><?php _e('Texte d\'ancrage', 'smart-backlink-manager'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="anchor_text" id="anchor_text" class="regular-text">
                                <p class="description">
                                    <?php _e('Le texte cliquable du lien', 'smart-backlink-manager'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="backlink_type"><?php _e('Type de backlink', 'smart-backlink-manager'); ?></label>
                            </th>
                            <td>
                                <select name="backlink_type" id="backlink_type">
                                    <option value="autre"><?php _e('Autre', 'smart-backlink-manager'); ?></option>
                                    <option value="annuaire"><?php _e('Annuaire', 'smart-backlink-manager'); ?></option>
                                    <option value="partenaire"><?php _e('Partenaire', 'smart-backlink-manager'); ?></option>
                                    <option value="presse"><?php _e('Presse / Média', 'smart-backlink-manager'); ?></option>
                                    <option value="guest_post"><?php _e('Guest post', 'smart-backlink-manager'); ?></option>
                                    <option value="commentaire"><?php _e('Commentaire', 'smart-backlink-manager'); ?></option>
                                    <option value="forum"><?php _e('Forum', 'smart-backlink-manager'); ?></option>
                                    <option value="réseau_social"><?php _e('Réseau social', 'smart-backlink-manager'); ?></option>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <p class="submit">
                        <button type="submit" class="button button-primary">
                            <?php _e('Ajouter le backlink', 'smart-backlink-manager'); ?>
                        </button>
                        <button type="button" class="button" id="sbm-cancel-add">
                            <?php _e('Annuler', 'smart-backlink-manager'); ?>
                        </button>
                    </p>
                </form>
            </div>
            
            <!-- Statistiques -->
            <div class="sbm-stats-row">
                <div class="sbm-stat-box sbm-stat-active">
                    <span class="sbm-stat-number"><?php echo $this->get_backlinks_count('active'); ?></span>
                    <span class="sbm-stat-label"><?php _e('Actifs', 'smart-backlink-manager'); ?></span>
                </div>
                <div class="sbm-stat-box sbm-stat-dead">
                    <span class="sbm-stat-number"><?php echo $this->get_backlinks_count('dead'); ?></span>
                    <span class="sbm-stat-label"><?php _e('Morts', 'smart-backlink-manager'); ?></span>
                </div>
                <div class="sbm-stat-box sbm-stat-redirect">
                    <span class="sbm-stat-number"><?php echo $this->get_backlinks_count('redirect'); ?></span>
                    <span class="sbm-stat-label"><?php _e('Redirections', 'smart-backlink-manager'); ?></span>
                </div>
                <div class="sbm-stat-box sbm-stat-total">
                    <span class="sbm-stat-number"><?php echo $this->get_backlinks_count(); ?></span>
                    <span class="sbm-stat-label"><?php _e('Total', 'smart-backlink-manager'); ?></span>
                </div>
            </div>
            
            <!-- Filtres -->
            <div class="sbm-filters">
                <select id="sbm-filter-status">
                    <option value=""><?php _e('Tous les statuts', 'smart-backlink-manager'); ?></option>
                    <option value="active"><?php _e('Actifs', 'smart-backlink-manager'); ?></option>
                    <option value="dead"><?php _e('Morts', 'smart-backlink-manager'); ?></option>
                    <option value="redirect"><?php _e('Redirections', 'smart-backlink-manager'); ?></option>
                </select>
                
                <select id="sbm-filter-type">
                    <option value=""><?php _e('Tous les types', 'smart-backlink-manager'); ?></option>
                    <option value="annuaire"><?php _e('Annuaires', 'smart-backlink-manager'); ?></option>
                    <option value="partenaire"><?php _e('Partenaires', 'smart-backlink-manager'); ?></option>
                    <option value="presse"><?php _e('Presse', 'smart-backlink-manager'); ?></option>
                    <option value="guest_post"><?php _e('Guest posts', 'smart-backlink-manager'); ?></option>
                    <option value="autre"><?php _e('Autres', 'smart-backlink-manager'); ?></option>
                </select>
                
                <input type="text" id="sbm-search-backlinks" placeholder="<?php _e('Rechercher...', 'smart-backlink-manager'); ?>">
                
                <button class="button" id="sbm-export-backlinks">
                    <?php _e('Exporter', 'smart-backlink-manager'); ?>
                </button>
            </div>
            
            <!-- Tableau des backlinks -->
            <div class="sbm-backlinks-table-container">
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th scope="col" class="manage-column column-primary"><?php _e('Source', 'smart-backlink-manager'); ?></th>
                            <th scope="col"><?php _e('Cible', 'smart-backlink-manager'); ?></th>
                            <th scope="col"><?php _e('Texte d\'ancrage', 'smart-backlink-manager'); ?></th>
                            <th scope="col"><?php _e('Type', 'smart-backlink-manager'); ?></th>
                            <th scope="col"><?php _e('Statut', 'smart-backlink-manager'); ?></th>
                            <th scope="col"><?php _e('Vérifié', 'smart-backlink-manager'); ?></th>
                            <th scope="col"><?php _e('Actions', 'smart-backlink-manager'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="sbm-backlinks-list">
                        <?php echo $this->get_backlinks_rows(); ?>
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <div class="tablenav bottom">
                    <div class="tablenav-pages" id="sbm-pagination">
                        <?php echo $this->get_pagination(); ?>
                    </div>
                </div>
            </div>
            
            <!-- Analyse des domaines -->
            <div class="sbm-domain-analysis">
                <h2><?php _e('Analyse des domaines', 'smart-backlink-manager'); ?></h2>
                <div class="sbm-domain-stats">
                    <?php echo $this->get_domain_analysis(); ?>
                </div>
            </div>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            // Afficher/masquer le formulaire d'ajout
            $('#sbm-add-backlink-btn').on('click', function(e) {
                e.preventDefault();
                $('#sbm-add-backlink-form').slideToggle();
            });
            
            $('#sbm-cancel-add').on('click', function() {
                $('#sbm-add-backlink-form').slideUp();
                $('#sbm-backlink-form')[0].reset();
            });
            
            // Soumission du formulaire
            $('#sbm-backlink-form').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                
                $.post(ajaxurl, formData + '&action=sbm_add_backlink&_ajax_nonce=<?php echo wp_create_nonce('sbm_nonce'); ?>', function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data || '<?php _e('Erreur lors de l\'ajout du backlink', 'smart-backlink-manager'); ?>');
                    }
                });
            });
            
            // Vérifier tous les backlinks
            $('#sbm-check-all-btn').on('click', function(e) {
                e.preventDefault();
                if (!confirm('<?php _e('Cette action peut prendre du temps. Continuer?', 'smart-backlink-manager'); ?>')) {
                    return;
                }
                
                var $btn = $(this);
                $btn.prop('disabled', true).find('.dashicons').addClass('spin');
                
                $.post(ajaxurl, {
                    action: 'sbm_check_all_backlinks',
                    _ajax_nonce: '<?php echo wp_create_nonce('sbm_nonce'); ?>'
                }, function(response) {
                    $btn.prop('disabled', false).find('.dashicons').removeClass('spin');
                    if (response.success) {
                        alert(response.data.message);
                        location.reload();
                    } else {
                        alert(response.data || '<?php _e('Erreur lors de la vérification', 'smart-backlink-manager'); ?>');
                    }
                });
            });
            
            // Vérifier un backlink individuel
            $(document).on('click', '.sbm-check-single', function() {
                var $btn = $(this);
                var id = $btn.data('id');
                
                $btn.prop('disabled', true);
                
                $.post(ajaxurl, {
                    action: 'sbm_check_backlink',
                    backlink_id: id,
                    _ajax_nonce: '<?php echo wp_create_nonce('sbm_nonce'); ?>'
                }, function(response) {
                    $btn.prop('disabled', false);
                    if (response.success) {
                        var $row = $btn.closest('tr');
                        $row.find('.sbm-status').html(response.data.status_html);
                        $row.find('.sbm-last-check').text(response.data.last_check);
                    } else {
                        alert(response.data || '<?php _e('Erreur lors de la vérification', 'smart-backlink-manager'); ?>');
                    }
                });
            });
            
            // Filtrage
            $('#sbm-filter-status, #sbm-filter-type, #sbm-search-backlinks').on('change keyup', function() {
                sbmFilterBacklinks();
            });
            
            function sbmFilterBacklinks() {
                var status = $('#sbm-filter-status').val();
                var type = $('#sbm-filter-type').val();
                var search = $('#sbm-search-backlinks').val().toLowerCase();
                
                $('#sbm-backlinks-list tr').each(function() {
                    var show = true;
                    
                    if (status && $(this).data('status') != status) show = false;
                    if (type && $(this).data('type') != type) show = false;
                    if (search && $(this).text().toLowerCase().indexOf(search) === -1) show = false;
                    
                    $(this).toggle(show);
                });
            }
            
            // Export
            $('#sbm-export-backlinks').on('click', function() {
                var params = new URLSearchParams({
                    action: 'sbm_export_backlinks',
                    _ajax_nonce: '<?php echo wp_create_nonce('sbm_nonce'); ?>'
                });
                
                window.location.href = ajaxurl + '?' + params.toString();
            });
        });
        </script>
        <style>
        .spin {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        </style>
        <?php
    }
    
    private function get_backlinks_count($status = ''): int {
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_backlinks';
        
        if ($status) {
            return intval($wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE status = %s", $status)));
        }
        
        return intval($wpdb->get_var("SELECT COUNT(*) FROM $table"));
    }
    
    private function get_backlinks_rows(): string {
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_backlinks';
        
        $page = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
        $per_page = 20;
        $offset = ($page - 1) * $per_page;
        
        $where = '';
        if (isset($_GET['status']) && $_GET['status']) {
            $where = $wpdb->prepare("WHERE status = %s", $_GET['status']);
        }
        
        $backlinks = $wpdb->get_results("
            SELECT * FROM $table 
            $where
            ORDER BY date_added DESC 
            LIMIT $per_page OFFSET $offset
        ");
        
        $rows = '';
        foreach ($backlinks as $backlink) {
            $status_html = $this->get_status_html($backlink->status);
            $host = parse_url($backlink->source_url, PHP_URL_HOST);
            
            $rows .= sprintf(
                '<tr data-status="%s" data-type="%s">
                    <td>
                        <strong><a href="%s" target="_blank">%s</a></strong>
                        <br><small class="sbm-domain">%s</small>
                    </td>
                    <td><a href="%s" target="_blank">%s</a></td>
                    <td>%s</td>
                    <td>%s</td>
                    <td class="sbm-status">%s</td>
                    <td class="sbm-last-check">%s</td>
                    <td>
                        <button class="button button-small sbm-check-single" data-id="%d">
                            %s
                        </button>
                        <button class="button button-small sbm-delete-link" data-id="%d">
                            %s
                        </button>
                    </td>
                </tr>',
                $backlink->status,
                $backlink->backlink_type,
                esc_url($backlink->source_url),
                esc_html(parse_url($backlink->source_url, PHP_URL_PATH)),
                esc_html($host),
                esc_url($backlink->target_url),
                esc_html(parse_url($backlink->target_url, PHP_URL_PATH)),
                esc_html($backlink->anchor_text ?: '-'),
                $this->get_type_label($backlink->backlink_type),
                $status_html,
                $backlink->last_check ? date('d/m/Y H:i', strtotime($backlink->last_check)) : __('Jamais', 'smart-backlink-manager'),
                $backlink->id,
                __('Vérifier', 'smart-backlink-manager'),
                $backlink->id,
                __('Supprimer', 'smart-backlink-manager')
            );
        }
        
        if (empty($rows)) {
            $rows = '<tr><td colspan="7">' . __('Aucun backlink trouvé', 'smart-backlink-manager') . '</td></tr>';
        }
        
        return $rows;
    }
    
    private function get_status_html(string $status): string {
        $labels = [
            'active' => '<span class="sbm-status-active">' . __('Actif', 'smart-backlink-manager') . '</span>',
            'dead' => '<span class="sbm-status-dead">' . __('Mort', 'smart-backlink-manager') . '</span>',
            'redirect' => '<span class="sbm-status-redirect">' . __('Redirection', 'smart-backlink-manager') . '</span>'
        ];
        
        return $labels[$status] ?? $status;
    }
    
    private function get_type_label(string $type): string {
        $labels = [
            'annuaire' => __('Annuaire', 'smart-backlink-manager'),
            'partenaire' => __('Partenaire', 'smart-backlink-manager'),
            'presse' => __('Presse', 'smart-backlink-manager'),
            'guest_post' => __('Guest post', 'smart-backlink-manager'),
            'commentaire' => __('Commentaire', 'smart-backlink-manager'),
            'forum' => __('Forum', 'smart-backlink-manager'),
            'réseau_social' => __('Réseau social', 'smart-backlink-manager'),
            'autre' => __('Autre', 'smart-backlink-manager')
        ];
        
        return $labels[$type] ?? $type;
    }
    
    private function get_pagination(): string {
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_backlinks';
        
        $total = intval($wpdb->get_var("SELECT COUNT(*) FROM $table"));
        $per_page = 20;
        $current_page = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
        $total_pages = ceil($total / $per_page);
        
        if ($total_pages <= 1) return '';
        
        $pagination = '<span class="displaying-num">' . sprintf(_n('%s élément', '%s éléments', $total), number_format($total)) . '</span>';
        $pagination .= '<span class="pagination-links">';
        
        // Previous
        if ($current_page > 1) {
            $pagination .= sprintf(
                '<a class="prev-page" href="%s"><span class="screen-reader-text">%s</span><span aria-hidden="true">‹</span></a>',
                esc_url(add_query_arg('paged', $current_page - 1)),
                __('Page précédente', 'smart-backlink-manager')
            );
        }
        
        // Page numbers
        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $current_page) {
                $pagination .= '<span class="paging-input">' . sprintf(_x('%1$s sur %2$s', 'paging'), $i, $total_pages) . '</span>';
            } else {
                $pagination .= sprintf(
                    '<a class="page-numbers" href="%s">%d</a>',
                    esc_url(add_query_arg('paged', $i)),
                    $i
                );
            }
        }
        
        // Next
        if ($current_page < $total_pages) {
            $pagination .= sprintf(
                '<a class="next-page" href="%s"><span class="screen-reader-text">%s</span><span aria-hidden="true">›</span></a>',
                esc_url(add_query_arg('paged', $current_page + 1)),
                __('Page suivante', 'smart-backlink-manager')
            );
        }
        
        $pagination .= '</span>';
        
        return $pagination;
    }
    
    private function get_domain_analysis(): string {
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_backlinks';
        
        $domains = $wpdb->get_results("
            SELECT 
                SUBSTRING_INDEX(SUBSTRING_INDEX(source_url, '//', -1), '/', 1) as domain,
                COUNT(*) as count,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_count
            FROM $table 
            GROUP BY domain 
            ORDER BY count DESC 
            LIMIT 10
        ");
        
        $html = '<div class="sbm-domain-grid">';
        foreach ($domains as $domain) {
            $html .= sprintf(
                '<div class="sbm-domain-item">
                    <div class="sbm-domain-name">%s</div>
                    <div class="sbm-domain-count">%d backlinks</div>
                    <div class="sbm-domain-active">%d actifs</div>
                </div>',
                esc_html($domain->domain),
                $domain->count,
                $domain->active_count
            );
        }
        $html .= '</div>';
        
        return $html;
    }
    
    public function ajax_add_backlink(): void {
        check_ajax_referer('sbm_nonce', '_ajax_nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Vous n\'avez pas les permissions nécessaires', 'smart-backlink-manager'));
        }
        
        $source_url = esc_url_raw($_POST['source_url']);
        $target_url = esc_url_raw($_POST['target_url']);
        $anchor_text = sanitize_text_field($_POST['anchor_text']);
        $backlink_type = sanitize_text_field($_POST['backlink_type']);
        
        if (!$source_url || !$target_url) {
            wp_send_json_error(__('Les URLs source et cible sont requises', 'smart-backlink-manager'));
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
            wp_send_json_success(__('Backlink ajouté avec succès', 'smart-backlink-manager'));
        } else {
            wp_send_json_error(__('Erreur lors de l\'ajout du backlink', 'smart-backlink-manager'));
        }
    }
    
    public function ajax_check_backlink(): void {
        check_ajax_referer('sbm_nonce', '_ajax_nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Vous n\'avez pas les permissions nécessaires', 'smart-backlink-manager'));
        }
        
        $backlink_id = intval($_POST['backlink_id']);
        
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_backlinks';
        
        $backlink = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $backlink_id));
        
        if (!$backlink) {
            wp_send_json_error(__('Backlink non trouvé', 'smart-backlink-manager'));
        }
        
        // Vérifier le statut
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
        
        // Mettre à jour
        $wpdb->update(
            $table,
            [
                'status' => $status,
                'http_code' => $http_code,
                'last_check' => current_time('mysql')
            ],
            ['id' => $backlink_id],
            ['%s', '%d', '%s'],
            ['%d']
        );
        
        wp_send_json_success([
            'status' => $status,
            'status_html' => $this->get_status_html($status),
            'last_check' => date('d/m/Y H:i')
        ]);
    }
    
    public function ajax_check_all_backlinks(): void {
        check_ajax_referer('sbm_nonce', '_ajax_nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Vous n\'avez pas les permissions nécessaires', 'smart-backlink-manager'));
        }
        
        $checked = $this->check_all_backlinks();
        
        wp_send_json_success(sprintf(
            __('%d backlinks vérifiés avec succès', 'smart-backlink-manager'),
            $checked
        ));
    }
    
    public function check_all_backlinks(): int {
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_backlinks';
        
        $backlinks = $wpdb->get_results("SELECT * FROM $table");
        $checked = 0;
        $max_checks_per_batch = 10; // Limite pour éviter les timeouts
        $batch_delay = 1; // Pause en secondes entre chaque batch
        
        foreach ($backlinks as $backlink) {
            $response = wp_remote_head($backlink->source_url, [
                'timeout' => 10,
                'user-agent' => 'Smart Backlink Manager/' . SBM_VERSION
            ]);
            
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
            
            $wpdb->update(
                $table,
                [
                    'status' => $status,
                    'http_code' => $http_code,
                    'last_check' => current_time('mysql')
                ],
                ['id' => $backlink->id],
                ['%s', '%d', '%s'],
                ['%d']
            );
            
            $checked++;
            
            // Rate limiting: pause après chaque batch
            if ($checked % $max_checks_per_batch === 0) {
                sleep($batch_delay);
                
                // Vérifier si on approche du timeout PHP
                $time_elapsed = time() - $_SERVER['REQUEST_TIME'];
                $max_execution_time = ini_get('max_execution_time');
                if ($max_execution_time > 0 && $time_elapsed > ($max_execution_time - 10)) {
                    break; // Arrêter avant le timeout
                }
            }
        }
        
        return $checked;
    }
    
    public function ajax_delete_backlink(): void {
        check_ajax_referer('sbm_nonce', '_ajax_nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Vous n\'avez pas les permissions nécessaires', 'smart-backlink-manager'));
        }
        
        $backlink_id = intval($_POST['backlink_id']);
        
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_backlinks';
        
        $result = $wpdb->delete(
            $table,
            ['id' => $backlink_id],
            ['%d']
        );
        
        if ($result) {
            wp_send_json_success(__('Backlink supprimé avec succès', 'smart-backlink-manager'));
        } else {
            wp_send_json_error(__('Erreur lors de la suppression du backlink', 'smart-backlink-manager'));
        }
    }
}
