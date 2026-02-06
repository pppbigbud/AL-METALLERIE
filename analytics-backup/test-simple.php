<?php
require_once('wp-config.php');
require_once('wp-load.php');

echo "Vérification de l'action AJAX...\n";

global $wp_filter;
if (isset($wp_filter['wp_ajax_almetal_get_seo_improvements_with_comments'])) {
    echo "✅ L'action AJAX est enregistrée\n";
} else {
    echo "❌ L'action AJAX n'est PAS enregistrée\n";
}

echo "\nListe des actions AJAX almetal :\n";
foreach ($wp_filter as $hook => $actions) {
    if (strpos($hook, 'wp_ajax_almetal_') === 0) {
        echo "- $hook\n";
    }
}
?>
