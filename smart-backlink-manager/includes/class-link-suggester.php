<?php
/**
 * Link Suggester class for Smart Backlink Manager
 */

if (!defined('ABSPATH')) {
    exit;
}

class SBM_Link_Suggester {
    
    private $custom_keywords;
    private $suggestions_limit;
    
    public function init(): void {
        $this->custom_keywords = json_decode(get_option('sbm_custom_keywords', '[]'), true);
        $this->suggestions_limit = get_option('sbm_suggestions_limit', 5);
        
        // Hook pour Gutenberg
        add_action('rest_api_init', [$this, 'register_rest_routes']);
    }
    
    public function register_rest_routes(): void {
        register_rest_route('smart-backlink-manager/v1', '/suggest-links', [
            'methods' => 'POST',
            'callback' => [$this, 'get_suggestions'],
            'permission_callback' => [$this, 'check_permissions']
        ]);
    }
    
    public function check_permissions(): bool {
        return current_user_can('edit_posts');
    }
    
    public function get_suggestions($request): WP_REST_Response {
        $content = $request->get_param('content');
        $post_id = $request->get_param('post_id');
        
        if (empty($content)) {
            return new WP_REST_Response(['suggestions' => []], 200);
        }
        
        $suggestions = $this->analyze_content_and_suggest($content, $post_id);
        
        return new WP_REST_Response(['suggestions' => $suggestions], 200);
    }
    
    private function analyze_content_and_suggest(string $content, int $current_post_id): array {
        $keywords_found = $this->extract_keywords($content);
        $suggestions = [];
        
        if (empty($keywords_found)) {
            return $suggestions;
        }
        
        // Rechercher les articles/pages pertinents
        $relevant_posts = $this->find_relevant_posts($keywords_found, $current_post_id);
        
        // Limiter le nombre de suggestions
        $relevant_posts = array_slice($relevant_posts, 0, $this->suggestions_limit);
        
        foreach ($relevant_posts as $post) {
            $suggestions[] = [
                'id' => $post->ID,
                'title' => get_the_title($post),
                'url' => get_permalink($post),
                'type' => get_post_type($post),
                'relevance_score' => $post->relevance_score,
                'matched_keywords' => $post->matched_keywords
            ];
        }
        
        return $suggestions;
    }
    
    private function extract_keywords(string $content): array {
        $keywords_found = [];
        $content_lower = strtolower($content);
        
        // Extraire les mots-clés personnalisés
        foreach ($this->custom_keywords as $keyword) {
            $keyword_lower = strtolower($keyword);
            if (strpos($content_lower, $keyword_lower) !== false) {
                $keywords_found[] = $keyword;
            }
        }
        
        // Extraire les mots-clés du contenu (mots de plus de 4 caractères)
        $words = preg_split('/\s+/', strip_tags($content));
        $word_counts = array_count_values($words);
        
        // Filtrer les mots courants et les mots courts
        $stop_words = ['le', 'la', 'les', 'de', 'des', 'du', 'et', 'est', 'en', 'pour', 'avec', 'sur', 'dans', 'une', 'un', 'par', 'que', 'qui', 'ce', 'se', 'son', 'sa', 'ses', 'au', 'aux', 'avec', 'pour', 'dans', 'sur', 'à', 'as', 'a', 'ai', 'avoir', 'être', 'et', 'mais', 'ou', 'donc', 'or', 'ni', 'car', 'vos', 'votre', 'votres', 'nous', 'vous', 'ils', 'elles', 'leur', 'leurs'];
        
        foreach ($word_counts as $word => $count) {
            $word = strtolower($word);
            if (strlen($word) > 4 && !in_array($word, $stop_words) && $count >= 2) {
                $keywords_found[] = $word;
            }
        }
        
        return array_unique($keywords_found);
    }
    
    private function find_relevant_posts(array $keywords, int $exclude_post_id): array {
        $args = [
            'post_type' => ['post', 'page', 'realisation'],
            'post_status' => 'publish',
            'posts_per_page' => 50,
            'post__not_in' => [$exclude_post_id],
            'orderby' => 'relevance',
            'order' => 'DESC'
        ];
        
        // Construire la recherche avec les mots-clés
        if (!empty($keywords)) {
            $args['s'] = implode(' ', array_slice($keywords, 0, 5));
        }
        
        $query = new WP_Query($args);
        $posts = [];
        
        foreach ($query->posts as $post) {
            $post->matched_keywords = [];
            $post->relevance_score = 0;
            
            // Calculer le score de pertinence
            $post_content = strtolower(get_the_title($post) . ' ' . $post->post_content);
            
            foreach ($keywords as $keyword) {
                $keyword_lower = strtolower($keyword);
                $occurrences = substr_count($post_content, $keyword_lower);
                
                if ($occurrences > 0) {
                    $post->matched_keywords[] = $keyword;
                    // Plus de poids si dans le titre
                    if (strpos(strtolower(get_the_title($post)), $keyword_lower) !== false) {
                        $post->relevance_score += $occurrences * 3;
                    } else {
                        $post->relevance_score += $occurrences;
                    }
                }
            }
            
            // Ajouter un bonus pour les mots-clés personnalisés
            foreach ($post->matched_keywords as $keyword) {
                if (in_array($keyword, $this->custom_keywords)) {
                    $post->relevance_score += 2;
                }
            }
            
            // Ajouter un bonus pour les réalisations (priorité métier)
            if ($post->post_type === 'realisation') {
                $post->relevance_score += 1;
            }
            
            if ($post->relevance_score > 0) {
                $posts[] = $post;
            }
        }
        
        // Trier par score de pertinence
        usort($posts, function($a, $b) {
            return $b->relevance_score - $a->relevance_score;
        });
        
        return $posts;
    }
    
    public function get_internal_links_stats(): array {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sbm_internal_links';
        
        $total_links = $wpdb->get_var("SELECT COUNT(*) FROM $table");
        $posts_with_links = $wpdb->get_var("SELECT COUNT(DISTINCT from_post_id) FROM $table");
        $most_linked = $wpdb->get_results("
            SELECT to_post_id, COUNT(*) as link_count 
            FROM $table 
            GROUP BY to_post_id 
            ORDER BY link_count DESC 
            LIMIT 5
        ");
        
        return [
            'total_links' => intval($total_links),
            'posts_with_links' => intval($posts_with_links),
            'most_linked' => $most_linked
        ];
    }
    
    public function add_internal_link($from_post_id, $to_post_id, $anchor_text): bool {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sbm_internal_links';
        
        $result = $wpdb->insert(
            $table,
            [
                'from_post_id' => $from_post_id,
                'to_post_id' => $to_post_id,
                'anchor_text' => $anchor_text,
                'date_added' => current_time('mysql')
            ],
            ['%d', '%d', '%s', '%s']
        );
        
        return $result !== false;
    }
}
