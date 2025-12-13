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

// Nettoyer le nom de la ville pour l'affichage (gérer les anciens et nouveaux titres)
$city_display = get_the_title();
$city_display = str_replace('Métallier Serrurier à ', '', $city_display);
$city_display = str_replace('Métallier Ferronnier à ', '', $city_display);
if (empty($city_display) || $city_display === get_the_title()) {
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

        <!-- Image hero (background aléatoire comme desktop) -->
        <?php
        $mobile_city_hero_bg = function_exists('almetal_get_city_hero_background_url')
            ? almetal_get_city_hero_background_url(get_the_ID())
            : null;
        ?>
        <?php if (!empty($mobile_city_hero_bg)) : ?>
            <div class="mobile-single-hero scroll-fade scroll-delay-2" style="background-image: url('<?php echo esc_url($mobile_city_hero_bg); ?>'); background-size: cover; background-position: center; background-repeat: no-repeat; aspect-ratio: 16/9;">
                <div style="width:100%;height:100%;background:linear-gradient(to bottom, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.35) 100%);"></div>
            </div>
        <?php endif; ?>

        <!-- Description -->
        <div class="mobile-single-excerpt scroll-fade scroll-delay-3">
            <p><?php echo get_the_excerpt() ?: "Artisan métallier serrurier intervenant à {$city_display} et ses environs. Portails, garde-corps, escaliers sur mesure."; ?></p>
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

        <!-- Services - Liste dynamique des types de réalisations -->
        <div class="mobile-city-services scroll-fade">
            <h2 class="mobile-section-subtitle">Nos services</h2>
            <ul class="mobile-city-services-list">
                <?php
                // Récupérer dynamiquement tous les types de réalisations
                $types_realisation = get_terms(array(
                    'taxonomy' => 'type_realisation',
                    'hide_empty' => false,
                    'orderby' => 'name',
                    'order' => 'ASC'
                ));
                
                // Icônes SVG par type de réalisation
                $icons = array(
                    'portails' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="18" rx="1"/><rect x="14" y="3" width="7" height="18" rx="1"/><line x1="6.5" y1="10" x2="6.5" y2="14"/><line x1="17.5" y1="10" x2="17.5" y2="14"/></svg>',
                    'garde-corps' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="6" x2="3" y2="20"/><line x1="21" y1="6" x2="21" y2="20"/><line x1="8" y1="6" x2="8" y2="20"/><line x1="16" y1="6" x2="16" y2="20"/><line x1="12" y1="6" x2="12" y2="20"/></svg>',
                    'escaliers' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 22H2V12h6v-4h6V4h8v18z"/></svg>',
                    'pergolas' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="8" x2="21" y2="8"/><line x1="3" y1="8" x2="3" y2="21"/><line x1="21" y1="8" x2="21" y2="21"/><line x1="6" y1="4" x2="6" y2="8"/><line x1="12" y1="4" x2="12" y2="8"/><line x1="18" y1="4" x2="18" y2="8"/></svg>',
                    'ferronnerie-dart' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/></svg>',
                    'grilles' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/></svg>',
                    'verrieres' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="12" y1="3" x2="12" y2="21"/></svg>',
                    'mobilier-metallique' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="6" rx="1"/><line x1="6" y1="10" x2="6" y2="20"/><line x1="18" y1="10" x2="18" y2="20"/><line x1="4" y1="14" x2="20" y2="14"/></svg>',
                    'serrurerie' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><circle cx="12" cy="16" r="1"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>',
                    'industrie' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 20h20"/><path d="M5 20V8l5 4V8l5 4V4h5v16"/><circle cx="7" cy="14" r="1"/><circle cx="12" cy="14" r="1"/><circle cx="17" cy="10" r="1"/></svg>',
                    'vehicules' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 17h14v-5H5z"/><path d="M19 12l-2-5H7l-2 5"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/><line x1="5" y1="12" x2="19" y2="12"/></svg>',
                    'autres' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>',
                );
                
                // Icône par défaut
                $default_icon = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>';
                
                if (!empty($types_realisation) && !is_wp_error($types_realisation)) :
                    foreach ($types_realisation as $type) :
                        $slug = $type->slug;
                        $icon = isset($icons[$slug]) ? $icons[$slug] : $default_icon;
                ?>
                <li>
                    <a href="<?php echo esc_url(get_term_link($type)); ?>">
                        <?php echo $icon; ?>
                        <span><?php echo esc_html($type->name); ?></span>
                    </a>
                </li>
                <?php 
                    endforeach;
                endif;
                ?>
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
