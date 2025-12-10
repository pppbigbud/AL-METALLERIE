<?php
/**
 * Fonctions SEO - City Pages Generator
 */

if (!defined('ABSPATH')) exit;

// Meta tags
add_action('wp_head', 'cpg_meta_tags', 1);
function cpg_meta_tags() {
    if (!is_singular('city_page')) return;
    
    $id = get_the_ID();
    $city = get_post_meta($id, '_cpg_city_name', true);
    $postal = get_post_meta($id, '_cpg_postal_code', true);
    $dept = get_post_meta($id, '_cpg_department', true);
    
    if (!$city) return;
    
    $s = get_option('cpg_settings', []);
    $company = $s['company_name'] ?? 'AL Métallerie & Soudure';
    $desc = "Métallier ferronnier à {$city} ({$postal}). {$company} : portails, garde-corps, escaliers sur mesure. Devis gratuit, intervention {$dept}.";
    
    echo "\n<!-- CPG SEO -->\n";
    echo '<meta name="description" content="'.esc_attr($desc).'">'."\n";
    echo '<meta name="geo.placename" content="'.esc_attr($city).'">'."\n";
    echo '<meta name="geo.region" content="FR-'.esc_attr(substr($postal,0,2)).'">'."\n";
    echo '<meta property="og:title" content="Métallier à '.esc_attr($city).' | '.esc_attr($company).'">'."\n";
    echo '<meta property="og:description" content="'.esc_attr($desc).'">'."\n";
    echo '<meta property="og:type" content="website">'."\n";
    echo '<meta property="og:url" content="'.esc_url(get_permalink()).'">'."\n";
    echo "<!-- /CPG SEO -->\n\n";
}

// Titre
add_filter('pre_get_document_title', 'cpg_title', 20);
function cpg_title($title) {
    if (!is_singular('city_page')) return $title;
    $city = get_post_meta(get_the_ID(), '_cpg_city_name', true);
    $postal = get_post_meta(get_the_ID(), '_cpg_postal_code', true);
    return $city ? "Métallier Ferronnier à {$city} ({$postal}) | AL Métallerie" : $title;
}

// Schema.org
add_action('wp_head', 'cpg_schema', 5);
function cpg_schema() {
    if (!is_singular('city_page')) return;
    
    $id = get_the_ID();
    $city = get_post_meta($id, '_cpg_city_name', true);
    $postal = get_post_meta($id, '_cpg_postal_code', true);
    $dept = get_post_meta($id, '_cpg_department', true);
    
    if (!$city) return;
    
    $s = get_option('cpg_settings', []);
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        'name' => $s['company_name'] ?? 'AL Métallerie & Soudure',
        'description' => "Artisan métallier ferronnier à {$city}, {$dept}",
        'url' => get_permalink(),
        'telephone' => $s['phone'] ?? '06 73 33 35 32',
        'email' => $s['email'] ?? 'contact@al-metallerie.fr',
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $s['address'] ?? '14 route de Maringues',
            'addressLocality' => $s['workshop_city'] ?? 'Peschadoires',
            'postalCode' => '63920',
            'addressCountry' => 'FR',
        ],
        'areaServed' => [
            '@type' => 'City',
            'name' => $city,
            'postalCode' => $postal,
        ],
        'priceRange' => '€€',
    ];
    
    echo "\n<script type=\"application/ld+json\">\n";
    echo json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    echo "\n</script>\n";
}

// Breadcrumb shortcode
add_shortcode('cpg_breadcrumb', 'cpg_breadcrumb');
function cpg_breadcrumb() {
    if (!is_singular('city_page')) return '';
    
    $city = get_post_meta(get_the_ID(), '_cpg_city_name', true);
    $dept = get_post_meta(get_the_ID(), '_cpg_department', true);
    
    $out = '<nav class="cpg-breadcrumb"><ol itemscope itemtype="https://schema.org/BreadcrumbList">';
    $out .= '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="'.home_url().'"><span itemprop="name">Accueil</span></a><meta itemprop="position" content="1"></li>';
    if ($dept) {
        $out .= ' &gt; <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><span itemprop="name">'.esc_html($dept).'</span><meta itemprop="position" content="2"></li>';
    }
    $out .= ' &gt; <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><span itemprop="name">Métallier à '.esc_html($city).'</span><meta itemprop="position" content="3"></li>';
    $out .= '</ol></nav>';
    
    return $out;
}
