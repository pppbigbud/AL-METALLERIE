<?php
/**
 * AL Metallerie Soudure Theme Functions
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
    
    // Styles pour les slides promotionnelles/commerciales
    wp_enqueue_style(
        'almetal-hero-promo',
        get_template_directory_uri() . '/assets/css/hero-promo.css',
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
            time() // Force le rechargement du cache
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
            '2.1.0'
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
    
    // Style et script du formulaire contact MOBILE (page contact ET page d'accueil)
    if (almetal_is_mobile() && (is_front_page() || is_page_template('page-contact.php') || is_page('contact'))) {
        wp_enqueue_style(
            'almetal-mobile-contact-page',
            get_template_directory_uri() . '/assets/css/mobile-contact-page.css',
            array('almetal-style', 'almetal-components'),
            wp_get_theme()->get('Version')
        );
        
        wp_enqueue_script(
            'almetal-mobile-contact-form',
            get_template_directory_uri() . '/assets/js/mobile-contact-form.js',
            array(),
            wp_get_theme()->get('Version'),
            true
        );
        
        // Variables AJAX pour le formulaire mobile
        wp_localize_script('almetal-mobile-contact-form', 'almetal_mobile_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
        ));
    }
    
    // Style et script de la page contact DESKTOP
    if (!almetal_is_mobile() && (is_page_template('page-contact.php') || is_page('contact'))) {
        wp_enqueue_style(
            'almetal-contact',
            get_template_directory_uri() . '/assets/css/contact.css',
            array('almetal-style', 'almetal-components'),
            wp_get_theme()->get('Version')
        );
        
        wp_enqueue_script(
            'almetal-contact',
            get_template_directory_uri() . '/assets/js/contact.js',
            array('jquery'),
            wp_get_theme()->get('Version'),
            true
        );
    }
    
    // Style des pages formations (seulement sur les pages formations)
    if (is_front_page() ||
        is_page_template('page-formations.php') || 
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
    
    // Script principal (DESKTOP UNIQUEMENT) - Version minifiée pour performance
    if (!almetal_is_mobile()) {
        wp_enqueue_script(
            'almetal-script',
            get_template_directory_uri() . '/assets/js/main.min.js',
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
    
    // Script compte à rebours pour les promos (front-page uniquement)
    if (is_front_page()) {
        wp_enqueue_script(
            'almetal-promo-countdown',
            get_template_directory_uri() . '/assets/js/promo-countdown.js',
            array(),
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
        
        // Filtrage AJAX réalisations (front-page uniquement)
        if (is_front_page()) {
            wp_enqueue_script(
                'almetal-mobile-realisations-ajax',
                get_template_directory_uri() . '/assets/js/mobile-realisations-ajax.js',
                array(),
                wp_get_theme()->get('Version'),
                true
            );
        }
        
        // Filtrage archive réalisations (page archive uniquement)
        if (is_post_type_archive('realisation') || is_tax('type_realisation')) {
            wp_enqueue_script(
                'almetal-mobile-archive-filter',
                get_template_directory_uri() . '/assets/js/mobile-archive-filter.js',
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
    
    // Script AJAX réalisations Desktop (front-page uniquement, hors mobile)
    if (is_front_page() && !almetal_is_mobile()) {
        wp_enqueue_script(
            'almetal-desktop-realisations-ajax',
            get_template_directory_uri() . '/assets/js/desktop-realisations-ajax.js',
            array(),
            wp_get_theme()->get('Version'),
            true
        );
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
        
        // Script de galerie avancée
        wp_enqueue_script(
            'almetal-gallery-advanced',
            get_template_directory_uri() . '/assets/js/gallery-advanced.js',
            array('jquery'),
            wp_get_theme()->get('Version'),
            true
        );
        
        // CSS nouvelle mise en page single réalisation V2
        wp_enqueue_style(
            'almetal-single-realisation-v2',
            get_template_directory_uri() . '/assets/css/single-realisation-v2.css',
            array('almetal-style'),
            wp_get_theme()->get('Version')
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
    
    // CSS des pages archives (Réalisations, Formations et Taxonomies)
    if (is_post_type_archive('realisation') || 
        is_page_template('page-formations.php') ||
        is_page_template('page-formations-particuliers.php') ||
        is_page_template('page-formations-professionnels.php') ||
        is_page('formations') ||
        is_page('formations-particuliers') ||
        is_page('formations-professionnels') ||
        is_page('realisations') ||
        is_tax('type_realisation')) {
        wp_enqueue_style(
            'almetal-archive-pages',
            get_template_directory_uri() . '/assets/css/archive-pages.css',
            array('almetal-style'),
            wp_get_theme()->get('Version')
        );
    }
    
    // CSS des pages ville (CPT city_page)
    if (is_singular('city_page') || is_post_type_archive('city_page')) {
        wp_enqueue_style(
            'almetal-city-pages',
            get_template_directory_uri() . '/assets/css/city-pages.css',
            array('almetal-style'),
            wp_get_theme()->get('Version')
        );
    }
    
    // Script de lazy loading pour la page archive des réalisations
    if (is_post_type_archive('realisation') || is_page('realisations')) {
        wp_enqueue_script(
            'almetal-archive-lazy-load',
            get_template_directory_uri() . '/assets/js/archive-lazy-load.js',
            array(),
            wp_get_theme()->get('Version'),
            true
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
    
    // Variables AJAX pour le formulaire de contact
    wp_localize_script('almetal-contact', 'almetal_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
    
    // ============================================
    // BANNIÈRE DE CONSENTEMENT AUX COOKIES
    // ============================================
    
    // CSS de la bannière de cookies (chargé sur toutes les pages)
    wp_enqueue_style(
        'almetal-cookie-banner',
        get_template_directory_uri() . '/assets/css/cookie-banner.css',
        array('almetal-style'),
        wp_get_theme()->get('Version')
    );
    
    // JavaScript de la bannière de cookies (chargé sur toutes les pages)
    wp_enqueue_script(
        'almetal-cookie-consent',
        get_template_directory_uri() . '/assets/js/cookie-consent.js',
        array(),
        wp_get_theme()->get('Version'),
        true // Chargé dans le footer
    );
    
    // ============================================
    // PAGE 404
    // ============================================
    
    // CSS de la page 404 (chargé uniquement sur la page 404)
    if (is_404()) {
        wp_enqueue_style(
            'almetal-error-404',
            get_template_directory_uri() . '/assets/css/error-404.css',
            array('almetal-style'),
            wp_get_theme()->get('Version')
        );
    }
    
    // ============================================
    // PAGE EN CONSTRUCTION
    // ============================================
    
    // CSS de la page En Construction (chargé uniquement sur les pages utilisant ce template)
    if (is_page_template('page-en-construction.php')) {
        wp_enqueue_style(
            'almetal-under-construction',
            get_template_directory_uri() . '/assets/css/under-construction.css',
            array('almetal-style'),
            wp_get_theme()->get('Version')
        );
    }
    
    // Google Analytics 4 - Events Tracking
    wp_enqueue_script(
        'almetal-ga4-events',
        get_template_directory_uri() . '/assets/js/ga4-events.js',
        array(),
        wp_get_theme()->get('Version'),
        true
    );
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
            gap: 4px !important;
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
            width: 26px !important;
            height: 3px !important;
            min-height: 3px !important;
            background: #F08B18 !important;
            border-radius: 10px !important;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55) !important;
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            transform-origin: center !important;
            box-shadow: 0 0 4px rgba(240, 139, 24, 0.3) !important;
        }
        .mobile-burger-btn .mobile-burger-line:nth-child(2) {
            width: 20px !important;
        }
        /* Animation burger → X (croix) avec rebond */
        .mobile-burger-btn.active .mobile-burger-line:nth-child(1) {
            transform: translateY(8px) rotate(45deg) !important;
            width: 26px !important;
        }
        .mobile-burger-btn.active .mobile-burger-line:nth-child(2) {
            opacity: 0 !important;
            transform: scaleX(0) rotate(180deg) !important;
        }
        .mobile-burger-btn.active .mobile-burger-line:nth-child(3) {
            transform: translateY(-8px) rotate(-45deg) !important;
            width: 26px !important;
        }
        .mobile-burger-btn.active .mobile-burger-line {
            box-shadow: 0 0 8px rgba(240, 139, 24, 0.6) !important;
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
    
    // Pages de catégories (taxonomies) - Template dédié avec contenu SEO
    if (is_tax('type_realisation')) {
        $mobile_template = locate_template('taxonomy-type_realisation-mobile.php');
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
 * Enqueue Google Fonts - Optimisé pour performance
 * Charge uniquement les poids utilisés
 */
function almetal_enqueue_fonts() {
    // Version optimisée : seulement les poids réellement utilisés
    wp_enqueue_style(
        'almetal-google-fonts',
        'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap',
        array(),
        null
    );
}
add_action('wp_enqueue_scripts', 'almetal_enqueue_fonts');

/**
 * Préconnexion aux serveurs de polices pour améliorer le LCP
 */
function almetal_preconnect_fonts() {
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
add_action('wp_head', 'almetal_preconnect_fonts', 1);

/**
 * Ajouter fetchpriority et preload pour l'image LCP
 */
function almetal_add_lcp_hints() {
    if (is_front_page()) {
        // Précharger l'image LCP (hero ou première image visible)
        $hero_image = get_template_directory_uri() . '/assets/images/gallery/pexels-kelly-2950108 1.webp';
        echo '<link rel="preload" as="image" href="' . esc_url($hero_image) . '" type="image/webp">' . "\n";
    }
}
add_action('wp_head', 'almetal_add_lcp_hints', 2);

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
require_once get_template_directory() . '/inc/theme-icons.php';
require_once get_template_directory() . '/inc/custom-post-types.php';
require_once get_template_directory() . '/inc/facebook-importer.php';
require_once get_template_directory() . '/inc/contact-handler.php';
require_once get_template_directory() . '/inc/slideshow-admin.php';
require_once get_template_directory() . '/inc/formations-cards-admin.php';
// require_once get_template_directory() . '/inc/customizer.php';

/**
 * Système de Publication Automatique sur les Réseaux Sociaux
 */
require_once get_template_directory() . '/inc/social-auto-publish.php';
require_once get_template_directory() . '/inc/seo-text-generator.php';
require_once get_template_directory() . '/inc/image-optimizer.php';
require_once get_template_directory() . '/inc/social-settings-page.php';
require_once get_template_directory() . '/inc/image-webp-optimizer.php';
require_once get_template_directory() . '/inc/sitemap-generator.php';
require_once get_template_directory() . '/inc/seo-local.php';
require_once get_template_directory() . '/inc/performance-optimizer.php';
require_once get_template_directory() . '/inc/admin-dashboard.php';

/**
 * ============================================================================
 * OPTIMISATIONS SEO AUTOMATIQUES POUR LES RÉALISATIONS
 * ============================================================================
 * Génération automatique de :
 * - Meta tags SEO (title, description, Open Graph, Twitter, géolocalisation)
 * - Schemas JSON-LD (Article, LocalBusiness, BreadcrumbList)
 * - Structure H1/H2/H3 optimisée
 * - Attributs ALT pour les images de galerie
 * - Enrichissement de contenu court
 * - Fil d'Ariane avec microdonnées
 * - Liens internes contextuels
 * ============================================================================
 */

/**
 * 1. GÉNÉRATION AUTOMATIQUE DES META TAGS SEO
 * Injecte les meta tags dans le <head> pour les pages single realisation
 */
function almetal_seo_meta_tags() {
    // Uniquement sur les pages single realisation
    if (!is_singular('realisation')) {
        return;
    }
    
    global $post;
    
    // Récupération des données
    $title = get_the_title();
    $lieu = get_post_meta($post->ID, '_almetal_lieu', true) ?: 'Puy-de-Dôme';
    $client = get_post_meta($post->ID, '_almetal_client', true);
    $duree = get_post_meta($post->ID, '_almetal_duree', true);
    $terms = get_the_terms($post->ID, 'type_realisation');
    $type_realisation = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : 'Métallerie';
    
    // Construction de la description SEO optimisée (max 160 caractères)
    $description = "{$type_realisation} à {$lieu} par AL Métallerie, artisan ferronnier à Thiers (63).";
    if ($duree) {
        $description .= " Réalisé en {$duree}.";
    }
    $description .= " Découvrez ce projet et demandez votre devis gratuit !";
    
    // Tronquer si trop long (160 caractères max)
    if (strlen($description) > 160) {
        $description = substr($description, 0, 157) . '...';
    }
    
    // Image à la une pour Open Graph
    $image_url = get_the_post_thumbnail_url($post->ID, 'large') ?: get_template_directory_uri() . '/assets/images/default-og.jpg';
    
    // URL canonique
    $canonical_url = get_permalink();
    
    // Coordonnées GPS de Peschadoires (siège social)
    $latitude = '45.8344';
    $longitude = '3.1636';
    
    ?>
    <!-- SEO Meta Tags - Générés automatiquement -->
    <meta name="description" content="<?php echo esc_attr($description); ?>">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <link rel="canonical" href="<?php echo esc_url($canonical_url); ?>">
    
    <!-- Open Graph -->
    <meta property="og:locale" content="fr_FR">
    <meta property="og:type" content="article">
    <meta property="og:title" content="<?php echo esc_attr($title . ' - ' . $type_realisation . ' à ' . $lieu); ?>">
    <meta property="og:description" content="<?php echo esc_attr($description); ?>">
    <meta property="og:url" content="<?php echo esc_url($canonical_url); ?>">
    <meta property="og:site_name" content="AL Métallerie & Soudure">
    <meta property="og:image" content="<?php echo esc_url($image_url); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo esc_attr($title . ' - ' . $type_realisation); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr($description); ?>">
    <meta name="twitter:image" content="<?php echo esc_url($image_url); ?>">
    
    <!-- Géolocalisation -->
    <meta name="geo.region" content="FR-63">
    <meta name="geo.placename" content="Peschadoires">
    <meta name="geo.position" content="<?php echo $latitude; ?>;<?php echo $longitude; ?>">
    <meta name="ICBM" content="<?php echo $latitude; ?>, <?php echo $longitude; ?>">
    <?php
}
add_action('wp_head', 'almetal_seo_meta_tags', 1);

/**
 * FONCTION UTILITAIRE : Récupérer les coordonnées GPS d'une ville
 * Retourne les coordonnées du centre-ville pour le référencement local
 */
function almetal_get_ville_coordinates($ville) {
    // Normaliser le nom de la ville (minuscules, sans accents, sans tirets)
    $ville_normalized = strtolower(remove_accents($ville));
    $ville_normalized = str_replace([' ', '-'], '', $ville_normalized);
    
    // Mapping des principales villes du Puy-de-Dôme et environs
    $coordinates = array(
        'clermontferrand' => array('lat' => '45.7772', 'lon' => '3.0870'),
        'thiers' => array('lat' => '45.8556', 'lon' => '3.5478'),
        'riom' => array('lat' => '45.8944', 'lon' => '3.1128'),
        'cournondauvergne' => array('lat' => '45.7361', 'lon' => '3.1972'),
        'issoire' => array('lat' => '45.5433', 'lon' => '3.2489'),
        'aubiere' => array('lat' => '45.7517', 'lon' => '3.1092'),
        'beaumont' => array('lat' => '45.7500', 'lon' => '3.0833'),
        'chamalieres' => array('lat' => '45.7750', 'lon' => '3.0667'),
        'royat' => array('lat' => '45.7667', 'lon' => '3.0500'),
        'pontduchâteau' => array('lat' => '45.7978', 'lon' => '3.2461'),
        'gerzat' => array('lat' => '45.8228', 'lon' => '3.1442'),
        'lempdes' => array('lat' => '45.7711', 'lon' => '3.1969'),
        'peschadoires' => array('lat' => '45.8344', 'lon' => '3.1636'),
        'billom' => array('lat' => '45.7231', 'lon' => '3.3394'),
        'ambert' => array('lat' => '45.5500', 'lon' => '3.7417'),
        'vichy' => array('lat' => '46.1278', 'lon' => '3.4267'),
        'montlucon' => array('lat' => '46.3403', 'lon' => '2.6033'),
        'moulins' => array('lat' => '46.5667', 'lon' => '3.3333'),
    );
    
    // Retourner les coordonnées si trouvées
    return isset($coordinates[$ville_normalized]) ? $coordinates[$ville_normalized] : null;
}

/**
 * 2. GÉNÉRATION AUTOMATIQUE DES SCHEMAS JSON-LD
 * Injecte les microdonnées structurées pour Google
 */
function almetal_seo_json_ld_schemas() {
    // Uniquement sur les pages single realisation
    if (!is_singular('realisation')) {
        return;
    }
    
    global $post;
    
    // Récupération des données
    $title = get_the_title();
    $lieu = get_post_meta($post->ID, '_almetal_lieu', true) ?: 'Puy-de-Dôme';
    $client = get_post_meta($post->ID, '_almetal_client', true);
    $date_realisation = get_post_meta($post->ID, '_almetal_date_realisation', true) ?: get_the_date('Y-m-d');
    $terms = get_the_terms($post->ID, 'type_realisation');
    $type_realisation = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : 'Métallerie';
    
    // Images de la galerie
    $gallery_ids = get_post_meta($post->ID, '_almetal_gallery_images', true);
    $images = [];
    if (!empty($gallery_ids)) {
        $gallery_ids = explode(',', $gallery_ids);
        foreach ($gallery_ids as $img_id) {
            $img_url = wp_get_attachment_image_url(trim($img_id), 'large');
            if ($img_url) {
                $images[] = $img_url;
            }
        }
    }
    // Fallback sur l'image à la une
    if (empty($images)) {
        $featured_img = get_the_post_thumbnail_url($post->ID, 'large');
        if ($featured_img) {
            $images[] = $featured_img;
        }
    }
    
    $content = wp_strip_all_tags(get_the_content());
    $excerpt = wp_trim_words($content, 30, '...');
    
    // Mapping des coordonnées GPS des villes (centre-ville)
    $ville_coords = almetal_get_ville_coordinates($lieu);
    
    // Schema 1 : Article
    $schema_article = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $title,
        'description' => $excerpt,
        'image' => $images,
        'datePublished' => get_the_date('c'),
        'dateModified' => get_the_modified_date('c'),
        'author' => [
            '@type' => 'Organization',
            'name' => 'AL Métallerie',
            'url' => home_url()
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'AL Métallerie',
            'logo' => [
                '@type' => 'ImageObject',
                'url' => get_template_directory_uri() . '/assets/images/logo.png'
            ]
        ],
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => get_permalink()
        ]
    ];
    
    // Ajouter contentLocation si coordonnées disponibles
    if ($ville_coords) {
        $schema_article['contentLocation'] = [
            '@type' => 'Place',
            'name' => $lieu,
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => $ville_coords['lat'],
                'longitude' => $ville_coords['lon']
            ]
        ];
    }
    
    // Schema 2 : LocalBusiness
    $schema_business = [
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        'name' => 'AL Métallerie',
        'image' => get_template_directory_uri() . '/assets/images/logo.png',
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => 'Peschadoires',
            'addressLocality' => 'Peschadoires',
            'postalCode' => '63920',
            'addressRegion' => 'Auvergne-Rhône-Alpes',
            'addressCountry' => 'FR'
        ],
        'geo' => [
            '@type' => 'GeoCoordinates',
            'latitude' => '45.8344',
            'longitude' => '3.1636'
        ],
        'url' => home_url(),
        'telephone' => '+33-4-XX-XX-XX-XX',
        'priceRange' => '$$',
        'areaServed' => [
            '@type' => 'GeoCircle',
            'geoMidpoint' => [
                '@type' => 'GeoCoordinates',
                'latitude' => '45.8344',
                'longitude' => '3.1636'
            ],
            'geoRadius' => '50000'
        ],
        'hasOfferCatalog' => [
            '@type' => 'OfferCatalog',
            'name' => 'Services de métallerie',
            'itemListElement' => [
                [
                    '@type' => 'Offer',
                    'itemOffered' => [
                        '@type' => 'Service',
                        'name' => $type_realisation
                    ]
                ]
            ]
        ]
    ];
    
    // Schema 3 : BreadcrumbList
    $schema_breadcrumb = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Accueil',
                'item' => home_url()
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'Réalisations',
                'item' => get_post_type_archive_link('realisation')
            ]
        ]
    ];
    
    // Ajouter la catégorie si elle existe
    if (!empty($terms) && !is_wp_error($terms)) {
        $schema_breadcrumb['itemListElement'][] = [
            '@type' => 'ListItem',
            'position' => 3,
            'name' => $terms[0]->name,
            'item' => get_term_link($terms[0])
        ];
        $schema_breadcrumb['itemListElement'][] = [
            '@type' => 'ListItem',
            'position' => 4,
            'name' => $title,
            'item' => get_permalink()
        ];
    } else {
        $schema_breadcrumb['itemListElement'][] = [
            '@type' => 'ListItem',
            'position' => 3,
            'name' => $title,
            'item' => get_permalink()
        ];
    }
    
    // Injection des schemas
    ?>
    <!-- Schema.org JSON-LD - Générés automatiquement -->
    <script type="application/ld+json">
    <?php echo wp_json_encode($schema_article, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>
    </script>
    <script type="application/ld+json">
    <?php echo wp_json_encode($schema_business, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>
    </script>
    <script type="application/ld+json">
    <?php echo wp_json_encode($schema_breadcrumb, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>
    </script>
    <?php
}
add_action('wp_head', 'almetal_seo_json_ld_schemas', 2);

/**
 * 3. OPTIMISATION AUTOMATIQUE DE LA STRUCTURE H1/H2/H3
 * Filtre le contenu pour ajouter des titres sémantiques si absents
 */
function almetal_seo_optimize_heading_structure($content) {
    // Uniquement sur les pages single realisation
    if (!is_singular('realisation')) {
        return $content;
    }
    
    global $post;
    
    // Vérifier si le contenu contient déjà des H2
    if (preg_match('/<h[2-3]/i', $content)) {
        return $content; // Structure déjà présente
    }
    
    // Récupération des données
    $lieu = get_post_meta($post->ID, '_almetal_lieu', true) ?: 'Puy-de-Dôme';
    $terms = get_the_terms($post->ID, 'type_realisation');
    $type_realisation = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : 'Métallerie';
    
    // Ajouter une structure H2/H3 optimisée au début du contenu
    $seo_structure = '<h2>Présentation du projet de ' . esc_html($type_realisation) . ' à ' . esc_html($lieu) . '</h2>';
    $seo_structure .= '<p>' . $content . '</p>';
    $seo_structure .= '<h3>Notre expertise en ' . esc_html($type_realisation) . '</h3>';
    $seo_structure .= '<p>AL Métallerie met son savoir-faire au service de vos projets de ' . strtolower(esc_html($type_realisation)) . ' dans le Puy-de-Dôme et ses environs.</p>';
    
    return $seo_structure;
}
add_filter('the_content', 'almetal_seo_optimize_heading_structure', 10);

/**
 * 4. GÉNÉRATION AUTOMATIQUE DES ATTRIBUTS ALT POUR LES IMAGES
 * Ajoute des ALT optimisés et variés aux images de galerie
 */
function almetal_seo_generate_image_alt($attr, $attachment, $size) {
    // Uniquement sur les pages single realisation
    if (!is_singular('realisation')) {
        return $attr;
    }
    
    // Si l'ALT existe déjà, on le garde
    if (!empty($attr['alt'])) {
        return $attr;
    }
    
    global $post;
    
    // Récupération des données
    $title = get_the_title();
    $lieu = get_post_meta($post->ID, '_almetal_lieu', true) ?: 'Puy-de-Dôme';
    $terms = get_the_terms($post->ID, 'type_realisation');
    $type_realisation = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : 'Métallerie';
    
    // Variations d'ALT pour éviter la répétition
    $alt_variations = [
        $type_realisation . ' réalisé par AL Métallerie à ' . $lieu,
        'Projet de ' . strtolower($type_realisation) . ' à ' . $lieu . ' - AL Métallerie',
        'Réalisation ' . strtolower($type_realisation) . ' ' . $lieu . ' par AL Métallerie',
        'Détail du projet de ' . strtolower($type_realisation) . ' à ' . $lieu,
        $title . ' - ' . $type_realisation . ' ' . $lieu
    ];
    
    // Sélection aléatoire mais cohérente (basée sur l'ID de l'image)
    $index = $attachment->ID % count($alt_variations);
    $attr['alt'] = $alt_variations[$index];
    
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'almetal_seo_generate_image_alt', 10, 3);

/**
 * 5. ENRICHISSEMENT AUTOMATIQUE DES CONTENUS COURTS
 * Ajoute du contenu SEO si le texte est trop court (< 200 mots)
 */
function almetal_seo_enrich_short_content($content) {
    // Uniquement sur les pages single realisation
    if (!is_singular('realisation')) {
        return $content;
    }
    
    global $post;
    
    // Compter les mots du contenu
    $word_count = str_word_count(wp_strip_all_tags($content));
    
    // Si le contenu est déjà suffisant, ne rien faire
    if ($word_count >= 200) {
        return $content;
    }
    
    // Récupération des données
    $lieu = get_post_meta($post->ID, '_almetal_lieu', true) ?: 'Puy-de-Dôme';
    $client = get_post_meta($post->ID, '_almetal_client', true);
    $duree = get_post_meta($post->ID, '_almetal_duree', true);
    $terms = get_the_terms($post->ID, 'type_realisation');
    $type_realisation = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : 'Métallerie';
    
    // Contenu d'enrichissement SEO (seulement "À propos")
    $enrichment = '<div class="seo-enrichment">';
    $enrichment .= '<h3>À propos de ce projet</h3>';
    $enrichment .= '<p>Ce projet de ' . strtolower(esc_html($type_realisation)) . ' a été réalisé à ' . esc_html($lieu) . ' par AL Métallerie, spécialiste de la métallerie dans le Puy-de-Dôme.</p>';
    
    if ($client) {
        $enrichment .= '<p>Réalisé pour ' . esc_html($client) . ', ce projet illustre notre expertise et notre engagement envers la qualité.</p>';
    }
    
    if ($duree) {
        $enrichment .= '<p>La durée de réalisation de ce projet a été de ' . esc_html($duree) . ', témoignant de notre efficacité et de notre professionnalisme.</p>';
    }
    
    $enrichment .= '</div>';
    
    return $content . $enrichment;
}
add_filter('the_content', 'almetal_seo_enrich_short_content', 20);

/**
 * 6. FIL D'ARIANE AUTOMATIQUE AVEC SCHEMA
 * Affiche un breadcrumb HTML avec microdonnées
 */
function almetal_seo_breadcrumb() {
    // Uniquement sur les pages single realisation
    if (!is_singular('realisation')) {
        return;
    }
    
    global $post;
    
    $terms = get_the_terms($post->ID, 'type_realisation');
    
    echo '<nav class="breadcrumb" aria-label="Fil d\'Ariane" itemscope itemtype="https://schema.org/BreadcrumbList">';
    
    // Accueil
    echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
    echo '<a itemprop="item" href="' . esc_url(home_url()) . '"><span itemprop="name">Accueil</span></a>';
    echo '<meta itemprop="position" content="1" />';
    echo '</span>';
    echo ' &raquo; ';
    
    // Réalisations
    echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
    echo '<a itemprop="item" href="' . esc_url(get_post_type_archive_link('realisation')) . '"><span itemprop="name">Réalisations</span></a>';
    echo '<meta itemprop="position" content="2" />';
    echo '</span>';
    
    // Catégorie (si existe)
    if (!empty($terms) && !is_wp_error($terms)) {
        echo ' &raquo; ';
        echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<a itemprop="item" href="' . esc_url(get_term_link($terms[0])) . '"><span itemprop="name">' . esc_html($terms[0]->name) . '</span></a>';
        echo '<meta itemprop="position" content="3" />';
        echo '</span>';
        $position = 4;
    } else {
        $position = 3;
    }
    
    // Page actuelle
    echo ' &raquo; ';
    echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
    echo '<span itemprop="name">' . esc_html(get_the_title()) . '</span>';
    echo '<meta itemprop="position" content="' . $position . '" />';
    echo '</span>';
    
    echo '</nav>';
}

/**
 * 7. LIENS INTERNES CONTEXTUELS
 * DÉSACTIVÉ - Bloc "Découvrez nos autres réalisations" supprimé
 */
// function almetal_seo_add_internal_links($content) {
//     // Fonction désactivée
//     return $content;
// }
// add_filter('the_content', 'almetal_seo_add_internal_links', 30);

/**
 * 8. SECTION "POURQUOI CHOISIR AL MÉTALLERIE"
 * DÉSACTIVÉE - Maintenant affichée dans la sidebar (single-realisation.php)
 */
// function almetal_seo_why_choose_us($content) {
//     // Fonction désactivée - contenu déplacé dans la sidebar
//     return $content;
// }
// add_filter('the_content', 'almetal_seo_why_choose_us', 40);

/**
 * 9. ENREGISTREMENT DU CSS POUR LES OPTIMISATIONS SEO
 * Charge les styles pour le breadcrumb, enrichissement et liens internes
 */
function almetal_seo_enqueue_styles() {
    if (is_singular('realisation')) {
        wp_enqueue_style(
            'almetal-seo-enhancements',
            get_template_directory_uri() . '/assets/css/seo-enhancements.css',
            array(),
            '1.0.0'
        );
    }
}
add_action('wp_enqueue_scripts', 'almetal_seo_enqueue_styles');

/**
 * ============================================================================
 * AJAX : CHARGEMENT DES RÉALISATIONS MOBILE AVEC FILTRAGE
 * ============================================================================
 */
function almetal_ajax_load_mobile_realisations() {
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $per_page = 3;
    
    $args = array(
        'post_type' => 'realisation',
        'posts_per_page' => $per_page,
        'paged' => $page,
        'orderby' => 'date',
        'order' => 'DESC',
    );
    
    // Filtrer par catégorie si spécifiée
    if (!empty($category) && $category !== '*') {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'type_realisation',
                'field' => 'slug',
                'terms' => $category,
            ),
        );
    }
    
    $query = new WP_Query($args);
    $html = '';
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            $terms = get_the_terms(get_the_ID(), 'type_realisation');
            $term_classes = '';
            $term_data = '';
            if ($terms && !is_wp_error($terms)) {
                $term_slugs = array_map(function($term) {
                    return $term->slug;
                }, $terms);
                $term_classes = implode(' ', $term_slugs);
                $term_data = implode(' ', $term_slugs);
            }
            
            $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
            if (!$thumbnail_url) {
                $thumbnail_url = get_template_directory_uri() . '/assets/images/gallery/pexels-kelly-2950108 1.webp';
            }
            
            $date_realisation = get_post_meta(get_the_ID(), '_almetal_date_realisation', true);
            $lieu = get_post_meta(get_the_ID(), '_almetal_lieu', true) ?: 'Puy-de-Dôme';
            
            // Alt SEO optimisé avec type + lieu
            $type_name = ($terms && !is_wp_error($terms)) ? $terms[0]->name : 'Réalisation';
            $alt_seo = $type_name . ' à ' . $lieu . ' - ' . get_the_title() . ' | AL Métallerie Thiers';
            
            $html .= '<article class="mobile-realisation-card ' . esc_attr($term_classes) . '" data-categories="' . esc_attr($term_data) . '">';
            $html .= '<div class="mobile-realisation-image-wrapper">';
            $html .= '<a href="' . get_permalink() . '">';
            $html .= '<img src="' . esc_url($thumbnail_url) . '" alt="' . esc_attr($alt_seo) . '" width="400" height="300" loading="lazy" decoding="async" class="mobile-realisation-image">';
            $html .= '<div class="mobile-realisation-overlay"><span class="view-more">Voir le projet</span></div>';
            $html .= '</a>';
            
            if ($terms && !is_wp_error($terms)) {
                $html .= '<div class="mobile-realisation-badges" onclick="event.stopPropagation();">';
                foreach (array_slice($terms, 0, 2) as $term) {
                    $term_link = get_term_link($term);
                    $html .= '<a href="' . esc_url($term_link) . '" class="mobile-realisation-badge" onclick="event.stopPropagation();">';
                    $html .= '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>';
                    $html .= esc_html($term->name);
                    $html .= '</a>';
                }
                $html .= '</div>';
            }
            
            $html .= '</div>';
            $html .= '<div class="mobile-realisation-content">';
            $html .= '<h3 class="mobile-realisation-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
            
            if ($date_realisation || $lieu) {
                $html .= '<div class="mobile-realisation-meta">';
                if ($date_realisation) {
                    $html .= '<span class="meta-item meta-date"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>' . date_i18n('M Y', strtotime($date_realisation)) . '</span>';
                }
                if ($lieu) {
                    $html .= '<span class="meta-item meta-lieu"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>' . esc_html($lieu) . '</span>';
                }
                $html .= '</div>';
            }
            
            $html .= '<a href="' . get_permalink() . '" class="mobile-btn-view-project">';
            $html .= '<span class="circle" aria-hidden="true"><svg class="icon arrow" width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 6H17M17 6L12 1M17 6L12 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>';
            $html .= '<span class="button-text">Voir le projet</span>';
            $html .= '</a>';
            $html .= '</div>';
            $html .= '</article>';
        }
        wp_reset_postdata();
    }
    
    // Calculer s'il y a plus de pages
    $has_more = ($page * $per_page) < $query->found_posts;
    
    wp_send_json_success(array(
        'html' => $html,
        'has_more' => $has_more,
        'total' => $query->found_posts,
        'current_page' => $page,
    ));
}
add_action('wp_ajax_load_mobile_realisations', 'almetal_ajax_load_mobile_realisations');
add_action('wp_ajax_nopriv_load_mobile_realisations', 'almetal_ajax_load_mobile_realisations');

/**
 * ============================================================================
 * AJAX : CHARGEMENT DES RÉALISATIONS DESKTOP AVEC FILTRAGE ET PAGINATION
 * ============================================================================
 */
function almetal_ajax_load_desktop_realisations() {
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $per_page = isset($_POST['per_page']) ? intval($_POST['per_page']) : 6;
    
    $args = array(
        'post_type' => 'realisation',
        'posts_per_page' => $per_page,
        'paged' => $page,
        'orderby' => 'date',
        'order' => 'DESC',
    );
    
    // Filtrer par catégorie si spécifiée
    if (!empty($category)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'type_realisation',
                'field' => 'slug',
                'terms' => $category,
            ),
        );
    }
    
    $query = new WP_Query($args);
    $html = '';
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            $terms = get_the_terms(get_the_ID(), 'type_realisation');
            $term_classes = '';
            if ($terms && !is_wp_error($terms)) {
                $term_slugs = array_map(function($term) {
                    return $term->slug;
                }, $terms);
                $term_classes = implode(' ', $term_slugs);
            }
            
            $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'realisation-card');
            if (!$thumbnail_url) {
                $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
            }
            if (!$thumbnail_url) {
                $thumbnail_url = get_template_directory_uri() . '/assets/images/gallery/pexels-kelly-2950108 1.webp';
            }
            
            // Alt SEO
            $type_name = ($terms && !is_wp_error($terms)) ? $terms[0]->name : 'Réalisation';
            $lieu = get_post_meta(get_the_ID(), '_almetal_lieu', true) ?: 'Puy-de-Dôme';
            $alt_seo = $type_name . ' à ' . $lieu . ' - ' . get_the_title() . ' | AL Métallerie';
            
            $date_realisation = get_post_meta(get_the_ID(), '_almetal_date_realisation', true);
            $duree = get_post_meta(get_the_ID(), '_almetal_duree', true);
            
            $html .= '<article class="realisation-card ' . esc_attr($term_classes) . '" data-categories="' . esc_attr($term_classes) . '">';
            $html .= '<div class="realisation-image-wrapper">';
            $html .= '<img src="' . esc_url($thumbnail_url) . '" alt="' . esc_attr($alt_seo) . '" class="realisation-image" width="400" height="300" loading="lazy" decoding="async">';
            $html .= '</div>';
            $html .= '<div class="realisation-content">';
            $html .= '<h3 class="realisation-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
            
            if ($date_realisation || $lieu || $duree) {
                $html .= '<div class="realisation-meta">';
                if ($date_realisation) {
                    $html .= '<span class="meta-item meta-date"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>' . esc_html($date_realisation) . '</span>';
                }
                if ($lieu) {
                    if (function_exists('cpg_render_city_badge')) {
                        $html .= cpg_render_city_badge($lieu, true);
                    } else {
                        $html .= '<span class="meta-item meta-lieu"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>' . esc_html($lieu) . '</span>';
                    }
                }
                if ($duree) {
                    $html .= '<span class="meta-item meta-duree"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>' . esc_html($duree) . '</span>';
                }
                $html .= '</div>';
            }
            
            $html .= '<a href="' . get_permalink() . '" class="btn-view-project">';
            $html .= '<span class="circle" aria-hidden="true"><svg class="icon arrow" width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 6H17M17 6L12 1M17 6L12 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>';
            $html .= '<span class="button-text">Voir le projet</span>';
            $html .= '</a>';
            $html .= '</div>';
            $html .= '</article>';
        }
        wp_reset_postdata();
    }
    
    // Calculer le total et les restantes
    $total = $query->found_posts;
    $loaded = $page * $per_page;
    $remaining = max(0, $total - $loaded);
    $has_more = $loaded < $total;
    
    wp_send_json_success(array(
        'html' => $html,
        'has_more' => $has_more,
        'total' => $total,
        'remaining' => $remaining,
        'current_page' => $page,
    ));
}
add_action('wp_ajax_load_desktop_realisations', 'almetal_ajax_load_desktop_realisations');
add_action('wp_ajax_nopriv_load_desktop_realisations', 'almetal_ajax_load_desktop_realisations');

/**
 * Charger le CSS admin personnalisé
 */
function almetal_admin_styles() {
    wp_enqueue_style(
        'almetal-admin',
        get_template_directory_uri() . '/assets/css/admin.css',
        array(),
        wp_get_theme()->get('Version')
    );
}
add_action('admin_enqueue_scripts', 'almetal_admin_styles');