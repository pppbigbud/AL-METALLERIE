<?php
/**
 * Classe Bookings - Gestion des réservations
 *
 * @package TrainingManager
 * @since 1.0.0
 */

namespace TrainingManager;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe Bookings
 * 
 * Gère les demandes d'information et réservations (Phase 2)
 */
class Bookings {

    /**
     * Table des réservations
     *
     * @var string
     */
    private $table;

    /**
     * Constructeur
     */
    public function __construct() {
        global $wpdb;
        $this->table = $wpdb->prefix . 'tm_bookings';
    }

    /**
     * Gérer une demande de contact (AJAX)
     */
    public function handle_contact_request(): void {
        check_ajax_referer('tm_contact_nonce', 'nonce');

        // Validation des champs
        $required = ['session_id', 'first_name', 'last_name', 'email'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                wp_send_json_error([
                    'message' => sprintf(__('Le champ %s est requis.', 'training-manager'), $field),
                ]);
            }
        }

        // Sanitization
        $data = [
            'session_id' => absint($_POST['session_id']),
            'first_name' => sanitize_text_field($_POST['first_name']),
            'last_name'  => sanitize_text_field($_POST['last_name']),
            'email'      => sanitize_email($_POST['email']),
            'phone'      => sanitize_text_field($_POST['phone'] ?? ''),
            'company'    => sanitize_text_field($_POST['company'] ?? ''),
            'message'    => sanitize_textarea_field($_POST['message'] ?? ''),
        ];

        // Validation email
        if (!is_email($data['email'])) {
            wp_send_json_error([
                'message' => __('Adresse email invalide.', 'training-manager'),
            ]);
        }

        // Vérifier que la session existe
        $session = get_post($data['session_id']);
        if (!$session || $session->post_type !== 'training_session') {
            wp_send_json_error([
                'message' => __('Session de formation invalide.', 'training-manager'),
            ]);
        }

        // Enregistrer la demande
        $booking_id = $this->create_booking($data);

        if (!$booking_id) {
            wp_send_json_error([
                'message' => __('Erreur lors de l\'enregistrement de votre demande.', 'training-manager'),
            ]);
        }

        // Envoyer la notification
        do_action('tm_send_notification', 'new_request', $data['session_id'], array_merge($data, ['booking_id' => $booking_id]));

        wp_send_json_success([
            'message'    => __('Votre demande a bien été envoyée. Nous vous contacterons rapidement.', 'training-manager'),
            'booking_id' => $booking_id,
        ]);
    }

    /**
     * Créer une réservation/demande
     *
     * @param array $data
     * @return int|false
     */
    public function create_booking(array $data) {
        global $wpdb;

        $result = $wpdb->insert(
            $this->table,
            [
                'session_id'   => $data['session_id'],
                'user_id'      => get_current_user_id() ?: null,
                'first_name'   => $data['first_name'],
                'last_name'    => $data['last_name'],
                'email'        => $data['email'],
                'phone'        => $data['phone'] ?? '',
                'company'      => $data['company'] ?? '',
                'message'      => $data['message'] ?? '',
                'status'       => 'pending',
                'booking_type' => 'contact_request',
            ],
            ['%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s']
        );

        return $result ? $wpdb->insert_id : false;
    }

    /**
     * Obtenir les réservations d'une session
     *
     * @param int    $session_id
     * @param string $status
     * @return array
     */
    public function get_session_bookings(int $session_id, string $status = ''): array {
        global $wpdb;

        $sql = "SELECT * FROM {$this->table} WHERE session_id = %d";
        $params = [$session_id];

        if (!empty($status)) {
            $sql .= " AND status = %s";
            $params[] = $status;
        }

        $sql .= " ORDER BY created_at DESC";

        return $wpdb->get_results($wpdb->prepare($sql, $params));
    }

    /**
     * Obtenir une réservation par ID
     *
     * @param int $booking_id
     * @return object|null
     */
    public function get_booking(int $booking_id): ?object {
        global $wpdb;

        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->table} WHERE id = %d",
            $booking_id
        ));
    }

    /**
     * Mettre à jour le statut d'une réservation
     *
     * @param int    $booking_id
     * @param string $status
     * @return bool
     */
    public function update_status(int $booking_id, string $status): bool {
        global $wpdb;

        $valid_statuses = ['pending', 'confirmed', 'cancelled', 'completed'];
        if (!in_array($status, $valid_statuses)) {
            return false;
        }

        $result = $wpdb->update(
            $this->table,
            ['status' => $status],
            ['id' => $booking_id],
            ['%s'],
            ['%d']
        );

        return $result !== false;
    }

    /**
     * Supprimer une réservation
     *
     * @param int $booking_id
     * @return bool
     */
    public function delete_booking(int $booking_id): bool {
        global $wpdb;

        return $wpdb->delete($this->table, ['id' => $booking_id], ['%d']) !== false;
    }

    /**
     * Compter les réservations
     *
     * @param array $args
     * @return int
     */
    public function count_bookings(array $args = []): int {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($args['session_id'])) {
            $sql .= " AND session_id = %d";
            $params[] = $args['session_id'];
        }

        if (!empty($args['status'])) {
            $sql .= " AND status = %s";
            $params[] = $args['status'];
        }

        if (!empty($args['booking_type'])) {
            $sql .= " AND booking_type = %s";
            $params[] = $args['booking_type'];
        }

        if (!empty($args['date_from'])) {
            $sql .= " AND created_at >= %s";
            $params[] = $args['date_from'];
        }

        if (!empty($args['date_to'])) {
            $sql .= " AND created_at <= %s";
            $params[] = $args['date_to'];
        }

        if (!empty($params)) {
            return (int) $wpdb->get_var($wpdb->prepare($sql, $params));
        }

        return (int) $wpdb->get_var($sql);
    }

    /**
     * Obtenir les réservations avec pagination
     *
     * @param array $args
     * @return array
     */
    public function get_bookings(array $args = []): array {
        global $wpdb;

        $defaults = [
            'session_id'   => 0,
            'status'       => '',
            'booking_type' => '',
            'orderby'      => 'created_at',
            'order'        => 'DESC',
            'per_page'     => 20,
            'page'         => 1,
        ];

        $args = wp_parse_args($args, $defaults);

        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($args['session_id'])) {
            $sql .= " AND session_id = %d";
            $params[] = $args['session_id'];
        }

        if (!empty($args['status'])) {
            $sql .= " AND status = %s";
            $params[] = $args['status'];
        }

        if (!empty($args['booking_type'])) {
            $sql .= " AND booking_type = %s";
            $params[] = $args['booking_type'];
        }

        // Order
        $allowed_orderby = ['created_at', 'first_name', 'last_name', 'email', 'status'];
        $orderby = in_array($args['orderby'], $allowed_orderby) ? $args['orderby'] : 'created_at';
        $order = strtoupper($args['order']) === 'ASC' ? 'ASC' : 'DESC';
        $sql .= " ORDER BY {$orderby} {$order}";

        // Pagination
        $offset = ($args['page'] - 1) * $args['per_page'];
        $sql .= " LIMIT %d OFFSET %d";
        $params[] = $args['per_page'];
        $params[] = $offset;

        if (!empty($params)) {
            return $wpdb->get_results($wpdb->prepare($sql, $params));
        }

        return $wpdb->get_results($sql);
    }

    /**
     * Exporter les réservations en CSV
     *
     * @param int $session_id
     * @return string
     */
    public function export_csv(int $session_id = 0): string {
        $bookings = $this->get_bookings([
            'session_id' => $session_id,
            'per_page'   => -1,
        ]);

        $csv = [];
        $csv[] = [
            __('ID', 'training-manager'),
            __('Session', 'training-manager'),
            __('Prénom', 'training-manager'),
            __('Nom', 'training-manager'),
            __('Email', 'training-manager'),
            __('Téléphone', 'training-manager'),
            __('Entreprise', 'training-manager'),
            __('Message', 'training-manager'),
            __('Statut', 'training-manager'),
            __('Type', 'training-manager'),
            __('Date', 'training-manager'),
        ];

        foreach ($bookings as $booking) {
            $session = get_post($booking->session_id);
            $csv[] = [
                $booking->id,
                $session ? $session->post_title : '',
                $booking->first_name,
                $booking->last_name,
                $booking->email,
                $booking->phone,
                $booking->company,
                $booking->message,
                $booking->status,
                $booking->booking_type,
                $booking->created_at,
            ];
        }

        $output = '';
        foreach ($csv as $row) {
            $output .= '"' . implode('","', array_map('esc_html', $row)) . '"' . "\n";
        }

        return $output;
    }
}
