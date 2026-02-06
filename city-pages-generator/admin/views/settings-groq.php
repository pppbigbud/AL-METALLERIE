<?php
/**
 * Page de réglages Groq pour City Pages Generator
 * 
 * @package CityPagesGenerator
 */

if (!defined('ABSPATH')) {
    exit;
}

// Sauvegarder les réglages
if (isset($_POST['save_groq_settings'])) {
    check_admin_referer('cpg_groq_settings');
    
    $settings = get_option('cpg_settings', []);
    
    // Activer/désactiver Groq
    $settings['use_groq'] = isset($_POST['use_groq']) ? 1 : 0;
    
    // Paramètres Groq
    $settings['groq_temperature'] = floatval($_POST['groq_temperature']);
    $settings['groq_persona'] = sanitize_text_field($_POST['groq_persona']);
    
    // Options de génération
    $settings['generate_on_create'] = isset($_POST['generate_on_create']) ? 1 : 0;
    $settings['regenerate_faq_on_realisation'] = isset($_POST['regenerate_faq_on_realisation']) ? 1 : 0;
    $settings['show_preview'] = isset($_POST['show_preview']) ? 1 : 0;
    
    update_option('cpg_settings', $settings);
    
    echo '<div class="notice notice-success"><p>Réglages Groq sauvegardés !</p></div>';
}

$settings = get_option('cpg_settings', []);
$use_groq = isset($settings['use_groq']) ? $settings['use_groq'] : 0;
$temperature = isset($settings['groq_temperature']) ? $settings['groq_temperature'] : 0.7;
$persona = isset($settings['groq_persona']) ? $settings['groq_persona'] : 'artisan_expert';
$generate_on_create = isset($settings['generate_on_create']) ? $settings['generate_on_create'] : 1;
$regenerate_faq = isset($settings['regenerate_faq_on_realisation']) ? $settings['regenerate_faq_on_realisation'] : 1;
$show_preview = isset($settings['show_preview']) ? $settings['show_preview'] : 1;

// Vérifier si Groq est configuré
$groq = CPG_Groq_Integration::get_instance();
$groq_configured = $groq->is_configured();
?>

<div class="wrap">
    <h1>🤖 Configuration Groq AI - City Pages</h1>
    
    <?php if (!$groq_configured): ?>
        <div class="notice notice-warning">
            <p>
                <strong>⚠️ Groq AI n'est pas configuré.</strong><br>
                Veuillez configurer la clé API dans <a href="<?php echo admin_url('admin.php?page=almetal-analytics-groq'); ?>">Analytics > Groq AI</a>
            </p>
        </div>
    <?php endif; ?>
    
    <form method="post" action="">
        <?php wp_nonce_field('cpg_groq_settings'); ?>
        
        <div class="card">
            <h2>Activation de Groq AI</h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="use_groq">Utiliser Groq AI</label>
                    </th>
                    <td>
                        <label>
                            <input type="checkbox" id="use_groq" name="use_groq" value="1" <?php checked($use_groq, 1); ?> <?php disabled(!$groq_configured); ?>>
                            Activer la génération de contenu avec Groq AI
                        </label>
                        <p class="description">
                            Génère du contenu unique pour chaque ville avec l'IA.
                            <br>Si désactivé, utilise les templates fixes (backup).
                        </p>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="card">
            <h2>Paramètres de génération</h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="groq_temperature">Créativité</label>
                    </th>
                    <td>
                        <input type="range" id="groq_temperature" name="groq_temperature" 
                               min="0.5" max="1" step="0.1" value="<?php echo esc_attr($temperature); ?>"
                               <?php disabled(!$groq_configured || !$use_groq); ?>>
                        <span id="temp-value"><?php echo esc_html($temperature); ?></span>
                        <p class="description">
                            0.5 = Plus prévisible<br>
                            1.0 = Plus créatif et unique
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="groq_persona">Persona</label>
                    </th>
                    <td>
                        <select id="groq_persona" name="groq_persona" <?php disabled(!$groq_configured || !$use_groq); ?>>
                            <option value="artisan_expert" <?php selected($persona, 'artisan_expert'); ?>>Artisan expert</option>
                            <option value="commercial" <?php selected($persona, 'commercial'); ?>>Expert commercial</option>
                            <option value="technical" <?php selected($persona, 'technical'); ?>>Expert technique</option>
                        </select>
                        <p class="description">
                            Style adopté par l'IA pour générer le contenu.
                        </p>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="card">
            <h2>Automatisation</h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="generate_on_create">Génération automatique</label>
                    </th>
                    <td>
                        <label>
                            <input type="checkbox" id="generate_on_create" name="generate_on_create" value="1" 
                                   <?php checked($generate_on_create, 1); ?> <?php disabled(!$groq_configured || !$use_groq); ?>>
                            Générer le contenu automatiquement à la création d'une nouvelle ville
                        </label>
                        <p class="description">
                            Les 7 sections seront générées immédiatement lors de l'ajout d'une ville.
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="regenerate_faq_on_realisation">FAQ dynamique</label>
                    </th>
                    <td>
                        <label>
                            <input type="checkbox" id="regenerate_faq_on_realisation" name="regenerate_faq_on_realisation" value="1" 
                                   <?php checked($regenerate_faq, 1); ?> <?php disabled(!$groq_configured || !$use_groq); ?>>
                            Regénérer la FAQ lors de l'ajout d'une réalisation
                        </label>
                        <p class="description">
                            Met à jour la FAQ de la ville concernée avec une nouvelle question.
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="show_preview">Aperçu avant application</label>
                    </th>
                    <td>
                        <label>
                            <input type="checkbox" id="show_preview" name="show_preview" value="1" 
                                   <?php checked($show_preview, 1); ?> <?php disabled(!$groq_configured || !$use_groq); ?>>
                            Afficher un aperçu avant d'appliquer le contenu généré
                        </label>
                        <p class="description">
                            Permet de valider le contenu avant de le publier.
                        </p>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="card">
            <h2>📊 Statistiques d'utilisation</h2>
            <p>
                <strong>Crédit Groq disponible :</strong> ~$10-14 gratuits/mois<br>
                <strong>Estimation par page ville :</strong> ~0.02$ (7 sections)<br>
                <strong>Capacité mensuelle :</strong> ~500 pages ville complètes
            </p>
        </div>
        
        <?php submit_button('Sauvegarder les réglages', 'primary', 'save_groq_settings'); ?>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    $('#groq_temperature').on('input', function() {
        $('#temp-value').text($(this).val());
    });
});
</script>
