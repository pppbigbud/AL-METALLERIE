<?php
/**
 * Template Part: Section Formations Mobile
 * Affiche 2 cards : Professionnels et Particuliers
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */
?>

<?php
$almetal_formations_pro_page = get_page_by_path('formations-professionnelles');
$almetal_formations_part_page = get_page_by_path('formations-particuliers');

$almetal_formations_pro_url = $almetal_formations_pro_page ? get_permalink($almetal_formations_pro_page) : home_url('/formations-professionnelles/');
$almetal_formations_part_url = $almetal_formations_part_page ? get_permalink($almetal_formations_part_page) : home_url('/formations-particuliers/');
?>

<div class="mobile-formations-container">
    <!-- Tag -->
    <div class="mobile-formations-tag">
        <span><?php esc_html_e('Nos Formations', 'almetal'); ?></span>
    </div>

    <!-- Titre -->
    <h2 class="mobile-section-title">
        <?php esc_html_e('DÉVELOPPEZ VOS COMPÉTENCES', 'almetal'); ?>
    </h2>

    <!-- Sous-titre -->
    <p class="mobile-formations-subtitle">
        <?php esc_html_e('Formations professionnelles en métallerie adaptées à vos besoins', 'almetal'); ?>
    </p>

    <!-- Grille de 2 cards -->
    <div class="mobile-formations-grid">
        
        <!-- Card Professionnels -->
        <article class="mobile-formation-card" data-href="<?php echo esc_url($almetal_formations_pro_url); ?>" role="link" tabindex="0">
            <div class="mobile-formation-card-inner">
                <!-- Icône -->
                <div class="mobile-formation-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>

                <!-- Contenu -->
                <div class="mobile-formation-content">
                    <h3 class="mobile-formation-title">
                        <?php esc_html_e('PROFESSIONNELS', 'almetal'); ?>
                    </h3>
                    
                    <p class="mobile-formation-description">
                        <?php esc_html_e('Formations spécialisées pour les professionnels du métal : techniques avancées et perfectionnement.', 'almetal'); ?>
                    </p>

                    <!-- Points clés -->
                    <ul class="mobile-formation-features">
                        <li>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <span><?php esc_html_e('Certification pro', 'almetal'); ?></span>
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
                            <span><?php esc_html_e('Équipement pro', 'almetal'); ?></span>
                        </li>
                    </ul>

                    <!-- Bouton -->
                    <a href="<?php echo esc_url($almetal_formations_pro_url); ?>" class="mobile-btn-cta">
                        <?php esc_html_e('En savoir +', 'almetal'); ?>
                    </a>
                </div>
            </div>
        </article>

        <!-- Card Particuliers -->
        <article class="mobile-formation-card" data-href="<?php echo esc_url($almetal_formations_part_url); ?>" role="link" tabindex="0">
            <div class="mobile-formation-card-inner">
                <!-- Icône -->
                <div class="mobile-formation-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                </div>

                <!-- Contenu -->
                <div class="mobile-formation-content">
                    <h3 class="mobile-formation-title">
                        <?php esc_html_e('PARTICULIERS', 'almetal'); ?>
                    </h3>
                    
                    <p class="mobile-formation-description">
                        <?php esc_html_e('Initiations et ateliers pour les passionnés : découverte et création en toute sécurité.', 'almetal'); ?>
                    </p>

                    <!-- Points clés -->
                    <ul class="mobile-formation-features">
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
                            <span><?php esc_html_e('Projets perso', 'almetal'); ?></span>
                        </li>
                    </ul>

                    <!-- Bouton -->
                    <a href="<?php echo esc_url($almetal_formations_part_url); ?>" class="mobile-btn-cta">
                        <?php esc_html_e('En savoir +', 'almetal'); ?>
                    </a>
                </div>
            </div>
        </article>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.mobile-formation-card[data-href]').forEach(function (card) {
    card.addEventListener('click', function (e) {
      if (e.target && e.target.closest && e.target.closest('a')) {
        return;
      }
      var href = card.getAttribute('data-href');
      if (href) {
        window.location.href = href;
      }
    });

    card.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        var href = card.getAttribute('data-href');
        if (href) {
          window.location.href = href;
        }
      }
    });
  });
});
</script>
