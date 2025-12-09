<?php
/**
 * Classe Deactivator - Désactivation du plugin
 *
 * @package TrainingManager
 * @since 1.0.0
 */

namespace TrainingManager;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe Deactivator
 * 
 * Actions exécutées lors de la désactivation du plugin
 */
class Deactivator {

    /**
     * Désactiver le plugin
     */
    public static function deactivate(): void {
        // Flush les règles de réécriture
        flush_rewrite_rules();
        
        // Nettoyer les tâches cron
        self::clear_scheduled_events();
    }

    /**
     * Nettoyer les événements planifiés
     */
    private static function clear_scheduled_events(): void {
        $events = [
            'tm_daily_reminder_check',
            'tm_session_status_check',
        ];
        
        foreach ($events as $event) {
            $timestamp = wp_next_scheduled($event);
            if ($timestamp) {
                wp_unschedule_event($timestamp, $event);
            }
        }
    }
}
