<?php
// Activer toutes les erreurs AVANT de charger WordPress
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "Début du test WordPress...<br>";

// Vérifier si wp-load.php existe
if (!file_exists('wp-load.php')) {
    echo "ERREUR: wp-load.php non trouvé!<br>";
    echo "Répertoire courant: " . getcwd() . "<br>";
    exit;
}

echo "Tentative de chargement de WordPress...<br>";

// Charger WordPress avec gestion d'erreur
try {
    require_once('wp-load.php');
    echo "WordPress chargé avec succès!<br>";
    
    // Test simple WordPress
    if (function_exists('get_bloginfo')) {
        echo "Nom du site: " . get_bloginfo('name') . "<br>";
        echo "URL du site: " . get_bloginfo('url') . "<br>";
    }
} catch (Error $e) {
    echo "ERREUR FATALE: " . $e->getMessage() . "<br>";
    echo "Fichier: " . $e->getFile() . " Ligne: " . $e->getLine() . "<br>";
} catch (Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "<br>";
}

echo "Fin du test.<br>";
?>
