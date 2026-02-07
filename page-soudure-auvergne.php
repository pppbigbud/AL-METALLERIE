<?php
/**
 * Template Name: Zones d'intervention
 * Template pour la page /soudure-auvergne/
 * Carte interactive des zones d'intervention + contenu SEO
 * 
 * @package ALMetallerie
 * @since 2.0.0
 */

get_header();

// Détection mobile
if (function_exists('almetal_is_mobile') && almetal_is_mobile()) {
    get_template_part('template-parts/header', 'mobile');
    ?>
    <main class="mobile-page-zones">
        <?php get_template_part('template-parts/page-soudure-auvergne', 'mobile'); ?>
    </main>
    <?php
    get_footer();
    return;
}

// Récupérer toutes les pages ville publiées avec leurs coordonnées
$city_pages = get_posts(array(
    'post_type'      => 'city_page',
    'posts_per_page' => 300,
    'post_status'    => 'publish',
    'orderby'        => 'title',
    'order'          => 'ASC',
));

// Préparer les données des villes pour la carte
$cities_data = array();
$departments = array();

foreach ($city_pages as $city) {
    $city_name = get_post_meta($city->ID, '_cpg_city_name', true) ?: get_the_title($city->ID);
    $city_name = str_replace(array('Métallier Serrurier à ', 'Métallier Ferronnier à '), '', $city_name);
    
    $lat = get_post_meta($city->ID, '_cpg_latitude', true);
    $lng = get_post_meta($city->ID, '_cpg_longitude', true);
    $postal_code = get_post_meta($city->ID, '_cpg_postal_code', true);
    $department = get_post_meta($city->ID, '_cpg_department', true) ?: 'Puy-de-Dôme';
    $permalink = get_permalink($city->ID);
    
    // Grouper par département
    if (!isset($departments[$department])) {
        $departments[$department] = array();
    }
    $departments[$department][] = array(
        'name' => $city_name,
        'url'  => $permalink,
        'postal_code' => $postal_code,
    );
    
    // Données pour la carte (seulement si coordonnées disponibles)
    if ($lat && $lng) {
        $cities_data[] = array(
            'name'        => $city_name,
            'lat'         => floatval($lat),
            'lng'         => floatval($lng),
            'url'         => $permalink,
            'postal_code' => $postal_code,
            'department'  => $department,
        );
    }
}

// Trier les départements par nom
ksort($departments);

// Coordonnées de l'atelier (Peschadoires)
$atelier_lat = 45.8167;
$atelier_lng = 3.5167;

// Compter les villes
$total_cities = count($city_pages);

// Catégories de réalisations pour le maillage interne
$categories = get_terms(array(
    'taxonomy'   => 'type_realisation',
    'hide_empty' => true,
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 10,
));
?>

<main class="zones-page-main">
    
    <!-- HERO SECTION -->
    <section class="zones-hero">
        <div class="zones-hero__background">
            <div class="zones-hero__overlay"></div>
        </div>
        <div class="container">
            <div class="zones-hero__content">
                <!-- Breadcrumb -->
                <nav class="city-breadcrumb" aria-label="Fil d'Ariane" itemscope itemtype="https://schema.org/BreadcrumbList">
                    <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <a href="<?php echo home_url(); ?>" itemprop="item"><span itemprop="name">Accueil</span></a>
                        <meta itemprop="position" content="1">
                    </span>
                    <span class="separator">›</span>
                    <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <span itemprop="name" class="current">Zones d'intervention</span>
                        <meta itemprop="position" content="2">
                    </span>
                </nav>

                <div class="zones-tag">
                    <span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        Auvergne – Puy-de-Dôme (63)
                    </span>
                </div>

                <h1 class="zones-hero__title">
                    Métallier Serrurier en <span class="highlight">Auvergne</span>
                </h1>

                <p class="zones-hero__subtitle">
                    AL Métallerie & Soudure intervient dans tout le Puy-de-Dôme et les départements limitrophes. 
                    Découvrez nos <strong><?php echo $total_cities; ?> zones d'intervention</strong> pour vos projets de métallerie, 
                    serrurerie et ferronnerie sur mesure.
                </p>

                <div class="zones-hero__cta">
                    <a href="<?php echo home_url('/contact/'); ?>" class="btn btn-primary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        Devis gratuit
                    </a>
                    <a href="tel:0673333532" class="btn btn-secondary">
                        06 73 33 35 32
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- CARTE INTERACTIVE -->
    <section class="zones-map-section">
        <div class="container">
            <h2 class="zones-section-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/>
                    <line x1="8" y1="2" x2="8" y2="18"/>
                    <line x1="16" y1="6" x2="16" y2="22"/>
                </svg>
                Notre zone d'intervention
            </h2>
            <p class="zones-section-subtitle">
                Basés à <strong>Peschadoires (63920)</strong>, nous intervenons dans un rayon de 50 km. 
                Cliquez sur un marqueur pour découvrir nos services dans votre ville.
            </p>
            
            <div class="zones-map-wrapper">
                <div id="zones-map" class="zones-map"></div>
                
                <!-- Légende -->
                <div class="zones-map-legend">
                    <div class="legend-item legend-item--atelier">
                        <span class="legend-marker legend-marker--atelier"></span>
                        <span>Notre atelier – Peschadoires</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-marker legend-marker--city"></span>
                        <span><?php echo $total_cities; ?> villes d'intervention</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-marker legend-marker--zone"></span>
                        <span>Rayon de 50 km</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- LISTE DES VILLES PAR DÉPARTEMENT -->
    <section class="zones-cities-section">
        <div class="container">
            <h2 class="zones-section-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>
                Toutes nos zones d'intervention
            </h2>
            <p class="zones-section-subtitle">
                Retrouvez ci-dessous l'ensemble des villes où nous intervenons, classées par département.
            </p>

            <div class="zones-departments">
                <?php foreach ($departments as $dept_name => $dept_cities) : ?>
                <div class="zones-department">
                    <h3 class="zones-department__title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        <?php echo esc_html($dept_name); ?>
                        <span class="zones-department__count"><?php echo count($dept_cities); ?> ville<?php echo count($dept_cities) > 1 ? 's' : ''; ?></span>
                    </h3>
                    <div class="zones-cities-grid">
                        <?php foreach ($dept_cities as $city) : ?>
                        <a href="<?php echo esc_url($city['url']); ?>" class="zones-city-card">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            <span class="zones-city-card__name"><?php echo esc_html($city['name']); ?></span>
                            <?php if ($city['postal_code']) : ?>
                            <span class="zones-city-card__code"><?php echo esc_html($city['postal_code']); ?></span>
                            <?php endif; ?>
                            <svg class="zones-city-card__arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- SERVICES -->
    <section class="zones-services-section">
        <div class="container">
            <h2 class="zones-section-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                </svg>
                Nos services de métallerie en Auvergne
            </h2>
            <p class="zones-section-subtitle">
                Quel que soit votre projet, nous vous accompagnons de la conception à la pose, 
                avec un savoir-faire artisanal et des matériaux de qualité.
            </p>

            <div class="zones-services-grid">
                <?php if ($categories && !is_wp_error($categories)) : ?>
                    <?php foreach ($categories as $cat) : 
                        $service_icons = array(
                            'portails' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="7" height="18" rx="1"/><rect x="14" y="3" width="7" height="18" rx="1"/></svg>',
                            'garde-corps' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 12h18M3 6h18M3 18h18"/></svg>',
                            'escaliers' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 20h4v-4h4v-4h4V8h4"/></svg>',
                            'pergolas' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 21h18M4 18h16M5 15h14M6 12h12"/></svg>',
                            'grilles' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18M15 3v18"/></svg>',
                            'ferronnerie-dart' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 20c4-4 4-12 8-12s4 8 8 12"/><circle cx="12" cy="8" r="2"/></svg>',
                            'serrurerie' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 1 1 8 0v4"/></svg>',
                            'vehicules' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 17h14v-5l-2-4H7l-2 4v5z"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/></svg>',
                            'mobilier-metallique' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="4" y="6" width="16" height="4" rx="1"/><path d="M6 10v10M18 10v10M4 14h16"/></svg>',
                        );
                        $icon = isset($service_icons[$cat->slug]) ? $service_icons[$cat->slug] : '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>';
                    ?>
                    <a href="<?php echo esc_url(get_term_link($cat)); ?>" class="zones-service-card">
                        <div class="zones-service-card__icon"><?php echo $icon; ?></div>
                        <h3 class="zones-service-card__title"><?php echo esc_html($cat->name); ?></h3>
                        <span class="zones-service-card__count"><?php echo $cat->count; ?> réalisation<?php echo $cat->count > 1 ? 's' : ''; ?></span>
                    </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- CTA FINAL -->
    <section class="zones-cta-section">
        <div class="container">
            <div class="zones-cta-card">
                <h2>Votre projet de métallerie en Auvergne</h2>
                <p>Vous ne trouvez pas votre ville dans la liste ? Pas de souci, nous intervenons dans tout le Puy-de-Dôme 
                et les départements limitrophes. Contactez-nous pour discuter de votre projet.</p>
                <div class="zones-cta-card__buttons">
                    <a href="<?php echo home_url('/contact/'); ?>" class="btn btn-primary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="4" width="20" height="16" rx="2"/>
                            <path d="M22 6l-10 7L2 6"/>
                        </svg>
                        Demander un devis gratuit
                    </a>
                    <a href="tel:0673333532" class="btn btn-secondary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        06 73 33 35 32
                    </a>
                </div>
            </div>
        </div>
    </section>

</main>

<!-- Leaflet CSS & JS (via cdnjs, autorisé par le CSP) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" crossorigin="">
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js" crossorigin=""></script>

<script>
(function() {
    'use strict';
    
    // Données des villes
    var cities = <?php echo json_encode($cities_data, JSON_UNESCAPED_UNICODE); ?>;
    var atelierLat = <?php echo $atelier_lat; ?>;
    var atelierLng = <?php echo $atelier_lng; ?>;
    
    // Initialiser la carte
    var map = L.map('zones-map', {
        scrollWheelZoom: false,
        zoomControl: true
    }).setView([atelierLat, atelierLng], 10);
    
    // Tuiles sombres pour correspondre au thème
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; <a href="https://carto.com/">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 19
    }).addTo(map);
    
    // Icône personnalisée pour l'atelier
    var atelierIcon = L.divIcon({
        className: 'zones-marker zones-marker--atelier',
        html: '<div class="marker-inner"><svg width="20" height="20" viewBox="0 0 24 24" fill="#F08B18" stroke="#fff" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>',
        iconSize: [40, 40],
        iconAnchor: [20, 40],
        popupAnchor: [0, -40]
    });
    
    // Icône personnalisée pour les villes
    var cityIcon = L.divIcon({
        className: 'zones-marker zones-marker--city',
        html: '<div class="marker-inner"><svg width="14" height="14" viewBox="0 0 24 24" fill="#F08B18" stroke="#fff" stroke-width="2"><circle cx="12" cy="12" r="8"/></svg></div>',
        iconSize: [28, 28],
        iconAnchor: [14, 14],
        popupAnchor: [0, -14]
    });
    
    // Marqueur de l'atelier
    L.marker([atelierLat, atelierLng], { icon: atelierIcon })
        .addTo(map)
        .bindPopup('<div class="zones-popup zones-popup--atelier"><strong>AL Métallerie & Soudure</strong><br>14 route de Maringues<br>63920 Peschadoires<br><a href="tel:0673333532">06 73 33 35 32</a></div>');
    
    // Cercle de 50km
    L.circle([atelierLat, atelierLng], {
        radius: 50000,
        color: '#F08B18',
        fillColor: '#F08B18',
        fillOpacity: 0.05,
        weight: 1.5,
        dashArray: '8, 8',
        opacity: 0.4
    }).addTo(map);
    
    // Marqueurs des villes
    cities.forEach(function(city) {
        L.marker([city.lat, city.lng], { icon: cityIcon })
            .addTo(map)
            .bindPopup('<div class="zones-popup"><strong>' + city.name + '</strong>' + (city.postal_code ? ' (' + city.postal_code + ')' : '') + '<br><a href="' + city.url + '">Voir nos services →</a></div>');
    });
    
    // Ajuster la vue pour inclure tous les marqueurs
    if (cities.length > 0) {
        var bounds = L.latLngBounds([[atelierLat, atelierLng]]);
        cities.forEach(function(city) {
            bounds.extend([city.lat, city.lng]);
        });
        map.fitBounds(bounds.pad(0.1));
    }
    
    // Activer le scroll zoom au clic
    map.on('click', function() {
        map.scrollWheelZoom.enable();
    });
    map.on('mouseout', function() {
        map.scrollWheelZoom.disable();
    });
})();
</script>

<!-- Schema.org LocalBusiness + Service -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "@id": "<?php echo home_url('/#localbusiness'); ?>",
    "name": "AL Métallerie & Soudure",
    "description": "Artisan métallier serrurier en Auvergne. Fabrication sur mesure de portails, garde-corps, escaliers, pergolas. Intervention dans tout le Puy-de-Dôme.",
    "url": "<?php echo home_url(); ?>",
    "telephone": "+33673333532",
    "email": "contact@al-metallerie.fr",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "14 route de Maringues",
        "addressLocality": "Peschadoires",
        "postalCode": "63920",
        "addressRegion": "Auvergne-Rhône-Alpes",
        "addressCountry": "FR"
    },
    "geo": {
        "@type": "GeoCoordinates",
        "latitude": <?php echo $atelier_lat; ?>,
        "longitude": <?php echo $atelier_lng; ?>
    },
    "areaServed": [
        <?php 
        $area_items = array();
        foreach ($departments as $dept_name => $dept_cities) {
            foreach ($dept_cities as $city) {
                $area_items[] = '{"@type": "City", "name": "' . esc_js($city['name']) . '"}';
            }
        }
        echo implode(",\n        ", $area_items);
        ?>
    ],
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "4.8",
        "bestRating": "5",
        "ratingCount": "47"
    }
}
</script>

<?php get_footer(); ?>
