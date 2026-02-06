<?php
/**
 * Générateur simple sans IA (fallback)
 */

if (!defined('ABSPATH')) {
    exit;
}

class AICG_Simple_Generator {
    
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
    
    private function init_variations() {
        $this->variations = array(
            'intros' => array(
                'Fier de notre savoir-faire artisanal',
                'Spécialistes de la métallerie de précision',
                'Votre expert en solutions métalliques',
                'Artisans métalliers passionnés',
                'Des décennies d\'expérience en métallerie'
            ),
            'services' => array(
                'nous maîtrisons parfaitement',
                'nous excellons dans',
                'notre expertise couvre',
                'nous sommes spécialisés en',
                'notre compétence s\'étend à'
            ),
            'qualities' => array(
                'des réalisations uniques et durables',
                'des ouvrages alliant esthétique et robustesse',
                'des créations sur mesure de qualité',
                'des travaux métalliques exceptionnels',
                'des solutions personnalisées et pérennes'
            ),
            'locations' => array(
                'dans le cœur de l\'Auvergne',
                'à Thiers et ses alentours',
                'en région Auvergne-Rhône-Alpes',
                'dans le Puy-de-Dôme',
                'sur tout le département'
            ),
            'conclusions' => array(
                'Contactez-nous pour un devis personnalisé',
                'Confiez-nous la réalisation de vos projets',
                'Notre équipe est à votre écoute',
                'N\'hésitez pas à nous solliciter',
                'Nous mettons notre expertise à votre service'
            )
        );
    }
    
    public function generate_content($type, $data = array()) {
        $seed = rand(0, 1000);
        mt_srand($seed);
        
        $content = '';
        
        switch ($type) {
            case 'realisation':
                $content = $this->generate_realisation($data);
                break;
                
            case 'city_page':
                $content = $this->generate_city_page($data);
                break;
                
            case 'meta_description':
                $content = $this->generate_meta_description($data);
                break;
                
            default:
                $content = $this->generate_general($data);
        }
        
        mt_srand(); // Reset seed
        return $content;
    }
    
    private function generate_realisation($data) {
        $intro = $this->get_random('intros');
        $service = $this->get_random('services');
        $quality = $this->get_random('qualities');
        $location = $this->get_random('locations');
        $conclusion = $this->get_random('conclusions');
        
        $type = $data['type'] ?? 'structure métallique';
        $materials = $data['materials'] ?? 'acier et fer';
        
        return "# {$intro} : Réalisation {$type}\n\n" .
               "Cette magnifique création en {$materials} témoigne de notre engagement envers l'excellence. " .
               "Nous {$service} l'art de la métallerie pour concevoir {$quality}.\n\n" .
               "Réalissée {$location}, ce projet illustre notre capacité à allier tradition et innovation. " .
               "Chaque détail a été pensé pour garantir durabilité et esthétique.\n\n" .
               "{$conclusion} pour donner vie à vos ambitions métalliques.";
    }
    
    private function generate_city_page($data) {
        $city = $data['city'] ?? 'votre ville';
        $intro = $this->get_random('intros');
        $service = $this->get_random('services');
        $location = $this->get_random('locations');
        
        return "# Métallier Serrurier à {$city}\n\n" .
               "{$intro}, AL-Metallerie Soudure déploie son expertise {$location}. " .
               "Nous {$service} toutes les techniques de soudure et de ferronnerie pour répondre aux besoins spécifiques des habitants de {$city}.\n\n" .
               "Nos artisans qualifiés interviennent pour vos projets de portails, escaliers, garde-corps et structures métalliques sur mesure. " .
               "Chaque réalisation est pensée pour s'intégrer harmonieusement dans l'environnement architectural local.\n\n" .
               "Faites confiance à notre savoir-faire pour vos travaux de métallerie à {$city}.";
    }
    
    private function generate_meta_description($data) {
        $subject = $data['subject'] ?? 'nos services de métallerie';
        $location = $data['location'] ?? 'Thiers';
        
        $templates = array(
            "AL-Metallerie Soudure : {$subject} à {$location}. Expert en soudure, ferronnerie et fabrication métallique sur mesure. Devis gratuit !",
            "Découvrez nos services de métallerie professionnelle à {$location}. {$subject} de qualité par AL-Metallerie Soudure. Contactez-nous !",
            "Spécialiste de la métallerie à {$location}, AL-Metallerie Soudure réalise {$subject}. Qualité, savoir-faire et satisfaction garantie."
        );
        
        return $templates[array_rand($templates)];
    }
    
    private function get_random($type) {
        $variations = $this->variations[$type];
        return $variations[array_rand($variations)];
    }
}
