<?php
/**
 * Optimisations SEO Local pour AL Métallerie Soudure
 * Métallerie, Ferronnerie, Serrurerie à Thiers, Puy-de-Dôme
 * 
 * @package AL-Metallerie Soudure
 * @since 1.0.0
 */

// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Modifier le titre des pages pour le SEO local
 * Optimisé selon audit SEO - Décembre 2024
 */
function almetal_seo_title($title) {
    // Ne pas interférer avec les pages ville (géré par le plugin city-pages-generator)
    if (is_singular('city_page')) {
        return $title;
    }
    
    // Page d'accueil - Optimisé avec mots-clés services
    if (is_front_page() || is_home()) {
        return 'AL Métallerie | Portails, Garde-corps, Escaliers sur Mesure | Thiers (63)';
    }
    
    // Page réalisations - Optimisé avec nombre de projets
    if (is_post_type_archive('realisation') || is_page('realisations')) {
        return 'Réalisations Métallerie Thiers | Portails & Garde-corps sur Mesure | Photos';
    }
    
    // Page formations
    if (is_page('formations')) {
        return 'Formations Soudure MIG TIG ARC | Thiers (63) | AL Métallerie';
    }
    
    // Page formations particuliers
    if (is_page('formations-particuliers')) {
        return 'Formations Soudure Particuliers | Stages Découverte | AL Métallerie Thiers';
    }
    
    // Page contact - Optimisé avec CTA et téléphone
    if (is_page('contact')) {
        return 'Contact & Devis Gratuit | Métallier Thiers | Réponse 24h | AL Métallerie';
    }
    
    // Taxonomie type de réalisation
    if (is_tax('type_realisation')) {
        $term = get_queried_object();
        return ucfirst($term->name) . ' sur mesure | AL Métallerie & Soudure Thiers, Puy-de-Dôme';
    }
    
    // Réalisations individuelles - Title optimisé (max 60 caractères)
    if (is_singular('realisation')) {
        global $post;
        $post_title = get_the_title();
        $lieu = get_post_meta($post->ID, '_almetal_lieu', true);
        $terms = get_the_terms($post->ID, 'type_realisation');
        $type = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : '';
        
        // Format court: "Titre – AL Métallerie Soudure | Thiers"
        // Si le titre est trop long, on le tronque intelligemment
        $suffix = ' – AL Métallerie Soudure';
        $max_title_length = 60 - strlen($suffix);
        
        if (strlen($post_title) > $max_title_length) {
            // Tronquer au dernier mot complet
            $post_title = substr($post_title, 0, $max_title_length - 3);
            $last_space = strrpos($post_title, ' ');
            if ($last_space !== false) {
                $post_title = substr($post_title, 0, $last_space);
            }
            $post_title .= '...';
        }
        
        return $post_title . $suffix;
    }
    
    return $title;
}
add_filter('pre_get_document_title', 'almetal_seo_title', 10);

/**
 * Ajouter les meta tags SEO dans le head
 * Ne pas interférer avec les pages ville (géré par le plugin city-pages-generator)
 */
function almetal_seo_meta_head() {
    // Ne pas ajouter de meta tags sur les pages ville (géré par le plugin CPG)
    if (is_singular('city_page')) {
        return;
    }
    
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
    
    // Meta description par défaut (160 caractères max) - Optimisé audit SEO
    $description = 'Artisan métallier à Thiers (63). Fabrication et pose de portails, garde-corps, escaliers, pergolas sur mesure. Devis gratuit sous 24h. ☎ 06 73 33 35 32';
    
    // Page d'accueil - 158 caractères avec CTA fort
    if (is_front_page() || is_home()) {
        $description = 'Artisan métallier à Thiers (63). Fabrication et pose de portails, garde-corps, escaliers, pergolas sur mesure. Devis gratuit sous 24h. ☎ 06 73 33 35 32';
    }
    
    // Page réalisations - 156 caractères avec incitation
    if (is_post_type_archive('realisation') || is_page('realisations')) {
        $description = 'Découvrez nos réalisations en métallerie : portails, garde-corps, escaliers dans le Puy-de-Dôme. Photos avant/après. Inspirez-vous pour votre projet !';
    }
    
    // Page formations - 159 caractères
    if (is_page('formations')) {
        $description = 'Formations soudure MIG, TIG, ARC à Thiers (63). Stages pour particuliers et professionnels. Apprenez avec un artisan expérimenté. Inscrivez-vous !';
    }
    
    // Page formations particuliers
    if (is_page('formations-particuliers')) {
        $description = 'Stages soudure pour particuliers à Thiers (63). Découverte ou perfectionnement. Apprenez à souder avec un artisan métallier. Places limitées !';
    }
    
    // Page contact - 155 caractères avec CTA
    if (is_page('contact')) {
        $description = 'Contactez AL Métallerie pour un devis gratuit. Réponse sous 24h. Déplacement gratuit dans le Puy-de-Dôme. ☎ 06 73 33 35 32';
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
    
    // Ne pas ajouter sur les pages ville (géré par le plugin CPG)
    if (is_singular('city_page')) {
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

/**
 * Générer automatiquement les attributs ALT des images
 * Optimisé pour le SEO - Audit Décembre 2024
 */
function almetal_auto_image_alt($attr, $attachment, $size) {
    // Si l'alt est déjà défini et non vide, ne pas le modifier
    if (!empty($attr['alt'])) {
        return $attr;
    }
    
    // Récupérer le titre de l'image
    $alt = get_the_title($attachment->ID);
    
    // Si on est sur une réalisation, enrichir l'alt
    if (is_singular('realisation')) {
        global $post;
        $lieu = get_post_meta($post->ID, '_almetal_lieu', true);
        $terms = get_the_terms($post->ID, 'type_realisation');
        $type = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : '';
        
        if ($type && $lieu) {
            $attr['alt'] = ucfirst($type) . ' - ' . $alt . ' à ' . $lieu . ' - AL Métallerie';
        } elseif ($type) {
            $attr['alt'] = ucfirst($type) . ' - ' . $alt . ' - AL Métallerie Thiers';
        } elseif ($lieu) {
            $attr['alt'] = $alt . ' à ' . $lieu . ' - AL Métallerie';
        } else {
            $attr['alt'] = $alt . ' - AL Métallerie Thiers';
        }
    }
    // Sur les pages ville
    elseif (is_singular('city_page')) {
        $city_name = get_post_meta(get_the_ID(), '_cpg_city_name', true);
        if ($city_name) {
            $attr['alt'] = $alt . ' - Métallier à ' . $city_name . ' - AL Métallerie';
        } else {
            $attr['alt'] = $alt . ' - AL Métallerie';
        }
    }
    // Sur les archives de réalisations
    elseif (is_post_type_archive('realisation') || is_tax('type_realisation')) {
        $attr['alt'] = $alt . ' - Réalisation métallerie - AL Métallerie Thiers';
    }
    // Autres pages
    else {
        $attr['alt'] = $alt . ' - AL Métallerie Thiers (63)';
    }
    
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'almetal_auto_image_alt', 10, 3);

/**
 * Ajouter alt automatique aux images dans le contenu
 */
function almetal_content_image_alt($content) {
    if (empty($content)) {
        return $content;
    }
    
    // Pattern pour trouver les images sans alt ou avec alt vide
    $pattern = '/<img([^>]*?)alt=["\'][\s]*["\']([^>]*?)>/i';
    
    // Contexte pour l'alt
    $context = 'AL Métallerie Thiers';
    if (is_singular('realisation')) {
        $terms = get_the_terms(get_the_ID(), 'type_realisation');
        $type = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : 'Réalisation';
        $lieu = get_post_meta(get_the_ID(), '_almetal_lieu', true);
        $context = $type . ($lieu ? ' à ' . $lieu : '') . ' - AL Métallerie';
    } elseif (is_singular('city_page')) {
        $city = get_post_meta(get_the_ID(), '_cpg_city_name', true);
        $context = 'Métallier à ' . ($city ?: 'Thiers') . ' - AL Métallerie';
    }
    
    // Remplacer les alt vides
    $content = preg_replace_callback($pattern, function($matches) use ($context) {
        return '<img' . $matches[1] . 'alt="' . esc_attr($context) . '"' . $matches[2] . '>';
    }, $content);
    
    return $content;
}
add_filter('the_content', 'almetal_content_image_alt', 20);

/**
 * Schema FAQPage pour les pages ville
 * Améliore le SEO avec des Rich Snippets FAQ
 */
function almetal_schema_faq_city_pages() {
    if (!is_singular('city_page')) {
        return;
    }
    
    $post_id = get_the_ID();
    $city_name = get_post_meta($post_id, '_cpg_city_name', true);
    $postal_code = get_post_meta($post_id, '_cpg_postal_code', true);
    
    if (empty($city_name)) {
        return;
    }
    
    // FAQ contextualisée pour la ville
    $faqs = array(
        array(
            'question' => 'Quel est le prix moyen d\'un portail sur mesure à ' . $city_name . ' ?',
            'answer' => 'Le prix d\'un portail sur mesure à ' . $city_name . ' varie selon les dimensions, le matériau (acier, inox, aluminium) et la finition. Comptez entre 1 500€ et 5 000€ pour un portail de qualité. AL Métallerie propose des devis gratuits et personnalisés sous 24h.'
        ),
        array(
            'question' => 'Quels sont les délais de fabrication pour un garde-corps à ' . $city_name . ' ?',
            'answer' => 'Les délais de fabrication d\'un garde-corps sur mesure sont généralement de 2 à 4 semaines après validation du devis. AL Métallerie intervient à ' . $city_name . ' et dans tout le Puy-de-Dôme pour la pose.'
        ),
        array(
            'question' => 'AL Métallerie intervient-il à ' . $city_name . ' (' . $postal_code . ') ?',
            'answer' => 'Oui, AL Métallerie intervient à ' . $city_name . ' et dans toutes les communes du Puy-de-Dôme dans un rayon de 50 km autour de Thiers. Le déplacement pour devis est gratuit.'
        ),
        array(
            'question' => 'Quels types de soudure utilisez-vous pour vos réalisations ?',
            'answer' => 'Notre atelier maîtrise les techniques de soudure MIG, TIG et ARC, permettant de travailler l\'acier, l\'inox 316L et l\'aluminium avec précision. Chaque technique est choisie selon le projet et le matériau.'
        ),
        array(
            'question' => 'Proposez-vous des formations soudure près de ' . $city_name . ' ?',
            'answer' => 'Oui, AL Métallerie propose des formations soudure pour particuliers et professionnels dans notre atelier à Peschadoires, à proximité de ' . $city_name . '. Stages découverte et perfectionnement disponibles.'
        )
    );
    
    $faq_items = array();
    foreach ($faqs as $faq) {
        $faq_items[] = array(
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text' => $faq['answer']
            )
        );
    }
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $faq_items
    );
    
    echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>' . "\n";
}
add_action('wp_head', 'almetal_schema_faq_city_pages', 8);
