<?php
/**
 * Header Mobile - AL Metallerie
 * 
 * 2 variantes :
 * - Menu burger (one-page accueil)
 * - Bouton retour (pages détaillées)
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

// Détecter si on est sur la page d'accueil ou une page détaillée
$is_home = is_front_page();
$header_class = $is_home ? 'mobile-header--burger' : 'mobile-header--back';
?>

<header class="mobile-header <?php echo esc_attr($header_class); ?>" id="mobile-header">
    <div class="mobile-header-inner">
        
        <?php if (!$is_home) : ?>
            <!-- Bouton RETOUR (pages détaillées) -->
            <a href="<?php echo esc_url(home_url('/')); ?>" class="mobile-back-btn" aria-label="<?php esc_attr_e('Retour à l\'accueil', 'almetal'); ?>">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span><?php esc_html_e('Retour', 'almetal'); ?></span>
            </a>
        <?php endif; ?>
        
        <!-- Logo centré -->
        <div class="mobile-logo">
            <a href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('AL Metallerie - Accueil', 'almetal'); ?>">
                <?php
                if (has_custom_logo()) {
                    the_custom_logo();
                } else {
                    $logo_path = get_template_directory_uri() . '/assets/images/logo.png';
                    ?>
                    <img src="<?php echo esc_url($logo_path); ?>" 
                         alt="<?php bloginfo('name'); ?> - Logo" 
                         class="mobile-logo-img"
                         width="120"
                         height="40">
                    <?php
                }
                ?>
            </a>
        </div>
        
        <?php if ($is_home) : ?>
            <!-- Menu BURGER (one-page accueil) -->
            <button class="mobile-burger-btn" id="mobile-burger-btn" aria-label="<?php esc_attr_e('Menu', 'almetal'); ?>" aria-expanded="false" aria-controls="mobile-menu">
                <span class="mobile-burger-line"></span>
                <span class="mobile-burger-line"></span>
                <span class="mobile-burger-line"></span>
            </button>
        <?php else : ?>
            <!-- Espace vide pour équilibrer le layout -->
            <div class="mobile-header-spacer"></div>
        <?php endif; ?>
        
    </div>
</header>

<?php if ($is_home) : ?>
    <!-- Overlay pour fermer le menu -->
    <div class="mobile-menu-overlay" id="mobile-menu-overlay"></div>
    
    <!-- Menu de navigation (one-page uniquement) -->
    <nav class="mobile-menu" id="mobile-menu" aria-label="<?php esc_attr_e('Navigation mobile', 'almetal'); ?>">
        <div class="mobile-menu-content">
            <ul class="mobile-menu-list">
                <li class="mobile-menu-item">
                    <a href="#accueil" class="mobile-menu-link">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span><?php esc_html_e('Accueil', 'almetal'); ?></span>
                    </a>
                </li>
                <li class="mobile-menu-item">
                    <a href="#presentation" class="mobile-menu-link">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span><?php esc_html_e('Présentation', 'almetal'); ?></span>
                    </a>
                </li>
                <li class="mobile-menu-item">
                    <a href="#actualites" class="mobile-menu-link">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="3" y1="9" x2="21" y2="9"></line>
                            <line x1="9" y1="21" x2="9" y2="9"></line>
                        </svg>
                        <span><?php esc_html_e('Actualités', 'almetal'); ?></span>
                    </a>
                </li>
                <li class="mobile-menu-item">
                    <a href="#formations" class="mobile-menu-link">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                        </svg>
                        <span><?php esc_html_e('Formations', 'almetal'); ?></span>
                    </a>
                </li>
                <li class="mobile-menu-item">
                    <a href="#contact" class="mobile-menu-link">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span><?php esc_html_e('Contact', 'almetal'); ?></span>
                    </a>
                </li>
            </ul>
            
            <!-- Informations de contact dans le menu -->
            <div class="mobile-menu-footer">
                <a href="tel:0473148888" class="mobile-menu-contact">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                    <span>04 73 14 88 88</span>
                </a>
            </div>
        </div>
    </nav>
<?php endif; ?>
