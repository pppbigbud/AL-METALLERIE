<?php
/**
 * Classe PostTypes - Custom Post Types
 *
 * @package TrainingManager
 * @since 1.0.0
 */

namespace TrainingManager;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe PostTypes
 * 
 * Enregistre les Custom Post Types du plugin
 */
class PostTypes {

    /**
     * Enregistrer les Custom Post Types
     */
    public function register(): void {
        $this->register_training_session();
    }

    /**
     * Enregistrer le CPT Training Session
     */
    private function register_training_session(): void {
        $labels = [
            'name'                  => _x('Sessions de Formation', 'Post Type General Name', 'training-manager'),
            'singular_name'         => _x('Session de Formation', 'Post Type Singular Name', 'training-manager'),
            'menu_name'             => __('Formations', 'training-manager'),
            'name_admin_bar'        => __('Session de Formation', 'training-manager'),
            'archives'              => __('Archives des formations', 'training-manager'),
            'attributes'            => __('Attributs de la session', 'training-manager'),
            'parent_item_colon'     => __('Session parente :', 'training-manager'),
            'all_items'             => __('Toutes les sessions', 'training-manager'),
            'add_new_item'          => __('Ajouter une session', 'training-manager'),
            'add_new'               => __('Ajouter', 'training-manager'),
            'new_item'              => __('Nouvelle session', 'training-manager'),
            'edit_item'             => __('Modifier la session', 'training-manager'),
            'update_item'           => __('Mettre à jour la session', 'training-manager'),
            'view_item'             => __('Voir la session', 'training-manager'),
            'view_items'            => __('Voir les sessions', 'training-manager'),
            'search_items'          => __('Rechercher une session', 'training-manager'),
            'not_found'             => __('Aucune session trouvée', 'training-manager'),
            'not_found_in_trash'    => __('Aucune session dans la corbeille', 'training-manager'),
            'featured_image'        => __('Image de la formation', 'training-manager'),
            'set_featured_image'    => __('Définir l\'image', 'training-manager'),
            'remove_featured_image' => __('Supprimer l\'image', 'training-manager'),
            'use_featured_image'    => __('Utiliser comme image', 'training-manager'),
            'insert_into_item'      => __('Insérer dans la session', 'training-manager'),
            'uploaded_to_this_item' => __('Téléversé pour cette session', 'training-manager'),
            'items_list'            => __('Liste des sessions', 'training-manager'),
            'items_list_navigation' => __('Navigation des sessions', 'training-manager'),
            'filter_items_list'     => __('Filtrer les sessions', 'training-manager'),
        ];

        $args = [
            'label'               => __('Session de Formation', 'training-manager'),
            'description'         => __('Sessions de formation pour particuliers et professionnels', 'training-manager'),
            'labels'              => $labels,
            'supports'            => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions'],
            'taxonomies'          => ['training_type', 'training_theme'],
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => 'tm-dashboard', // Afficher dans le menu personnalisé
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => 'formations',
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'show_in_rest'        => true,
            'rest_base'           => 'training-sessions',
            'rewrite'             => [
                'slug'       => 'formation',
                'with_front' => false,
            ],
        ];

        register_post_type('training_session', $args);
    }
}
