<?php
/**
 * Page temporaire pour géocoder toutes les villes existantes
 * À utiliser une seule fois, puis supprimer ce fichier
 * 
 * UTILISATION :
 * 1. Uploader ce fichier à la racine du site
 * 2. Visiter https://www.al-metallerie.fr/geocode-cities.php
 * 3. Attendre la fin du traitement
 * 4. Supprimer ce fichier
 */

// Inclure WordPress
require_once('wp-config.php');

// Désactiver le timeout pour les longs traitements
set_time_limit(0);

echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Géocodage des villes - AL Métallerie</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        .progress { background: #f0f0f0; padding: 10px; margin: 10px 0; border-radius: 5px; }
        pre { background: #f5f5f5; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Géocodage automatique des villes</h1>
    <p>Cette page va géocoder toutes les villes existantes en utilisant l\'API OpenStreetMap.</p>
    <p><strong>Attention : </strong>Ce processus peut prendre plusieurs minutes selon le nombre de villes.</p>';

// Inclure les fonctions WordPress
$wp_load = 'wp-load.php';
if (file_exists($wp_load)) {
    require_once($wp_load);
} else {
    echo '<div class="error">Erreur : Impossible de charger WordPress</div>';
    exit;
}

// Charger la fonction de géocodage
if (function_exists('almetal_geocode_city')) {
    echo '<div class="progress">Début du géocodage...</div>';
    
    $city_post_types = array('city_page', 'city-page', 'villes', 'ville', 'city');
    $total_geocoded = 0;
    $total_errors = 0;
    
    foreach ($city_post_types as $post_type) {
        if (post_type_exists($post_type)) {
            echo "<h2>Vérification du type de post : {$post_type}</h2>";
            
            $cities = get_posts(array(
                'post_type' => $post_type,
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'orderby' => 'title',
                'order' => 'ASC'
            ));
            
            if ($cities && !is_wp_error($cities)) {
                echo "<p>Trouvé " . count($cities) . " villes</p>";
                
                foreach ($cities as $city) {
                    $city_name = get_the_title($city->ID);
                    
                    // Vérifier si déjà géocodé
                    $existing_lat = get_post_meta($city->ID, '_city_lat', true);
                    $existing_lng = get_post_meta($city->ID, '_city_lng', true);
                    
                    if (!empty($existing_lat) && !empty($existing_lng)) {
                        echo "<div class='success'>✓ {$city_name} - déjà géocodé</div>";
                    } else {
                        echo "<div>Géocodage de : <strong>{$city_name}</strong>... ";
                        
                        $coords = almetal_geocode_city($city_name, $city->ID);
                        
                        if ($coords) {
                            echo "<span class='success'>✓ Lat: {$coords['lat']}, Lng: {$coords['lng']}</span></div>";
                            $total_geocoded++;
                        } else {
                            echo "<span class='error'>✗ Erreur</span></div>";
                            $total_errors++;
                        }
                        
                        // Respecter la limite de rate de Nominatim (1 req/sec)
                        flush();
                        sleep(1);
                    }
                }
            } else {
                echo "<p>Aucune ville trouvée pour le type {$post_type}</p>";
            }
        }
    }
    
    echo "<h2>Résumé</h2>";
    echo "<p><strong>{$total_geocoded}</strong> villes géocodées avec succès</p>";
    echo "<p><strong>{$total_errors}</strong> erreurs</p>";
    
    if ($total_geocoded > 0) {
        echo '<div class="success">
            <h3>✓ Succès !</h3>
            <p>Toutes les villes ont été géocodées. Vous pouvez maintenant supprimer ce fichier.</p>
            <p>La carte affichera automatiquement toutes les villes avec leurs coordonnées.</p>
        </div>';
    }
    
} else {
    echo '<div class="error">Erreur : La fonction de géocodage n\'est pas disponible. Assurez-vous d\'avoir mis à jour le fichier functions.php.</div>';
}

echo '</body>
</html>';
?>
