<?php
// Script pour régénérer une page ville avec Groq
$post_id = 344;
$city_data = array(
    "city_name" => "saint-agathe",
    "department" => "Puy-de-Dôme",
    "postal_code" => "63120",
    "distance_km" => "15",
    "travel_time" => "20 minutes",
    "local_specifics" => ""
);

require_once "wp-content/plugins/city-pages-generator/includes/class-content-generator-groq.php";
$generator = new CPG_Content_Generator_Groq($city_data);
$content = $generator->generate();

wp_update_post(array(
    "ID" => $post_id,
    "post_content" => $content
));

echo "Contenu régénéré avec Groq AI pour la page saint-agathe !\n";
?>
