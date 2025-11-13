<?php
/**
 * Système de Publication Automatique sur les Réseaux Sociaux
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

class ALMetal_Social_Auto_Publish {
    
    private $api_keys;
    
    public function __construct() {
        // Hooks
        add_action('add_meta_boxes', array($this, 'add_social_meta_box'));
        add_action('save_post_realisation', array($this, 'save_social_meta'), 10, 3);
        add_action('publish_realisation', array($this, 'auto_publish_to_social'), 10, 2);
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_ajax_generate_seo_text', array($this, 'ajax_generate_seo_text'));
        add_action('wp_ajax_test_social_connection', array($this, 'ajax_test_connection'));
        add_action('wp_ajax_refresh_detected_info', array($this, 'ajax_refresh_detected_info'));
        add_action('wp_ajax_attach_image_to_post', array($this, 'ajax_attach_image'));
        add_action('wp_ajax_detach_image_from_post', array($this, 'ajax_detach_image'));
        add_action('wp_ajax_set_featured_image', array($this, 'ajax_set_featured_image'));
        
        // Masquer l'encart "Image principale" pour éviter le doublon
        add_action('admin_head', array($this, 'hide_featured_image_box'));
        
        // Activer automatiquement le support de l'extrait pour les réalisations
        add_action('init', array($this, 'enable_excerpt_for_realisation'));
        
        // Charger les clés API
        $this->api_keys = get_option('almetal_social_api_keys', array());
    }
    
    /**
     * Ajouter la meta box de publication sociale
     */
    public function add_social_meta_box() {
        add_meta_box(
            'almetal_social_publish',
            '📱 Publication sur les Réseaux Sociaux',
            array($this, 'render_social_meta_box'),
            'realisation',
            'side',
            'high'
        );
        
        add_meta_box(
            'almetal_seo_generator',
            '✨ Générateur de Texte SEO',
            array($this, 'render_seo_generator_box'),
            'realisation',
            'normal',
            'high'
        );
    }
    
    /**
     * Afficher la meta box de publication sociale
     */
    public function render_social_meta_box($post) {
        wp_nonce_field('almetal_social_publish', 'almetal_social_nonce');
        
        $publish_facebook = get_post_meta($post->ID, '_almetal_publish_facebook', true);
        $publish_instagram = get_post_meta($post->ID, '_almetal_publish_instagram', true);
        $publish_linkedin = get_post_meta($post->ID, '_almetal_publish_linkedin', true);
        $auto_publish = get_post_meta($post->ID, '_almetal_auto_publish', true);
        
        ?>
        <div class="almetal-social-publish">
            <p>
                <label>
                    <input type="checkbox" name="almetal_auto_publish" value="1" <?php checked($auto_publish, '1'); ?>>
                    <strong>Activer la publication automatique</strong>
                </label>
            </p>
            
            <hr>
            
            <p><strong>Publier sur :</strong></p>
            
            <p>
                <label>
                    <input type="checkbox" name="almetal_publish_facebook" value="1" <?php checked($publish_facebook, '1'); ?>>
                    <span class="dashicons dashicons-facebook"></span> Facebook
                </label>
            </p>
            
            <p>
                <label>
                    <input type="checkbox" name="almetal_publish_instagram" value="1" <?php checked($publish_instagram, '1'); ?>>
                    <span class="dashicons dashicons-instagram"></span> Instagram
                </label>
            </p>
            
            <p>
                <label>
                    <input type="checkbox" name="almetal_publish_linkedin" value="1" <?php checked($publish_linkedin, '1'); ?>>
                    <span class="dashicons dashicons-linkedin"></span> LinkedIn
                </label>
            </p>
            
            <hr>
            
            <p>
                <button type="button" class="button button-secondary" id="test-social-connection">
                    🔌 Tester les connexions
                </button>
            </p>
            
            <div id="social-connection-status" style="margin-top: 10px;"></div>
            
            <?php if ($post->post_status === 'publish') : ?>
                <hr>
                <p>
                    <button type="button" class="button button-primary" id="republish-to-social">
                        🔄 Republier maintenant
                    </button>
                </p>
            <?php endif; ?>
        </div>
        
        <style>
            .almetal-social-publish label {
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .almetal-social-publish .dashicons {
                color: #2271b1;
            }
        </style>
        <?php
    }
    
    /**
     * Afficher les informations détectées
     */
    private function render_detected_info($post_id) {
        // Récupérer les métadonnées
        $client = get_post_meta($post_id, '_almetal_client', true);
        $lieu = get_post_meta($post_id, '_almetal_lieu', true);
        $date_realisation = get_post_meta($post_id, '_almetal_date_realisation', true);
        $duree = get_post_meta($post_id, '_almetal_duree', true);
        $types = wp_get_post_terms($post_id, 'type_realisation');
        
        $has_info = false;
        
        echo '<ul style="margin: 10px 0 0 0;">';
        
        if ($client) {
            echo '<li>👤 Client : ' . esc_html($client) . '</li>';
            $has_info = true;
        }
        
        if ($lieu) {
            echo '<li>📍 Lieu : ' . esc_html($lieu) . '</li>';
            $has_info = true;
        }
        
        if ($date_realisation) {
            echo '<li>📅 Date : ' . date_i18n('F Y', strtotime($date_realisation)) . '</li>';
            $has_info = true;
        }
        
        if ($duree) {
            echo '<li>⏱️ Durée : ' . esc_html($duree) . '</li>';
            $has_info = true;
        }
        
        if ($types && !is_wp_error($types) && !empty($types)) {
            echo '<li>🏷️ Type : ' . implode(', ', wp_list_pluck($types, 'name')) . '</li>';
            $has_info = true;
        }
        
        if (!$has_info) {
            echo '<li style="color: #d63638;">⚠️ Aucune information détectée. Veuillez remplir les champs ci-dessous et cliquer sur "🔄 Rafraîchir".</li>';
        }
        
        echo '</ul>';
    }
    
    /**
     * Afficher la meta box du générateur SEO
     */
    public function render_seo_generator_box($post) {
        $generated_text = get_post_meta($post->ID, '_almetal_generated_seo_text', true);
        $facebook_text = get_post_meta($post->ID, '_almetal_facebook_text', true);
        $instagram_text = get_post_meta($post->ID, '_almetal_instagram_text', true);
        $linkedin_text = get_post_meta($post->ID, '_almetal_linkedin_text', true);
        
        ?>
        <div class="almetal-seo-generator">
            <div class="generator-info" id="detected-info-box">
                <p><strong>📊 Informations détectées :</strong></p>
                <div id="detected-info-content">
                    <?php $this->render_detected_info($post->ID); ?>
                </div>
            </div>
            
            <div class="almetal-action-buttons">
                <button type="button" class="button button-primary button-large" id="refresh-detected-info">
                    🔄 Rafraîchir les informations
                </button>
                <button type="button" class="button button-primary button-large" id="generate-seo-text">
                    ✨ Générer le texte SEO
                </button>
                <button type="button" class="button button-secondary button-large" id="copy-to-description">
                    📝 Copier dans le contenu
                </button>
                <span class="spinner" style="float: none; margin: 0 10px;"></span>
            </div>
            
            <div id="seo-generation-status"></div>
            
            <hr>
            
            <h3>📸 Visuels de la Réalisation</h3>
            <div id="almetal-images-container">
                <button type="button" class="button button-secondary" id="almetal-add-images">
                    ➕ Ajouter des images
                </button>
                <div id="almetal-images-preview" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px; margin-top: 15px;">
                    <?php
                    // Afficher les images existantes
                    $attachments = get_posts(array(
                        'post_type' => 'attachment',
                        'posts_per_page' => -1,
                        'post_parent' => $post->ID,
                        'post_mime_type' => 'image',
                        'orderby' => 'menu_order',
                        'order' => 'ASC'
                    ));
                    
                    $featured_id = get_post_thumbnail_id($post->ID);
                    
                    foreach ($attachments as $attachment) {
                        $img_url = wp_get_attachment_image_url($attachment->ID, 'thumbnail');
                        $is_featured = ($attachment->ID == $featured_id);
                        
                        echo '<div class="almetal-image-item" data-id="' . $attachment->ID . '" style="position: relative;">';
                        echo '<img src="' . esc_url($img_url) . '" style="width: 100%; height: 150px; object-fit: cover; border-radius: 4px; ' . ($is_featured ? 'border: 3px solid #F08B18;' : '') . '">';
                        
                        // Bouton supprimer
                        echo '<button type="button" class="almetal-remove-image" data-id="' . $attachment->ID . '" style="position: absolute; top: 5px; right: 5px; background: red; color: white; border: none; border-radius: 50%; width: 25px; height: 25px; cursor: pointer; font-size: 16px; line-height: 1;">×</button>';
                        
                        // Bouton définir comme image à la une
                        if (!$is_featured) {
                            echo '<button type="button" class="almetal-set-featured" data-id="' . $attachment->ID . '" style="position: absolute; bottom: 5px; left: 5px; background: #F08B18; color: white; border: none; border-radius: 4px; padding: 5px 10px; cursor: pointer; font-size: 11px;">⭐ Image à la une</button>';
                        } else {
                            echo '<div style="position: absolute; bottom: 5px; left: 5px; background: #F08B18; color: white; border-radius: 4px; padding: 5px 10px; font-size: 11px; font-weight: bold;">⭐ Image à la une</div>';
                        }
                        
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
            <p class="description">Ces images seront utilisées pour la publication sur les réseaux sociaux. La première image sera l'image principale.</p>
            
            <hr>
            
            <h3>📝 Texte SEO Généré (Description WordPress)</h3>
            <textarea name="almetal_generated_seo_text" id="almetal_generated_seo_text" rows="6" style="width: 100%;"><?php echo esc_textarea($generated_text); ?></textarea>
            <p class="description">Ce texte sera utilisé comme description de la réalisation et optimisé pour Yoast SEO.</p>
            
            <hr>
            
            <h3>📱 Textes pour les Réseaux Sociaux</h3>
            
            <h4><span class="dashicons dashicons-facebook"></span> Facebook</h4>
            <textarea name="almetal_facebook_text" id="almetal_facebook_text" rows="4" style="width: 100%;"><?php echo esc_textarea($facebook_text); ?></textarea>
            <p class="description">Texte adapté pour Facebook (max 63 206 caractères)</p>
            
            <h4><span class="dashicons dashicons-instagram"></span> Instagram</h4>
            <textarea name="almetal_instagram_text" id="almetal_instagram_text" rows="4" style="width: 100%;"><?php echo esc_textarea($instagram_text); ?></textarea>
            <p class="description">Texte adapté pour Instagram avec hashtags (max 2 200 caractères)</p>
            
            <h4><span class="dashicons dashicons-linkedin"></span> LinkedIn</h4>
            <textarea name="almetal_linkedin_text" id="almetal_linkedin_text" rows="4" style="width: 100%;"><?php echo esc_textarea($linkedin_text); ?></textarea>
            <p class="description">Texte professionnel pour LinkedIn (max 3 000 caractères)</p>
        </div>
        
        <style>
            .almetal-seo-generator {
                padding: 15px;
            }
            .generator-info {
                background: #f0f0f1;
                padding: 15px;
                border-radius: 4px;
                margin-bottom: 15px;
                position: relative;
            }
            .generator-info ul {
                margin: 10px 0 0 0;
            }
            #detected-info-content {
                transition: background 0.3s ease;
                padding: 5px;
                border-radius: 4px;
            }
            .almetal-action-buttons {
                display: flex;
                gap: 15px;
                align-items: center;
                margin: 20px 0;
                flex-wrap: wrap;
            }
            .almetal-action-buttons .button-large {
                flex: 1;
                min-width: 250px;
                text-align: center;
            }
            .almetal-seo-generator h4 {
                margin-top: 15px;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            #seo-generation-status {
                margin: 15px 0;
            }
            .seo-success {
                background: #d4edda;
                border: 1px solid #c3e6cb;
                color: #155724;
                padding: 10px;
                border-radius: 4px;
            }
            .seo-error {
                background: #f8d7da;
                border: 1px solid #f5c6cb;
                color: #721c24;
                padding: 10px;
                border-radius: 4px;
            }
        </style>
        <?php
    }
    
    /**
     * Sauvegarder les métadonnées de publication sociale
     */
    public function save_social_meta($post_id, $post, $update) {
        // Vérifications de sécurité
        if (!isset($_POST['almetal_social_nonce']) || !wp_verify_nonce($_POST['almetal_social_nonce'], 'almetal_social_publish')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Sauvegarder les options de publication
        update_post_meta($post_id, '_almetal_auto_publish', isset($_POST['almetal_auto_publish']) ? '1' : '0');
        update_post_meta($post_id, '_almetal_publish_facebook', isset($_POST['almetal_publish_facebook']) ? '1' : '0');
        update_post_meta($post_id, '_almetal_publish_instagram', isset($_POST['almetal_publish_instagram']) ? '1' : '0');
        update_post_meta($post_id, '_almetal_publish_linkedin', isset($_POST['almetal_publish_linkedin']) ? '1' : '0');
        
        // Sauvegarder les textes générés
        if (isset($_POST['almetal_generated_seo_text'])) {
            update_post_meta($post_id, '_almetal_generated_seo_text', sanitize_textarea_field($_POST['almetal_generated_seo_text']));
        }
        
        if (isset($_POST['almetal_facebook_text'])) {
            update_post_meta($post_id, '_almetal_facebook_text', sanitize_textarea_field($_POST['almetal_facebook_text']));
        }
        
        if (isset($_POST['almetal_instagram_text'])) {
            update_post_meta($post_id, '_almetal_instagram_text', sanitize_textarea_field($_POST['almetal_instagram_text']));
        }
        
        if (isset($_POST['almetal_linkedin_text'])) {
            update_post_meta($post_id, '_almetal_linkedin_text', sanitize_textarea_field($_POST['almetal_linkedin_text']));
        }
    }
    
    /**
     * Publication automatique lors de la publication
     */
    public function auto_publish_to_social($post_id, $post) {
        // Vérifier si la publication automatique est activée
        $auto_publish = get_post_meta($post_id, '_almetal_auto_publish', true);
        
        if ($auto_publish !== '1') {
            return;
        }
        
        // Vérifier quels réseaux sont activés
        $publish_facebook = get_post_meta($post_id, '_almetal_publish_facebook', true);
        $publish_instagram = get_post_meta($post_id, '_almetal_publish_instagram', true);
        $publish_linkedin = get_post_meta($post_id, '_almetal_publish_linkedin', true);
        
        // Publier sur les réseaux sélectionnés
        if ($publish_facebook === '1') {
            $this->publish_to_facebook($post_id);
        }
        
        if ($publish_instagram === '1') {
            $this->publish_to_instagram($post_id);
        }
        
        if ($publish_linkedin === '1') {
            $this->publish_to_linkedin($post_id);
        }
    }
    
    /**
     * Générer le texte SEO avec Hugging Face
     */
    public function ajax_generate_seo_text() {
        check_ajax_referer('almetal_generate_seo', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error('Permission refusée');
        }
        
        $post_id = intval($_POST['post_id']);
        
        // Récupérer les informations de la réalisation
        $post = get_post($post_id);
        $client = get_post_meta($post_id, '_almetal_client', true);
        $lieu = get_post_meta($post_id, '_almetal_lieu', true);
        $date_realisation = get_post_meta($post_id, '_almetal_date_realisation', true);
        $duree = get_post_meta($post_id, '_almetal_duree', true);
        $types = wp_get_post_terms($post_id, 'type_realisation');
        
        // Générer les textes
        require_once get_template_directory() . '/inc/seo-text-generator.php';
        $generator = new ALMetal_SEO_Text_Generator();
        
        $texts = $generator->generate_texts(array(
            'title' => $post->post_title,
            'client' => $client,
            'lieu' => $lieu,
            'date' => $date_realisation,
            'duree' => $duree,
            'types' => $types
        ));
        
        if ($texts) {
            // Sauvegarder les textes générés dans les meta
            update_post_meta($post_id, '_almetal_generated_seo_text', $texts['seo']);
            update_post_meta($post_id, '_almetal_facebook_text', $texts['facebook']);
            update_post_meta($post_id, '_almetal_instagram_text', $texts['instagram']);
            update_post_meta($post_id, '_almetal_linkedin_text', $texts['linkedin']);
            
            wp_send_json_success($texts);
        } else {
            wp_send_json_error('Erreur lors de la génération du texte');
        }
    }
    
    /**
     * Rafraîchir les informations détectées (AJAX)
     */
    public function ajax_refresh_detected_info() {
        check_ajax_referer('almetal_refresh_info', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error('Permission refusée');
        }
        
        $post_id = intval($_POST['post_id']);
        
        // Sauvegarder d'abord les champs s'ils sont envoyés
        if (isset($_POST['fields'])) {
            $fields = $_POST['fields'];
            
            if (isset($fields['client'])) {
                update_post_meta($post_id, '_almetal_client', sanitize_text_field($fields['client']));
            }
            
            if (isset($fields['lieu'])) {
                update_post_meta($post_id, '_almetal_lieu', sanitize_text_field($fields['lieu']));
            }
            
            if (isset($fields['date'])) {
                update_post_meta($post_id, '_almetal_date_realisation', sanitize_text_field($fields['date']));
            }
            
            if (isset($fields['duree'])) {
                update_post_meta($post_id, '_almetal_duree', sanitize_text_field($fields['duree']));
            }
        }
        
        // Générer le HTML des informations détectées
        ob_start();
        $this->render_detected_info($post_id);
        $html = ob_get_clean();
        
        wp_send_json_success(array('html' => $html));
    }
    
    /**
     * Tester la connexion aux réseaux sociaux
     */
    public function ajax_test_connection() {
        check_ajax_referer('almetal_test_social', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission refusée');
        }
        
        $results = array(
            'facebook' => $this->test_facebook_connection(),
            'instagram' => $this->test_instagram_connection(),
            'linkedin' => $this->test_linkedin_connection()
        );
        
        wp_send_json_success($results);
    }
    
    /**
     * Publier sur Facebook
     */
    private function publish_to_facebook($post_id) {
        // TODO: Implémenter la publication Facebook
        // Sera activé quand les clés API seront configurées
        error_log('Publication Facebook pour le post ' . $post_id);
    }
    
    /**
     * Publier sur Instagram
     */
    private function publish_to_instagram($post_id) {
        // TODO: Implémenter la publication Instagram
        error_log('Publication Instagram pour le post ' . $post_id);
    }
    
    /**
     * Publier sur LinkedIn
     */
    private function publish_to_linkedin($post_id) {
        // TODO: Implémenter la publication LinkedIn
        error_log('Publication LinkedIn pour le post ' . $post_id);
    }
    
    /**
     * Tester la connexion Facebook
     */
    private function test_facebook_connection() {
        // TODO: Implémenter le test
        return array('status' => 'not_configured', 'message' => 'API non configurée');
    }
    
    /**
     * Tester la connexion Instagram
     */
    private function test_instagram_connection() {
        // TODO: Implémenter le test
        return array('status' => 'not_configured', 'message' => 'API non configurée');
    }
    
    /**
     * Tester la connexion LinkedIn
     */
    private function test_linkedin_connection() {
        // TODO: Implémenter le test
        return array('status' => 'not_configured', 'message' => 'API non configurée');
    }
    
    /**
     * Attacher une image au post (AJAX)
     */
    public function ajax_attach_image() {
        check_ajax_referer('almetal_generate_seo', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error('Permission refusée');
        }
        
        $post_id = intval($_POST['post_id']);
        $attachment_id = intval($_POST['attachment_id']);
        
        // Attacher l'image au post
        wp_update_post(array(
            'ID' => $attachment_id,
            'post_parent' => $post_id
        ));
        
        // Récupérer l'URL de la miniature
        $thumbnail_url = wp_get_attachment_image_url($attachment_id, 'thumbnail');
        
        // Si pas de miniature, utiliser l'image complète
        if (!$thumbnail_url) {
            $thumbnail_url = wp_get_attachment_url($attachment_id);
        }
        
        wp_send_json_success(array(
            'thumbnail_url' => $thumbnail_url,
            'attachment_id' => $attachment_id
        ));
    }
    
    /**
     * Détacher une image du post (AJAX)
     */
    public function ajax_detach_image() {
        check_ajax_referer('almetal_generate_seo', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error('Permission refusée');
        }
        
        $attachment_id = intval($_POST['attachment_id']);
        
        // Détacher l'image (mettre post_parent à 0)
        wp_update_post(array(
            'ID' => $attachment_id,
            'post_parent' => 0
        ));
        
        wp_send_json_success();
    }
    
    /**
     * Définir une image comme image à la une (AJAX)
     */
    public function ajax_set_featured_image() {
        check_ajax_referer('almetal_generate_seo', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error('Permission refusée');
        }
        
        $post_id = intval($_POST['post_id']);
        $attachment_id = intval($_POST['attachment_id']);
        
        // Définir l'image à la une
        $result = set_post_thumbnail($post_id, $attachment_id);
        
        if ($result) {
            wp_send_json_success();
        } else {
            wp_send_json_error('Impossible de définir l\'image à la une');
        }
    }
    
    /**
     * Activer le support de l'extrait pour les réalisations
     */
    public function enable_excerpt_for_realisation() {
        add_post_type_support('realisation', 'excerpt');
    }
    
    /**
     * Masquer l'encart "Image principale" dans la sidebar
     */
    public function hide_featured_image_box() {
        global $post_type;
        
        if ($post_type === 'realisation') {
            echo '<style>
                #postimagediv {
                    display: none !important;
                }
            </style>';
        }
    }
    
    /**
     * Charger les scripts admin
     */
    public function enqueue_admin_scripts($hook) {
        global $post_type;
        
        if ($post_type !== 'realisation') {
            return;
        }
        
        // Enqueue WordPress media uploader
        wp_enqueue_media();
        
        wp_enqueue_script(
            'almetal-social-publish',
            get_template_directory_uri() . '/assets/js/admin-social-publish.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
        wp_localize_script('almetal-social-publish', 'almetalSocial', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce_generate' => wp_create_nonce('almetal_generate_seo'),
            'nonce_test' => wp_create_nonce('almetal_test_social'),
            'nonce_refresh' => wp_create_nonce('almetal_refresh_info'),
            'settings_url' => admin_url('options-general.php?page=almetal-social-settings')
        ));
    }
}

// Initialiser
new ALMetal_Social_Auto_Publish();
