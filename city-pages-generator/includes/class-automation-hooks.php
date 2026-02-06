<?php
/**
 * Hooks d'automatisation pour la génération avec Groq AI
 * 
 * @package CityPagesGenerator
 */

if (!defined('ABSPATH')) {
    exit;
}

class CPG_Automation_Hooks {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('cpg_city_page_created', array($this, 'on_city_page_created'), 10, 2);
        add_action('save_post_realisation', array($this, 'on_realisation_saved'), 10, 3);
        add_action('wp_ajax_cpg_preview_groq_content', array($this, 'ajax_preview_content'));
    }
    
    /**
     * Lors de la création d'une nouvelle page ville
     */
    public function on_city_page_created($post_id, $city_data) {
        $settings = get_option('cpg_settings', []);
        
        // Vérifier si Groq est activé et la génération auto est activée
        if (!isset($settings['use_groq']) || !$settings['use_groq']) {
            return;
        }
        
        if (!isset($settings['generate_on_create']) || !$settings['generate_on_create']) {
            return;
        }
        
        // Vérifier que Groq est configuré
        $groq = CPG_Groq_Integration::get_instance();
        if (!$groq->is_configured()) {
            error_log('CPG: Groq non configuré, impossible de générer le contenu');
            return;
        }
        
        // Générer le contenu avec Groq
        $generator = new CPG_Content_Generator_Groq($city_data);
        $content = $generator->generate();
        
        if (isset($settings['show_preview']) && $settings['show_preview']) {
            // Sauvegarder en brouillon pour aperçu
            wp_update_post(array(
                'ID' => $post_id,
                'post_content' => $content,
                'post_status' => 'draft'
            ));
            
            // Ajouter une meta pour indiquer que c'est un aperçu
            update_post_meta($post_id, '_cpg_groq_preview', 1);
            
            // Notifier l'admin
            wp_mail(get_option('admin_email'), 
                'Nouvelle page ville générée - Aperçu disponible',
                "La page ville a été générée avec Groq AI et est en attente de validation :\n" . 
                get_edit_post_link($post_id)
            );
        } else {
            // Publier directement
            wp_update_post(array(
                'ID' => $post_id,
                'post_content' => $content
            ));
            
            // Notifier l'admin
            wp_mail(get_option('admin_email'), 
                'Nouvelle page ville générée et publiée',
                "La page ville a été générée avec Groq AI et publiée :\n" . 
                get_permalink($post_id)
            );
        }
    }
    
    /**
     * Lors de la sauvegarde d'une réalisation
     */
    public function on_realisation_saved($post_id, $post, $update) {
        // Seulement lors de la création
        if ($update) {
            return;
        }
        
        $settings = get_option('cpg_settings', []);
        
        // Vérifier si la régénération FAQ est activée
        if (!isset($settings['regenerate_faq_on_realisation']) || !$settings['regenerate_faq_on_realisation']) {
            return;
        }
        
        // Récupérer la ville associée à la réalisation
        $villes = wp_get_post_terms($post_id, 'villes');
        if (empty($villes)) {
            return;
        }
        
        $ville = $villes[0];
        $city_name = $ville->name;
        
        // Trouver la page ville correspondante
        $city_pages = get_posts(array(
            'post_type' => 'city_page',
            'meta_query' => array(
                array(
                    'key' => 'city_name',
                    'value' => $city_name,
                    'compare' => '='
                )
            ),
            'posts_per_page' => 1
        ));
        
        if (empty($city_pages)) {
            return;
        }
        
        $city_page = $city_pages[0];
        
        // Récupérer les données de la ville
        $city_data = array(
            'city_name' => get_post_meta($city_page->ID, 'city_name', true),
            'department' => get_post_meta($city_page->ID, 'department', true),
            'postal_code' => get_post_meta($city_page->ID, 'postal_code', true),
            'distance_km' => get_post_meta($city_page->ID, 'distance_km', true),
            'travel_time' => get_post_meta($city_page->ID, 'travel_time', true),
            'local_specifics' => get_post_meta($city_page->ID, 'local_specifics', true)
        );
        
        // Générer une nouvelle FAQ
        $groq = CPG_Groq_Integration::get_instance();
        if (!$groq->is_configured()) {
            return;
        }
        
        $temperature = isset($settings['groq_temperature']) ? floatval($settings['groq_temperature']) : 0.7;
        $new_faq = $groq->generate_city_faq($city_data, $temperature);
        
        if (!is_wp_error($new_faq)) {
            // Mettre à jour le contenu de la page
            $current_content = $city_page->post_content;
            
            // Remplacer la section FAQ
            if (preg_match('/<!-- Section FAQ.*?-->(.*?)(?=\n\n|$)/s', $current_content, $matches)) {
                $new_section = "<!-- Section FAQ (Groq AI) -->\n<h2>Questions fréquentes</h2>\n" . nl2br($new_faq);
                $updated_content = str_replace($matches[0], $new_section, $current_content);
                
                wp_update_post(array(
                    'ID' => $city_page->ID,
                    'post_content' => $updated_content
                ));
                
                // Notifier l'admin
                wp_mail(get_option('admin_email'), 
                    'FAQ mise à jour - ' . $city_name,
                    "La FAQ de la page {$city_name} a été mise à jour suite à la nouvelle réalisation :\n" . 
                    get_permalink($city_page->ID)
                );
            }
        }
    }
    
    /**
     * AJAX pour prévisualiser le contenu
     */
    public function ajax_preview_content() {
        check_ajax_referer('cpg_preview_nonce', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_die(__('Permission denied'));
        }
        
        $city_name = sanitize_text_field($_POST['city_name']);
        $temperature = floatval($_POST['temperature']);
        
        // Données de test
        $city_data = array(
            'city_name' => $city_name,
            'department' => 'Puy-de-Dôme',
            'postal_code' => '63000',
            'distance_km' => '35',
            'travel_time' => '30 minutes',
            'local_specifics' => 'Centre ville, quartiers résidentiels'
        );
        
        $groq = CPG_Groq_Integration::get_instance();
        if (!$groq->is_configured()) {
            wp_send_json_error('Groq non configuré');
        }
        
        // Générer chaque section
        $sections = array();
        $sections['intro'] = $groq->generate_city_intro($city_data, $temperature);
        $sections['why_us'] = $groq->generate_why_us_section($city_name, $temperature);
        $sections['faq'] = $groq->generate_city_faq($city_data, $temperature);
        
        wp_send_json_success($sections);
    }
}
