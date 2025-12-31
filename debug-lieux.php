<?php
// Script de débogage pour vérifier les valeurs de lieu
require_once('wp-config.php');

// Connexion à la base de données
global $wpdb;

// Récupérer toutes les valeurs de _almetal_lieu
$results = $wpdb->get_results("
    SELECT post_id, meta_value 
    FROM {$wpdb->postmeta} 
    WHERE meta_key = '_almetal_lieu' 
    AND meta_value != ''
    ORDER BY meta_value
");

echo "=== VALEURS STOCKÉES DANS _almetal_lieu ===\n\n";
foreach ($results as $row) {
    echo "Post ID: {$row->post_id} | Lieu: '{$row->meta_value}'\n";
}

echo "\n=== ANALYSE DES VALEURS POUR THIERS ===\n";
$thiers_variants = array('Thiers', 'thiers', 'Thiers ', ' Thiers', 'Thiers,', ',Thiers');
foreach ($thiers_variants as $variant) {
    $count = $wpdb->get_var($wpdb->prepare("
        SELECT COUNT(*) 
        FROM {$wpdb->postmeta} 
        WHERE meta_key = '_almetal_lieu' 
        AND meta_value LIKE %s
    ", '%' . $wpdb->esc_like($variant) . '%'));
    echo "Recherche pour '{$variant}': {$count} résultats\n";
}

echo "\n=== TEST DE REQUÊTE COMPLÈTE ===\n";
$test_query = new WP_Query(array(
    'post_type' => 'realisation',
    'posts_per_page' => -1,
    'meta_query' => array(
        'relation' => 'OR',
        array(
            'key' => '_almetal_lieu',
            'value' => 'Thiers',
            'compare' => 'LIKE'
        )
    )
));
echo "Nombre de réalisations pour Thiers: " . $test_query->found_posts . "\n";

// Afficher les titres des réalisations trouvées
if ($test_query->have_posts()) {
    echo "\nListe des réalisations trouvées:\n";
    while ($test_query->have_posts()) {
        $test_query->the_post();
        $lieu = get_post_meta(get_the_ID(), '_almetal_lieu', true);
        echo "- " . get_the_title() . " | Lieu: '{$lieu}'\n";
    }
}
?>
