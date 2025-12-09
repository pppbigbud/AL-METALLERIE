<?php
/**
 * Template One Page pour Mobile
 * 
 * Affiche toutes les sections en une seule page avec navigation par ancres
 * Optimisé pour la performance et le SEO mobile
 * 
 * @package AL-Metallerie Soudure
 * @since 1.0.0
 */
?>

<!-- Header Mobile avec menu burger -->
<?php get_template_part('template-parts/header', 'mobile'); ?>

<!-- Section Accueil / Hero -->
<section id="accueil" class="mobile-section mobile-hero" style="position: relative;">
    <?php get_template_part('template-parts/hero-carousel'); ?>
    
    <!-- SVG Séparateur Paysage avec Phare -->
    <div class="mobile-hero-separator scroll-fade">
<svg width="100%" height="58" viewBox="0 0 481 58" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M15.5 27.0356C12.7 27.0356 4.33333 31.0356 0.5 33.0356V56.5356H480.5V38.5356C475.333 37.7023 464.4 35.8356 462 35.0356C459 34.0356 451.5 32.0356 449.5 32.0356C447.5 32.0356 433.5 33.5356 432.5 33.0356C431.5 32.5356 428.5 31.0356 426 31.5356C423.5 32.0356 410 39.0356 404.5 39.0356C399 39.0356 384.5 37.0356 376.5 35.5356C370.1 34.3356 363.833 31.7023 361.5 30.5356H357.5L353.5 34.0356L350.5 34.5356L346 36.5356H334C331 36.5356 298.5 27.0356 297.5 27.0356C296.7 27.0356 294.5 28.369 293.5 29.0356L286.5 29.5356L263 16.0356C262.667 15.5356 261.8 14.4356 261 14.0356C260 13.5356 257 11.0356 255.5 10.5356C254.3 10.1356 252 8.03564 251 7.03564L250.5 0.0356445V7.03564L249.5 8.53564L243 11.0356C240.333 11.7023 234.8 12.9356 234 12.5356C233 12.0356 232 11.5356 230.5 11.5356C229 11.5356 225 12.5356 221 13.0356C217 13.5356 218 15.5356 215 16.0356C212.6 16.4356 210.667 18.5356 210 19.5356C204.333 24.369 191.4 34.1356 185 34.5356C178.6 34.9356 172.333 37.0356 170 38.0356C169.333 38.869 167.5 40.3356 165.5 39.5356C163.5 38.7356 157 38.5356 154 38.5356L143.5 34.0356L129 37.5356C126.667 36.5356 121.7 34.5356 120.5 34.5356H110C108 34.5356 102 37.0356 98.5 37.0356C95 37.0356 89.5 33.0356 81.5 33.0356C75.1 33.0356 65.5 36.0356 61.5 37.5356C59.1667 36.5356 54.2 34.4356 53 34.0356C51.5 33.5356 47.5 35.0356 45.5 33.0356C43.5 31.0356 38 30.0356 36.5 30.0356C35 30.0356 19 27.0356 15.5 27.0356Z" fill="white" stroke="white"/>
</svg>


    </div>
</section>

<!-- Section Présentation -->
<section id="presentation" class="mobile-section mobile-presentation">
    <?php get_template_part('template-parts/mobile', 'presentation'); ?>
</section>

<!-- Section CTA -->
<section id="cta-mobile" class="mobile-section mobile-cta scroll-fade">
    <?php get_template_part('template-parts/section', 'cta'); ?>
</section>

<!-- Section Réalisations / Actualités -->
<section id="actualites" class="mobile-section mobile-realisations scroll-fade">
    <div class="mobile-realisations-container">
        <!-- Tag -->
        <div class="mobile-realisations-tag scroll-zoom">
            <span><?php esc_html_e('Nos Réalisations', 'almetal'); ?></span>
        </div>

        <div class="mobile-realisations-header">
            <!-- Titre avec lien -->
            <a href="<?php echo esc_url(get_post_type_archive_link('realisation')); ?>" class="mobile-section-title-link">
                <h2 class="mobile-section-title scroll-fade scroll-delay-1">
                    <?php esc_html_e('NOS RÉALISATIONS', 'almetal'); ?>
                </h2>
            </a>
        </div>
    </div>
    <?php get_template_part('template-parts/mobile', 'realisations'); ?>
</section>

<!-- Séparateur -->
<div class="mobile-section-separator"></div>

<!-- Section Formations (Dynamique depuis backoffice) -->
<?php
// Récupérer les données depuis le backoffice
$mobile_cards = array();
$mobile_section = array();

if (class_exists('Almetal_Formations_Cards_Admin')) {
    $mobile_cards = Almetal_Formations_Cards_Admin::get_cards();
    $mobile_section = Almetal_Formations_Cards_Admin::get_section_settings();
    
    // Filtrer les cards actives
    $mobile_cards = array_filter($mobile_cards, function($card) {
        return isset($card['active']) && $card['active'];
    });
}

// Paramètres de la section
$mobile_tag = !empty($mobile_section['tag']) ? $mobile_section['tag'] : 'Nos Formations';
$mobile_title = !empty($mobile_section['title']) ? $mobile_section['title'] : 'DÉVELOPPEZ VOS COMPÉTENCES';
$mobile_subtitle = !empty($mobile_section['subtitle']) ? $mobile_section['subtitle'] : 'Formations professionnelles en métallerie adaptées à vos besoins';

// Fonction pour obtenir l'icône SVG (si pas déjà définie)
if (!function_exists('mobile_get_formation_icon')) {
function mobile_get_formation_icon($icon_key) {
    $icons = array(
        'users' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
        'home' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>',
        'building' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect><path d="M9 22v-4h6v4"></path><path d="M8 6h.01"></path><path d="M16 6h.01"></path><path d="M12 6h.01"></path><path d="M12 10h.01"></path><path d="M12 14h.01"></path><path d="M16 10h.01"></path><path d="M16 14h.01"></path><path d="M8 10h.01"></path><path d="M8 14h.01"></path></svg>',
        'graduation' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>',
        'tools' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>',
        'zap' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>',
        'certificate' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"></circle><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"></path></svg>',
        'calendar' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>',
    );
    return isset($icons[$icon_key]) ? $icons[$icon_key] : $icons['users'];
}
}

// Fonction pour parser les points clés
if (!function_exists('mobile_parse_features')) {
function mobile_parse_features($features_text) {
    if (empty($features_text)) return array();
    $lines = explode("\n", $features_text);
    $features = array();
    foreach ($lines as $line) {
        $line = trim($line);
        if (!empty($line)) {
            $features[] = $line;
        }
    }
    return array_slice($features, 0, 5);
}
}

if (!empty($mobile_cards)) :
?>
<section id="formations" class="mobile-section mobile-formations scroll-fade">
    <div class="mobile-formations-container">
        <!-- Tag -->
        <div class="mobile-formations-tag scroll-zoom">
            <span><?php echo esc_html($mobile_tag); ?></span>
        </div>

        <!-- Titre -->
        <h2 class="mobile-section-title">
            <?php echo esc_html($mobile_title); ?>
        </h2>

        <!-- Sous-titre -->
        <p class="mobile-formations-subtitle scroll-fade scroll-delay-2">
            <?php echo esc_html($mobile_subtitle); ?>
        </p>

        <!-- Grille de cards dynamiques -->
        <div class="mobile-formations-grid">
            
            <?php 
            $card_index = 1;
            foreach ($mobile_cards as $card) : 
                $is_coming_soon = !empty($card['coming_soon']);
                $has_image = !empty($card['image']);
                $icon = !empty($card['icon']) ? $card['icon'] : 'users';
                $features = mobile_parse_features($card['features'] ?? '');
                $cta_url = !empty($card['cta_url']) ? $card['cta_url'] : '#';
                $cta_text = !empty($card['cta_text']) ? $card['cta_text'] : 'En savoir +';
                $delay_class = 'scroll-delay-' . (($card_index % 5) + 1);
            ?>
            
            <article class="mobile-formation-card scroll-slide-up <?php echo $delay_class; ?><?php echo $is_coming_soon ? ' mobile-formation-card--disabled' : ''; ?>">
                <div class="mobile-formation-card-inner">
                    
                    <?php if ($is_coming_soon) : ?>
                    <div class="mobile-formation-badge">Bientôt disponible</div>
                    <?php endif; ?>
                    
                    <?php if ($has_image) : ?>
                    <!-- Image -->
                    <div class="mobile-formation-image">
                        <img src="<?php echo esc_url($card['image']); ?>" 
                             alt="<?php echo esc_attr($card['image_alt'] ?? $card['title']); ?>"
                             loading="lazy">
                    </div>
                    <?php else : ?>
                    <!-- Icône -->
                    <div class="mobile-formation-icon">
                        <?php echo mobile_get_formation_icon($icon); ?>
                    </div>
                    <?php endif; ?>

                    <!-- Contenu -->
                    <div class="mobile-formation-content">
                        <h3 class="mobile-formation-title">
                            <?php echo esc_html($card['title']); ?>
                        </h3>
                        
                        <?php if (!empty($card['description'])) : ?>
                        <p class="mobile-formation-description">
                            <?php echo esc_html($card['description']); ?>
                        </p>
                        <?php endif; ?>

                        <?php if (!empty($features)) : ?>
                        <!-- Points clés -->
                        <ul class="mobile-formation-features">
                            <?php foreach ($features as $feature) : ?>
                            <li>
                                <span><?php echo esc_html($feature); ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>

                        <?php if (!$is_coming_soon) : ?>
                        <!-- Bouton -->
                        <a href="<?php echo esc_url(home_url($cta_url)); ?>" class="mobile-btn-cta scroll-zoom">
                            <?php echo esc_html($cta_text); ?>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
            
            <?php 
            $card_index++;
            endforeach; 
            ?>

        </div>
    </div>
</section>
<?php endif; ?>

<!-- Séparateur -->
<div class="mobile-section-separator"></div>

<!-- Section Contact (informations uniquement) -->
<section id="contact" class="mobile-section mobile-contact scroll-fade">
    <div class="mobile-contact-container">
        <!-- Tag -->
        <div class="mobile-contact-tag scroll-zoom">
            <span><?php esc_html_e('Nous Contacter', 'almetal'); ?></span>
        </div>

        <!-- Titre -->
        <h2 class="mobile-section-title scroll-fade scroll-delay-1">
            <?php esc_html_e('CONTACTEZ-NOUS', 'almetal'); ?>
        </h2>

        <!-- Sous-titre -->
        <p class="mobile-contact-subtitle scroll-fade scroll-delay-2">
            <?php esc_html_e('Une question ? Un projet ? N\'hésitez pas à nous contacter', 'almetal'); ?>
        </p>

        <div class="mobile-contact-info-grid">
            <!-- Téléphone -->
            <a href="tel:0673333532" class="mobile-contact-info-card scroll-fade scroll-delay-1">
                <div class="mobile-contact-info-icon scroll-zoom">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                </div>
                <div class="mobile-contact-info-content">
                    <h3><?php esc_html_e('Téléphone', 'almetal'); ?></h3>
                    <p>06 73 33 35 32</p>
                </div>
            </a>

            <!-- Email -->
            <a href="mailto:contact@al-metallerie.fr" class="mobile-contact-info-card scroll-fade scroll-delay-2">
                <div class="mobile-contact-info-icon scroll-zoom">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                </div>
                <div class="mobile-contact-info-content">
                    <h3><?php esc_html_e('Email', 'almetal'); ?></h3>
                    <p>contact@al-metallerie.fr</p>
                </div>
            </a>

            <!-- Adresse -->
            <a href="https://www.google.com/maps/place/14+Rte+de+Maringues,+63920+Peschadoires/@45.8407255,3.493908,17z/data=!3m1!4b1!4m6!3m5!1s0x47f6ea900214670d:0x161d4fbc54650d0a!8m2!3d45.8407218!4d3.4964829!16s%2Fg%2F11c1zhggyj?entry=ttu&g_ep=EgoyMDI1MTEwNC4xIKXMDSoASAFQAw%3D%3D" target="_blank" rel="noopener" class="mobile-contact-info-card scroll-fade scroll-delay-3">
                <div class="mobile-contact-info-icon scroll-zoom">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                </div>
                <div class="mobile-contact-info-content">
                    <h3><?php esc_html_e('Adresse', 'almetal'); ?></h3>
                    <p>14 Rte de Maringues, 63920 Peschadoires</p>
                </div>
            </a>
        </div>

        <!-- Formulaire de contact intégré -->
        <div class="mobile-contact-form-section scroll-fade scroll-delay-4">
            <h3 class="mobile-contact-form-title">
                <?php esc_html_e('Envoyez-nous un message', 'almetal'); ?>
            </h3>
            
            <form class="mobile-contact-form" method="post" id="mobile-home-contact-form">
                <input type="hidden" name="action" value="almetal_contact_form">
                <?php wp_nonce_field('almetal_contact_form', 'contact_nonce'); ?>
                
                <div class="mobile-form-group">
                    <label for="home-contact-name"><?php esc_html_e('Nom complet', 'almetal'); ?> *</label>
                    <input type="text" id="home-contact-name" name="contact_name" placeholder="Votre nom" required>
                </div>
                
                <div class="mobile-form-group">
                    <label for="home-contact-phone"><?php esc_html_e('Téléphone', 'almetal'); ?> *</label>
                    <input type="tel" id="home-contact-phone" name="contact_phone" placeholder="06 XX XX XX XX" required>
                </div>
                
                <div class="mobile-form-group">
                    <label for="home-contact-email"><?php esc_html_e('Email', 'almetal'); ?> *</label>
                    <input type="email" id="home-contact-email" name="contact_email" placeholder="votre@email.com" required>
                </div>
                
                <div class="mobile-form-group">
                    <label for="home-contact-project"><?php esc_html_e('Type de projet', 'almetal'); ?> *</label>
                    <select id="home-contact-project" name="contact_project" required>
                        <option value=""><?php esc_html_e('Sélectionnez un type de projet', 'almetal'); ?></option>
                        <option value="Portail / Clôture"><?php esc_html_e('Portail / Clôture', 'almetal'); ?></option>
                        <option value="Garde-corps / Rampe"><?php esc_html_e('Garde-corps / Rampe', 'almetal'); ?></option>
                        <option value="Escalier métallique"><?php esc_html_e('Escalier métallique', 'almetal'); ?></option>
                        <option value="Pergola / Auvent"><?php esc_html_e('Pergola / Auvent', 'almetal'); ?></option>
                        <option value="Mobilier métallique"><?php esc_html_e('Mobilier métallique', 'almetal'); ?></option>
                        <option value="Réparation / Soudure"><?php esc_html_e('Réparation / Soudure', 'almetal'); ?></option>
                        <option value="Formation soudure"><?php esc_html_e('Formation soudure', 'almetal'); ?></option>
                        <option value="Autre projet"><?php esc_html_e('Autre projet', 'almetal'); ?></option>
                    </select>
                </div>
                
                <div class="mobile-form-group">
                    <label for="home-contact-message"><?php esc_html_e('Message', 'almetal'); ?> *</label>
                    <textarea id="home-contact-message" name="contact_message" rows="4" placeholder="Décrivez votre projet..." required></textarea>
                </div>
                
                <div class="mobile-form-group mobile-form-consent">
                    <label class="mobile-checkbox-label">
                        <input type="checkbox" name="contact_consent" id="home-contact-consent" required>
                        <span class="mobile-checkbox-text">
                            <?php esc_html_e('J\'accepte que mes données soient utilisées pour traiter ma demande', 'almetal'); ?> *
                        </span>
                    </label>
                </div>
                
                <button type="submit" class="mobile-btn-cta mobile-btn-cta--large">
                    <?php esc_html_e('Envoyer le message', 'almetal'); ?>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</section>

<!-- Bouton scroll to top -->
<button id="scroll-to-top" class="scroll-to-top scroll-zoom" aria-label="<?php esc_attr_e('Retour en haut', 'almetal'); ?>">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <line x1="12" y1="19" x2="12" y2="5"></line>
        <polyline points="5 12 12 5 19 12"></polyline>
    </svg>
</button>
