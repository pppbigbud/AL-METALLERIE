<?php
/**
 * Intégration Groq V2 - Simple et efficace
 */

// Inclure le générateur
require_once __DIR__ . '/includes/class-groq-generator-v2.php';

// Remplacer complètement le générateur de contenu
add_filter('cpg_generate_city_content', function($content, $city_data) {
    $settings = get_option('cpg_settings', []);
    
    // Vérifier si Groq est activé
    if (isset($settings['use_groq']) && $settings['use_groq']) {
        $generator = new CPG_Groq_Generator_V2();
        $new_content = $generator->generate_city_page($city_data);
        
        // Ajouter un marqueur pour savoir que c'est Groq
        if (!is_wp_error($new_content)) {
            return "<!-- Page générée par Groq AI -->\n" . $new_content;
        }
    }
    
    return $content;
}, 10, 2);

// Hook pour la création de page
add_action('cpg_city_page_created', function($post_id, $city_data) {
    $settings = get_option('cpg_settings', []);
    
    if (isset($settings['use_groq']) && $settings['use_groq']) {
        // Générer le contenu avec Groq
        $generator = new CPG_Groq_Generator_V2();
        $content = $generator->generate_city_page($city_data);
        
        if (!is_wp_error($content)) {
            wp_update_post(array(
                'ID' => $post_id,
                'post_content' => $content
            ));
            
            // Ajouter meta
            update_post_meta($post_id, '_cpg_generated_by', 'groq_v2');
        }
    }
}, 10, 2);

// Bouton de régénération dans l'admin
add_action('add_meta_boxes', function() {
    add_meta_box('cpg_regenerate_with_groq', 'Générer avec Groq AI', function($post) {
        if ($post->post_type !== 'city_page') return;
        
        $settings = get_option('cpg_settings', []);
        $is_groq = get_post_meta($post->ID, '_cpg_generated_by', true) === 'groq_v2';
        
        echo '<div style="padding: 10px;">';
        if ($is_groq) {
            echo '<p style="color: green;">✅ Cette page a été générée par Groq AI</p>';
        }
        
        if (isset($settings['use_groq']) && $settings['use_groq']) {
            echo '<button type="button" id="regenerate-groq" class="button button-primary">Régénérer avec Groq AI</button>';
            echo '<p><small>Cela remplacera tout le contenu actuel.</small></p>';
        } else {
            echo '<p style="color: orange;">⚠️ Groq AI n\'est pas activé. <a href="' . admin_url('admin.php?page=cpg-groq-settings') . '">Activer ici</a></p>';
        }
        echo '</div>';
        
        // JavaScript pour la régénération AJAX
        echo '<script>
        jQuery(document).ready(function($) {
            $("#regenerate-groq").on("click", function() {
                if (!confirm("Êtes-vous sûr de vouloir régénérer tout le contenu avec Groq AI ?")) return;
                
                $(this).prop("disabled", true).html("Génération en cours...");
                
                $.ajax({
                    url: ajaxurl,
                    type: "POST",
                    data: {
                        action: "cpg_regenerate_groq",
                        post_id: ' . $post->ID . ',
                        nonce: "' . wp_create_nonce('cpg_regenerate_groq') . '"
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert("Erreur : " + response.data);
                        }
                    }
                });
            });
        });
        </script>';
    }, 'city_page', 'side', 'high');
});

// Handler AJAX pour la régénération
add_action('wp_ajax_cpg_regenerate_groq', function() {
    check_ajax_referer('cpg_regenerate_groq');
    
    if (!current_user_can('edit_posts')) {
        wp_die(__('Permission denied'));
    }
    
    $post_id = intval($_POST['post_id']);
    $city_data = get_post_meta($post_id);
    
    // Préparer les données
    $city_data_array = array(
        'city_name' => get_post_meta($post_id, 'city_name', true),
        'department' => get_post_meta($post_id, 'department', true),
        'postal_code' => get_post_meta($post_id, 'postal_code', true),
        'distance_km' => get_post_meta($post_id, 'distance_km', true),
        'travel_time' => get_post_meta($post_id, 'travel_time', true),
        'local_specifics' => get_post_meta($post_id, 'local_specifics', true)
    );
    
    $generator = new CPG_Groq_Generator_V2();
    $content = $generator->generate_city_page($city_data_array);
    
    if (!is_wp_error($content)) {
        wp_update_post(array(
            'ID' => $post_id,
            'post_content' => $content
        ));
        
        update_post_meta($post_id, '_cpg_generated_by', 'groq_v2');
        
        wp_send_json_success('Contenu généré avec succès');
    } else {
        wp_send_json_error($content->get_error_message());
    }
});
