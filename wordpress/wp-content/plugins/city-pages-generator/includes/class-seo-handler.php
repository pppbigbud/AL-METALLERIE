<?php
/**
 * Gestion SEO pour les pages ville
 *
 * @package CityPagesGenerator
 */

if (!defined('ABSPATH')) {
    exit;
}

class CPG_SEO_Handler {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Meta tags
        add_action('wp_head', [$this, 'output_meta_tags'], 1);
        add_action('wp_head', [$this, 'output_schema_markup'], 2);
        
        // Titre de la page
        add_filter('pre_get_document_title', [$this, 'filter_document_title'], 20);
        add_filter('document_title_parts', [$this, 'filter_title_parts'], 20);
        
        // Canonical
        add_action('wp_head', [$this, 'output_canonical'], 3);
        
        // Breadcrumbs
        add_shortcode('cpg_breadcrumb', [$this, 'render_breadcrumb']);
    }

    /**
     * Afficher les meta tags
     */
    public function output_meta_tags() {
        if (!is_singular('city_page')) {
            return;
        }

        $post_id = get_the_ID();
        $city_name = get_post_meta($post_id, '_cpg_city_name', true);
        $postal_code = get_post_meta($post_id, '_cpg_postal_code', true);
        $department = get_post_meta($post_id, '_cpg_department', true);
        $meta_description = get_post_meta($post_id, '_cpg_meta_description', true);

        // Description par défaut si vide
        if (empty($meta_description)) {
            $meta_description = sprintf(
                'Artisan métallier à %s (%s). Fabrication sur mesure de portails, garde-corps, escaliers. Devis gratuit. Intervention rapide dans le %s. ☎ 06 73 33 35 32',
                $city_name,
                $postal_code,
                $department
            );
        }

        // Meta description
        echo '<meta name="description" content="' . esc_attr($meta_description) . '">' . "\n";

        // Meta geo
        echo '<meta name="geo.region" content="FR-63">' . "\n";
        echo '<meta name="geo.placename" content="' . esc_attr($city_name) . '">' . "\n";

        // Open Graph
        echo '<meta property="og:title" content="' . esc_attr($this->get_seo_title($post_id)) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($meta_description) . '">' . "\n";
        echo '<meta property="og:type" content="website">' . "\n";
        echo '<meta property="og:url" content="' . esc_url(get_permalink($post_id)) . '">' . "\n";
        echo '<meta property="og:locale" content="fr_FR">' . "\n";

        if (has_post_thumbnail($post_id)) {
            echo '<meta property="og:image" content="' . esc_url(get_the_post_thumbnail_url($post_id, 'large')) . '">' . "\n";
        }

        // Twitter Card
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr($this->get_seo_title($post_id)) . '">' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr($meta_description) . '">' . "\n";
    }

    /**
     * Afficher le Schema.org markup
     */
    public function output_schema_markup() {
        if (!is_singular('city_page')) {
            return;
        }

        $post_id = get_the_ID();
        $city_name = get_post_meta($post_id, '_cpg_city_name', true);
        $settings = get_option('cpg_settings', []);

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            '@id' => get_permalink($post_id) . '#localbusiness',
            'name' => $settings['company_name'] ?? 'AL Métallerie & Soudure',
            'description' => sprintf('Artisan métallier ferronnier à %s', $city_name),
            'url' => home_url(),
            'telephone' => $settings['phone_international'] ?? '+33673333532',
            'email' => $settings['email'] ?? 'contact@al-metallerie.fr',
            'priceRange' => '€€',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => '14 route de Maringues',
                'addressLocality' => 'Peschadoires',
                'postalCode' => '63920',
                'addressRegion' => 'Auvergne-Rhône-Alpes',
                'addressCountry' => 'FR',
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => '45.8167',
                'longitude' => '3.4833',
            ],
            'areaServed' => [
                '@type' => 'City',
                'name' => $city_name,
            ],
            'openingHoursSpecification' => [
                [
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                    'opens' => '08:00',
                    'closes' => '18:00',
                ],
                [
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => 'Saturday',
                    'opens' => '09:00',
                    'closes' => '12:00',
                ],
            ],
            'sameAs' => [
                'https://www.facebook.com/almetallerie',
            ],
            'hasOfferCatalog' => [
                '@type' => 'OfferCatalog',
                'name' => 'Services de métallerie',
                'itemListElement' => [
                    ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Portails sur mesure']],
                    ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Garde-corps']],
                    ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Escaliers métalliques']],
                    ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Ferronnerie d\'art']],
                ],
            ],
        ];

        // Ajouter le logo si disponible
        $logo_id = get_theme_mod('custom_logo');
        if ($logo_id) {
            $schema['image'] = wp_get_attachment_url($logo_id);
            $schema['logo'] = wp_get_attachment_url($logo_id);
        }

        echo '<script type="application/ld+json">' . "\n";
        echo wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        echo "\n</script>\n";

        // Schema BreadcrumbList
        $this->output_breadcrumb_schema($post_id, $city_name);
    }

    /**
     * Schema pour le fil d'Ariane
     */
    private function output_breadcrumb_schema($post_id, $city_name) {
        $department = get_post_meta($post_id, '_cpg_department', true);

        $breadcrumb_schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Accueil',
                    'item' => home_url(),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Métallier ' . $department,
                    'item' => get_post_type_archive_link('city_page'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => 'Métallier à ' . $city_name,
                    'item' => get_permalink($post_id),
                ],
            ],
        ];

        echo '<script type="application/ld+json">' . "\n";
        echo wp_json_encode($breadcrumb_schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        echo "\n</script>\n";
    }

    /**
     * Filtrer le titre du document
     */
    public function filter_document_title($title) {
        if (!is_singular('city_page')) {
            return $title;
        }

        return $this->get_seo_title(get_the_ID());
    }

    /**
     * Filtrer les parties du titre
     */
    public function filter_title_parts($title_parts) {
        if (!is_singular('city_page')) {
            return $title_parts;
        }

        $post_id = get_the_ID();
        $title_parts['title'] = $this->get_seo_title($post_id);

        return $title_parts;
    }

    /**
     * Obtenir le titre SEO
     */
    private function get_seo_title($post_id) {
        $meta_title = get_post_meta($post_id, '_cpg_meta_title', true);

        if (!empty($meta_title)) {
            return $meta_title;
        }

        $city_name = get_post_meta($post_id, '_cpg_city_name', true);
        $postal_code = get_post_meta($post_id, '_cpg_postal_code', true);

        return sprintf('Métallier Ferronnier à %s (%s) | AL Métallerie', $city_name, $postal_code);
    }

    /**
     * Afficher le canonical
     */
    public function output_canonical() {
        if (!is_singular('city_page')) {
            return;
        }

        // Supprimer le canonical par défaut de WordPress
        remove_action('wp_head', 'rel_canonical');

        echo '<link rel="canonical" href="' . esc_url(get_permalink()) . '">' . "\n";
    }

    /**
     * Shortcode pour le fil d'Ariane
     */
    public function render_breadcrumb($atts) {
        if (!is_singular('city_page')) {
            return '';
        }

        $post_id = get_the_ID();
        $city_name = get_post_meta($post_id, '_cpg_city_name', true);
        $department = get_post_meta($post_id, '_cpg_department', true);

        $output = '<nav class="cpg-breadcrumb" aria-label="Fil d\'Ariane">';
        $output .= '<ol class="cpg-breadcrumb-list">';
        $output .= '<li class="cpg-breadcrumb-item"><a href="' . esc_url(home_url()) . '">Accueil</a></li>';
        $output .= '<li class="cpg-breadcrumb-separator">›</li>';
        $output .= '<li class="cpg-breadcrumb-item"><a href="' . esc_url(get_post_type_archive_link('city_page')) . '">Métallier ' . esc_html($department) . '</a></li>';
        $output .= '<li class="cpg-breadcrumb-separator">›</li>';
        $output .= '<li class="cpg-breadcrumb-item cpg-breadcrumb-current" aria-current="page">' . esc_html($city_name) . '</li>';
        $output .= '</ol>';
        $output .= '</nav>';

        return $output;
    }
}
