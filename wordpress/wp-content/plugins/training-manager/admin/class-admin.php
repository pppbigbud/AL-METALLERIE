<?php
/**
 * Classe Admin - Interface d'administration
 *
 * @package TrainingManager
 * @since 1.0.0
 */

namespace TrainingManager\Admin;

if (!defined('ABSPATH')) {
    exit;
}

use TrainingManager\Settings;
use TrainingManager\Bookings;

/**
 * Classe Admin
 * 
 * Gère l'interface d'administration du plugin
 */
class Admin {

    /**
     * Enqueue des styles admin
     *
     * @param string $hook
     */
    public function enqueue_styles(string $hook): void {
        $screen = get_current_screen();
        
        // Charger uniquement sur les pages du plugin
        if (!$this->is_plugin_page($screen)) {
            return;
        }

        wp_enqueue_style(
            'tm-admin',
            TM_PLUGIN_URL . 'admin/css/admin.css',
            [],
            TM_VERSION
        );

        // Color picker
        wp_enqueue_style('wp-color-picker');
    }

    /**
     * Enqueue des scripts admin
     *
     * @param string $hook
     */
    public function enqueue_scripts(string $hook): void {
        $screen = get_current_screen();
        
        if (!$this->is_plugin_page($screen)) {
            return;
        }

        wp_enqueue_media();
        wp_enqueue_script('wp-color-picker');

        wp_enqueue_script(
            'tm-admin',
            TM_PLUGIN_URL . 'admin/js/admin.js',
            ['jquery', 'wp-color-picker'],
            TM_VERSION,
            true
        );

        wp_localize_script('tm-admin', 'tmAdmin', [
            'ajaxUrl'   => admin_url('admin-ajax.php'),
            'nonce'     => wp_create_nonce('tm_admin_nonce'),
            'strings'   => [
                'confirmDelete' => __('Êtes-vous sûr de vouloir supprimer cet élément ?', 'training-manager'),
                'loading'       => __('Chargement...', 'training-manager'),
                'error'         => __('Une erreur est survenue.', 'training-manager'),
            ],
        ]);
    }

    /**
     * Vérifier si on est sur une page du plugin
     *
     * @param \WP_Screen|null $screen
     * @return bool
     */
    private function is_plugin_page(?\WP_Screen $screen): bool {
        if (!$screen) {
            return false;
        }

        $plugin_pages = [
            'training_session',
            'edit-training_session',
            'toplevel_page_tm-dashboard',
            'formations_page_tm-bookings',
            'formations_page_tm-reports',
            'formations_page_tm-settings',
        ];

        return in_array($screen->id, $plugin_pages) || 
               strpos($screen->id, 'training_session') !== false ||
               strpos($screen->id, 'training_type') !== false ||
               strpos($screen->id, 'training_theme') !== false;
    }

    /**
     * Ajouter le menu admin
     */
    public function add_admin_menu(): void {
        // Menu principal (Dashboard)
        add_menu_page(
            __('Formations', 'training-manager'),
            __('Formations', 'training-manager'),
            'manage_training_sessions',
            'tm-dashboard',
            [$this, 'render_dashboard_page'],
            'dashicons-welcome-learn-more',
            25
        );

        // Sous-menu Dashboard
        add_submenu_page(
            'tm-dashboard',
            __('Tableau de bord', 'training-manager'),
            __('Tableau de bord', 'training-manager'),
            'manage_training_sessions',
            'tm-dashboard',
            [$this, 'render_dashboard_page']
        );

        // Sous-menu Toutes les sessions
        add_submenu_page(
            'tm-dashboard',
            __('Toutes les sessions', 'training-manager'),
            __('Toutes les sessions', 'training-manager'),
            'edit_training_sessions',
            'edit.php?post_type=training_session'
        );

        // Sous-menu Ajouter
        add_submenu_page(
            'tm-dashboard',
            __('Ajouter une session', 'training-manager'),
            __('Ajouter', 'training-manager'),
            'edit_training_sessions',
            'post-new.php?post_type=training_session'
        );

        // Sous-menu Demandes/Réservations
        add_submenu_page(
            'tm-dashboard',
            __('Demandes', 'training-manager'),
            __('Demandes', 'training-manager'),
            'manage_training_sessions',
            'tm-bookings',
            [$this, 'render_bookings_page']
        );

        // Sous-menu Rapports
        add_submenu_page(
            'tm-dashboard',
            __('Rapports', 'training-manager'),
            __('Rapports', 'training-manager'),
            'view_training_reports',
            'tm-reports',
            [$this, 'render_reports_page']
        );

        // Sous-menu Paramètres
        add_submenu_page(
            'tm-dashboard',
            __('Paramètres', 'training-manager'),
            __('Paramètres', 'training-manager'),
            'manage_options',
            'tm-settings',
            [$this, 'render_settings_page']
        );
    }

    /**
     * Rendu de la page Dashboard
     */
    public function render_dashboard_page(): void {
        $stats = $this->get_dashboard_stats();
        ?>
        <div class="wrap tm-dashboard">
            <h1><?php _e('Tableau de bord Formations', 'training-manager'); ?></h1>

            <div class="tm-dashboard-grid">
                <!-- Stats Cards -->
                <div class="tm-stats-cards">
                    <div class="tm-stat-card tm-stat-sessions">
                        <div class="tm-stat-icon">
                            <span class="dashicons dashicons-calendar-alt"></span>
                        </div>
                        <div class="tm-stat-content">
                            <span class="tm-stat-number"><?php echo esc_html($stats['upcoming_sessions']); ?></span>
                            <span class="tm-stat-label"><?php _e('Sessions à venir', 'training-manager'); ?></span>
                        </div>
                    </div>

                    <div class="tm-stat-card tm-stat-requests">
                        <div class="tm-stat-icon">
                            <span class="dashicons dashicons-email"></span>
                        </div>
                        <div class="tm-stat-content">
                            <span class="tm-stat-number"><?php echo esc_html($stats['pending_requests']); ?></span>
                            <span class="tm-stat-label"><?php _e('Demandes en attente', 'training-manager'); ?></span>
                        </div>
                    </div>

                    <div class="tm-stat-card tm-stat-fill">
                        <div class="tm-stat-icon">
                            <span class="dashicons dashicons-groups"></span>
                        </div>
                        <div class="tm-stat-content">
                            <span class="tm-stat-number"><?php echo esc_html($stats['fill_rate']); ?>%</span>
                            <span class="tm-stat-label"><?php _e('Taux de remplissage', 'training-manager'); ?></span>
                        </div>
                    </div>

                    <div class="tm-stat-card tm-stat-revenue">
                        <div class="tm-stat-icon">
                            <span class="dashicons dashicons-chart-bar"></span>
                        </div>
                        <div class="tm-stat-content">
                            <span class="tm-stat-number"><?php echo esc_html(number_format($stats['projected_revenue'], 0, ',', ' ')); ?> €</span>
                            <span class="tm-stat-label"><?php _e('Revenus prévisionnels', 'training-manager'); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Prochaines sessions -->
                <div class="tm-dashboard-section tm-upcoming-sessions">
                    <h2><?php _e('Prochaines sessions', 'training-manager'); ?></h2>
                    <?php $this->render_upcoming_sessions_table(); ?>
                </div>

                <!-- Dernières demandes -->
                <div class="tm-dashboard-section tm-recent-requests">
                    <h2><?php _e('Dernières demandes', 'training-manager'); ?></h2>
                    <?php $this->render_recent_requests_table(); ?>
                </div>

                <!-- Actions rapides -->
                <div class="tm-dashboard-section tm-quick-actions">
                    <h2><?php _e('Actions rapides', 'training-manager'); ?></h2>
                    <div class="tm-quick-actions-grid">
                        <a href="<?php echo admin_url('post-new.php?post_type=training_session'); ?>" class="tm-quick-action">
                            <span class="dashicons dashicons-plus-alt"></span>
                            <?php _e('Nouvelle session', 'training-manager'); ?>
                        </a>
                        <a href="<?php echo admin_url('admin.php?page=tm-bookings'); ?>" class="tm-quick-action">
                            <span class="dashicons dashicons-email-alt"></span>
                            <?php _e('Voir les demandes', 'training-manager'); ?>
                        </a>
                        <a href="<?php echo admin_url('admin.php?page=tm-reports'); ?>" class="tm-quick-action">
                            <span class="dashicons dashicons-chart-area"></span>
                            <?php _e('Rapports', 'training-manager'); ?>
                        </a>
                        <a href="<?php echo admin_url('admin.php?page=tm-settings'); ?>" class="tm-quick-action">
                            <span class="dashicons dashicons-admin-settings"></span>
                            <?php _e('Paramètres', 'training-manager'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Obtenir les statistiques du dashboard
     *
     * @return array
     */
    private function get_dashboard_stats(): array {
        global $wpdb;

        // Sessions à venir
        $upcoming_sessions = get_posts([
            'post_type'      => 'training_session',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'meta_query'     => [
                [
                    'key'     => '_tm_start_date',
                    'value'   => date('Y-m-d'),
                    'compare' => '>=',
                    'type'    => 'DATE',
                ],
            ],
        ]);

        // Demandes en attente
        $bookings = new Bookings();
        $pending_requests = $bookings->count_bookings(['status' => 'pending']);

        // Taux de remplissage
        $total_places = 0;
        $reserved_places = 0;
        $projected_revenue = 0;

        foreach ($upcoming_sessions as $session) {
            $places = get_post_meta($session->ID, '_tm_total_places', true) ?: 0;
            $reserved = get_post_meta($session->ID, '_tm_reserved_places', true) ?: 0;
            $price = get_post_meta($session->ID, '_tm_price', true) ?: 0;

            $total_places += $places;
            $reserved_places += $reserved;
            $projected_revenue += $reserved * $price;
        }

        $fill_rate = $total_places > 0 ? round(($reserved_places / $total_places) * 100) : 0;

        return [
            'upcoming_sessions' => count($upcoming_sessions),
            'pending_requests'  => $pending_requests,
            'fill_rate'         => $fill_rate,
            'projected_revenue' => $projected_revenue,
        ];
    }

    /**
     * Rendu du tableau des prochaines sessions
     */
    private function render_upcoming_sessions_table(): void {
        $sessions = get_posts([
            'post_type'      => 'training_session',
            'posts_per_page' => 5,
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
        ]);

        if (empty($sessions)) {
            echo '<p>' . __('Aucune session à venir.', 'training-manager') . '</p>';
            return;
        }
        ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('Formation', 'training-manager'); ?></th>
                    <th><?php _e('Date', 'training-manager'); ?></th>
                    <th><?php _e('Places', 'training-manager'); ?></th>
                    <th><?php _e('Statut', 'training-manager'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sessions as $session) : 
                    $start_date = get_post_meta($session->ID, '_tm_start_date', true);
                    $total = get_post_meta($session->ID, '_tm_total_places', true);
                    $reserved = get_post_meta($session->ID, '_tm_reserved_places', true);
                    $status = get_post_meta($session->ID, '_tm_status', true) ?: 'open';
                ?>
                    <tr>
                        <td>
                            <a href="<?php echo get_edit_post_link($session->ID); ?>">
                                <?php echo esc_html($session->post_title); ?>
                            </a>
                        </td>
                        <td><?php echo date_i18n('d/m/Y', strtotime($start_date)); ?></td>
                        <td><?php echo esc_html($reserved . '/' . $total); ?></td>
                        <td><span class="tm-status-badge tm-status-<?php echo esc_attr($status); ?>"><?php echo esc_html($status); ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
    }

    /**
     * Rendu du tableau des dernières demandes
     */
    private function render_recent_requests_table(): void {
        $bookings = new Bookings();
        $requests = $bookings->get_bookings(['per_page' => 5]);

        if (empty($requests)) {
            echo '<p>' . __('Aucune demande pour le moment.', 'training-manager') . '</p>';
            return;
        }
        ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('Nom', 'training-manager'); ?></th>
                    <th><?php _e('Formation', 'training-manager'); ?></th>
                    <th><?php _e('Date', 'training-manager'); ?></th>
                    <th><?php _e('Statut', 'training-manager'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $request) : 
                    $session = get_post($request->session_id);
                ?>
                    <tr>
                        <td><?php echo esc_html($request->first_name . ' ' . $request->last_name); ?></td>
                        <td><?php echo $session ? esc_html($session->post_title) : '-'; ?></td>
                        <td><?php echo date_i18n('d/m/Y H:i', strtotime($request->created_at)); ?></td>
                        <td><span class="tm-status-badge tm-status-<?php echo esc_attr($request->status); ?>"><?php echo esc_html($request->status); ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
    }

    /**
     * Rendu de la page des demandes
     */
    public function render_bookings_page(): void {
        $bookings = new Bookings();
        $session_id = isset($_GET['session_id']) ? absint($_GET['session_id']) : 0;
        $status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';
        $page = isset($_GET['paged']) ? absint($_GET['paged']) : 1;

        $requests = $bookings->get_bookings([
            'session_id' => $session_id,
            'status'     => $status,
            'page'       => $page,
        ]);

        $total = $bookings->count_bookings([
            'session_id' => $session_id,
            'status'     => $status,
        ]);
        ?>
        <div class="wrap tm-bookings">
            <h1>
                <?php _e('Demandes d\'information', 'training-manager'); ?>
                <a href="<?php echo admin_url('admin.php?page=tm-bookings&action=export'); ?>" class="page-title-action">
                    <?php _e('Exporter CSV', 'training-manager'); ?>
                </a>
            </h1>

            <!-- Filtres -->
            <div class="tm-bookings-filters">
                <form method="get">
                    <input type="hidden" name="page" value="tm-bookings">
                    
                    <select name="session_id">
                        <option value=""><?php _e('Toutes les sessions', 'training-manager'); ?></option>
                        <?php
                        $sessions = get_posts(['post_type' => 'training_session', 'posts_per_page' => -1]);
                        foreach ($sessions as $session) :
                        ?>
                            <option value="<?php echo $session->ID; ?>" <?php selected($session_id, $session->ID); ?>>
                                <?php echo esc_html($session->post_title); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="status">
                        <option value=""><?php _e('Tous les statuts', 'training-manager'); ?></option>
                        <option value="pending" <?php selected($status, 'pending'); ?>><?php _e('En attente', 'training-manager'); ?></option>
                        <option value="confirmed" <?php selected($status, 'confirmed'); ?>><?php _e('Confirmé', 'training-manager'); ?></option>
                        <option value="cancelled" <?php selected($status, 'cancelled'); ?>><?php _e('Annulé', 'training-manager'); ?></option>
                    </select>

                    <button type="submit" class="button"><?php _e('Filtrer', 'training-manager'); ?></button>
                </form>
            </div>

            <!-- Tableau -->
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e('ID', 'training-manager'); ?></th>
                        <th><?php _e('Nom', 'training-manager'); ?></th>
                        <th><?php _e('Email', 'training-manager'); ?></th>
                        <th><?php _e('Téléphone', 'training-manager'); ?></th>
                        <th><?php _e('Formation', 'training-manager'); ?></th>
                        <th><?php _e('Message', 'training-manager'); ?></th>
                        <th><?php _e('Date', 'training-manager'); ?></th>
                        <th><?php _e('Statut', 'training-manager'); ?></th>
                        <th><?php _e('Actions', 'training-manager'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($requests)) : ?>
                        <tr>
                            <td colspan="9"><?php _e('Aucune demande trouvée.', 'training-manager'); ?></td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($requests as $request) : 
                            $session = get_post($request->session_id);
                        ?>
                            <tr>
                                <td><?php echo esc_html($request->id); ?></td>
                                <td><?php echo esc_html($request->first_name . ' ' . $request->last_name); ?></td>
                                <td><a href="mailto:<?php echo esc_attr($request->email); ?>"><?php echo esc_html($request->email); ?></a></td>
                                <td><?php echo esc_html($request->phone); ?></td>
                                <td><?php echo $session ? esc_html($session->post_title) : '-'; ?></td>
                                <td><?php echo esc_html(wp_trim_words($request->message, 10)); ?></td>
                                <td><?php echo date_i18n('d/m/Y H:i', strtotime($request->created_at)); ?></td>
                                <td><span class="tm-status-badge tm-status-<?php echo esc_attr($request->status); ?>"><?php echo esc_html($request->status); ?></span></td>
                                <td>
                                    <a href="#" class="tm-view-request" data-id="<?php echo $request->id; ?>"><?php _e('Voir', 'training-manager'); ?></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    /**
     * Rendu de la page des rapports
     */
    public function render_reports_page(): void {
        ?>
        <div class="wrap tm-reports">
            <h1><?php _e('Rapports & Statistiques', 'training-manager'); ?></h1>
            
            <div class="tm-reports-grid">
                <div class="tm-report-card">
                    <h3><?php _e('Formations les plus populaires', 'training-manager'); ?></h3>
                    <?php $this->render_popular_sessions_chart(); ?>
                </div>

                <div class="tm-report-card">
                    <h3><?php _e('Évolution des demandes', 'training-manager'); ?></h3>
                    <?php $this->render_requests_chart(); ?>
                </div>

                <div class="tm-report-card">
                    <h3><?php _e('Répartition par type', 'training-manager'); ?></h3>
                    <?php $this->render_type_distribution(); ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Rendu du graphique des sessions populaires
     */
    private function render_popular_sessions_chart(): void {
        global $wpdb;
        $table = $wpdb->prefix . 'tm_bookings';
        
        $results = $wpdb->get_results("
            SELECT session_id, COUNT(*) as count 
            FROM $table 
            GROUP BY session_id 
            ORDER BY count DESC 
            LIMIT 5
        ");

        if (empty($results)) {
            echo '<p>' . __('Pas assez de données.', 'training-manager') . '</p>';
            return;
        }

        echo '<ul class="tm-popular-list">';
        foreach ($results as $row) {
            $session = get_post($row->session_id);
            if ($session) {
                echo '<li><span class="tm-session-name">' . esc_html($session->post_title) . '</span><span class="tm-session-count">' . esc_html($row->count) . ' demandes</span></li>';
            }
        }
        echo '</ul>';
    }

    /**
     * Rendu du graphique des demandes
     */
    private function render_requests_chart(): void {
        echo '<p>' . __('Graphique à implémenter avec Chart.js', 'training-manager') . '</p>';
    }

    /**
     * Rendu de la distribution par type
     */
    private function render_type_distribution(): void {
        $types = get_terms(['taxonomy' => 'training_type', 'hide_empty' => false]);
        
        echo '<ul class="tm-type-list">';
        foreach ($types as $type) {
            $count = $type->count;
            echo '<li><span class="tm-type-name">' . esc_html($type->name) . '</span><span class="tm-type-count">' . esc_html($count) . ' sessions</span></li>';
        }
        echo '</ul>';
    }

    /**
     * Rendu de la page des paramètres
     */
    public function render_settings_page(): void {
        $settings = new Settings();
        $settings->render_settings_page();
    }

    /**
     * Définir les colonnes personnalisées
     *
     * @param array $columns
     * @return array
     */
    public function set_custom_columns(array $columns): array {
        $new_columns = [];
        
        foreach ($columns as $key => $value) {
            if ($key === 'title') {
                $new_columns[$key] = $value;
                $new_columns['tm_date'] = __('Date', 'training-manager');
                $new_columns['tm_places'] = __('Places', 'training-manager');
                $new_columns['tm_status'] = __('Statut', 'training-manager');
                $new_columns['tm_price'] = __('Tarif', 'training-manager');
            } elseif ($key !== 'date') {
                $new_columns[$key] = $value;
            }
        }

        return $new_columns;
    }

    /**
     * Contenu des colonnes personnalisées
     *
     * @param string $column
     * @param int    $post_id
     */
    public function custom_column_content(string $column, int $post_id): void {
        switch ($column) {
            case 'tm_date':
                $start = get_post_meta($post_id, '_tm_start_date', true);
                $end = get_post_meta($post_id, '_tm_end_date', true);
                echo $start ? date_i18n('d/m/Y', strtotime($start)) : '-';
                if ($end && $end !== $start) {
                    echo ' - ' . date_i18n('d/m/Y', strtotime($end));
                }
                break;

            case 'tm_places':
                $total = get_post_meta($post_id, '_tm_total_places', true) ?: 0;
                $reserved = get_post_meta($post_id, '_tm_reserved_places', true) ?: 0;
                $remaining = $total - $reserved;
                echo '<span class="tm-places">' . esc_html($reserved) . '/' . esc_html($total) . '</span>';
                echo '<div class="tm-places-bar-mini"><div style="width:' . ($total > 0 ? ($reserved / $total * 100) : 0) . '%"></div></div>';
                break;

            case 'tm_status':
                $status = get_post_meta($post_id, '_tm_status', true) ?: 'open';
                $labels = [
                    'open'      => __('Ouvert', 'training-manager'),
                    'full'      => __('Complet', 'training-manager'),
                    'waitlist'  => __('Liste d\'attente', 'training-manager'),
                    'cancelled' => __('Annulé', 'training-manager'),
                    'completed' => __('Terminé', 'training-manager'),
                ];
                echo '<span class="tm-status-badge tm-status-' . esc_attr($status) . '">' . esc_html($labels[$status] ?? $status) . '</span>';
                break;

            case 'tm_price':
                $price = get_post_meta($post_id, '_tm_price', true);
                echo $price ? esc_html(number_format($price, 0, ',', ' ')) . ' €' : '-';
                break;
        }
    }

    /**
     * Colonnes triables
     *
     * @param array $columns
     * @return array
     */
    public function sortable_columns(array $columns): array {
        $columns['tm_date'] = 'tm_date';
        $columns['tm_price'] = 'tm_price';
        return $columns;
    }
}
