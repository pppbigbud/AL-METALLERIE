<?php
/**
 * Classe Metaboxes - Champs personnalisés
 *
 * @package TrainingManager
 * @since 1.0.0
 */

namespace TrainingManager;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe Metaboxes
 * 
 * Gère les metaboxes et champs personnalisés des sessions
 */
class Metaboxes {

    /**
     * Préfixe des meta fields
     */
    const META_PREFIX = '_tm_';

    /**
     * Ajouter les metaboxes
     */
    public function add_meta_boxes(): void {
        add_meta_box(
            'tm_session_dates',
            __('Dates et Horaires', 'training-manager'),
            [$this, 'render_dates_metabox'],
            'training_session',
            'normal',
            'high'
        );

        add_meta_box(
            'tm_session_capacity',
            __('Capacité et Places', 'training-manager'),
            [$this, 'render_capacity_metabox'],
            'training_session',
            'normal',
            'high'
        );

        add_meta_box(
            'tm_session_details',
            __('Détails de la Formation', 'training-manager'),
            [$this, 'render_details_metabox'],
            'training_session',
            'normal',
            'default'
        );

        add_meta_box(
            'tm_session_status',
            __('Statut de la Session', 'training-manager'),
            [$this, 'render_status_metabox'],
            'training_session',
            'side',
            'high'
        );

        add_meta_box(
            'tm_session_stats',
            __('Statistiques', 'training-manager'),
            [$this, 'render_stats_metabox'],
            'training_session',
            'side',
            'default'
        );
    }

    /**
     * Rendu de la metabox Dates et Horaires
     *
     * @param \WP_Post $post
     */
    public function render_dates_metabox(\WP_Post $post): void {
        wp_nonce_field('tm_save_session', 'tm_session_nonce');

        $start_date = get_post_meta($post->ID, self::META_PREFIX . 'start_date', true);
        $end_date = get_post_meta($post->ID, self::META_PREFIX . 'end_date', true);
        $start_time = get_post_meta($post->ID, self::META_PREFIX . 'start_time', true) ?: '09:00';
        $end_time = get_post_meta($post->ID, self::META_PREFIX . 'end_time', true) ?: '17:00';
        $time_slots = get_post_meta($post->ID, self::META_PREFIX . 'time_slots', true) ?: [];
        ?>
        <div class="tm-metabox-content">
            <div class="tm-field-row">
                <div class="tm-field-group">
                    <label for="tm_start_date"><?php _e('Date de début', 'training-manager'); ?> <span class="required">*</span></label>
                    <input type="date" id="tm_start_date" name="tm_start_date" value="<?php echo esc_attr($start_date); ?>" required>
                </div>
                <div class="tm-field-group">
                    <label for="tm_end_date"><?php _e('Date de fin', 'training-manager'); ?></label>
                    <input type="date" id="tm_end_date" name="tm_end_date" value="<?php echo esc_attr($end_date); ?>">
                    <p class="description"><?php _e('Laisser vide si formation sur une seule journée', 'training-manager'); ?></p>
                </div>
            </div>

            <div class="tm-field-row">
                <div class="tm-field-group">
                    <label for="tm_start_time"><?php _e('Heure de début', 'training-manager'); ?></label>
                    <input type="time" id="tm_start_time" name="tm_start_time" value="<?php echo esc_attr($start_time); ?>">
                </div>
                <div class="tm-field-group">
                    <label for="tm_end_time"><?php _e('Heure de fin', 'training-manager'); ?></label>
                    <input type="time" id="tm_end_time" name="tm_end_time" value="<?php echo esc_attr($end_time); ?>">
                </div>
            </div>

            <div class="tm-field-group tm-time-slots">
                <label><?php _e('Créneaux horaires supplémentaires', 'training-manager'); ?></label>
                <div id="tm-time-slots-container">
                    <?php if (!empty($time_slots)) : ?>
                        <?php foreach ($time_slots as $index => $slot) : ?>
                            <div class="tm-time-slot-row">
                                <input type="time" name="tm_time_slots[<?php echo $index; ?>][start]" value="<?php echo esc_attr($slot['start']); ?>">
                                <span>-</span>
                                <input type="time" name="tm_time_slots[<?php echo $index; ?>][end]" value="<?php echo esc_attr($slot['end']); ?>">
                                <button type="button" class="button tm-remove-slot">&times;</button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <button type="button" class="button tm-add-slot" id="tm-add-time-slot">
                    <?php _e('+ Ajouter un créneau', 'training-manager'); ?>
                </button>
            </div>
        </div>
        <?php
    }

    /**
     * Rendu de la metabox Capacité
     *
     * @param \WP_Post $post
     */
    public function render_capacity_metabox(\WP_Post $post): void {
        $total_places = get_post_meta($post->ID, self::META_PREFIX . 'total_places', true) ?: 8;
        $min_places = get_post_meta($post->ID, self::META_PREFIX . 'min_places', true);
        $reserved_places = get_post_meta($post->ID, self::META_PREFIX . 'reserved_places', true) ?: 0;
        $remaining_places = $total_places - $reserved_places;
        ?>
        <div class="tm-metabox-content">
            <div class="tm-field-row">
                <div class="tm-field-group">
                    <label for="tm_total_places"><?php _e('Nombre de places total', 'training-manager'); ?> <span class="required">*</span></label>
                    <input type="number" id="tm_total_places" name="tm_total_places" value="<?php echo esc_attr($total_places); ?>" min="1" max="100" required>
                </div>
                <div class="tm-field-group">
                    <label for="tm_min_places"><?php _e('Nombre minimum de participants', 'training-manager'); ?></label>
                    <input type="number" id="tm_min_places" name="tm_min_places" value="<?php echo esc_attr($min_places); ?>" min="0" max="100">
                    <p class="description"><?php _e('Optionnel - pour maintenir la session', 'training-manager'); ?></p>
                </div>
            </div>

            <div class="tm-capacity-display">
                <div class="tm-capacity-stat">
                    <span class="tm-stat-label"><?php _e('Places réservées', 'training-manager'); ?></span>
                    <span class="tm-stat-value tm-reserved"><?php echo esc_html($reserved_places); ?></span>
                </div>
                <div class="tm-capacity-stat">
                    <span class="tm-stat-label"><?php _e('Places restantes', 'training-manager'); ?></span>
                    <span class="tm-stat-value tm-remaining <?php echo $remaining_places <= 2 ? 'tm-warning' : ''; ?>"><?php echo esc_html($remaining_places); ?></span>
                </div>
                <div class="tm-capacity-bar">
                    <div class="tm-capacity-fill" style="width: <?php echo ($total_places > 0) ? ($reserved_places / $total_places * 100) : 0; ?>%"></div>
                </div>
            </div>

            <input type="hidden" name="tm_reserved_places" value="<?php echo esc_attr($reserved_places); ?>">
        </div>
        <?php
    }

    /**
     * Rendu de la metabox Détails
     *
     * @param \WP_Post $post
     */
    public function render_details_metabox(\WP_Post $post): void {
        $trainer = get_post_meta($post->ID, self::META_PREFIX . 'trainer', true);
        $location = get_post_meta($post->ID, self::META_PREFIX . 'location', true);
        $address = get_post_meta($post->ID, self::META_PREFIX . 'address', true);
        $price = get_post_meta($post->ID, self::META_PREFIX . 'price', true);
        $price_info = get_post_meta($post->ID, self::META_PREFIX . 'price_info', true);
        $prerequisites = get_post_meta($post->ID, self::META_PREFIX . 'prerequisites', true);
        $materials = get_post_meta($post->ID, self::META_PREFIX . 'materials', true);
        $documents = get_post_meta($post->ID, self::META_PREFIX . 'documents', true) ?: [];
        ?>
        <div class="tm-metabox-content">
            <div class="tm-field-row">
                <div class="tm-field-group">
                    <label for="tm_trainer"><?php _e('Formateur', 'training-manager'); ?></label>
                    <input type="text" id="tm_trainer" name="tm_trainer" value="<?php echo esc_attr($trainer); ?>" class="regular-text">
                </div>
                <div class="tm-field-group">
                    <label for="tm_price"><?php _e('Tarif (€)', 'training-manager'); ?></label>
                    <input type="number" id="tm_price" name="tm_price" value="<?php echo esc_attr($price); ?>" min="0" step="0.01">
                </div>
            </div>

            <div class="tm-field-group">
                <label for="tm_price_info"><?php _e('Informations tarifaires', 'training-manager'); ?></label>
                <input type="text" id="tm_price_info" name="tm_price_info" value="<?php echo esc_attr($price_info); ?>" class="large-text" placeholder="<?php _e('Ex: Tarif par personne, matériel inclus...', 'training-manager'); ?>">
            </div>

            <div class="tm-field-group">
                <label for="tm_location"><?php _e('Lieu de formation', 'training-manager'); ?></label>
                <input type="text" id="tm_location" name="tm_location" value="<?php echo esc_attr($location); ?>" class="large-text" placeholder="<?php _e('Ex: Atelier AL Métallerie', 'training-manager'); ?>">
            </div>

            <div class="tm-field-group">
                <label for="tm_address"><?php _e('Adresse complète', 'training-manager'); ?></label>
                <textarea id="tm_address" name="tm_address" rows="2" class="large-text"><?php echo esc_textarea($address); ?></textarea>
            </div>

            <div class="tm-field-group">
                <label for="tm_prerequisites"><?php _e('Prérequis', 'training-manager'); ?></label>
                <textarea id="tm_prerequisites" name="tm_prerequisites" rows="3" class="large-text" placeholder="<?php _e('Aucun prérequis nécessaire...', 'training-manager'); ?>"><?php echo esc_textarea($prerequisites); ?></textarea>
            </div>

            <div class="tm-field-group">
                <label for="tm_materials"><?php _e('Matériel nécessaire', 'training-manager'); ?></label>
                <textarea id="tm_materials" name="tm_materials" rows="3" class="large-text" placeholder="<?php _e('Vêtements de travail, chaussures de sécurité...', 'training-manager'); ?>"><?php echo esc_textarea($materials); ?></textarea>
            </div>

            <div class="tm-field-group">
                <label><?php _e('Documents joints', 'training-manager'); ?></label>
                <div id="tm-documents-container">
                    <?php foreach ($documents as $doc) : ?>
                        <div class="tm-document-item">
                            <span class="tm-doc-name"><?php echo esc_html(basename($doc)); ?></span>
                            <input type="hidden" name="tm_documents[]" value="<?php echo esc_url($doc); ?>">
                            <button type="button" class="button tm-remove-doc">&times;</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button tm-add-document" id="tm-add-document">
                    <?php _e('+ Ajouter un document', 'training-manager'); ?>
                </button>
            </div>
        </div>
        <?php
    }

    /**
     * Rendu de la metabox Statut
     *
     * @param \WP_Post $post
     */
    public function render_status_metabox(\WP_Post $post): void {
        $status = get_post_meta($post->ID, self::META_PREFIX . 'status', true) ?: 'open';
        $statuses = [
            'open'         => __('Ouvert aux inscriptions', 'training-manager'),
            'full'         => __('Complet', 'training-manager'),
            'waitlist'     => __('Liste d\'attente', 'training-manager'),
            'cancelled'    => __('Annulé', 'training-manager'),
            'completed'    => __('Terminé', 'training-manager'),
        ];
        ?>
        <div class="tm-metabox-content">
            <div class="tm-field-group">
                <label for="tm_status"><?php _e('Statut de la session', 'training-manager'); ?></label>
                <select id="tm_status" name="tm_status" class="tm-status-select">
                    <?php foreach ($statuses as $value => $label) : ?>
                        <option value="<?php echo esc_attr($value); ?>" <?php selected($status, $value); ?>>
                            <?php echo esc_html($label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="tm-status-indicator tm-status-<?php echo esc_attr($status); ?>">
                <span class="tm-status-dot"></span>
                <span class="tm-status-text"><?php echo esc_html($statuses[$status] ?? $status); ?></span>
            </div>
        </div>
        <?php
    }

    /**
     * Rendu de la metabox Statistiques
     *
     * @param \WP_Post $post
     */
    public function render_stats_metabox(\WP_Post $post): void {
        global $wpdb;
        
        $table_bookings = $wpdb->prefix . 'tm_bookings';
        $total_requests = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_bookings WHERE session_id = %d",
            $post->ID
        ));
        
        $pending_requests = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_bookings WHERE session_id = %d AND status = 'pending'",
            $post->ID
        ));
        ?>
        <div class="tm-metabox-content">
            <div class="tm-stats-grid">
                <div class="tm-stat-item">
                    <span class="tm-stat-number"><?php echo esc_html($total_requests ?: 0); ?></span>
                    <span class="tm-stat-label"><?php _e('Demandes totales', 'training-manager'); ?></span>
                </div>
                <div class="tm-stat-item">
                    <span class="tm-stat-number"><?php echo esc_html($pending_requests ?: 0); ?></span>
                    <span class="tm-stat-label"><?php _e('En attente', 'training-manager'); ?></span>
                </div>
            </div>
            <?php if ($total_requests > 0) : ?>
                <a href="<?php echo admin_url('admin.php?page=tm-bookings&session_id=' . $post->ID); ?>" class="button button-secondary">
                    <?php _e('Voir les demandes', 'training-manager'); ?>
                </a>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Sauvegarder les metaboxes
     *
     * @param int      $post_id
     * @param \WP_Post $post
     */
    public function save_meta_boxes(int $post_id, \WP_Post $post): void {
        // Vérifications de sécurité
        if (!isset($_POST['tm_session_nonce']) || !wp_verify_nonce($_POST['tm_session_nonce'], 'tm_save_session')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Champs à sauvegarder
        $fields = [
            'start_date'      => 'sanitize_text_field',
            'end_date'        => 'sanitize_text_field',
            'start_time'      => 'sanitize_text_field',
            'end_time'        => 'sanitize_text_field',
            'total_places'    => 'absint',
            'min_places'      => 'absint',
            'reserved_places' => 'absint',
            'trainer'         => 'sanitize_text_field',
            'location'        => 'sanitize_text_field',
            'address'         => 'sanitize_textarea_field',
            'price'           => 'floatval',
            'price_info'      => 'sanitize_text_field',
            'prerequisites'   => 'sanitize_textarea_field',
            'materials'       => 'sanitize_textarea_field',
            'status'          => 'sanitize_text_field',
        ];

        foreach ($fields as $field => $sanitize_callback) {
            $key = 'tm_' . $field;
            if (isset($_POST[$key])) {
                $value = call_user_func($sanitize_callback, $_POST[$key]);
                update_post_meta($post_id, self::META_PREFIX . $field, $value);
            }
        }

        // Créneaux horaires
        if (isset($_POST['tm_time_slots']) && is_array($_POST['tm_time_slots'])) {
            $time_slots = [];
            foreach ($_POST['tm_time_slots'] as $slot) {
                if (!empty($slot['start']) && !empty($slot['end'])) {
                    $time_slots[] = [
                        'start' => sanitize_text_field($slot['start']),
                        'end'   => sanitize_text_field($slot['end']),
                    ];
                }
            }
            update_post_meta($post_id, self::META_PREFIX . 'time_slots', $time_slots);
        }

        // Documents
        if (isset($_POST['tm_documents']) && is_array($_POST['tm_documents'])) {
            $documents = array_map('esc_url_raw', $_POST['tm_documents']);
            update_post_meta($post_id, self::META_PREFIX . 'documents', array_filter($documents));
        }

        // Vérifier si la session est complète
        $total_places = get_post_meta($post_id, self::META_PREFIX . 'total_places', true);
        $reserved_places = get_post_meta($post_id, self::META_PREFIX . 'reserved_places', true);
        
        if ($reserved_places >= $total_places) {
            update_post_meta($post_id, self::META_PREFIX . 'status', 'full');
            
            // Déclencher notification
            do_action('tm_send_notification', 'session_full', $post_id, []);
        }
    }
}
