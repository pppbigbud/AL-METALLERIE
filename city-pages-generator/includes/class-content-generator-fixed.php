<?php
/**
 * Générateur de contenu pour les pages ville avec Groq AI (Version corrigée)
 *
 * @package CityPagesGenerator
 */

if (!defined('ABSPATH')) {
    exit;
}

class CPG_Content_Generator_Fixed {

    private $city_data;
    private $settings;
    private $groq;
    private $use_groq;

    /**
     * Constructeur
     */
    public function __construct($city_data) {
        $this->city_data = $city_data;
        $this->settings = get_option('cpg_settings', []);
        $this->groq = CPG_Groq_Integration::get_instance();
        $this->use_groq = isset($this->settings['use_groq']) ? $this->settings['use_groq'] : false;
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
     * Section Introduction avec Groq
     */
    private function generate_section_intro() {
        if ($this->use_groq && $this->groq->is_configured()) {
            $temperature = isset($this->settings['groq_temperature']) ? floatval($this->settings['groq_temperature']) : 0.7;
            $content = $this->groq->generate_city_intro($this->city_data, $temperature);
            
            if (!is_wp_error($content)) {
                return "<!-- Section Introduction (Groq AI) -->\n" . $content;
            }
        }
        
        // Fallback sur le système original
        return $this->generate_section_intro_original();
    }

    /**
     * Section Services avec Groq
     */
    private function generate_section_services() {
        $city = $this->city_data['city_name'];
        $services = $this->settings['services'] ?? [];

        do_action('cpg_before_city_services', $this->city_data);

        $output = sprintf('<h2>Nos services de métallerie à %s</h2>', $city);
        $output .= "\n<div class=\"cpg-services-grid\">\n";

        foreach ($services as $key => $service) {
            if (!isset($service['enabled']) || !$service['enabled']) {
                continue;
            }

            $name = $service['name'];
            
            // Utiliser Groq si disponible
            if ($this->use_groq && $this->groq->is_configured()) {
                $temperature = isset($this->settings['groq_temperature']) ? floatval($this->settings['groq_temperature']) : 0.7;
                $description = $this->groq->generate_service_description($name, $city, $temperature);
                
                if (is_wp_error($description)) {
                    $description = sprintf($service['description'] . ' Installation à %s et environs.', $city);
                }
            } else {
                $description = sprintf($service['description'] . ' Installation à %s et environs.', $city);
            }

            // Récupérer le lien vers la catégorie de réalisation correspondante
            $term = get_term_by('slug', $key, 'type_realisation');
            $term_link = $term ? get_term_link($term) : home_url('/realisations/');

            $output .= sprintf(
                '<div class="cpg-service-item">
                    <a href="%s" class="cpg-service-link">
                        <div class="cpg-service-icon"><span class="cpg-icon cpg-icon-%s"></span></div>
                        <h3>%s</h3>
                        <p>%s</p>
                    </a>
                </div>',
                esc_url($term_link),
                esc_attr($key),
                esc_html($name),
                esc_html($description)
            );
        }

        $output .= "</div>\n";

        do_action('cpg_after_city_services', $this->city_data);

        return "<!-- Section Services " . ($this->use_groq ? '(Groq AI)' : '(Templates)') . " -->\n" . $output;
    }

    /**
     * Section FAQ avec Groq
     */
    private function generate_section_faq() {
        if ($this->use_groq && $this->groq->is_configured()) {
            $temperature = isset($this->settings['groq_temperature']) ? floatval($this->settings['groq_temperature']) : 0.7;
            $content = $this->groq->generate_city_faq($this->city_data, $temperature);
            
            if (!is_wp_error($content)) {
                return "<!-- Section FAQ (Groq AI) -->\n<h2>Questions fréquentes</h2>\n" . nl2br($content);
            }
        }
        
        // Fallback sur le système original
        return $this->generate_section_faq_original();
    }

    /**
     * Section "Pourquoi nous choisir" avec Groq
     */
    private function generate_section_why_us() {
        if ($this->use_groq && $this->groq->is_configured()) {
            $temperature = isset($this->settings['groq_temperature']) ? floatval($this->settings['groq_temperature']) : 0.7;
            $city = $this->city_data['city_name'];
            $content = $this->groq->generate_why_us_section($city, $temperature);
            
            if (!is_wp_error($content)) {
                return "<!-- Section Pourquoi nous choisir (Groq AI) -->\n<h2>Pourquoi choisir AL Métallerie & Soudure ?</h2>\n" . nl2br($content);
            }
        }
        
        // Fallback sur le système original
        return $this->generate_section_why_us_original();
    }

    /**
     * Section zone d'intervention avec Groq
     */
    private function generate_section_zone() {
        if ($this->use_groq && $this->groq->is_configured()) {
            $temperature = isset($this->settings['groq_temperature']) ? floatval($this->settings['groq_temperature']) : 0.7;
            $city = $this->city_data['city_name'];
            $nearby_cities = isset($this->city_data['nearby_cities']) ? explode(',', $this->city_data['nearby_cities']) : [];
            $content = $this->groq->generate_zone_section($city, $nearby_cities, $temperature);
            
            if (!is_wp_error($content)) {
                return "<!-- Section Zone d'intervention (Groq AI) -->\n<h2>Zone d'intervention</h2>\n" . nl2br($content);
            }
        }
        
        // Fallback sur le système original
        return $this->generate_section_zone_original();
    }

    /**
     * Méthodes originales (fallback)
     */
    private function generate_section_intro_original() {
        $city = $this->city_data['city_name'];
        $department = $this->city_data['department'];
        $postal_code = $this->city_data['postal_code'];
        $distance = $this->city_data['distance_km'];
        $travel_time = $this->city_data['travel_time'];
        $specifics = $this->city_data['local_specifics'];
        $company = $this->settings['company_name'] ?? 'AL Métallerie & Soudure';
        $workshop = $this->settings['workshop_city'] ?? 'Peschadoires';

        $output = "<!-- Section Introduction (Original) -->\n";
        $output .= sprintf('<h2>Votre artisan métallier à %s</h2>', $city);
        $output .= sprintf('<p><strong>%s</strong>, artisan métallier serrurier basé à %s, intervient à <strong>%s (%s)</strong> et dans tout le <strong>%s</strong> pour tous vos projets de métallerie sur mesure.</p>', 
            $company, $workshop, $city, $postal_code, $department);
        $output .= sprintf('<p>📍 <strong>Intervention rapide</strong> : %s est à %s km de notre atelier (environ %s).</p>', 
            $city, $distance, $travel_time);

        return $output;
    }

    private function generate_section_faq_original() {
        $city = $this->city_data['city_name'];
        
        $output = "<!-- Section FAQ (Original) -->\n";
        $output .= "<h2>Questions fréquentes</h2>\n";
        $output .= "<p><strong>Intervenez-vous à {$city} ?</strong><br>Oui, nous intervenons régulièrement à {$city} et dans tout le département.</p>\n";
        $output .= "<p><strong>Quel délai pour un devis ?</strong><br>Généralement sous 48h après votre demande.</p>\n";
        $output .= "<p><strong>Proposez-vous la pose ?</strong><br>Oui, nous assurons fabrication ET pose de tous nos ouvrages.</p>\n";
        
        return $output;
    }

    private function generate_section_why_us_original() {
        $output = "<!-- Section Pourquoi nous choisir (Original) -->\n";
        $output .= "<h2>Pourquoi choisir AL Métallerie & Soudure ?</h2>\n";
        $output .= "<ul>\n";
        $output .= "<li>✓ Artisan local, intervention rapide</li>\n";
        $output .= "<li>✓ Fabrication 100% sur mesure</li>\n";
        $output .= "<li>✓ Devis gratuit sans engagement</li>\n";
        $output .= "<li>✓ Finitions soignées et durables</li>\n";
        $output .= "<li>✓ Conseils personnalisés</li>\n";
        $output .= "</ul>\n";
        
        return $output;
    }

    private function generate_section_zone_original() {
        $city = $this->city_data['city_name'];
        $department = $this->city_data['department'];
        
        $output = "<!-- Section Zone d'intervention (Original) -->\n";
        $output .= "<h2>Zone d'intervention</h2>\n";
        $output .= "<p>Nous intervenons dans tout le {$department} et les départements limitrophes. Contactez-nous pour savoir si nous intervenons dans votre commune.</p>\n";
        
        return $output;
    }

    /**
     * Sections non modifiées
     */
    private function generate_section_realisations() {
        $city = $this->city_data['city_name'];
        $output = "<!-- Section Réalisations -->\n";
        $output .= "<h2>Nos réalisations à {$city}</h2>\n";
        $output .= "[cpg_city_realisations city=\"{$city}\" count=\"6\"]\n";
        
        return $output;
    }

    private function generate_section_contact() {
        $city = $this->city_data['city_name'];
        $company = $this->settings['company_name'] ?? 'AL Métallerie & Soudure';
        $phone = $this->settings['phone'] ?? '06 73 33 35 32';
        $email = $this->settings['email'] ?? 'contact@al-metallerie.fr';
        
        $output = "<!-- Section Contact -->\n";
        $output .= "<h2>Contactez votre métallier à {$city}</h2>\n";
        $output .= "<p>📞 {$phone}<br>📧 {$email}</p>\n";
        $output .= "<p><strong>Demander un devis gratuit</strong></p>\n";
        $output .= "[cpg_contact_form city=\"{$city}\"]\n";
        
        return $output;
    }
}
