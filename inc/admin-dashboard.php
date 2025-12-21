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

// Constantes Google Analytics 4
if (!defined('ALMETAL_GA4_MEASUREMENT_ID')) {
    define('ALMETAL_GA4_MEASUREMENT_ID', 'G-LQXQ5E0NE3');
}
if (!defined('ALMETAL_GA4_PROPERTY_ID')) {
    define('ALMETAL_GA4_PROPERTY_ID', ''); // À remplir avec l'ID de propriété si disponible
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
                echo '<img src="' . esc_url($slide['image']) . '" alt="Aperçu slide" style="width:100%;height:100%;object-fit:cover;">';
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
 * Contenu du widget Contact - Affiche les derniers messages reçus
 */
function almetal_contact_widget_content() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'almetal_contacts';
    
    echo '<div class="almetal-widget">';
    
    // Vérifier si la table existe
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name;
    
    if ($table_exists) {
        // Compter les messages
        $total_messages = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        
        // Statistiques
        echo '<div style="display:flex;gap:20px;margin-bottom:15px;">';
        echo '<div style="text-align:center;">';
        echo '<div style="font-size:24px;font-weight:bold;color:#2271b1;">' . intval($total_messages) . '</div>';
        echo '<div style="font-size:11px;color:#666;">Messages reçus</div>';
        echo '</div>';
        echo '</div>';
        
        // Derniers messages (compatible avec submitted_at ou created_at)
        $recent_messages = $wpdb->get_results(
            "SELECT * FROM $table_name ORDER BY submitted_at DESC LIMIT 5"
        );
        
        if (!empty($recent_messages)) {
            echo '<h4>Derniers messages :</h4>';
            echo '<div class="contact-messages-list">';
            foreach ($recent_messages as $msg) {
                // Utiliser submitted_at ou created_at selon ce qui existe
                $date_field = isset($msg->submitted_at) ? $msg->submitted_at : (isset($msg->created_at) ? $msg->created_at : '');
                $date = $date_field ? date_i18n('d/m H:i', strtotime($date_field)) : '';
                $name = esc_html($msg->name ?? '');
                $project = esc_html($msg->project_type ?? '');
                $email = esc_html($msg->email ?? '');
                
                echo '<div class="contact-message-item" style="padding:8px 0;border-bottom:1px solid #f0f0f0;">';
                echo '<strong>' . $name . '</strong>';
                if ($date) {
                    echo ' <span style="color:#666;font-size:11px;">(' . $date . ')</span>';
                }
                echo '<br><span style="color:#666;font-size:12px;">' . ($project ?: $email) . '</span>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo '<p style="color:#666;font-style:italic;">Aucun message reçu</p>';
        }
        
        echo '<p style="margin-top:15px;">';
        echo '<a href="' . admin_url('admin.php?page=almetal-contacts') . '" class="button button-primary">Voir tous les messages</a>';
        echo '</p>';
    } else {
        echo '<p style="color:#666;">La table des contacts n\'existe pas encore.</p>';
        echo '<p><a href="' . admin_url('admin.php?page=almetal-contacts') . '" class="button">Configurer</a></p>';
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
 * Contenu du widget Analytics - Graphiques et statistiques détaillées
 */
function almetal_analytics_widget_content() {
    echo '<div class="almetal-widget almetal-analytics-widget">';
    
    // Vérifier si le plugin analytics est actif et la classe Database existe
    if (class_exists('Almetal_Analytics_Database')) {
        // Récupérer les statistiques
        $stats = Almetal_Analytics_Database::get_stats('7days');
        $visits_by_day = Almetal_Analytics_Database::get_visits_by_day('7days');
        $top_pages = Almetal_Analytics_Database::get_top_pages('7days', 5);
        $devices = Almetal_Analytics_Database::get_devices('7days');
        
        // KPIs principaux
        echo '<div class="analytics-kpis" style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:15px;">';
        
        echo '<div style="text-align:center;padding:10px;background:#f8f9fa;border-radius:6px;">';
        echo '<div style="font-size:20px;font-weight:bold;color:#2271b1;">' . number_format($stats['unique_visitors'] ?? 0) . '</div>';
        echo '<div style="font-size:10px;color:#666;">Visiteurs</div>';
        echo '</div>';
        
        echo '<div style="text-align:center;padding:10px;background:#f8f9fa;border-radius:6px;">';
        echo '<div style="font-size:20px;font-weight:bold;color:#00a32a;">' . number_format($stats['page_views'] ?? 0) . '</div>';
        echo '<div style="font-size:10px;color:#666;">Pages vues</div>';
        echo '</div>';
        
        echo '<div style="text-align:center;padding:10px;background:#f8f9fa;border-radius:6px;">';
        echo '<div style="font-size:20px;font-weight:bold;color:#dba617;">' . ($stats['bounce_rate'] ?? 0) . '%</div>';
        echo '<div style="font-size:10px;color:#666;">Rebond</div>';
        echo '</div>';
        
        $avg_duration = isset($stats['avg_duration']) ? gmdate('i:s', $stats['avg_duration']) : '0:00';
        echo '<div style="text-align:center;padding:10px;background:#f8f9fa;border-radius:6px;">';
        echo '<div style="font-size:20px;font-weight:bold;color:#8c5383;">' . $avg_duration . '</div>';
        echo '<div style="font-size:10px;color:#666;">Durée moy.</div>';
        echo '</div>';
        
        echo '</div>';
        
        // Mini graphique des 7 derniers jours
        if (!empty($visits_by_day)) {
            echo '<div class="analytics-chart" style="margin-bottom:15px;">';
            echo '<h4 style="margin:0 0 10px;font-size:12px;">Visites (7 derniers jours)</h4>';
            echo '<div style="display:flex;align-items:flex-end;height:60px;gap:4px;">';
            
            $max_visits = max(array_column($visits_by_day, 'visits'));
            $max_visits = $max_visits > 0 ? $max_visits : 1;
            
            foreach ($visits_by_day as $day) {
                $height = ($day['visits'] / $max_visits) * 100;
                $date_label = date_i18n('D', strtotime($day['date']));
                echo '<div style="flex:1;display:flex;flex-direction:column;align-items:center;">';
                echo '<div style="width:100%;background:linear-gradient(to top,#2271b1,#72aee6);border-radius:3px 3px 0 0;height:' . $height . '%;min-height:4px;" title="' . $day['visits'] . ' visites"></div>';
                echo '<div style="font-size:9px;color:#666;margin-top:4px;">' . $date_label . '</div>';
                echo '</div>';
            }
            echo '</div>';
            echo '</div>';
        }
        
        // Top pages d'entrée
        if (!empty($top_pages)) {
            echo '<h4 style="margin:15px 0 8px;font-size:12px;">Pages les plus visitées</h4>';
            echo '<div class="top-pages-list">';
            foreach (array_slice($top_pages, 0, 5) as $page) {
                $title = !empty($page['page_title']) ? $page['page_title'] : parse_url($page['page_url'], PHP_URL_PATH);
                $title = strlen($title) > 35 ? substr($title, 0, 32) . '...' : $title;
                echo '<div style="display:flex;justify-content:space-between;padding:4px 0;border-bottom:1px solid #f0f0f0;font-size:12px;">';
                echo '<span style="color:#1d2327;">' . esc_html($title) . '</span>';
                echo '<span style="color:#666;font-weight:500;">' . number_format($page['views']) . '</span>';
                echo '</div>';
            }
            echo '</div>';
        }
        
        // Répartition devices (mini)
        if (!empty($devices)) {
            $total_devices = array_sum(array_column($devices, 'count'));
            if ($total_devices > 0) {
                echo '<div style="display:flex;gap:15px;margin-top:12px;font-size:11px;">';
                foreach ($devices as $device) {
                    $pct = round(($device['count'] / $total_devices) * 100);
                    $icon = $device['device_type'] === 'mobile' ? '📱' : ($device['device_type'] === 'tablet' ? '📱' : '💻');
                    echo '<span>' . $icon . ' ' . ucfirst($device['device_type']) . ' ' . $pct . '%</span>';
                }
                echo '</div>';
            }
        }
        
        echo '<div style="margin-top:15px;display:flex;gap:8px;flex-wrap:wrap;">';
        echo '<a href="' . admin_url('admin.php?page=almetal-analytics') . '" class="button button-primary">Dashboard local</a>';
        echo '</div>';
        
    } else {
        // Fallback si le plugin n'est pas actif
        echo '<p style="margin-bottom:10px;color:#666;font-size:12px;">Activez le plugin Analytics pour les données locales.</p>';
    }
    
    // Toujours afficher les liens Google Analytics 4
    echo '<div style="margin-top:15px;padding-top:15px;border-top:1px solid #f0f0f0;">';
    echo '<h4 style="margin:0 0 8px;font-size:12px;display:flex;align-items:center;gap:6px;">';
    echo '<img src="https://www.gstatic.com/analytics-suite/header/suite/v2/ic_analytics.svg" width="18" height="18" alt="GA4">';
    echo 'Google Analytics 4</h4>';
    echo '<p style="font-size:11px;color:#666;margin-bottom:10px;">ID: ' . ALMETAL_GA4_MEASUREMENT_ID . '</p>';
    echo '<div style="display:flex;gap:6px;flex-wrap:wrap;">';
    echo '<a href="https://analytics.google.com/" target="_blank" class="button button-small">📈 Rapports</a>';
    echo '<a href="https://analytics.google.com/" target="_blank" class="button button-small">⚡ Temps réel</a>';
    echo '<a href="https://search.google.com/search-console" target="_blank" class="button button-small">🔍 Search Console</a>';
    echo '</div>';
    echo '</div>';
    
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
            @media (max-width: 768px) {
                #almetal_analytics_widget .analytics-kpis {
                    grid-template-columns: repeat(2, 1fr);
                    gap: 6px;
                }
                #almetal_analytics_widget .analytics-kpis div {
                    font-size: 12px;
                    line-height: 1.2;
                }
                #almetal_analytics_widget .top-pages-list div {
                    display: flex;
                    flex-direction: column;
                    font-size: 11px;
                    word-break: break-word;
                }
                #almetal_analytics_widget .analytics-chart {
                    overflow-x: auto;
                }
                #almetal_analytics_widget h4 {
                    font-size: 12px;
                    word-break: break-word;
                }
                #almetal_analytics_widget .top-pages-list {
                    max-width: 100%;
                }
                #almetal_analytics_widget .top-pages-list div span {
                    white-space: normal;
                }
                #almetal_analytics_widget table {
                    width: 100%;
                    display: block;
                    overflow-x: auto;
                }
                #almetal_analytics_widget table th,
                #almetal_analytics_widget table td {
                    word-break: break-all;
                    white-space: normal;
                    font-size: 12px;
                }
            }
        </style>
        <?php
    }
}
add_action('admin_head', 'almetal_dashboard_styles');
