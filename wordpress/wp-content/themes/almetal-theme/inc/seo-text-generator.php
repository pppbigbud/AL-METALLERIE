<?php
/**
 * Générateur de Texte SEO avec Hugging Face
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

class ALMetal_SEO_Text_Generator {
    
    private $huggingface_api_key;
    private $api_url = 'https://api-inference.huggingface.co/models/mistralai/Mistral-7B-Instruct-v0.2';
    
    public function __construct() {
        $this->huggingface_api_key = get_option('almetal_huggingface_api_key', '');
    }
    
    /**
     * Générer tous les textes (SEO + réseaux sociaux)
     */
    public function generate_texts($data) {
        // Nettoyer le titre (enlever "Brouillon auto", etc.)
        if (isset($data['title'])) {
            $data['title'] = $this->clean_title($data['title']);
        }
        
        $texts = array();
        
        // Générer le texte SEO principal
        $texts['seo'] = $this->generate_seo_text($data);
        
        // Générer les textes pour les réseaux sociaux
        $texts['facebook'] = $this->generate_facebook_text($data);
        $texts['instagram'] = $this->generate_instagram_text($data);
        $texts['linkedin'] = $this->generate_linkedin_text($data);
        
        return $texts;
    }
    
    /**
     * Nettoyer le titre (enlever "Brouillon auto", etc.)
     */
    private function clean_title($title) {
        // Enlever "Brouillon auto"
        $title = str_replace('Brouillon auto', '', $title);
        
        // Enlever "Auto Draft"
        $title = str_replace('Auto Draft', '', $title);
        
        // Enlever les espaces multiples
        $title = preg_replace('/\s+/', ' ', $title);
        
        // Trim
        $title = trim($title);
        
        // Si le titre est vide après nettoyage, utiliser un placeholder
        if (empty($title)) {
            $title = 'Nouvelle réalisation';
        }
        
        return $title;
    }
    
    /**
     * Générer le texte SEO principal (compatible Yoast)
     */
    private function generate_seo_text($data) {
        // Si l'API Hugging Face n'est pas configurée, utiliser un template
        if (empty($this->huggingface_api_key)) {
            return $this->generate_seo_template($data);
        }
        
        // Préparer le prompt pour Hugging Face
        $prompt = $this->build_seo_prompt($data);
        
        // Appeler l'API Hugging Face
        $response = $this->call_huggingface_api($prompt);
        
        if ($response) {
            return $response;
        }
        
        // Fallback sur le template si l'API échoue
        return $this->generate_seo_template($data);
    }
    
    /**
     * Générer le texte pour Facebook
     */
    private function generate_facebook_text($data) {
        if (empty($this->huggingface_api_key)) {
            return $this->generate_facebook_template($data);
        }
        
        $prompt = $this->build_facebook_prompt($data);
        $response = $this->call_huggingface_api($prompt);
        
        return $response ? $response : $this->generate_facebook_template($data);
    }
    
    /**
     * Générer le texte pour Instagram
     */
    private function generate_instagram_text($data) {
        if (empty($this->huggingface_api_key)) {
            return $this->generate_instagram_template($data);
        }
        
        $prompt = $this->build_instagram_prompt($data);
        $response = $this->call_huggingface_api($prompt);
        
        return $response ? $response : $this->generate_instagram_template($data);
    }
    
    /**
     * Générer le texte pour LinkedIn
     */
    private function generate_linkedin_text($data) {
        if (empty($this->huggingface_api_key)) {
            return $this->generate_linkedin_template($data);
        }
        
        $prompt = $this->build_linkedin_prompt($data);
        $response = $this->call_huggingface_api($prompt);
        
        return $response ? $response : $this->generate_linkedin_template($data);
    }
    
    /**
     * Construire le prompt SEO pour Hugging Face
     */
    private function build_seo_prompt($data) {
        $type_names = !empty($data['types']) ? implode(', ', wp_list_pluck($data['types'], 'name')) : 'métallerie';
        $lieu = !empty($data['lieu']) ? $data['lieu'] : 'Clermont-Ferrand';
        $date = !empty($data['date']) ? date_i18n('F Y', strtotime($data['date'])) : date_i18n('F Y');
        
        $prompt = "Écris une description SEO optimisée pour une réalisation de métallerie. 

Informations :
- Titre : {$data['title']}
- Type : {$type_names}
- Lieu : {$lieu}
- Date : {$date}";
        
        // Type de client
        if (!empty($data['client_type'])) {
            $client_label = ($data['client_type'] === 'professionnel') ? 'Client professionnel' : 'Client particulier';
            $prompt .= "\n- Type de client : {$client_label}";
            if ($data['client_type'] === 'professionnel' && !empty($data['client_nom'])) {
                $prompt .= " ({$data['client_nom']})";
            }
        }
        
        // Matière
        if (!empty($data['matiere'])) {
            $prompt .= "\n- Matière : {$data['matiere']}";
        }
        
        // Peinture
        if (!empty($data['peinture'])) {
            $prompt .= "\n- Finition peinture : {$data['peinture']}";
        }
        
        // Pose
        if (!empty($data['pose']) && $data['pose'] === '1') {
            $prompt .= "\n- Pose réalisée par AL Métallerie : Oui";
        }
        
        if (!empty($data['duree'])) {
            $prompt .= "\n- Durée : {$data['duree']}";
        }
        
        $prompt .= "\n\nLa description doit :
- Faire entre 150 et 160 caractères (optimal pour Yoast SEO)
- Inclure les mots-clés : métallerie, {$type_names}, {$lieu}";
        
        if (!empty($data['matiere'])) {
            $prompt .= ", {$data['matiere']}";
        }
        
        $prompt .= "
- Être engageante et professionnelle
- Mentionner AL Métallerie
- Ne pas utiliser de guillemets

Écris uniquement la description, sans introduction ni conclusion.";
        
        return $prompt;
    }
    
    /**
     * Construire le prompt Facebook
     */
    private function build_facebook_prompt($data) {
        $type_names = !empty($data['types']) ? implode(', ', wp_list_pluck($data['types'], 'name')) : 'métallerie';
        $lieu = !empty($data['lieu']) ? $data['lieu'] : 'Clermont-Ferrand';
        
        $prompt = "Écris un post Facebook engageant pour une réalisation de métallerie.

Informations :
- Titre : {$data['title']}
- Type : {$type_names}
- Lieu : {$lieu}";
        
        // Type de client
        if (!empty($data['client_type'])) {
            if ($data['client_type'] === 'professionnel' && !empty($data['client_nom'])) {
                $prompt .= "\n- Client professionnel : {$data['client_nom']}";
            } else {
                $prompt .= "\n- Client : Particulier";
            }
        }
        
        // Matière
        if (!empty($data['matiere'])) {
            $prompt .= "\n- Matière utilisée : {$data['matiere']}";
        }
        
        // Peinture
        if (!empty($data['peinture'])) {
            $prompt .= "\n- Finition : {$data['peinture']}";
        }
        
        // Pose
        if (!empty($data['pose']) && $data['pose'] === '1') {
            $prompt .= "\n- Pose incluse : Oui";
        }
        
        $prompt .= "\n\nLe post doit :
- Être conversationnel et chaleureux
- Faire 3-4 paragraphes
- Inclure des émojis pertinents
- Mentionner AL Métallerie
- Mentionner les détails techniques (matière, finition) si disponibles
- Terminer par un call-to-action
- Ne pas dépasser 500 caractères

Écris uniquement le post, sans titre ni hashtags.";
        
        return $prompt;
    }
    
    /**
     * Construire le prompt Instagram
     */
    private function build_instagram_prompt($data) {
        $type_names = !empty($data['types']) ? implode(', ', wp_list_pluck($data['types'], 'name')) : 'métallerie';
        $lieu = !empty($data['lieu']) ? $data['lieu'] : 'Clermont-Ferrand';
        
        $prompt = "Écris une légende Instagram pour une réalisation de métallerie.

Informations :
- Titre : {$data['title']}
- Type : {$type_names}
- Lieu : {$lieu}";
        
        // Matière
        if (!empty($data['matiere'])) {
            $prompt .= "\n- Matière : {$data['matiere']}";
        }
        
        // Peinture
        if (!empty($data['peinture'])) {
            $prompt .= "\n- Finition : {$data['peinture']}";
        }
        
        // Pose
        if (!empty($data['pose']) && $data['pose'] === '1') {
            $prompt .= "\n- Pose réalisée : Oui";
        }
        
        $prompt .= "\n\nLa légende doit :
- Être courte et impactante (2-3 lignes)
- Inclure 10-15 hashtags pertinents (dont des hashtags sur la matière si disponible)
- Utiliser des émojis
- Mentionner AL Métallerie
- Ne pas dépasser 300 caractères (hors hashtags)

Format : [Texte] + [Hashtags sur des lignes séparées]";
        
        return $prompt;
    }
    
    /**
     * Construire le prompt LinkedIn
     */
    private function build_linkedin_prompt($data) {
        $type_names = !empty($data['types']) ? implode(', ', wp_list_pluck($data['types'], 'name')) : 'métallerie';
        $lieu = !empty($data['lieu']) ? $data['lieu'] : 'Clermont-Ferrand';
        
        $prompt = "Écris un post LinkedIn professionnel pour une réalisation de métallerie.

Informations :
- Titre : {$data['title']}
- Type : {$type_names}
- Lieu : {$lieu}";
        
        // Type de client
        if (!empty($data['client_type'])) {
            if ($data['client_type'] === 'professionnel' && !empty($data['client_nom'])) {
                $prompt .= "\n- Client professionnel : {$data['client_nom']}";
            } else {
                $prompt .= "\n- Client : Particulier";
            }
        }
        
        // Matière
        if (!empty($data['matiere'])) {
            $prompt .= "\n- Matière : {$data['matiere']}";
        }
        
        // Peinture
        if (!empty($data['peinture'])) {
            $prompt .= "\n- Finition peinture : {$data['peinture']}";
        }
        
        // Pose
        if (!empty($data['pose']) && $data['pose'] === '1') {
            $prompt .= "\n- Prestation complète avec pose : Oui";
        }
        
        if (!empty($data['duree'])) {
            $prompt .= "\n- Durée : {$data['duree']}";
        }
        
        $prompt .= "\n\nLe post doit :
- Être professionnel et technique
- Faire 4-5 paragraphes
- Mettre en avant l'expertise et le savoir-faire
- Inclure des détails techniques (matière, finition, pose)
- Mentionner AL Métallerie
- Terminer par un call-to-action professionnel
- Ne pas dépasser 600 caractères

Écris uniquement le post, sans hashtags.";
        
        return $prompt;
    }
    
    /**
     * Appeler l'API Hugging Face
     */
    private function call_huggingface_api($prompt) {
        if (empty($this->huggingface_api_key)) {
            return false;
        }
        
        $response = wp_remote_post($this->api_url, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->huggingface_api_key,
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode(array(
                'inputs' => $prompt,
                'parameters' => array(
                    'max_new_tokens' => 500,
                    'temperature' => 0.7,
                    'top_p' => 0.95,
                    'do_sample' => true
                )
            )),
            'timeout' => 30
        ));
        
        if (is_wp_error($response)) {
            error_log('Hugging Face API Error: ' . $response->get_error_message());
            return false;
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (isset($body[0]['generated_text'])) {
            // Nettoyer la réponse (enlever le prompt)
            $text = str_replace($prompt, '', $body[0]['generated_text']);
            return trim($text);
        }
        
        return false;
    }
    
    /**
     * Template SEO (fallback) - 5 variations
     */
    private function generate_seo_template($data) {
        $type_names = !empty($data['types']) ? implode(' et ', wp_list_pluck($data['types'], 'name')) : 'métallerie';
        $lieu = !empty($data['lieu']) ? $data['lieu'] : 'Clermont-Ferrand';
        $date = !empty($data['date']) ? date_i18n('F Y', strtotime($data['date'])) : date_i18n('F Y');
        $matiere = !empty($data['matiere']) ? $this->get_matiere_label($data['matiere']) : '';
        $pose_text = (!empty($data['pose']) && ($data['pose'] === '1' || $data['pose'] == 1)) ? ' Pose incluse.' : '';
        
        $templates = array();
        
        // Template 1 : Classique
        if ($matiere) {
            $templates[] = "AL Métallerie : {$type_names} en {$matiere} à {$lieu} ({$date}). Découvrez notre savoir-faire artisanal.{$pose_text}";
        } else {
            $templates[] = "AL Métallerie vous présente sa réalisation de {$type_names} à {$lieu} ({$date}). Découvrez notre savoir-faire en métallerie sur-mesure.";
        }
        
        // Template 2 : Focus projet avec matière
        if ($matiere) {
            $templates[] = "Projet {$type_names} en {$matiere} réalisé à {$lieu}. AL Métallerie, expert en métallerie sur-mesure.{$pose_text}";
        } else {
            $templates[] = "Découvrez notre projet de {$type_names} réalisé à {$lieu} en {$date}. AL Métallerie, votre expert en métallerie sur-mesure.";
        }
        
        // Template 3 : Focus expertise
        if ($matiere) {
            $templates[] = "{$type_names} {$matiere} sur-mesure à {$lieu} par AL Métallerie. Expertise et qualité pour vos projets.{$pose_text}";
        } else {
            $templates[] = "{$type_names} sur-mesure à {$lieu} par AL Métallerie ({$date}). Expertise et qualité pour vos projets de métallerie.";
        }
        
        // Template 4 : Focus résultat
        $templates[] = "Projet de {$type_names} finalisé à {$lieu} en {$date}. AL Métallerie : conception et réalisation de métallerie haut de gamme.{$pose_text}";
        
        // Template 5 : Focus local
        $templates[] = "AL Métallerie réalise votre {$type_names} à {$lieu}. Découvrez notre dernière réalisation de {$date}. Métallerie artisanale.";
        
        // Choisir un template aléatoire
        return $templates[array_rand($templates)];
    }
    
    /**
     * Obtenir le label lisible de la matière
     */
    private function get_matiere_label($matiere) {
        $labels = array(
            'acier' => 'acier',
            'inox' => 'inox',
            'aluminium' => 'aluminium',
            'cuivre' => 'cuivre',
            'laiton' => 'laiton',
            'fer-forge' => 'fer forgé',
            'mixte' => 'matériaux mixtes'
        );
        return isset($labels[$matiere]) ? $labels[$matiere] : $matiere;
    }
    
    /**
     * Générer la description SEO longue structurée pour la page
     */
    public function generate_seo_description($data) {
        error_log('ALMETAL SEO: generate_seo_description called');
        
        try {
            // Essayer d'abord avec l'IA si la clé est configurée
            if (!empty($this->huggingface_api_key)) {
                error_log('ALMETAL SEO: Trying AI generation with Hugging Face');
                $ai_result = $this->generate_seo_description_with_ai($data);
                if ($ai_result && !empty($ai_result)) {
                    error_log('ALMETAL SEO: AI generation successful');
                    return $ai_result;
                }
                error_log('ALMETAL SEO: AI generation failed, falling back to template');
            } else {
                error_log('ALMETAL SEO: No API key, using template directly');
            }
        } catch (Exception $e) {
            error_log('ALMETAL SEO: AI Exception: ' . $e->getMessage());
        }
        
        // Fallback : template structuré (toujours fonctionnel)
        error_log('ALMETAL SEO: Using template fallback');
        $template_result = $this->generate_seo_description_template($data);
        
        if (empty($template_result)) {
            error_log('ALMETAL SEO: Template also returned empty!');
        }
        
        return $template_result;
    }
    
    /**
     * Générer la description SEO avec l'IA
     */
    private function generate_seo_description_with_ai($data) {
        $title = $data['title'] ?? 'Réalisation métallerie';
        $type_primary = $data['type_primary'] ?? 'métallerie';
        $type_list = $data['type_list'] ?? 'métallerie';
        $lieu = $data['lieu'] ?? 'Clermont-Ferrand';
        $departement = $data['departement'] ?? 'Puy-de-Dôme';
        $date = !empty($data['date']) ? date_i18n('F Y', strtotime($data['date'])) : '';
        $duree = $data['duree'] ?? '';
        $matiere = $data['matiere'] ?? '';
        $peinture = $data['peinture'] ?? '';
        $pose = (!empty($data['pose']) && ($data['pose'] === '1' || $data['pose'] == 1));
        $client_type = $data['client_type'] ?? '';
        $client_nom = $data['client_nom'] ?? '';
        
        $prompt = "<s>[INST] Tu es un expert en rédaction SEO pour une entreprise de métallerie française. 
Génère une description de page web structurée et optimisée pour Google.

INFORMATIONS DU PROJET :
- Titre : {$title}
- Type de réalisation : {$type_list}
- Lieu : {$lieu} ({$departement})
" . ($date ? "- Date : {$date}\n" : "") 
  . ($duree ? "- Durée : {$duree}\n" : "")
  . ($matiere ? "- Matière : {$matiere}\n" : "")
  . ($peinture ? "- Finition : {$peinture}\n" : "")
  . ($pose ? "- Pose incluse : oui\n" : "")
  . ($client_type === 'professionnel' && $client_nom ? "- Client professionnel : {$client_nom}\n" : "") . "

STRUCTURE OBLIGATOIRE (utilise ces balises HTML) :
<h2>Présentation du projet de {$type_primary} à {$lieu}</h2>
<p>Introduction accrocheuse avec mots-clés SEO...</p>

<h3>Notre expertise en {$type_primary}</h3>
<p>Paragraphe sur le savoir-faire AL Métallerie...</p>

<h3>Caractéristiques techniques</h3>
<p>Détails sur les matériaux, finitions, pose...</p>

<h3>À propos de ce projet</h3>
<p>Conclusion avec localisation et appel à l'action...</p>

RÈGLES :
- Utilise les mots-clés : {$type_list}, métallerie, {$lieu}, {$departement}, sur-mesure, artisan
- Écris en français professionnel
- Environ 300-400 mots
- Mentionne AL Métallerie naturellement
- Inclus les informations techniques si disponibles

Génère uniquement le HTML, sans commentaires. [/INST]";

        $response = wp_remote_post($this->api_url, array(
            'timeout' => 60,
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->huggingface_api_key,
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode(array(
                'inputs' => $prompt,
                'parameters' => array(
                    'max_new_tokens' => 1500,
                    'temperature' => 0.7,
                    'top_p' => 0.9,
                    'do_sample' => true,
                    'return_full_text' => false
                )
            ))
        ));
        
        if (is_wp_error($response)) {
            return false;
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (isset($body[0]['generated_text'])) {
            $text = $body[0]['generated_text'];
            
            // Nettoyer le texte
            $text = preg_replace('/\[\/INST\].*$/s', '', $text);
            $text = trim($text);
            
            // Vérifier qu'il contient du HTML valide
            if (strpos($text, '<h2>') !== false || strpos($text, '<h3>') !== false) {
                return $text;
            }
        }
        
        return false;
    }
    
    /**
     * Template de description SEO (fallback)
     */
    private function generate_seo_description_template($data) {
        $title = $data['title'] ?? 'Réalisation métallerie';
        $type_primary = $data['type_primary'] ?? 'métallerie';
        $type_list = $data['type_list'] ?? 'métallerie';
        $lieu = $data['lieu'] ?? 'Clermont-Ferrand';
        $departement = $data['departement'] ?? 'Puy-de-Dôme';
        $date = !empty($data['date']) ? date_i18n('F Y', strtotime($data['date'])) : '';
        $duree = $data['duree'] ?? '';
        $matiere = $data['matiere'] ?? '';
        $peinture = $data['peinture'] ?? '';
        $pose = (!empty($data['pose']) && ($data['pose'] === '1' || $data['pose'] == 1));
        $client_type = $data['client_type'] ?? '';
        $client_nom = $data['client_nom'] ?? '';
        $client_url = $data['client_url'] ?? '';
        
        // Construire la description structurée
        $html = '';
        
        // H2 - Titre principal
        $html .= "<h2>Présentation du projet de {$type_primary} à {$lieu}</h2>\n\n";
        
        // Introduction
        $intro = "<p>{$type_list} sur-mesure à {$lieu} par AL Métallerie. ";
        if ($matiere) {
            $intro .= "Ce projet en <strong>{$matiere}</strong> ";
        } else {
            $intro .= "Ce projet ";
        }
        $intro .= "illustre notre expertise et notre engagement envers la qualité.</p>\n\n";
        $html .= $intro;
        
        // Section expertise
        $html .= "<h3>Notre expertise en {$type_primary}</h3>\n";
        $html .= "<p>AL Métallerie met son savoir-faire au service de vos projets de {$type_primary} dans le {$departement} et ses environs. ";
        $html .= "Chaque réalisation est conçue sur-mesure pour répondre parfaitement à vos besoins et s'intégrer harmonieusement à votre environnement.</p>\n\n";
        
        // Section caractéristiques techniques (si données disponibles)
        if ($matiere || $peinture || $pose) {
            $html .= "<h3>Caractéristiques techniques</h3>\n";
            $html .= "<p>";
            $specs = array();
            if ($matiere) {
                $specs[] = "Ce projet a été réalisé en <strong>{$matiere}</strong>, un matériau choisi pour sa durabilité et son esthétique";
            }
            if ($peinture) {
                $specs[] = "La finition <strong>{$peinture}</strong> apporte une touche finale soignée et une protection optimale";
            }
            if ($pose) {
                $specs[] = "La <strong>pose a été réalisée par nos équipes</strong>, garantissant une installation professionnelle et conforme aux normes";
            }
            $html .= implode('. ', $specs) . ".</p>\n\n";
        }
        
        // Section client professionnel (si applicable)
        if ($client_type === 'professionnel' && $client_nom) {
            $html .= "<h3>Un projet pour {$client_nom}</h3>\n";
            $html .= "<p>Ce projet a été réalisé pour <strong>{$client_nom}</strong>";
            if ($client_url) {
                $html .= " (<a href=\"{$client_url}\" target=\"_blank\" rel=\"noopener\">{$client_url}</a>)";
            }
            $html .= ". Nous sommes fiers de cette collaboration qui témoigne de la confiance que nous accordent les professionnels de la région.</p>\n\n";
        }
        
        // Section à propos du projet
        $html .= "<h3>À propos de ce projet</h3>\n";
        $html .= "<p>Ce projet de {$type_primary} a été réalisé à {$lieu} par AL Métallerie, spécialiste de la métallerie dans le {$departement}. ";
        if ($duree) {
            $html .= "La durée de réalisation de ce projet a été de <strong>{$duree}</strong>, témoignant de notre efficacité et de notre professionnalisme. ";
        }
        if ($date) {
            $html .= "Projet finalisé en {$date}. ";
        }
        $html .= "</p>\n\n";
        
        // Appel à l'action
        $html .= "<p><strong>Vous avez un projet similaire ?</strong> Contactez AL Métallerie pour un devis gratuit et personnalisé. ";
        $html .= "Notre équipe est à votre écoute pour concrétiser vos idées en {$type_primary} sur-mesure.</p>";
        
        return $html;
    }
    
    /**
     * Template Facebook (fallback) - 5 variations
     */
    private function generate_facebook_template($data) {
        $type_names = !empty($data['types']) ? implode(' et ', wp_list_pluck($data['types'], 'name')) : 'métallerie';
        $lieu = !empty($data['lieu']) ? $data['lieu'] : 'Clermont-Ferrand';
        
        // Gestion du client professionnel avec URL
        $client_nom = (!empty($data['client_type']) && $data['client_type'] === 'professionnel' && !empty($data['client_nom'])) ? $data['client_nom'] : '';
        $client_url = (!empty($data['client_type']) && $data['client_type'] === 'professionnel' && !empty($data['client_url'])) ? $data['client_url'] : '';
        
        $client_text = "";
        if ($client_nom) {
            $client_text = "Merci à {$client_nom} pour leur confiance ! 🙏";
            if ($client_url) {
                $client_text .= "\n🔗 {$client_url}";
            }
            $client_text .= "\n\n";
        }
        
        // Détails techniques
        $matiere = !empty($data['matiere']) ? $this->get_matiere_label($data['matiere']) : '';
        $peinture = !empty($data['peinture']) ? $data['peinture'] : '';
        $pose = (!empty($data['pose']) && ($data['pose'] === '1' || $data['pose'] == 1));
        
        $details_text = "";
        if ($matiere || $peinture || $pose) {
            $details = array();
            if ($matiere) $details[] = "🔧 Matière : " . ucfirst($matiere);
            if ($peinture) $details[] = "🎨 Finition : {$peinture}";
            if ($pose) $details[] = "✅ Pose réalisée par nos équipes";
            $details_text = implode("\n", $details) . "\n\n";
        }
        
        $templates = array();
        
        // Template 1 : Enthousiaste avec détails
        $templates[] = "🔥 Nouvelle réalisation AL Métallerie ! 🔥\n\n"
            . "Nous sommes fiers de vous présenter notre dernier projet : {$data['title']} à {$lieu}.\n\n"
            . "✨ Un travail de {$type_names} réalisé avec passion et expertise par notre équipe.\n\n"
            . $details_text
            . $client_text
            . "📞 Vous avez un projet similaire ? Contactez-nous !\n"
            . "👉 www.al-metallerie.fr";
        
        // Template 2 : Storytelling
        $templates[] = "Il y a quelques semaines, nous avons eu le plaisir de réaliser ce magnifique projet à {$lieu}... 🏗️\n\n"
            . "Aujourd'hui, nous sommes ravis de vous dévoiler : {$data['title']} !\n\n"
            . "Un projet de {$type_names} qui reflète notre engagement pour la qualité et le sur-mesure. 💪\n\n"
            . $details_text
            . $client_text
            . "Envie d'un projet unique ? Parlons-en ! 💬\n"
            . "👉 www.al-metallerie.fr";
        
        // Template 3 : Professionnel avec détails techniques
        $templates[] = "✅ Projet finalisé !\n\n"
            . "AL Métallerie vient de terminer la réalisation de {$type_names} à {$lieu}.\n\n"
            . "📐 {$data['title']}\n"
            . ($matiere ? "🔧 Matière : " . ucfirst($matiere) . "\n" : "🔧 Conception et réalisation sur-mesure\n")
            . ($peinture ? "🎨 Finition : {$peinture}\n" : "")
            . ($pose ? "✅ Pose réalisée par nos équipes\n" : "")
            . "⭐ Résultat à la hauteur des attentes\n\n"
            . $client_text
            . "Un projet en tête ? Demandez votre devis gratuit !\n"
            . "👉 www.al-metallerie.fr";
        
        // Template 4 : Focus client pro avec lien
        $client_collab = "";
        if ($client_nom) {
            $client_collab = "Nous avons eu le plaisir de collaborer avec {$client_nom}";
            if ($client_url) {
                $client_collab .= " ({$client_url})";
            }
            $client_collab .= " pour réaliser ce projet de {$type_names} à {$lieu}.\n\n";
        } else {
            $client_collab = "Découvrez notre dernière réalisation de {$type_names} à {$lieu}.\n\n";
        }
        $templates[] = "🎉 Un nouveau projet dont nous sommes particulièrement fiers !\n\n"
            . $client_collab
            . "Le résultat ? {$data['title']} qui allie esthétique et robustesse ! 💎\n\n"
            . $details_text
            . "Votre projet mérite le meilleur. Faites confiance à AL Métallerie ! 🤝\n"
            . "👉 www.al-metallerie.fr";
        
        // Template 5 : Fiche technique style avec client
        $client_fiche = "";
        if ($client_nom) {
            $client_fiche = "🏢 Client : {$client_nom}";
            if ($client_url) {
                $client_fiche .= "\n🔗 {$client_url}";
            }
            $client_fiche .= "\n";
        }
        $templates[] = "📸 Découvrez notre dernière création !\n\n"
            . "📍 Lieu : {$lieu}\n"
            . "📐 Projet : {$data['title']}\n"
            . "🔨 Type : {$type_names}\n"
            . ($matiere ? "⚙️ Matière : " . ucfirst($matiere) . "\n" : "")
            . ($peinture ? "🎨 Finition : {$peinture}\n" : "")
            . ($pose ? "✅ Pose incluse\n" : "")
            . $client_fiche
            . "\nDe la conception à la réalisation, AL Métallerie transforme vos idées en réalité. ✨\n\n"
            . "Besoin d'un artisan de confiance ? On est là ! 💪\n"
            . "👉 www.al-metallerie.fr";
        
        // Choisir un template aléatoire
        return $templates[array_rand($templates)];
    }
    
    /**
     * Template Instagram (fallback) - 5 variations
     */
    private function generate_instagram_template($data) {
        $type_names = !empty($data['types']) ? strtolower(implode(' ', wp_list_pluck($data['types'], 'name'))) : 'métallerie';
        $lieu = !empty($data['lieu']) ? $data['lieu'] : 'Clermont-Ferrand';
        $lieu_hashtag = str_replace(array(' ', '-'), '', $lieu);
        
        // Détails techniques
        $matiere = !empty($data['matiere']) ? $this->get_matiere_label($data['matiere']) : '';
        $matiere_hashtag = $matiere ? '#' . ucfirst(str_replace(' ', '', $matiere)) : '';
        $peinture = !empty($data['peinture']) ? $data['peinture'] : '';
        $pose = (!empty($data['pose']) && ($data['pose'] === '1' || $data['pose'] == 1));
        
        // Hashtags de base avec matière si disponible
        $base_hashtags = "#ALMetallerie #{$type_names} #Metallerie #MetalWork #Artisan #SurMesure #{$lieu_hashtag} #Auvergne #AuvergneRhoneAlpes #Ferronnerie";
        if ($matiere_hashtag) {
            $base_hashtags .= " {$matiere_hashtag}";
        }
        $base_hashtags .= " #Design #Architecture";
        
        $templates = array();
        
        // Template 1 : Classique avec émojis et matière
        $matiere_line = $matiere ? "⚙️ " . ucfirst($matiere) . "\n" : "";
        $templates[] = "✨ {$data['title']} ✨\n\n"
            . "Nouvelle réalisation à {$lieu} 🔥\n"
            . $matiere_line
            . "Swipe pour voir toutes les photos ! 👉\n\n"
            . $base_hashtags . " #Renovation #Construction";
        
        // Template 2 : Question engageante
        $templates[] = "Qu'en pensez-vous ? 🤔\n\n"
            . "Notre dernière création : {$data['title']}\n"
            . "📍 {$lieu}\n"
            . ($matiere ? "⚙️ " . ucfirst($matiere) . "\n" : "")
            . "\nDouble tap si tu aimes ! ❤️\n\n"
            . $base_hashtags . " #MetalDesign #CustomMade";
        
        // Template 3 : Style minimaliste avec détails
        $templates[] = "{$data['title']}\n"
            . "{$lieu} | " . date('Y') . "\n\n"
            . "🔨 Métallerie sur-mesure\n"
            . ($matiere ? "⚙️ " . ucfirst($matiere) . "\n" : "")
            . ($peinture ? "🎨 {$peinture}\n" : "")
            . "📸 Swipe →\n\n"
            . $base_hashtags . " #Craftsmanship #HandMade";
        
        // Template 4 : Focus processus
        $templates[] = "Du dessin à la réalisation... 📐➡️🔨\n\n"
            . "{$data['title']} à {$lieu}\n"
            . ($matiere ? "En " . $matiere . " ⚙️\n" : "")
            . "\nChaque projet est unique, comme vous ! 💎\n"
            . "Découvrez le résultat en images 👉\n\n"
            . $base_hashtags . " #Process #MadeInFrance";
        
        // Template 5 : Fiche technique style
        $templates[] = "🏗️ Projet : {$data['title']}\n"
            . "📍 Lieu : {$lieu}\n"
            . "🔧 Type : {$type_names}\n"
            . ($matiere ? "⚙️ Matière : " . ucfirst($matiere) . "\n" : "")
            . ($peinture ? "🎨 Finition : {$peinture}\n" : "")
            . ($pose ? "✅ Pose incluse\n" : "")
            . "\nVotre projet mérite le meilleur ! 💪\n\n"
            . $base_hashtags . " #QualityWork #ProudOfIt";
        
        // Choisir un template aléatoire
        return $templates[array_rand($templates)];
    }
    
    /**
     * Template LinkedIn (fallback) - 5 variations
     */
    private function generate_linkedin_template($data) {
        $type_names = !empty($data['types']) ? implode(' et ', wp_list_pluck($data['types'], 'name')) : 'métallerie';
        $lieu = !empty($data['lieu']) ? $data['lieu'] : 'Clermont-Ferrand';
        $date = !empty($data['date']) ? date_i18n('F Y', strtotime($data['date'])) : date_i18n('F Y');
        $duree_text = !empty($data['duree']) ? "Réalisé en {$data['duree']}, " : "";
        
        // Détails techniques
        $matiere = !empty($data['matiere']) ? $this->get_matiere_label($data['matiere']) : '';
        $peinture = !empty($data['peinture']) ? $data['peinture'] : '';
        $pose = (!empty($data['pose']) && ($data['pose'] === '1' || $data['pose'] == 1));
        
        // Client professionnel avec URL
        $client_pro = (!empty($data['client_type']) && $data['client_type'] === 'professionnel' && !empty($data['client_nom'])) ? $data['client_nom'] : '';
        $client_url = (!empty($data['client_type']) && $data['client_type'] === 'professionnel' && !empty($data['client_url'])) ? $data['client_url'] : '';
        
        // Construire les détails techniques
        $tech_details = "";
        if ($matiere || $peinture || $pose) {
            $tech_details = "\nCaractéristiques techniques :\n";
            if ($matiere) $tech_details .= "• Matière : " . ucfirst($matiere) . "\n";
            if ($peinture) $tech_details .= "• Finition : {$peinture}\n";
            if ($pose) $tech_details .= "• Prestation complète avec pose\n";
            $tech_details .= "\n";
        }
        
        $templates = array();
        
        // Template 1 : Professionnel classique avec détails et lien
        $client_mention = "";
        if ($client_pro) {
            $client_mention = "Projet réalisé pour {$client_pro}";
            if ($client_url) {
                $client_mention .= " ({$client_url})";
            }
            $client_mention .= ".\n\n";
        }
        $templates[] = "Nouvelle réalisation AL Métallerie\n\n"
            . "Nous sommes heureux de partager notre dernière réalisation : {$data['title']} à {$lieu} ({$date}).\n\n"
            . $client_mention
            . "Ce projet de {$type_names}" . ($matiere ? " en {$matiere}" : "") . " illustre notre expertise et notre engagement envers la qualité. {$duree_text}ce chantier a mobilisé notre savoir-faire technique et notre sens du détail.\n"
            . $tech_details
            . "Chez AL Métallerie, chaque projet est unique et conçu sur-mesure pour répondre aux besoins spécifiques de nos clients.\n\n"
            . "Vous avez un projet de métallerie ? Parlons-en !\n"
            . "📧 contact@al-metallerie.fr";
        
        // Template 2 : Focus expertise technique avec fiche
        $client_line = "";
        if ($client_pro) {
            $client_line = "🏢 Client : {$client_pro}";
            if ($client_url) {
                $client_line .= " - {$client_url}";
            }
            $client_line .= "\n";
        }
        $templates[] = "Expertise métallerie | Projet finalisé\n\n"
            . "AL Métallerie vient de finaliser un projet de {$type_names} à {$lieu}.\n\n"
            . "📐 Projet : {$data['title']}\n"
            . "📅 Date : {$date}\n"
            . ($duree_text ? "⏱️ Durée : {$data['duree']}\n" : "")
            . ($matiere ? "⚙️ Matière : " . ucfirst($matiere) . "\n" : "")
            . ($peinture ? "🎨 Finition : {$peinture}\n" : "")
            . ($pose ? "✅ Pose incluse\n" : "")
            . $client_line
            . "\nNotre approche :\n"
            . "• Étude technique approfondie\n"
            . "• Conception sur-mesure\n"
            . "• Réalisation par des artisans qualifiés\n"
            . "• Suivi qualité rigoureux\n\n"
            . "AL Métallerie : votre partenaire pour des réalisations durables et esthétiques.\n\n"
            . "Contact : contact@al-metallerie.fr";
        
        // Template 3 : Focus résultat client avec lien
        $client_thanks = "";
        if ($client_pro) {
            $client_thanks = "Merci à {$client_pro} pour leur confiance.";
            if ($client_url) {
                $client_thanks .= "\n🔗 {$client_url}";
            }
            $client_thanks .= "\n\n";
        }
        $templates[] = "Satisfaction client | Projet livré\n\n"
            . "Retour sur notre dernière réalisation à {$lieu} : {$data['title']}.\n\n"
            . $client_thanks
            . "Ce projet de {$type_names}" . ($matiere ? " en {$matiere}" : "") . " a été mené de bout en bout par nos équipes. {$duree_text}nous avons su répondre aux exigences techniques et esthétiques de ce chantier.\n"
            . $tech_details
            . "Notre priorité ? La satisfaction de nos clients et la qualité de nos ouvrages.\n\n"
            . "AL Métallerie accompagne les particuliers et professionnels dans leurs projets de métallerie sur-mesure en Auvergne-Rhône-Alpes.\n\n"
            . "Un projet ? Échangeons : contact@al-metallerie.fr";
        
        // Template 4 : Style success story avec client
        $client_success = "";
        if ($client_pro) {
            $client_success = "🏢 Client : {$client_pro}";
            if ($client_url) {
                $client_success .= "\n🔗 {$client_url}";
            }
            $client_success .= "\n";
        }
        $templates[] = "Success Story | {$data['title']}\n\n"
            . "Fiers de partager cette réalisation qui illustre notre savoir-faire en {$type_names}.\n\n"
            . "🎯 Objectif : Créer une solution sur-mesure répondant aux contraintes techniques et esthétiques\n"
            . "📍 Localisation : {$lieu}\n"
            . "📆 Réalisation : {$date}\n"
            . ($duree_text ? "⏱️ Délai : {$data['duree']}\n" : "")
            . ($matiere ? "⚙️ Matière : " . ucfirst($matiere) . "\n" : "")
            . ($peinture ? "🎨 Finition : {$peinture}\n" : "")
            . ($pose ? "✅ Pose réalisée par nos équipes\n" : "")
            . $client_success
            . "\nRésultat : Un ouvrage qui allie robustesse, design et durabilité.\n\n"
            . "AL Métallerie : 20 ans d'expérience au service de vos projets.\n\n"
            . "Discutons de votre projet : contact@al-metallerie.fr";
        
        // Template 5 : Focus innovation/qualité avec matériaux et lien client
        $client_realise = "";
        if ($client_pro) {
            $client_realise = "🏢 Réalisé pour {$client_pro}";
            if ($client_url) {
                $client_realise .= " - {$client_url}";
            }
            $client_realise .= "\n";
        }
        $templates[] = "Qualité & Innovation | Nouvelle réalisation\n\n"
            . "AL Métallerie présente : {$data['title']}\n\n"
            . "Un projet de {$type_names}" . ($matiere ? " en {$matiere}" : "") . " qui démontre notre capacité à allier tradition artisanale et techniques modernes.\n\n"
            . "📍 {$lieu} | {$date}\n"
            . ($duree_text ? "⏱️ {$data['duree']} de travail minutieux\n" : "")
            . $client_realise
            . "\nNotre engagement :\n"
            . ($matiere ? "✓ " . ucfirst($matiere) . " de qualité supérieure\n" : "✓ Matériaux de qualité supérieure\n")
            . ($peinture ? "✓ Finition {$peinture}\n" : "✓ Finitions soignées\n")
            . "✓ Respect des délais\n"
            . ($pose ? "✓ Pose professionnelle incluse\n" : "✓ Garantie et suivi\n")
            . "\nVotre projet mérite une expertise reconnue. Contactez AL Métallerie.\n\n"
            . "📧 contact@al-metallerie.fr";
        
        // Choisir un template aléatoire
        return $templates[array_rand($templates)];
    }
}
