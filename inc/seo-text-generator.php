<?php
/**
 * Générateur de Texte SEO avec Hugging Face
 * 
 * @package AL-Metallerie Soudure
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
        
        // Générer l'extrait/sous-titre (courte description)
        $texts['excerpt'] = $this->generate_excerpt($data);
        
        // Générer les textes pour les réseaux sociaux
        $texts['facebook'] = $this->generate_facebook_text($data);
        $texts['instagram'] = $this->generate_instagram_text($data);
        $texts['linkedin'] = $this->generate_linkedin_text($data);
        
        return $texts;
    }
    
    /**
     * Générer l'extrait/sous-titre (courte description affichée sous le titre)
     */
    private function generate_excerpt($data) {
        $type_names = !empty($data['types']) ? wp_list_pluck($data['types'], 'name') : array('métallerie');
        $type_primary = $type_names[0];
        $lieu = !empty($data['lieu']) ? $data['lieu'] : 'Thiers';
        $matiere = !empty($data['matiere']) ? $data['matiere'] : '';
        $client_type = !empty($data['client_type']) ? $data['client_type'] : '';
        
        // Variations d'introductions
        $intros = array(
            "Découvrez cette réalisation de {$type_primary}",
            "Projet de {$type_primary} sur mesure",
            "Création artisanale de {$type_primary}",
            "{$type_primary} personnalisé(e)",
            "Réalisation de {$type_primary}",
        );
        
        // Variations de localisation
        $lieux = array(
            "à {$lieu}",
            "réalisé(e) à {$lieu}",
            "installé(e) à {$lieu}",
            "pour un client de {$lieu}",
        );
        
        // Variations de matière
        $matieres = array();
        if ($matiere) {
            $matieres = array(
                "en {$matiere}",
                "fabriqué(e) en {$matiere}",
                "conçu(e) en {$matiere}",
            );
        }
        
        // Variations de client
        $clients = array();
        if ($client_type === 'professionnel') {
            $clients = array(
                "pour un professionnel",
                "pour une entreprise",
            );
        } elseif ($client_type === 'particulier') {
            $clients = array(
                "pour un particulier",
                "pour une maison individuelle",
            );
        }
        
        // Construire l'extrait
        $intro = $intros[array_rand($intros)];
        $lieu_text = $lieux[array_rand($lieux)];
        
        $excerpt = $intro . ' ' . $lieu_text;
        
        if (!empty($matieres)) {
            $excerpt .= ', ' . $matieres[array_rand($matieres)];
        }
        
        if (!empty($clients)) {
            $excerpt .= ' ' . $clients[array_rand($clients)];
        }
        
        $excerpt .= '. AL-Metallerie Soudure, artisan métallier dans le Puy-de-Dôme.';
        
        return $excerpt;
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
            $prompt .= "\n- Pose réalisée par AL-Metallerie Soudure : Oui";
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
- Mentionner AL-Metallerie Soudure
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
- Mentionner AL-Metallerie Soudure
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
- Mentionner AL-Metallerie Soudure
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
- Mentionner AL-Metallerie Soudure
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
        $type_names = !empty($data['types']) ? implode(', ', wp_list_pluck($data['types'], 'name')) : 'métallerie';
        $lieu = !empty($data['lieu']) ? $data['lieu'] : 'Clermont-Ferrand';
        $date = !empty($data['date']) ? date_i18n('F Y', strtotime($data['date'])) : date_i18n('F Y');
        $matiere = !empty($data['matiere']) ? $this->get_matiere_label($data['matiere']) : '';
        $pose_text = (!empty($data['pose']) && ($data['pose'] === '1' || $data['pose'] == 1)) ? ' Pose incluse.' : '';
        
        $templates = array();
        
        // Template 1 : Classique
        if ($matiere) {
            $templates[] = "AL-Metallerie Soudure : {$type_names} en {$matiere} à {$lieu} ({$date}). Découvrez notre savoir-faire artisanal.{$pose_text}";
        } else {
            $templates[] = "AL-Metallerie Soudure vous présente sa réalisation de {$type_names} à {$lieu} ({$date}). Découvrez notre savoir-faire en métallerie sur-mesure.";
        }
        
        // Template 2 : Focus projet avec matière
        if ($matiere) {
            $templates[] = "Projet {$type_names} en {$matiere} réalisé à {$lieu}. AL-Metallerie Soudure, expert en métallerie sur-mesure.{$pose_text}";
        } else {
            $templates[] = "Découvrez notre projet de {$type_names} réalisé à {$lieu} en {$date}. AL-Metallerie Soudure, votre expert en métallerie sur-mesure.";
        }
        
        // Template 3 : Focus expertise
        if ($matiere) {
            $templates[] = "{$type_names} {$matiere} sur-mesure à {$lieu} par AL-Metallerie Soudure. Expertise et qualité pour vos projets.{$pose_text}";
        } else {
            $templates[] = "{$type_names} sur-mesure à {$lieu} par AL-Metallerie Soudure ({$date}). Expertise et qualité pour vos projets de métallerie.";
        }
        
        // Template 4 : Focus résultat
        $templates[] = "Projet de {$type_names} finalisé à {$lieu} en {$date}. AL-Metallerie Soudure : conception et réalisation de métallerie haut de gamme.{$pose_text}";
        
        // Template 5 : Focus local
        $templates[] = "AL-Metallerie Soudure réalise votre {$type_names} à {$lieu}. Découvrez notre dernière réalisation de {$date}. Métallerie artisanale.";
        
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
<p>Paragraphe sur le savoir-faire AL-Metallerie Soudure...</p>

<h3>Caractéristiques techniques</h3>
<p>Détails sur les matériaux, finitions, pose...</p>

<h3>À propos de ce projet</h3>
<p>Conclusion avec localisation et appel à l'action...</p>

RÈGLES :
- Utilise les mots-clés : {$type_list}, métallerie, {$lieu}, {$departement}, sur-mesure, artisan
- Écris en français professionnel
- Environ 300-400 mots
- Mentionne AL-Metallerie Soudure naturellement
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
     * Template de description SEO (fallback) - AVEC VARIATIONS ALÉATOIRES
     * Structure optimisée SEO : 300-500 mots avec sections enrichies
     */
    private function generate_seo_description_template($data) {
        $title = $data['title'] ?? 'Réalisation métallerie';
        $type_primary = $data['type_primary'] ?? 'métallerie';
        $type_list = $data['type_list'] ?? 'métallerie';
        $lieu = $data['lieu'] ?? 'Thiers';
        $departement = $data['departement'] ?? 'Puy-de-Dôme';
        $date = !empty($data['date']) ? date_i18n('F Y', strtotime($data['date'])) : '';
        $annee = !empty($data['date']) ? date('Y', strtotime($data['date'])) : date('Y');
        $duree = $data['duree'] ?? '2 à 3 semaines';
        $matiere = $data['matiere'] ?? 'acier';
        $peinture = $data['peinture'] ?? 'thermolaquage';
        $pose = (!empty($data['pose']) && ($data['pose'] === '1' || $data['pose'] == 1));
        $client_type = $data['client_type'] ?? 'particulier';
        $client_nom = $data['client_nom'] ?? '';
        $client_url = $data['client_url'] ?? '';
        
        // Labels lisibles
        $matiere_label = $this->get_matiere_label($matiere);
        $client_label = ($client_type === 'professionnel') ? 'un professionnel' : 'un particulier';
        
        // ========================================
        // VARIATIONS POUR CHAQUE SECTION
        // ========================================
        
        // Titres H2 variés
        $titres_h2 = array(
            "Présentation du projet de {$type_primary} à {$lieu}",
            "Découvrez notre réalisation de {$type_primary} à {$lieu}",
            "{$type_primary} sur-mesure à {$lieu} : notre dernière création",
            "Projet de {$type_primary} réalisé à {$lieu}",
            "Notre expertise en {$type_primary} à {$lieu}",
            "Réalisation {$type_primary} à {$lieu} par AL-Metallerie Soudure",
        );
        
        // Introductions variées
        $intros = array();
        if ($matiere) {
            $intros[] = "Découvrez cette magnifique réalisation de {$type_list} en <strong>{$matiere}</strong>, conçue et fabriquée sur-mesure à {$lieu} par les artisans d'AL-Metallerie Soudure.";
            $intros[] = "AL-Metallerie Soudure a le plaisir de vous présenter ce projet de {$type_list} en <strong>{$matiere}</strong>, réalisé avec passion à {$lieu}.";
            $intros[] = "Ce projet de {$type_list} en <strong>{$matiere}</strong> illustre parfaitement le savoir-faire artisanal d'AL-Metallerie Soudure, spécialiste de la métallerie à {$lieu}.";
            $intros[] = "Voici notre dernière création : un{$this->get_article($type_primary)} {$type_list} en <strong>{$matiere}</strong>, fabriqué{$this->get_accord($type_primary)} sur-mesure pour un client de {$lieu}.";
            $intros[] = "Nous sommes fiers de vous dévoiler cette réalisation de {$type_list} en <strong>{$matiere}</strong>. Un projet unique réalisé à {$lieu} avec le plus grand soin.";
        } else {
            $intros[] = "Découvrez cette réalisation de {$type_list} conçue et fabriquée sur-mesure à {$lieu} par les artisans d'AL-Metallerie Soudure.";
            $intros[] = "AL-Metallerie Soudure vous présente son dernier projet de {$type_list}, réalisé avec passion et expertise à {$lieu}.";
            $intros[] = "Ce projet de {$type_list} témoigne du savoir-faire artisanal d'AL-Metallerie Soudure, votre spécialiste métallerie à {$lieu} et dans le {$departement}.";
            $intros[] = "Voici notre dernière création : un{$this->get_article($type_primary)} {$type_list} fabriqué{$this->get_accord($type_primary)} sur-mesure pour un client de {$lieu}.";
            $intros[] = "Nous avons le plaisir de vous présenter cette nouvelle réalisation de {$type_list}. Un projet unique créé à {$lieu} avec le plus grand soin.";
        }
        
        // Titres H3 expertise variés
        $titres_expertise = array(
            "Notre savoir-faire en {$type_primary}",
            "L'expertise AL-Metallerie Soudure",
            "Un travail artisanal de qualité",
            "La qualité au service de votre projet",
            "Pourquoi choisir AL-Metallerie Soudure ?",
            "Notre engagement qualité",
        );
        
        // Paragraphes expertise variés
        $expertises = array(
            "Depuis notre atelier situé dans le {$departement}, nous concevons et fabriquons des ouvrages de {$type_primary} sur-mesure. Chaque projet est unique et bénéficie de toute notre attention pour un résultat à la hauteur de vos attentes.",
            "AL-Metallerie Soudure met son expertise au service de vos projets de {$type_primary} dans le {$departement} et ses environs. Notre équipe d'artisans qualifiés travaille avec précision pour créer des ouvrages durables et esthétiques.",
            "Spécialisés dans la {$type_primary} sur-mesure, nous accompagnons nos clients de la conception à la réalisation. Notre atelier dans le {$departement} nous permet de maîtriser chaque étape de fabrication.",
            "Chez AL-Metallerie Soudure, nous croyons que chaque projet mérite une attention particulière. C'est pourquoi nous travaillons en étroite collaboration avec nos clients pour créer des ouvrages de {$type_primary} parfaitement adaptés à leurs besoins.",
            "Fort de notre expérience en métallerie, nous réalisons des projets de {$type_primary} alliant robustesse, esthétique et durabilité. Notre implantation dans le {$departement} nous permet d'intervenir rapidement sur toute la région.",
        );
        
        // Titres H3 caractéristiques variés
        $titres_carac = array(
            "Caractéristiques techniques",
            "Les détails de ce projet",
            "Fiche technique",
            "Matériaux et finitions",
            "Spécifications du projet",
        );
        
        // Phrases matière variées
        $phrases_matiere = array(
            "Ce projet a été réalisé en <strong>{$matiere}</strong>, un matériau sélectionné pour ses qualités de durabilité et son rendu esthétique",
            "Nous avons choisi le <strong>{$matiere}</strong> pour ce projet, un matériau noble qui garantit robustesse et longévité",
            "La fabrication en <strong>{$matiere}</strong> assure à cet ouvrage une excellente résistance et un aspect visuel remarquable",
            "Le <strong>{$matiere}</strong> a été privilégié pour cette réalisation, offrant le parfait équilibre entre solidité et élégance",
            "Cet ouvrage en <strong>{$matiere}</strong> allie les qualités mécaniques du matériau à une finition soignée",
        );
        
        // Phrases peinture variées
        $phrases_peinture = array(
            "La finition <strong>{$peinture}</strong> apporte une touche finale soignée et une protection optimale contre les intempéries",
            "Le traitement de surface <strong>{$peinture}</strong> garantit une protection durable tout en sublimant l'aspect de l'ouvrage",
            "La finition <strong>{$peinture}</strong> a été appliquée pour assurer une protection longue durée et un rendu esthétique impeccable",
            "Nous avons opté pour une finition <strong>{$peinture}</strong>, offrant à la fois protection et esthétique",
            "Le revêtement <strong>{$peinture}</strong> protège l'ouvrage tout en lui conférant son aspect définitif",
        );
        
        // Phrases pose variées
        $phrases_pose = array(
            "La <strong>pose a été réalisée par nos équipes</strong>, garantissant une installation professionnelle conforme aux normes en vigueur",
            "Nos artisans ont assuré la <strong>pose complète</strong> de l'ouvrage, pour un résultat parfait et sécurisé",
            "L'<strong>installation a été effectuée par AL-Metallerie Soudure</strong>, assurant ainsi une mise en œuvre dans les règles de l'art",
            "La <strong>pose professionnelle</strong> par notre équipe garantit une fixation solide et durable",
            "Nous avons pris en charge l'<strong>installation sur site</strong>, pour un service complet de A à Z",
        );
        
        // Titres H3 à propos variés
        $titres_apropos = array(
            "À propos de ce projet",
            "En résumé",
            "Ce projet en quelques mots",
            "Informations sur cette réalisation",
            "Détails du projet",
        );
        
        // Phrases conclusion variées
        $conclusions = array(
            "Ce projet de {$type_primary} a été réalisé à {$lieu} par AL-Metallerie Soudure, artisan métallier dans le {$departement}.",
            "Cette réalisation de {$type_primary} à {$lieu} illustre notre engagement pour la qualité et le sur-mesure.",
            "AL-Metallerie Soudure, votre artisan métallier dans le {$departement}, a eu le plaisir de réaliser ce projet de {$type_primary} à {$lieu}.",
            "Basés dans le {$departement}, nous avons conçu et fabriqué ce projet de {$type_primary} pour un client de {$lieu}.",
            "Ce projet de {$type_primary} réalisé à {$lieu} témoigne de notre savoir-faire en métallerie sur-mesure.",
        );
        
        // Phrases durée variées
        $phrases_duree = array(
            "La réalisation s'est étalée sur <strong>{$duree}</strong>, un délai optimisé grâce à notre organisation efficace.",
            "Ce projet a nécessité <strong>{$duree}</strong> de travail, de la conception à l'installation finale.",
            "Durée de réalisation : <strong>{$duree}</strong>, témoignant de notre réactivité et de notre professionnalisme.",
            "En <strong>{$duree}</strong>, nous avons mené ce projet à bien, dans le respect des délais convenus.",
        );
        
        // Appels à l'action variés
        $ctas = array(
            "<strong>Vous avez un projet similaire ?</strong> Contactez AL-Metallerie Soudure pour un devis gratuit et personnalisé. Notre équipe est à votre écoute pour concrétiser vos idées en {$type_primary} sur-mesure.",
            "<strong>Envie d'un projet sur-mesure ?</strong> N'hésitez pas à nous contacter pour discuter de votre projet de {$type_primary}. Devis gratuit et conseils personnalisés.",
            "<strong>Ce projet vous inspire ?</strong> AL-Metallerie Soudure réalise votre {$type_primary} sur-mesure dans le {$departement} et ses environs. Demandez votre devis gratuit !",
            "<strong>Besoin d'un artisan métallier ?</strong> Contactez-nous pour votre projet de {$type_primary}. Nous vous accompagnons de la conception à la pose.",
            "<strong>Prêt à concrétiser votre projet ?</strong> AL-Metallerie Soudure est à votre disposition pour étudier votre projet de {$type_primary}. Devis gratuit sous 48h.",
        );
        
        // ========================================
        // CONSTRUCTION DU HTML ENRICHI (300-500 mots)
        // ========================================
        
        $html = '';
        
        // ----------------------------------------
        // 1. TITRE H2 PRINCIPAL
        // ----------------------------------------
        $html .= "<h2>{$type_primary} en {$matiere_label} à {$lieu} - {$annee}</h2>\n\n";
        
        // ----------------------------------------
        // 2. CHAPEAU INTRODUCTIF (50 mots)
        // ----------------------------------------
        $chapeaux = array(
            "Découvrez cette <strong>réalisation de {$type_list}</strong> en {$matiere_label}, conçue et fabriquée sur mesure pour {$client_label} de {$lieu}. Ce projet de <strong>métallerie sur mesure</strong> illustre parfaitement le savoir-faire artisanal d'AL Métallerie & Soudure, votre artisan métallier dans le {$departement}.",
            "AL Métallerie & Soudure vous présente ce magnifique projet de <strong>{$type_list} sur mesure</strong> réalisé à {$lieu} pour {$client_label}. Cette <strong>fabrication artisanale</strong> en {$matiere_label} témoigne de notre expertise en métallerie dans le {$departement}.",
            "Voici notre dernière création : un{$this->get_article($type_primary)} <strong>{$type_list}</strong> en {$matiere_label}, fabriqué{$this->get_accord($type_primary)} avec passion pour {$client_label} de {$lieu}. Un projet de <strong>métallerie sur mesure</strong> qui allie qualité et esthétique.",
        );
        $html .= "<p>" . $chapeaux[array_rand($chapeaux)] . "</p>\n\n";
        
        // ----------------------------------------
        // 3. SECTION "LE PROJET" (H3)
        // ----------------------------------------
        $html .= "<h3>Le Projet</h3>\n";
        
        $demandes_initiales = array(
            'portail' => "Notre client souhaitait une entrée de propriété à la fois élégante et sécurisée. La demande initiale portait sur un portail robuste, design moderne, s'intégrant parfaitement à l'architecture existante.",
            'garde-corps' => "Le client recherchait une solution de sécurisation pour son escalier/terrasse, alliant conformité aux normes et esthétique contemporaine. L'objectif était de créer un garde-corps design sans compromettre la luminosité.",
            'escalier' => "Ce projet répondait au besoin de créer un accès fonctionnel et esthétique entre deux niveaux. Le client souhaitait un escalier métallique moderne, optimisant l'espace disponible.",
            'default' => "Ce projet de {$type_primary} a été initié suite à la demande de {$client_label} de {$lieu}, souhaitant un ouvrage sur mesure répondant à des critères précis de qualité et d'esthétique."
        );
        
        $type_key = 'default';
        if (stripos($type_primary, 'portail') !== false) $type_key = 'portail';
        elseif (stripos($type_primary, 'garde-corps') !== false || stripos($type_primary, 'rambarde') !== false) $type_key = 'garde-corps';
        elseif (stripos($type_primary, 'escalier') !== false) $type_key = 'escalier';
        
        $html .= "<p>" . $demandes_initiales[$type_key] . "</p>\n";
        
        $contraintes = array(
            "Les contraintes du projet incluaient l'adaptation aux dimensions exactes de l'espace, le respect du budget défini, et une finition résistante aux conditions climatiques du {$departement}.",
            "Plusieurs contraintes techniques ont guidé notre travail : intégration architecturale, respect des normes en vigueur, et durabilité face aux intempéries.",
            "Le cahier des charges imposait des exigences précises : dimensions sur mesure, choix esthétiques spécifiques, et garantie de qualité professionnelle.",
        );
        $html .= "<p>" . $contraintes[array_rand($contraintes)] . "</p>\n\n";
        
        // ----------------------------------------
        // 4. SECTION "NOTRE RÉALISATION" (H3)
        // ----------------------------------------
        $html .= "<h3>Notre Réalisation</h3>\n";
        
        // Matériaux et techniques
        $techniques_soudure = array(
            'acier' => 'soudure MIG/MAG',
            'inox' => 'soudure TIG pour des cordons parfaits',
            'aluminium' => 'soudure TIG spécifique aluminium',
            'fer-forge' => 'techniques traditionnelles de forge à chaud',
        );
        $technique = isset($techniques_soudure[$matiere]) ? $techniques_soudure[$matiere] : 'soudure professionnelle';
        
        $html .= "<p>Pour cette réalisation, nous avons sélectionné le <strong>{$matiere_label}</strong>, un matériau reconnu pour sa robustesse et son rendu esthétique. ";
        $html .= "La fabrication a été réalisée dans notre atelier de Peschadoires, près de Thiers, en utilisant nos techniques de <strong>{$technique}</strong>.</p>\n";
        
        // Finition
        $finitions_desc = array(
            'thermolaquage' => "Le <strong>thermolaquage</strong> appliqué garantit une protection durable (garantie 10 ans) dans la teinte RAL choisie par le client. Ce traitement offre une excellente résistance aux UV et aux intempéries.",
            'galvanisation' => "La <strong>galvanisation à chaud</strong> assure une protection anticorrosion exceptionnelle, idéale pour les installations extérieures soumises aux conditions climatiques du {$departement}.",
            'peinture-epoxy' => "La finition <strong>peinture époxy</strong> bi-composant offre une résistance optimale aux rayures et aux intempéries, tout en sublimant l'aspect de l'ouvrage.",
            'brut' => "L'aspect <strong>brut ciré</strong> met en valeur la beauté naturelle du métal, créant un style industriel authentique très recherché.",
        );
        $finition_desc = isset($finitions_desc[$peinture]) ? $finitions_desc[$peinture] : "La finition <strong>{$peinture}</strong> apporte protection et esthétique à l'ouvrage.";
        $html .= "<p>{$finition_desc}</p>\n";
        
        // Durée et pose
        $html .= "<p>La <strong>fabrication artisanale</strong> s'est étalée sur {$duree}, un délai optimisé grâce à notre organisation et notre expertise. ";
        if ($pose) {
            $html .= "La <strong>pose a été réalisée par nos équipes</strong> à {$lieu}, garantissant une installation professionnelle conforme aux normes en vigueur (NF P01-012 pour les garde-corps, etc.).</p>\n\n";
        } else {
            $html .= "Le client a assuré lui-même l'installation, avec nos conseils techniques pour une mise en œuvre optimale.</p>\n\n";
        }
        
        // ----------------------------------------
        // 5. SECTION "DÉTAILS TECHNIQUES" (H3) - Liste
        // ----------------------------------------
        $html .= "<h3>Détails Techniques</h3>\n";
        $html .= "<ul>\n";
        $html .= "<li><strong>Matériau principal :</strong> {$matiere_label}</li>\n";
        $html .= "<li><strong>Finition :</strong> " . ucfirst(str_replace('-', ' ', $peinture)) . "</li>\n";
        $html .= "<li><strong>Lieu d'installation :</strong> {$lieu} ({$departement})</li>\n";
        $html .= "<li><strong>Durée de fabrication :</strong> {$duree}</li>\n";
        $html .= "<li><strong>Pose incluse :</strong> " . ($pose ? 'Oui, par nos équipes' : 'Non') . "</li>\n";
        $html .= "<li><strong>Garantie structure :</strong> 10 ans</li>\n";
        $html .= "<li><strong>Garantie finition :</strong> 10 ans (thermolaquage)</li>\n";
        $html .= "</ul>\n\n";
        
        // ----------------------------------------
        // 6. SECTION "RÉSULTAT" (H3)
        // ----------------------------------------
        $html .= "<h3>Résultat et Satisfaction Client</h3>\n";
        
        $resultats = array(
            'portail' => "Le portail terminé répond parfaitement aux attentes : une entrée élégante et sécurisée qui valorise la propriété. La qualité de <strong>fabrication artisanale</strong> et la finition soignée garantissent une durabilité exceptionnelle.",
            'garde-corps' => "Le garde-corps installé allie parfaitement sécurité et esthétique. Conforme aux normes NF P01-012, il apporte une touche design moderne tout en assurant une protection optimale.",
            'escalier' => "L'escalier réalisé s'intègre harmonieusement dans l'espace. Sa conception sur mesure optimise la circulation tout en créant un véritable élément architectural qui sublime l'intérieur.",
            'default' => "Cette réalisation illustre notre savoir-faire en <strong>métallerie sur mesure</strong>. La qualité des finitions et l'attention portée aux détails témoignent de notre engagement pour l'excellence."
        );
        $html .= "<p>" . $resultats[$type_key] . "</p>\n\n";
        
        // ----------------------------------------
        // 7. CALL-TO-ACTION FINAL
        // ----------------------------------------
        $html .= "<h3>Un projet similaire ?</h3>\n";
        $html .= "<p><strong>Vous souhaitez un{$this->get_article($type_primary)} {$type_primary} sur mesure</strong> pour votre propriété à {$lieu} ou dans le {$departement} ? ";
        $html .= "Contactez AL Métallerie & Soudure pour un <strong>devis gratuit</strong> et personnalisé.</p>\n";
        $html .= "<p>✅ <strong>Devis gratuit sous 48h</strong><br>\n";
        $html .= "✅ <strong>Fabrication artisanale</strong> dans notre atelier près de Thiers<br>\n";
        $html .= "✅ <strong>Pose professionnelle</strong> incluse sur demande<br>\n";
        $html .= "✅ <strong>Garantie 10 ans</strong> sur la structure et les finitions</p>\n";
        $html .= "<p>📞 <strong>06 73 33 35 32</strong> | 📧 contact@al-metallerie.fr</p>";
        
        return $html;
    }
    
    /**
     * Retourne l'article approprié (e/empty) selon le type
     */
    private function get_article($type) {
        $feminins = array('rampe', 'rambarde', 'grille', 'porte', 'pergola', 'marquise', 'verrière', 'clôture', 'barrière');
        foreach ($feminins as $fem) {
            if (stripos($type, $fem) !== false) {
                return 'e';
            }
        }
        return '';
    }
    
    /**
     * Retourne l'accord approprié (e/empty) selon le type
     */
    private function get_accord($type) {
        $feminins = array('rampe', 'rambarde', 'grille', 'porte', 'pergola', 'marquise', 'verrière', 'clôture', 'barrière');
        foreach ($feminins as $fem) {
            if (stripos($type, $fem) !== false) {
                return 'e';
            }
        }
        return '';
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
        $matiere_line = $matiere ? "⚙️ " . ucfirst($matiere) . "\n" : "";
        $templates[] = "✨ {$data['title']} ✨\n\n"
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
            . "AL-Metallerie Soudure vient de terminer la réalisation de {$type_names} à {$lieu}.\n\n"
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
            . "Votre projet mérite le meilleur. Faites confiance à AL-Metallerie Soudure ! 🤝\n"
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
            . "\nDe la conception à la réalisation, AL-Metallerie Soudure transforme vos idées en réalité. ✨\n\n"
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
        $base_hashtags = "#AL-Metallerie Soudure #{$type_names} #Metallerie #MetalWork #Artisan #SurMesure #{$lieu_hashtag} #Auvergne #AuvergneRhoneAlpes #Ferronnerie";
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
