<?php
/**
 * Personnalisation du Dashboard WordPress
 * 
 * Affiche uniquement les widgets utiles :
 * - État de santé du site
 * - Analytics
 * - Réalisations
 * - Formations (agenda)
 * - Slideshow Accueil
 * - Contact
 * - Pages Ville
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Supprimer les widgets par défaut inutiles
 */
function almetal_remove_dashboard_widgets() {
    global $wp_meta_boxes;
    
    // Supprimer les widgets WordPress par défaut
    remove_meta_box('dashboard_primary', 'dashboard', 'side');           // Événements et actualités WordPress
    remove_meta_box('dashboard_secondary', 'dashboard', 'side');         // Autres flux WordPress
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');       // Publication rapide
    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');     // Brouillons récents
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');  // Liens entrants
    remove_meta_box('dashboard_plugins', 'dashboard', 'normal');         // Plugins
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); // Commentaires récents
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal');       // En ce moment (remplacé par notre widget)
    remove_meta_box('dashboard_activity', 'dashboard', 'normal');        // Activité
    
    // Supprimer les widgets de plugins tiers courants
    remove_meta_box('wpseo-dashboard-overview', 'dashboard', 'normal');  // Yoast SEO
    remove_meta_box('rg_forms_dashboard', 'dashboard', 'normal');        // Gravity Forms
    remove_meta_box('jetpack_summary_widget', 'dashboard', 'normal');    // Jetpack
}
add_action('wp_dashboard_setup', 'almetal_remove_dashboard_widgets', 999);

/**
 * Ajouter nos widgets personnalisés
 */
function almetal_add_dashboard_widgets() {
    // Widget Réalisations
    wp_add_dashboard_widget(
        'almetal_realisations_widget',
        '🔧 Réalisations',
        'almetal_realisations_widget_content'
    );
    
    // Widget Formations / Agenda
    wp_add_dashboard_widget(
        'almetal_formations_widget',
        '📚 Formations - Agenda',
        'almetal_formations_widget_content'
    );
    
    // Widget Slideshow Accueil
    wp_add_dashboard_widget(
        'almetal_slideshow_widget',
        '🎠 Slideshow Accueil',
        'almetal_slideshow_widget_content'
    );
    
    // Widget Contact
    wp_add_dashboard_widget(
        'almetal_contact_widget',
        '📧 Messages Contact',
        'almetal_contact_widget_content'
    );
    
    // Widget Pages Ville
    wp_add_dashboard_widget(
        'almetal_city_pages_widget',
        '🏙️ Pages Ville',
        'almetal_city_pages_widget_content'
    );
    
    // Widget Analytics (si disponible)
    wp_add_dashboard_widget(
        'almetal_analytics_widget',
        '📊 Analytics',
        'almetal_analytics_widget_content'
    );
}
add_action('wp_dashboard_setup', 'almetal_add_dashboard_widgets');

/**
 * Contenu du widget Réalisations
 */
function almetal_realisations_widget_content() {
    $count_published = wp_count_posts('realisation')->publish;
    $count_draft = wp_count_posts('realisation')->draft;
    
    // Dernières réalisations
    $recent = get_posts(array(
        'post_type' => 'realisation',
        'posts_per_page' => 5,
        'post_status' => 'publish',
    ));
    
    echo '<div class="almetal-widget">';
    echo '<p><strong>' . $count_published . '</strong> réalisations publiées';
    if ($count_draft > 0) {
        echo ' | <strong>' . $count_draft . '</strong> brouillons';
    }
    echo '</p>';
    
    if (!empty($recent)) {
        echo '<h4>Dernières réalisations :</h4>';
        echo '<ul>';
        foreach ($recent as $post) {
            $edit_link = get_edit_post_link($post->ID);
            $lieu = get_post_meta($post->ID, '_almetal_lieu', true);
            echo '<li>';
            echo '<a href="' . esc_url($edit_link) . '">' . esc_html($post->post_title) . '</a>';
            if ($lieu) {
                echo ' <small style="color:#666;">(' . esc_html($lieu) . ')</small>';
            }
            echo '</li>';
        }
        echo '</ul>';
    }
    
    echo '<p style="margin-top:15px;">';
    echo '<a href="' . admin_url('edit.php?post_type=realisation') . '" class="button">Voir toutes</a> ';
    echo '<a href="' . admin_url('post-new.php?post_type=realisation') . '" class="button button-primary">+ Ajouter</a>';
    echo '</p>';
    echo '</div>';
}

/**
 * Contenu du widget Formations
 */
function almetal_formations_widget_content() {
    // Vérifier si le plugin Training Manager est actif
    if (!post_type_exists('training_session')) {
        echo '<p>Le plugin Training Manager n\'est pas activé.</p>';
        return;
    }
    
    $count_published = wp_count_posts('training_session')->publish;
    
    // Prochaines formations
    $upcoming = get_posts(array(
        'post_type' => 'training_session',
        'posts_per_page' => 5,
        'post_status' => 'publish',
        'meta_key' => '_tm_start_date',
        'orderby' => 'meta_value',
        'order' => 'ASC',
        'meta_query' => array(
            array(
                'key' => '_tm_start_date',
                'value' => date('Y-m-d'),
                'compare' => '>=',
                'type' => 'DATE',
            ),
        ),
    ));
    
    echo '<div class="almetal-widget">';
    echo '<p><strong>' . $count_published . '</strong> formations programmées</p>';
    
    if (!empty($upcoming)) {
        echo '<h4>Prochaines formations :</h4>';
        echo '<ul>';
        foreach ($upcoming as $post) {
            $edit_link = get_edit_post_link($post->ID);
            $date = get_post_meta($post->ID, '_tm_start_date', true);
            $places = get_post_meta($post->ID, '_tm_available_places', true);
            echo '<li>';
            echo '<a href="' . esc_url($edit_link) . '">' . esc_html($post->post_title) . '</a>';
            if ($date) {
                echo ' <small style="color:#666;">(' . date_i18n('d/m/Y', strtotime($date)) . ')</small>';
            }
            if ($places) {
                echo ' <span style="background:#f0f0f0;padding:2px 6px;border-radius:3px;font-size:11px;">' . $places . ' places</span>';
            }
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p style="color:#666;font-style:italic;">Aucune formation à venir</p>';
    }
    
    echo '<p style="margin-top:15px;">';
    echo '<a href="' . admin_url('edit.php?post_type=training_session') . '" class="button">Voir toutes</a> ';
    echo '<a href="' . admin_url('post-new.php?post_type=training_session') . '" class="button button-primary">+ Ajouter</a>';
    echo '</p>';
    echo '</div>';
}

/**
 * Contenu du widget Slideshow
 */
function almetal_slideshow_widget_content() {
    // Récupérer les slides
    $slides = get_option('almetal_slideshow_slides', array());
    $active_count = count(array_filter($slides, function($s) { 
        return !empty($s['image']) && (!isset($s['active']) || $s['active']); 
    }));
    
    echo '<div class="almetal-widget">';
    echo '<p><strong>' . $active_count . '</strong> slides actives sur la page d\'accueil</p>';
    
    if (!empty($slides)) {
        echo '<div style="display:flex;gap:10px;flex-wrap:wrap;margin:10px 0;">';
        foreach (array_slice($slides, 0, 4) as $slide) {
            if (!empty($slide['image'])) {
                echo '<div style="width:60px;height:40px;overflow:hidden;border-radius:4px;border:1px solid #ddd;">';
                echo '<img src="' . esc_url($slide['image']) . '" style="width:100%;height:100%;object-fit:cover;">';
                echo '</div>';
            }
        }
        echo '</div>';
    }
    
    echo '<p style="margin-top:15px;">';
    echo '<a href="' . admin_url('admin.php?page=almetal-slideshow') . '" class="button button-primary">Gérer le Slideshow</a>';
    echo '</p>';
    echo '</div>';
}

/**
 * Contenu du widget Contact
 */
function almetal_contact_widget_content() {
    // Vérifier si Contact Form 7 ou WPForms est actif
    $cf7_active = class_exists('WPCF7');
    $wpforms_active = class_exists('WPForms');
    
    echo '<div class="almetal-widget">';
    
    if ($cf7_active) {
        // Lien vers Contact Form 7
        echo '<p>Gérez vos formulaires de contact avec Contact Form 7.</p>';
        echo '<p><a href="' . admin_url('admin.php?page=wpcf7') . '" class="button">Formulaires CF7</a></p>';
    } elseif ($wpforms_active) {
        // Lien vers WPForms
        echo '<p>Gérez vos formulaires de contact avec WPForms.</p>';
        echo '<p><a href="' . admin_url('admin.php?page=wpforms-overview') . '" class="button">Formulaires WPForms</a></p>';
    } else {
        echo '<p>Aucun plugin de formulaire détecté.</p>';
    }
    
    // Lien vers la page contact
    $contact_page = get_page_by_path('contact');
    if ($contact_page) {
        echo '<p style="margin-top:10px;">';
        echo '<a href="' . get_edit_post_link($contact_page->ID) . '" class="button">Modifier la page Contact</a>';
        echo '</p>';
    }
    
    echo '</div>';
}

/**
 * Contenu du widget Pages Ville
 */
function almetal_city_pages_widget_content() {
    // Vérifier si le CPT city_page existe
    if (!post_type_exists('city_page')) {
        echo '<p>Le plugin City Pages Generator n\'est pas activé.</p>';
        return;
    }
    
    $count_published = wp_count_posts('city_page')->publish;
    $count_draft = wp_count_posts('city_page')->draft;
    
    // Dernières pages ville
    $recent = get_posts(array(
        'post_type' => 'city_page',
        'posts_per_page' => 5,
        'post_status' => array('publish', 'draft'),
        'orderby' => 'date',
        'order' => 'DESC',
    ));
    
    echo '<div class="almetal-widget">';
    echo '<p><strong>' . $count_published . '</strong> pages ville publiées';
    if ($count_draft > 0) {
        echo ' | <strong>' . $count_draft . '</strong> brouillons';
    }
    echo '</p>';
    
    if (!empty($recent)) {
        echo '<h4>Dernières pages :</h4>';
        echo '<ul>';
        foreach ($recent as $post) {
            $edit_link = get_edit_post_link($post->ID);
            $status = $post->post_status === 'draft' ? ' <span style="color:#d63638;">(brouillon)</span>' : '';
            echo '<li>';
            echo '<a href="' . esc_url($edit_link) . '">' . esc_html($post->post_title) . '</a>' . $status;
            echo '</li>';
        }
        echo '</ul>';
    }
    
    echo '<p style="margin-top:15px;">';
    echo '<a href="' . admin_url('edit.php?post_type=city_page') . '" class="button">Voir toutes</a> ';
    echo '<a href="' . admin_url('admin.php?page=city-pages-generator') . '" class="button button-primary">Générateur</a>';
    echo '</p>';
    echo '</div>';
}

/**
 * Contenu du widget Analytics
 */
function almetal_analytics_widget_content() {
    echo '<div class="almetal-widget">';
    
    // Vérifier si notre système d'analytics est actif
    if (class_exists('Almetal_Analytics') || function_exists('almetal_get_analytics_stats')) {
        echo '<p>Statistiques de visites du site.</p>';
        echo '<p><a href="' . admin_url('admin.php?page=almetal-analytics') . '" class="button button-primary">Voir les statistiques</a></p>';
    } else {
        // Lien vers Google Analytics ou alternative
        echo '<p>Consultez vos statistiques de trafic.</p>';
        echo '<p>';
        echo '<a href="https://analytics.google.com/" target="_blank" class="button">Google Analytics ↗</a> ';
        echo '<a href="https://search.google.com/search-console" target="_blank" class="button">Search Console ↗</a>';
        echo '</p>';
    }
    
    echo '</div>';
}

/**
 * Réorganiser les widgets du dashboard
 */
function almetal_dashboard_widget_order() {
    $user_id = get_current_user_id();
    
    // Définir l'ordre des widgets
    $order = array(
        'normal' => 'dashboard_site_health,almetal_analytics_widget,almetal_realisations_widget,almetal_formations_widget',
        'side' => 'almetal_slideshow_widget,almetal_contact_widget,almetal_city_pages_widget',
    );
    
    // Appliquer l'ordre si pas déjà personnalisé par l'utilisateur
    $current_order = get_user_meta($user_id, 'meta-box-order_dashboard', true);
    if (empty($current_order)) {
        update_user_meta($user_id, 'meta-box-order_dashboard', $order);
    }
}
add_action('admin_init', 'almetal_dashboard_widget_order');

/**
 * Styles pour les widgets du dashboard
 */
function almetal_dashboard_styles() {
    $screen = get_current_screen();
    if ($screen && $screen->id === 'dashboard') {
        ?>
        <style>
            .almetal-widget ul {
                margin: 10px 0;
                padding-left: 0;
            }
            .almetal-widget ul li {
                padding: 5px 0;
                border-bottom: 1px solid #f0f0f0;
                list-style: none;
            }
            .almetal-widget ul li:last-child {
                border-bottom: none;
            }
            .almetal-widget h4 {
                margin: 15px 0 5px;
                font-size: 13px;
                color: #1d2327;
            }
            .almetal-widget .button {
                margin-right: 5px;
            }
            #almetal_realisations_widget .inside,
            #almetal_formations_widget .inside,
            #almetal_slideshow_widget .inside,
            #almetal_contact_widget .inside,
            #almetal_city_pages_widget .inside,
            #almetal_analytics_widget .inside {
                padding: 12px;
            }
            /* Couleurs des titres de widgets */
            #almetal_realisations_widget .postbox-header h2 { color: #2271b1; }
            #almetal_formations_widget .postbox-header h2 { color: #d63638; }
            #almetal_slideshow_widget .postbox-header h2 { color: #dba617; }
            #almetal_city_pages_widget .postbox-header h2 { color: #00a32a; }
        </style>
        <?php
    }
}
add_action('admin_head', 'almetal_dashboard_styles');
