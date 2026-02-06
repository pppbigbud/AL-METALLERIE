<?php
/**
 * SEO Improver V2 - Version avec vraies données SEO et suggestions optimisées
 */

if (!defined('ABSPATH')) {
    exit;
}

class Almetal_Seo_Improver_V2_Optimized {
    
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
            $meta_title = get_post_meta($post_id, '_yoast_wpseo_title', true);
            $meta_desc = get_post_meta($post_id, '_yoast_wpseo_metadesc', true);
            $content = $post->post_content;
        }
        
        // Analyser le titre
        if (empty($meta_title)) {
            $issues[] = array(
                'type' => 'meta_title',
                'message' => 'Meta titre manquant',
                'current_value' => '',
                'suggested_value' => $this->generate_optimized_title($title)
            );
        } elseif (strlen($meta_title) > 60) {
            $issues[] = array(
                'type' => 'meta_title',
                'message' => 'Titre trop long (> 60 caractères), risque de troncature dans Google',
                'current_value' => $meta_title . ' (' . strlen($meta_title) . ' caractères)',
                'suggested_value' => $this->optimize_title_length($meta_title)
            );
        }
        
        // Analyser la meta description
        if (empty($meta_desc)) {
            $issues[] = array(
                'type' => 'meta_description',
                'message' => 'Meta description manquante',
                'current_value' => '',
                'suggested_value' => $this->generate_optimized_description($title)
            );
        } elseif (strlen($meta_desc) > 160) {
            $issues[] = array(
                'type' => 'meta_description',
                'message' => 'Meta description trop longue (> 160 caractères)',
                'current_value' => $meta_desc . ' (' . strlen($meta_desc) . ' caractères)',
                'suggested_value' => $this->optimize_description_length($meta_desc)
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
                    'suggested_value' => $this->generate_content_enhancement($title, $word_count)
                );
            } elseif ($word_count < 600) {
                $issues[] = array(
                    'type' => 'content_length',
                    'message' => 'Contenu moyen. Visez 600+ mots pour un meilleur référencement.',
                    'current_value' => $word_count . ' mots',
                    'suggested_value' => $this->generate_content_enhancement($title, $word_count)
                );
            }
        }
        
        return $issues;
    }
    
    private function generate_optimized_title($title) {
        // Utiliser Groq si disponible
        $groq_suggestion = $this->ask_groq_for_title($title);
        if ($groq_suggestion) {
            return $groq_suggestion;
        }
        
        // Sinon, utiliser la version par défaut
        return "Métallerie " . $title . " | AL Métallerie - Artisans qualifiés";
    }
    
    private function ask_groq_for_title($title) {
        if (!class_exists('Almetal_Groq_Generator')) {
            return false;
        }
        
        try {
            $groq = Almetal_Groq_Generator::get_instance();
            $prompt = "Génère un meta titre SEO optimisé pour une page de métallerie à " . $title . ". Maximum 60 caractères. Inclure 'AL Métallerie'. Sois concis et percutant.";
            
            $response = $groq->generate_content($prompt, array(
                'max_tokens' => 50,
                'temperature' => 0.7
            ));
            
            if ($response && !empty($response['content'])) {
                return trim($response['content']);
            }
        } catch (Exception $e) {
            error_log('Groq error: ' . $e->getMessage());
        }
        
        return false;
    }
    
    private function generate_optimized_description($title) {
        // Utiliser Groq si disponible
        $groq_suggestion = $this->ask_groq_for_description($title);
        if ($groq_suggestion) {
            return $groq_suggestion;
        }
        
        // Sinon, utiliser la version par défaut
        return "Découvrez nos services de métallerie à " . $title . ". Artisans qualifiés pour tous vos travaux : ferronnerie, escaliers, portails, garde-corps. Devis gratuit !";
    }
    
    private function ask_groq_for_description($title) {
        if (!class_exists('Almetal_Groq_Generator')) {
            return false;
        }
        
        try {
            $groq = Almetal_Groq_Generator::get_instance();
            $prompt = "Génère une meta description SEO pour une page de métallerie à " . $title . ". Maximum 160 caractères. Inclus les services : ferronnerie, escaliers, portails, garde-corps. Mentionne 'devis gratuit'.";
            
            $response = $groq->generate_content($prompt, array(
                'max_tokens' => 80,
                'temperature' => 0.7
            ));
            
            if ($response && !empty($response['content'])) {
                return trim($response['content']);
            }
        } catch (Exception $e) {
            error_log('Groq error: ' . $e->getMessage());
        }
        
        return false;
    }
    
    private function optimize_title_length($title) {
        if (strlen($title) <= 60) return $title;
        
        // Garder les mots-clés importants
        $keywords = array('Métallerie', 'AL Métallerie', 'Artisans', 'Ferronnerie');
        $optimized = $title;
        
        foreach ($keywords as $keyword) {
            if (strpos($title, $keyword) !== false) {
                // Garder le mot-clé et couper après
                $pos = strpos($optimized, $keyword) + strlen($keyword);
                $optimized = substr($optimized, 0, $pos);
                break;
            }
        }
        
        return substr($optimized, 0, 57) . '...';
    }
    
    private function generate_optimized_description($title) {
        return "Découvrez nos services de métallerie à " . $title . ". Artisans qualifiés pour tous vos travaux : ferronnerie, escaliers, portails, garde-corps. Devis gratuit !";
    }
    
    private function optimize_description_length($description) {
        if (strlen($description) <= 160) return $description;
        
        // Extraire les phrases importantes
        $sentences = preg_split('/[.!?]+/', $description, -1, PREG_SPLIT_NO_EMPTY);
        $optimized = '';
        
        foreach ($sentences as $sentence) {
            $sentence = trim($sentence);
            if (strlen($optimized . $sentence . '.') <= 157) {
                $optimized .= $sentence . '. ';
            } else {
                break;
            }
        }
        
        return substr(trim($optimized), 0, 157) . '...';
    }
    
    private function generate_content_enhancement($title, $current_words) {
        $needed = 600 - $current_words;
        $suggestions = array(
            "Ajoutez une section sur vos spécificités locales à " . $title . ".",
            "Décrivez vos matériaux de fabrication (acier, inox, aluminium...).",
            "Présentez vos certifications et qualifications professionnelles.",
            "Ajoutez des exemples de réalisations récentes dans la région.",
            "Expliquez votre processus de fabrication et les délais.",
            "Incluez un témoignage client de " . $title . ".",
            "Développez sur vos garanties et service après-vente.",
            "Ajoutez des informations sur vos tarifs et devis."
        );
        
        $enhancement = "Pour atteindre " . (600) . " mots, ajoutez environ " . $needed . " mots :\n\n";
        $enhancement .= "• " . implode("\n• ", array_slice($suggestions, 0, 4));
        
        return $enhancement;
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
        
        // Générer du contenu optimisé
        if ($is_taxonomy) {
            $term = get_term($post_id);
            $title = $term->name;
        } else {
            $post = get_post($post_id);
            $title = $post->post_title;
        }
        
        wp_send_json_success(array(
            'meta_title' => $this->generate_optimized_title($title),
            'meta_description' => $this->generate_optimized_description($title),
            'content' => $this->generate_full_content($title)
        ));
    }
    
    private function generate_full_content($title) {
        // Utiliser Groq si disponible
        $groq_content = $this->ask_groq_for_content($title);
        if ($groq_content) {
            return $groq_content;
        }
        
        // Sinon, utiliser le contenu par défaut
        return "<h2>Métallerie à " . $title . "</h2>
<p>AL Métallerie est votre artisan spécialisé dans la conception et la réalisation de tous vos projets de métallerie à " . $title . " et ses environs.</p>

<h3>Nos Services de Métallerie</h3>
<p>Nous proposons une large gamme de services pour répondre à tous vos besoins :</p>
<ul>
<li><strong>Ferronnerie d'art</strong> : Portails, grilles, garde-corps sur mesure</li>
<li><strong>Escaliers</strong> : Escaliers intérieurs, extérieurs, colimaçon</li>
<li><strong>Charpentes métalliques</strong> : Construction et rénovation</li>
<li><strong>Bardages et couvertures</strong> : Protection et esthétique</li>
</ul>

<h3>Pourquoi choisir AL Métallerie ?</h3>
<p>Notre équipe d'artisans qualifiés met son savoir-faire à votre service pour réaliser des projets sur mesure, alliant technique et esthétique.</p>";
    }
    
    private function ask_groq_for_content($title) {
        if (!class_exists('Almetal_Groq_Generator')) {
            return false;
        }
        
        try {
            $groq = Almetal_Groq_Generator::get_instance();
            $prompt = "Génère un contenu SEO optimisé en HTML pour une page de métallerie à " . $title . ". Inclus : introduction, services (ferronnerie, escaliers, portails), pourquoi nous choisir, et conclusion. Utilise des balises <h2>, <h3>, <p>, <ul>, <li>. Environ 400 mots.";
            
            $response = $groq->generate_content($prompt, array(
                'max_tokens' => 600,
                'temperature' => 0.7
            ));
            
            if ($response && !empty($response['content'])) {
                return trim($response['content']);
            }
        } catch (Exception $e) {
            error_log('Groq error: ' . $e->getMessage());
        }
        
        return false;
    }
    
    public function ajax_apply_improvements_v2() {
        check_ajax_referer('almetal_seo_improvements', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_die(__('Permission denied'));
        }
        
        $post_id = intval($_POST['post_id']);
        $improvements = $_POST['improvements'];
        
        // Appliquer les améliorations
        if (isset($improvements['meta_title'])) {
            update_post_meta($post_id, '_yoast_wpseo_title', $improvements['meta_title']);
        }
        
        if (isset($improvements['meta_description'])) {
            update_post_meta($post_id, '_yoast_wpseo_metadesc', $improvements['meta_description']);
        }
        
        wp_send_json_success('Améliorations appliquées avec succès');
    }
}

// Initialiser avec le hook init
add_action('init', function() {
    if (is_admin()) {
        new Almetal_Seo_Improver_V2_Optimized();
    }
});
