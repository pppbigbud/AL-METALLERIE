<?php
/**
 * SEO Improver V2 - Version avec Groq IA compatible
 */

if (!defined('ABSPATH')) {
    exit;
}

class Almetal_Seo_Improver_V2_Groq {
    
    public function __construct() {
        add_action('wp_ajax_almetal_get_seo_improvements_with_comments', array($this, 'ajax_get_improvements_with_comments'));
        add_action('wp_ajax_almetal_generate_ai_content_v2', array($this, 'ajax_generate_ai_content_v2'));
        add_action('wp_ajax_almetal_apply_seo_improvements_v2', array($this, 'ajax_apply_improvements_v2'));
    }
    
    public function ajax_get_improvements_with_comments() {
        error_log('AJAX get_improvements called');
        check_ajax_referer('almetal_seo_improvements', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_die(__('Permission denied'));
        }
        
        $post_id = intval($_POST['post_id']);
        $is_taxonomy = isset($_POST['is_taxonomy']) && $_POST['is_taxonomy'] === 'true';
        
        error_log('Processing post_id: ' . $post_id . ', is_taxonomy: ' . ($is_taxonomy ? 'true' : 'false'));
        
        try {
            // Analyse SEO avec vraies données
            $issues = $this->get_real_seo_issues($post_id, $is_taxonomy);
            
            error_log('Issues count: ' . count($issues));
            
            wp_send_json_success(array(
                'issues' => $issues,
                'score' => $this->calculate_score($issues)
            ));
        } catch (Exception $e) {
            error_log('Error in get_improvements: ' . $e->getMessage());
            wp_send_json_error('Erreur: ' . $e->getMessage());
        }
    }
    
    private function get_real_seo_issues($post_id, $is_taxonomy) {
        $issues = array();
        
        // Récupérer les données SEO réelles
        if ($is_taxonomy) {
            $term = get_term($post_id);
            if (!$term || is_wp_error($term)) {
                return array();
            }
            $title = $term->name;
            $meta_title = get_term_meta($post_id, '_yoast_wpseo_title', true);
            $meta_desc = get_term_meta($post_id, '_yoast_wpseo_metadesc', true);
        } else {
            $post = get_post($post_id);
            if (!$post) {
                return array();
            }
            $title = $post->post_title;
            
            // Si le titre contient "Formations" ou autre, extraire la ville
            if (strpos($title, 'Formations') !== false || strpos($title, 'Métallier') !== false) {
                // Récupérer la ville depuis les custom fields ou le slug
                $ville = get_post_meta($post_id, 'ville', true);
                if (!$ville) {
                    // Essayer de récupérer depuis le slug ou la taxonomie
                    $terms = wp_get_post_terms($post_id, 'departement');
                    if (!empty($terms)) {
                        $ville = $terms[0]->name;
                    }
                }
                if ($ville) {
                    $title = $ville;
                }
            }
            
            $meta_title = get_post_meta($post_id, '_yoast_wpseo_title', true);
            $meta_desc = get_post_meta($post_id, '_yoast_wpseo_metadesc', true);
            $content = $post->post_content;
        }
        
        error_log('Final title for SEO: ' . $title);
        
        // Analyser le titre
        if (empty($meta_title)) {
            $issues[] = array(
                'type' => 'meta_title',
                'message' => 'Meta titre manquant',
                'current_value' => '',
                'suggested_value' => $this->generate_groq_title($title)
            );
        } elseif (strlen($meta_title) > 60) {
            $issues[] = array(
                'type' => 'meta_title',
                'message' => 'Titre trop long (> 60 caractères), risque de troncature dans Google',
                'current_value' => $meta_title . ' (' . strlen($meta_title) . ' caractères)',
                'suggested_value' => $this->generate_groq_title($title, true)
            );
        }
        
        // Analyser la meta description
        if (empty($meta_desc)) {
            $issues[] = array(
                'type' => 'meta_description',
                'message' => 'Meta description manquante',
                'current_value' => '',
                'suggested_value' => $this->generate_groq_description($title)
            );
        } elseif (strlen($meta_desc) > 160) {
            $issues[] = array(
                'type' => 'meta_description',
                'message' => 'Meta description trop longue (> 160 caractères)',
                'current_value' => $meta_desc . ' (' . strlen($meta_desc) . ' caractères)',
                'suggested_value' => $this->generate_groq_description($title, true)
            );
        }
        
        // Analyser le contenu
        if (!$is_taxonomy) {
            $word_count = str_word_count(strip_tags($content));
            if ($word_count < 300) {
                $issues[] = array(
                    'type' => 'content_length',
                    'message' => 'Contenu trop court. Visez 300+ mots pour un meilleur référencement.',
                    'current_value' => $word_count . ' mots',
                    'suggested_value' => $this->generate_groq_content($title, $word_count)
                );
            } elseif ($word_count < 600) {
                $issues[] = array(
                    'type' => 'content_length',
                    'message' => 'Contenu moyen. Visez 600+ mots pour un meilleur référencement.',
                    'current_value' => $word_count . ' mots',
                    'suggested_value' => $this->generate_groq_content($title, $word_count)
                );
            }
        }
        
        return $issues;
    }
    
    private function generate_groq_title($title, $is_shorten = false) {
        error_log('generate_groq_title called for: ' . $title);
        
        // Vérifier si la classe Groq est disponible
        if (!class_exists('Almetal_Groq_Generator')) {
            error_log('Almetal_Groq_Generator class not found');
            return $this->fallback_title($title);
        }
        
        try {
            $groq = Almetal_Groq_Generator::get_instance();
            error_log('Groq instance created');
            
            // Utiliser le bon format pour la classe Groq
            $response = $groq->generate_content('meta_description', array(
                'subject' => 'métallerie à ' . $title,
                'location' => $title,
                'type' => 'page',
                'max_tokens' => 50
            ));
            
            error_log('Groq response: ' . print_r($response, true));
            
            if ($response && !empty($response) && $response !== $this->fallback_title($title)) {
                // Nettoyer la réponse pour en faire un titre
                $clean_title = $this->clean_response_to_title($response, $title);
                error_log('Clean title: ' . $clean_title);
                return $clean_title;
            }
        } catch (Exception $e) {
            error_log('Groq error: ' . $e->getMessage());
        }
        
        error_log('Using fallback title');
        return $this->fallback_title($title);
    }
    
    private function clean_response_to_title($response, $title) {
        // Si la réponse est une description, extraire les mots-clés pour faire un titre
        if (strlen($response) > 60) {
            // Créer un titre optimisé
            $patterns = array(
                "Métallerie {$title} | AL Métallerie",
                "AL Métallerie {$title} - Artisans qualifiés",
                "Métallier {$title} | AL Métallerie"
            );
            return $patterns[array_rand($patterns)];
        }
        
        return $response;
    }
    
    private function generate_groq_description($title, $is_shorten = false) {
        error_log('generate_groq_description called for: ' . $title);
        
        if (!class_exists('Almetal_Groq_Generator')) {
            error_log('Almetal_Groq_Generator class not found');
            return $this->fallback_description($title);
        }
        
        try {
            $groq = Almetal_Groq_Generator::get_instance();
            error_log('Groq instance created for description');
            
            // Utiliser le bon format pour la classe Groq
            $response = $groq->generate_content('meta_description', array(
                'subject' => 'services de métallerie',
                'location' => $title,
                'type' => 'page'
            ));
            
            error_log('Groq description response: ' . print_r($response, true));
            
            if ($response && !empty($response) && $response !== $this->fallback_description($title)) {
                error_log('Using Groq description');
                return $response;
            }
        } catch (Exception $e) {
            error_log('Groq error: ' . $e->getMessage());
        }
        
        error_log('Using fallback description');
        return $this->fallback_description($title);
    }
    
    private function generate_groq_content($title, $current_words) {
        if (!class_exists('Almetal_Groq_Generator')) {
            return $this->fallback_content($title, $current_words);
        }
        
        try {
            $groq = Almetal_Groq_Generator::get_instance();
            $needed = 600 - $current_words;
            
            $prompt = "Génère " . $needed . " mots de contenu SEO pour une page de métallerie à " . $title . ". Inclus : présentation, services (ferronnerie, escaliers), pourquoi nous choisir, et appel à l'action.";
            
            $response = $groq->generate_content('seo_content', array(
                'title' => $title,
                'prompt' => $prompt,
                'max_tokens' => 600
            ));
            
            if ($response && !empty($response)) {
                return is_string($response) ? $response : (isset($response['content']) ? $response['content'] : $this->fallback_content($title, $current_words));
            }
        } catch (Exception $e) {
            error_log('Groq error: ' . $e->getMessage());
        }
        
        return $this->fallback_content($title, $current_words);
    }
    
    private function fallback_title($title) {
        return "Métallerie " . $title . " | AL Métallerie - Artisans qualifiés";
    }
    
    private function fallback_description($title) {
        return "Découvrez nos services de métallerie à " . $title . ". Artisans qualifiés pour ferronnerie, escaliers, portails. Devis gratuit !";
    }
    
    private function fallback_content($title, $current_words) {
        $needed = 600 - $current_words;
        return "Pour atteindre 600 mots, ajoutez environ " . $needed . " mots sur vos services de métallerie à " . $title . ".";
    }
    
    private function calculate_score($issues) {
        $base_score = 100;
        $penalties = array(
            'meta_title' => 15,
            'meta_description' => 20,
            'content_length' => 15
        );
        
        foreach ($issues as $issue) {
            if (isset($penalties[$issue['type']])) {
                $base_score -= $penalties[$issue['type']];
            }
        }
        
        return max(0, $base_score);
    }
    
    public function ajax_generate_ai_content_v2() {
        check_ajax_referer('almetal_seo_improvements', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_die(__('Permission denied'));
        }
        
        $post_id = intval($_POST['post_id']);
        $is_taxonomy = isset($_POST['is_taxonomy']) && $_POST['is_taxonomy'] === 'true';
        
        if ($is_taxonomy) {
            $term = get_term($post_id);
            $title = $term->name;
        } else {
            $post = get_post($post_id);
            $title = $post->post_title;
        }
        
        wp_send_json_success(array(
            'meta_title' => $this->generate_groq_title($title),
            'meta_description' => $this->generate_groq_description($title),
            'content' => $this->generate_full_groq_content($title)
        ));
    }
    
    private function generate_full_groq_content($title) {
        if (!class_exists('Almetal_Groq_Generator')) {
            return $this->fallback_full_content($title);
        }
        
        try {
            $groq = Almetal_Groq_Generator::get_instance();
            
            $prompt = "Génère un contenu SEO complet en HTML pour une page de métallerie à " . $title . ". Inclus : introduction, services (ferronnerie, escaliers, portails), pourquoi nous choisir, et conclusion. Utilise des balises <h2>, <h3>, <p>, <ul>, <li>. Environ 400 mots.";
            
            $response = $groq->generate_content('seo_full_content', array(
                'title' => $title,
                'prompt' => $prompt,
                'max_tokens' => 600
            ));
            
            if ($response && !empty($response)) {
                return is_string($response) ? $response : (isset($response['content']) ? $response['content'] : $this->fallback_full_content($title));
            }
        } catch (Exception $e) {
            error_log('Groq error: ' . $e->getMessage());
        }
        
        return $this->fallback_full_content($title);
    }
    
    private function fallback_full_content($title) {
        return "<h2>Métallerie à " . $title . "</h2>
<p>AL Métallerie est votre artisan spécialisé dans la conception et la réalisation de tous vos projets de métallerie.</p>";
    }
    
    public function ajax_apply_improvements_v2() {
        check_ajax_referer('almetal_seo_improvements', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_die(__('Permission denied'));
        }
        
        $post_id = intval($_POST['post_id']);
        $improvements = $_POST['improvements'];
        
        if (isset($improvements['meta_title'])) {
            update_post_meta($post_id, '_yoast_wpseo_title', sanitize_text_field($improvements['meta_title']));
        }
        
        if (isset($improvements['meta_description'])) {
            update_post_meta($post_id, '_yoast_wpseo_metadesc', sanitize_textarea_field($improvements['meta_description']));
        }
        
        wp_send_json_success('Améliorations appliquées avec succès');
    }
}

// Initialiser avec le hook init
add_action('init', function() {
    if (is_admin()) {
        new Almetal_Seo_Improver_V2_Groq();
    }
});
