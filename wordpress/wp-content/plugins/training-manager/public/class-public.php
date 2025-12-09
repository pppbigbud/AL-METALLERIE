<?php
/**
 * Classe PublicHandler - Interface publique
 *
 * @package TrainingManager
 * @since 1.0.0
 */

namespace TrainingManager\PublicSide;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe PublicHandler
 * 
 * Gère l'affichage frontend du plugin
 */
class PublicHandler {

    /**
     * Enqueue des styles publics
     */
    public function enqueue_styles(): void {
        wp_register_style(
            'tm-public',
            TM_PLUGIN_URL . 'public/css/public.css',
            [],
            TM_VERSION
        );

        wp_register_style(
            'tm-calendar',
            TM_PLUGIN_URL . 'public/css/calendar.css',
            ['tm-public'],
            TM_VERSION
        );

        // FullCalendar CSS
        wp_register_style(
            'fullcalendar',
            'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css',
            [],
            '6.1.10'
        );

        // Charger sur les pages avec shortcodes ou single session
        if ($this->should_load_assets()) {
            wp_enqueue_style('tm-public');
        }
    }

    /**
     * Enqueue des scripts publics
     */
    public function enqueue_scripts(): void {
        // FullCalendar JS
        wp_register_script(
            'fullcalendar',
            'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js',
            [],
            '6.1.10',
            true
        );

        wp_register_script(
            'fullcalendar-locales',
            'https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/locales-all.global.min.js',
            ['fullcalendar'],
            '6.1.10',
            true
        );

        wp_register_script(
            'tm-calendar',
            TM_PLUGIN_URL . 'public/js/calendar.js',
            ['fullcalendar', 'fullcalendar-locales'],
            TM_VERSION,
            true
        );

        wp_register_script(
            'tm-public',
            TM_PLUGIN_URL . 'public/js/public.js',
            [],
            TM_VERSION,
            true
        );

        // Localisation
        $localize_data = [
            'ajaxUrl'      => admin_url('admin-ajax.php'),
            'calendarNonce'=> wp_create_nonce('tm_calendar_nonce'),
            'contactNonce' => wp_create_nonce('tm_contact_nonce'),
            'filterNonce'  => wp_create_nonce('tm_filter_nonce'),
            'locale'       => substr(get_locale(), 0, 2),
            'firstDay'     => get_option('tm_calendar_first_day', 1),
            'primaryColor' => get_option('tm_primary_color', '#F08B18'),
            'strings'      => [
                'loading'      => __('Chargement...', 'training-manager'),
                'error'        => __('Une erreur est survenue.', 'training-manager'),
                'noResults'    => __('Aucun résultat.', 'training-manager'),
                'success'      => __('Votre demande a été envoyée.', 'training-manager'),
                'places'       => __('places restantes', 'training-manager'),
                'full'         => __('Complet', 'training-manager'),
            ],
        ];

        wp_localize_script('tm-calendar', 'tmCalendar', $localize_data);
        wp_localize_script('tm-public', 'tmPublic', $localize_data);

        if ($this->should_load_assets()) {
            wp_enqueue_script('tm-public');
        }
    }

    /**
     * Vérifier si on doit charger les assets
     *
     * @return bool
     */
    private function should_load_assets(): bool {
        global $post;

        // Single session
        if (is_singular('training_session')) {
            return true;
        }

        // Archive
        if (is_post_type_archive('training_session')) {
            return true;
        }

        // Taxonomies
        if (is_tax('training_type') || is_tax('training_theme')) {
            return true;
        }

        // Shortcodes dans le contenu
        if ($post && has_shortcode($post->post_content, 'training_calendar')) {
            return true;
        }
        if ($post && has_shortcode($post->post_content, 'training_list')) {
            return true;
        }
        if ($post && has_shortcode($post->post_content, 'training_upcoming')) {
            return true;
        }
        if ($post && has_shortcode($post->post_content, 'training_category')) {
            return true;
        }

        return false;
    }

    /**
     * Filtrer les sessions (AJAX)
     */
    public function filter_sessions(): void {
        check_ajax_referer('tm_filter_nonce', 'nonce');

        $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';
        $theme = isset($_POST['theme']) ? sanitize_text_field($_POST['theme']) : '';
        $availability = isset($_POST['availability']) ? sanitize_text_field($_POST['availability']) : '';
        $date_filter = isset($_POST['date']) ? sanitize_text_field($_POST['date']) : '';
        $page = isset($_POST['page']) ? absint($_POST['page']) : 1;
        $per_page = isset($_POST['per_page']) ? absint($_POST['per_page']) : 9;

        $args = [
            'post_type'      => 'training_session',
            'posts_per_page' => $per_page,
            'paged'          => $page,
            'post_status'    => 'publish',
            'meta_key'       => '_tm_start_date',
            'orderby'        => 'meta_value',
            'order'          => 'ASC',
            'meta_query'     => [],
            'tax_query'      => [],
        ];

        // Filtre par date
        switch ($date_filter) {
            case 'upcoming':
                $args['meta_query'][] = [
                    'key'     => '_tm_start_date',
                    'value'   => date('Y-m-d'),
                    'compare' => '>=',
                    'type'    => 'DATE',
                ];
                break;
            case 'this_month':
                $args['meta_query'][] = [
                    'key'     => '_tm_start_date',
                    'value'   => [date('Y-m-01'), date('Y-m-t')],
                    'compare' => 'BETWEEN',
                    'type'    => 'DATE',
                ];
                break;
            case 'next_month':
                $next_month = date('Y-m-01', strtotime('+1 month'));
                $args['meta_query'][] = [
                    'key'     => '_tm_start_date',
                    'value'   => [$next_month, date('Y-m-t', strtotime($next_month))],
                    'compare' => 'BETWEEN',
                    'type'    => 'DATE',
                ];
                break;
            default:
                $args['meta_query'][] = [
                    'key'     => '_tm_start_date',
                    'value'   => date('Y-m-d'),
                    'compare' => '>=',
                    'type'    => 'DATE',
                ];
        }

        // Filtre par disponibilité
        if ($availability === 'available') {
            $args['meta_query'][] = [
                'key'     => '_tm_status',
                'value'   => 'open',
                'compare' => '=',
            ];
        }

        // Filtre par type
        if (!empty($type)) {
            $args['tax_query'][] = [
                'taxonomy' => 'training_type',
                'field'    => 'slug',
                'terms'    => $type,
            ];
        }

        // Filtre par thème
        if (!empty($theme)) {
            $args['tax_query'][] = [
                'taxonomy' => 'training_theme',
                'field'    => 'slug',
                'terms'    => $theme,
            ];
        }

        $query = new \WP_Query($args);
        $html = '';

        if ($query->have_posts()) {
            $shortcodes = new \TrainingManager\Shortcodes();
            while ($query->have_posts()) {
                $query->the_post();
                $html .= $this->render_session_card(get_post());
            }
            wp_reset_postdata();
        }

        wp_send_json_success([
            'html'       => $html,
            'found'      => $query->found_posts,
            'max_pages'  => $query->max_num_pages,
            'current'    => $page,
        ]);
    }

    /**
     * Rendu d'une carte de session
     *
     * @param \WP_Post $session
     * @return string
     */
    private function render_session_card(\WP_Post $session): string {
        $start_date = get_post_meta($session->ID, '_tm_start_date', true);
        $end_date = get_post_meta($session->ID, '_tm_end_date', true);
        $start_time = get_post_meta($session->ID, '_tm_start_time', true);
        $end_time = get_post_meta($session->ID, '_tm_end_time', true);
        $location = get_post_meta($session->ID, '_tm_location', true);
        $price = get_post_meta($session->ID, '_tm_price', true);
        $status = get_post_meta($session->ID, '_tm_status', true) ?: 'open';
        $total_places = get_post_meta($session->ID, '_tm_total_places', true);
        $reserved_places = get_post_meta($session->ID, '_tm_reserved_places', true);
        $remaining = $total_places - $reserved_places;
        $trainer = get_post_meta($session->ID, '_tm_trainer', true);

        $types = get_the_terms($session->ID, 'training_type');
        $type_name = (!empty($types) && !is_wp_error($types)) ? $types[0]->name : '';
        $type_slug = (!empty($types) && !is_wp_error($types)) ? $types[0]->slug : '';

        $themes = get_the_terms($session->ID, 'training_theme');
        $theme_name = (!empty($themes) && !is_wp_error($themes)) ? $themes[0]->name : '';

        $date_format = get_option('tm_date_format', 'd/m/Y');
        $currency = get_option('tm_currency_symbol', '€');

        $status_labels = [
            'open'      => __('Disponible', 'training-manager'),
            'full'      => __('Complet', 'training-manager'),
            'waitlist'  => __('Liste d\'attente', 'training-manager'),
            'cancelled' => __('Annulé', 'training-manager'),
        ];

        ob_start();
        ?>
        <article class="tm-session-card tm-status-<?php echo esc_attr($status); ?> tm-type-<?php echo esc_attr($type_slug); ?>">
            <?php if (has_post_thumbnail($session->ID)) : ?>
                <div class="tm-session-image">
                    <a href="<?php echo get_permalink($session->ID); ?>">
                        <?php echo get_the_post_thumbnail($session->ID, 'medium_large'); ?>
                    </a>
                    <span class="tm-session-badge tm-badge-<?php echo esc_attr($status); ?>">
                        <?php echo esc_html($status_labels[$status] ?? $status); ?>
                    </span>
                </div>
            <?php endif; ?>

            <div class="tm-session-content">
                <div class="tm-session-meta-top">
                    <?php if ($type_name) : ?>
                        <span class="tm-session-type"><?php echo esc_html($type_name); ?></span>
                    <?php endif; ?>
                    <?php if ($theme_name) : ?>
                        <span class="tm-session-theme"><?php echo esc_html($theme_name); ?></span>
                    <?php endif; ?>
                </div>

                <h3 class="tm-session-title">
                    <a href="<?php echo get_permalink($session->ID); ?>"><?php echo esc_html($session->post_title); ?></a>
                </h3>

                <div class="tm-session-details">
                    <div class="tm-detail-item tm-detail-date">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <span>
                            <?php 
                            echo date_i18n($date_format, strtotime($start_date));
                            if ($end_date && $end_date !== $start_date) {
                                echo ' - ' . date_i18n($date_format, strtotime($end_date));
                            }
                            ?>
                        </span>
                    </div>

                    <?php if ($location) : ?>
                        <div class="tm-detail-item tm-detail-location">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            <span><?php echo esc_html($location); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="tm-session-footer">
                    <div class="tm-session-places">
                        <div class="tm-places-bar">
                            <div class="tm-places-fill" style="width: <?php echo ($total_places > 0) ? ($reserved_places / $total_places * 100) : 0; ?>%"></div>
                        </div>
                        <span class="tm-places-text">
                            <?php 
                            if ($status === 'full') {
                                _e('Complet', 'training-manager');
                            } else {
                                printf(_n('%d place', '%d places', $remaining, 'training-manager'), $remaining);
                            }
                            ?>
                        </span>
                    </div>

                    <?php if ($price) : ?>
                        <div class="tm-session-price">
                            <?php echo esc_html(number_format($price, 0, ',', ' ')); ?><?php echo esc_html($currency); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <a href="<?php echo get_permalink($session->ID); ?>" class="tm-session-cta">
                    <?php _e('Voir les détails', 'training-manager'); ?>
                </a>
            </div>
        </article>
        <?php
        return ob_get_clean();
    }
}
