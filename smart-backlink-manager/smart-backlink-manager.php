<?php
/**
 * Plugin Name: Smart Backlink Manager
 * Plugin URI: https://al-metallerie.fr
 * Description: Gestion intelligente des backlinks et liens internes pour AL Métallerie - Soudure, Serrurerie, Métallerie à Clermont-Ferrand (Puy-de-Dôme, Auvergne)
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Author: AL Métallerie
 * Author URI: https://al-metallerie.fr
 * License: GPL v2 or later
 * Text Domain: smart-backlink-manager
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit('Accès direct interdit');
}

define('SBM_VERSION', '1.0.0');
define('SBM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SBM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SBM_PLUGIN_BASENAME', plugin_basename(__FILE__));

register_activation_hook(__FILE__, 'sbm_activate_plugin');
function sbm_activate_plugin(): void {
    require_once SBM_PLUGIN_DIR . 'includes/class-activator.php';
    SBM_Activator::activate();
}

register_uninstall_hook(__FILE__, 'sbm_uninstall_plugin');
function sbm_uninstall_plugin(): void {
    require_once SBM_PLUGIN_DIR . 'uninstall.php';
    SBM_Uninstaller::uninstall();
}

require_once SBM_PLUGIN_DIR . 'includes/class-main.php';

function sbm_init(): void {
    $sbm = new SBM_Main();
    $sbm->run();
}
add_action('plugins_loaded', 'sbm_init');
