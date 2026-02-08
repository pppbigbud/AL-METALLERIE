<?php
/**
 * Section "De l'idée à l'installation" - Page d'accueil
 * 
 * @package ALMetallerie
 * @since 1.0.1
 */
?>

<section class="hp-processus-section" id="processus">
    <div class="hp-processus-wrapper">
        
        <div class="hp-section-tag">
            <span><?php esc_html_e('Notre méthode', 'almetal'); ?></span>
        </div>

        <h2 class="hp-section-title">
            <?php esc_html_e('DE L\'IDÉE À L\'INSTALLATION : NOTRE MÉTHODE ÉPROUVÉE', 'almetal'); ?>
        </h2>

        <p class="hp-section-subtitle">
            <?php esc_html_e('Un processus transparent en 5 étapes pour la réussite de votre projet de métallerie sur mesure.', 'almetal'); ?>
        </p>

        <div class="hp-processus-timeline">

            <div class="hp-processus-step">
                <div class="hp-processus-step-number">
                    <span>01</span>
                </div>
                <div class="hp-processus-step-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        <polyline points="7.5 4.21 12 6.81 16.5 4.21"/><polyline points="7.5 19.79 7.5 14.6 3 12"/><polyline points="21 12 16.5 14.6 16.5 19.79"/>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>
                    </svg>
                </div>
                <div class="hp-processus-step-content">
                    <h3 class="hp-processus-step-title"><?php esc_html_e('Échange et visite gratuite', 'almetal'); ?></h3>
                    <p class="hp-processus-step-text">
                        <?php esc_html_e('Nous nous déplaçons chez vous pour comprendre votre projet, prendre les mesures précises et évaluer les contraintes techniques. Conseil personnalisé sur les matériaux, les finitions et les options possibles.', 'almetal'); ?>
                    </p>
                </div>
            </div>

            <div class="hp-processus-step">
                <div class="hp-processus-step-number">
                    <span>02</span>
                </div>
                <div class="hp-processus-step-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
                    </svg>
                </div>
                <div class="hp-processus-step-content">
                    <h3 class="hp-processus-step-title"><?php esc_html_e('Devis détaillé sous 48h', 'almetal'); ?></h3>
                    <p class="hp-processus-step-text">
                        <?php esc_html_e('Vous recevez un devis transparent avec tarification détaillée, plans et croquis si nécessaire. Nous présentons plusieurs options et alternatives pour s\'adapter à votre budget.', 'almetal'); ?>
                    </p>
                </div>
            </div>

            <div class="hp-processus-step">
                <div class="hp-processus-step-number">
                    <span>03</span>
                </div>
                <div class="hp-processus-step-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/>
                    </svg>
                </div>
                <div class="hp-processus-step-content">
                    <h3 class="hp-processus-step-title"><?php esc_html_e('Fabrication dans notre atelier', 'almetal'); ?></h3>
                    <p class="hp-processus-step-text">
                        <?php echo wp_kses_post('Votre ouvrage est <strong>fabriqué sur mesure</strong> dans notre atelier de Peschadoires. Techniques de <strong>soudure MIG, TIG et ARC</strong> selon les besoins. Thermolaquage professionnel pour une finition impeccable. Contrôle qualité rigoureux à chaque étape.'); ?>
                    </p>
                </div>
            </div>

            <div class="hp-processus-step">
                <div class="hp-processus-step-number">
                    <span>04</span>
                </div>
                <div class="hp-processus-step-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                    </svg>
                </div>
                <div class="hp-processus-step-content">
                    <h3 class="hp-processus-step-title"><?php esc_html_e('Pose professionnelle', 'almetal'); ?></h3>
                    <p class="hp-processus-step-text">
                        <?php esc_html_e('Nos équipes qualifiées assurent l\'installation dans les règles de l\'art. Respect strict des normes de sécurité. Mise en service complète avec réglages et ajustements finaux.', 'almetal'); ?>
                    </p>
                </div>
            </div>

            <div class="hp-processus-step">
                <div class="hp-processus-step-number">
                    <span>05</span>
                </div>
                <div class="hp-processus-step-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <div class="hp-processus-step-content">
                    <h3 class="hp-processus-step-title"><?php esc_html_e('Suivi et garantie', 'almetal'); ?></h3>
                    <p class="hp-processus-step-text">
                        <?php echo wp_kses_post('Nous restons disponibles après la pose pour tout ajustement. <strong>Garantie décennale</strong> sur tous nos travaux. Conseils d\'entretien personnalisés. <a href="' . esc_url(home_url('/contact/')) . '">Contactez-nous</a> pour toute question.'); ?>
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
/* ============================================
   SECTION PROCESSUS - HOME PAGE (hp-)
   ============================================ */
.hp-processus-section {
    padding: 80px 0;
    background: #191919;
    border-top: 1px solid rgba(240, 139, 24, 0.12);
}

.hp-processus-wrapper {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 2rem;
    text-align: center;
}

.hp-processus-timeline {
    margin-top: 3rem;
    position: relative;
}

.hp-processus-timeline::before {
    content: '';
    position: absolute;
    left: 40px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, rgba(240, 139, 24, 0.5), rgba(240, 139, 24, 0.1));
}

.hp-processus-step {
    display: flex;
    align-items: flex-start;
    gap: 1.5rem;
    padding: 1.5rem 0;
    position: relative;
    text-align: left;
}

.hp-processus-step-number {
    flex-shrink: 0;
    width: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 1;
}

.hp-processus-step-number span {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 52px;
    height: 52px;
    background: linear-gradient(135deg, #F08B18 0%, #e67e0f 100%);
    border-radius: 50%;
    color: white;
    font-size: 1.1rem;
    font-weight: 800;
    letter-spacing: 1px;
    box-shadow: 0 4px 15px rgba(240, 139, 24, 0.4);
}

.hp-processus-step-icon {
    display: none;
}

.hp-processus-step-content {
    flex: 1;
    background: rgba(34, 34, 34, 0.6);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    border: 1px solid rgba(240, 139, 24, 0.1);
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.hp-processus-step:hover .hp-processus-step-content {
    border-color: rgba(240, 139, 24, 0.3);
    box-shadow: 0 8px 25px rgba(240, 139, 24, 0.1);
}

.hp-processus-step-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #F08B18;
    margin: 0 0 0.5rem 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.hp-processus-step-text {
    font-size: 0.95rem;
    line-height: 1.7;
    color: rgba(255, 255, 255, 0.75);
    margin: 0;
}

.hp-processus-step-text a {
    color: #F08B18;
    text-decoration: none;
    border-bottom: 1px solid rgba(240, 139, 24, 0.3);
    transition: border-color 0.3s ease;
}

.hp-processus-step-text a:hover {
    border-bottom-color: #F08B18;
}

.hp-processus-step-text strong {
    color: rgba(255, 255, 255, 0.9);
}

/* Animation au scroll */
.hp-processus-step {
    opacity: 0;
    transform: translateX(-30px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}

.hp-processus-step.hp-visible {
    opacity: 1;
    transform: translateX(0);
}


.hp-processus-timeline::before {
    transform-origin: top;
    transform: scaleY(0);
    transition: transform 1.2s ease;
}

.hp-processus-timeline.hp-line-visible::before {
    transform: scaleY(1);
}

@media (max-width: 768px) {
    .hp-processus-section {
        padding: 60px 0;
    }
    .hp-processus-timeline::before {
        left: 28px;
    }
    .hp-processus-step-number {
        width: 56px;
    }
    .hp-processus-step-number span {
        width: 44px;
        height: 44px;
        font-size: 0.95rem;
    }
    .hp-processus-step {
        gap: 1rem;
    }
    .hp-processus-step-content {
        padding: 1.25rem;
    }
}
</style>

<script>
(function() {
    var section = document.getElementById('processus');
    if (!section) return;

    var timeline = section.querySelector('.hp-processus-timeline');
    var steps = section.querySelectorAll('.hp-processus-step');

    // Observer pour la ligne de timeline
    var lineObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                timeline.classList.add('hp-line-visible');
                lineObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    lineObserver.observe(timeline);

    // Observer individuel pour chaque étape
    var stepObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('hp-visible');
                stepObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.3 });

    steps.forEach(function(step) {
        stepObserver.observe(step);
    });
})();
</script>
