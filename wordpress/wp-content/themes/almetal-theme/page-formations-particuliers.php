<?php
/**
 * Template Name: Formations Particuliers
 * Description: Page des formations pour particuliers
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

get_header();

// Détection mobile
if (almetal_is_mobile()) {
    get_template_part('template-parts/header', 'mobile');
    echo '<main class="mobile-page-formations mobile-formations-particuliers">';
} else {
    echo '<main class="container section">';
}
?>
    <!-- En-tête de page -->
    <div class="formations-hero text-center mb-lg">
        <!-- <a href="<?php echo esc_url(home_url('/formations')); ?>" class="btn btn-small mb-2" style="display: inline-flex;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Retour aux formations
        </a> -->
        
        <h1 class="formations-main-title mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; color: var(--color-primary);">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            Formations Particuliers
        </h1>
        <p class="formations-intro" style="font-size: 1.2rem; color: var(--color-text-light); max-width: 800px; margin: 0 auto; line-height: 1.8;">
            Découvrez la ferronnerie d'art à travers nos stages pratiques et créatifs. 
            Que vous soyez débutant ou amateur éclairé, nos formations s'adaptent à votre niveau.
        </p>
        <div class="separator separator--animated mt-3 mb-3"></div>
    </div>

    <!-- Grille des formations -->
    <div class="formations-list">
        <h2 class="text-center mb-3" style="font-size: 2rem; color: var(--color-primary);">
            Nos stages pour particuliers
        </h2>

        <!-- Stage Découverte -->
        <div class="formation-detail-card card card-primary mb-3">
            <div class="formation-detail-header">
                <div class="formation-detail-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-primary);">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                </div>
                <div class="formation-detail-title-wrapper">
                    <h3 style="font-size: 1.8rem; color: var(--color-primary); margin-bottom: 0.5rem;">
                        Stage Découverte - Initiation
                    </h3>
                    <div class="badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        1 à 2 jours
                    </div>
                </div>
            </div>
            
            <p style="font-size: 1.1rem; color: var(--color-text-light); line-height: 1.8; margin: 1.5rem 0;">
                Première approche de la ferronnerie d'art. Apprenez les bases de la forge, 
                du travail du fer et réalisez votre première création (crochet, porte-clés, petit objet décoratif).
            </p>

            <div class="formation-detail-content">
                <div class="formation-detail-section">
                    <h4 style="color: var(--color-text-light); font-size: 1.2rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-primary);">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Programme
                    </h4>
                    <ul class="formation-list">
                        <li>Découverte de l'atelier et des outils</li>
                        <li>Techniques de base : chauffage, forgeage, mise en forme</li>
                        <li>Sécurité et gestes essentiels</li>
                        <li>Réalisation d'un objet simple</li>
                        <li>Finitions et patines</li>
                    </ul>
                </div>

                <div class="formation-detail-section">
                    <h4 style="color: var(--color-text-light); font-size: 1.2rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-primary);">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <polyline points="17 11 19 13 23 9"></polyline>
                        </svg>
                        Public
                    </h4>
                    <p style="color: var(--color-text); line-height: 1.6;">
                        Débutants, aucune expérience requise. À partir de 16 ans.
                    </p>
                </div>

                <div class="formation-detail-section">
                    <h4 style="color: var(--color-text-light); font-size: 1.2rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-primary);">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                        Tarif
                    </h4>
                    <p style="color: var(--color-text-light); font-size: 1.3rem; font-weight: 600;">
                        À partir de 150€ / jour
                    </p>
                    <p style="color: var(--color-text); font-size: 0.9rem; margin-top: 0.5rem;">
                        Matériel et matières premières inclus
                    </p>
                </div>
            </div>
        </div>

        <!-- Stage Perfectionnement -->
        <div class="formation-detail-card card card-primary mb-3">
            <div class="formation-detail-header">
                <div class="formation-detail-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-primary);">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                </div>
                <div class="formation-detail-title-wrapper">
                    <h3 style="font-size: 1.8rem; color: var(--color-primary); margin-bottom: 0.5rem;">
                        Stage Perfectionnement
                    </h3>
                    <div class="badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        3 à 5 jours
                    </div>
                </div>
            </div>
            
            <p style="font-size: 1.1rem; color: var(--color-text-light); line-height: 1.8; margin: 1.5rem 0;">
                Approfondissez vos connaissances et réalisez des projets plus complexes : 
                grilles décoratives, mobilier en fer forgé, sculptures métalliques.
            </p>

            <div class="formation-detail-content">
                <div class="formation-detail-section">
                    <h4 style="color: var(--color-text-light); font-size: 1.2rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-primary);">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Programme
                    </h4>
                    <ul class="formation-list">
                        <li>Techniques avancées de forgeage</li>
                        <li>Assemblages et soudures</li>
                        <li>Travail de la tôle et du profilé</li>
                        <li>Création de volutes et ornements</li>
                        <li>Réalisation d'un projet personnel</li>
                        <li>Patines et finitions professionnelles</li>
                    </ul>
                </div>

                <div class="formation-detail-section">
                    <h4 style="color: var(--color-text-light); font-size: 1.2rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-primary);">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <polyline points="17 11 19 13 23 9"></polyline>
                        </svg>
                        Public
                    </h4>
                    <p style="color: var(--color-text); line-height: 1.6;">
                        Niveau intermédiaire. Avoir suivi le stage découverte ou avoir des bases en ferronnerie.
                    </p>
                </div>

                <div class="formation-detail-section">
                    <h4 style="color: var(--color-text-light); font-size: 1.2rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-primary);">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                        Tarif
                    </h4>
                    <p style="color: var(--color-text-light); font-size: 1.3rem; font-weight: 600;">
                        À partir de 140€ / jour
                    </p>
                    <p style="color: var(--color-text); font-size: 0.9rem; margin-top: 0.5rem;">
                        Matériel et matières premières inclus
                    </p>
                </div>
            </div>
        </div>

        <!-- Stage Création Libre -->
        <div class="formation-detail-card card card-primary mb-3">
            <div class="formation-detail-header">
                <div class="formation-detail-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-primary);">
                        <path d="M12 19l7-7 3 3-7 7-3-3z"></path>
                        <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path>
                        <path d="M2 2l7.586 7.586"></path>
                        <circle cx="11" cy="11" r="2"></circle>
                    </svg>
                </div>
                <div class="formation-detail-title-wrapper">
                    <h3 style="font-size: 1.8rem; color: var(--color-primary); margin-bottom: 0.5rem;">
                        Stage Création Libre
                    </h3>
                    <div class="badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        Sur mesure
                    </div>
                </div>
            </div>
            
            <p style="font-size: 1.1rem; color: var(--color-text-light); line-height: 1.8; margin: 1.5rem 0;">
                Vous avez un projet personnel ? Venez le réaliser dans notre atelier avec l'accompagnement 
                d'un ferronnier professionnel. Durée et contenu adaptés à votre projet.
            </p>

            <div class="formation-detail-content">
                <div class="formation-detail-section">
                    <h4 style="color: var(--color-text-light); font-size: 1.2rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-primary);">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Formule
                    </h4>
                    <ul class="formation-list">
                        <li>Accompagnement personnalisé sur votre projet</li>
                        <li>Accès à l'atelier et aux machines</li>
                        <li>Conseils techniques et artistiques</li>
                        <li>Aide à la conception et aux plans</li>
                        <li>Durée flexible selon votre projet</li>
                    </ul>
                </div>

                <div class="formation-detail-section">
                    <h4 style="color: var(--color-text-light); font-size: 1.2rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-primary);">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <polyline points="17 11 19 13 23 9"></polyline>
                        </svg>
                        Public
                    </h4>
                    <p style="color: var(--color-text); line-height: 1.6;">
                        Tous niveaux. Avoir un projet défini et des bases en ferronnerie recommandées.
                    </p>
                </div>

                <div class="formation-detail-section">
                    <h4 style="color: var(--color-text-light); font-size: 1.2rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-primary);">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                        Tarif
                    </h4>
                    <p style="color: var(--color-text-light); font-size: 1.3rem; font-weight: 600;">
                        Sur devis
                    </p>
                    <p style="color: var(--color-text); font-size: 0.9rem; margin-top: 0.5rem;">
                        Selon la durée et les matériaux nécessaires
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations pratiques -->
    <div class="formations-practical-info mt-lg">
        <h2 class="text-center mb-3" style="font-size: 2rem; color: var(--color-primary);">
            Informations pratiques
        </h2>
        
        <div class="formations-advantages-grid">
            <div class="card card--light p-md">
                <h3 style="font-size: 1.3rem; color: var(--color-text-light); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-primary);">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    Horaires
                </h3>
                <p style="color: var(--color-text); line-height: 1.6;">
                    <strong>9h00 - 12h30 / 14h00 - 17h30</strong><br>
                    Pause déjeuner libre (restaurants à proximité)
                </p>
            </div>

            <div class="card card--light p-md">
                <h3 style="font-size: 1.3rem; color: var(--color-text-light); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-primary);">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    Effectif
                </h3>
                <p style="color: var(--color-text); line-height: 1.6;">
                    <strong>Maximum 6 participants</strong><br>
                    Pour un accompagnement personnalisé
                </p>
            </div>

            <div class="card card--light p-md">
                <h3 style="font-size: 1.3rem; color: var(--color-text-light); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-primary);">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                        <line x1="7" y1="7" x2="7.01" y2="7"></line>
                    </svg>
                    Matériel
                </h3>
                <p style="color: var(--color-text); line-height: 1.6;">
                    <strong>Tout est fourni</strong><br>
                    Outils, équipements de sécurité, matières premières
                </p>
            </div>

            <div class="card card--light p-md">
                <h3 style="font-size: 1.3rem; color: var(--color-text-light); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-primary);">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    Réservation
                </h3>
                <p style="color: var(--color-text); line-height: 1.6;">
                    <strong>Acompte de 30%</strong><br>
                    À la réservation, solde le premier jour du stage
                </p>
            </div>
        </div>
    </div>

    <!-- CTA Contact -->
    <div class="formations-cta text-center mt-lg">
        <h2 style="font-size: 2rem; color: var(--color-text-light); margin-bottom: 1rem;">
            Prêt à vous lancer ?
        </h2>
        <p style="font-size: 1.1rem; color: var(--color-text); margin-bottom: 2rem;">
            Contactez-nous pour connaître les prochaines dates disponibles et réserver votre place.
        </p>
        <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-primary btn-large">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            Réserver un stage
        </a>
    </div>
</main>

<?php
get_footer();
