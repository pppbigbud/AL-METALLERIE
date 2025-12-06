<?php
/**
 * Template Name: Page En Construction
 * Template pour les pages en cours de développement
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

get_header();
?>

<div class="under-construction-page">
    <div class="under-construction-container">
        
        <!-- Icône En Construction -->
        <div class="under-construction-icon">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <!-- Roue crantée / Engrenage -->
                <g transform="translate(100, 100)">
                    <!-- Dents de l'engrenage (12 dents) -->
                    <g fill="currentColor">
                        <rect x="-8" y="-70" width="16" height="20" rx="2"/>
                        <rect x="-8" y="50" width="16" height="20" rx="2"/>
                        <rect x="-70" y="-8" width="20" height="16" rx="2"/>
                        <rect x="50" y="-8" width="20" height="16" rx="2"/>
                        
                        <g transform="rotate(45)">
                            <rect x="-8" y="-70" width="16" height="20" rx="2"/>
                            <rect x="-8" y="50" width="16" height="20" rx="2"/>
                            <rect x="-70" y="-8" width="20" height="16" rx="2"/>
                            <rect x="50" y="-8" width="20" height="16" rx="2"/>
                        </g>
                        
                        <g transform="rotate(22.5)">
                            <rect x="-8" y="-70" width="16" height="20" rx="2"/>
                            <rect x="-8" y="50" width="16" height="20" rx="2"/>
                            <rect x="-70" y="-8" width="20" height="16" rx="2"/>
                            <rect x="50" y="-8" width="20" height="16" rx="2"/>
                        </g>
                        
                        <g transform="rotate(67.5)">
                            <rect x="-8" y="-70" width="16" height="20" rx="2"/>
                            <rect x="-8" y="50" width="16" height="20" rx="2"/>
                            <rect x="-70" y="-8" width="20" height="16" rx="2"/>
                            <rect x="50" y="-8" width="20" height="16" rx="2"/>
                        </g>
                    </g>
                    
                    <!-- Corps principal de l'engrenage -->
                    <circle cx="0" cy="0" r="50" fill="currentColor"/>
                    
                    <!-- Trou central -->
                    <circle cx="0" cy="0" r="20" fill="#222222"/>
                    
                    <!-- Cercle intérieur décoratif -->
                    <circle cx="0" cy="0" r="15" fill="none" stroke="currentColor" stroke-width="3"/>
                    
                    <!-- Ombre portée -->
                    <ellipse cx="5" cy="80" rx="55" ry="12" fill="currentColor" opacity="0.2"/>
                </g>
            </svg>
        </div>

        <!-- Titre -->
        <h1 class="under-construction-title">
            <?php esc_html_e('Cette page est encore à l\'atelier !', 'almetal'); ?>
        </h1>

        <!-- Message -->
        <p class="under-construction-description">
            <?php esc_html_e('Nos métalliers travaillent d\'arrache-pied pour vous proposer un contenu de qualité. Cette page sera bientôt disponible.', 'almetal'); ?>
        </p>

        <!-- Bouton retour à l'accueil -->
        <div class="under-construction-actions">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="under-construction-btn">
                <svg class="btn-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                <span><?php esc_html_e('Retour à l\'accueil', 'almetal'); ?></span>
            </a>
        </div>

    </div>
</div>

<?php
get_footer();
