<?php
if (!defined('ABSPATH')) {
    exit;
}

class SBM_Activator {
    
    public static function activate(): void {
        self::create_tables();
        self::set_default_options();
        self::schedule_cron_jobs();
        flush_rewrite_rules();
        set_transient('sbm_activation_redirect', true, 30);
    }
    
    private static function create_tables(): void {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        $prefix = $wpdb->prefix;
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        // Table des backlinks
        $sql_backlinks = "CREATE TABLE IF NOT EXISTS {$prefix}sbm_backlinks (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            source_url VARCHAR(500) NOT NULL,
            target_url VARCHAR(500) NOT NULL,
            anchor_text VARCHAR(255) DEFAULT NULL,
            backlink_type VARCHAR(100) DEFAULT 'autre',
            status ENUM('active', 'dead', 'redirect') DEFAULT 'active',
            http_code INT DEFAULT NULL,
            last_check DATETIME DEFAULT NULL,
            date_added DATETIME NOT NULL,
            PRIMARY KEY (id),
            KEY source_url (source_url(191)),
            KEY status (status),
            KEY date_added (date_added)
        ) $charset_collate;";
        
        // Table des liens internes
        $sql_internal_links = "CREATE TABLE IF NOT EXISTS {$prefix}sbm_internal_links (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            from_post_id BIGINT(20) UNSIGNED NOT NULL,
            to_post_id BIGINT(20) UNSIGNED NOT NULL,
            anchor_text VARCHAR(255) DEFAULT NULL,
            date_added DATETIME NOT NULL,
            PRIMARY KEY (id),
            KEY from_post_id (from_post_id),
            KEY to_post_id (to_post_id),
            KEY date_added (date_added)
        ) $charset_collate;";
        
        // Table des opportunités
        $sql_opportunities = "CREATE TABLE IF NOT EXISTS {$prefix}sbm_opportunities (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            site_name VARCHAR(255) NOT NULL,
            url VARCHAR(500) NOT NULL,
            type VARCHAR(100) DEFAULT 'autre',
            status ENUM('to_contact', 'in_progress', 'obtained', 'refused') DEFAULT 'to_contact',
            notes TEXT DEFAULT NULL,
            date_added DATETIME NOT NULL,
            date_updated DATETIME DEFAULT NULL,
            PRIMARY KEY (id),
            KEY status (status),
            KEY type (type)
        ) $charset_collate;";
        
        // Table des réglages
        $sql_settings = "CREATE TABLE IF NOT EXISTS {$prefix}sbm_settings (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            option_name VARCHAR(100) NOT NULL UNIQUE,
            option_value TEXT DEFAULT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY option_name (option_name)
        ) $charset_collate;";
        
        dbDelta($sql_backlinks);
        dbDelta($sql_internal_links);
        dbDelta($sql_opportunities);
        dbDelta($sql_settings);
        
        update_option('sbm_db_version', '1.0.0');
    }
    
    private static function set_default_options(): void {
        $defaults = [
            'sbm_site_name' => 'AL Métallerie',
            'sbm_site_url' => 'https://al-metallerie.fr',
            'sbm_site_niche' => 'Soudure, Serrurerie, Métallerie - Clermont-Ferrand, Puy-de-Dôme, Auvergne',
            'sbm_suggestions_limit' => 5,
            'sbm_gutenberg_panel_enabled' => 1,
            'sbm_check_frequency' => 'weekly',
            'sbm_custom_keywords' => json_encode([
                // Produits
                'portail', 'garde-corps', 'pergola', 'portillon', 'clôture', 'grille',
                'balcon', 'escalier', 'rampe', 'marquise', 'verrière', 'auvent',
                // Matériaux
                'acier', 'inox', 'aluminium', 'fer', 'ferronnerie', 'métal',
                'galvanisé', 'thermolaqué', 'corten',
                // Métiers
                'soudure', 'serrurerie', 'métallerie', 'serrurier', 'métallier',
                // Localisation
                'Clermont-Ferrand', 'Puy-de-Dôme', 'Auvergne', '63'
            ])
        ];
        
        foreach ($defaults as $key => $value) {
            if (get_option($key) === false) {
                add_option($key, $value);
            }
        }
        
        self::add_sample_opportunities();
    }
    
    private static function add_sample_opportunities(): void {
        global $wpdb;
        $table = $wpdb->prefix . 'sbm_opportunities';
        
        $samples = [
            [
                'site_name' => 'Annuaire Métallerie France',
                'url' => 'https://www.metallerie.net/annuaire',
                'type' => 'annuaire',
                'status' => 'to_contact',
                'notes' => 'Annuaire professionnel spécialisé métallerie'
            ],
            [
                'site_name' => 'Pages Jaunes Clermont-Ferrand',
                'url' => 'https://www.pagesjaunes.fr/pros/clermont-ferrand',
                'type' => 'annuaire',
                'status' => 'to_contact',
                'notes' => 'Annuaire local Clermont-Ferrand'
            ],
            [
                'site_name' => 'CCI Puy-de-Dôme',
                'url' => 'https://www.puy-de-dome.cci.fr',
                'type' => 'partenaire',
                'status' => 'to_contact',
                'notes' => 'Chambre de Commerce et d\'Industrie locale'
            ]
        ];
        
        foreach ($samples as $sample) {
            $wpdb->insert(
                $table,
                array_merge($sample, ['date_added' => current_time('mysql')]),
                ['%s', '%s', '%s', '%s', '%s', '%s']
            );
        }
    }
    
    private static function schedule_cron_jobs(): void {
        if (!wp_next_scheduled('sbm_check_backlinks_cron')) {
            wp_schedule_event(time(), 'weekly', 'sbm_check_backlinks_cron');
        }
    }
}
