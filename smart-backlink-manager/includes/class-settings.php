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
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="sbm_suggestions_limit"><?php _e('Limite de suggestions', 'smart-backlink-manager'); ?></label>
                                </th>
                                <td>
                                    <input type="number" name="sbm_suggestions_limit" id="sbm_suggestions_limit" 
                                           value="<?php echo intval(get_option('sbm_suggestions_limit', 5)); ?>" 
                                           min="1" max="20" class="small-text">
                                    <p class="description">
                                        <?php _e('Nombre maximum de suggestions de liens à afficher', 'smart-backlink-manager'); ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <!-- Onglet Gutenberg -->
                    <div id="gutenberg" class="sbm-tab-content">
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <?php _e('Panneau Gutenberg', 'smart-backlink-manager'); ?>
                                </th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="sbm_gutenberg_panel_enabled" 
                                               value="1" <?php checked(get_option('sbm_gutenberg_panel_enabled', 1)); ?>>
                                        <?php _e('Activer le panneau de suggestions dans l\'éditeur Gutenberg', 'smart-backlink-manager'); ?>
                                    </label>
                                    <p class="description">
                                        <?php _e('Affiche un panneau latéral dans l\'éditeur avec des suggestions de liens internes', 'smart-backlink-manager'); ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <!-- Onglet Automatisation -->
                    <div id="automation" class="sbm-tab-content">
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="sbm_check_frequency"><?php _e('Fréquence de vérification', 'smart-backlink-manager'); ?></label>
                                </th>
                                <td>
                                    <select name="sbm_check_frequency" id="sbm_check_frequency">
                                        <option value="daily" <?php selected(get_option('sbm_check_frequency', 'weekly'), 'daily'); ?>>
                                            <?php _e('Quotidienne', 'smart-backlink-manager'); ?>
                                        </option>
                                        <option value="weekly" <?php selected(get_option('sbm_check_frequency', 'weekly'), 'weekly'); ?>>
                                            <?php _e('Hebdomadaire', 'smart-backlink-manager'); ?>
                                        </option>
                                        <option value="monthly" <?php selected(get_option('sbm_check_frequency', 'weekly'), 'monthly'); ?>>
                                            <?php _e('Mensuelle', 'smart-backlink-manager'); ?>
                                        </option>
                                    </select>
                                    <p class="description">
                                        <?php _e('À quelle fréquence vérifier automatiquement les backlinks', 'smart-backlink-manager'); ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                        
                        <h3><?php _e('Notifications email', 'smart-backlink-manager'); ?></h3>
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <?php _e('Email de notification', 'smart-backlink-manager'); ?>
                                </th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="sbm_email_notifications" 
                                               value="1" <?php checked(get_option('sbm_email_notifications', 0)); ?>>
                                        <?php _e('Recevoir des notifications par email', 'smart-backlink-manager'); ?>
                                    </label>
                                    <p class="description">
                                        <?php _e('Recevoir un email lorsqu\'un backlink est détecté comme mort', 'smart-backlink-manager'); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="sbm_notification_email"><?php _e('Adresse email', 'smart-backlink-manager'); ?></label>
                                </th>
                                <td>
                                    <input type="email" name="sbm_notification_email" id="sbm_notification_email" 
                                           value="<?php echo esc_attr(get_option('sbm_notification_email', get_option('admin_email'))); ?>" 
                                           class="regular-text">
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
                
                <?php wp_nonce_field('sbm_settings_nonce', 'sbm_nonce'); ?>
            </form>
            
            <!-- Informations système -->
            <div class="sbm-system-info">
                <h2><?php _e('Informations système', 'smart-backlink-manager'); ?></h2>
                <table class="widefat">
                    <tr>
                        <td><strong><?php _e('Version du plugin', 'smart-backlink-manager'); ?></strong></td>
                        <td><?php echo SBM_VERSION; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Version WordPress', 'smart-backlink-manager'); ?></strong></td>
                        <td><?php echo get_bloginfo('version'); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Version PHP', 'smart-backlink-manager'); ?></strong></td>
                        <td><?php echo PHP_VERSION; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Base de données', 'smart-backlink-manager'); ?></strong></td>
                        <td>
                            <?php
                            global $wpdb;
                            $tables = [
                                $wpdb->prefix . 'sbm_backlinks',
                                $wpdb->prefix . 'sbm_internal_links',
                                $wpdb->prefix . 'sbm_opportunities',
                                $wpdb->prefix . 'sbm_settings'
                            ];
                            
                            foreach ($tables as $table) {
                                $count = $wpdb->get_var("SELECT COUNT(*) FROM $table");
                                echo sprintf('%s: %d enregistrements<br>', $table, $count);
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            // Gestion des onglets
            $('.sbm-tab-nav a').on('click', function(e) {
                e.preventDefault();
                var target = $(this).attr('href');
                
                $('.sbm-tab-nav li').removeClass('active');
                $('.sbm-tab-content').removeClass('active');
                
                $(this).parent().addClass('active');
                $(target).addClass('active');
            });
            
            // Ajouter un mot-clé
            $('#sbm-add-keyword').on('click', function() {
                var $newItem = $('<div class="sbm-keyword-item">' +
                    '<input type="text" name="sbm_custom_keywords[]" class="regular-text">' +
                    '<button type="button" class="button sbm-remove-keyword"><?php _e('Supprimer', 'smart-backlink-manager'); ?></button>' +
                    '</div>');
                
                $('#sbm-keywords-container').append($newItem);
            });
            
            // Supprimer un mot-clé
            $(document).on('click', '.sbm-remove-keyword', function() {
                $(this).parent().remove();
            });
            
            // Soumission du formulaire
            $('#sbm-settings-form').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                
                $.post(ajaxurl, formData + '&action=sbm_save_settings', function(response) {
                    if (response.success) {
                        alert('<?php _e('Réglages enregistrés avec succès', 'smart-backlink-manager'); ?>');
                    } else {
                        alert(response.data || '<?php _e('Erreur lors de l\'enregistrement', 'smart-backlink-manager'); ?>');
                    }
                });
            });
        });
        </script>
        
        <style>
        .sbm-settings-tabs {
            margin-top: 20px;
        }
        
        .sbm-tab-nav {
            list-style: none;
            margin: 0;
            padding: 0;
            border-bottom: 1px solid #ccc;
        }
        
        .sbm-tab-nav li {
            display: inline-block;
            margin: 0;
            padding: 0;
        }
        
        .sbm-tab-nav li a {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            border: 1px solid transparent;
            border-bottom: none;
            background: #f1f1f1;
            color: #444;
        }
        
        .sbm-tab-nav li.active a {
            background: #fff;
            border-color: #ccc;
            border-bottom: 1px solid #fff;
            margin-bottom: -1px;
            color: #000;
        }
        
        .sbm-tab-content {
            display: none;
            padding: 20px 0;
            border: 1px solid #ccc;
            border-top: none;
            background: #fff;
        }
        
        .sbm-tab-content.active {
            display: block;
        }
        
        .sbm-keyword-item {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .sbm-keyword-item input {
            flex: 1;
        }
        
        .sbm-system-info {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        </style>
        <?php
    }
    
    public function ajax_save_settings(): void {
        check_ajax_referer('sbm_settings_nonce', 'sbm_nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Vous n\'avez pas les permissions nécessaires', 'smart-backlink-manager'));
        }
        
        // Sauvegarder tous les réglages
        $settings = [
            'sbm_site_name',
            'sbm_site_url',
            'sbm_site_niche',
            'sbm_suggestions_limit',
            'sbm_gutenberg_panel_enabled',
            'sbm_check_frequency',
            'sbm_email_notifications',
            'sbm_notification_email'
        ];
        
        foreach ($settings as $setting) {
            if (isset($_POST[$setting])) {
                $value = sanitize_text_field($_POST[$setting]);
                if ($setting === 'sbm_suggestions_limit') {
                    $value = intval($value);
                } elseif (strpos($setting, '_enabled') !== false || strpos($setting, 'sbm_email_') !== false) {
                    $value = $value ? '1' : '0';
                }
                update_option($setting, $value);
            }
        }
        
        // Sauvegarder les mots-clés
        if (isset($_POST['sbm_custom_keywords'])) {
            $keywords = array_map('sanitize_text_field', $_POST['sbm_custom_keywords']);
            $keywords = array_filter($keywords); // Supprimer les valeurs vides
            update_option('sbm_custom_keywords', json_encode(array_values($keywords)));
        }
        
        wp_send_json_success(__('Réglages enregistrés avec succès', 'smart-backlink-manager'));
    }
}
