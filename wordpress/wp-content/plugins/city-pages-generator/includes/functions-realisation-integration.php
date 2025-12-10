<?php
/**
 * Intégration Réalisations <-> Pages Ville
 * 
 * - Génère automatiquement une page ville quand une nouvelle ville est ajoutée à une réalisation
 * - Fournit des fonctions pour lier les badges de lieu aux pages ville
 */

if (!defined('ABSPATH')) exit;

/**
 * Quand une réalisation est sauvegardée, vérifier si la ville existe
 * et créer une page ville automatiquement si nécessaire
 */
add_action('save_post_realisation', 'cpg_auto_create_city_page_from_realisation', 99, 3);
function cpg_auto_create_city_page_from_realisation($post_id, $post, $update) {
    // Éviter les auto-saves et révisions
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (wp_is_post_revision($post_id)) return;
    if ($post->post_status === 'auto-draft') return;
    
    // Récupérer le lieu de la réalisation
    $lieu = get_post_meta($post_id, '_almetal_lieu', true);
    
    if (empty($lieu)) return;
    
    // Nettoyer le nom de la ville (enlever code postal si présent)
    $city_name = cpg_clean_city_name($lieu);
    
    if (empty($city_name)) return;
    
    // Vérifier si une page ville existe déjà pour cette ville
    $existing = cpg_get_city_page_by_name($city_name);
    
    if ($existing) {
        // La page existe, on peut lier la réalisation à cette page
        update_post_meta($post_id, '_cpg_linked_city_page', $existing->ID);
        return;
    }
    
    // Créer automatiquement la page ville (en brouillon)
    $new_city_page_id = cpg_auto_generate_city_page($city_name);
    
    if ($new_city_page_id && !is_wp_error($new_city_page_id)) {
        // Lier la réalisation à la nouvelle page ville
        update_post_meta($post_id, '_cpg_linked_city_page', $new_city_page_id);
        
        // Ajouter une note admin
        add_action('admin_notices', function() use ($city_name, $new_city_page_id) {
            echo '<div class="notice notice-info is-dismissible"><p>';
            echo 'Une nouvelle page ville a été créée automatiquement pour <strong>' . esc_html($city_name) . '</strong>. ';
            echo '<a href="' . get_edit_post_link($new_city_page_id) . '">Modifier la page</a>';
            echo '</p></div>';
        });
    }
}

/**
 * Nettoyer le nom de la ville (enlever code postal, département, etc.)
 */
function cpg_clean_city_name($lieu) {
    // Supprimer les codes postaux (5 chiffres)
    $city = preg_replace('/\s*\d{5}\s*/', '', $lieu);
    
    // Supprimer les parenthèses avec leur contenu (ex: "(63)")
    $city = preg_replace('/\s*\([^)]*\)\s*/', '', $city);
    
    // Supprimer les tirets suivis de département
    $city = preg_replace('/\s*-\s*(Puy-de-Dôme|Allier|Cantal|Haute-Loire|Loire|Rhône).*$/i', '', $city);
    
    // Nettoyer les espaces
    $city = trim($city);
    
    return $city;
}

/**
 * Récupérer une page ville par son nom
 */
function cpg_get_city_page_by_name($city_name) {
    // Recherche exacte par meta
    $pages = get_posts(array(
        'post_type'      => 'city_page',
        'post_status'    => array('publish', 'draft'),
        'meta_key'       => '_cpg_city_name',
        'meta_value'     => $city_name,
        'posts_per_page' => 1,
    ));
    
    if (!empty($pages)) {
        return $pages[0];
    }
    
    // Recherche par titre (fallback)
    $pages = get_posts(array(
        'post_type'      => 'city_page',
        'post_status'    => array('publish', 'draft'),
        'title'          => 'Métallier Ferronnier à ' . $city_name,
        'posts_per_page' => 1,
    ));
    
    if (!empty($pages)) {
        return $pages[0];
    }
    
    // Recherche approximative par slug
    $slug = sanitize_title($city_name);
    $pages = get_posts(array(
        'post_type'      => 'city_page',
        'post_status'    => array('publish', 'draft'),
        'name'           => $slug,
        'posts_per_page' => 1,
    ));
    
    return !empty($pages) ? $pages[0] : null;
}

/**
 * Générer automatiquement une page ville
 */
function cpg_auto_generate_city_page($city_name) {
    // Déterminer le département basé sur le code postal ou la région
    $department = cpg_guess_department($city_name);
    
    // Utiliser la fonction de création existante si disponible
    if (function_exists('cpg_create_city_page')) {
        return cpg_create_city_page(array(
            'city_name'   => $city_name,
            'postal_code' => '',
            'department'  => $department,
            'priority'    => 2,
            'post_status' => 'draft', // Créer en brouillon pour révision
        ));
    }
    
    // Fallback : création simple
    $post_id = wp_insert_post(array(
        'post_title'   => 'Métallier Ferronnier à ' . $city_name,
        'post_name'    => sanitize_title($city_name),
        'post_content' => cpg_generate_simple_content($city_name, $department),
        'post_status'  => 'draft',
        'post_type'    => 'city_page',
        'post_excerpt' => "Artisan métallier à {$city_name}. Portails, garde-corps, escaliers sur mesure. Devis gratuit.",
    ));
    
    if (!is_wp_error($post_id)) {
        update_post_meta($post_id, '_cpg_city_name', $city_name);
        update_post_meta($post_id, '_cpg_department', $department);
        update_post_meta($post_id, '_cpg_auto_generated', true);
        update_post_meta($post_id, '_cpg_generated_date', current_time('mysql'));
    }
    
    return $post_id;
}

/**
 * Deviner le département basé sur le nom de la ville
 */
function cpg_guess_department($city_name) {
    // Liste des villes connues par département
    $cities_by_dept = array(
        'Puy-de-Dôme' => array('Clermont-Ferrand', 'Thiers', 'Riom', 'Issoire', 'Cournon', 'Chamalières', 'Beaumont', 'Aubière', 'Pont-du-Château', 'Lempdes', 'Peschadoires', 'Maringues', 'Lezoux', 'Courpière', 'Ambert', 'Billom'),
        'Allier' => array('Vichy', 'Montluçon', 'Moulins', 'Cusset', 'Commentry', 'Gannat'),
        'Cantal' => array('Aurillac', 'Saint-Flour', 'Mauriac'),
        'Haute-Loire' => array('Le Puy-en-Velay', 'Monistrol-sur-Loire', 'Brioude', 'Yssingeaux'),
        'Loire' => array('Saint-Étienne', 'Roanne', 'Saint-Chamond', 'Firminy', 'Montbrison'),
    );
    
    $city_lower = mb_strtolower($city_name);
    
    foreach ($cities_by_dept as $dept => $cities) {
        foreach ($cities as $city) {
            if (mb_strtolower($city) === $city_lower || strpos($city_lower, mb_strtolower($city)) !== false) {
                return $dept;
            }
        }
    }
    
    // Par défaut : Puy-de-Dôme (zone principale d'intervention)
    return 'Puy-de-Dôme';
}

/**
 * Générer un contenu SEO unique pour la page ville
 * Utilise le générateur avancé si disponible, sinon fallback simple
 */
function cpg_generate_simple_content($city, $dept) {
    // Utiliser le générateur SEO avancé si disponible
    if (function_exists('cpg_generate_seo_content')) {
        return cpg_generate_seo_content($city, $dept);
    }
    
    // Fallback simple
    $company = 'AL Métallerie & Soudure';
    
    return "<h2>Votre artisan métallier à {$city}</h2>
<p><strong>{$company}</strong>, artisan métallier ferronnier basé à Peschadoires, intervient à <strong>{$city}</strong> et dans tout le <strong>{$dept}</strong> pour tous vos projets de métallerie sur mesure.</p>

<h2>Nos services à {$city}</h2>
<ul>
<li><strong>Portails sur mesure</strong> : coulissants, battants, en acier ou fer forgé</li>
<li><strong>Garde-corps et rambardes</strong> : sécurisation de vos escaliers et terrasses</li>
<li><strong>Escaliers métalliques</strong> : droits, quart tournant, hélicoïdaux</li>
<li><strong>Pergolas et auvents</strong> : structures extérieures sur mesure</li>
<li><strong>Verrières d'intérieur</strong> : style atelier pour vos espaces</li>
<li><strong>Ferronnerie d'art</strong> : créations décoratives uniques</li>
</ul>

<h2>Pourquoi nous choisir ?</h2>
<ul>
<li>✓ <strong>Artisan local</strong> : intervention rapide</li>
<li>✓ <strong>Fabrication sur mesure</strong> : chaque projet est unique</li>
<li>✓ <strong>Devis gratuit</strong> : étude personnalisée</li>
<li>✓ <strong>Qualité artisanale</strong> : finitions soignées</li>
</ul>

<h2>Contactez-nous</h2>
<p>📞 <strong>06 73 33 35 32</strong><br>
📧 contact@al-metallerie.fr</p>

<p><a href=\"/contact/\" class=\"button\">Demander un devis gratuit</a></p>";
}

/**
 * Récupérer l'URL de la page ville pour un lieu donné
 * Utilisé pour créer les liens sur les badges
 */
function cpg_get_city_page_url($lieu) {
    if (empty($lieu)) return null;
    
    $city_name = cpg_clean_city_name($lieu);
    $city_page = cpg_get_city_page_by_name($city_name);
    
    if ($city_page && $city_page->post_status === 'publish') {
        return get_permalink($city_page->ID);
    }
    
    return null;
}

/**
 * Afficher le badge lieu avec lien vers la page ville
 * Remplace le simple affichage du lieu
 */
function cpg_render_city_badge($lieu, $with_icon = true) {
    if (empty($lieu)) return '';
    
    $city_url = cpg_get_city_page_url($lieu);
    $icon = '';
    
    if ($with_icon) {
        $icon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
            <circle cx="12" cy="10" r="3"/>
        </svg>';
    }
    
    if ($city_url) {
        return sprintf(
            '<a href="%s" class="meta-item meta-lieu meta-lieu--linked" title="Voir nos réalisations à %s">%s<span>%s</span></a>',
            esc_url($city_url),
            esc_attr($lieu),
            $icon,
            esc_html($lieu)
        );
    }
    
    return sprintf(
        '<span class="meta-item meta-lieu">%s%s</span>',
        $icon,
        esc_html($lieu)
    );
}

/**
 * Shortcode pour afficher les réalisations d'une ville
 * Usage: [realisations_ville ville="Clermont-Ferrand" limit="6"]
 */
add_shortcode('realisations_ville', 'cpg_shortcode_realisations_ville');
function cpg_shortcode_realisations_ville($atts) {
    $atts = shortcode_atts(array(
        'ville' => '',
        'limit' => 6,
    ), $atts);
    
    if (empty($atts['ville'])) {
        // Si on est sur une page ville, utiliser la ville de cette page
        if (is_singular('city_page')) {
            $atts['ville'] = get_post_meta(get_the_ID(), '_cpg_city_name', true);
        }
    }
    
    if (empty($atts['ville'])) return '';
    
    $realisations = get_posts(array(
        'post_type'      => 'realisation',
        'posts_per_page' => intval($atts['limit']),
        'meta_query'     => array(
            array(
                'key'     => '_almetal_lieu',
                'value'   => $atts['ville'],
                'compare' => 'LIKE',
            ),
        ),
    ));
    
    if (empty($realisations)) {
        return '<p>Aucune réalisation trouvée pour ' . esc_html($atts['ville']) . '.</p>';
    }
    
    $output = '<div class="cpg-realisations-grid">';
    
    foreach ($realisations as $real) {
        $thumb = get_the_post_thumbnail_url($real->ID, 'medium');
        $output .= '<div class="cpg-realisation-item">';
        if ($thumb) {
            $output .= '<a href="' . get_permalink($real->ID) . '"><img src="' . esc_url($thumb) . '" alt="' . esc_attr($real->post_title) . '"></a>';
        }
        $output .= '<h4><a href="' . get_permalink($real->ID) . '">' . esc_html($real->post_title) . '</a></h4>';
        $output .= '</div>';
    }
    
    $output .= '</div>';
    
    return $output;
}

/**
 * Ajouter les styles pour les badges liés
 */
add_action('wp_head', 'cpg_city_badge_styles');
function cpg_city_badge_styles() {
    ?>
    <style>
    .meta-lieu--linked {
        cursor: pointer;
        transition: color 0.2s ease, background-color 0.2s ease;
        text-decoration: none;
    }
    .meta-lieu--linked:hover {
        color: #F08B18;
        background-color: rgba(240, 139, 24, 0.1);
        border-radius: 4px;
    }
    .cpg-realisations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        margin: 20px 0;
    }
    .cpg-realisation-item {
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .cpg-realisation-item img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }
    .cpg-realisation-item h4 {
        padding: 15px;
        margin: 0;
        font-size: 1rem;
    }
    .cpg-realisation-item h4 a {
        color: #333;
        text-decoration: none;
    }
    .cpg-realisation-item h4 a:hover {
        color: #F08B18;
    }
    </style>
    <?php
}
