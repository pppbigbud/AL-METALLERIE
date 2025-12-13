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
    
    // Créer automatiquement une page ville si le lieu n'existe pas
    if (isset($_POST['almetal_lieu']) && !empty($_POST['almetal_lieu'])) {
        $lieu = sanitize_text_field($_POST['almetal_lieu']);
        almetal_maybe_create_city_page($lieu);
    }
}
add_action('save_post_realisation', 'almetal_save_realisation_meta');

/**
 * Créer automatiquement une page ville si elle n'existe pas
 * 
 * @param string $city_name Nom de la ville
 * @return int|false ID de la page créée ou false si déjà existante
 */
function almetal_maybe_create_city_page($city_name) {
    $city_name = trim($city_name);
    if (empty($city_name)) {
        return false;
    }
    
    // Vérifier si le CPT city_page existe
    if (!post_type_exists('city_page')) {
        return false;
    }
    
    // Vérifier si une page ville existe déjà pour ce lieu (via meta _cpg_city_name)
    $existing = get_posts(array(
        'post_type' => 'city_page',
        'posts_per_page' => 1,
        'post_status' => array('publish', 'draft', 'pending'),
        'meta_query' => array(
            array(
                'key' => '_cpg_city_name',
                'value' => $city_name,
                'compare' => '=',
            ),
        ),
    ));
    
    if (!empty($existing)) {
        return false; // Page existe déjà
    }
    
    // Vérifier aussi par titre (fallback)
    $existing_by_title = get_posts(array(
        'post_type' => 'city_page',
        'posts_per_page' => 1,
        'post_status' => array('publish', 'draft', 'pending'),
        'title' => 'Métallier Serrurier à ' . $city_name,
    ));
    
    if (!empty($existing_by_title)) {
        return false;
    }
    
    // Générer le contenu automatique
    $content = almetal_generate_city_page_content($city_name);
    
    // Créer la page ville
    $post_data = array(
        'post_type' => 'city_page',
        'post_status' => 'draft', // En brouillon pour révision
        'post_title' => 'Métallier Serrurier à ' . $city_name,
        'post_name' => 'metallier-' . sanitize_title($city_name),
        'post_content' => $content,
    );
    
    $post_id = wp_insert_post($post_data);
    
    if (is_wp_error($post_id)) {
        return false;
    }
    
    // Ajouter les meta données
    update_post_meta($post_id, '_cpg_city_name', $city_name);
    update_post_meta($post_id, '_cpg_department', 'Puy-de-Dôme'); // Département par défaut
    update_post_meta($post_id, '_cpg_auto_created', '1'); // Marqueur de création auto
    update_post_meta($post_id, '_cpg_created_from_realisation', '1');
    
    // Log pour debug (optionnel)
    error_log('[AL Métallerie] Page ville créée automatiquement: ' . $city_name . ' (ID: ' . $post_id . ')');
    
    return $post_id;
}

/**
 * Générer le contenu automatique pour une page ville
 * 
 * @param string $city_name Nom de la ville
 * @return string Contenu HTML généré
 */
function almetal_generate_city_page_content($city_name) {
    $city = esc_html($city_name);
    
    // Variations pour éviter le contenu dupliqué
    $variations = array(
        array(
            'intro' => "Vous recherchez un <strong>métallier serrurier professionnel à {$city}</strong> ? AL Métallerie & Soudure, artisan basé à Peschadoires (63), intervient dans votre commune pour tous vos projets de métallerie sur mesure.",
            'expertise' => "Notre expertise en ferronnerie et soudure nous permet de réaliser des ouvrages uniques, parfaitement adaptés à votre habitat ou votre entreprise.",
        ),
        array(
            'intro' => "Besoin d'un <strong>artisan métallier à {$city}</strong> pour votre projet ? AL Métallerie & Soudure vous accompagne dans la conception et la réalisation de vos ouvrages métalliques sur mesure.",
            'expertise' => "Depuis notre atelier de Peschadoires, nous intervenons rapidement à {$city} et ses environs pour des créations personnalisées de qualité.",
        ),
        array(
            'intro' => "<strong>Métallier ferronnier intervenant à {$city}</strong>, AL Métallerie & Soudure met son savoir-faire artisanal au service de vos projets. Portails, garde-corps, escaliers : nous créons des pièces uniques.",
            'expertise' => "Notre proximité avec {$city} nous permet d'assurer un suivi personnalisé de votre projet, de la conception à la pose.",
        ),
        array(
            'intro' => "AL Métallerie & Soudure, votre <strong>serrurier métallier de confiance à {$city}</strong>. Nous réalisons tous types d'ouvrages en métal : portails, clôtures, garde-corps, escaliers et ferronnerie d'art.",
            'expertise' => "Artisan passionné, nous privilégions la qualité et le sur-mesure pour chaque réalisation à {$city}.",
        ),
    );
    
    $v = $variations[array_rand($variations)];
    
    $content = "
<p>{$v['intro']}</p>

<p>{$v['expertise']}</p>

<h2>Nos services de métallerie à {$city}</h2>

<p>Nous proposons une gamme complète de services pour répondre à tous vos besoins :</p>

<ul>
<li><strong>Portails et portillons</strong> : battants, coulissants, motorisés</li>
<li><strong>Garde-corps et rampes</strong> : intérieurs et extérieurs, design contemporain ou classique</li>
<li><strong>Escaliers métalliques</strong> : droits, quart tournant, hélicoïdaux</li>
<li><strong>Pergolas et auvents</strong> : structures sur mesure pour vos espaces extérieurs</li>
<li><strong>Grilles et clôtures</strong> : sécurisation de votre propriété</li>
<li><strong>Ferronnerie d'art</strong> : pièces décoratives uniques</li>
<li><strong>Serrurerie</strong> : dépannage et installation</li>
</ul>

<h2>Pourquoi choisir AL Métallerie à {$city} ?</h2>

<ul>
<li><strong>Fabrication française</strong> : Tout est conçu et fabriqué dans notre atelier</li>
<li><strong>Sur mesure uniquement</strong> : Chaque projet est unique et personnalisé</li>
<li><strong>Devis gratuit</strong> : Étude de votre projet sans engagement</li>
<li><strong>Pose incluse</strong> : Installation professionnelle par nos soins</li>
<li><strong>Garantie décennale</strong> : Travail assuré et garanti</li>
<li><strong>Proximité</strong> : Intervention rapide à {$city} et environs</li>
</ul>

<h2>Zone d'intervention autour de {$city}</h2>

<p>Basés à Peschadoires dans le Puy-de-Dôme (63), nous intervenons dans un rayon de 50 km, incluant {$city} et les communes environnantes. Notre connaissance du terrain local nous permet d'adapter nos réalisations aux spécificités architecturales de votre région.</p>

<h2>Demandez votre devis gratuit</h2>

<p>Vous avez un projet de métallerie à {$city} ? Contactez-nous pour obtenir un devis gratuit et personnalisé. Nous nous déplaçons chez vous pour étudier votre projet et vous proposer la solution la plus adaptée.</p>

<p><strong>Téléphone :</strong> 06 73 33 35 32<br>
<strong>Email :</strong> contact@al-metallerie.fr</p>
";

    return trim($content);
}

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
                echo '<div style="width:60px;height:60px;overflow:hidden;">';
                echo get_the_post_thumbnail($post_id, 'thumbnail', array('style' => 'width:60px;height:60px;object-fit:cover;'));
                echo '</div>';
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
 * ============================================
 * CUSTOM POST TYPE : MATIÈRES
 * Pages dédiées aux matériaux (Acier, Inox, Aluminium, Fer forgé)
 * ============================================
 */

/**
 * Enregistrer le Custom Post Type "Matière"
 */
function almetal_register_matiere_cpt() {
    $labels = array(
        'name'                  => _x('Matières', 'Post Type General Name', 'almetal'),
        'singular_name'         => _x('Matière', 'Post Type Singular Name', 'almetal'),
        'menu_name'             => __('Matières', 'almetal'),
        'name_admin_bar'        => __('Matière', 'almetal'),
        'archives'              => __('Archives des matières', 'almetal'),
        'attributes'            => __('Attributs de la matière', 'almetal'),
        'parent_item_colon'     => __('Matière parente:', 'almetal'),
        'all_items'             => __('Toutes les matières', 'almetal'),
        'add_new_item'          => __('Ajouter une matière', 'almetal'),
        'add_new'               => __('Ajouter', 'almetal'),
        'new_item'              => __('Nouvelle matière', 'almetal'),
        'edit_item'             => __('Modifier la matière', 'almetal'),
        'update_item'           => __('Mettre à jour', 'almetal'),
        'view_item'             => __('Voir la matière', 'almetal'),
        'view_items'            => __('Voir les matières', 'almetal'),
        'search_items'          => __('Rechercher une matière', 'almetal'),
        'not_found'             => __('Aucune matière trouvée', 'almetal'),
        'not_found_in_trash'    => __('Aucune matière dans la corbeille', 'almetal'),
        'featured_image'        => __('Image principale', 'almetal'),
        'set_featured_image'    => __('Définir l\'image principale', 'almetal'),
        'remove_featured_image' => __('Retirer l\'image principale', 'almetal'),
        'use_featured_image'    => __('Utiliser comme image principale', 'almetal'),
    );

    $args = array(
        'label'                 => __('Matière', 'almetal'),
        'description'           => __('Pages dédiées aux matériaux de métallerie', 'almetal'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 6,
        'menu_icon'             => 'dashicons-database',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rewrite'               => array('slug' => 'matiere', 'with_front' => false),
    );

    register_post_type('matiere', $args);
}
add_action('init', 'almetal_register_matiere_cpt', 0);

/**
 * Ajouter les metaboxes pour les matières
 */
function almetal_matiere_metaboxes() {
    add_meta_box(
        'almetal_matiere_details',
        __('Détails de la matière', 'almetal'),
        'almetal_matiere_details_callback',
        'matiere',
        'normal',
        'high'
    );
    
    add_meta_box(
        'almetal_matiere_seo',
        __('SEO & Contenu', 'almetal'),
        'almetal_matiere_seo_callback',
        'matiere',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'almetal_matiere_metaboxes');

/**
 * Callback pour la metabox des détails
 */
function almetal_matiere_details_callback($post) {
    wp_nonce_field('almetal_matiere_nonce', 'almetal_matiere_nonce_field');
    
    $slug = get_post_meta($post->ID, '_almetal_matiere_slug', true);
    $icone = get_post_meta($post->ID, '_almetal_matiere_icone', true);
    $couleur = get_post_meta($post->ID, '_almetal_matiere_couleur', true) ?: '#F08B18';
    $proprietes = get_post_meta($post->ID, '_almetal_matiere_proprietes', true);
    $avantages = get_post_meta($post->ID, '_almetal_matiere_avantages', true);
    $applications = get_post_meta($post->ID, '_almetal_matiere_applications', true);
    ?>
    <style>
        .almetal-metabox-row { margin-bottom: 15px; }
        .almetal-metabox-row label { display: block; font-weight: 600; margin-bottom: 5px; }
        .almetal-metabox-row input[type="text"],
        .almetal-metabox-row textarea { width: 100%; }
        .almetal-metabox-row textarea { min-height: 100px; }
        .almetal-metabox-row input[type="color"] { width: 60px; height: 35px; }
        .almetal-metabox-hint { color: #666; font-size: 12px; margin-top: 3px; }
    </style>
    
    <div class="almetal-metabox-row">
        <label for="almetal_matiere_slug"><?php _e('Slug (identifiant)', 'almetal'); ?></label>
        <input type="text" id="almetal_matiere_slug" name="almetal_matiere_slug" value="<?php echo esc_attr($slug); ?>" placeholder="acier, inox, aluminium, fer-forge">
        <p class="almetal-metabox-hint"><?php _e('Identifiant utilisé pour lier les réalisations (ex: acier, inox, aluminium, fer-forge)', 'almetal'); ?></p>
    </div>
    
    <div class="almetal-metabox-row">
        <label for="almetal_matiere_couleur"><?php _e('Couleur d\'accent', 'almetal'); ?></label>
        <input type="color" id="almetal_matiere_couleur" name="almetal_matiere_couleur" value="<?php echo esc_attr($couleur); ?>">
    </div>
    
    <div class="almetal-metabox-row">
        <label for="almetal_matiere_proprietes"><?php _e('Propriétés techniques', 'almetal'); ?></label>
        <textarea id="almetal_matiere_proprietes" name="almetal_matiere_proprietes" placeholder="Une propriété par ligne"><?php echo esc_textarea($proprietes); ?></textarea>
        <p class="almetal-metabox-hint"><?php _e('Ex: Résistance à la corrosion, Durabilité, Facilité de soudure...', 'almetal'); ?></p>
    </div>
    
    <div class="almetal-metabox-row">
        <label for="almetal_matiere_avantages"><?php _e('Avantages', 'almetal'); ?></label>
        <textarea id="almetal_matiere_avantages" name="almetal_matiere_avantages" placeholder="Un avantage par ligne"><?php echo esc_textarea($avantages); ?></textarea>
    </div>
    
    <div class="almetal-metabox-row">
        <label for="almetal_matiere_applications"><?php _e('Applications / Types de réalisations', 'almetal'); ?></label>
        <textarea id="almetal_matiere_applications" name="almetal_matiere_applications" placeholder="Une application par ligne"><?php echo esc_textarea($applications); ?></textarea>
        <p class="almetal-metabox-hint"><?php _e('Ex: Portails, Garde-corps, Escaliers, Pergolas...', 'almetal'); ?></p>
    </div>
    <?php
}

/**
 * Callback pour la metabox SEO
 */
function almetal_matiere_seo_callback($post) {
    $meta_title = get_post_meta($post->ID, '_almetal_matiere_meta_title', true);
    $meta_description = get_post_meta($post->ID, '_almetal_matiere_meta_description', true);
    $intro_text = get_post_meta($post->ID, '_almetal_matiere_intro', true);
    $faq = get_post_meta($post->ID, '_almetal_matiere_faq', true);
    ?>
    <div class="almetal-metabox-row">
        <label for="almetal_matiere_meta_title"><?php _e('Meta Title (SEO)', 'almetal'); ?></label>
        <input type="text" id="almetal_matiere_meta_title" name="almetal_matiere_meta_title" value="<?php echo esc_attr($meta_title); ?>" placeholder="Métallerie [Matière] Thiers | AL Métallerie Soudure">
    </div>
    
    <div class="almetal-metabox-row">
        <label for="almetal_matiere_meta_description"><?php _e('Meta Description (SEO)', 'almetal'); ?></label>
        <textarea id="almetal_matiere_meta_description" name="almetal_matiere_meta_description" placeholder="Description pour les moteurs de recherche (150-160 caractères)"><?php echo esc_textarea($meta_description); ?></textarea>
    </div>
    
    <div class="almetal-metabox-row">
        <label for="almetal_matiere_intro"><?php _e('Texte d\'introduction', 'almetal'); ?></label>
        <textarea id="almetal_matiere_intro" name="almetal_matiere_intro" style="min-height: 150px;"><?php echo esc_textarea($intro_text); ?></textarea>
    </div>
    
    <div class="almetal-metabox-row">
        <label for="almetal_matiere_faq"><?php _e('FAQ (format: Question|Réponse, une par ligne)', 'almetal'); ?></label>
        <textarea id="almetal_matiere_faq" name="almetal_matiere_faq" style="min-height: 150px;" placeholder="Pourquoi choisir l'acier ?|L'acier offre une excellente résistance..."><?php echo esc_textarea($faq); ?></textarea>
    </div>
    <?php
}

/**
 * Sauvegarder les métadonnées des matières
 */
function almetal_save_matiere_meta($post_id) {
    if (!isset($_POST['almetal_matiere_nonce_field']) || !wp_verify_nonce($_POST['almetal_matiere_nonce_field'], 'almetal_matiere_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    $fields = array(
        'almetal_matiere_slug',
        'almetal_matiere_couleur',
        'almetal_matiere_proprietes',
        'almetal_matiere_avantages',
        'almetal_matiere_applications',
        'almetal_matiere_meta_title',
        'almetal_matiere_meta_description',
        'almetal_matiere_intro',
        'almetal_matiere_faq',
    );
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_textarea_field($_POST[$field]));
        }
    }
}
add_action('save_post_matiere', 'almetal_save_matiere_meta');

/**
 * Récupérer l'URL d'une page matière par son slug
 */
function almetal_get_matiere_url($matiere_slug) {
    if (empty($matiere_slug)) {
        return false;
    }
    
    // Normaliser le slug
    $matiere_slug = sanitize_title($matiere_slug);
    
    // Chercher la page matière correspondante
    $matiere_posts = get_posts(array(
        'post_type' => 'matiere',
        'posts_per_page' => 1,
        'meta_query' => array(
            array(
                'key' => '_almetal_matiere_slug',
                'value' => $matiere_slug,
                'compare' => '=',
            ),
        ),
    ));
    
    if (!empty($matiere_posts)) {
        return get_permalink($matiere_posts[0]->ID);
    }
    
    // Fallback: chercher par titre
    $matiere_post = get_page_by_title($matiere_slug, OBJECT, 'matiere');
    if ($matiere_post) {
        return get_permalink($matiere_post->ID);
    }
    
    return false;
}

/**
 * Générer le lien HTML vers une page matière
 */
function almetal_matiere_link_html($matiere_name, $class = '') {
    if (empty($matiere_name)) {
        return '';
    }
    
    $url = almetal_get_matiere_url($matiere_name);
    $class_attr = $class ? ' class="' . esc_attr($class) . '"' : '';
    
    if ($url) {
        return '<a href="' . esc_url($url) . '"' . $class_attr . '>' . esc_html($matiere_name) . '</a>';
    }
    
    return esc_html($matiere_name);
}

/**
 * Récupérer les réalisations par matière
 */
function almetal_get_realisations_by_matiere($matiere_slug, $limit = 6) {
    return get_posts(array(
        'post_type' => 'realisation',
        'posts_per_page' => $limit,
        'meta_query' => array(
            array(
                'key' => '_almetal_matiere',
                'value' => $matiere_slug,
                'compare' => 'LIKE',
            ),
        ),
        'orderby' => 'date',
        'order' => 'DESC',
    ));
}
