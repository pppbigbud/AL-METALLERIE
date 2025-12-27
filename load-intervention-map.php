<?php
/**
 * Script pour charger les assets de la page intervention
 * À placer dans functions.php ou à exécuter une fois
 */

// Enqueue styles et scripts pour la page intervention
function almetal_intervention_map_scripts() {
    // Vérifier si nous sommes sur la page soudure-auvergne
    if (is_page_template('page-soudure-auvergne.php')) {
        // Charger le CSS
        wp_enqueue_style(
            'intervention-map-style',
            get_template_directory_uri() . '/assets/css/intervention-map.css',
            array(),
            '1.0.0'
        );
        
        // Charger le JavaScript
        wp_enqueue_script(
            'intervention-map-script',
            get_template_directory_uri() . '/assets/js/intervention-map.js',
            array('jquery', 'leaflet-js'),
            '1.0.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'almetal_intervention_map_scripts');

// Instructions pour l'utilisateur:
echo "Pour activer la page soudure-auvergne:\n";
echo "1. Copiez page-soudure-auvergne.php dans wordpress/wp-content/themes/almetal-theme/\n";
echo "2. Copiez intervention-map.css dans assets/css/\n";
echo "3. Copiez intervention-map.js dans assets/js/\n";
echo "4. Ajoutez le code ci-dessus dans functions.php\n";
echo "5. Dans l'admin WordPress, éditez la page soudure-auvergne et sélectionnez le template 'Page Soudure Auvergne'\n";
?>
