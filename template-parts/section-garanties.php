<?php
/**
 * Section "Garanties et certifications" - Page d'accueil
 * 
 * @package ALMetallerie
 * @since 1.0.1
 */
?>

<section class="hp-garanties-section" id="garanties">
    <div class="hp-garanties-wrapper">
        
        <div class="hp-section-tag">
            <span><?php esc_html_e('Nos garanties', 'almetal'); ?></span>
        </div>

        <h2 class="hp-section-title">
            <?php esc_html_e('VOS GARANTIES, NOTRE ENGAGEMENT', 'almetal'); ?>
        </h2>

        <div class="hp-garanties-grid">

            <div class="hp-garanties-card">
                <div class="hp-garanties-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                </div>
                <h3 class="hp-garanties-card-title"><?php esc_html_e('Garantie décennale', 'almetal'); ?></h3>
                <p class="hp-garanties-card-text">
                    <?php esc_html_e('Tous nos travaux de métallerie et serrurerie sont couverts par une garantie décennale obligatoire.', 'almetal'); ?>
                </p>
            </div>

            <div class="hp-garanties-card">
                <div class="hp-garanties-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="4" y="2" width="16" height="20" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M12 6h.01"/><path d="M12 10h.01"/><path d="M12 14h.01"/><path d="M16 10h.01"/><path d="M16 14h.01"/><path d="M8 10h.01"/><path d="M8 14h.01"/>
                    </svg>
                </div>
                <h3 class="hp-garanties-card-title"><?php esc_html_e('Assurance RC Pro', 'almetal'); ?></h3>
                <p class="hp-garanties-card-text">
                    <?php esc_html_e('Responsabilité civile professionnelle à jour pour votre protection et la nôtre.', 'almetal'); ?>
                </p>
            </div>

            <div class="hp-garanties-card">
                <div class="hp-garanties-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>
                    </svg>
                </div>
                <h3 class="hp-garanties-card-title"><?php esc_html_e('Certifications', 'almetal'); ?></h3>
                <p class="hp-garanties-card-text">
                    <?php esc_html_e('Qualibois (chaudières biomasse), RIFAP (premiers secours), formations continues techniques.', 'almetal'); ?>
                </p>
            </div>

            <div class="hp-garanties-card">
                <div class="hp-garanties-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <h3 class="hp-garanties-card-title"><?php esc_html_e('Normes NF et DTU', 'almetal'); ?></h3>
                <p class="hp-garanties-card-text">
                    <?php esc_html_e('Conformité stricte aux normes françaises et Documents Techniques Unifiés du bâtiment.', 'almetal'); ?>
                </p>
            </div>

            <div class="hp-garanties-card">
                <div class="hp-garanties-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 18v3c0 .6.4 1 1 1h4v-3h3v-3h2l1.4-1.4a6.5 6.5 0 1 0-4-4Z"/><circle cx="16.5" cy="7.5" r=".5"/>
                    </svg>
                </div>
                <h3 class="hp-garanties-card-title"><?php esc_html_e('Artisan qualifié', 'almetal'); ?></h3>
                <p class="hp-garanties-card-text">
                    <?php esc_html_e('Immatriculé chambre des métiers du Puy-de-Dôme. Entreprise artisanale locale et pérenne.', 'almetal'); ?>
                </p>
            </div>

            <div class="hp-garanties-card">
                <div class="hp-garanties-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                </div>
                <h3 class="hp-garanties-card-title"><?php esc_html_e('Formation continue', 'almetal'); ?></h3>
                <p class="hp-garanties-card-text">
                    <?php esc_html_e('Veille technique permanente, formations sécurité et nouvelles techniques de soudage.', 'almetal'); ?>
                </p>
            </div>

        </div>
    </div>
</section>

<style>
/* ============================================
   SECTION GARANTIES - HOME PAGE (hp-)
   ============================================ */
.hp-garanties-section {
    padding: 80px 0 120px;
    background: linear-gradient(to bottom, #141414 0%, #141414 60%, #191919 100%);
    margin-bottom: 0;
    border-top: 1px solid rgba(240, 139, 24, 0.12);
}

.hp-garanties-wrapper {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    text-align: center;
}

.hp-garanties-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    margin-top: 3rem;
}

.hp-garanties-card {
    background: rgba(34, 34, 34, 0.6);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    border: 1px solid rgba(240, 139, 24, 0.1);
    padding: 1.5rem 1.25rem;
    text-align: center;
    transition: all 0.3s ease;
}

.hp-garanties-card:hover {
    border-color: rgba(240, 139, 24, 0.35);
    box-shadow: 0 8px 25px rgba(240, 139, 24, 0.12);
    transform: translateY(-3px);
}

.hp-garanties-card-icon {
    width: 52px;
    height: 52px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #F08B18 0%, #e67e0f 100%);
    border-radius: 50%;
    margin: 0 auto 1rem;
    box-shadow: 0 4px 15px rgba(240, 139, 24, 0.25);
}

.hp-garanties-card-icon svg {
    width: 24px;
    height: 24px;
    stroke: white;
}

.hp-garanties-card-title {
    font-size: 1rem;
    font-weight: 700;
    color: #F08B18;
    margin: 0 0 0.5rem 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.hp-garanties-card-text {
    font-size: 0.9rem;
    line-height: 1.6;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

@media (max-width: 992px) {
    .hp-garanties-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Alternance des backgrounds pour les sections existantes sur la homepage */
body.home .actualites-section {
    background: #141414;
    border-top: 1px solid rgba(240, 139, 24, 0.12);
}

body.home .hp-formations-section {
    background: #141414;
    border-top: 1px solid rgba(240, 139, 24, 0.12);
}

/* Transition fluide vers le footer mountains sur la homepage */
body.home .footer-mountains {
    margin-top: 80px;
}

body.home .footer-mountains::before {
    top: -80px;
    height: 80px;
    background: linear-gradient(to bottom, transparent 0%, #191919 100%);
}

@media (max-width: 576px) {
    .hp-garanties-section {
        padding: 60px 0 80px;
    }
    .hp-garanties-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    .hp-garanties-card {
        padding: 1.25rem 1rem;
    }
}
</style>
