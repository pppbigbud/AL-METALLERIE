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
            <a href="tel:+33673333532" class="mobile-contact-page-card scroll-fade scroll-delay-1" data-track="Contact|phone_click|mobile">
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
            <a href="mailto:aurelien@al-metallerie.fr" class="mobile-contact-page-card scroll-fade scroll-delay-2" data-track="Contact|email_click|mobile">
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
               class="mobile-contact-page-card scroll-fade scroll-delay-3"
               data-track="Contact|directions_click|mobile">
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

        <!-- Formulaire de devis -->
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
            
            <form id="mobile-contact-form" class="mobile-contact-form scroll-fade scroll-delay-3" method="post">
                <?php wp_nonce_field('almetal_contact_form', 'contact_nonce'); ?>
                <input type="hidden" name="action" value="almetal_contact_form">
                <input type="hidden" name="form_source" value="mobile_contact">

                <div class="mobile-form-row">
                    <div class="mobile-form-group">
                        <label for="mobile-contact-name"><?php esc_html_e('Nom complet', 'almetal'); ?> *</label>
                        <input type="text" id="mobile-contact-name" name="contact_name" placeholder="Jean Dupont" required>
                    </div>

                    <div class="mobile-form-group">
                        <label for="mobile-contact-phone"><?php esc_html_e('Téléphone', 'almetal'); ?> *</label>
                        <input type="tel" id="mobile-contact-phone" name="contact_phone" placeholder="06 12 34 56 78" required>
                    </div>
                </div>

                <div class="mobile-form-group">
                    <label for="mobile-contact-email"><?php esc_html_e('Email', 'almetal'); ?> *</label>
                    <input type="email" id="mobile-contact-email" name="contact_email" placeholder="jean.dupont@email.com" required>
                </div>

                <div class="mobile-form-group">
                    <label for="mobile-contact-project"><?php esc_html_e('Type de projet', 'almetal'); ?> *</label>
                    <select id="mobile-contact-project" name="contact_project" required>
                        <option value=""><?php esc_html_e('Sélectionnez un type', 'almetal'); ?></option>
                        <option value="portail"><?php esc_html_e('Portail', 'almetal'); ?></option>
                        <option value="garde-corps"><?php esc_html_e('Garde-corps', 'almetal'); ?></option>
                        <option value="escalier"><?php esc_html_e('Escalier', 'almetal'); ?></option>
                        <option value="pergola"><?php esc_html_e('Pergola', 'almetal'); ?></option>
                        <option value="verriere"><?php esc_html_e('Verrière', 'almetal'); ?></option>
                        <option value="mobilier"><?php esc_html_e('Mobilier métallique', 'almetal'); ?></option>
                        <option value="reparation"><?php esc_html_e('Réparation', 'almetal'); ?></option>
                        <option value="formation"><?php esc_html_e('Formation', 'almetal'); ?></option>
                        <option value="autre"><?php esc_html_e('Autre', 'almetal'); ?></option>
                    </select>
                </div>

                <div class="mobile-form-group">
                    <label for="mobile-contact-message"><?php esc_html_e('Votre message', 'almetal'); ?> *</label>
                    <textarea id="mobile-contact-message" name="contact_message" rows="5" placeholder="Décrivez votre projet..." required></textarea>
                </div>

                <!-- Opt-ins pour le marketing -->
                <div class="mobile-form-optins">
                    <div class="mobile-optin-group">
                        <label class="mobile-optin-label">
                            <input type="checkbox" name="consent_newsletter" value="1">
                            <span class="mobile-optin-checkbox"></span>
                            <span class="mobile-optin-text"><?php esc_html_e('Je souhaite recevoir les actualités et offres d\'AL Métallerie', 'almetal'); ?></span>
                        </label>
                    </div>
                    <div class="mobile-optin-group">
                        <label class="mobile-optin-label">
                            <input type="checkbox" name="consent_marketing" value="1">
                            <span class="mobile-optin-checkbox"></span>
                            <span class="mobile-optin-text"><?php esc_html_e('J\'accepte d\'être recontacté pour des offres personnalisées', 'almetal'); ?></span>
                        </label>
                    </div>
                </div>

                <input type="hidden" name="contact_consent" value="1">

                <button type="submit" class="mobile-contact-submit-btn" data-track="Contact|form_submit|mobile">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="22" y1="2" x2="11" y2="13"/>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                    </svg>
                    <?php esc_html_e('Envoyer ma demande', 'almetal'); ?>
                </button>
                
                <p class="mobile-form-consent-text">
                    <?php esc_html_e('En cliquant sur "Envoyer ma demande", vous acceptez que vos données soient utilisées pour vous recontacter.', 'almetal'); ?>
                    <a href="<?php echo esc_url(home_url('/politique-confidentialite')); ?>"><?php esc_html_e('Politique de confidentialité', 'almetal'); ?></a>
                </p>

                <div class="mobile-form-message" style="display: none;"></div>
            </form>
        </div>

    </div>
</main>

<!-- Script pour le formulaire AJAX -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('mobile-contact-form');
    const messageDiv = form.querySelector('.mobile-form-message');
    const submitBtn = form.querySelector('.mobile-contact-submit-btn');
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Désactiver le bouton
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="spin" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg> Envoi en cours...';
        
        const formData = new FormData(form);
        
        try {
            const response = await fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            });
            
            const data = await response.json();
            
            if (data.success) {
                messageDiv.className = 'mobile-form-message mobile-form-success';
                messageDiv.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg> ' + (data.data?.message || 'Message envoyé avec succès !');
                messageDiv.style.display = 'flex';
                form.reset();
                
                // Tracker l'événement
                if (window.almetalTrackEvent) {
                    window.almetalTrackEvent('Contact', 'form_success', 'mobile');
                }
            } else {
                messageDiv.className = 'mobile-form-message mobile-form-error';
                messageDiv.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg> ' + (data.data?.message || 'Erreur lors de l\'envoi');
                messageDiv.style.display = 'flex';
            }
        } catch (error) {
            messageDiv.className = 'mobile-form-message mobile-form-error';
            messageDiv.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg> Erreur de connexion';
            messageDiv.style.display = 'flex';
        }
        
        // Réactiver le bouton
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg> Envoyer ma demande';
        
        // Scroll vers le message
        messageDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });
});
</script>

<?php get_footer(); ?>
