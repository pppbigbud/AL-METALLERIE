<?php
/**
 * SEO Improver V2 - Version finale avec vraies données et suggestions optimisées (sans Groq)
 */

if (!defined('ABSPATH')) {
    exit;
}

class Almetal_Seo_Improver_V2_Final {
    
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
                'suggested_value' => $this->generate_smart_title($title)
            );
        } elseif (strlen($meta_title) > 60) {
            $issues[] = array(
                'type' => 'meta_title',
                'message' => 'Titre trop long (> 60 caractères), risque de troncature dans Google',
                'current_value' => $meta_title . ' (' . strlen($meta_title) . ' caractères)',
                'suggested_value' => $this->smart_title_shorten($meta_title, $title)
            );
        }
        
        // Analyser la meta description
        if (empty($meta_desc)) {
            $issues[] = array(
                'type' => 'meta_description',
                'message' => 'Meta description manquante',
                'current_value' => '',
                'suggested_value' => $this->generate_smart_description($title)
            );
        } elseif (strlen($meta_desc) > 160) {
            $issues[] = array(
                'type' => 'meta_description',
                'message' => 'Meta description trop longue (> 160 caractères)',
                'current_value' => $meta_desc . ' (' . strlen($meta_desc) . ' caractères)',
                'suggested_value' => $this->smart_description_shorten($meta_desc)
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
                    'suggested_value' => $this->generate_smart_content($title, $word_count)
                );
            } elseif ($word_count < 600) {
                $issues[] = array(
                    'type' => 'content_length',
                    'message' => 'Contenu moyen. Visez 600+ mots pour un meilleur référencement.',
                    'current_value' => $word_count . ' mots',
                    'suggested_value' => $this->generate_smart_content($title, $word_count)
                );
            }
        }
        
        return $issues;
    }
    
    private function generate_smart_title($title) {
        // Variations intelligentes de titres
        $patterns = array(
            "Métallerie {ville} | AL Métallerie - Artisans qualifiés",
            "{ville} : Métallerie & Ferronnerie | AL Métallerie",
            "AL Métallerie {ville} | Services de Métallerie",
            "Métallier {ville} | AL Métallerie - Devis Gratuit",
            "Travaux de Métallerie à {ville} | AL Métallerie"
        );
        
        $pattern = $patterns[array_rand($patterns)];
        return str_replace('{ville}', $title, $pattern);
    }
    
    private function smart_title_shorten($title, $fallback) {
        // Garder les éléments importants
        if (strpos($title, 'AL Métallerie') !== false) {
            return substr($title, 0, 57) . '...';
        }
        
        // Essayer de garder le nom de la ville
        $parts = explode(' ', $title);
        $short = '';
        foreach ($parts as $part) {
            if (strlen($short . $part) <= 57) {
                $short .= $part . ' ';
            } else {
                break;
            }
        }
        
        return trim($short) . '...';
    }
    
    private function generate_smart_description($title) {
        // Variations intelligentes de descriptions
        $patterns = array(
            "Découvrez nos services de métallerie à {ville}. Artisans qualifiés pour ferronnerie, escaliers, portails. Devis gratuit !",
            "AL Métallerie à {ville} : spécialiste en ferronnerie d'art, escaliers sur mesure, portails et garde-corps. Contactez-nous !",
            "Métallerie à {ville} : réalisations sur mesure, fabrication française. Garantie 10 ans. Devis gratuit sous 24h.",
            "Votre métallier à {ville} pour tous vos projets : portails, escaliers, verrières, charpentes. Qualité & savoir-faire."
        );
        
        $pattern = $patterns[array_rand($patterns)];
        return str_replace('{ville}', $title, $pattern);
    }
    
    private function smart_description_shorten($description) {
        // Garder les phrases complètes importantes
        $sentences = preg_split('/[.!?]+/', $description, -1, PREG_SPLIT_NO_EMPTY);
        $short = '';
        
        foreach ($sentences as $sentence) {
            $sentence = trim($sentence);
            if (strlen($short . $sentence . '.') <= 157) {
                $short .= $sentence . '. ';
            } else {
                break;
            }
        }
        
        return trim($short) . '...';
    }
    
    private function generate_smart_content($title, $current_words) {
        $needed = max(300, 600) - $current_words;
        
        $content = "Pour optimiser votre contenu, ajoutez environ {$needed} mots :\n\n";
        
        // Suggestions basées sur le type de contenu
        $suggestions = array(
            "Présentez l'histoire de votre entreprise de métallerie à {$title}",
            "Décrivez vos techniques de fabrication traditionnelles",
            "Listez les matériaux que vous utilisez (acier, inox, aluminium, fer forgé)",
            "Montrez des photos de vos réalisations récentes dans la région",
            "Expliquez vos processus de fabrication et les délais",
            "Ajoutez un témoignage client de {$title}",
            "Décrivez vos certifications et qualifications",
            "Présentez votre équipe d'artisans métalliers",
            "Expliquez vos garanties et service après-vente",
            "Décrivez vos zones d'intervention autour de {$title}"
        );
        
        shuffle($suggestions);
        $selected = array_slice($suggestions, 0, 5);
        
        foreach ($selected as $suggestion) {
            $content .= "• " . $suggestion . "\n";
        }
        
        return $content;
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
            'meta_title' => $this->generate_smart_title($title),
            'meta_description' => $this->generate_smart_description($title),
            'content' => $this->generate_full_content($title)
        ));
    }
    
    private function generate_full_content($title) {
        ob_start();
        ?>
<h2>Métallerie à <?php echo esc_html($title); ?></h2>
<p>AL Métallerie est votre entreprise artisanale spécialisée dans la conception, la fabrication et la pose de tous ouvrages en métal à <?php echo esc_html($title); ?> et ses environs. Depuis plus de 10 ans, nous mettons notre savoir-faire au service de particuliers et professionnels.</p>

<h3>Nos Services de Métallerie</h3>
<p>Nous maîtrisons tous les aspects de la métallerie pour répondre à vos besoins :</p>
<ul>
<li><strong>Ferronnerie d'art</strong> : Portails d'entrée, grilles de protection, garde-corps, balcons, rampes d'escalier</li>
<li><strong>Escaliers sur mesure</strong> : Escaliers intérieurs, extérieurs, colimaçons, hélicoïdaux en métal, bois ou mixtes</li>
<li><strong>Portails et portillons</strong> : Portails coulissants, battants, automatisés, en aluminium, acier ou fer forgé</li>
<li><strong>Charpentes métalliques</strong> : Structure pour pergolas, carports, abris de jardin, ossatures</li>
<li><strong>Bardages et couvertures</strong> : Protection et esthétique pour vos façades et toitures</li>
</ul>

<h3>Pourquoi Choisir AL Métallerie à <?php echo esc_html($title); ?> ?</h3>
<p>Notre engagement : qualité, précision et satisfaction client. Chaque projet est unique et bénéficie de toute notre attention.</p>
<ul>
<li>✓ Artisans qualifiés et expérimentés</li>
<li>✓ Matériaux de haute qualité (acier, inox, aluminium)</li>
<li>✓ Fabrication 100% française dans notre atelier</li>
<li>✓ Garantie décennale sur tous nos ouvrages</li>
<li>✓ Devis gratuit et personnalisé</li>
<li>✓ Intervention rapide dans tout le département</li>
</ul>

<h3>Réalisations à <?php echo esc_html($title); ?></h3>
<p>Nous avons réalisé de nombreux projets dans votre secteur : portails d'entrées résidentielles, escaliers modernes pour bureaux, garde-corps sécurisés pour établissements publics, ferronnerie décorative pour bâtiments historiques.</p>

<p><strong>Contactez-nous dès aujourd'hui pour étudier votre projet !</strong><br>
Téléphone : 04 73 XX XX XX<br>
Email : contact@al-metallerie.fr</p>
        <?php
        return ob_get_clean();
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
        new Almetal_Seo_Improver_V2_Final();
    }
});
