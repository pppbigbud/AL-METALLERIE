<?php
/**
 * Générateur de contenu avec Groq API
 * 
 * @package Almetal_Analytics
 */

if (!defined('ABSPATH')) {
    exit;
}

class Almetal_Groq_Generator {
    
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
        // Récupérer la clé API depuis les options
        $this->api_key = get_option('almetal_groq_api_key', '');
    }
    
    /**
     * Génère du contenu avec Groq
     */
    public function generate_content($type, $data = array()) {
        if (empty($this->api_key)) {
            return $this->fallback_generation($type, $data);
        }
        
        $prompt = $this->build_prompt($type, $data);
        
        $response = wp_remote_post($this->api_url, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->api_key,
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode(array(
                'model' => 'mixtral-8x7b-32768', // Modèle rapide et efficace
                'messages' => array(
                    array(
                        'role' => 'system',
                        'content' => 'Vous êtes un expert en SEO et rédaction web pour une entreprise de métallerie à Thiers, France. Générez du contenu professionnel, optimisé pour le référencement local.'
                    ),
                    array(
                        'role' => 'user',
                        'content' => $prompt
                    )
                ),
                'max_tokens' => $this->get_max_tokens($type),
                'temperature' => isset($data['temperature']) ? floatval($data['temperature']) : 0.7,
            )),
            'timeout' => 30,
        ));
        
        if (is_wp_error($response)) {
            return $this->fallback_generation($type, $data);
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (isset($data['choices'][0]['message']['content'])) {
            return trim($data['choices'][0]['message']['content']);
        }
        
        return $this->fallback_generation($type, $data);
    }
    
    /**
     * Construit le prompt selon le type
     */
    private function build_prompt($type, $data) {
        switch ($type) {
            case 'meta_description':
                return $this->build_meta_prompt($data);
            case 'content':
                return $this->build_content_prompt($data);
            default:
                return '';
        }
    }
    
    /**
     * Prompt pour méta description
     */
    private function build_meta_prompt($data) {
        $subject = isset($data['subject']) ? $data['subject'] : 'nos services de métallerie';
        $location = isset($data['location']) ? $data['location'] : 'Thiers';
        $type = isset($data['type']) ? $data['type'] : 'page';
        
        return "Génère une méta description SEO optimisée (max 160 caractères) pour :
- Sujet : {$subject}
- Localisation : {$location}
- Type : {$type}
- Entreprise : Métallerie professionnelle

La méta description doit être attractive, inclure des mots-clés locaux et un appel à l'action.";
    }
    
    /**
     * Prompt pour contenu
     */
    private function build_content_prompt($data) {
        $subject = isset($data['subject']) ? $data['subject'] : 'nos services';
        $tone = isset($data['tone']) ? $data['tone'] : 'professional';
        $length = isset($data['length']) ? $data['length'] : 'medium';
        
        $tone_instructions = array(
            'professional' => 'Utilise un ton professionnel et expert',
            'friendly' => 'Utilise un ton amical et accessible',
            'technical' => 'Utilise un ton technique et précis'
        );
        
        $length_instructions = array(
            'short' => 'environ 100 mots',
            'medium' => 'environ 200 mots',
            'long' => 'environ 300 mots'
        );
        
        return "Génère un contenu de {$length_instructions[$length]} sur : {$subject}

Instructions :
- {$tone_instructions[$tone]}
- Optimisé pour le SEO local
- Inclut des bénéfices concrets
- Structure claire avec paragraphes
- Adapté pour une entreprise de métallerie à Thiers";
    }
    
    /**
     * Nombre de tokens selon le type
     */
    private function get_max_tokens($type) {
        switch ($type) {
            case 'meta_description':
                return 100;
            case 'content':
                return 500;
            default:
                return 300;
        }
    }
    
    /**
     * Génération de secours si Groq échoue
     */
    private function fallback_generation($type, $data) {
        // Utilise le générateur simple en backup
        require_once ALMETAL_ANALYTICS_PATH . 'includes/class-simple-content-generator.php';
        $fallback = Almetal_Simple_Content_Generator::get_instance();
        return $fallback->generate_content($type, $data);
    }
    
    /**
     * Vérifie si l'API Groq est configurée
     */
    public function is_configured() {
        return !empty($this->api_key);
    }
}
