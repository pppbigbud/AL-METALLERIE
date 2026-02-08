<?php
/**
 * Section "Zone d'intervention" - Page d'accueil
 * 
 * @package ALMetallerie
 * @since 1.0.1
 */

$villes = array(
    'Thiers'                  => '/metallier-thiers/',
    'Clermont-Ferrand'        => '/metallier-clermont-ferrand/',
    'Riom'                    => '/metallier-riom/',
    'Issoire'                 => '/metallier-issoire/',
    'Ambert'                  => '/metallier-ambert/',
    'Vichy'                   => '/metallier-vichy/',
    'Courpière'               => '/metallier-courpiere/',
    'Cournon-d\'Auvergne'     => '/metallier-cournon-dauvergne/',
    'Chamalières'             => '/metallier-chamalieres/',
    'Beaumont'                => '/metallier-beaumont/',
    'Aubière'                 => '/metallier-aubiere/',
    'Lezoux'                  => '/metallier-lezoux/',
    'Pont-du-Château'         => '/metallier-pont-du-chateau/',
    'Saint-Rémy-sur-Durolle'  => '/metallier-saint-remy-sur-durolle/',
);
?>

<section class="hp-zone-section" id="zone-intervention">
    <div class="hp-zone-wrapper">
        
        <div class="hp-section-tag">
            <span><?php esc_html_e('Zone d\'intervention', 'almetal'); ?></span>
        </div>

        <h2 class="hp-section-title">
            <?php esc_html_e('INTERVENTION DANS TOUT LE PUY-DE-DÔME', 'almetal'); ?>
        </h2>

        <div class="hp-zone-icon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
            </svg>
        </div>

        <p class="hp-zone-intro">
            <?php esc_html_e('Basés à Peschadoires près de Thiers, nous intervenons dans un rayon de 50 km couvrant l\'ensemble du Puy-de-Dôme et les départements limitrophes d\'Auvergne-Rhône-Alpes.', 'almetal'); ?>
        </p>

        <h3 class="hp-zone-subtitle"><?php esc_html_e('Nos principales zones d\'intervention', 'almetal'); ?></h3>

        <div class="hp-zone-pills">
            <?php foreach ($villes as $ville => $slug) : ?>
                <a href="<?php echo esc_url(home_url($slug)); ?>" class="hp-zone-pill">
                    <?php echo esc_html($ville); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <p class="hp-zone-closing">
            <?php esc_html_e('Pour les projets d\'envergure, nous étudions les demandes au-delà de notre zone habituelle. N\'hésitez pas à nous consulter, nous trouverons une solution adaptée.', 'almetal'); ?>
        </p>

        <div class="hp-zone-cta">
            <a href="<?php echo esc_url(home_url('/soudure-auvergne/')); ?>" class="hp-zone-cta-button">
                <span><?php esc_html_e('Voir toutes nos zones d\'intervention', 'almetal'); ?></span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                </svg>
            </a>
        </div>
    </div>
</section>

<style>
/* ============================================
   SECTION ZONE D'INTERVENTION - HOME PAGE (hp-)
   ============================================ */
.hp-zone-section {
    padding: 80px 0;
    background: #191919;
    border-top: 1px solid rgba(240, 139, 24, 0.12);
}

.hp-zone-wrapper {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 2rem;
    text-align: center;
}

.hp-zone-icon {
    margin: 1.5rem 0;
}

.hp-zone-icon svg {
    stroke: #F08B18;
    filter: drop-shadow(0 4px 12px rgba(240, 139, 24, 0.3));
}

.hp-zone-intro {
    font-size: 1.05rem;
    line-height: 1.8;
    color: rgba(255, 255, 255, 0.8);
    max-width: 700px;
    margin: 0 auto 2rem;
}

.hp-zone-subtitle {
    font-size: 1rem;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.6);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin: 0 0 1.5rem 0;
}

.hp-zone-pills {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.75rem;
    margin-bottom: 2rem;
}

.hp-zone-pill {
    display: inline-block;
    padding: 8px 18px;
    background: rgba(240, 139, 24, 0.08);
    border: 1px solid rgba(240, 139, 24, 0.25);
    border-radius: 30px;
    color: rgba(255, 255, 255, 0.85);
    font-size: 0.9rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
}

.hp-zone-pill:hover {
    background: rgba(240, 139, 24, 0.2);
    border-color: #F08B18;
    color: #F08B18;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(240, 139, 24, 0.2);
}

.hp-zone-closing {
    font-size: 0.95rem;
    line-height: 1.7;
    color: rgba(255, 255, 255, 0.6);
    max-width: 600px;
    margin: 0 auto 2rem;
    font-style: italic;
}

.hp-zone-cta {
    margin-top: 1rem;
}

.hp-zone-cta-button {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 28px;
    background: transparent;
    border: 2px solid #F08B18;
    border-radius: 50px;
    color: #F08B18;
    font-size: 0.95rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.hp-zone-cta-button:hover {
    background: #F08B18;
    color: #fff;
    box-shadow: 0 6px 20px rgba(240, 139, 24, 0.3);
}

.hp-zone-cta-button svg {
    transition: transform 0.3s ease;
}

.hp-zone-cta-button:hover svg {
    transform: translateX(4px);
}

/* Animation au scroll - cascade pills */
.hp-zone-pill {
    opacity: 0;
    transform: translateY(15px) scale(0.9);
    transition: opacity 0.4s ease, transform 0.4s ease;
}

.hp-zone-pill.hp-visible {
    opacity: 1;
    transform: translateY(0) scale(1);
}

.hp-zone-cta {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}

.hp-zone-cta.hp-visible {
    opacity: 1;
    transform: translateY(0);
}

@media (max-width: 768px) {
    .hp-zone-section {
        padding: 60px 0;
    }
    .hp-zone-pills {
        gap: 0.5rem;
    }
    .hp-zone-pill {
        padding: 6px 14px;
        font-size: 0.85rem;
    }
}
</style>

<script>
(function() {
    var pills = document.querySelectorAll('.hp-zone-pill');
    var cta = document.querySelector('.hp-zone-cta');
    if (!pills.length) return;

    // Observer pour les pills avec cascade rapide
    var pillContainer = document.querySelector('.hp-zone-pills');
    var containerObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                pills.forEach(function(pill, index) {
                    setTimeout(function() {
                        pill.classList.add('hp-visible');
                    }, index * 80);
                });
                containerObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.3 });

    containerObserver.observe(pillContainer);

    // Observer pour le CTA
    if (cta) {
        var ctaObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('hp-visible');
                    ctaObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });
        ctaObserver.observe(cta);
    }
})();
</script>
