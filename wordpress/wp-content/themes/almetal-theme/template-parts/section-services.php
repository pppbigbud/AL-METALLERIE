<?php
/**
 * Section Services/Offres - Page d'accueil
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */
?>

<section class="services-section" id="services">
    <!-- Image de fond -->
    <div class="services-background"></div>
    
    <div class="services-container">

        <!-- Titre -->
        <h2 class="services-title">
            <?php esc_html_e('MES DIFFÉRENTES OFFRES DE FORMATIONS', 'almetal'); ?>
        </h2>

        <!-- Grille de cartes -->
        <div class="services-grid">
            
            <!-- Carte 1 : Formation (page principale) -->
            <article class="service-card realisation-card">
                <div class="realisation-image-wrapper">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/gallery/pexels-kelly-2950108 1.png'); ?>" 
                         alt="Formation métallerie et soudure à Thiers - AL Metallerie"
                         class="realisation-image"
                         loading="lazy">
                </div>
                <div class="realisation-content">
                    <h3 class="realisation-title">
                        <a href="<?php echo esc_url(home_url('/formations/')); ?>"><?php esc_html_e('FORMATIONS', 'almetal'); ?></a>
                    </h3>
                    <p class="service-description">
                        <?php esc_html_e('Découvrez nos formations en métallerie et soudure à Thiers. Stages d\'initiation et perfectionnement pour tous niveaux.', 'almetal'); ?>
                    </p>
                    <a href="<?php echo esc_url(home_url('/formations/')); ?>" class="btn-view-project">
                        <span class="circle" aria-hidden="true">
                            <svg class="icon arrow" width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 6H17M17 6L12 1M17 6L12 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span class="button-text"><?php _e('Découvrir', 'almetal'); ?></span>
                    </a>
                </div>
            </article>

            <!-- Carte 2 : Particuliers -->
            <article class="service-card realisation-card">
                <div class="realisation-image-wrapper">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/gallery/pexels-rik-schots-11624248 2.png'); ?>" 
                         alt="Formation métallerie pour particuliers à Thiers"
                         class="realisation-image"
                         loading="lazy">
                </div>
                <div class="realisation-content">
                    <h3 class="realisation-title">
                        <a href="<?php echo esc_url(home_url('/formations-particuliers/')); ?>"><?php esc_html_e('PARTICULIERS', 'almetal'); ?></a>
                    </h3>
                    <p class="service-description">
                        <?php esc_html_e('Stages d\'initiation à la métallerie pour particuliers. Apprenez les bases de la forge, soudure et ferronnerie dans notre atelier.', 'almetal'); ?>
                    </p>
                    <a href="<?php echo esc_url(home_url('/formations-particuliers/')); ?>" class="btn-view-project">
                        <span class="circle" aria-hidden="true">
                            <svg class="icon arrow" width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 6H17M17 6L12 1M17 6L12 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span class="button-text"><?php _e('Découvrir', 'almetal'); ?></span>
                    </a>
                </div>
            </article>

            <!-- Carte 3 : Professionnels (désactivée - bientôt disponible) -->
            <article class="service-card realisation-card service-card--disabled">
                <div class="service-card__badge"><?php esc_html_e('Bientôt', 'almetal'); ?></div>
                <div class="realisation-image-wrapper">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/gallery/pexels-tima-miroshnichenko-5846282 1.png'); ?>" 
                         alt="Formation métallerie pour professionnels à Thiers"
                         class="realisation-image"
                         loading="lazy">
                </div>
                <div class="realisation-content">
                    <h3 class="realisation-title">
                        <span><?php esc_html_e('PROFESSIONNELS', 'almetal'); ?></span>
                    </h3>
                    <p class="service-description">
                        <?php esc_html_e('Formations professionnelles certifiantes en métallerie et soudure. Perfectionnement pour salariés et demandeurs d\'emploi.', 'almetal'); ?>
                    </p>
                    <div class="service-card__coming-soon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span><?php esc_html_e('Disponible courant 2026', 'almetal'); ?></span>
                    </div>
                </div>
            </article>

        </div>
    </div>
</section>
