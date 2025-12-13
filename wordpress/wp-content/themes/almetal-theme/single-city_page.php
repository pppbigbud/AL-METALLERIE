<?php
/**
 * Template pour les pages ville (CPT city_page)
 * Style cohérent avec les pages réalisations et formations
 */

get_header();

// Détection mobile - Charger le header mobile si nécessaire
if (almetal_is_mobile()) {
    // Récupérer les données de la page ville
    $city_name = get_post_meta(get_the_ID(), '_cpg_city_name', true) ?: get_the_title();
    $city_display = get_the_title();
    $city_display = str_replace('Métallier Serrurier à ', '', $city_display);
    $city_display = str_replace('Métallier Ferronnier à ', '', $city_display);
    if (empty($city_display) || $city_display === get_the_title()) {
        $city_display = $city_name;
    }
    ?>
    <!-- Header Mobile avec menu burger -->
    <?php get_template_part('template-parts/header', 'mobile'); ?>
    
    <main class="mobile-page-city">
        <?php get_template_part('template-parts/single-city_page', 'mobile'); ?>
    </main>
    
    <?php
    get_footer();
    return;
}

// Récupérer les données de la page ville
$city_name = get_post_meta(get_the_ID(), '_cpg_city_name', true) ?: get_the_title();
$department = get_post_meta(get_the_ID(), '_cpg_department', true) ?: 'Puy-de-Dôme';
$postal_code = get_post_meta(get_the_ID(), '_cpg_postal_code', true);

// Nettoyer le nom de la ville pour l'affichage (gérer les anciens et nouveaux titres)
$city_display = get_the_title();
$city_display = str_replace('Métallier Serrurier à ', '', $city_display);
$city_display = str_replace('Métallier Ferronnier à ', '', $city_display);
if (empty($city_display) || $city_display === get_the_title()) {
    $city_display = $city_name;
}

// Coordonnées pour la carte (à récupérer via API ou meta)
$lat = get_post_meta(get_the_ID(), '_cpg_latitude', true);
$lng = get_post_meta(get_the_ID(), '_cpg_longitude', true);

// Image hero aléatoire (réalisations) - Portails / Garde-corps / Escaliers
$city_hero_bg_url = null;
$random_realisation_id = get_posts(array(
    'post_type' => 'realisation',
    'posts_per_page' => 1,
    'orderby' => 'rand',
    'fields' => 'ids',
    'meta_query' => array(
        array(
            'key' => '_thumbnail_id',
            'compare' => 'EXISTS',
        ),
    ),
    'tax_query' => array(
        array(
            'taxonomy' => 'type_realisation',
            'field' => 'slug',
            'terms' => array('garde-corps', 'portails', 'escaliers'),
        ),
    ),
));

if (!empty($random_realisation_id) && !is_wp_error($random_realisation_id)) {
    $city_hero_bg_url = get_the_post_thumbnail_url($random_realisation_id[0], 'full');
}

if (!$city_hero_bg_url && has_post_thumbnail()) {
    $city_hero_bg_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
}
?>

<main class="city-page-main">
    <?php while (have_posts()) : the_post(); ?>
    
    <!-- HERO SECTION - Style réalisations -->
    <section class="city-hero">
        <div class="city-hero__background"<?php echo $city_hero_bg_url ? ' style="background-image: url(' . esc_url($city_hero_bg_url) . ');"' : ''; ?>>
            <?php if (!$city_hero_bg_url) : ?>
                <div class="city-hero__gradient"></div>
            <?php endif; ?>
            <div class="city-hero__overlay"></div>
        </div>
        
        <div class="container">
            <div class="city-hero__content">
                <!-- Breadcrumb -->
                <nav class="city-breadcrumb" aria-label="Fil d'Ariane">
                    <a href="<?php echo home_url(); ?>">Accueil</a>
                    <span class="separator">›</span>
                    <a href="<?php echo home_url('/soudure-auvergne/'); ?>">Zones d'intervention</a>
                    <span class="separator">›</span>
                    <span class="current"><?php echo esc_html($city_display); ?></span>
                </nav>
                
                <!-- Tag -->
                <div class="city-tag">
                    <span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        <?php echo esc_html($department); ?>
                    </span>
                </div>
                
                <!-- Titre -->
                <h1 class="city-hero__title">
                    Métallier Serrurier à <span class="highlight"><?php echo esc_html($city_display); ?></span>
                </h1>
                
                <!-- Sous-titre -->
                <p class="city-hero__subtitle">
                    <?php echo get_the_excerpt() ?: "Artisan métallier serrurier intervenant à {$city_display} et ses environs. Portails, garde-corps, escaliers sur mesure."; ?>
                </p>
                
                <!-- CTA -->
                <div class="city-hero__cta">
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
    
    <!-- CONTENU PRINCIPAL -->
    <section class="city-content">
        <div class="container">
            <div class="city-content__grid">
                <!-- Colonne principale -->
                <div class="city-content__main">
                    <div class="city-content__text">
                        <?php the_content(); ?>
                    </div>
                    
                    <!-- Réalisations dans cette ville -->
                    <?php
                    $realisations = get_posts(array(
                        'post_type'      => 'realisation',
                        'posts_per_page' => 6,
                        'meta_query'     => array(
                            array(
                                'key'     => '_almetal_lieu',
                                'value'   => $city_display,
                                'compare' => 'LIKE',
                            ),
                        ),
                    ));
                    
                    if (!empty($realisations)) :
                    ?>
                    <div class="city-realisations">
                        <h2 class="city-section-title">
                            <span class="icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <polyline points="21 15 16 10 5 21"/>
                                </svg>
                            </span>
                            Nos réalisations à <?php echo esc_html($city_display); ?>
                        </h2>
                        
                        <div class="city-realisations__grid">
                            <?php foreach ($realisations as $real) : 
                                $thumb = get_the_post_thumbnail_url($real->ID, 'medium');
                                $types = get_the_terms($real->ID, 'type_realisation');
                                $type_name = (!empty($types) && !is_wp_error($types)) ? $types[0]->name : '';
                            ?>
                            <article class="city-realisation-card">
                                <a href="<?php echo get_permalink($real->ID); ?>" class="city-realisation-card__link">
                                    <?php if ($thumb) : ?>
                                    <div class="city-realisation-card__image">
                                        <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($real->post_title); ?>" loading="lazy">
                                    </div>
                                    <?php endif; ?>
                                    <div class="city-realisation-card__content">
                                        <?php if ($type_name) : ?>
                                        <span class="city-realisation-card__type"><?php echo esc_html($type_name); ?></span>
                                        <?php endif; ?>
                                        <h3 class="city-realisation-card__title"><?php echo esc_html($real->post_title); ?></h3>
                                    </div>
                                </a>
                            </article>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="city-realisations__more">
                            <a href="<?php echo home_url('/realisations/'); ?>" class="btn btn-outline">
                                Voir toutes nos réalisations
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                    <polyline points="12 5 19 12 12 19"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Sidebar -->
                <aside class="city-content__sidebar">
                    <!-- Carte Google Maps -->
                    <div class="city-map-card">
                        <h3 class="city-map-card__title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            Localisation
                        </h3>
                        <div class="city-map-card__map">
                            <iframe 
                                src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&q=<?php echo urlencode($city_display . ', ' . $department . ', France'); ?>&zoom=12"
                                width="100%" 
                                height="250" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                        <p class="city-map-card__info">
                            Nous intervenons à <strong><?php echo esc_html($city_display); ?></strong> et dans un rayon de 50 km autour de notre atelier à Peschadoires.
                        </p>
                    </div>
                    
                    <!-- Contact rapide -->
                    <div class="city-contact-card">
                        <h3 class="city-contact-card__title">Contact rapide</h3>
                        <div class="city-contact-card__items">
                            <a href="tel:0673333532" class="city-contact-card__item">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                                <span>06 73 33 35 32</span>
                            </a>
                            <a href="mailto:contact@al-metallerie.fr" class="city-contact-card__item">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                                <span>contact@al-metallerie.fr</span>
                            </a>
                        </div>
                        <a href="<?php echo home_url('/contact/'); ?>" class="btn btn-primary btn-block">
                            Demander un devis
                        </a>
                    </div>
                    
                    <!-- Services -->
                    <div class="city-services-card">
                        <h3 class="city-services-card__title">Nos services</h3>
                        <ul class="city-services-card__list">
                            <?php
                            $service_terms = get_terms(array(
                                'taxonomy' => 'type_realisation',
                                'hide_empty' => false,
                                'orderby' => 'name',
                                'order' => 'ASC',
                            ));
                            if (!empty($service_terms) && !is_wp_error($service_terms)) :
                                foreach ($service_terms as $term) :
                            ?>
                            <li><a href="<?php echo esc_url(get_term_link($term)); ?>"><?php echo esc_html($term->name); ?></a></li>
                            <?php 
                                endforeach;
                            endif;
                            ?>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </section>
    
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
