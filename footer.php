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
        
        <!-- Chaîne des Puys - Séparateur animé -->
        <div class="footer-mountains">
            <svg viewBox="0 0 1440 200" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="mountainGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" style="stop-color:#3a3a3a;stop-opacity:1" />
                        <stop offset="40%" style="stop-color:#2a2a2a;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#1a1a1a;stop-opacity:1" />
                    </linearGradient>
                    <linearGradient id="lavaGlow" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" style="stop-color:#F08B18;stop-opacity:0.8" />
                        <stop offset="100%" style="stop-color:#F08B18;stop-opacity:0" />
                    </linearGradient>
                </defs>
                <!-- Chaîne des Puys - silhouette réaliste -->
                <path class="mountain-silhouette mountain-back" d="
                    M0,200 L0,160 
                    C40,158 60,155 90,148 
                    C110,143 130,138 160,130 
                    C180,125 200,128 230,122 
                    C250,118 270,110 300,105 
                    C320,101 340,108 370,100 
                    C390,95 410,88 440,82 
                    C460,78 480,85 510,78 
                    C530,73 550,68 580,60 
                    C600,55 610,58 630,52 
                    C650,48 660,42 680,38 
                    C695,33 705,30 720,25 
                    C735,20 745,18 760,22 
                    C775,26 790,32 810,38 
                    C830,44 850,50 880,55 
                    C910,60 930,52 960,48 
                    C980,45 1000,50 1020,46 
                    C1040,42 1055,38 1070,35 
                    C1085,32 1095,30 1110,33 
                    C1125,36 1140,42 1160,48 
                    C1180,54 1200,60 1230,68 
                    C1260,76 1290,82 1320,90 
                    C1350,98 1380,108 1410,118 
                    C1430,125 1440,130 1440,135 
                    L1440,200 Z" 
                    fill="url(#mountainGradient)" opacity="0.6"/>
                <!-- Chaîne principale avec Puy de Dôme -->
                <path class="mountain-silhouette mountain-front" d="
                    M0,200 L0,170 
                    C30,168 50,165 80,158 
                    C100,153 120,150 150,145 
                    C170,141 190,144 220,138 
                    C240,134 260,128 290,122 
                    C310,118 330,124 360,118 
                    C380,114 400,108 430,102 
                    C450,98 470,104 500,96 
                    C520,90 540,84 570,76 
                    C590,70 600,74 620,68 
                    C640,62 655,55 675,48 
                    C690,42 700,38 715,32 
                    C725,28 732,25 740,22 
                    C748,19 755,18 762,20 
                    C770,22 778,26 790,32 
                    C802,38 815,45 835,52 
                    C855,59 875,65 900,70 
                    C920,74 940,68 965,62 
                    C985,57 1005,62 1025,58 
                    C1045,54 1060,48 1080,44 
                    C1095,40 1105,38 1120,42 
                    C1135,46 1150,52 1175,60 
                    C1200,68 1225,76 1255,85 
                    C1285,94 1310,102 1340,112 
                    C1370,122 1400,132 1420,140 
                    C1435,146 1440,150 1440,155 
                    L1440,200 Z" 
                    fill="url(#mountainGradient)"/>
            </svg>

            <!-- Point d'éruption Puy de Dôme -->
            <div class="eruption-point">
                <div class="eruption-glow"></div>
                <!-- Coulées de lave -->
                <div class="lava-flows">
                    <div class="lava-flow lava-flow-1"></div>
                    <div class="lava-flow lava-flow-2"></div>
                    <div class="lava-flow lava-flow-3"></div>
                </div>
                <!-- Particules -->
                <div class="eruption-particles">
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                </div>
                <!-- Fumée -->
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
