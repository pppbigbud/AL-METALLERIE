<?php
/**
 * Uninstall script for Smart Backlink Manager
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

class SBM_Uninstaller {
    
    public static function uninstall(): void {
        global $wpdb;
        
        // Supprimer les tables
        self::drop_tables($wpdb);
        
        // Supprimer les options
        self::delete_options();
        
        // Supprimer les tâches cron
        self::unschedule_cron_jobs();
        
        // Supprimer les transients
        self::delete_transients();
    }
    
    private static function drop_tables($wpdb): void {
        $tables = [
            $wpdb->prefix . 'sbm_backlinks',
            $wpdb->prefix . 'sbm_internal_links',
            $wpdb->prefix . 'sbm_opportunities',
            $wpdb->prefix . 'sbm_settings'
        ];
        
        foreach ($tables as $table) {
            $wpdb->query("DROP TABLE IF EXISTS $table");
        }
    }
    
    private static function delete_options(): void {
        $options = [
            'sbm_db_version',
            'sbm_site_name',
            'sbm_site_url',
            'sbm_site_niche',
            'sbm_suggestions_limit',
            'sbm_gutenberg_panel_enabled',
            'sbm_check_frequency',
            'sbm_custom_keywords'
        ];
        
        foreach ($options as $option) {
            delete_option($option);
        }
    }
    
    private static function unschedule_cron_jobs(): void {
        wp_clear_scheduled_hook('sbm_check_backlinks_cron');
    }
    
    private static function delete_transients(): void {
        delete_transient('sbm_activation_redirect');
    }
}

// Exécuter la désinstallation
SBM_Uninstaller::uninstall();
