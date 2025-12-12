<?php
/**
 * Custom Post Type pour les pages ville
 *
 * @package CityPagesGenerator
 */

if (!defined('ABSPATH')) {
    exit;
}

class CPG_Post_Type {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action('init', array($this, 'register_post_type'), 5);
    }

    /**
     * Enregistrer le Custom Post Type
     */
    public function register_post_type() {
        $labels = [
            'name'                  => __('Pages Ville', 'city-pages-generator'),
            'singular_name'         => __('Page Ville', 'city-pages-generator'),
            'menu_name'             => __('Pages Ville', 'city-pages-generator'),
            'name_admin_bar'        => __('Page Ville', 'city-pages-generator'),
            'archives'              => __('Archives des pages ville', 'city-pages-generator'),
            'attributes'            => __('Attributs', 'city-pages-generator'),
            'parent_item_colon'     => __('Page parente :', 'city-pages-generator'),
            'all_items'             => __('Toutes les pages', 'city-pages-generator'),
            'add_new_item'          => __('Ajouter une page ville', 'city-pages-generator'),
            'add_new'               => __('Ajouter', 'city-pages-generator'),
            'new_item'              => __('Nouvelle page', 'city-pages-generator'),
            'edit_item'             => __('Modifier la page', 'city-pages-generator'),
            'update_item'           => __('Mettre à jour', 'city-pages-generator'),
            'view_item'             => __('Voir la page', 'city-pages-generator'),
            'view_items'            => __('Voir les pages', 'city-pages-generator'),
            'search_items'          => __('Rechercher', 'city-pages-generator'),
            'not_found'             => __('Aucune page trouvée', 'city-pages-generator'),
            'not_found_in_trash'    => __('Aucune page dans la corbeille', 'city-pages-generator'),
            'featured_image'        => __('Image mise en avant', 'city-pages-generator'),
            'set_featured_image'    => __('Définir l\'image', 'city-pages-generator'),
            'remove_featured_image' => __('Retirer l\'image', 'city-pages-generator'),
            'use_featured_image'    => __('Utiliser comme image', 'city-pages-generator'),
            'insert_into_item'      => __('Insérer dans la page', 'city-pages-generator'),
            'uploaded_to_this_item' => __('Téléversé vers cette page', 'city-pages-generator'),
            'items_list'            => __('Liste des pages', 'city-pages-generator'),
            'items_list_navigation' => __('Navigation', 'city-pages-generator'),
            'filter_items_list'     => __('Filtrer les pages', 'city-pages-generator'),
        ];

        $args = [
            'label'               => __('Page Ville', 'city-pages-generator'),
            'description'         => __('Pages SEO local par ville', 'city-pages-generator'),
            'labels'              => $labels,
            'supports'            => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'taxonomies'          => ['cpg_department', 'cpg_priority'],
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => false, // On l'affiche dans notre menu custom
            'menu_position'       => 26,
            'menu_icon'           => 'dashicons-location-alt',
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => 'metallier-puy-de-dome',
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'show_in_rest'        => true,
            'rest_base'           => 'city-pages',
            'rewrite'             => [
                'slug'       => 'metallier',
                'with_front' => false,
            ],
        ];

        register_post_type('city_page', $args);
    }

    /**
     * Créer une page ville
     */
    public static function create_city_page($data) {
        // Générer le slug
        $slug = sanitize_title($data['city_name']);
        
        // Vérifier si la page existe déjà
        $existing = get_page_by_path($slug, OBJECT, 'city_page');
        if ($existing) {
            return new WP_Error('city_exists', __('Une page existe déjà pour cette ville.', 'city-pages-generator'));
        }

        // Générer le contenu
        $content_generator = new CPG_Content_Generator($data);
        $content = $content_generator->generate();

        // Créer le post
        $post_data = [
            'post_title'   => sprintf('Métallier Serrurier à %s', $data['city_name']),
            'post_name'    => $slug,
            'post_content' => $content,
            'post_status'  => isset($data['post_status']) ? $data['post_status'] : 'draft',
            'post_type'    => 'city_page',
            'post_excerpt' => sprintf(
                'Artisan métallier à %s (%s). Fabrication sur mesure de portails, garde-corps, escaliers. Devis gratuit. Intervention rapide dans le %s.',
                $data['city_name'],
                $data['postal_code'],
                $data['department']
            ),
        ];

        $post_id = wp_insert_post($post_data);

        if (is_wp_error($post_id)) {
            return $post_id;
        }

        // Sauvegarder les métadonnées
        update_post_meta($post_id, '_cpg_city_name', sanitize_text_field($data['city_name']));
        update_post_meta($post_id, '_cpg_postal_code', sanitize_text_field($data['postal_code']));
        update_post_meta($post_id, '_cpg_department', sanitize_text_field($data['department']));
        update_post_meta($post_id, '_cpg_priority', intval($data['priority']));
        update_post_meta($post_id, '_cpg_local_specifics', sanitize_textarea_field($data['local_specifics']));
        update_post_meta($post_id, '_cpg_distance_km', floatval($data['distance_km']));
        update_post_meta($post_id, '_cpg_travel_time', sanitize_text_field($data['travel_time']));
        update_post_meta($post_id, '_cpg_nearby_cities', isset($data['nearby_cities']) ? array_map('sanitize_text_field', $data['nearby_cities']) : []);
        update_post_meta($post_id, '_cpg_content_variation', rand(1, 4)); // Pour les variations de contenu
        update_post_meta($post_id, '_cpg_generated_date', current_time('mysql'));

        // Assigner la taxonomie département
        if (!empty($data['department'])) {
            wp_set_object_terms($post_id, sanitize_title($data['department']), 'cpg_department');
        }

        // Assigner la priorité
        if (!empty($data['priority'])) {
            wp_set_object_terms($post_id, 'priority-' . intval($data['priority']), 'cpg_priority');
        }

        return $post_id;
    }

    /**
     * Mettre à jour une page ville
     */
    public static function update_city_page($post_id, $data, $regenerate_content = false) {
        // Vérifier que le post existe
        $post = get_post($post_id);
        if (!$post || $post->post_type !== 'city_page') {
            return new WP_Error('invalid_post', __('Page ville invalide.', 'city-pages-generator'));
        }

        // Mettre à jour les métadonnées
        if (isset($data['city_name'])) {
            update_post_meta($post_id, '_cpg_city_name', sanitize_text_field($data['city_name']));
        }
        if (isset($data['postal_code'])) {
            update_post_meta($post_id, '_cpg_postal_code', sanitize_text_field($data['postal_code']));
        }
        if (isset($data['department'])) {
            update_post_meta($post_id, '_cpg_department', sanitize_text_field($data['department']));
            wp_set_object_terms($post_id, sanitize_title($data['department']), 'cpg_department');
        }
        if (isset($data['priority'])) {
            update_post_meta($post_id, '_cpg_priority', intval($data['priority']));
            wp_set_object_terms($post_id, 'priority-' . intval($data['priority']), 'cpg_priority');
        }
        if (isset($data['local_specifics'])) {
            update_post_meta($post_id, '_cpg_local_specifics', sanitize_textarea_field($data['local_specifics']));
        }
        if (isset($data['distance_km'])) {
            update_post_meta($post_id, '_cpg_distance_km', floatval($data['distance_km']));
        }
        if (isset($data['travel_time'])) {
            update_post_meta($post_id, '_cpg_travel_time', sanitize_text_field($data['travel_time']));
        }
        if (isset($data['nearby_cities'])) {
            update_post_meta($post_id, '_cpg_nearby_cities', array_map('sanitize_text_field', $data['nearby_cities']));
        }

        // Regénérer le contenu si demandé
        if ($regenerate_content) {
            $full_data = self::get_city_data($post_id);
            $content_generator = new CPG_Content_Generator($full_data);
            $content = $content_generator->generate();

            wp_update_post([
                'ID'           => $post_id,
                'post_content' => $content,
            ]);

            update_post_meta($post_id, '_cpg_content_variation', rand(1, 4));
            update_post_meta($post_id, '_cpg_regenerated_date', current_time('mysql'));
        }

        return $post_id;
    }

    /**
     * Récupérer les données d'une ville
     */
    public static function get_city_data($post_id) {
        $post = get_post($post_id);
        if (!$post || $post->post_type !== 'city_page') {
            return false;
        }

        return [
            'post_id'         => $post_id,
            'city_name'       => get_post_meta($post_id, '_cpg_city_name', true),
            'postal_code'     => get_post_meta($post_id, '_cpg_postal_code', true),
            'department'      => get_post_meta($post_id, '_cpg_department', true),
            'priority'        => get_post_meta($post_id, '_cpg_priority', true),
            'local_specifics' => get_post_meta($post_id, '_cpg_local_specifics', true),
            'distance_km'     => get_post_meta($post_id, '_cpg_distance_km', true),
            'travel_time'     => get_post_meta($post_id, '_cpg_travel_time', true),
            'nearby_cities'   => get_post_meta($post_id, '_cpg_nearby_cities', true) ?: [],
            'variation'       => get_post_meta($post_id, '_cpg_content_variation', true) ?: 1,
        ];
    }

    /**
     * Récupérer toutes les pages ville
     */
    public static function get_all_city_pages($args = []) {
        $defaults = [
            'post_type'      => 'city_page',
            'posts_per_page' => -1,
            'post_status'    => 'any',
            'orderby'        => 'title',
            'order'          => 'ASC',
        ];

        $query_args = wp_parse_args($args, $defaults);
        return get_posts($query_args);
    }
}
