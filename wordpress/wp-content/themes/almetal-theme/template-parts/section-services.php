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
            
            <!-- Carte 1 : Professionnels -->
            <article class="service-card realisation-card">
                <div class="realisation-image-wrapper">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/gallery/pexels-tima-miroshnichenko-5846282 1.png'); ?>" 
                         alt="Métallerie pour professionnels à Thiers - AL Metallerie"
                         class="realisation-image"
                         loading="lazy">
                </div>
                <div class="realisation-content">
                    <h3 class="realisation-title">
                        <a href="#contact"><?php esc_html_e('PROFESSIONNELS', 'almetal'); ?></a>
                    </h3>
                    <p class="service-description">
                        <?php esc_html_e('Structures métalliques sur mesure pour entreprises, industries et collectivités à Thiers et Puy-de-Dôme. Charpentes, passerelles, garde-corps industriels et aménagements professionnels.', 'almetal'); ?>
                    </p>
                    <a href="#contact" class="btn-view-project">
                        <span class="circle" aria-hidden="true">
                            <svg class="icon arrow" width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 6H17M17 6L12 1M17 6L12 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span class="button-text"><?php _e('En savoir +', 'almetal'); ?></span>
                    </a>
                </div>
            </article>

            <!-- Carte 2 : Particuliers -->
            <article class="service-card realisation-card">
                <div class="realisation-image-wrapper">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/gallery/pexels-rik-schots-11624248 2.png'); ?>" 
                         alt="Métallerie pour particuliers à Thiers - Portails, garde-corps"
                         class="realisation-image"
                         loading="lazy">
                </div>
                <div class="realisation-content">
                    <h3 class="realisation-title">
                        <a href="#contact"><?php esc_html_e('PARTICULIERS', 'almetal'); ?></a>
                    </h3>
                    <p class="service-description">
                        <?php esc_html_e('Portails, portillons, garde-corps, pergolas et mobilier métallique personnalisé pour votre maison à Thiers. Création, installation et rénovation de ferronnerie d\'art et métallerie décorative.', 'almetal'); ?>
                    </p>
                    <a href="#contact" class="btn-view-project">
                        <span class="circle" aria-hidden="true">
                            <svg class="icon arrow" width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 6H17M17 6L12 1M17 6L12 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span class="button-text"><?php _e('En savoir +', 'almetal'); ?></span>
                    </a>
                </div>
            </article>

            <!-- Carte 3 : Formation -->
            <article class="service-card realisation-card">
                <div class="realisation-image-wrapper">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/gallery/pexels-kelly-2950108 1.png'); ?>" 
                         alt="Formation métallerie et soudure à Thiers - AL Metallerie"
                         class="realisation-image"
                         loading="lazy">
                </div>
                <div class="realisation-content">
                    <h3 class="realisation-title">
                        <a href="#contact"><?php esc_html_e('FORMATION', 'almetal'); ?></a>
                    </h3>
                    <p class="service-description">
                        <?php esc_html_e('Formations professionnelles en métallerie et soudure à Thiers. Initiation et perfectionnement pour particuliers, salariés et demandeurs d\'emploi. Transmission de savoir-faire et techniques de métallier.', 'almetal'); ?>
                    </p>
                    <a href="#contact" class="btn-view-project">
                        <span class="circle" aria-hidden="true">
                            <svg class="icon arrow" width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 6H17M17 6L12 1M17 6L12 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span class="button-text"><?php _e('En savoir +', 'almetal'); ?></span>
                    </a>
                </div>
            </article>

        </div>
    </div>
</section>
