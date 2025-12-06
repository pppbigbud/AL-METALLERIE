<?php
/**
 * Footer du thème
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */
?>

    </main><!-- #primary -->

    <footer id="colophon" class="site-footer">
        <div class="container">
            <?php
            // Détecter si on est sur mobile
            $is_mobile = almetal_is_mobile();
            ?>
            
            <?php if (!$is_mobile) : ?>
            <!-- FOOTER DESKTOP -->
            <div class="footer-content">
                <!-- Colonne 1 : À propos -->
                <div class="footer-column footer-about">
                    <h3 class="footer-title">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                        <?php bloginfo('name'); ?>
                    </h3>
                    <p class="footer-description">
                        <?php _e('Expert en métallerie et soudure à Clermont-Ferrand. Création sur mesure de portails, garde-corps, escaliers et mobilier métallique.', 'almetal'); ?>
                    </p>
                    <div class="footer-badge">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                        </svg>
                        <?php _e('Artisan qualifié', 'almetal'); ?>
                    </div>
                </div>

                <!-- Colonne 2 : Informations de contact -->
                <div class="footer-column footer-contact">
                    <h3 class="footer-title"><?php _e('Nous contacter', 'almetal'); ?></h3>
                    
                    <div class="footer-contact-list">
                        <!-- Téléphone -->
                        <a href="tel:+33673333532" class="footer-contact-item">
                            <div class="footer-contact-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                            </div>
                            <div class="footer-contact-text">
                                <span class="footer-contact-label"><?php _e('Téléphone', 'almetal'); ?></span>
                                <span class="footer-contact-value">06 73 33 35 32</span>
                            </div>
                        </a>

                        <!-- Adresse -->
                        <a href="https://www.google.com/maps/dir/?api=1&destination=14+route+de+Maringues,+Peschadoires,+63920" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="footer-contact-item">
                            <div class="footer-contact-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                            </div>
                            <div class="footer-contact-text">
                                <span class="footer-contact-label"><?php _e('Adresse', 'almetal'); ?></span>
                                <span class="footer-contact-value">14 route de Maringues<br>63920 Peschadoires</span>
                            </div>
                        </a>

                        <!-- Horaires -->
                        <div class="footer-contact-item">
                            <div class="footer-contact-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                            </div>
                            <div class="footer-contact-text">
                                <span class="footer-contact-label"><?php _e('Horaires', 'almetal'); ?></span>
                                <span class="footer-contact-value">
                                    Lun - Ven : 8h00 - 18h00<br>
                                    Sam : Sur rendez-vous
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Colonne 3 : Réseaux sociaux -->
                <div class="footer-column footer-social">
                    <h3 class="footer-title"><?php _e('Nous suivre', 'almetal'); ?></h3>
                    <p class="footer-social-text"><?php _e('Suivez nos réalisations et actualités', 'almetal'); ?></p>
                    
                    <div class="footer-social-links">
                        <!-- Facebook -->
                        <a href="https://www.facebook.com/al.metallerie.soudure" class="footer-social-link" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>

                        <!-- Instagram -->
                        <a href="https://www.instagram.com/al.metallerie.soudure/" class="footer-social-link" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>

                        <!-- LinkedIn -->
                        <a href="https://www.linkedin.com/in/aur%C3%A9lien-lasteyras-184596202/" class="footer-social-link" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                    </div>

                    <!-- Bouton CTA -->
                    <a href="<?php echo esc_url(home_url('/contact')); ?>" class="footer-cta-btn">
                        <span class="circle" aria-hidden="true">
                            <svg class="icon arrow" width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 6H17M17 6L12 1M17 6L12 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span class="button-text"><?php _e('Demander un devis', 'almetal'); ?></span>
                    </a>
                </div>
            </div>

            <!-- Bas du footer -->
            <div class="footer-main">
                <!-- CTA Mobile uniquement (3 boutons) -->
                <div class="footer-cta-mobile">
                    <!-- Appeler -->
                    <a href="tel:+33673333532" class="footer-cta-mobile-btn">
                        <span class="circle" aria-hidden="true">
                            <svg class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" stroke="white" stroke-width="2"/>
                            </svg>
                        </span>
                        <span class="button-text"><?php _e('Appeler', 'almetal'); ?></span>
                    </a>
                    
                    <!-- Nous rejoindre (Maps/Waze) -->
                    <a href="https://www.google.com/maps/dir/?api=1&destination=14+route+de+Maringues,+Peschadoires,+63920" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="footer-cta-mobile-btn">
                        <span class="circle" aria-hidden="true">
                            <svg class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" stroke="white" stroke-width="2"/>
                                <circle cx="12" cy="10" r="3" stroke="white" stroke-width="2"/>
                            </svg>
                        </span>
                        <span class="button-text"><?php _e('Nous rejoindre', 'almetal'); ?></span>
                    </a>
                    
                    <!-- Demande de devis -->
                    <a href="<?php echo esc_url(home_url('/contact')); ?>" class="footer-cta-mobile-btn">
                        <span class="circle" aria-hidden="true">
                            <svg class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="white" stroke-width="2"/>
                                <polyline points="14 2 14 8 20 8" stroke="white" stroke-width="2"/>
                                <line x1="16" y1="13" x2="8" y2="13" stroke="white" stroke-width="2"/>
                                <line x1="16" y1="17" x2="8" y2="17" stroke="white" stroke-width="2"/>
                                <polyline points="10 9 9 9 8 9" stroke="white" stroke-width="2"/>
                            </svg>
                        </span>
                        <span class="button-text"><?php _e('Demande de devis', 'almetal'); ?></span>
                    </a>
                </div>
                
                <div class="footer-columns">
                    <p class="footer-copyright">
                        &copy; <?php echo date('Y'); ?> <strong><?php bloginfo('name'); ?></strong>. 
                        <?php _e('Tous droits réservés.', 'almetal'); ?>
                    </p>
                    <div class="footer-bottom-links">
                        <a href="<?php echo esc_url(home_url('/mentions-legales')); ?>"><?php _e('Mentions légales', 'almetal'); ?></a>
                        <span class="separator">|</span>
                        <a href="<?php echo esc_url(home_url('/politique-confidentialite')); ?>"><?php _e('Politique de confidentialité', 'almetal'); ?></a>
                    </div>
                </div>
            </div>
            
            <?php else : ?>
            <!-- FOOTER MOBILE -->
            <div class="footer-mobile">
                <!-- 3 CTA Principaux -->
                <div class="footer-mobile-cta">
                    <!-- Appeler -->
                    <a href="tel:+33673333532" class="footer-cta-mobile-btn">
                        <span class="circle" aria-hidden="true">
                            <svg class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" stroke="white" stroke-width="2"/>
                            </svg>
                        </span>
                        <span class="button-text"><?php _e('Appeler', 'almetal'); ?></span>
                    </a>
                    
                    <!-- Nous rejoindre (Maps/Waze) -->
                    <a href="https://www.google.com/maps/dir/?api=1&destination=14+route+de+Maringues,+Peschadoires,+63920" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="footer-cta-mobile-btn">
                        <span class="circle" aria-hidden="true">
                            <svg class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" stroke="white" stroke-width="2"/>
                                <circle cx="12" cy="10" r="3" stroke="white" stroke-width="2"/>
                            </svg>
                        </span>
                        <span class="button-text"><?php _e('Nous rejoindre', 'almetal'); ?></span>
                    </a>
                    
                    <!-- Demande de devis -->
                    <a href="<?php echo esc_url(home_url('/contact')); ?>" class="footer-cta-mobile-btn">
                        <span class="circle" aria-hidden="true">
                            <svg class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="white" stroke-width="2"/>
                                <polyline points="14 2 14 8 20 8" stroke="white" stroke-width="2"/>
                                <line x1="16" y1="13" x2="8" y2="13" stroke="white" stroke-width="2"/>
                                <line x1="16" y1="17" x2="8" y2="17" stroke="white" stroke-width="2"/>
                            </svg>
                        </span>
                        <span class="button-text"><?php _e('Demande de devis', 'almetal'); ?></span>
                    </a>
                </div>
                
                <!-- Mentions légales avec réseaux sociaux -->
                <div class="footer-mobile-bottom">
                    <div class="footer-mobile-social">
                        <!-- Facebook -->
                        <a href="https://www.facebook.com/al.metallerie.soudure" class="footer-mobile-social-link" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        
                        <!-- Instagram -->
                        <a href="https://www.instagram.com/al.metallerie.soudure/" class="footer-mobile-social-link" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        
                        <!-- LinkedIn -->
                        <a href="https://www.linkedin.com/in/aur%C3%A9lien-lasteyras-184596202/" class="footer-mobile-social-link" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                    </div>
                    
                    <p class="footer-mobile-copyright">
                        &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>
                    </p>
                    
                    <div class="footer-mobile-links">
                        <a href="<?php echo esc_url(home_url('/mentions-legales')); ?>"><?php _e('Mentions légales', 'almetal'); ?></a>
                        <span>•</span>
                        <a href="<?php echo esc_url(home_url('/politique-confidentialite')); ?>"><?php _e('Confidentialité', 'almetal'); ?></a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
