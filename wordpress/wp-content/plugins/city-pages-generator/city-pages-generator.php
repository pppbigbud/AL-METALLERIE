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
        $this->load_dependencies();
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
        // Activation/Désactivation
        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);

        // Initialisation
        add_action('init', [$this, 'init']);
        add_action('plugins_loaded', [$this, 'load_textdomain']);
    }

    /**
     * Initialisation du plugin
     */
    public function init() {
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

    /**
     * Activation du plugin
     */
    public function activate() {
        // Créer les options par défaut
        $this->create_default_options();
        
        // Enregistrer le CPT pour flush les rewrite rules
        CPG_Post_Type::get_instance()->register_post_type();
        CPG_Taxonomy::get_instance()->register_taxonomy();
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Désactivation du plugin
     */
    public function deactivate() {
        flush_rewrite_rules();
    }

    /**
     * Créer les options par défaut
     */
    private function create_default_options() {
        $defaults = [
            'company_name' => 'AL Métallerie & Soudure',
            'workshop_city' => 'Peschadoires',
            'workshop_address' => '14 route de Maringues, 63920 Peschadoires',
            'phone' => '06 73 33 35 32',
            'phone_international' => '+33673333532',
            'email' => 'contact@al-metallerie.fr',
            'company_description' => 'Artisan métallier ferronnier depuis plus de 15 ans, AL Métallerie & Soudure réalise tous vos projets de métallerie sur mesure. Fabrication artisanale dans notre atelier de Peschadoires, intervention dans tout le Puy-de-Dôme et l\'Auvergne.',
            'services' => [
                'portails' => [
                    'enabled' => true,
                    'name' => 'Portails sur mesure',
                    'icon' => 'gate',
                    'description' => 'Portails coulissants et battants en acier, aluminium ou fer forgé.'
                ],
                'garde_corps' => [
                    'enabled' => true,
                    'name' => 'Garde-corps et rambardes',
                    'icon' => 'railing',
                    'description' => 'Garde-corps intérieurs et extérieurs, rambardes d\'escalier.'
                ],
                'escaliers' => [
                    'enabled' => true,
                    'name' => 'Escaliers métalliques',
                    'icon' => 'stairs',
                    'description' => 'Escaliers droits, quart tournant, hélicoïdaux en métal.'
                ],
                'grilles' => [
                    'enabled' => true,
                    'name' => 'Grilles de sécurité',
                    'icon' => 'grid',
                    'description' => 'Grilles de défense, grilles de fenêtre, protection anti-intrusion.'
                ],
                'pergolas' => [
                    'enabled' => true,
                    'name' => 'Pergolas et structures',
                    'icon' => 'pergola',
                    'description' => 'Pergolas bioclimatiques, auvents, structures métalliques extérieures.'
                ],
                'verrieres' => [
                    'enabled' => true,
                    'name' => 'Verrières d\'intérieur',
                    'icon' => 'window',
                    'description' => 'Verrières atelier, cloisons vitrées, séparations design.'
                ],
                'ferronnerie' => [
                    'enabled' => true,
                    'name' => 'Ferronnerie d\'art',
                    'icon' => 'art',
                    'description' => 'Créations artistiques, pièces décoratives, restauration.'
                ],
                'mobilier' => [
                    'enabled' => true,
                    'name' => 'Mobilier métallique',
                    'icon' => 'furniture',
                    'description' => 'Tables, étagères, consoles, mobilier sur mesure en métal.'
                ],
            ],
            'google_maps_api_key' => '',
            'default_radius_km' => 20,
            'sections_order' => ['intro', 'services', 'realisations', 'why_us', 'zone', 'contact', 'faq'],
            'sections_enabled' => [
                'intro' => true,
                'services' => true,
                'realisations' => true,
                'why_us' => true,
                'zone' => true,
                'contact' => true,
                'faq' => true,
            ],
        ];

        if (!get_option('cpg_settings')) {
            add_option('cpg_settings', $defaults);
        }
    }
}

/**
 * Fonction d'initialisation du plugin
 */
function cpg_init() {
    return City_Pages_Generator::get_instance();
}

// Démarrer le plugin
cpg_init();
