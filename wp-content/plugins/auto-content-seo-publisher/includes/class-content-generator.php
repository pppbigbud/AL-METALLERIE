<?php
/**
 * Classe de génération de contenu
 */
class ACSP_Content_Generator {
    
    /**
     * Templates d'articles
     */
    private $templates;
    
    /**
     * Base de connaissances AL Métallerie
     */
    private $knowledge_base;
    
    /**
     * Constructeur
     */
    public function __construct() {
        $this->templates = new ACSP_Article_Templates();
        $this->knowledge_base = new ACSP_Knowledge_Base();
    }
    
    /**
     * Générer et publier un article
     */
    public function generate_and_publish_article() {
        global $wpdb;
        
        try {
            // 1. Choisir un sujet
            $topic = $this->select_next_topic();
            if (!$topic) {
                error_log('ACSP: Aucun sujet disponible');
                return false;
            }
            
            // 2. Choisir un mot-clé principal
            $keyword = $this->select_primary_keyword($topic);
            
            // 3. Générer le contenu
            $content = $this->generate_content($topic, $keyword);
            
            // 4. Optimiser SEO
            $seo_optimizer = new ACSP_SEO_Optimizer();
            $content = $seo_optimizer->optimize_content($content, $keyword);
            
            // 5. Gérer l'image
            $image_manager = new ACSP_Image_Manager();
            $image_id = $image_manager->get_or_create_image($topic, $keyword);
            
            // 6. Créer l'article WordPress
            $post_id = $this->create_wordpress_post($content, $image_id);
            
            if ($post_id) {
                // 7. Sauvegarder dans la base de données
                $this->save_generated_article($content, $post_id, $topic);
                
                // 8. Marquer le sujet comme utilisé
                $this->mark_topic_as_used($topic);
                
                // 9. Envoyer notification
                $this->send_notification($post_id);
                
                return $post_id;
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log('ACSP Erreur génération article: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Sélectionner le prochain sujet
     */
    private function select_next_topic() {
        global $wpdb;
        
        $table_topics = $wpdb->prefix . 'acsp_topics';
        
        // Priorité aux sujets saisonniers
        $current_season = $this->get_current_season();
        
        $topic = $wpdb->get_row($wpdb->prepare("
            SELECT * FROM $table_topics 
            WHERE used = FALSE 
            AND (season IS NULL OR season = %s)
            ORDER BY priority DESC, RAND() 
            LIMIT 1
        ", $current_season));
        
        return $topic;
    }
    
    /**
     * Sélectionner un mot-clé principal
     */
    private function select_primary_keyword($topic) {
        global $wpdb;
        
        $table_keywords = $wpdb->prefix . 'acsp_keywords';
        
        // Choisir un mot-clé pertinent selon le type de sujet
        $category_map = [
            'guide' => ['portails', 'garde_corps', 'escaliers', 'general'],
            'trend' => ['garde_corps', 'pergolas', 'mobilier', 'general'],
            'tutorial' => ['soudure', 'entretien', 'formations', 'motorisation'],
            'case_study' => ['realisations', 'general', 'materiaux'],
            'faq' => ['general', 'portails', 'garde_corps'],
            'comparison' => ['materiaux', 'portails', 'motorisation'],
            'inspiration' => ['mobilier', 'escaliers', 'pergolas']
        ];
        
        $categories = $category_map[$topic->type] ?? ['general'];
        
        $keyword = $wpdb->get_row($wpdb->prepare("
            SELECT * FROM $table_keywords 
            WHERE category IN (" . implode(',', array_fill(0, count($categories), '%s')) . ")
            ORDER BY times_used ASC, RAND() 
            LIMIT 1
        ", ...$categories));
        
        return $keyword;
    }
    
    /**
     * Générer le contenu
     */
    private function generate_content($topic, $keyword) {
        // Obtenir le template selon le type
        $template = $this->templates->get_template($topic->type);
        
        // Variables de substitution
        $variables = [
            '{COMPANY}' => 'AL Métallerie & Soudure',
            '{LOCATION}' => $this->get_random_location(),
            '{KEYWORD}' => $keyword->keyword,
            '{KEYWORD_PLURAL}' => $this->pluralize($keyword->keyword),
            '{SERVICE}' => $this->get_random_service(),
            '{MATERIAL}' => $this->get_random_material(),
            '{PHONE}' => '06 73 33 35 32',
            '{EMAIL}' => 'contact@al-metallerie.fr',
            '{YEAR}' => date('Y'),
            '{SEASON}' => $this->get_current_season_name(),
            '{REALISATION}' => $this->get_random_realisation()
        ];
        
        // Substituer les variables
        $content = [];
        foreach ($template as $key => $value) {
            $content[$key] = str_replace(array_keys($variables), array_values($variables), $value);
        }
        
        return $content;
    }
    
    /**
     * Créer l'article WordPress
     */
    private function create_wordpress_post($content, $image_id = null) {
        $post_status = get_option('acsp_post_status', 'draft');
        
        $post_data = [
            'post_title' => $content['title'],
            'post_content' => $content['content'],
            'post_excerpt' => $content['excerpt'],
            'post_status' => $post_status,
            'post_author' => 1,
            'post_type' => 'post',
            'post_category' => [get_cat_ID('Blog') ?: 1],
            'meta_input' => [
                '_yoast_wpseo_title' => $content['title'],
                '_yoast_wpseo_metadesc' => $content['meta_description'],
                '_yoast_wpseo_focuskw' => $content['keyword'],
                'acsp_generated' => true,
                'acsp_type' => $content['type'],
                'acsp_keywords' => json_encode($content['keywords'])
            ]
        ];
        
        $post_id = wp_insert_post($post_data);
        
        if ($post_id && $image_id) {
            set_post_thumbnail($post_id, $image_id);
        }
        
        return $post_id;
    }
    
    /**
     * Sauvegarder l'article généré
     */
    private function save_generated_article($content, $post_id, $topic) {
        global $wpdb;
        
        $table_articles = $wpdb->prefix . 'acsp_articles';
        
        $wpdb->insert(
            $table_articles,
            [
                'title' => $content['title'],
                'slug' => $content['slug'],
                'content' => $content['content'],
                'excerpt' => $content['excerpt'],
                'keywords' => json_encode($content['keywords']),
                'post_id' => $post_id,
                'type' => $topic->type,
                'status' => 'published',
                'published_date' => current_time('mysql'),
                'seo_score' => $content['seo_score'] ?? 0,
                'image_url' => $content['image_url'] ?? null,
                'created_at' => current_time('mysql')
            ]
        );
    }
    
    /**
     * Marquer le sujet comme utilisé
     */
    private function mark_topic_as_used($topic) {
        global $wpdb;
        
        $table_topics = $wpdb->prefix . 'acsp_topics';
        
        $wpdb->update(
            $table_topics,
            [
                'used' => true,
                'last_used' => current_time('mysql')
            ],
            ['id' => $topic->id]
        );
    }
    
    /**
     * Envoyer une notification
     */
    private function send_notification($post_id) {
        if (!get_option('acsp_notify_on_publish', 1)) {
            return;
        }
        
        $email = get_option('acsp_notification_email');
        $post = get_post($post_id);
        
        $subject = sprintf('[AL Métallerie] Nouvel article généré : %s', $post->post_title);
        $message = sprintf(
            "Un nouvel article a été généré et publié :\n\nTitre : %s\nLien : %s\n\nCordialement,\nAuto Content SEO Publisher",
            $post->post_title,
            get_permalink($post_id)
        );
        
        wp_mail($email, $subject, $message);
    }
    
    /**
     * Obtenir la saison actuelle
     */
    private function get_current_season() {
        $month = date('n');
        
        if ($month >= 12 || $month <= 2) {
            return 'winter';
        } elseif ($month >= 3 && $month <= 5) {
            return 'spring';
        } elseif ($month >= 6 && $month <= 8) {
            return 'summer';
        } else {
            return 'autumn';
        }
    }
    
    /**
     * Obtenir le nom de la saison
     */
    private function get_current_season_name() {
        $season = $this->get_current_season();
        
        $names = [
            'winter' => 'hiver',
            'spring' => 'printemps',
            'summer' => 'été',
            'autumn' => 'automne'
        ];
        
        return $names[$season] ?? '';
    }
    
    /**
     * Obtenir une localisation aléatoire
     */
    private function get_random_location() {
        $locations = json_decode(get_option('acsp_locations', '[]'), true);
        return $locations[array_rand($locations)] ?? 'Thiers';
    }
    
    /**
     * Obtenir un service aléatoire
     */
    private function get_random_service() {
        $services = [
            'portails sur mesure',
            'garde-corps design',
            'escaliers métalliques',
            'pergolas et abris',
            'vérandes atelier',
            'mobilier métallique',
            'soudure professionnelle',
            'formations soudure'
        ];
        
        return $services[array_rand($services)];
    }
    
    /**
     * Obtenir un matériau aléatoire
     */
    private function get_random_material() {
        $materials = [
            'acier',
            'acier thermolaqué',
            'acier inoxydable',
            'inox 304',
            'inox 316',
            'aluminium',
            'fer forgé'
        ];
        
        return $materials[array_rand($materials)];
    }
    
    /**
     * Obtenir une réalisation aléatoire
     */
    private function get_random_realisation() {
        $realisations = [
            'portail coulissant à Clermont-Ferrand',
            'garde-corps sur mesure à Riom',
            'escalier hélicoïdal à Issoire',
            'pergola bioclimatique à Vichy',
            'verrière d\'atelier à Thiers',
            'mobilier design pour restaurant'
        ];
        
        return $realisations[array_rand($realisations)];
    }
    
    /**
     * Pluriel simple
     */
    private function pluralize($word) {
        // Simplification - pourrait être amélioré
        if (substr($word, -1) === 'x' || substr($word, -1) === 's' || substr($word, -1) === 'z') {
            return $word;
        }
        
        return $word . 's';
    }
}
