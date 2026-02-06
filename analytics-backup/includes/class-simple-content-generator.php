<?php
/**
 * Générateur de contenu simple intégré
 * 
 * @package Almetal_Analytics
 */

if (!defined('ABSPATH')) {
    exit;
}

class Almetal_Simple_Content_Generator {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Génère du contenu basé sur le type demandé
     */
    public function generate_content($type, $data = array()) {
        switch ($type) {
            case 'meta_description':
                return $this->generate_meta_description($data);
            case 'content':
                return $this->generate_content_improvement($data);
            default:
                return '';
        }
    }
    
    /**
     * Génère une méta description SEO optimisée
     */
    private function generate_meta_description($data) {
        $type = isset($data['type']) ? $data['type'] : 'page';
        $subject = isset($data['subject']) ? $data['subject'] : '';
        $location = isset($data['location']) ? $data['location'] : '';
        
        $templates = array(
            'page' => array(
                "Découvrez nos services de métallerie à {$location}. {$subject} - Solutions professionnelles sur mesure pour vos projets. Devis gratuit.",
                "Expert en métallerie à {$location}, nous réalisons {$subject}. Qualité, savoir-faire et satisfaction garantie. Contactez-nous.",
                "Métallerie professionnelle à {$location} : {$subject}. Artisans qualifiés, matériaux premium, intervention rapide. Demandez un devis.",
                "Spécialiste de la métallerie à {$location}. {$subject} et installations sur mesure. Plus de 20 ans d'expérience."
            ),
            'post' => array(
                "{$subject} - Actualités et conseils de notre équipe de métalliers à {$location}. Expertise et partage de savoir-faire.",
                "Découvrez {$subject} dans notre actualité. Métallerie d'excellence à {$location}. Tendances et innovations du secteur.",
                "Notre équipe vous présente {$subject}. Articles d'experts en métallerie à {$location}. Conseils pratiques et réalisations."
            ),
            'product' => array(
                "{$subject} - Vente en ligne de produits de métallerie. Expédition rapide depuis {$location}. Qualité professionnelle.",
                "Achetez {$subject} en ligne. Métallerie de qualité à {$location}. Large choix, prix compétitifs, livraison sécurisée.",
                "{$subject} disponible sur notre boutique. Spécialiste de la métallerie à {$location}. Produits certifiés, service client."
            ),
            'default' => array(
                "{$subject} - Métallerie professionnelle à {$location}. Solutions sur mesure, expertise locale, devis gratuit.",
                "Découvrez {$subject} chez votre métallier à {$location}. Qualité artisanale, matériaux premium, satisfaction garantie."
            )
        );
        
        $type_templates = isset($templates[$type]) ? $templates[$type] : $templates['default'];
        return $type_templates[array_rand($type_templates)];
    }
    
    /**
     * Génère une amélioration de contenu
     */
    private function generate_content_improvement($data) {
        $tone = isset($data['tone']) ? $data['tone'] : 'professional';
        $length = isset($data['length']) ? $data['length'] : 'medium';
        $subject = isset($data['subject']) ? $data['subject'] : 'nos services';
        
        $lengths = array(
            'short' => array(80, 120),
            'medium' => array(150, 200),
            'long' => array(250, 300)
        );
        
        $word_count = $lengths[$length];
        
        $contents = array(
            'professional' => array(
                "Notre expertise en métallerie vous garantit des solutions adaptées à vos besoins spécifiques. Nous mettons à votre disposition notre savoir-faire pour réaliser des projets sur mesure, alliant esthétique et fonctionnalité. Chaque réalisation bénéficie de notre attention particulière aux détails et de notre engagement envers la qualité.",
                "Forts de nombreuses années d'expérience dans le domaine de la métallerie, nous proposons des services complets pour tous types de projets. De la conception à la réalisation, notre équipe vous accompagne dans chaque étape pour garantir des résultats à la hauteur de vos attentes.",
                "La métallerie d'art et fonctionnelle est notre spécialité. Nous combinons techniques traditionnelles et innovations modernes pour créer des pièces uniques. Notre engagement envers l'excellence se reflète dans chaque projet que nous entreprenons."
            ),
            'friendly' => array(
                "Bienvenue dans l'univers de la métallerie ! Notre équipe passionnée est là pour donner vie à vos idées. Que vous rêviez d'un portail design, d'une belle rampe ou d'une structure unique, nous sommes à votre écoute pour transformer vos projets en réalité.",
                "La métallerie, c'est notre passion ! Nous adorons travailler le métal pour créer des pièces qui rendront votre espace spécial. N'hésitez pas à nous parler de vos projets, aussi originaux soient-ils. Ensemble, nous trouverons la solution parfaite !",
                "Hello ! Prêts à découvrir tout ce que la métallerie peut faire pour vous ? De la plus petite pièce aux plus grandes structures, nous mettons notre cœur et notre savoir-faire dans chaque réalisation. Votre satisfaction est notre plus belle récompense !"
            ),
            'technical' => array(
                "Nos procédés de fabrication respectent les normes les plus strictes du secteur. Nous utilisons des techniques avancées de découpe, soudure et assemblage pour garantir la précision et la durabilité de chaque pièce. Matériaux certifiés et contrôles qualité à chaque étape.",
                "L'ingénierie métallique appliquée à vos projets : nous maîtrisons l'ensemble des paramètres techniques essentiels - résistance des matériaux, calculs de charge, traitements thermiques. Notre approche scientifique assure des structures fiables et pérennes.",
                "Spécialistes des alliages métalliques et des traitements de surface, nous optimisons chaque composant selon sa fonction. De la sélection des matériaux aux finitions, notre expertise technique garantit des performances supérieures et une longévité accrue."
            )
        );
        
        $base_content = $contents[$tone][array_rand($contents[$tone])];
        
        // Ajuster la longueur
        $words = explode(' ', $base_content);
        if (count($words) > $word_count[1]) {
            $words = array_slice($words, 0, $word_count[1]);
            $base_content = implode(' ', $words) . '...';
        } elseif (count($words) < $word_count[0]) {
            // Ajouter une phrase si trop court
            $extra_sentences = array(
                " Contactez-nous pour en savoir plus sur nos réalisations.",
                " Nous serions ravis de discuter de votre projet.",
                " N'hésitez pas à nous consulter pour un devis personnalisé."
            );
            $base_content .= $extra_sentences[array_rand($extra_sentences)];
        }
        
        return $base_content;
    }
}
