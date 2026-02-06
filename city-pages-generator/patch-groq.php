<?php
/**
 * Patch pour activer Groq dans City Pages Generator
 */

// Remplacer la fonction de génération principale
add_filter('cpg_get_content_generator', function($generator, $city_data) {
    $settings = get_option('cpg_settings', []);
    
    // Forcer l'utilisation de Groq si activé
    if (isset($settings['use_groq']) && $settings['use_groq']) {
        require_once __DIR__ . '/includes/class-content-generator-fixed.php';
        return new CPG_Content_Generator_Fixed($city_data);
    }
    
    return $generator;
}, 10, 2);

// Forcer la régénération à la création
add_action('cpg_city_page_created', function($post_id, $city_data) {
    $settings = get_option('cpg_settings', []);
    
    if (isset($settings['use_groq']) && $settings['use_groq']) {
        // Régénérer avec Groq
        require_once __DIR__ . '/includes/class-content-generator-fixed.php';
        $generator = new CPG_Content_Generator_Fixed($city_data);
        $content = $generator->generate();
        
        wp_update_post(array(
            'ID' => $post_id,
            'post_content' => $content
        ));
        
        // Ajouter un meta pour indiquer que c'est généré par Groq
        update_post_meta($post_id, '_cpg_generated_by', 'groq');
    }
}, 10, 2);
?>
