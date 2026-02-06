<?php
/**
 * SEO Improver V2 - Avec prise en compte des commentaires et génération IA
 */

if (!defined('ABSPATH')) {
    exit;
}

class Almetal_Seo_Improver_V2 {
    
    private $groq_generator;
    
    public function __construct() {
        // Inclure les classes nécessaires
        $plugin_path = plugin_dir_path(dirname(__FILE__, 2));
        
        if (!class_exists('Almetal_Groq_Generator')) {
            require_once $plugin_path . 'includes/class-groq-generator.php';
        }
        if (!class_exists('Almetal_Seo_Analyzer')) {
            require_once $plugin_path . 'includes/class-seo.php';
        }
        
        $this->groq_generator = Almetal_Groq_Generator::get_instance();
        add_action('wp_ajax_almetal_get_seo_improvements_with_comments', array($this, 'ajax_get_improvements_with_comments'));
        add_action('wp_ajax_almetal_generate_ai_content_v2', array($this, 'ajax_generate_ai_content_v2'));
        add_action('wp_ajax_almetal_apply_seo_improvements_v2', array($this, 'ajax_apply_improvements_v2'));
    }
    
    /**
     * Récupérer les améliorations avec commentaires et suggestions IA
     */
    public function ajax_get_improvements_with_comments() {
        check_ajax_referer('almetal_seo_improvements', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_die(__('Permission denied'));
        }
        
        $post_id = intval($_POST['post_id']);
        $is_taxonomy = isset($_POST['is_taxonomy']) && $_POST['is_taxonomy'] === 'true';
        
        if ($is_taxonomy) {
            $term = get_term($post_id);
            if (!$term || is_wp_error($term)) {
                wp_send_json_error('Terme non trouvé');
            }
            
            $issues = $this->get_taxonomy_issues_with_ai($term);
        } else {
            $post = get_post($post_id);
            if (!$post) {
                wp_send_json_error('Post non trouvé');
            }
            
            $issues = $this->get_post_issues_with_ai($post);
        }
        
        wp_send_json_success(array(
            'issues' => $issues,
            'score' => $this->calculate_seo_score($post_id, $is_taxonomy)
        ));
    }
    
    /**
     * Analyser les problèmes d'un post avec suggestions IA
     */
    private function get_post_issues_with_ai($post) {
        $issues = array();
        $seo_analyzer = new Almetal_Seo_Analyzer();
        $analysis = $seo_analyzer->analyze_post($post->ID);
        
        // Meta description manquante ou trop courte
        if (empty($analysis['meta_description']) || strlen($analysis['meta_description']) < 120) {
            $current = $analysis['meta_description'] ?: '';
            $ai_suggestion = '';
            
            if ($this->groq_generator->is_configured()) {
                $ai_suggestion = $this->groq_generator->generate_meta_description(array(
                    'title' => $post->post_title,
                    'content' => $post->post_content,
                    'type' => 'page',
                    'location' => $this->extract_location_from_content($post->post_content)
                ));
            }
            
            $issues[] = array(
                'type' => 'meta_description',
                'title' => 'Meta description manquante ou trop courte',
                'description' => 'La meta description devrait être entre 120 et 160 caractères pour un SEO optimal.',
                'current_value' => $current,
                'severity' => 'high',
                'ai_suggestion' => $ai_suggestion && !is_wp_error($ai_suggestion) ? $ai_suggestion : ''
            );
        }
        
        // Titre trop long ou trop court
        if (strlen($post->post_title) > 60 || strlen($post->post_title) < 30) {
            $ai_suggestion = '';
            
            if ($this->groq_generator->is_configured()) {
                $ai_suggestion = $this->groq_generator->generate_title_suggestion(array(
                    'title' => $post->post_title,
                    'content' => $post->post_content
                ));
            }
            
            $issues[] = array(
                'type' => 'title_length',
                'title' => 'Longueur du titre non optimale',
                'description' => 'Le titre devrait être entre 30 et 60 caractères pour un affichage optimal dans les résultats de recherche.',
                'current_value' => $post->post_title,
                'severity' => 'medium',
                'ai_suggestion' => $ai_suggestion && !is_wp_error($ai_suggestion) ? $ai_suggestion : ''
            );
        }
        
        // Contenu trop court
        $word_count = str_word_count(strip_tags($post->post_content));
        if ($word_count < 300) {
            $ai_suggestion = '';
            
            if ($this->groq_generator->is_configured()) {
                $ai_suggestion = $this->groq_generator->generate_content_improvement(array(
                    'title' => $post->post_title,
                    'content' => $post->post_content,
                    'word_count_needed' => 300 - $word_count,
                    'type' => 'expand'
                ));
            }
            
            $issues[] = array(
                'type' => 'content_length',
                'title' => 'Contenu trop court',
                'description' => "Le contenu contient actuellement {$word_count} mots. Un minimum de 300 mots est recommandé pour un bon référencement.",
                'current_value' => $post->post_content,
                'severity' => 'high',
                'ai_suggestion' => $ai_suggestion && !is_wp_error($ai_suggestion) ? $ai_suggestion : ''
            );
        }
        
        // H1 manquant
        if (!$this->has_h1($post->post_content)) {
            $ai_suggestion = $post->post_title;
            
            $issues[] = array(
                'type' => 'h1_missing',
                'title' => 'Balise H1 manquante',
                'description' => 'Une balise H1 est essentielle pour la structure SEO de votre page.',
                'current_value' => '',
                'severity' => 'high',
                'ai_suggestion' => $ai_suggestion
            );
        }
        
        // H2 manquants
        if (!$this->has_h2($post->post_content)) {
            $ai_suggestion = $this->generate_h2_suggestion($post->post_title, $post->post_content);
            
            $issues[] = array(
                'type' => 'h2_missing',
                'title' => 'Balises H2 manquantes',
                'description' => 'Les balises H2 aident à structurer votre contenu et améliorent le SEO.',
                'current_value' => '',
                'severity' => 'medium',
                'ai_suggestion' => $ai_suggestion
            );
        }
        
        // Images sans alt
        if (isset($analysis['images']['without_alt']) && $analysis['images']['without_alt'] > 0) {
            $issues[] = array(
                'type' => 'images_alt',
                'title' => 'Images sans texte alternatif',
                'description' => "{$analysis['images']['without_alt']} image(s) n'ont pas de texte alternatif (attribut alt).",
                'current_value' => '',
                'severity' => 'medium',
                'ai_suggestion' => 'Ajoutez des textes alternatifs décrivant chaque image pour améliorer l\'accessibilité et le SEO.'
            );
        }
        
        return $issues;
    }
    
    /**
     * Analyser les problèmes d'une taxonomie avec suggestions IA
     */
    private function get_taxonomy_issues_with_ai($term) {
        $issues = array();
        
        // Description manquante ou trop courte
        if (empty($term->description) || strlen($term->description) < 150) {
            $ai_suggestion = '';
            
            if ($this->groq_generator->is_configured()) {
                $ai_suggestion = $this->groq_generator->generate_taxonomy_description(array(
                    'name' => $term->name,
                    'slug' => $term->slug,
                    'description' => $term->description,
                    'taxonomy' => $term->taxonomy
                ));
            }
            
            $issues[] = array(
                'type' => 'taxonomy_description',
                'title' => 'Description de taxonomie trop courte',
                'description' => 'La description devrait être d\'au moins 150 caractères pour un SEO optimal.',
                'current_value' => $term->description,
                'severity' => 'high',
                'ai_suggestion' => $ai_suggestion && !is_wp_error($ai_suggestion) ? $ai_suggestion : ''
            );
        }
        
        return $issues;
    }
    
    /**
     * Générer du contenu IA (version V2)
     */
    public function ajax_generate_ai_content_v2() {
        check_ajax_referer('almetal_seo_improvements', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_die(__('Permission denied'));
        }
        
        $post_id = intval($_POST['post_id']);
        $is_taxonomy = isset($_POST['is_taxonomy']) && $_POST['is_taxonomy'] === 'true';
        $options = isset($_POST['options']) ? $_POST['options'] : array();
        
        $result = array();
        $generator_used = 'Templates (fallback)';
        
        // Récupérer le contenu
        if ($is_taxonomy) {
            $term = get_term($post_id);
            $title = $term->name;
            $content = $term->description;
        } else {
            $post = get_post($post_id);
            $title = $post->post_title;
            $content = $post->post_content;
        }
        
        // Générer la meta description
        if (isset($options['generate_meta']) && $options['generate_meta']) {
            if ($this->groq_generator->is_configured()) {
                $meta_desc = $this->groq_generator->generate_meta_description(array(
                    'title' => $title,
                    'content' => $content,
                    'type' => $is_taxonomy ? 'taxonomy' : 'page',
                    'temperature' => isset($options['temperature']) ? $options['temperature'] : 0.7,
                    'tone' => isset($options['tone']) ? $options['tone'] : 'professional'
                ));
                
                if (!is_wp_error($meta_desc)) {
                    $result['meta_description'] = $meta_desc;
                    $generator_used = 'Groq AI';
                }
            }
            
            if (empty($result['meta_description'])) {
                $result['meta_description'] = $this->generate_fallback_meta($title, $content);
            }
        }
        
        // Améliorer le contenu
        if (isset($options['generate_content']) && $options['generate_content']) {
            if ($this->groq_generator->is_configured()) {
                $improved_content = $this->groq_generator->generate_content_improvement(array(
                    'title' => $title,
                    'content' => $content,
                    'length' => isset($options['length']) ? $options['length'] : 'medium',
                    'temperature' => isset($options['temperature']) ? $options['temperature'] : 0.7,
                    'tone' => isset($options['tone']) ? $options['tone'] : 'professional'
                ));
                
                if (!is_wp_error($improved_content)) {
                    $result['content'] = $improved_content;
                    $generator_used = 'Groq AI';
                }
            }
        }
        
        // Générer le contenu manquant
        if (isset($options['generate_missing']) && $options['generate_missing']) {
            if ($this->groq_generator->is_configured()) {
                $missing_content = $this->generate_missing_content($title, $content, $options);
                
                if (!is_wp_error($missing_content)) {
                    $result['missing_content'] = $missing_content;
                    $generator_used = 'Groq AI';
                }
            }
        }
        
        $result['generator'] = $generator_used;
        
        wp_send_json_success($result);
    }
    
    /**
     * Générer le contenu manquant
     */
    private function generate_missing_content($title, $content, $options) {
        $missing = array();
        
        // Vérifier ce qui manque
        if (!$this->has_h1($content)) {
            $missing[] = "<h1>{$title}</h1>";
        }
        
        if (!$this->has_h2($content)) {
            $missing[] = "<h2>" . $this->generate_h2_suggestion($title, $content) . "</h2>";
            $missing[] = "<p>Contenu additionnel généré pour améliorer la structure SEO.</p>";
        }
        
        if (str_word_count(strip_tags($content)) < 300) {
            $additional = $this->groq_generator->generate_content_improvement(array(
                'title' => $title,
                'content' => $content,
                'word_count_needed' => 300 - str_word_count(strip_tags($content)),
                'type' => 'expand',
                'temperature' => isset($options['temperature']) ? $options['temperature'] : 0.7
            ));
            
            if (!is_wp_error($additional)) {
                $missing[] = $additional;
            }
        }
        
        return implode("\n\n", $missing);
    }
    
    /**
     * Appliquer les améliorations (version V2)
     */
    public function ajax_apply_improvements_v2() {
        check_ajax_referer('almetal_seo_improvements', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_die(__('Permission denied'));
        }
        
        $post_id = intval($_POST['post_id']);
        $is_taxonomy = isset($_POST['is_taxonomy']) && $_POST['is_taxonomy'] === 'true';
        $improvements = isset($_POST['improvements']) ? $_POST['improvements'] : array();
        
        if ($is_taxonomy) {
            $term = get_term($post_id);
            if (!$term || is_wp_error($term)) {
                wp_send_json_error('Terme non trouvé');
            }
            
            // Appliquer les améliorations à la taxonomie
            $args = array();
            foreach ($improvements as $improvement) {
                if ($improvement['type'] === 'taxonomy_description') {
                    $args['description'] = $improvement['value'];
                }
            }
            
            if (!empty($args)) {
                wp_update_term($term->term_id, $term->taxonomy, $args);
            }
        } else {
            $post = get_post($post_id);
            if (!$post) {
                wp_send_json_error('Post non trouvé');
            }
            
            // Appliquer les améliorations au post
            $update_args = array('ID' => $post_id);
            
            foreach ($improvements as $improvement) {
                switch ($improvement['type']) {
                    case 'title_length':
                        $update_args['post_title'] = $improvement['value'];
                        break;
                    case 'content':
                    case 'content_length':
                    case 'missing_content':
                        $update_args['post_content'] = $improvement['value'];
                        break;
                    case 'meta_description':
                        update_post_meta($post_id, '_yoast_wpseo_metadesc', $improvement['value']);
                        update_post_meta($post_id, '_seo_description', $improvement['value']);
                        break;
                }
            }
            
            wp_update_post($update_args);
        }
        
        wp_send_json_success('Améliorations appliquées');
    }
    
    /**
     * Fonctions utilitaires
     */
    private function has_h1($content) {
        return preg_match('/<h1[^>]*>/i', $content);
    }
    
    private function has_h2($content) {
        return preg_match('/<h2[^>]*>/i', $content);
    }
    
    private function extract_location_from_content($content) {
        // Chercher des indices de localisation dans le contenu
        if (preg_match('/\b(\d{5})\b/', $content, $matches)) {
            return $matches[1];
        }
        return '';
    }
    
    private function generate_h2_suggestion($title, $content) {
        // Générer une suggestion de H2 basée sur le titre
        if (strpos(strtolower($title), 'métallier') !== false) {
            return 'Nos services de métallerie';
        } elseif (strpos(strtolower($title), 'réalisation') !== false) {
            return 'Détails de la réalisation';
        } else {
            return 'En savoir plus';
        }
    }
    
    private function generate_fallback_meta($title, $content) {
        $excerpt = wp_trim_words($content, 20, '');
        return sprintf('%s | AL Métallerie & Soudure', $excerpt ?: $title);
    }
    
    private function calculate_seo_score($post_id, $is_taxonomy) {
        $seo_analyzer = new Almetal_Seo_Analyzer();
        if ($is_taxonomy) {
            return $seo_analyzer->analyze_taxonomy($post_id)['score'];
        } else {
            return $seo_analyzer->analyze_post($post_id)['score'];
        }
    }
}

// Initialiser avec le hook init
add_action('init', function() {
    // Uniquement dans l'admin ET si les classes nécessaires sont disponibles
    if (is_admin() && class_exists('Almetal_Analytics')) {
        new Almetal_Seo_Improver_V2();
    }
});
