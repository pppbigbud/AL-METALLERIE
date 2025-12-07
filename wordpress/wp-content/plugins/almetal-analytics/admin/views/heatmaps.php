<?php
/**
 * Vue Heatmaps
 * 
 * @package Almetal_Analytics
 */

if (!defined('ABSPATH')) {
    exit;
}

$tracked_pages = Almetal_Analytics_Heatmap::get_tracked_pages(20);
?>

<div class="wrap almetal-analytics-wrap">
    <div class="almetal-analytics-header">
        <h1>
            <span class="dashicons dashicons-location-alt"></span>
            <?php _e('Heatmaps', 'almetal-analytics'); ?>
        </h1>
    </div>
    
    <?php if (!get_option('almetal_analytics_heatmap_enabled', false)) : ?>
    <div class="notice notice-warning">
        <p>
            <strong><?php _e('Heatmaps désactivées', 'almetal-analytics'); ?></strong> - 
            <?php _e('Activez les heatmaps dans les réglages pour commencer à collecter des données.', 'almetal-analytics'); ?>
            <a href="<?php echo admin_url('admin.php?page=almetal-analytics-settings'); ?>"><?php _e('Aller aux réglages', 'almetal-analytics'); ?></a>
        </p>
    </div>
    <?php endif; ?>
    
    <div class="almetal-heatmap-container">
        <!-- Liste des pages -->
        <div class="almetal-table-card">
            <div class="almetal-table-header">
                <h3><?php _e('Pages avec données heatmap', 'almetal-analytics'); ?></h3>
            </div>
            <div class="almetal-table-body">
                <?php if (empty($tracked_pages)) : ?>
                    <p class="almetal-no-data"><?php _e('Aucune donnée de heatmap disponible.', 'almetal-analytics'); ?></p>
                <?php else : ?>
                    <table class="almetal-table">
                        <thead>
                            <tr>
                                <th><?php _e('Page', 'almetal-analytics'); ?></th>
                                <th><?php _e('Clics', 'almetal-analytics'); ?></th>
                                <th><?php _e('Dernier clic', 'almetal-analytics'); ?></th>
                                <th><?php _e('Actions', 'almetal-analytics'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tracked_pages as $page) : ?>
                            <tr>
                                <td>
                                    <a href="<?php echo esc_url(home_url($page['page_url'])); ?>" target="_blank">
                                        <?php echo esc_html($page['page_url']); ?>
                                    </a>
                                </td>
                                <td><?php echo number_format_i18n($page['total_clicks']); ?></td>
                                <td><?php echo esc_html($page['last_click']); ?></td>
                                <td>
                                    <button class="button button-small view-heatmap" 
                                            data-url="<?php echo esc_attr($page['page_url']); ?>">
                                        <?php _e('Voir', 'almetal-analytics'); ?>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Visualisation heatmap -->
        <div class="almetal-table-card" id="heatmap-viewer" style="display: none;">
            <div class="almetal-table-header">
                <h3><?php _e('Visualisation', 'almetal-analytics'); ?></h3>
                <div class="almetal-heatmap-controls">
                    <select id="heatmap-device" class="almetal-select">
                        <option value="all"><?php _e('Tous les appareils', 'almetal-analytics'); ?></option>
                        <option value="desktop"><?php _e('Desktop', 'almetal-analytics'); ?></option>
                        <option value="mobile"><?php _e('Mobile', 'almetal-analytics'); ?></option>
                        <option value="tablet"><?php _e('Tablet', 'almetal-analytics'); ?></option>
                    </select>
                </div>
            </div>
            <div class="almetal-table-body">
                <div id="heatmap-canvas" style="position: relative; min-height: 400px; background: #f5f5f5;">
                    <!-- Heatmap sera rendue ici -->
                </div>
                <div id="heatmap-elements" style="margin-top: 20px;">
                    <h4><?php _e('Éléments les plus cliqués', 'almetal-analytics'); ?></h4>
                    <ul id="top-elements-list"></ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('.view-heatmap').on('click', function() {
        const url = $(this).data('url');
        const device = $('#heatmap-device').val();
        
        $('#heatmap-viewer').show();
        
        // Charger les données
        $.ajax({
            url: almetalAnalyticsAdmin.restUrl + 'heatmap/' + encodeURIComponent(url) + '?device=' + device,
            headers: { 'X-WP-Nonce': almetalAnalyticsAdmin.nonce },
            success: function(data) {
                renderHeatmap(data);
            }
        });
    });
    
    function renderHeatmap(data) {
        const canvas = $('#heatmap-canvas');
        canvas.empty();
        
        if (!data.clicks || data.clicks.length === 0) {
            canvas.html('<p class="almetal-no-data"><?php _e('Pas de données pour cette page', 'almetal-analytics'); ?></p>');
            return;
        }
        
        // Trouver le max pour normaliser
        const maxCount = Math.max(...data.clicks.map(c => c.count));
        
        // Créer les points de chaleur
        data.clicks.forEach(click => {
            const intensity = click.count / maxCount;
            const size = 20 + (intensity * 40);
            const opacity = 0.3 + (intensity * 0.5);
            
            const dot = $('<div>').css({
                position: 'absolute',
                left: click.x + 'px',
                top: click.y + 'px',
                width: size + 'px',
                height: size + 'px',
                borderRadius: '50%',
                background: `radial-gradient(circle, rgba(240, 139, 24, ${opacity}) 0%, transparent 70%)`,
                transform: 'translate(-50%, -50%)',
                pointerEvents: 'none'
            });
            
            canvas.append(dot);
        });
        
        // Afficher les éléments les plus cliqués
        const list = $('#top-elements-list');
        list.empty();
        
        if (data.top_elements) {
            data.top_elements.forEach(el => {
                list.append(`<li><code>${el.element_selector}</code> - ${el.clicks} clics</li>`);
            });
        }
    }
});
</script>
