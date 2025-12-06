<?php
/**
 * Interface d'administration pour la gestion du slideshow
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

// Sécurité : empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe de gestion du slideshow
 */
class Almetal_Slideshow_Admin {
    
    /**
     * Nom de l'option dans la base de données
     */
    const OPTION_NAME = 'almetal_slideshow_slides';
    
    /**
     * Nombre maximum de slides (augmenté pour plus de flexibilité)
     */
    const MAX_SLIDES = 10;
    
    /**
     * Dimensions optimales pour le slideshow
     */
    const SLIDE_WIDTH = 1920;
    const SLIDE_HEIGHT = 800;
    const WEBP_QUALITY = 85;
    
    /**
     * Constructeur
     */
    public function __construct() {
        // Ajouter le menu d'administration
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Enregistrer les scripts et styles admin
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        
        // Traiter la sauvegarde
        add_action('admin_post_save_slideshow', array($this, 'save_slideshow'));
        
        // AJAX pour la réorganisation des slides
        add_action('wp_ajax_reorder_slides', array($this, 'ajax_reorder_slides'));
    }
    
    /**
     * Ajouter le menu dans l'administration
     */
    public function add_admin_menu() {
        add_menu_page(
            'Gestion du Slideshow',           // Titre de la page
            'Slideshow Accueil',               // Titre du menu
            'edit_theme_options',              // Capacité requise
            'almetal-slideshow',               // Slug du menu
            array($this, 'render_admin_page'), // Fonction de callback
            'dashicons-images-alt2',           // Icône
            25                                 // Position (après "Apparence")
        );
    }
    
    /**
     * Enregistrer les assets admin
     */
    public function enqueue_admin_assets($hook) {
        // Charger uniquement sur notre page
        if ($hook !== 'toplevel_page_almetal-slideshow') {
            return;
        }
        
        // Enqueue WordPress Media Uploader
        wp_enqueue_media();
        
        // Enqueue jQuery UI Sortable pour le drag & drop
        wp_enqueue_script('jquery-ui-sortable');
        
        // CSS admin personnalisé
        wp_enqueue_style(
            'almetal-slideshow-admin',
            get_template_directory_uri() . '/assets/css/admin-slideshow.css',
            array(),
            '1.0.0'
        );
        
        // JS admin personnalisé
        wp_enqueue_script(
            'almetal-slideshow-admin',
            get_template_directory_uri() . '/assets/js/admin-slideshow.js',
            array('jquery', 'jquery-ui-sortable'),
            '1.0.0',
            true
        );
        
        // Passer des données à JavaScript
        wp_localize_script('almetal-slideshow-admin', 'almetalSlideshow', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('almetal_slideshow_nonce'),
            'maxSlides' => self::MAX_SLIDES,
        ));
    }
    
    /**
     * Récupérer les slides depuis la base de données
     */
    public static function get_slides() {
        $slides = get_option(self::OPTION_NAME, array());
        
        // Si vide, retourner les valeurs par défaut (slides actuelles hardcodées)
        if (empty($slides)) {
            $slides = self::get_default_slides();
        }
        
        return $slides;
    }
    
    /**
     * Slides par défaut (valeurs actuelles du site)
     */
    private static function get_default_slides() {
        return array(
            array(
                'active' => true,
                'image' => get_template_directory_uri() . '/assets/images/hero/hero-1.png',
                'title' => 'Bienvenue chez AL Métallerie',
                'subtitle' => 'Expert en métallerie à Clermont-Ferrand',
                'cta_text' => 'Demander un devis',
                'cta_url' => '#contact',
                'order' => 0,
            ),
            array(
                'active' => true,
                'image' => get_template_directory_uri() . '/assets/images/hero/hero-2.png',
                'title' => 'Créations sur mesure',
                'subtitle' => 'Portails, garde-corps, escaliers',
                'cta_text' => 'Découvrir nos réalisations',
                'cta_url' => '#services',
                'order' => 1,
            ),
            array(
                'active' => true,
                'image' => get_template_directory_uri() . '/assets/images/hero/hero-3.png',
                'title' => 'Formations',
                'subtitle' => 'Particulier, centre de formation, à la demande',
                'cta_text' => 'Découvrir nos formations',
                'cta_url' => '#services',
                'order' => 2,
            ),
        );
    }
    
    /**
     * Afficher la page d'administration
     */
    public function render_admin_page() {
        // Vérifier les permissions
        if (!current_user_can('edit_theme_options')) {
            wp_die(__('Vous n\'avez pas les permissions nécessaires pour accéder à cette page.'));
        }
        
        // Récupérer les slides
        $slides = self::get_slides();
        
        // Trier par ordre
        usort($slides, function($a, $b) {
            return ($a['order'] ?? 0) - ($b['order'] ?? 0);
        });
        
        ?>
        <div class="wrap almetal-slideshow-admin">
            <h1>
                <span class="dashicons dashicons-images-alt2"></span>
                Gestion du Slideshow - Page d'Accueil
            </h1>
            
            <div class="almetal-admin-notice">
                <p>
                    <strong>ℹ️ Instructions :</strong> 
                    Gérez jusqu'à <?php echo self::MAX_SLIDES; ?> slides du carrousel de la page d'accueil. 
                    Les images sont <strong>automatiquement converties en WebP</strong> et redimensionnées à <?php echo self::SLIDE_WIDTH; ?>x<?php echo self::SLIDE_HEIGHT; ?>px pour des performances optimales.
                    Glissez-déposez les slides pour changer leur ordre.
                </p>
            </div>
            
            <div class="almetal-admin-stats">
                <span class="stat-item">
                    <span class="dashicons dashicons-images-alt2"></span>
                    <strong><?php echo count(array_filter($slides, function($s) { return !empty($s['active']); })); ?></strong> slides actives
                </span>
                <span class="stat-item">
                    <span class="dashicons dashicons-performance"></span>
                    Format: <strong>WebP</strong> (optimisé)
                </span>
                <span class="stat-item">
                    <span class="dashicons dashicons-desktop"></span>
                    Dimensions: <strong><?php echo self::SLIDE_WIDTH; ?>x<?php echo self::SLIDE_HEIGHT; ?>px</strong>
                </span>
            </div>
            
            <?php if (isset($_GET['updated']) && $_GET['updated'] === 'true') : ?>
                <div class="notice notice-success is-dismissible">
                    <p><strong>✅ Slideshow mis à jour avec succès !</strong></p>
                </div>
            <?php endif; ?>
            
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" class="almetal-slideshow-form">
                <?php wp_nonce_field('almetal_slideshow_save', 'almetal_slideshow_nonce'); ?>
                <input type="hidden" name="action" value="save_slideshow">
                
                <div id="slides-container" class="slides-container">
                    <?php foreach ($slides as $index => $slide) : ?>
                        <?php $this->render_slide_editor($index, $slide); ?>
                    <?php endforeach; ?>
                </div>
                
                <div class="almetal-form-footer">
                    <button type="button" id="add-new-slide" class="button button-secondary button-hero">
                        <span class="dashicons dashicons-plus-alt"></span>
                        Ajouter un slide
                    </button>
                    
                    <button type="submit" class="button button-primary button-hero">
                        <span class="dashicons dashicons-yes"></span>
                        Enregistrer les modifications
                    </button>
                    
                    <button type="button" id="reset-slides" class="button button-secondary">
                        <span class="dashicons dashicons-image-rotate"></span>
                        Réinitialiser aux valeurs par défaut
                    </button>
                </div>
            </form>
        </div>
        <?php
    }
    
    /**
     * Afficher l'éditeur d'un slide
     */
    private function render_slide_editor($index, $slide) {
        $active = isset($slide['active']) ? $slide['active'] : true;
        $image = isset($slide['image']) ? $slide['image'] : '';
        $title = isset($slide['title']) ? $slide['title'] : '';
        $subtitle = isset($slide['subtitle']) ? $slide['subtitle'] : '';
        $cta_text = isset($slide['cta_text']) ? $slide['cta_text'] : '';
        $cta_url = isset($slide['cta_url']) ? $slide['cta_url'] : '';
        $order = isset($slide['order']) ? $slide['order'] : $index;
        
        // Champs commerciaux/promotionnels
        $is_promo = isset($slide['is_promo']) ? $slide['is_promo'] : false;
        $promo_badge = isset($slide['promo_badge']) ? $slide['promo_badge'] : '';
        $promo_price = isset($slide['promo_price']) ? $slide['promo_price'] : '';
        $promo_old_price = isset($slide['promo_old_price']) ? $slide['promo_old_price'] : '';
        $promo_discount = isset($slide['promo_discount']) ? $slide['promo_discount'] : '';
        $promo_end_date = isset($slide['promo_end_date']) ? $slide['promo_end_date'] : '';
        $promo_stock = isset($slide['promo_stock']) ? $slide['promo_stock'] : '';
        $promo_features = isset($slide['promo_features']) ? $slide['promo_features'] : '';
        
        $slide_type_class = $is_promo ? 'slide-promo' : '';
        
        ?>
        <div class="slide-editor <?php echo $active ? '' : 'slide-inactive'; ?> <?php echo $slide_type_class; ?>" data-slide-index="<?php echo $index; ?>">
            <div class="slide-header">
                <div class="slide-drag-handle">
                    <span class="dashicons dashicons-menu"></span>
                </div>
                <h2 class="slide-title-header">
                    Slide <?php echo ($index + 1); ?>
                    <?php if ($is_promo) : ?>
                        <span class="slide-type-badge promo-badge">🛒 Commercial</span>
                    <?php endif; ?>
                </h2>
                
                <!-- Toggle Mode Commercial -->
                <div class="slide-mode-toggle">
                    <label class="mode-switch" title="Activer le mode commercial/promotionnel">
                        <input type="checkbox" 
                               name="slides[<?php echo $index; ?>][is_promo]" 
                               value="1" 
                               <?php checked($is_promo, true); ?>
                               class="slide-promo-toggle">
                        <span class="dashicons dashicons-cart"></span>
                        <span class="mode-label">Promo</span>
                    </label>
                </div>
                
                <div class="slide-toggle">
                    <label class="toggle-switch">
                        <input type="checkbox" 
                               name="slides[<?php echo $index; ?>][active]" 
                               value="1" 
                               <?php checked($active, true); ?>
                               class="slide-active-toggle">
                        <span class="toggle-slider"></span>
                    </label>
                    <span class="toggle-label"><?php echo $active ? 'Activé' : 'Désactivé'; ?></span>
                </div>
            </div>
            
            <div class="slide-content">
                <input type="hidden" name="slides[<?php echo $index; ?>][order]" value="<?php echo $order; ?>" class="slide-order-input">
                
                <!-- Image -->
                <div class="form-group">
                    <label class="form-label">
                        <span class="dashicons dashicons-format-image"></span>
                        Image de fond
                        <span class="required">*</span>
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
                               name="slides[<?php echo $index; ?>][image]" 
                               value="<?php echo esc_attr($image); ?>" 
                               class="image-url-input"
                               required>
                    </div>
                    <p class="form-help">
                        <span class="dashicons dashicons-info"></span>
                        L'image sera automatiquement convertie en <strong>WebP</strong> et redimensionnée à <strong>1920x800px</strong>
                    </p>
                </div>
                
                <!-- Icône du CTA -->
                <div class="form-group">
                    <label class="form-label">
                        <span class="dashicons dashicons-star-filled"></span>
                        Icône du bouton CTA
                    </label>
                    <?php 
                    $title_icon = isset($slide['title_icon']) ? $slide['title_icon'] : '';
                    almetal_render_icon_selector(
                        'slides[' . $index . '][title_icon]',
                        $title_icon,
                        'icon-selector-' . $index
                    );
                    ?>
                    <p class="form-help">
                        <span class="dashicons dashicons-info"></span>
                        Choisissez une icône à afficher dans le bouton CTA (par défaut : Formations)
                    </p>
                </div>
                
                <!-- Titre -->
                <div class="form-group">
                    <label class="form-label">
                        <span class="dashicons dashicons-text"></span>
                        Titre principal
                        <span class="required">*</span>
                    </label>
                    <input type="text" 
                           name="slides[<?php echo $index; ?>][title]" 
                           value="<?php echo esc_attr($title); ?>" 
                           class="form-control"
                           placeholder="Ex: Bienvenue chez AL Métallerie"
                           required>
                </div>
                
                <!-- Sous-titre -->
                <div class="form-group">
                    <label class="form-label">
                        <span class="dashicons dashicons-editor-alignleft"></span>
                        Sous-titre / Description
                    </label>
                    <textarea name="slides[<?php echo $index; ?>][subtitle]" 
                              class="form-control"
                              rows="2"
                              placeholder="Ex: Expert en métallerie à Clermont-Ferrand"><?php echo esc_textarea($subtitle); ?></textarea>
                </div>
                
                <!-- Bouton CTA -->
                <div class="form-row">
                    <div class="form-group form-group-half">
                        <label class="form-label">
                            <span class="dashicons dashicons-admin-links"></span>
                            Texte du bouton
                        </label>
                        <input type="text" 
                               name="slides[<?php echo $index; ?>][cta_text]" 
                               value="<?php echo esc_attr($cta_text); ?>" 
                               class="form-control"
                               placeholder="Ex: Demander un devis">
                    </div>
                    
                    <div class="form-group form-group-half">
                        <label class="form-label">
                            <span class="dashicons dashicons-admin-site"></span>
                            URL du bouton
                        </label>
                        <input type="text" 
                               name="slides[<?php echo $index; ?>][cta_url]" 
                               value="<?php echo esc_attr($cta_url); ?>" 
                               class="form-control"
                               placeholder="Ex: #contact ou /contact">
                        <p class="form-help">Utilisez # pour les ancres (ex: #contact) ou une URL complète</p>
                    </div>
                </div>
                
                <!-- ============================================ -->
                <!-- SECTION COMMERCIALE / PROMOTIONNELLE -->
                <!-- ============================================ -->
                <div class="promo-section <?php echo $is_promo ? 'promo-visible' : 'promo-hidden'; ?>">
                    <div class="promo-section-header">
                        <span class="dashicons dashicons-megaphone"></span>
                        <h3>Options Commerciales / Promotionnelles</h3>
                    </div>
                    
                    <!-- Badge promo -->
                    <div class="form-row">
                        <div class="form-group form-group-half">
                            <label class="form-label">
                                <span class="dashicons dashicons-tag"></span>
                                Badge promotionnel
                            </label>
                            <select name="slides[<?php echo $index; ?>][promo_badge]" class="form-control">
                                <option value="" <?php selected($promo_badge, ''); ?>>-- Aucun badge --</option>
                                <option value="promo" <?php selected($promo_badge, 'promo'); ?>>🔥 PROMO</option>
                                <option value="nouveau" <?php selected($promo_badge, 'nouveau'); ?>>✨ NOUVEAU</option>
                                <option value="exclusif" <?php selected($promo_badge, 'exclusif'); ?>>⭐ EXCLUSIF</option>
                                <option value="limited" <?php selected($promo_badge, 'limited'); ?>>⏰ ÉDITION LIMITÉE</option>
                                <option value="bestseller" <?php selected($promo_badge, 'bestseller'); ?>>🏆 BEST-SELLER</option>
                                <option value="soldout" <?php selected($promo_badge, 'soldout'); ?>>❌ ÉPUISÉ</option>
                                <option value="noel" <?php selected($promo_badge, 'noel'); ?>>🎄 SPÉCIAL NOËL</option>
                                <option value="custom" <?php selected($promo_badge, 'custom'); ?>>📝 Personnalisé...</option>
                            </select>
                        </div>
                        
                        <div class="form-group form-group-half">
                            <label class="form-label">
                                <span class="dashicons dashicons-edit"></span>
                                Badge personnalisé
                            </label>
                            <input type="text" 
                                   name="slides[<?php echo $index; ?>][promo_badge_custom]" 
                                   value="<?php echo esc_attr(isset($slide['promo_badge_custom']) ? $slide['promo_badge_custom'] : ''); ?>" 
                                   class="form-control"
                                   placeholder="Ex: -50% ou SOLDES">
                        </div>
                    </div>
                    
                    <!-- Prix -->
                    <div class="form-row">
                        <div class="form-group form-group-third">
                            <label class="form-label">
                                <span class="dashicons dashicons-money-alt"></span>
                                Prix actuel (€)
                            </label>
                            <input type="text" 
                                   name="slides[<?php echo $index; ?>][promo_price]" 
                                   value="<?php echo esc_attr($promo_price); ?>" 
                                   class="form-control price-input"
                                   placeholder="Ex: 149,00">
                        </div>
                        
                        <div class="form-group form-group-third">
                            <label class="form-label">
                                <span class="dashicons dashicons-dismiss"></span>
                                Ancien prix (€)
                            </label>
                            <input type="text" 
                                   name="slides[<?php echo $index; ?>][promo_old_price]" 
                                   value="<?php echo esc_attr($promo_old_price); ?>" 
                                   class="form-control price-input old-price"
                                   placeholder="Ex: 199,00">
                            <p class="form-help">Sera barré</p>
                        </div>
                        
                        <div class="form-group form-group-third">
                            <label class="form-label">
                                <span class="dashicons dashicons-chart-line"></span>
                                Réduction
                            </label>
                            <input type="text" 
                                   name="slides[<?php echo $index; ?>][promo_discount]" 
                                   value="<?php echo esc_attr($promo_discount); ?>" 
                                   class="form-control"
                                   placeholder="Ex: -25%">
                        </div>
                    </div>
                    
                    <!-- Date fin et stock -->
                    <div class="form-row">
                        <div class="form-group form-group-half">
                            <label class="form-label">
                                <span class="dashicons dashicons-calendar-alt"></span>
                                Date de fin de l'offre
                            </label>
                            <input type="date" 
                                   name="slides[<?php echo $index; ?>][promo_end_date]" 
                                   value="<?php echo esc_attr($promo_end_date); ?>" 
                                   class="form-control">
                            <p class="form-help">Affichera un compte à rebours</p>
                        </div>
                        
                        <div class="form-group form-group-half">
                            <label class="form-label">
                                <span class="dashicons dashicons-archive"></span>
                                Stock disponible
                            </label>
                            <input type="text" 
                                   name="slides[<?php echo $index; ?>][promo_stock]" 
                                   value="<?php echo esc_attr($promo_stock); ?>" 
                                   class="form-control"
                                   placeholder="Ex: Plus que 5 en stock !">
                        </div>
                    </div>
                    
                    <!-- Caractéristiques produit -->
                    <div class="form-group">
                        <label class="form-label">
                            <span class="dashicons dashicons-list-view"></span>
                            Points forts du produit
                        </label>
                        <textarea name="slides[<?php echo $index; ?>][promo_features]" 
                                  class="form-control"
                                  rows="3"
                                  placeholder="Un point par ligne. Ex:&#10;✓ Fabrication artisanale&#10;✓ Acier haute qualité&#10;✓ Livraison gratuite"><?php echo esc_textarea($promo_features); ?></textarea>
                        <p class="form-help">Un avantage par ligne (utilisez ✓ ou • pour les puces)</p>
                    </div>
                </div>
                <!-- FIN SECTION COMMERCIALE -->
                
            </div>
        </div>
        <?php
    }
    
    /**
     * Sauvegarder le slideshow
     */
    public function save_slideshow() {
        // Vérifier le nonce
        if (!isset($_POST['almetal_slideshow_nonce']) || 
            !wp_verify_nonce($_POST['almetal_slideshow_nonce'], 'almetal_slideshow_save')) {
            wp_die('Erreur de sécurité');
        }
        
        // Vérifier les permissions
        if (!current_user_can('edit_theme_options')) {
            wp_die('Permissions insuffisantes');
        }
        
        // Récupérer et nettoyer les données
        $slides = isset($_POST['slides']) ? $_POST['slides'] : array();
        $cleaned_slides = array();
        
        foreach ($slides as $index => $slide) {
            $cleaned_slides[$index] = array(
                // Champs de base
                'active' => isset($slide['active']) && $slide['active'] === '1',
                'image' => esc_url_raw($slide['image']),
                'title_icon' => sanitize_text_field(isset($slide['title_icon']) ? $slide['title_icon'] : ''),
                'title' => sanitize_text_field($slide['title']),
                'subtitle' => sanitize_textarea_field($slide['subtitle']),
                'cta_text' => sanitize_text_field($slide['cta_text']),
                'cta_url' => esc_url_raw($slide['cta_url']),
                'order' => intval($slide['order']),
                
                // Champs commerciaux/promotionnels
                'is_promo' => isset($slide['is_promo']) && $slide['is_promo'] === '1',
                'promo_badge' => sanitize_text_field(isset($slide['promo_badge']) ? $slide['promo_badge'] : ''),
                'promo_badge_custom' => sanitize_text_field(isset($slide['promo_badge_custom']) ? $slide['promo_badge_custom'] : ''),
                'promo_price' => sanitize_text_field(isset($slide['promo_price']) ? $slide['promo_price'] : ''),
                'promo_old_price' => sanitize_text_field(isset($slide['promo_old_price']) ? $slide['promo_old_price'] : ''),
                'promo_discount' => sanitize_text_field(isset($slide['promo_discount']) ? $slide['promo_discount'] : ''),
                'promo_end_date' => sanitize_text_field(isset($slide['promo_end_date']) ? $slide['promo_end_date'] : ''),
                'promo_stock' => sanitize_text_field(isset($slide['promo_stock']) ? $slide['promo_stock'] : ''),
                'promo_features' => sanitize_textarea_field(isset($slide['promo_features']) ? $slide['promo_features'] : ''),
            );
        }
        
        // Sauvegarder dans la base de données
        update_option(self::OPTION_NAME, $cleaned_slides);
        
        // Rediriger avec message de succès
        wp_redirect(add_query_arg(
            array('page' => 'almetal-slideshow', 'updated' => 'true'),
            admin_url('admin.php')
        ));
        exit;
    }
    
    /**
     * AJAX : Réorganiser les slides
     */
    public function ajax_reorder_slides() {
        // Vérifier le nonce
        check_ajax_referer('almetal_slideshow_nonce', 'nonce');
        
        // Vérifier les permissions
        if (!current_user_can('edit_theme_options')) {
            wp_send_json_error('Permissions insuffisantes');
        }
        
        // Récupérer l'ordre
        $order = isset($_POST['order']) ? $_POST['order'] : array();
        
        if (empty($order)) {
            wp_send_json_error('Ordre invalide');
        }
        
        // Récupérer les slides actuels
        $slides = self::get_slides();
        
        // Réorganiser
        $reordered = array();
        foreach ($order as $new_index => $old_index) {
            if (isset($slides[$old_index])) {
                $slides[$old_index]['order'] = $new_index;
                $reordered[$old_index] = $slides[$old_index];
            }
        }
        
        // Sauvegarder
        update_option(self::OPTION_NAME, $reordered);
        
        wp_send_json_success('Ordre mis à jour');
    }
    
    /**
     * AJAX : Convertir une image en WebP optimisé pour le slideshow
     */
    public function ajax_convert_to_webp() {
        // Vérifier le nonce
        check_ajax_referer('almetal_slideshow_nonce', 'nonce');
        
        // Vérifier les permissions
        if (!current_user_can('edit_theme_options')) {
            wp_send_json_error('Permissions insuffisantes');
        }
        
        $attachment_id = isset($_POST['attachment_id']) ? intval($_POST['attachment_id']) : 0;
        
        if (!$attachment_id) {
            wp_send_json_error('ID d\'attachment invalide');
        }
        
        // Convertir l'image
        $result = self::convert_image_to_webp($attachment_id);
        
        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result['message']);
        }
    }
    
    /**
     * Convertir une image en WebP avec les dimensions du slideshow
     * 
     * @param int $attachment_id ID de l'attachment WordPress
     * @return array Résultat de la conversion
     */
    public static function convert_image_to_webp($attachment_id) {
        $file_path = get_attached_file($attachment_id);
        
        if (!$file_path || !file_exists($file_path)) {
            return array('success' => false, 'message' => 'Fichier non trouvé');
        }
        
        // Vérifier que GD est disponible avec support WebP
        if (!function_exists('imagecreatefromjpeg') || !function_exists('imagewebp')) {
            return array('success' => false, 'message' => 'GD ou support WebP non disponible sur le serveur');
        }
        
        // Obtenir le type MIME
        $mime_type = get_post_mime_type($attachment_id);
        
        // Charger l'image selon son type
        $image = null;
        switch ($mime_type) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = @imagecreatefromjpeg($file_path);
                break;
            case 'image/png':
                $image = @imagecreatefrompng($file_path);
                break;
            case 'image/gif':
                $image = @imagecreatefromgif($file_path);
                break;
            case 'image/webp':
                // Déjà en WebP, on redimensionne juste si nécessaire
                $image = @imagecreatefromwebp($file_path);
                break;
            default:
                return array('success' => false, 'message' => 'Format d\'image non supporté: ' . $mime_type);
        }
        
        if (!$image) {
            return array('success' => false, 'message' => 'Impossible de charger l\'image');
        }
        
        // Obtenir les dimensions originales
        $orig_width = imagesx($image);
        $orig_height = imagesy($image);
        
        // Calculer les nouvelles dimensions en conservant le ratio
        // On veut remplir 1920x800 en recadrant si nécessaire (cover)
        $target_width = self::SLIDE_WIDTH;
        $target_height = self::SLIDE_HEIGHT;
        
        $orig_ratio = $orig_width / $orig_height;
        $target_ratio = $target_width / $target_height;
        
        if ($orig_ratio > $target_ratio) {
            // Image plus large que nécessaire, on recadre les côtés
            $new_height = $orig_height;
            $new_width = $orig_height * $target_ratio;
            $src_x = ($orig_width - $new_width) / 2;
            $src_y = 0;
        } else {
            // Image plus haute que nécessaire, on recadre le haut/bas
            $new_width = $orig_width;
            $new_height = $orig_width / $target_ratio;
            $src_x = 0;
            $src_y = ($orig_height - $new_height) / 2;
        }
        
        // Créer l'image finale aux dimensions exactes
        $final_image = imagecreatetruecolor($target_width, $target_height);
        
        // Préserver la transparence
        imagealphablending($final_image, false);
        imagesavealpha($final_image, true);
        $transparent = imagecolorallocatealpha($final_image, 0, 0, 0, 127);
        imagefill($final_image, 0, 0, $transparent);
        
        // Redimensionner et recadrer
        imagecopyresampled(
            $final_image, $image,
            0, 0,                           // Destination x, y
            (int)$src_x, (int)$src_y,       // Source x, y
            $target_width, $target_height,  // Destination width, height
            (int)$new_width, (int)$new_height // Source width, height
        );
        
        // Générer le chemin du fichier WebP
        $upload_dir = wp_upload_dir();
        $slideshow_dir = $upload_dir['basedir'] . '/slideshow-optimized';
        
        // Créer le dossier s'il n'existe pas
        if (!file_exists($slideshow_dir)) {
            wp_mkdir_p($slideshow_dir);
        }
        
        // Nom du fichier WebP
        $filename = pathinfo($file_path, PATHINFO_FILENAME);
        $webp_filename = sanitize_file_name($filename) . '-' . $target_width . 'x' . $target_height . '.webp';
        $webp_path = $slideshow_dir . '/' . $webp_filename;
        
        // Sauvegarder en WebP
        $saved = imagewebp($final_image, $webp_path, self::WEBP_QUALITY);
        
        // Libérer la mémoire
        imagedestroy($image);
        imagedestroy($final_image);
        
        if (!$saved) {
            return array('success' => false, 'message' => 'Erreur lors de la sauvegarde WebP');
        }
        
        // Calculer les économies
        $original_size = filesize($file_path);
        $webp_size = filesize($webp_path);
        $savings = round((1 - ($webp_size / $original_size)) * 100);
        
        // URL du fichier WebP
        $webp_url = $upload_dir['baseurl'] . '/slideshow-optimized/' . $webp_filename;
        
        // Générer et appliquer les métadonnées SEO optimisées
        $seo_metadata = self::generate_seo_metadata($attachment_id);
        self::update_attachment_seo_metadata($attachment_id, $seo_metadata);
        
        return array(
            'success' => true,
            'url' => $webp_url,
            'path' => $webp_path,
            'original_size' => size_format($original_size),
            'webp_size' => size_format($webp_size),
            'savings' => $savings . '%',
            'dimensions' => $target_width . 'x' . $target_height,
            'seo' => $seo_metadata
        );
    }
    
    /**
     * Générer les métadonnées SEO optimisées pour le référencement local
     * 
     * @param int $attachment_id ID de l'attachment
     * @return array Métadonnées SEO
     */
    public static function generate_seo_metadata($attachment_id) {
        // Récupérer le nom du fichier original
        $file_path = get_attached_file($attachment_id);
        $filename = pathinfo($file_path, PATHINFO_FILENAME);
        
        // Nettoyer le nom de fichier pour en extraire des mots-clés
        $clean_name = str_replace(array('-', '_', '.'), ' ', $filename);
        $clean_name = preg_replace('/[0-9]+/', '', $clean_name); // Retirer les chiffres
        $clean_name = trim(preg_replace('/\s+/', ' ', $clean_name)); // Nettoyer les espaces
        
        // Mots-clés SEO locaux enrichis pour AL Métallerie
        $local_keywords = array(
            // Localisation
            'Thiers',
            '63',
            '63300',
            'Puy-de-Dôme',
            'Auvergne',
            'Auvergne-Rhône-Alpes',
            'Clermont-Ferrand',
            // Métier principal
            'métallerie',
            'métallier',
            'ferronnier',
            'ferronnerie',
            'ferronnerie d\'art',
            // Techniques
            'soudure',
            'soudeur',
            'soudage',
            'forge',
            'forgeage',
            'travail du métal',
            // Services
            'sur mesure',
            'fabrication',
            'création',
            'rénovation',
            'installation',
            'artisan',
            'artisanal',
            // Produits
            'portail',
            'garde-corps',
            'escalier',
            'pergola',
            'grille',
            'rampe',
            'balcon',
            'clôture',
            'verrière',
            // Matériaux
            'acier',
            'fer forgé',
            'inox',
            'aluminium',
            // Cibles
            'particuliers',
            'professionnels',
            'formation'
        );
        
        // Variations de descriptions pour éviter le contenu dupliqué (enrichies)
        $descriptions = array(
            "AL Métallerie, artisan métallier-ferronnier et soudeur professionnel à Thiers (63) dans le Puy-de-Dôme. Fabrication sur mesure de portails, garde-corps, escaliers et ferronnerie d'art pour particuliers et professionnels en Auvergne-Rhône-Alpes.",
            "Découvrez le savoir-faire d'AL Métallerie à Thiers (63300). Soudure, forge et création artisanale de structures métalliques : portails, garde-corps, escaliers, pergolas. Expert ferronnerie Puy-de-Dôme.",
            "Expert en métallerie, soudure et ferronnerie à Thiers, AL Métallerie réalise vos projets sur mesure en acier, fer forgé et inox. Qualité artisanale au cœur de l'Auvergne, département 63.",
            "Atelier de métallerie et soudure AL Métallerie à Thiers (63). Fabrication artisanale, formations soudeur et réalisations sur mesure : portails, escaliers, garde-corps près de Clermont-Ferrand.",
            "AL Métallerie Thiers 63 - Artisan ferronnier et soudeur spécialisé dans la création métallique sur mesure. Portails, escaliers, garde-corps, pergolas en Puy-de-Dôme et Auvergne.",
            "Ferronnier d'art et métallier à Thiers (63300), AL Métallerie propose soudure professionnelle, forge traditionnelle et créations sur mesure. Devis gratuit en Puy-de-Dôme.",
            "AL Métallerie - Votre expert soudure et métallerie à Thiers, Auvergne. Fabrication de portails, garde-corps, escaliers en acier et fer forgé. Artisan qualifié département 63.",
            "Spécialiste métallerie et ferronnerie d'art à Thiers (63), AL Métallerie allie tradition du fer forgé et techniques modernes de soudure. Interventions Puy-de-Dôme et Clermont-Ferrand."
        );
        
        // Variations de titres (enrichies)
        $titles = array(
            "AL Métallerie Thiers 63 - Artisan Métallier Ferronnier Soudeur Puy-de-Dôme",
            "Métallerie Soudure sur mesure Thiers (63) - AL Métallerie Auvergne",
            "Ferronnier d'art et Soudeur Thiers - AL Métallerie Puy-de-Dôme 63",
            "AL Métallerie - Soudure Forge Création métallique Thiers 63300",
            "Artisan métallier soudeur Thiers - AL Métallerie Auvergne-Rhône-Alpes",
            "AL Métallerie Thiers - Ferronnerie Soudure Portails Escaliers 63",
            "Métallerie Ferronnerie Thiers 63 - AL Métallerie près Clermont-Ferrand",
            "AL Métallerie - Expert Soudure Fer Forgé Acier Thiers Puy-de-Dôme"
        );
        
        // Variations d'alt text (optimisés pour l'accessibilité + SEO)
        $alt_texts = array(
            "AL Métallerie artisan métallier soudeur Thiers 63 Puy-de-Dôme",
            "Atelier soudure métallerie AL Métallerie Thiers Auvergne",
            "Création métallique fer forgé AL Métallerie Thiers 63300",
            "Ferronnier soudeur professionnel AL Métallerie Thiers Puy-de-Dôme",
            "Métallerie artisanale soudure AL Métallerie Thiers 63 Auvergne",
            "Forge ferronnerie d'art AL Métallerie Thiers département 63",
            "Soudeur métallier AL Métallerie Thiers près Clermont-Ferrand",
            "Travail du métal acier inox AL Métallerie Thiers Puy-de-Dôme 63"
        );
        
        // Variations de captions (enrichies)
        $captions = array(
            "AL Métallerie - Artisan métallier-ferronnier et soudeur à Thiers (63) Puy-de-Dôme",
            "Excellence de la métallerie et soudure artisanale à Thiers avec AL Métallerie",
            "AL Métallerie Thiers 63 - L'art du métal, de la forge et de la soudure",
            "Création sur mesure en fer forgé et acier par AL Métallerie, Thiers (63300)",
            "AL Métallerie - Savoir-faire artisanal en soudure et ferronnerie depuis Thiers",
            "Portails, escaliers, garde-corps sur mesure - AL Métallerie Thiers Auvergne",
            "Ferronnier d'art et soudeur qualifié - AL Métallerie Thiers Puy-de-Dôme",
            "AL Métallerie - Votre partenaire métallerie et soudure en Auvergne-Rhône-Alpes"
        );
        
        // Sélectionner aléatoirement pour éviter le contenu dupliqué
        $random_index = $attachment_id % count($descriptions);
        
        // Sélectionner un sous-ensemble de mots-clés (les plus importants)
        $priority_keywords = array(
            'Thiers', '63', 'Puy-de-Dôme', 'Auvergne',
            'métallerie', 'ferronnier', 'soudure', 'soudeur',
            'fer forgé', 'sur mesure', 'artisan',
            'portail', 'garde-corps', 'escalier'
        );
        
        return array(
            'alt_text' => $alt_texts[$random_index],
            'title' => $titles[$random_index],
            'caption' => $captions[$random_index],
            'description' => $descriptions[$random_index],
            'keywords' => implode(', ', $priority_keywords)
        );
    }
    
    /**
     * Mettre à jour les métadonnées SEO d'un attachment
     * 
     * @param int $attachment_id ID de l'attachment
     * @param array $seo_metadata Métadonnées à appliquer
     */
    public static function update_attachment_seo_metadata($attachment_id, $seo_metadata) {
        // Mettre à jour le titre et la description de l'attachment
        wp_update_post(array(
            'ID' => $attachment_id,
            'post_title' => $seo_metadata['title'],
            'post_excerpt' => $seo_metadata['caption'], // Caption = excerpt
            'post_content' => $seo_metadata['description']
        ));
        
        // Mettre à jour l'alt text (stocké en meta)
        update_post_meta($attachment_id, '_wp_attachment_image_alt', $seo_metadata['alt_text']);
        
        // Ajouter des métadonnées personnalisées pour le SEO
        update_post_meta($attachment_id, '_almetal_seo_keywords', $seo_metadata['keywords']);
        update_post_meta($attachment_id, '_almetal_seo_optimized', true);
        update_post_meta($attachment_id, '_almetal_seo_date', current_time('mysql'));
    }
}

// Initialiser la classe
$almetal_slideshow = new Almetal_Slideshow_Admin();

/**
 * Appliquer automatiquement les métadonnées SEO lors de l'upload d'une image
 * Cela remplit les champs Alt, Title, Caption, Description dès l'upload
 */
function almetal_auto_seo_on_upload($attachment_id) {
    // Vérifier que c'est une image
    if (!wp_attachment_is_image($attachment_id)) {
        return;
    }
    
    // Vérifier si on est sur la page du slideshow admin (via referer)
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    
    // Appliquer le SEO pour toutes les images uploadées (pas seulement slideshow)
    // Cela améliore le SEO global du site
    
    // Générer les métadonnées SEO
    $seo_metadata = Almetal_Slideshow_Admin::generate_seo_metadata($attachment_id);
    
    // Appliquer les métadonnées
    Almetal_Slideshow_Admin::update_attachment_seo_metadata($attachment_id, $seo_metadata);
    
    // Log pour debug
    error_log('AL Métallerie SEO: Métadonnées appliquées à l\'image #' . $attachment_id);
}
add_action('add_attachment', 'almetal_auto_seo_on_upload');

/**
 * AJAX : Convertir une image en WebP pour le slideshow
 */
function almetal_ajax_convert_slideshow_image() {
    // Vérifier le nonce
    check_ajax_referer('almetal_slideshow_nonce', 'nonce');
    
    // Vérifier les permissions
    if (!current_user_can('edit_theme_options')) {
        wp_send_json_error('Permissions insuffisantes');
    }
    
    $attachment_id = isset($_POST['attachment_id']) ? intval($_POST['attachment_id']) : 0;
    
    if (!$attachment_id) {
        wp_send_json_error('ID d\'attachment invalide');
    }
    
    // Convertir l'image
    $result = Almetal_Slideshow_Admin::convert_image_to_webp($attachment_id);
    
    if ($result['success']) {
        wp_send_json_success($result);
    } else {
        wp_send_json_error($result['message']);
    }
}
add_action('wp_ajax_convert_slideshow_image', 'almetal_ajax_convert_slideshow_image');
