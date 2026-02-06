<?php
/**
 * Templates et prompts pour la génération de contenu
 */

if (!defined('ABSPATH')) {
    exit;
}

class AICG_Content_Templates {
    
    private static $instance = null;
    private $variations;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->init_variations();
    }
    
    /**
     * Initialiser les variations pour éviter le duplicate content
     */
    private function init_variations() {
        $this->variations = array(
            'introductions' => array(
                'Découvrez',
                'Explorez',
                'Plongez dans l\'univers de',
                'Laissez-vous séduire par',
                'Venez explorer',
                'Appréciez la qualité de',
                'Savourez l\'excellence de',
                'Profitez de l\'expertise de',
                'Faites confiance à',
                'Choisissez le professionnalisme de'
            ),
            'qualifiers' => array(
                'exceptionnel',
                'remarquable',
                'impressionnant',
                'unique',
                'sur mesure',
                'personnalisé',
                'artisanal',
                'professionnel',
                'haut de gamme',
                'innovant'
            ),
            'benefits' => array(
                'alliant esthétique et durabilité',
                'alliant design et robustesse',
                'conciliant beauté et solidité',
                'mariant élégance et résistance',
                'associant charme et pérennité',
                'fusionnant style et solidité',
                'unissant raffinement et durabilité',
                'combinant grâce et longévité',
                'harmonisant esthétisme et solidité',
                'joignant élégance et endurance'
            ),
            'locations' => array(
                'à Thiers et ses environs',
                'dans le Puy-de-Dôme',
                'en Auvergne',
                'dans la région Clermontoise',
                'sur le département 63',
                'au cœur de l\'Auvergne',
                'en région Auvergne-Rhône-Alpes',
                'dans le centre de la France',
                'à proximité de Clermont-Ferrand',
                'en Limagne'
            ),
            'conclusions' => array(
                'N\'hésitez pas à nous contacter pour votre projet.',
                'Contactez-nous pour donner vie à vos idées.',
                'Notre équipe est à votre disposition.',
                'Faites appel à notre savoir-faire.',
                'Confiez-nous la réalisation de vos projets.',
                'Nous sommes à votre écoute.',
                'Discutons ensemble de votre projet.',
                'Votre satisfaction est notre priorité.',
                'Réalisons ensemble vos ambitions.',
                'Laissez-nous transformer vos idées en réalité.'
            )
        );
    }
    
    /**
     * Obtenir une variation aléatoire
     */
    private function get_variation($type) {
        $variations = $this->variations[$type];
        return $variations[array_rand($variations)];
    }
    
    /**
     * Générer un prompt pour une réalisation
     */
    public function get_realisation_prompt($data) {
        $intro = $this->get_variation('introductions');
        $qualifier = $this->get_variation('qualifiers');
        $benefit = $this->get_variation('benefits');
        $location = $this->get_variation('locations');
        $conclusion = $this->get_variation('conclusions');
        
        $seed = rand(0, 1000);
        
        $prompt = "Génère une description unique et captivante pour une réalisation en métallerie. ";
        $prompt .= "Utilise une approche originale avec la seed {$seed}. ";
        $prompt .= "Structure: 1) Titre accrocheur, 2) Description du projet (150-200 mots), ";
        $prompt .= "3) Détails techniques (100 mots), 4) Bénéfices client (100 mots).\n\n";
        $prompt .= "Informations: ";
        $prompt .= "- Type de projet: " . ($data['type'] ?? 'structure métallique') . "\n";
        $prompt .= "- Matériaux: " . ($data['materials'] ?? 'acier, fer') . "\n";
        $prompt .= "- Client: " . ($data['client'] ?? 'particulier/professionnel') . "\n";
        $prompt .= "- Lieu: {$location}\n";
        $prompt .= "- Date: " . ($data['date'] ?? date('Y')) . "\n\n";
        $prompt .= "Style: {$intro} ce travail {$qualifier} {$benefit}. ";
        $prompt .= "Sois créatif et évite les clichés. {$conclusion}";
        
        return $prompt;
    }
    
    /**
     * Générer un prompt pour une page ville
     */
    public function get_city_page_prompt($data) {
        $intro = $this->get_variation('introductions');
        $qualifier = $this->get_variation('qualifiers');
        $location = $data['city'] ?? 'ville';
        $dept = $data['department'] ?? 'département';
        
        $seed = rand(0, 1000);
        
        $prompt = "Crée un contenu unique pour la page de présentation de nos services de métallerie à {$location} ({$dept}). ";
        $prompt .= "Seed: {$seed}. Évite toute duplication avec d'autres pages villes.\n\n";
        $prompt .= "Structure demandée:\n";
        $prompt .= "1. Titre H1 optimisé: Métallier Serrurier à {$location}\n";
        $prompt .= "2. Introduction (100 mots): Présentation d'AL-Metallerie et de son expertise locale\n";
        $prompt .= "3. Services proposés à {$location} (200 mots): Adaptés aux besoins locaux\n";
        $prompt .= "4. Réalisations locales (150 mots): Exemples pertinents pour la ville\n";
        $prompt .= "5. Pourquoi nous choisir (150 mots): Arguments différenciants\n";
        $prompt .= "6. Zone d'intervention (100 mots): Communes autour de {$location}\n";
        $prompt .= "7. Appel à l'action (50 mots)\n\n";
        $prompt .= "Informations: ";
        $prompt .= "- Population: " . ($data['population'] ?? 'N/C') . "\n";
        $prompt .= "- Particularités locales: " . ($data['specifics'] ?? 'architecture locale, besoins spécifiques') . "\n";
        $prompt .= "- Services adaptés: " . ($data['services'] ?? 'soudure, ferronnerie, portails, escaliers') . "\n\n";
        $prompt .= "IMPORTANT: Sois spécifique à {$location}, mentionne des éléments locaux si possible. ";
        $prompt .= "Le contenu doit être 100% unique et différent des autres villes.";
        
        return $prompt;
    }
    
    /**
     * Générer un prompt pour meta description SEO
     */
    public function get_meta_description_prompt($data) {
        $page_type = $data['type'] ?? 'page';
        $subject = $data['subject'] ?? 'nos services de métallerie';
        $location = $data['location'] ?? 'Thiers';
        
        $variations = array(
            "AL-Metallerie Soudure, votre expert en métallerie à {$location}. ",
            "Découvrez les services de métallerie professionnelle d'AL-Metallerie à {$location}. ",
            "Spécialiste de la soudure et la métallerie à {$location}, AL-Metallerie vous accompagne. ",
            "Pour tous vos travaux de métallerie à {$location}, faites confiance à AL-Metallerie. ",
            "AL-Metallerie Soudure: solutions de métallerie sur mesure à {$location}. "
        );
        
        $intro = $variations[array_rand($variations)];
        $seed = rand(0, 1000);
        
        $prompt = "Génère une meta description SEO unique (155-160 caractères) avec seed {$seed}. ";
        $prompt .= "Commence par: \"{$intro}\" ";
        $prompt .= "Page: {$page_type}, Sujet: {$subject}. ";
        $prompt .= "Inclus des mots-clés pertinents et un appel à l'action implicite. ";
        $prompt .= "Sois concis mais percutant. Optimisé pour le clic.";
        
        return $prompt;
    }
    
    /**
     * Générer un prompt pour améliorer le contenu existant
     */
    public function get_content_improvement_prompt($data) {
        $content = $data['content'] ?? '';
        $target_length = $data['target_length'] ?? 300;
        $keywords = $data['keywords'] ?? array();
        
        $seed = rand(0, 1000);
        
        $prompt = "Améliore et enrichis ce texte avec seed {$seed}. ";
        $prompt .= "Texte original: \"{$content}\"\n\n";
        $prompt .= "Instructions:\n";
        $prompt .= "1. Conserve le sens principal mais reformule avec des mots différents\n";
        $prompt .= "2. Ajoute {$target_length} caractères de contenu pertinent\n";
        $prompt .= "3. Intègre naturellement ces mots-clés: " . implode(', ', $keywords) . "\n";
        $prompt .= "4. Améliore la structure avec des paragraphes aérés\n";
        $prompt .= "5. Ajoute une touche professionnelle et locale\n";
        $prompt .= "6. Termine par un appel à l'action subtil\n\n";
        $prompt .= "Le résultat doit être 100% unique et optimisé SEO.";
        
        return $prompt;
    }
    
    /**
     * Générer un prompt pour les témoignages
     */
    public function get_testimonial_prompt($data) {
        $service = $data['service'] ?? 'nos services de métallerie';
        $location = $data['location'] ?? 'Thiers';
        $client_type = $data['client_type'] ?? 'particulier';
        
        $names = array('M. Dubois', 'Mme Lefevre', 'M. et Mme Martin', 'M. Bernard', 'Mme Petit');
        $professions = array('artisan', 'commerçant', 'retraité', 'chef d\'entreprise', 'agriculteur');
        
        $name = $names[array_rand($names)];
        $profession = $professions[array_rand($professions)];
        $seed = rand(0, 1000);
        
        $prompt = "Rédige un témoignage authentique et crédible avec seed {$seed}. ";
        $prompt .= "Client: {$name}, {$profession} de {$location}. ";
        $prompt .= "Service: {$service}. ";
        $prompt .= "Le témoignage doit:\n";
        $prompt .= "1. Être spontané et naturel (80-120 mots)\n";
        $prompt .= "2. Mentionner un problème résolu\n";
        $prompt .= "3. Louer le professionnalisme et la qualité\n";
        $prompt .= "4. Recommander clairement nos services\n";
        $prompt .= "5. Inclure des détails spécifiques au projet\n\n";
        $prompt .= "Évite les superlatifs excessifs. Sois réaliste et convaincant.";
        
        return $prompt;
    }
    
    /**
     * Obtenir tous les templates disponibles
     */
    public function get_available_templates() {
        return array(
            'realisation' => 'Description de réalisation',
            'city_page' => 'Page ville',
            'meta_description' => 'Meta description SEO',
            'content_improvement' => 'Amélioration de contenu',
            'testimonial' => 'Témoignage client'
        );
    }
}
