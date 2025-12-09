<?php
/**
 * Classe Calendar - Gestion du calendrier
 *
 * @package TrainingManager
 * @since 1.0.0
 */

namespace TrainingManager;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe Calendar
 * 
 * Gère l'affichage et les données du calendrier
 */
class Calendar {

    /**
     * Obtenir les événements pour le calendrier (AJAX)
     */
    public function get_events(): void {
        check_ajax_referer('tm_calendar_nonce', 'nonce');

        $start = isset($_POST['start']) ? sanitize_text_field($_POST['start']) : '';
        $end = isset($_POST['end']) ? sanitize_text_field($_POST['end']) : '';
        $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';

        $events = $this->fetch_events($start, $end, $type);

        wp_send_json_success($events);
    }

    /**
     * Récupérer les événements
     *
     * @param string $start Date de début
     * @param string $end   Date de fin
     * @param string $type  Type de formation
     * @return array
     */
    public function fetch_events(string $start = '', string $end = '', string $type = ''): array {
        $args = [
            'post_type'      => 'training_session',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'meta_query'     => [
                'relation' => 'AND',
            ],
        ];

        // Filtrer par dates
        if (!empty($start)) {
            $args['meta_query'][] = [
                'key'     => '_tm_start_date',
                'value'   => $start,
                'compare' => '>=',
                'type'    => 'DATE',
            ];
        }

        if (!empty($end)) {
            $args['meta_query'][] = [
                'key'     => '_tm_start_date',
                'value'   => $end,
                'compare' => '<=',
                'type'    => 'DATE',
            ];
        }

        // Filtrer par type
        if (!empty($type)) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'training_type',
                    'field'    => 'slug',
                    'terms'    => $type,
                ],
            ];
        }

        $sessions = get_posts($args);
        $events = [];

        foreach ($sessions as $session) {
            $events[] = $this->format_event($session);
        }

        return $events;
    }

    /**
     * Formater un événement pour le calendrier
     *
     * @param \WP_Post $session
     * @return array
     */
    private function format_event(\WP_Post $session): array {
        $start_date = get_post_meta($session->ID, '_tm_start_date', true);
        $end_date = get_post_meta($session->ID, '_tm_end_date', true) ?: $start_date;
        $start_time = get_post_meta($session->ID, '_tm_start_time', true) ?: '09:00';
        $end_time = get_post_meta($session->ID, '_tm_end_time', true) ?: '17:00';
        $status = get_post_meta($session->ID, '_tm_status', true) ?: 'open';
        $total_places = get_post_meta($session->ID, '_tm_total_places', true) ?: 0;
        $reserved_places = get_post_meta($session->ID, '_tm_reserved_places', true) ?: 0;
        $remaining = $total_places - $reserved_places;

        // Couleur selon le type
        $types = get_the_terms($session->ID, 'training_type');
        $type_slug = (!empty($types) && !is_wp_error($types)) ? $types[0]->slug : '';
        
        $colors = [
            'particuliers'   => '#F08B18', // Orange
            'professionnels' => '#2563eb', // Bleu
        ];
        $color = $colors[$type_slug] ?? '#6b7280';

        // Modifier la couleur selon le statut
        if ($status === 'full') {
            $color = '#dc2626'; // Rouge
        } elseif ($status === 'cancelled') {
            $color = '#9ca3af'; // Gris
        } elseif ($remaining <= 2 && $remaining > 0) {
            $color = '#f59e0b'; // Orange foncé
        }

        // Thème
        $themes = get_the_terms($session->ID, 'training_theme');
        $theme_name = (!empty($themes) && !is_wp_error($themes)) ? $themes[0]->name : '';

        return [
            'id'              => $session->ID,
            'title'           => $session->post_title,
            'start'           => $start_date . 'T' . $start_time,
            'end'             => $end_date . 'T' . $end_time,
            'url'             => get_permalink($session->ID),
            'backgroundColor' => $color,
            'borderColor'     => $color,
            'textColor'       => '#ffffff',
            'extendedProps'   => [
                'status'          => $status,
                'type'            => $type_slug,
                'theme'           => $theme_name,
                'total_places'    => $total_places,
                'reserved_places' => $reserved_places,
                'remaining_places'=> $remaining,
                'location'        => get_post_meta($session->ID, '_tm_location', true),
                'price'           => get_post_meta($session->ID, '_tm_price', true),
                'trainer'         => get_post_meta($session->ID, '_tm_trainer', true),
            ],
        ];
    }

    /**
     * Générer le HTML du calendrier
     *
     * @param array $atts Attributs du shortcode
     * @return string
     */
    public function render(array $atts = []): string {
        $atts = shortcode_atts([
            'type'        => '',
            'show_legend' => 'yes',
            'view'        => 'dayGridMonth',
        ], $atts);

        // Enqueue les assets
        wp_enqueue_style('tm-calendar');
        wp_enqueue_script('tm-calendar');

        $calendar_id = 'tm-calendar-' . uniqid();

        ob_start();
        ?>
        <div class="tm-calendar-wrapper" data-type="<?php echo esc_attr($atts['type']); ?>" data-view="<?php echo esc_attr($atts['view']); ?>">
            <?php if ($atts['show_legend'] === 'yes') : ?>
                <div class="tm-calendar-legend">
                    <div class="tm-legend-item">
                        <span class="tm-legend-color" style="background-color: #F08B18;"></span>
                        <span class="tm-legend-label"><?php _e('Particuliers', 'training-manager'); ?></span>
                    </div>
                    <div class="tm-legend-item">
                        <span class="tm-legend-color" style="background-color: #2563eb;"></span>
                        <span class="tm-legend-label"><?php _e('Professionnels', 'training-manager'); ?></span>
                    </div>
                    <div class="tm-legend-item">
                        <span class="tm-legend-color" style="background-color: #dc2626;"></span>
                        <span class="tm-legend-label"><?php _e('Complet', 'training-manager'); ?></span>
                    </div>
                    <div class="tm-legend-item">
                        <span class="tm-legend-color" style="background-color: #f59e0b;"></span>
                        <span class="tm-legend-label"><?php _e('Dernières places', 'training-manager'); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <div id="<?php echo esc_attr($calendar_id); ?>" class="tm-calendar"></div>
        </div>

        <!-- Modal détails session -->
        <div id="tm-session-modal" class="tm-modal" style="display: none;">
            <div class="tm-modal-overlay"></div>
            <div class="tm-modal-content">
                <button class="tm-modal-close" aria-label="<?php _e('Fermer', 'training-manager'); ?>">&times;</button>
                <div class="tm-modal-body">
                    <!-- Contenu chargé dynamiquement -->
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Obtenir les sessions du mois
     *
     * @param int    $month
     * @param int    $year
     * @param string $type
     * @return array
     */
    public function get_month_sessions(int $month, int $year, string $type = ''): array {
        $start = sprintf('%04d-%02d-01', $year, $month);
        $end = date('Y-m-t', strtotime($start));

        return $this->fetch_events($start, $end, $type);
    }
}
