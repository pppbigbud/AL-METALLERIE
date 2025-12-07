<?php
/**
 * Vue Réglages
 * 
 * @package Almetal_Analytics
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap almetal-analytics-wrap">
    <div class="almetal-analytics-header">
        <h1>
            <span class="dashicons dashicons-admin-settings"></span>
            <?php _e('Réglages Analytics', 'almetal-analytics'); ?>
        </h1>
    </div>
    
    <form method="post" action="options.php" class="almetal-settings-form">
        <?php settings_fields('almetal_analytics_settings'); ?>
        
        <!-- Général -->
        <div class="almetal-settings-section">
            <h2><?php _e('Général', 'almetal-analytics'); ?></h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Activer le tracking', 'almetal-analytics'); ?></th>
                    <td>
                        <label class="almetal-toggle">
                            <input type="checkbox" name="almetal_analytics_enabled" value="1" <?php checked(get_option('almetal_analytics_enabled', true)); ?>>
                            <span class="almetal-toggle-slider"></span>
                        </label>
                        <p class="description"><?php _e('Active ou désactive la collecte de données.', 'almetal-analytics'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php _e('Heatmaps', 'almetal-analytics'); ?></th>
                    <td>
                        <label class="almetal-toggle">
                            <input type="checkbox" name="almetal_analytics_heatmap_enabled" value="1" <?php checked(get_option('almetal_analytics_heatmap_enabled', false)); ?>>
                            <span class="almetal-toggle-slider"></span>
                        </label>
                        <p class="description"><?php _e('Active l\'enregistrement des clics pour les heatmaps.', 'almetal-analytics'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php _e('Tracker les utilisateurs connectés', 'almetal-analytics'); ?></th>
                    <td>
                        <label class="almetal-toggle">
                            <input type="checkbox" name="almetal_analytics_track_logged_users" value="1" <?php checked(get_option('almetal_analytics_track_logged_users', false)); ?>>
                            <span class="almetal-toggle-slider"></span>
                        </label>
                        <p class="description"><?php _e('Inclure les utilisateurs connectés dans les statistiques.', 'almetal-analytics'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php _e('Exclure les rôles', 'almetal-analytics'); ?></th>
                    <td>
                        <?php
                        $excluded_roles = get_option('almetal_analytics_exclude_roles', array('administrator'));
                        $all_roles = wp_roles()->get_names();
                        foreach ($all_roles as $role_key => $role_name) :
                        ?>
                        <label style="display: block; margin-bottom: 5px;">
                            <input type="checkbox" name="almetal_analytics_exclude_roles[]" value="<?php echo esc_attr($role_key); ?>" <?php checked(in_array($role_key, $excluded_roles)); ?>>
                            <?php echo esc_html($role_name); ?>
                        </label>
                        <?php endforeach; ?>
                        <p class="description"><?php _e('Ces rôles ne seront pas trackés.', 'almetal-analytics'); ?></p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- RGPD -->
        <div class="almetal-settings-section">
            <h2>
                <span class="dashicons dashicons-shield"></span>
                <?php _e('Conformité RGPD', 'almetal-analytics'); ?>
            </h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Anonymisation IP', 'almetal-analytics'); ?></th>
                    <td>
                        <label class="almetal-toggle">
                            <input type="checkbox" name="almetal_analytics_anonymize_ip" value="1" <?php checked(get_option('almetal_analytics_anonymize_ip', true)); ?>>
                            <span class="almetal-toggle-slider"></span>
                        </label>
                        <p class="description"><?php _e('Masque le dernier octet de l\'adresse IP (recommandé).', 'almetal-analytics'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php _e('Rétention des données', 'almetal-analytics'); ?></th>
                    <td>
                        <select name="almetal_analytics_retention_months" class="almetal-select">
                            <?php
                            $retention = get_option('almetal_analytics_retention_months', 13);
                            $options = array(
                                3 => __('3 mois', 'almetal-analytics'),
                                6 => __('6 mois', 'almetal-analytics'),
                                12 => __('12 mois', 'almetal-analytics'),
                                13 => __('13 mois (CNIL)', 'almetal-analytics'),
                                24 => __('24 mois', 'almetal-analytics'),
                            );
                            foreach ($options as $value => $label) :
                            ?>
                            <option value="<?php echo $value; ?>" <?php selected($retention, $value); ?>><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description"><?php _e('Les données plus anciennes seront automatiquement supprimées. La CNIL recommande 13 mois maximum.', 'almetal-analytics'); ?></p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Webhooks -->
        <div class="almetal-settings-section">
            <h2>
                <span class="dashicons dashicons-rest-api"></span>
                <?php _e('Intégrations', 'almetal-analytics'); ?>
            </h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('URL Webhook', 'almetal-analytics'); ?></th>
                    <td>
                        <input type="url" name="almetal_analytics_webhook_url" value="<?php echo esc_attr(get_option('almetal_analytics_webhook_url', '')); ?>" class="regular-text">
                        <p class="description"><?php _e('URL pour envoyer les événements en temps réel (optionnel).', 'almetal-analytics'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php _e('Secret Webhook', 'almetal-analytics'); ?></th>
                    <td>
                        <input type="password" name="almetal_analytics_webhook_secret" value="<?php echo esc_attr(get_option('almetal_analytics_webhook_secret', '')); ?>" class="regular-text">
                        <p class="description"><?php _e('Clé secrète pour signer les requêtes webhook (HMAC-SHA256).', 'almetal-analytics'); ?></p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Informations -->
        <div class="almetal-settings-section almetal-info-section">
            <h2>
                <span class="dashicons dashicons-info"></span>
                <?php _e('Informations', 'almetal-analytics'); ?>
            </h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('ID du site', 'almetal-analytics'); ?></th>
                    <td>
                        <code><?php echo esc_html(get_option('almetal_analytics_site_id', 'Non généré')); ?></code>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Version du plugin', 'almetal-analytics'); ?></th>
                    <td>
                        <code><?php echo ALMETAL_ANALYTICS_VERSION; ?></code>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Endpoint API', 'almetal-analytics'); ?></th>
                    <td>
                        <code><?php echo esc_url(rest_url('almetal-analytics/v1/')); ?></code>
                    </td>
                </tr>
            </table>
        </div>
        
        <?php submit_button(__('Enregistrer les modifications', 'almetal-analytics')); ?>
    </form>
</div>
