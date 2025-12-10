<?php
/**
 * Chargeur de templates pour les pages ville
 *
 * @package CityPagesGenerator
 */

if (!defined('ABSPATH')) {
    exit;
}

class CPG_Template_Loader {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_filter('single_template', [$this, 'load_single_template']);
        add_filter('archive_template', [$this, 'load_archive_template']);
        add_filter('the_content', [$this, 'wrap_content'], 20);
    }

    /**
     * Charger le template single
     */
    public function load_single_template($template) {
        if (!is_singular('city_page')) {
            return $template;
        }

        // Chercher d'abord dans le thème
        $theme_template = locate_template(['single-city_page.php']);
        if ($theme_template) {
            return $theme_template;
        }

        // Sinon utiliser le template du plugin
        $plugin_template = CPG_PLUGIN_DIR . 'templates/single-city_page.php';
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }

        return $template;
    }

    /**
     * Charger le template archive
     */
    public function load_archive_template($template) {
        if (!is_post_type_archive('city_page')) {
            return $template;
        }

        // Chercher d'abord dans le thème
        $theme_template = locate_template(['archive-city_page.php']);
        if ($theme_template) {
            return $theme_template;
        }

        // Sinon utiliser le template du plugin
        $plugin_template = CPG_PLUGIN_DIR . 'templates/archive-city_page.php';
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }

        return $template;
    }

    /**
     * Envelopper le contenu avec les classes CSS
     */
    public function wrap_content($content) {
        if (!is_singular('city_page')) {
            return $content;
        }

        // Ajouter le fil d'Ariane au début
        $breadcrumb = do_shortcode('[cpg_breadcrumb]');

        // Envelopper le contenu
        $wrapped = '<div class="cpg-city-page-content">';
        $wrapped .= $breadcrumb;
        $wrapped .= '<div class="cpg-content-inner">';
        $wrapped .= $content;
        $wrapped .= '</div>';
        $wrapped .= '</div>';

        return $wrapped;
    }
}
