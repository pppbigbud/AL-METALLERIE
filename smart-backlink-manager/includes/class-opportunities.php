<?php
/**
 * Opportunities class for Smart Backlink Manager
 */

if (!defined('ABSPATH')) {
    exit;
}

class SBM_Opportunities {
    
    public function init(): void {
        // Hooks AJAX
        add_action('wp_ajax_sbm_add_opportunity', [$this, 'ajax_add_opportunity']);
        add_action('wp_ajax_sbm_update_opportunity', [$this, 'ajax_update_opportunity']);
        add_action('wp_ajax_sbm_delete_opportunity', [$this, 'ajax_delete_opportunity']);
        
        // Hook pour la recherche automatique d'opportunités
        add_action('sbm_find_opportunities_cron', [$this, 'find_local_opportunities']);
    }
    
    public function render_page(): void {
        ?>
        <div class="wrap sbm-opportunities">
            <h1 class="wp-heading-inline">
                <?php _e('Opportunités de Backlinks', 'smart-backlink-manager'); ?>
            </h1>
            <a href="#" class="page-title-action" id="sbm-add-opportunity-btn">
                <?php _e('Ajouter une opportunité', 'smart-backlink-manager'); ?>
            </a>
            <a href="#" class="page-title-action" id="sbm-find-local-btn">
                <span class="dashicons dashicons-search"></span>
                <?php _e('Rechercher localement', 'smart-backlink-manager'); ?>
            </a>
            <hr class="wp-header-end">
            
            <!-- Formulaire d'ajout -->
            <div id="sbm-add-opportunity-form" class="sbm-form-card" style="display: none;">
                <h2><?php _e('Ajouter une opportunité', 'smart-backlink-manager'); ?></h2>
                <form id="sbm-opportunity-form" class="sbm-ajax-form" data-action="sbm_add_opportunity">
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="site_name"><?php _e('Nom du site', 'smart-backlink-manager'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="site_name" id="site_name" class="regular-text" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="url"><?php _e('URL', 'smart-backlink-manager'); ?></label>
                            </th>
                            <td>
                                <input type="url" name="url" id="url" class="regular-text" required>
                                <p class="description">
                                    <?php _e('URL du site où vous pourriez obtenir un backlink', 'smart-backlink-manager'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="type"><?php _e('Type d\'opportunité', 'smart-backlink-manager'); ?></label>
                            </th>
                            <td>
                                <select name="type" id="type">
                                    <option value="autre"><?php _e('Autre', 'smart-backlink-manager'); ?></option>
                                    <option value="annuaire_local"><?php _e('Annuaire local', 'smart-backlink-manager'); ?></option>
                                    <option value="partenaire_local"><?php _e('Partenaire local', 'smart-backlink-manager'); ?></option>
                                    <option value="média_local"><?php _e('Média local', 'smart-backlink-manager'); ?></option>
                                    <option value="association"><?php _e('Association', 'smart-backlink-manager'); ?></option>
                                    <option value="fournisseur"><?php _e('Fournisseur', 'smart-backlink-manager'); ?></option>
                                    <option value="client"><?php _e('Client', 'smart-backlink-manager'); ?></option>
                                    <option value="guest_post"><?php _e('Guest post', 'smart-backlink-manager'); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="contact_info"><?php _e('Informations de contact', 'smart-backlink-manager'); ?></label>
                            </th>
                            <td>
                                <textarea name="contact_info" id="contact_info" rows="3" class="large-text"></textarea>
                                <p class="description">
                                    <?php _e('Email, téléphone, nom du contact...', 'smart-backlink-manager'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="notes"><?php _e('Notes', 'smart-backlink-manager'); ?></label>
                            </th>
                            <td>
                                <textarea name="notes" id="notes" rows="4" class="large-text"></textarea>
                                <p class="description">
                                    <?php _e('Détails sur l\'opportunité, comment contacter, etc.', 'smart-backlink-manager'); ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                    <p class="submit">
                        <button type="submit" class="button button-primary">
                            <?php _e('Ajouter l\'opportunité', 'smart-backlink-manager'); ?>
                        </button>
                        <button type="button" class="button" id="sbm-cancel-add">
                            <?php _e('Annuler', 'smart-backlink-manager'); ?>
                        </button>
                    </p>
                </form>
            </div>
            
            <!-- Statistiques -->
            <div class="sbm-stats-row">
                <div class="sbm-stat-box sbm-stat-to-contact">
                    <span class="sbm-stat-number"><?php echo $this->get_opportunities_count('to_contact'); ?></span>
                    <span class="sbm-stat-label"><?php _e('À contacter', 'smart-backlink-manager'); ?></span>
                </div>
                <div class="sbm-stat-box sbm-stat-in-progress">
                    <span class="sbm-stat-number"><?php echo $this->get_opportunities_count('in_progress'); ?></span>
                    <span class="sbm-stat-label"><?php _e('En cours', 'smart-backlink-manager'); ?></span>
                </div>
                <div class="sbm-stat-box sbm-stat-obtained">
                    <span class="sbm-stat-number"><?php echo $this->get_opportunities_count('obtained'); ?></span>
                    <span class="sbm-stat-label"><?php _e('Obtenus', 'smart-backlink-manager'); ?></span>
                </div>
                <div class="sbm-stat-box sbm-stat-total">
                    <span class="sbm-stat-number"><?php echo $this->get_opportunities_count(); ?></span>
                    <span class="sbm-stat-label"><?php _e('Total', 'smart-backlink-manager'); ?></span>
                </div>
            </div>
            
            <!-- Filtres -->
            <div class="sbm-filters">
                <select id="sbm-filter-status">
                    <option value=""><?php _e('Tous les statuts', 'smart-backlink-manager'); ?></option>
                    <option value="to_contact"><?php _e('À contacter', 'smart-backlink-manager'); ?></option>
                    <option value="in_progress"><?php _e('En cours', 'smart-backlink-manager'); ?></option>
                    <option value="obtained"><?php _e('Obtenus', 'smart-backlink-manager'); ?></option>
                    <option value="refused"><?php _e('Refusés', 'smart-backlink-manager'); ?></option>
                </select>
                
                <select id="sbm-filter-type">
                    <option value=""><?php _e('Tous les types', 'smart-backlink-manager'); ?></option>
                    <option value="annuaire_local"><?php _e('Annuaires locaux', 'smart-backlink-manager'); ?></option>
                    <option value="partenaire_local"><?php _e('Partenaires locaux', 'smart-backlink-manager'); ?></option>
                    <option value="média_local"><?php _e('Médias locaux', 'smart-backlink-manager'); ?></option>
                    <option value="association"><?php _e('Associations', 'smart-backlink-manager'); ?></option>
                    <option value="autre"><?php _e('Autres', 'smart-backlink-manager'); ?></option>
                </select>
                
                <input type="text" id="sbm-search-opportunities" placeholder="<?php _e('Rechercher...', 'smart-backlink-manager'); ?>">
                
                <button class="button" id="sbm-export-opportunities">
                    <?php _e('Exporter', 'smart-backlink-manager'); ?>
                </button>
            </div>
            
            <!-- Tableau des opportunités -->
            <div class="sbm-opportunities-table-container">
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th scope="col" class="manage-column column-primary"><?php _e('Site', 'smart-backlink-manager'); ?></th>
                            <th scope="col"><?php _e('Type', 'smart-backlink-manager'); ?></th>
                            <th scope="col"><?php _e('Statut', 'smart-backlink-manager'); ?></th>
                            <th scope="col"><?php _e('Contact', 'smart-backlink-manager'); ?></th>
                            <th scope="col"><?php _e('Ajouté', 'smart-backlink-manager'); ?></th>
                            <th scope="col"><?php _e('Actions', 'smart-backlink-manager'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="sbm-opportunities-list">
                        <?php echo $this->get_opportunities_rows(); ?>
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <div class="tablenav bottom">
                    <div class="tablenav-pages" id="sbm-pagination">
                        <?php echo $this->get_pagination(); ?>
                    </div>
                </div>
            </div>
            
            <!-- Conseils locaux -->
            <div class="sbm-tips-section">
                <h2><?php _e('Opportunités locales pour AL Métallerie', 'smart-backlink-manager'); ?></h2>
                <div class="sbm-tips-grid">
                    <div class="sbm-tip-card">
                        <h3><?php _e('Annuaires de Clermont-Ferrand', 'smart-backlink-manager'); ?></h3>
                        <p><?php _e('Inscrivez-vous dans les annuaires locaux spécialisés dans l\'artisanat et le bâtiment.', 'smart-backlink-manager'); ?></p>
                    </div>
                    <div class="sbm-tip-card">
                        <h3><?php _e('Partenaires fournisseurs', 'smart-backlink-manager'); ?></h3>
                        <p><?php _e('Contactez vos fournisseurs pour obtenir des liens sur leurs pages "Partenaires".', 'smart-backlink-manager'); ?></p>
                    </div>
                    <div class="sbm-tip-card">
                        <h3><?php _e('Médias locaux', 'smart-backlink-manager'); ?></h3>
                        <p><?php _e('La Montagne, France Bleu Auvergne... Proposez des témoignages ou expertises.', 'smart-backlink-manager'); ?></p>
                    </div>
                    <div class="sbm-tip-card">
                        <h3><?php _e('Associations locales', 'smart-backlink-manager'); ?></h3>
                        <p><?php _e('Bâtiment de France, CAPEB, Chambre de Métiers... Devenez membre actif.', 'smart-backlink-manager'); ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            // Afficher/masquer le formulaire d'ajout
            $('#sbm-add-opportunity-btn').on('click', function(e) {
                e.preventDefault();
                $('#sbm-add-opportunity-form').slideToggle();
            });
            
            $('#sbm-cancel-add').on('click', function() {
                $('#sbm-add-opportunity-form').slideUp();
                $('#sbm-opportunity-form')[0].reset();
            });
            
            // Soumission du formulaire
            $('#sbm-opportunity-form').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                
                $.post(ajaxurl, formData + '&action=sbm_add_opportunity&_ajax_nonce=<?php echo wp_create_nonce('sbm_nonce'); ?>', function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data || '<?php _e('Erreur lors de l\'ajout de l\'opportunité', 'smart-backlink-manager'); ?>');
                    }
                });
            });
            
            // Recherche locale
            $('#sbm-find-local-btn').on('click', function(e) {
                e.preventDefault();
                if (!confirm('<?php _e('Rechercher des opportunités locales dans la région Clermont-Ferrand?', 'smart-backlink-manager'); ?>')) {
                    return;
                }
                
                var $btn = $(this);
                $btn.prop('disabled', true);
                
                $.post(ajaxurl, {
                    action: 'sbm_find_local_opportunities',
                    _ajax_nonce: '<?php echo wp_create_nonce('sbm_nonce'); ?>'
                }, function(response) {
                    $btn.prop('disabled', false);
                    if (response.success) {
                        alert(response.data.message);
                        location.reload();
                    } else {
                        alert(response.data || '<?php _e('Erreur lors de la recherche', 'smart-backlink-manager'); ?>');
                    }
                });
            });
            
            // Filtrage
            $('#sbm-filter-status, #sbm-filter-type, #sbm-search-opportunities').on('change keyup', function() {
                sbmFilterOpportunities();
            });
            
            function sbmFilterOpportunities() {
                var status = $('#sbm-filter-status').val();
                var type = $('#sbm-filter-type').val();
                var search = $('#sbm-search-opportunities').val().toLowerCase();
                
                $('#sbm-opportunities-list tr').each(function() {
                    var show = true;
                    
                    if (status && $(this).data('status') != status) show = false;
                    if (type && $(this).data('type') != type) show = false;
                    if (search && $(this).text().toLowerCase().indexOf(search) === -1) show = false;
                    
                    $(this).toggle(show);
                });
            }
            
            // Export
            $('#sbm-export-opportunities').on('click', function() {
                var params = new URLSearchParams({
                    action: 'sbm_export_opportunities',
                    _ajax_nonce: '<?php echo wp_create_nonce('sbm_nonce'); ?>'
                });
                
                window.location.href = ajaxurl + '?' + params.toString();
            });
        });
        </script>
        <?php
    }
    
    private function get_opportunities_count($status = ''): int {
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_opportunities';
        
        if ($status) {
            return intval($wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE status = %s", $status)));
        }
        
        return intval($wpdb->get_var("SELECT COUNT(*) FROM $table"));
    }
    
    private function get_opportunities_rows(): string {
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_opportunities';
        
        $page = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
        $per_page = 20;
        $offset = ($page - 1) * $per_page;
        
        $where = '';
        if (isset($_GET['status']) && $_GET['status']) {
            $where = $wpdb->prepare("WHERE status = %s", $_GET['status']);
        }
        
        $opportunities = $wpdb->get_results("
            SELECT * FROM $table 
            $where
            ORDER BY date_added DESC 
            LIMIT $per_page OFFSET $offset
        ");
        
        $rows = '';
        foreach ($opportunities as $opp) {
            $status_html = $this->get_status_html($opp->status);
            
            $rows .= sprintf(
                '<tr data-status="%s" data-type="%s">
                    <td>
                        <strong><a href="%s" target="_blank">%s</a></strong>
                        %s
                    </td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>
                        <button class="button button-small sbm-edit-opportunity" data-id="%d">
                            %s
                        </button>
                        <button class="button button-small sbm-delete-opportunity" data-id="%d">
                            %s
                        </button>
                    </td>
                </tr>',
                $opp->status,
                $opp->type,
                esc_url($opp->url),
                esc_html($opp->site_name),
                $opp->contact_info ? '<br><small>' . esc_html($opp->contact_info) . '</small>' : '',
                $this->get_type_label($opp->type),
                $status_html,
                esc_html($opp->contact_info ?: '-'),
                date('d/m/Y', strtotime($opp->date_added)),
                $opp->id,
                __('Modifier', 'smart-backlink-manager'),
                $opp->id,
                __('Supprimer', 'smart-backlink-manager')
            );
        }
        
        if (empty($rows)) {
            $rows = '<tr><td colspan="6">' . __('Aucune opportunité trouvée', 'smart-backlink-manager') . '</td></tr>';
        }
        
        return $rows;
    }
    
    private function get_status_html(string $status): string {
        $labels = [
            'to_contact' => '<span class="sbm-status-to-contact">' . __('À contacter', 'smart-backlink-manager') . '</span>',
            'in_progress' => '<span class="sbm-status-in-progress">' . __('En cours', 'smart-backlink-manager') . '</span>',
            'obtained' => '<span class="sbm-status-obtained">' . __('Obtenu', 'smart-backlink-manager') . '</span>',
            'refused' => '<span class="sbm-status-refused">' . __('Refusé', 'smart-backlink-manager') . '</span>'
        ];
        
        return $labels[$status] ?? $status;
    }
    
    private function get_type_label(string $type): string {
        $labels = [
            'annuaire_local' => __('Annuaire local', 'smart-backlink-manager'),
            'partenaire_local' => __('Partenaire local', 'smart-backlink-manager'),
            'média_local' => __('Média local', 'smart-backlink-manager'),
            'association' => __('Association', 'smart-backlink-manager'),
            'fournisseur' => __('Fournisseur', 'smart-backlink-manager'),
            'client' => __('Client', 'smart-backlink-manager'),
            'guest_post' => __('Guest post', 'smart-backlink-manager'),
            'autre' => __('Autre', 'smart-backlink-manager')
        ];
        
        return $labels[$type] ?? $type;
    }
    
    private function get_pagination(): string {
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_opportunities';
        
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
    
    public function ajax_add_opportunity(): void {
        check_ajax_referer('sbm_nonce', '_ajax_nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Vous n\'avez pas les permissions nécessaires', 'smart-backlink-manager'));
        }
        
        $site_name = sanitize_text_field($_POST['site_name']);
        $url = esc_url_raw($_POST['url']);
        $type = sanitize_text_field($_POST['type']);
        $contact_info = sanitize_textarea_field($_POST['contact_info']);
        $notes = sanitize_textarea_field($_POST['notes']);
        
        if (!$site_name || !$url) {
            wp_send_json_error(__('Le nom du site et l\'URL sont requis', 'smart-backlink-manager'));
        }
        
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_opportunities';
        
        $result = $wpdb->insert(
            $table,
            [
                'site_name' => $site_name,
                'url' => $url,
                'type' => $type,
                'contact_info' => $contact_info,
                'notes' => $notes,
                'status' => 'to_contact',
                'date_added' => current_time('mysql')
            ],
            ['%s', '%s', '%s', '%s', '%s', '%s', '%s']
        );
        
        if ($result) {
            wp_send_json_success(__('Opportunité ajoutée avec succès', 'smart-backlink-manager'));
        } else {
            wp_send_json_error(__('Erreur lors de l\'ajout de l\'opportunité', 'smart-backlink-manager'));
        }
    }
    
    public function ajax_update_opportunity(): void {
        check_ajax_referer('sbm_nonce', '_ajax_nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Vous n\'avez pas les permissions nécessaires', 'smart-backlink-manager'));
        }
        
        $id = intval($_POST['id']);
        $status = sanitize_text_field($_POST['status']);
        $notes = sanitize_textarea_field($_POST['notes']);
        
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
            wp_send_json_success(__('Opportunité mise à jour', 'smart-backlink-manager'));
        } else {
            wp_send_json_error(__('Erreur lors de la mise à jour', 'smart-backlink-manager'));
        }
    }
    
    public function ajax_delete_opportunity(): void {
        check_ajax_referer('sbm_nonce', '_ajax_nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Vous n\'avez pas les permissions nécessaires', 'smart-backlink-manager'));
        }
        
        $id = intval($_POST['id']);
        
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_opportunities';
        
        $result = $wpdb->delete(
            $table,
            ['id' => $id],
            ['%d']
        );
        
        if ($result) {
            wp_send_json_success(__('Opportunité supprimée avec succès', 'smart-backlink-manager'));
        } else {
            wp_send_json_error(__('Erreur lors de la suppression', 'smart-backlink-manager'));
        }
    }
    
    public function find_local_opportunities(): int {
        $opportunities = [
            [
                'site_name' => 'Annuaire des artisans Clermont-Ferrand',
                'url' => 'https://www.annuaire-artisanat-clermont.fr',
                'type' => 'annuaire_local',
                'contact_info' => 'contact@annuaire-artisanat-clermont.fr',
                'notes' => 'Annuaire spécialisé dans les artisans de la métallerie à Clermont-Ferrand'
            ],
            [
                'site_name' => 'CAPEB Puy-de-Dôme',
                'url' => 'https://www.capeb-63.fr',
                'type' => 'association',
                'contact_info' => 'contact@capeb-63.fr',
                'notes' => 'Chambre des métiers de l\'artisanat du Puy-de-Dôme'
            ],
            [
                'site_name' => 'La Montagne - Économie',
                'url' => 'https://www.lamontagne.fr/economie',
                'type' => 'média_local',
                'contact_info' => 'redaction.economie@lamontagne.fr',
                'notes' => 'Journal local - proposer des témoignages sur des projets de métallerie'
            ],
            [
                'site_name' => 'Mairie de Clermont-Ferrand',
                'url' => 'https://www.clermont-ferrand.fr',
                'type' => 'partenaire_local',
                'contact_info' => 'mairie@clermont-ferrand.fr',
                'notes' => 'Projets municipaux - possibilité de partenariat pour les travaux publics'
            ],
            [
                'site_name' => 'PageJaunes - Métalliers Auvergne',
                'url' => 'https://www.pagesjaunes.fr/recherche/auvergne/metallier',
                'type' => 'annuaire_local',
                'contact_info' => '',
                'notes' => 'Optimiser la fiche PageJaunes avec des photos de réalisations'
            ]
        ];
        
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_opportunities';
        $added = 0;
        
        foreach ($opportunities as $opp) {
            // Vérifier si déjà existant
            $exists = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM $table WHERE url = %s",
                $opp['url']
            ));
            
            if (!$exists) {
                $wpdb->insert(
                    $table,
                    [
                        'site_name' => $opp['site_name'],
                        'url' => $opp['url'],
                        'type' => $opp['type'],
                        'contact_info' => $opp['contact_info'],
                        'notes' => $opp['notes'],
                        'status' => 'to_contact',
                        'date_added' => current_time('mysql')
                    ],
                    ['%s', '%s', '%s', '%s', '%s', '%s', '%s']
                );
                $added++;
            }
        }
        
        return $added;
    }
}
