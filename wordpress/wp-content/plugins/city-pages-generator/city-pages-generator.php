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
 * Charger les fichiers du plugin
 */
function cpg_load_files() {
    // Fichiers includes
    $includes = array(
        'includes/class-post-type.php',
        'includes/class-taxonomy.php',
        'includes/class-metaboxes.php',
        'includes/class-content-generator.php',
        'includes/class-seo-handler.php',
        'includes/class-template-loader.php',
        'includes/class-realisation-integration.php',
        'public/class-public.php',
    );
    
    foreach ($includes as $file) {
        $filepath = CPG_PLUGIN_DIR . $file;
        if (file_exists($filepath)) {
            require_once $filepath;
        }
    }
    
    // Fichiers admin
    if (is_admin()) {
        $admin_files = array(
            'admin/class-admin.php',
            'admin/class-settings.php',
        );
        
        foreach ($admin_files as $file) {
            $filepath = CPG_PLUGIN_DIR . $file;
            if (file_exists($filepath)) {
                require_once $filepath;
            }
        }
    }
}
add_action('plugins_loaded', 'cpg_load_files', 10);

/**
 * Initialiser le plugin
 */
function cpg_init() {
    // Enregistrer le CPT
    if (class_exists('CPG_Post_Type')) {
        CPG_Post_Type::get_instance();
    }
    
    // Enregistrer les taxonomies
    if (class_exists('CPG_Taxonomy')) {
        CPG_Taxonomy::get_instance();
    }
    
    // Metaboxes
    if (class_exists('CPG_Metaboxes')) {
        CPG_Metaboxes::get_instance();
    }
    
    // Template Loader
    if (class_exists('CPG_Template_Loader')) {
        CPG_Template_Loader::get_instance();
    }
    
    // SEO Handler
    if (class_exists('CPG_SEO_Handler')) {
        CPG_SEO_Handler::get_instance();
    }
    
    // Realisation Integration
    if (class_exists('CPG_Realisation_Integration')) {
        CPG_Realisation_Integration::get_instance();
    }
    
    // Admin
    if (is_admin()) {
        if (class_exists('CPG_Admin')) {
            CPG_Admin::get_instance();
        }
        if (class_exists('CPG_Settings')) {
            CPG_Settings::get_instance();
        }
    }
    
    // Public
    if (class_exists('CPG_Public')) {
        CPG_Public::get_instance();
    }
}
add_action('init', 'cpg_init', 10);

/**
 * Activation du plugin
 */
function cpg_activate() {
    // Charger les classes nécessaires
    require_once CPG_PLUGIN_DIR . 'includes/class-post-type.php';
    require_once CPG_PLUGIN_DIR . 'includes/class-taxonomy.php';
    
    // Enregistrer le CPT et taxonomies
    if (class_exists('CPG_Post_Type')) {
        $post_type = new CPG_Post_Type();
        $post_type->register_post_type();
    }
    
    if (class_exists('CPG_Taxonomy')) {
        $taxonomy = new CPG_Taxonomy();
        $taxonomy->register_taxonomy();
    }
    
    // Créer les options par défaut
    $defaults = array(
        'company_name' => 'AL Métallerie & Soudure',
        'workshop_city' => 'Peschadoires',
        'workshop_address' => '14 route de Maringues, 63920 Peschadoires',
        'phone' => '06 73 33 35 32',
        'phone_international' => '+33673333532',
        'email' => 'contact@al-metallerie.fr',
        'company_description' => 'Artisan métallier ferronnier depuis plus de 15 ans.',
        'google_maps_api_key' => '',
        'default_radius_km' => 20,
    );

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
