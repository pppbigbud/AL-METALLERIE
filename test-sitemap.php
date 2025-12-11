<?php
/**
 * Test du sitemap - À supprimer après test
 * Accédez à : https://www.al-metallerie.fr/test-sitemap.php
 */

// Charger WordPress - essayer plusieurs chemins possibles
if (file_exists(dirname(__FILE__) . '/wp-load.php')) {
    require_once dirname(__FILE__) . '/wp-load.php';
} elseif (file_exists(dirname(__FILE__) . '/wordpress/wp-load.php')) {
    require_once dirname(__FILE__) . '/wordpress/wp-load.php';
} else {
    die('Impossible de trouver wp-load.php');
}

// Activer l'affichage des erreurs pour le debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test Sitemap</h1>";

// Test 1: Vérifier si WordPress est chargé
echo "<h2>1. WordPress chargé</h2>";
echo "<p>Home URL: " . home_url() . "</p>";

// Test 2: Vérifier les pages villes
echo "<h2>2. Pages Villes (city_page)</h2>";
$city_pages = get_posts(array(
    'post_type' => 'city_page',
    'posts_per_page' => -1,
    'post_status' => 'publish',
));
echo "<p>Nombre de pages villes publiées: " . count($city_pages) . "</p>";

if (!empty($city_pages)) {
    echo "<ul>";
    foreach (array_slice($city_pages, 0, 5) as $page) {
        echo "<li>" . get_the_title($page) . " - " . get_permalink($page) . "</li>";
    }
    if (count($city_pages) > 5) {
        echo "<li>... et " . (count($city_pages) - 5) . " autres</li>";
    }
    echo "</ul>";
}

// Test 3: Vérifier les réalisations
echo "<h2>3. Réalisations</h2>";
$realisations = get_posts(array(
    'post_type' => 'realisation',
    'posts_per_page' => -1,
    'post_status' => 'publish',
));
echo "<p>Nombre de réalisations publiées: " . count($realisations) . "</p>";

// Test 4: Vérifier si la fonction sitemap existe
echo "<h2>4. Fonction sitemap</h2>";
if (function_exists('almetal_generate_sitemap')) {
    echo "<p style='color:green;'>✓ La fonction almetal_generate_sitemap existe</p>";
    
    // Générer le sitemap
    echo "<h2>5. Aperçu du sitemap (premières lignes)</h2>";
    $sitemap = almetal_generate_sitemap();
    echo "<pre>" . htmlspecialchars(substr($sitemap, 0, 2000)) . "...</pre>";
    echo "<p>Taille totale du sitemap: " . strlen($sitemap) . " caractères</p>";
} else {
    echo "<p style='color:red;'>✗ La fonction almetal_generate_sitemap n'existe PAS</p>";
}

// Test 5: Vérifier le sitemap WordPress natif
echo "<h2>6. Sitemap WordPress natif</h2>";
if (function_exists('wp_sitemaps_get_server')) {
    echo "<p style='color:green;'>✓ Le sitemap WordPress natif est disponible</p>";
} else {
    echo "<p style='color:red;'>✗ Le sitemap WordPress natif n'est pas disponible</p>";
}

echo "<hr><p><strong>Supprimez ce fichier après le test!</strong></p>";
