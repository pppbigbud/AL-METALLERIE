<?php
/**
 * Template Name: Page Soudure Auvergne
 * Template pour la page des zones d'intervention avec carte
 * 
 * @package AL-Metallerie Soudure
 * @since 1.0.0
 */

get_header();
?>

<div class="intervention-page">
    <!-- Carte Leaflet en plein écran -->
    <div class="intervention-map-container">
        <div id="intervention-map" class="intervention-map"></div>
    </div>

    <!-- Overlay avec informations sur les zones d'intervention -->
    <div class="intervention-overlay">
        <!-- Bloc gauche : Informations sur les zones -->
        <div class="intervention-info-card intervention-info-left">
            
            <!-- En-tête -->
            <div class="intervention-header">
                <h1 class="intervention-title">
                    <svg class="intervention-icon-main" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    <?php _e('Zones d\'intervention', 'almetal'); ?>
                </h1>
                <p class="intervention-subtitle"><?php _e('Expert en Soudure, Métallerie, Ferronerie dans toute l\'Auvergne', 'almetal'); ?></p>
            </div>

            <!-- Liste des zones d'intervention -->
            <div class="intervention-zones-list">
                <h3><?php _e('Nos zones d\'intervention principales', 'almetal'); ?></h3>
                
                <!-- Départements -->
                <div class="intervention-departments">
                    <div class="department-item">
                        <div class="department-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                        </div>
                        <div class="department-content">
                            <span class="department-name"><?php _e('Puy-de-Dôme (63)', 'almetal'); ?></span>
                            <span class="department-cities"><?php _e('Clermont-Ferrand, Thiers, Issoire, Ambert, Cournon-d\'Auvergne', 'almetal'); ?></span>
                        </div>
                    </div>
                    
                    <div class="department-item">
                        <div class="department-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                        </div>
                        <div class="department-content">
                            <span class="department-name"><?php _e('Allier (03)', 'almetal'); ?></span>
                            <span class="department-cities"><?php _e('Vichy, Moulins, Montluçon, Cusset, Yzeure', 'almetal'); ?></span>
                        </div>
                    </div>
                    
                    <div class="department-item">
                        <div class="department-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                        </div>
                        <div class="department-content">
                            <span class="department-name"><?php _e('Cantal (15)', 'almetal'); ?></span>
                            <span class="department-cities"><?php _e('Aurillac, Saint-Flour, Mauriac, Arpajon-sur-Cère', 'almetal'); ?></span>
                        </div>
                    </div>
                    
                    <div class="department-item">
                        <div class="department-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                        </div>
                        <div class="department-content">
                            <span class="department-name"><?php _e('Haute-Loire (43)', 'almetal'); ?></span>
                            <span class="department-cities"><?php _e('Le Puy-en-Velay, Brioude, Yssingeaux, Monistrol-sur-Loire', 'almetal'); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Rayon d'intervention -->
                <div class="intervention-radius">
                    <h4><?php _e('Rayon d\'intervention', 'almetal'); ?></h4>
                    <p><?php _e('Nous intervenons dans un rayon de 150km autour de notre siège à Peschadoires (63)', 'almetal'); ?></p>
                    <div class="radius-visual">
                        <div class="radius-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                            <span><?php _e('Intervention rapide', 'almetal'); ?></span>
                        </div>
                        <div class="radius-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                            <span><?php _e('Toute l\'Auvergne', 'almetal'); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="intervention-quick-actions">
                    <a href="tel:+33673333532" class="quick-action-btn quick-action-call">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <?php _e('Nous appeler', 'almetal'); ?>
                    </a>
                    <a href="#contact-form" class="quick-action-btn quick-action-quote">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                        </svg>
                        <?php _e('Demander un devis', 'almetal'); ?>
                    </a>
                </div>

            </div>

        </div>

        <!-- Bloc droit : Informations complémentaires et formulaire -->
        <div class="intervention-info-card intervention-form-right">
            <div class="intervention-content-container">
                <h2 class="intervention-content-title">
                    <svg class="intervention-content-icon" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    <?php _e('Services de soudure', 'almetal'); ?>
                </h2>
                <p class="intervention-content-subtitle"><?php _e('Interventions professionnelles en soudure dans toute l\'Auvergne', 'almetal'); ?></p>

                <!-- Services -->
                <div class="intervention-services">
                    <div class="service-item">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                        </svg>
                        <span><?php _e('Soudure MIG/MAG', 'almetal'); ?></span>
                    </div>
                    <div class="service-item">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                        </svg>
                        <span><?php _e('Soudure TIG', 'almetal'); ?></span>
                    </div>
                    <div class="service-item">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                        </svg>
                        <span><?php _e('Soudure à l\'arc', 'almetal'); ?></span>
                    </div>
                    <div class="service-item">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                        </svg>
                        <span><?php _e('Soudure aluminium', 'almetal'); ?></span>
                    </div>
                    <div class="service-item">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                        </svg>
                        <span><?php _e('Soudure inox', 'almetal'); ?></span>
                    </div>
                    <div class="service-item">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                        </svg>
                        <span><?php _e('Réparations soudure', 'almetal'); ?></span>
                    </div>
                </div>

                <!-- Formulaire de contact simplifié -->
                <div id="contact-form" class="intervention-contact-form">
                    <h3><?php _e('Contactez-nous pour votre projet de soudure', 'almetal'); ?></h3>
                    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                        <?php wp_nonce_field('almetal_contact_form', 'contact_nonce'); ?>
                        <input type="hidden" name="action" value="almetal_contact_form">
                        
                        <div class="form-group">
                            <label for="contact-name"><?php _e('Nom', 'almetal'); ?> *</label>
                            <input type="text" id="contact-name" name="contact_name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="contact-phone"><?php _e('Téléphone', 'almetal'); ?> *</label>
                            <input type="tel" id="contact-phone" name="contact_phone" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="contact-message"><?php _e('Description du projet', 'almetal'); ?> *</label>
                            <textarea id="contact-message" name="contact_message" rows="4" required></textarea>
                        </div>
                        
                        <button type="submit" class="intervention-submit-btn">
                            <?php _e('Envoyer la demande', 'almetal'); ?>
                        </button>
                    </form>
                </div>
            
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
