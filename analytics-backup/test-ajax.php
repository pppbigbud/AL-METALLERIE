<?php
/**
 * Script de test pour l'AJAX
 */

// Simuler WordPress
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);

echo "Test de l'action AJAX...\n";

// Vérifier si l'action est enregistrée
if (has_action('wp_ajax_almetal_get_seo_improvements_with_comments')) {
    echo "✅ L'action AJAX est enregistrée\n";
} else {
    echo "❌ L'action AJAX n'est PAS enregistrée\n";
}

// Afficher toutes les actions AJAX enregistrées
global $wp_filter;
echo "\nActions AJAX disponibles :\n";
foreach ($wp_filter as $hook => $actions) {
    if (strpos($hook, 'wp_ajax_') === 0) {
        echo "- $hook\n";
    }
}

// Test de nonce
$nonce = wp_create_nonce('almetal_seo_improvements');
echo "\nNonce créé : $nonce\n";

// Vérification du nonce
$result = wp_verify_nonce($nonce, 'almetal_seo_improvements');
echo "Vérification du nonce : " . ($result ? "✅ Valide" : "❌ Invalide") . "\n";
?>
