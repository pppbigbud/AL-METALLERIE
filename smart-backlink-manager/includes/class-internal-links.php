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
                <form id="sbm-internal-link-form">
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
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th scope="col" class="manage-column column-primary"><?php _e('Source', 'smart-backlink-manager'); ?></th>
                            <th scope="col"><?php _e('Cible', 'smart-backlink-manager'); ?></th>
                            <th scope="col"><?php _e('Texte d\'ancrage', 'smart-backlink-manager'); ?></th>
                            <th scope="col"><?php _e('Date', 'smart-backlink-manager'); ?></th>
                            <th scope="col"><?php _e('Actions', 'smart-backlink-manager'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="sbm-links-list">
                        <?php echo $this->get_links_rows(); ?>
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <div class="tablenav bottom">
                    <div class="tablenav-pages" id="sbm-pagination">
                        <?php echo $this->get_pagination(); ?>
                    </div>
                </div>
            </div>
            
            <!-- Import en masse -->
            <div class="sbm-import-section">
                <h2><?php _e('Importation en masse', 'smart-backlink-manager'); ?></h2>
                <p>
                    <?php _e('Importez plusieurs liens internes à partir d\'un fichier CSV.', 'smart-backlink-manager'); ?>
                </p>
                <form id="sbm-import-form" enctype="multipart/form-data">
                    <input type="file" name="csv_file" accept=".csv" required>
                    <button type="submit" class="button button-secondary">
                        <?php _e('Importer le CSV', 'smart-backlink-manager'); ?>
                    </button>
                    <a href="#" class="sbm-download-template">
                        <?php _e('Télécharger le modèle CSV', 'smart-backlink-manager'); ?>
                    </a>
                </form>
            </div>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            // Afficher/masquer le formulaire d'ajout
            $('#sbm-add-link-btn').on('click', function(e) {
                e.preventDefault();
                $('#sbm-add-link-form').slideToggle();
            });
            
            $('#sbm-cancel-add').on('click', function() {
                $('#sbm-add-link-form').slideUp();
                $('#sbm-internal-link-form')[0].reset();
            });
            
            // Soumission du formulaire
            $('#sbm-internal-link-form').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                
                $.post(ajaxurl, formData + '&action=sbm_add_internal_link&_ajax_nonce=<?php echo wp_create_nonce('sbm_nonce'); ?>', function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data || '<?php _e('Erreur lors de l\'ajout du lien', 'smart-backlink-manager'); ?>');
                    }
                });
            });
            
            // Filtrage
            $('#sbm-filter-source, #sbm-filter-target, #sbm-search-links').on('change keyup', function() {
                sbmFilterLinks();
            });
            
            function sbmFilterLinks() {
                var source = $('#sbm-filter-source').val();
                var target = $('#sbm-filter-target').val();
                var search = $('#sbm-search-links').val().toLowerCase();
                
                $('#sbm-links-list tr').each(function() {
                    var show = true;
                    
                    if (source && $(this).data('source') != source) show = false;
                    if (target && $(this).data('target') != target) show = false;
                    if (search && $(this).text().toLowerCase().indexOf(search) === -1) show = false;
                    
                    $(this).toggle(show);
                });
            }
            
            // Export
            $('#sbm-export-links').on('click', function() {
                var params = new URLSearchParams({
                    action: 'sbm_export_links',
                    _ajax_nonce: '<?php echo wp_create_nonce('sbm_nonce'); ?>'
                });
                
                window.location.href = ajaxurl + '?' + params.toString();
            });
        });
        </script>
        <?php
    }
    
    private function get_posts_options($field_id = ''): string {
        $posts = get_posts([
            'post_type' => ['post', 'page', 'realisation'],
            'post_status' => 'publish',
            'numberposts' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ]);
        
        $options = '';
        foreach ($posts as $post) {
            $options .= sprintf(
                '<option value="%d">%s (%s)</option>',
                $post->ID,
                esc_html($post->post_title),
                esc_html(get_post_type_object($post->post_type)->labels->singular_name)
            );
        }
        
        return $options;
    }
    
    private function get_links_rows(): string {
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_internal_links';
        
        $page = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
        $per_page = 20;
        $offset = ($page - 1) * $per_page;
        
        $links = $wpdb->get_results($wpdb->prepare("
            SELECT * FROM $table 
            ORDER BY date_added DESC 
            LIMIT %d OFFSET %d
        ", $per_page, $offset));
        
        $rows = '';
        foreach ($links as $link) {
            $source_post = get_post($link->from_post_id);
            $target_post = get_post($link->to_post_id);
            
            if ($source_post && $target_post) {
                $rows .= sprintf(
                    '<tr data-source="%d" data-target="%d">
                        <td><strong><a href="%s">%s</a></strong></td>
                        <td><strong><a href="%s">%s</a></strong></td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>
                            <button class="button button-small sbm-delete-link" data-id="%d">
                                %s
                            </button>
                        </td>
                    </tr>',
                    $link->from_post_id,
                    $link->to_post_id,
                    get_edit_post_link($source_post->ID),
                    esc_html($source_post->post_title),
                    get_edit_post_link($target_post->ID),
                    esc_html($target_post->post_title),
                    esc_html($link->anchor_text ?: '-'),
                    date('d/m/Y H:i', strtotime($link->date_added)),
                    $link->id,
                    __('Supprimer', 'smart-backlink-manager')
                );
            }
        }
        
        if (empty($rows)) {
            $rows = '<tr><td colspan="5">' . __('Aucun lien interne trouvé', 'smart-backlink-manager') . '</td></tr>';
        }
        
        return $rows;
    }
    
    private function get_pagination(): string {
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_internal_links';
        
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
    
    public function ajax_add_internal_link(): void {
        check_ajax_referer('sbm_nonce', '_ajax_nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_die(__('Vous n\'avez pas les permissions nécessaires', 'smart-backlink-manager'));
        }
        
        $from_post_id = intval($_POST['from_post_id']);
        $to_post_id = intval($_POST['to_post_id']);
        $anchor_text = sanitize_text_field($_POST['anchor_text']);
        
        if (!$from_post_id || !$to_post_id) {
            wp_send_json_error(__('Veuillez sélectionner les pages source et cible', 'smart-backlink-manager'));
        }
        
        if ($from_post_id === $to_post_id) {
            wp_send_json_error(__('La page source et cible ne peuvent pas être identiques', 'smart-backlink-manager'));
        }
        
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_internal_links';
        
        // Vérifier si le lien existe déjà
        $exists = $wpdb->get_var($wpdb->prepare("
            SELECT COUNT(*) FROM $table 
            WHERE from_post_id = %d AND to_post_id = %d
        ", $from_post_id, $to_post_id));
        
        if ($exists) {
            wp_send_json_error(__('Ce lien existe déjà', 'smart-backlink-manager'));
        }
        
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
        
        if ($result) {
            wp_send_json_success(__('Lien interne ajouté avec succès', 'smart-backlink-manager'));
        } else {
            wp_send_json_error(__('Erreur lors de l\'ajout du lien', 'smart-backlink-manager'));
        }
    }
    
    public function ajax_delete_internal_link(): void {
        check_ajax_referer('sbm_nonce', '_ajax_nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_die(__('Vous n\'avez pas les permissions nécessaires', 'smart-backlink-manager'));
        }
        
        $link_id = intval($_POST['link_id']);
        
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_internal_links';
        
        $result = $wpdb->delete(
            $table,
            ['id' => $link_id],
            ['%d']
        );
        
        if ($result) {
            wp_send_json_success(__('Lien supprimé avec succès', 'smart-backlink-manager'));
        } else {
            wp_send_json_error(__('Erreur lors de la suppression du lien', 'smart-backlink-manager'));
        }
    }
    
    public function ajax_bulk_import_links(): void {
        check_ajax_referer('sbm_nonce', '_ajax_nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_die(__('Vous n\'avez pas les permissions nécessaires', 'smart-backlink-manager'));
        }
        
        if (!isset($_FILES['csv_file'])) {
            wp_send_json_error(__('Aucun fichier uploadé', 'smart-backlink-manager'));
        }
        
        $file = $_FILES['csv_file']['tmp_name'];
        $handle = fopen($file, 'r');
        
        if (!$handle) {
            wp_send_json_error(__('Impossible de lire le fichier', 'smart-backlink-manager'));
        }
        
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_internal_links';
        $imported = 0;
        $errors = [];
        
        // Skip header row
        fgetcsv($handle);
        
        while (($row = fgetcsv($handle)) !== FALSE) {
            if (count($row) < 2) continue;
            
            $source_url = trim($row[0]);
            $target_url = trim($row[1]);
            $anchor_text = isset($row[2]) ? trim($row[2]) : '';
            
            // Convertir les URLs en IDs
            $source_id = url_to_postid($source_url);
            $target_id = url_to_postid($target_url);
            
            if (!$source_id || !$target_id) {
                $errors[] = sprintf(__('URL invalide: %s -> %s', 'smart-backlink-manager'), $source_url, $target_url);
                continue;
            }
            
            // Insérer le lien
            $result = $wpdb->insert(
                $table,
                [
                    'from_post_id' => $source_id,
                    'to_post_id' => $target_id,
                    'anchor_text' => $anchor_text ?: get_the_title($target_id),
                    'date_added' => current_time('mysql')
                ],
                ['%d', '%d', '%s', '%s']
            );
            
            if ($result) {
                $imported++;
            }
        }
        
        fclose($handle);
        
        wp_send_json_success([
            'imported' => $imported,
            'errors' => $errors
        ]);
    }
}
