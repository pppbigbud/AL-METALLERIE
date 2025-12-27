<?php
/**
 * Classe de planification des tâches
 */
class ACSP_Scheduler {
    
    /**
     * Constructeur
     */
    public function __construct() {
        add_action('init', [$this, 'schedule_events']);
        add_action('acsp_generate_weekly_article', [$this, 'execute_scheduled_generation']);
        add_action('acsp_cleanup_old_articles', [$this, 'cleanup_old_articles']);
    }
    
    /**
     * Planifier les événements
     */
    public function schedule_events() {
        // Planifier la génération hebdomadaire
        if (!wp_next_scheduled('acsp_generate_weekly_article')) {
            $this->schedule_weekly_generation();
        }
        
        // Planifier le nettoyage mensuel
        if (!wp_next_scheduled('acsp_cleanup_old_articles')) {
            wp_schedule_event(strtotime('first day of next month 00:00:00'), 'monthly', 'acsp_cleanup_old_articles');
        }
    }
    
    /**
     * Planifier la génération hebdomadaire
     */
    public function schedule_weekly_generation() {
        $day = get_option('acsp_publish_day', 'monday');
        $time = get_option('acsp_publish_time', '08:00');
        
        // Calculer le prochain jour/heure
        $days_map = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday'
        ];
        
        $next_time = strtotime('next ' . $days_map[$day] . ' ' . $time);
        
        wp_schedule_event($next_time, 'weekly', 'acsp_generate_weekly_article');
    }
    
    /**
     * Exécuter la génération planifiée
     */
    public function execute_scheduled_generation() {
        // Vérifier si la génération automatique est activée
        if (!get_option('acsp_enable_auto_generation', 1)) {
            return;
        }
        
        // Générer l'article
        $generator = new ACSP_Content_Generator();
        $result = $generator->generate_and_publish_article();
        
        // Logger le résultat
        $this->log_generation_attempt($result);
        
        // Planifier la prochaine génération
        wp_clear_scheduled_hook('acsp_generate_weekly_article');
        $this->schedule_weekly_generation();
    }
    
    /**
     * Logger la tentative de génération
     */
    private function log_generation_attempt($result) {
        $log_entry = [
            'date' => current_time('mysql'),
            'success' => $result !== false,
            'post_id' => $result ?: null,
            'error' => $result === false ? 'Échec de la génération' : null
        ];
        
        // Sauvegarder dans les options (simple)
        $logs = get_option('acsp_generation_logs', []);
        array_unshift($logs, $log_entry);
        
        // Garder seulement les 100 derniers logs
        $logs = array_slice($logs, 0, 100);
        
        update_option('acsp_generation_logs', $logs);
    }
    
    /**
     * Nettoyer les anciens articles
     */
    public function cleanup_old_articles() {
        global $wpdb;
        
        $table_articles = $wpdb->prefix . 'acsp_articles';
        
        // Supprimer les articles de plus d'un an avec statut 'failed'
        $wpdb->query($wpdb->prepare("
            DELETE FROM $table_articles 
            WHERE status = 'failed' 
            AND created_at < DATE_SUB(NOW(), INTERVAL 1 YEAR)
        "));
        
        // Archiver les articles publiés de plus de 2 ans
        $wpdb->query($wpdb->prepare("
            UPDATE $table_articles 
            SET status = 'archived' 
            WHERE status = 'published' 
            AND published_date < DATE_SUB(NOW(), INTERVAL 2 YEAR)
        "));
    }
    
    /**
     * Obtenir la prochaine date de génération
     */
    public function get_next_generation_date() {
        $timestamp = wp_next_scheduled('acsp_generate_weekly_article');
        
        if ($timestamp) {
            return [
                'timestamp' => $timestamp,
                'date' => date('d/m/Y H:i', $timestamp),
                'relative' => human_time_diff($timestamp) . ' restant'
            ];
        }
        
        return null;
    }
    
    /**
     * Obtenir les statistiques de génération
     */
    public function get_generation_stats($period = 'month') {
        global $wpdb;
        
        $table_articles = $wpdb->prefix . 'acsp_articles';
        
        // Période
        $date_condition = '';
        switch ($period) {
            case 'week':
                $date_condition = "AND created_at >= DATE_SUB(NOW(), INTERVAL 1 WEEK)";
                break;
            case 'month':
                $date_condition = "AND created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                break;
            case 'year':
                $date_condition = "AND created_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                break;
        }
        
        $stats = $wpdb->get_row($wpdb->prepare("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) as published,
                SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed,
                AVG(seo_score) as avg_seo_score
            FROM $table_articles 
            WHERE 1=1 $date_condition
        "));
        
        return [
            'total' => (int) $stats->total,
            'published' => (int) $stats->published,
            'failed' => (int) $stats->failed,
            'success_rate' => $stats->total > 0 ? round(($stats->published / $stats->total) * 100) : 0,
            'avg_seo_score' => round($stats->avg_seo_score ?? 0)
        ];
    }
    
    /**
     * Forcer la génération d'un article
     */
    public function force_generation() {
        // Vérifier les permissions
        if (!current_user_can('manage_options')) {
            return new WP_Error('permission_denied', 'Permission refusée');
        }
        
        // Désactiver temporairement la planification
        $was_scheduled = wp_next_scheduled('acsp_generate_weekly_article');
        if ($was_scheduled) {
            wp_clear_scheduled_hook('acsp_generate_weekly_article');
        }
        
        // Générer l'article
        $generator = new ACSP_Content_Generator();
        $result = $generator->generate_and_publish_article();
        
        // Restaurer la planification si elle existait
        if ($was_scheduled) {
            $this->schedule_weekly_generation();
        }
        
        return $result;
    }
    
    /**
     * Mettre à jour la planification
     */
    public function update_schedule($day, $time, $frequency) {
        // Effacer l'ancienne planification
        wp_clear_scheduled_hook('acsp_generate_weekly_article');
        
        // Sauvegarder les nouvelles options
        update_option('acsp_publish_day', $day);
        update_option('acsp_publish_time', $time);
        update_option('acsp_frequency', $frequency);
        
        // Créer la nouvelle planification
        if ($frequency === 'weekly') {
            $this->schedule_weekly_generation();
        } elseif ($frequency === 'biweekly') {
            // Planifier toutes les deux semaines
            $days_map = [
                'monday' => 'Monday',
                'tuesday' => 'Tuesday',
                'wednesday' => 'Wednesday',
                'thursday' => 'Thursday',
                'friday' => 'Friday',
                'saturday' => 'Saturday',
                'sunday' => 'Sunday'
            ];
            
            $next_time = strtotime('next ' . $days_map[$day] . ' ' . $time);
            wp_schedule_event($next_time, 'acsp_biweekly', 'acsp_generate_weekly_article');
            
            // Ajouter l'intervalle personnalisé
            add_filter('cron_schedules', function($schedules) {
                $schedules['acsp_biweekly'] = [
                    'interval' => 14 * DAY_IN_SECONDS,
                    'display' => 'Toutes les deux semaines'
                ];
                return $schedules;
            });
        } elseif ($frequency === 'monthly') {
            // Planifier mensuellement
            $next_time = strtotime('first ' . $day . ' of next month ' . $time);
            wp_schedule_event($next_time, 'monthly', 'acsp_generate_weekly_article');
        }
        
        return true;
    }
}
