<?php
/**
 * Template Name: Page Contact
 * Template pour la page de contact avec carte Google Maps
 * 
 * @package AL-Metallerie Soudure
 * @since 1.0.0
 */

get_header();
?>

<?php
// Fermer le conteneur principal ouvert par le header
if (function_exists('wp_body_open')) {
    echo '</div>'; // Ferme #page ou .site-content si nécessaire
}
?>

<div class="contact-page">
    <!-- Carte Google Maps en plein écran -->
    <div class="contact-map-container">
        <div id="contact-map" class="contact-map"></div>
    </div>

    <!-- Overlay avec informations de contact -->
    <div class="contact-overlay">
        <!-- Bloc gauche : Informations de contact -->
        <div class="contact-info-card contact-info-left">
            
            <!-- En-tête -->
            <div class="contact-header">
                <h1 class="contact-title">
                    <svg class="contact-icon-main" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    <?php _e('Contactez-nous', 'almetal'); ?>
                </h1>
                <p class="contact-subtitle"><?php _e('Expert en Métallerie, Ferronerie, Serrurie à Thiers dans le Puy-de-Dôme, Auvergne', 'almetal'); ?></p>
            </div>

            <!-- Informations de contact -->
            <div class="contact-info-list">
                <!-- Téléphone -->
                <a href="tel:+33673333532" class="contact-info-item contact-phone">
                    <div class="contact-info-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                    </div>
                    <div class="contact-info-content">
                        <span class="contact-info-label"><?php _e('Téléphone', 'almetal'); ?></span>
                        <span class="contact-info-value">06 73 33 35 32</span>
                    </div>
                </a>

                <!-- Adresse -->
                <a href="https://www.google.com/maps/dir/?api=1&destination=14+route+de+Maringues,+Peschadoires,+63920" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="contact-info-item contact-address">
                    <div class="contact-info-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <div class="contact-info-content">
                        <span class="contact-info-label"><?php _e('Adresse', 'almetal'); ?></span>
                        <span class="contact-info-value">14 route de Maringues<br>63920 Peschadoires</span>
                    </div>
                </a>

                <!-- Email -->
                <a href="mailto:aurelien@al-metallerie.fr" class="contact-info-item contact-email">
                    <div class="contact-info-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                    </div>
                    <div class="contact-info-content">
                        <span class="contact-info-label"><?php _e('Email', 'almetal'); ?></span>
                        <span class="contact-info-value">aurelien@al-metallerie.fr</span>
                    </div>
                </a>

                <!-- Horaires -->
                <div class="contact-info-item contact-hours">
                    <div class="contact-info-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                    <div class="contact-info-content">
                        <span class="contact-info-label"><?php _e('Horaires', 'almetal'); ?></span>
                        <span class="contact-info-value">
                            Lun - Ven : 8h00 - 18h00<br>
                            Sam : Sur rendez-vous
                        </span>
                    </div>
                </div>

                 <!-- Boutons d'action rapide -->
                <div class="contact-quick-actions">
                    <a href="tel:+33673333532" class="quick-action-btn quick-action-call">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <?php _e('Appeler', 'almetal'); ?>
                    </a>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=14+route+de+Maringues,+Peschadoires,+63920" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="quick-action-btn quick-action-directions">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="3 11 22 2 13 21 11 13 3 11"/>
                        </svg>
                        <?php _e('Itinéraire', 'almetal'); ?>
                    </a>
                </div>

            </div>

        </div>

        <!-- Bloc droit : Formulaire de devis -->
        <div class="contact-info-card contact-form-right">
            <div class="contact-form-container">
                <h2 class="contact-form-title">
                    <svg class="contact-form-icon" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                    </svg>
                    <?php _e('Demande de devis', 'almetal'); ?>
                </h2>
                <p class="contact-form-subtitle"><?php _e('Décrivez-nous votre projet', 'almetal'); ?></p>

                <form id="contact-form" class="contact-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                    <?php wp_nonce_field('almetal_contact_form', 'contact_nonce'); ?>
                    <input type="hidden" name="action" value="almetal_contact_form">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="contact-name"><?php _e('Nom complet', 'almetal'); ?> *</label>
                            <input type="text" id="contact-name" name="contact_name" required>
                        </div>

                        <div class="form-group">
                            <label for="contact-phone"><?php _e('Téléphone', 'almetal'); ?> *</label>
                            <input type="tel" id="contact-phone" name="contact_phone" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contact-email"><?php _e('Email', 'almetal'); ?> *</label>
                        <input type="email" id="contact-email" name="contact_email" required>
                    </div>

                    <div class="form-group">
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

                    <div class="form-group">
                        <label for="contact-message"><?php _e('Votre message', 'almetal'); ?> *</label>
                        <textarea id="contact-message" name="contact_message" rows="5" required></textarea>
                    </div>

                    <input type="hidden" name="contact_consent" value="1">

                    <button type="submit" class="contact-submit-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="22" y1="2" x2="11" y2="13"/>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                        </svg>
                        <?php _e('Envoyer ma demande', 'almetal'); ?>
                    </button>
                    <p class="form-consent-text"><?php _e('En cliquant sur "Envoyer ma demande", vous acceptez que vos données soient utilisées pour vous recontacter.', 'almetal'); ?></p>

                    <div class="form-message" style="display: none;"></div>
                </form>
            
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
