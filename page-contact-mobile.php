<?php
/**
 * Template Name: Contact Mobile
 * Template pour la page Contact - VERSION MOBILE
 * 
 * Affiche le formulaire de contact complet + Google Maps
 * Header avec bouton retour vers l'accueil
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

get_header();
?>

<!-- Header Mobile avec bouton RETOUR -->
<?php get_template_part('template-parts/header', 'mobile'); ?>

<main class="mobile-page-contact">
    
    <div class="mobile-page-container">
        
        <!-- Tag -->
        <div class="mobile-contact-page-tag scroll-zoom">
            <span><?php esc_html_e('Nous Contacter', 'almetal'); ?></span>
        </div>

        <!-- Titre de la page -->
        <h1 class="mobile-page-title scroll-fade scroll-delay-1">
            <?php esc_html_e('CONTACTEZ-NOUS', 'almetal'); ?>
        </h1>
        <p class="mobile-page-subtitle scroll-fade scroll-delay-2">
            <?php esc_html_e('Une question ? Un projet ? N\'hésitez pas à nous contacter', 'almetal'); ?>
        </p>

        <!-- Informations de contact -->
        <div class="mobile-contact-info-grid">
            <!-- Téléphone -->
            <a href="tel:0673333532" class="mobile-contact-page-card scroll-fade scroll-delay-1">
                <div class="mobile-contact-page-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                </div>
                <div class="mobile-contact-page-content">
                    <h3><?php esc_html_e('Téléphone', 'almetal'); ?></h3>
                    <p>06 73 33 35 32</p>
                </div>
            </a>

            <!-- Email -->
            <a href="mailto:contact@al-metallerie.fr" class="mobile-contact-page-card scroll-fade scroll-delay-2">
                <div class="mobile-contact-page-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                </div>
                <div class="mobile-contact-page-content">
                    <h3><?php esc_html_e('Email', 'almetal'); ?></h3>
                    <p>aurelien@al-metallerie.fr</p>
                </div>
            </a>

            <!-- Adresse -->
            <a href="https://maps.google.com/?q=Thiers" target="_blank" rel="noopener" class="mobile-contact-page-card scroll-fade scroll-delay-3">
                <div class="mobile-contact-page-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                </div>
                <div class="mobile-contact-page-content">
                    <h3><?php esc_html_e('Adresse', 'almetal'); ?></h3>
                    <p>Thiers, France</p>
                </div>
            </a>

            <!-- Horaires -->
            <div class="mobile-contact-page-card scroll-fade scroll-delay-4">
                <div class="mobile-contact-page-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <div class="mobile-contact-page-content">
                    <h3><?php esc_html_e('Horaires', 'almetal'); ?></h3>
                    <p><?php esc_html_e('Lun - Sam : 8h - 18h', 'almetal'); ?></p>
                    <p><?php esc_html_e('Sam : sur RDV', 'almetal'); ?></p>
                </div>
            </div>
        </div>

        <!-- Formulaire de contact -->
        <div class="mobile-contact-form-section scroll-fade">
            <h2 class="mobile-contact-form-title scroll-fade scroll-delay-1">
                <?php esc_html_e('Envoyez-nous un message', 'almetal'); ?>
            </h2>
            <p class="mobile-contact-form-subtitle scroll-fade scroll-delay-2">
                <?php esc_html_e('Remplissez le formulaire ci-dessous et nous vous répondrons dans les plus brefs délais', 'almetal'); ?>
            </p>
            
            <?php
            // Afficher le formulaire Contact Form 7 si disponible
            if (function_exists('wpcf7_contact_form')) {
                echo do_shortcode('[contact-form-7 id="1" title="Contact form 1"]');
            } else {
                // Formulaire HTML de secours
                ?>
                <form class="mobile-contact-form scroll-fade scroll-delay-3" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                    <input type="hidden" name="action" value="submit_contact_form">
                    <?php wp_nonce_field('contact_form_nonce', 'contact_nonce'); ?>
                    
                    <div class="mobile-form-group">
                        <label for="contact-name"><?php esc_html_e('Nom complet', 'almetal'); ?> *</label>
                        <input type="text" id="contact-name" name="contact_name" required>
                    </div>
                    
                    <div class="mobile-form-group">
                        <label for="contact-email"><?php esc_html_e('Email', 'almetal'); ?> *</label>
                        <input type="email" id="contact-email" name="contact_email" required>
                    </div>
                    
                    <div class="mobile-form-group">
                        <label for="contact-phone"><?php esc_html_e('Téléphone', 'almetal'); ?></label>
                        <input type="tel" id="contact-phone" name="contact_phone">
                    </div>
                    
                    <div class="mobile-form-group">
                        <label for="contact-subject"><?php esc_html_e('Sujet', 'almetal'); ?> *</label>
                        <input type="text" id="contact-subject" name="contact_subject" required>
                    </div>
                    
                    <div class="mobile-form-group">
                        <label for="contact-message"><?php esc_html_e('Message', 'almetal'); ?> *</label>
                        <textarea id="contact-message" name="contact_message" rows="6" required></textarea>
                    </div>
                    
                    <button type="submit" class="mobile-btn-cta mobile-btn-cta--large scroll-zoom">
                        <?php esc_html_e('Envoyer le message', 'almetal'); ?>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                    </button>
                </form>
                <?php
            }
            ?>
        </div>

    </div>
</main>

<?php get_footer(); ?>
