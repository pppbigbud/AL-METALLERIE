<?php
// Test de génération Groq
require_once 'wp-config.php';

// Données de test
$city_data = array(
    'city_name' => 'Test-Groq-Ville',
    'department' => 'Puy-de-Dôme',
    'postal_code' => '63000',
    'distance_km' => '25',
    'travel_time' => '25 minutes',
    'local_specifics' => 'Centre ville, zone commerciale'
);

// Charger les classes
require_once 'wp-content/plugins/city-pages-generator/includes/class-content-generator-fixed.php';
require_once 'wp-content/plugins/city-pages-generator/includes/class-groq-integration.php';

// Générer le contenu
$generator = new CPG_Content_Generator_Fixed($city_data);
$content = $generator->generate();

echo "=== CONTENU GÉNÉRÉ ===\n";
echo $content . "\n";
echo "=== FIN ===\n";

// Vérifier si c'est du Groq
if (strpos($content, '(Groq AI)') !== false) {
    echo "\n✅ CONTENU GÉNÉRÉ PAR GROQ AI !\n";
} else {
    echo "\n❌ Contenu généré par templates (fallback)\n";
}
?>
