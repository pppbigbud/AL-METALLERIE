<?php
/**
 * Custom Post Types pour AL Métallerie
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enregistrer le Custom Post Type "Réalisations"
 */
function almetal_register_realisations_cpt() {
    $labels = array(
        'name'                  => _x('Réalisations', 'Post Type General Name', 'almetal'),
        'singular_name'         => _x('Réalisation', 'Post Type Singular Name', 'almetal'),
        'menu_name'             => __('Réalisations', 'almetal'),
        'name_admin_bar'        => __('Réalisation', 'almetal'),
        'archives'              => __('Archives des réalisations', 'almetal'),
        'attributes'            => __('Attributs de la réalisation', 'almetal'),
        'parent_item_colon'     => __('Réalisation parente:', 'almetal'),
        'all_items'             => __('Toutes les réalisations', 'almetal'),
        'add_new_item'          => __('Ajouter une réalisation', 'almetal'),
        'add_new'               => __('Ajouter', 'almetal'),
        'new_item'              => __('Nouvelle réalisation', 'almetal'),
        'edit_item'             => __('Modifier la réalisation', 'almetal'),
        'update_item'           => __('Mettre à jour', 'almetal'),
        'view_item'             => __('Voir la réalisation', 'almetal'),
        'view_items'            => __('Voir les réalisations', 'almetal'),
        'search_items'          => __('Rechercher une réalisation', 'almetal'),
        'not_found'             => __('Aucune réalisation trouvée', 'almetal'),
        'not_found_in_trash'    => __('Aucune réalisation dans la corbeille', 'almetal'),
        'featured_image'        => __('Image principale', 'almetal'),
        'set_featured_image'    => __('Définir l\'image principale', 'almetal'),
        'remove_featured_image' => __('Retirer l\'image principale', 'almetal'),
        'use_featured_image'    => __('Utiliser comme image principale', 'almetal'),
        'insert_into_item'      => __('Insérer dans la réalisation', 'almetal'),
        'uploaded_to_this_item' => __('Téléchargé pour cette réalisation', 'almetal'),
        'items_list'            => __('Liste des réalisations', 'almetal'),
        'items_list_navigation' => __('Navigation des réalisations', 'almetal'),
        'filter_items_list'     => __('Filtrer les réalisations', 'almetal'),
    );

    $args = array(
        'label'                 => __('Réalisation', 'almetal'),
        'description'           => __('Projets et réalisations de métallerie', 'almetal'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'taxonomies'            => array('type_realisation'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-hammer',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true, // Support Gutenberg
        'rewrite'               => array('slug' => 'realisations'),
    );

    register_post_type('realisation', $args);
}
add_action('init', 'almetal_register_realisations_cpt', 0);

/**
 * Enregistrer la taxonomie "Type de réalisation"
 */
function almetal_register_type_realisation_taxonomy() {
    $labels = array(
        'name'                       => _x('Types de réalisation', 'Taxonomy General Name', 'almetal'),
        'singular_name'              => _x('Type de réalisation', 'Taxonomy Singular Name', 'almetal'),
        'menu_name'                  => __('Types', 'almetal'),
        'all_items'                  => __('Tous les types', 'almetal'),
        'parent_item'                => __('Type parent', 'almetal'),
        'parent_item_colon'          => __('Type parent:', 'almetal'),
        'new_item_name'              => __('Nouveau type', 'almetal'),
        'add_new_item'               => __('Ajouter un type', 'almetal'),
        'edit_item'                  => __('Modifier le type', 'almetal'),
        'update_item'                => __('Mettre à jour', 'almetal'),
        'view_item'                  => __('Voir le type', 'almetal'),
        'separate_items_with_commas' => __('Séparer les types par des virgules', 'almetal'),
        'add_or_remove_items'        => __('Ajouter ou retirer des types', 'almetal'),
        'choose_from_most_used'      => __('Choisir parmi les plus utilisés', 'almetal'),
        'popular_items'              => __('Types populaires', 'almetal'),
        'search_items'               => __('Rechercher un type', 'almetal'),
        'not_found'                  => __('Aucun type trouvé', 'almetal'),
        'no_terms'                   => __('Aucun type', 'almetal'),
        'items_list'                 => __('Liste des types', 'almetal'),
        'items_list_navigation'      => __('Navigation des types', 'almetal'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rewrite'                    => array('slug' => 'type-realisation'),
    );

    register_taxonomy('type_realisation', array('realisation'), $args);
}
add_action('init', 'almetal_register_type_realisation_taxonomy', 0);

/**
 * Créer les types de réalisation par défaut
 */
function almetal_create_default_realisation_types() {
    // Vérifier si les termes existent déjà
    if (!term_exists('Portails', 'type_realisation')) {
        $default_types = array(
            'Portails',
            'Garde-corps',
            'Escaliers',
            'Rampes',
            'Grilles',
            'Pergolas',
            'Mobilier métallique',
            'Ferronnerie d\'art',
            'Serrurerie',
            'Autres',
        );

        foreach ($default_types as $type) {
            wp_insert_term($type, 'type_realisation');
        }
    }
}
add_action('init', 'almetal_create_default_realisation_types', 1);

/**
 * Ajouter des meta boxes personnalisées pour les réalisations
 */
function almetal_add_realisation_meta_boxes() {
    add_meta_box(
        'almetal_realisation_details',
        __('Détails de la réalisation', 'almetal'),
        'almetal_realisation_details_callback',
        'realisation',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'almetal_add_realisation_meta_boxes');

/**
 * Callback pour la meta box des détails
 */
function almetal_realisation_details_callback($post) {
    wp_nonce_field('almetal_realisation_details_nonce', 'almetal_realisation_details_nonce');
    
    $client = get_post_meta($post->ID, '_almetal_client', true);
    $date_realisation = get_post_meta($post->ID, '_almetal_date_realisation', true);
    $lieu = get_post_meta($post->ID, '_almetal_lieu', true);
    $duree = get_post_meta($post->ID, '_almetal_duree', true);
    $facebook_id = get_post_meta($post->ID, '_almetal_facebook_id', true);
    ?>
    
    <table class="form-table">
        <tr>
            <th><label for="almetal_client"><?php _e('Client', 'almetal'); ?></label></th>
            <td>
                <input type="text" id="almetal_client" name="almetal_client" value="<?php echo esc_attr($client); ?>" class="regular-text">
                <p class="description"><?php _e('Nom du client (optionnel)', 'almetal'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="almetal_date_realisation"><?php _e('Date de réalisation', 'almetal'); ?></label></th>
            <td>
                <input type="date" id="almetal_date_realisation" name="almetal_date_realisation" value="<?php echo esc_attr($date_realisation); ?>">
            </td>
        </tr>
        <tr>
            <th><label for="almetal_lieu"><?php _e('Lieu', 'almetal'); ?></label></th>
            <td>
                <input type="text" id="almetal_lieu" name="almetal_lieu" value="<?php echo esc_attr($lieu); ?>" class="regular-text">
                <p class="description"><?php _e('Ville ou région', 'almetal'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="almetal_duree"><?php _e('Durée du projet', 'almetal'); ?></label></th>
            <td>
                <input type="text" id="almetal_duree" name="almetal_duree" value="<?php echo esc_attr($duree); ?>" class="regular-text">
                <p class="description"><?php _e('Ex: 2 semaines, 1 mois...', 'almetal'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="almetal_facebook_id"><?php _e('ID Facebook', 'almetal'); ?></label></th>
            <td>
                <input type="text" id="almetal_facebook_id" name="almetal_facebook_id" value="<?php echo esc_attr($facebook_id); ?>" class="regular-text" readonly>
                <p class="description"><?php _e('ID de la publication Facebook (rempli automatiquement lors de l\'import)', 'almetal'); ?></p>
            </td>
        </tr>
    </table>
    
    <?php
}

/**
 * Sauvegarder les meta données
 */
function almetal_save_realisation_meta($post_id) {
    // Vérifications de sécurité
    if (!isset($_POST['almetal_realisation_details_nonce'])) {
        return;
    }
    if (!wp_verify_nonce($_POST['almetal_realisation_details_nonce'], 'almetal_realisation_details_nonce')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Sauvegarder les champs
    $fields = array('almetal_client', 'almetal_date_realisation', 'almetal_lieu', 'almetal_duree', 'almetal_facebook_id');
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post_realisation', 'almetal_save_realisation_meta');

/**
 * Personnaliser les colonnes dans l'admin
 */
function almetal_realisation_columns($columns) {
    $new_columns = array(
        'cb' => $columns['cb'],
        'thumbnail' => __('Image', 'almetal'),
        'title' => $columns['title'],
        'type_realisation' => __('Type', 'almetal'),
        'date_realisation' => __('Date réalisation', 'almetal'),
        'lieu' => __('Lieu', 'almetal'),
        'date' => $columns['date'],
    );
    return $new_columns;
}
add_filter('manage_realisation_posts_columns', 'almetal_realisation_columns');

/**
 * Remplir les colonnes personnalisées
 */
function almetal_realisation_custom_column($column, $post_id) {
    switch ($column) {
        case 'thumbnail':
            if (has_post_thumbnail($post_id)) {
                echo get_the_post_thumbnail($post_id, array(80, 80));
            } else {
                echo '—';
            }
            break;
        case 'date_realisation':
            $date = get_post_meta($post_id, '_almetal_date_realisation', true);
            echo $date ? date_i18n(get_option('date_format'), strtotime($date)) : '—';
            break;
        case 'lieu':
            $lieu = get_post_meta($post_id, '_almetal_lieu', true);
            echo $lieu ? esc_html($lieu) : '—';
            break;
    }
}
add_action('manage_realisation_posts_custom_column', 'almetal_realisation_custom_column', 10, 2);

/**
 * Rendre les colonnes triables
 */
function almetal_realisation_sortable_columns($columns) {
    $columns['date_realisation'] = 'date_realisation';
    $columns['lieu'] = 'lieu';
    return $columns;
}
add_filter('manage_edit-realisation_sortable_columns', 'almetal_realisation_sortable_columns');
