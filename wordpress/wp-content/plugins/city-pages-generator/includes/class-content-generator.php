<?php
/**
 * Générateur de contenu pour les pages ville
 *
 * @package CityPagesGenerator
 */

if (!defined('ABSPATH')) {
    exit;
}

class CPG_Content_Generator {

    private $city_data;
    private $settings;
    private $variation;

    /**
     * Constructeur
     */
    public function __construct($city_data) {
        $this->city_data = $city_data;
        $this->settings = get_option('cpg_settings', []);
        $this->variation = isset($city_data['variation']) ? intval($city_data['variation']) : rand(1, 4);
    }

    /**
     * Générer le contenu complet
     */
    public function generate() {
        $sections = [];
        $enabled = isset($this->settings['sections_enabled']) ? $this->settings['sections_enabled'] : [];
        $order = isset($this->settings['sections_order']) ? $this->settings['sections_order'] : ['intro', 'services', 'realisations', 'why_us', 'zone', 'contact', 'faq'];

        foreach ($order as $section) {
            if (!isset($enabled[$section]) || $enabled[$section]) {
                $method = 'generate_section_' . $section;
                if (method_exists($this, $method)) {
                    $sections[] = $this->$method();
                }
            }
        }

        $content = implode("\n\n", array_filter($sections));

        // Appliquer le filtre pour personnalisation
        return apply_filters('cpg_city_page_content', $content, $this->city_data);
    }

    /**
     * Section Introduction
     */
    private function generate_section_intro() {
        $city = $this->city_data['city_name'];
        $department = $this->city_data['department'];
        $postal_code = $this->city_data['postal_code'];
        $distance = $this->city_data['distance_km'];
        $travel_time = $this->city_data['travel_time'];
        $specifics = $this->city_data['local_specifics'];
        $company = $this->settings['company_name'] ?? 'AL Métallerie & Soudure';
        $workshop = $this->settings['workshop_city'] ?? 'Peschadoires';

        // Variations d'introduction pour éviter le contenu dupliqué
        $intros = [
            1 => sprintf(
                '<p><strong>%s</strong>, votre artisan métallier ferronnier à <strong>%s</strong> (%s), vous accompagne dans tous vos projets de métallerie sur mesure. Basé à %s, à seulement %s km de %s, nous intervenons rapidement pour la fabrication et la pose de vos ouvrages métalliques.</p>',
                $company, $city, $postal_code, $workshop, $distance, $city
            ),
            2 => sprintf(
                '<p>Vous recherchez un <strong>métallier qualifié à %s</strong> ? %s intervient dans tout le %s pour vos projets de métallerie et ferronnerie. Notre atelier de %s, situé à %s de %s, nous permet d\'assurer des délais d\'intervention rapides.</p>',
                $city, $company, $department, $workshop, $travel_time, $city
            ),
            3 => sprintf(
                '<p>Spécialiste de la <strong>métallerie sur mesure à %s</strong> et ses environs, %s met son savoir-faire artisanal au service de vos projets. Depuis notre atelier de %s, nous desservons %s et l\'ensemble du %s.</p>',
                $city, $company, $workshop, $city, $department
            ),
            4 => sprintf(
                '<p><strong>Artisan métallier ferronnier</strong> intervenant à %s (%s), %s réalise tous vos ouvrages métalliques sur mesure. Notre proximité avec %s (à %s) nous permet une grande réactivité pour vos projets.</p>',
                $city, $postal_code, $company, $city, $travel_time
            ),
        ];

        $intro = $intros[$this->variation] ?? $intros[1];

        // Ajouter les spécificités locales si présentes
        if (!empty($specifics)) {
            $specifics_text = sprintf(
                '<p>Nous intervenons dans tous les quartiers de %s : %s. Que vous soyez un particulier ou un professionnel, nous nous déplaçons pour étudier votre projet et vous proposer un devis gratuit et personnalisé.</p>',
                $city,
                $specifics
            );
            $intro .= "\n" . $specifics_text;
        }

        // Paragraphe sur les services
        $services_intro_variations = [
            1 => sprintf('<p>Notre expertise couvre l\'ensemble des travaux de métallerie : portails sur mesure, garde-corps, escaliers métalliques, grilles de sécurité, pergolas, verrières et ferronnerie d\'art. Chaque réalisation est fabriquée dans notre atelier puis posée par nos soins à %s.</p>', $city),
            2 => sprintf('<p>De la conception à la pose, nous prenons en charge l\'intégralité de votre projet de métallerie à %s. Portails, rambardes, escaliers, structures métalliques... Tous nos ouvrages sont fabriqués sur mesure dans notre atelier.</p>', $city),
            3 => sprintf('<p>Fabrication artisanale et pose professionnelle : %s vous garantit des ouvrages métalliques de qualité à %s. Nous travaillons l\'acier, l\'aluminium et le fer forgé pour créer des pièces uniques adaptées à vos besoins.</p>', $company, $city),
            4 => sprintf('<p>Pour tous vos besoins en métallerie à %s, faites confiance à un artisan local expérimenté. Nous réalisons portails, garde-corps, escaliers et créations sur mesure avec un souci constant de qualité et de finition.</p>', $city),
        ];

        $intro .= "\n" . ($services_intro_variations[$this->variation] ?? $services_intro_variations[1]);

        return "<!-- Section Introduction -->\n" . $intro;
    }

    /**
     * Section Services
     */
    private function generate_section_services() {
        $city = $this->city_data['city_name'];
        $services = $this->settings['services'] ?? [];

        do_action('cpg_before_city_services', $this->city_data);

        $output = sprintf('<h2>Nos services de métallerie à %s</h2>', $city);
        $output .= "\n<div class=\"cpg-services-grid\">\n";

        $service_descriptions = $this->get_service_descriptions();

        foreach ($services as $key => $service) {
            if (!isset($service['enabled']) || !$service['enabled']) {
                continue;
            }

            $name = $service['name'];
            $description = isset($service_descriptions[$key][$this->variation]) 
                ? sprintf($service_descriptions[$key][$this->variation], $city)
                : sprintf($service['description'] . ' Installation à %s et environs.', $city);

            $output .= sprintf(
                '<div class="cpg-service-item">
                    <div class="cpg-service-icon"><span class="cpg-icon cpg-icon-%s"></span></div>
                    <h3>%s</h3>
                    <p>%s</p>
                </div>',
                esc_attr($key),
                esc_html($name),
                esc_html($description)
            );
        }

        $output .= "</div>\n";

        do_action('cpg_after_city_services', $this->city_data);

        return "<!-- Section Services -->\n" . $output;
    }

    /**
     * Descriptions de services avec variations
     */
    private function get_service_descriptions() {
        return [
            'portails' => [
                1 => 'Fabrication et pose de portails sur mesure à %s. Portails coulissants, battants, motorisés. Acier, aluminium ou fer forgé selon vos envies.',
                2 => 'Votre portail sur mesure à %s : coulissant ou battant, nous créons le portail qui s\'intègre parfaitement à votre propriété.',
                3 => 'Spécialiste du portail métallique à %s. Conception personnalisée, fabrication artisanale et pose professionnelle incluse.',
                4 => 'Portails d\'entrée sur mesure pour les habitants de %s. Large choix de styles et finitions, motorisation possible.',
            ],
            'garde_corps' => [
                1 => 'Garde-corps et rambardes sur mesure à %s. Sécurisez vos escaliers, balcons et terrasses avec style.',
                2 => 'Installation de garde-corps à %s : intérieurs ou extérieurs, nous concevons des rambardes alliant sécurité et esthétique.',
                3 => 'Garde-corps métalliques pour %s et environs. Designs contemporains ou classiques, conformes aux normes de sécurité.',
                4 => 'Rambardes et garde-corps personnalisés à %s. Acier, inox ou fer forgé, avec ou sans verre.',
            ],
            'escaliers' => [
                1 => 'Escaliers métalliques sur mesure à %s. Droits, quart tournant ou hélicoïdaux, nous réalisons l\'escalier de vos rêves.',
                2 => 'Création d\'escaliers en métal à %s. Structure acier avec marches bois, verre ou métal selon vos préférences.',
                3 => 'Votre escalier sur mesure à %s : design industriel, contemporain ou classique. Fabrication et pose par nos soins.',
                4 => 'Escaliers intérieurs et extérieurs à %s. Conception 3D, fabrication artisanale, installation professionnelle.',
            ],
            'grilles' => [
                1 => 'Grilles de sécurité et de défense à %s. Protégez efficacement vos fenêtres et ouvertures.',
                2 => 'Installation de grilles de protection à %s. Solutions anti-intrusion esthétiques et robustes.',
                3 => 'Grilles de fenêtre sur mesure pour %s. Sécurité renforcée sans compromettre l\'esthétique de votre façade.',
                4 => 'Sécurisez votre habitation à %s avec nos grilles de défense sur mesure. Devis gratuit.',
            ],
            'pergolas' => [
                1 => 'Pergolas et structures métalliques à %s. Créez un espace extérieur agréable et protégé.',
                2 => 'Pergola sur mesure à %s : bioclimatique, adossée ou autoportée. Profitez de votre terrasse toute l\'année.',
                3 => 'Aménagez votre extérieur à %s avec une pergola métallique design. Fabrication et pose incluses.',
                4 => 'Pergolas, auvents et carports à %s. Structures robustes et élégantes pour votre jardin.',
            ],
            'verrieres' => [
                1 => 'Verrières d\'intérieur style atelier à %s. Apportez lumière et caractère à votre intérieur.',
                2 => 'Création de verrières sur mesure à %s. Cloisons vitrées, séparations design, style industriel.',
                3 => 'Verrière atelier pour votre maison à %s. Fabrication artisanale, pose soignée.',
                4 => 'Transformez votre intérieur à %s avec une verrière sur mesure. Devis gratuit.',
            ],
            'ferronnerie' => [
                1 => 'Ferronnerie d\'art à %s. Créations uniques, pièces décoratives, restauration de ferronnerie ancienne.',
                2 => 'Artisan ferronnier à %s : portillons, grilles décoratives, éléments de décoration sur mesure.',
                3 => 'Ferronnerie traditionnelle et contemporaine à %s. Savoir-faire artisanal pour des pièces uniques.',
                4 => 'Créations en fer forgé à %s. Mobilier, décoration, restauration. L\'art du métal au service de vos envies.',
            ],
            'mobilier' => [
                1 => 'Mobilier métallique sur mesure à %s. Tables, étagères, consoles, créations personnalisées.',
                2 => 'Création de mobilier en métal à %s. Design industriel ou contemporain, pièces uniques.',
                3 => 'Mobilier sur mesure pour %s : tables, bibliothèques, rangements. Métal et bois, métal et verre.',
                4 => 'Votre mobilier personnalisé à %s. Nous créons les meubles qui correspondent à votre intérieur.',
            ],
        ];
    }

    /**
     * Section Réalisations
     */
    private function generate_section_realisations() {
        $city = $this->city_data['city_name'];
        
        $output = sprintf('<h2>Nos réalisations à %s et alentours</h2>', $city);
        $output .= sprintf('<p>Découvrez quelques-unes de nos réalisations à %s et dans les communes environnantes. Chaque projet est unique et réalisé sur mesure selon les besoins de nos clients.</p>', $city);
        
        // Shortcode pour afficher les réalisations
        $output .= "\n[cpg_city_realisations city=\"" . esc_attr($city) . "\" count=\"6\"]\n";

        return "<!-- Section Réalisations -->\n" . $output;
    }

    /**
     * Section Pourquoi nous choisir
     */
    private function generate_section_why_us() {
        $city = $this->city_data['city_name'];
        $travel_time = $this->city_data['travel_time'];
        $company = $this->settings['company_name'] ?? 'AL Métallerie & Soudure';

        $output = sprintf('<h2>Pourquoi choisir %s pour vos projets à %s ?</h2>', $company, $city);

        $reasons_variations = [
            1 => [
                sprintf('Artisan local, intervention rapide à %s (en %s)', $city, $travel_time),
                'Devis gratuit et détaillé sous 48h',
                'Fabrication française dans notre atelier',
                'Plus de 15 ans d\'expérience en métallerie',
                'Travail sur mesure et de qualité',
                'Pose incluse et garantie décennale',
            ],
            2 => [
                sprintf('Proximité : nous intervenons à %s en %s', $city, $travel_time),
                'Devis personnalisé gratuit',
                'Fabrication artisanale locale',
                'Expertise reconnue depuis 15 ans',
                'Chaque projet est unique et sur mesure',
                'Installation professionnelle garantie',
            ],
            3 => [
                sprintf('Réactivité : déplacement à %s sous 48h', $city),
                'Étude et devis sans engagement',
                'Production 100% française',
                'Savoir-faire artisanal éprouvé',
                'Solutions personnalisées à vos besoins',
                'Garantie et service après-vente',
            ],
            4 => [
                sprintf('Service de proximité pour %s et environs', $city),
                'Devis gratuit, sans surprise',
                'Atelier de fabrication local',
                'Artisan expérimenté et passionné',
                'Créations uniques et sur mesure',
                'Pose soignée et garantie',
            ],
        ];

        $reasons = $reasons_variations[$this->variation] ?? $reasons_variations[1];

        $output .= "\n<ul class=\"cpg-why-us-list\">\n";
        foreach ($reasons as $reason) {
            $output .= sprintf('<li><span class="cpg-check-icon">✓</span> %s</li>', esc_html($reason)) . "\n";
        }
        $output .= "</ul>\n";

        return "<!-- Section Pourquoi nous choisir -->\n" . $output;
    }

    /**
     * Section Zone d'intervention
     */
    private function generate_section_zone() {
        $city = $this->city_data['city_name'];
        $nearby = $this->city_data['nearby_cities'] ?? [];
        $department = $this->city_data['department'];

        $output = sprintf('<h2>Communes desservies autour de %s</h2>', $city);
        $output .= sprintf('<p>En plus de %s, nous intervenons dans toutes les communes environnantes du %s :</p>', $city, $department);

        if (!empty($nearby)) {
            $output .= "\n<div class=\"cpg-nearby-cities-list\">\n";
            foreach ($nearby as $nearby_city) {
                $output .= sprintf('<span class="cpg-nearby-city">%s</span>', esc_html($nearby_city));
            }
            $output .= "\n</div>\n";
        }

        $output .= sprintf('<p>Vous habitez une autre commune proche de %s ? Contactez-nous pour vérifier que nous intervenons dans votre secteur.</p>', $city);

        return "<!-- Section Zone d'intervention -->\n" . $output;
    }

    /**
     * Section Contact
     */
    private function generate_section_contact() {
        $city = $this->city_data['city_name'];
        $travel_time = $this->city_data['travel_time'];
        $phone = $this->settings['phone'] ?? '06 73 33 35 32';
        $email = $this->settings['email'] ?? 'contact@al-metallerie.fr';
        $address = $this->settings['workshop_address'] ?? '14 route de Maringues, 63920 Peschadoires';

        $output = sprintf('<h2>Contactez votre métallier à %s</h2>', $city);

        $output .= '<div class="cpg-contact-section">';
        
        // Infos de contact
        $output .= '<div class="cpg-contact-info">';
        $output .= sprintf('<p><strong>Téléphone :</strong> <a href="tel:+33%s" class="cpg-phone-link">%s</a></p>', 
            preg_replace('/[^0-9]/', '', $phone), 
            esc_html($phone)
        );
        $output .= sprintf('<p><strong>Email :</strong> <a href="mailto:%s">%s</a></p>', 
            esc_attr($email), 
            esc_html($email)
        );
        $output .= sprintf('<p><strong>Atelier :</strong> %s</p>', esc_html($address));
        $output .= sprintf('<p class="cpg-travel-info"><strong>Intervention à %s :</strong> %s depuis notre atelier</p>', 
            esc_html($city), 
            esc_html($travel_time)
        );
        $output .= '</div>';

        // Formulaire de contact (shortcode)
        $output .= '<div class="cpg-contact-form">';
        $output .= '[cpg_contact_form city="' . esc_attr($city) . '"]';
        $output .= '</div>';

        $output .= '</div>';

        // Map
        $output .= "\n[cpg_city_map city=\"" . esc_attr($city) . "\"]\n";

        return "<!-- Section Contact -->\n" . $output;
    }

    /**
     * Section FAQ
     */
    private function generate_section_faq() {
        $city = $this->city_data['city_name'];
        $travel_time = $this->city_data['travel_time'];
        $department = $this->city_data['department'];

        $output = sprintf('<h2>Questions fréquentes - Métallerie à %s</h2>', $city);

        $faqs = [
            [
                'question' => sprintf('Intervenez-vous à %s pour les particuliers ?', $city),
                'answer' => sprintf('Oui, nous intervenons à %s aussi bien pour les particuliers que pour les professionnels. Que ce soit pour un portail, un garde-corps, un escalier ou tout autre ouvrage métallique, nous nous déplaçons gratuitement pour étudier votre projet.', $city),
            ],
            [
                'question' => sprintf('Quel est le délai pour un devis à %s ?', $city),
                'answer' => sprintf('Nous nous engageons à vous fournir un devis détaillé sous 48h après notre visite à %s. Le déplacement et le devis sont gratuits et sans engagement.', $city),
            ],
            [
                'question' => sprintf('Combien coûte un portail sur mesure à %s ?', $city),
                'answer' => sprintf('Le prix d\'un portail sur mesure à %s dépend de nombreux facteurs : dimensions, matériaux, design, motorisation... Comptez à partir de 1 500€ pour un portail battant simple. Contactez-nous pour un devis précis adapté à votre projet.', $city),
            ],
            [
                'question' => sprintf('Proposez-vous la pose à %s ?', $city),
                'answer' => sprintf('Absolument ! Nous assurons la fabrication dans notre atelier ET la pose à %s. Nos équipes se déplacent dans tout le %s pour installer vos ouvrages métalliques dans les règles de l\'art.', $city, $department),
            ],
            [
                'question' => sprintf('En combien de temps pouvez-vous intervenir à %s ?', $city),
                'answer' => sprintf('Situés à %s de %s, nous pouvons généralement effectuer une première visite sous 48 à 72h. Pour les urgences, n\'hésitez pas à nous appeler directement.', $travel_time, $city),
            ],
        ];

        $output .= "\n<div class=\"cpg-faq-list\" itemscope itemtype=\"https://schema.org/FAQPage\">\n";

        foreach ($faqs as $faq) {
            $output .= '<div class="cpg-faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">';
            $output .= sprintf('<h3 itemprop="name">%s</h3>', esc_html($faq['question']));
            $output .= '<div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">';
            $output .= sprintf('<p itemprop="text">%s</p>', esc_html($faq['answer']));
            $output .= '</div>';
            $output .= '</div>';
        }

        $output .= "</div>\n";

        return "<!-- Section FAQ -->\n" . $output;
    }
}
