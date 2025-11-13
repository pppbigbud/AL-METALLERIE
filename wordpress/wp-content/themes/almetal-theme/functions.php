<?php
/**
 * AL Metallerie Theme Functions
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

// Sécurité : empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Configuration du thème
 */
function almetal_theme_setup() {
    // Support du titre automatique
    add_theme_support('title-tag');
    
    // Support des images à la une
    add_theme_support('post-thumbnails');
    
    // Support du logo personnalisé
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    
    // Support HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    
    // Support des formats d'articles
    add_theme_support('post-formats', array(
        'aside',
        'gallery',
        'quote',
        'image',
        'video',
    ));
    
    // Enregistrer les menus
    register_nav_menus(array(
        'primary' => __('Menu Principal', 'almetal'),
        'footer'  => __('Menu Footer', 'almetal'),
    ));
    
    // Support de l'éditeur de blocs
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
}
add_action('after_setup_theme', 'almetal_theme_setup');

/**
 * Enqueue des styles et scripts
 */
function almetal_enqueue_scripts() {
    // ============================================
    // CSS COMMUNS (Desktop ET Mobile)
    // ============================================
    
    // Style principal (base commune)
    wp_enqueue_style(
        'almetal-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get('Version')
    );
    
    // Composants réutilisables (boutons, cartes, animations)
    wp_enqueue_style(
        'almetal-components',
        get_template_directory_uri() . '/assets/css/components.css',
        array('almetal-style'),
        wp_get_theme()->get('Version')
    );
    
    // ============================================
    // CSS DESKTOP UNIQUEMENT
    // ============================================
    if (!almetal_is_mobile()) {
        // Header desktop
        wp_enqueue_style(
            'almetal-header',
            get_template_directory_uri() . '/assets/css/header-new.css',
            array('almetal-style', 'almetal-components'),
            wp_get_theme()->get('Version')
        );
        
        // Mega menu
        wp_enqueue_style(
            'almetal-mega-menu',
            get_template_directory_uri() . '/assets/css/mega-menu.css',
            array('almetal-style', 'almetal-components'),
            wp_get_theme()->get('Version')
        );
        
        // Custom styles
        wp_enqueue_style(
            'almetal-custom',
            get_template_directory_uri() . '/assets/css/custom.css',
            array('almetal-style', 'almetal-components'),
            wp_get_theme()->get('Version')
        );
        
        // Footer desktop
        wp_enqueue_style(
            'almetal-footer-new',
            get_template_directory_uri() . '/assets/css/footer-new.css',
            array('almetal-style', 'almetal-components'),
            wp_get_theme()->get('Version')
        );
        
        // Montagnes footer
        wp_enqueue_style(
            'almetal-footer-mountains',
            get_template_directory_uri() . '/assets/css/footer-mountains.css',
            array('almetal-style'),
            wp_get_theme()->get('Version')
        );
        
        // Réalisations desktop
        wp_enqueue_style(
            'almetal-realisations',
            get_template_directory_uri() . '/assets/css/realisations.css',
            array('almetal-style', 'almetal-components'),
            wp_get_theme()->get('Version')
        );
    }
    
    // ============================================
    // CSS MOBILE UNIQUEMENT
    // ============================================
    if (almetal_is_mobile()) {
        // CSS Mobile unifié (remplace tous les anciens fichiers mobiles)
        wp_enqueue_style(
            'almetal-mobile-unified',
            get_template_directory_uri() . '/assets/css/mobile-unified.css',
            array('almetal-style', 'almetal-components'),
            '2.0.0-' . time() // Timestamp pour forcer le rechargement
        );
    }
    
    /* ANCIENS FICHIERS MOBILES DÉSACTIVÉS - Remplacés par mobile-unified.css
    - mobile.css
    - mobile-optimized.css
    - mobile-force.css
    - mobile-emergency.css
    - footer-mobile-cta.css
    - debug-images.css
    */
    
    // Style de la page contact (seulement sur la page contact)
    if (is_page_template('page-contact.php') || is_page('contact')) {
        wp_enqueue_style(
            'almetal-contact',
            get_template_directory_uri() . '/assets/css/contact.css',
            array('almetal-style', 'almetal-components'),
            wp_get_theme()->get('Version')
        );
    }
    
    // Style des pages formations (seulement sur les pages formations)
    if (is_page_template('page-formations.php') || 
        is_page_template('page-formations-particuliers.php') || 
        is_page_template('page-formations-professionnels.php') ||
        is_page('formations') || 
        is_page('formations-particuliers') || 
        is_page('formations-professionnels')) {
        wp_enqueue_style(
            'almetal-formations',
            get_template_directory_uri() . '/assets/css/formations.css',
            array('almetal-style', 'almetal-components'),
            wp_get_theme()->get('Version')
        );
    }
    
    // Script principal (DESKTOP UNIQUEMENT)
    if (!almetal_is_mobile()) {
        wp_enqueue_script(
            'almetal-script',
            get_template_directory_uri() . '/assets/js/main.js',
            array('jquery'),
            wp_get_theme()->get('Version'),
            true
        );
    }
    
    // Script de filtrage des actualités (front-page uniquement)
    if (is_front_page() && !almetal_is_mobile()) {
        wp_enqueue_script(
            'almetal-actualites-filter',
            get_template_directory_uri() . '/assets/js/actualites-filter.js',
            array('jquery'),
            wp_get_theme()->get('Version'),
            true
        );
    }
    
    // Script mega menu (desktop uniquement)
    if (!almetal_is_mobile()) {
        wp_enqueue_script(
            'almetal-mega-menu',
            get_template_directory_uri() . '/assets/js/mega-menu.js',
            array(),
            wp_get_theme()->get('Version'),
            true
        );
    }
    
    // Scripts mobile (mobile uniquement)
    if (almetal_is_mobile()) {
        // ============================================
        // SCRIPTS MOBILES - Réactivés progressivement
        // ============================================
        
        // Swiper slideshow (front-page uniquement)
        if (is_front_page()) {
            wp_enqueue_style(
                'swiper-css',
                'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
                array(),
                '11.0.0'
            );
            
            wp_enqueue_script(
                'swiper-js',
                'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
                array(),
                '11.0.0',
                true
            );
            
            wp_enqueue_script(
                'almetal-mobile-slideshow',
                get_template_directory_uri() . '/assets/js/mobile-slideshow.js',
                array('swiper-js'),
                wp_get_theme()->get('Version'),
                true
            );
        }
        
        // Menu burger
        wp_enqueue_script(
            'almetal-mobile-burger-clean',
            get_template_directory_uri() . '/assets/js/mobile-burger-clean.js',
            array(),
            '2.0.0',
            true
        );
        
        // Animations au scroll
        wp_enqueue_style(
            'almetal-mobile-animations-css',
            get_template_directory_uri() . '/assets/css/mobile-animations.css',
            array(),
            '2.0.0'
        );
        
        wp_enqueue_script(
            'almetal-mobile-animations',
            get_template_directory_uri() . '/assets/js/mobile-animations.js',
            array(),
            '2.0.0',
            true
        );
        
        // Bouton scroll to top
        wp_enqueue_script(
            'almetal-mobile-scroll-to-top',
            get_template_directory_uri() . '/assets/js/mobile-scroll-to-top.js',
            array(),
            wp_get_theme()->get('Version'),
            true
        );
        
        // Filtrage réalisations (front-page uniquement)
        if (is_front_page()) {
            wp_enqueue_script(
                'almetal-mobile-realisations-simple-filter',
                get_template_directory_uri() . '/assets/js/mobile-realisations-simple-filter.js',
                array(),
                wp_get_theme()->get('Version'),
                true
            );
        }
        
        // Slideshow single réalisation (single realisation uniquement)
        if (is_singular('realisation')) {
            // Swiper pour le slideshow
            wp_enqueue_style(
                'swiper-css',
                'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
                array(),
                '11.0.0'
            );
            
            wp_enqueue_script(
                'swiper-js',
                'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
                array(),
                '11.0.0',
                true
            );
            
            wp_enqueue_script(
                'almetal-mobile-single-realisation',
                get_template_directory_uri() . '/assets/js/mobile-single-realisation.js',
                array('swiper-js'),
                wp_get_theme()->get('Version'),
                true
            );
        }
        
        // Script FIX menu mobile - DÉSACTIVÉ (remplacé par mobile-unified.css)
        /* wp_enqueue_script(
            'almetal-mobile-menu-fix',
            get_template_directory_uri() . '/assets/js/mobile-menu-fix.js',
            array(),
            wp_get_theme()->get('Version'),
            true
        ); */
    }
    
    // Script carrousel mobile (sur les pages de réalisations)
    if (is_singular('realisation')) {
        wp_enqueue_script(
            'almetal-gallery-mobile',
            get_template_directory_uri() . '/assets/js/gallery-mobile.js',
            array(),
            wp_get_theme()->get('Version'),
            true
        );
    }
    
    // Script de galerie avancée (seulement sur les pages de réalisations)
    if (is_singular('realisation')) {
        wp_enqueue_script(
            'almetal-gallery-advanced',
            get_template_directory_uri() . '/assets/js/gallery-advanced.js',
            array('jquery'),
            wp_get_theme()->get('Version'),
            true
        );
    }
    
    // Script de la page contact (seulement sur la page contact)
    if (is_page_template('page-contact.php') || is_page('contact')) {
        wp_enqueue_script(
            'almetal-contact',
            get_template_directory_uri() . '/assets/js/contact.js',
            array('jquery'),
            wp_get_theme()->get('Version'),
            true
        );
    }
    
    // CSS des pages légales (mentions légales et politique de confidentialité)
    // Chargement sur les pages avec slug 'mentions-legales' ou 'politique-confidentialite'
    if (is_page_template('page-mentions-legales.php') || 
        is_page_template('page-politique-confidentialite.php') ||
        is_page('mentions-legales') || 
        is_page('politique-confidentialite')) {
        wp_enqueue_style(
            'almetal-legal-pages',
            get_template_directory_uri() . '/assets/css/legal-pages.css',
            array('almetal-style'),
            wp_get_theme()->get('Version')
        );
    }
    
    // Script de filtrage des actualités - DÉSACTIVÉ (doublon, déjà chargé ligne 200)
    /* if (is_front_page() || is_home()) {
        wp_enqueue_script(
            'almetal-actualites-filter',
            get_template_directory_uri() . '/assets/js/actualites-filter.js',
            array(),
            wp_get_theme()->get('Version'),
            true
        );
    } */
    
    // Passer des variables PHP à JavaScript
    wp_localize_script('almetal-script', 'almetalData', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'isMobile' => wp_is_mobile(),
    ));
    
    // Variables pour le script contact
    wp_localize_script('almetal-contact', 'almetal_theme', array(
        'template_url' => get_template_directory_uri(),
    ));
}
add_action('wp_enqueue_scripts', 'almetal_enqueue_scripts');

/**
 * Enregistrer les zones de widgets
 */
function almetal_widgets_init() {
    // Sidebar principale
    register_sidebar(array(
        'name'          => __('Sidebar Principale', 'almetal'),
        'id'            => 'sidebar-1',
        'description'   => __('Zone de widgets pour la sidebar', 'almetal'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    // Footer widgets
    for ($i = 1; $i <= 3; $i++) {
        register_sidebar(array(
            'name'          => sprintf(__('Footer Widget %d', 'almetal'), $i),
            'id'            => 'footer-' . $i,
            'description'   => sprintf(__('Zone de widgets pour le footer %d', 'almetal'), $i),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ));
    }
}
add_action('widgets_init', 'almetal_widgets_init');

/**
 * Détection mobile/desktop
 * Ajouter ?force_mobile=1 dans l'URL pour forcer le mode mobile
 */
function almetal_is_mobile() {
    // Forcer le mode mobile avec paramètre URL (pour tests)
    if (isset($_GET['force_mobile']) && $_GET['force_mobile'] == '1') {
        return true;
    }
    
    return wp_is_mobile();
}

/**
 * CSS CRITIQUE MOBILE - Inline dans le <head>
 * Garantit que le burger est toujours visible et fonctionnel
 */
function almetal_critical_mobile_css() {
    ?>
    <style id="almetal-critical-mobile">
    @media (max-width: 768px) {
        /* Spécificité MAXIMALE pour écraser tout */
        button#mobile-burger-btn.mobile-burger-btn,
        #mobile-burger-btn.mobile-burger-btn,
        .mobile-burger-btn#mobile-burger-btn {
            width: 40px !important;
            height: 40px !important;
            min-width: 40px !important;
            min-height: 40px !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: center !important;
            align-items: center !important;
            gap: 6px !important;
            background: transparent !important;
            border: none !important;
            cursor: pointer !important;
            z-index: 999999 !important;
            position: relative !important;
            pointer-events: auto !important;
            padding: 0 !important;
            margin: 0 !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
        .mobile-burger-btn .mobile-burger-line,
        #mobile-burger-btn .mobile-burger-line {
            width: 28px !important;
            height: 3px !important;
            min-height: 3px !important;
            background: #F08B18 !important;
            border-radius: 2px !important;
            transition: all 0.3s ease !important;
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
        .mobile-header {
            pointer-events: none !important;
            display: block !important;
            width: 100% !important;
            height: 70px !important;
        }
        .mobile-header-inner {
            pointer-events: none !important;
            display: flex !important;
            width: 100% !important;
            height: 100% !important;
            align-items: center !important;
            justify-content: space-between !important;
            padding: 0 1rem !important;
        }
        .mobile-logo,
        .mobile-logo a,
        .mobile-logo img {
            pointer-events: auto !important;
        }
    }
    </style>
    <?php
}
add_action('wp_head', 'almetal_critical_mobile_css', 1);

/**
 * Forcer les templates mobiles pour certaines pages
 */
function almetal_force_mobile_templates($template) {
    if (!almetal_is_mobile()) {
        return $template;
    }
    
    // Archive des réalisations
    if (is_post_type_archive('realisation')) {
        $mobile_template = locate_template('archive-realisation-mobile.php');
        if ($mobile_template) {
            return $mobile_template;
        }
    }
    
    // Page Formations
    if (is_page('formations')) {
        $mobile_template = locate_template('page-formations-mobile.php');
        if ($mobile_template) {
            return $mobile_template;
        }
    }
    
    // Page Contact
    if (is_page('contact')) {
        $mobile_template = locate_template('page-contact-mobile.php');
        if ($mobile_template) {
            return $mobile_template;
        }
    }
    
    return $template;
}
add_filter('template_include', 'almetal_force_mobile_templates', 99);

/**
 * Fonction pour charger le bon template selon le device
 */
function almetal_get_device_template($mobile_template, $desktop_template) {
    if (almetal_is_mobile()) {
        return locate_template($mobile_template);
    } else {
        return locate_template($desktop_template);
    }
}

/**
 * Ajouter des classes au body selon le device
 */
function almetal_body_classes($classes) {
    if (almetal_is_mobile()) {
        $classes[] = 'is-mobile';
        $classes[] = 'mobile-view'; // Pour le padding-top du header fixe
        $classes[] = 'one-page-layout';
    } else {
        $classes[] = 'is-desktop';
        $classes[] = 'multi-page-layout';
    }
    
    return $classes;
}
add_filter('body_class', 'almetal_body_classes');

/**
 * Personnaliser l'excerpt
 */
function almetal_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'almetal_excerpt_length');

function almetal_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'almetal_excerpt_more');

/**
 * Ajouter le support des SVG dans la médiathèque
 */
function almetal_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'almetal_mime_types');

/**
 * Désactiver les emojis WordPress (performance)
 */
function almetal_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
}
add_action('init', 'almetal_disable_emojis');

/**
 * Optimisation : supprimer les versions des CSS/JS (sécurité)
 */
function almetal_remove_version_scripts_styles($src) {
    if (strpos($src, 'ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'almetal_remove_version_scripts_styles', 9999);
add_filter('script_loader_src', 'almetal_remove_version_scripts_styles', 9999);

/**
 * Ajouter un champ personnalisé pour les ancres de navigation one-page
 */
function almetal_add_section_id_metabox() {
    add_meta_box(
        'almetal_section_id',
        __('ID de section (pour navigation one-page)', 'almetal'),
        'almetal_section_id_callback',
        'page',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'almetal_add_section_id_metabox');

function almetal_section_id_callback($post) {
    wp_nonce_field('almetal_section_id_nonce', 'almetal_section_id_nonce');
    $value = get_post_meta($post->ID, '_almetal_section_id', true);
    echo '<input type="text" name="almetal_section_id" value="' . esc_attr($value) . '" class="widefat" placeholder="ex: services, contact, about">';
    echo '<p class="description">Utilisé pour la navigation one-page sur mobile</p>';
}

function almetal_save_section_id($post_id) {
    if (!isset($_POST['almetal_section_id_nonce'])) {
        return;
    }
    if (!wp_verify_nonce($_POST['almetal_section_id_nonce'], 'almetal_section_id_nonce')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (isset($_POST['almetal_section_id'])) {
        update_post_meta($post_id, '_almetal_section_id', sanitize_text_field($_POST['almetal_section_id']));
    }
}
add_action('save_post', 'almetal_save_section_id');

/**
 * Fonction helper pour obtenir l'ID de section
 */
function almetal_get_section_id($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    return get_post_meta($post_id, '_almetal_section_id', true);
}

/**
 * Enqueue Google Fonts
 */
function almetal_enqueue_fonts() {
    wp_enqueue_style(
        'almetal-google-fonts',
        'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto+Flex:opsz,wght@8..144,100..1000&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap',
        array(),
        null
    );
}
add_action('wp_enqueue_scripts', 'almetal_enqueue_fonts');

/**
 * Walker personnalisé pour les menus avec dropdown et icônes
 */
class Almetal_Walker_Nav_Menu extends Walker_Nav_Menu {
    
    /**
     * Démarre le niveau d'un élément
     */
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"sub-menu dropdown-menu\">\n";
    }
    
    /**
     * Démarre un élément
     */
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        // Ajouter la classe pour les items avec sous-menu
        if (in_array('menu-item-has-children', $classes)) {
            $classes[] = 'has-dropdown';
        }
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names .'>';
        
        $atts = array();
        $atts['title']  = ! empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = ! empty($item->target) ? $item->target : '';
        $atts['rel']    = ! empty($item->xfn) ? $item->xfn : '';
        $atts['href']   = ! empty($item->url) ? $item->url : '';
        
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);
        
        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (! empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        
        // Icône personnalisée depuis les meta du menu
        $icon = get_post_meta($item->ID, '_menu_item_icon', true);
        $icon_html = '';
        
        if ($icon) {
            $icon_html = '<span class="menu-icon">' . $icon . '</span>';
        }
        
        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $icon_html;
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        
        // Flèche pour les items avec sous-menu
        if (in_array('menu-item-has-children', $classes)) {
            $item_output .= '<span class="dropdown-arrow">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                    <path d="M2 4L6 8L10 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>';
        }
        
        $item_output .= '</a>';
        $item_output .= $args->after;
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

/**
 * Menu par défaut si aucun menu n'est défini
 */
function almetal_default_menu() {
    echo '<ul id="primary-menu" class="nav-menu">';
    echo '<li class="menu-item"><a href="' . esc_url(home_url('/')) . '">Accueil</a></li>';
    echo '<li class="menu-item has-dropdown">';
    echo '<a href="' . esc_url(home_url('/realisations')) . '">Réalisations';
    echo '<span class="dropdown-arrow"><svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M2 4L6 8L10 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>';
    echo '</a>';
    echo '<ul class="sub-menu dropdown-menu">';
    
    // Récupérer les termes de la taxonomie
    $terms = get_terms(array(
        'taxonomy' => 'type_realisation',
        'hide_empty' => false,
    ));
    
    if (!empty($terms) && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            // Icônes par défaut selon le type
            $icons = array(
                'portail' => '🚪',
                'garde-corps' => '🚧',
                'escalier' => '🪧',
                'pergola' => '☂️',
                'veranda' => '🏠',
                'cloture' => '🚧',
                'mobilier' => '🪑',
                'verriere' => '🧊',
            );
            
            $icon = isset($icons[$term->slug]) ? '<span class="menu-icon">' . $icons[$term->slug] . '</span>' : '';
            echo '<li class="menu-item"><a href="' . esc_url(get_term_link($term)) . '">' . $icon . esc_html($term->name) . '</a></li>';
        }
    }
    
    echo '</ul></li>';
    echo '<li class="menu-item"><a href="' . esc_url(home_url('/formations')) . '">Formations</a></li>';
    echo '<li class="menu-item"><a href="' . esc_url(home_url('/contact')) . '">Contact</a></li>';
    echo '</ul>';
}

/**
 * Ajouter un champ personnalisé pour les icônes de menu
 */
function almetal_menu_item_custom_fields($item_id, $item, $depth, $args) {
    $icon = get_post_meta($item_id, '_menu_item_icon', true);
    ?>
    <p class="field-icon description description-wide">
        <label for="edit-menu-item-icon-<?php echo $item_id; ?>">
            <?php _e('Icône (emoji ou HTML)', 'almetal'); ?><br>
            <input type="text" id="edit-menu-item-icon-<?php echo $item_id; ?>" class="widefat" name="menu-item-icon[<?php echo $item_id; ?>]" value="<?php echo esc_attr($icon); ?>">
            <span class="description"><?php _e('Ex: 🚪 ou <svg>...</svg>', 'almetal'); ?></span>
        </label>
    </p>
    <?php
}
add_action('wp_nav_menu_item_custom_fields', 'almetal_menu_item_custom_fields', 10, 4);

/**
 * Sauvegarder le champ personnalisé des icônes
 */
function almetal_update_menu_item_icon($menu_id, $menu_item_db_id, $args) {
    if (isset($_POST['menu-item-icon'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, '_menu_item_icon', sanitize_text_field($_POST['menu-item-icon'][$menu_item_db_id]));
    } else {
        delete_post_meta($menu_item_db_id, '_menu_item_icon');
    }
}
add_action('wp_update_nav_menu_item', 'almetal_update_menu_item_icon', 10, 3);

/**
 * Inclure les fichiers personnalisés
 */
require_once get_template_directory() . '/inc/custom-post-types.php';
require_once get_template_directory() . '/inc/facebook-importer.php';
require_once get_template_directory() . '/inc/contact-handler.php';
// require_once get_template_directory() . '/inc/customizer.php';

/**
 * Système de Publication Automatique sur les Réseaux Sociaux
 */
require_once get_template_directory() . '/inc/social-auto-publish.php';
require_once get_template_directory() . '/inc/seo-text-generator.php';
require_once get_template_directory() . '/inc/image-optimizer.php';
require_once get_template_directory() . '/inc/social-settings-page.php';
require_once get_template_directory() . '/inc/image-webp-optimizer.php';