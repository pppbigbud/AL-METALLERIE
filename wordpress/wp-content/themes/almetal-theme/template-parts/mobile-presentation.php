<?php
/**
 * Section Présentation - Version Mobile
 * Le fond blanc et le SVG sont visibles immédiatement
 * Les textes et icônes apparaissent au scroll
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */
?>

<section class="presentation-section" id="presentation-mobile">
    <div class="presentation-container">
        <!-- Bande orange décorative - visible immédiatement -->
        <div class="presentation-orange-bar" aria-hidden="true"></div>
        
        <!-- Bloc images - animation au scroll -->
        <div class="presentation-images scroll-fade scroll-delay-1">
            <div class="presentation-image-wrapper presentation-image-top">
                <picture>
                    <source srcset="<?php echo esc_url(get_template_directory_uri() . '/assets/images/gallery/pexels-kelly-2950108 1.webp'); ?>" type="image/webp">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/gallery/pexels-kelly-2950108 1.png'); ?>" 
                         alt="Soudeur professionnel AL-Metallerie Soudure en action à Thiers"
                         class="presentation-img"
                         loading="lazy"
                         width="300"
                         height="400">
                </picture>
            </div>
            <div class="presentation-image-wrapper presentation-image-bottom">
                <picture>
                    <source srcset="<?php echo esc_url(get_template_directory_uri() . '/assets/images/gallery/pexels-rik-schots-11624248 2.webp'); ?>" type="image/webp">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/gallery/pexels-rik-schots-11624248 2.png'); ?>" 
                         alt="Travaux de métallerie de précision à Thiers, Puy-de-Dôme"
                         class="presentation-img"
                         loading="lazy"
                         width="300"
                         height="400">
                </picture>
            </div>
        </div>

        <!-- Bloc contenu -->
        <div class="presentation-content">
            <!-- Tag de bienvenue - animation au scroll -->
            <div class="presentation-tag scroll-fade scroll-delay-1">
                <span><?php esc_html_e('Bienvenu chez AL-Metallerie Soudure', 'almetal'); ?></span>
            </div>

            <!-- H1 unique pour le SEO - Titre principal de la page -->
            <h1 class="presentation-h1 scroll-fade scroll-delay-2">
                <?php esc_html_e('Métallier Ferronnier à Thiers', 'almetal'); ?>
            </h1>

            <!-- Sous-titre - animation au scroll -->
            <h2 class="presentation-title scroll-fade scroll-delay-2">
                <?php esc_html_e('PROFESSIONNEL', 'almetal'); ?><br>
                <?php esc_html_e('ET CRÉATIF', 'almetal'); ?>
            </h2>

            <!-- Description - animation au scroll -->
            <div class="presentation-description scroll-fade scroll-delay-3">
                <p>
                    <?php esc_html_e('AL-Metallerie Soudure, votre expert en métallerie à Thiers (Puy-de-Dôme), accompagne entreprises et particuliers depuis de nombreuses années. Spécialisés dans la fabrication sur mesure, la rénovation et l\'installation de structures métalliques, nous mettons notre savoir-faire au service de vos projets les plus exigeants. De la conception à la réalisation, notre équipe qualifiée garantit des travaux de qualité supérieure, allégeant coûts et délais. Nous proposons également des formations professionnelles pour transmettre notre expertise. Faites confiance à AL-Metallerie Soudure pour donner vie à vos idées avec créativité et professionnalisme.', 'almetal'); ?>
                </p>
            </div>

            <!-- Points de validation avec icônes personnalisées - animation au scroll -->
            <ul class="presentation-features">
                <!-- Ouvert 6j/7 - Icône horloge/calendrier -->
                <li class="presentation-feature-item scroll-slide-up scroll-delay-1">
                    <div class="presentation-feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                            <path d="M8 14h.01"></path>
                            <path d="M12 14h.01"></path>
                            <path d="M16 14h.01"></path>
                            <path d="M8 18h.01"></path>
                            <path d="M12 18h.01"></path>
                        </svg>
                    </div>
                    <span class="presentation-feature-text"><?php esc_html_e('Ouvert 6j/7', 'almetal'); ?></span>
                </li>
                
                <!-- Devis rapide - Icône éclair/rapidité -->
                <li class="presentation-feature-item scroll-slide-up scroll-delay-2">
                    <div class="presentation-feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                        </svg>
                    </div>
                    <span class="presentation-feature-text"><?php esc_html_e('Devis rapide', 'almetal'); ?></span>
                </li>
                
                <!-- Respect des délais - Icône check avec horloge -->
                <li class="presentation-feature-item scroll-slide-up scroll-delay-3">
                    <div class="presentation-feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                            <path d="M16.24 7.76l2.12-2.12"></path>
                        </svg>
                    </div>
                    <span class="presentation-feature-text"><?php esc_html_e('Respect des délais', 'almetal'); ?></span>
                </li>
                
                <!-- Flexibilité - Icône ajustement/réglage -->
                <li class="presentation-feature-item scroll-slide-up scroll-delay-4">
                    <div class="presentation-feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M12 1v6m0 6v6"></path>
                            <path d="m4.93 4.93 4.24 4.24m5.66 5.66 4.24 4.24"></path>
                            <path d="M1 12h6m6 0h6"></path>
                            <path d="m4.93 19.07 4.24-4.24m5.66-5.66 4.24-4.24"></path>
                        </svg>
                    </div>
                    <span class="presentation-feature-text"><?php esc_html_e('Flexibilité', 'almetal'); ?></span>
                </li>
            </ul>
        </div>
    </div>
</section>
