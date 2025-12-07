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
        return 'AL Métallerie & Soudure | Métallier Ferronnier à Thiers, Puy-de-Dôme (63)';
    }
    
    // Page réalisations
    if (is_post_type_archive('realisation') || is_page('realisations')) {
        return 'Nos Réalisations | Portails, Garde-corps, Escaliers | AL Métallerie & Soudure Thiers';
    }
    
    // Page formations
    if (is_page('formations')) {
        return 'Formations Soudure & Métallerie | AL Métallerie & Soudure Thiers (63)';
    }
    
    // Page contact
    if (is_page('contact')) {
        return 'Contact | AL Métallerie & Soudure à Peschadoires près de Thiers (63)';
    }
    
    // Taxonomie type de réalisation
    if (is_tax('type_realisation')) {
        $term = get_queried_object();
        return ucfirst($term->name) . ' sur mesure | AL Métallerie & Soudure Thiers, Puy-de-Dôme';
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
        'name' => 'AL Métallerie & Soudure',
        'address' => '14 route de Maringues, 63920 Peschadoires',
        'phone' => '06 73 33 35 32',
        'email' => 'aurelien@al-metallerie.fr',
        'region' => 'Puy-de-Dôme, Auvergne-Rhône-Alpes',
        'lat' => '45.8344',
        'lon' => '3.1636',
    );
    
    // Meta description par défaut (160 caractères max)
    $description = 'AL Métallerie & Soudure, artisan métallier ferronnier à Thiers (63). Portails, garde-corps, escaliers sur mesure. Devis gratuit ☎ 06 73 33 35 32';
    
    // Page d'accueil (158 caractères)
    if (is_front_page() || is_home()) {
        $description = 'AL Métallerie & Soudure, artisan métallier ferronnier à Thiers (63). Fabrication sur mesure : portails, garde-corps, escaliers. Devis gratuit ☎ 06 73 33 35 32';
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
        $description = 'Contactez AL Métallerie & Soudure à Thiers (63) pour votre projet sur mesure. Devis gratuit sous 48h. ☎ 06 73 33 35 32 ou formulaire. Réponse rapide !';
    }
    
    // Taxonomie type de réalisation (adapté dynamiquement)
    if (is_tax('type_realisation')) {
        $term = get_queried_object();
        $description = ucfirst($term->name) . ' sur mesure à Thiers (63) par AL Métallerie & Soudure. Fabrication artisanale, fer forgé et métal. Devis gratuit !';
    }
    
    // Single réalisation - géré par almetal_seo_meta_tags() dans functions.php
    if (is_singular('realisation')) {
        return; // Ne pas doubler les meta tags
    }
    
    // Générer le titre OG selon la page
    $og_title = 'AL Métallerie & Soudure | Métallier Ferronnier à Thiers (63)';
    $og_type = 'website';
    $og_url = home_url('/');
    $og_image = get_template_directory_uri() . '/assets/images/og-image.jpg';
    
    if (is_front_page() || is_home()) {
        $og_title = 'AL Métallerie & Soudure | Métallier Ferronnier à Thiers, Puy-de-Dôme';
    } elseif (is_post_type_archive('realisation') || is_page('realisations')) {
        $og_title = 'Nos Réalisations | Portails, Garde-corps, Escaliers | AL Métallerie & Soudure';
        $og_url = get_post_type_archive_link('realisation');
    } elseif (is_page('formations')) {
        $og_title = 'Formations Soudure & Métallerie | AL Métallerie & Soudure Thiers';
        $og_url = get_permalink();
    } elseif (is_page('contact')) {
        $og_title = 'Contact | AL Métallerie & Soudure à Thiers (63)';
        $og_url = get_permalink();
    } elseif (is_tax('type_realisation')) {
        $term = get_queried_object();
        $og_title = ucfirst($term->name) . ' sur mesure | AL Métallerie & Soudure Thiers';
        $og_url = get_term_link($term);
    } elseif (is_page()) {
        $og_title = get_the_title() . ' | AL Métallerie & Soudure';
        $og_url = get_permalink();
    }
    
    ?>
    <!-- SEO Local - AL Métallerie & Soudure -->
    <meta name="description" content="<?php echo esc_attr($description); ?>">
    <meta name="keywords" content="métallerie Thiers, ferronnier Puy-de-Dôme, soudure 63, portail sur mesure, garde-corps, escalier métallique, pergola, ferronnerie art, artisan métallier Auvergne, formation soudure">
    <meta name="author" content="AL Métallerie & Soudure">
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
    <meta property="og:site_name" content="AL Métallerie & Soudure">
    <meta property="og:image" content="<?php echo esc_url($og_image); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="AL Métallerie & Soudure - Artisan métallier ferronnier à Thiers (63)">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo esc_attr($og_title); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr($description); ?>">
    <meta name="twitter:image" content="<?php echo esc_url($og_image); ?>">
    <meta name="twitter:image:alt" content="AL Métallerie & Soudure - Artisan métallier ferronnier à Thiers (63)">
    <?php
}
add_action('wp_head', 'almetal_seo_meta_head', 1);

/**
 * Schema JSON-LD LocalBusiness pour toutes les pages
 * Optimisé pour le SEO local avec zones d'intervention complètes
 */
function almetal_schema_local_business() {
    // Ne pas ajouter sur les single realisation (déjà géré)
    if (is_singular('realisation')) {
        return;
    }
    
    // Communes dans un rayon de 30km autour de Thiers (63)
    $communes_30km = array(
        // Proches (< 10km)
        'Peschadoires', 'Thiers', 'Escoutoux', 'Celles-sur-Durolle', 'La Monnerie-le-Montel',
        'Palladuc', 'Dorat', 'Puy-Guillaume', 'Noalhat', 'Orléat',
        // 10-20km
        'Lezoux', 'Courpière', 'Vollore-Ville', 'Augerolles', 'Olliergues',
        'Saint-Rémy-sur-Durolle', 'Chabreloche', 'Arconsat', 'Viscomtat', 'Sainte-Agathe',
        'Sermentizon', 'Bort-l\'Étang', 'Seychalles', 'Lempty', 'Bulhon',
        'Maringues', 'Joze', 'Culhat', 'Luzillat', 'Limons',
        // 20-30km
        'Billom', 'Saint-Dier-d\'Auvergne', 'Cunlhat', 'Ambert', 'Viverols',
        'Pont-de-Dore', 'Ris', 'Châteldon', 'Randan', 'Saint-Priest-Bramefant',
        'Aigueperse', 'Effiat', 'Thuret', 'Ennezat', 'Riom',
        'Mozac', 'Volvic', 'Châtel-Guyon', 'Manzat', 'Combronde',
        'Clermont-Ferrand', 'Chamalières', 'Royat', 'Beaumont', 'Aubière',
        'Cournon-d\'Auvergne', 'Lempdes', 'Pont-du-Château', 'Gerzat', 'Cébazat',
        'Issoire', 'Vic-le-Comte', 'Veyre-Monton', 'Saint-Amant-Tallende', 'Tallende'
    );
    
    // Récupérer les catégories de réalisations dynamiquement
    $terms = get_terms(array(
        'taxonomy' => 'type_realisation',
        'hide_empty' => false,
    ));
    
    // Construire les services dynamiquement
    $services = array();
    if (!empty($terms) && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            $services[] = array(
                '@type' => 'Offer',
                'itemOffered' => array(
                    '@type' => 'Service',
                    'name' => ucfirst($term->name) . ' sur mesure',
                    'description' => 'Fabrication et installation de ' . strtolower($term->name) . ' sur mesure à Thiers et dans le Puy-de-Dôme.',
                    'url' => get_term_link($term),
                    'areaServed' => 'Puy-de-Dôme (63)',
                    'provider' => array(
                        '@type' => 'LocalBusiness',
                        'name' => 'AL Métallerie'
                    )
                )
            );
        }
    }
    
    // Ajouter les formations
    $services[] = array(
        '@type' => 'Offer',
        'itemOffered' => array(
            '@type' => 'Service',
            'name' => 'Formations soudure MIG, TIG, ARC',
            'description' => 'Formations professionnelles en soudure pour particuliers et entreprises à Thiers.',
            'url' => home_url('/formations/'),
            'areaServed' => 'Puy-de-Dôme (63)'
        )
    );
    
    // Construire les zones desservies
    $areas_served = array();
    foreach ($communes_30km as $commune) {
        $areas_served[] = array(
            '@type' => 'City',
            'name' => $commune,
            'containedInPlace' => array(
                '@type' => 'AdministrativeArea',
                'name' => 'Puy-de-Dôme'
            )
        );
    }
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => array('LocalBusiness', 'HomeAndConstructionBusiness'),
        'additionalType' => 'https://schema.org/Locksmith',
        '@id' => home_url('/#localbusiness'),
        'name' => 'AL Métallerie & Soudure',
        'alternateName' => 'AL Métallerie & Soudure Thiers',
        'description' => 'Artisan métallier ferronnier à Thiers (63). Fabrication sur mesure de portails, garde-corps, escaliers, pergolas, ferronnerie d\'art. Formations soudure. Devis gratuit.',
        'url' => home_url('/'),
        'telephone' => '+33673333532',
        'email' => 'aurelien@al-metallerie.fr',
        'image' => array(
            get_template_directory_uri() . '/assets/images/logo.png',
            get_template_directory_uri() . '/assets/images/og-image.jpg'
        ),
        'logo' => get_template_directory_uri() . '/assets/images/logo.png',
        'priceRange' => '€€',
        'currenciesAccepted' => 'EUR',
        'paymentAccepted' => 'Espèces, Chèque, Virement bancaire',
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
            'latitude' => 45.8344,
            'longitude' => 3.1636
        ),
        'openingHoursSpecification' => array(
            array(
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'),
                'opens' => '08:00',
                'closes' => '18:00'
            ),
            array(
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => 'Saturday',
                'opens' => '09:00',
                'closes' => '12:00',
                'description' => 'Sur rendez-vous uniquement'
            )
        ),
        'areaServed' => $areas_served,
        'serviceArea' => array(
            '@type' => 'GeoCircle',
            'geoMidpoint' => array(
                '@type' => 'GeoCoordinates',
                'latitude' => 45.8556,
                'longitude' => 3.5478
            ),
            'geoRadius' => '30000'
        ),
        'sameAs' => array(
            'https://www.facebook.com/almetallerie',
            'https://www.instagram.com/almetallerie'
        ),
        'hasOfferCatalog' => array(
            '@type' => 'OfferCatalog',
            'name' => 'Services de métallerie et ferronnerie',
            'itemListElement' => $services
        ),
        'knowsAbout' => array(
            'Métallerie',
            'Ferronnerie',
            'Serrurerie',
            'Soudure MIG',
            'Soudure TIG',
            'Soudure ARC',
            'Travail du fer forgé',
            'Fabrication sur mesure'
        ),
        'slogan' => 'Votre artisan métallier ferronnier & soudeur à Thiers',
        'foundingDate' => '2020',
        'founder' => array(
            '@type' => 'Person',
            'name' => 'Aurélien',
            'jobTitle' => 'Artisan métallier ferronnier'
        )
    );
    
    echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>' . "\n";
}
add_action('wp_head', 'almetal_schema_local_business', 5);

/**
 * Schema JSON-LD Service pour chaque catégorie de réalisation
 * Affiché sur les pages de taxonomie
 */
function almetal_schema_service() {
    if (!is_tax('type_realisation')) {
        return;
    }
    
    $term = get_queried_object();
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        '@id' => get_term_link($term) . '#service',
        'name' => ucfirst($term->name) . ' sur mesure',
        'description' => 'Fabrication et installation de ' . strtolower($term->name) . ' sur mesure par AL Métallerie, artisan métallier ferronnier à Thiers (63). Travail artisanal de qualité, devis gratuit.',
        'url' => get_term_link($term),
        'provider' => array(
            '@type' => 'LocalBusiness',
            '@id' => home_url('/#localbusiness'),
            'name' => 'AL Métallerie',
            'telephone' => '+33673333532',
            'address' => array(
                '@type' => 'PostalAddress',
                'addressLocality' => 'Peschadoires',
                'postalCode' => '63920',
                'addressCountry' => 'FR'
            )
        ),
        'areaServed' => array(
            '@type' => 'AdministrativeArea',
            'name' => 'Puy-de-Dôme'
        ),
        'serviceType' => ucfirst($term->name),
        'category' => 'Métallerie / Ferronnerie',
        'offers' => array(
            '@type' => 'Offer',
            'availability' => 'https://schema.org/InStock',
            'priceSpecification' => array(
                '@type' => 'PriceSpecification',
                'priceCurrency' => 'EUR',
                'description' => 'Devis gratuit sur demande'
            )
        )
    );
    
    echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>' . "\n";
}
add_action('wp_head', 'almetal_schema_service', 7);

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
 * Contenu enrichi pour atteindre 300+ mots
 */
function almetal_seo_footer_text() {
    if (!is_front_page()) {
        return;
    }
    ?>
    <div class="seo-footer-text" style="background: #1a1a1a; padding: 3rem 0; border-top: 1px solid rgba(240,139,24,0.2);">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 1.5rem;">
            <h2 style="color: #F08B18; font-size: 1.3rem; font-weight: 700; text-align: center; margin-bottom: 1.5rem;">
                Votre Artisan Métallier Ferronnier à Thiers, Puy-de-Dôme
            </h2>
            <div style="color: rgba(255,255,255,0.7); font-size: 0.9rem; line-height: 1.9; text-align: justify; column-count: 1;">
                <p style="margin-bottom: 1rem;">
                    <strong style="color: #F08B18;">AL Métallerie & Soudure</strong> est votre artisan métallier ferronnier de confiance, 
                    installé à <strong>Peschadoires</strong>, près de <strong>Thiers</strong> dans le <strong>Puy-de-Dôme (63)</strong>. 
                    Spécialisé dans la <strong>fabrication sur mesure</strong> d'ouvrages métalliques, notre atelier allie savoir-faire 
                    traditionnel et techniques modernes pour réaliser vos projets les plus ambitieux.
                </p>
                <p style="margin-bottom: 1rem;">
                    Notre expertise couvre un large éventail de réalisations : <strong>portails sur mesure</strong> (coulissants, battants, 
                    automatisés), <strong>garde-corps</strong> et rambardes pour escaliers et terrasses, <strong>escaliers métalliques</strong> 
                    droits ou hélicoïdaux, <strong>pergolas</strong> et structures d'extérieur, <strong>verrières</strong> d'intérieur style 
                    atelier, ainsi que des pièces de <strong>ferronnerie d'art</strong> uniques. Chaque création est pensée et fabriquée 
                    selon vos besoins spécifiques.
                </p>
                <p style="margin-bottom: 1rem;">
                    Nous maîtrisons les techniques de <strong>soudure MIG, TIG et ARC</strong>, permettant de travailler l'acier, 
                    l'inox et l'aluminium avec précision. Notre atelier propose également des <strong>formations soudure</strong> 
                    pour particuliers et professionnels souhaitant acquérir ou perfectionner leurs compétences.
                </p>
                <p style="margin-bottom: 1rem;">
                    <strong>Zone d'intervention</strong> : nous intervenons dans tout le Puy-de-Dôme et l'Auvergne, notamment à 
                    <strong>Clermont-Ferrand</strong>, <strong>Riom</strong>, <strong>Issoire</strong>, <strong>Ambert</strong>, 
                    <strong>Cournon-d'Auvergne</strong>, <strong>Chamalières</strong>, <strong>Lezoux</strong>, <strong>Courpière</strong>, 
                    <strong>Pont-du-Château</strong> et toutes les communes environnantes dans un rayon de 50 km.
                </p>
                <p style="margin-bottom: 0; text-align: center;">
                    <strong>Devis gratuit et personnalisé</strong> sous 48h. Contactez-nous au 
                    <a href="tel:+33673333532" style="color: #F08B18; font-weight: 700;">06 73 33 35 32</a> 
                    ou par email à <a href="mailto:aurelien@al-metallerie.fr" style="color: #F08B18;">aurelien@al-metallerie.fr</a>.
                </p>
            </div>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'almetal_seo_footer_text', 5);
