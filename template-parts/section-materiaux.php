<?php
/**
 * Section "Matériaux et finitions" - Page d'accueil
 * 
 * @package ALMetallerie
 * @since 1.0.1
 */
?>

<section class="hp-materiaux-section" id="materiaux">
    <div class="hp-materiaux-wrapper">
        
        <div class="hp-section-tag">
            <span><?php esc_html_e('Nos matériaux', 'almetal'); ?></span>
        </div>

        <h2 class="hp-section-title">
            <?php esc_html_e('DES MATÉRIAUX NOBLES POUR DES RÉALISATIONS DURABLES', 'almetal'); ?>
        </h2>

        <p class="hp-section-subtitle">
            <?php esc_html_e('Nous sélectionnons les meilleurs matériaux pour garantir longévité et esthétique à vos ouvrages métalliques.', 'almetal'); ?>
        </p>

        <div class="hp-materiaux-grid">

            <div class="hp-materiaux-card">
                <div class="hp-materiaux-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"/><path d="M12 1v6m0 6v6"/><path d="m4.93 4.93 4.24 4.24m5.66 5.66 4.24 4.24"/><path d="M1 12h6m6 0h6"/><path d="m4.93 19.07 4.24-4.24m5.66-5.66 4.24-4.24"/>
                    </svg>
                </div>
                <h3 class="hp-materiaux-card-title"><?php esc_html_e('Acier', 'almetal'); ?></h3>
                <p class="hp-materiaux-card-text">
                    <?php echo wp_kses_post('Matériau privilégié pour sa <strong>robustesse</strong> et sa polyvalence. Idéal pour <a href="' . esc_url(home_url('/type-realisation/portails/')) . '">portails</a>, <a href="' . esc_url(home_url('/type-realisation/garde-corps/')) . '">garde-corps</a> et structures porteuses. Traitement anticorrosion et <strong>thermolaquage</strong> dans une large gamme de coloris RAL.'); ?>
                </p>
            </div>

            <div class="hp-materiaux-card">
                <div class="hp-materiaux-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/>
                        <path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/>
                    </svg>
                </div>
                <h3 class="hp-materiaux-card-title"><?php esc_html_e('Inox 316L', 'almetal'); ?></h3>
                <p class="hp-materiaux-card-text">
                    <?php esc_html_e('Résistance exceptionnelle à la corrosion. Recommandé pour les environnements humides, les cuisines professionnelles et les piscines. Entretien minimal, esthétique moderne et intemporelle.', 'almetal'); ?>
                </p>
            </div>

            <div class="hp-materiaux-card">
                <div class="hp-materiaux-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16" y1="8" x2="2" y2="22"/><line x1="17.5" y1="15" x2="9" y2="15"/>
                    </svg>
                </div>
                <h3 class="hp-materiaux-card-title"><?php esc_html_e('Aluminium', 'almetal'); ?></h3>
                <p class="hp-materiaux-card-text">
                    <?php esc_html_e('Léger et naturellement inoxydable. Parfait pour les grandes structures (pergolas, vérandas) et les projets nécessitant une portée importante sans poids excessif. Excellent rapport résistance/poids.', 'almetal'); ?>
                </p>
            </div>

            <div class="hp-materiaux-card hp-materiaux-card--finitions">
                <div class="hp-materiaux-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="13.5" cy="6.5" r=".5"/><circle cx="17.5" cy="10.5" r=".5"/><circle cx="8.5" cy="7.5" r=".5"/><circle cx="6.5" cy="12.5" r=".5"/>
                        <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2Z"/>
                    </svg>
                </div>
                <h3 class="hp-materiaux-card-title"><?php esc_html_e('Finitions premium', 'almetal'); ?></h3>
                <ul class="hp-materiaux-finitions-list">
                    <li><?php echo wp_kses_post('<strong>Thermolaquage RAL</strong> : protection optimale, 200+ coloris'); ?></li>
                    <li><?php echo wp_kses_post('<strong>Acier brut verni</strong> : style industriel authentique'); ?></li>
                    <li><?php echo wp_kses_post('<strong>Peinture époxy</strong> : résistance chimique renforcée'); ?></li>
                    <li><?php echo wp_kses_post('<strong>Galvanisation à chaud</strong> : durabilité maximale extérieur'); ?></li>
                </ul>
            </div>

        </div>
    </div>
</section>

<style>
/* ============================================
   SECTION MATÉRIAUX - HOME PAGE (hp-)
   ============================================ */
.hp-materiaux-section {
    padding: 80px 0;
    background: #141414;
    border-top: 1px solid rgba(240, 139, 24, 0.12);
}

.hp-materiaux-wrapper {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    text-align: center;
}

.hp-materiaux-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
    margin-top: 3rem;
}

.hp-materiaux-card {
    background: rgba(34, 34, 34, 0.6);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    border: 1px solid rgba(240, 139, 24, 0.1);
    padding: 2rem 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
}

.hp-materiaux-card:hover {
    border-color: rgba(240, 139, 24, 0.4);
    box-shadow: 0 10px 30px rgba(240, 139, 24, 0.15);
    transform: translateY(-4px);
}

.hp-materiaux-card-icon {
    width: 64px;
    height: 64px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #F08B18 0%, #e67e0f 100%);
    border-radius: 50%;
    margin: 0 auto 1.25rem;
    box-shadow: 0 6px 20px rgba(240, 139, 24, 0.3);
}

.hp-materiaux-card-icon svg {
    width: 28px;
    height: 28px;
    stroke: white;
}

.hp-materiaux-card-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #F08B18;
    margin: 0 0 0.75rem 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.hp-materiaux-card-text {
    font-size: 0.95rem;
    line-height: 1.7;
    color: rgba(255, 255, 255, 0.75);
    margin: 0;
}

.hp-materiaux-card-text a {
    color: #F08B18;
    text-decoration: none;
    border-bottom: 1px solid rgba(240, 139, 24, 0.3);
    transition: border-color 0.3s ease;
}

.hp-materiaux-card-text a:hover {
    border-bottom-color: #F08B18;
}

.hp-materiaux-finitions-list {
    list-style: none;
    padding: 0;
    margin: 0;
    text-align: left;
}

.hp-materiaux-finitions-list li {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.95rem;
    padding: 0.6rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    line-height: 1.6;
}

.hp-materiaux-finitions-list li:last-child {
    border-bottom: none;
}

.hp-materiaux-finitions-list li strong {
    color: #F08B18;
}

/* Animation au scroll - slide up */
.hp-materiaux-card {
    opacity: 0;
    transform: translateY(40px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}

.hp-materiaux-card.hp-visible {
    opacity: 1;
    transform: translateY(0);
}

@media (max-width: 768px) {
    .hp-materiaux-section {
        padding: 60px 0;
    }
    .hp-materiaux-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    .hp-materiaux-card {
        padding: 1.5rem 1.25rem;
    }
}
</style>

<script>
(function() {
    var cards = document.querySelectorAll('.hp-materiaux-card');
    if (!cards.length) return;

    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('hp-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.3 });

    cards.forEach(function(card) {
        observer.observe(card);
    });
})();
</script>
