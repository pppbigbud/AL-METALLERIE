<?php
/**
 * Page de paramètres du plugin
 *
 * @package CityPagesGenerator
 */

if (!defined('ABSPATH')) {
    exit;
}

class CPG_Settings {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_init', [$this, 'register_settings']);
    }

    /**
     * Enregistrer les paramètres
     */
    public function register_settings() {
        register_setting('cpg_settings_group', 'cpg_settings', [$this, 'sanitize_settings']);
    }

    /**
     * Sanitizer les paramètres
     */
    public function sanitize_settings($input) {
        $sanitized = [];

        // Informations entreprise
        $sanitized['company_name'] = sanitize_text_field($input['company_name'] ?? '');
        $sanitized['workshop_city'] = sanitize_text_field($input['workshop_city'] ?? '');
        $sanitized['workshop_address'] = sanitize_text_field($input['workshop_address'] ?? '');
        $sanitized['phone'] = sanitize_text_field($input['phone'] ?? '');
        $sanitized['phone_international'] = sanitize_text_field($input['phone_international'] ?? '');
        $sanitized['email'] = sanitize_email($input['email'] ?? '');
        $sanitized['company_description'] = sanitize_textarea_field($input['company_description'] ?? '');

        // Services
        if (isset($input['services']) && is_array($input['services'])) {
            $sanitized['services'] = [];
            foreach ($input['services'] as $key => $service) {
                $sanitized['services'][$key] = [
                    'enabled' => isset($service['enabled']),
                    'name' => sanitize_text_field($service['name'] ?? ''),
                    'icon' => sanitize_text_field($service['icon'] ?? ''),
                    'description' => sanitize_text_field($service['description'] ?? ''),
                ];
            }
        }

        // Google Maps
        $sanitized['google_maps_api_key'] = sanitize_text_field($input['google_maps_api_key'] ?? '');
        $sanitized['default_radius_km'] = intval($input['default_radius_km'] ?? 20);

        // Sections
        if (isset($input['sections_enabled']) && is_array($input['sections_enabled'])) {
            $sanitized['sections_enabled'] = array_map(function($v) {
                return (bool) $v;
            }, $input['sections_enabled']);
        }

        if (isset($input['sections_order']) && is_array($input['sections_order'])) {
            $sanitized['sections_order'] = array_map('sanitize_text_field', $input['sections_order']);
        }

        return $sanitized;
    }

    /**
     * Afficher la page de paramètres
     */
    public function render_page() {
        $settings = get_option('cpg_settings', []);
        ?>
        <div class="wrap cpg-admin-wrap">
            <h1><?php _e('Paramètres du générateur de pages ville', 'city-pages-generator'); ?></h1>

            <form method="post" action="options.php" class="cpg-settings-form">
                <?php settings_fields('cpg_settings_group'); ?>

                <!-- Onglets -->
                <nav class="nav-tab-wrapper cpg-tabs">
                    <a href="#tab-company" class="nav-tab nav-tab-active"><?php _e('Entreprise', 'city-pages-generator'); ?></a>
                    <a href="#tab-services" class="nav-tab"><?php _e('Services', 'city-pages-generator'); ?></a>
                    <a href="#tab-sections" class="nav-tab"><?php _e('Sections', 'city-pages-generator'); ?></a>
                    <a href="#tab-advanced" class="nav-tab"><?php _e('Avancé', 'city-pages-generator'); ?></a>
                </nav>

                <!-- Onglet Entreprise -->
                <div id="tab-company" class="cpg-tab-content cpg-tab-active">
                    <table class="form-table">
                        <tr>
                            <th><label for="company_name"><?php _e('Nom de l\'entreprise', 'city-pages-generator'); ?></label></th>
                            <td>
                                <input type="text" id="company_name" name="cpg_settings[company_name]" value="<?php echo esc_attr($settings['company_name'] ?? 'AL Métallerie & Soudure'); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="workshop_city"><?php _e('Ville de l\'atelier', 'city-pages-generator'); ?></label></th>
                            <td>
                                <input type="text" id="workshop_city" name="cpg_settings[workshop_city]" value="<?php echo esc_attr($settings['workshop_city'] ?? 'Peschadoires'); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="workshop_address"><?php _e('Adresse complète', 'city-pages-generator'); ?></label></th>
                            <td>
                                <input type="text" id="workshop_address" name="cpg_settings[workshop_address]" value="<?php echo esc_attr($settings['workshop_address'] ?? '14 route de Maringues, 63920 Peschadoires'); ?>" class="large-text">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="phone"><?php _e('Téléphone', 'city-pages-generator'); ?></label></th>
                            <td>
                                <input type="text" id="phone" name="cpg_settings[phone]" value="<?php echo esc_attr($settings['phone'] ?? '06 73 33 35 32'); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="phone_international"><?php _e('Téléphone (format international)', 'city-pages-generator'); ?></label></th>
                            <td>
                                <input type="text" id="phone_international" name="cpg_settings[phone_international]" value="<?php echo esc_attr($settings['phone_international'] ?? '+33673333532'); ?>" class="regular-text">
                                <p class="description"><?php _e('Pour le Schema.org', 'city-pages-generator'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="email"><?php _e('Email', 'city-pages-generator'); ?></label></th>
                            <td>
                                <input type="email" id="email" name="cpg_settings[email]" value="<?php echo esc_attr($settings['email'] ?? 'contact@al-metallerie.fr'); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="company_description"><?php _e('Présentation de l\'entreprise', 'city-pages-generator'); ?></label></th>
                            <td>
                                <textarea id="company_description" name="cpg_settings[company_description]" rows="5" class="large-text"><?php echo esc_textarea($settings['company_description'] ?? ''); ?></textarea>
                                <p class="description"><?php _e('Ce texte peut être réutilisé sur chaque page ville.', 'city-pages-generator'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Onglet Services -->
                <div id="tab-services" class="cpg-tab-content">
                    <p class="description"><?php _e('Configurez les services affichés sur les pages ville.', 'city-pages-generator'); ?></p>
                    
                    <table class="widefat cpg-services-table">
                        <thead>
                            <tr>
                                <th><?php _e('Actif', 'city-pages-generator'); ?></th>
                                <th><?php _e('Service', 'city-pages-generator'); ?></th>
                                <th><?php _e('Description', 'city-pages-generator'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $default_services = [
                                'portails' => ['name' => 'Portails sur mesure', 'description' => 'Portails coulissants et battants en acier, aluminium ou fer forgé.'],
                                'garde_corps' => ['name' => 'Garde-corps et rambardes', 'description' => 'Garde-corps intérieurs et extérieurs, rambardes d\'escalier.'],
                                'escaliers' => ['name' => 'Escaliers métalliques', 'description' => 'Escaliers droits, quart tournant, hélicoïdaux en métal.'],
                                'grilles' => ['name' => 'Grilles de sécurité', 'description' => 'Grilles de défense, grilles de fenêtre, protection anti-intrusion.'],
                                'pergolas' => ['name' => 'Pergolas et structures', 'description' => 'Pergolas bioclimatiques, auvents, structures métalliques extérieures.'],
                                'verrieres' => ['name' => 'Verrières d\'intérieur', 'description' => 'Verrières atelier, cloisons vitrées, séparations design.'],
                                'ferronnerie' => ['name' => 'Ferronnerie d\'art', 'description' => 'Créations artistiques, pièces décoratives, restauration.'],
                                'mobilier' => ['name' => 'Mobilier métallique', 'description' => 'Tables, étagères, consoles, mobilier sur mesure en métal.'],
                            ];

                            $services = isset($settings['services']) ? $settings['services'] : $default_services;

                            foreach ($default_services as $key => $default) :
                                $service = isset($services[$key]) ? $services[$key] : $default;
                                $enabled = isset($service['enabled']) ? $service['enabled'] : true;
                            ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="cpg_settings[services][<?php echo $key; ?>][enabled]" value="1" <?php checked($enabled); ?>>
                                    </td>
                                    <td>
                                        <input type="text" name="cpg_settings[services][<?php echo $key; ?>][name]" value="<?php echo esc_attr($service['name'] ?? $default['name']); ?>" class="regular-text">
                                    </td>
                                    <td>
                                        <input type="text" name="cpg_settings[services][<?php echo $key; ?>][description]" value="<?php echo esc_attr($service['description'] ?? $default['description']); ?>" class="large-text">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Onglet Sections -->
                <div id="tab-sections" class="cpg-tab-content">
                    <p class="description"><?php _e('Activez ou désactivez les sections des pages ville.', 'city-pages-generator'); ?></p>
                    
                    <table class="form-table">
                        <?php
                        $sections = [
                            'intro' => __('Introduction', 'city-pages-generator'),
                            'services' => __('Services', 'city-pages-generator'),
                            'realisations' => __('Réalisations', 'city-pages-generator'),
                            'why_us' => __('Pourquoi nous choisir', 'city-pages-generator'),
                            'zone' => __('Zone d\'intervention', 'city-pages-generator'),
                            'contact' => __('Contact', 'city-pages-generator'),
                            'faq' => __('FAQ', 'city-pages-generator'),
                        ];

                        $enabled_sections = isset($settings['sections_enabled']) ? $settings['sections_enabled'] : array_fill_keys(array_keys($sections), true);

                        foreach ($sections as $key => $label) :
                            $is_enabled = isset($enabled_sections[$key]) ? $enabled_sections[$key] : true;
                        ?>
                            <tr>
                                <th><?php echo esc_html($label); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="cpg_settings[sections_enabled][<?php echo $key; ?>]" value="1" <?php checked($is_enabled); ?>>
                                        <?php _e('Activer cette section', 'city-pages-generator'); ?>
                                    </label>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>

                <!-- Onglet Avancé -->
                <div id="tab-advanced" class="cpg-tab-content">
                    <table class="form-table">
                        <tr>
                            <th><label for="google_maps_api_key"><?php _e('Clé API Google Maps', 'city-pages-generator'); ?></label></th>
                            <td>
                                <input type="text" id="google_maps_api_key" name="cpg_settings[google_maps_api_key]" value="<?php echo esc_attr($settings['google_maps_api_key'] ?? ''); ?>" class="large-text">
                                <p class="description"><?php _e('Optionnel. Sans clé, un lien vers Google Maps sera affiché à la place de la carte intégrée.', 'city-pages-generator'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="default_radius_km"><?php _e('Rayon par défaut (km)', 'city-pages-generator'); ?></label></th>
                            <td>
                                <input type="number" id="default_radius_km" name="cpg_settings[default_radius_km]" value="<?php echo intval($settings['default_radius_km'] ?? 20); ?>" class="small-text" min="5" max="100">
                                <p class="description"><?php _e('Rayon pour la zone d\'intervention autour de chaque ville.', 'city-pages-generator'); ?></p>
                            </td>
                        </tr>
                    </table>

                    <h3><?php _e('Hooks disponibles', 'city-pages-generator'); ?></h3>
                    <p class="description"><?php _e('Pour les développeurs : personnalisez le contenu via ces hooks.', 'city-pages-generator'); ?></p>
                    <pre>
// Filtrer le contenu généré
add_filter('cpg_city_page_content', function($content, $city_data) {
    return $content;
}, 10, 2);

// Actions avant/après les services
do_action('cpg_before_city_services', $city_data);
do_action('cpg_after_city_services', $city_data);
                    </pre>
                </div>

                <?php submit_button(__('Enregistrer les paramètres', 'city-pages-generator')); ?>
            </form>
        </div>
        <?php
    }
}
