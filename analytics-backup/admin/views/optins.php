<?php
/**
 * Vue Opt-ins
 * 
 * @package Almetal_Analytics
 */

if (!defined('ABSPATH')) {
    exit;
}

$period = isset($_GET['period']) ? sanitize_text_field($_GET['period']) : '30days';
$stats = Almetal_Analytics_Optin::get_optin_stats($period);
?>

<div class="wrap almetal-analytics-wrap">
    <div class="almetal-analytics-header">
        <h1>
            <span class="dashicons dashicons-email-alt"></span>
            <?php _e('Opt-ins & Leads', 'almetal-analytics'); ?>
        </h1>
        
        <div class="almetal-analytics-header-actions">
            <select id="period-selector" class="almetal-select">
                <option value="7days" <?php selected($period, '7days'); ?>><?php _e('7 derniers jours', 'almetal-analytics'); ?></option>
                <option value="30days" <?php selected($period, '30days'); ?>><?php _e('30 derniers jours', 'almetal-analytics'); ?></option>
                <option value="90days" <?php selected($period, '90days'); ?>><?php _e('90 derniers jours', 'almetal-analytics'); ?></option>
            </select>
            
            <a href="<?php echo esc_url(rest_url('almetal-analytics/v1/export/optins?format=csv')); ?>" class="button" target="_blank">
                <span class="dashicons dashicons-download"></span>
                <?php _e('Exporter CSV', 'almetal-analytics'); ?>
            </a>
        </div>
    </div>
    
    <!-- Stats -->
    <div class="almetal-stats-grid">
        <div class="almetal-stat-card">
            <div class="almetal-stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <span class="dashicons dashicons-admin-users"></span>
            </div>
            <div class="almetal-stat-content">
                <span class="almetal-stat-value"><?php echo number_format_i18n($stats['total']); ?></span>
                <span class="almetal-stat-label"><?php _e('Total inscriptions', 'almetal-analytics'); ?></span>
            </div>
        </div>
        
        <div class="almetal-stat-card">
            <div class="almetal-stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <span class="dashicons dashicons-yes-alt"></span>
            </div>
            <div class="almetal-stat-content">
                <span class="almetal-stat-value"><?php echo number_format_i18n($stats['confirmed']); ?></span>
                <span class="almetal-stat-label"><?php _e('Confirmés (double opt-in)', 'almetal-analytics'); ?></span>
            </div>
        </div>
        
        <div class="almetal-stat-card">
            <div class="almetal-stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <span class="dashicons dashicons-clock"></span>
            </div>
            <div class="almetal-stat-content">
                <span class="almetal-stat-value"><?php echo number_format_i18n($stats['pending']); ?></span>
                <span class="almetal-stat-label"><?php _e('En attente', 'almetal-analytics'); ?></span>
            </div>
        </div>
        
        <div class="almetal-stat-card">
            <div class="almetal-stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <span class="dashicons dashicons-chart-pie"></span>
            </div>
            <div class="almetal-stat-content">
                <span class="almetal-stat-value"><?php echo $stats['confirmation_rate']; ?>%</span>
                <span class="almetal-stat-label"><?php _e('Taux de confirmation', 'almetal-analytics'); ?></span>
            </div>
        </div>
    </div>
    
    <!-- Par source -->
    <div class="almetal-tables-grid">
        <div class="almetal-table-card">
            <div class="almetal-table-header">
                <h3><?php _e('Inscriptions par source', 'almetal-analytics'); ?></h3>
            </div>
            <div class="almetal-table-body">
                <?php if (empty($stats['by_source'])) : ?>
                    <p class="almetal-no-data"><?php _e('Aucune donnée disponible.', 'almetal-analytics'); ?></p>
                <?php else : ?>
                    <table class="almetal-table">
                        <thead>
                            <tr>
                                <th><?php _e('Source', 'almetal-analytics'); ?></th>
                                <th><?php _e('Inscriptions', 'almetal-analytics'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['by_source'] as $source) : ?>
                            <tr>
                                <td><?php echo esc_html($source['source'] ?: __('Non définie', 'almetal-analytics')); ?></td>
                                <td><?php echo number_format_i18n($source['count']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Intégration formulaire -->
        <div class="almetal-table-card">
            <div class="almetal-table-header">
                <h3><?php _e('Intégration', 'almetal-analytics'); ?></h3>
            </div>
            <div class="almetal-table-body">
                <p><?php _e('Pour collecter des opt-ins, ajoutez ce shortcode à vos formulaires :', 'almetal-analytics'); ?></p>
                <pre style="background: #f5f5f5; padding: 15px; border-radius: 6px; overflow-x: auto;">[almetal_optin_form source="homepage" form_id="newsletter"]</pre>
                
                <p style="margin-top: 20px;"><?php _e('Ou utilisez l\'API REST :', 'almetal-analytics'); ?></p>
                <pre style="background: #f5f5f5; padding: 15px; border-radius: 6px; overflow-x: auto;">POST <?php echo esc_url(rest_url('almetal-analytics/v1/optin')); ?>
{
  "email": "user@example.com",
  "name": "John Doe",
  "source": "contact_form",
  "consent_marketing": true
}</pre>
            </div>
        </div>
    </div>
</div>

<script>
jQuery('#period-selector').on('change', function() {
    window.location.href = '<?php echo admin_url('admin.php?page=almetal-analytics-optins&period='); ?>' + this.value;
});
</script>
