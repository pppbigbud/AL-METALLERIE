<?php
/**
 * Plugin Name: City Pages Generator
 * Plugin URI: https://al-metallerie.fr
 * Description: Générateur de pages ville optimisées SEO local pour AL Métallerie
 * Version: 1.1.0
 * Author: AL Métallerie
 * Author URI: https://al-metallerie.fr
 * License: GPL-2.0+
 * Text Domain: city-pages-generator
 */

if (!defined('ABSPATH')) {
    exit;
}

define('CPG_VERSION', '1.1.0');
define('CPG_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CPG_PLUGIN_URL', plugin_dir_url(__FILE__));

// Charger les fichiers
add_action('plugins_loaded', 'cpg_load_files');
function cpg_load_files() {
    $files = array(
        'includes/functions-content.php',
        'includes/functions-seo.php',
        'includes/functions-realisation-integration.php',
    );
    
    foreach ($files as $file) {
        $path = CPG_PLUGIN_DIR . $file;
        if (file_exists($path)) {
            require_once $path;
        }
    }
    
    if (is_admin()) {
        $admin_file = CPG_PLUGIN_DIR . 'includes/functions-admin.php';
        if (file_exists($admin_file)) {
            require_once $admin_file;
        }
    }
}

// Enregistrer le CPT
add_action('init', 'cpg_register_cpt');
function cpg_register_cpt() {
    register_post_type('city_page', array(
        'labels' => array(
            'name'          => 'Pages Ville',
            'singular_name' => 'Page Ville',
            'menu_name'     => 'Pages Ville',
            'add_new'       => 'Ajouter',
            'add_new_item'  => 'Ajouter une page',
            'edit_item'     => 'Modifier',
            'view_item'     => 'Voir',
            'search_items'  => 'Rechercher',
            'not_found'     => 'Aucune page trouvée',
        ),
        'public'        => true,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'menu_position' => 26,
        'menu_icon'     => 'dashicons-location-alt',
        'supports'      => array('title', 'editor', 'thumbnail', 'excerpt'),
        'has_archive'   => 'metallier-auvergne',
        'rewrite'       => array('slug' => 'metallier', 'with_front' => false),
        'show_in_rest'  => true,
    ));
}

// Activation
register_activation_hook(__FILE__, 'cpg_activate');
function cpg_activate() {
    cpg_register_cpt();
    flush_rewrite_rules();
}

// Désactivation
register_deactivation_hook(__FILE__, 'cpg_deactivate');
function cpg_deactivate() {
    flush_rewrite_rules();
}
