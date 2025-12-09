<?php
/**
 * Classe Activator - Activation du plugin
 *
 * @package TrainingManager
 * @since 1.0.0
 */

namespace TrainingManager;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe Activator
 * 
 * Actions exécutées lors de l'activation du plugin
 */
class Activator {

    /**
     * Activer le plugin
     */
    public static function activate(): void {
        // Créer les tables personnalisées
        self::create_tables();
        
        // Créer les options par défaut
        self::create_default_options();
        
        // Créer les rôles et capacités
        self::create_capabilities();
        
        // Flush les règles de réécriture
        flush_rewrite_rules();
        
        // Marquer la version installée
        update_option('tm_version', TM_VERSION);
        update_option('tm_installed', time());
    }

    /**
     * Créer les tables personnalisées pour les réservations
     */
    private static function create_tables(): void {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Table des réservations (préparation Phase 2)
        $table_bookings = $wpdb->prefix . 'tm_bookings';
        
        $sql_bookings = "CREATE TABLE IF NOT EXISTS $table_bookings (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            session_id bigint(20) unsigned NOT NULL,
            user_id bigint(20) unsigned DEFAULT NULL,
            first_name varchar(100) NOT NULL,
            last_name varchar(100) NOT NULL,
            email varchar(255) NOT NULL,
            phone varchar(50) DEFAULT NULL,
            company varchar(255) DEFAULT NULL,
            message text,
            status varchar(50) DEFAULT 'pending',
            booking_type varchar(50) DEFAULT 'contact_request',
            payment_status varchar(50) DEFAULT NULL,
            payment_amount decimal(10,2) DEFAULT NULL,
            payment_method varchar(50) DEFAULT NULL,
            payment_date datetime DEFAULT NULL,
            notes text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY session_id (session_id),
            KEY user_id (user_id),
            KEY email (email),
            KEY status (status),
            KEY booking_type (booking_type)
        ) $charset_collate;";
        
        // Table des notifications envoyées
        $table_notifications = $wpdb->prefix . 'tm_notifications_log';
        
        $sql_notifications = "CREATE TABLE IF NOT EXISTS $table_notifications (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            session_id bigint(20) unsigned DEFAULT NULL,
            booking_id bigint(20) unsigned DEFAULT NULL,
            notification_type varchar(100) NOT NULL,
            recipient_email varchar(255) NOT NULL,
            subject varchar(255) NOT NULL,
            content longtext,
            status varchar(50) DEFAULT 'sent',
            sent_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY session_id (session_id),
            KEY booking_id (booking_id),
            KEY notification_type (notification_type)
        ) $charset_collate;";
        
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql_bookings);
        dbDelta($sql_notifications);
    }

    /**
     * Créer les options par défaut
     */
    private static function create_default_options(): void {
        $default_options = [
            'tm_admin_email' => get_option('admin_email'),
            'tm_additional_emails' => '',
            'tm_notification_new_request' => 'yes',
            'tm_notification_session_full' => 'yes',
            'tm_notification_session_almost_full' => 'yes',
            'tm_notification_almost_full_threshold' => 2,
            'tm_notification_reminder_days' => 7,
            'tm_notification_reminder_enabled' => 'yes',
            'tm_calendar_first_day' => 1, // Lundi
            'tm_default_session_duration' => 7, // heures
            'tm_currency' => 'EUR',
            'tm_currency_symbol' => '€',
            'tm_date_format' => 'd/m/Y',
            'tm_time_format' => 'H:i',
            'tm_primary_color' => '#F08B18',
            'tm_secondary_color' => '#222222',
        ];
        
        foreach ($default_options as $key => $value) {
            if (get_option($key) === false) {
                add_option($key, $value);
            }
        }
        
        // Templates d'emails par défaut
        $email_templates = [
            'tm_email_new_request' => [
                'subject' => __('[{site_name}] Nouvelle demande d\'information - {session_title}', 'training-manager'),
                'content' => self::get_default_email_template('new_request'),
            ],
            'tm_email_session_full' => [
                'subject' => __('[{site_name}] Session complète - {session_title}', 'training-manager'),
                'content' => self::get_default_email_template('session_full'),
            ],
            'tm_email_reminder' => [
                'subject' => __('[{site_name}] Rappel - Formation dans {days} jours', 'training-manager'),
                'content' => self::get_default_email_template('reminder'),
            ],
        ];
        
        foreach ($email_templates as $key => $template) {
            if (get_option($key) === false) {
                add_option($key, $template);
            }
        }
    }

    /**
     * Obtenir le template d'email par défaut
     *
     * @param string $type Type de template
     * @return string
     */
    private static function get_default_email_template(string $type): string {
        $templates = [
            'new_request' => "Bonjour,\n\nUne nouvelle demande d'information a été reçue pour la formation :\n\n<strong>{session_title}</strong>\nDate : {session_date}\n\n<strong>Informations du demandeur :</strong>\nNom : {first_name} {last_name}\nEmail : {email}\nTéléphone : {phone}\n\nMessage :\n{message}\n\nCordialement,\n{site_name}",
            
            'session_full' => "Bonjour,\n\nLa session de formation suivante est maintenant complète :\n\n<strong>{session_title}</strong>\nDate : {session_date}\nPlaces : {total_places}\n\nCordialement,\n{site_name}",
            
            'reminder' => "Bonjour,\n\nRappel : la formation suivante aura lieu dans {days} jours :\n\n<strong>{session_title}</strong>\nDate : {session_date}\nLieu : {location}\n\nCordialement,\n{site_name}",
        ];
        
        return $templates[$type] ?? '';
    }

    /**
     * Créer les capacités personnalisées
     */
    private static function create_capabilities(): void {
        $admin = get_role('administrator');
        
        if ($admin) {
            $capabilities = [
                'edit_training_session',
                'read_training_session',
                'delete_training_session',
                'edit_training_sessions',
                'edit_others_training_sessions',
                'publish_training_sessions',
                'read_private_training_sessions',
                'delete_training_sessions',
                'delete_private_training_sessions',
                'delete_published_training_sessions',
                'delete_others_training_sessions',
                'edit_private_training_sessions',
                'edit_published_training_sessions',
                'manage_training_sessions',
                'view_training_reports',
                'export_training_data',
            ];
            
            foreach ($capabilities as $cap) {
                $admin->add_cap($cap);
            }
        }
    }
}
