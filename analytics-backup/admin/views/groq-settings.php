<?php
/**
 * Page de réglages Groq AI
 * 
 * @package Almetal_Analytics
 */

if (!defined('ABSPATH')) {
    exit;
}

// Sauvegarder les réglages
if (isset($_POST['save_groq_settings'])) {
    check_admin_referer('almetal_groq_settings');
    
    $api_key = sanitize_text_field($_POST['groq_api_key']);
    update_option('almetal_groq_api_key', $api_key);
    
    echo '<div class="notice notice-success"><p>Réglages Groq sauvegardés !</p></div>';
}
?>

<div class="wrap">
    <h1>⚡ Configuration Groq AI</h1>
    
    <div class="card">
        <h2>Paramètres de l'API</h2>
        
        <form method="post" action="">
            <?php wp_nonce_field('almetal_groq_settings'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="groq_api_key">Clé API Groq</label>
                    </th>
                    <td>
                        <input type="password" 
                               id="groq_api_key" 
                               name="groq_api_key" 
                               value="<?php echo esc_attr(get_option('almetal_groq_api_key', '')); ?>" 
                               class="regular-text"
                               placeholder="gsk_...">
                        <p class="description">
                            <strong>Comment obtenir votre clé :</strong><br>
                            1. Créez un compte gratuit sur <a href="https://groq.com" target="_blank">groq.com</a><br>
                            2. Allez dans votre dashboard<br>
                            3. Copiez votre clé API (commence par "gsk_")<br>
                            <em>Crédit gratuit : $10-14 par mois (~30 000 générations)</em>
                        </p>
                    </td>
                </tr>
            </table>
            
            <?php submit_button('Sauvegarder les réglages', 'primary', 'save_groq_settings'); ?>
        </form>
    </div>
    
    <div class="card">
        <h2>📊 Statistiques d'utilisation</h2>
        <p>
            <strong>Crédit disponible :</strong> ~$10-14 gratuits/mois<br>
            <strong>Coût par génération :</strong> ~$0.0001<br>
            <strong>Capacité mensuelle :</strong> ~30 000 contenus SEO
        </p>
    </div>
    
    <div class="card">
        <h2>🚀 Modèles disponibles</h2>
        <ul>
            <li><strong>Mixtral-8x7b</strong> - Rapide et équilibré (utilisé par défaut)</li>
            <li><strong>Llama2-70b</strong> - Très performant</li>
            <li><strong>Gemma-7b</strong> - Léger et efficace</li>
        </ul>
    </div>
</div>
