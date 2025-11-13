<?php
/**
 * Section CTA (Call-to-Action) - Page d'accueil
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */
?>

<section class="cta-section" id="cta">
    <div class="cta-container">
        <div class="cta-content">
            <h2 class="cta-title">
                <?php esc_html_e('VOUS ÊTES À LA RECHERCHE D\'UN TRAVAIL DE QUALITÉ ?', 'almetal'); ?>
            </h2>
        </div>
        <div class="cta-button-wrapper">
            <a href="#contact" class="cta-button">
                <span class="cta-button__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                </span>
                <span class="cta-button__text"><?php esc_html_e('CONTACTEZ-MOI', 'almetal'); ?></span>
            </a>
        </div>
    </div>
</section>
