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
        $count = $term->count;
        $description = ucfirst($term->name) . ' sur mesure à Thiers (63) par AL Métallerie & Soudure. ' . $count . ' réalisations. Fabrication artisanale, devis gratuit ☎ 06 73 33 35 32';
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
 * Schema ItemList pour les pages de taxonomie (liste des realisations)
 * Ameliore le SEO en montrant a Google la structure des realisations
 */
function almetal_schema_itemlist_taxonomy() {
    if (!is_tax('type_realisation')) {
        return;
    }
    
    $term = get_queried_object();
    
    // Recuperer les realisations de cette categorie
    $realisations = new WP_Query(array(
        'post_type' => 'realisation',
        'posts_per_page' => 10,
        'tax_query' => array(
            array(
                'taxonomy' => 'type_realisation',
                'field' => 'term_id',
                'terms' => $term->term_id,
            ),
        ),
    ));
    
    if (!$realisations->have_posts()) {
        return;
    }
    
    $items = array();
    $position = 1;
    
    while ($realisations->have_posts()) {
        $realisations->the_post();
        $lieu = get_post_meta(get_the_ID(), '_almetal_lieu', true);
        
        $items[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'item' => array(
                '@type' => 'CreativeWork',
                'name' => get_the_title(),
                'url' => get_permalink(),
                'image' => get_the_post_thumbnail_url(get_the_ID(), 'large'),
                'description' => wp_trim_words(get_the_excerpt(), 20),
                'author' => array(
                    '@type' => 'Organization',
                    'name' => 'AL Metallerie & Soudure'
                ),
                'locationCreated' => $lieu ? array(
                    '@type' => 'Place',
                    'name' => $lieu,
                    'address' => array(
                        '@type' => 'PostalAddress',
                        'addressRegion' => 'Puy-de-Dome',
                        'addressCountry' => 'FR'
                    )
                ) : null
            )
        );
        $position++;
    }
    wp_reset_postdata();
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => ucfirst($term->name) . ' - Realisations AL Metallerie',
        'description' => 'Decouvrez nos ' . strtolower($term->name) . ' sur mesure realises par AL Metallerie & Soudure a Thiers (63)',
        'numberOfItems' => $term->count,
        'itemListElement' => $items
    );
    
    echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>' . "\n";
}
add_action('wp_head', 'almetal_schema_itemlist_taxonomy', 8);

/**
 * Schema FAQPage pour les pages de taxonomie
 * Genere automatiquement des FAQ pertinentes pour chaque categorie
 */
function almetal_schema_faq_taxonomy() {
    if (!is_tax('type_realisation')) {
        return;
    }
    
    $term = get_queried_object();
    $term_name = $term->name;
    $term_name_lower = strtolower($term_name);
    
    // FAQ generiques adaptees a chaque categorie
    $faqs = array(
        array(
            'question' => 'Quel est le prix d\'un ' . $term_name_lower . ' sur mesure ?',
            'answer' => 'Le prix d\'un ' . $term_name_lower . ' sur mesure depend de plusieurs facteurs : dimensions, materiaux (acier, inox, aluminium), finitions et complexite du design. Chez AL Metallerie & Soudure, nous etablissons un devis gratuit et personnalise apres etude de votre projet. Contactez-nous au 06 73 33 35 32 pour obtenir une estimation.'
        ),
        array(
            'question' => 'Quel est le delai de fabrication pour un ' . $term_name_lower . ' ?',
            'answer' => 'Le delai de fabrication varie selon la complexite du projet. En general, comptez 2 a 4 semaines pour un ' . $term_name_lower . ' standard, et jusqu\'a 6 semaines pour des realisations plus elaborees. Nous vous communiquons un planning precis lors de la validation du devis.'
        ),
        array(
            'question' => 'Intervenez-vous pour la pose des ' . $term_name_lower . ' ?',
            'answer' => 'Oui, AL Metallerie & Soudure assure la fabrication ET la pose de tous nos ouvrages. Nous intervenons dans tout le Puy-de-Dome (63) et les departements limitrophes : Clermont-Ferrand, Thiers, Riom, Issoire, Ambert et leurs environs.'
        ),
        array(
            'question' => 'Quels materiaux utilisez-vous pour les ' . $term_name_lower . ' ?',
            'answer' => 'Nous travaillons principalement l\'acier (brut, galvanise ou thermolaque), l\'inox (304 ou 316 pour l\'exterieur) et l\'aluminium. Le choix du materiau depend de l\'usage, de l\'esthetique souhaitee et du budget. Nous vous conseillons sur la meilleure option pour votre projet.'
        ),
        array(
            'question' => 'Proposez-vous une garantie sur vos ' . $term_name_lower . ' ?',
            'answer' => 'Tous nos ouvrages beneficient d\'une garantie decennale pour les elements structurels et d\'une garantie de 2 ans sur les finitions. Nous utilisons des materiaux de qualite professionnelle et des techniques de soudure certifiees pour assurer la durabilite de nos realisations.'
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
add_action('wp_head', 'almetal_schema_faq_taxonomy', 9);

/**
 * Generer du contenu SEO dynamique pour les nouvelles categories
 * Utilise quand une categorie n'a pas de contenu predefini
 */
function almetal_get_dynamic_seo_content($term_slug, $term_name) {
    // Contenus predefinis
    $predefined = array(
        'portails', 'garde-corps', 'escaliers', 'ferronnerie-dart', 
        'grilles', 'serrurerie', 'mobilier-metallique', 'vehicules', 'autres'
    );
    
    // Si la categorie a un contenu predefini, retourner null
    if (in_array($term_slug, $predefined)) {
        return null;
    }
    
    // Generer du contenu dynamique pour les nouvelles categories
    $term_name_lower = strtolower($term_name);
    
    return array(
        'intro' => 'Specialiste de la fabrication de ' . $term_name_lower . ' sur mesure dans le Puy-de-Dome, AL Metallerie & Soudure concoit des ouvrages adaptes a vos besoins et a votre environnement.',
        'details' => 'Nos ' . $term_name_lower . ' sont fabriques dans notre atelier a Peschadoires, pres de Thiers. Nous utilisons des materiaux de qualite (acier, inox, aluminium) et des techniques de soudure professionnelles (MIG, TIG, ARC) pour garantir la solidite et la durabilite de chaque realisation. Chaque projet est etudie sur place pour une adaptation parfaite. Intervention dans tout le Puy-de-Dome : Thiers, Clermont-Ferrand, Riom, Issoire et leurs environs.',
        'cta' => 'Demandez votre devis gratuit pour vos ' . $term_name_lower . ' sur mesure.'
    );
}

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
