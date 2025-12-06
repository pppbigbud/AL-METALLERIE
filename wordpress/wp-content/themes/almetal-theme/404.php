<?php
/**
 * Template pour la page 404 (Page non trouvée)
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

get_header();
?>

<div class="error-404-page">
    <div class="error-404-container">
        
        <!-- Code erreur 404 stylisé -->
        <div class="error-404-code">
            <span class="error-digit">4</span>
            <span class="error-digit error-digit-middle">
                <svg class="error-icon" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <!-- Icône de marteau de forgeron -->
                    <path d="M30,70 L40,60 L60,80 L50,90 Z" fill="currentColor"/>
                    <path d="M45,55 L55,45 L75,65 L65,75 Z" fill="currentColor"/>
                    <rect x="60" y="30" width="8" height="40" transform="rotate(45 64 50)" fill="currentColor"/>
                    <circle cx="70" cy="35" r="8" fill="currentColor"/>
                </svg>
            </span>
            <span class="error-digit">4</span>
        </div>

        <!-- Message d'erreur humoristique -->
        <h1 class="error-404-title">
            <?php esc_html_e('Cette page a été soudée au mauvais endroit !', 'almetal'); ?>
        </h1>

        <p class="error-404-description">
            <?php esc_html_e('Désolé, la page que vous cherchez semble avoir été forgée dans une autre dimension. Nos métalliers sont sur le coup pour la retrouver !', 'almetal'); ?>
        </p>

        <!-- Bouton retour à l'accueil -->
        <div class="error-404-actions">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="error-404-btn">
                <svg class="btn-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                <span><?php esc_html_e('Retour à l\'accueil', 'almetal'); ?></span>
            </a>
        </div>

        <!-- Suggestions de navigation (optionnel) -->
        <div class="error-404-suggestions">
            <p class="suggestions-title"><?php esc_html_e('Ou explorez nos services :', 'almetal'); ?></p>
            <div class="suggestions-links">
                <a href="<?php echo esc_url(home_url('/realisations')); ?>" class="suggestion-link">
                    <?php esc_html_e('Réalisations', 'almetal'); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/contact')); ?>" class="suggestion-link">
                    <?php esc_html_e('Contact', 'almetal'); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/formations')); ?>" class="suggestion-link">
                    <?php esc_html_e('Formations', 'almetal'); ?>
                </a>
            </div>
        </div>

    </div>
</div>

<?php
get_footer();
