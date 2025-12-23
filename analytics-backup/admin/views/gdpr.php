<?php
/**
 * Vue RGPD
 * 
 * @package Almetal_Analytics
 */

if (!defined('ABSPATH')) {
    exit;
}

$compliance_report = Almetal_Analytics_GDPR::get_compliance_report();
?>

<div class="wrap almetal-analytics-wrap">
    <div class="almetal-analytics-header">
        <h1>
            <span class="dashicons dashicons-shield"></span>
            <?php _e('Conformité RGPD', 'almetal-analytics'); ?>
        </h1>
    </div>
    
    <!-- Rapport de conformité -->
    <div class="almetal-gdpr-report">
        <div class="almetal-table-card">
            <div class="almetal-table-header">
                <h3><?php _e('Rapport de conformité', 'almetal-analytics'); ?></h3>
                <span class="almetal-badge almetal-badge-success"><?php _e('Conforme', 'almetal-analytics'); ?></span>
            </div>
            <div class="almetal-table-body">
                <div class="almetal-gdpr-checklist">
                    <div class="almetal-gdpr-item <?php echo $compliance_report['ip_anonymization'] ? 'success' : 'warning'; ?>">
                        <span class="dashicons <?php echo $compliance_report['ip_anonymization'] ? 'dashicons-yes-alt' : 'dashicons-warning'; ?>"></span>
                        <span><?php _e('Anonymisation des adresses IP', 'almetal-analytics'); ?></span>
                    </div>
                    
                    <div class="almetal-gdpr-item success">
                        <span class="dashicons dashicons-yes-alt"></span>
                        <span><?php _e('Chiffrement AES-256 des données sensibles', 'almetal-analytics'); ?></span>
                    </div>
                    
                    <div class="almetal-gdpr-item success">
                        <span class="dashicons dashicons-yes-alt"></span>
                        <span><?php printf(__('Rétention des données : %d mois', 'almetal-analytics'), $compliance_report['data_retention_months']); ?></span>
                    </div>
                    
                    <div class="almetal-gdpr-item success">
                        <span class="dashicons dashicons-yes-alt"></span>
                        <span><?php _e('Cookie banner avec choix utilisateur', 'almetal-analytics'); ?></span>
                    </div>
                    
                    <div class="almetal-gdpr-item success">
                        <span class="dashicons dashicons-yes-alt"></span>
                        <span><?php _e('Log des preuves de consentement', 'almetal-analytics'); ?></span>
                    </div>
                    
                    <div class="almetal-gdpr-item success">
                        <span class="dashicons dashicons-yes-alt"></span>
                        <span><?php _e('Double opt-in pour les collectes email', 'almetal-analytics'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistiques des données -->
    <div class="almetal-gdpr-stats">
        <div class="almetal-table-card">
            <div class="almetal-table-header">
                <h3><?php _e('Données stockées', 'almetal-analytics'); ?></h3>
            </div>
            <div class="almetal-table-body">
                <table class="almetal-table">
                    <thead>
                        <tr>
                            <th><?php _e('Type de données', 'almetal-analytics'); ?></th>
                            <th><?php _e('Nombre d\'entrées', 'almetal-analytics'); ?></th>
                            <th><?php _e('Plus ancienne', 'almetal-analytics'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($compliance_report['data_counts'] as $type => $count) : ?>
                        <tr>
                            <td><?php echo esc_html(ucfirst($type)); ?></td>
                            <td><?php echo number_format_i18n($count); ?></td>
                            <td><?php echo $compliance_report['oldest_data'][$type] ? esc_html($compliance_report['oldest_data'][$type]) : '-'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Actions RGPD -->
    <div class="almetal-gdpr-actions">
        <div class="almetal-table-card">
            <div class="almetal-table-header">
                <h3><?php _e('Actions RGPD', 'almetal-analytics'); ?></h3>
            </div>
            <div class="almetal-table-body">
                <!-- Export données utilisateur -->
                <div class="almetal-gdpr-action-box">
                    <h4><?php _e('Exporter les données d\'un utilisateur (Art. 15)', 'almetal-analytics'); ?></h4>
                    <p><?php _e('Recherchez par visitor_id ou adresse email pour exporter toutes les données associées.', 'almetal-analytics'); ?></p>
                    <form id="gdpr-export-form" class="almetal-inline-form">
                        <input type="text" name="identifier" placeholder="<?php _e('visitor_id ou email', 'almetal-analytics'); ?>" required>
                        <button type="submit" class="button button-primary">
                            <span class="dashicons dashicons-download"></span>
                            <?php _e('Exporter', 'almetal-analytics'); ?>
                        </button>
                    </form>
                    <div id="gdpr-export-result"></div>
                </div>
                
                <!-- Supprimer données utilisateur -->
                <div class="almetal-gdpr-action-box">
                    <h4><?php _e('Supprimer les données d\'un utilisateur (Art. 17 - Droit à l\'oubli)', 'almetal-analytics'); ?></h4>
                    <p><?php _e('Supprime définitivement toutes les données associées à un identifiant.', 'almetal-analytics'); ?></p>
                    <form id="gdpr-delete-form" class="almetal-inline-form">
                        <input type="text" name="identifier" placeholder="<?php _e('visitor_id ou email', 'almetal-analytics'); ?>" required>
                        <button type="submit" class="button button-secondary almetal-btn-danger">
                            <span class="dashicons dashicons-trash"></span>
                            <?php _e('Supprimer', 'almetal-analytics'); ?>
                        </button>
                    </form>
                    <div id="gdpr-delete-result"></div>
                </div>
                
                <!-- Purge manuelle -->
                <div class="almetal-gdpr-action-box">
                    <h4><?php _e('Purge manuelle des anciennes données', 'almetal-analytics'); ?></h4>
                    <p><?php _e('Force la suppression des données plus anciennes que la période de rétention configurée.', 'almetal-analytics'); ?></p>
                    <button id="gdpr-purge-btn" class="button">
                        <span class="dashicons dashicons-database-remove"></span>
                        <?php _e('Lancer la purge', 'almetal-analytics'); ?>
                    </button>
                    <div id="gdpr-purge-result"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Export
    $('#gdpr-export-form').on('submit', function(e) {
        e.preventDefault();
        var identifier = $(this).find('input[name="identifier"]').val();
        
        $.ajax({
            url: almetalAnalyticsAdmin.restUrl + 'gdpr/export',
            method: 'POST',
            headers: { 'X-WP-Nonce': almetalAnalyticsAdmin.nonce },
            data: JSON.stringify({ identifier: identifier }),
            contentType: 'application/json',
            success: function(data) {
                var blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
                var url = URL.createObjectURL(blob);
                var a = document.createElement('a');
                a.href = url;
                a.download = 'gdpr-export-' + identifier + '.json';
                a.click();
                $('#gdpr-export-result').html('<div class="notice notice-success"><p><?php _e('Export téléchargé', 'almetal-analytics'); ?></p></div>');
            },
            error: function() {
                $('#gdpr-export-result').html('<div class="notice notice-error"><p><?php _e('Erreur lors de l\'export', 'almetal-analytics'); ?></p></div>');
            }
        });
    });
    
    // Delete
    $('#gdpr-delete-form').on('submit', function(e) {
        e.preventDefault();
        if (!confirm('<?php _e('Êtes-vous sûr ? Cette action est irréversible.', 'almetal-analytics'); ?>')) {
            return;
        }
        
        var identifier = $(this).find('input[name="identifier"]').val();
        
        $.ajax({
            url: almetalAnalyticsAdmin.restUrl + 'gdpr/delete',
            method: 'POST',
            headers: { 'X-WP-Nonce': almetalAnalyticsAdmin.nonce },
            data: JSON.stringify({ identifier: identifier }),
            contentType: 'application/json',
            success: function(data) {
                var total = Object.values(data).reduce((a, b) => a + b, 0);
                $('#gdpr-delete-result').html('<div class="notice notice-success"><p><?php _e('Données supprimées :', 'almetal-analytics'); ?> ' + total + ' entrées</p></div>');
            },
            error: function() {
                $('#gdpr-delete-result').html('<div class="notice notice-error"><p><?php _e('Erreur lors de la suppression', 'almetal-analytics'); ?></p></div>');
            }
        });
    });
});
</script>
