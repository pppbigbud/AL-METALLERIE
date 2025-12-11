<?php
/**
 * Template Part pour Single Page Ville - VERSION MOBILE
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

// Récupérer les données de la page ville
$city_name = get_post_meta(get_the_ID(), '_cpg_city_name', true) ?: get_the_title();
$department = get_post_meta(get_the_ID(), '_cpg_department', true) ?: 'Puy-de-Dôme';
$postal_code = get_post_meta(get_the_ID(), '_cpg_postal_code', true);

// Nettoyer le nom de la ville pour l'affichage
$city_display = str_replace('Métallier Ferronnier à ', '', get_the_title());
if (empty($city_display)) {
    $city_display = $city_name;
}
?>

<?php while (have_posts()) : the_post(); ?>

<article class="mobile-single-city">
    <div class="mobile-single-container">
        
        <!-- Tag département -->
        <div class="mobile-single-tags scroll-zoom">
            <span class="mobile-single-tag">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>
                <?php echo esc_html($department); ?>
            </span>
        </div>

        <!-- Titre -->
        <h1 class="mobile-single-title scroll-fade scroll-delay-1">
            Métallier Soudeur à <?php echo esc_html($city_display); ?>
        </h1>

        <!-- Image hero si disponible -->
        <?php if (has_post_thumbnail()) : ?>
            <div class="mobile-single-hero scroll-fade scroll-delay-2">
                <?php the_post_thumbnail('large', array('class' => 'mobile-single-hero-img', 'loading' => 'eager')); ?>
            </div>
        <?php endif; ?>

        <!-- Description -->
        <div class="mobile-single-excerpt scroll-fade scroll-delay-3">
            <p><?php echo get_the_excerpt() ?: "Artisan métallier ferronnier intervenant à {$city_display} et ses environs. Portails, garde-corps, escaliers sur mesure."; ?></p>
        </div>

        <!-- CTA Contact -->
        <div class="mobile-city-cta scroll-fade scroll-delay-4">
            <a href="tel:0673333532" class="mobile-btn-primary">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                </svg>
                06 73 33 35 32
            </a>
            <a href="<?php echo home_url('/contact/'); ?>" class="mobile-btn-secondary">
                Demander un devis
            </a>
        </div>

        <!-- Contenu principal -->
        <div class="mobile-single-content scroll-fade">
            <?php the_content(); ?>
        </div>

        <!-- Réalisations dans cette ville -->
        <?php
        $realisations = get_posts(array(
            'post_type'      => 'realisation',
            'posts_per_page' => 4,
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
        <div class="mobile-city-realisations scroll-fade">
            <h2 class="mobile-section-subtitle">
                Nos réalisations à <?php echo esc_html($city_display); ?>
            </h2>
            
            <div class="mobile-city-realisations-grid">
                <?php foreach ($realisations as $real) : 
                    $thumb = get_the_post_thumbnail_url($real->ID, 'medium');
                    $types = get_the_terms($real->ID, 'type_realisation');
                    $type_name = (!empty($types) && !is_wp_error($types)) ? $types[0]->name : '';
                ?>
                <article class="mobile-city-realisation-card">
                    <a href="<?php echo get_permalink($real->ID); ?>">
                        <?php if ($thumb) : ?>
                        <div class="mobile-city-realisation-image">
                            <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($real->post_title); ?>" loading="lazy">
                            <?php if ($type_name) : ?>
                            <span class="mobile-city-realisation-badge"><?php echo esc_html($type_name); ?></span>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        <h3 class="mobile-city-realisation-title"><?php echo esc_html($real->post_title); ?></h3>
                    </a>
                </article>
                <?php endforeach; ?>
            </div>
            
            <div class="mobile-city-realisations-more">
                <a href="<?php echo home_url('/realisations/'); ?>" class="mobile-btn-outline">
                    Voir toutes nos réalisations
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="5" y1="12" x2="19" y2="12"/>
                        <polyline points="12 5 19 12 12 19"/>
                    </svg>
                </a>
            </div>
        </div>
        <?php endif; ?>

        <!-- Services -->
        <div class="mobile-city-services scroll-fade">
            <h2 class="mobile-section-subtitle">Nos services</h2>
            <ul class="mobile-city-services-list">
                <li><a href="<?php echo home_url('/type_realisation/portails/'); ?>">Portails sur mesure</a></li>
                <li><a href="<?php echo home_url('/type_realisation/garde-corps/'); ?>">Garde-corps</a></li>
                <li><a href="<?php echo home_url('/type_realisation/escaliers/'); ?>">Escaliers métalliques</a></li>
                <li><a href="<?php echo home_url('/type_realisation/pergolas/'); ?>">Pergolas</a></li>
                <li><a href="<?php echo home_url('/type_realisation/ferronnerie-dart/'); ?>">Ferronnerie d'art</a></li>
            </ul>
        </div>

        <!-- Carte Google Maps -->
        <div class="mobile-city-map scroll-fade">
            <h2 class="mobile-section-subtitle">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>
                Localisation
            </h2>
            <div class="mobile-city-map-container">
                <iframe 
                    src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&q=<?php echo urlencode($city_display . ', ' . $department . ', France'); ?>&zoom=12"
                    width="100%" 
                    height="250" 
                    style="border:0; border-radius: 12px;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
            <p class="mobile-city-map-info">
                Nous intervenons à <strong><?php echo esc_html($city_display); ?></strong> et dans un rayon de 50 km autour de notre atelier à Peschadoires.
            </p>
        </div>

        <!-- Contact final -->
        <div class="mobile-city-contact-final scroll-fade">
            <h2 class="mobile-section-subtitle">Contactez-nous</h2>
            <div class="mobile-city-contact-buttons">
                <a href="tel:0673333532" class="mobile-btn-primary mobile-btn-full">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                    Appeler maintenant
                </a>
                <a href="<?php echo home_url('/contact/'); ?>" class="mobile-btn-secondary mobile-btn-full">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    Demander un devis gratuit
                </a>
            </div>
        </div>

    </div>
</article>

<?php endwhile; ?>
