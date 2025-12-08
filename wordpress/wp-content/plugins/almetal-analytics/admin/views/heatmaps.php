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
    let currentUrl = '';
    
    $('.view-heatmap').on('click', function() {
        currentUrl = $(this).data('url');
        loadHeatmap();
    });
    
    $('#heatmap-device').on('change', function() {
        if (currentUrl) {
            loadHeatmap();
        }
    });
    
    function loadHeatmap() {
        const device = $('#heatmap-device').val();
        
        $('#heatmap-viewer').show();
        $('#heatmap-canvas').html('<p class="almetal-loading"><span class="spinner is-active"></span> Chargement...</p>');
        
        // Charger les données avec l'URL en paramètre GET
        $.ajax({
            url: almetalAnalyticsAdmin.restUrl + 'heatmap',
            data: {
                page_url: currentUrl,
                device: device,
                period: '30days'
            },
            headers: { 'X-WP-Nonce': almetalAnalyticsAdmin.nonce },
            success: function(data) {
                renderHeatmap(data);
            },
            error: function(xhr, status, error) {
                $('#heatmap-canvas').html('<p class="almetal-no-data">Erreur: ' + error + '</p>');
            }
        });
    }
    
    function renderHeatmap(data) {
        const canvas = $('#heatmap-canvas');
        canvas.empty();
        
        if (!data.clicks || data.clicks.length === 0) {
            canvas.html('<p class="almetal-no-data"><?php _e('Pas de données pour cette page', 'almetal-analytics'); ?></p>');
            $('#top-elements-list').empty();
            return;
        }
        
        // Afficher les stats
        const statsHtml = `
            <div class="almetal-heatmap-stats" style="margin-bottom: 15px; padding: 10px; background: #f9f9f9; border-radius: 4px;">
                <strong>Page:</strong> ${data.page_url} | 
                <strong>Total clics:</strong> ${data.total_clicks} | 
                <strong>Période:</strong> ${data.period}
            </div>
        `;
        canvas.append(statsHtml);
        
        // Créer le conteneur de la heatmap
        const heatmapContainer = $('<div>').css({
            position: 'relative',
            width: '100%',
            minHeight: '600px',
            background: 'linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%)',
            borderRadius: '8px',
            overflow: 'hidden'
        });
        
        // Trouver le max pour normaliser et les dimensions
        const maxCount = Math.max(...data.clicks.map(c => parseInt(c.count)));
        const maxX = Math.max(...data.clicks.map(c => parseInt(c.x)));
        const maxY = Math.max(...data.clicks.map(c => parseInt(c.y)));
        
        // Ajuster la hauteur du conteneur
        heatmapContainer.css('height', Math.min(maxY + 100, 800) + 'px');
        
        // Créer les points de chaleur
        data.clicks.forEach(click => {
            const intensity = parseInt(click.count) / maxCount;
            const size = 30 + (intensity * 60);
            const opacity = 0.4 + (intensity * 0.5);
            
            // Couleur basée sur l'intensité (vert -> jaune -> orange -> rouge)
            let color;
            if (intensity < 0.25) {
                color = `rgba(76, 175, 80, ${opacity})`; // Vert
            } else if (intensity < 0.5) {
                color = `rgba(255, 235, 59, ${opacity})`; // Jaune
            } else if (intensity < 0.75) {
                color = `rgba(255, 152, 0, ${opacity})`; // Orange
            } else {
                color = `rgba(244, 67, 54, ${opacity})`; // Rouge
            }
            
            const dot = $('<div>').css({
                position: 'absolute',
                left: click.x + 'px',
                top: click.y + 'px',
                width: size + 'px',
                height: size + 'px',
                borderRadius: '50%',
                background: `radial-gradient(circle, ${color} 0%, transparent 70%)`,
                transform: 'translate(-50%, -50%)',
                pointerEvents: 'none',
                zIndex: Math.round(intensity * 100)
            }).attr('title', `${click.count} clics`);
            
            heatmapContainer.append(dot);
        });
        
        canvas.append(heatmapContainer);
        
        // Légende
        const legend = `
            <div style="margin-top: 15px; display: flex; gap: 20px; align-items: center;">
                <span style="display: flex; align-items: center; gap: 5px;">
                    <span style="width: 15px; height: 15px; background: #4CAF50; border-radius: 50%;"></span> Faible
                </span>
                <span style="display: flex; align-items: center; gap: 5px;">
                    <span style="width: 15px; height: 15px; background: #FFEB3B; border-radius: 50%;"></span> Moyen
                </span>
                <span style="display: flex; align-items: center; gap: 5px;">
                    <span style="width: 15px; height: 15px; background: #FF9800; border-radius: 50%;"></span> Élevé
                </span>
                <span style="display: flex; align-items: center; gap: 5px;">
                    <span style="width: 15px; height: 15px; background: #F44336; border-radius: 50%;"></span> Très élevé
                </span>
            </div>
        `;
        canvas.append(legend);
        
        // Afficher les éléments les plus cliqués
        const list = $('#top-elements-list');
        list.empty();
        
        if (data.top_elements && data.top_elements.length > 0) {
            data.top_elements.forEach((el, index) => {
                const percent = data.total_clicks > 0 ? Math.round((el.clicks / data.total_clicks) * 100) : 0;
                list.append(`
                    <li style="margin-bottom: 8px; padding: 8px; background: #f5f5f5; border-radius: 4px;">
                        <strong>#${index + 1}</strong> 
                        <code style="background: #e0e0e0; padding: 2px 6px; border-radius: 3px;">${el.element_selector}</code>
                        <span style="float: right; color: #F08B18; font-weight: bold;">${el.clicks} clics (${percent}%)</span>
                    </li>
                `);
            });
        } else {
            list.append('<li>Aucun élément identifié</li>');
        }
    }
});
</script>
