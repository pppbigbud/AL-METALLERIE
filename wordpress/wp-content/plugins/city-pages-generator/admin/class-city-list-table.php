<?php
/**
 * Tableau de liste des pages ville
 *
 * @package CityPagesGenerator
 */

if (!defined('ABSPATH')) {
    exit;
}

class CPG_City_List_Table extends WP_List_Table {

    public function __construct() {
        parent::__construct([
            'singular' => __('Page ville', 'city-pages-generator'),
            'plural'   => __('Pages ville', 'city-pages-generator'),
            'ajax'     => false,
        ]);
    }

    /**
     * Colonnes du tableau
     */
    public function get_columns() {
        return [
            'cb'         => '<input type="checkbox">',
            'city_name'  => __('Ville', 'city-pages-generator'),
            'postal_code'=> __('Code postal', 'city-pages-generator'),
            'department' => __('Département', 'city-pages-generator'),
            'priority'   => __('Priorité', 'city-pages-generator'),
            'status'     => __('Statut', 'city-pages-generator'),
            'date'       => __('Date', 'city-pages-generator'),
        ];
    }

    /**
     * Colonnes triables
     */
    public function get_sortable_columns() {
        return [
            'city_name'  => ['city_name', false],
            'department' => ['department', false],
            'priority'   => ['priority', false],
            'date'       => ['date', true],
        ];
    }

    /**
     * Actions en masse
     */
    public function get_bulk_actions() {
        return [
            'publish' => __('Publier', 'city-pages-generator'),
            'draft'   => __('Mettre en brouillon', 'city-pages-generator'),
            'delete'  => __('Supprimer', 'city-pages-generator'),
        ];
    }

    /**
     * Préparer les éléments
     */
    public function prepare_items() {
        $per_page = 20;
        $current_page = $this->get_pagenum();

        // Colonnes
        $columns = $this->get_columns();
        $hidden = [];
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = [$columns, $hidden, $sortable];

        // Traiter les actions en masse
        $this->process_bulk_action();

        // Requête
        $args = [
            'post_type'      => 'city_page',
            'posts_per_page' => $per_page,
            'paged'          => $current_page,
            'post_status'    => 'any',
        ];

        // Recherche
        if (!empty($_REQUEST['s'])) {
            $args['s'] = sanitize_text_field($_REQUEST['s']);
        }

        // Tri
        if (!empty($_REQUEST['orderby'])) {
            switch ($_REQUEST['orderby']) {
                case 'city_name':
                    $args['meta_key'] = '_cpg_city_name';
                    $args['orderby'] = 'meta_value';
                    break;
                case 'department':
                    $args['meta_key'] = '_cpg_department';
                    $args['orderby'] = 'meta_value';
                    break;
                case 'priority':
                    $args['meta_key'] = '_cpg_priority';
                    $args['orderby'] = 'meta_value_num';
                    break;
                default:
                    $args['orderby'] = sanitize_text_field($_REQUEST['orderby']);
            }
            $args['order'] = !empty($_REQUEST['order']) ? strtoupper(sanitize_text_field($_REQUEST['order'])) : 'ASC';
        }

        $query = new WP_Query($args);

        $this->items = $query->posts;

        $this->set_pagination_args([
            'total_items' => $query->found_posts,
            'per_page'    => $per_page,
            'total_pages' => ceil($query->found_posts / $per_page),
        ]);
    }

    /**
     * Traiter les actions en masse
     */
    public function process_bulk_action() {
        if (!isset($_REQUEST['city_page']) || !is_array($_REQUEST['city_page'])) {
            return;
        }

        $action = $this->current_action();
        $post_ids = array_map('intval', $_REQUEST['city_page']);

        if (!$action || empty($post_ids)) {
            return;
        }

        // Vérifier le nonce
        check_admin_referer('bulk-' . $this->_args['plural']);

        switch ($action) {
            case 'publish':
                foreach ($post_ids as $post_id) {
                    wp_update_post(['ID' => $post_id, 'post_status' => 'publish']);
                }
                break;

            case 'draft':
                foreach ($post_ids as $post_id) {
                    wp_update_post(['ID' => $post_id, 'post_status' => 'draft']);
                }
                break;

            case 'delete':
                foreach ($post_ids as $post_id) {
                    wp_delete_post($post_id, true);
                }
                break;
        }
    }

    /**
     * Colonne checkbox
     */
    public function column_cb($item) {
        return sprintf('<input type="checkbox" name="city_page[]" value="%d">', $item->ID);
    }

    /**
     * Colonne ville
     */
    public function column_city_name($item) {
        $city_name = get_post_meta($item->ID, '_cpg_city_name', true) ?: $item->post_title;
        $edit_link = get_edit_post_link($item->ID);
        $view_link = get_permalink($item->ID);

        $actions = [
            'edit' => sprintf('<a href="%s">%s</a>', $edit_link, __('Modifier', 'city-pages-generator')),
            'view' => sprintf('<a href="%s" target="_blank">%s</a>', $view_link, __('Voir', 'city-pages-generator')),
            'regenerate' => sprintf(
                '<a href="#" class="cpg-regenerate-link" data-post-id="%d">%s</a>',
                $item->ID,
                __('Regénérer', 'city-pages-generator')
            ),
            'delete' => sprintf(
                '<a href="#" class="cpg-delete-link" data-post-id="%d" style="color:#a00;">%s</a>',
                $item->ID,
                __('Supprimer', 'city-pages-generator')
            ),
        ];

        return sprintf(
            '<strong><a href="%s">%s</a></strong>%s',
            $edit_link,
            esc_html($city_name),
            $this->row_actions($actions)
        );
    }

    /**
     * Colonne code postal
     */
    public function column_postal_code($item) {
        return esc_html(get_post_meta($item->ID, '_cpg_postal_code', true));
    }

    /**
     * Colonne département
     */
    public function column_department($item) {
        return esc_html(get_post_meta($item->ID, '_cpg_department', true));
    }

    /**
     * Colonne priorité
     */
    public function column_priority($item) {
        $priority = get_post_meta($item->ID, '_cpg_priority', true);
        $labels = [
            1 => '<span class="cpg-priority cpg-priority-1">Haute</span>',
            2 => '<span class="cpg-priority cpg-priority-2">Moyenne</span>',
            3 => '<span class="cpg-priority cpg-priority-3">Basse</span>',
        ];
        return $labels[$priority] ?? '-';
    }

    /**
     * Colonne statut
     */
    public function column_status($item) {
        $status_obj = get_post_status_object($item->post_status);
        $class = $item->post_status === 'publish' ? 'cpg-status-publish' : 'cpg-status-draft';
        return sprintf('<span class="cpg-status %s">%s</span>', $class, $status_obj->label);
    }

    /**
     * Colonne date
     */
    public function column_date($item) {
        return get_the_date('d/m/Y', $item->ID);
    }

    /**
     * Colonne par défaut
     */
    public function column_default($item, $column_name) {
        return '-';
    }

    /**
     * Message si aucun élément
     */
    public function no_items() {
        _e('Aucune page ville trouvée.', 'city-pages-generator');
    }
}
