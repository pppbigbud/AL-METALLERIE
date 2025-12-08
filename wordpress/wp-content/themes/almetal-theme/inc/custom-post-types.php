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
        'rewrite'               => false, // Désactivé - on gère manuellement les URLs
    );

    register_post_type('realisation', $args);
}
add_action('init', 'almetal_register_realisations_cpt', 0);

/**
 * Générer les URLs personnalisées pour les réalisations
 * Format: /realisations/categories-ville-jour-mois-annee
 */
function almetal_realisation_custom_permalink($permalink, $post, $leavename) {
    // Uniquement pour les réalisations
    if (!is_object($post) || $post->post_type !== 'realisation') {
        return $permalink;
    }
    
    // Récupérer les catégories (types de réalisation)
    $terms = get_the_terms($post->ID, 'type_realisation');
    $categories_slug = '';
    
    if (!empty($terms) && !is_wp_error($terms)) {
        $slugs = array();
        foreach ($terms as $term) {
            $slugs[] = $term->slug;
        }
        // Trier alphabétiquement pour cohérence
        sort($slugs);
        $categories_slug = implode('-', $slugs);
    } else {
        $categories_slug = 'realisation';
    }
    
    // Récupérer la ville
    $ville = get_post_meta($post->ID, '_almetal_lieu', true);
    if (empty($ville)) {
        $ville = 'france';
    }
    // Nettoyer la ville pour l'URL
    $ville_slug = sanitize_title($ville);
    
    // Récupérer la date de réalisation ou la date de publication
    $date_realisation = get_post_meta($post->ID, '_almetal_date_realisation', true);
    if (!empty($date_realisation)) {
        $timestamp = strtotime($date_realisation);
    } else {
        $timestamp = strtotime($post->post_date);
    }
    $date_slug = date('d-m-Y', $timestamp);
    
    // Construire l'URL finale (sans le titre/slug du post)
    $custom_permalink = home_url('/realisations/' . $categories_slug . '-' . $ville_slug . '-' . $date_slug . '/');
    
    return $custom_permalink;
}
add_filter('post_type_link', 'almetal_realisation_custom_permalink', 10, 3);

/**
 * Ajouter les règles de réécriture pour les URLs personnalisées
 */
function almetal_realisation_rewrite_rules() {
    // Règle pour l'archive des réalisations
    add_rewrite_rule(
        '^realisations/?$',
        'index.php?post_type=realisation',
        'top'
    );
    
    // Règle pour capturer: /realisations/[categories]-[ville]-[date]
    add_rewrite_rule(
        '^realisations/([^/]+)/?$',
        'index.php?post_type=realisation&realisation_custom_slug=$matches[1]',
        'top'
    );
}
add_action('init', 'almetal_realisation_rewrite_rules', 10);

/**
 * Rediriger ?post_type=realisation vers /realisations/
 * Pour éviter le contenu dupliqué
 */
function almetal_redirect_realisation_archive() {
    if (isset($_GET['post_type']) && $_GET['post_type'] === 'realisation' && !is_admin()) {
        wp_redirect(home_url('/realisations/'), 301);
        exit;
    }
}
add_action('template_redirect', 'almetal_redirect_realisation_archive');

/**
 * Enregistrer la query var personnalisée
 */
function almetal_realisation_query_vars($vars) {
    $vars[] = 'realisation_custom_slug';
    return $vars;
}
add_filter('query_vars', 'almetal_realisation_query_vars');

/**
 * Résoudre l'URL personnalisée vers le bon post
 */
function almetal_realisation_parse_request($wp) {
    if (!isset($wp->query_vars['realisation_custom_slug'])) {
        return;
    }
    
    $custom_slug = $wp->query_vars['realisation_custom_slug'];
    
    // Extraire la date (format: dd-mm-yyyy à la fin)
    if (preg_match('/^(.+)-(\d{2}-\d{2}-\d{4})$/', $custom_slug, $matches)) {
        $slug_without_date = $matches[1];
        $date_str = $matches[2];
        
        // Convertir la date
        $date_parts = explode('-', $date_str);
        $date_formatted = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0]; // Y-m-d
        
        // Rechercher le post correspondant
        $args = array(
            'post_type' => 'realisation',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        );
        
        $posts = get_posts($args);
        
        foreach ($posts as $post) {
            // Reconstruire le slug attendu pour ce post
            $terms = get_the_terms($post->ID, 'type_realisation');
            $categories_slug = '';
            
            if (!empty($terms) && !is_wp_error($terms)) {
                $slugs = array();
                foreach ($terms as $term) {
                    $slugs[] = $term->slug;
                }
                sort($slugs);
                $categories_slug = implode('-', $slugs);
            } else {
                $categories_slug = 'realisation';
            }
            
            $ville = get_post_meta($post->ID, '_almetal_lieu', true);
            $ville_slug = !empty($ville) ? sanitize_title($ville) : 'france';
            
            $expected_slug = $categories_slug . '-' . $ville_slug;
            
            // Vérifier la date
            $post_date = get_post_meta($post->ID, '_almetal_date_realisation', true);
            if (empty($post_date)) {
                $post_date = $post->post_date;
            }
            $post_date_formatted = date('Y-m-d', strtotime($post_date));
            
            if ($expected_slug === $slug_without_date && $post_date_formatted === $date_formatted) {
                // Trouvé ! Rediriger vers ce post
                $wp->query_vars = array(
                    'post_type' => 'realisation',
                    'p' => $post->ID,
                    'name' => $post->post_name,
                );
                unset($wp->query_vars['realisation_custom_slug']);
                return;
            }
        }
    }
}
add_action('parse_request', 'almetal_realisation_parse_request');

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
    
    // Récupération des valeurs existantes
    $client_type = get_post_meta($post->ID, '_almetal_client_type', true);
    $client_nom = get_post_meta($post->ID, '_almetal_client_nom', true);
    $client_url = get_post_meta($post->ID, '_almetal_client_url', true);
    $date_realisation = get_post_meta($post->ID, '_almetal_date_realisation', true);
    $lieu = get_post_meta($post->ID, '_almetal_lieu', true);
    $duree = get_post_meta($post->ID, '_almetal_duree', true);
    $matiere = get_post_meta($post->ID, '_almetal_matiere', true);
    $peinture = get_post_meta($post->ID, '_almetal_peinture', true);
    $pose = get_post_meta($post->ID, '_almetal_pose', true);
    $facebook_id = get_post_meta($post->ID, '_almetal_facebook_id', true);
    
    // Migration de l'ancien champ client si nécessaire
    $old_client = get_post_meta($post->ID, '_almetal_client', true);
    if ($old_client && !$client_type) {
        $client_type = 'particulier';
    }
    ?>
    
    <style>
        .almetal-metabox-section { margin-bottom: 20px; padding: 15px; background: #f9f9f9; border-radius: 5px; }
        .almetal-metabox-section h4 { margin: 0 0 15px 0; padding-bottom: 10px; border-bottom: 1px solid #ddd; }
        .almetal-pro-fields { display: none; margin-top: 15px; padding: 15px; background: #fff; border: 1px solid #ddd; border-radius: 5px; }
        .almetal-pro-fields.visible { display: block; }
        .almetal-checkbox-row { display: flex; align-items: center; gap: 10px; }
        .almetal-checkbox-row input[type="checkbox"] { width: 18px; height: 18px; }
    </style>
    
    <table class="form-table">
        <!-- Section Client -->
        <tr>
            <th><label for="almetal_client_type"><?php _e('Type de client', 'almetal'); ?></label></th>
            <td>
                <select id="almetal_client_type" name="almetal_client_type" style="min-width: 200px;">
                    <option value=""><?php _e('-- Sélectionner --', 'almetal'); ?></option>
                    <option value="particulier" <?php selected($client_type, 'particulier'); ?>><?php _e('Particulier', 'almetal'); ?></option>
                    <option value="professionnel" <?php selected($client_type, 'professionnel'); ?>><?php _e('Professionnel', 'almetal'); ?></option>
                </select>
                
                <div id="almetal_pro_fields" class="almetal-pro-fields <?php echo ($client_type === 'professionnel') ? 'visible' : ''; ?>">
                    <p>
                        <label for="almetal_client_nom"><strong><?php _e('Nom de l\'entreprise', 'almetal'); ?></strong></label><br>
                        <input type="text" id="almetal_client_nom" name="almetal_client_nom" value="<?php echo esc_attr($client_nom); ?>" class="regular-text" placeholder="<?php _e('Ex: Société Dupont', 'almetal'); ?>">
                    </p>
                    <p>
                        <label for="almetal_client_url"><strong><?php _e('Site web du client', 'almetal'); ?></strong></label><br>
                        <input type="url" id="almetal_client_url" name="almetal_client_url" value="<?php echo esc_attr($client_url); ?>" class="regular-text" placeholder="https://www.exemple.com">
                    </p>
                </div>
            </td>
        </tr>
        
        <!-- Matière utilisée -->
        <tr>
            <th><label for="almetal_matiere"><?php _e('Matière utilisée', 'almetal'); ?></label></th>
            <td>
                <select id="almetal_matiere" name="almetal_matiere" style="min-width: 200px;">
                    <option value=""><?php _e('-- Sélectionner --', 'almetal'); ?></option>
                    <option value="acier" <?php selected($matiere, 'acier'); ?>><?php _e('Acier', 'almetal'); ?></option>
                    <option value="inox" <?php selected($matiere, 'inox'); ?>><?php _e('Inox', 'almetal'); ?></option>
                    <option value="aluminium" <?php selected($matiere, 'aluminium'); ?>><?php _e('Aluminium', 'almetal'); ?></option>
                    <option value="cuivre" <?php selected($matiere, 'cuivre'); ?>><?php _e('Cuivre', 'almetal'); ?></option>
                    <option value="laiton" <?php selected($matiere, 'laiton'); ?>><?php _e('Laiton', 'almetal'); ?></option>
                    <option value="fer-forge" <?php selected($matiere, 'fer-forge'); ?>><?php _e('Fer forgé', 'almetal'); ?></option>
                    <option value="mixte" <?php selected($matiere, 'mixte'); ?>><?php _e('Mixte (plusieurs matières)', 'almetal'); ?></option>
                </select>
                <p class="description"><?php _e('Matière principale utilisée pour cette réalisation', 'almetal'); ?></p>
            </td>
        </tr>
        
        <!-- Finition peinture -->
        <tr>
            <th><label for="almetal_peinture"><?php _e('Finition peinture', 'almetal'); ?></label></th>
            <td>
                <input type="text" id="almetal_peinture" name="almetal_peinture" value="<?php echo esc_attr($peinture); ?>" class="regular-text" placeholder="<?php _e('Ex: RAL 7016, Noir mat, Thermolaquage blanc...', 'almetal'); ?>">
                <p class="description"><?php _e('Indiquer la finition peinture si applicable (laisser vide si pas de peinture)', 'almetal'); ?></p>
            </td>
        </tr>
        
        <!-- Pose effectuée -->
        <tr>
            <th><label><?php _e('Pose effectuée', 'almetal'); ?></label></th>
            <td>
                <div class="almetal-checkbox-row">
                    <input type="checkbox" id="almetal_pose" name="almetal_pose" value="1" <?php checked($pose, '1'); ?>>
                    <label for="almetal_pose"><?php _e('Oui, la pose a été réalisée par AL Métallerie', 'almetal'); ?></label>
                </div>
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
    
    <script>
    jQuery(document).ready(function($) {
        // Afficher/masquer les champs professionnels
        $('#almetal_client_type').on('change', function() {
            if ($(this).val() === 'professionnel') {
                $('#almetal_pro_fields').addClass('visible');
            } else {
                $('#almetal_pro_fields').removeClass('visible');
            }
        });
    });
    </script>
    
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

    // Sauvegarder les champs texte
    $text_fields = array(
        'almetal_client_type',
        'almetal_client_nom',
        'almetal_date_realisation', 
        'almetal_lieu', 
        'almetal_duree',
        'almetal_matiere',
        'almetal_peinture',
        'almetal_facebook_id'
    );
    
    foreach ($text_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
    
    // Sauvegarder l'URL du client (avec validation URL)
    if (isset($_POST['almetal_client_url'])) {
        update_post_meta($post_id, '_almetal_client_url', esc_url_raw($_POST['almetal_client_url']));
    }
    
    // Sauvegarder la checkbox pose
    $pose_value = isset($_POST['almetal_pose']) ? '1' : '0';
    update_post_meta($post_id, '_almetal_pose', $pose_value);
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
                echo get_the_post_thumbnail($post_id, 'thumbnail', array(
                    'style' => 'width: 60px; height: 60px; object-fit: cover; border-radius: 4px;'
                ));
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

/**
 * CSS admin pour les miniatures des réalisations
 */
function almetal_admin_realisation_styles() {
    global $typenow;
    if ($typenow === 'realisation') {
        ?>
        <style>
            .column-thumbnail {
                width: 80px !important;
            }
            .column-thumbnail img {
                width: 60px !important;
                height: 60px !important;
                object-fit: cover !important;
                border-radius: 4px !important;
            }
        </style>
        <?php
    }
}
add_action('admin_head', 'almetal_admin_realisation_styles');
