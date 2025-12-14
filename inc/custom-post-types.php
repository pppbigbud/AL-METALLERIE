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
 * Format: /{categorie}/{titre-ville}/
 * Exemple: /pergolas/pergola-montlucon/
 */
function almetal_realisation_custom_permalink($permalink, $post, $leavename) {
    // Uniquement pour les réalisations
    if (!is_object($post) || $post->post_type !== 'realisation') {
        return $permalink;
    }
    
    // Récupérer la catégorie principale (première catégorie)
    $terms = get_the_terms($post->ID, 'type_realisation');
    $category_slug = 'realisations'; // Fallback
    
    if (!empty($terms) && !is_wp_error($terms)) {
        // Prendre la première catégorie
        $category_slug = $terms[0]->slug;
    }
    
    // Récupérer la ville
    $ville = get_post_meta($post->ID, '_almetal_lieu', true);
    $ville_slug = !empty($ville) ? sanitize_title($ville) : '';
    
    // Construire le slug de la réalisation: titre-ville
    $post_slug = sanitize_title($post->post_title);
    if (!empty($ville_slug) && strpos($post_slug, $ville_slug) === false) {
        $post_slug .= '-' . $ville_slug;
    }
    
    // Construire l'URL finale: /{categorie}/{titre-ville}/
    $custom_permalink = home_url('/' . $category_slug . '/' . $post_slug . '/');
    
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
    
    // Récupérer toutes les catégories de réalisations
    $terms = get_terms(array(
        'taxonomy' => 'type_realisation',
        'hide_empty' => false,
    ));
    
    if (!is_wp_error($terms) && !empty($terms)) {
        foreach ($terms as $term) {
            // Règle pour: /{categorie}/{slug-realisation}/
            add_rewrite_rule(
                '^' . preg_quote($term->slug, '/') . '/([^/]+)/?$',
                'index.php?post_type=realisation&realisation_custom_slug=$matches[1]&realisation_category=' . $term->slug,
                'top'
            );
        }
    }
    
    // Fallback pour /realisations/{slug}/
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
 * Enregistrer les query vars personnalisées
 */
function almetal_realisation_query_vars($vars) {
    $vars[] = 'realisation_custom_slug';
    $vars[] = 'realisation_category';
    return $vars;
}
add_filter('query_vars', 'almetal_realisation_query_vars');

/**
 * Résoudre l'URL personnalisée vers le bon post
 * Format: /{categorie}/{titre-ville}/
 */
function almetal_realisation_parse_request($wp) {
    if (!isset($wp->query_vars['realisation_custom_slug'])) {
        return;
    }
    
    $custom_slug = $wp->query_vars['realisation_custom_slug'];
    
    // Rechercher le post correspondant par son slug généré
    $args = array(
        'post_type' => 'realisation',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    );
    
    $posts = get_posts($args);
    
    foreach ($posts as $post) {
        // Reconstruire le slug attendu pour ce post
        $ville = get_post_meta($post->ID, '_almetal_lieu', true);
        $ville_slug = !empty($ville) ? sanitize_title($ville) : '';
        
        // Construire le slug attendu: titre-ville
        $expected_slug = sanitize_title($post->post_title);
        if (!empty($ville_slug) && strpos($expected_slug, $ville_slug) === false) {
            $expected_slug .= '-' . $ville_slug;
        }
        
        if ($expected_slug === $custom_slug) {
            // Trouvé ! Configurer la requête pour ce post
            $wp->query_vars = array(
                'post_type' => 'realisation',
                'p' => $post->ID,
                'name' => $post->post_name,
            );
            unset($wp->query_vars['realisation_custom_slug']);
            unset($wp->query_vars['realisation_category']);
            return;
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
    
    // Fallback: chercher par titre (WP_Query au lieu de get_page_by_title obsolète)
    $fallback_query = new WP_Query(array(
        'post_type' => 'matiere',
        'title' => $matiere_slug,
        'posts_per_page' => 1,
        'post_status' => 'publish',
    ));
    
    if ($fallback_query->have_posts()) {
        return get_permalink($fallback_query->posts[0]->ID);
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

/**
 * ============================================
 * INITIALISATION DES PAGES MATIÈRES
 * Crée automatiquement les pages matières avec contenu SEO
 * ============================================
 */
function almetal_init_matiere_pages() {
    // Vérifier si déjà initialisé
    if (get_option('almetal_matieres_initialized')) {
        return;
    }
    
    $matieres = array(
        'acier' => array(
            'title' => 'Acier',
            'slug' => 'acier',
            'couleur' => '#4A5568',
            'intro' => "L'acier est le matériau de prédilection en métallerie pour sa robustesse exceptionnelle et sa polyvalence. Chez AL Métallerie Soudure à Thiers, nous maîtrisons parfaitement le travail de l'acier pour créer des ouvrages durables et esthétiques : portails, garde-corps, escaliers, pergolas et bien plus encore.",
            'proprietes' => "Excellente résistance mécanique\nGrande durabilité dans le temps\nFacilité de soudure et d'assemblage\nPossibilité de thermolaquage (large choix de couleurs)\nRapport qualité-prix optimal\nRecyclable à 100%",
            'avantages' => "Matériau économique et performant\nAdapté aux grandes structures\nEntretien facile avec traitement antirouille\nPersonnalisation illimitée des formes\nCompatible avec tous types de finitions",
            'applications' => "Portails et portillons\nGarde-corps et rampes\nEscaliers métalliques\nPergolas et carports\nClôtures et grilles\nMobilier métallique\nStructures et charpentes",
            'meta_title' => "Métallerie Acier Thiers | Portails, Garde-corps, Escaliers | AL Métallerie",
            'meta_description' => "Artisan métallier spécialiste de l'acier à Thiers. Fabrication sur mesure de portails, garde-corps, escaliers en acier thermolaqué. Devis gratuit.",
            'faq' => "Pourquoi choisir l'acier pour mon portail ?|L'acier offre une excellente résistance aux chocs et aux intempéries. Avec un traitement thermolaqué, votre portail en acier durera des décennies sans rouiller.\nL'acier rouille-t-il ?|Sans traitement, l'acier peut rouiller. C'est pourquoi nous appliquons systématiquement un traitement antirouille et un thermolaquage qui protègent durablement vos ouvrages.\nQuel entretien pour l'acier thermolaqué ?|Un simple nettoyage à l'eau savonneuse une à deux fois par an suffit. Le thermolaquage est très résistant aux UV et aux intempéries.\nPeut-on peindre l'acier de n'importe quelle couleur ?|Oui ! Le thermolaquage permet de choisir parmi des centaines de teintes RAL. Nous pouvons même reproduire des couleurs sur mesure.\nQuelle est la durée de vie d'un ouvrage en acier ?|Avec un entretien minimal, un ouvrage en acier thermolaqué peut durer plus de 30 ans sans problème.",
        ),
        'inox' => array(
            'title' => 'Inox',
            'slug' => 'inox',
            'couleur' => '#A0AEC0',
            'intro' => "L'acier inoxydable, communément appelé inox, est le choix premium pour les ouvrages de métallerie haut de gamme. Sa résistance naturelle à la corrosion et son aspect moderne en font le matériau idéal pour les garde-corps, rampes et éléments décoratifs. AL Métallerie Soudure vous propose des réalisations en inox 304 et 316 selon vos besoins.",
            'proprietes' => "Résistance naturelle à la corrosion\nAspect brillant et moderne\nHygiénique et facile à nettoyer\nExcellente tenue aux UV\nInox 304 (intérieur) ou 316 (extérieur/bord de mer)\nDurée de vie exceptionnelle",
            'avantages' => "Aucun traitement de surface nécessaire\nAspect contemporain et élégant\nIdéal pour les environnements humides\nParfait pour le bord de mer (inox 316)\nValeur ajoutée pour votre propriété",
            'applications' => "Garde-corps et balustrades\nRampes d'escalier\nMains courantes\nÉléments de cuisine professionnelle\nMobilier design\nÉléments décoratifs\nPiscines et spas",
            'meta_title' => "Métallerie Inox Thiers | Garde-corps, Rampes Inox | AL Métallerie",
            'meta_description' => "Spécialiste inox à Thiers. Fabrication sur mesure de garde-corps, rampes et balustrades en acier inoxydable 304/316. Design moderne, devis gratuit.",
            'faq' => "Quelle différence entre inox 304 et 316 ?|L'inox 304 convient parfaitement pour l'intérieur et l'extérieur standard. L'inox 316, enrichi en molybdène, résiste mieux aux environnements salins (bord de mer, piscine).\nL'inox nécessite-t-il un entretien ?|Très peu ! Un nettoyage occasionnel à l'eau savonneuse suffit. Pour les traces de doigts, un produit spécial inox redonne tout son éclat.\nPeut-on combiner inox et verre ?|Absolument ! C'est même une combinaison très tendance pour les garde-corps modernes. Nous réalisons des garde-corps inox avec remplissage verre trempé.\nL'inox est-il plus cher que l'acier ?|Oui, l'inox est plus onéreux à l'achat, mais il ne nécessite aucun traitement de surface et sa durée de vie est supérieure. C'est un investissement rentable à long terme.\nPeut-on souder l'inox ?|Oui, mais cela requiert une expertise spécifique. Nous maîtrisons le soudage TIG de l'inox pour des finitions parfaites et durables.",
        ),
        'aluminium' => array(
            'title' => 'Aluminium',
            'slug' => 'aluminium',
            'couleur' => '#CBD5E0',
            'intro' => "L'aluminium est le matériau moderne par excellence pour la métallerie légère. Sa légèreté, sa résistance à la corrosion et sa facilité d'entretien en font un choix privilégié pour les portails, pergolas et garde-corps contemporains. AL Métallerie Soudure travaille l'aluminium avec précision pour des réalisations élégantes et durables.",
            'proprietes' => "Légèreté exceptionnelle\nRésistance naturelle à la corrosion\nExcellente conductivité thermique\nThermolaquage disponible (toutes couleurs RAL)\nRecyclable à 100%\nNe rouille jamais",
            'avantages' => "Idéal pour les grandes portées (portails)\nMotorisation facilitée par la légèreté\nEntretien quasi inexistant\nDesign contemporain\nParfait pour les bords de mer",
            'applications' => "Portails coulissants et battants\nPergolas bioclimatiques\nGarde-corps design\nClôtures et brise-vue\nCarports\nVérandas\nVolets et persiennes",
            'meta_title' => "Métallerie Aluminium Thiers | Portails, Pergolas Alu | AL Métallerie",
            'meta_description' => "Expert aluminium à Thiers. Portails, pergolas, garde-corps en aluminium sur mesure. Léger, durable, sans entretien. Devis gratuit.",
            'faq' => "L'aluminium est-il solide ?|Oui ! Malgré sa légèreté, l'aluminium offre une excellente résistance mécanique. Les profilés utilisés en métallerie sont conçus pour supporter des charges importantes.\nPourquoi l'aluminium pour un portail ?|Sa légèreté facilite la motorisation et réduit l'usure des mécanismes. De plus, il ne rouille jamais et nécessite très peu d'entretien.\nPeut-on avoir de l'aluminium couleur bois ?|Oui ! Le thermolaquage permet d'obtenir des finitions imitation bois très réalistes, combinant l'esthétique du bois et les avantages de l'aluminium.\nL'aluminium convient-il en bord de mer ?|Parfaitement ! L'aluminium résiste naturellement à la corrosion et au sel marin. C'est le choix idéal pour les environnements côtiers.\nQuel entretien pour l'aluminium ?|Un simple lavage à l'eau claire une à deux fois par an suffit. L'aluminium thermolaqué conserve son aspect pendant des décennies.",
        ),
        'fer-forge' => array(
            'title' => 'Fer forgé',
            'slug' => 'fer-forge',
            'couleur' => '#2D3748',
            'intro' => "Le fer forgé incarne l'excellence de l'artisanat traditionnel en métallerie. Chaque pièce est unique, façonnée à la main par nos artisans pour créer des ouvrages d'exception. Portails ouvragés, garde-corps aux motifs élaborés, grilles décoratives : le fer forgé apporte caractère et authenticité à votre propriété.",
            'proprietes' => "Travail artisanal traditionnel\nPièces uniques et personnalisées\nExcellente malléabilité à chaud\nPossibilité de motifs complexes\nPatine naturelle ou traitement antirouille\nValeur patrimoniale",
            'avantages' => "Esthétique intemporelle et élégante\nPersonnalisation totale des motifs\nValorisation du patrimoine\nDurabilité exceptionnelle\nRestauration possible",
            'applications' => "Portails et portillons ouvragés\nGarde-corps et balcons\nGrilles de défense décoratives\nRampes d'escalier\nMobilier de jardin\nÉléments décoratifs\nRestauration de patrimoine",
            'meta_title' => "Ferronnerie d'Art Thiers | Portails Fer Forgé | AL Métallerie",
            'meta_description' => "Artisan ferronnier à Thiers. Création sur mesure de portails, garde-corps et grilles en fer forgé. Travail traditionnel, pièces uniques. Devis gratuit.",
            'faq' => "Quelle différence entre fer forgé et acier ?|Le fer forgé est travaillé à chaud de manière artisanale, permettant des formes et motifs impossibles à réaliser industriellement. L'acier est plus standardisé mais aussi plus économique.\nLe fer forgé rouille-t-il ?|Sans traitement, oui. Nous appliquons une protection antirouille et une peinture de finition. Certains clients préfèrent la patine naturelle du fer pour un aspect authentique.\nPeut-on reproduire des motifs anciens ?|Absolument ! Nous pouvons reproduire des motifs traditionnels ou créer des designs sur mesure selon vos souhaits. La restauration de ferronnerie ancienne fait partie de notre savoir-faire.\nLe fer forgé est-il plus cher ?|Le travail artisanal du fer forgé demande plus de temps et de savoir-faire, ce qui se reflète dans le prix. C'est un investissement dans une pièce unique et durable.\nCombien de temps pour réaliser un portail en fer forgé ?|Selon la complexité des motifs, comptez 3 à 6 semaines. Chaque pièce étant unique, nous prenons le temps nécessaire pour un travail de qualité.",
        ),
        'cuivre' => array(
            'title' => 'Cuivre',
            'slug' => 'cuivre',
            'couleur' => '#C77B58',
            'intro' => "Le cuivre est un matériau noble qui apporte chaleur et élégance à vos ouvrages de métallerie. Sa patine naturelle qui évolue avec le temps lui confère un charme unique. AL Métallerie Soudure travaille le cuivre pour des réalisations décoratives haut de gamme et des éléments architecturaux distinctifs.",
            'proprietes' => "Couleur chaude et chaleureuse\nPatine naturelle (vert-de-gris)\nExcellente conductivité\nAntibactérien naturel\nMalléabilité exceptionnelle\nDurée de vie centenaire",
            'avantages' => "Esthétique unique et évolutive\nMatériau noble et prestigieux\nRésistance naturelle à la corrosion\nFacilité de mise en forme\nValeur patrimoniale",
            'applications' => "Éléments décoratifs\nCouvertures et zinguerie\nGouttières et descentes\nLuminaires\nMobilier design\nÉléments architecturaux\nRestauration de patrimoine",
            'meta_title' => "Métallerie Cuivre Thiers | Éléments Décoratifs Cuivre | AL Métallerie",
            'meta_description' => "Artisan métallier spécialiste du cuivre à Thiers. Création d'éléments décoratifs, zinguerie et ouvrages en cuivre. Matériau noble, devis gratuit.",
            'faq' => "Le cuivre change-t-il de couleur ?|Oui, c'est sa caractéristique ! Le cuivre développe naturellement une patine qui passe du brun au vert-de-gris. Cette évolution peut être accélérée ou stoppée selon vos préférences.\nLe cuivre est-il résistant ?|Très résistant ! Les toitures en cuivre peuvent durer plus de 100 ans. C'est un investissement durable malgré un coût initial plus élevé.\nPeut-on garder le cuivre brillant ?|Oui, avec un vernis de protection ou un entretien régulier. Cependant, beaucoup apprécient la patine naturelle qui donne du caractère.\nLe cuivre convient-il en extérieur ?|Parfaitement ! Le cuivre résiste naturellement aux intempéries. Sa patine le protège de la corrosion.\nPourquoi le cuivre est-il plus cher ?|Le cuivre est un métal précieux dont le cours fluctue. Son travail demande aussi un savoir-faire spécifique. C'est un choix premium pour des réalisations d'exception.",
        ),
        'laiton' => array(
            'title' => 'Laiton',
            'slug' => 'laiton',
            'couleur' => '#D4A84B',
            'intro' => "Le laiton, alliage de cuivre et de zinc, offre une teinte dorée élégante pour vos ouvrages de métallerie décorative. Apprécié pour son aspect luxueux et sa facilité de travail, le laiton est idéal pour les poignées, rampes, éléments décoratifs et pièces de quincaillerie haut de gamme.",
            'proprietes' => "Teinte dorée naturelle\nExcellente usinabilité\nBonne résistance à la corrosion\nAspect luxueux\nPolissage miroir possible\nAlliage cuivre-zinc",
            'avantages' => "Esthétique dorée sans dorure\nFacilité de mise en forme\nFinitions variées (poli, brossé, patiné)\nDurabilité\nEntretien simple",
            'applications' => "Poignées et quincaillerie\nRampes et mains courantes\nÉléments décoratifs\nLuminaires\nPlaques et enseignes\nMobilier design\nRestauration",
            'meta_title' => "Métallerie Laiton Thiers | Quincaillerie, Déco Laiton | AL Métallerie",
            'meta_description' => "Artisan métallier spécialiste du laiton à Thiers. Création de poignées, rampes et éléments décoratifs en laiton. Aspect doré luxueux, devis gratuit.",
            'faq' => "Le laiton ternit-il ?|Avec le temps, le laiton peut développer une patine. Un entretien régulier avec un produit adapté lui redonne son éclat. Certains préfèrent la patine pour un aspect vintage.\nQuelle différence entre laiton et bronze ?|Le laiton est un alliage cuivre-zinc (teinte dorée), le bronze est un alliage cuivre-étain (teinte plus brune). Le laiton est plus facile à travailler.\nLe laiton convient-il en extérieur ?|Oui, mais il nécessite un traitement de protection ou un entretien régulier. En intérieur, il conserve mieux son éclat.\nPeut-on avoir du laiton brossé ?|Absolument ! Nous proposons différentes finitions : poli miroir, brossé, satiné ou patiné selon l'effet recherché.\nLe laiton est-il hygiénique ?|Oui, comme le cuivre, le laiton possède des propriétés antibactériennes naturelles. Idéal pour les poignées de porte.",
        ),
        'mixte' => array(
            'title' => 'Mixte',
            'slug' => 'mixte',
            'couleur' => '#F08B18',
            'intro' => "Les réalisations mixtes combinent plusieurs matériaux pour créer des ouvrages uniques alliant les avantages de chacun. Acier et bois, inox et verre, aluminium et composite : AL Métallerie Soudure maîtrise l'art de marier les matières pour des créations sur mesure qui répondent parfaitement à vos attentes esthétiques et fonctionnelles.",
            'proprietes' => "Combinaison de matériaux\nOptimisation des propriétés\nDesign personnalisé\nAdaptation aux contraintes\nCréativité sans limite\nSolutions sur mesure",
            'avantages' => "Le meilleur de chaque matériau\nEsthétique unique et personnalisée\nOptimisation du budget\nAdaptation parfaite au projet\nOriginalité garantie",
            'applications' => "Portails acier et bois\nGarde-corps inox et verre\nEscaliers métal et bois\nPergolas alu et bois\nClôtures mixtes\nMobilier design\nAménagements sur mesure",
            'meta_title' => "Métallerie Mixte Thiers | Acier-Bois, Inox-Verre | AL Métallerie",
            'meta_description' => "Expert en réalisations mixtes à Thiers. Portails acier-bois, garde-corps inox-verre, escaliers métal-bois. Créations sur mesure, devis gratuit.",
            'faq' => "Quelles combinaisons de matériaux sont possibles ?|Presque toutes ! Les plus courantes : acier/bois, inox/verre, aluminium/composite, fer forgé/bois. Nous étudions chaque projet pour proposer la meilleure combinaison.\nLes matériaux mixtes sont-ils durables ?|Oui, à condition de bien choisir les matériaux et leurs traitements. Nous veillons à la compatibilité et à la durabilité de chaque assemblage.\nEst-ce plus cher qu'un seul matériau ?|Pas nécessairement. Parfois, combiner les matériaux permet d'optimiser le budget en utilisant chacun là où il est le plus pertinent.\nComment entretenir un ouvrage mixte ?|Chaque matériau a ses spécificités. Nous vous fournissons les conseils d'entretien adaptés à votre réalisation.\nPeut-on personnaliser les proportions ?|Absolument ! C'est tout l'intérêt du sur mesure. Nous adaptons les proportions de chaque matériau selon vos goûts et contraintes.",
        ),
    );
    
    foreach ($matieres as $key => $data) {
        // Vérifier si la page existe déjà
        $existing = get_posts(array(
            'post_type' => 'matiere',
            'posts_per_page' => 1,
            'meta_query' => array(
                array(
                    'key' => '_almetal_matiere_slug',
                    'value' => $data['slug'],
                ),
            ),
        ));
        
        if (!empty($existing)) {
            continue;
        }
        
        // Créer la page matière
        $post_id = wp_insert_post(array(
            'post_title' => $data['title'],
            'post_type' => 'matiere',
            'post_status' => 'publish',
            'post_content' => '',
        ));
        
        if ($post_id && !is_wp_error($post_id)) {
            update_post_meta($post_id, '_almetal_matiere_slug', $data['slug']);
            update_post_meta($post_id, '_almetal_matiere_couleur', $data['couleur']);
            update_post_meta($post_id, '_almetal_matiere_intro', $data['intro']);
            update_post_meta($post_id, '_almetal_matiere_proprietes', $data['proprietes']);
            update_post_meta($post_id, '_almetal_matiere_avantages', $data['avantages']);
            update_post_meta($post_id, '_almetal_matiere_applications', $data['applications']);
            update_post_meta($post_id, '_almetal_matiere_meta_title', $data['meta_title']);
            update_post_meta($post_id, '_almetal_matiere_meta_description', $data['meta_description']);
            update_post_meta($post_id, '_almetal_matiere_faq', $data['faq']);
        }
    }
    
    // Marquer comme initialisé
    update_option('almetal_matieres_initialized', true);
}
add_action('init', 'almetal_init_matiere_pages', 20);

/**
 * Réinitialiser les pages matières (pour forcer la recréation)
 * Utiliser: delete_option('almetal_matieres_initialized'); dans la console ou via un hook
 */
function almetal_reset_matiere_pages() {
    delete_option('almetal_matieres_initialized');
}
