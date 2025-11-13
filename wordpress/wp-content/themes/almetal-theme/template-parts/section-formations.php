<?php
/**
 * Section Formations - Page d'accueil
 * Affiche 2 cards : Professionnels et Particuliers
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */
?>

<section class="formations-section" id="formations">
    <div class="formations-container">
        <!-- Tag -->
        <div class="formations-tag">
            <span><?php esc_html_e('Nos Formations', 'almetal'); ?></span>
        </div>

        <!-- Titre -->
        <h2 class="formations-title">
            <?php esc_html_e('DÉVELOPPEZ VOS COMPÉTENCES', 'almetal'); ?>
        </h2>

        <!-- Description -->
        <p class="formations-subtitle">
            <?php esc_html_e('Formations professionnelles en métallerie et soudure adaptées à vos besoins', 'almetal'); ?>
        </p>

        <!-- Grille de 2 cartes -->
        <div class="formations-grid">
            
            <!-- Card Professionnels -->
            <article class="formation-card">
                <div class="formation-card-inner">
                    <!-- Icône -->
                    <div class="formation-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>

                    <!-- Contenu -->
                    <div class="formation-content">
                        <h3 class="formation-title">
                            <?php esc_html_e('PROFESSIONNELS', 'almetal'); ?>
                        </h3>
                        
                        <p class="formation-description">
                            <?php esc_html_e('Formations spécialisées pour les professionnels du métal : techniques avancées de soudure, fabrication de structures métalliques, et perfectionnement aux normes industrielles.', 'almetal'); ?>
                        </p>

                        <!-- Points clés -->
                        <ul class="formation-features">
                            <li>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span><?php esc_html_e('Certification professionnelle', 'almetal'); ?></span>
                            </li>
                            <li>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span><?php esc_html_e('Formateurs experts', 'almetal'); ?></span>
                            </li>
                            <li>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span><?php esc_html_e('Équipement professionnel', 'almetal'); ?></span>
                            </li>
                        </ul>

                        <!-- Bouton -->
                        <a href="<?php echo esc_url(home_url('/formations-professionnels')); ?>" class="btn-view-project">
                            <span class="circle" aria-hidden="true">
                                <svg class="icon arrow" width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 6H17M17 6L12 1M17 6L12 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <span class="button-text"><?php _e('En savoir +', 'almetal'); ?></span>
                        </a>
                    </div>
                </div>
            </article>

            <!-- Card Particuliers -->
            <article class="formation-card">
                <div class="formation-card-inner">
                    <!-- Icône -->
                    <div class="formation-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    </div>

                    <!-- Contenu -->
                    <div class="formation-content">
                        <h3 class="formation-title">
                            <?php esc_html_e('PARTICULIERS', 'almetal'); ?>
                        </h3>
                        
                        <p class="formation-description">
                            <?php esc_html_e('Initiations et ateliers pour les passionnés : découverte de la métallerie, création d\'objets décoratifs, et apprentissage des techniques de base en toute sécurité.', 'almetal'); ?>
                        </p>

                        <!-- Points clés -->
                        <ul class="formation-features">
                            <li>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span><?php esc_html_e('Ateliers découverte', 'almetal'); ?></span>
                            </li>
                            <li>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span><?php esc_html_e('Petits groupes', 'almetal'); ?></span>
                            </li>
                            <li>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span><?php esc_html_e('Projets personnalisés', 'almetal'); ?></span>
                            </li>
                        </ul>

                        <!-- Bouton -->
                        <a href="<?php echo esc_url(home_url('/formations-particuliers')); ?>" class="btn-view-project">
                            <span class="circle" aria-hidden="true">
                                <svg class="icon arrow" width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 6H17M17 6L12 1M17 6L12 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <span class="button-text"><?php _e('En savoir +', 'almetal'); ?></span>
                        </a>
                    </div>
                </div>
            </article>

        </div>
    </div>
</section>
