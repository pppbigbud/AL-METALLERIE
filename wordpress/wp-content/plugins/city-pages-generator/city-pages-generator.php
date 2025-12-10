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
    require_once CPG_PLUGIN_DIR . 'includes/functions-content.php';
    require_once CPG_PLUGIN_DIR . 'includes/functions-seo.php';
    if (is_admin()) {
        require_once CPG_PLUGIN_DIR . 'includes/functions-admin.php';
    }
}

// Enregistrer le CPT
add_action('init', 'cpg_register_post_type');
function cpg_register_post_type() {
    $labels = array(
        'name'               => 'Pages Ville',
        'singular_name'      => 'Page Ville',
        'menu_name'          => 'Pages Ville',
        'add_new'            => 'Ajouter',
        'add_new_item'       => 'Ajouter une page ville',
        'edit_item'          => 'Modifier',
        'new_item'           => 'Nouvelle page',
        'view_item'          => 'Voir',
        'search_items'       => 'Rechercher',
        'not_found'          => 'Aucune page trouvée',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_position'      => 26,
        'menu_icon'          => 'dashicons-location-alt',
        'supports'           => array('title', 'editor', 'thumbnail'),
        'has_archive'        => 'villes',
        'rewrite'            => array('slug' => 'metallier', 'with_front' => false),
        'show_in_rest'       => true,
    );

    register_post_type('city_page', $args);
}

// Menu admin simple
add_action('admin_menu', 'cpg_admin_menu');
function cpg_admin_menu() {
    add_submenu_page(
        'edit.php?post_type=city_page',
        'Ajouter une ville',
        'Générateur',
        'manage_options',
        'cpg-generator',
        'cpg_render_generator_page'
    );
}

// Page du générateur
function cpg_render_generator_page() {
    // Traitement du formulaire
    if (isset($_POST['cpg_generate']) && wp_verify_nonce($_POST['cpg_nonce'], 'cpg_generate_city')) {
        $city_name = sanitize_text_field($_POST['city_name']);
        $postal_code = sanitize_text_field($_POST['postal_code']);
        $department = sanitize_text_field($_POST['department']);
        
        if (!empty($city_name) && !empty($postal_code)) {
            $content = cpg_generate_content($city_name, $postal_code, $department);
            
            $post_id = wp_insert_post(array(
                'post_title'   => 'Métallier Ferronnier à ' . $city_name,
                'post_content' => $content,
                'post_status'  => 'draft',
                'post_type'    => 'city_page',
            ));
            
            if ($post_id) {
                update_post_meta($post_id, '_cpg_city_name', $city_name);
                update_post_meta($post_id, '_cpg_postal_code', $postal_code);
                update_post_meta($post_id, '_cpg_department', $department);
                
                echo '<div class="notice notice-success"><p>Page créée pour ' . esc_html($city_name) . ' ! <a href="' . get_edit_post_link($post_id) . '">Modifier</a></p></div>';
            }
        }
    }
    ?>
    <div class="wrap">
        <h1>Générateur de Pages Ville</h1>
        
        <form method="post" style="max-width: 600px; margin-top: 20px;">
            <?php wp_nonce_field('cpg_generate_city', 'cpg_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th><label for="city_name">Nom de la ville *</label></th>
                    <td><input type="text" id="city_name" name="city_name" class="regular-text" required></td>
                </tr>
                <tr>
                    <th><label for="postal_code">Code postal *</label></th>
                    <td><input type="text" id="postal_code" name="postal_code" class="small-text" maxlength="5" required></td>
                </tr>
                <tr>
                    <th><label for="department">Département</label></th>
                    <td>
                        <select id="department" name="department">
                            <option value="Puy-de-Dôme">Puy-de-Dôme (63)</option>
                            <option value="Allier">Allier (03)</option>
                            <option value="Cantal">Cantal (15)</option>
                            <option value="Haute-Loire">Haute-Loire (43)</option>
                            <option value="Loire">Loire (42)</option>
                        </select>
                    </td>
                </tr>
            </table>
            
            <p class="submit">
                <input type="submit" name="cpg_generate" class="button button-primary" value="Générer la page">
            </p>
        </form>
    </div>
    <?php
}

// Générer le contenu
function cpg_generate_content($city, $postal, $dept) {
    $company = 'AL Métallerie & Soudure';
    
    $content = "<!-- Section Introduction -->
<h2>Votre artisan métallier à {$city}</h2>
<p><strong>{$company}</strong>, artisan métallier ferronnier basé à Peschadoires, intervient à <strong>{$city} ({$postal})</strong> et dans tout le <strong>{$dept}</strong> pour tous vos projets de métallerie sur mesure.</p>

<p>Que vous soyez un particulier souhaitant embellir votre habitat ou un professionnel à la recherche d'un partenaire fiable, nous mettons notre savoir-faire artisanal à votre service.</p>

<!-- Section Services -->
<h2>Nos services de métallerie à {$city}</h2>
<ul>
<li><strong>Portails sur mesure</strong> : portails coulissants, battants, en acier ou fer forgé</li>
<li><strong>Garde-corps et rambardes</strong> : sécurisation de vos escaliers et terrasses</li>
<li><strong>Escaliers métalliques</strong> : droits, quart tournant, hélicoïdaux</li>
<li><strong>Pergolas et auvents</strong> : structures extérieures sur mesure</li>
<li><strong>Verrières d'intérieur</strong> : style atelier pour moderniser vos espaces</li>
<li><strong>Grilles de sécurité</strong> : protection de vos ouvertures</li>
<li><strong>Ferronnerie d'art</strong> : créations décoratives uniques</li>
</ul>

<!-- Section Pourquoi nous choisir -->
<h2>Pourquoi choisir {$company} à {$city} ?</h2>
<ul>
<li>✓ <strong>Artisan local</strong> : intervention rapide dans le {$dept}</li>
<li>✓ <strong>Fabrication sur mesure</strong> : chaque projet est unique</li>
<li>✓ <strong>Devis gratuit</strong> : étude personnalisée de votre projet</li>
<li>✓ <strong>Qualité artisanale</strong> : finitions soignées et durables</li>
<li>✓ <strong>Prix justes</strong> : rapport qualité-prix optimal</li>
</ul>

<!-- Section Contact -->
<h2>Contactez votre métallier à {$city}</h2>
<p>Pour un devis gratuit ou des renseignements sur nos prestations à {$city} et ses environs, contactez-nous :</p>
<p>📞 <strong>06 73 33 35 32</strong><br>
📧 contact@al-metallerie.fr</p>

<p><a href=\"/contact/\" class=\"button\">Demander un devis gratuit</a></p>";

    return $content;
}

// Activation
register_activation_hook(__FILE__, 'cpg_activate_plugin');
function cpg_activate_plugin() {
    cpg_register_post_type();
    flush_rewrite_rules();
}

// Désactivation
register_deactivation_hook(__FILE__, 'cpg_deactivate_plugin');
function cpg_deactivate_plugin() {
    flush_rewrite_rules();
}
