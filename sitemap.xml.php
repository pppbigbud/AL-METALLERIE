<?php
/**
 * Sitemap XML dynamique
 * Ce fichier génère le sitemap en chargeant WordPress
 */

// Charger WordPress
if (file_exists(dirname(__FILE__) . '/wp-load.php')) {
    require_once dirname(__FILE__) . '/wp-load.php';
} elseif (file_exists(dirname(__FILE__) . '/wordpress/wp-load.php')) {
    require_once dirname(__FILE__) . '/wordpress/wp-load.php';
} else {
    header('HTTP/1.1 500 Internal Server Error');
    die('WordPress not found');
}

// Headers XML
header('Content-Type: application/xml; charset=utf-8');
header('X-Robots-Tag: noindex, follow');
header('Cache-Control: max-age=3600');

// Générer et afficher le sitemap
if (function_exists('almetal_generate_sitemap')) {
    echo almetal_generate_sitemap();
} else {
    // Fallback si la fonction n'existe pas
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    echo '<url><loc>' . home_url('/') . '</loc></url>';
    echo '</urlset>';
}
