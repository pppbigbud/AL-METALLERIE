<?php
// Test direct de l'AJAX
$_POST['action'] = 'almetal_get_seo_improvements_with_comments';
$_POST['post_id'] = 2;
$_POST['is_taxonomy'] = 'false';
$_POST['nonce'] = wp_create_nonce('almetal_seo_improvements');

// Simuler l'appel AJAX
define('DOING_AJAX', true);

// Inclure WordPress
require_once('./wp-config.php');

// Appeler la fonction
try {
    $improver = new Almetal_Seo_Improver_V2();
    $improver->ajax_get_improvements_with_comments();
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
?>
