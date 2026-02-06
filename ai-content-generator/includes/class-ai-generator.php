<?php
/**
 * Classe principale pour la communication avec Ollama
 */

if (!defined('ABSPATH')) {
    exit;
}

class AICG_AI_Generator {
    
    private static $instance = null;
    private $ollama_url;
    private $model;
    private $temperature;
    private $max_tokens;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->ollama_url = get_option('aicg_ollama_url', 'http://localhost:11434');
        $this->model = get_option('aicg_default_model', 'llama3.1:8b');
        $this->temperature = floatval(get_option('aicg_temperature', '0.7'));
        $this->max_tokens = intval(get_option('aicg_max_tokens', '2000'));
    }
    
    /**
     * Vérifier si Ollama est disponible
     */
    public function is_ollama_available() {
        $response = wp_remote_get($this->ollama_url . '/api/tags', array(
            'timeout' => 5,
            'headers' => array('Content-Type' => 'application/json')
        ));
        
        return !is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200;
    }
    
    /**
     * Lister les modèles disponibles
     */
    public function get_available_models() {
        $response = wp_remote_get($this->ollama_url . '/api/tags', array(
            'timeout' => 10,
            'headers' => array('Content-Type' => 'application/json')
        ));
        
        if (is_wp_error($response)) {
            return array();
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        $models = array();
        
        if (isset($body['models'])) {
            foreach ($body['models'] as $model) {
                $models[] = $model['name'];
            }
        }
        
        return $models;
    }
    
    /**
     * Générer du contenu avec Ollama ou fallback
     */
    public function generate_content($prompt, $context = array()) {
        // Si Ollama n'est pas disponible, utiliser le générateur simple
        if (!$this->is_ollama_available()) {
            $simple_generator = AICG_Simple_Generator::get_instance();
            
            // Détecter le type depuis le prompt
            if (strpos($prompt, 'réalisation') !== false) {
                return $simple_generator->generate_content('realisation', $context);
            } elseif (strpos($prompt, 'ville') !== false) {
                return $simple_generator->generate_content('city_page', $context);
            } elseif (strpos($prompt, 'meta description') !== false) {
                return $simple_generator->generate_content('meta_description', $context);
            } else {
                return $simple_generator->generate_content('general', $context);
            }
        }
        
        // Vérifier le cache d'abord
        $cache_hash = md5($prompt . serialize($context) . $this->model);
        $cached = $this->get_cached_content($cache_hash);
        if ($cached) {
            return $cached;
        }
        
        // Construire le prompt complet avec contexte
        $full_prompt = $this->build_prompt($prompt, $context);
        
        // Préparer la requête
        $request_body = array(
            'model' => $this->model,
            'prompt' => $full_prompt,
            'stream' => false,
            'options' => array(
                'temperature' => $this->temperature,
                'num_predict' => $this->max_tokens
            )
        );
        
        // Envoyer la requête
        $response = wp_remote_post($this->ollama_url . '/api/generate', array(
            'timeout' => 30,
            'headers' => array('Content-Type' => 'application/json'),
            'body' => json_encode($request_body)
        ));
        
        if (is_wp_error($response)) {
            return new WP_Error('ollama_error', 'Erreur de communication avec Ollama: ' . $response->get_error_message());
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (!isset($body['response'])) {
            return new WP_Error('ollama_error', 'Réponse invalide de Ollama');
        }
        
        $generated_content = $body['response'];
        
        // Mettre en cache
        $this->cache_content($cache_hash, $prompt, $generated_content);
        
        return $generated_content;
    }
    
    /**
     * Construire le prompt avec contexte
     */
    private function build_prompt($prompt, $context) {
        $system_prompt = "Tu es un expert en rédaction web SEO pour l'entreprise AL-Metallerie Soudure, une entreprise de métallerie située à Thiers, France. ";
        $system_prompt .= "Ton style est professionnel, engageant et optimisé pour le référencement. ";
        $system_prompt .= "Tu dois générer du contenu unique, jamais identique, en variant les tournures de phrases, le vocabulaire et la structure. ";
        $system_prompt .= "IMPORTANT: Ne répète jamais exactement les mêmes phrases. Sois créatif mais reste professionnel.\n\n";
        
        // Ajouter le contexte spécifique
        if (!empty($context)) {
            $system_prompt .= "CONTEXTE SPÉCIFIQUE:\n";
            foreach ($context as $key => $value) {
                $system_prompt .= "- $key: $value\n";
            }
            $system_prompt .= "\n";
        }
        
        return $system_prompt . $prompt;
    }
    
    /**
     * Mettre en cache le contenu généré
     */
    private function cache_content($hash, $prompt, $content) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aicg_content_cache';
        
        $wpdb->insert(
            $table_name,
            array(
                'content_hash' => $hash,
                'content_type' => $this->detect_content_type($prompt),
                'generated_content' => $content,
                'model_used' => $this->model
            ),
            array('%s', '%s', '%s', '%s')
        );
    }
    
    /**
     * Récupérer du contenu mis en cache
     */
    private function get_cached_content($hash) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aicg_content_cache';
        
        $cached = $wpdb->get_var($wpdb->prepare(
            "SELECT generated_content FROM $table_name WHERE content_hash = %s",
            $hash
        ));
        
        return $cached ? $cached : false;
    }
    
    /**
     * Détecter le type de contenu
     */
    private function detect_content_type($prompt) {
        if (strpos($prompt, 'réalisation') !== false || strpos($prompt, 'projet') !== false) {
            return 'realisation';
        } elseif (strpos($prompt, 'ville') !== false || strpos($prompt, 'local') !== false) {
            return 'city_page';
        } elseif (strpos($prompt, 'description') !== false || strpos($prompt, 'meta') !== false) {
            return 'seo_meta';
        } else {
            return 'general';
        }
    }
    
    /**
     * Obtenir des statistiques d'utilisation
     */
    public function get_usage_stats() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aicg_content_cache';
        
        $stats = array(
            'total_generated' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name"),
            'by_type' => $wpdb->get_results("SELECT content_type, COUNT(*) as count FROM $table_name GROUP BY content_type"),
            'last_generated' => $wpdb->get_var("SELECT created_at FROM $table_name ORDER BY created_at DESC LIMIT 1")
        );
        
        return $stats;
    }
}
