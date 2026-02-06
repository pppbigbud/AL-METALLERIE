<?php
/**
 * Correction des erreurs Google Search Console
 * À ajouter dans le fichier functions.php de votre thème
 */

// =============================================
// 1. DÉSACTIVER L'INDEXATION DES ATTACHMENTS
// =============================================

// Ajouter noindex pour les pages d'attachment
add_action('wp_head', function() {
    if (is_attachment()) {
        echo '<meta name="robots" content="noindex, nofollow" />' . "\n";
    }
}, 1);

// Rediriger les attachments vers la page parente ou la home
add_action('template_redirect', function() {
    if (is_attachment()) {
        global $post;
        if (!empty($post->post_parent)) {
            wp_redirect(get_permalink($post->post_parent), 301);
        } else {
            wp_redirect(home_url(), 301);
        }
        exit;
    }
});

// Exclure les attachments de l'indexation Yoast
add_filter('wpseo_attachment_indexable', '__return_false');
add_filter('wpseo_sitemap_exclude_attachment', '__return_true');

// =============================================
// 2. GÉRER LES PAGES AMP
// =============================================

// Si vous n'utilisez pas AMP, rediriger les URLs /amp/
add_action('template_redirect', function() {
    // Vérifier si l'URL se termine par /amp/
    if (preg_match('/\/amp\/?$/', $_SERVER['REQUEST_URI'])) {
        // Rediriger vers la page normale
        $url = preg_replace('/\/amp\/?$/', '/', $_SERVER['REQUEST_URI']);
        wp_redirect(home_url($url), 301);
        exit;
    }
});

// Supprimer les liens AMP du head
remove_action('wp_head', 'amp_add_amphtml_link');

// =============================================
// 3. CORRECTIONS SUPPLÉMENTAIRES
// =============================================

// Nettoyer les URLs canoniques
add_filter('wpseo_canonical', function($canonical) {
    // Supprimer /amp/ des URLs canoniques
    $canonical = str_replace('/amp/', '/', $canonical);
    // Supprimer les parameters de pagination inutiles
    $canonical = remove_query_arg('amp', $canonical);
    return $canonical;
});

// Améliorer les balises robots
add_filter('wpseo_robots', function($robots) {
    if (is_attachment() || (function_exists('is_amp_endpoint') && is_amp_endpoint())) {
        return 'noindex, nofollow';
    }
    return $robots;
});

// =============================================
// 4. VALIDATION DANS SEARCH CONSOLE
// =============================================

// Après avoir ajouté ce code :
// 1. Allez dans Google Search Console
// 2. Dans "Couverture", cliquez sur les erreurs
// 3. Valider la correction pour les URLs avec /attachment/ et /amp/
// 4. Attendez la prochaine exploration de Google

?>
