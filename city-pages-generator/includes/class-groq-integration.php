<?php
/**
 * Intégration Groq AI pour City Pages Generator
 * 
 * @package CityPagesGenerator
 */

if (!defined('ABSPATH')) {
    exit;
}

class CPG_Groq_Integration {
    
    private static $instance = null;
    private $api_key;
    private $api_url = 'https://api.groq.com/openai/v1/chat/completions';
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Utiliser la même clé API que le plugin Analytics
        $this->api_key = get_option('almetal_groq_api_key', '');
        
        // Debug
        if (empty($this->api_key)) {
            error_log('CPG: Clé API Groq non trouvée dans almetal_groq_api_key');
        }
    }
    
    /**
     * Vérifie si Groq est configuré
     */
    public function is_configured() {
        return !empty($this->api_key);
    }
    
    /**
     * Génère du contenu avec Groq
     */
    public function generate_content($prompt, $temperature = 0.7, $max_tokens = 1000) {
        if (!$this->is_configured()) {
            return new WP_Error('groq_not_configured', 'Groq API non configurée');
        }
        
        $response = wp_remote_post($this->api_url, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->api_key,
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode(array(
                'model' => 'mixtral-8x7b-32768',
                'messages' => array(
                    array(
                        'role' => 'system',
                        'content' => 'Tu es un artisan expert en métallerie à Thiers, France. Tu rédiges du contenu web optimisé pour le SEO local. Ton ton est professionnel mais accessible. Tu utilises des variations pour éviter le contenu dupliqué. Tu réponds en français uniquement.'
                    ),
                    array(
                        'role' => 'user',
                        'content' => $prompt
                    )
                ),
                'max_tokens' => $max_tokens,
                'temperature' => $temperature,
            )),
            'timeout' => 30,
        ));
        
        if (is_wp_error($response)) {
            return $response;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (isset($data['choices'][0]['message']['content'])) {
            return trim($data['choices'][0]['message']['content']);
        }
        
        return new WP_Error('groq_api_error', 'Erreur API Groq');
    }
    
    /**
     * Génère l'introduction d'une page ville
     */
    public function generate_city_intro($city_data, $temperature = 0.7) {
        $city = $city_data['city_name'];
        $department = $city_data['department'];
        $postal_code = $city_data['postal_code'];
        $distance = $city_data['distance_km'];
        $travel_time = $city_data['travel_time'];
        $specifics = $city_data['local_specifics'];
        
        $prompt = "Génère une introduction unique et optimisée SEO pour une page de métallerie à {$city} ({$postal_code}, {$department}).
        
        Informations à inclure :
        - Artisan métallier basé à Peschadoires (à {$distance} km, {$travel_time} de {$city})
        - Entreprise : AL Métallerie & Soudure
        - Services : portails, garde-corps, escaliers, ferronnerie sur mesure
        - Intervention rapide sur {$city} et environs
        
        Structure :
        - 1 paragraphe d'introduction (3-4 lignes)
        - 1 paragraphe sur les services spécifiques à {$city}
        
        Style : Professionnel mais chaleureux, optimisé pour le référencement local.";
        
        return $this->generate_content($prompt, $temperature, 500);
    }
    
    /**
     * Génère la description d'un service pour une ville
     */
    public function generate_service_description($service_name, $city, $temperature = 0.7) {
        $prompt = "Génère une description unique et optimisée SEO pour le service '{$service_name}' à {$city}.
        
        Informations :
        - Artisan métallier à {$city}
        - Service de {$service_name} sur mesure
        - Fabrication locale et pose professionnelle
        
        Style : 2-3 phrases maximum, convaincant et local. Inclure des mots-clés locaux.";
        
        return $this->generate_content($prompt, $temperature, 300);
    }
    
    /**
     * Génère la FAQ pour une ville
     */
    public function generate_city_faq($city_data, $temperature = 0.7) {
        $city = $city_data['city_name'];
        $department = $city_data['department'];
        
        $prompt = "Génère 5 questions-réponses uniques pour une FAQ sur la métallerie à {$city} ({$department}).
        
        Types de questions :
        - 1 question sur les délais d'intervention
        - 1 question sur les matériaux utilisés
        - 1 question sur les tarifs/devis
        - 1 question sur une spécificité locale
        - 1 question sur la garantie
        
        Format :
        Q : [Question] ?
        R : [Réponse détaillée de 2-3 lignes]
        
        Style : Réponses d'expert artisan, local à {$city}.";
        
        return $this->generate_content($prompt, $temperature, 800);
    }
    
    /**
     * Génère la description d'une taxonomie pour une ville
     */
    public function generate_taxonomy_description($term_name, $city, $temperature = 0.7) {
        $prompt = "Génère une description unique optimisée SEO pour la catégorie '{$term_name}' à {$city}.
        
        Informations :
        - Spécialiste {$term_name} à {$city}
        - Fabrication sur mesure
        - Matériaux de qualité
        
        Style : 3-4 phrases maximum, persuasif et local. Inclure des bénéfices pour les habitants de {$city}.";
        
        return $this->generate_content($prompt, $temperature, 300);
    }
    
    /**
     * Génère la section "Pourquoi nous choisir"
     */
    public function generate_why_us_section($city, $temperature = 0.7) {
        $prompt = "Génère une section 'Pourquoi nous choisir' pour un artisan métallier à {$city}.
        
        Structure en 6 points :
        1. Savoir-faire artisanal local
        2. Matériaux premium
        3. Devis gratuit
        4. Délais rapides
        5. Garantie décennale
        6. Service après-vente
        
        Format : Titre + 1 phrase d'explication par point.
        Style : Confiant et professionnel, adapté à {$city}.";
        
        return $this->generate_content($prompt, $temperature, 600);
    }
    
    /**
     * Génère la section zone d'intervention
     */
    public function generate_zone_section($city, $nearby_cities, $temperature = 0.7) {
        $cities_list = !empty($nearby_cities) ? implode(', ', array_slice($nearby_cities, 0, 10)) : '';
        
        $prompt = "Génère une section 'Zone d\'intervention' pour un artisan métallier basé près de {$city}.
        
        Villes à mentionner : {$cities_list}
        
        Structure :
        - 1 phrase sur le rayon d'intervention
        - Liste des villes principales
        - 1 phrase sur la réactivité
        
        Style : Informatif et local.";
        
        return $this->generate_content($prompt, $temperature, 400);
    }
}
