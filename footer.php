<?php
/**
 * Nouveau Footer avec style contact-info-card
 * 
 * @package AL-Metallerie Soudure
 * @since 1.0.0
 */
?>

    </main><!-- #primary -->

    <?php if (almetal_is_mobile()) : ?>
        <!-- Footer Mobile Light -->
        <?php get_template_part('template-parts/footer', 'mobile'); ?>
    <?php else : ?>
        <!-- Footer Desktop -->
        <footer id="colophon" class="site-footer-new">
        
        <!-- Montagnes d'Auvergne avec animation d'eruption -->
        <div class="footer-mountains">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <?php if (is_page_template('page-contact.php')) : ?>
                    <!-- SVG transparent pour la page contact -->
                    <path class="mountain-silhouette" fill="transparent" d="
                        M0,120 L0,85
                        
                        C20,84 40,82 60,80
                        C80,78 100,76 120,73
                        L140,70
                        C155,68 170,65 185,63
                        L200,60
                        C215,58 230,56 245,55
                        L260,54
                        
                        C275,52 290,49 305,46
                        L320,42
                        C335,38 350,33 365,28
                        L380,22
                        C390,18 400,14 410,11
                        L425,8
                        L440,6
                        L455,5
                        L465,3
                        L475,2
                        L485,3
                        L495,5
                        L505,7
                        L515,9
                        L525,11
                        L535,14
                        L545,17
                        C555,20 565,24 575,28
                        L590,33
                        C600,37 610,41 620,44
                        L635,48
                        C650,51 665,54 680,56
                        L695,58
                        C710,59 725,60 740,60
                        L755,60
                        C770,59 785,58 800,56
                        L815,54
                        C825,52 835,50 845,48
                        L860,45
                        C875,42 890,39 905,36
                        L920,33
                        C935,30 950,27 965,25
                        L980,23
                        C990,21 1000,20 1010,19
                        L1025,18
                        L1040,17
                        L1055,16
                        L1070,16
                        L1085,17
                        L1100,18
                        C1115,20 1130,22 1145,25
                        L1160,28
                        C1170,31 1180,34 1190,38
                        L1200,42
                        L1200,120 Z
                    "/>
                <?php else : ?>
                    <!-- SVG normal pour les autres pages -->
                    <defs>
                        <linearGradient id="mountainGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" style="stop-color:#4a4a4a;stop-opacity:1" />
                            <stop offset="50%" style="stop-color:#2a2a2a;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#1a1a1a;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    
                    <path class="mountain-silhouette" fill="url(#mountainGradient)" d="
                        M0,120 L0,85
                        
                        C20,84 40,82 60,80
                        C80,78 100,76 120,73
                        L140,70
                        C155,68 170,65 185,63
                        L200,60
                        C215,58 230,56 245,55
                        L260,54
                        
                        C275,52 290,49 305,46
                        L320,42
                        C335,38 350,33 365,28
                        L380,22
                        C390,18 400,14 410,11
                        L425,8
                        L440,6
                        L455,5
                        L465,3
                        L475,2
                        L485,3
                        L495,5
                        L505,7
                        L515,9
                        L525,11
                        L535,14
                        L545,17
                        C555,20 565,24 575,28
                        L590,33
                        C600,37 610,41 620,44
                        L635,48
                        C650,51 665,54 680,56
                        L695,58
                        C710,59 725,60 740,60
                        L755,60
                        C770,59 785,58 800,56
                        L815,54
                        C825,52 835,50 845,48
                        L860,45
                        C875,42 890,39 905,36
                        L920,33
                        C935,30 950,27 965,25
                        L980,23
                        C990,21 1000,20 1010,19
                        L1025,18
                        L1040,17
                        L1055,16
                        L1070,16
                        L1085,17
                        L1100,18
                        C1115,20 1130,22 1145,25
                        L1160,28
                        C1170,31 1180,34 1190,38
                        L1200,42
                        L1200,120 Z
                    "/>
                <?php endif; ?>
                    C870,43 880,41 890,40
                    L905,38
                    L920,37
                    L935,36
                    L950,35
                    L960,34
                    L968,33
                    L973,32
                    L978,33
                    L983,34
                    L988,35
                    L993,36
                    L998,37
                    L1003,38
                    C1013,40 1023,43 1033,46
                    L1048,51
                    C1063,56 1078,62 1093,68
                    L1108,75
                    C1123,82 1138,90 1153,98
                    L1168,107
                    L1180,115
                    L1200,120
                    L1200,120 Z
                " />
            </svg>
            
            <!-- Point d'éruption au sommet (Puy de Dôme) -->
            <div class="eruption-point">
                <div class="eruption-glow"></div>
                <div class="eruption-particles">
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                </div>
                <div class="eruption-smoke">
                    <div class="smoke-puff"></div>
                    <div class="smoke-puff"></div>
                    <div class="smoke-puff"></div>
                </div>
            </div>
        </div>
        
        <div class="container">
            <!-- FOOTER DESKTOP -->
            
            <!-- Séparateur décoratif -->
            <!-- <div class="footer-separator"></div> -->
            
            <div class="footer-new-content">
                
                <!-- Carte 1 : Contact -->
                <div class="footer-card footer-card-contact">
                    <div class="footer-card-header">
                        <div class="footer-card-icon-main">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            </svg>
                        </div>
                        <h3 class="footer-card-title"><?php _e('Contact', 'almetal'); ?></h3>
                    </div>
                    
                    <div class="footer-card-items">
                        <!-- Téléphone -->
                        <a href="tel:+33673333532" class="footer-card-item footer-phone">
                            <div class="footer-card-item-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                            </div>
                            <div class="footer-card-item-content">
                                <span class="footer-card-item-label"><?php _e('Téléphone', 'almetal'); ?></span>
                                <span class="footer-card-item-value">06 73 33 35 32</span>
                            </div>
                        </a>

                        <!-- Email -->
                        <a href="mailto:contact@al-metallerie.fr" class="footer-card-item footer-email">
                            <div class="footer-card-item-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                            </div>
                            <div class="footer-card-item-content">
                                <span class="footer-card-item-label"><?php _e('Email', 'almetal'); ?></span>
                                <span class="footer-card-item-value">contact@al-metallerie.fr</span>
                            </div>
                        </a>

                        <!-- Adresse -->
                        <a href="https://www.google.com/maps/dir/?api=1&destination=14+route+de+Maringues,+Peschadoires,+63920" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="footer-card-item footer-address">
                            <div class="footer-card-item-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                            </div>
                            <div class="footer-card-item-content">
                                <span class="footer-card-item-label"><?php _e('Adresse', 'almetal'); ?></span>
                                <span class="footer-card-item-value">14 route de Maringues<br>63920 Peschadoires</span>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Carte 2 : Horaires & Infos -->
                <div class="footer-card footer-card-hours">
                    <div class="footer-card-header">
                        <div class="footer-card-icon-main">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                        <h3 class="footer-card-title"><?php _e('Horaires', 'almetal'); ?></h3>
                    </div>
                    
                    <div class="footer-card-items">
                        <!-- Horaires semaine -->
                        <div class="footer-card-item footer-hours">
                            <div class="footer-card-item-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                            </div>
                            <div class="footer-card-item-content">
                                <span class="footer-card-item-label"><?php _e('Lundi - Vendredi', 'almetal'); ?></span>
                                <span class="footer-card-item-value">8h00 - 18h00</span>
                            </div>
                        </div>

                        <!-- Weekend -->
                        <div class="footer-card-item footer-hours">
                            <div class="footer-card-item-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </div>
                            <div class="footer-card-item-content">
                                <span class="footer-card-item-label"><?php _e('Samedi', 'almetal'); ?></span>
                                <span class="footer-card-item-value">Sur rendez-vous</span>
                            </div>
                        </div>

                        <!-- Badge artisan -->
                        <div class="footer-badge-item">
                            <div class="footer-badge">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                                </svg>
                                <span><?php _e('Artisan qualifié', 'almetal'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carte 3 : Réseaux & CTA -->
                <div class="footer-card footer-card-social">
                    <div class="footer-card-header">
                        <div class="footer-card-icon-main">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="18" cy="5" r="3"/>
                                <circle cx="6" cy="12" r="3"/>
                                <circle cx="18" cy="19" r="3"/>
                                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
                                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                            </svg>
                        </div>
                        <h3 class="footer-card-title"><?php _e('Suivez-nous', 'almetal'); ?></h3>
                    </div>
                    
                    <p class="footer-social-desc"><?php _e('Découvrez nos dernières réalisations', 'almetal'); ?></p>
                    
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
                            <svg class="icon arrow" width="18" height="12" viewBox="0 0 18 12" fill="none">
                                <path d="M1 6H17M17 6L12 1M17 6L12 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span class="button-text"><?php _e('Demander un devis', 'almetal'); ?></span>
                    </a>
                    
                    <!-- Avis Google Business -->
                    <?php echo almetal_render_google_reviews_widget(); ?>
                </div>
            </div>

            <!-- Bas du footer -->
            <div class="footer-new-bottom">
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
        </footer><!-- #colophon -->
    <?php endif; ?>

</div><!-- #page -->

<?php 
/**
 * Bannière de consentement aux cookies
 * Injectée via JavaScript pour une meilleure performance
 */
?>

<?php wp_footer(); ?>

</body>
</html>
