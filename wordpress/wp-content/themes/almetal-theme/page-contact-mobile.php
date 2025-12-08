<?php
/**
 * Template Name: Contact Mobile
 * Template pour la page Contact - VERSION MOBILE
 * 
 * Formulaire de contact identique à la version desktop
 * Intégration avec le système d'opt-ins Analytics
 * 
 * @package AL-Metallerie Soudure
 * @since 1.0.0
 */

get_header();

// Enqueue jQuery si pas déjà chargé
wp_enqueue_script('jquery');
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
            <?php esc_html_e('Expert en Métallerie, Ferronerie, Serrurie à Thiers', 'almetal'); ?>
        </p>

        <!-- Boutons d'action rapide -->
        <div class="mobile-quick-actions scroll-fade scroll-delay-1">
            <a href="tel:+33673333532" class="mobile-quick-action-btn mobile-quick-action-call">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                </svg>
                <?php esc_html_e('Appeler', 'almetal'); ?>
            </a>
            <a href="https://www.google.com/maps/dir/?api=1&destination=14+route+de+Maringues,+Peschadoires,+63920" 
               target="_blank" 
               rel="noopener noreferrer"
               class="mobile-quick-action-btn mobile-quick-action-directions">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="3 11 22 2 13 21 11 13 3 11"/>
                </svg>
                <?php esc_html_e('Itinéraire', 'almetal'); ?>
            </a>
        </div>

        <!-- Informations de contact -->
        <div class="mobile-contact-info-grid">
            <!-- Téléphone -->
            <a href="tel:+33673333532" class="mobile-contact-page-card scroll-fade scroll-delay-1">
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
            <a href="mailto:aurelien@al-metallerie.fr" class="mobile-contact-page-card scroll-fade scroll-delay-2">
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
            <a href="https://www.google.com/maps/dir/?api=1&destination=14+route+de+Maringues,+Peschadoires,+63920" 
               target="_blank" 
               rel="noopener noreferrer" 
               class="mobile-contact-page-card scroll-fade scroll-delay-3">
                <div class="mobile-contact-page-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                </div>
                <div class="mobile-contact-page-content">
                    <h3><?php esc_html_e('Adresse', 'almetal'); ?></h3>
                    <p>14 route de Maringues<br>63920 Peschadoires</p>
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
                    <p>Lun - Ven : 8h00 - 18h00<br>Sam : Sur rendez-vous</p>
                </div>
            </div>
        </div>

        <!-- Formulaire de devis - IDENTIQUE AU DESKTOP -->
        <div class="mobile-contact-form-section scroll-fade">
            <div class="mobile-contact-form-header">
                <svg class="mobile-contact-form-icon" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                </svg>
                <h2 class="mobile-contact-form-title">
                    <?php esc_html_e('Demande de devis', 'almetal'); ?>
                </h2>
            </div>
            <p class="mobile-contact-form-subtitle">
                <?php esc_html_e('Décrivez-nous votre projet', 'almetal'); ?>
            </p>
            
            <!-- Formulaire identique au desktop avec action vers admin-post.php -->
            <form id="contact-form" class="mobile-contact-form contact-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php wp_nonce_field('almetal_contact_form', 'contact_nonce'); ?>
                <input type="hidden" name="action" value="almetal_contact_form">
                <input type="hidden" name="form_source" value="mobile_contact">

                <div class="form-row mobile-form-row">
                    <div class="form-group mobile-form-group">
                        <label for="contact-name"><?php _e('Nom complet', 'almetal'); ?> *</label>
                        <input type="text" id="contact-name" name="contact_name" required>
                    </div>

                    <div class="form-group mobile-form-group">
                        <label for="contact-phone"><?php _e('Téléphone', 'almetal'); ?> *</label>
                        <input type="tel" id="contact-phone" name="contact_phone" required>
                    </div>
                </div>

                <div class="form-group mobile-form-group">
                    <label for="contact-email"><?php _e('Email', 'almetal'); ?> *</label>
                    <input type="email" id="contact-email" name="contact_email" required>
                </div>

                <div class="form-group mobile-form-group">
                    <label for="contact-project"><?php _e('Type de projet', 'almetal'); ?> *</label>
                    <select id="contact-project" name="contact_project" required>
                        <option value=""><?php _e('Sélectionnez un type', 'almetal'); ?></option>
                        <option value="portail"><?php _e('Portail', 'almetal'); ?></option>
                        <option value="garde-corps"><?php _e('Garde-corps', 'almetal'); ?></option>
                        <option value="escalier"><?php _e('Escalier', 'almetal'); ?></option>
                        <option value="pergola"><?php _e('Pergola', 'almetal'); ?></option>
                        <option value="verriere"><?php _e('Verrière', 'almetal'); ?></option>
                        <option value="mobilier"><?php _e('Mobilier métallique', 'almetal'); ?></option>
                        <option value="reparation"><?php _e('Réparation', 'almetal'); ?></option>
                        <option value="formation"><?php _e('Formation', 'almetal'); ?></option>
                        <option value="autre"><?php _e('Autre', 'almetal'); ?></option>
                    </select>
                </div>

                <div class="form-group mobile-form-group">
                    <label for="contact-message"><?php _e('Votre message', 'almetal'); ?> *</label>
                    <textarea id="contact-message" name="contact_message" rows="5" required></textarea>
                </div>

                <!-- Opt-ins pour le marketing -->
                <div class="form-optins mobile-form-optins">
                    <div class="optin-group mobile-optin-group">
                        <label class="optin-label mobile-optin-label">
                            <input type="checkbox" name="consent_newsletter" value="1">
                            <span class="optin-checkbox mobile-optin-checkbox"></span>
                            <span class="optin-text mobile-optin-text"><?php _e('Je souhaite recevoir les actualités et offres d\'AL Métallerie', 'almetal'); ?></span>
                        </label>
                    </div>
                    <div class="optin-group mobile-optin-group">
                        <label class="optin-label mobile-optin-label">
                            <input type="checkbox" name="consent_marketing" value="1">
                            <span class="optin-checkbox mobile-optin-checkbox"></span>
                            <span class="optin-text mobile-optin-text"><?php _e('J\'accepte d\'être recontacté pour des offres personnalisées', 'almetal'); ?></span>
                        </label>
                    </div>
                </div>

                <input type="hidden" name="contact_consent" value="1">

                <button type="submit" class="contact-submit-btn mobile-contact-submit-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="22" y1="2" x2="11" y2="13"/>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                    </svg>
                    <?php _e('Envoyer ma demande', 'almetal'); ?>
                </button>
                
                <p class="form-consent-text mobile-form-consent-text">
                    <?php _e('En cliquant sur "Envoyer ma demande", vous acceptez que vos données soient utilisées pour vous recontacter.', 'almetal'); ?>
                    <a href="<?php echo esc_url(home_url('/politique-confidentialite')); ?>"><?php _e('Politique de confidentialité', 'almetal'); ?></a>
                </p>

                <div class="form-message mobile-form-message" style="display: none;"></div>
            </form>
        </div>

    </div>
</main>

<!-- Script AJAX identique au desktop -->
<script>
(function($) {
    'use strict';
    
    $(document).ready(function() {
        var form = $('#contact-form');
        var messageDiv = $('.form-message');
        
        if (!form.length) {
            return;
        }
        
        form.on('submit', function(e) {
            e.preventDefault();
            
            // Désactiver le bouton de soumission
            var submitBtn = form.find('button[type="submit"]');
            var originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<span>Envoi en cours...</span>');
            
            // Récupérer les données du formulaire
            var formData = new FormData(this);
            
            // Envoyer via AJAX vers admin-ajax.php
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Afficher le message de succès
                        messageDiv
                            .removeClass('error mobile-form-error')
                            .addClass('success mobile-form-success')
                            .html('✓ Votre message a été envoyé avec succès ! Nous vous recontacterons rapidement.')
                            .fadeIn();
                        
                        // Réinitialiser le formulaire
                        form[0].reset();
                    } else {
                        // Afficher le message d'erreur
                        messageDiv
                            .removeClass('success mobile-form-success')
                            .addClass('error mobile-form-error')
                            .html('✗ ' + (response.data?.message || 'Une erreur est survenue.'))
                            .fadeIn();
                    }
                    
                    // Faire défiler vers le message
                    $('html, body').animate({
                        scrollTop: messageDiv.offset().top - 100
                    }, 500);
                },
                error: function(xhr, status, error) {
                    // Afficher le message d'erreur
                    messageDiv
                        .removeClass('success mobile-form-success')
                        .addClass('error mobile-form-error')
                        .html('✗ Une erreur est survenue. Veuillez réessayer ou nous contacter directement par téléphone.')
                        .fadeIn();
                },
                complete: function() {
                    // Réactiver le bouton
                    submitBtn.prop('disabled', false).html(originalText);
                    
                    // Masquer le message après 8 secondes
                    setTimeout(function() {
                        messageDiv.fadeOut();
                    }, 8000);
                }
            });
        });
        
        // Validation en temps réel
        form.find('input[required], textarea[required], select[required]').on('blur', function() {
            var field = $(this);
            if (!field.val()) {
                field.css('border-color', '#f44336');
            } else {
                field.css('border-color', 'rgba(255, 255, 255, 0.1)');
            }
        });
        
        // Validation de l'email
        form.find('input[type="email"]').on('blur', function() {
            var email = $(this).val();
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email && !emailRegex.test(email)) {
                $(this).css('border-color', '#f44336');
            } else {
                $(this).css('border-color', 'rgba(255, 255, 255, 0.1)');
            }
        });
        
        // Validation du téléphone
        form.find('input[type="tel"]').on('blur', function() {
            var phone = $(this).val();
            var phoneRegex = /^[0-9\s\-\+\(\)]{10,}$/;
            if (phone && !phoneRegex.test(phone)) {
                $(this).css('border-color', '#f44336');
            } else {
                $(this).css('border-color', 'rgba(255, 255, 255, 0.1)');
            }
        });
    });
})(jQuery);
</script>

<?php get_footer(); ?>
