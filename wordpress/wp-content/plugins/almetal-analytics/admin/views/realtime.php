<?php
/**
 * Vue Temps réel
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
            <span class="dashicons dashicons-performance"></span>
            <?php _e('Temps réel', 'almetal-analytics'); ?>
            <span class="almetal-live-indicator">
                <span class="almetal-live-dot"></span>
                <?php _e('LIVE', 'almetal-analytics'); ?>
            </span>
        </h1>
    </div>
    
    <!-- Visiteurs actifs -->
    <div class="almetal-realtime-hero">
        <div class="almetal-realtime-count">
            <span id="active-visitors-count">0</span>
        </div>
        <div class="almetal-realtime-label">
            <?php _e('visiteurs actifs en ce moment', 'almetal-analytics'); ?>
        </div>
    </div>
    
    <!-- Activité récente -->
    <div class="almetal-realtime-activity">
        <div class="almetal-table-card">
            <div class="almetal-table-header">
                <h3><?php _e('Activité en temps réel', 'almetal-analytics'); ?></h3>
            </div>
            <div class="almetal-table-body">
                <table class="almetal-table" id="realtime-table">
                    <thead>
                        <tr>
                            <th><?php _e('Heure', 'almetal-analytics'); ?></th>
                            <th><?php _e('Page', 'almetal-analytics'); ?></th>
                            <th><?php _e('Appareil', 'almetal-analytics'); ?></th>
                            <th><?php _e('Navigateur', 'almetal-analytics'); ?></th>
                            <th><?php _e('Pays', 'almetal-analytics'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="realtime-tbody">
                        <tr>
                            <td colspan="5" class="almetal-loading">
                                <span class="spinner is-active"></span>
                                <?php _e('Chargement...', 'almetal-analytics'); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Rafraîchir toutes les 5 secondes
(function() {
    function updateRealtime() {
        fetch(almetalAnalyticsAdmin.restUrl + 'realtime', {
            headers: {
                'X-WP-Nonce': almetalAnalyticsAdmin.nonce
            }
        })
        .then(response => response.json())
        .then(data => {
            // Mettre à jour le compteur
            document.getElementById('active-visitors-count').textContent = data.active_visitors;
            
            // Mettre à jour le tableau
            const tbody = document.getElementById('realtime-tbody');
            if (data.recent_visits.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="almetal-no-data"><?php _e('Aucune activité récente', 'almetal-analytics'); ?></td></tr>';
            } else {
                tbody.innerHTML = data.recent_visits.map(visit => `
                    <tr class="almetal-fade-in">
                        <td>${new Date(visit.created_at).toLocaleTimeString()}</td>
                        <td title="${visit.page_url}">${visit.page_title || visit.page_url.substring(0, 50)}</td>
                        <td><span class="almetal-device-badge ${visit.device_type}">${visit.device_type}</span></td>
                        <td>${visit.browser}</td>
                        <td>${visit.country || '-'}</td>
                    </tr>
                `).join('');
            }
        })
        .catch(error => {
            console.error('Realtime error:', error);
        });
    }
    
    // Premier chargement
    updateRealtime();
    
    // Rafraîchir toutes les 5 secondes
    setInterval(updateRealtime, 5000);
})();
</script>
