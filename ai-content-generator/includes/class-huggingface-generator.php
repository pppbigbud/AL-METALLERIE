<?php
/**
 * Alternative : Hugging Face API (gratuit)
 */

if (!defined('ABSPATH')) {
    exit;
}

class AICG_HuggingFace_Generator extends AICG_AI_Generator {
    
    private $api_url = 'https://api-inference.huggingface.co/models/mistralai/Mistral-7B-Instruct-v0.1';
    private $api_key;
    
    public function __construct() {
        // Clé API gratuite Hugging Face
        $this->api_key = get_option('aicg_hf_api_key', '');
    }
    
    public function is_available() {
        return !empty($this->api_key);
    }
    
    public function generate_content($prompt, $context = array()) {
        // Préparer le prompt
        $full_prompt = $this->build_prompt($prompt, $context);
        
        // Préparer la requête
        $data = array(
            'inputs' => $full_prompt,
            'parameters' => array(
                'max_new_tokens' => 500,
                'temperature' => 0.7,
                'return_full_text' => false
            )
        );
        
        $response = wp_remote_post($this->api_url, array(
            'timeout' => 30,
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->api_key,
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode($data)
        ));
        
        if (is_wp_error($response)) {
            return new WP_Error('hf_error', 'Erreur API Hugging Face: ' . $response->get_error_message());
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (isset($body[0]['generated_text'])) {
            return $body[0]['generated_text'];
        }
        
        return new WP_Error('hf_error', 'Réponse invalide de Hugging Face');
    }
}
