<?php
/**
 * Plugin Name: City Pages Generator - Groq AI Integration
 * Description: Intégration de Groq AI pour la génération de contenu unique pour les pages ville
 * Version: 1.0.0
 * Author: AL Métallerie
 */

if (!defined('ABSPATH')) {
    exit;
}

// Constantes
define('CPG_GROQ_VERSION', '1.0.0');
define('CPG_GROQ_PATH', plugin_dir_path(__FILE__));
define('CPG_GROQ_URL', plugin_dir_url(__FILE__));

// Inclure les classes nécessaires
require_once CPG_GROQ_PATH . 'includes/class-groq-integration.php';
require_once CPG_GROQ_PATH . 'groq-integration-v2.php';

// Initialiser les hooks
add_action('plugins_loaded', function() {
    CPG_Automation_Hooks::get_instance();
});

// Ajouter la page de réglages
add_action('admin_menu', function() {
    add_submenu_page(
        'edit.php?post_type=city_page',
        'Groq AI',
        'Groq AI',
        'manage_options',
        'cpg-groq-settings',
        function() {
            require_once CPG_GROQ_PATH . 'admin/views/settings-groq.php';
        }
    );
});

// Filtrer pour utiliser le générateur Groq si activé
add_filter('cpg_get_content_generator', function($generator, $city_data) {
    $settings = get_option('cpg_settings', []);
    
    if (isset($settings['use_groq']) && $settings['use_groq']) {
        return new CPG_Content_Generator_Groq($city_data);
    }
    
    return $generator;
}, 10, 2);

// Ajouter le JS pour l'aperçu
add_action('admin_enqueue_scripts', function($hook) {
    if (strpos($hook, 'city-pages-generator') !== false) {
        wp_enqueue_script('cpg-groq-preview', CPG_GROQ_URL . 'assets/js/preview.js', array('jquery'), CPG_GROQ_VERSION, true);
        wp_localize_script('cpg-groq-preview', 'cpgGroq', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cpg_preview_nonce')
        ));
    }
});

// Activer le système
register_activation_hook(__FILE__, function() {
    // Ajouter les options par défaut
    $settings = get_option('cpg_settings', []);
    $settings['use_groq'] = 0;
    $settings['groq_temperature'] = 0.7;
    $settings['groq_persona'] = 'artisan_expert';
    $settings['generate_on_create'] = 1;
    $settings['regenerate_faq_on_realisation'] = 1;
    $settings['show_preview'] = 1;
    update_option('cpg_settings', $settings);
});
