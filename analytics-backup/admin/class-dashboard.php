<?php
/**
 * Dashboard Widget pour WordPress
 * 
 * @package Almetal_Analytics
 */

if (!defined('ABSPATH')) {
    exit;
}

class Almetal_Analytics_Dashboard {
    
    public function __construct() {
        add_action('wp_dashboard_setup', array($this, 'add_dashboard_widget'));
    }
    
    /**
     * Ajouter le widget au dashboard WordPress
     */
    public function add_dashboard_widget() {
        wp_add_dashboard_widget(
            'almetal_analytics_widget',
            __('📊 Analytics - Aperçu', 'almetal-analytics'),
            array($this, 'render_widget')
        );
    }
    
    /**
     * Afficher le widget
     */
    public function render_widget() {
        $stats = Almetal_Analytics_Database::get_stats('7days');
        ?>
        <div class="almetal-dashboard-widget">
            <div class="almetal-widget-stats">
                <div class="almetal-widget-stat">
                    <span class="almetal-widget-value"><?php echo number_format_i18n($stats['total_visits']); ?></span>
                    <span class="almetal-widget-label"><?php _e('Visites (7j)', 'almetal-analytics'); ?></span>
                </div>
                <div class="almetal-widget-stat">
                    <span class="almetal-widget-value"><?php echo number_format_i18n($stats['unique_visitors']); ?></span>
                    <span class="almetal-widget-label"><?php _e('Visiteurs', 'almetal-analytics'); ?></span>
                </div>
                <div class="almetal-widget-stat">
                    <span class="almetal-widget-value"><?php echo $stats['bounce_rate']; ?>%</span>
                    <span class="almetal-widget-label"><?php _e('Rebond', 'almetal-analytics'); ?></span>
                </div>
            </div>
            <p style="margin-top: 15px;">
                <a href="<?php echo admin_url('admin.php?page=almetal-analytics'); ?>" class="button button-primary">
                    <?php _e('Voir le dashboard complet', 'almetal-analytics'); ?>
                </a>
            </p>
        </div>
        <style>
            .almetal-widget-stats { display: flex; gap: 20px; }
            .almetal-widget-stat { text-align: center; flex: 1; }
            .almetal-widget-value { display: block; font-size: 24px; font-weight: 700; color: #F08B18; }
            .almetal-widget-label { display: block; font-size: 12px; color: #666; margin-top: 4px; }
        </style>
        <?php
    }
}

// Initialiser
new Almetal_Analytics_Dashboard();
