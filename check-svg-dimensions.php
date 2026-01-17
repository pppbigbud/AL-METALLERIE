<?php
/**
 * Script pour vérifier les dimensions du SVG du footer dans l'historique
 */

echo "=== Vérification des dimensions du SVG du footer ===\n\n";

// Lire le fichier footer.php actuel
$current_footer = file_get_contents(__DIR__ . '/footer.php');

// Extraire le viewBox actuel
if (preg_match('/viewBox="([^"]+)"/', $current_footer, $matches)) {
    echo "ViewBox actuel : " . $matches[1] . "\n";
}

// Extraire les dimensions du path SVG
if (preg_match('/<path[^>]*d="([^"]+)"/', $current_footer, $matches)) {
    $path = $matches[1];
    
    // Extraire les coordonnées X et Y maximales
    preg_match_all('/([MLC])\s*([\d.]+),([\d.]+)/', $path, $coords, PREG_SET_ORDER);
    
    $max_x = 0;
    $max_y = 0;
    
    foreach ($coords as $coord) {
        $max_x = max($max_x, floatval($coord[2]));
        $max_y = max($max_y, floatval($coord[3]));
    }
    
    echo "Dimensions maximales du path : X={$max_x}, Y={$max_y}\n";
    echo "ViewBox attendu : 0 0 " . ceil($max_x) . " " . ceil($max_y) . "\n\n";
}

// Vérifier les CSS
$css_file = __DIR__ . '/assets/css/footer-mountains.css';
if (file_exists($css_file)) {
    $css_content = file_get_contents($css_file);
    
    if (preg_match('/\.footer-mountains\s*{\s*[^}]*height:\s*([^;]+)/', $css_content, $matches)) {
        echo "Hauteur CSS du footer : " . trim($matches[1]) . "\n";
    }
    
    if (preg_match('/\.footer-mountains\s*{\s*[^}]*width:\s*([^;]+)/', $css_content, $matches)) {
        echo "Largeur CSS du footer : " . trim($matches[1]) . "\n";
    }
}

echo "\n=== Analyse ===\n";
echo "Le viewBox '0 0 1200 120' correspond à :\n";
echo "- Largeur : 1200 unités\n";
echo "- Hauteur : 120 unités\n";
echo "- Ratio : 10:1 (très large)\n\n";

echo "Si le SVG apparaît 'aplati' ou 'étiré', c'est probablement parce que :\n";
echo "1. La hauteur CSS (120px) ne correspond pas au ratio du viewBox\n";
echo "2. Le preserveAspectRatio='none' force l'étirement\n";
echo "3. Le container a une largeur de 100vw avec des marges négatives\n\n";

echo "=== Solutions possibles ===\n";
echo "1. Changer la hauteur CSS pour correspondre au ratio attendu\n";
echo "2. Modifier preserveAspectRatio='xMidYMid slice' au lieu de 'none'\n";
echo "3. Ajuster le viewBox pour mieux correspondre aux dimensions souhaitées\n";

?>
