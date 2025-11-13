<?php
/**
 * Page de Configuration des API Réseaux Sociaux
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

class ALMetal_Social_Settings_Page {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
    }
    
    /**
     * Ajouter la page de réglages
     */
    public function add_settings_page() {
        add_options_page(
            'Publication Sociale',
            'Publication Sociale',
            'manage_options',
            'almetal-social-settings',
            array($this, 'render_settings_page')
        );
    }
    
    /**
     * Enregistrer les réglages
     */
    public function register_settings() {
        // Section Hugging Face
        add_settings_section(
            'almetal_huggingface_section',
            '🤖 Hugging Face API (Génération de Texte)',
            array($this, 'render_huggingface_section'),
            'almetal-social-settings'
        );
        
        register_setting('almetal_social_settings', 'almetal_huggingface_api_key');
        
        add_settings_field(
            'almetal_huggingface_api_key',
            'Clé API Hugging Face',
            array($this, 'render_huggingface_field'),
            'almetal-social-settings',
            'almetal_huggingface_section'
        );
        
        // Section Facebook
        add_settings_section(
            'almetal_facebook_section',
            '📘 Facebook API',
            array($this, 'render_facebook_section'),
            'almetal-social-settings'
        );
        
        register_setting('almetal_social_settings', 'almetal_facebook_app_id');
        register_setting('almetal_social_settings', 'almetal_facebook_app_secret');
        register_setting('almetal_social_settings', 'almetal_facebook_page_id');
        register_setting('almetal_social_settings', 'almetal_facebook_access_token');
        
        add_settings_field(
            'almetal_facebook_app_id',
            'App ID',
            array($this, 'render_facebook_app_id_field'),
            'almetal-social-settings',
            'almetal_facebook_section'
        );
        
        add_settings_field(
            'almetal_facebook_app_secret',
            'App Secret',
            array($this, 'render_facebook_app_secret_field'),
            'almetal-social-settings',
            'almetal_facebook_section'
        );
        
        add_settings_field(
            'almetal_facebook_page_id',
            'Page ID',
            array($this, 'render_facebook_page_id_field'),
            'almetal-social-settings',
            'almetal_facebook_section'
        );
        
        add_settings_field(
            'almetal_facebook_access_token',
            'Access Token',
            array($this, 'render_facebook_access_token_field'),
            'almetal-social-settings',
            'almetal_facebook_section'
        );
        
        // Section Instagram
        add_settings_section(
            'almetal_instagram_section',
            '📷 Instagram API',
            array($this, 'render_instagram_section'),
            'almetal-social-settings'
        );
        
        register_setting('almetal_social_settings', 'almetal_instagram_user_id');
        register_setting('almetal_social_settings', 'almetal_instagram_access_token');
        
        add_settings_field(
            'almetal_instagram_user_id',
            'User ID',
            array($this, 'render_instagram_user_id_field'),
            'almetal-social-settings',
            'almetal_instagram_section'
        );
        
        add_settings_field(
            'almetal_instagram_access_token',
            'Access Token',
            array($this, 'render_instagram_access_token_field'),
            'almetal-social-settings',
            'almetal_instagram_section'
        );
        
        // Section LinkedIn
        add_settings_section(
            'almetal_linkedin_section',
            '💼 LinkedIn API',
            array($this, 'render_linkedin_section'),
            'almetal-social-settings'
        );
        
        register_setting('almetal_social_settings', 'almetal_linkedin_client_id');
        register_setting('almetal_social_settings', 'almetal_linkedin_client_secret');
        register_setting('almetal_social_settings', 'almetal_linkedin_access_token');
        register_setting('almetal_social_settings', 'almetal_linkedin_organization_id');
        
        add_settings_field(
            'almetal_linkedin_client_id',
            'Client ID',
            array($this, 'render_linkedin_client_id_field'),
            'almetal-social-settings',
            'almetal_linkedin_section'
        );
        
        add_settings_field(
            'almetal_linkedin_client_secret',
            'Client Secret',
            array($this, 'render_linkedin_client_secret_field'),
            'almetal-social-settings',
            'almetal_linkedin_section'
        );
        
        add_settings_field(
            'almetal_linkedin_access_token',
            'Access Token',
            array($this, 'render_linkedin_access_token_field'),
            'almetal-social-settings',
            'almetal_linkedin_section'
        );
        
        add_settings_field(
            'almetal_linkedin_organization_id',
            'Organization ID',
            array($this, 'render_linkedin_organization_id_field'),
            'almetal-social-settings',
            'almetal_linkedin_section'
        );
    }
    
    /**
     * Afficher la page de réglages
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1>⚙️ Configuration de la Publication Sociale</h1>
            
            <div class="notice notice-info">
                <p><strong>ℹ️ Information :</strong> Cette page vous permet de configurer les API pour la publication automatique sur les réseaux sociaux.</p>
            </div>
            
            <form method="post" action="options.php">
                <?php
                settings_fields('almetal_social_settings');
                do_settings_sections('almetal-social-settings');
                submit_button('💾 Enregistrer les réglages');
                ?>
            </form>
            
            <hr>
            
            <h2>📚 Guides de Configuration</h2>
            
            <div class="almetal-guides">
                <div class="guide-card">
                    <h3>🤖 Hugging Face</h3>
                    <ol>
                        <li>Créez un compte sur <a href="https://huggingface.co/" target="_blank">huggingface.co</a></li>
                        <li>Allez dans Settings → Access Tokens</li>
                        <li>Créez un nouveau token avec les droits "Read"</li>
                        <li>Copiez le token et collez-le ci-dessus</li>
                    </ol>
                    <p><strong>Gratuit :</strong> 1000 requêtes/mois</p>
                </div>
                
                <div class="guide-card">
                    <h3>📘 Facebook</h3>
                    <ol>
                        <li>Allez sur <a href="https://developers.facebook.com/" target="_blank">developers.facebook.com</a></li>
                        <li>Créez une nouvelle application</li>
                        <li>Ajoutez le produit "Facebook Login"</li>
                        <li>Récupérez l'App ID et l'App Secret</li>
                        <li>Générez un Page Access Token</li>
                    </ol>
                </div>
                
                <div class="guide-card">
                    <h3>📷 Instagram</h3>
                    <ol>
                        <li>Instagram utilise l'API Facebook</li>
                        <li>Convertissez votre compte en compte professionnel</li>
                        <li>Liez-le à votre page Facebook</li>
                        <li>Utilisez le même Access Token que Facebook</li>
                        <li>Récupérez votre Instagram Business Account ID</li>
                    </ol>
                </div>
                
                <div class="guide-card">
                    <h3>💼 LinkedIn</h3>
                    <ol>
                        <li>Allez sur <a href="https://www.linkedin.com/developers/" target="_blank">linkedin.com/developers</a></li>
                        <li>Créez une nouvelle application</li>
                        <li>Demandez l'accès à "Share on LinkedIn"</li>
                        <li>Récupérez le Client ID et Client Secret</li>
                        <li>Générez un Access Token</li>
                    </ol>
                </div>
            </div>
        </div>
        
        <style>
            .almetal-guides {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 20px;
                margin-top: 20px;
            }
            .guide-card {
                background: white;
                border: 1px solid #ccd0d4;
                border-radius: 4px;
                padding: 20px;
                box-shadow: 0 1px 1px rgba(0,0,0,0.04);
            }
            .guide-card h3 {
                margin-top: 0;
                color: #2271b1;
            }
            .guide-card ol {
                padding-left: 20px;
            }
            .guide-card li {
                margin-bottom: 8px;
            }
        </style>
        <?php
    }
    
    // Sections
    public function render_huggingface_section() {
        echo '<p>Hugging Face est utilisé pour générer automatiquement les textes SEO et les descriptions pour les réseaux sociaux.</p>';
        echo '<p><strong>Gratuit :</strong> 1000 requêtes par mois suffisent largement pour vos besoins.</p>';
    }
    
    public function render_facebook_section() {
        echo '<p>Configurez l\'API Facebook pour publier automatiquement sur votre page Facebook.</p>';
    }
    
    public function render_instagram_section() {
        echo '<p>Instagram utilise l\'API Facebook. Assurez-vous d\'avoir un compte Instagram Business lié à votre page Facebook.</p>';
    }
    
    public function render_linkedin_section() {
        echo '<p>Configurez l\'API LinkedIn pour publier sur votre page d\'entreprise.</p>';
    }
    
    // Champs Hugging Face
    public function render_huggingface_field() {
        $value = get_option('almetal_huggingface_api_key', '');
        echo '<input type="text" name="almetal_huggingface_api_key" value="' . esc_attr($value) . '" class="regular-text" placeholder="hf_xxxxxxxxxxxxxxxxxxxxx">';
        echo '<p class="description">Votre clé API Hugging Face (commence par "hf_")</p>';
    }
    
    // Champs Facebook
    public function render_facebook_app_id_field() {
        $value = get_option('almetal_facebook_app_id', '');
        echo '<input type="text" name="almetal_facebook_app_id" value="' . esc_attr($value) . '" class="regular-text">';
    }
    
    public function render_facebook_app_secret_field() {
        $value = get_option('almetal_facebook_app_secret', '');
        echo '<input type="password" name="almetal_facebook_app_secret" value="' . esc_attr($value) . '" class="regular-text">';
    }
    
    public function render_facebook_page_id_field() {
        $value = get_option('almetal_facebook_page_id', '');
        echo '<input type="text" name="almetal_facebook_page_id" value="' . esc_attr($value) . '" class="regular-text">';
    }
    
    public function render_facebook_access_token_field() {
        $value = get_option('almetal_facebook_access_token', '');
        echo '<textarea name="almetal_facebook_access_token" rows="3" class="large-text">' . esc_textarea($value) . '</textarea>';
        echo '<p class="description">Page Access Token (ne jamais partager)</p>';
    }
    
    // Champs Instagram
    public function render_instagram_user_id_field() {
        $value = get_option('almetal_instagram_user_id', '');
        echo '<input type="text" name="almetal_instagram_user_id" value="' . esc_attr($value) . '" class="regular-text">';
        echo '<p class="description">Instagram Business Account ID</p>';
    }
    
    public function render_instagram_access_token_field() {
        $value = get_option('almetal_instagram_access_token', '');
        echo '<textarea name="almetal_instagram_access_token" rows="3" class="large-text">' . esc_textarea($value) . '</textarea>';
        echo '<p class="description">Même token que Facebook si le compte est lié</p>';
    }
    
    // Champs LinkedIn
    public function render_linkedin_client_id_field() {
        $value = get_option('almetal_linkedin_client_id', '');
        echo '<input type="text" name="almetal_linkedin_client_id" value="' . esc_attr($value) . '" class="regular-text">';
    }
    
    public function render_linkedin_client_secret_field() {
        $value = get_option('almetal_linkedin_client_secret', '');
        echo '<input type="password" name="almetal_linkedin_client_secret" value="' . esc_attr($value) . '" class="regular-text">';
    }
    
    public function render_linkedin_access_token_field() {
        $value = get_option('almetal_linkedin_access_token', '');
        echo '<textarea name="almetal_linkedin_access_token" rows="3" class="large-text">' . esc_textarea($value) . '</textarea>';
    }
    
    public function render_linkedin_organization_id_field() {
        $value = get_option('almetal_linkedin_organization_id', '');
        echo '<input type="text" name="almetal_linkedin_organization_id" value="' . esc_attr($value) . '" class="regular-text">';
        echo '<p class="description">ID de votre page d\'entreprise LinkedIn</p>';
    }
}

// Initialiser
new ALMetal_Social_Settings_Page();
