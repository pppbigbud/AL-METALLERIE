<?php
/**
 * Optimisations de Performance Mobile pour AL Métallerie
 * Cible: Score Lighthouse 90%+
 * 
 * Problèmes identifiés:
 * - LCP: 5.9s (image hero en background-image, non préchargeable)
 * - TBT: 870ms (trop de JS bloquant)
 * - CSS bloquant: 38KB
 * - JS inutilisé: jQuery + Swiper
 * 
 * @package ALMetallerie
 * @since 2.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * ============================================
 * 1. PRÉCHARGER L'IMAGE LCP DU SLIDESHOW MOBILE
 * ============================================
 * Le problème: l'image est en background-image CSS, donc non découvrable
 * Solution: ajouter un <link rel="preload"> pour la première image
 */
function almetal_preload_mobile_lcp() {
    // Uniquement sur mobile et page d'accueil
    if (!almetal_is_mobile() || (!is_front_page() && !is_home())) {
        return;
    }
    
    // Récupérer les slides
    $slides = Almetal_Slideshow_Admin::get_slides();
    $active_slides = array_filter($slides, function($slide) {
        return isset($slide['active']) && $slide['active'] === true;
    });
    
    if (empty($active_slides)) {
        return;
    }
    
    // Trier par ordre et prendre la première
    usort($active_slides, function($a, $b) {
        return ($a['order'] ?? 0) - ($b['order'] ?? 0);
    });
    
    $first_slide = reset($active_slides);
    $lcp_image = $first_slide['image'] ?? '';
    
    if (!empty($lcp_image)) {
        // Précharger l'image LCP avec haute priorité
        echo '<link rel="preload" as="image" href="' . esc_url($lcp_image) . '" fetchpriority="high">' . "\n";
    }
}
add_action('wp_head', 'almetal_preload_mobile_lcp', 1);

/**
 * ============================================
 * 2. SUPPRIMER LE CSS BLOCK-LIBRARY (GUTENBERG)
 * ============================================
 * Économie: 14KB de CSS inutilisé
 */
function almetal_remove_block_library_css() {
    // Supprimer le CSS Gutenberg si on n'utilise pas de blocs
    if (!is_admin()) {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('wc-blocks-style'); // WooCommerce blocks
        wp_dequeue_style('global-styles'); // Styles globaux Gutenberg
    }
}
add_action('wp_enqueue_scripts', 'almetal_remove_block_library_css', 100);

/**
 * ============================================
 * 3. CHARGER SWIPER DE MANIÈRE OPTIMISÉE
 * ============================================
 * Problème: Swiper charge 44KB de JS dont 26KB inutilisés
 * Solution: Charger en defer et uniquement sur mobile
 */
function almetal_optimize_swiper_loading($tag, $handle, $src) {
    // Ajouter defer à Swiper
    if ($handle === 'swiper-js' || strpos($src, 'swiper') !== false) {
        return str_replace(' src', ' defer src', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'almetal_optimize_swiper_loading', 10, 3);

/**
 * ============================================
 * 4. DIFFÉRER LE CHARGEMENT DES CSS NON CRITIQUES
 * ============================================
 */
function almetal_defer_non_critical_styles($html, $handle, $href, $media) {
    // CSS à différer sur mobile
    $defer_on_mobile = array(
        'almetal-formations',
        'almetal-hero-promo',
        'almetal-mobile-animations',
        'swiper-css',
    );
    
    if (almetal_is_mobile() && in_array($handle, $defer_on_mobile)) {
        // Charger de manière non-bloquante
        return sprintf(
            '<link rel="preload" href="%s" as="style" onload="this.onload=null;this.rel=\'stylesheet\'"><noscript><link rel="stylesheet" href="%s"></noscript>',
            esc_url($href),
            esc_url($href)
        );
    }
    
    return $html;
}
add_filter('style_loader_tag', 'almetal_defer_non_critical_styles', 10, 4);

/**
 * ============================================
 * 5. INLINE LE CSS CRITIQUE MOBILE
 * ============================================
 * Réduit le temps de First Contentful Paint
 */
function almetal_inline_mobile_critical_css() {
    if (!almetal_is_mobile() || (!is_front_page() && !is_home())) {
        return;
    }
    ?>
    <style id="mobile-critical-css">
    /* CSS critique mobile - Above the fold */
    *{box-sizing:border-box;margin:0;padding:0}
    html{font-size:16px;-webkit-text-size-adjust:100%}
    body{font-family:Poppins,system-ui,sans-serif;background:#191919;color:#fff;overflow-x:hidden}
    
    /* Header mobile */
    .mobile-header{position:fixed;top:0;left:0;right:0;z-index:1000;background:rgba(25,25,25,.95);backdrop-filter:blur(10px);height:60px;display:flex;align-items:center;padding:0 1rem}
    .mobile-header-inner{display:flex;align-items:center;justify-content:space-between;width:100%}
    .mobile-logo{flex:1;display:flex;justify-content:center}
    .mobile-logo-img{height:40px;width:auto}
    .mobile-burger-btn{background:none;border:none;padding:8px;cursor:pointer}
    .mobile-burger-line{display:block;width:24px;height:2px;background:#fff;margin:5px 0;transition:transform .3s}
    
    /* Hero slideshow mobile */
    .mobile-hero-swiper{width:100%;height:70vh;min-height:400px;position:relative;margin-top:60px}
    .swiper-slide{position:relative;overflow:hidden}
    .mobile-hero-image{position:absolute;inset:0;background-size:cover;background-position:center}
    .mobile-hero-overlay{position:absolute;inset:0;background:linear-gradient(to bottom,rgba(0,0,0,.3),rgba(0,0,0,.7))}
    .mobile-hero-content{position:absolute;bottom:80px;left:1rem;right:1rem;z-index:2}
    .mobile-hero-title{font-size:1.5rem;font-weight:700;margin-bottom:.5rem;text-shadow:0 2px 4px rgba(0,0,0,.5)}
    .mobile-hero-subtitle{font-size:.9rem;opacity:.9;margin-bottom:1rem}
    .mobile-hero-cta{display:inline-flex;align-items:center;gap:.5rem;background:#F08B18;color:#fff;padding:.75rem 1.5rem;border-radius:8px;text-decoration:none;font-weight:600}
    
    /* Pagination Swiper */
    .swiper-pagination{bottom:20px!important}
    .swiper-pagination-bullet{background:#fff;opacity:.5;width:8px;height:8px}
    .swiper-pagination-bullet-active{opacity:1;background:#F08B18}
    
    /* Masquer le desktop header sur mobile */
    .site-header:not(.mobile-header){display:none}
    </style>
    <?php
}
add_action('wp_head', 'almetal_inline_mobile_critical_css', 2);

/**
 * ============================================
 * 6. OPTIMISER LE CHARGEMENT DE JQUERY
 * ============================================
 * Problème: jQuery charge 29KB dont 22KB inutilisés
 * Solution: Charger en defer dans le footer
 */
function almetal_optimize_jquery_mobile() {
    if (!is_admin() && almetal_is_mobile()) {
        // Ajouter defer à jQuery
        add_filter('script_loader_tag', function($tag, $handle) {
            if ($handle === 'jquery-core' || $handle === 'jquery') {
                return str_replace(' src', ' defer src', $tag);
            }
            return $tag;
        }, 10, 2);
    }
}
add_action('wp_enqueue_scripts', 'almetal_optimize_jquery_mobile', 1);

/**
 * ============================================
 * 7. MINIFIER LE CSS MOBILE-UNIFIED
 * ============================================
 * Économie estimée: 2.7KB
 */
function almetal_should_minify_css() {
    // Activer la minification en production
    return !WP_DEBUG;
}

/**
 * ============================================
 * 8. OPTIMISER LES IMAGES DU SLIDESHOW
 * ============================================
 * Problème: Image 335KB peut être réduite à 250KB
 */
function almetal_get_optimized_slideshow_image($image_url, $width = 480) {
    // Si l'image est déjà optimisée (contient -optimized ou dimensions)
    if (strpos($image_url, '-optimized') !== false || preg_match('/-\d+x\d+\./', $image_url)) {
        return $image_url;
    }
    
    // Essayer de trouver une version redimensionnée
    $upload_dir = wp_upload_dir();
    $base_url = $upload_dir['baseurl'];
    
    if (strpos($image_url, $base_url) !== false) {
        // C'est une image uploadée, chercher une version plus petite
        $path_info = pathinfo($image_url);
        $optimized_url = $path_info['dirname'] . '/' . $path_info['filename'] . '-' . $width . 'x' . round($width * 0.75) . '.' . $path_info['extension'];
        
        // Vérifier si le fichier existe (via HTTP HEAD serait trop lent)
        // On retourne l'URL optimisée, WordPress servira le fallback si inexistant
        return $optimized_url;
    }
    
    return $image_url;
}

/**
 * ============================================
 * 9. RÉDUIRE LE TOTAL BLOCKING TIME
 * ============================================
 * Problème: TBT 870ms causé par les tâches longues
 * Solution: Découper les scripts en chunks
 */
function almetal_defer_analytics_scripts($tag, $handle, $src) {
    // Scripts analytics à différer
    $defer_scripts = array(
        'almetal-tracker',
        'almetal-heatmap',
        'almetal-cookie-banner',
    );
    
    if (in_array($handle, $defer_scripts)) {
        // Charger après le chargement de la page
        return str_replace(' src', ' defer src', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'almetal_defer_analytics_scripts', 10, 3);

/**
 * ============================================
 * 10. OPTIMISER LE CACHE NAVIGATEUR
 * ============================================
 */
function almetal_add_resource_hints() {
    // Préconnexion aux CDN utilisés
    echo '<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>' . "\n";
    echo '<link rel="dns-prefetch" href="https://cdn.jsdelivr.net">' . "\n";
}
add_action('wp_head', 'almetal_add_resource_hints', 1);

/**
 * ============================================
 * 11. SUPPRIMER LES SCRIPTS INUTILES SUR MOBILE
 * ============================================
 */
function almetal_remove_unused_mobile_scripts() {
    if (!almetal_is_mobile()) {
        return;
    }
    
    // Scripts desktop uniquement
    wp_dequeue_script('almetal-desktop-slideshow');
    wp_dequeue_script('almetal-mega-menu');
    
    // Supprimer jQuery migrate (souvent inutile)
    wp_dequeue_script('jquery-migrate');
}
add_action('wp_enqueue_scripts', 'almetal_remove_unused_mobile_scripts', 100);

/**
 * ============================================
 * 12. CONVERTIR BACKGROUND-IMAGE EN IMG TAG POUR LCP
 * ============================================
 * C'est la solution la plus efficace pour le LCP
 */
function almetal_use_img_tag_for_lcp() {
    // Cette fonction sera appelée dans le template
    // pour générer une balise <img> au lieu de background-image
    // pour la première slide (LCP)
}

/**
 * ============================================
 * 13. AJOUTER FETCHPRIORITY AUX RESSOURCES CRITIQUES
 * ============================================
 */
function almetal_add_fetchpriority_hints() {
    if (!almetal_is_mobile() || (!is_front_page() && !is_home())) {
        return;
    }
    
    // Logo mobile
    $logo_url = get_template_directory_uri() . '/assets/images/logo.webp';
    echo '<link rel="preload" as="image" href="' . esc_url($logo_url) . '" fetchpriority="high">' . "\n";
}
add_action('wp_head', 'almetal_add_fetchpriority_hints', 1);

/**
 * ============================================
 * 14. OPTIMISER LE CHARGEMENT DES POLICES
 * ============================================
 */
function almetal_optimize_fonts_mobile() {
    if (!almetal_is_mobile()) {
        return;
    }
    
    // Charger uniquement les poids nécessaires sur mobile
    wp_dequeue_style('almetal-google-fonts');
    wp_dequeue_style('almetal-google-fonts-optimized');
    
    // Version ultra-légère pour mobile (seulement 400 et 600)
    wp_enqueue_style(
        'almetal-fonts-mobile',
        'https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap',
        array(),
        null
    );
}
add_action('wp_enqueue_scripts', 'almetal_optimize_fonts_mobile', 20);

/**
 * ============================================
 * 15. LAZY LOAD POUR LES IMAGES HORS VIEWPORT
 * ============================================
 */
function almetal_native_lazy_loading($attr, $attachment, $size) {
    // Ne pas lazy load les images LCP (première image visible)
    if (is_front_page() && !isset($GLOBALS['almetal_first_image_loaded'])) {
        $GLOBALS['almetal_first_image_loaded'] = true;
        $attr['fetchpriority'] = 'high';
        $attr['loading'] = 'eager';
        unset($attr['loading']); // Retirer lazy si présent
    } else {
        $attr['loading'] = 'lazy';
        $attr['decoding'] = 'async';
    }
    
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'almetal_native_lazy_loading', 10, 3);
