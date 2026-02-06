<?php
/**
 * Désactiver l'indexation des pages de pièces jointes (attachments)
 */

// Ajouter la balise noindex pour les attachments
add_action('wp_head', function() {
    if (is_attachment()) {
        echo '<meta name="robots" content="noindex, nofollow" />' . "\n";
    }
}, 1);

// Rediriger les attachments vers la page parente
add_action('template_redirect', function() {
    if (is_attachment() && !empty($post->post_parent)) {
        wp_redirect(get_permalink($post->post_parent), 301);
        exit;
    }
});

// Empêcher la création de URLs d'attachment dans Yoast
add_filter('wpseo_attachment_indexable', '__return_false');
add_filter('wpseo_opengraph_image_attachment', '__return_false');

// Filtrer les attachments des sitemaps
add_filter('wpseo_sitemap_exclude_attachment', '__return_true');

// Ne pas inclure les attachments dans la recherche
add_filter('pre_get_posts', function($query) {
    if ($query->is_search && !is_admin()) {
        $query->set('post_type', array('post', 'page', 'realisation', 'city_page'));
    }
    return $query;
});
