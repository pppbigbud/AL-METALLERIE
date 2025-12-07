<?php
/**
 * Optimisations de Performance pour AL Métallerie
 * Résout les problèmes Lighthouse
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Ajouter les headers de cache pour les ressources statiques
 */
function almetal_add_cache_headers() {
    // Ne pas modifier les headers en admin
    if (is_admin()) {
        return;
    }
    
    // Ajouter les headers de cache via PHP (fallback si .htaccess ne fonctionne pas)
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
}
add_action('send_headers', 'almetal_add_cache_headers');

/**
 * Préconnexion aux ressources externes (Google Fonts)
 */
function almetal_preconnect_resources() {
    ?>
    <!-- Préconnexion pour améliorer les performances -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <?php
}
add_action('wp_head', 'almetal_preconnect_resources', 1);

/**
 * Précharger l'image LCP (Largest Contentful Paint)
 */
function almetal_preload_lcp_image() {
    // Page d'accueil - précharger l'image LCP du slideshow
    if (is_front_page() || is_home()) {
        // Précharger la première image du slideshow (LCP)
        $args = array(
            'post_type' => 'realisation',
            'posts_per_page' => 1,
            'orderby' => 'date',
            'order' => 'DESC',
        );
        $query = new WP_Query($args);
        if ($query->have_posts()) {
            $query->the_post();
            $image_id = get_post_thumbnail_id();
            if ($image_id) {
                // Obtenir l'URL de l'image en taille medium_large (optimale pour LCP)
                $image_src = wp_get_attachment_image_src($image_id, 'medium_large');
                if ($image_src) {
                    echo '<link rel="preload" as="image" href="' . esc_url($image_src[0]) . '" fetchpriority="high" type="image/webp">' . "\n";
                }
            }
            wp_reset_postdata();
        }
        
        // Précharger aussi le logo
        $logo_url = get_template_directory_uri() . '/assets/images/logo.webp';
        echo '<link rel="preload" as="image" href="' . esc_url($logo_url) . '">' . "\n";
    }
}
add_action('wp_head', 'almetal_preload_lcp_image', 2);

/**
 * Charger les CSS non critiques de manière asynchrone
 */
function almetal_defer_non_critical_css($html, $handle, $href, $media) {
    // CSS à différer (non critiques)
    $defer_handles = array(
        'almetal-cookie-banner',
        'almetal-footer-mountains',
        'almetal-realisations',
        'almetal-archive-pages',
    );
    
    if (in_array($handle, $defer_handles)) {
        // Charger de manière asynchrone
        $html = '<link rel="preload" href="' . esc_url($href) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
        $html .= '<noscript><link rel="stylesheet" href="' . esc_url($href) . '"></noscript>';
    }
    
    return $html;
}
add_filter('style_loader_tag', 'almetal_defer_non_critical_css', 10, 4);

/**
 * Ajouter defer aux scripts non critiques
 */
function almetal_defer_scripts($tag, $handle, $src) {
    // Scripts à différer
    $defer_handles = array(
        'almetal-actualites-filter',
        'jquery-migrate',
    );
    
    if (in_array($handle, $defer_handles)) {
        return str_replace(' src', ' defer src', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'almetal_defer_scripts', 10, 3);

/**
 * Supprimer les scripts/styles inutiles sur certaines pages
 */
function almetal_conditional_assets() {
    // Ne pas charger le CSS des réalisations sur la page d'accueil
    if (is_front_page() && !is_page('realisations')) {
        // wp_dequeue_style('almetal-realisations');
    }
    
    // Ne pas charger le JS du formulaire de contact sauf sur la page contact
    if (!is_page('contact')) {
        wp_dequeue_script('almetal-contact');
        wp_dequeue_style('almetal-contact');
    }
}
add_action('wp_enqueue_scripts', 'almetal_conditional_assets', 100);

/**
 * Optimiser le chargement de jQuery
 */
function almetal_optimize_jquery() {
    if (!is_admin()) {
        // Déplacer jQuery dans le footer
        wp_scripts()->add_data('jquery', 'group', 1);
        wp_scripts()->add_data('jquery-core', 'group', 1);
        wp_scripts()->add_data('jquery-migrate', 'group', 1);
    }
}
add_action('wp_enqueue_scripts', 'almetal_optimize_jquery', 1);

/**
 * Ajouter fetchpriority="high" à l'image LCP
 */
function almetal_add_fetchpriority_to_lcp($attr, $attachment, $size) {
    // Si c'est l'image à la une sur la page d'accueil
    if (is_front_page() || is_home()) {
        $attr['fetchpriority'] = 'high';
        $attr['decoding'] = 'async';
    }
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'almetal_add_fetchpriority_to_lcp', 10, 3);

// Note: almetal_disable_emojis() est déjà définie dans functions.php

/**
 * Supprimer les liens inutiles du head
 */
function almetal_cleanup_head() {
    // Supprimer le lien RSD
    remove_action('wp_head', 'rsd_link');
    // Supprimer le lien Windows Live Writer
    remove_action('wp_head', 'wlwmanifest_link');
    // Supprimer le générateur WordPress
    remove_action('wp_head', 'wp_generator');
    // Supprimer les liens shortlink
    remove_action('wp_head', 'wp_shortlink_wp_head');
    // Supprimer les liens REST API
    remove_action('wp_head', 'rest_output_link_wp_head');
    // Supprimer oEmbed
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
}
add_action('init', 'almetal_cleanup_head');

/**
 * Ajouter loading="lazy" aux images hors viewport
 */
function almetal_lazy_load_images($content) {
    if (is_admin()) {
        return $content;
    }
    
    // Ajouter loading="lazy" aux images qui n'ont pas déjà cet attribut
    $content = preg_replace(
        '/<img((?!loading=)[^>]*)>/i',
        '<img$1 loading="lazy">',
        $content
    );
    
    return $content;
}
add_filter('the_content', 'almetal_lazy_load_images', 99);

/**
 * Optimiser les Google Fonts - charger uniquement les poids nécessaires
 */
function almetal_optimize_google_fonts() {
    // Supprimer l'ancien enqueue de Google Fonts
    wp_dequeue_style('almetal-google-fonts');
    
    // Ajouter une version optimisée avec display=swap
    wp_enqueue_style(
        'almetal-google-fonts-optimized',
        'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap',
        array(),
        null
    );
}
add_action('wp_enqueue_scripts', 'almetal_optimize_google_fonts', 5);

/**
 * Ajouter les attributs de performance aux Google Fonts
 */
function almetal_font_loader_tag($html, $handle) {
    if ($handle === 'almetal-google-fonts-optimized') {
        // Ajouter media="print" et onload pour charger de manière non-bloquante
        $html = str_replace(
            "rel='stylesheet'",
            "rel='stylesheet' media='print' onload=\"this.media='all'\"",
            $html
        );
        // Ajouter un fallback noscript
        $html .= '<noscript>' . str_replace(" media='print' onload=\"this.media='all'\"", "", $html) . '</noscript>';
    }
    return $html;
}
add_filter('style_loader_tag', 'almetal_font_loader_tag', 10, 2);

/**
 * Générer des images responsives avec srcset optimisé
 * Améliore le LCP en servant la bonne taille d'image
 * Basé sur les dimensions réelles affichées (rapport PageSpeed)
 */
function almetal_responsive_image_sizes($sizes, $size, $image_src, $image_meta, $attachment_id) {
    // Tailles optimisées pour les différents breakpoints
    // Mobile: 400px, Tablet: 600px, Desktop: 640px max
    $sizes = '(max-width: 480px) 400px, (max-width: 768px) 500px, (max-width: 1024px) 640px, 640px';
    return $sizes;
}
add_filter('wp_calculate_image_sizes', 'almetal_responsive_image_sizes', 10, 5);

/**
 * Forcer l'utilisation des tailles d'images optimisées pour les réalisations
 */
function almetal_optimize_realisation_thumbnails($html, $post_id, $post_thumbnail_id, $size, $attr) {
    // Si c'est une archive ou la page d'accueil, utiliser la taille optimisée
    if (is_archive() || is_front_page() || is_home()) {
        // Récupérer l'image dans la taille optimisée
        $optimized_html = wp_get_attachment_image($post_thumbnail_id, 'realisation-card', false, $attr);
        if ($optimized_html) {
            return $optimized_html;
        }
    }
    return $html;
}
add_filter('post_thumbnail_html', 'almetal_optimize_realisation_thumbnails', 10, 5);

/**
 * Augmenter la compression des images WebP/JPEG
 * Réduit la taille de téléchargement (recommandation PageSpeed)
 */
function almetal_image_quality($quality, $mime_type = '') {
    // Qualité optimale pour le web (balance taille/qualité)
    // 70-75 est recommandé pour WebP, 75-80 pour JPEG
    if ($mime_type === 'image/webp') {
        return 70;
    }
    return 75;
}
add_filter('wp_editor_set_quality', 'almetal_image_quality', 10, 2);
add_filter('jpeg_quality', function() { return 75; });
add_filter('wp_image_quality', function() { return 75; });

/**
 * Ajouter fetchpriority="high" aux images du slideshow (LCP)
 */
function almetal_optimize_slideshow_images($content) {
    if (is_admin()) {
        return $content;
    }
    
    // Ajouter fetchpriority="high" à la première image du slideshow
    $count = 0;
    $content = preg_replace_callback(
        '/<img([^>]*class="[^"]*slideshow[^"]*"[^>]*)>/i',
        function($matches) use (&$count) {
            $count++;
            if ($count === 1) {
                // Première image = LCP, ajouter fetchpriority
                $img = $matches[0];
                if (strpos($img, 'fetchpriority') === false) {
                    $img = str_replace('<img', '<img fetchpriority="high"', $img);
                }
                // Retirer loading="lazy" de l'image LCP
                $img = preg_replace('/loading=["\']lazy["\']/', '', $img);
                return $img;
            }
            return $matches[0];
        },
        $content
    );
    
    return $content;
}
add_filter('the_content', 'almetal_optimize_slideshow_images', 5);

/**
 * Optimiser la qualité de compression WebP
 */
function almetal_webp_quality($quality) {
    return 75; // Qualité optimale pour le web (balance taille/qualité)
}
add_filter('wp_editor_set_quality', 'almetal_webp_quality');

/**
 * Ajouter les tailles d'images personnalisées optimisées
 * Basé sur les dimensions réelles affichées (rapport PageSpeed)
 */
function almetal_add_image_sizes() {
    // Taille pour le slideshow desktop (dimensions affichées: ~633x293)
    add_image_size('slideshow-desktop', 640, 300, true);
    // Taille pour le slideshow mobile
    add_image_size('slideshow-mobile', 400, 300, true);
    // Taille pour les cartes de réalisation (dimensions affichées: ~390x293)
    add_image_size('realisation-card', 400, 300, true);
    // Taille pour les cartes portrait (dimensions affichées: ~390x520)
    add_image_size('realisation-card-portrait', 400, 530, true);
    // Taille pour les miniatures
    add_image_size('realisation-thumb', 300, 200, true);
    // Taille pour la galerie (dimensions affichées: ~400x498)
    add_image_size('gallery-image', 400, 500, true);
    // Taille pour le logo (dimensions affichées: 81x80)
    add_image_size('logo-small', 100, 100, false);
}
add_action('after_setup_theme', 'almetal_add_image_sizes');

/**
 * Différer jQuery et le charger dans le footer
 */
function almetal_move_jquery_to_footer() {
    if (!is_admin()) {
        wp_deregister_script('jquery');
        wp_register_script('jquery', includes_url('/js/jquery/jquery.min.js'), array(), null, true);
        wp_enqueue_script('jquery');
    }
}
add_action('wp_enqueue_scripts', 'almetal_move_jquery_to_footer', 1);

/**
 * Précharger les polices critiques
 */
function almetal_preload_fonts() {
    ?>
    <!-- Préchargement des polices critiques -->
    <link rel="preload" href="https://fonts.gstatic.com/s/poppins/v24/pxiByp8kv8JHgFVrLCz7Z1xlFQ.woff2" as="font" type="font/woff2" crossorigin>
    <?php
}
add_action('wp_head', 'almetal_preload_fonts', 1);

/**
 * Inline le CSS critique pour le First Contentful Paint
 */
function almetal_inline_critical_css() {
    if (is_front_page() || is_home()) {
        ?>
        <style id="critical-css">
        /* CSS critique pour le rendu initial */
        *{box-sizing:border-box}body{margin:0;font-family:Poppins,sans-serif;background:#191919;color:#fff}
        .site-header{position:fixed;top:0;left:0;right:0;z-index:1000;background:#191919}
        .header-mega{display:flex;justify-content:center;align-items:center;padding:1rem 2rem}
        .header-mega__list{display:flex;list-style:none;margin:0;padding:0;gap:2rem}
        .header-mega__item a{color:#fff;text-decoration:none;display:flex;align-items:center;gap:0.5rem}
        .site-logo img{max-height:140px;width:auto}
        </style>
        <?php
    }
}
add_action('wp_head', 'almetal_inline_critical_css', 3);
