<?php
/**
 * Classe Taxonomies - Custom Taxonomies
 *
 * @package TrainingManager
 * @since 1.0.0
 */

namespace TrainingManager;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe Taxonomies
 * 
 * Enregistre les Custom Taxonomies du plugin
 */
class Taxonomies {

    /**
     * Enregistrer les taxonomies
     */
    public function register(): void {
        $this->register_training_type();
        $this->register_training_theme();
        $this->create_default_terms();
    }

    /**
     * Enregistrer la taxonomie Type de Formation
     */
    private function register_training_type(): void {
        $labels = [
            'name'                       => _x('Types de Formation', 'Taxonomy General Name', 'training-manager'),
            'singular_name'              => _x('Type de Formation', 'Taxonomy Singular Name', 'training-manager'),
            'menu_name'                  => __('Types', 'training-manager'),
            'all_items'                  => __('Tous les types', 'training-manager'),
            'parent_item'                => __('Type parent', 'training-manager'),
            'parent_item_colon'          => __('Type parent :', 'training-manager'),
            'new_item_name'              => __('Nouveau type', 'training-manager'),
            'add_new_item'               => __('Ajouter un type', 'training-manager'),
            'edit_item'                  => __('Modifier le type', 'training-manager'),
            'update_item'                => __('Mettre à jour le type', 'training-manager'),
            'view_item'                  => __('Voir le type', 'training-manager'),
            'separate_items_with_commas' => __('Séparer les types par des virgules', 'training-manager'),
            'add_or_remove_items'        => __('Ajouter ou supprimer des types', 'training-manager'),
            'choose_from_most_used'      => __('Choisir parmi les plus utilisés', 'training-manager'),
            'popular_items'              => __('Types populaires', 'training-manager'),
            'search_items'               => __('Rechercher un type', 'training-manager'),
            'not_found'                  => __('Aucun type trouvé', 'training-manager'),
            'no_terms'                   => __('Aucun type', 'training-manager'),
            'items_list'                 => __('Liste des types', 'training-manager'),
            'items_list_navigation'      => __('Navigation des types', 'training-manager'),
        ];

        $args = [
            'labels'            => $labels,
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud'     => false,
            'show_in_rest'      => true,
            'rest_base'         => 'training-types',
            'rewrite'           => [
                'slug'         => 'type-formation',
                'with_front'   => false,
                'hierarchical' => true,
            ],
        ];

        register_taxonomy('training_type', ['training_session'], $args);
    }

    /**
     * Enregistrer la taxonomie Thème de Formation
     */
    private function register_training_theme(): void {
        $labels = [
            'name'                       => _x('Thèmes de Formation', 'Taxonomy General Name', 'training-manager'),
            'singular_name'              => _x('Thème de Formation', 'Taxonomy Singular Name', 'training-manager'),
            'menu_name'                  => __('Thèmes', 'training-manager'),
            'all_items'                  => __('Tous les thèmes', 'training-manager'),
            'parent_item'                => __('Thème parent', 'training-manager'),
            'parent_item_colon'          => __('Thème parent :', 'training-manager'),
            'new_item_name'              => __('Nouveau thème', 'training-manager'),
            'add_new_item'               => __('Ajouter un thème', 'training-manager'),
            'edit_item'                  => __('Modifier le thème', 'training-manager'),
            'update_item'                => __('Mettre à jour le thème', 'training-manager'),
            'view_item'                  => __('Voir le thème', 'training-manager'),
            'separate_items_with_commas' => __('Séparer les thèmes par des virgules', 'training-manager'),
            'add_or_remove_items'        => __('Ajouter ou supprimer des thèmes', 'training-manager'),
            'choose_from_most_used'      => __('Choisir parmi les plus utilisés', 'training-manager'),
            'popular_items'              => __('Thèmes populaires', 'training-manager'),
            'search_items'               => __('Rechercher un thème', 'training-manager'),
            'not_found'                  => __('Aucun thème trouvé', 'training-manager'),
            'no_terms'                   => __('Aucun thème', 'training-manager'),
            'items_list'                 => __('Liste des thèmes', 'training-manager'),
            'items_list_navigation'      => __('Navigation des thèmes', 'training-manager'),
        ];

        $args = [
            'labels'            => $labels,
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud'     => false,
            'show_in_rest'      => true,
            'rest_base'         => 'training-themes',
            'rewrite'           => [
                'slug'         => 'theme-formation',
                'with_front'   => false,
                'hierarchical' => true,
            ],
        ];

        register_taxonomy('training_theme', ['training_session'], $args);
    }

    /**
     * Créer les termes par défaut
     */
    private function create_default_terms(): void {
        // Types de formation
        $types = [
            'particuliers' => [
                'name'        => __('Particuliers', 'training-manager'),
                'description' => __('Formations destinées aux particuliers', 'training-manager'),
            ],
            'professionnels' => [
                'name'        => __('Professionnels', 'training-manager'),
                'description' => __('Formations destinées aux professionnels', 'training-manager'),
            ],
        ];

        foreach ($types as $slug => $data) {
            if (!term_exists($slug, 'training_type')) {
                wp_insert_term($data['name'], 'training_type', [
                    'slug'        => $slug,
                    'description' => $data['description'],
                ]);
            }
        }

        // Thèmes de formation
        $themes = [
            // Particuliers
            'decouverte' => [
                'name'        => __('Découverte', 'training-manager'),
                'description' => __('Initiation à la soudure pour débutants', 'training-manager'),
                'parent'      => 'particuliers',
            ],
            'je-fais-moi-meme' => [
                'name'        => __('Je fais moi-même', 'training-manager'),
                'description' => __('Venez avec votre projet et réalisez-le', 'training-manager'),
                'parent'      => 'particuliers',
            ],
            // Professionnels
            'cap-soudure' => [
                'name'        => __('CAP Soudure', 'training-manager'),
                'description' => __('Préparation au CAP Soudure', 'training-manager'),
                'parent'      => 'professionnels',
            ],
            'perfectionnement' => [
                'name'        => __('Perfectionnement', 'training-manager'),
                'description' => __('Formations de perfectionnement pour professionnels', 'training-manager'),
                'parent'      => 'professionnels',
            ],
            'qualification' => [
                'name'        => __('Qualification', 'training-manager'),
                'description' => __('Formations qualifiantes', 'training-manager'),
                'parent'      => 'professionnels',
            ],
        ];

        // Récupérer les IDs des types parents
        $particuliers_term = get_term_by('slug', 'particuliers', 'training_type');
        $professionnels_term = get_term_by('slug', 'professionnels', 'training_type');

        foreach ($themes as $slug => $data) {
            if (!term_exists($slug, 'training_theme')) {
                $parent_id = 0;
                
                // Associer au bon type (pour info, pas de parent direct car taxonomies différentes)
                wp_insert_term($data['name'], 'training_theme', [
                    'slug'        => $slug,
                    'description' => $data['description'],
                ]);
            }
        }
    }
}
