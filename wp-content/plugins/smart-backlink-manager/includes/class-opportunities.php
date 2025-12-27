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
            
            <!-- Filtres -->
            <div class="sbm-filters">
                <select id="sbm-filter-status">
                    <option value=""><?php _e('Tous les statuts', 'smart-backlink-manager'); ?></option>
                    <option value="new"><?php _e('Nouveau', 'smart-backlink-manager'); ?></option>
                    <option value="to_contact"><?php _e('À contacter', 'smart-backlink-manager'); ?></option>
                    <option value="in_progress"><?php _e('En cours', 'smart-backlink-manager'); ?></option>
                    <option value="obtained"><?php _e('Obtenu', 'smart-backlink-manager'); ?></option>
                    <option value="refused"><?php _e('Refusé', 'smart-backlink-manager'); ?></option>
                </select>
                
                <select id="sbm-filter-priority">
                    <option value=""><?php _e('Toutes les priorités', 'smart-backlink-manager'); ?></option>
                    <option value="high"><?php _e('Haute', 'smart-backlink-manager'); ?></option>
                    <option value="medium"><?php _e('Moyenne', 'smart-backlink-manager'); ?></option>
                    <option value="low"><?php _e('Basse', 'smart-backlink-manager'); ?></option>
                </select>
                
                <button class="button" id="sbm-export-opportunities">
                    <?php _e('Exporter', 'smart-backlink-manager'); ?>
                </button>
            </div>
            
            <!-- Tableau des opportunités -->
            <div class="sbm-opportunities-table-container">
                <table class="wp-list-table widefat fixed striped sbm-opportunities-table">
                    <thead>
                        <tr>
                            <th scope="col" class="manage-column"><?php _e('Site', 'smart-backlink-manager'); ?></th>
                            <th scope="col" class="manage-column"><?php _e('Type', 'smart-backlink-manager'); ?></th>
                            <th scope="col" class="manage-column"><?php _e('Contact', 'smart-backlink-manager'); ?></th>
                            <th scope="col" class="manage-column"><?php _e('Statut', 'smart-backlink-manager'); ?></th>
                            <th scope="col" class="manage-column"><?php _e('Priorité', 'smart-backlink-manager'); ?></th>
                            <th scope="col" class="manage-column"><?php _e('Actions', 'smart-backlink-manager'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="sbm-opportunities-list">
                        <?php $this->display_opportunities_list(); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
    
    private function display_opportunities_list(): void {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sbm_opportunities';
        $opportunities = $wpdb->get_results("SELECT * FROM $table ORDER BY priority DESC, created_at DESC LIMIT 50");
        
        if (empty($opportunities)) {
            echo '<tr><td colspan="6">' . __('Aucune opportunité trouvée.', 'smart-backlink-manager') . '</td></tr>';
            return;
        }
        
        foreach ($opportunities as $opportunity) {
            $status_class = 'sbm-status-' . $opportunity->status;
            $priority_class = 'sbm-priority-' . $opportunity->priority;
            
            echo '<tr>';
            echo '<td><a href="' . esc_url($opportunity->url) . '" target="_blank">' . esc_html($opportunity->site_name) . '</a></td>';
            echo '<td>' . esc_html($opportunity->type) . '</td>';
            echo '<td>' . esc_html($opportunity->contact_info) . '</td>';
            echo '<td><span class="sbm-status ' . $status_class . '">' . $this->get_status_label($opportunity->status) . '</span></td>';
            echo '<td><span class="sbm-priority ' . $priority_class . '">' . $this->get_priority_label($opportunity->priority) . '</span></td>';
            echo '<td>';
            echo '<button class="button button-small sbm-edit-opportunity" data-id="' . $opportunity->id . '">' . __('Modifier', 'smart-backlink-manager') . '</button> ';
            echo '<button class="button button-small sbm-delete-opportunity" data-id="' . $opportunity->id . '">' . __('Supprimer', 'smart-backlink-manager') . '</button>';
            echo '</td>';
            echo '</tr>';
        }
    }
    
    private function get_status_label(string $status): string {
        $labels = [
            'new' => __('Nouveau', 'smart-backlink-manager'),
            'to_contact' => __('À contacter', 'smart-backlink-manager'),
            'in_progress' => __('En cours', 'smart-backlink-manager'),
            'obtained' => __('Obtenu', 'smart-backlink-manager'),
            'refused' => __('Refusé', 'smart-backlink-manager')
        ];
        
        return $labels[$status] ?? $status;
    }
    
    private function get_priority_label(string $priority): string {
        $labels = [
            'high' => __('Haute', 'smart-backlink-manager'),
            'medium' => __('Moyenne', 'smart-backlink-manager'),
            'low' => __('Basse', 'smart-backlink-manager')
        ];
        
        return $labels[$priority] ?? $priority;
    }
    
    public function ajax_add_opportunity(): void {
        check_ajax_referer('sbm_ajax_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Permission refusée', 'smart-backlink-manager'));
        }
        
        $site_name = sanitize_text_field($_POST['site_name']);
        $url = esc_url_raw($_POST['url']);
        $type = sanitize_text_field($_POST['type']);
        $contact_info = sanitize_textarea_field($_POST['contact_info']);
        $notes = sanitize_textarea_field($_POST['notes']);
        
        if (empty($site_name) || empty($url)) {
            wp_send_json_error(['message' => __('Veuillez remplir le nom du site et l\'URL', 'smart-backlink-manager')]);
        }
        
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_opportunities';
        
        // Insérer la nouvelle opportunité
        $result = $wpdb->insert(
            $table,
            [
                'site_name' => $site_name,
                'url' => $url,
                'type' => $type,
                'contact_info' => $contact_info,
                'notes' => $notes,
                'status' => 'new',
                'priority' => 'medium',
                'created_at' => current_time('mysql')
            ],
            ['%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s']
        );
        
        if ($result === false) {
            wp_send_json_error(['message' => __('Erreur lors de l\'ajout de l\'opportunité', 'smart-backlink-manager')]);
        }
        
        wp_send_json_success(['message' => __('Opportunité ajoutée avec succès', 'smart-backlink-manager')]);
    }
    
    public function ajax_update_opportunity(): void {
        check_ajax_referer('sbm_ajax_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Permission refusée', 'smart-backlink-manager'));
        }
        
        $opportunity_id = intval($_POST['opportunity_id']);
        $status = sanitize_text_field($_POST['status']);
        $priority = sanitize_text_field($_POST['priority']);
        
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_opportunities';
        
        $result = $wpdb->update(
            $table,
            [
                'status' => $status,
                'priority' => $priority,
                'updated_at' => current_time('mysql')
            ],
            ['id' => $opportunity_id],
            ['%s', '%s', '%s'],
            ['%d']
        );
        
        if ($result === false) {
            wp_send_json_error(['message' => __('Erreur lors de la mise à jour', 'smart-backlink-manager')]);
        }
        
        wp_send_json_success(['message' => __('Opportunité mise à jour', 'smart-backlink-manager')]);
    }
    
    public function ajax_delete_opportunity(): void {
        check_ajax_referer('sbm_ajax_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Permission refusée', 'smart-backlink-manager'));
        }
        
        $opportunity_id = intval($_POST['opportunity_id']);
        
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_opportunities';
        
        $result = $wpdb->delete(
            $table,
            ['id' => $opportunity_id],
            ['%d']
        );
        
        if ($result === false) {
            wp_send_json_error(['message' => __('Erreur lors de la suppression', 'smart-backlink-manager')]);
        }
        
        wp_send_json_success(['message' => __('Opportunité supprimée', 'smart-backlink-manager')]);
    }
    
    public function find_local_opportunities(): void {
        // Implémentation de la recherche automatique d'opportunités locales
        // Utiliser Google Places API ou autres sources pour trouver des entreprises locales
    }
}
