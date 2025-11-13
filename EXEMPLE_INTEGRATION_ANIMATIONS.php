<?php
/**
 * EXEMPLE D'INTÉGRATION DES ANIMATIONS MOBILE
 * AL Métallerie - Template mobile-onepage.php
 * 
 * Ce fichier montre comment ajouter les classes d'animation
 * aux différents éléments de la page mobile one-page.
 * 
 * Copiez-collez les exemples dans votre template mobile-onepage.php
 */
?>

<!-- ============================================
     SECTION HERO
     ============================================ -->
<section id="hero" class="mobile-section mobile-hero scroll-fade">
    <div class="mobile-hero-content">
        <h1 class="mobile-hero-title scroll-fade scroll-delay-1">
            <?php esc_html_e('AL Métallerie', 'almetal'); ?>
        </h1>
        <p class="mobile-hero-subtitle scroll-fade scroll-delay-2">
            <?php esc_html_e('Votre expert en métallerie à Clermont-Ferrand', 'almetal'); ?>
        </p>
        <a href="#contact" class="mobile-btn-cta scroll-zoom scroll-delay-3">
            <?php esc_html_e('Demander un devis', 'almetal'); ?>
        </a>
    </div>
</section>

<!-- ============================================
     SECTION SERVICES
     ============================================ -->
<section id="services" class="mobile-section mobile-services scroll-fade">
    <div class="mobile-container">
        <h2 class="mobile-section-title scroll-fade">
            <?php esc_html_e('Nos Services', 'almetal'); ?>
        </h2>
        
        <div class="mobile-services-grid">
            <?php
            $services = array(
                array('title' => 'Portails', 'icon' => 'gate'),
                array('title' => 'Garde-corps', 'icon' => 'railing'),
                array('title' => 'Escaliers', 'icon' => 'stairs'),
            );
            
            foreach ($services as $index => $service) :
                $delay = ($index % 3) + 1;
            ?>
                <div class="mobile-service-card scroll-slide-up scroll-delay-<?php echo $delay; ?>">
                    <div class="mobile-service-icon scroll-zoom">
                        <!-- Icône SVG -->
                    </div>
                    <h3><?php echo esc_html($service['title']); ?></h3>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================
     SECTION RÉALISATIONS
     ============================================ -->
<section id="realisations" class="mobile-section mobile-realisations scroll-fade">
    <div class="mobile-container">
        <h2 class="mobile-section-title scroll-fade">
            <?php esc_html_e('Nos Réalisations', 'almetal'); ?>
        </h2>
        
        <!-- Filtres -->
        <div class="mobile-filter-dropdown scroll-fade scroll-delay-1">
            <select id="mobile-category-filter">
                <option value="all"><?php esc_html_e('Toutes les catégories', 'almetal'); ?></option>
                <!-- ... -->
            </select>
        </div>
        
        <!-- Grille de réalisations -->
        <div class="mobile-realisations-grid">
            <?php
            $realisations = new WP_Query(array(
                'post_type' => 'realisation',
                'posts_per_page' => 6,
            ));
            
            if ($realisations->have_posts()) :
                $index = 0;
                while ($realisations->have_posts()) : $realisations->the_post();
                    $delay = ($index % 3) + 1;
            ?>
                <article class="realisation-card scroll-slide-up scroll-delay-<?php echo $delay; ?>" data-category="<?php /* catégorie */ ?>">
                    <a href="<?php the_permalink(); ?>">
                        <div class="realisation-card-image scroll-zoom-in">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('medium'); ?>
                            <?php endif; ?>
                        </div>
                        <div class="realisation-card-content">
                            <h3><?php the_title(); ?></h3>
                            
                            <!-- Badges de catégories -->
                            <?php
                            $categories = get_the_terms(get_the_ID(), 'categorie_realisation');
                            if ($categories) :
                                foreach ($categories as $cat_index => $category) :
                                    $badge_delay = $delay + $cat_index + 1;
                            ?>
                                <span class="badge scroll-zoom scroll-delay-<?php echo $badge_delay; ?>">
                                    <?php echo esc_html($category->name); ?>
                                </span>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </div>
                    </a>
                </article>
            <?php
                    $index++;
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
        
        <!-- Bouton "Voir plus" -->
        <div class="mobile-cta-center scroll-zoom">
            <a href="<?php echo esc_url(get_post_type_archive_link('realisation')); ?>" class="mobile-btn-cta">
                <?php esc_html_e('Voir toutes les réalisations', 'almetal'); ?>
            </a>
        </div>
    </div>
</section>

<!-- ============================================
     SECTION ACTUALITÉS
     ============================================ -->
<section id="actualites" class="mobile-section mobile-actualites scroll-fade">
    <div class="mobile-container">
        <h2 class="mobile-section-title scroll-fade">
            <?php esc_html_e('Actualités', 'almetal'); ?>
        </h2>
        
        <div class="mobile-actualites-grid">
            <?php
            $actualites = new WP_Query(array(
                'post_type' => 'post',
                'posts_per_page' => 3,
            ));
            
            if ($actualites->have_posts()) :
                $index = 0;
                while ($actualites->have_posts()) : $actualites->the_post();
                    $delay = ($index % 3) + 1;
            ?>
                <article class="actualite-card scroll-slide-left scroll-delay-<?php echo $delay; ?>">
                    <a href="<?php the_permalink(); ?>">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="actualite-card-image scroll-zoom-in">
                                <?php the_post_thumbnail('medium'); ?>
                            </div>
                        <?php endif; ?>
                        <div class="actualite-card-content">
                            <time class="actualite-date scroll-fade">
                                <?php echo get_the_date(); ?>
                            </time>
                            <h3><?php the_title(); ?></h3>
                            <p><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                        </div>
                    </a>
                </article>
            <?php
                    $index++;
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
</section>

<!-- ============================================
     SECTION CONTACT (Informations uniquement)
     ============================================ -->
<section id="contact" class="mobile-section mobile-contact scroll-fade">
    <div class="mobile-contact-container">
        <h2 class="mobile-section-title scroll-fade">
            <?php esc_html_e('Contactez-nous', 'almetal'); ?>
        </h2>
        
        <div class="mobile-contact-info-grid">
            <!-- Téléphone -->
            <a href="tel:0473940000" class="mobile-contact-info-card scroll-fade scroll-delay-1">
                <div class="mobile-contact-info-icon scroll-zoom">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                </div>
                <div class="mobile-contact-info-content">
                    <h3><?php esc_html_e('Téléphone', 'almetal'); ?></h3>
                    <p>04 73 94 00 00</p>
                </div>
            </a>

            <!-- Email -->
            <a href="mailto:contact@al-metallerie.fr" class="mobile-contact-info-card scroll-fade scroll-delay-2">
                <div class="mobile-contact-info-icon scroll-zoom">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                </div>
                <div class="mobile-contact-info-content">
                    <h3><?php esc_html_e('Email', 'almetal'); ?></h3>
                    <p>contact@al-metallerie.fr</p>
                </div>
            </a>

            <!-- Adresse -->
            <a href="https://www.google.com/maps/..." target="_blank" rel="noopener" class="mobile-contact-info-card scroll-fade scroll-delay-3">
                <div class="mobile-contact-info-icon scroll-zoom">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                </div>
                <div class="mobile-contact-info-content">
                    <h3><?php esc_html_e('Adresse', 'almetal'); ?></h3>
                    <p>14 Rte de Maringues, 63920 Peschadoires</p>
                </div>
            </a>
        </div>

        <!-- Bouton vers page contact -->
        <div class="mobile-contact-cta scroll-zoom scroll-delay-4">
            <a href="<?php echo esc_url(home_url('/contact')); ?>" class="mobile-btn-cta">
                <?php esc_html_e('Formulaire de contact', 'almetal'); ?>
            </a>
        </div>
    </div>
</section>

<!-- ============================================
     BOUTON SCROLL TO TOP
     ============================================ -->
<button id="scroll-to-top" class="scroll-to-top scroll-zoom" aria-label="<?php esc_attr_e('Retour en haut', 'almetal'); ?>">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <line x1="12" y1="19" x2="12" y2="5"></line>
        <polyline points="5 12 12 5 19 12"></polyline>
    </svg>
</button>

<?php
/**
 * NOTES D'INTÉGRATION :
 * 
 * 1. Classes principales à ajouter :
 *    - Sections : .scroll-fade
 *    - Cards : .scroll-slide-up + .scroll-delay-X
 *    - Boutons : .scroll-zoom
 *    - Images : .scroll-zoom-in
 *    - Icônes : .scroll-zoom
 * 
 * 2. Délais en cascade :
 *    - Utilisez scroll-delay-1 à scroll-delay-5
 *    - Calculez dynamiquement : scroll-delay-<?php echo ($index % 3) + 1; ?>
 * 
 * 3. Combinaisons possibles :
 *    - .scroll-fade .scroll-delay-1 : Fade avec délai
 *    - .scroll-slide-up .scroll-delay-2 : Slide avec délai
 *    - .scroll-zoom : Zoom simple
 * 
 * 4. Performances :
 *    - Ne pas abuser des animations (max 3-4 par viewport)
 *    - Privilégier les animations simples (fade, slide)
 *    - Éviter les animations sur les gros éléments
 * 
 * 5. Accessibilité :
 *    - Le système désactive automatiquement les animations si prefers-reduced-motion
 *    - Pas besoin de code supplémentaire
 */
?>
