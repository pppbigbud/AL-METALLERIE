<?php
/**
 * SEO Improver V2 - Version simplifiée pour debug
 */

if (!defined('ABSPATH')) {
    exit;
}

class Almetal_Seo_Improver_V2_Simple {
    
    public function __construct() {
        add_action('wp_ajax_almetal_get_seo_improvements_with_comments', array($this, 'ajax_get_improvements_with_comments'));
        add_action('wp_ajax_almetal_generate_ai_content_v2', array($this, 'ajax_generate_ai_content_v2'));
        add_action('wp_ajax_almetal_apply_seo_improvements_v2', array($this, 'ajax_apply_improvements_v2'));
    }
    
    /**
     * Récupérer les améliorations avec commentaires et suggestions IA
     */
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
            // Analyse SEO simplifiée
            $issues = $this->simple_seo_analysis($post_id, $is_taxonomy);
            
            // Debug temporaire
            error_log('SEO Improver Debug: post_id=' . $post_id . ', is_taxonomy=' . ($is_taxonomy ? 'true' : 'false') . ', issues_count=' . count($issues));
            
            wp_send_json_success(array(
                'issues' => $issues,
                'score' => $this->calculate_simple_score($issues)
            ));
        } catch (Exception $e) {
            error_log('Error in get_improvements: ' . $e->getMessage());
            wp_send_json_error('Erreur: ' . $e->getMessage());
        }
    }
    
    private function simple_seo_analysis($post_id, $is_taxonomy) {
        $issues = array();
        
        try {
            // Récupérer les données exactement comme dans la page des détails
            if (class_exists('Almetal_Analytics_SEO')) {
                $seo_class = new Almetal_Analytics_SEO();
                
                if ($is_taxonomy) {
                    $term = get_term($post_id);
                    if (!$term || is_wp_error($term)) {
                        throw new Exception('Taxonomie non trouvée');
                    }
                    $analysis = $seo_class->analyze_taxonomy_term($term);
                } else {
                    $post = get_post($post_id);
                    if (!$post) {
                        throw new Exception('Post non trouvé');
                    }
                    $analysis = $seo_class->analyze_page($post_id);
                }
                
                // Debug pour voir ce qu'on reçoit
                error_log('Analysis result: ' . print_r($analysis, true));
                
                // Utiliser les recommandations exactement comme dans les détails
                if (isset($analysis['recommendations']) && is_array($analysis['recommendations'])) {
                    foreach ($analysis['recommendations'] as $rec) {
                        // Créer le problème exactement comme dans les détails
                        $issues[] = array(
                            'type' => $this->get_issue_type($rec['message']),
                            'message' => $rec['message'],
                            'current_value' => $this->get_current_value_from_recommendation($rec, $post_id, $is_taxonomy),
                            'suggested_value' => $this->generate_suggestion_from_recommendation($rec, $post_id, $is_taxonomy)
                        );
                    }
                }
            } else {
                // Version de secours si la classe n'existe pas
                $issues = $this->fallback_analysis($post_id, $is_taxonomy);
            }
        } catch (Exception $e) {
            error_log('Error in simple_seo_analysis: ' . $e->getMessage());
            // Version de secours en cas d'erreur
            $issues = $this->fallback_analysis($post_id, $is_taxonomy);
        }
        
        return $issues;
    }
    
    private function fallback_analysis($post_id, $is_taxonomy) {
        $issues = array();
        
        // Analyse basique de secours
        if ($is_taxonomy) {
            $term = get_term($post_id);
            $title = $term->name;
            $meta_title = get_term_meta($post_id, '_yoast_wpseo_title', true);
            $meta_desc = get_term_meta($post_id, '_yoast_wpseo_metadesc', true);
        } else {
            $post = get_post($post_id);
            $title = $post->post_title;
            $meta_title = get_post_meta($post_id, '_yoast_wpseo_title', true);
            $meta_desc = get_post_meta($post_id, '_yoast_wpseo_metadesc', true);
            $content = $post->post_content;
        }
        
        // Vérifier le titre
        if (empty($meta_title)) {
            $issues[] = array(
                'type' => 'meta_title',
                'message' => 'Meta titre manquant',
                'current_value' => '',
                'suggested_value' => $title . ' - AL Métallerie'
            );
        } elseif (strlen($meta_title) > 60) {
            $issues[] = array(
                'type' => 'meta_title',
                'message' => 'Titre trop long (> 60 caractères), risque de troncature dans Google',
                'current_value' => $meta_title . ' (' . strlen($meta_title) . ' caractères)',
                'suggested_value' => substr($meta_title, 0, 57) . '...'
            );
        }
        
        // Vérifier la meta description
        if (empty($meta_desc)) {
            $issues[] = array(
                'type' => 'meta_description',
                'message' => 'Meta description manquante',
                'current_value' => '',
                'suggested_value' => 'Découvrez nos services de métallerie pour ' . $title . '. Artisans qualifiés, ferronnerie, escaliers, portails.'
            );
        } elseif (strlen($meta_desc) > 160) {
            $issues[] = array(
                'type' => 'meta_description',
                'message' => 'Meta description trop longue (> 160 caractères)',
                'current_value' => $meta_desc . ' (' . strlen($meta_desc) . ' caractères)',
                'suggested_value' => substr($meta_desc, 0, 157) . '...'
            );
        }
        
        // Vérifier le contenu
        if (!$is_taxonomy) {
            $word_count = str_word_count(strip_tags($content));
            if ($word_count < 300) {
                $issues[] = array(
                    'type' => 'content_length',
                    'message' => 'Contenu trop court. Visez 300+ mots pour un meilleur référencement.',
                    'current_value' => $word_count . ' mots',
                    'suggested_value' => 'Ajoutez plus de détails sur vos services de métallerie à ' . $title
                );
            }
        }
        
        return $issues;
    }
    
    private function get_current_value_from_recommendation($rec, $post_id, $is_taxonomy) {
        // Essayer de récupérer la valeur actuelle basée sur le message
        if ($is_taxonomy) {
            $term = get_term($post_id);
            $title = $term->name;
            $meta_title = get_term_meta($post_id, '_yoast_wpseo_title', true);
            $meta_desc = get_term_meta($post_id, '_yoast_wpseo_metadesc', true);
        } else {
            $post = get_post($post_id);
            $title = $post->post_title;
            $meta_title = get_post_meta($post_id, '_yoast_wpseo_title', true);
            $meta_desc = get_post_meta($post_id, '_yoast_wpseo_metadesc', true);
        }
        
        if (strpos($rec['message'], 'titre') !== false && $meta_title) {
            return $meta_title . ' (' . strlen($meta_title) . ' caractères)';
        } elseif (strpos($rec['message'], 'meta description') !== false && $meta_desc) {
            return $meta_desc . ' (' . strlen($meta_desc) . ' caractères)';
        } elseif (strpos($rec['message'], 'Contenu') !== false) {
            $content = get_post_field('post_content', $post_id);
            $word_count = str_word_count(strip_tags($content));
            return $word_count . ' mots';
        }
        
        return '';
    }
    
    private function generate_suggestion_from_recommendation($rec, $post_id, $is_taxonomy) {
        // Récupérer le titre pour les suggestions
        if ($is_taxonomy) {
            $term = get_term($post_id);
            $title = $term->name;
        } else {
            $post = get_post($post_id);
            $title = $post->post_title;
        }
        
        // Générer une suggestion basée sur la recommandation exacte
        if (strpos($rec['message'], 'titre trop long') !== false) {
            $meta_title = get_post_meta($post_id, '_yoast_wpseo_title', true);
            return substr($meta_title, 0, 57) . '...';
        } elseif (strpos($rec['message'], 'meta description trop longue') !== false) {
            $meta_desc = get_post_meta($post_id, '_yoast_wpseo_metadesc', true);
            return substr($meta_desc, 0, 157) . '...';
        } elseif (strpos($rec['message'], 'Contenu') !== false) {
            return 'Enrichissez le contenu avec plus de détails sur vos services de métallerie à ' . $title . '. Ajoutez des informations sur vos réalisations, vos matériaux et votre savoir-faire.';
        }
        
        return 'Suggestion pour: ' . $rec['message'];
    }
    
    /**
     * Générer une suggestion avec Groq IA
     */
    private function generate_groq_suggestion($prompt) {
        // Vérifier si la classe Groq est disponible
        if (!class_exists('Almetal_Groq_Generator')) {
            return false;
        }
        
        try {
            $groq = Almetal_Groq_Generator::get_instance();
            if (!$groq->is_configured()) {
                return false;
            }
            
            // Utiliser Groq pour générer la suggestion
            $response = $groq->generate_content(array(
                'prompt' => $prompt,
                'max_tokens' => 100,
                'temperature' => 0.7
            ));
            
            if (!is_wp_error($response) && !empty($response)) {
                return trim($response);
            }
        } catch (Exception $e) {
            error_log('Groq generation error: ' . $e->getMessage());
        }
        
        return false;
    }
    
    private function calculate_simple_score($issues) {
        $base_score = 100;
        $penalties = array(
            'meta_title' => 10,
            'meta_description' => 15,
            'content_length' => 20
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
        
        // Récupérer les informations du post/taxonomie
        if ($is_taxonomy) {
            $term = get_term($post_id);
            if (!$term || is_wp_error($term)) {
                wp_send_json_error('Taxonomie non trouvée');
            }
            $title = $term->name;
        } else {
            $post = get_post($post_id);
            if (!$post) {
                wp_send_json_error('Post non trouvé');
            }
            $title = $post->post_title;
        }
        
        // Générer du contenu avec Groq si disponible
        $meta_title = $this->generate_groq_suggestion("Génère un excellent meta titre SEO pour: " . $title . ". Max 60 caractères. AL Métallerie.");
        $meta_desc = $this->generate_groq_suggestion("Génère une excellente meta description pour: " . $title . ". Max 160 caractères. Métallerie, ferronnerie, escaliers.");
        $content = $this->generate_groq_suggestion("Génère 300 mots de contenu sur la métallerie pour: " . $title . ". Inclus services et expertise locale.");
        
        // Générer du contenu mock (simulé)
        wp_send_json_success(array(
            'meta_title' => $meta_title ?: $title . ' - AL Métallerie | Expert en Métallerie à Clermont-Ferrand',
            'meta_description' => $meta_desc ?: 'Découvrez nos services de métallerie pour ' . $title . '. Artisans qualifiés, ferronnerie, escaliers, portails et plus. Devis gratuit.',
            'content' => $content ?: 'Notre entreprise de métallerie à Clermont-Ferrand vous accompagne pour tous vos projets en ' . $title . '. Avec plus de 10 ans d\'expérience, nous réalisons des ouvrages sur mesure : escaliers, portails, garde-corps, verrières et bien plus encore. Contactez-nous pour un devis personnalisé.'
        ));
    }
    
    public function ajax_apply_improvements_v2() {
        check_ajax_referer('almetal_seo_improvements', 'nonce');
        
        wp_send_json_success('Améliorations appliquées');
    }
}

// Initialiser avec le hook init
add_action('init', function() {
    // Uniquement dans l'admin
    if (is_admin()) {
        new Almetal_Seo_Improver_V2_Simple();
    }
});
