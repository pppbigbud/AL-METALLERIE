<?php
/**
 * Metaboxes pour les pages ville
 *
 * @package CityPagesGenerator
 */

if (!defined('ABSPATH')) {
    exit;
}

class CPG_Metaboxes {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post_city_page', array($this, 'save_meta_boxes'), 10, 2);
    }

    /**
     * Ajouter les metaboxes
     */
    public function add_meta_boxes() {
        // Informations de la ville
        add_meta_box(
            'cpg_city_info',
            __('Informations de la ville', 'city-pages-generator'),
            [$this, 'render_city_info_metabox'],
            'city_page',
            'normal',
            'high'
        );

        // Localisation
        add_meta_box(
            'cpg_location',
            __('Localisation et accès', 'city-pages-generator'),
            [$this, 'render_location_metabox'],
            'city_page',
            'normal',
            'default'
        );

        // Villes à proximité
        add_meta_box(
            'cpg_nearby',
            __('Communes à proximité', 'city-pages-generator'),
            [$this, 'render_nearby_metabox'],
            'city_page',
            'normal',
            'default'
        );

        // SEO
        add_meta_box(
            'cpg_seo',
            __('SEO et métadonnées', 'city-pages-generator'),
            [$this, 'render_seo_metabox'],
            'city_page',
            'side',
            'default'
        );

        // Actions
        add_meta_box(
            'cpg_actions',
            __('Actions', 'city-pages-generator'),
            [$this, 'render_actions_metabox'],
            'city_page',
            'side',
            'high'
        );
    }

    /**
     * Metabox : Informations de la ville
     */
    public function render_city_info_metabox($post) {
        wp_nonce_field('cpg_save_meta', 'cpg_meta_nonce');

        $city_name = get_post_meta($post->ID, '_cpg_city_name', true);
        $postal_code = get_post_meta($post->ID, '_cpg_postal_code', true);
        $department = get_post_meta($post->ID, '_cpg_department', true);
        $priority = get_post_meta($post->ID, '_cpg_priority', true) ?: 2;
        $local_specifics = get_post_meta($post->ID, '_cpg_local_specifics', true);
        ?>
        <table class="form-table cpg-metabox-table">
            <tr>
                <th><label for="cpg_city_name"><?php _e('Nom de la ville', 'city-pages-generator'); ?> <span class="required">*</span></label></th>
                <td>
                    <input type="text" id="cpg_city_name" name="cpg_city_name" value="<?php echo esc_attr($city_name); ?>" class="regular-text" required>
                    <p class="description"><?php _e('Exemple : Clermont-Ferrand', 'city-pages-generator'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="cpg_postal_code"><?php _e('Code postal', 'city-pages-generator'); ?> <span class="required">*</span></label></th>
                <td>
                    <input type="text" id="cpg_postal_code" name="cpg_postal_code" value="<?php echo esc_attr($postal_code); ?>" class="small-text" maxlength="5" required>
                </td>
            </tr>
            <tr>
                <th><label for="cpg_department"><?php _e('Département', 'city-pages-generator'); ?></label></th>
                <td>
                    <input type="text" id="cpg_department" name="cpg_department" value="<?php echo esc_attr($department); ?>" class="regular-text">
                    <p class="description"><?php _e('Exemple : Puy-de-Dôme', 'city-pages-generator'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="cpg_priority"><?php _e('Priorité', 'city-pages-generator'); ?></label></th>
                <td>
                    <select id="cpg_priority" name="cpg_priority">
                        <option value="1" <?php selected($priority, 1); ?>><?php _e('1 - Haute (ville principale)', 'city-pages-generator'); ?></option>
                        <option value="2" <?php selected($priority, 2); ?>><?php _e('2 - Moyenne (ville secondaire)', 'city-pages-generator'); ?></option>
                        <option value="3" <?php selected($priority, 3); ?>><?php _e('3 - Basse (petite commune)', 'city-pages-generator'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="cpg_local_specifics"><?php _e('Spécificités locales', 'city-pages-generator'); ?></label></th>
                <td>
                    <textarea id="cpg_local_specifics" name="cpg_local_specifics" rows="4" class="large-text"><?php echo esc_textarea($local_specifics); ?></textarea>
                    <p class="description"><?php _e('Quartiers, axes routiers, particularités locales à mentionner dans le contenu.', 'city-pages-generator'); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Metabox : Localisation
     */
    public function render_location_metabox($post) {
        $distance_km = get_post_meta($post->ID, '_cpg_distance_km', true);
        $travel_time = get_post_meta($post->ID, '_cpg_travel_time', true);
        $latitude = get_post_meta($post->ID, '_cpg_latitude', true);
        $longitude = get_post_meta($post->ID, '_cpg_longitude', true);
        ?>
        <table class="form-table cpg-metabox-table">
            <tr>
                <th><label for="cpg_distance_km"><?php _e('Distance depuis Peschadoires', 'city-pages-generator'); ?></label></th>
                <td>
                    <input type="number" id="cpg_distance_km" name="cpg_distance_km" value="<?php echo esc_attr($distance_km); ?>" class="small-text" step="0.1" min="0"> km
                </td>
            </tr>
            <tr>
                <th><label for="cpg_travel_time"><?php _e('Temps de trajet estimé', 'city-pages-generator'); ?></label></th>
                <td>
                    <input type="text" id="cpg_travel_time" name="cpg_travel_time" value="<?php echo esc_attr($travel_time); ?>" class="regular-text" placeholder="25 minutes">
                </td>
            </tr>
            <tr>
                <th><label><?php _e('Coordonnées GPS', 'city-pages-generator'); ?></label></th>
                <td>
                    <input type="text" id="cpg_latitude" name="cpg_latitude" value="<?php echo esc_attr($latitude); ?>" class="small-text" placeholder="Latitude">
                    <input type="text" id="cpg_longitude" name="cpg_longitude" value="<?php echo esc_attr($longitude); ?>" class="small-text" placeholder="Longitude">
                    <p class="description"><?php _e('Optionnel : pour l\'intégration Google Maps', 'city-pages-generator'); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Metabox : Villes à proximité
     */
    public function render_nearby_metabox($post) {
        $nearby_cities = get_post_meta($post->ID, '_cpg_nearby_cities', true) ?: [];
        ?>
        <div class="cpg-nearby-cities">
            <p class="description"><?php _e('Liste des communes desservies autour de cette ville (une par ligne).', 'city-pages-generator'); ?></p>
            <textarea id="cpg_nearby_cities" name="cpg_nearby_cities" rows="8" class="large-text" placeholder="Chamalières&#10;Royat&#10;Beaumont&#10;Ceyrat"><?php echo esc_textarea(implode("\n", $nearby_cities)); ?></textarea>
            <p class="description"><?php _e('Ces communes seront affichées dans la section "Zone d\'intervention".', 'city-pages-generator'); ?></p>
        </div>
        <?php
    }

    /**
     * Metabox : SEO
     */
    public function render_seo_metabox($post) {
        $meta_title = get_post_meta($post->ID, '_cpg_meta_title', true);
        $meta_description = get_post_meta($post->ID, '_cpg_meta_description', true);
        $city_name = get_post_meta($post->ID, '_cpg_city_name', true);
        $postal_code = get_post_meta($post->ID, '_cpg_postal_code', true);

        // Valeurs par défaut
        if (empty($meta_title) && $city_name) {
            $meta_title = sprintf('Métallier Ferronnier à %s (%s) | AL Métallerie', $city_name, $postal_code);
        }
        if (empty($meta_description) && $city_name) {
            $meta_description = sprintf('Artisan métallier à %s. Fabrication sur mesure de portails, garde-corps, escaliers. Devis gratuit. ☎ 06 73 33 35 32', $city_name);
        }
        ?>
        <p>
            <label for="cpg_meta_title"><strong><?php _e('Titre SEO', 'city-pages-generator'); ?></strong></label>
            <input type="text" id="cpg_meta_title" name="cpg_meta_title" value="<?php echo esc_attr($meta_title); ?>" class="widefat">
            <span class="cpg-char-count"><span id="cpg_title_count"><?php echo strlen($meta_title); ?></span>/60</span>
        </p>
        <p>
            <label for="cpg_meta_description"><strong><?php _e('Meta description', 'city-pages-generator'); ?></strong></label>
            <textarea id="cpg_meta_description" name="cpg_meta_description" rows="3" class="widefat"><?php echo esc_textarea($meta_description); ?></textarea>
            <span class="cpg-char-count"><span id="cpg_desc_count"><?php echo strlen($meta_description); ?></span>/160</span>
        </p>
        <?php
    }

    /**
     * Metabox : Actions
     */
    public function render_actions_metabox($post) {
        $generated_date = get_post_meta($post->ID, '_cpg_generated_date', true);
        $regenerated_date = get_post_meta($post->ID, '_cpg_regenerated_date', true);
        ?>
        <div class="cpg-actions-box">
            <?php if ($generated_date) : ?>
                <p><strong><?php _e('Généré le :', 'city-pages-generator'); ?></strong><br>
                <?php echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($generated_date)); ?></p>
            <?php endif; ?>

            <?php if ($regenerated_date) : ?>
                <p><strong><?php _e('Regénéré le :', 'city-pages-generator'); ?></strong><br>
                <?php echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($regenerated_date)); ?></p>
            <?php endif; ?>

            <hr>

            <p>
                <button type="button" id="cpg_regenerate_content" class="button button-secondary" data-post-id="<?php echo $post->ID; ?>">
                    <span class="dashicons dashicons-update"></span>
                    <?php _e('Regénérer le contenu', 'city-pages-generator'); ?>
                </button>
            </p>
            <p class="description"><?php _e('Attention : cela écrasera le contenu actuel.', 'city-pages-generator'); ?></p>

            <?php if ($post->post_status === 'publish') : ?>
                <hr>
                <p>
                    <a href="<?php echo get_permalink($post->ID); ?>" target="_blank" class="button">
                        <span class="dashicons dashicons-external"></span>
                        <?php _e('Voir la page', 'city-pages-generator'); ?>
                    </a>
                </p>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Sauvegarder les metaboxes
     */
    public function save_meta_boxes($post_id, $post) {
        // Vérifications de sécurité
        if (!isset($_POST['cpg_meta_nonce']) || !wp_verify_nonce($_POST['cpg_meta_nonce'], 'cpg_save_meta')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Sauvegarder les champs
        $fields = [
            'cpg_city_name'       => 'sanitize_text_field',
            'cpg_postal_code'     => 'sanitize_text_field',
            'cpg_department'      => 'sanitize_text_field',
            'cpg_priority'        => 'intval',
            'cpg_local_specifics' => 'sanitize_textarea_field',
            'cpg_distance_km'     => 'floatval',
            'cpg_travel_time'     => 'sanitize_text_field',
            'cpg_latitude'        => 'sanitize_text_field',
            'cpg_longitude'       => 'sanitize_text_field',
            'cpg_meta_title'      => 'sanitize_text_field',
            'cpg_meta_description'=> 'sanitize_textarea_field',
        ];

        foreach ($fields as $field => $sanitize_callback) {
            if (isset($_POST[$field])) {
                $value = call_user_func($sanitize_callback, $_POST[$field]);
                update_post_meta($post_id, '_' . $field, $value);
            }
        }

        // Traitement spécial pour les villes à proximité
        if (isset($_POST['cpg_nearby_cities'])) {
            $nearby = array_filter(array_map('trim', explode("\n", $_POST['cpg_nearby_cities'])));
            $nearby = array_map('sanitize_text_field', $nearby);
            update_post_meta($post_id, '_cpg_nearby_cities', $nearby);
        }
    }
}
