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
    $queried_object = get_queried_object();
    if (is_singular('city_page') || ($queried_object instanceof WP_Post && $queried_object->post_type === 'city_page')) {
        return;
    }
    
    // Informations de l'entreprise
    $business = array(
        'name' => 'AL Métallerie & Soudure',
        'address' => '14 route de Maringues, 63920 Peschadoires',
        'phone' => '06 73 33 35 32',
        'email' => 'contact@al-metallerie.fr',
        'region' => 'Puy-de-Dôme, Auvergne-Rhône-Alpes',
        'lat' => '45.8344',
        'lon' => '3.1636',
    );
    
    // Meta description par défaut (160 caractères max) - Optimisé audit SEO
    $description = almetal_build_meta_description(array(
        'type' => 'Métallerie artisanale',
        'detail' => 'fabrication et pose de portails, garde-corps, escaliers, pergolas sur mesure',
        'city' => 'Thiers (63)',
        'cta' => 'Devis gratuit sous 24h.',
    ));

    // Page d'accueil - 158 caractères avec CTA fort
    if (is_front_page() || is_home()) {
        $description = almetal_build_meta_description(array(
            'type' => 'Métallerie artisanale',
            'detail' => 'fabrication et pose de portails, garde-corps, escaliers, pergolas sur mesure',
            'city' => 'Thiers (63)',
            'cta' => 'Devis gratuit sous 24h.',
        ));
    }

    // Page réalisations - 156 caractères avec incitation
    if (is_post_type_archive('realisation') || is_page('realisations')) {
        $description = almetal_build_meta_description(array(
            'type' => 'Réalisations métallerie',
            'detail' => 'portails, garde-corps, escaliers sur mesure avec photos avant/après',
            'city' => 'Puy-de-Dôme',
            'cta' => 'Inspirez-vous pour votre projet !',
        ));
    }

    // Page formations - 159 caractères
    if (is_page('formations')) {
        $description = almetal_build_meta_description(array(
            'type' => 'Formations soudure',
            'detail' => 'stages MIG, TIG, ARC pour particuliers et professionnels',
            'city' => 'Thiers (63)',
            'cta' => 'Inscrivez-vous dès maintenant !',
        ));
    }

    // Page formations particuliers
    if (is_page('formations-particuliers')) {
        $description = almetal_build_meta_description(array(
            'type' => 'Formations soudure pour particuliers',
            'detail' => 'stages découverte et perfectionnement encadrés par un artisan métallier',
            'city' => 'Thiers (63)',
            'cta' => 'Places limitées !',
        ));
    }

    // Page contact - 155 caractères avec CTA
    if (is_page('contact')) {
        $description = almetal_build_meta_description(array(
            'type' => 'Contact & devis',
            'detail' => 'devis gratuit et déplacement offert dans le Puy-de-Dôme',
            'city' => 'Puy-de-Dôme',
            'cta' => 'Réponse sous 24h.',
        ));
    }

    // Taxonomie type de réalisation (adapté dynamiquement)
    if (is_tax('type_realisation')) {
        $term = get_queried_object();
        $description = almetal_build_meta_description(array(
            'type' => ucfirst($term->name) . ' sur mesure',
            'detail' => 'fabrication artisanale en fer forgé et métal',
            'city' => 'Thiers (63)',
            'cta' => 'Devis gratuit !',
        ));
    }
    
    // Single réalisation - géré par almetal_seo_meta_tags() dans functions.php
    if (is_singular('realisation')) {
        return; // Ne pas doubler les meta tags
    }
    
    // Générer le titre OG selon la page
    $og_title = 'AL Métallerie & Soudure | Métallier Serrurier à Thiers (63)';
    $og_type = 'website';
    $og_url = home_url('/');
    $og_image = get_template_directory_uri() . '/assets/images/og-image.jpg';
    
    if (is_front_page() || is_home()) {
        $og_title = 'AL Métallerie & Soudure | Métallier Serrurier à Thiers, Puy-de-Dôme';
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
    <meta name="keywords" content="métallerie Thiers, serrurier Puy-de-Dôme, soudure 63, portail sur mesure, garde-corps, escalier métallique, pergola, ferronnerie art, artisan métallier Auvergne, formation soudure">
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
    <meta property="og:image:alt" content="AL Métallerie & Soudure - Artisan métallier serrurier à Thiers (63)">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo esc_attr($og_title); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr($description); ?>">
    <meta name="twitter:image" content="<?php echo esc_url($og_image); ?>">
    <meta name="twitter:image:alt" content="AL Métallerie & Soudure - Artisan métallier serrurier à Thiers (63)">
    <?php
}
add_action('wp_head', 'almetal_seo_meta_head', 1);

/**
 * Schema JSON-LD LocalBusiness pour toutes les pages
 * Optimisé pour le SEO local avec zones d'intervention complètes
 */
function almetal_schema_local_business() {
    $queried_object = get_queried_object();
    
    // Ne pas ajouter sur les single realisation (déjà géré)
    if (is_singular('realisation') || ($queried_object instanceof WP_Post && $queried_object->post_type === 'realisation')) {
        return;
    }
    
    // Ne pas ajouter sur les pages ville (géré par le plugin CPG)
    if (is_singular('city_page') || ($queried_object instanceof WP_Post && $queried_object->post_type === 'city_page')) {
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
        'alternateName' => array('AL Métallerie', 'AL Métallerie Thiers', 'AL Métallerie Soudure'),
        'description' => 'Artisan métallier serrurier à Peschadoires près de Thiers (63). Fabrication sur mesure de portails, garde-corps, escaliers, verrières, pergolas, ferronnerie d\'art. Formations soudure MIG/TIG/ARC. Intervention dans tout le Puy-de-Dôme (50km). Devis gratuit sous 48h.',
        'url' => 'https://www.al-metallerie.fr',
        'telephone' => '+33673333532',
        'email' => 'contact@al-metallerie.fr',
        'image' => array(
            'https://www.al-metallerie.fr/wp-content/themes/almetal-theme/assets/images/logo.png',
            'https://www.al-metallerie.fr/wp-content/themes/almetal-theme/assets/images/og-image.jpg'
        ),
        'logo' => array(
            '@type' => 'ImageObject',
            'url' => 'https://www.al-metallerie.fr/wp-content/themes/almetal-theme/assets/images/logo.png',
            'width' => 200,
            'height' => 200
        ),
        'priceRange' => '€€',
        'currenciesAccepted' => 'EUR',
        'paymentAccepted' => array('Espèces', 'Chèque', 'Virement bancaire', 'Carte bancaire'),
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
            'latitude' => 45.9052,
            'longitude' => 3.4688
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
            'geoRadius' => '50000'
        ),
        'sameAs' => array(
            'https://www.facebook.com/al.metallerie.soudure',
            'https://www.instagram.com/al.metallerie.soudure/',
            'https://www.linkedin.com/in/aur%C3%A9lien-lasteyras-184596202/'
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
            'Fabrication sur mesure',
            'Portails sur mesure',
            'Garde-corps',
            'Escaliers métalliques',
            'Verrières',
            'Pergolas',
            'Formations soudure'
        ),
        'slogan' => 'Votre artisan métallier serrurier à Thiers - Fabrication sur mesure',
        'foundingDate' => '2020',
        'founder' => array(
            '@type' => 'Person',
            'name' => 'Aurélien Lasteyras',
            'jobTitle' => 'Artisan métallier serrurier'
        ),
        'aggregateRating' => array(
            '@type' => 'AggregateRating',
            'ratingValue' => '5',
            'reviewCount' => '15',
            'bestRating' => '5',
            'worstRating' => '1'
        ),
        'contactPoint' => array(
            '@type' => 'ContactPoint',
            'telephone' => '+33673333532',
            'contactType' => 'customer service',
            'availableLanguage' => 'French',
            'areaServed' => 'FR'
        )
    );
    
    echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>' . "\n";
}
add_action('wp_head', 'almetal_schema_local_business', 5);

/**
 * Schema JSON-LD Organization pour la page d'accueil
 */
function almetal_schema_organization() {
    if (!is_front_page() && !is_home()) {
        return;
    }
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        '@id' => 'https://www.al-metallerie.fr/#organization',
        'name' => 'AL Métallerie & Soudure',
        'url' => 'https://www.al-metallerie.fr',
        'logo' => array(
            '@type' => 'ImageObject',
            'url' => 'https://www.al-metallerie.fr/wp-content/themes/almetal-theme/assets/images/logo.png',
            'width' => 200,
            'height' => 200
        ),
        'description' => 'Entreprise artisanale de métallerie et soudure basée à Peschadoires (63). Spécialiste de la fabrication sur mesure : portails, garde-corps, escaliers, verrières, pergolas. Formations soudure.',
        'foundingDate' => '2020',
        'founder' => array(
            '@type' => 'Person',
            'name' => 'Aurélien Lasteyras',
            'jobTitle' => 'Gérant - Artisan métallier serrurier'
        ),
        'address' => array(
            '@type' => 'PostalAddress',
            'streetAddress' => '14 route de Maringues',
            'addressLocality' => 'Peschadoires',
            'postalCode' => '63920',
            'addressRegion' => 'Auvergne-Rhône-Alpes',
            'addressCountry' => 'FR'
        ),
        'contactPoint' => array(
            '@type' => 'ContactPoint',
            'telephone' => '+33673333532',
            'contactType' => 'customer service',
            'email' => 'contact@al-metallerie.fr',
            'availableLanguage' => 'French'
        ),
        'sameAs' => array(
            'https://www.facebook.com/al.metallerie.soudure',
            'https://www.instagram.com/al.metallerie.soudure/',
            'https://www.linkedin.com/in/aur%C3%A9lien-lasteyras-184596202/'
        ),
        'knowsAbout' => array(
            'Métallerie',
            'Ferronnerie',
            'Serrurerie',
            'Soudure MIG/TIG/ARC',
            'Fabrication sur mesure'
        ),
        'hasCredential' => array(
            '@type' => 'EducationalOccupationalCredential',
            'credentialCategory' => 'Artisan qualifié',
            'name' => 'Artisan métallier'
        )
    );
    
    echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>' . "\n";
}
add_action('wp_head', 'almetal_schema_organization', 6);

/**
 * Schema JSON-LD FAQPage pour la page d'accueil
 * Questions fréquentes sur les services de métallerie
 */
function almetal_schema_faq_homepage() {
    if (!is_front_page() && !is_home()) {
        return;
    }
    
    $faqs = array(
        array(
            'question' => 'Quel est le prix d\'un garde-corps sur mesure ?',
            'answer' => 'Le prix d\'un garde-corps sur mesure dépend de plusieurs facteurs : matériau (acier, inox, aluminium), dimensions, design et finition. Comptez en moyenne entre 150€ et 400€ par mètre linéaire pose comprise. Contactez-nous pour un devis gratuit personnalisé.'
        ),
        array(
            'question' => 'Quels sont les délais de fabrication ?',
            'answer' => 'Les délais de fabrication varient selon la complexité du projet. Pour un portail standard, comptez 2 à 3 semaines. Pour des réalisations plus complexes (escaliers, verrières), prévoyez 4 à 6 semaines. Nous vous communiquons un délai précis lors du devis.'
        ),
        array(
            'question' => 'Quelle est votre zone d\'intervention ?',
            'answer' => 'Nous intervenons dans un rayon de 50 km autour de Thiers (63), couvrant tout le Puy-de-Dôme : Clermont-Ferrand, Riom, Vichy, Ambert, Issoire, et les communes environnantes. Pour des projets plus éloignés, contactez-nous pour étudier la faisabilité.'
        ),
        array(
            'question' => 'Le devis est-il gratuit ?',
            'answer' => 'Oui, le devis est entièrement gratuit et sans engagement. Nous nous déplaçons chez vous pour prendre les mesures et comprendre vos besoins. Vous recevez un devis détaillé sous 48h avec le prix ferme et définitif.'
        ),
        array(
            'question' => 'Proposez-vous des formations en soudure ?',
            'answer' => 'Oui, AL Métallerie propose des formations soudure pour particuliers et professionnels dans notre atelier à Peschadoires. Nous enseignons les techniques MIG, TIG et ARC. Stages découverte, perfectionnement et préparation CAP disponibles.'
        ),
        array(
            'question' => 'Quels matériaux travaillez-vous ?',
            'answer' => 'Nous travaillons principalement l\'acier, l\'inox et l\'aluminium. Nous réalisons également des ouvrages en fer forgé pour la ferronnerie d\'art. Chaque matériau est choisi en fonction de votre projet et de son environnement (intérieur/extérieur).'
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
add_action('wp_head', 'almetal_schema_faq_homepage', 7);

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
        'description' => 'Fabrication et installation de ' . strtolower($term->name) . ' sur mesure par AL Métallerie, artisan métallier serrurier à Thiers (63). Travail artisanal de qualité, devis gratuit.',
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
                Votre Artisan Métallier Serrurier à Thiers, Puy-de-Dôme
            </h2>
            <div style="color: rgba(255,255,255,0.7); font-size: 0.9rem; line-height: 1.9; text-align: justify; column-count: 1;">
                <p style="margin-bottom: 1rem;">
                    <strong style="color: #F08B18;">AL Métallerie & Soudure</strong> est votre artisan métallier serrurier de confiance, 
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
                    ou par email à <a href="mailto:contact@al-metallerie.fr" style="color: #F08B18;">contact@al-metallerie.fr</a>.
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
/**
 * Liste des étiquettes lisibles pour les matières
 */
function almetal_realisation_matiere_labels() {
    return array(
        'acier'      => 'Acier',
        'inox'       => 'Inox',
        'aluminium'  => 'Aluminium',
        'fer-forge'  => 'Fer forgé',
        'mixte'      => 'Mixte (acier/bois)',
        'brut'       => 'Aspect brut ciré',
    );
}

/**
 * Liste des étiquettes pour les finitions/peintures
 */
function almetal_realisation_peinture_labels() {
    return array(
        'thermolaquage'   => 'Thermolaquage',
        'galvanisation'   => 'Galvanisation à chaud',
        'peinture-epoxy'  => 'Peinture époxy',
        'brut'            => 'Aspect brut ciré',
        'inox-brosse'     => 'Inox brossé',
        'brut-ciré'       => 'Brut ciré',
        'peinture-ral'    => 'Peinture RAL',
    );
}

/**
 * Construit un contexte SEO à partir des métadonnées d'une réalisation
 */
function almetal_get_realisation_alt_context($post_id) {
    $post = get_post($post_id);
    if (!$post || $post->post_type !== 'realisation') {
        return array();
    }

    $terms = get_the_terms($post_id, 'type_realisation');
    $type_name = !empty($terms) && !is_wp_error($terms) ? $terms[0]->name : 'Réalisation';

    $matiere = get_post_meta($post_id, '_almetal_matiere', true) ?: 'acier';
    $peinture = get_post_meta($post_id, '_almetal_peinture', true) ?: 'thermolaquage';
    $client_type = get_post_meta($post_id, '_almetal_client_type', true);
    $pose = get_post_meta($post_id, '_almetal_pose', true);
    $lieu = get_post_meta($post_id, '_almetal_lieu', true) ?: 'Thiers';
    $dimensions = get_post_meta($post_id, '_almetal_dimensions', true);
    $duree = get_post_meta($post_id, '_almetal_duree', true);

    $matiere_labels = almetal_realisation_matiere_labels();
    $peinture_labels = almetal_realisation_peinture_labels();

    return array(
        'type_name'      => $type_name,
        'type_slug'      => $terms && !is_wp_error($terms) ? $terms[0]->slug : 'autres',
        'matiere'        => $matiere,
        'matiere_label'  => isset($matiere_labels[$matiere]) ? $matiere_labels[$matiere] : ucfirst($matiere),
        'peinture_label' => isset($peinture_labels[$peinture]) ? $peinture_labels[$peinture] : ucfirst(str_replace('-', ' ', $peinture)),
        'client_type'    => $client_type ?: 'particulier',
        'pose'           => $pose,
        'lieu'           => $lieu,
        'dimensions'     => $dimensions,
        'duree'          => $duree,
    );
}

/**
 * Tronque un texte ALT pour respecter la limite SEO de 125 caractères
 */
function almetal_truncate_alt_text($text, $max = 125) {
    $text = trim(preg_replace('/\s+/', ' ', $text));
    if (mb_strlen($text) <= $max) {
        return $text;
    }

    $trimmed = mb_substr($text, 0, $max);
    $last_space = mb_strrpos($trimmed, ' ');
    if ($last_space !== false) {
        $trimmed = mb_substr($trimmed, 0, $last_space);
    }

    return rtrim($trimmed, ' -');
}

/**
 * Génère un ALT descriptif pour les réalisations
 */
function almetal_build_realisation_alt($post_id, $image_index = 0) {
    $context = almetal_get_realisation_alt_context($post_id);
    if (empty($context)) {
        return get_the_title($post_id);
    }

    $parts = array();
    $material_part = 'en ' . mb_strtolower($context['matiere_label']);
    if (!empty($context['peinture_label'])) {
        $material_part .= ' ' . mb_strtolower($context['peinture_label']);
    }
    $parts[] = $material_part;

    if (!empty($context['client_type']) && $context['client_type'] !== 'particulier') {
        $parts[] = 'pour ' . mb_strtolower($context['client_type']);
    }

    if (!empty($context['pose']) && in_array(mb_strtolower($context['pose']), array('oui', '1', 'true'), true)) {
        $parts[] = 'pose incluse';
    }

    if (!empty($context['dimensions'])) {
        $parts[] = 'dimensions ' . mb_strtolower($context['dimensions']);
    }

    $detail = implode(' ', array_filter($parts));
    if (empty($detail)) {
        $detail = 'sur mesure';
    }

    $type = ucfirst(mb_strtolower($context['type_name']));
    $lieu = ucwords(mb_strtolower($context['lieu']));
    $alt = $type . ' ' . $detail . ' - ' . $lieu;
    if ($image_index > 0) {
        $alt .= ' (détail ' . ($image_index + 1) . ')';
    }

    return almetal_truncate_alt_text($alt);
}

/**
 * Tronque une méta description pour ne pas dépasser 155 caractères
 */
function almetal_truncate_meta_description($text, $max = 155) {
    $text = trim(preg_replace('/\s+/', ' ', $text));
    if (mb_strlen($text) <= $max) {
        return $text;
    }

    $trimmed = mb_substr($text, 0, $max);
    $last_space = mb_strrpos($trimmed, ' ');
    if ($last_space !== false) {
        $trimmed = mb_substr($trimmed, 0, $last_space);
    }

    return rtrim($trimmed, '.,- ') . '...';
}

/**
 * Génère une méta description SEO structurée selon le contexte
 */
function almetal_build_meta_description($context = array()) {
    $defaults = array(
        'type' => 'Métallerie artisanale',
        'detail' => 'fabrication et pose sur mesure',
        'city' => 'Thiers (63)',
        'material' => '',
        'duration' => '',
        'cta' => 'Devis gratuit sous 48h.',
    );
    $context = wp_parse_args($context, $defaults);

    $parts = array();
    $parts[] = ucfirst($context['type']);

    if (!empty($context['material'])) {
        $parts[] = 'en ' . $context['material'];
    }

    $parts[] = $context['detail'];
    $parts[] = 'à ' . $context['city'];

    if (!empty($context['duration'])) {
        $parts[] = $context['duration'];
    }

    $parts[] = $context['cta'];

    $description = implode('. ', array_filter($parts));
    return almetal_truncate_meta_description($description);
}

function almetal_auto_image_alt($attr, $attachment, $size) {
    // Si l'alt est déjà défini et non vide, ne pas le modifier
    if (!empty($attr['alt'])) {
        return $attr;
    }
    
    $alt = get_the_title($attachment->ID);

    if (is_singular('realisation')) {
        $attr['alt'] = almetal_build_realisation_alt(get_the_ID());
        return $attr;
    }
    // Sur les pages ville
    if (is_singular('city_page')) {
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

/**
 * Afficher la FAQ visuelle pour les pages taxonomie type_realisation
 */
function almetal_display_taxonomy_faq() {
    if (!is_tax('type_realisation')) {
        return;
    }
    
    $term = get_queried_object();
    if (!$term) {
        return;
    }
    
    // FAQ par type de réalisation
    $faqs_by_type = array(
        'portails' => array(
            array('question' => 'Quel est le prix d\'un portail sur mesure ?', 'answer' => 'Le prix d\'un portail sur mesure varie entre 1 500€ et 5 000€ selon les dimensions, le matériau (acier, inox, aluminium) et la motorisation. Contactez-nous pour un devis gratuit personnalisé.'),
            array('question' => 'Quel est le délai de fabrication ?', 'answer' => 'Comptez en moyenne 3 à 4 semaines pour la fabrication d\'un portail sur mesure, selon la complexité du design et les finitions choisies.'),
            array('question' => 'Assurez-vous la pose ?', 'answer' => 'Oui, nous assurons la pose complète de votre portail, incluant la préparation du terrain, l\'installation et les réglages. Garantie décennale incluse.'),
            array('question' => 'Quels matériaux utilisez-vous ?', 'answer' => 'Nous travaillons l\'acier, l\'inox et l\'aluminium. Chaque matériau a ses avantages : l\'acier pour la robustesse, l\'inox pour la durabilité, l\'aluminium pour la légèreté.'),
            array('question' => 'Quelle garantie proposez-vous ?', 'answer' => 'Garantie décennale sur les éléments structurels et 10 ans sur les finitions thermolaquées. Matériaux professionnels et soudure certifiée.')
        ),
        'garde-corps' => array(
            array('question' => 'Quel est le prix d\'un garde-corps au mètre linéaire ?', 'answer' => 'Le prix d\'un garde-corps sur mesure varie entre 150€ et 400€ par mètre linéaire pose comprise, selon le matériau et le design choisi.'),
            array('question' => 'Les garde-corps sont-ils conformes aux normes ?', 'answer' => 'Oui, tous nos garde-corps respectent la norme NF P01-012 : hauteur minimale de 1m, espacement des barreaux inférieur à 11cm, résistance aux charges.'),
            array('question' => 'Quel délai pour un garde-corps ?', 'answer' => 'Comptez 2 à 3 semaines pour la fabrication et la pose d\'un garde-corps standard. Les projets complexes peuvent nécessiter un délai supplémentaire.'),
            array('question' => 'Quels styles proposez-vous ?', 'answer' => 'Nous réalisons tous les styles : contemporain avec câbles inox, classique à barreaux, design avec verre, ou traditionnel en fer forgé.'),
            array('question' => 'Quel entretien pour un garde-corps ?', 'answer' => 'Un garde-corps thermolaqué nécessite peu d\'entretien : un nettoyage à l\'eau savonneuse 2 fois par an suffit. Garantie anticorrosion de 10 ans.')
        ),
        'escaliers' => array(
            array('question' => 'Quel est le prix d\'un escalier métallique ?', 'answer' => 'Le prix d\'un escalier métallique sur mesure varie entre 3 000€ et 15 000€ selon le type (droit, quart tournant, hélicoïdal), les dimensions et les finitions.'),
            array('question' => 'Quels types de marches proposez-vous ?', 'answer' => 'Nous proposons des marches en tôle larmée, caillebotis, bois massif ou verre selon vos préférences et l\'usage prévu (intérieur/extérieur).'),
            array('question' => 'L\'escalier inclut-il la rampe ?', 'answer' => 'Oui, nos escaliers sont livrés complets avec rampe et garde-corps assortis, fabriqués dans le même matériau pour une harmonie parfaite.'),
            array('question' => 'Quel délai de fabrication ?', 'answer' => 'Un escalier sur mesure nécessite 4 à 6 semaines de fabrication, incluant la prise de mesures, la conception et la réalisation en atelier.'),
            array('question' => 'Quelle garantie sur les escaliers ?', 'answer' => 'Garantie décennale sur la structure métallique et 10 ans sur les finitions. Tous nos escaliers sont conformes aux normes de sécurité.')
        ),
        'default' => array(
            array('question' => 'Proposez-vous des devis gratuits ?', 'answer' => 'Oui, nous nous déplaçons gratuitement pour étudier votre projet et vous remettre un devis détaillé sous 48h, sans engagement.'),
            array('question' => 'Quelle est votre zone d\'intervention ?', 'answer' => 'Nous intervenons dans un rayon de 50km autour de Thiers, couvrant tout le Puy-de-Dôme : Clermont-Ferrand, Riom, Vichy, Ambert, Issoire...'),
            array('question' => 'Assurez-vous la pose ?', 'answer' => 'Oui, nous assurons la fabrication ET la pose de tous nos ouvrages. Installation professionnelle avec garantie décennale.'),
            array('question' => 'Quels matériaux utilisez-vous ?', 'answer' => 'Nous travaillons l\'acier, l\'inox, l\'aluminium et le fer forgé. Chaque projet est réalisé avec des matériaux de qualité professionnelle.'),
            array('question' => 'Quelle garantie proposez-vous ?', 'answer' => 'Garantie décennale sur les éléments structurels et 2 ans sur les finitions. Matériaux professionnels et soudure certifiée.')
        )
    );
    
    $type_key = isset($faqs_by_type[$term->slug]) ? $term->slug : 'default';
    $faqs = $faqs_by_type[$type_key];
    
    ?>
    <div class="taxonomy-faq-section">
        <div class="container">
            <h2>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
                Questions fréquentes sur nos <?php echo esc_html(strtolower($term->name)); ?>
            </h2>
            
            <div class="faq-list">
                <?php foreach ($faqs as $index => $faq) : ?>
                    <div class="faq-item<?php echo $index === 0 ? ' active' : ''; ?>">
                        <button class="faq-question">
                            <span><?php echo esc_html($faq['question']); ?></span>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                        </button>
                        <div class="faq-answer">
                            <p><?php echo esc_html($faq['answer']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="taxonomy-faq-cta">
                <p><strong>Vous avez un projet de <?php echo esc_html(strtolower($term->name)); ?> ?</strong></p>
                <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn-primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                    Demander un devis gratuit
                </a>
                <span class="phone-link">ou appelez le <a href="tel:+33673333532">06 73 33 35 32</a></span>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Générer un attribut ALT SEO optimisé pour les images de réalisations
 * 
 * @param int $post_id ID du post réalisation
 * @param int $image_index Index de l'image dans la galerie (optionnel)
 * @return string Attribut ALT optimisé
 */
function almetal_generate_image_alt($post_id, $image_index = 0) {
    return almetal_build_realisation_alt($post_id, $image_index);
}

/**
 * Générer une balise img optimisée SEO avec srcset responsive
 * 
 * @param int $attachment_id ID de l'image
 * @param string $size Taille de l'image
 * @param array $args Arguments supplémentaires (alt, class, loading, etc.)
 * @return string HTML de la balise img
 */
function almetal_optimized_image($attachment_id, $size = 'large', $args = array()) {
    $defaults = array(
        'alt' => '',
        'class' => '',
        'loading' => 'lazy',
        'decoding' => 'async',
        'fetchpriority' => '',
        'post_id' => 0,
        'image_index' => 0,
    );
    $args = wp_parse_args($args, $defaults);
    
    // Récupérer les données de l'image
    $image_src = wp_get_attachment_image_src($attachment_id, $size);
    if (!$image_src) {
        return '';
    }
    
    $src = $image_src[0];
    $width = $image_src[1];
    $height = $image_src[2];
    
    // Générer l'ALT si non fourni
    if (empty($args['alt'])) {
        if ($args['post_id']) {
            $args['alt'] = almetal_generate_image_alt($args['post_id'], $args['image_index']);
        } else {
            $args['alt'] = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
            if (empty($args['alt'])) {
                $args['alt'] = get_the_title($attachment_id) . ' - AL Métallerie Thiers';
            }
        }
    }
    
    // Générer le srcset
    $srcset = wp_get_attachment_image_srcset($attachment_id, $size);
    $sizes = wp_get_attachment_image_sizes($attachment_id, $size);
    
    // Construire la balise img
    $html = '<img';
    $html .= ' src="' . esc_url($src) . '"';
    $html .= ' alt="' . esc_attr($args['alt']) . '"';
    $html .= ' width="' . esc_attr($width) . '"';
    $html .= ' height="' . esc_attr($height) . '"';
    
    if ($srcset) {
        $html .= ' srcset="' . esc_attr($srcset) . '"';
    }
    if ($sizes) {
        $html .= ' sizes="' . esc_attr($sizes) . '"';
    }
    if (!empty($args['class'])) {
        $html .= ' class="' . esc_attr($args['class']) . '"';
    }
    if (!empty($args['loading'])) {
        $html .= ' loading="' . esc_attr($args['loading']) . '"';
    }
    if (!empty($args['decoding'])) {
        $html .= ' decoding="' . esc_attr($args['decoding']) . '"';
    }
    if (!empty($args['fetchpriority'])) {
        $html .= ' fetchpriority="' . esc_attr($args['fetchpriority']) . '"';
    }
    
    $html .= '>';
    
    return $html;
}

/**
 * Filtrer les attributs ALT des images de réalisations automatiquement
 */
function almetal_filter_realisation_image_alt($attr, $attachment, $size) {
    global $post;
    
    // Seulement pour les réalisations
    if (!$post || $post->post_type !== 'realisation') {
        return $attr;
    }
    
    // Si l'ALT est vide ou générique, le remplacer
    if (empty($attr['alt']) || $attr['alt'] === get_the_title($attachment->ID)) {
        $image_index = !empty($attr['data-image-index']) ? (int) $attr['data-image-index'] : 0;
        $attr['alt'] = almetal_build_realisation_alt($post->ID, $image_index);
    }
    
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'almetal_filter_realisation_image_alt', 10, 3);

/**
 * Ajouter automatiquement width et height aux images pour éviter CLS
 */
function almetal_add_image_dimensions($content) {
    if (is_admin()) {
        return $content;
    }
    
    // Regex pour trouver les images sans width/height
    $pattern = '/<img([^>]+)src=["\']([^"\']+)["\']([^>]*)>/i';
    
    $content = preg_replace_callback($pattern, function($matches) {
        $before = $matches[1];
        $src = $matches[2];
        $after = $matches[3];
        
        // Vérifier si width et height sont déjà présents
        if (strpos($before . $after, 'width=') !== false && strpos($before . $after, 'height=') !== false) {
            return $matches[0];
        }
        
        // Essayer de récupérer les dimensions
        $attachment_id = attachment_url_to_postid($src);
        if ($attachment_id) {
            $image_data = wp_get_attachment_image_src($attachment_id, 'full');
            if ($image_data) {
                $width = $image_data[1];
                $height = $image_data[2];
                
                $dimensions = '';
                if (strpos($before . $after, 'width=') === false) {
                    $dimensions .= ' width="' . $width . '"';
                }
                if (strpos($before . $after, 'height=') === false) {
                    $dimensions .= ' height="' . $height . '"';
                }
                
                return '<img' . $before . 'src="' . $src . '"' . $after . $dimensions . '>';
            }
        }
        
        return $matches[0];
    }, $content);
    
    return $content;
}
add_filter('the_content', 'almetal_add_image_dimensions', 20);
