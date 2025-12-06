<?php
/**
 * Footer Mobile Light - AL Metallerie
 * 
 * Version allégée du footer pour mobile
 * - Mentions légales
 * - Réseaux sociaux
 * - Copyright
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */
?>

<footer class="mobile-footer">
    <div class="mobile-footer-container">
        
        <!-- Réseaux sociaux -->
        <div class="mobile-footer-social">
            <p class="mobile-footer-social-title"><?php esc_html_e('Suivez-nous', 'almetal'); ?></p>
            <div class="mobile-footer-social-links">
                <a href="https://www.facebook.com/al.metallerie.soudure" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('Facebook', 'almetal'); ?>" class="mobile-social-link">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                    </svg>
                </a>
                <a href="https://www.instagram.com/al.metallerie.soudure/" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('Instagram', 'almetal'); ?>" class="mobile-social-link">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                    </svg>
                </a>
                <a href="https://www.linkedin.com/in/aur%C3%A9lien-lasteyras-184596202/" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('LinkedIn', 'almetal'); ?>" class="mobile-social-link">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                        <rect x="2" y="9" width="4" height="12"></rect>
                        <circle cx="4" cy="4" r="2"></circle>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Copyright et liens -->
        <div class="mobile-footer-copyright">
            <p>
                &copy; <?php echo date('Y'); ?> 
                <strong>AL Métallerie</strong> - 
                <?php esc_html_e('Tous droits réservés', 'almetal'); ?>
            </p>
            <div class="mobile-footer-bottom-links">
                <a href="<?php echo esc_url(home_url('/mentions-legales')); ?>"><?php esc_html_e('Mentions légales', 'almetal'); ?></a>
                <span class="mobile-footer-separator-inline">•</span>
                <a href="<?php echo esc_url(home_url('/politique-confidentialite')); ?>"><?php esc_html_e('Confidentialité', 'almetal'); ?></a>
                <span class="mobile-footer-separator-inline">•</span>
                <a href="<?php echo esc_url(home_url('/contact')); ?>"><?php esc_html_e('Contact', 'almetal'); ?></a>
            </div>
            <p class="mobile-footer-credits">
                <?php esc_html_e('Fait avec', 'almetal'); ?> 
                <span class="mobile-footer-heart">❤️</span> 
                <?php esc_html_e('à Palladuc', 'almetal'); ?>
            </p>
        </div>

    </div>
</footer>
