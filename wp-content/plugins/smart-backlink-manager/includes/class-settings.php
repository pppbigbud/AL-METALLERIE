<?php
/**
 * Settings class for Smart Backlink Manager
 */

if (!defined('ABSPATH')) {
    exit;
}

class SBM_Settings {
    
    public function init(): void {
        // Hook AJAX
        add_action('wp_ajax_sbm_save_settings', [$this, 'ajax_save_settings']);
    }
    
    public function render_page(): void {
        ?>
        <div class="wrap sbm-settings">
            <h1><?php _e('Réglages Smart Backlink Manager', 'smart-backlink-manager'); ?></h1>
            
            <form id="sbm-settings-form" method="post">
                <div class="sbm-settings-tabs">
                    <ul class="sbm-tab-nav">
                        <li class="active"><a href="#general"><?php _e('Général', 'smart-backlink-manager'); ?></a></li>
                        <li><a href="#seo"><?php _e('SEO', 'smart-backlink-manager'); ?></a></li>
                        <li><a href="#gutenberg"><?php _e('Gutenberg', 'smart-backlink-manager'); ?></a></li>
                        <li><a href="#automation"><?php _e('Automatisation', 'smart-backlink-manager'); ?></a></li>
                    </ul>
                    
                    <!-- Onglet Général -->
                    <div id="general" class="sbm-tab-content active">
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="sbm_site_name"><?php _e('Nom du site', 'smart-backlink-manager'); ?></label>
                                </th>
                                <td>
                                    <input type="text" name="sbm_site_name" id="sbm_site_name" 
                                           value="<?php echo esc_attr(get_option('sbm_site_name', 'AL Métallerie')); ?>" 
                                           class="regular-text">
                                    <p class="description">
                                        <?php _e('Nom de votre site utilisé dans les rapports et notifications', 'smart-backlink-manager'); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="sbm_site_url"><?php _e('URL du site', 'smart-backlink-manager'); ?></label>
                                </th>
                                <td>
                                    <input type="url" name="sbm_site_url" id="sbm_site_url" 
                                           value="<?php echo esc_attr(get_option('sbm_site_url', home_url())); ?>" 
                                           class="regular-text">
                                    <p class="description">
                                        <?php _e('URL principale de votre site', 'smart-backlink-manager'); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="sbm_site_niche"><?php _e('Secteur d\'activité', 'smart-backlink-manager'); ?></label>
                                </th>
                                <td>
                                    <input type="text" name="sbm_site_niche" id="sbm_site_niche" 
                                           value="<?php echo esc_attr(get_option('sbm_site_niche', 'Métallerie, Serrurerie, Soudure')); ?>" 
                                           class="regular-text">
                                    <p class="description">
                                        <?php _e('Votre secteur d\'activité principal', 'smart-backlink-manager'); ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <!-- Onglet SEO -->
                    <div id="seo" class="sbm-tab-content">
                        <h3><?php _e('Mots-clés personnalisés', 'smart-backlink-manager'); ?></h3>
                        <p class="description">
                            <?php _e('Ajoutez des mots-clés spécifiques à votre activité pour améliorer les suggestions de liens', 'smart-backlink-manager'); ?>
                        </p>
                        
                        <div id="sbm-keywords-container">
                            <?php
                            $keywords = json_decode(get_option('sbm_custom_keywords', '[]'), true);
                            if (!empty($keywords)) {
                                foreach ($keywords as $keyword) {
                                    echo '<div class="sbm-keyword-item">';
                                    echo '<input type="text" name="sbm_custom_keywords[]" value="' . esc_attr($keyword) . '" class="regular-text">';
                                    echo '<button type="button" class="button sbm-remove-keyword">' . __('Supprimer', 'smart-backlink-manager') . '</button>';
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>
                        
                        <p>
                            <button type="button" class="button" id="sbm-add-keyword">
                                <?php _e('Ajouter un mot-clé', 'smart-backlink-manager'); ?>
                            </button>
                        </p>
                        
                        <hr>
                        
                        <h3><?php _e('Ancre de lien par défaut', 'smart-backlink-manager'); ?></h3>
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="sbm_default_anchor"><?php _e('Texte d\'ancrage', 'smart-backlink-manager'); ?></label>
                                </th>
                                <td>
                                    <input type="text" name="sbm_default_anchor" id="sbm_default_anchor" 
                                           value="<?php echo esc_attr(get_option('sbm_default_anchor', 'métallier {ville}')); ?>" 
                                           class="regular-text">
                                    <p class="description">
                                        <?php _e('Utilisez {ville} comme variable pour le nom de la ville', 'smart-backlink-manager'); ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <!-- Onglet Gutenberg -->
                    <div id="gutenberg" class="sbm-tab-content">
                        <h3><?php _e('Intégration Gutenberg', 'smart-backlink-manager'); ?></h3>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label>
                                        <input type="checkbox" name="sbm_enable_gutenberg" 
                                               <?php checked(get_option('sbm_enable_gutenberg', 1)); ?>>
                                        <?php _e('Activer le bloc Gutenberg', 'smart-backlink-manager'); ?>
                                    </label>
                                </th>
                                <td>
                                    <p class="description">
                                        <?php _e('Ajoute un bloc pour insérer facilement des liens internes dans l\'éditeur Gutenberg', 'smart-backlink-manager'); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label>
                                        <input type="checkbox" name="sbm_enable_suggestions" 
                                               <?php checked(get_option('sbm_enable_suggestions', 1)); ?>>
                                        <?php _e('Activer les suggestions automatiques', 'smart-backlink-manager'); ?>
                                    </label>
                                </th>
                                <td>
                                    <p class="description">
                                        <?php _e('Suggère des liens internes pertinents lors de la rédaction', 'smart-backlink-manager'); ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <!-- Onglet Automatisation -->
                    <div id="automation" class="sbm-tab-content">
                        <h3><?php _e('Tâches automatiques', 'smart-backlink-manager'); ?></h3>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label>
                                        <input type="checkbox" name="sbm_auto_check_backlinks" 
                                               <?php checked(get_option('sbm_auto_check_backlinks', 1)); ?>>
                                        <?php _e('Vérifier automatiquement les backlinks', 'smart-backlink-manager'); ?>
                                    </label>
                                </th>
                                <td>
                                    <p class="description">
                                        <?php _e('Vérifie l\'état des backlinks chaque jour', 'smart-backlink-manager'); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label>
                                        <input type="checkbox" name="sbm_auto_find_opportunities" 
                                               <?php checked(get_option('sbm_auto_find_opportunities', 0)); ?>>
                                        <?php _e('Rechercher automatiquement des opportunités', 'smart-backlink-manager'); ?>
                                    </label>
                                </th>
                                <td>
                                    <p class="description">
                                        <?php _e('Cherche de nouvelles opportunités de backlinks chaque semaine', 'smart-backlink-manager'); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="sbm_notification_email"><?php _e('Email de notification', 'smart-backlink-manager'); ?></label>
                                </th>
                                <td>
                                    <input type="email" name="sbm_notification_email" id="sbm_notification_email" 
                                           value="<?php echo esc_attr(get_option('sbm_notification_email', get_option('admin_email'))); ?>" 
                                           class="regular-text">
                                    <p class="description">
                                        <?php _e('Adresse email pour recevoir les rapports et alertes', 'smart-backlink-manager'); ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <p class="submit">
                    <button type="submit" class="button button-primary">
                        <?php _e('Enregistrer les modifications', 'smart-backlink-manager'); ?>
                    </button>
                </p>
                
                <?php wp_nonce_field('sbm_save_settings', 'sbm_settings_nonce'); ?>
            </form>
        </div>
        <?php
    }
    
    public function ajax_save_settings(): void {
        check_ajax_referer('sbm_settings_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Permission refusée', 'smart-backlink-manager'));
        }
        
        // Sauvegarder les options générales
        update_option('sbm_site_name', sanitize_text_field($_POST['sbm_site_name']));
        update_option('sbm_site_url', esc_url_raw($_POST['sbm_site_url']));
        update_option('sbm_site_niche', sanitize_text_field($_POST['sbm_site_niche']));
        
        // Sauvegarder les mots-clés
        $keywords = array_map('sanitize_text_field', $_POST['sbm_custom_keywords'] ?? []);
        $keywords = array_filter($keywords);
        update_option('sbm_custom_keywords', json_encode($keywords));
        
        // Sauvegarder l'ancre par défaut
        update_option('sbm_default_anchor', sanitize_text_field($_POST['sbm_default_anchor']));
        
        // Sauvegarder les options Gutenberg
        update_option('sbm_enable_gutenberg', isset($_POST['sbm_enable_gutenberg']) ? 1 : 0);
        update_option('sbm_enable_suggestions', isset($_POST['sbm_enable_suggestions']) ? 1 : 0);
        
        // Sauvegarder les options d'automatisation
        update_option('sbm_auto_check_backlinks', isset($_POST['sbm_auto_check_backlinks']) ? 1 : 0);
        update_option('sbm_auto_find_opportunities', isset($_POST['sbm_auto_find_opportunities']) ? 1 : 0);
        update_option('sbm_notification_email', sanitize_email($_POST['sbm_notification_email']));
        
        wp_send_json_success(['message' => __('Réglages enregistrés avec succès', 'smart-backlink-manager')]);
    }
}
