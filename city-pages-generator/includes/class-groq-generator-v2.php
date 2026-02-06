<?php
/**
 * Générateur Groq V2 - Version simplifiée qui fonctionne
 */

if (!defined('ABSPATH')) {
    exit;
}

class CPG_Groq_Generator_V2 {
    
    private $api_key;
    private $api_url = 'https://api.groq.com/openai/v1/chat/completions';
    
    public function __construct() {
        $this->api_key = get_option('almetal_groq_api_key', '');
    }
    
    /**
     * Génère une page ville complète avec Groq
     */
    public function generate_city_page($city_data) {
        if (empty($this->api_key)) {
            return $this->generate_fallback($city_data);
        }
        
        $city = $city_data['city_name'];
        $department = $city_data['department'];
        $postal_code = $city_data['postal_code'];
        $distance = $city_data['distance_km'];
        $travel_time = $city_data['travel_time'];
        
        $prompt = $this->build_prompt($city_data);
        
        $response = $this->call_groq($prompt);
        
        if (is_wp_error($response)) {
            return $this->generate_fallback($city_data);
        }
        
        // Ajouter les shortcodes
        $content = $response;
        $content .= "\n\n[cpg_city_realisations city=\"{$city}\" count=\"6\"]\n\n";
        $content .= "[cpg_contact_form city=\"{$city}\"]";
        
        return $content;
    }
    
    /**
     * Construit le prompt pour Groq
     */
    private function build_prompt($city_data) {
        $city = $city_data['city_name'];
        $department = $city_data['department'];
        $postal_code = $city_data['postal_code'];
        $distance = $city_data['distance_km'];
        $travel_time = $city_data['travel_time'];
        
        return "Génère une page web complète et optimisée SEO pour un artisan métallier à {$city} ({$postal_code}, {$department}).

CONTEXTE :
- Entreprise : AL Métallerie & Soudure
- Localisation atelier : Peschadoires (à {$distance} km, {$travel_time} de {$city})
- Services : portails, garde-corps, escaliers, grilles, pergolas, verrières, ferronnerie, mobilier
- Zone : tout le {$department}

STRUCTURE OBLIGATOIRE (avec balises HTML) :
1. Titre H1 : \"Votre artisan métallier à {$city}\"
2. Introduction : 2 paragraphes (présentation + intervention rapide)
3. Section H2 : \"Nos services de métallerie à {$city}\"
   - 8 sous-sections avec H3 pour chaque service
   - 2-3 phrases par service
4. Section H2 : \"Pourquoi choisir AL Métallerie & Soudure ?\"
   - 6 arguments avec descriptions
5. Section H2 : \"Zone d'intervention\"
   - Liste des villes à proximité
6. Section H2 : \"Contactez votre métallier à {$city}\"
   - Téléphone : 06 73 33 35 32
   - Email : contact@al-metallerie.fr

STYLE :
- Ton : professionnel mais accessible
- Mots-clés : métallier, serrurier, {$city}, {$department}, sur mesure
- Longueur : 800-1000 mots
- Unique : évite les phrases génériques

Génère UNIQUEMENT le contenu HTML, sans commentaire.";
    }
    
    /**
     * Appelle l'API Groq
     */
    private function call_groq($prompt) {
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
                        'content' => 'Tu es un expert en SEO et rédaction web pour les artisans. Tu génères du contenu unique et optimisé.'
                    ),
                    array(
                        'role' => 'user',
                        'content' => $prompt
                    )
                ),
                'max_tokens' => 2000,
                'temperature' => 0.7,
            )),
            'timeout' => 30,
        ));
        
        if (is_wp_error($response)) {
            return $response;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (isset($data['choices'][0]['message']['content'])) {
            return $data['choices'][0]['message']['content'];
        }
        
        return new WP_Error('groq_error', 'Erreur API Groq');
    }
    
    /**
     * Fallback si Groq échoue
     */
    private function generate_fallback($city_data) {
        $city = $city_data['city_name'];
        $department = $city_data['department'];
        $postal_code = $city_data['postal_code'];
        $distance = $city_data['distance_km'];
        $travel_time = $city_data['travel_time'];
        
        $content = "<h1>Votre artisan métallier à {$city}</h1>";
        $content .= "<p><strong>AL Métallerie & Soudure</strong>, artisan métallier serrurier basé à Peschadoires, intervient à <strong>{$city} ({$postal_code})</strong> et dans tout le <strong>{$department}</strong> pour tous vos projets de métallerie sur mesure.</p>";
        $content .= "<p>📍 <strong>Intervention rapide</strong> : {$city} est à {$distance} km de notre atelier (environ {$travel_time}).</p>";
        
        $content .= "<h2>Nos services de métallerie à {$city}</h2>";
        $services = array('Portails sur mesure', 'Garde-corps et rambardes', 'Escaliers métalliques', 'Grilles de sécurité', 'Pergolas et structures', 'Verrières d\'intérieur', 'Ferronnerie d\'art', 'Mobilier métallique');
        
        foreach ($services as $service) {
            $content .= "<h3>{$service}</h3>";
            $content .= "<p>Fabrication et pose de {$service} à {$city} et environs. Devis gratuit.</p>";
        }
        
        $content .= "<h2>Pourquoi choisir AL Métallerie & Soudure ?</h2>";
        $content .= "<ul>";
        $content .= "<li>✓ Artisan local, intervention rapide</li>";
        $content .= "<li>✓ Fabrication 100% sur mesure</li>";
        $content .= "<li>✓ Devis gratuit sans engagement</li>";
        $content .= "<li>✓ Finitions soignées et durables</li>";
        $content .= "<li>✓ Conseils personnalisés</li>";
        $content .= "<li>✓ Garantie décennale</li>";
        $content .= "</ul>";
        
        $content .= "<h2>Zone d'intervention</h2>";
        $content .= "<p>Nous intervenons dans tout le {$department} et les départements limitrophes.</p>";
        
        $content .= "<h2>Contactez votre métallier à {$city}</h2>";
        $content .= "<p>📞 06 73 33 35 32<br>📧 contact@al-metallerie.fr</p>";
        
        return $content;
    }
}
