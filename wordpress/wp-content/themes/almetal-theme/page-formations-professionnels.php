<?php
/**
 * Template Name: Formations Professionnels
 * Description: Page des formations pour professionnels
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

get_header();

// Détection mobile
if (almetal_is_mobile()) {
    get_template_part('template-parts/header', 'mobile');
    echo '<main class="mobile-page-formations mobile-formations-professionnels">';
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
                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
            </svg>
            Formations Professionnels
        </h1>
        <p class="formations-intro" style="font-size: 1.2rem; color: var(--color-text-light); max-width: 800px; margin: 0 auto; line-height: 1.8;">
            Formations certifiantes et qualifiantes en ferronnerie et métallerie. 
            Développez vos compétences professionnelles ou reconvertissez-vous dans un métier d'art passionnant.
        </p>
        <div class="separator separator--animated mt-3 mb-3"></div>
    </div>

    <!-- Grille des formations -->
    <div class="formations-list">
        <!-- <h2 class="text-center mb-3" style="font-size: 2rem; color: var(--color-primary);">
            Nos formations professionnelles
        </h2> -->

        <!-- CAP Ferronnier d'art -->
        <div class="formation-detail-card card card-primary mb-3">
            <div class="formation-detail-header">
                <div class="formation-detail-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-primary);">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                    </svg>
                </div>
                <div class="formation-detail-title-wrapper">
                    <h3 style="font-size: 1.8rem; color: var(--color-primary); margin-bottom: 0.5rem;">
                        CAP Ferronnier d'Art
                    </h3>
                    <div class="badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        10 à 12 mois
                    </div>
                </div>
            </div>
            
            <p style="font-size: 1.1rem; color: var(--color-text-light); line-height: 1.8; margin: 1.5rem 0;">
                Formation diplômante reconnue par l'État. Maîtrisez l'ensemble des techniques de ferronnerie d'art 
                et obtenez une qualification professionnelle de niveau 3 (anciennement niveau V).
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
                        <li>Techniques de forge traditionnelle et moderne</li>
                        <li>Soudure (arc, MIG, TIG)</li>
                        <li>Traçage et développement</li>
                        <li>Assemblage et montage</li>
                        <li>Dessin technique et artistique</li>
                        <li>Histoire de l'art et styles architecturaux</li>
                        <li>Technologie des matériaux</li>
                        <li>Prévention, santé et environnement</li>
                    </ul>
                </div>

                <div class="formation-detail-section">
                    <h4 style="color: var(--color-text-light); font-size: 1.2rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-primary);">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                        Financement
                    </h4>
                    <p style="color: var(--color-text); line-height: 1.6;">
                        <strong>CPF, Pôle Emploi, Région, OPCO</strong><br>
                        Nous vous accompagnons dans vos démarches de financement.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Contact -->
    <div class="formations-cta text-center mt-lg">
        <h2 style="font-size: 2rem; color: var(--color-text-light); margin-bottom: 1rem;">
            Lancez votre carrière en ferronnerie
        </h2>
        <p style="font-size: 1.1rem; color: var(--color-text); margin-bottom: 2rem;">
            Contactez-nous pour un entretien personnalisé et construire ensemble votre projet professionnel.
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
