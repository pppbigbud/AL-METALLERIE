<?php
/**
 * Code à ajouter temporairement dans wp-config.php après la ligne <?php
 * Pour afficher les erreurs PHP directement à l'écran
 */

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Forcer WordPress à afficher les erreurs
define('WP_DEBUG', true);
define('WP_DEBUG_DISPLAY', true);
define('WP_DEBUG_LOG', true);

?>
