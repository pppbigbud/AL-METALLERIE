<?php
/**
 * Redirections 301 pour les réalisations avec slugs modifiés
 * Ajouter ici toutes les anciennes URLs vers les nouvelles
 */

// Redirections pour les réalisations
function almetal_realisation_redirects() {
    $redirects = array(
        // Ancienne URL => Nouvelle URL
        '/realisations/grilles-serrurerie-cunlhat-11-03-2025/' => '/realisations/grilles-serrurerie-cunlhat/',
        '/realisations/portails-escoutoux-01-07-2025/' => '/realisations/portails-escoutoux/',
        '/realisations/garde-corps-chamalieres-20-11-2023/' => '/realisations/garde-corps-chamalieres/',
        
        // Ajouter d'autres redirections ici si nécessaire
        // '/ancienne-url/' => '/nouvelle-url/',
    );
    
    $request_uri = $_SERVER['REQUEST_URI'];
    
    foreach ($redirects as $old => $new) {
        if ($request_uri === $old) {
            wp_redirect(home_url($new), 301);
            exit();
        }
    }
}
add_action('template_redirect', 'almetal_realisation_redirects');

/**
 * Supprimer les dates des slugs de réalisations automatiquement
 */
function almetal_clean_realisation_slugs($post_id, $post, $update) {
    if ($post->post_type !== 'realisation' || $update) {
        return;
    }
    
    // Supprimer les dates du slug (format JJ-MM-AAAA ou JJ-MM-YY)
    $slug = $post->post_name;
    $slug = preg_replace('/-\d{2}-\d{2}-\d{4}$/', '', $slug); // JJ-MM-AAAA
    $slug = preg_replace('/-\d{2}-\d{2}-\d{2}$/', '', $slug);  // JJ-MM-YY
    
    if ($slug !== $post->post_name) {
        wp_update_post(array(
            'ID' => $post_id,
            'post_name' => $slug
        ));
    }
}
// add_action('wp_insert_post', 'almetal_clean_realisation_slugs', 10, 3);
