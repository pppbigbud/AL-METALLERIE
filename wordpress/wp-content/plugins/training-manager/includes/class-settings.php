<?php
/**
 * Classe Settings - Page de paramètres
 *
 * @package TrainingManager
 * @since 1.0.0
 */

namespace TrainingManager;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe Settings
 * 
 * Gère les paramètres du plugin
 */
class Settings {

    /**
     * Enregistrer les paramètres
     */
    public function register_settings(): void {
        // Section Générale
        add_settings_section(
            'tm_general_section',
            __('Paramètres généraux', 'training-manager'),
            [$this, 'render_general_section'],
            'tm-settings'
        );

        // Section Notifications
        add_settings_section(
            'tm_notifications_section',
            __('Notifications', 'training-manager'),
            [$this, 'render_notifications_section'],
            'tm-settings'
        );

        // Section Affichage
        add_settings_section(
            'tm_display_section',
            __('Affichage', 'training-manager'),
            [$this, 'render_display_section'],
            'tm-settings'
        );

        // Champs Généraux
        $general_fields = [
            'tm_admin_email' => [
                'label' => __('Email administrateur', 'training-manager'),
                'type'  => 'email',
            ],
            'tm_additional_emails' => [
                'label'       => __('Emails supplémentaires', 'training-manager'),
                'type'        => 'text',
                'description' => __('Séparez les emails par des virgules', 'training-manager'),
            ],
            'tm_currency_symbol' => [
                'label'   => __('Symbole monétaire', 'training-manager'),
                'type'    => 'text',
                'default' => '€',
            ],
            'tm_date_format' => [
                'label'   => __('Format de date', 'training-manager'),
                'type'    => 'text',
                'default' => 'd/m/Y',
            ],
            'tm_time_format' => [
                'label'   => __('Format d\'heure', 'training-manager'),
                'type'    => 'text',
                'default' => 'H:i',
            ],
        ];

        foreach ($general_fields as $id => $field) {
            register_setting('tm-settings', $id);
            add_settings_field(
                $id,
                $field['label'],
                [$this, 'render_field'],
                'tm-settings',
                'tm_general_section',
                array_merge($field, ['id' => $id])
            );
        }

        // Champs Notifications
        $notification_fields = [
            'tm_notification_new_request' => [
                'label'   => __('Nouvelle demande d\'information', 'training-manager'),
                'type'    => 'checkbox',
                'default' => 'yes',
            ],
            'tm_notification_session_full' => [
                'label'   => __('Session complète', 'training-manager'),
                'type'    => 'checkbox',
                'default' => 'yes',
            ],
            'tm_notification_session_almost_full' => [
                'label'   => __('Session bientôt complète', 'training-manager'),
                'type'    => 'checkbox',
                'default' => 'yes',
            ],
            'tm_notification_almost_full_threshold' => [
                'label'       => __('Seuil "bientôt complet"', 'training-manager'),
                'type'        => 'number',
                'default'     => 2,
                'description' => __('Nombre de places restantes', 'training-manager'),
            ],
            'tm_notification_reminder_enabled' => [
                'label'   => __('Rappel avant formation', 'training-manager'),
                'type'    => 'checkbox',
                'default' => 'yes',
            ],
            'tm_notification_reminder_days' => [
                'label'       => __('Jours avant rappel', 'training-manager'),
                'type'        => 'number',
                'default'     => 7,
                'description' => __('Nombre de jours avant la formation', 'training-manager'),
            ],
        ];

        foreach ($notification_fields as $id => $field) {
            register_setting('tm-settings', $id);
            add_settings_field(
                $id,
                $field['label'],
                [$this, 'render_field'],
                'tm-settings',
                'tm_notifications_section',
                array_merge($field, ['id' => $id])
            );
        }

        // Champs Affichage
        $display_fields = [
            'tm_primary_color' => [
                'label'   => __('Couleur principale', 'training-manager'),
                'type'    => 'color',
                'default' => '#F08B18',
            ],
            'tm_secondary_color' => [
                'label'   => __('Couleur secondaire', 'training-manager'),
                'type'    => 'color',
                'default' => '#222222',
            ],
            'tm_calendar_first_day' => [
                'label'   => __('Premier jour de la semaine', 'training-manager'),
                'type'    => 'select',
                'options' => [
                    0 => __('Dimanche', 'training-manager'),
                    1 => __('Lundi', 'training-manager'),
                ],
                'default' => 1,
            ],
            'tm_default_session_duration' => [
                'label'       => __('Durée par défaut (heures)', 'training-manager'),
                'type'        => 'number',
                'default'     => 7,
                'description' => __('Durée par défaut d\'une session', 'training-manager'),
            ],
        ];

        foreach ($display_fields as $id => $field) {
            register_setting('tm-settings', $id);
            add_settings_field(
                $id,
                $field['label'],
                [$this, 'render_field'],
                'tm-settings',
                'tm_display_section',
                array_merge($field, ['id' => $id])
            );
        }
    }

    /**
     * Rendu de la section générale
     */
    public function render_general_section(): void {
        echo '<p>' . __('Configurez les paramètres généraux du plugin.', 'training-manager') . '</p>';
    }

    /**
     * Rendu de la section notifications
     */
    public function render_notifications_section(): void {
        echo '<p>' . __('Configurez les notifications email.', 'training-manager') . '</p>';
    }

    /**
     * Rendu de la section affichage
     */
    public function render_display_section(): void {
        echo '<p>' . __('Personnalisez l\'apparence du calendrier et des listes.', 'training-manager') . '</p>';
    }

    /**
     * Rendu d'un champ
     *
     * @param array $args
     */
    public function render_field(array $args): void {
        $id = $args['id'];
        $type = $args['type'];
        $default = $args['default'] ?? '';
        $description = $args['description'] ?? '';
        $options = $args['options'] ?? [];
        
        $value = get_option($id, $default);

        switch ($type) {
            case 'checkbox':
                ?>
                <label>
                    <input type="checkbox" name="<?php echo esc_attr($id); ?>" value="yes" <?php checked($value, 'yes'); ?>>
                    <?php _e('Activé', 'training-manager'); ?>
                </label>
                <?php
                break;

            case 'select':
                ?>
                <select name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>">
                    <?php foreach ($options as $key => $label) : ?>
                        <option value="<?php echo esc_attr($key); ?>" <?php selected($value, $key); ?>>
                            <?php echo esc_html($label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php
                break;

            case 'textarea':
                ?>
                <textarea name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" rows="5" class="large-text"><?php echo esc_textarea($value); ?></textarea>
                <?php
                break;

            case 'color':
                ?>
                <input type="color" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($value); ?>">
                <?php
                break;

            case 'number':
                ?>
                <input type="number" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($value); ?>" class="small-text">
                <?php
                break;

            default:
                ?>
                <input type="<?php echo esc_attr($type); ?>" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($value); ?>" class="regular-text">
                <?php
        }

        if (!empty($description)) {
            echo '<p class="description">' . esc_html($description) . '</p>';
        }
    }

    /**
     * Rendu de la page de paramètres
     */
    public function render_settings_page(): void {
        if (!current_user_can('manage_options')) {
            return;
        }

        // Sauvegarder les messages
        if (isset($_GET['settings-updated'])) {
            add_settings_error('tm_messages', 'tm_message', __('Paramètres enregistrés.', 'training-manager'), 'updated');
        }

        settings_errors('tm_messages');
        ?>
        <div class="wrap tm-settings-wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <div class="tm-settings-tabs">
                <nav class="nav-tab-wrapper">
                    <a href="#general" class="nav-tab nav-tab-active"><?php _e('Général', 'training-manager'); ?></a>
                    <a href="#notifications" class="nav-tab"><?php _e('Notifications', 'training-manager'); ?></a>
                    <a href="#emails" class="nav-tab"><?php _e('Templates Emails', 'training-manager'); ?></a>
                    <a href="#display" class="nav-tab"><?php _e('Affichage', 'training-manager'); ?></a>
                </nav>

                <form action="options.php" method="post">
                    <?php
                    settings_fields('tm-settings');
                    
                    echo '<div id="general" class="tm-tab-content">';
                    do_settings_sections('tm-settings');
                    echo '</div>';
                    
                    submit_button(__('Enregistrer les paramètres', 'training-manager'));
                    ?>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Obtenir une option avec valeur par défaut
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public static function get(string $key, $default = '') {
        return get_option($key, $default);
    }
}
