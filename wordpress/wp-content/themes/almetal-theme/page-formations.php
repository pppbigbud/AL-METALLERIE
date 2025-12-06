<?php
/**
 * Template Name: Formations
 * Description: Page parente des formations en ferronnerie
 * Design inspiré des pages légales
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

get_header();
?>

<div class="archive-page formations-archive">
    <!-- Hero Section -->
    <div class="archive-hero">
        <div class="container">
            <h1 class="archive-title">
                <svg class="archive-icon" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
                <?php _e('Nos Formations en Ferronnerie', 'almetal'); ?>
            </h1>
            <p class="archive-subtitle">
                Découvrez nos <strong>formations professionnelles en ferronnerie d'art</strong> et métallerie. 
                Que vous soyez <strong>particulier passionné</strong> ou <strong>professionnel en reconversion</strong>, 
                nous vous accompagnons dans l'apprentissage des <em>techniques traditionnelles</em> et <em>modernes</em>.
            </p>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="archive-content">
        <div class="container">
            <!-- Grille des formations -->
            <div class="archive-grid formations-grid">
        <!-- Carte Particuliers -->
        <a href="<?php echo esc_url(home_url('/formations-particuliers')); ?>" class="formation-card card card-primary hover-lift">
            <div class="formation-card-icon mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white;">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <h2 class="formation-card-title" style="font-size: 2rem; color: var(--color-primary); margin-bottom: 1rem;">
                Formations Particuliers
            </h2>
            <p class="formation-card-description" style="font-size: 1.1rem; color: var(--color-text-light); line-height: 1.6; margin-bottom: 1.5rem;">
                Initiez-vous à la ferronnerie d'art lors de stages découverte ou perfectionnez vos compétences 
                avec nos ateliers pratiques. Idéal pour les passionnés et les créatifs.
            </p>
            <div class="formation-card-features mb-3">
                <div class="badge badge--small mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    Stages de 1 à 5 jours
                </div>
                <div class="badge badge--small mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    Petits groupes (max 6)
                </div>
                <div class="badge badge--small">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Tous niveaux acceptés
                </div>
            </div>
        </a>

        <!-- Carte Professionnels - Bientôt disponible -->
        <div class="formation-card formation-card--coming-soon card card-primary">
            <div class="formation-card-icon mb-2" style="opacity: 0.5;">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white;">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                </svg>
            </div>
            <h2 class="formation-card-title" style="font-size: 2rem; color: rgba(240, 139, 24, 0.5); margin-bottom: 1rem;">
                Formations Professionnels
            </h2>
            <p class="formation-card-description" style="font-size: 1.1rem; color: rgba(255, 255, 255, 0.5); line-height: 1.6; margin-bottom: 1.5rem;">
                Nos formations certifiantes et qualifiantes pour les professionnels du bâtiment arrivent prochainement. 
                Inscrivez-vous à notre newsletter pour être informé du lancement.
            </p>
            <div class="formation-card-features mb-3" style="opacity: 0.5;">
                <div class="badge badge--small mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                    </svg>
                    Certification professionnelle
                </div>
                <div class="badge badge--small mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 6v6l4 2"></path>
                    </svg>
                    Formation de 3 à 12 mois
                </div>
                <div class="badge badge--small">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                    Financement CPF/Pôle Emploi
                </div>
            </div>
            <div class="coming-soon-notice">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <span>Lancement prévu courant 2026</span>
            </div>
        </div>
            </div>

            <!-- Section avantages -->
            <div class="formations-advantages">
                <h2><?php _e('Pourquoi choisir nos formations ?', 'almetal'); ?></h2>
                <div class="formations-advantages-grid">
                    <div class="card">
                        <div class="card-item-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 20h9"></path>
                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                            </svg>
                        </div>
                        <h3><?php _e('Atelier équipé', 'almetal'); ?></h3>
                        <p><?php _e('Accès à un atelier professionnel avec tout le matériel nécessaire : forge, enclume, outils de découpe et de soudure.', 'almetal'); ?></p>
                    </div>

                    <div class="card">
                        <div class="card-item-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <h3><?php _e('Formateurs experts', 'almetal'); ?></h3>
                        <p><?php _e('Apprenez auprès d\'artisans ferronniers expérimentés, passionnés par la transmission de leur savoir-faire.', 'almetal'); ?></p>
                    </div>

                    <div class="card">
                        <div class="card-item-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                            </svg>
                        </div>
                        <h3><?php _e('Pratique intensive', 'almetal'); ?></h3>
                        <p><?php _e('80% de pratique pour maîtriser rapidement les gestes techniques et créer vos propres réalisations.', 'almetal'); ?></p>
                    </div>

                    <div class="card">
                        <div class="card-item-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </div>
                        <h3><?php _e('Suivi personnalisé', 'almetal'); ?></h3>
                        <p><?php _e('Groupes restreints pour un accompagnement individualisé et adapté à votre niveau et vos objectifs.', 'almetal'); ?></p>
                    </div>

                    <div class="card">
                        <div class="card-item-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                        </div>
                        <h3><?php _e('Repartez avec votre création', 'almetal'); ?></h3>
                        <p><?php _e('À la fin de chaque stage, repartez avec l\'objet que vous avez fabriqué : un souvenir unique de votre apprentissage.', 'almetal'); ?></p>
                    </div>

                    <div class="card">
                        <div class="card-item-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M12 6v6l4 2"></path>
                            </svg>
                        </div>
                        <h3><?php _e('Horaires flexibles', 'almetal'); ?></h3>
                        <p><?php _e('Des créneaux adaptés à vos disponibilités : stages en semaine, week-end ou pendant les vacances scolaires.', 'almetal'); ?></p>
                    </div>
                </div>
            </div>

            <!-- CTA Contact -->
            <div class="formations-cta">
                <h2><?php _e('Une question sur nos formations ?', 'almetal'); ?></h2>
                <p><?php _e('Contactez-nous pour obtenir plus d\'informations ou pour réserver votre place.', 'almetal'); ?></p>
                <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <?php _e('Nous contacter', 'almetal'); ?>
                </a>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
