<?php
/**
 * Intégration avec les autres plugins
 */

if (!defined('ABSPATH')) {
    exit;
}

class AICG_Integration {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Intégration avec city-pages-generator
        add_action('cpg_before_generate_content', array($this, 'enhance_city_content'), 10, 2);
        
        // Intégration avec almetal-analytics (SEO)
        add_filter('almetal_seo_improvement_suggestion', array($this, 'generate_seo_improvement'), 10, 2);
        
        // Intégration avec le CPT realisation
        add_action('save_post_realisation', array($this, 'auto_generate_realisation_content'), 10, 2);
        
        // Ajouter les métaboxes
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        
        // AJAX pour génération rapide
        add_action('wp_ajax_aicg_quick_generate', array($this, 'ajax_quick_generate'));
    }
    
    /**
     * Améliorer le contenu des pages villes
     */
    public function enhance_city_content($city_data, $post_id) {
        if (!class_exists('AICG_AI_Generator')) {
            return $city_data;
        }
        
        $generator = AICG_AI_Generator::get_instance();
        $templates = AICG_Content_Templates::get_instance();
        
        // Générer chaque section avec IA
        $prompt = $templates->get_city_page_prompt(array(
            'city' => $city_data['city_name'],
            'department' => $city_data['department'],
            'population' => $city_data['population'],
            'specifics' => $city_data['local_specifics'],
            'services' => 'soudure, ferronnerie, portails, escaliers, garde-corps, verrières'
        ));
        
        $content = $generator->generate_content($prompt);
        
        if (!is_wp_error($content)) {
            // Parser le contenu généré et l'assigner aux sections
            $city_data = $this->parse_generated_content($content, $city_data);
        }
        
        return $city_data;
    }
    
    /**
     * Générer des améliorations SEO
     */
    public function generate_seo_improvement($suggestions, $post_id) {
        if (!class_exists('AICG_AI_Generator')) {
            return $suggestions;
        }
        
        $post = get_post($post_id);
        $generator = AICG_AI_Generator::get_instance();
        $templates = AICG_Content_Templates::get_instance();
        
        // Générer meta description
        if (empty($post->post_excerpt) || strlen($post->post_excerpt) < 150) {
            $prompt = $templates->get_meta_description_prompt(array(
                'type' => get_post_type($post_id),
                'subject' => $post->post_title,
                'location' => 'Thiers'
            ));
            
            $meta_desc = $generator->generate_content($prompt);
            
            if (!is_wp_error($meta_desc)) {
                $suggestions['meta_description'] = array(
                    'type' => 'meta_description',
                    'description' => 'Meta description générée par IA',
                    'priority' => 'high',
                    'suggested' => $meta_desc
                );
            }
        }
        
        // Générer amélioration de contenu
        if (strlen($post->post_content) < 300) {
            $prompt = $templates->get_content_improvement_prompt(array(
                'content' => $post->post_content,
                'target_length' => 200,
                'keywords' => array('métallerie', 'Thiers', 'soudure', 'qualité')
            ));
            
            $improved_content = $generator->generate_content($prompt);
            
            if (!is_wp_error($improved_content)) {
                $suggestions['content_enhancement'] = array(
                    'type' => 'content_enhancement',
                    'description' => 'Contenu enrichi par IA',
                    'priority' => 'medium',
                    'suggested' => $improved_content
                );
            }
        }
        
        return $suggestions;
    }
    
    /**
     * Générer automatiquement le contenu d'une réalisation
     */
    public function auto_generate_realisation_content($post_id, $post) {
        // Vérifier si le contenu doit être généré
        if (get_post_meta($post_id, '_aicg_generated', true) || !empty($post->post_content)) {
            return;
        }
        
        if (!class_exists('AICG_AI_Generator')) {
            return;
        }
        
        // Récupérer les données du formulaire
        $type = get_post_meta($post_id, '_type_realisation', true);
        $materials = get_post_meta($post_id, '_materiaux', true);
        $client = get_post_meta($post_id, '_client', true);
        
        $generator = AICG_AI_Generator::get_instance();
        $templates = AICG_Content_Templates::get_instance();
        
        $prompt = $templates->get_realisation_prompt(array(
            'type' => $type,
            'materials' => $materials,
            'client' => $client,
            'date' => date('Y')
        ));
        
        $content = $generator->generate_content($prompt);
        
        if (!is_wp_error($content)) {
            // Mettre à jour le post
            wp_update_post(array(
                'ID' => $post_id,
                'post_content' => $content
            ));
            
            // Marquer comme généré
            update_post_meta($post_id, '_aicg_generated', true);
            update_post_meta($post_id, '_aicg_generation_date', current_time('mysql'));
        }
    }
    
    /**
     * Ajouter les metaboxes
     */
    public function add_meta_boxes() {
        // Pour les réalisations
        add_meta_box(
            'aicg-realisation-generator',
            'Génération IA',
            array($this, 'render_realisation_metabox'),
            'realisation',
            'side',
            'high'
        );
        
        // Pour les pages
        add_meta_box(
            'aicg-content-generator',
            'Génération de contenu IA',
            array($this, 'render_content_metabox'),
            'page',
            'side',
            'high'
        );
    }
    
    /**
     * Metabox pour les réalisations
     */
    public function render_realisation_metabox($post) {
        ?>
        <div class="aicg-metabox">
            <p>
                <label>Type de réalisation:</label><br>
                <input type="text" id="aicg-type" value="<?php echo get_post_meta($post->ID, '_type_realisation', true); ?>" class="widefat">
            </p>
            <p>
                <label>Matériaux:</label><br>
                <input type="text" id="aicg-materials" value="<?php echo get_post_meta($post->ID, '_materiaux', true); ?>" class="widefat">
            </p>
            <p>
                <label>Client:</label><br>
                <input type="text" id="aicg-client" value="<?php echo get_post_meta($post->ID, '_client', true); ?>" class="widefat">
            </p>
            <p>
                <button type="button" class="button button-primary" id="generate-realisation">
                    Générer avec l'IA
                </button>
                <span class="spinner" style="display:none;"></span>
            </p>
            <p class="aicg-result" style="display:none;"></p>
        </div>
        
        <script>
        jQuery('#generate-realisation').on('click', function() {
            var btn = jQuery(this);
            var spinner = btn.next('.spinner');
            var result = btn.parent().next('.aicg-result');
            
            btn.prop('disabled', true);
            spinner.show();
            
            jQuery.post(aicg_ajax.ajax_url, {
                action: 'aicg_quick_generate',
                type: 'realisation',
                data: {
                    type: jQuery('#aicg-type').val(),
                    materials: jQuery('#aicg-materials').val(),
                    client: jQuery('#aicg-client').val()
                },
                nonce: aicg_ajax.nonce
            }, function(response) {
                spinner.hide();
                btn.prop('disabled', false);
                
                if (response.success) {
                    result.html('<strong>Contenu généré!</strong><br><a href="#" id="apply-content">Appliquer</a>').show();
                    jQuery('#aicg-temp-content').val(response.data.content);
                } else {
                    result.html('<strong>Erreur:</strong> ' + response.data).show();
                }
            });
        });
        
        jQuery(document).on('click', '#apply-content', function(e) {
            e.preventDefault();
            var content = jQuery('#aicg-temp-content').val();
            
            // Insérer dans l'éditeur
            if (typeof tinyMCE !== 'undefined' && tinyMCE.activeEditor) {
                tinyMCE.activeEditor.setContent(content);
            } else {
                jQuery('#content').val(content);
            }
        });
        </script>
        
        <input type="hidden" id="aicg-temp-content">
        <?php
    }
    
    /**
     * Metabox pour le contenu
     */
    public function render_content_metabox($post) {
        ?>
        <div class="aicg-metabox">
            <p>
                <label>Type d'amélioration:</label><br>
                <select id="aicg-improvement-type" class="widefat">
                    <option value="meta_description">Meta description</option>
                    <option value="content_improvement">Améliorer le contenu</option>
                    <option value="introduction">Générer une introduction</option>
                    <option value="conclusion">Générer une conclusion</option>
                </select>
            </p>
            <p>
                <button type="button" class="button" id="generate-improvement">
                    Générer
                </button>
                <span class="spinner" style="display:none;"></span>
            </p>
            <div id="aicg-improvement-result"></div>
        </div>
        
        <script>
        jQuery('#generate-improvement').on('click', function() {
            // Logique de génération
        });
        </script>
        <?php
    }
    
    /**
     * AJAX: Génération rapide
     */
    public function ajax_quick_generate() {
        check_ajax_referer('aicg_nonce', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_die('Permission denied');
        }
        
        $type = $_POST['type'];
        $data = $_POST['data'];
        
        if (!class_exists('AICG_AI_Generator')) {
            wp_send_json_error('Plugin AI non disponible');
        }
        
        $generator = AICG_AI_Generator::get_instance();
        $templates = AICG_Content_Templates::get_instance();
        
        switch ($type) {
            case 'realisation':
                $prompt = $templates->get_realisation_prompt($data);
                break;
                
            default:
                wp_send_json_error('Type non supporté');
        }
        
        $content = $generator->generate_content($prompt);
        
        if (is_wp_error($content)) {
            wp_send_json_error($content->get_error_message());
        }
        
        wp_send_json_success(array('content' => $content));
    }
    
    /**
     * Parser le contenu généré
     */
    private function parse_generated_content($content, $city_data) {
        // Extraction des sections avec regex
        $sections = array();
        
        // Introduction
        if (preg_match('/Introduction\s*\n?(.*?)(?=\n\n|\n#|$)/s', $content, $matches)) {
            $city_data['introduction'] = trim($matches[1]);
        }
        
        // Services
        if (preg_match('/Services.*?\n?(.*?)(?=\n\n|\n#|$)/s', $content, $matches)) {
            $city_data['services'] = trim($matches[1]);
        }
        
        // Réalisations
        if (preg_match('/Réalisation.*?\n?(.*?)(?=\n\n|\n#|$)/s', $content, $matches)) {
            $city_data['realisations'] = trim($matches[1]);
        }
        
        // Pourquoi nous choisir
        if (preg_match('/Pourquoi.*?\n?(.*?)(?=\n\n|\n#|$)/s', $content, $matches)) {
            $city_data['why_choose'] = trim($matches[1]);
        }
        
        // Zone d'intervention
        if (preg_match('/Zone.*?\n?(.*?)(?=\n\n|\n#|$)/s', $content, $matches)) {
            $city_data['intervention_area'] = trim($matches[1]);
        }
        
        return $city_data;
    }
}
