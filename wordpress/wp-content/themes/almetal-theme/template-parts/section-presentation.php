<?php
/**
 * Section Présentation - Page d'accueil
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */
?>

<section class="presentation-section" id="presentation">
    <div class="presentation-container">
        <!-- Bande orange décorative -->
        <div class="presentation-orange-bar" aria-hidden="true"></div>
        
        <!-- Bloc images -->
        <div class="presentation-images">
            <div class="presentation-image-wrapper presentation-image-top">
                <picture>
                    <source srcset="<?php echo esc_url(get_template_directory_uri() . '/assets/images/gallery/pexels-kelly-optimized.webp'); ?>"
                            sizes="(max-width: 768px) 300px, 400px"
                            type="image/webp">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/gallery/pexels-kelly-2950108 1.webp'); ?>" 
                         alt="Soudeur professionnel AL-Metallerie Soudure en action à Thiers"
                         class="presentation-img"
                         fetchpriority="high"
                         decoding="async"
                         width="400"
                         height="498">
                </picture>
            </div>
            <div class="presentation-image-wrapper presentation-image-bottom">
                <picture>
                    <source srcset="<?php echo esc_url(get_template_directory_uri() . '/assets/images/gallery/pexels-rik-optimized.webp'); ?>"
                            sizes="(max-width: 768px) 250px, 400px"
                            type="image/webp">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/gallery/pexels-rik-schots-11624248 2.webp'); ?>" 
                         alt="Travaux de métallerie de précision à Thiers, Puy-de-Dôme"
                         class="presentation-img"
                         loading="lazy"
                         width="400"
                         height="267">
                </picture>
            </div>
        </div>

        <!-- Bloc contenu -->
        <div class="presentation-content">
            <!-- Tag de bienvenue -->
            <div class="presentation-tag">
                <span><?php esc_html_e('Bienvenu chez AL-Metallerie Soudure', 'almetal'); ?></span>
            </div>

            <!-- H1 unique pour le SEO - Titre principal de la page -->
            <h1 class="presentation-h1">
                Métallier Serrurier à Thiers
            </h1>

            <!-- Sous-titre -->
            <h2 class="presentation-title">
                <?php esc_html_e('PROFESSIONNEL', 'almetal'); ?><br>
                <?php esc_html_e('ET CRÉATIF', 'almetal'); ?>
            </h2>

            <!-- Description -->
            <div class="presentation-description">
                <p>
                    <strong>AL-Metallerie Soudure</strong>, votre <em>expert en métallerie</em> à <strong>Thiers</strong> (Puy-de-Dôme), accompagne <em>entreprises et particuliers</em> depuis de nombreuses années. Spécialisés dans la <strong>fabrication sur mesure</strong>, la <em>rénovation</em> et l'<em>installation de structures métalliques</em>, nous mettons notre <strong>savoir-faire</strong> au service de vos projets les plus exigeants. De la <em>conception</em> à la <em>réalisation</em>, notre équipe qualifiée garantit des <strong>travaux de qualité supérieure</strong>, allégeant coûts et délais. Nous proposons également des <strong>formations professionnelles</strong> pour transmettre notre expertise. Faites confiance à <strong>AL-Metallerie Soudure</strong> pour donner vie à vos idées avec <em>créativité</em> et <em>professionnalisme</em>.
                </p>
            </div>

            <!-- Points de validation avec icônes personnalisées -->
            <ul class="presentation-features">
                <!-- Ouvert 6j/7 - Icône horloge/calendrier -->
                <li class="presentation-feature-item">
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
                <li class="presentation-feature-item">
                    <div class="presentation-feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                        </svg>
                    </div>
                    <span class="presentation-feature-text"><?php esc_html_e('Devis rapide', 'almetal'); ?></span>
                </li>
                
                <!-- Respect des délais - Icône check avec horloge -->
                <li class="presentation-feature-item">
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
                <li class="presentation-feature-item">
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
