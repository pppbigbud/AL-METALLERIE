<?php
/**
 * Template pour afficher une session de formation
 *
 * Ce template peut être copié dans votre thème :
 * votre-theme/single-training_session.php
 *
 * @package TrainingManager
 * @since 1.0.0
 */

get_header();

while (have_posts()) :
    the_post();
    
    // Récupérer les métadonnées
    $start_date = get_post_meta(get_the_ID(), '_tm_start_date', true);
    $end_date = get_post_meta(get_the_ID(), '_tm_end_date', true);
    $start_time = get_post_meta(get_the_ID(), '_tm_start_time', true);
    $end_time = get_post_meta(get_the_ID(), '_tm_end_time', true);
    $location = get_post_meta(get_the_ID(), '_tm_location', true);
    $address = get_post_meta(get_the_ID(), '_tm_address', true);
    $trainer = get_post_meta(get_the_ID(), '_tm_trainer', true);
    $price = get_post_meta(get_the_ID(), '_tm_price', true);
    $price_info = get_post_meta(get_the_ID(), '_tm_price_info', true);
    $prerequisites = get_post_meta(get_the_ID(), '_tm_prerequisites', true);
    $materials = get_post_meta(get_the_ID(), '_tm_materials', true);
    $status = get_post_meta(get_the_ID(), '_tm_status', true) ?: 'open';
    $total_places = get_post_meta(get_the_ID(), '_tm_total_places', true);
    $reserved_places = get_post_meta(get_the_ID(), '_tm_reserved_places', true);
    $remaining = $total_places - $reserved_places;
    $documents = get_post_meta(get_the_ID(), '_tm_documents', true) ?: [];
    
    $types = get_the_terms(get_the_ID(), 'training_type');
    $themes = get_the_terms(get_the_ID(), 'training_theme');
    
    $date_format = get_option('tm_date_format', 'd/m/Y');
    $currency = get_option('tm_currency_symbol', '€');
    
    $status_labels = [
        'open'      => __('Inscriptions ouvertes', 'training-manager'),
        'full'      => __('Complet', 'training-manager'),
        'waitlist'  => __('Liste d\'attente', 'training-manager'),
        'cancelled' => __('Annulé', 'training-manager'),
    ];
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('tm-single-session'); ?>>
    
    <!-- Hero -->
    <header class="tm-session-hero">
        <?php if (has_post_thumbnail()) : ?>
            <div class="tm-hero-image">
                <?php the_post_thumbnail('full'); ?>
                <div class="tm-hero-overlay"></div>
            </div>
        <?php endif; ?>
        
        <div class="tm-hero-content">
            <div class="tm-hero-inner">
                <!-- Badges -->
                <div class="tm-session-badges">
                    <?php if ($types && !is_wp_error($types)) : ?>
                        <?php foreach ($types as $type) : ?>
                            <a href="<?php echo esc_url(get_term_link($type)); ?>" class="tm-badge tm-badge-type">
                                <?php echo esc_html($type->name); ?>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <?php if ($themes && !is_wp_error($themes)) : ?>
                        <?php foreach ($themes as $theme) : ?>
                            <span class="tm-badge tm-badge-theme">
                                <?php echo esc_html($theme->name); ?>
                            </span>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <!-- Titre -->
                <h1 class="tm-session-title"><?php the_title(); ?></h1>
                
                <!-- Statut -->
                <div class="tm-session-status tm-status-<?php echo esc_attr($status); ?>">
                    <span class="tm-status-dot"></span>
                    <span class="tm-status-text"><?php echo esc_html($status_labels[$status] ?? $status); ?></span>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Contenu principal -->
    <div class="tm-session-content-wrapper">
        <div class="tm-container">
            <div class="tm-session-grid">
                
                <!-- Colonne principale -->
                <div class="tm-session-main">
                    
                    <!-- Barre d'infos rapides -->
                    <div class="tm-quick-info">
                        <?php if ($start_date) : ?>
                            <div class="tm-quick-item">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                <div>
                                    <span class="tm-quick-label"><?php _e('Date', 'training-manager'); ?></span>
                                    <span class="tm-quick-value">
                                        <?php 
                                        echo date_i18n($date_format, strtotime($start_date));
                                        if ($end_date && $end_date !== $start_date) {
                                            echo ' - ' . date_i18n($date_format, strtotime($end_date));
                                        }
                                        ?>
                                    </span>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($start_time) : ?>
                            <div class="tm-quick-item">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                                <div>
                                    <span class="tm-quick-label"><?php _e('Horaires', 'training-manager'); ?></span>
                                    <span class="tm-quick-value">
                                        <?php echo esc_html($start_time); ?>
                                        <?php if ($end_time) echo ' - ' . esc_html($end_time); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($location) : ?>
                            <div class="tm-quick-item">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                                <div>
                                    <span class="tm-quick-label"><?php _e('Lieu', 'training-manager'); ?></span>
                                    <span class="tm-quick-value"><?php echo esc_html($location); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($trainer) : ?>
                            <div class="tm-quick-item">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                                <div>
                                    <span class="tm-quick-label"><?php _e('Formateur', 'training-manager'); ?></span>
                                    <span class="tm-quick-value"><?php echo esc_html($trainer); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Description -->
                    <div class="tm-session-description">
                        <h2><?php _e('Description de la formation', 'training-manager'); ?></h2>
                        <?php the_content(); ?>
                    </div>
                    
                    <!-- Prérequis -->
                    <?php if ($prerequisites) : ?>
                        <div class="tm-session-section">
                            <h3><?php _e('Prérequis', 'training-manager'); ?></h3>
                            <div class="tm-section-content">
                                <?php echo wpautop(esc_html($prerequisites)); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Matériel -->
                    <?php if ($materials) : ?>
                        <div class="tm-session-section">
                            <h3><?php _e('Matériel nécessaire', 'training-manager'); ?></h3>
                            <div class="tm-section-content">
                                <?php echo wpautop(esc_html($materials)); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Adresse -->
                    <?php if ($address) : ?>
                        <div class="tm-session-section">
                            <h3><?php _e('Adresse', 'training-manager'); ?></h3>
                            <div class="tm-section-content">
                                <p><?php echo nl2br(esc_html($address)); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Documents -->
                    <?php if (!empty($documents)) : ?>
                        <div class="tm-session-section">
                            <h3><?php _e('Documents', 'training-manager'); ?></h3>
                            <div class="tm-documents-list">
                                <?php foreach ($documents as $doc) : ?>
                                    <a href="<?php echo esc_url($doc); ?>" class="tm-document-link" target="_blank">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                            <polyline points="14 2 14 8 20 8"/>
                                            <line x1="16" y1="13" x2="8" y2="13"/>
                                            <line x1="16" y1="17" x2="8" y2="17"/>
                                            <polyline points="10 9 9 9 8 9"/>
                                        </svg>
                                        <?php echo esc_html(basename($doc)); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Sidebar -->
                <aside class="tm-session-sidebar">
                    
                    <!-- Carte de réservation -->
                    <div class="tm-booking-card">
                        <?php if ($price) : ?>
                            <div class="tm-booking-price">
                                <span class="tm-price-amount"><?php echo esc_html(number_format($price, 0, ',', ' ')); ?></span>
                                <span class="tm-price-currency"><?php echo esc_html($currency); ?></span>
                                <?php if ($price_info) : ?>
                                    <span class="tm-price-info"><?php echo esc_html($price_info); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Places -->
                        <div class="tm-booking-places">
                            <div class="tm-places-header">
                                <span><?php _e('Places disponibles', 'training-manager'); ?></span>
                                <span class="tm-places-count"><?php echo esc_html($remaining); ?>/<?php echo esc_html($total_places); ?></span>
                            </div>
                            <div class="tm-places-bar">
                                <div class="tm-places-fill" style="width: <?php echo ($total_places > 0) ? ($reserved_places / $total_places * 100) : 0; ?>%"></div>
                            </div>
                        </div>
                        
                        <!-- Formulaire de contact -->
                        <?php if ($status === 'open' || $status === 'waitlist') : ?>
                            <div class="tm-contact-form">
                                <h3><?php _e('Demander des informations', 'training-manager'); ?></h3>
                                <form id="tm-contact-form" method="post">
                                    <input type="hidden" name="session_id" value="<?php echo get_the_ID(); ?>">
                                    
                                    <div class="tm-form-row">
                                        <div class="tm-form-group">
                                            <label for="tm-first-name"><?php _e('Prénom', 'training-manager'); ?> <span class="required">*</span></label>
                                            <input type="text" id="tm-first-name" name="first_name" required>
                                        </div>
                                        <div class="tm-form-group">
                                            <label for="tm-last-name"><?php _e('Nom', 'training-manager'); ?> <span class="required">*</span></label>
                                            <input type="text" id="tm-last-name" name="last_name" required>
                                        </div>
                                    </div>
                                    
                                    <div class="tm-form-group">
                                        <label for="tm-email"><?php _e('Email', 'training-manager'); ?> <span class="required">*</span></label>
                                        <input type="email" id="tm-email" name="email" required>
                                    </div>
                                    
                                    <div class="tm-form-group">
                                        <label for="tm-phone"><?php _e('Téléphone', 'training-manager'); ?></label>
                                        <input type="tel" id="tm-phone" name="phone">
                                    </div>
                                    
                                    <div class="tm-form-group">
                                        <label for="tm-company"><?php _e('Entreprise', 'training-manager'); ?></label>
                                        <input type="text" id="tm-company" name="company">
                                    </div>
                                    
                                    <div class="tm-form-group">
                                        <label for="tm-message"><?php _e('Message', 'training-manager'); ?></label>
                                        <textarea id="tm-message" name="message" rows="4"></textarea>
                                    </div>
                                    
                                    <button type="submit" class="tm-form-submit">
                                        <?php _e('Envoyer ma demande', 'training-manager'); ?>
                                    </button>
                                </form>
                            </div>
                        <?php else : ?>
                            <div class="tm-booking-closed">
                                <p><?php _e('Les inscriptions sont fermées pour cette session.', 'training-manager'); ?></p>
                                <a href="<?php echo get_post_type_archive_link('training_session'); ?>" class="tm-btn-secondary">
                                    <?php _e('Voir les autres formations', 'training-manager'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Contact direct -->
                    <div class="tm-contact-direct">
                        <h4><?php _e('Besoin d\'aide ?', 'training-manager'); ?></h4>
                        <p><?php _e('Contactez-nous directement :', 'training-manager'); ?></p>
                        <a href="tel:+33673333532" class="tm-phone-link">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                            06 73 33 35 32
                        </a>
                    </div>
                    
                </aside>
                
            </div>
        </div>
    </div>
    
</article>

<?php
endwhile;

get_footer();
