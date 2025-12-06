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
    // Page d'accueil - précharger le logo
    if (is_front_page() || is_home()) {
        $logo_url = get_template_directory_uri() . '/assets/images/logo.webp';
        echo '<link rel="preload" as="image" href="' . esc_url($logo_url) . '" fetchpriority="high">' . "\n";
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
    );
    
    if (in_array($handle, $defer_handles)) {
        // Charger de manière asynchrone
        $html = '<link rel="preload" href="' . $href . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
        $html .= '<noscript><link rel="stylesheet" href="' . $href . '"></noscript>';
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
        'almetal-cookie-consent',
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
