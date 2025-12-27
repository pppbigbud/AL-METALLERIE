<?php
/**
 * Internal Links class for Smart Backlink Manager
 */

if (!defined('ABSPATH')) {
    exit;
}

class SBM_Internal_Links {
    
    public function init(): void {
        // Hooks AJAX
        add_action('wp_ajax_sbm_add_internal_link', [$this, 'ajax_add_internal_link']);
        add_action('wp_ajax_sbm_delete_internal_link', [$this, 'ajax_delete_internal_link']);
        add_action('wp_ajax_sbm_bulk_import_links', [$this, 'ajax_bulk_import_links']);
    }
    
    public function render_page(): void {
        ?>
        <div class="wrap sbm-internal-links">
            <h1 class="wp-heading-inline">
                <?php _e('Liens Internes', 'smart-backlink-manager'); ?>
            </h1>
            <a href="#" class="page-title-action" id="sbm-add-link-btn">
                <?php _e('Ajouter un lien', 'smart-backlink-manager'); ?>
            </a>
            <hr class="wp-header-end">
            
            <!-- Formulaire d'ajout rapide -->
            <div id="sbm-add-link-form" class="sbm-form-card" style="display: none;">
                <h2><?php _e('Ajouter un lien interne', 'smart-backlink-manager'); ?></h2>
                <form id="sbm-internal-link-form" class="sbm-ajax-form" data-action="sbm_add_internal_link">
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="from_post"><?php _e('Page source', 'smart-backlink-manager'); ?></label>
                            </th>
                            <td>
                                <select name="from_post_id" id="from_post" required>
                                    <option value=""><?php _e('Sélectionner une page', 'smart-backlink-manager'); ?></option>
                                    <?php echo $this->get_posts_options(); ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="to_post"><?php _e('Page cible', 'smart-backlink-manager'); ?></label>
                            </th>
                            <td>
                                <select name="to_post_id" id="to_post" required>
                                    <option value=""><?php _e('Sélectionner une page', 'smart-backlink-manager'); ?></option>
                                    <?php echo $this->get_posts_options(); ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="anchor_text"><?php _e('Texte d\'ancrage', 'smart-backlink-manager'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="anchor_text" id="anchor_text" class="regular-text">
                                <p class="description">
                                    <?php _e('Le texte cliquable du lien. Si vide, utilisera le titre de la page cible.', 'smart-backlink-manager'); ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                    <p class="submit">
                        <button type="submit" class="button button-primary">
                            <?php _e('Ajouter le lien', 'smart-backlink-manager'); ?>
                        </button>
                        <button type="button" class="button" id="sbm-cancel-add">
                            <?php _e('Annuler', 'smart-backlink-manager'); ?>
                        </button>
                    </p>
                </form>
            </div>
            
            <!-- Filtres et recherche -->
            <div class="sbm-filters">
                <select id="sbm-filter-source">
                    <option value=""><?php _e('Toutes les pages sources', 'smart-backlink-manager'); ?></option>
                    <?php echo $this->get_posts_options('filter-source'); ?>
                </select>
                
                <select id="sbm-filter-target">
                    <option value=""><?php _e('Toutes les pages cibles', 'smart-backlink-manager'); ?></option>
                    <?php echo $this->get_posts_options('filter-target'); ?>
                </select>
                
                <input type="text" id="sbm-search-links" placeholder="<?php _e('Rechercher un lien...', 'smart-backlink-manager'); ?>">
                
                <button class="button" id="sbm-export-links">
                    <?php _e('Exporter', 'smart-backlink-manager'); ?>
                </button>
            </div>
            
            <!-- Tableau des liens -->
            <div class="sbm-links-table-container">
                <table class="wp-list-table widefat fixed striped sbm-links-table">
                    <thead>
                        <tr>
                            <th scope="col" class="manage-column"><?php _e('Page Source', 'smart-backlink-manager'); ?></th>
                            <th scope="col" class="manage-column"><?php _e('Page Cible', 'smart-backlink-manager'); ?></th>
                            <th scope="col" class="manage-column"><?php _e('Texte d\'ancrage', 'smart-backlink-manager'); ?></th>
                            <th scope="col" class="manage-column"><?php _e('Date d\'ajout', 'smart-backlink-manager'); ?></th>
                            <th scope="col" class="manage-column"><?php _e('Actions', 'smart-backlink-manager'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="sbm-links-list">
                        <?php $this->display_links_list(); ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Import en masse -->
            <div class="sbm-bulk-import">
                <h2><?php _e('Importation en masse', 'smart-backlink-manager'); ?></h2>
                <p><?php _e('Importez des liens internes depuis un fichier CSV.', 'smart-backlink-manager'); ?></p>
                
                <form id="sbm-import-form" enctype="multipart/form-data">
                    <p>
                        <input type="file" name="import_file" accept=".csv" required>
                    </p>
                    <p class="description">
                        <?php _e('Format CSV : URL source, URL cible, Texte d\'ancrage', 'smart-backlink-manager'); ?>
                    </p>
                    <p>
                        <button type="submit" class="button button-secondary">
                            <?php _e('Importer le fichier', 'smart-backlink-manager'); ?>
                        </button>
                    </p>
                </form>
            </div>
        </div>
        <?php
    }
    
    private function get_posts_options(string $field_id = ''): string {
        $posts = get_posts([
            'post_type' => ['page', 'post', 'realisation'],
            'post_status' => 'publish',
            'numberposts' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ]);
        
        $options = '';
        foreach ($posts as $post) {
            $options .= sprintf(
                '<option value="%d">%s</option>',
                $post->ID,
                esc_html($post->post_title)
            );
        }
        
        return $options;
    }
    
    private function display_links_list(): void {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sbm_internal_links';
        $links = $wpdb->get_results("SELECT * FROM $table ORDER BY date_added DESC LIMIT 50");
        
        if (empty($links)) {
            echo '<tr><td colspan="5">' . __('Aucun lien interne trouvé.', 'smart-backlink-manager') . '</td></tr>';
            return;
        }
        
        foreach ($links as $link) {
            $source_post = get_post($link->from_post_id);
            $target_post = get_post($link->to_post_id);
            
            echo '<tr>';
            echo '<td>' . ($source_post ? '<a href="' . get_edit_post_link($source_post->ID) . '">' . esc_html($source_post->post_title) . '</a>' : __('Page supprimée', 'smart-backlink-manager')) . '</td>';
            echo '<td>' . ($target_post ? '<a href="' . get_edit_post_link($target_post->ID) . '">' . esc_html($target_post->post_title) . '</a>' : __('Page supprimée', 'smart-backlink-manager')) . '</td>';
            echo '<td>' . esc_html($link->anchor_text) . '</td>';
            echo '<td>' . date_i18n(get_option('date_format'), strtotime($link->date_added)) . '</td>';
            echo '<td>';
            echo '<button class="button button-small sbm-delete-link" data-id="' . $link->id . '">' . __('Supprimer', 'smart-backlink-manager') . '</button>';
            echo '</td>';
            echo '</tr>';
        }
    }
    
    public function ajax_add_internal_link(): void {
        check_ajax_referer('sbm_ajax_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Permission refusée', 'smart-backlink-manager'));
        }
        
        $from_post_id = intval($_POST['from_post_id']);
        $to_post_id = intval($_POST['to_post_id']);
        $anchor_text = sanitize_text_field($_POST['anchor_text']);
        
        if (empty($from_post_id) || empty($to_post_id)) {
            wp_send_json_error(['message' => __('Veuillez sélectionner les pages source et cible', 'smart-backlink-manager')]);
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
            wp_send_json_error(['message' => __('Ce lien existe déjà', 'smart-backlink-manager')]);
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
            wp_send_json_error(['message' => __('Erreur lors de l\'ajout du lien', 'smart-backlink-manager')]);
        }
        
        wp_send_json_success(['message' => __('Lien interne ajouté avec succès', 'smart-backlink-manager')]);
    }
    
    public function ajax_delete_internal_link(): void {
        check_ajax_referer('sbm_ajax_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Permission refusée', 'smart-backlink-manager'));
        }
        
        $link_id = intval($_POST['link_id']);
        
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_internal_links';
        
        $result = $wpdb->delete(
            $table,
            ['id' => $link_id],
            ['%d']
        );
        
        if ($result === false) {
            wp_send_json_error(['message' => __('Erreur lors de la suppression du lien', 'smart-backlink-manager')]);
        }
        
        wp_send_json_success(['message' => __('Lien supprimé avec succès', 'smart-backlink-manager')]);
    }
    
    public function ajax_bulk_import_links(): void {
        check_ajax_referer('sbm_ajax_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Permission refusée', 'smart-backlink-manager'));
        }
        
        if (!isset($_FILES['import_file'])) {
            wp_send_json_error(['message' => __('Veuillez sélectionner un fichier', 'smart-backlink-manager')]);
        }
        
        $file = $_FILES['import_file']['tmp_name'];
        $handle = fopen($file, 'r');
        
        if (!$handle) {
            wp_send_json_error(['message' => __('Impossible de lire le fichier', 'smart-backlink-manager')]);
        }
        
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_internal_links';
        $imported = 0;
        $skipped = 0;
        
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            if (count($data) < 2) continue;
            
            $source_url = trim($data[0]);
            $target_url = trim($data[1]);
            $anchor_text = isset($data[2]) ? trim($data[2]) : '';
            
            // Convertir les URLs en IDs de post
            $source_id = url_to_postid($source_url);
            $target_id = url_to_postid($target_url);
            
            if (!$source_id || !$target_id) {
                $skipped++;
                continue;
            }
            
            // Vérifier si le lien existe
            $exists = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM $table WHERE from_post_id = %d AND to_post_id = %d",
                $source_id,
                $target_id
            ));
            
            if ($exists) {
                $skipped++;
                continue;
            }
            
            // Insérer
            $wpdb->insert(
                $table,
                [
                    'from_post_id' => $source_id,
                    'to_post_id' => $target_id,
                    'anchor_text' => $anchor_text ?: get_the_title($target_id),
                    'date_added' => current_time('mysql')
                ],
                ['%d', '%d', '%s', '%s']
            );
            
            $imported++;
        }
        
        fclose($handle);
        
        wp_send_json_success([
            'message' => sprintf(
                __('Importation terminée : %d liens importés, %d ignorés', 'smart-backlink-manager'),
                $imported,
                $skipped
            )
        ]);
    }
}
