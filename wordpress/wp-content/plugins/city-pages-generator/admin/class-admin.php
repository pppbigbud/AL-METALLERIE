<?php
/**
 * Interface d'administration du plugin
 *
 * @package CityPagesGenerator
 */

if (!defined('ABSPATH')) {
    exit;
}

class CPG_Admin {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_ajax_cpg_create_city', [$this, 'ajax_create_city']);
        add_action('wp_ajax_cpg_regenerate_content', [$this, 'ajax_regenerate_content']);
        add_action('wp_ajax_cpg_delete_city', [$this, 'ajax_delete_city']);
        add_action('wp_ajax_cpg_export_csv', [$this, 'ajax_export_csv']);
        add_action('wp_ajax_cpg_import_csv', [$this, 'ajax_import_csv']);
        add_action('wp_ajax_cpg_bulk_generate', [$this, 'ajax_bulk_generate']);
    }

    /**
     * Ajouter le menu admin
     */
    public function add_admin_menu() {
        // Menu principal
        add_menu_page(
            __('Pages Ville', 'city-pages-generator'),
            __('Pages Ville', 'city-pages-generator'),
            'manage_options',
            'city-pages-generator',
            [$this, 'render_dashboard_page'],
            'dashicons-location-alt',
            26
        );

        // Sous-menu : Tableau de bord
        add_submenu_page(
            'city-pages-generator',
            __('Tableau de bord', 'city-pages-generator'),
            __('Tableau de bord', 'city-pages-generator'),
            'manage_options',
            'city-pages-generator',
            [$this, 'render_dashboard_page']
        );

        // Sous-menu : Ajouter une ville
        add_submenu_page(
            'city-pages-generator',
            __('Ajouter une ville', 'city-pages-generator'),
            __('Ajouter une ville', 'city-pages-generator'),
            'manage_options',
            'cpg-add-city',
            [$this, 'render_add_city_page']
        );

        // Sous-menu : Toutes les villes
        add_submenu_page(
            'city-pages-generator',
            __('Toutes les villes', 'city-pages-generator'),
            __('Toutes les villes', 'city-pages-generator'),
            'manage_options',
            'cpg-all-cities',
            [$this, 'render_all_cities_page']
        );

        // Sous-menu : Import/Export
        add_submenu_page(
            'city-pages-generator',
            __('Import / Export', 'city-pages-generator'),
            __('Import / Export', 'city-pages-generator'),
            'manage_options',
            'cpg-import-export',
            [$this, 'render_import_export_page']
        );

        // Sous-menu : Paramètres
        add_submenu_page(
            'city-pages-generator',
            __('Paramètres', 'city-pages-generator'),
            __('Paramètres', 'city-pages-generator'),
            'manage_options',
            'cpg-settings',
            [$this, 'render_settings_page']
        );
    }

    /**
     * Charger les assets admin
     */
    public function enqueue_assets($hook) {
        if (strpos($hook, 'city-pages') === false && get_post_type() !== 'city_page') {
            return;
        }

        wp_enqueue_style(
            'cpg-admin',
            CPG_PLUGIN_URL . 'admin/css/admin.css',
            [],
            CPG_VERSION
        );

        wp_enqueue_script(
            'cpg-admin',
            CPG_PLUGIN_URL . 'admin/js/admin.js',
            ['jquery'],
            CPG_VERSION,
            true
        );

        wp_localize_script('cpg-admin', 'cpgAdmin', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cpg_admin_nonce'),
            'strings' => [
                'confirmDelete' => __('Êtes-vous sûr de vouloir supprimer cette page ville ?', 'city-pages-generator'),
                'confirmRegenerate' => __('Êtes-vous sûr de vouloir regénérer le contenu ? Cela écrasera les modifications manuelles.', 'city-pages-generator'),
                'generating' => __('Génération en cours...', 'city-pages-generator'),
                'success' => __('Opération réussie !', 'city-pages-generator'),
                'error' => __('Une erreur est survenue.', 'city-pages-generator'),
            ],
        ]);
    }

    /**
     * Page : Tableau de bord
     */
    public function render_dashboard_page() {
        // Statistiques
        $total_cities = wp_count_posts('city_page');
        $published = $total_cities->publish ?? 0;
        $drafts = $total_cities->draft ?? 0;

        // Dernières pages créées
        $recent_pages = get_posts([
            'post_type' => 'city_page',
            'posts_per_page' => 5,
            'orderby' => 'date',
            'order' => 'DESC',
        ]);

        // Pages par département
        $departments = get_terms([
            'taxonomy' => 'cpg_department',
            'hide_empty' => false,
        ]);
        ?>
        <div class="wrap cpg-admin-wrap">
            <h1 class="cpg-admin-title">
                <span class="dashicons dashicons-location-alt"></span>
                <?php _e('Générateur de Pages Ville', 'city-pages-generator'); ?>
            </h1>

            <!-- Stats Cards -->
            <div class="cpg-stats-grid">
                <div class="cpg-stat-card">
                    <div class="cpg-stat-icon cpg-stat-primary">
                        <span class="dashicons dashicons-admin-page"></span>
                    </div>
                    <div class="cpg-stat-content">
                        <span class="cpg-stat-number"><?php echo intval($published); ?></span>
                        <span class="cpg-stat-label"><?php _e('Pages publiées', 'city-pages-generator'); ?></span>
                    </div>
                </div>

                <div class="cpg-stat-card">
                    <div class="cpg-stat-icon cpg-stat-warning">
                        <span class="dashicons dashicons-edit"></span>
                    </div>
                    <div class="cpg-stat-content">
                        <span class="cpg-stat-number"><?php echo intval($drafts); ?></span>
                        <span class="cpg-stat-label"><?php _e('Brouillons', 'city-pages-generator'); ?></span>
                    </div>
                </div>

                <div class="cpg-stat-card">
                    <div class="cpg-stat-icon cpg-stat-info">
                        <span class="dashicons dashicons-admin-site"></span>
                    </div>
                    <div class="cpg-stat-content">
                        <span class="cpg-stat-number"><?php echo count($departments); ?></span>
                        <span class="cpg-stat-label"><?php _e('Départements', 'city-pages-generator'); ?></span>
                    </div>
                </div>

                <div class="cpg-stat-card">
                    <div class="cpg-stat-icon cpg-stat-success">
                        <span class="dashicons dashicons-chart-area"></span>
                    </div>
                    <div class="cpg-stat-content">
                        <span class="cpg-stat-number"><?php echo intval($published + $drafts); ?></span>
                        <span class="cpg-stat-label"><?php _e('Total', 'city-pages-generator'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="cpg-dashboard-row">
                <div class="cpg-dashboard-col cpg-dashboard-col-2">
                    <div class="cpg-card">
                        <h2 class="cpg-card-title"><?php _e('Actions rapides', 'city-pages-generator'); ?></h2>
                        <div class="cpg-quick-actions">
                            <a href="<?php echo admin_url('admin.php?page=cpg-add-city'); ?>" class="button button-primary button-hero">
                                <span class="dashicons dashicons-plus-alt"></span>
                                <?php _e('Ajouter une ville', 'city-pages-generator'); ?>
                            </a>
                            <a href="<?php echo admin_url('admin.php?page=cpg-import-export'); ?>" class="button button-secondary button-hero">
                                <span class="dashicons dashicons-upload"></span>
                                <?php _e('Importer des villes', 'city-pages-generator'); ?>
                            </a>
                            <a href="<?php echo admin_url('admin.php?page=cpg-all-cities'); ?>" class="button button-secondary button-hero">
                                <span class="dashicons dashicons-list-view"></span>
                                <?php _e('Voir toutes les pages', 'city-pages-generator'); ?>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="cpg-dashboard-col cpg-dashboard-col-2">
                    <div class="cpg-card">
                        <h2 class="cpg-card-title"><?php _e('Dernières pages créées', 'city-pages-generator'); ?></h2>
                        <?php if ($recent_pages) : ?>
                            <ul class="cpg-recent-list">
                                <?php foreach ($recent_pages as $page) : 
                                    $city_name = get_post_meta($page->ID, '_cpg_city_name', true);
                                ?>
                                    <li>
                                        <a href="<?php echo get_edit_post_link($page->ID); ?>">
                                            <?php echo esc_html($city_name ?: $page->post_title); ?>
                                        </a>
                                        <span class="cpg-status cpg-status-<?php echo $page->post_status; ?>">
                                            <?php echo get_post_status_object($page->post_status)->label; ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <p class="cpg-empty"><?php _e('Aucune page créée pour le moment.', 'city-pages-generator'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Départements -->
            <div class="cpg-card">
                <h2 class="cpg-card-title"><?php _e('Pages par département', 'city-pages-generator'); ?></h2>
                <div class="cpg-departments-grid">
                    <?php foreach ($departments as $dept) : ?>
                        <div class="cpg-department-card">
                            <span class="cpg-department-name"><?php echo esc_html($dept->name); ?></span>
                            <span class="cpg-department-count"><?php echo intval($dept->count); ?> <?php _e('pages', 'city-pages-generator'); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Page : Ajouter une ville
     */
    public function render_add_city_page() {
        ?>
        <div class="wrap cpg-admin-wrap">
            <h1><?php _e('Ajouter une nouvelle ville', 'city-pages-generator'); ?></h1>

            <form id="cpg-add-city-form" class="cpg-form">
                <?php wp_nonce_field('cpg_add_city', 'cpg_add_city_nonce'); ?>

                <div class="cpg-form-section">
                    <h2><?php _e('Informations de la ville', 'city-pages-generator'); ?></h2>
                    
                    <table class="form-table">
                        <tr>
                            <th><label for="city_name"><?php _e('Nom de la ville', 'city-pages-generator'); ?> <span class="required">*</span></label></th>
                            <td>
                                <input type="text" id="city_name" name="city_name" class="regular-text" required>
                                <p class="description"><?php _e('Exemple : Clermont-Ferrand', 'city-pages-generator'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="postal_code"><?php _e('Code postal', 'city-pages-generator'); ?> <span class="required">*</span></label></th>
                            <td>
                                <input type="text" id="postal_code" name="postal_code" class="small-text" maxlength="5" required>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="department"><?php _e('Département', 'city-pages-generator'); ?></label></th>
                            <td>
                                <select id="department" name="department">
                                    <option value="Puy-de-Dôme">Puy-de-Dôme (63)</option>
                                    <option value="Allier">Allier (03)</option>
                                    <option value="Cantal">Cantal (15)</option>
                                    <option value="Haute-Loire">Haute-Loire (43)</option>
                                    <option value="Loire">Loire (42)</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="priority"><?php _e('Priorité', 'city-pages-generator'); ?></label></th>
                            <td>
                                <select id="priority" name="priority">
                                    <option value="1"><?php _e('1 - Haute (ville principale)', 'city-pages-generator'); ?></option>
                                    <option value="2" selected><?php _e('2 - Moyenne (ville secondaire)', 'city-pages-generator'); ?></option>
                                    <option value="3"><?php _e('3 - Basse (petite commune)', 'city-pages-generator'); ?></option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="cpg-form-section">
                    <h2><?php _e('Localisation', 'city-pages-generator'); ?></h2>
                    
                    <table class="form-table">
                        <tr>
                            <th><label for="distance_km"><?php _e('Distance depuis Peschadoires', 'city-pages-generator'); ?></label></th>
                            <td>
                                <input type="number" id="distance_km" name="distance_km" class="small-text" step="0.1" min="0"> km
                            </td>
                        </tr>
                        <tr>
                            <th><label for="travel_time"><?php _e('Temps de trajet', 'city-pages-generator'); ?></label></th>
                            <td>
                                <input type="text" id="travel_time" name="travel_time" class="regular-text" placeholder="25 minutes">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="local_specifics"><?php _e('Spécificités locales', 'city-pages-generator'); ?></label></th>
                            <td>
                                <textarea id="local_specifics" name="local_specifics" rows="4" class="large-text" placeholder="Quartiers, axes routiers, particularités..."></textarea>
                                <p class="description"><?php _e('Ces informations seront intégrées au contenu de la page.', 'city-pages-generator'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="nearby_cities"><?php _e('Communes à proximité', 'city-pages-generator'); ?></label></th>
                            <td>
                                <textarea id="nearby_cities" name="nearby_cities" rows="5" class="large-text" placeholder="Une commune par ligne"></textarea>
                                <p class="description"><?php _e('Liste des communes desservies autour de cette ville.', 'city-pages-generator'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="cpg-form-section">
                    <h2><?php _e('Options de publication', 'city-pages-generator'); ?></h2>
                    
                    <table class="form-table">
                        <tr>
                            <th><label for="post_status"><?php _e('Statut', 'city-pages-generator'); ?></label></th>
                            <td>
                                <select id="post_status" name="post_status">
                                    <option value="draft"><?php _e('Brouillon (relecture manuelle)', 'city-pages-generator'); ?></option>
                                    <option value="publish"><?php _e('Publié immédiatement', 'city-pages-generator'); ?></option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>

                <p class="submit">
                    <button type="submit" class="button button-primary button-large" id="cpg-generate-btn">
                        <span class="dashicons dashicons-admin-page"></span>
                        <?php _e('Générer la page', 'city-pages-generator'); ?>
                    </button>
                    <span class="spinner" id="cpg-spinner"></span>
                </p>

                <div id="cpg-result-message" class="cpg-message" style="display:none;"></div>
            </form>
        </div>
        <?php
    }

    /**
     * Page : Toutes les villes
     */
    public function render_all_cities_page() {
        // Inclure la classe WP_List_Table
        if (!class_exists('WP_List_Table')) {
            require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
        }
        
        // Inclure notre classe de liste
        if (!class_exists('CPG_City_List_Table')) {
            require_once CPG_PLUGIN_DIR . 'admin/class-city-list-table.php';
        }

        $list_table = new CPG_City_List_Table();
        $list_table->prepare_items();
        ?>
        <div class="wrap cpg-admin-wrap">
            <h1 class="wp-heading-inline"><?php _e('Toutes les pages ville', 'city-pages-generator'); ?></h1>
            <a href="<?php echo admin_url('admin.php?page=cpg-add-city'); ?>" class="page-title-action">
                <?php _e('Ajouter', 'city-pages-generator'); ?>
            </a>
            <hr class="wp-header-end">

            <form method="get">
                <input type="hidden" name="page" value="cpg-all-cities">
                <?php
                $list_table->search_box(__('Rechercher', 'city-pages-generator'), 'city-search');
                $list_table->display();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Page : Import/Export
     */
    public function render_import_export_page() {
        ?>
        <div class="wrap cpg-admin-wrap">
            <h1><?php _e('Import / Export', 'city-pages-generator'); ?></h1>

            <div class="cpg-dashboard-row">
                <!-- Export -->
                <div class="cpg-dashboard-col cpg-dashboard-col-2">
                    <div class="cpg-card">
                        <h2 class="cpg-card-title"><?php _e('Exporter les villes', 'city-pages-generator'); ?></h2>
                        <p><?php _e('Téléchargez la liste de toutes les pages ville au format CSV.', 'city-pages-generator'); ?></p>
                        <button type="button" id="cpg-export-btn" class="button button-primary">
                            <span class="dashicons dashicons-download"></span>
                            <?php _e('Exporter en CSV', 'city-pages-generator'); ?>
                        </button>
                    </div>
                </div>

                <!-- Import -->
                <div class="cpg-dashboard-col cpg-dashboard-col-2">
                    <div class="cpg-card">
                        <h2 class="cpg-card-title"><?php _e('Importer des villes', 'city-pages-generator'); ?></h2>
                        <p><?php _e('Importez des villes depuis un fichier CSV.', 'city-pages-generator'); ?></p>
                        <form id="cpg-import-form" enctype="multipart/form-data">
                            <?php wp_nonce_field('cpg_import', 'cpg_import_nonce'); ?>
                            <p>
                                <input type="file" name="csv_file" id="csv_file" accept=".csv">
                            </p>
                            <p>
                                <label>
                                    <input type="checkbox" name="import_as_draft" value="1" checked>
                                    <?php _e('Importer en brouillon', 'city-pages-generator'); ?>
                                </label>
                            </p>
                            <button type="submit" class="button button-secondary">
                                <span class="dashicons dashicons-upload"></span>
                                <?php _e('Importer', 'city-pages-generator'); ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Format CSV -->
            <div class="cpg-card">
                <h2 class="cpg-card-title"><?php _e('Format du fichier CSV', 'city-pages-generator'); ?></h2>
                <p><?php _e('Le fichier CSV doit contenir les colonnes suivantes (séparateur : point-virgule) :', 'city-pages-generator'); ?></p>
                <code>city_name;postal_code;department;priority;distance_km;travel_time;local_specifics;nearby_cities</code>
                <p class="description"><?php _e('Les communes à proximité doivent être séparées par des virgules.', 'city-pages-generator'); ?></p>
                
                <h3><?php _e('Exemple :', 'city-pages-generator'); ?></h3>
                <pre>Clermont-Ferrand;63000;Puy-de-Dôme;1;25;25 minutes;quartiers de Chamalières, Royat;Chamalières,Royat,Beaumont,Ceyrat
Thiers;63300;Puy-de-Dôme;2;15;15 minutes;cité coutelière, centre historique;Peschadoires,Celles-sur-Durolle,Escoutoux</pre>
            </div>

            <!-- Génération en masse -->
            <div class="cpg-card">
                <h2 class="cpg-card-title"><?php _e('Génération progressive', 'city-pages-generator'); ?></h2>
                <p><?php _e('Générer les pages par lots pour rester naturel aux yeux des moteurs de recherche.', 'city-pages-generator'); ?></p>
                
                <form id="cpg-bulk-generate-form">
                    <?php wp_nonce_field('cpg_bulk', 'cpg_bulk_nonce'); ?>
                    <p>
                        <label for="batch_size"><?php _e('Nombre de pages par lot :', 'city-pages-generator'); ?></label>
                        <select id="batch_size" name="batch_size">
                            <option value="5">5 pages</option>
                            <option value="10">10 pages</option>
                            <option value="20">20 pages</option>
                            <option value="all"><?php _e('Toutes', 'city-pages-generator'); ?></option>
                        </select>
                    </p>
                    <p>
                        <label>
                            <input type="checkbox" name="publish_immediately" value="1">
                            <?php _e('Publier immédiatement', 'city-pages-generator'); ?>
                        </label>
                    </p>
                    <button type="submit" class="button button-secondary">
                        <?php _e('Publier les brouillons', 'city-pages-generator'); ?>
                    </button>
                </form>
            </div>

            <div id="cpg-import-result" class="cpg-message" style="display:none;"></div>
        </div>
        <?php
    }

    /**
     * Page : Paramètres (délégué à CPG_Settings)
     */
    public function render_settings_page() {
        CPG_Settings::get_instance()->render_page();
    }

    /**
     * AJAX : Créer une ville
     */
    public function ajax_create_city() {
        check_ajax_referer('cpg_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Permission refusée.', 'city-pages-generator')]);
        }

        $data = [
            'city_name' => sanitize_text_field($_POST['city_name'] ?? ''),
            'postal_code' => sanitize_text_field($_POST['postal_code'] ?? ''),
            'department' => sanitize_text_field($_POST['department'] ?? 'Puy-de-Dôme'),
            'priority' => intval($_POST['priority'] ?? 2),
            'local_specifics' => sanitize_textarea_field($_POST['local_specifics'] ?? ''),
            'distance_km' => floatval($_POST['distance_km'] ?? 0),
            'travel_time' => sanitize_text_field($_POST['travel_time'] ?? ''),
            'nearby_cities' => array_filter(array_map('trim', explode("\n", $_POST['nearby_cities'] ?? ''))),
            'post_status' => sanitize_text_field($_POST['post_status'] ?? 'draft'),
        ];

        if (empty($data['city_name']) || empty($data['postal_code'])) {
            wp_send_json_error(['message' => __('Le nom de la ville et le code postal sont obligatoires.', 'city-pages-generator')]);
        }

        $result = CPG_Post_Type::create_city_page($data);

        if (is_wp_error($result)) {
            wp_send_json_error(['message' => $result->get_error_message()]);
        }

        wp_send_json_success([
            'message' => sprintf(__('Page créée pour %s !', 'city-pages-generator'), $data['city_name']),
            'post_id' => $result,
            'edit_url' => get_edit_post_link($result, 'raw'),
            'view_url' => get_permalink($result),
        ]);
    }

    /**
     * AJAX : Regénérer le contenu
     */
    public function ajax_regenerate_content() {
        check_ajax_referer('cpg_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Permission refusée.', 'city-pages-generator')]);
        }

        $post_id = intval($_POST['post_id'] ?? 0);

        if (!$post_id) {
            wp_send_json_error(['message' => __('ID de page invalide.', 'city-pages-generator')]);
        }

        $result = CPG_Post_Type::update_city_page($post_id, [], true);

        if (is_wp_error($result)) {
            wp_send_json_error(['message' => $result->get_error_message()]);
        }

        wp_send_json_success(['message' => __('Contenu regénéré avec succès !', 'city-pages-generator')]);
    }

    /**
     * AJAX : Supprimer une ville
     */
    public function ajax_delete_city() {
        check_ajax_referer('cpg_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Permission refusée.', 'city-pages-generator')]);
        }

        $post_id = intval($_POST['post_id'] ?? 0);

        if (!$post_id) {
            wp_send_json_error(['message' => __('ID de page invalide.', 'city-pages-generator')]);
        }

        $result = wp_delete_post($post_id, true);

        if (!$result) {
            wp_send_json_error(['message' => __('Impossible de supprimer la page.', 'city-pages-generator')]);
        }

        wp_send_json_success(['message' => __('Page supprimée.', 'city-pages-generator')]);
    }

    /**
     * AJAX : Export CSV
     */
    public function ajax_export_csv() {
        check_ajax_referer('cpg_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Permission refusée.', 'city-pages-generator')]);
        }

        $cities = CPG_Post_Type::get_all_city_pages();
        $csv_data = [];

        // En-têtes
        $csv_data[] = ['city_name', 'postal_code', 'department', 'priority', 'distance_km', 'travel_time', 'local_specifics', 'nearby_cities', 'status', 'url'];

        foreach ($cities as $city) {
            $data = CPG_Post_Type::get_city_data($city->ID);
            $csv_data[] = [
                $data['city_name'],
                $data['postal_code'],
                $data['department'],
                $data['priority'],
                $data['distance_km'],
                $data['travel_time'],
                $data['local_specifics'],
                implode(',', $data['nearby_cities']),
                $city->post_status,
                get_permalink($city->ID),
            ];
        }

        // Générer le CSV
        $output = fopen('php://temp', 'r+');
        foreach ($csv_data as $row) {
            fputcsv($output, $row, ';');
        }
        rewind($output);
        $csv_content = stream_get_contents($output);
        fclose($output);

        wp_send_json_success([
            'csv' => $csv_content,
            'filename' => 'pages-ville-' . date('Y-m-d') . '.csv',
        ]);
    }

    /**
     * AJAX : Import CSV
     */
    public function ajax_import_csv() {
        check_ajax_referer('cpg_import', 'cpg_import_nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Permission refusée.', 'city-pages-generator')]);
        }

        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            wp_send_json_error(['message' => __('Erreur lors du téléchargement du fichier.', 'city-pages-generator')]);
        }

        $file = $_FILES['csv_file']['tmp_name'];
        $as_draft = isset($_POST['import_as_draft']) && $_POST['import_as_draft'] === '1';

        $handle = fopen($file, 'r');
        if (!$handle) {
            wp_send_json_error(['message' => __('Impossible de lire le fichier.', 'city-pages-generator')]);
        }

        $imported = 0;
        $errors = [];
        $row_num = 0;

        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            $row_num++;

            // Ignorer l'en-tête
            if ($row_num === 1 && $row[0] === 'city_name') {
                continue;
            }

            if (count($row) < 2) {
                continue;
            }

            $data = [
                'city_name' => $row[0] ?? '',
                'postal_code' => $row[1] ?? '',
                'department' => $row[2] ?? 'Puy-de-Dôme',
                'priority' => intval($row[3] ?? 2),
                'distance_km' => floatval($row[4] ?? 0),
                'travel_time' => $row[5] ?? '',
                'local_specifics' => $row[6] ?? '',
                'nearby_cities' => !empty($row[7]) ? explode(',', $row[7]) : [],
                'post_status' => $as_draft ? 'draft' : 'publish',
            ];

            if (empty($data['city_name'])) {
                continue;
            }

            $result = CPG_Post_Type::create_city_page($data);

            if (is_wp_error($result)) {
                $errors[] = sprintf('%s : %s', $data['city_name'], $result->get_error_message());
            } else {
                $imported++;
            }
        }

        fclose($handle);

        $message = sprintf(__('%d ville(s) importée(s).', 'city-pages-generator'), $imported);
        if (!empty($errors)) {
            $message .= ' ' . sprintf(__('%d erreur(s).', 'city-pages-generator'), count($errors));
        }

        wp_send_json_success([
            'message' => $message,
            'imported' => $imported,
            'errors' => $errors,
        ]);
    }

    /**
     * AJAX : Génération en masse
     */
    public function ajax_bulk_generate() {
        check_ajax_referer('cpg_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Permission refusée.', 'city-pages-generator')]);
        }

        $batch_size = $_POST['batch_size'] ?? 5;
        $publish = isset($_POST['publish_immediately']) && $_POST['publish_immediately'] === '1';

        $args = [
            'post_type' => 'city_page',
            'post_status' => 'draft',
            'posts_per_page' => $batch_size === 'all' ? -1 : intval($batch_size),
        ];

        $drafts = get_posts($args);
        $published = 0;

        foreach ($drafts as $draft) {
            if ($publish) {
                wp_update_post([
                    'ID' => $draft->ID,
                    'post_status' => 'publish',
                ]);
                $published++;
            }
        }

        wp_send_json_success([
            'message' => sprintf(__('%d page(s) publiée(s).', 'city-pages-generator'), $published),
            'published' => $published,
        ]);
    }
}
