<?php
/**
 * Administration des Cards Formations - Page d'accueil
 * 
 * Permet de gérer les cards de la section formations depuis le backoffice
 * avec optimisation automatique des images en WebP et SEO
 * 
 * @package ALMetallerie
 * @since 2.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class Almetal_Formations_Cards_Admin {
    
    const OPTION_NAME = 'almetal_formations_cards';
    const SECTION_OPTION = 'almetal_formations_section';
    
    public function __construct() {
        // Ajouter le menu admin
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Enregistrer les assets admin
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        
        // Traiter la sauvegarde
        add_action('admin_post_save_formations_cards', array($this, 'save_formations_cards'));
    }
    
    /**
     * Ajouter le menu dans l'admin (sidebar principale)
     */
    public function add_admin_menu() {
        $hook = add_menu_page(
            'Cards Formations',
            'Cards Formations',
            'edit_theme_options',
            'almetal-formations-cards',
            array($this, 'render_admin_page'),
            'dashicons-welcome-learn-more',
            30 // Position après "Commentaires"
        );
        
        // Définir le titre de la page pour éviter le warning strip_tags
        add_action('load-' . $hook, function() {
            global $title;
            $title = 'Cards Formations';
        });
    }
    
    /**
     * Charger les assets admin
     */
    public function enqueue_admin_assets($hook) {
        // Vérifier si on est sur la page des cards formations
        if (strpos($hook, 'almetal-formations-cards') === false) {
            return;
        }
        
        wp_enqueue_media();
        wp_enqueue_style(
            'almetal-formations-admin',
            get_template_directory_uri() . '/assets/css/admin-slideshow.css',
            array(),
            filemtime(get_template_directory() . '/assets/css/admin-slideshow.css')
        );
        wp_enqueue_script(
            'almetal-formations-admin',
            get_template_directory_uri() . '/assets/js/admin-formations.js',
            array('jquery', 'jquery-ui-sortable'),
            filemtime(get_template_directory() . '/assets/js/admin-formations.js'),
            true
        );
    }
    
    /**
     * Récupérer les cards
     */
    public static function get_cards() {
        $default_cards = array(
            array(
                'active' => true,
                'coming_soon' => false,
                'title' => 'PROFESSIONNELS',
                'description' => 'Formations spécialisées pour les professionnels du métal : techniques avancées de soudure, fabrication de structures métalliques, et perfectionnement aux normes industrielles.',
                'image' => '',
                'image_alt' => 'Formation professionnelle en métallerie',
                'icon' => 'professionnels',
                'features' => "Certification professionnelle\nFormateurs experts\nÉquipement professionnel",
                'cta_text' => 'En savoir +',
                'cta_url' => '/formations-professionnelles',
                'order' => 0,
            ),
            array(
                'active' => true,
                'coming_soon' => false,
                'title' => 'PARTICULIERS',
                'description' => 'Initiations et ateliers pour les passionnés : découverte de la métallerie, création d\'objets décoratifs, et apprentissage des techniques de base en toute sécurité.',
                'image' => '',
                'image_alt' => 'Formation particuliers en métallerie',
                'icon' => 'particuliers',
                'features' => "Ateliers découverte\nPetits groupes\nProjets personnalisés",
                'cta_text' => 'En savoir +',
                'cta_url' => '/formations-particuliers',
                'order' => 1,
            ),
        );
        
        $cards = get_option(self::OPTION_NAME, $default_cards);
        
        // Trier par ordre
        usort($cards, function($a, $b) {
            return ($a['order'] ?? 0) - ($b['order'] ?? 0);
        });
        
        return $cards;
    }
    
    /**
     * Récupérer les paramètres de la section
     */
    public static function get_section_settings() {
        $defaults = array(
            'tag' => 'Nos Formations',
            'title' => 'DÉVELOPPEZ VOS COMPÉTENCES',
            'subtitle' => 'Formations professionnelles en métallerie et soudure adaptées à vos besoins',
            'background_image' => '',
            'background_overlay' => '0.7',
        );
        
        $settings = get_option(self::SECTION_OPTION, $defaults);
        
        // S'assurer que toutes les clés existent et ne sont jamais null (évite le warning strip_tags)
        $result = array();
        foreach ($defaults as $key => $default_value) {
            $result[$key] = isset($settings[$key]) && $settings[$key] !== null ? $settings[$key] : $default_value;
        }
        return $result;
    }
    
    /**
     * Afficher la page d'administration
     */
    public function render_admin_page() {
        $cards = self::get_cards();
        $section = self::get_section_settings();
        $updated = isset($_GET['updated']) && $_GET['updated'] === 'true';
        
        ?>
        <div class="wrap almetal-admin-wrap">
            <div class="almetal-admin-header">
                <h1>
                    <span class="dashicons dashicons-welcome-learn-more"></span>
                    Cards Formations - Page d'accueil
                </h1>
                <p class="description">
                    Gérez les cards de la section formations affichées sur la page d'accueil.
                    Les images sont automatiquement optimisées en WebP.
                </p>
            </div>
            
            <?php if ($updated) : ?>
                <div class="notice notice-success is-dismissible">
                    <p><strong>✅ Cards formations sauvegardées avec succès !</strong></p>
                </div>
            <?php endif; ?>
            
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" class="almetal-slideshow-form">
                <?php wp_nonce_field('almetal_formations_save', 'almetal_formations_nonce'); ?>
                <input type="hidden" name="action" value="save_formations_cards">
                
                <!-- Paramètres de la section -->
                <div class="almetal-section-box">
                    <h2>
                        <span class="dashicons dashicons-admin-settings"></span>
                        Paramètres de la section
                    </h2>
                    
                    <div class="form-row">
                        <div class="form-group form-group-third">
                            <label class="form-label">
                                <span class="dashicons dashicons-tag"></span>
                                Tag
                            </label>
                            <input type="text" 
                                   name="section[tag]" 
                                   value="<?php echo esc_attr($section['tag']); ?>" 
                                   class="form-control"
                                   placeholder="Ex: Nos Formations">
                        </div>
                        
                        <div class="form-group form-group-third">
                            <label class="form-label">
                                <span class="dashicons dashicons-heading"></span>
                                Titre principal
                            </label>
                            <input type="text" 
                                   name="section[title]" 
                                   value="<?php echo esc_attr($section['title']); ?>" 
                                   class="form-control"
                                   placeholder="Ex: DÉVELOPPEZ VOS COMPÉTENCES">
                        </div>
                        
                        <div class="form-group form-group-third">
                            <label class="form-label">
                                <span class="dashicons dashicons-editor-alignleft"></span>
                                Sous-titre
                            </label>
                            <input type="text" 
                                   name="section[subtitle]" 
                                   value="<?php echo esc_attr($section['subtitle']); ?>" 
                                   class="form-control"
                                   placeholder="Ex: Formations professionnelles...">
                        </div>
                    </div>
                    
                    <!-- Image de fond -->
                    <div class="form-row">
                        <div class="form-group form-group-two-thirds">
                            <label class="form-label">
                                <span class="dashicons dashicons-format-image"></span>
                                Image de fond de la section
                            </label>
                            <div class="image-upload-wrapper">
                                <input type="text" 
                                       name="section[background_image]" 
                                       value="<?php echo esc_attr($section['background_image'] ?? ''); ?>" 
                                       class="form-control section-bg-url"
                                       placeholder="URL de l'image de fond">
                                <button type="button" class="button upload-section-bg-btn">
                                    <span class="dashicons dashicons-upload"></span>
                                    Choisir
                                </button>
                                <button type="button" class="button remove-section-bg-btn" <?php echo empty($section['background_image']) ? 'style="display:none;"' : ''; ?>>
                                    <span class="dashicons dashicons-trash"></span>
                                </button>
                            </div>
                            <?php if (!empty($section['background_image'])) : ?>
                            <div class="section-bg-preview" style="margin-top: 10px;">
                                <img src="<?php echo esc_url($section['background_image']); ?>" alt="Aperçu" style="max-width: 300px; max-height: 150px; border-radius: 8px; border: 2px solid #F08B18;">
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group form-group-third">
                            <label class="form-label">
                                <span class="dashicons dashicons-visibility"></span>
                                Opacité de l'overlay
                            </label>
                            <input type="range" 
                                   name="section[background_overlay]" 
                                   value="<?php echo esc_attr($section['background_overlay'] ?? '0.7'); ?>" 
                                   min="0" max="1" step="0.1"
                                   class="form-control overlay-range"
                                   style="width: 100%;">
                            <span class="overlay-value"><?php echo esc_html($section['background_overlay'] ?? '0.7'); ?></span>
                            <p class="description">0 = transparent, 1 = opaque</p>
                        </div>
                    </div>
                </div>
                
                <!-- Cards -->
                <div id="cards-container" class="slides-container">
                    <?php foreach ($cards as $index => $card) : ?>
                        <?php $this->render_card_editor($index, $card); ?>
                    <?php endforeach; ?>
                </div>
                
                <!-- Boutons d'action -->
                <div class="almetal-actions">
                    <button type="button" id="add-card-btn" class="button button-secondary button-large">
                        <span class="dashicons dashicons-plus-alt"></span>
                        Ajouter une card
                    </button>
                    
                    <button type="submit" class="button button-primary button-large">
                        <span class="dashicons dashicons-saved"></span>
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
        <?php
    }
    
    /**
     * Afficher l'éditeur d'une card
     */
    private function render_card_editor($index, $card) {
        $active = isset($card['active']) ? $card['active'] : true;
        $coming_soon = isset($card['coming_soon']) ? $card['coming_soon'] : false;
        $title = isset($card['title']) ? $card['title'] : '';
        $description = isset($card['description']) ? $card['description'] : '';
        $image = isset($card['image']) ? $card['image'] : '';
        $image_alt = isset($card['image_alt']) ? $card['image_alt'] : '';
        $icon = isset($card['icon']) ? $card['icon'] : '';
        $features = isset($card['features']) ? $card['features'] : '';
        $cta_text = isset($card['cta_text']) ? $card['cta_text'] : '';
        $cta_url = isset($card['cta_url']) ? $card['cta_url'] : '';
        $order = isset($card['order']) ? $card['order'] : $index;
        
        ?>
        <div class="slide-editor <?php echo $active ? '' : 'slide-inactive'; ?>" data-card-index="<?php echo $index; ?>">
            <div class="slide-header">
                <div class="slide-drag-handle">
                    <span class="dashicons dashicons-menu"></span>
                </div>
                <h2 class="slide-title-header">
                    Card <?php echo ($index + 1); ?> : <?php echo esc_html($title); ?>
                </h2>
                
                <div class="slide-toggle">
                    <label class="toggle-switch">
                        <input type="checkbox" 
                               name="cards[<?php echo $index; ?>][active]" 
                               value="1" 
                               <?php checked($active, true); ?>
                               class="slide-active-toggle">
                        <span class="toggle-slider"></span>
                    </label>
                    <span class="toggle-label"><?php echo $active ? 'Activée' : 'Désactivée'; ?></span>
                </div>
                
                <div class="slide-toggle coming-soon-toggle">
                    <label class="toggle-switch toggle-switch-warning">
                        <input type="checkbox" 
                               name="cards[<?php echo $index; ?>][coming_soon]" 
                               value="1" 
                               <?php checked($coming_soon, true); ?>
                               class="coming-soon-checkbox">
                        <span class="toggle-slider toggle-slider-warning"></span>
                    </label>
                    <span class="toggle-label toggle-label-warning">
                        <span class="dashicons dashicons-clock"></span>
                        Bientôt disponible
                    </span>
                </div>
                
                <button type="button" class="button button-link-delete delete-card-btn" title="Supprimer cette card">
                    <span class="dashicons dashicons-trash"></span>
                </button>
            </div>
            
            <div class="slide-content">
                <input type="hidden" name="cards[<?php echo $index; ?>][order]" value="<?php echo $order; ?>" class="card-order-input">
                
                <!-- Image -->
                <div class="form-group">
                    <label class="form-label">
                        <span class="dashicons dashicons-format-image"></span>
                        Image de la card
                    </label>
                    <div class="image-upload-wrapper">
                        <div class="image-preview <?php echo $image ? 'has-image' : ''; ?>">
                            <?php if ($image) : ?>
                                <img src="<?php echo esc_url($image); ?>" alt="Aperçu">
                            <?php endif; ?>
                            <div class="image-preview-overlay">
                                <button type="button" class="button upload-image-button">
                                    <span class="dashicons dashicons-upload"></span>
                                    <?php echo $image ? 'Changer l\'image' : 'Choisir une image'; ?>
                                </button>
                                <?php if ($image) : ?>
                                    <button type="button" class="button button-link-delete remove-image-button">
                                        <span class="dashicons dashicons-no"></span>
                                        Supprimer
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <input type="hidden" 
                               name="cards[<?php echo $index; ?>][image]" 
                               value="<?php echo esc_attr($image); ?>" 
                               class="image-url-input">
                    </div>
                    <p class="form-help">
                        <span class="dashicons dashicons-info"></span>
                        L'image sera automatiquement convertie en <strong>WebP</strong> et optimisée
                    </p>
                </div>
                
                <!-- Alt de l'image (SEO) -->
                <div class="form-group">
                    <label class="form-label">
                        <span class="dashicons dashicons-visibility"></span>
                        Texte alternatif (SEO)
                    </label>
                    <input type="text" 
                           name="cards[<?php echo $index; ?>][image_alt]" 
                           value="<?php echo esc_attr($image_alt); ?>" 
                           class="form-control"
                           placeholder="Ex: Formation professionnelle en soudure à Thiers">
                    <p class="form-help">
                        <span class="dashicons dashicons-info"></span>
                        Description de l'image pour le référencement et l'accessibilité
                    </p>
                </div>
                
                <!-- Icône -->
                <div class="form-group">
                    <label class="form-label">
                        <span class="dashicons dashicons-star-filled"></span>
                        Icône de la card
                    </label>
                    <select name="cards[<?php echo $index; ?>][icon]" class="form-control">
                        <option value="">-- Choisir une icône --</option>
                        <option value="professionnels" <?php selected($icon, 'professionnels'); ?>>👥 Professionnels (groupe)</option>
                        <option value="particuliers" <?php selected($icon, 'particuliers'); ?>>🏠 Particuliers (maison)</option>
                        <option value="entreprise" <?php selected($icon, 'entreprise'); ?>>🏢 Entreprise</option>
                        <option value="formation" <?php selected($icon, 'formation'); ?>>🎓 Formation (graduation)</option>
                        <option value="outils" <?php selected($icon, 'outils'); ?>>🔧 Outils</option>
                        <option value="soudure" <?php selected($icon, 'soudure'); ?>>⚡ Soudure</option>
                        <option value="certificat" <?php selected($icon, 'certificat'); ?>>📜 Certificat</option>
                        <option value="calendrier" <?php selected($icon, 'calendrier'); ?>>📅 Calendrier</option>
                    </select>
                </div>
                
                <!-- Titre -->
                <div class="form-group">
                    <label class="form-label">
                        <span class="dashicons dashicons-text"></span>
                        Titre de la card
                        <span class="required">*</span>
                    </label>
                    <input type="text" 
                           name="cards[<?php echo $index; ?>][title]" 
                           value="<?php echo esc_attr($title); ?>" 
                           class="form-control"
                           placeholder="Ex: PROFESSIONNELS"
                           required>
                </div>
                
                <!-- Description -->
                <div class="form-group">
                    <label class="form-label">
                        <span class="dashicons dashicons-editor-alignleft"></span>
                        Description
                    </label>
                    <textarea name="cards[<?php echo $index; ?>][description]" 
                              class="form-control"
                              rows="3"
                              placeholder="Description de la formation..."><?php echo esc_textarea($description); ?></textarea>
                </div>
                
                <!-- Points clés -->
                <div class="form-group">
                    <label class="form-label">
                        <span class="dashicons dashicons-yes-alt"></span>
                        Points clés (un par ligne)
                    </label>
                    <textarea name="cards[<?php echo $index; ?>][features]" 
                              class="form-control"
                              rows="4"
                              placeholder="Certification professionnelle&#10;Formateurs experts&#10;Équipement professionnel"><?php echo esc_textarea($features); ?></textarea>
                    <p class="form-help">
                        <span class="dashicons dashicons-info"></span>
                        Entrez un point clé par ligne (3 maximum recommandé)
                    </p>
                </div>
                
                <!-- Bouton CTA -->
                <div class="form-row">
                    <div class="form-group form-group-half">
                        <label class="form-label">
                            <span class="dashicons dashicons-admin-links"></span>
                            Texte du bouton
                        </label>
                        <input type="text" 
                               name="cards[<?php echo $index; ?>][cta_text]" 
                               value="<?php echo esc_attr($cta_text); ?>" 
                               class="form-control"
                               placeholder="Ex: En savoir +">
                    </div>
                    
                    <div class="form-group form-group-half">
                        <label class="form-label">
                            <span class="dashicons dashicons-admin-links"></span>
                            URL du bouton
                        </label>
                        <input type="text" 
                               name="cards[<?php echo $index; ?>][cta_url]" 
                               value="<?php echo esc_attr($cta_url); ?>" 
                               class="form-control"
                               placeholder="Ex: /formations-professionnelles">
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Sauvegarder les cards
     */
    public function save_formations_cards() {
        // Vérifier le nonce
        if (!isset($_POST['almetal_formations_nonce']) || 
            !wp_verify_nonce($_POST['almetal_formations_nonce'], 'almetal_formations_save')) {
            wp_die('Erreur de sécurité');
        }
        
        // Vérifier les permissions
        if (!current_user_can('edit_theme_options')) {
            wp_die('Permissions insuffisantes');
        }
        
        // Sauvegarder les paramètres de section (wp_unslash pour éviter les doubles échappements)
        if (isset($_POST['section'])) {
            $section_data = wp_unslash($_POST['section']);
            $section = array(
                'tag' => sanitize_text_field($section_data['tag']),
                'title' => sanitize_text_field($section_data['title']),
                'subtitle' => sanitize_text_field($section_data['subtitle']),
                'background_image' => esc_url_raw($section_data['background_image'] ?? ''),
                'background_overlay' => floatval($section_data['background_overlay'] ?? 0.7),
            );
            update_option(self::SECTION_OPTION, $section);
        }
        
        // Récupérer et nettoyer les cards (wp_unslash pour éviter les doubles échappements)
        $cards = isset($_POST['cards']) ? wp_unslash($_POST['cards']) : array();
        $cleaned_cards = array();
        
        foreach ($cards as $index => $card) {
            $cleaned_cards[$index] = array(
                'active' => isset($card['active']) && $card['active'] === '1',
                'coming_soon' => isset($card['coming_soon']) && $card['coming_soon'] === '1',
                'title' => sanitize_text_field($card['title']),
                'description' => sanitize_textarea_field($card['description']),
                'image' => esc_url_raw($card['image']),
                'image_alt' => sanitize_text_field($card['image_alt']),
                'icon' => sanitize_text_field($card['icon']),
                'features' => sanitize_textarea_field($card['features']),
                'cta_text' => sanitize_text_field($card['cta_text']),
                'cta_url' => sanitize_text_field($card['cta_url']),
                'order' => intval($card['order']),
            );
        }
        
        // Sauvegarder dans la base de données
        update_option(self::OPTION_NAME, $cleaned_cards);
        
        // Rediriger avec message de succès (vers le menu principal, pas themes.php)
        wp_redirect(admin_url('admin.php?page=almetal-formations-cards&updated=true'));
        exit;
    }
}

// Initialiser
new Almetal_Formations_Cards_Admin();

/**
 * Récupérer l'icône SVG pour une card formation
 */
function almetal_get_formation_icon($icon_slug) {
    $icons = array(
        'professionnels' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
        'particuliers' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>',
        'entreprise' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect><path d="M9 22v-4h6v4"></path><path d="M8 6h.01"></path><path d="M16 6h.01"></path><path d="M12 6h.01"></path><path d="M12 10h.01"></path><path d="M12 14h.01"></path><path d="M16 10h.01"></path><path d="M16 14h.01"></path><path d="M8 10h.01"></path><path d="M8 14h.01"></path></svg>',
        'formation' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/></svg>',
        'outils' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>',
        'soudure' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>',
        'certificat' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>',
        'calendrier' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>',
    );
    
    return isset($icons[$icon_slug]) ? $icons[$icon_slug] : '';
}

/**
 * Formater les features en tableau
 */
function almetal_format_formation_features($features_text) {
    if (empty($features_text)) return array();
    $lines = explode("\n", $features_text);
    return array_filter(array_map('trim', $lines));
}
