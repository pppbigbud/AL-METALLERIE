<?php
/**
 * Optimisations SEO Local pour AL Métallerie
 * Métallerie, Ferronnerie, Serrurerie à Thiers, Puy-de-Dôme
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Modifier le titre des pages pour le SEO local
 */
function almetal_seo_title($title) {
    // Page d'accueil
    if (is_front_page() || is_home()) {
        return 'AL Métallerie | Métallier Ferronnier à Thiers, Puy-de-Dôme (63)';
    }
    
    // Page réalisations
    if (is_post_type_archive('realisation') || is_page('realisations')) {
        return 'Nos Réalisations | Portails, Garde-corps, Escaliers | AL Métallerie Thiers';
    }
    
    // Page formations
    if (is_page('formations')) {
        return 'Formations Soudure & Métallerie | AL Métallerie Thiers (63)';
    }
    
    // Page contact
    if (is_page('contact')) {
        return 'Contact | AL Métallerie à Peschadoires près de Thiers (63)';
    }
    
    // Taxonomie type de réalisation
    if (is_tax('type_realisation')) {
        $term = get_queried_object();
        return ucfirst($term->name) . ' sur mesure | AL Métallerie Thiers, Puy-de-Dôme';
    }
    
    return $title;
}
add_filter('pre_get_document_title', 'almetal_seo_title', 10);

/**
 * Ajouter les meta tags SEO dans le head
 */
function almetal_seo_meta_head() {
    // Informations de l'entreprise
    $business = array(
        'name' => 'AL Métallerie',
        'address' => '14 route de Maringues, 63920 Peschadoires',
        'phone' => '06 73 33 35 32',
        'email' => 'aurelien@al-metallerie.fr',
        'region' => 'Puy-de-Dôme, Auvergne-Rhône-Alpes',
        'lat' => '45.8344',
        'lon' => '3.1636',
    );
    
    // Meta description par défaut (160 caractères max)
    $description = 'AL Métallerie, artisan métallier ferronnier à Thiers (63). Portails, garde-corps, escaliers sur mesure. Devis gratuit ☎ 06 73 33 35 32';
    
    // Page d'accueil (158 caractères)
    if (is_front_page() || is_home()) {
        $description = 'AL Métallerie, artisan métallier ferronnier à Thiers (63). Fabrication sur mesure : portails, garde-corps, escaliers, ferronnerie d\'art. Devis gratuit ☎ 06 73 33 35 32';
    }
    
    // Page réalisations (156 caractères)
    if (is_post_type_archive('realisation') || is_page('realisations')) {
        $description = 'Découvrez nos réalisations en métallerie à Thiers (63) : portails, garde-corps, escaliers, pergolas. Artisanat de qualité. Inspirez-vous pour votre projet !';
    }
    
    // Page formations (159 caractères)
    if (is_page('formations')) {
        $description = 'Formations soudure MIG, TIG, ARC à Thiers (63). Stages pour particuliers et pros. Apprenez avec un artisan métallier expérimenté. Inscrivez-vous maintenant !';
    }
    
    // Page contact (155 caractères)
    if (is_page('contact')) {
        $description = 'Contactez AL Métallerie à Thiers (63) pour votre projet sur mesure. Devis gratuit sous 48h. ☎ 06 73 33 35 32 ou formulaire en ligne. Réponse rapide !';
    }
    
    // Taxonomie type de réalisation (adapté dynamiquement)
    if (is_tax('type_realisation')) {
        $term = get_queried_object();
        $description = ucfirst($term->name) . ' sur mesure à Thiers (63) par AL Métallerie. Fabrication artisanale, fer forgé et métal. Demandez votre devis gratuit !';
    }
    
    // Single réalisation - géré par almetal_seo_meta_tags() dans functions.php
    if (is_singular('realisation')) {
        return; // Ne pas doubler les meta tags
    }
    
    // Générer le titre OG selon la page
    $og_title = 'AL Métallerie | Métallier Ferronnier à Thiers (63)';
    $og_type = 'website';
    $og_url = home_url('/');
    $og_image = get_template_directory_uri() . '/assets/images/og-image.jpg';
    
    if (is_front_page() || is_home()) {
        $og_title = 'AL Métallerie | Métallier Ferronnier à Thiers, Puy-de-Dôme';
    } elseif (is_post_type_archive('realisation') || is_page('realisations')) {
        $og_title = 'Nos Réalisations | Portails, Garde-corps, Escaliers | AL Métallerie';
        $og_url = get_post_type_archive_link('realisation');
    } elseif (is_page('formations')) {
        $og_title = 'Formations Soudure & Métallerie | AL Métallerie Thiers';
        $og_url = get_permalink();
    } elseif (is_page('contact')) {
        $og_title = 'Contact | AL Métallerie à Thiers (63)';
        $og_url = get_permalink();
    } elseif (is_tax('type_realisation')) {
        $term = get_queried_object();
        $og_title = ucfirst($term->name) . ' sur mesure | AL Métallerie Thiers';
        $og_url = get_term_link($term);
    } elseif (is_page()) {
        $og_title = get_the_title() . ' | AL Métallerie';
        $og_url = get_permalink();
    }
    
    ?>
    <!-- SEO Local - AL Métallerie -->
    <meta name="description" content="<?php echo esc_attr($description); ?>">
    <meta name="author" content="AL Métallerie">
    <meta name="robots" content="index, follow, max-image-preview:large">
    <link rel="canonical" href="<?php echo esc_url($og_url); ?>">
    
    <!-- Géolocalisation -->
    <meta name="geo.region" content="FR-63">
    <meta name="geo.placename" content="Peschadoires, Thiers">
    <meta name="geo.position" content="<?php echo $business['lat']; ?>;<?php echo $business['lon']; ?>">
    <meta name="ICBM" content="<?php echo $business['lat']; ?>, <?php echo $business['lon']; ?>">
    
    <!-- Open Graph (Facebook, LinkedIn, etc.) -->
    <meta property="og:locale" content="fr_FR">
    <meta property="og:type" content="<?php echo esc_attr($og_type); ?>">
    <meta property="og:title" content="<?php echo esc_attr($og_title); ?>">
    <meta property="og:description" content="<?php echo esc_attr($description); ?>">
    <meta property="og:url" content="<?php echo esc_url($og_url); ?>">
    <meta property="og:site_name" content="AL Métallerie">
    <meta property="og:image" content="<?php echo esc_url($og_image); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="AL Métallerie - Artisan métallier ferronnier à Thiers (63)">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo esc_attr($og_title); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr($description); ?>">
    <meta name="twitter:image" content="<?php echo esc_url($og_image); ?>">
    <meta name="twitter:image:alt" content="AL Métallerie - Artisan métallier ferronnier à Thiers (63)">
    <?php
}
add_action('wp_head', 'almetal_seo_meta_head', 1);

/**
 * Schema JSON-LD LocalBusiness pour toutes les pages
 */
function almetal_schema_local_business() {
    // Ne pas ajouter sur les single realisation (déjà géré)
    if (is_singular('realisation')) {
        return;
    }
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        '@id' => home_url('/#localbusiness'),
        'name' => 'AL Métallerie',
        'description' => 'Métallerie, ferronnerie et serrurerie à Thiers dans le Puy-de-Dôme. Fabrication sur mesure de portails, garde-corps, escaliers, pergolas.',
        'url' => home_url('/'),
        'telephone' => '+33673333532',
        'email' => 'aurelien@al-metallerie.fr',
        'image' => get_template_directory_uri() . '/assets/images/logo.png',
        'priceRange' => '€€',
        'address' => array(
            '@type' => 'PostalAddress',
            'streetAddress' => '14 route de Maringues',
            'addressLocality' => 'Peschadoires',
            'postalCode' => '63920',
            'addressRegion' => 'Auvergne-Rhône-Alpes',
            'addressCountry' => 'FR'
        ),
        'geo' => array(
            '@type' => 'GeoCoordinates',
            'latitude' => '45.8344',
            'longitude' => '3.1636'
        ),
        'openingHoursSpecification' => array(
            array(
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'),
                'opens' => '08:00',
                'closes' => '18:00'
            )
        ),
        'areaServed' => array(
            array(
                '@type' => 'City',
                'name' => 'Thiers'
            ),
            array(
                '@type' => 'City',
                'name' => 'Clermont-Ferrand'
            ),
            array(
                '@type' => 'AdministrativeArea',
                'name' => 'Puy-de-Dôme'
            )
        ),
        'sameAs' => array(
            'https://www.facebook.com/almetallerie',
            'https://www.instagram.com/almetallerie'
        ),
        'hasOfferCatalog' => array(
            '@type' => 'OfferCatalog',
            'name' => 'Services de métallerie',
            'itemListElement' => array(
                array(
                    '@type' => 'Offer',
                    'itemOffered' => array(
                        '@type' => 'Service',
                        'name' => 'Fabrication de portails sur mesure'
                    )
                ),
                array(
                    '@type' => 'Offer',
                    'itemOffered' => array(
                        '@type' => 'Service',
                        'name' => 'Installation de garde-corps'
                    )
                ),
                array(
                    '@type' => 'Offer',
                    'itemOffered' => array(
                        '@type' => 'Service',
                        'name' => 'Création d\'escaliers métalliques'
                    )
                ),
                array(
                    '@type' => 'Offer',
                    'itemOffered' => array(
                        '@type' => 'Service',
                        'name' => 'Construction de pergolas'
                    )
                ),
                array(
                    '@type' => 'Offer',
                    'itemOffered' => array(
                        '@type' => 'Service',
                        'name' => 'Formations soudure'
                    )
                )
            )
        )
    );
    
    echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>' . "\n";
}
add_action('wp_head', 'almetal_schema_local_business', 5);

/**
 * Schema BreadcrumbList pour le fil d'Ariane
 */
function almetal_schema_breadcrumb() {
    if (is_front_page()) {
        return;
    }
    
    $breadcrumbs = array(
        array(
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Accueil',
            'item' => home_url('/')
        )
    );
    
    $position = 2;
    
    // Page réalisations
    if (is_post_type_archive('realisation') || is_page('realisations')) {
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => 'Réalisations',
            'item' => home_url('/realisations/')
        );
    }
    
    // Taxonomie
    if (is_tax('type_realisation')) {
        $term = get_queried_object();
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => 'Réalisations',
            'item' => home_url('/realisations/')
        );
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position + 1,
            'name' => $term->name,
            'item' => get_term_link($term)
        );
    }
    
    // Single réalisation
    if (is_singular('realisation')) {
        $terms = get_the_terms(get_the_ID(), 'type_realisation');
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => 'Réalisations',
            'item' => home_url('/realisations/')
        );
        if ($terms && !is_wp_error($terms)) {
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => $position + 1,
                'name' => $terms[0]->name,
                'item' => get_term_link($terms[0])
            );
            $position++;
        }
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position + 1,
            'name' => get_the_title()
        );
    }
    
    // Pages standard
    if (is_page() && !is_front_page()) {
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => get_the_title()
        );
    }
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $breadcrumbs
    );
    
    echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'almetal_schema_breadcrumb', 6);

/**
 * Ajouter les mots-clés locaux dans le contenu (footer SEO)
 */
function almetal_seo_footer_text() {
    if (!is_front_page()) {
        return;
    }
    ?>
    <div class="seo-footer-text" style="background: #1a1a1a; padding: 2rem 0; border-top: 1px solid rgba(240,139,24,0.2);">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
            <p style="color: rgba(255,255,255,0.6); font-size: 0.85rem; line-height: 1.8; text-align: center;">
                <strong style="color: #F08B18;">AL Métallerie</strong> - Votre artisan métallier ferronnier à <strong>Thiers</strong> et dans tout le <strong>Puy-de-Dôme (63)</strong>. 
                Nous intervenons à <strong>Clermont-Ferrand</strong>, <strong>Riom</strong>, <strong>Issoire</strong>, <strong>Ambert</strong>, <strong>Cournon-d'Auvergne</strong> 
                et dans toute l'<strong>Auvergne</strong> pour vos projets de <strong>portails sur mesure</strong>, <strong>garde-corps</strong>, 
                <strong>escaliers métalliques</strong>, <strong>pergolas</strong>, <strong>verrières</strong> et <strong>ferronnerie d'art</strong>. 
                Devis gratuit au <a href="tel:+33673333532" style="color: #F08B18;">06 73 33 35 32</a>.
            </p>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'almetal_seo_footer_text', 5);
