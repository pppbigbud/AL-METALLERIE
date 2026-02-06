<?php
/**
 * Plugin Name: AI Content Generator for AL-Metallerie
 * Description: Génération de contenu unique par IA (Ollama) pour les réalisations, pages villes et SEO
 * Version: 1.0.0
 * Author: AL-Metallerie
 * Text Domain: ai-content-generator
 */

if (!defined('ABSPATH')) {
    exit;
}

define('AICG_PATH', plugin_dir_path(__FILE__));
define('AICG_URL', plugin_dir_url(__FILE__));

// Inclure les classes principales
require_once AICG_PATH . 'includes/class-ai-generator.php';
require_once AICG_PATH . 'includes/class-simple-generator.php';
require_once AICG_PATH . 'includes/class-content-templates.php';
require_once AICG_PATH . 'includes/class-admin.php';
require_once AICG_PATH . 'includes/class-integration.php';

// Initialiser le plugin
function aicg_init() {
    // Vérifier si Ollama est installé
    if (!class_exists('AICG_AI_Generator')) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>AI Content Generator : Ollama n\'est pas installé sur le serveur.</p></div>';
        });
        return;
    }
    
    AICG_AI_Generator::get_instance();
    AICG_Content_Templates::get_instance();
    AICG_Admin::get_instance();
    AICG_Integration::get_instance();
}
add_action('plugins_loaded', 'aicg_init');

// Activation du plugin
register_activation_hook(__FILE__, 'aicg_activate');
function aicg_activate() {
    // Créer les tables nécessaires
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'aicg_content_cache';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        content_hash varchar(64) NOT NULL,
        content_type varchar(50) NOT NULL,
        generated_content longtext NOT NULL,
        model_used varchar(50) NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id),
        UNIQUE KEY content_hash (content_hash),
        KEY content_type (content_type)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    
    // Options par défaut
    add_option('aicg_ollama_url', 'http://localhost:11434');
    add_option('aicg_default_model', 'llama3.1:8b');
    add_option('aicg_temperature', '0.7');
    add_option('aicg_max_tokens', '2000');
}
