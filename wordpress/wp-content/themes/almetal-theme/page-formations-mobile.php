<?php
/**
 * Template Name: Formations Mobile
 * Template pour la page Formations - VERSION MOBILE
 * 
 * Affiche toutes les formations disponibles
 * Header avec bouton retour vers l'accueil
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

get_header();
?>

<!-- Header Mobile avec bouton RETOUR -->
<?php get_template_part('template-parts/header', 'mobile'); ?>

<main class="mobile-page-formations">
    <div class="mobile-page-container">
        
        <!-- Titre de la page -->
        <div class="mobile-page-header">
            <h1 class="mobile-page-title">
                <?php esc_html_e('Nos Formations', 'almetal'); ?>
            </h1>
            <p class="mobile-page-subtitle">
                <?php esc_html_e('Développez vos compétences en métallerie avec nos formations professionnelles', 'almetal'); ?>
            </p>
        </div>

        <!-- Grille de formations -->
        <div class="mobile-formations-list">
            
            <!-- Formation Professionnels -->
            <article class="mobile-formation-card-full">
                <div class="mobile-formation-card-inner">
                    <!-- Icône -->
                    <div class="mobile-formation-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>

                    <!-- Contenu -->
                    <div class="mobile-formation-content">
                        <h2 class="mobile-formation-title-full">
                            <?php esc_html_e('FORMATIONS PROFESSIONNELS', 'almetal'); ?>
                        </h2>
                        
                        <p class="mobile-formation-description-full">
                            <?php esc_html_e('Formations spécialisées pour les professionnels du métal : techniques avancées, perfectionnement et certification. Nos formateurs experts vous accompagnent avec du matériel professionnel de pointe.', 'almetal'); ?>
                        </p>

                        <!-- Points clés détaillés -->
                        <div class="mobile-formation-details">
                            <h3 class="mobile-formation-subtitle"><?php esc_html_e('Programme', 'almetal'); ?></h3>
                            <ul class="mobile-formation-features-full">
                                <li>
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <span><?php esc_html_e('Techniques de soudure avancées (TIG, MIG, ARC)', 'almetal'); ?></span>
                                </li>
                                <li>
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <span><?php esc_html_e('Fabrication de structures métalliques complexes', 'almetal'); ?></span>
                                </li>
                                <li>
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <span><?php esc_html_e('Lecture de plans et traçage professionnel', 'almetal'); ?></span>
                                </li>
                                <li>
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <span><?php esc_html_e('Certification professionnelle reconnue', 'almetal'); ?></span>
                                </li>
                            </ul>
                        </div>

                        <!-- Bouton -->
                        <a href="<?php echo esc_url(home_url('/formations-professionnels')); ?>" class="mobile-btn-cta mobile-btn-cta--large">
                            <?php esc_html_e('En savoir plus', 'almetal'); ?>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                    </div>
                </div>
            </article>

            <!-- Formation Particuliers -->
            <article class="mobile-formation-card-full">
                <div class="mobile-formation-card-inner">
                    <!-- Icône -->
                    <div class="mobile-formation-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    </div>

                    <!-- Contenu -->
                    <div class="mobile-formation-content">
                        <h2 class="mobile-formation-title-full">
                            <?php esc_html_e('FORMATIONS PARTICULIERS', 'almetal'); ?>
                        </h2>
                        
                        <p class="mobile-formation-description-full">
                            <?php esc_html_e('Initiations et ateliers pour les passionnés de métallerie : découverte des techniques, création de projets personnels en toute sécurité. Ambiance conviviale en petits groupes.', 'almetal'); ?>
                        </p>

                        <!-- Points clés détaillés -->
                        <div class="mobile-formation-details">
                            <h3 class="mobile-formation-subtitle"><?php esc_html_e('Programme', 'almetal'); ?></h3>
                            <ul class="mobile-formation-features-full">
                                <li>
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <span><?php esc_html_e('Initiation à la soudure et au travail du métal', 'almetal'); ?></span>
                                </li>
                                <li>
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <span><?php esc_html_e('Création d\'objets décoratifs et utilitaires', 'almetal'); ?></span>
                                </li>
                                <li>
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <span><?php esc_html_e('Ateliers découverte en petits groupes (max 6 personnes)', 'almetal'); ?></span>
                                </li>
                                <li>
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <span><?php esc_html_e('Accompagnement de vos projets personnels', 'almetal'); ?></span>
                                </li>
                            </ul>
                        </div>

                        <!-- Bouton -->
                        <a href="<?php echo esc_url(home_url('/formations-particuliers')); ?>" class="mobile-btn-cta mobile-btn-cta--large">
                            <?php esc_html_e('En savoir plus', 'almetal'); ?>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                    </div>
                </div>
            </article>

        </div>

    </div>
</main>

<?php get_footer(); ?>
