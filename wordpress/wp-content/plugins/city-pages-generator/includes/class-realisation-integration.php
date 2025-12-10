<?php
/**
 * Intégration avec les réalisations
 *
 * @package CityPagesGenerator
 */

if (!defined('ABSPATH')) {
    exit;
}

class CPG_Realisation_Integration {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        // Shortcodes
        add_shortcode('cpg_city_realisations', [$this, 'render_city_realisations']);
        add_shortcode('cpg_city_map', [$this, 'render_city_map']);
        add_shortcode('cpg_contact_form', [$this, 'render_contact_form']);
        
        // Ajouter le champ ville aux réalisations
        add_action('add_meta_boxes', [$this, 'add_realisation_city_metabox']);
        add_action('save_post_realisation', [$this, 'save_realisation_city']);
    }

    /**
     * Shortcode pour afficher les réalisations d'une ville
     */
    public function render_city_realisations($atts) {
        $atts = shortcode_atts([
            'city' => '',
            'count' => 6,
            'columns' => 3,
        ], $atts);

        if (empty($atts['city'])) {
            return '';
        }

        $city_slug = sanitize_title($atts['city']);

        // Chercher les réalisations avec cette ville
        $args = [
            'post_type' => 'realisation',
            'posts_per_page' => intval($atts['count']),
            'post_status' => 'publish',
            'tax_query' => [
                [
                    'taxonomy' => 'realisation_city',
                    'field' => 'slug',
                    'terms' => $city_slug,
                ],
            ],
        ];

        $realisations = get_posts($args);

        // Si pas de réalisations pour cette ville, chercher les plus récentes
        if (empty($realisations)) {
            $args = [
                'post_type' => 'realisation',
                'posts_per_page' => intval($atts['count']),
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC',
            ];
            $realisations = get_posts($args);
        }

        if (empty($realisations)) {
            return '<p class="cpg-no-realisations">' . __('Aucune réalisation à afficher pour le moment.', 'city-pages-generator') . '</p>';
        }

        $output = '<div class="cpg-realisations-grid cpg-columns-' . intval($atts['columns']) . '">';

        foreach ($realisations as $realisation) {
            $thumbnail = get_the_post_thumbnail_url($realisation->ID, 'medium_large');
            $title = get_the_title($realisation->ID);
            $permalink = get_permalink($realisation->ID);
            $categories = get_the_terms($realisation->ID, 'type_realisation');
            $category_name = ($categories && !is_wp_error($categories)) ? $categories[0]->name : '';

            $output .= '<div class="cpg-realisation-card">';
            $output .= '<a href="' . esc_url($permalink) . '" class="cpg-realisation-link">';
            
            if ($thumbnail) {
                $output .= '<div class="cpg-realisation-image">';
                $output .= '<img src="' . esc_url($thumbnail) . '" alt="' . esc_attr($title . ' à ' . $atts['city'] . ' - AL Métallerie') . '" loading="lazy">';
                $output .= '</div>';
            }
            
            $output .= '<div class="cpg-realisation-content">';
            if ($category_name) {
                $output .= '<span class="cpg-realisation-category">' . esc_html($category_name) . '</span>';
            }
            $output .= '<h4 class="cpg-realisation-title">' . esc_html($title) . '</h4>';
            $output .= '</div>';
            
            $output .= '</a>';
            $output .= '</div>';
        }

        $output .= '</div>';

        // Lien vers toutes les réalisations
        $archive_link = get_post_type_archive_link('realisation');
        if ($archive_link) {
            $output .= '<div class="cpg-realisations-more">';
            $output .= '<a href="' . esc_url($archive_link) . '" class="cpg-btn cpg-btn-outline">';
            $output .= __('Voir toutes nos réalisations', 'city-pages-generator');
            $output .= '</a>';
            $output .= '</div>';
        }

        return $output;
    }

    /**
     * Shortcode pour afficher la carte Google Maps
     */
    public function render_city_map($atts) {
        $atts = shortcode_atts([
            'city' => '',
            'height' => '400px',
        ], $atts);

        if (empty($atts['city'])) {
            return '';
        }

        $settings = get_option('cpg_settings', []);
        $api_key = isset($settings['google_maps_api_key']) ? $settings['google_maps_api_key'] : '';

        // Si pas de clé API, afficher un lien vers Google Maps
        if (empty($api_key)) {
            $maps_url = 'https://www.google.com/maps/search/' . urlencode($atts['city'] . ', France');
            
            $output = '<div class="cpg-map-placeholder">';
            $output .= '<div class="cpg-map-placeholder-content">';
            $output .= '<span class="cpg-map-icon">📍</span>';
            $output .= '<p>' . sprintf(__('Localisation : %s', 'city-pages-generator'), esc_html($atts['city'])) . '</p>';
            $output .= '<a href="' . esc_url($maps_url) . '" target="_blank" rel="noopener" class="cpg-btn cpg-btn-primary">';
            $output .= __('Voir sur Google Maps', 'city-pages-generator');
            $output .= '</a>';
            $output .= '</div>';
            $output .= '</div>';
            
            return $output;
        }

        // Avec clé API, afficher la carte intégrée
        $embed_url = sprintf(
            'https://www.google.com/maps/embed/v1/place?key=%s&q=%s,France&zoom=12',
            esc_attr($api_key),
            urlencode($atts['city'])
        );

        $output = '<div class="cpg-map-container" style="height: ' . esc_attr($atts['height']) . ';">';
        $output .= '<iframe src="' . esc_url($embed_url) . '" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
        $output .= '</div>';

        return $output;
    }

    /**
     * Shortcode pour le formulaire de contact
     */
    public function render_contact_form($atts) {
        $atts = shortcode_atts([
            'city' => '',
        ], $atts);

        // Vérifier si Contact Form 7 est actif
        if (shortcode_exists('contact-form-7')) {
            // Utiliser le formulaire CF7 existant (à adapter selon l'ID du formulaire)
            return do_shortcode('[contact-form-7 id="contact-form" title="Contact"]');
        }

        // Formulaire simple par défaut
        $settings = get_option('cpg_settings', []);
        $email = isset($settings['email']) ? $settings['email'] : 'contact@al-metallerie.fr';

        $output = '<form class="cpg-contact-form" method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
        $output .= wp_nonce_field('cpg_contact_form', 'cpg_contact_nonce', true, false);
        $output .= '<input type="hidden" name="action" value="cpg_submit_contact">';
        $output .= '<input type="hidden" name="cpg_city" value="' . esc_attr($atts['city']) . '">';

        $output .= '<div class="cpg-form-row">';
        $output .= '<div class="cpg-form-group">';
        $output .= '<label for="cpg_name">' . __('Nom', 'city-pages-generator') . ' <span class="required">*</span></label>';
        $output .= '<input type="text" id="cpg_name" name="cpg_name" required>';
        $output .= '</div>';
        $output .= '<div class="cpg-form-group">';
        $output .= '<label for="cpg_email">' . __('Email', 'city-pages-generator') . ' <span class="required">*</span></label>';
        $output .= '<input type="email" id="cpg_email" name="cpg_email" required>';
        $output .= '</div>';
        $output .= '</div>';

        $output .= '<div class="cpg-form-row">';
        $output .= '<div class="cpg-form-group">';
        $output .= '<label for="cpg_phone">' . __('Téléphone', 'city-pages-generator') . '</label>';
        $output .= '<input type="tel" id="cpg_phone" name="cpg_phone">';
        $output .= '</div>';
        $output .= '<div class="cpg-form-group">';
        $output .= '<label for="cpg_service">' . __('Service souhaité', 'city-pages-generator') . '</label>';
        $output .= '<select id="cpg_service" name="cpg_service">';
        $output .= '<option value="">' . __('Sélectionner...', 'city-pages-generator') . '</option>';
        $output .= '<option value="portail">' . __('Portail', 'city-pages-generator') . '</option>';
        $output .= '<option value="garde-corps">' . __('Garde-corps', 'city-pages-generator') . '</option>';
        $output .= '<option value="escalier">' . __('Escalier', 'city-pages-generator') . '</option>';
        $output .= '<option value="grille">' . __('Grille de sécurité', 'city-pages-generator') . '</option>';
        $output .= '<option value="pergola">' . __('Pergola', 'city-pages-generator') . '</option>';
        $output .= '<option value="verriere">' . __('Verrière', 'city-pages-generator') . '</option>';
        $output .= '<option value="ferronnerie">' . __('Ferronnerie d\'art', 'city-pages-generator') . '</option>';
        $output .= '<option value="autre">' . __('Autre', 'city-pages-generator') . '</option>';
        $output .= '</select>';
        $output .= '</div>';
        $output .= '</div>';

        $output .= '<div class="cpg-form-group">';
        $output .= '<label for="cpg_message">' . __('Votre message', 'city-pages-generator') . ' <span class="required">*</span></label>';
        $output .= '<textarea id="cpg_message" name="cpg_message" rows="5" required></textarea>';
        $output .= '</div>';

        $output .= '<div class="cpg-form-group">';
        $output .= '<button type="submit" class="cpg-btn cpg-btn-primary">';
        $output .= __('Envoyer ma demande', 'city-pages-generator');
        $output .= '</button>';
        $output .= '</div>';

        $output .= '</form>';

        return $output;
    }

    /**
     * Ajouter la metabox ville aux réalisations
     */
    public function add_realisation_city_metabox() {
        if (!post_type_exists('realisation')) {
            return;
        }

        add_meta_box(
            'cpg_realisation_city',
            __('Ville de la réalisation', 'city-pages-generator'),
            [$this, 'render_realisation_city_metabox'],
            'realisation',
            'side',
            'default'
        );
    }

    /**
     * Afficher la metabox ville
     */
    public function render_realisation_city_metabox($post) {
        wp_nonce_field('cpg_realisation_city', 'cpg_realisation_city_nonce');

        $city = get_post_meta($post->ID, '_cpg_realisation_city', true);
        
        // Récupérer toutes les pages ville
        $city_pages = get_posts([
            'post_type' => 'city_page',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC',
        ]);
        ?>
        <p>
            <label for="cpg_realisation_city"><?php _e('Ville :', 'city-pages-generator'); ?></label>
            <select id="cpg_realisation_city" name="cpg_realisation_city" class="widefat">
                <option value=""><?php _e('— Sélectionner —', 'city-pages-generator'); ?></option>
                <?php foreach ($city_pages as $city_page) : 
                    $city_name = get_post_meta($city_page->ID, '_cpg_city_name', true);
                ?>
                    <option value="<?php echo esc_attr($city_name); ?>" <?php selected($city, $city_name); ?>>
                        <?php echo esc_html($city_name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <p class="description"><?php _e('Associer cette réalisation à une ville pour l\'afficher sur la page correspondante.', 'city-pages-generator'); ?></p>
        <?php
    }

    /**
     * Sauvegarder la ville de la réalisation
     */
    public function save_realisation_city($post_id) {
        if (!isset($_POST['cpg_realisation_city_nonce']) || !wp_verify_nonce($_POST['cpg_realisation_city_nonce'], 'cpg_realisation_city')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['cpg_realisation_city'])) {
            $city = sanitize_text_field($_POST['cpg_realisation_city']);
            update_post_meta($post_id, '_cpg_realisation_city', $city);

            // Assigner aussi la taxonomie
            if (!empty($city)) {
                wp_set_object_terms($post_id, sanitize_title($city), 'realisation_city');
            }
        }
    }
}
