<?php
// Script de debug pour mobile
header('Content-Type: text/plain; charset=utf-8');

echo "=== DEBUG MOBILE ===\n";
echo "User-Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n\n";

// Charger WordPress
require_once('wp-config.php');
require_once('wp-load.php');

echo "Détection WordPress:\n";
echo "wp_is_mobile(): " . (wp_is_mobile() ? 'true' : 'false') . "\n";

if (function_exists('almetal_is_mobile')) {
    echo "almetal_is_mobile(): " . (almetal_is_mobile() ? 'true' : 'false') . "\n";
} else {
    echo "almetal_is_mobile(): function not found\n";
}

echo "\nPage actuelle:\n";
echo "is_singular(realisation): " . (is_singular('realisation') ? 'true' : 'false') . "\n";

echo "\nScripts chargés:\n";
global $wp_scripts;
if (isset($wp_scripts->registered['mobile-realisation-map'])) {
    echo "mobile-realisation-map.js: ✓ ENQUEUED\n";
} else {
    echo "mobile-realisation-map.js: ✗ NOT ENQUEUED\n";
}

echo "\nCSS chargés:\n";
global $wp_styles;
if (isset($wp_styles->registered['almetal-mobile-unified'])) {
    echo "mobile-unified.css: ✓ ENQUEUED\n";
} else {
    echo "mobile-unified.css: ✗ NOT ENQUEUED\n";
}

echo "\nCache headers:\n";
if (headers_sent()) {
    echo "Headers already sent\n";
} else {
    echo "Headers not sent yet\n";
}
?>
