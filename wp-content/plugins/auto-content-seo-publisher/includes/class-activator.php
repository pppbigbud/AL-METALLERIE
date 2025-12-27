<?php
/**
 * Classe d'activation du plugin
 */
class ACSP_Activator {
    
    /**
     * Activer le plugin
     */
    public function activate() {
        $this->create_tables();
        $this->create_default_options();
        $this->create_default_data();
    }
    
    /**
     * Créer les tables du plugin
     */
    private function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Table des articles générés
        $table_articles = $wpdb->prefix . 'acsp_articles';
        $sql_articles = "CREATE TABLE IF NOT EXISTS $table_articles (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            title varchar(255) NOT NULL,
            slug varchar(255) NOT NULL,
            content longtext NOT NULL,
            excerpt text DEFAULT NULL,
            keywords text DEFAULT NULL,
            post_id bigint(20) unsigned DEFAULT NULL,
            type varchar(50) DEFAULT 'guide',
            status enum('scheduled','published','failed') DEFAULT 'scheduled',
            scheduled_date datetime DEFAULT NULL,
            published_date datetime DEFAULT NULL,
            seo_score int DEFAULT 0,
            image_url varchar(500) DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY slug (slug),
            KEY post_id (post_id),
            KEY status (status),
            KEY scheduled_date (scheduled_date),
            KEY type (type)
        ) $charset_collate;";
        
        // Table des mots-clés utilisés
        $table_keywords = $wpdb->prefix . 'acsp_keywords';
        $sql_keywords = "CREATE TABLE IF NOT EXISTS $table_keywords (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            keyword varchar(255) NOT NULL,
            search_volume int DEFAULT 0,
            difficulty enum('easy','medium','hard') DEFAULT 'medium',
            last_used datetime DEFAULT NULL,
            times_used int DEFAULT 0,
            category varchar(100) DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY keyword (keyword),
            KEY category (category),
            KEY last_used (last_used)
        ) $charset_collate;";
        
        // Table des sujets traités
        $table_topics = $wpdb->prefix . 'acsp_topics';
        $sql_topics = "CREATE TABLE IF NOT EXISTS $table_topics (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            topic varchar(255) NOT NULL,
            type varchar(50) NOT NULL,
            used boolean DEFAULT FALSE,
            last_used datetime DEFAULT NULL,
            priority int DEFAULT 5,
            season varchar(20) DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY type (type),
            KEY used (used),
            KEY priority (priority),
            KEY season (season)
        ) $charset_collate;";
        
        // Table des images
        $table_images = $wpdb->prefix . 'acsp_images';
        $sql_images = "CREATE TABLE IF NOT EXISTS $table_images (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            url varchar(500) NOT NULL,
            source varchar(100) DEFAULT 'unsplash',
            alt_text varchar(255) DEFAULT NULL,
            used_count int DEFAULT 0,
            category varchar(100) DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY source (source),
            KEY category (category)
        ) $charset_collate;";
        
        // Exécuter les requêtes
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_articles);
        dbDelta($sql_keywords);
        dbDelta($sql_topics);
        dbDelta($sql_images);
    }
    
    /**
     * Créer les options par défaut
     */
    private function create_default_options() {
        // Options de planification
        add_option('acsp_enable_auto_generation', 1);
        add_option('acsp_frequency', 'weekly');
        add_option('acsp_publish_day', 'monday');
        add_option('acsp_publish_time', '08:00');
        add_option('acsp_post_status', 'draft');
        
        // Options SEO
        add_option('acsp_min_word_count', 1000);
        add_option('acsp_max_word_count', 1500);
        add_option('acsp_min_seo_score', 70);
        add_option('acsp_primary_keywords', json_encode([
            'portail acier thiers',
            'garde corps metallique',
            'escalier sur mesure',
            'pergola acier',
            'formation soudure',
            'serrurerie puy de dome',
            'metallerie clermont ferrand'
        ]));
        add_option('acsp_locations', json_encode([
            'Thiers', 'Clermont-Ferrand', 'Riom', 'Issoire', 'Vichy',
            'Peschadoires', 'Lezoux', 'Courpière', 'Ambert', 'Cournon-d\'Auvergne'
        ]));
        
        // Options de contenu
        add_option('acsp_tone', 'professional');
        add_option('acsp_enabled_types', json_encode([
            'guide', 'trend', 'tutorial', 'case_study', 'faq', 'comparison', 'inspiration'
        ]));
        add_option('acsp_author_name', 'Équipe AL Métallerie');
        add_option('acsp_signature_enabled', 1);
        
        // Options d'images
        add_option('acsp_image_source', 'unsplash');
        add_option('acsp_image_width', 1200);
        add_option('acsp_image_height', 630);
        
        // Options de notifications
        add_option('acsp_notification_email', 'contact@al-metallerie.fr');
        add_option('acsp_notify_on_publish', 1);
        add_option('acsp_notify_on_error', 1);
    }
    
    /**
     * Créer les données par défaut
     */
    private function create_default_data() {
        global $wpdb;
        
        // Insérer les mots-clés par défaut
        $table_keywords = $wpdb->prefix . 'acsp_keywords';
        $default_keywords = [
            ['portail acier', 'portails', 'easy'],
            ['portail coulissant', 'portails', 'medium'],
            ['portail battant', 'portails', 'easy'],
            ['garde corps metallique', 'garde_corps', 'medium'],
            ['garde corps escalier', 'garde_corps', 'medium'],
            ['garde corps terrasse', 'garde_corps', 'hard'],
            ['escalier metallique', 'escaliers', 'medium'],
            ['escalier droit', 'escaliers', 'easy'],
            ['escalier colimaçon', 'escaliers', 'hard'],
            ['pergola acier', 'pergolas', 'medium'],
            ['pergola bioclimatique', 'pergolas', 'hard'],
            ['verriere atelier', 'verrieres', 'medium'],
            ['mobilier metallique', 'mobilier', 'easy'],
            ['table acier', 'mobilier', 'medium'],
            ['soudure tig', 'soudure', 'medium'],
            ['soudure mig', 'soudure', 'easy'],
            ['formation soudure', 'formations', 'hard'],
            ['serrurerie thiers', 'serrurerie', 'easy'],
            ['metallerie puy de dome', 'general', 'medium'],
            ['acier thermolaque', 'materiaux', 'easy'],
            ['inox 304', 'materiaux', 'medium'],
            ['aluminium', 'materiaux', 'easy'],
            ['portail motorisé', 'motorisation', 'hard'],
            ['automatisme portail', 'motorisation', 'medium']
        ];
        
        foreach ($default_keywords as $keyword) {
            $wpdb->insert(
                $table_keywords,
                [
                    'keyword' => $keyword[0],
                    'category' => $keyword[1],
                    'difficulty' => $keyword[2],
                    'created_at' => current_time('mysql')
                ]
            );
        }
        
        // Insérer les sujets par défaut
        $table_topics = $wpdb->prefix . 'acsp_topics';
        $default_topics = [
            ['Comment choisir son portail en acier ?', 'guide', 10, null],
            ['Les tendances garde-corps 2025', 'trend', 9, null],
            ['Entretenir sa pergola en acier', 'tutorial', 8, null],
            ['Réalisation escalier sur mesure', 'case_study', 7, null],
            ['Acier vs Inox : quel choix ?', 'comparison', 6, null],
            ['Les bases de la soudure TIG', 'tutorial', 8, null],
            ['5 questions sur les garde-corps', 'faq', 5, null],
            ['10 idées de mobilier métallique', 'inspiration', 6, null],
            ['Préparer sa métallerie pour l\'hiver', 'tutorial', 7, 'winter'],
            ['Portails et sécurité', 'guide', 9, null],
            ['Motorisation de portail : guide complet', 'guide', 8, null],
            ['L\'acier thermolaqué : avantages', 'guide', 6, null],
            ['Escalier design : inspiration', 'inspiration', 7, null],
            ['Formation soudure pour débutants', 'tutorial', 10, null],
            ['AL Métallerie au salon habitat', 'case_study', 5, null],
            ['Choisir son serrurier à Thiers', 'guide', 8, null],
            ['Pergola : ombrage et élégance', 'trend', 7, 'summer'],
            ['Barbecue métallique sur mesure', 'inspiration', 6, 'summer'],
            ['Protection anti-gel pour métallerie', 'tutorial', 7, 'winter'],
            ['Réussir ses soudures', 'tutorial', 9, null]
        ];
        
        foreach ($default_topics as $topic) {
            $wpdb->insert(
                $table_topics,
                [
                    'topic' => $topic[0],
                    'type' => $topic[1],
                    'priority' => $topic[2],
                    'season' => $topic[3],
                    'created_at' => current_time('mysql')
                ]
            );
        }
        
        // Insérer quelques images par défaut
        $table_images = $wpdb->prefix . 'acsp_images';
        $default_images = [
            ['https://images.unsplash.com/photo-1581094794329-c8112a89af12?q=80&w=1920', 'unsplash', 'Portail en acier moderne', 'portails'],
            ['https://images.unsplash.com/photo-1629553844753-0f4a1c1b4c5a?q=80&w=1920', 'unsplash', 'Garde-corps design', 'garde_corps'],
            ['https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?q=80&w=1920', 'unsplash', 'Escalier métallique', 'escaliers'],
            ['https://images.unsplash.com/photo-1581094794329-c8112a89af12?q=80&w=1920', 'unsplash', 'Soudure professionnelle', 'soudure'],
            ['https://images.unsplash.com/photo-1541888946425-d81bb19240f5?q=80&w=1920', 'unsplash', 'Pergola en acier', 'pergolas']
        ];
        
        foreach ($default_images as $image) {
            $wpdb->insert(
                $table_images,
                [
                    'url' => $image[0],
                    'source' => $image[1],
                    'alt_text' => $image[2],
                    'category' => $image[3],
                    'created_at' => current_time('mysql')
                ]
            );
        }
    }
}
