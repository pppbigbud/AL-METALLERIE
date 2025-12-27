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
 * Headers de sécurité HTTP
 * Ajoute les headers recommandés pour améliorer la sécurité du site
 */
function almetal_security_headers() {
    if (headers_sent()) {
        return;
    }
    
    // Strict-Transport-Security (HSTS) - Force HTTPS pendant 1 an
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
    
    // Content-Security-Policy - Politique de sécurité du contenu
    // Autorise les ressources du même domaine + Google Fonts/Maps + YouTube + CDNs courants
    $csp = "default-src 'self'; ";
    $csp .= "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.google.com https://www.gstatic.com https://maps.googleapis.googleapis.com https://www.googletagmanager.com https://www.google-analytics.com https://connect.facebook.net https://www.youtube.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com blob:; ";
    $csp .= "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; ";
    $csp .= "img-src 'self' data: https: blob:; ";
    $csp .= "font-src 'self' https://fonts.gstatic.com data:; ";
    $csp .= "frame-src 'self' https://www.google.com https://www.youtube.com https://www.youtube-nocookie.com https://maps.google.com https://maps.googleapis.com; ";
    $csp .= "connect-src 'self' https://www.google-analytics.com https://maps.googleapis.com https://fonts.googleapis.com; ";
    $csp .= "object-src 'none'; ";
    $csp .= "base-uri 'self'; ";
    $csp .= "form-action 'self';";
    header('Content-Security-Policy: ' . $csp);
    
    // Referrer-Policy - Contrôle les informations de référent envoyées
    header('Referrer-Policy: strict-origin-when-cross-origin');
    
    // Permissions-Policy - Désactive les fonctionnalités non utilisées
    $permissions = 'accelerometer=(), camera=(), geolocation=(self), gyroscope=(), magnetometer=(), microphone=(), payment=(), usb=()';
    header('Permissions-Policy: ' . $permissions);
    
    // X-Content-Type-Options - Empêche le sniffing MIME (déjà présent mais on s'assure)
    header('X-Content-Type-Options: nosniff');
    
    // X-Frame-Options - Protection contre le clickjacking (déjà présent mais on s'assure)
    header('X-Frame-Options: SAMEORIGIN');
    
    // X-XSS-Protection - Protection XSS pour anciens navigateurs
    header('X-XSS-Protection: 1; mode=block');
}
add_action('send_headers', 'almetal_security_headers');

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
        
        // Pages Matières
        if (is_singular('matiere')) {
            wp_enqueue_style(
                'almetal-matiere',
                get_template_directory_uri() . '/assets/css/matiere.css',
                array('almetal-style', 'almetal-components'),
                wp_get_theme()->get('Version')
            );
        }
    }
    
    // ============================================
    // CSS MOBILE UNIQUEMENT
    // ============================================
    if (almetal_is_mobile()) {
        // CSS Mobile unifié (remplace tous les anciens fichiers mobiles)
        $mobile_unified_css = get_template_directory() . '/assets/css/mobile-unified.css';
        wp_enqueue_style(
            'almetal-mobile-unified',
            get_template_directory_uri() . '/assets/css/mobile-unified.css',
            array('almetal-style', 'almetal-components'),
            file_exists($mobile_unified_css) ? filemtime($mobile_unified_css) : wp_get_theme()->get('Version')
        );
    }

    if (!function_exists('almetal_matiere_mobile_footer_styles')) {
        function almetal_matiere_mobile_footer_styles() {
            if (!function_exists('almetal_is_mobile') || !almetal_is_mobile() || !is_singular('matiere')) {
                return;
            }

            echo '<style id="almetal-matiere-mobile-override">'
                . 'body{background:#191919 !important;}'
                . '.single-matiere{background:#191919 !important;color:#fff !important;min-height:100vh !important;}'
                . '.single-matiere .matiere-properties,.single-matiere .matiere-applications,.single-matiere .matiere-realisations,.single-matiere .matiere-content,.single-matiere .matiere-faq,.single-matiere .matiere-cta{background:#191919 !important;}'
                . '.single-matiere .properties-card,.single-matiere .application-item,.single-matiere .realisation-card,.single-matiere .faq-item,.single-matiere .content-wrapper{background:rgba(255,255,255,0.05) !important;border:1px solid rgba(255,255,255,0.1) !important;border-radius:16px !important;}'
                . '.single-matiere .section-title,.single-matiere .properties-card__title,.single-matiere .realisation-card__title,.single-matiere .matiere-cta .cta-content h2{color:#fff !important;}'
                . '.single-matiere .section-title__icon svg,.single-matiere .realisation-card__meta svg,.single-matiere .faq-icon{stroke:#F08B18 !important;}'
                . '.single-matiere .btn-view-all,.single-matiere .matiere-cta .cta-button{background:linear-gradient(135deg,#F08B18 0%,#e67e0f 100%) !important;color:#fff !important;border-radius:25px !important;}'
                . '</style>';
        }
    }

    if (!function_exists('has_action') || !has_action('wp_footer', 'almetal_matiere_mobile_footer_styles')) {
        add_action('wp_footer', 'almetal_matiere_mobile_footer_styles', 100);
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
    if (is_page_template('page-contact.php') || is_page('contact')) {
        wp_enqueue_style(
            'leaflet-css',
            'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css',
            array(),
            '1.9.4'
        );
        
        wp_enqueue_style(
            'almetal-contact',
            get_template_directory_uri() . '/assets/css/contact.css',
            array('almetal-style', 'almetal-components', 'leaflet-css'),
            wp_get_theme()->get('Version')
        );
        
        wp_enqueue_script(
            'leaflet-js',
            'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js',
            array(),
            '1.9.4',
            true
        );
        
        wp_enqueue_script(
            'almetal-contact',
            get_template_directory_uri() . '/assets/js/contact.js',
            array('jquery', 'leaflet-js'),
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
        is_page('formations-professionnelles')) {
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
        
        // Script AJAX pour charger plus de réalisations (desktop)
        wp_enqueue_script(
            'almetal-desktop-realisations-ajax',
            get_template_directory_uri() . '/assets/js/desktop-realisations-ajax.js',
            array(),
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
        
        // Patch pour corriger les API obsolètes Chrome
        wp_enqueue_script(
            'almetal-obsolete-api-patch',
            get_template_directory_uri() . '/assets/js/obsolete-api-patch.js',
            array(),
            '1.0.0',
            true
        );
        
        // Solution finale pour les faux positifs Chrome
        wp_enqueue_script(
            'almetal-chrome-api-fix-final',
            get_template_directory_uri() . '/assets/js/chrome-api-fix-final.js',
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
        is_page('formations-professionnelles') ||
        is_page('realisations') ||
        is_tax('type_realisation')) {
        wp_enqueue_style(
            'almetal-archive-pages',
            get_template_directory_uri() . '/assets/css/archive-pages.css',
            array('almetal-style'),
            wp_get_theme()->get('Version')
        );
    }
    
    // CSS pour les pages ville (city_page)
    if (is_singular('city_page')) {
        $city_pages_css = get_template_directory() . '/assets/css/city-pages.css';
        wp_enqueue_style(
            'almetal-city-pages',
            get_template_directory_uri() . '/assets/css/city-pages.css',
            array('almetal-style'),
            file_exists($city_pages_css) ? filemtime($city_pages_css) : wp_get_theme()->get('Version')
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
 * Redirections 301 pour les réalisations avec slugs modifiés
 */
function almetal_realisation_redirects() {
    $redirects = array(
        // Ancienne URL => Nouvelle URL
        '/realisations/grilles-serrurerie-cunlhat-11-03-2025/' => '/realisations/grilles-serrurerie-cunlhat/',
        '/realisations/portails-escoutoux-01-07-2025/' => '/realisations/portails-escoutoux/',
        '/realisations/garde-corps-chamalieres-20-11-2023/' => '/realisations/garde-corps-chamalieres/',
        
        // Ajouter d'autres redirections ici si nécessaire
        // '/ancienne-url/' => '/nouvelle-url/',
    );
    
    $request_uri = $_SERVER['REQUEST_URI'];
    
    foreach ($redirects as $old => $new) {
        if ($request_uri === $old) {
            wp_redirect(home_url($new), 301);
            exit();
        }
    }
}
add_action('template_redirect', 'almetal_realisation_redirects');

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

add_filter('template_include', 'almetal_force_city_page_template', 99);
function almetal_force_city_page_template($template) {
    if (!is_singular('city_page')) {
        return $template;
    }

    $theme_template = locate_template('single-city_page.php');
    if (!empty($theme_template)) {
        return $theme_template;
    }

    return $template;
}

add_action('template_redirect', 'almetal_disable_cache_for_city_pages', 0);
function almetal_disable_cache_for_city_pages() {
    if (!is_singular('city_page')) {
        return;
    }

    if (!defined('DONOTCACHEPAGE')) {
        define('DONOTCACHEPAGE', true);
    }
    if (!defined('DONOTCACHEDB')) {
        define('DONOTCACHEDB', true);
    }
    if (!defined('DONOTMINIFY')) {
        define('DONOTMINIFY', true);
    }

    if (function_exists('nocache_headers')) {
        nocache_headers();
    }
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
    if (strpos($src, '/assets/css/city-pages.css') !== false) {
        return $src;
    }

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

function almetal_get_city_page_url($city_name) {
    $city_name = is_string($city_name) ? trim($city_name) : '';
    if ($city_name === '') {
        return null;
    }

    // Recherche exacte via meta _cpg_city_name
    $exact = get_posts(array(
        'post_type' => 'city_page',
        'posts_per_page' => 1,
        'post_status' => 'publish',
        'fields' => 'ids',
        'meta_query' => array(
            array(
                'key' => '_cpg_city_name',
                'value' => $city_name,
                'compare' => '=',
            ),
        ),
    ));

    if (!empty($exact) && !is_wp_error($exact)) {
        return get_permalink((int) $exact[0]);
    }

    // Fallback: recherche dans le titre
    $search = get_posts(array(
        'post_type' => 'city_page',
        'posts_per_page' => 1,
        'post_status' => 'publish',
        'fields' => 'ids',
        's' => $city_name,
    ));

    if (!empty($search) && !is_wp_error($search)) {
        return get_permalink((int) $search[0]);
    }

    return null;
}

function almetal_city_link_html($city_name, $class = '') {
    $city_name = is_string($city_name) ? trim($city_name) : '';
    if ($city_name === '') {
        return '';
    }

    $url = almetal_get_city_page_url($city_name);
    if (!$url) {
        return esc_html($city_name);
    }

    $class_attr = $class !== '' ? ' class="' . esc_attr($class) . '"' : '';
    return '<a href="' . esc_url($url) . '"' . $class_attr . '>' . esc_html($city_name) . '</a>';
}

function almetal_get_city_pages_map() {
    static $map = null;
    if (is_array($map)) {
        return $map;
    }

    $map = array();
    $posts = get_posts(array(
        'post_type' => 'city_page',
        'posts_per_page' => 300,
        'post_status' => 'publish',
        'fields' => 'ids',
        'orderby' => 'date',
        'order' => 'DESC',
    ));

    if (empty($posts) || is_wp_error($posts)) {
        return $map;
    }

    foreach ($posts as $id) {
        $name = get_post_meta($id, '_cpg_city_name', true);
        if (!$name) {
            $name = get_the_title($id);
        }
        $name = is_string($name) ? trim($name) : '';
        if ($name === '') {
            continue;
        }

        $map[$name] = get_permalink((int) $id);
    }

    // Plus longs d'abord pour éviter de lier "Riom" dans "Riom-ès-Montagnes"
    uksort($map, function ($a, $b) {
        return mb_strlen($b) <=> mb_strlen($a);
    });

    return $map;
}

add_filter('the_content', 'almetal_autolink_city_pages_in_content', 20);

/**
 * Auto-lier les noms de villes vers leurs pages ville respectives
 * S'applique sur toutes les pages, première occurrence de chaque ville uniquement
 */
function almetal_autolink_city_pages_in_content($content) {
    // Ne pas appliquer dans l'admin ou sur les archives
    if (is_admin() || is_archive() || is_home()) {
        return $content;
    }
    
    // Appliquer sur les pages ville, réalisations et pages standard
    if (!is_singular(array('city_page', 'realisation', 'page', 'post'))) {
        return $content;
    }

    $map = almetal_get_city_pages_map();
    if (empty($map)) {
        return $content;
    }
    
    // Exclure la ville de la page courante (éviter de lier vers soi-même)
    $current_city = '';
    if (is_singular('city_page')) {
        $current_city = get_post_meta(get_the_ID(), '_cpg_city_name', true);
    }

    // Tracker les villes déjà liées (une seule fois par ville)
    $linked_cities = array();

    // Pour chaque ville dans la map, remplacer la première occurrence
    foreach ($map as $city => $url) {
        if ($city === '' || !$url) {
            continue;
        }
        
        // Ne pas lier vers la page courante
        if ($current_city && mb_strtolower($city) === mb_strtolower($current_city)) {
            continue;
        }
        
        // Ne lier qu'une seule fois par ville (déjà fait par preg_replace avec limit=1)
        if (isset($linked_cities[mb_strtolower($city)])) {
            continue;
        }

        // Échapper les caractères spéciaux regex
        $city_escaped = preg_quote($city, '/');
        
        // Pattern: le nom de la ville, pas déjà dans un lien (lookbehind négatif pour <a et href)
        // On utilise un pattern simple qui évite de matcher dans les balises HTML
        $pattern = '/(?<!["\'>\/])(\b' . $city_escaped . '\b)(?![^<]*<\/a>)/iu';
        
        // Vérifier si la ville est présente (hors liens existants)
        if (preg_match($pattern, $content)) {
            $replacement = '<a href="' . esc_url($url) . '" class="city-autolink">$1</a>';
            $content = preg_replace($pattern, $replacement, $content, 1); // limit=1 pour première occurrence seulement
            $linked_cities[mb_strtolower($city)] = true;
        }
    }

    return $content;
}
// ... (code inchangé)
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
require_once get_template_directory() . '/inc/city-hero-helper.php';
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
require_once get_template_directory() . '/inc/realisation-content-generator.php';
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
            
            $html .= '<article class="mobile-realisation-card scroll-slide-up">';
            $html .= '<div class="mobile-realisation-card-inner">';
            $html .= '<div class="mobile-realisation-image">';
            $html .= '<img src="' . esc_url($thumbnail_url) . '" alt="' . esc_attr($alt_seo) . '" width="400" height="300" loading="lazy" decoding="async">';
            
            // Badge de ville en bas à gauche
            if ($lieu) {
                $html .= '<div class="mobile-city-badge">';
                $html .= '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">';
                $html .= '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>';
                $html .= '<circle cx="12" cy="10" r="3"/>';
                $html .= '</svg>';
                $html .= almetal_city_link_html($lieu, 'mobile-city-link');
                $html .= '</div>';
            }
            
            // Badges catégories et matériaux en haut à droite
            $html .= '<div class="mobile-top-badges">';
            
            // Badge matériau
            $matiere = get_post_meta(get_the_ID(), '_almetal_matiere', true);
            if ($matiere) {
                $matiere_labels = array(
                    'acier' => 'Acier',
                    'inox' => 'Inox',
                    'aluminium' => 'Aluminium',
                    'cuivre' => 'Cuivre',
                    'laiton' => 'Laiton',
                    'fer-forge' => 'Fer forgé',
                    'mixte' => 'Mixte'
                );
                $html .= '<a href="' . esc_url(almetal_get_matiere_url($matiere)) . '" class="mobile-matiere-badge">';
                $html .= esc_html($matiere_labels[$matiere] ?? ucfirst($matiere));
                $html .= '</a>';
            }
            
            // Badges catégories avec SVG
            if ($terms && !is_wp_error($terms)) {
                $mobile_icons = array(
                    'portails' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="18" rx="1"/><rect x="14" y="3" width="7" height="18" rx="1"/></svg>',
                    'garde-corps' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/><circle cx="6" cy="12" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="18" cy="12" r="1"/></svg>',
                    'escaliers' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 20h4v-4h4v-4h4V8h4"/></svg>',
                    'pergolas' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M4 18h16M5 15h14M6 12h12M7 9h10M8 6h8M9 3h6"/></svg>',
                    'grilles' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18M15 3v18"/></svg>',
                    'ferronnerie-art' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 20c4-4 4-12 8-12s4 8 8 12"/><path d="M4 16c3-3 3-8 6-8s3 5 6 8"/><circle cx="12" cy="8" r="2"/></svg>',
                    'ferronnerie-dart' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 20c4-4 4-12 8-12s4 8 8 12"/><path d="M4 16c3-3 3-8 6-8s3 5 6 8"/><circle cx="12" cy="8" r="2"/></svg>',
                    'vehicules' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 17h14v-5l-2-4H7l-2 4v5z"/><path d="M3 17h18v2H3z"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/><path d="M5 12h14"/></svg>',
                    'serrurerie' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M12 16v2"/><circle cx="12" cy="16" r="1"/><path d="M8 11V7a4 4 0 1 1 8 0v4"/></svg>',
                    'mobilier-metallique' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="6" width="16" height="4" rx="1"/><path d="M6 10v10M18 10v10"/><path d="M4 14h16"/></svg>',
                    'autres' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>'
                );
                
                foreach ($terms as $term) {
                    $icon_svg = isset($mobile_icons[$term->slug]) ? $mobile_icons[$term->slug] : $mobile_icons['autres'];
                    $html .= '<a href="' . esc_url(get_term_link($term)) . '" class="mobile-category-badge">';
                    $html .= $icon_svg;
                    $html .= esc_html($term->name);
                    $html .= '</a>';
                }
            }
            
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="mobile-realisation-content">';
            $html .= '<h3 class="mobile-realisation-title">';
            $html .= '<a href="' . get_permalink() . '">';
            $html .= get_the_title();
            $html .= '</a>';
            $html .= '</h3>';
            
            if (has_excerpt()) {
                $html .= '<p class="mobile-realisation-excerpt">' . wp_trim_words(get_the_excerpt(), 15) . '</p>';
            }
            
            $html .= '<span class="mobile-realisation-cta">';
            $html .= '<a href="' . get_permalink() . '">';
            $html .= esc_html__('Voir le projet', 'almetal');
            $html .= '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">';
            $html .= '<line x1="5" y1="12" x2="19" y2="12"></line>';
            $html .= '<polyline points="12 5 19 12 12 19"></polyline>';
            $html .= '</svg>';
            $html .= '</a>';
            $html .= '</span>';
            $html .= '</div>';
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
 * ============================================================================/**
 * Fonction helper pour générer le HTML d'une card de réalisation
 * Utilise le template-part card-realisation.php avec buffering
 * 
 * @param array $args Arguments pour personnaliser l'affichage
 * @return string HTML de la card
 */
function almetal_get_realisation_card_html($args = array()) {
    ob_start();
    get_template_part('template-parts/card-realisation', null, $args);
    return ob_get_clean();
}

/**
 * Fonction helper pour charger le template-part avec buffering
 * Utilise la fonction almetal_get_realisation_card_html pour générer le HTML
 */
function almetal_get_realisation_card($args = array()) {
    echo almetal_get_realisation_card_html($args);
}

/**
 * ============================================================================
 * DONNÉES STRUCTURÉES SCHEMA.ORG POUR LES PAGES CATÉGORIES
 * ============================================================================
 */

// Ajout des données structurées pour les pages de taxonomie type_realisation
function almetal_add_taxonomy_schema_service() {
    // Vérifier si nous sommes sur une page de taxonomie type_realisation
    if (is_tax('type_realisation')) {
        $term = get_queried_object();
        
        // Contenu SEO par catégorie pour les données structurées
        $seo_data = array(
            'portails' => array(
                'serviceType' => 'Fabrication de portails sur mesure',
                'description' => 'Création et installation de portails battants, coulissants et motorisés dans le Puy-de-Dôme',
                'areaServed' => array('Thiers', 'Clermont-Ferrand', 'Peschadoires', 'Riom', 'Issoire', 'Auvergne')
            ),
            'garde-corps' => array(
                'serviceType' => 'Fabrication de garde-corps sur mesure',
                'description' => 'Conception et pose de garde-corps, balustrades et rampes sécurisées selon les normes NF P01-012',
                'areaServed' => array('Thiers', 'Clermont-Ferrand', 'Peschadoires', 'Riom', 'Issoire', 'Auvergne')
            ),
            'escaliers' => array(
                'serviceType' => 'Fabrication d\'escaliers métalliques',
                'description' => 'Création d\'escaliers droits, quart tournant, hélicoïdaux en acier, inox ou bois/métal',
                'areaServed' => array('Thiers', 'Clermont-Ferrand', 'Peschadoires', 'Riom', 'Issoire', 'Auvergne')
            ),
            'ferronnerie-dart' => array(
                'serviceType' => 'Ferronnerie d\'art',
                'description' => 'Créations artistiques en fer forgé, restauration de pièces anciennes et œuvres décoratives',
                'areaServed' => array('Thiers', 'Clermont-Ferrand', 'Peschadoires', 'Riom', 'Issoire', 'Auvergne')
            ),
            'grilles' => array(
                'serviceType' => 'Fabrication de grilles de protection',
                'description' => 'Grilles de sécurité, décoratives, de fenêtre et de porte sur mesure',
                'areaServed' => array('Thiers', 'Clermont-Ferrand', 'Peschadoires', 'Riom', 'Issoire', 'Auvergne')
            ),
            'serrurerie' => array(
                'serviceType' => 'Serrurerie métallique',
                'description' => 'Fabrication de portes métalliques, portillons et systèmes de fermeture haute sécurité',
                'areaServed' => array('Thiers', 'Clermont-Ferrand', 'Peschadoires', 'Riom', 'Issoire', 'Auvergne')
            ),
            'mobilier-metallique' => array(
                'serviceType' => 'Création de mobilier métallique',
                'description' => 'Tables, étagères, verrières et mobilier design sur mesure en métal et matériaux associés',
                'areaServed' => array('Thiers', 'Clermont-Ferrand', 'Peschadoires', 'Riom', 'Issoire', 'Auvergne')
            ),
            'vehicules' => array(
                'serviceType' => 'Aménagements métalliques pour véhicules',
                'description' => 'Hard-tops, racks, protections et équipements sur mesure pour pick-ups et utilitaires',
                'areaServed' => array('Thiers', 'Clermont-Ferrand', 'Peschadoires', 'Riom', 'Issoire', 'Auvergne')
            ),
            'autres' => array(
                'serviceType' => 'Métallerie sur mesure',
                'description' => 'Réalisations personnalisées en métal, structures techniques et projets spéciaux',
                'areaServed' => array('Thiers', 'Clermont-Ferrand', 'Peschadoires', 'Riom', 'Issoire', 'Auvergne')
            )
        );
        
        $data = isset($seo_data[$term->slug]) ? $seo_data[$term->slug] : $seo_data['autres'];
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $data['serviceType'],
            'description' => $data['description'],
            'provider' => array(
                '@type' => 'LocalBusiness',
                'name' => 'AL Métallerie & Soudure',
                'address' => array(
                    '@type' => 'PostalAddress',
                    'streetAddress' => 'Zone Artisanale de la Goutte',
                    'addressLocality' => 'Peschadoires',
                    'postalCode' => '63570',
                    'addressCountry' => 'FR'
                ),
                'telephone' => '+33673333532',
                'email' => 'contact@al-metallerie.fr',
                'url' => 'https://al-metallerie.fr',
                'geo' => array(
                    '@type' => 'GeoCoordinates',
                    'latitude' => '45.8567',
                    'longitude' => '3.5530'
                ),
                'openingHoursSpecification' => array(
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'),
                    'opens' => '08:00',
                    'closes' => '18:00'
                )
            ),
            'areaServed' => array(
                '@type' => 'Place',
                'name' => implode(', ', $data['areaServed'])
            ),
            'serviceType' => $data['serviceType'],
            'offers' => array(
                '@type' => 'Offer',
                'itemOffered' => array(
                    '@type' => 'Service',
                    'name' => $data['serviceType']
                ),
                'availability' => 'https://schema.org/InStock',
                'priceCurrency' => 'EUR',
                'description' => 'Devis gratuit et personnalisé'
            )
        );
        
        echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
        
        // Ajouter le Schema.org FAQPage pour les rich snippets
        if (isset($current_seo['faq']) && !empty($current_seo['faq'])) {
            $faq_schema = array(
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => array()
            );
            
            // Récupérer les FAQ depuis le tableau SEO
            $seo_contents = array(
                'portails' => array(
                    'faq' => array(
                        'Quels sont les délais de fabrication d\'un portail sur mesure ?' => 'Les délais varient de 4 à 8 semaines selon la complexité du design et les matériaux choisis. Nous vous fournissons un planning précis lors de la validation du devis.',
                        'Quelle est la différence entre un portail en acier et en aluminium ?' => 'L\'acier est plus robuste et économique, idéal pour les grands portails. L\'aluminium est plus léger, ne rouille pas et convient parfaitement aux portails motorisés.',
                        'Fournissez-vous la motorisation du portail ?' => 'Oui, nous sommes partenaires des meilleures marques (Somfy, Nice, BFT) et nous assurons l\'installation complète avec garantie décennale.',
                        'Quel entretien nécessite un portail métallique ?' => 'Un portail thermolaqué nécessite peu d\'entretien : un nettoyage annuel à l\'eau savonneuse suffit. Le fer forgé nécessite une couche de protection tous les 5-7 ans.',
                        'Quelle est la durée de garantie de vos portails ?' => 'Nous offrons une garantie décennale sur la structure et 2 ans sur la motorisation. Tous nos portails sont assurés pendant 10 ans en responsabilité civile professionnelle.'
                    )
                ),
                'garde-corps' => array(
                    'faq' => array(
                        'Quelles sont les normes pour les garde-corps ?' => 'Les garde-corps doivent respecter la norme NF P01-012 : hauteur minimale de 1m pour les balcons, 0.9m pour les escaliers, et espacement maximum de 11cm entre les éléments.',
                        'Quels matériaux choisir pour un garde-corps extérieur ?' => 'L\'inox 316 est idéal pour l\'extérieur (ne rouille pas), l\'acier thermolaqué offre un large choix de couleurs, et le fer forgé apporte un style traditionnel très recherché.',
                        'Pouvez-vous installer des garde-corps sur mesure pour des formes particulières ?' => 'Oui, nous adaptons nos garde-corps à toutes les configurations : escaliers quart tournant, terrasses arrondies, balcons trapézoïdaux, etc.',
                        'Quel est le prix moyen d\'un garde-corps sur mesure ?' => 'Les prix varient de 250€ à 800€ par mètre linéaire selon le matériau, le design et la complexité de l\'installation.',
                        'Assurez-vous la mise en conformité des garde-corps existants ?' => 'Oui, nous pouvons rénover et mettre aux normes vos garde-corps existants en renforçant la structure ou en modifiant les espacements.'
                    )
                ),
                'escaliers' => array(
                    'faq' => array(
                        'Quels types d\'escaliers métalliques fabriquez-vous ?' => 'Nous réalisons des escaliers droits, quart tournant, demi-tournant, hélicoïdaux, et sur mesure avec toutes les configurations possibles.',
                        'Quelle est la hauteur de marche idéale pour un escalier confortable ?' => 'La hauteur idéale se situe entre 16 et 18cm, avec un giron de 25 à 30cm. Nous calculons le balancement optimal pour chaque projet.',
                        'Pouvez-vous associer le métal à d\'autres matériaux ?' => 'Oui, nous combinons régulièrement l\'acier avec le bois (chêne, hêtre), le verre, ou la pierre pour des escaliers personnalisés.',
                        'Quel est le délai de fabrication d\'un escalier sur mesure ?' => 'Comptez 6 à 10 semaines selon la complexité, incluant la prise de mesures, la fabrication en atelier et l\'installation.',
                        'Vos escaliers sont-ils garantis ?' => 'Oui, nous appliquons la garantie décennale sur la structure et 2 ans sur les finitions. Tous nos escaliers sont conformes aux normes de sécurité.'
                    )
                ),
                'ferronnerie-dart' => array(
                    'faq' => array(
                        'Quelle est la différence entre ferronnerie et serrurerie ?' => 'La ferronnerie d\'art concerne les éléments décoratifs et architecturaux (grilles, portails, rampes), tandis que la serrurerie se concentre sur les éléments fonctionnels (serrures, fermures).',
                        'Pouvez-vous reproduire des motifs anciens ?' => 'Oui, nous maîtrisons les techniques traditionnelles et pouvons reproduire ou restaurer des pièces d\'époque tout en respectant le style d\'origine.',
                        'Quels types de créations en ferronnerie d\'art proposez-vous ?' => 'Nous créons des grilles décoratives, portails ornementaux, rampes d\'escalier, luminaires, mobilier, et toutes pièces sur mesure.',
                        'Comment entretenir la ferronnerie d\'art ?' => 'Un traitement anti-corrosion est appliqué en usine. Un entretien annuel avec des produits adaptés préserve l\'aspect et la durabilité.',
                        'Travaillez-vous pour les monuments historiques ?' => 'Oui, nous avons l\'expérience nécessaire pour les chantiers de restauration du patrimoine et nous respectons les contraintes architecturales.'
                    )
                ),
                'grilles' => array(
                    'faq' => array(
                        'Quels types de grilles de protection fabriquez-vous ?' => 'Grilles de fenêtre, de porte, de soupirail, de ventilation, et toutes protections sur mesure pour vos ouvertures.',
                        'Les grilles sont-elles efficaces contre les effractions ?' => 'Oui, nos grilles en acier de 10mm avec soudures continues offrent une excellente protection. Nous pouvons aussi intégrer des serrures de sécurité.',
                        'Pouvez-vous créer des grilles décoratives ?' => 'Oui, nous réalisons des grilles alliant sécurité et esthétique avec des motifs personnalisés qui s\'intègrent à votre architecture.',
                        'Quelle est la différence entre grille fixe et ouvrante ?' => 'La grille fixe offre une sécurité maximale, la grille ouvrante permet l\'accès en cas d\'urgence (obligatoire pour certaines fenêtres).',
                        'Comment fixez-vous les grilles ?' => 'Nous utilisons des fixations scellées dans la maçonnerie ou des visseries anti-effraction selon le support et le type de grille.'
                    )
                ),
                'serrurerie' => array(
                    'faq' => array(
                        'Quels types de portes métalliques fabriquez-vous ?' => 'Portes d\'entrée, portes de garage, portillons, portes de service, et toutes ouvertures sur mesure en acier ou aluminium.',
                        'Proposez-vous des serrures haute sécurité ?' => 'Oui, nous intégrons les marques leaders (Fichet, Vachette, Mul-T-Lock) avec certification A2P et certification européenne.',
                        'Pouvez-vous motoriser les portes existantes ?' => 'Oui, nous adaptons des motorisations sur portes sectionnelles, battantes, ou coulissantes avec télécommande et contrôle d\'accès.',
                        'Quelle est la résistance au feu de vos portes ?' => 'Nous pouvons fabriquer des portes coupe-feu (CF 1h, 2h) certifiées et conformes à la réglementation ERP et habitation.',
                        'Assurez-vous l\'entretien des serrures ?' => 'Oui, nous proposons des contrats de maintenance annuelle pour vérifier et entretenir vos systèmes de fermeture.'
                    )
                ),
                'mobilier-metallique' => array(
                    'faq' => array(
                        'Quels types de mobilier métallique pouvez-vous fabriquer ?' => 'Tables, chaises, étagères, verrières, bureaux, rangements, luminaires, et toutes créations personnalisées.',
                        'Pouvez-vous associer le métal à d\'autres matériaux ?' => 'Oui, nous combinons l\'acier avec le bois massif, le verre, le béton, ou la pierre selon vos préférences.',
                        'Le mobilier métallique est-il adapté à l\'intérieur ?' => 'Absolument, avec les bonnes finitions (thermolaquage, laques), le métal apporte un style moderne et durable à tout intérieur.',
                        'Quels sont les délais de fabrication ?' => '4 à 6 semaines pour les pièces simples, 8 à 12 semaines pour les ensembles complexes ou les pièces sur-mesure.',
                        'Pouvez-vous travailler à partir d\'un plan ou d\'une photo ?' => 'Oui, nous pouvons interpréter vos croquis, plans, ou même créer à partir d\'images inspiratrices.'
                    )
                ),
                'vehicules' => array(
                    'faq' => array(
                        'Quels types de véhicules pouvez-vous équiper ?' => 'Pick-ups, fourgons, utilitaires, 4x4, et véhicules de loisirs. Nous adaptons nos équipements à chaque modèle.',
                        'Quels matériaux utilisez-vous pour les aménagements ?' => 'Acier pour la robustesse, aluminium pour la légèreté, inox pour les pièces exposées aux intempéries.',
                        'Les aménagements sont-ils démontables ?' => 'Oui, nous concevons des systèmes modulaires et démontables pour pouvoir les retirer ou les transférer.',
                        'Pouvez-vous intégrer des équipements électriques ?' => 'Oui, nous pouvons intégrer des éclairages, prises 12V/220V, systèmes de signalisation, et autres équipements électriques.',
                        'Quelle est la durée de fabrication ?' => '2 à 4 semaines selon la complexité de l\'aménagement et les personnalisations demandées.'
                    )
                ),
                'autres' => array(
                    'faq' => array(
                        'Quels types de projets pouvez-vous réaliser ?' => 'Toutes créations en métal : structures, pièces techniques, éléments décoratifs, et projets personnalisés.',
                        'Travaillez-vous avec des architectes ?' => 'Oui, nous collaborons régulièrement avec des architectes, designers, et particuliers sur des projets sur mesure.',
                        'Pouvez-vous travailler d\'après un simple croquis ?' => 'Oui, nous pouvons interpréter vos idées et vous proposer des solutions techniques et esthétiques.',
                        'Quelle est votre zone d\'intervention ?' => 'Nous intervenons principalement dans le Puy-de-Dôme et les départements limitrophes (Auvergne).',
                        'Comment obtenir un devis pour un projet original ?' => 'Contactez-nous avec vos idées, photos ou plans. Nous étudions votre projet gratuitement et vous proposons un devis détaillé.'
                    )
                )
            );
            
            $current_faq = isset($seo_contents[$term->slug]['faq']) ? $seo_contents[$term->slug]['faq'] : $seo_contents['autres']['faq'];
            
            foreach ($current_faq as $question => $answer) {
                $faq_schema['mainEntity'][] = array(
                    '@type' => 'Question',
                    'name' => $question,
                    'acceptedAnswer' => array(
                        '@type' => 'Answer',
                        'text' => $answer
                    )
                );
            }
            
            echo '<script type="application/ld+json">' . json_encode($faq_schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
        }
    }
}
add_action('wp_head', 'almetal_add_taxonomy_schema_service');

/**
 * ============================================================================
 * AJAX : CHARGEMENT DES RÉALISATIONS DESKTOP AVEC FILTRAGE
 * ============================================================================
 */
function almetal_ajax_load_desktop_realisations() {
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $per_page = isset($_POST['per_page']) ? intval($_POST['per_page']) : 6;
    
    // Debug: Logger la catégorie reçue
    error_log('AJAX Category received: "' . $category . '"');
    
    $args = array(
        'post_type' => 'realisation',
        'posts_per_page' => $per_page,
        'paged' => $page,
        'orderby' => 'date',
        'order' => 'DESC',
    );
    
    // Filtrer par catégorie si spécifiée
    if (!empty($category) && $category !== '*') {
        // Debug: Vérifier si le terme existe
        $term = get_term_by('slug', $category, 'type_realisation');
        error_log('Term exists for slug "' . $category . '": ' . ($term ? 'YES (ID: ' . $term->term_id . ', Name: ' . $term->name . ')' : 'NO'));
        
        // Debug: Lister tous les termes disponibles
        $all_terms = get_terms(array('taxonomy' => 'type_realisation', 'hide_empty' => false));
        $term_slugs = array();
        foreach ($all_terms as $t) {
            $term_slugs[] = $t->slug;
        }
        error_log('All available slugs: ' . implode(', ', $term_slugs));
        
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'type_realisation',
                'field' => 'slug',
                'terms' => $category,
            ),
        );
    }
    
    $query = new WP_Query($args);
    
    // Debug: Logger la requête SQL et les résultats
    error_log('WP_Query args: ' . print_r($args, true));
    error_log('SQL Query: ' . $query->request);
    error_log('Found posts: ' . $query->found_posts);
    
    $html = '';
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            // Utiliser la fonction helper pour générer le HTML de la card
            $card_args = array(
                'show_category_badges' => true,
                'show_location_badge' => true,
                'show_meta' => true,
                'show_cta' => true,
                'is_first' => false,
                'image_size' => 'realisation-card'
            );
            
            $html .= almetal_get_realisation_card_html($card_args);
        }
        wp_reset_postdata();
    } else {
        error_log('No posts found in query');
    }
    
    // Calculer s'il y a plus de pages
    $has_more = ($page * $per_page) < $query->found_posts;
    $remaining = max(0, $query->found_posts - ($page * $per_page));
    
    wp_send_json_success(array(
        'html' => $html,
        'has_more' => $has_more,
        'remaining' => $remaining,
        'current_page' => $page,
    ));
}
add_action('wp_ajax_load_desktop_realisations', 'almetal_ajax_load_desktop_realisations');
add_action('wp_ajax_nopriv_load_desktop_realisations', 'almetal_ajax_load_desktop_realisations');

/**
 * ============================================
 * GOOGLE BUSINESS REVIEWS
 * Récupère et affiche les avis Google Business
 * ============================================
 */

/**
 * Récupère les données Google Business (note moyenne et nombre d'avis)
 * Les données sont mises en cache pendant 24h pour éviter trop d'appels API
 * 
 * @return array|false Données des avis ou false en cas d'erreur
 */
function almetal_get_google_reviews() {
    // Vérifier le cache d'abord
    $cached = get_transient('almetal_google_reviews');
    if ($cached !== false) {
        return $cached;
    }
    
    // Configuration API
    $api_key = 'AIzaSyAWrQ0heLj3xzkTUy_-elelg0I9HtsvzH8';
    // Récupérer le Place ID sauvegardé ou le chercher automatiquement
    $place_id = get_option('almetal_google_place_id', '');
    
    // Si pas de Place ID configuré, essayer de le trouver via l'API
    if (empty($place_id)) {
        $place_id = almetal_find_google_place_id($api_key);
        if ($place_id) {
            update_option('almetal_google_place_id', $place_id);
        }
    }
    
    if (empty($place_id)) {
        // Retourner des valeurs par défaut si pas de Place ID
        return array(
            'rating' => 5.0,
            'total_reviews' => 0,
            'url' => 'https://www.google.com/maps/search/AL+Metallerie+Soudure+Peschadoires',
            'cached' => false,
        );
    }
    
    // Appel API Google Places Details
    $url = add_query_arg(array(
        'place_id' => $place_id,
        'fields' => 'rating,user_ratings_total,url',
        'key' => $api_key,
    ), 'https://maps.googleapis.com/maps/api/place/details/json');
    
    $response = wp_remote_get($url, array('timeout' => 10));
    
    if (is_wp_error($response)) {
        error_log('Google Places API Error: ' . $response->get_error_message());
        return false;
    }
    
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    
    if (empty($data['result'])) {
        // Pas de log ici - ce n'est pas une erreur, juste pas de résultat
        return false;
    }
    
    $result = array(
        'rating' => isset($data['result']['rating']) ? floatval($data['result']['rating']) : 5.0,
        'total_reviews' => isset($data['result']['user_ratings_total']) ? intval($data['result']['user_ratings_total']) : 0,
        'url' => isset($data['result']['url']) ? $data['result']['url'] : 'https://www.google.com/maps/search/AL+Metallerie+Soudure+Peschadoires',
        'cached' => true,
    );
    
    // Mettre en cache pendant 24 heures
    set_transient('almetal_google_reviews', $result, DAY_IN_SECONDS);
    
    return $result;
}

/**
 * Trouve le Place ID de l'entreprise via l'API Google Places
 * 
 * @param string $api_key Clé API Google
 * @return string|false Place ID ou false
 */
function almetal_find_google_place_id($api_key) {
    $search_query = 'AL Métallerie Soudure Peschadoires';
    
    $url = add_query_arg(array(
        'input' => $search_query,
        'inputtype' => 'textquery',
        'fields' => 'place_id',
        'key' => $api_key,
    ), 'https://maps.googleapis.com/maps/api/place/findplacefromtext/json');
    
    $response = wp_remote_get($url, array('timeout' => 10));
    
    if (is_wp_error($response)) {
        return false;
    }
    
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    
    if (!empty($data['candidates'][0]['place_id'])) {
        return $data['candidates'][0]['place_id'];
    }
    
    return false;
}

/**
 * Affiche les étoiles Google Business
 * 
 * @param float $rating Note moyenne (1-5)
 * @return string HTML des étoiles
 */
function almetal_render_google_stars($rating) {
    $full_stars = floor($rating);
    $half_star = ($rating - $full_stars) >= 0.5;
    $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);
    
    $html = '<div class="google-stars">';
    
    // Étoiles pleines
    for ($i = 0; $i < $full_stars; $i++) {
        $html .= '<svg class="star star-full" width="16" height="16" viewBox="0 0 24 24" fill="#FBBC04"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
    }
    
    // Demi-étoile
    if ($half_star) {
        $html .= '<svg class="star star-half" width="16" height="16" viewBox="0 0 24 24"><defs><linearGradient id="half"><stop offset="50%" stop-color="#FBBC04"/><stop offset="50%" stop-color="#4a4a4a"/></linearGradient></defs><path fill="url(#half)" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
    }
    
    // Étoiles vides
    for ($i = 0; $i < $empty_stars; $i++) {
        $html .= '<svg class="star star-empty" width="16" height="16" viewBox="0 0 24 24" fill="#4a4a4a"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
    }
    
    $html .= '</div>';
    
    return $html;
}

/**
 * Affiche le widget complet des avis Google
 * 
 * @return string HTML du widget
 */
function almetal_render_google_reviews_widget() {
    $reviews = almetal_get_google_reviews();
    
    // Fallback avec valeurs par défaut si l'API échoue
    if (!$reviews) {
        $reviews = array(
            'rating' => 5.0,
            'total_reviews' => 5,
            'url' => 'https://www.google.com/search?q=AL+M%C3%A9tallerie+Soudure',
        );
    }
    
    $rating = $reviews['rating'];
    $total = $reviews['total_reviews'];
    $url = $reviews['url'];
    
    $html = '<a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer" class="google-reviews-widget" title="Voir nos avis Google">';
    $html .= '<div class="google-reviews-content">';
    $html .= '<svg class="google-icon" width="18" height="18" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>';
    $html .= almetal_render_google_stars($rating);
    $html .= '<span class="google-rating">' . number_format($rating, 1, ',', '') . '</span>';
    if ($total > 0) {
        $html .= '<span class="google-count">(' . $total . ' avis)</span>';
    }
    $html .= '</div>';
    $html .= '</a>';
    
    return $html;
}

/**
 * ============================================================================
 * GÉOCODAGE AUTOMATIQUE DES VILLES
 * ============================================================================
 */

/**
 * Géocodage automatique des villes avec l'API Nominatim (OpenStreetMap)
 * Récupère les coordonnées GPS d'une ville et les stocke en meta fields
 */
function almetal_geocode_city($city_name, $post_id = null) {
    // Nettoyer le nom de la ville
    $city_name = str_replace(array(
        'Ferronier à ',
        'Ferronnier à ',
        'Serrurier à ',
        'Métallier ',
        'AL Métallerie ',
        'AL Métallerie'
    ), '', $city_name);
    $city_name = trim($city_name);
    
    if (empty($city_name)) {
        return false;
    }
    
    // Vérifier si on a déjà les coordonnées en cache (meta fields)
    if ($post_id) {
        $cached_lat = get_post_meta($post_id, '_city_lat', true);
        $cached_lng = get_post_meta($post_id, '_city_lng', true);
        
        if (!empty($cached_lat) && !empty($cached_lng)) {
            return array(
                'lat' => floatval($cached_lat),
                'lng' => floatval($cached_lng)
            );
        }
    }
    
    // Préparer la requête à l'API Nominatim
    $query = urlencode($city_name . ', France');
    $url = "https://nominatim.openstreetmap.org/search?format=json&q={$query}&limit=1&countrycodes=fr";
    
    // Ajouter un user agent requis par Nominatim
    $args = array(
        'user-agent' => 'AL Metallerie Website (geocoding)',
        'timeout' => 10,
        'headers' => array(
            'Accept' => 'application/json'
        )
    );
    
    // Faire la requête API
    $response = wp_remote_get($url, $args);
    
    if (is_wp_error($response)) {
        error_log('Erreur de géocodage pour ' . $city_name . ': ' . $response->get_error_message());
        return false;
    }
    
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    
    if (empty($data) || !isset($data[0])) {
        error_log('Aucun résultat de géocodage pour ' . $city_name);
        return false;
    }
    
    // Extraire les coordonnées
    $lat = floatval($data[0]['lat']);
    $lng = floatval($data[0]['lon']);
    
    // Stocker les coordonnées en meta fields si on a un post_id
    if ($post_id) {
        update_post_meta($post_id, '_city_lat', $lat);
        update_post_meta($post_id, '_city_lng', $lng);
        update_post_meta($post_id, '_city_geocoded_at', current_time('timestamp'));
    }
    
    return array(
        'lat' => $lat,
        'lng' => $lng
    );
}

/**
 * Hook pour géocoder automatiquement une ville lors de sa sauvegarde
 */
add_action('save_post', 'almetal_auto_geocode_city', 10, 3);

function almetal_auto_geocode_city($post_id, $post, $update) {
    // Vérifier si c'est un type de post "ville"
    $city_post_types = array('city_page', 'city-page', 'villes', 'ville', 'city');
    
    if (!in_array($post->post_type, $city_post_types)) {
        return;
    }
    
    // Éviter les autosaves
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Vérifier les permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Géocoder la ville
    almetal_geocode_city($post->post_title, $post_id);
}

/**
 * Fonction pour géocoder en masse toutes les villes existantes
 * À utiliser une seule fois pour initialiser les coordonnées
 */
function almetal_bulk_geocode_cities() {
    $city_post_types = array('city_page', 'city-page', 'villes', 'ville', 'city');
    
    foreach ($city_post_types as $post_type) {
        if (post_type_exists($post_type)) {
            $cities = get_posts(array(
                'post_type' => $post_type,
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'orderby' => 'title',
                'order' => 'ASC'
            ));
            
            if ($cities && !is_wp_error($cities)) {
                foreach ($cities as $city) {
                    $city_name = get_the_title($city->ID);
                    echo "Géocodage de: {$city_name}... ";
                    
                    $coords = almetal_geocode_city($city_name, $city->ID);
                    
                    if ($coords) {
                        echo "✓ Lat: {$coords['lat']}, Lng: {$coords['lng']}<br>";
                    } else {
                        echo "✗ Erreur<br>";
                    }
                    
                    // Respecter la limite de rate de Nominatim (1 req/sec)
                    sleep(1);
                }
            }
        }
    }
}

?>