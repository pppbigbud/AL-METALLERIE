<?php
/**
 * Taxonomies pour les pages ville
 *
 * @package CityPagesGenerator
 */

if (!defined('ABSPATH')) {
    exit;
}

class CPG_Taxonomy {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('init', [$this, 'register_taxonomy']);
        add_action('init', [$this, 'register_realisation_taxonomy']);
    }

    /**
     * Enregistrer les taxonomies
     */
    public function register_taxonomy() {
        // Taxonomie Département
        $department_labels = [
            'name'              => __('Départements', 'city-pages-generator'),
            'singular_name'     => __('Département', 'city-pages-generator'),
            'search_items'      => __('Rechercher', 'city-pages-generator'),
            'all_items'         => __('Tous les départements', 'city-pages-generator'),
            'parent_item'       => __('Département parent', 'city-pages-generator'),
            'parent_item_colon' => __('Département parent :', 'city-pages-generator'),
            'edit_item'         => __('Modifier', 'city-pages-generator'),
            'update_item'       => __('Mettre à jour', 'city-pages-generator'),
            'add_new_item'      => __('Ajouter un département', 'city-pages-generator'),
            'new_item_name'     => __('Nouveau département', 'city-pages-generator'),
            'menu_name'         => __('Départements', 'city-pages-generator'),
        ];

        register_taxonomy('cpg_department', ['city_page'], [
            'hierarchical'      => true,
            'labels'            => $department_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'show_in_rest'      => true,
            'rewrite'           => ['slug' => 'metallier-departement'],
        ]);

        // Taxonomie Priorité
        $priority_labels = [
            'name'              => __('Priorités', 'city-pages-generator'),
            'singular_name'     => __('Priorité', 'city-pages-generator'),
            'search_items'      => __('Rechercher', 'city-pages-generator'),
            'all_items'         => __('Toutes les priorités', 'city-pages-generator'),
            'edit_item'         => __('Modifier', 'city-pages-generator'),
            'update_item'       => __('Mettre à jour', 'city-pages-generator'),
            'add_new_item'      => __('Ajouter une priorité', 'city-pages-generator'),
            'new_item_name'     => __('Nouvelle priorité', 'city-pages-generator'),
            'menu_name'         => __('Priorités', 'city-pages-generator'),
        ];

        register_taxonomy('cpg_priority', ['city_page'], [
            'hierarchical'      => false,
            'labels'            => $priority_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'show_in_rest'      => true,
            'rewrite'           => false,
        ]);

        // Créer les termes de priorité par défaut
        $this->create_default_priority_terms();
        
        // Créer les départements par défaut
        $this->create_default_departments();
    }

    /**
     * Enregistrer la taxonomie ville pour les réalisations
     */
    public function register_realisation_taxonomy() {
        // Vérifier si le CPT realisation existe
        if (!post_type_exists('realisation')) {
            return;
        }

        $labels = [
            'name'              => __('Villes (Réalisations)', 'city-pages-generator'),
            'singular_name'     => __('Ville', 'city-pages-generator'),
            'search_items'      => __('Rechercher une ville', 'city-pages-generator'),
            'all_items'         => __('Toutes les villes', 'city-pages-generator'),
            'edit_item'         => __('Modifier la ville', 'city-pages-generator'),
            'update_item'       => __('Mettre à jour', 'city-pages-generator'),
            'add_new_item'      => __('Ajouter une ville', 'city-pages-generator'),
            'new_item_name'     => __('Nouvelle ville', 'city-pages-generator'),
            'menu_name'         => __('Villes', 'city-pages-generator'),
        ];

        register_taxonomy('realisation_city', ['realisation'], [
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'show_in_rest'      => true,
            'rewrite'           => ['slug' => 'realisation-ville'],
        ]);
    }

    /**
     * Créer les termes de priorité par défaut
     */
    private function create_default_priority_terms() {
        $priorities = [
            'priority-1' => __('Priorité 1 - Haute', 'city-pages-generator'),
            'priority-2' => __('Priorité 2 - Moyenne', 'city-pages-generator'),
            'priority-3' => __('Priorité 3 - Basse', 'city-pages-generator'),
        ];

        foreach ($priorities as $slug => $name) {
            if (!term_exists($slug, 'cpg_priority')) {
                wp_insert_term($name, 'cpg_priority', ['slug' => $slug]);
            }
        }
    }

    /**
     * Créer les départements par défaut (Auvergne)
     */
    private function create_default_departments() {
        $departments = [
            'puy-de-dome' => [
                'name' => 'Puy-de-Dôme (63)',
                'description' => 'Département du Puy-de-Dôme en Auvergne-Rhône-Alpes'
            ],
            'allier' => [
                'name' => 'Allier (03)',
                'description' => 'Département de l\'Allier en Auvergne-Rhône-Alpes'
            ],
            'cantal' => [
                'name' => 'Cantal (15)',
                'description' => 'Département du Cantal en Auvergne-Rhône-Alpes'
            ],
            'haute-loire' => [
                'name' => 'Haute-Loire (43)',
                'description' => 'Département de la Haute-Loire en Auvergne-Rhône-Alpes'
            ],
            'loire' => [
                'name' => 'Loire (42)',
                'description' => 'Département de la Loire en Auvergne-Rhône-Alpes'
            ],
        ];

        foreach ($departments as $slug => $data) {
            if (!term_exists($slug, 'cpg_department')) {
                wp_insert_term($data['name'], 'cpg_department', [
                    'slug' => $slug,
                    'description' => $data['description']
                ]);
            }
        }
    }
}
