<?php
/**
 * Interface d'administration du plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

class AICG_Admin {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_aicg_generate_content', array($this, 'ajax_generate_content'));
        add_action('wp_ajax_aicg_test_ollama', array($this, 'ajax_test_ollama'));
    }
    
    /**
     * Ajouter le menu admin
     */
    public function add_admin_menu() {
        add_menu_page(
            'AI Content Generator',
            'AI Generator',
            'manage_options',
            'aicg-dashboard',
            array($this, 'render_dashboard'),
            'dashicons-art',
            25
        );
        
        add_submenu_page(
            'aicg-dashboard',
            'Générateur',
            'Générateur',
            'manage_options',
            'aicg-dashboard',
            array($this, 'render_dashboard')
        );
        
        add_submenu_page(
            'aicg-dashboard',
            'Paramètres',
            'Paramètres',
            'manage_options',
            'aicg-settings',
            array($this, 'render_settings')
        );
    }
    
    /**
     * Charger les scripts
     */
    public function enqueue_scripts($hook) {
        if (strpos($hook, 'aicg-') === false) {
            return;
        }
        
        wp_enqueue_style('aicg-admin', AICG_URL . 'assets/css/admin.css', array(), '1.0.0');
        wp_enqueue_script('aicg-admin', AICG_URL . 'assets/js/admin.js', array('jquery'), '1.0.0', true);
        
        wp_localize_script('aicg-admin', 'aicg_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aicg_nonce')
        ));
    }
    
    /**
     * Page dashboard
     */
    public function render_dashboard() {
        $generator = AICG_AI_Generator::get_instance();
        $templates = AICG_Content_Templates::get_instance();
        
        // Vérifier Ollama
        $ollama_available = $generator->is_ollama_available();
        $models = $generator->get_available_models();
        $stats = $generator->get_usage_stats();
        ?>
        <div class="wrap aicg-wrap">
            <h1>AI Content Generator</h1>
            
            <?php if (!$ollama_available): ?>
                <div class="notice notice-error">
                    <p><strong>Ollama n'est pas disponible!</strong></p>
                    <p>Veuillez installer Ollama sur votre serveur. Instructions ci-dessous.</p>
                </div>
            <?php endif; ?>
            
            <div class="aicg-grid">
                <!-- Générateur de contenu -->
                <div class="aicg-card">
                    <h2>Générer du contenu</h2>
                    
                    <form id="aicg-generator-form">
                        <table class="form-table">
                            <tr>
                                <th><label>Type de contenu</label></th>
                                <td>
                                    <select name="template_type" id="template_type" required>
                                        <option value="">Sélectionner...</option>
                                        <?php foreach ($templates->get_available_templates() as $key => $label): ?>
                                            <option value="<?php echo $key; ?>"><?php echo $label; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr id="data-fields">
                                <th><label>Données</label></th>
                                <td>
                                    <div id="dynamic-fields">
                                        <!-- Les champs seront ajoutés dynamiquement -->
                                    </div>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><label>Modèle IA</label></th>
                                <td>
                                    <select name="model" id="model">
                                        <?php foreach ($models as $model): ?>
                                            <option value="<?php echo $model; ?>" <?php selected($model, get_option('aicg_default_model')); ?>>
                                                <?php echo $model; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        
                        <p class="submit">
                            <button type="submit" class="button button-primary" id="generate-btn">
                                Générer le contenu
                            </button>
                            <span class="spinner" style="display:none;"></span>
                        </p>
                    </form>
                    
                    <div id="result-container" style="display:none;">
                        <h3>Résultat:</h3>
                        <textarea id="generated-content" rows="10" class="large-text" readonly></textarea>
                        <p>
                            <button type="button" class="button" id="copy-content">Copier</button>
                            <button type="button" class="button" id="regenerate-content">Regénérer</button>
                        </p>
                    </div>
                </div>
                
                <!-- Statistiques -->
                <div class="aicg-card">
                    <h2>Statistiques</h2>
                    <table class="widefat">
                        <tr>
                            <td>Total généré</td>
                            <td><?php echo $stats['total_generated']; ?> contenus</td>
                        </tr>
                        <tr>
                            <td>Dernière génération</td>
                            <td><?php echo $stats['last_generated'] ? date('d/m/Y H:i', strtotime($stats['last_generated'])) : 'Jamais'; ?></td>
                        </tr>
                    </table>
                    
                    <h3>Par type</h3>
                    <table class="widefat">
                        <?php foreach ($stats['by_type'] as $type): ?>
                            <tr>
                                <td><?php echo $type->content_type; ?></td>
                                <td><?php echo $type->count; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
            
            <!-- Instructions Ollama -->
            <div class="aicg-card">
                <h2>Installer Ollama (Serveur)</h2>
                <p><strong>Étapes d'installation:</strong></p>
                <ol>
                    <li>Connectez-vous en SSH à votre serveur</li>
                    <li>Exécutez: <code>curl -fsSL https://ollama.com/install.sh | sh</code></li>
                    <li>Téléchargez un modèle: <code>ollama pull llama3.1:8b</code></li>
                    <li>Vérifiez: <code>ollama list</code></li>
                </ol>
                
                <p><strong>Modèles recommandés:</strong></p>
                <ul>
                    <li><code>llama3.1:8b</code> - Équilibre qualité/performance</li>
                    <li><code>qwen2.5:7b</code> - Excellent en français</li>
                    <li><code>mistral:7b</code> - Plus rapide</li>
                </ul>
            </div>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            // Champs dynamiques selon le type
            $('#template_type').on('change', function() {
                var type = $(this).val();
                var fieldsContainer = $('#dynamic-fields');
                
                fieldsContainer.empty();
                
                switch(type) {
                    case 'realisation':
                        fieldsContainer.html(`
                            <input type="text" name="type" placeholder="Type de projet (ex: portail, escalier)" class="regular-text">
                            <input type="text" name="materials" placeholder="Matériaux utilisés" class="regular-text">
                            <input type="text" name="client" placeholder="Type de client" class="regular-text">
                            <input type="text" name="date" placeholder="Date de réalisation" class="regular-text">
                        `);
                        break;
                        
                    case 'city_page':
                        fieldsContainer.html(`
                            <input type="text" name="city" placeholder="Nom de la ville" class="regular-text" required>
                            <input type="text" name="department" placeholder="Département" class="regular-text">
                            <input type="text" name="population" placeholder="Population" class="regular-text">
                            <input type="text" name="specifics" placeholder="Particularités locales" class="regular-text">
                            <input type="text" name="services" placeholder="Services adaptés" class="regular-text">
                        `);
                        break;
                        
                    case 'meta_description':
                        fieldsContainer.html(`
                            <input type="text" name="type" placeholder="Type de page" class="regular-text">
                            <input type="text" name="subject" placeholder="Sujet principal" class="regular-text">
                            <input type="text" name="location" placeholder="Localisation" class="regular-text">
                        `);
                        break;
                        
                    case 'content_improvement':
                        fieldsContainer.html(`
                            <textarea name="content" placeholder="Contenu à améliorer" class="large-text" rows="5"></textarea>
                            <input type="number" name="target_length" placeholder="Longueur cible à ajouter" class="small-text">
                            <input type="text" name="keywords" placeholder="Mots-clés (séparés par des virgules)" class="regular-text">
                        `);
                        break;
                        
                    case 'testimonial':
                        fieldsContainer.html(`
                            <input type="text" name="service" placeholder="Service concerné" class="regular-text">
                            <input type="text" name="location" placeholder="Localisation" class="regular-text">
                            <select name="client_type">
                                <option value="particulier">Particulier</option>
                                <option value="professionnel">Professionnel</option>
                            </select>
                        `);
                        break;
                }
            });
            
            // Génération AJAX
            $('#aicg-generator-form').on('submit', function(e) {
                e.preventDefault();
                
                $('#generate-btn').prop('disabled', true);
                $('.spinner').show();
                
                var formData = $(this).serializeArray();
                var data = {};
                
                $.each(formData, function(i, field) {
                    data[field.name] = field.value;
                });
                
                $.post(aicg_ajax.ajax_url, {
                    action: 'aicg_generate_content',
                    data: data,
                    nonce: aicg_ajax.nonce
                }, function(response) {
                    $('.spinner').hide();
                    $('#generate-btn').prop('disabled', false);
                    
                    if (response.success) {
                        $('#generated-content').val(response.data.content);
                        $('#result-container').show();
                    } else {
                        alert('Erreur: ' + response.data);
                    }
                });
            });
            
            // Copier le contenu
            $('#copy-content').on('click', function() {
                $('#generated-content').select();
                document.execCommand('copy');
                $(this).text('Copié!').delay(1000).queue(function() {
                    $(this).text('Copier').dequeue();
                });
            });
        });
        </script>
        <?php
    }
    
    /**
     * Page des paramètres
     */
    public function render_settings() {
        if (isset($_POST['submit'])) {
            update_option('aicg_ollama_url', sanitize_text_field($_POST['ollama_url']));
            update_option('aicg_default_model', sanitize_text_field($_POST['default_model']));
            update_option('aicg_temperature', floatval($_POST['temperature']));
            update_option('aicg_max_tokens', intval($_POST['max_tokens']));
            
            echo '<div class="notice notice-success"><p>Paramètres sauvegardés!</p></div>';
        }
        
        $ollama_url = get_option('aicg_ollama_url', 'http://localhost:11434');
        $default_model = get_option('aicg_default_model', 'llama3.1:8b');
        $temperature = get_option('aicg_temperature', '0.7');
        $max_tokens = get_option('aicg_max_tokens', '2000');
        ?>
        <div class="wrap">
            <h1>Paramètres AI Content Generator</h1>
            
            <form method="post">
                <table class="form-table">
                    <tr>
                        <th><label>URL Ollama</label></th>
                        <td>
                            <input type="text" name="ollama_url" value="<?php echo $ollama_url; ?>" class="regular-text">
                            <p class="description">URL de l'API Ollama (généralement http://localhost:11434)</p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th><label>Modèle par défaut</label></th>
                        <td>
                            <input type="text" name="default_model" value="<?php echo $default_model; ?>" class="regular-text">
                            <p class="description">Modèle IA utilisé par défaut (ex: llama3.1:8b)</p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th><label>Température</label></th>
                        <td>
                            <input type="number" name="temperature" value="<?php echo $temperature; ?>" step="0.1" min="0" max="2" class="small-text">
                            <p class="description">0 = très prévisible, 1 = créatif, 2 = très créatif</p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th><label>Max tokens</label></th>
                        <td>
                            <input type="number" name="max_tokens" value="<?php echo $max_tokens; ?>" min="100" max="8000" class="small-text">
                            <p class="description">Longueur maximale du contenu généré</p>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <input type="submit" name="submit" class="button button-primary" value="Sauvegarder">
                </p>
            </form>
        </div>
        <?php
    }
    
    /**
     * AJAX: Générer du contenu
     */
    public function ajax_generate_content() {
        check_ajax_referer('aicg_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Permission denied');
        }
        
        $data = $_POST['data'];
        $template_type = $data['template_type'];
        
        // Obtenir le template
        $templates = AICG_Content_Templates::get_instance();
        $prompt_method = 'get_' . $template_type . '_prompt';
        
        if (!method_exists($templates, $prompt_method)) {
            wp_send_json_error('Template non trouvé');
        }
        
        $prompt = $templates->$prompt_method($data);
        
        // Générer le contenu
        $generator = AICG_AI_Generator::get_instance();
        
        // Utiliser le modèle spécifié
        if (!empty($data['model'])) {
            $generator->model = $data['model'];
        }
        
        $content = $generator->generate_content($prompt);
        
        if (is_wp_error($content)) {
            wp_send_json_error($content->get_error_message());
        }
        
        wp_send_json_success(array('content' => $content));
    }
    
    /**
     * AJAX: Tester Ollama
     */
    public function ajax_test_ollama() {
        check_ajax_referer('aicg_nonce', 'nonce');
        
        $generator = AICG_AI_Generator::get_instance();
        $available = $generator->is_ollama_available();
        $models = $generator->get_available_models();
        
        wp_send_json_success(array(
            'available' => $available,
            'models' => $models
        ));
    }
}
