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
        
        if (!empty($data['client'])) {
            $prompt .= "\n- Client : {$data['client']}";
        }
        
        if (!empty($data['duree'])) {
            $prompt .= "\n- Durée : {$data['duree']}";
        }
        
        $prompt .= "\n\nLa description doit :
- Faire entre 150 et 160 caractères (optimal pour Yoast SEO)
- Inclure les mots-clés : métallerie, {$type_names}, {$lieu}
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
        
        if (!empty($data['client'])) {
            $prompt .= "\n- Client : {$data['client']}";
        }
        
        $prompt .= "\n\nLe post doit :
- Être conversationnel et chaleureux
- Faire 3-4 paragraphes
- Inclure des émojis pertinents
- Mentionner AL Métallerie
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
- Lieu : {$lieu}

La légende doit :
- Être courte et impactante (2-3 lignes)
- Inclure 10-15 hashtags pertinents
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
        
        if (!empty($data['duree'])) {
            $prompt .= "\n- Durée : {$data['duree']}";
        }
        
        $prompt .= "\n\nLe post doit :
- Être professionnel et technique
- Faire 4-5 paragraphes
- Mettre en avant l'expertise et le savoir-faire
- Inclure des détails techniques
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
        
        $templates = array(
            // Template 1 : Classique
            "AL Métallerie vous présente sa réalisation de {$type_names} à {$lieu} ({$date}). Découvrez notre savoir-faire en métallerie sur-mesure.",
            
            // Template 2 : Focus projet
            "Découvrez notre projet de {$type_names} réalisé à {$lieu} en {$date}. AL Métallerie, votre expert en métallerie sur-mesure.",
            
            // Template 3 : Focus expertise
            "{$type_names} sur-mesure à {$lieu} par AL Métallerie ({$date}). Expertise et qualité pour vos projets de métallerie.",
            
            // Template 4 : Focus résultat
            "Projet de {$type_names} finalisé à {$lieu} en {$date}. AL Métallerie : conception et réalisation de métallerie haut de gamme.",
            
            // Template 5 : Focus local
            "AL Métallerie réalise votre {$type_names} à {$lieu}. Découvrez notre dernière réalisation de {$date}. Métallerie artisanale."
        );
        
        // Choisir un template aléatoire
        return $templates[array_rand($templates)];
    }
    
    /**
     * Template Facebook (fallback) - 5 variations
     */
    private function generate_facebook_template($data) {
        $type_names = !empty($data['types']) ? implode(' et ', wp_list_pluck($data['types'], 'name')) : 'métallerie';
        $lieu = !empty($data['lieu']) ? $data['lieu'] : 'Clermont-Ferrand';
        $client_text = !empty($data['client']) ? "Merci à {$data['client']} pour leur confiance ! 🙏\n\n" : "";
        
        $templates = array();
        
        // Template 1 : Enthousiaste
        $templates[] = "🔥 Nouvelle réalisation AL Métallerie ! 🔥\n\n"
            . "Nous sommes fiers de vous présenter notre dernier projet : {$data['title']} à {$lieu}.\n\n"
            . "✨ Un travail de {$type_names} réalisé avec passion et expertise par notre équipe.\n\n"
            . $client_text
            . "📞 Vous avez un projet similaire ? Contactez-nous !\n"
            . "👉 www.al-metallerie.fr";
        
        // Template 2 : Storytelling
        $templates[] = "Il y a quelques semaines, nous avons eu le plaisir de réaliser ce magnifique projet à {$lieu}... 🏗️\n\n"
            . "Aujourd'hui, nous sommes ravis de vous dévoiler : {$data['title']} !\n\n"
            . "Un projet de {$type_names} qui reflète notre engagement pour la qualité et le sur-mesure. 💪\n\n"
            . $client_text
            . "Envie d'un projet unique ? Parlons-en ! 💬\n"
            . "👉 www.al-metallerie.fr";
        
        // Template 3 : Professionnel
        $templates[] = "✅ Projet finalisé !\n\n"
            . "AL Métallerie vient de terminer la réalisation de {$type_names} à {$lieu}.\n\n"
            . "📐 {$data['title']}\n"
            . "🔧 Conception et pose par nos équipes\n"
            . "⭐ Résultat à la hauteur des attentes\n\n"
            . $client_text
            . "Un projet en tête ? Demandez votre devis gratuit !\n"
            . "👉 www.al-metallerie.fr";
        
        // Template 4 : Focus client
        $templates[] = "🎉 Un nouveau projet dont nous sommes particulièrement fiers !\n\n"
            . ($client_text ? "Nous avons eu le plaisir de collaborer avec {$data['client']} pour réaliser ce projet de {$type_names} à {$lieu}.\n\n" : "Découvrez notre dernière réalisation de {$type_names} à {$lieu}.\n\n")
            . "Le résultat ? {$data['title']} qui allie esthétique et robustesse ! 💎\n\n"
            . "Votre projet mérite le meilleur. Faites confiance à AL Métallerie ! 🤝\n"
            . "👉 www.al-metallerie.fr";
        
        // Template 5 : Avant/Après style
        $templates[] = "📸 Découvrez notre dernière création !\n\n"
            . "Lieu : {$lieu} 📍\n"
            . "Projet : {$data['title']}\n"
            . "Type : {$type_names} 🔨\n\n"
            . "De la conception à la réalisation, AL Métallerie transforme vos idées en réalité. ✨\n\n"
            . $client_text
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
        
        // Hashtags de base
        $base_hashtags = "#ALMetallerie #{$type_names} #Metallerie #MetalWork #Artisan #SurMesure #{$lieu_hashtag} #Auvergne #AuvergneRhoneAlpes #Ferronnerie #Acier #Design #Architecture";
        
        $templates = array();
        
        // Template 1 : Classique avec émojis
        $templates[] = "✨ {$data['title']} ✨\n\n"
            . "Nouvelle réalisation à {$lieu} 🔥\n"
            . "Swipe pour voir toutes les photos ! 👉\n\n"
            . $base_hashtags . " #Renovation #Construction";
        
        // Template 2 : Question engageante
        $templates[] = "Qu'en pensez-vous ? 🤔\n\n"
            . "Notre dernière création : {$data['title']}\n"
            . "📍 {$lieu}\n\n"
            . "Double tap si tu aimes ! ❤️\n\n"
            . $base_hashtags . " #MetalDesign #CustomMade";
        
        // Template 3 : Style minimaliste
        $templates[] = "{$data['title']}\n"
            . "{$lieu} | " . date('Y') . "\n\n"
            . "🔨 Métallerie sur-mesure\n"
            . "✨ Conception & réalisation\n"
            . "📸 Swipe →\n\n"
            . $base_hashtags . " #Craftsmanship #HandMade";
        
        // Template 4 : Focus processus
        $templates[] = "Du dessin à la réalisation... 📐➡️🔨\n\n"
            . "{$data['title']} à {$lieu}\n\n"
            . "Chaque projet est unique, comme vous ! 💎\n"
            . "Découvrez le résultat en images 👉\n\n"
            . $base_hashtags . " #Process #MadeInFrance";
        
        // Template 5 : Style émojis
        $templates[] = "🏗️ Projet : {$data['title']}\n"
            . "📍 Lieu : {$lieu}\n"
            . "🔧 Type : {$type_names}\n"
            . "✅ Statut : Terminé\n\n"
            . "Votre projet mérite le meilleur ! 💪\n\n"
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
        
        $templates = array();
        
        // Template 1 : Professionnel classique
        $templates[] = "Nouvelle réalisation AL Métallerie\n\n"
            . "Nous sommes heureux de partager notre dernière réalisation : {$data['title']} à {$lieu} ({$date}).\n\n"
            . "Ce projet de {$type_names} illustre notre expertise et notre engagement envers la qualité. {$duree_text}ce chantier a mobilisé notre savoir-faire technique et notre sens du détail.\n\n"
            . "Chez AL Métallerie, chaque projet est unique et conçu sur-mesure pour répondre aux besoins spécifiques de nos clients.\n\n"
            . "Vous avez un projet de métallerie ? Parlons-en !\n"
            . "📧 contact@al-metallerie.fr";
        
        // Template 2 : Focus expertise technique
        $templates[] = "Expertise métallerie | Projet finalisé\n\n"
            . "AL Métallerie vient de finaliser un projet de {$type_names} à {$lieu}.\n\n"
            . "📐 Projet : {$data['title']}\n"
            . "📅 Date : {$date}\n"
            . ($duree_text ? "⏱️ Durée : {$data['duree']}\n\n" : "\n")
            . "Notre approche :\n"
            . "• Étude technique approfondie\n"
            . "• Conception sur-mesure\n"
            . "• Réalisation par des artisans qualifiés\n"
            . "• Suivi qualité rigoureux\n\n"
            . "AL Métallerie : votre partenaire pour des réalisations durables et esthétiques.\n\n"
            . "Contact : contact@al-metallerie.fr";
        
        // Template 3 : Focus résultat client
        $templates[] = "Satisfaction client | Projet livré\n\n"
            . "Retour sur notre dernière réalisation à {$lieu} : {$data['title']}.\n\n"
            . "Ce projet de {$type_names} a été mené de bout en bout par nos équipes. {$duree_text}nous avons su répondre aux exigences techniques et esthétiques de ce chantier.\n\n"
            . "Notre priorité ? La satisfaction de nos clients et la qualité de nos ouvrages.\n\n"
            . "AL Métallerie accompagne les particuliers et professionnels dans leurs projets de métallerie sur-mesure en Auvergne-Rhône-Alpes.\n\n"
            . "Un projet ? Échangeons : contact@al-metallerie.fr";
        
        // Template 4 : Style success story
        $templates[] = "Success Story | {$data['title']}\n\n"
            . "Fiers de partager cette réalisation qui illustre notre savoir-faire en {$type_names}.\n\n"
            . "🎯 Objectif : Créer une solution sur-mesure répondant aux contraintes techniques et esthétiques\n"
            . "📍 Localisation : {$lieu}\n"
            . "📆 Réalisation : {$date}\n"
            . ($duree_text ? "⏱️ Délai : {$data['duree']}\n\n" : "\n")
            . "Résultat : Un ouvrage qui allie robustesse, design et durabilité.\n\n"
            . "AL Métallerie : 20 ans d'expérience au service de vos projets.\n\n"
            . "Discutons de votre projet : contact@al-metallerie.fr";
        
        // Template 5 : Focus innovation/qualité
        $templates[] = "Qualité & Innovation | Nouvelle réalisation\n\n"
            . "AL Métallerie présente : {$data['title']}\n\n"
            . "Un projet de {$type_names} qui démontre notre capacité à allier tradition artisanale et techniques modernes.\n\n"
            . "📍 {$lieu} | {$date}\n"
            . ($duree_text ? "⏱️ {$data['duree']} de travail minutieux\n\n" : "\n")
            . "Notre engagement :\n"
            . "✓ Matériaux de qualité supérieure\n"
            . "✓ Finitions soignées\n"
            . "✓ Respect des délais\n"
            . "✓ Garantie et suivi\n\n"
            . "Votre projet mérite une expertise reconnue. Contactez AL Métallerie.\n\n"
            . "📧 contact@al-metallerie.fr";
        
        // Choisir un template aléatoire
        return $templates[array_rand($templates)];
    }
}
