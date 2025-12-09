<?php
/**
 * Classe Shortcodes - Shortcodes du plugin
 *
 * @package TrainingManager
 * @since 1.0.0
 */

namespace TrainingManager;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe Shortcodes
 * 
 * Enregistre et gère les shortcodes
 */
class Shortcodes {

    /**
     * Enregistrer les shortcodes
     */
    public function register(): void {
        add_shortcode('training_calendar', [$this, 'calendar_shortcode']);
        add_shortcode('training_list', [$this, 'list_shortcode']);
        add_shortcode('training_upcoming', [$this, 'upcoming_shortcode']);
        add_shortcode('training_category', [$this, 'category_shortcode']);
    }

    /**
     * Shortcode Calendrier
     *
     * @param array $atts
     * @return string
     */
    public function calendar_shortcode($atts): string {
        $atts = shortcode_atts([
            'type'        => '',
            'show_legend' => 'yes',
            'view'        => 'dayGridMonth',
        ], $atts, 'training_calendar');

        $calendar = new Calendar();
        return $calendar->render($atts);
    }

    /**
     * Shortcode Liste avec filtres
     *
     * @param array $atts
     * @return string
     */
    public function list_shortcode($atts): string {
        $atts = shortcode_atts([
            'type'         => '',
            'theme'        => '',
            'show_filters' => 'yes',
            'per_page'     => 9,
            'columns'      => 3,
        ], $atts, 'training_list');

        wp_enqueue_style('tm-public');
        wp_enqueue_script('tm-public');

        ob_start();
        ?>
        <div class="tm-sessions-wrapper" data-per-page="<?php echo esc_attr($atts['per_page']); ?>">
            
            <?php if ($atts['show_filters'] === 'yes') : ?>
                <div class="tm-filters">
                    <div class="tm-filter-group">
                        <label for="tm-filter-type"><?php _e('Type', 'training-manager'); ?></label>
                        <select id="tm-filter-type" class="tm-filter" data-filter="type">
                            <option value=""><?php _e('Tous les types', 'training-manager'); ?></option>
                            <?php
                            $types = get_terms(['taxonomy' => 'training_type', 'hide_empty' => true]);
                            foreach ($types as $type) :
                            ?>
                                <option value="<?php echo esc_attr($type->slug); ?>" <?php selected($atts['type'], $type->slug); ?>>
                                    <?php echo esc_html($type->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="tm-filter-group">
                        <label for="tm-filter-theme"><?php _e('Thème', 'training-manager'); ?></label>
                        <select id="tm-filter-theme" class="tm-filter" data-filter="theme">
                            <option value=""><?php _e('Tous les thèmes', 'training-manager'); ?></option>
                            <?php
                            $themes = get_terms(['taxonomy' => 'training_theme', 'hide_empty' => true]);
                            foreach ($themes as $theme) :
                            ?>
                                <option value="<?php echo esc_attr($theme->slug); ?>" <?php selected($atts['theme'], $theme->slug); ?>>
                                    <?php echo esc_html($theme->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="tm-filter-group">
                        <label for="tm-filter-availability"><?php _e('Disponibilité', 'training-manager'); ?></label>
                        <select id="tm-filter-availability" class="tm-filter" data-filter="availability">
                            <option value=""><?php _e('Toutes', 'training-manager'); ?></option>
                            <option value="available"><?php _e('Places disponibles', 'training-manager'); ?></option>
                        </select>
                    </div>

                    <div class="tm-filter-group">
                        <label for="tm-filter-date"><?php _e('Période', 'training-manager'); ?></label>
                        <select id="tm-filter-date" class="tm-filter" data-filter="date">
                            <option value=""><?php _e('Toutes les dates', 'training-manager'); ?></option>
                            <option value="upcoming"><?php _e('À venir', 'training-manager'); ?></option>
                            <option value="this_month"><?php _e('Ce mois', 'training-manager'); ?></option>
                            <option value="next_month"><?php _e('Mois prochain', 'training-manager'); ?></option>
                        </select>
                    </div>
                </div>
            <?php endif; ?>

            <div class="tm-sessions-grid tm-columns-<?php echo esc_attr($atts['columns']); ?>">
                <?php echo $this->render_sessions_grid($atts); ?>
            </div>

            <div class="tm-load-more-wrapper" style="display: none;">
                <button class="tm-load-more"><?php _e('Charger plus', 'training-manager'); ?></button>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Shortcode Prochaines formations
     *
     * @param array $atts
     * @return string
     */
    public function upcoming_shortcode($atts): string {
        $atts = shortcode_atts([
            'count'   => 3,
            'type'    => '',
            'columns' => 3,
        ], $atts, 'training_upcoming');

        wp_enqueue_style('tm-public');

        $args = [
            'post_type'      => 'training_session',
            'posts_per_page' => absint($atts['count']),
            'post_status'    => 'publish',
            'meta_key'       => '_tm_start_date',
            'orderby'        => 'meta_value',
            'order'          => 'ASC',
            'meta_query'     => [
                [
                    'key'     => '_tm_start_date',
                    'value'   => date('Y-m-d'),
                    'compare' => '>=',
                    'type'    => 'DATE',
                ],
                [
                    'key'     => '_tm_status',
                    'value'   => ['open', 'waitlist'],
                    'compare' => 'IN',
                ],
            ],
        ];

        if (!empty($atts['type'])) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'training_type',
                    'field'    => 'slug',
                    'terms'    => $atts['type'],
                ],
            ];
        }

        $sessions = get_posts($args);

        ob_start();
        ?>
        <div class="tm-upcoming-sessions tm-columns-<?php echo esc_attr($atts['columns']); ?>">
            <?php if (empty($sessions)) : ?>
                <p class="tm-no-sessions"><?php _e('Aucune formation à venir pour le moment.', 'training-manager'); ?></p>
            <?php else : ?>
                <?php foreach ($sessions as $session) : ?>
                    <?php echo $this->render_session_card($session); ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Shortcode par catégorie
     *
     * @param array $atts
     * @return string
     */
    public function category_shortcode($atts): string {
        $atts = shortcode_atts([
            'category' => '',
            'count'    => -1,
            'columns'  => 3,
        ], $atts, 'training_category');

        if (empty($atts['category'])) {
            return '';
        }

        wp_enqueue_style('tm-public');

        $args = [
            'post_type'      => 'training_session',
            'posts_per_page' => $atts['count'],
            'post_status'    => 'publish',
            'meta_key'       => '_tm_start_date',
            'orderby'        => 'meta_value',
            'order'          => 'ASC',
            'tax_query'      => [
                [
                    'taxonomy' => 'training_type',
                    'field'    => 'slug',
                    'terms'    => $atts['category'],
                ],
            ],
            'meta_query'     => [
                [
                    'key'     => '_tm_start_date',
                    'value'   => date('Y-m-d'),
                    'compare' => '>=',
                    'type'    => 'DATE',
                ],
            ],
        ];

        $sessions = get_posts($args);

        ob_start();
        ?>
        <div class="tm-category-sessions tm-columns-<?php echo esc_attr($atts['columns']); ?>">
            <?php if (empty($sessions)) : ?>
                <p class="tm-no-sessions"><?php _e('Aucune formation dans cette catégorie.', 'training-manager'); ?></p>
            <?php else : ?>
                <?php foreach ($sessions as $session) : ?>
                    <?php echo $this->render_session_card($session); ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Rendu de la grille de sessions
     *
     * @param array $atts
     * @return string
     */
    private function render_sessions_grid(array $atts): string {
        $args = [
            'post_type'      => 'training_session',
            'posts_per_page' => absint($atts['per_page']),
            'post_status'    => 'publish',
            'meta_key'       => '_tm_start_date',
            'orderby'        => 'meta_value',
            'order'          => 'ASC',
            'meta_query'     => [
                [
                    'key'     => '_tm_start_date',
                    'value'   => date('Y-m-d'),
                    'compare' => '>=',
                    'type'    => 'DATE',
                ],
            ],
        ];

        if (!empty($atts['type'])) {
            $args['tax_query'][] = [
                'taxonomy' => 'training_type',
                'field'    => 'slug',
                'terms'    => $atts['type'],
            ];
        }

        if (!empty($atts['theme'])) {
            $args['tax_query'][] = [
                'taxonomy' => 'training_theme',
                'field'    => 'slug',
                'terms'    => $atts['theme'],
            ];
        }

        $sessions = get_posts($args);

        if (empty($sessions)) {
            return '<p class="tm-no-sessions">' . __('Aucune formation disponible.', 'training-manager') . '</p>';
        }

        $output = '';
        foreach ($sessions as $session) {
            $output .= $this->render_session_card($session);
        }

        return $output;
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

        // Statut badge
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

                    <?php if ($start_time) : ?>
                        <div class="tm-detail-item tm-detail-time">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                            <span><?php echo esc_html($start_time); ?><?php if ($end_time) echo ' - ' . esc_html($end_time); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($location) : ?>
                        <div class="tm-detail-item tm-detail-location">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            <span><?php echo esc_html($location); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($trainer) : ?>
                        <div class="tm-detail-item tm-detail-trainer">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            <span><?php echo esc_html($trainer); ?></span>
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
                                printf(_n('%d place restante', '%d places restantes', $remaining, 'training-manager'), $remaining);
                            }
                            ?>
                        </span>
                    </div>

                    <?php if ($price) : ?>
                        <div class="tm-session-price">
                            <span class="tm-price-amount"><?php echo esc_html(number_format($price, 0, ',', ' ')); ?></span>
                            <span class="tm-price-currency"><?php echo esc_html($currency); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <a href="<?php echo get_permalink($session->ID); ?>" class="tm-session-cta">
                    <?php 
                    if ($status === 'open') {
                        _e('Voir les détails', 'training-manager');
                    } elseif ($status === 'waitlist') {
                        _e('Liste d\'attente', 'training-manager');
                    } else {
                        _e('En savoir plus', 'training-manager');
                    }
                    ?>
                </a>
            </div>
        </article>
        <?php
        return ob_get_clean();
    }
}
