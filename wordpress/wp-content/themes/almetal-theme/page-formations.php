<?php
/**
 * Template Name: Formations
 * Description: Page parente des formations en ferronnerie
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

get_header();
?>

<main class="container section">
    <div class="formations-hero text-center mb-lg">
        <h1 class="formations-main-title mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; color: var(--color-primary);">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
            </svg>
            Nos Formations en Ferronnerie
        </h1>
        <p class="formations-intro" style="font-size: 1.2rem; color: var(--color-text-light); max-width: 800px; margin: 0 auto; line-height: 1.8;">
            Découvrez nos formations professionnelles en ferronnerie d'art et métallerie. 
            Que vous soyez particulier passionné ou professionnel en reconversion, 
            nous vous accompagnons dans l'apprentissage des techniques traditionnelles et modernes.
        </p>
        <div class="separator separator--animated mt-3 mb-3"></div>
    </div>

    <!-- Grille des formations -->
    <div class="formations-grid">
        <!-- Carte Particuliers -->
        <a href="<?php echo esc_url(home_url('/formations-particuliers')); ?>" class="formation-card card card-primary hover-lift">
            <div class="formation-card-icon mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-primary);">
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
            <div class="btn-cta btn-cta--full">
                <span class="circle">
                    <span class="icon arrow">
                        <svg width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 6H17M17 6L12 1M17 6L12 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </span>
                <span class="button-text">Découvrir</span>
            </div>
        </a>

        <!-- Carte Professionnels -->
        <a href="<?php echo esc_url(home_url('/formations-professionnels')); ?>" class="formation-card card card-primary hover-lift">
            <div class="formation-card-icon mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-primary);">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                </svg>
            </div>
            <h2 class="formation-card-title" style="font-size: 2rem; color: var(--color-primary); margin-bottom: 1rem;">
                Formations Professionnels
            </h2>
            <p class="formation-card-description" style="font-size: 1.1rem; color: var(--color-text-light); line-height: 1.6; margin-bottom: 1.5rem;">
                Formations certifiantes et qualifiantes pour les professionnels du bâtiment, artisans en reconversion 
                ou demandeurs d'emploi. Financement CPF et Pôle Emploi possible.
            </p>
            <div class="formation-card-features mb-3">
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
            <div class="btn-cta btn-cta--full">
                <span class="circle">
                    <span class="icon arrow">
                        <svg width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 6H17M17 6L12 1M17 6L12 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </span>
                <span class="button-text">Découvrir</span>
            </div>
        </a>
    </div>

    <!-- Section avantages -->
    <div class="formations-advantages mt-lg">
        <h2 class="text-center mb-3" style="font-size: 2rem; color: var(--color-primary);">
            Pourquoi choisir nos formations ?
        </h2>
        <div class="formations-advantages-grid">
            <div class="card card--light p-md">
                <div class="card-item-icon mb-2 icon-animated--pulse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20h9"></path>
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                    </svg>
                </div>
                <h3 style="font-size: 1.3rem; color: var(--color-text-light); margin-bottom: 0.5rem;">Atelier équipé</h3>
                <p style="color: var(--color-text); line-height: 1.6;">
                    Accès à un atelier professionnel avec tout le matériel nécessaire : forge, enclume, outils de découpe et de soudure.
                </p>
            </div>

            <div class="card card--light p-md">
                <div class="card-item-icon mb-2 icon-animated--pulse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <h3 style="font-size: 1.3rem; color: var(--color-text-light); margin-bottom: 0.5rem;">Formateurs experts</h3>
                <p style="color: var(--color-text); line-height: 1.6;">
                    Apprenez auprès d'artisans ferronniers expérimentés, passionnés par la transmission de leur savoir-faire.
                </p>
            </div>

            <div class="card card--light p-md">
                <div class="card-item-icon mb-2 icon-animated--pulse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                    </svg>
                </div>
                <h3 style="font-size: 1.3rem; color: var(--color-text-light); margin-bottom: 0.5rem;">Pratique intensive</h3>
                <p style="color: var(--color-text); line-height: 1.6;">
                    80% de pratique pour maîtriser rapidement les gestes techniques et créer vos propres réalisations.
                </p>
            </div>

            <div class="card card--light p-md">
                <div class="card-item-icon mb-2 icon-animated--pulse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <h3 style="font-size: 1.3rem; color: var(--color-text-light); margin-bottom: 0.5rem;">Suivi personnalisé</h3>
                <p style="color: var(--color-text); line-height: 1.6;">
                    Groupes restreints pour un accompagnement individualisé et adapté à votre niveau et vos objectifs.
                </p>
            </div>
        </div>
    </div>

    <!-- CTA Contact -->
    <div class="formations-cta text-center mt-lg">
        <h2 style="font-size: 2rem; color: var(--color-text-light); margin-bottom: 1rem;">
            Une question sur nos formations ?
        </h2>
        <p style="font-size: 1.1rem; color: var(--color-text); margin-bottom: 2rem;">
            Contactez-nous pour obtenir plus d'informations ou pour réserver votre place.
        </p>
        <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-primary btn-large">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            Nous contacter
        </a>
    </div>
</main>

<?php
get_footer();
