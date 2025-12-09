<?php
/**
 * Classe Notifications - Système de notifications
 *
 * @package TrainingManager
 * @since 1.0.0
 */

namespace TrainingManager;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe Notifications
 * 
 * Gère l'envoi des notifications email
 */
class Notifications {

    /**
     * Envoyer une notification
     *
     * @param string $type       Type de notification
     * @param int    $session_id ID de la session
     * @param array  $data       Données supplémentaires
     */
    public function send_notification(string $type, int $session_id, array $data = []): void {
        // Vérifier si ce type de notification est activé
        if (!$this->is_notification_enabled($type)) {
            return;
        }

        $recipients = $this->get_recipients();
        $template = $this->get_template($type);
        $session = get_post($session_id);

        if (!$session || empty($recipients)) {
            return;
        }

        // Préparer les variables de remplacement
        $variables = $this->prepare_variables($session, $data);

        // Remplacer les variables dans le sujet et le contenu
        $subject = $this->replace_variables($template['subject'], $variables);
        $content = $this->replace_variables($template['content'], $variables);

        // Envoyer l'email
        $headers = [
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>',
        ];

        foreach ($recipients as $email) {
            $sent = wp_mail($email, $subject, $this->wrap_email_content($content), $headers);
            
            // Logger la notification
            $this->log_notification($session_id, $data['booking_id'] ?? null, $type, $email, $subject, $content, $sent ? 'sent' : 'failed');
        }
    }

    /**
     * Vérifier si un type de notification est activé
     *
     * @param string $type
     * @return bool
     */
    private function is_notification_enabled(string $type): bool {
        $option_map = [
            'new_request'          => 'tm_notification_new_request',
            'session_full'         => 'tm_notification_session_full',
            'session_almost_full'  => 'tm_notification_session_almost_full',
            'reminder'             => 'tm_notification_reminder_enabled',
        ];

        $option = $option_map[$type] ?? null;
        
        if (!$option) {
            return false;
        }

        return get_option($option) === 'yes';
    }

    /**
     * Obtenir les destinataires
     *
     * @return array
     */
    private function get_recipients(): array {
        $recipients = [];
        
        // Email admin principal
        $admin_email = get_option('tm_admin_email', get_option('admin_email'));
        if (!empty($admin_email)) {
            $recipients[] = $admin_email;
        }

        // Emails supplémentaires
        $additional = get_option('tm_additional_emails', '');
        if (!empty($additional)) {
            $emails = array_map('trim', explode(',', $additional));
            $recipients = array_merge($recipients, array_filter($emails, 'is_email'));
        }

        return array_unique($recipients);
    }

    /**
     * Obtenir le template d'email
     *
     * @param string $type
     * @return array
     */
    private function get_template(string $type): array {
        $option_map = [
            'new_request'         => 'tm_email_new_request',
            'session_full'        => 'tm_email_session_full',
            'reminder'            => 'tm_email_reminder',
        ];

        $option = $option_map[$type] ?? null;
        
        if (!$option) {
            return [
                'subject' => __('Notification Formation', 'training-manager'),
                'content' => '',
            ];
        }

        return get_option($option, [
            'subject' => '',
            'content' => '',
        ]);
    }

    /**
     * Préparer les variables de remplacement
     *
     * @param \WP_Post $session
     * @param array    $data
     * @return array
     */
    private function prepare_variables(\WP_Post $session, array $data): array {
        $start_date = get_post_meta($session->ID, '_tm_start_date', true);
        $end_date = get_post_meta($session->ID, '_tm_end_date', true);
        $location = get_post_meta($session->ID, '_tm_location', true);
        $total_places = get_post_meta($session->ID, '_tm_total_places', true);
        $reserved_places = get_post_meta($session->ID, '_tm_reserved_places', true);

        $date_format = get_option('tm_date_format', 'd/m/Y');
        $session_date = $start_date ? date_i18n($date_format, strtotime($start_date)) : '';
        if ($end_date && $end_date !== $start_date) {
            $session_date .= ' - ' . date_i18n($date_format, strtotime($end_date));
        }

        return [
            '{site_name}'       => get_bloginfo('name'),
            '{site_url}'        => home_url(),
            '{session_title}'   => $session->post_title,
            '{session_date}'    => $session_date,
            '{session_url}'     => get_permalink($session->ID),
            '{location}'        => $location,
            '{total_places}'    => $total_places,
            '{reserved_places}' => $reserved_places,
            '{remaining_places}'=> $total_places - $reserved_places,
            '{first_name}'      => $data['first_name'] ?? '',
            '{last_name}'       => $data['last_name'] ?? '',
            '{email}'           => $data['email'] ?? '',
            '{phone}'           => $data['phone'] ?? '',
            '{company}'         => $data['company'] ?? '',
            '{message}'         => $data['message'] ?? '',
            '{days}'            => $data['days'] ?? '',
            '{admin_url}'       => admin_url('edit.php?post_type=training_session'),
        ];
    }

    /**
     * Remplacer les variables dans le texte
     *
     * @param string $text
     * @param array  $variables
     * @return string
     */
    private function replace_variables(string $text, array $variables): string {
        return str_replace(array_keys($variables), array_values($variables), $text);
    }

    /**
     * Envelopper le contenu dans un template HTML
     *
     * @param string $content
     * @return string
     */
    private function wrap_email_content(string $content): string {
        $primary_color = get_option('tm_primary_color', '#F08B18');
        
        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f5f5f5;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f5f5; padding: 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <tr>
                        <td style="background-color: ' . esc_attr($primary_color) . '; padding: 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px;">' . esc_html(get_bloginfo('name')) . '</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 40px 30px;">
                            ' . nl2br($content) . '
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #222222; padding: 20px; text-align: center; color: #888888; font-size: 12px;">
                            <p style="margin: 0;">&copy; ' . date('Y') . ' ' . esc_html(get_bloginfo('name')) . '</p>
                            <p style="margin: 5px 0 0 0;"><a href="' . esc_url(home_url()) . '" style="color: ' . esc_attr($primary_color) . ';">' . esc_html(home_url()) . '</a></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>';
    }

    /**
     * Logger une notification
     *
     * @param int         $session_id
     * @param int|null    $booking_id
     * @param string      $type
     * @param string      $email
     * @param string      $subject
     * @param string      $content
     * @param string      $status
     */
    private function log_notification(int $session_id, ?int $booking_id, string $type, string $email, string $subject, string $content, string $status): void {
        global $wpdb;
        
        $table = $wpdb->prefix . 'tm_notifications_log';
        
        $wpdb->insert($table, [
            'session_id'        => $session_id,
            'booking_id'        => $booking_id,
            'notification_type' => $type,
            'recipient_email'   => $email,
            'subject'           => $subject,
            'content'           => $content,
            'status'            => $status,
        ], ['%d', '%d', '%s', '%s', '%s', '%s', '%s']);
    }

    /**
     * Vérifier les rappels quotidiens (appelé par cron)
     */
    public function check_daily_reminders(): void {
        if (!$this->is_notification_enabled('reminder')) {
            return;
        }

        $days = get_option('tm_notification_reminder_days', 7);
        $target_date = date('Y-m-d', strtotime("+{$days} days"));

        $sessions = get_posts([
            'post_type'      => 'training_session',
            'posts_per_page' => -1,
            'meta_query'     => [
                [
                    'key'     => '_tm_start_date',
                    'value'   => $target_date,
                    'compare' => '=',
                ],
                [
                    'key'     => '_tm_status',
                    'value'   => ['open', 'full', 'waitlist'],
                    'compare' => 'IN',
                ],
            ],
        ]);

        foreach ($sessions as $session) {
            $this->send_notification('reminder', $session->ID, ['days' => $days]);
        }
    }
}
