<?php
/**
 * Plugin Name: City Pages Generator
 * Plugin URI: https://al-metallerie.fr
 * Description: Générateur de pages ville optimisées SEO local pour AL Métallerie
 * Version: 1.0.0
 * Author: AL Métallerie
 * Author URI: https://al-metallerie.fr
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: city-pages-generator
 * Domain Path: /languages
 *
 * @package CityPagesGenerator
 */

// Sécurité : empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

// Constantes du plugin
define('CPG_VERSION', '1.0.0');
define('CPG_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CPG_PLUGIN_URL', plugin_dir_url(__FILE__));
define('CPG_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Classe principale du plugin
 */
final class City_Pages_Generator {

    /**
     * Instance unique (Singleton)
     */
    private static $instance = null;

    /**
     * Obtenir l'instance unique
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructeur privé
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Charger les dépendances
     */
    private function load_dependencies() {
        // Classes principales
        require_once CPG_PLUGIN_DIR . 'includes/class-post-type.php';
        require_once CPG_PLUGIN_DIR . 'includes/class-taxonomy.php';
        require_once CPG_PLUGIN_DIR . 'includes/class-metaboxes.php';
        require_once CPG_PLUGIN_DIR . 'includes/class-content-generator.php';
        require_once CPG_PLUGIN_DIR . 'includes/class-seo-handler.php';
        require_once CPG_PLUGIN_DIR . 'includes/class-template-loader.php';
        require_once CPG_PLUGIN_DIR . 'includes/class-realisation-integration.php';
        
        // Admin
        if (is_admin()) {
            require_once CPG_PLUGIN_DIR . 'admin/class-admin.php';
            require_once CPG_PLUGIN_DIR . 'admin/class-settings.php';
            require_once CPG_PLUGIN_DIR . 'admin/class-city-list-table.php';
        }
        
        // Public
        require_once CPG_PLUGIN_DIR . 'public/class-public.php';
    }

    /**
     * Initialiser les hooks
     */
    private function init_hooks() {
        // Charger les dépendances après plugins_loaded
        add_action('plugins_loaded', [$this, 'load_dependencies'], 5);
        add_action('plugins_loaded', [$this, 'load_textdomain'], 10);
        
        // Initialisation
        add_action('init', [$this, 'init'], 5);
    }

    /**
     * Initialisation du plugin
     */
    public function init() {
        // Vérifier que les classes existent
        if (!class_exists('CPG_Post_Type')) {
            return;
        }
        
        // Enregistrer le CPT et la taxonomie
        CPG_Post_Type::get_instance();
        CPG_Taxonomy::get_instance();
        CPG_Metaboxes::get_instance();
        CPG_Template_Loader::get_instance();
        CPG_SEO_Handler::get_instance();
        CPG_Realisation_Integration::get_instance();
        
        // Admin
        if (is_admin()) {
            CPG_Admin::get_instance();
            CPG_Settings::get_instance();
        }
        
        // Public
        CPG_Public::get_instance();
    }

    /**
     * Charger les traductions
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'city-pages-generator',
            false,
            dirname(CPG_PLUGIN_BASENAME) . '/languages/'
        );
    }
}

/**
 * Fonction d'initialisation du plugin
 */
function cpg_init() {
    return City_Pages_Generator::get_instance();
}

/**
 * Activation du plugin
 */
function cpg_activate() {
    // Charger les classes nécessaires
    require_once CPG_PLUGIN_DIR . 'includes/class-post-type.php';
    require_once CPG_PLUGIN_DIR . 'includes/class-taxonomy.php';
    
    // Enregistrer le CPT et taxonomies
    CPG_Post_Type::get_instance()->register_post_type();
    CPG_Taxonomy::get_instance()->register_taxonomy();
    
    // Créer les options par défaut
    $defaults = [
        'company_name' => 'AL Métallerie & Soudure',
        'workshop_city' => 'Peschadoires',
        'workshop_address' => '14 route de Maringues, 63920 Peschadoires',
        'phone' => '06 73 33 35 32',
        'phone_international' => '+33673333532',
        'email' => 'contact@al-metallerie.fr',
        'company_description' => 'Artisan métallier ferronnier depuis plus de 15 ans.',
        'google_maps_api_key' => '',
        'default_radius_km' => 20,
    ];

    if (!get_option('cpg_settings')) {
        add_option('cpg_settings', $defaults);
    }
    
    // Flush rewrite rules
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'cpg_activate');

/**
 * Désactivation du plugin
 */
function cpg_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'cpg_deactivate');

// Démarrer le plugin
cpg_init();
