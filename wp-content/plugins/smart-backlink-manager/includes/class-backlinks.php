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
                <form id="sbm-backlink-form" class="sbm-ajax-form" data-action="sbm_add_backlink">
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
            
            <!-- Filtres -->
            <div class="sbm-filters">
                <select id="sbm-filter-status">
                    <option value=""><?php _e('Tous les statuts', 'smart-backlink-manager'); ?></option>
                    <option value="active"><?php _e('Actifs', 'smart-backlink-manager'); ?></option>
                    <option value="dead"><?php _e('Morts', 'smart-backlink-manager'); ?></option>
                    <option value="lost"><?php _e('Perdus', 'smart-backlink-manager'); ?></option>
                </select>
                
                <select id="sbm-filter-type">
                    <option value=""><?php _e('Tous les types', 'smart-backlink-manager'); ?></option>
                    <option value="annuaire"><?php _e('Annuaires', 'smart-backlink-manager'); ?></option>
                    <option value="partenaire"><?php _e('Partenaires', 'smart-backlink-manager'); ?></option>
                    <option value="presse"><?php _e('Presse', 'smart-backlink-manager'); ?></option>
                    <option value="guest_post"><?php _e('Guest posts', 'smart-backlink-manager'); ?></option>
                </select>
                
                <button class="button" id="sbm-export-backlinks">
                    <?php _e('Exporter', 'smart-backlink-manager'); ?>
                </button>
            </div>
            
            <!-- Tableau des backlinks -->
            <div class="sbm-backlinks-table-container">
                <table class="wp-list-table widefat fixed striped sbm-backlinks-table">
                    <thead>
                        <tr>
                            <th scope="col" class="manage-column"><?php _e('URL Source', 'smart-backlink-manager'); ?></th>
                            <th scope="col" class="manage-column"><?php _e('URL Cible', 'smart-backlink-manager'); ?></th>
                            <th scope="col" class="manage-column"><?php _e('Ancre', 'smart-backlink-manager'); ?></th>
                            <th scope="col" class="manage-column"><?php _e('Type', 'smart-backlink-manager'); ?></th>
                            <th scope="col" class="manage-column"><?php _e('Statut', 'smart-backlink-manager'); ?></th>
                            <th scope="col" class="manage-column"><?php _e('DA', 'smart-backlink-manager'); ?></th>
                            <th scope="col" class="manage-column"><?php _e('Actions', 'smart-backlink-manager'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="sbm-backlinks-list">
                        <?php $this->display_backlinks_list(); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
    
    private function display_backlinks_list(): void {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sbm_backlinks';
        $backlinks = $wpdb->get_results("SELECT * FROM $table ORDER BY date_added DESC LIMIT 50");
        
        if (empty($backlinks)) {
            echo '<tr><td colspan="7">' . __('Aucun backlink trouvé.', 'smart-backlink-manager') . '</td></tr>';
            return;
        }
        
        foreach ($backlinks as $backlink) {
            $status_class = $backlink->status === 'active' ? 'sbm-status-active' : 'sbm-status-inactive';
            $status_text = $backlink->status === 'active' ? __('Actif', 'smart-backlink-manager') : __('Inactif', 'smart-backlink-manager');
            
            echo '<tr>';
            echo '<td><a href="' . esc_url($backlink->source_url) . '" target="_blank">' . esc_html(parse_url($backlink->source_url, PHP_URL_HOST)) . '</a></td>';
            echo '<td><a href="' . esc_url($backlink->target_url) . '" target="_blank">' . esc_html(parse_url($backlink->target_url, PHP_URL_PATH)) . '</a></td>';
            echo '<td>' . esc_html($backlink->anchor_text) . '</td>';
            echo '<td>' . esc_html($backlink->backlink_type) . '</td>';
            echo '<td><span class="sbm-status ' . $status_class . '">' . $status_text . '</span></td>';
            echo '<td>' . ($backlink->domain_authority ?: '-') . '</td>';
            echo '<td>';
            echo '<button class="button button-small sbm-check-backlink" data-id="' . $backlink->id . '">' . __('Vérifier', 'smart-backlink-manager') . '</button> ';
            echo '<button class="button button-small sbm-delete-backlink" data-id="' . $backlink->id . '">' . __('Supprimer', 'smart-backlink-manager') . '</button>';
            echo '</td>';
            echo '</tr>';
        }
    }
    
    public function ajax_add_backlink(): void {
        check_ajax_referer('sbm_ajax_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Permission refusée', 'smart-backlink-manager'));
        }
        
        $source_url = esc_url_raw($_POST['source_url']);
        $target_url = esc_url_raw($_POST['target_url']);
        $anchor_text = sanitize_text_field($_POST['anchor_text']);
        $backlink_type = sanitize_text_field($_POST['backlink_type']);
        
        if (empty($source_url) || empty($target_url)) {
            wp_send_json_error(['message' => __('Veuillez remplir les URLs source et cible', 'smart-backlink-manager')]);
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
            wp_send_json_error(['message' => __('Ce backlink existe déjà', 'smart-backlink-manager')]);
        }
        
        // Insérer le nouveau backlink
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
        
        if ($result === false) {
            wp_send_json_error(['message' => __('Erreur lors de l\'ajout du backlink', 'smart-backlink-manager')]);
        }
        
        wp_send_json_success(['message' => __('Backlink ajouté avec succès', 'smart-backlink-manager')]);
    }
    
    public function ajax_check_backlink(): void {
        check_ajax_referer('sbm_ajax_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Permission refusée', 'smart-backlink-manager'));
        }
        
        $backlink_id = intval($_POST['backlink_id']);
        
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_backlinks';
        $backlink = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $backlink_id));
        
        if (!$backlink) {
            wp_send_json_error(['message' => __('Backlink non trouvé', 'smart-backlink-manager')]);
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
        
        wp_send_json_success([
            'message' => __('Backlink vérifié', 'smart-backlink-manager'),
            'status' => $status
        ]);
    }
    
    public function ajax_check_all_backlinks(): void {
        check_ajax_referer('sbm_ajax_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Permission refusée', 'smart-backlink-manager'));
        }
        
        $checked = $this->check_all_backlinks();
        
        wp_send_json_success([
            'message' => sprintf(
                __('%d backlinks vérifiés', 'smart-backlink-manager'),
                $checked
            )
        ]);
    }
    
    public function check_all_backlinks(): int {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sbm_backlinks';
        $backlinks = $wpdb->get_results("SELECT * FROM $table");
        $checked = 0;
        
        foreach ($backlinks as $backlink) {
            $response = wp_remote_get($backlink->source_url, ['timeout' => 10]);
            
            if (is_wp_error($response)) {
                $status = 'dead';
            } else {
                $body = wp_remote_retrieve_body($response);
                $status = (strpos($body, $backlink->target_url) !== false) ? 'active' : 'lost';
            }
            
            $wpdb->update(
                $table,
                ['status' => $status, 'last_checked' => current_time('mysql')],
                ['id' => $backlink->id],
                ['%s', '%s'],
                ['%d']
            );
            
            $checked++;
            
            // Pause pour éviter de surcharger les serveurs
            usleep(500000); // 0.5 seconde
        }
        
        return $checked;
    }
    
    public function ajax_delete_backlink(): void {
        check_ajax_referer('sbm_ajax_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Permission refusée', 'smart-backlink-manager'));
        }
        
        $backlink_id = intval($_POST['backlink_id']);
        
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_backlinks';
        
        $result = $wpdb->delete(
            $table,
            ['id' => $backlink_id],
            ['%d']
        );
        
        if ($result === false) {
            wp_send_json_error(['message' => __('Erreur lors de la suppression du backlink', 'smart-backlink-manager')]);
        }
        
        wp_send_json_success(['message' => __('Backlink supprimé avec succès', 'smart-backlink-manager')]);
    }
}
