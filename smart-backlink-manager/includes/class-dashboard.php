<?php
/**
 * Dashboard class for Smart Backlink Manager
 */

if (!defined('ABSPATH')) {
    exit;
}

class SBM_Dashboard {
    
    public function init(): void {
        // Initialisation du dashboard
    }
    
    public function render_dashboard(): void {
        $stats = $this->get_dashboard_stats();
        ?>
        <div class="wrap sbm-dashboard">
            <h1 class="wp-heading-inline">
                <?php _e('Smart Backlink Manager', 'smart-backlink-manager'); ?>
            </h1>
            <hr class="wp-header-end">
            
            <div class="sbm-welcome-panel">
                <h2>
                    <?php _e('Bienvenue sur Smart Backlink Manager', 'smart-backlink-manager'); ?>
                    <span class="sbm-site-info">
                        <?php echo esc_html(get_option('sbm_site_name', 'AL Métallerie')); ?>
                    </span>
                </h2>
                <p class="sbm-dashboard-description">
                    <?php _e('Gérez intelligemment vos backlinks et optimisez vos liens internes pour un meilleur SEO.', 'smart-backlink-manager'); ?>
                </p>
            </div>
            
            <div class="sbm-stats-grid">
                <!-- Carte Liens Internes -->
                <div class="sbm-stat-card">
                    <div class="sbm-stat-icon sbm-icon-internal">
                        <span class="dashicons dashicons-admin-links"></span>
                    </div>
                    <div class="sbm-stat-content">
                        <h3><?php _e('Liens Internes', 'smart-backlink-manager'); ?></h3>
                        <div class="sbm-stat-number"><?php echo number_format($stats['internal_links']['total']); ?></div>
                        <div class="sbm-stat-label">
                            <?php 
                            printf(
                                __('%s ce mois', 'smart-backlink-manager'),
                                '<span class="sbm-stat-positive">+' . number_format($stats['internal_links']['this_month']) . '</span>'
                            );
                            ?>
                        </div>
                    </div>
                </div>
                
                <!-- Carte Backlinks -->
                <div class="sbm-stat-card">
                    <div class="sbm-stat-icon sbm-icon-backlinks">
                        <span class="dashicons dashicons-external"></span>
                    </div>
                    <div class="sbm-stat-content">
                        <h3><?php _e('Backlinks', 'smart-backlink-manager'); ?></h3>
                        <div class="sbm-stat-number"><?php echo number_format($stats['backlinks']['total']); ?></div>
                        <div class="sbm-stat-label">
                            <?php 
                            printf(
                                __('%s actifs', 'smart-backlink-manager'),
                                '<span class="sbm-stat-positive">' . number_format($stats['backlinks']['active']) . '</span>'
                            );
                            ?>
                        </div>
                    </div>
                </div>
                
                <!-- Carte Opportunités -->
                <div class="sbm-stat-card">
                    <div class="sbm-stat-icon sbm-icon-opportunities">
                        <span class="dashicons dashicons-star-filled"></span>
                    </div>
                    <div class="sbm-stat-content">
                        <h3><?php _e('Opportunités', 'smart-backlink-manager'); ?></h3>
                        <div class="sbm-stat-number"><?php echo number_format($stats['opportunities']['total']); ?></div>
                        <div class="sbm-stat-label">
                            <?php 
                            printf(
                                __('%s à contacter', 'smart-backlink-manager'),
                                '<span class="sbm-stat-neutral">' . number_format($stats['opportunities']['to_contact']) . '</span>'
                            );
                            ?>
                        </div>
                    </div>
                </div>
                
                <!-- Carte Score SEO -->
                <div class="sbm-stat-card">
                    <div class="sbm-stat-icon sbm-icon-seo">
                        <span class="dashicons dashicons-chart-line"></span>
                    </div>
                    <div class="sbm-stat-content">
                        <h3><?php _e('Score SEO', 'smart-backlink-manager'); ?></h3>
                        <div class="sbm-stat-number"><?php echo $stats['seo_score']; ?>/100</div>
                        <div class="sbm-stat-label">
                            <span class="sbm-stat-<?php echo $stats['seo_score'] >= 80 ? 'positive' : ($stats['seo_score'] >= 50 ? 'neutral' : 'negative'); ?>">
                                <?php echo $stats['seo_score'] >= 80 ? __('Excellent', 'smart-backlink-manager') : ($stats['seo_score'] >= 50 ? __('Bon', 'smart-backlink-manager') : __('À améliorer', 'smart-backlink-manager')); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions rapides -->
            <div class="sbm-quick-actions">
                <h2><?php _e('Actions Rapides', 'smart-backlink-manager'); ?></h2>
                <div class="sbm-action-buttons">
                    <a href="<?php echo admin_url('admin.php?page=sbm-internal-links'); ?>" class="sbm-action-btn sbm-btn-primary">
                        <span class="dashicons dashicons-plus-alt"></span>
                        <?php _e('Ajouter un lien interne', 'smart-backlink-manager'); ?>
                    </a>
                    <a href="<?php echo admin_url('admin.php?page=sbm-backlinks'); ?>" class="sbm-action-btn sbm-btn-secondary">
                        <span class="dashicons dashicons-update"></span>
                        <?php _e('Vérifier les backlinks', 'smart-backlink-manager'); ?>
                    </a>
                    <a href="<?php echo admin_url('admin.php?page=sbm-opportunities'); ?>" class="sbm-action-btn sbm-btn-secondary">
                        <span class="dashicons dashicons-star-filled"></span>
                        <?php _e('Nouvelle opportunité', 'smart-backlink-manager'); ?>
                    </a>
                    <a href="<?php echo admin_url('admin.php?page=sbm-settings'); ?>" class="sbm-action-btn sbm-btn-tertiary">
                        <span class="dashicons dashicons-admin-settings"></span>
                        <?php _e('Configuration', 'smart-backlink-manager'); ?>
                    </a>
                </div>
            </div>
            
            <!-- Graphiques et tendances -->
            <div class="sbm-dashboard-sections">
                <div class="sbm-section">
                    <h3><?php _e('Évolution des Liens', 'smart-backlink-manager'); ?></h3>
                    <div class="sbm-chart-container">
                        <canvas id="sbm-links-chart"></canvas>
                    </div>
                </div>
                
                <div class="sbm-section">
                    <h3><?php _e('Top Pages Liées', 'smart-backlink-manager'); ?></h3>
                    <div class="sbm-top-pages">
                        <?php foreach ($stats['top_pages'] as $page): ?>
                            <div class="sbm-page-item">
                                <div class="sbm-page-title">
                                    <a href="<?php echo get_edit_post_link($page->ID); ?>">
                                        <?php echo esc_html(get_the_title($page->ID)); ?>
                                    </a>
                                </div>
                                <div class="sbm-page-links">
                                    <?php echo number_format($page->link_count); ?> liens
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- Dernières activités -->
            <div class="sbm-section sbm-full-width">
                <h3><?php _e('Dernières Activités', 'smart-backlink-manager'); ?></h3>
                <div class="sbm-activities">
                    <?php foreach ($stats['recent_activities'] as $activity): ?>
                        <div class="sbm-activity-item">
                            <div class="sbm-activity-icon sbm-<?php echo esc_attr($activity->type); ?>">
                                <span class="dashicons <?php echo esc_attr($activity->icon); ?>"></span>
                            </div>
                            <div class="sbm-activity-content">
                                <div class="sbm-activity-title"><?php echo esc_html($activity->title); ?></div>
                                <div class="sbm-activity-time"><?php echo esc_html($activity->time); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <script>
        // Graphique simple avec Chart.js ou alternative
        jQuery(document).ready(function($) {
            // Initialiser les graphiques si Chart.js est disponible
            if (typeof Chart !== 'undefined') {
                var ctx = document.getElementById('sbm-links-chart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: <?php echo json_encode($stats['chart_labels']); ?>,
                        datasets: [{
                            label: '<?php _e('Liens internes', 'smart-backlink-manager'); ?>',
                            data: <?php echo json_encode($stats['internal_links_data']); ?>,
                            borderColor: '#0073aa',
                            backgroundColor: 'rgba(0, 115, 170, 0.1)',
                            tension: 0.4
                        }, {
                            label: '<?php _e('Backlinks', 'smart-backlink-manager'); ?>',
                            data: <?php echo json_encode($stats['backlinks_data']); ?>,
                            borderColor: '#66c6e4',
                            backgroundColor: 'rgba(102, 198, 228, 0.1)',
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }
        });
        </script>
        <?php
    }
    
    private function get_dashboard_stats(): array {
        global $wpdb;
        
        $stats = [];
        
        // Statistiques des liens internes
        $internal_table = $wpdb->prefix . 'sbm_internal_links';
        $stats['internal_links'] = [
            'total' => intval($wpdb->get_var("SELECT COUNT(*) FROM $internal_table")),
            'this_month' => intval($wpdb->get_var("SELECT COUNT(*) FROM $internal_table WHERE date_added >= DATE_FORMAT(NOW(), '%Y-%m-01')"))
        ];
        
        // Statistiques des backlinks
        $backlinks_table = $wpdb->prefix . 'sbm_backlinks';
        $stats['backlinks'] = [
            'total' => intval($wpdb->get_var("SELECT COUNT(*) FROM $backlinks_table")),
            'active' => intval($wpdb->get_var("SELECT COUNT(*) FROM $backlinks_table WHERE status = 'active'")),
            'dead' => intval($wpdb->get_var("SELECT COUNT(*) FROM $backlinks_table WHERE status = 'dead'"))
        ];
        
        // Statistiques des opportunités
        $opportunities_table = $wpdb->prefix . 'sbm_opportunities';
        $stats['opportunities'] = [
            'total' => intval($wpdb->get_var("SELECT COUNT(*) FROM $opportunities_table")),
            'to_contact' => intval($wpdb->get_var("SELECT COUNT(*) FROM $opportunities_table WHERE status = 'to_contact'")),
            'in_progress' => intval($wpdb->get_var("SELECT COUNT(*) FROM $opportunities_table WHERE status = 'in_progress'")),
            'obtained' => intval($wpdb->get_var("SELECT COUNT(*) FROM $opportunities_table WHERE status = 'obtained'"))
        ];
        
        // Score SEO (calcul simple)
        $internal_links_score = min(40, $stats['internal_links']['total'] * 2);
        $backlinks_score = min(40, $stats['backlinks']['active'] * 3);
        $opportunities_score = min(20, $stats['opportunities']['obtained'] * 4);
        $stats['seo_score'] = $internal_links_score + $backlinks_score + $opportunities_score;
        
        // Top pages
        $stats['top_pages'] = $wpdb->get_results("
            SELECT to_post_id as ID, COUNT(*) as link_count 
            FROM $internal_table 
            GROUP BY to_post_id 
            ORDER BY link_count DESC 
            LIMIT 5
        ");
        
        // Données pour le graphique (30 derniers jours)
        $stats['chart_labels'] = [];
        $stats['internal_links_data'] = [];
        $stats['backlinks_data'] = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $stats['chart_labels'][] = date('d/m', strtotime($date));
            
            $stats['internal_links_data'][] = intval($wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM $internal_table WHERE DATE(date_added) = %s",
                $date
            )));
            
            $stats['backlinks_data'][] = intval($wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM $backlinks_table WHERE DATE(date_added) = %s",
                $date
            )));
        }
        
        // Activités récentes
        $stats['recent_activities'] = $this->get_recent_activities();
        
        return $stats;
    }
    
    private function get_recent_activities(): array {
        $activities = [];
        
        // Liens internes récents
        global $wpdb;
        $internal_table = $wpdb->prefix . 'sbm_internal_links';
        $recent_internal = $wpdb->get_results("
            SELECT * FROM $internal_table 
            ORDER BY date_added DESC 
            LIMIT 3
        ");
        
        foreach ($recent_internal as $link) {
            $activities[] = (object) [
                'type' => 'internal-link',
                'icon' => 'dashicons-admin-links',
                'title' => sprintf(
                    __('Lien interne ajouté vers "%s"', 'smart-backlink-manager'),
                    get_the_title($link->to_post_id)
                ),
                'time' => human_time_diff(strtotime($link->date_added), current_time('timestamp')) . ' ' . __('ago', 'smart-backlink-manager')
            ];
        }
        
        // Backlinks récents
        $backlinks_table = $wpdb->prefix . 'sbm_backlinks';
        $recent_backlinks = $wpdb->get_results("
            SELECT * FROM $backlinks_table 
            ORDER BY date_added DESC 
            LIMIT 2
        ");
        
        foreach ($recent_backlinks as $backlink) {
            $activities[] = (object) [
                'type' => 'backlink',
                'icon' => 'dashicons-external',
                'title' => sprintf(
                    __('Backlink ajouté depuis "%s"', 'smart-backlink-manager'),
                    parse_url($backlink->source_url, PHP_URL_HOST)
                ),
                'time' => human_time_diff(strtotime($backlink->date_added), current_time('timestamp')) . ' ' . __('ago', 'smart-backlink-manager')
            ];
        }
        
        return array_slice($activities, 0, 5);
    }
}
