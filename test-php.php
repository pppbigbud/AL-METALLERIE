<?php
// Test simple pour vérifier que PHP fonctionne
echo "PHP fonctionne !<br>";
echo "Version PHP : " . phpversion() . "<br>";
echo "Date : " . date('Y-m-d H:i:s') . "<br>";

// Vérifier si WordPress peut être trouvé
if (file_exists('wp-config.php')) {
    echo "wp-config.php trouvé<br>";
} else {
    echo "wp-config.php NON TROUVÉ<br>";
}

// Vérifier les erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Test d'une erreur intentionnelle
// $test = undefined_variable; // Décommentez pour tester l'affichage des erreurs

?>
