<?php
/**
 * Plugin Name: Training Manager
 * Plugin URI: https://al-metallerie-soudure.fr
 * Description: Plugin professionnel de gestion de sessions de formation avec calendrier, réservations et notifications.
 * Version: 1.0.0
 * Author: AL Métallerie
 * Author URI: https://al-metallerie-soudure.fr
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: training-manager
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package TrainingManager
 */

namespace TrainingManager;

// Sécurité : empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

// Constantes du plugin
define('TM_VERSION', '1.0.0');
define('TM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('TM_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('TM_PLUGIN_FILE', __FILE__);

/**
 * Classe principale du plugin Training Manager
 *
 * @since 1.0.0
 */
final class TrainingManager {

    /**
     * Instance unique du plugin (Singleton)
     *
     * @var TrainingManager|null
     */
    private static $instance = null;

    /**
     * Loader pour les hooks
     *
     * @var Loader
     */
    private $loader;

    /**
     * Obtenir l'instance unique du plugin
     *
     * @return TrainingManager
     */
    public static function get_instance(): TrainingManager {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructeur privé (Singleton)
     */
    private function __construct() {
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Charger les dépendances du plugin
     */
    private function load_dependencies(): void {
        // Loader
        require_once TM_PLUGIN_DIR . 'includes/class-loader.php';
        
        // Core
        require_once TM_PLUGIN_DIR . 'includes/class-activator.php';
        require_once TM_PLUGIN_DIR . 'includes/class-deactivator.php';
        require_once TM_PLUGIN_DIR . 'includes/class-post-types.php';
        require_once TM_PLUGIN_DIR . 'includes/class-taxonomies.php';
        require_once TM_PLUGIN_DIR . 'includes/class-metaboxes.php';
        require_once TM_PLUGIN_DIR . 'includes/class-notifications.php';
        require_once TM_PLUGIN_DIR . 'includes/class-calendar.php';
        require_once TM_PLUGIN_DIR . 'includes/class-bookings.php';
        require_once TM_PLUGIN_DIR . 'includes/class-settings.php';
        require_once TM_PLUGIN_DIR . 'includes/class-shortcodes.php';
        
        // Admin
        require_once TM_PLUGIN_DIR . 'admin/class-admin.php';
        
        // Public
        require_once TM_PLUGIN_DIR . 'public/class-public.php';

        $this->loader = new Loader();
    }

    /**
     * Définir la locale pour l'internationalisation
     */
    private function set_locale(): void {
        $this->loader->add_action('plugins_loaded', $this, 'load_plugin_textdomain');
    }

    /**
     * Charger le domaine de traduction
     */
    public function load_plugin_textdomain(): void {
        load_plugin_textdomain(
            'training-manager',
            false,
            dirname(TM_PLUGIN_BASENAME) . '/languages/'
        );
    }

    /**
     * Définir les hooks admin
     */
    private function define_admin_hooks(): void {
        $admin = new Admin\Admin();
        $post_types = new PostTypes();
        $taxonomies = new Taxonomies();
        $metaboxes = new Metaboxes();
        $settings = new Settings();
        $notifications = new Notifications();

        // Post Types et Taxonomies
        $this->loader->add_action('init', $post_types, 'register');
        $this->loader->add_action('init', $taxonomies, 'register');

        // Metaboxes
        $this->loader->add_action('add_meta_boxes', $metaboxes, 'add_meta_boxes');
        $this->loader->add_action('save_post_training_session', $metaboxes, 'save_meta_boxes', 10, 2);

        // Admin
        $this->loader->add_action('admin_enqueue_scripts', $admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $admin, 'add_admin_menu');
        $this->loader->add_filter('manage_training_session_posts_columns', $admin, 'set_custom_columns');
        $this->loader->add_action('manage_training_session_posts_custom_column', $admin, 'custom_column_content', 10, 2);
        $this->loader->add_filter('manage_edit-training_session_sortable_columns', $admin, 'sortable_columns');

        // Settings
        $this->loader->add_action('admin_init', $settings, 'register_settings');

        // Notifications
        $this->loader->add_action('tm_send_notification', $notifications, 'send_notification', 10, 3);
    }

    /**
     * Définir les hooks public
     */
    private function define_public_hooks(): void {
        $public = new PublicSide\PublicHandler();
        $calendar = new Calendar();
        $shortcodes = new Shortcodes();
        $bookings = new Bookings();

        // Styles et scripts
        $this->loader->add_action('wp_enqueue_scripts', $public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $public, 'enqueue_scripts');

        // Shortcodes
        $this->loader->add_action('init', $shortcodes, 'register');

        // AJAX
        $this->loader->add_action('wp_ajax_tm_get_calendar_events', $calendar, 'get_events');
        $this->loader->add_action('wp_ajax_nopriv_tm_get_calendar_events', $calendar, 'get_events');
        $this->loader->add_action('wp_ajax_tm_filter_sessions', $public, 'filter_sessions');
        $this->loader->add_action('wp_ajax_nopriv_tm_filter_sessions', $public, 'filter_sessions');
        $this->loader->add_action('wp_ajax_tm_contact_request', $bookings, 'handle_contact_request');
        $this->loader->add_action('wp_ajax_nopriv_tm_contact_request', $bookings, 'handle_contact_request');
    }

    /**
     * Exécuter le plugin
     */
    public function run(): void {
        $this->loader->run();
    }

    /**
     * Obtenir le loader
     *
     * @return Loader
     */
    public function get_loader(): Loader {
        return $this->loader;
    }
}

/**
 * Activation du plugin
 */
function activate_training_manager(): void {
    require_once TM_PLUGIN_DIR . 'includes/class-activator.php';
    Activator::activate();
}
register_activation_hook(__FILE__, __NAMESPACE__ . '\\activate_training_manager');

/**
 * Désactivation du plugin
 */
function deactivate_training_manager(): void {
    require_once TM_PLUGIN_DIR . 'includes/class-deactivator.php';
    Deactivator::deactivate();
}
register_deactivation_hook(__FILE__, __NAMESPACE__ . '\\deactivate_training_manager');

/**
 * Démarrer le plugin
 */
function run_training_manager(): void {
    $plugin = TrainingManager::get_instance();
    $plugin->run();
}
run_training_manager();
