<?php
/**
 * Désactiver complètement AMP si non utilisé
 */

// Désactiver AMP
add_action('wp', function() {
    // Si AMP n'est pas nécessaire, rediriger vers la page normale
    if (function_exists('is_amp_endpoint') && is_amp_endpoint()) {
        $current_url = remove_query_arg('amp', $_SERVER['REQUEST_URI']);
        wp_redirect(home_url($current_url), 301);
        exit;
    }
});

// Empêcher WordPress de créer des URLs AMP
add_filter('amp_pre_render_url', '__return_false');

// Supprimer les balises AMP du head
remove_action('wp_head', 'amp_add_amphtml_link');

// Si vous utilisez le plugin AMP, le désactiver complètement
add_action('plugins_loaded', function() {
    if (class_exists('AMP_Plugin')) {
        // Désactiver le plugin AMP si présent
        if (!is_admin()) {
            add_filter('option_active_plugins', function($plugins) {
                $key = array_search('amp/amp.php', $plugins);
                if ($key !== false) {
                    unset($plugins[$key]);
                }
                return $plugins;
            });
        }
    }
}, 0);
