<?php
/**
 * Vue Dashboard Analytics
 * 
 * @package Almetal_Analytics
 */

if (!defined('ABSPATH')) {
    exit;
}

$period = isset($_GET['period']) ? sanitize_text_field($_GET['period']) : '30days';
$stats = Almetal_Analytics_Database::get_stats($period);
$consent_stats = Almetal_Analytics_Consent::get_consent_stats($period);
?>

<div class="wrap almetal-analytics-wrap">
    <div class="almetal-analytics-header">
        <h1>
            <span class="dashicons dashicons-chart-area"></span>
            <?php _e('Analytics Dashboard', 'almetal-analytics'); ?>
        </h1>
        
        <div class="almetal-analytics-header-actions">
            <!-- Sélecteur de période -->
            <select id="period-selector" class="almetal-select">
                <option value="today" <?php selected($period, 'today'); ?>><?php _e('Aujourd\'hui', 'almetal-analytics'); ?></option>
                <option value="7days" <?php selected($period, '7days'); ?>><?php _e('7 derniers jours', 'almetal-analytics'); ?></option>
                <option value="30days" <?php selected($period, '30days'); ?>><?php _e('30 derniers jours', 'almetal-analytics'); ?></option>
                <option value="90days" <?php selected($period, '90days'); ?>><?php _e('90 derniers jours', 'almetal-analytics'); ?></option>
                <option value="12months" <?php selected($period, '12months'); ?>><?php _e('12 derniers mois', 'almetal-analytics'); ?></option>
            </select>
            
            <!-- Export -->
            <div class="almetal-dropdown">
                <button class="button">
                    <span class="dashicons dashicons-download"></span>
                    <?php _e('Exporter', 'almetal-analytics'); ?>
                </button>
                <div class="almetal-dropdown-content">
                    <a href="<?php echo esc_url(rest_url('almetal-analytics/v1/export/analytics?format=csv&period=' . $period)); ?>" target="_blank">
                        <?php _e('Export CSV', 'almetal-analytics'); ?>
                    </a>
                    <a href="<?php echo esc_url(rest_url('almetal-analytics/v1/export/analytics?format=json&period=' . $period)); ?>" target="_blank">
                        <?php _e('Export JSON', 'almetal-analytics'); ?>
                    </a>
                </div>
            </div>
            
            <!-- Dark mode toggle -->
            <button id="dark-mode-toggle" class="button" title="<?php _e('Mode sombre', 'almetal-analytics'); ?>">
                <span class="dashicons dashicons-admin-appearance"></span>
            </button>
        </div>
    </div>
    
    <!-- KPIs Cards -->
    <div class="almetal-stats-grid">
        <div class="almetal-stat-card">
            <div class="almetal-stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <span class="dashicons dashicons-visibility"></span>
            </div>
            <div class="almetal-stat-content">
                <span class="almetal-stat-value"><?php echo number_format_i18n($stats['total_visits']); ?></span>
                <span class="almetal-stat-label"><?php _e('Visites', 'almetal-analytics'); ?></span>
            </div>
        </div>
        
        <div class="almetal-stat-card">
            <div class="almetal-stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <span class="dashicons dashicons-groups"></span>
            </div>
            <div class="almetal-stat-content">
                <span class="almetal-stat-value"><?php echo number_format_i18n($stats['unique_visitors']); ?></span>
                <span class="almetal-stat-label"><?php _e('Visiteurs uniques', 'almetal-analytics'); ?></span>
            </div>
        </div>
        
        <div class="almetal-stat-card">
            <div class="almetal-stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <span class="dashicons dashicons-chart-line"></span>
            </div>
            <div class="almetal-stat-content">
                <span class="almetal-stat-value"><?php echo $stats['bounce_rate']; ?>%</span>
                <span class="almetal-stat-label"><?php _e('Taux de rebond', 'almetal-analytics'); ?></span>
            </div>
        </div>
        
        <div class="almetal-stat-card">
            <div class="almetal-stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <span class="dashicons dashicons-clock"></span>
            </div>
            <div class="almetal-stat-content">
                <span class="almetal-stat-value"><?php echo gmdate('i:s', (int)$stats['avg_duration']); ?></span>
                <span class="almetal-stat-label"><?php _e('Durée moyenne', 'almetal-analytics'); ?></span>
            </div>
        </div>
    </div>
    
    <!-- Graphiques -->
    <div class="almetal-charts-grid">
        <!-- Courbe de trafic -->
        <div class="almetal-chart-card almetal-chart-large">
            <div class="almetal-chart-header">
                <h3><?php _e('Évolution du trafic', 'almetal-analytics'); ?></h3>
            </div>
            <div class="almetal-chart-body">
                <canvas id="traffic-chart"></canvas>
            </div>
        </div>
        
        <!-- Sources de trafic -->
        <div class="almetal-chart-card">
            <div class="almetal-chart-header">
                <h3><?php _e('Sources de trafic', 'almetal-analytics'); ?></h3>
            </div>
            <div class="almetal-chart-body">
                <canvas id="sources-chart"></canvas>
            </div>
        </div>
        
        <!-- Devices -->
        <div class="almetal-chart-card">
            <div class="almetal-chart-header">
                <h3><?php _e('Appareils', 'almetal-analytics'); ?></h3>
            </div>
            <div class="almetal-chart-body">
                <canvas id="devices-chart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Tableaux -->
    <div class="almetal-tables-grid">
        <!-- Top pages -->
        <div class="almetal-table-card">
            <div class="almetal-table-header">
                <h3><?php _e('Pages les plus visitées', 'almetal-analytics'); ?></h3>
            </div>
            <div class="almetal-table-body">
                <table class="almetal-table" id="top-pages-table">
                    <thead>
                        <tr>
                            <th><?php _e('Page', 'almetal-analytics'); ?></th>
                            <th><?php _e('Vues', 'almetal-analytics'); ?></th>
                            <th><?php _e('Visiteurs', 'almetal-analytics'); ?></th>
                            <th><?php _e('Durée moy.', 'almetal-analytics'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Chargé via JS -->
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Nouveaux vs Retours -->
        <div class="almetal-table-card">
            <div class="almetal-table-header">
                <h3><?php _e('Nouveaux vs Retours', 'almetal-analytics'); ?></h3>
            </div>
            <div class="almetal-table-body">
                <div class="almetal-visitors-comparison">
                    <div class="almetal-visitor-stat">
                        <div class="almetal-visitor-bar new" style="width: <?php echo $stats['unique_visitors'] > 0 ? ($stats['new_visitors'] / $stats['unique_visitors'] * 100) : 0; ?>%;"></div>
                        <span class="almetal-visitor-label"><?php _e('Nouveaux', 'almetal-analytics'); ?></span>
                        <span class="almetal-visitor-value"><?php echo number_format_i18n($stats['new_visitors']); ?></span>
                    </div>
                    <div class="almetal-visitor-stat">
                        <div class="almetal-visitor-bar returning" style="width: <?php echo $stats['unique_visitors'] > 0 ? ($stats['returning_visitors'] / $stats['unique_visitors'] * 100) : 0; ?>%;"></div>
                        <span class="almetal-visitor-label"><?php _e('Retours', 'almetal-analytics'); ?></span>
                        <span class="almetal-visitor-value"><?php echo number_format_i18n($stats['returning_visitors']); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Consentements -->
    <div class="almetal-consent-section">
        <div class="almetal-table-card">
            <div class="almetal-table-header">
                <h3>
                    <span class="dashicons dashicons-shield"></span>
                    <?php _e('Statistiques de consentement (RGPD)', 'almetal-analytics'); ?>
                </h3>
            </div>
            <div class="almetal-table-body">
                <div class="almetal-consent-stats">
                    <div class="almetal-consent-stat">
                        <span class="almetal-consent-value"><?php echo number_format_i18n($consent_stats['total']); ?></span>
                        <span class="almetal-consent-label"><?php _e('Total demandes', 'almetal-analytics'); ?></span>
                    </div>
                    <div class="almetal-consent-stat accepted">
                        <span class="almetal-consent-value"><?php echo number_format_i18n($consent_stats['accepted']); ?></span>
                        <span class="almetal-consent-label"><?php _e('Acceptés', 'almetal-analytics'); ?></span>
                    </div>
                    <div class="almetal-consent-stat refused">
                        <span class="almetal-consent-value"><?php echo number_format_i18n($consent_stats['refused']); ?></span>
                        <span class="almetal-consent-label"><?php _e('Refusés', 'almetal-analytics'); ?></span>
                    </div>
                    <div class="almetal-consent-stat rate">
                        <span class="almetal-consent-value"><?php echo $consent_stats['acceptance_rate']; ?>%</span>
                        <span class="almetal-consent-label"><?php _e('Taux d\'acceptation', 'almetal-analytics'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Données pour les graphiques
var almetalChartData = {
    period: '<?php echo esc_js($period); ?>',
    stats: <?php echo wp_json_encode($stats); ?>,
    consentStats: <?php echo wp_json_encode($consent_stats); ?>
};
</script>
