<?php
/**
 * Classe SEO Improver - Améliorations automatiques SEO
 * 
 * @package Almetal_Analytics
 * @since 1.2.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class Almetal_Analytics_SEO_Improver {
    
    /**
     * Instance unique
     */
    private static $instance = null;
    
    /**
     * Types d'améliorations possibles
     */
    private $improvement_types = array(
        'meta_description' => 'Générer/optimiser meta description',
        'title_length' => 'Optimiser longueur du titre',
        'alt_tags' => 'Ajouter attributs ALT manquants',
        'heading_structure' => 'Optimiser structure des titres',
        'internal_links' => 'Ajouter liens internes pertinents'
    );
    
    /**
     * Obtenir l'instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Analyser et améliorer une page
     */
    public function improve_page($post_id, $improvements = array(), $create_draft = true) {
        $post = get_post($post_id);
        if (!$post) {
            return array('error' => 'Post not found');
        }
        
        // Sauvegarder la révision actuelle
        wp_save_post_revision($post_id);
        
        $improvements_applied = array();
        $modified_content = $post->post_content;
        $modified_title = $post->post_title;
        $modified_excerpt = $post->post_excerpt;
        
        // 1. Améliorer la meta description
        if (in_array('meta_description', $improvements) || empty($improvements)) {
            $meta_desc = $this->improve_meta_description($post);
            if ($meta_desc) {
                $modified_excerpt = $meta_desc;
                $improvements_applied[] = 'Meta description générée';
            }
        }
        
        // 2. Optimiser la longueur du titre
        if (in_array('title_length', $improvements) || empty($improvements)) {
            $optimized_title = $this->optimize_title_length($post->post_title);
            if ($optimized_title !== $post->post_title) {
                $modified_title = $optimized_title;
                $improvements_applied[] = 'Titre optimisé';
            }
        }
        
        // 3. Ajouter les attributs ALT manquants
        if (in_array('alt_tags', $improvements) || empty($improvements)) {
            $content_with_alt = $this->add_missing_alt_tags($modified_content);
            if ($content_with_alt !== $modified_content) {
                $modified_content = $content_with_alt;
                $improvements_applied[] = 'Attributs ALT ajoutés';
            }
        }
        
        // 4. Optimiser la structure des titres
        if (in_array('heading_structure', $improvements) || empty($improvements)) {
            $content_with_headings = $this->optimize_heading_structure($modified_content);
            if ($content_with_headings !== $modified_content) {
                $modified_content = $content_with_headings;
                $improvements_applied[] = 'Structure des titres optimisée';
            }
        }
        
        // Créer le brouillon ou appliquer directement
        if ($create_draft) {
            return $this->create_improvement_draft($post_id, $modified_title, $modified_excerpt, $modified_content, $improvements_applied);
        } else {
            return $this->apply_improvements_directly($post_id, $modified_title, $modified_excerpt, $modified_content, $improvements_applied);
        }
    }
    
    /**
     * Améliorer la meta description
     */
    private function improve_meta_description($post) {
        // Si déjà définie et de bonne longueur, ne rien faire
        if ($post->post_excerpt && strlen($post->post_excerpt) >= 150 && strlen($post->post_excerpt) <= 160) {
            return false;
        }
        
        // Extraire le contenu pertinent
        $content = strip_tags($post->post_content);
        $content = preg_replace('/\s+/', ' ', $content);
        
        // Prendre la première phrase significative
        $sentences = preg_split('/[.!?]+/', $content, -1, PREG_SPLIT_NO_EMPTY);
        
        foreach ($sentences as $sentence) {
            $sentence = trim($sentence);
            if (strlen($sentence) >= 140 && strlen($sentence) <= 160) {
                return ucfirst($sentence);
            }
        }
        
        // Sinon, créer une description à partir des premières phrases
        $description = '';
        foreach ($sentences as $sentence) {
            $sentence = trim($sentence);
            if (strlen($description . $sentence) <= 160) {
                $description .= $sentence . '. ';
            } else {
                break;
            }
        }
        
        return substr(trim($description), 0, 160);
    }
    
    /**
     * Optimiser la longueur du titre
     */
    private function optimize_title_length($title) {
        $length = strlen($title);
        
        if ($length >= 50 && $length <= 60) {
            return $title; // Déjà optimal
        }
        
        if ($length > 60) {
            // Raccourcir intelligemment
            $words = explode(' ', $title);
            $shortened = '';
            
            foreach ($words as $word) {
                if (strlen($shortened . $word) <= 57) {
                    $shortened .= $word . ' ';
                } else {
                    break;
                }
            }
            
            return rtrim($shortened) . '...';
        }
        
        if ($length < 50) {
            // Ajouter des mots-clés pertinents
            $site_title = get_bloginfo('name');
            if (strlen($title . ' | ' . $site_title) <= 60) {
                return $title . ' | ' . $site_title;
            }
            
            // Sinon ajouter une localisation si disponible
            $location = get_option('almetal_business_location', 'Auvergne-Rhône-Alpes');
            if (strlen($title . ' - ' . $location) <= 60) {
                return $title . ' - ' . $location;
            }
        }
        
        return $title;
    }
    
    /**
     * Ajouter les attributs ALT manquants
     */
    private function add_missing_alt_tags($content) {
        // Trouver toutes les images sans ALT
        $pattern = '/<img([^>]*?)(?!.*alt=)([^>]*?)>/i';
        
        return preg_replace_callback($pattern, function($matches) {
            $img_tag = $matches[0];
            
            // Extraire le src pour générer un ALT
            if (preg_match('/src=["\']([^"\']*)["\']/', $img_tag, $src_match)) {
                $src = $src_match[1];
                $filename = pathinfo($src, PATHINFO_FILENAME);
                
                // Nettoyer et formater le nom de fichier en texte lisible
                $alt_text = str_replace(array('-', '_'), ' ', $filename);
                $alt_text = ucwords(strtolower($alt_text));
                
                // Ajouter l'attribut ALT
                return str_replace('<img', '<img alt="' . esc_attr($alt_text) . '"', $img_tag);
            }
            
            return $img_tag;
        }, $content);
    }
    
    /**
     * Optimiser la structure des titres
     */
    private function optimize_heading_structure($content) {
        // S'assurer qu'il y a un seul H1
        $h1_count = substr_count($content, '<h1');
        
        if ($h1_count === 0) {
            // Ajouter un H1 au début si le titre du post peut être utilisé
            $post_title = get_the_title();
            $content = '<h1>' . $post_title . '</h1>' . "\n\n" . $content;
        } elseif ($h1_count > 1) {
            // Convertir les H1 supplémentaires en H2
            $content = preg_replace('/<h1([^>]*)>(.*?)<\/h1>/', '<h2$1>$2</h2>', $content, $h1_count - 1);
        }
        
        return $content;
    }
    
    /**
     * Créer un brouillon avec les améliorations
     */
    private function create_improvement_draft($original_post_id, $title, $excerpt, $content, $improvements) {
        $original_post = get_post($original_post_id);
        
        $draft_data = array(
            'post_title' => $title . ' (Amélioré)',
            'post_content' => $content,
            'post_excerpt' => $excerpt,
            'post_status' => 'draft',
            'post_type' => $original_post->post_type,
            'post_author' => get_current_user_id(),
            'post_parent' => $original_post_id,
            'meta_input' => array(
                '_almetal_seo_improvements' => implode(', ', $improvements),
                '_almetal_original_post_id' => $original_post_id,
                '_almetal_improvement_date' => current_time('mysql')
            )
        );
        
        $draft_id = wp_insert_post($draft_data);
        
        if ($draft_id && !is_wp_error($draft_id)) {
            // Copier les termes de taxonomie
            $taxonomies = get_object_taxonomies($original_post->post_type);
            foreach ($taxonomies as $taxonomy) {
                $terms = wp_get_object_terms($original_post_id, $taxonomy);
                $term_ids = wp_list_pluck($terms, 'term_id');
                wp_set_object_terms($draft_id, $term_ids, $taxonomy);
            }
            
            // Copier les meta fields
            $meta_keys = get_post_custom_keys($original_post_id);
            if ($meta_keys) {
                foreach ($meta_keys as $key) {
                    if (strpos($key, '_') === 0) {
                        $values = get_post_meta($original_post_id, $key);
                        foreach ($values as $value) {
                            add_post_meta($draft_id, $key, $value);
                        }
                    }
                }
            }
        }
        
        return array(
            'success' => true,
            'draft_id' => $draft_id,
            'improvements' => $improvements,
            'edit_url' => get_edit_post_link($draft_id)
        );
    }
    
    /**
     * Appliquer directement les améliorations
     */
    private function apply_improvements_directly($post_id, $title, $excerpt, $content, $improvements) {
        $update_data = array(
            'ID' => $post_id,
            'post_title' => $title,
            'post_excerpt' => $excerpt,
            'post_content' => $content
        );
        
        $result = wp_update_post($update_data);
        
        if ($result && !is_wp_error($result)) {
            // Logger les améliorations
            update_post_meta($post_id, '_almetal_last_seo_improvements', implode(', ', $improvements));
            update_post_meta($post_id, '_almetal_last_improvement_date', current_time('mysql'));
            
            return array(
                'success' => true,
                'improvements' => $improvements,
                'post_url' => get_permalink($post_id)
            );
        }
        
        return array('error' => 'Failed to apply improvements');
    }
    
    /**
     * Obtenir les améliorations suggérées pour une page
     */
    public function get_suggested_improvements($post_id) {
        $analysis = Almetal_Analytics_SEO::analyze_page($post_id);
        $suggestions = array();
        
        if (isset($analysis['checks'])) {
            // Meta description manquante
            if ($analysis['checks']['meta_description']['status'] === 'error') {
                $suggestions[] = array(
                    'type' => 'meta_description',
                    'description' => 'Générer une meta description optimisée',
                    'priority' => 'high'
                );
            }
            
            // Titre trop long/court
            if ($analysis['checks']['title']['status'] === 'warning') {
                $suggestions[] = array(
                    'type' => 'title_length',
                    'description' => 'Optimiser la longueur du titre (50-60 caractères)',
                    'priority' => 'medium'
                );
            }
            
            // Images sans ALT
            if (isset($analysis['checks']['images']['missing_alt']) && $analysis['checks']['images']['missing_alt'] > 0) {
                $suggestions[] = array(
                    'type' => 'alt_tags',
                    'description' => sprintf('Ajouter %d attribut(s) ALT manquant(s)', $analysis['checks']['images']['missing_alt']),
                    'priority' => 'high'
                );
            }
        }
        
        return $suggestions;
    }
}
