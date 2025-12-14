<?php
/**
 * Générateur de Sitemap XML dynamique
 * Optimisé pour le référencement local - Métallerie Thiers, Puy-de-Dôme
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Générer le sitemap XML principal
 */
function almetal_generate_sitemap() {
    $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $sitemap .= '<?xml-stylesheet type="text/xsl" href="' . home_url('/sitemap.xsl') . '"?>' . "\n";
    $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
    $sitemap .= '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n\n";

    // Page d'accueil
    $sitemap .= almetal_sitemap_url(home_url('/'), date('Y-m-d'), 'weekly', '1.0');

    // Pages principales
    $pages = array(
        'realisations' => array('priority' => '0.9', 'changefreq' => 'weekly'),
        'formations' => array('priority' => '0.8', 'changefreq' => 'monthly'),
        'contact' => array('priority' => '0.9', 'changefreq' => 'monthly'),
        'mentions-legales' => array('priority' => '0.3', 'changefreq' => 'yearly'),
        'politique-confidentialite' => array('priority' => '0.3', 'changefreq' => 'yearly'),
    );

    foreach ($pages as $slug => $data) {
        $page = get_page_by_path($slug);
        if ($page) {
            $sitemap .= almetal_sitemap_url(
                get_permalink($page),
                get_the_modified_date('Y-m-d', $page),
                $data['changefreq'],
                $data['priority']
            );
        }
    }

    // Catégories de réalisations (taxonomies)
    $categories = get_terms(array(
        'taxonomy' => 'type_realisation',
        'hide_empty' => true,
    ));

    if (!is_wp_error($categories)) {
        foreach ($categories as $category) {
            $sitemap .= almetal_sitemap_url(
                get_term_link($category),
                date('Y-m-d'),
                'weekly',
                '0.8'
            );
        }
    }

    // Toutes les réalisations
    $realisations = get_posts(array(
        'post_type' => 'realisation',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ));

    foreach ($realisations as $realisation) {
        $image_id = get_post_thumbnail_id($realisation->ID);
        $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
        
        $sitemap .= almetal_sitemap_url(
            get_permalink($realisation),
            get_the_modified_date('Y-m-d', $realisation),
            'monthly',
            '0.7',
            $image_url,
            get_the_title($realisation)
        );
    }

    // Pages ville (city_page) - SEO Local
    $city_pages = get_posts(array(
        'post_type' => 'city_page',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ));

    foreach ($city_pages as $city_page) {
        $sitemap .= almetal_sitemap_url(
            get_permalink($city_page),
            get_the_modified_date('Y-m-d', $city_page),
            'weekly',
            '0.9'
        );
    }

    // Archive des pages ville
    $city_archive = get_post_type_archive_link('city_page');
    if ($city_archive) {
        $sitemap .= almetal_sitemap_url(
            $city_archive,
            date('Y-m-d'),
            'weekly',
            '0.7'
        );
    }

    // Pages Matières (acier, inox, aluminium, etc.)
    $matieres = get_posts(array(
        'post_type' => 'matiere',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ));

    foreach ($matieres as $matiere) {
        $image_id = get_post_thumbnail_id($matiere->ID);
        $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
        
        $sitemap .= almetal_sitemap_url(
            get_permalink($matiere),
            get_the_modified_date('Y-m-d', $matiere),
            'monthly',
            '0.8',
            $image_url,
            get_the_title($matiere) . ' - Métallerie AL Métallerie Soudure'
        );
    }

    // Articles de blog (si présents)
    $posts = get_posts(array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ));

    foreach ($posts as $post) {
        $sitemap .= almetal_sitemap_url(
            get_permalink($post),
            get_the_modified_date('Y-m-d', $post),
            'monthly',
            '0.6'
        );
    }

    $sitemap .= '</urlset>';

    return $sitemap;
}

/**
 * Échapper une chaîne pour XML (entités valides uniquement)
 */
function almetal_xml_escape($string) {
    // Convertir les entités HTML en caractères, puis échapper pour XML
    $string = html_entity_decode($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    // Échapper uniquement les 5 entités XML valides
    $string = htmlspecialchars($string, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    return $string;
}

/**
 * Formater une URL pour le sitemap
 */
function almetal_sitemap_url($loc, $lastmod, $changefreq, $priority, $image_url = '', $image_title = '') {
    $url = "    <url>\n";
    $url .= "        <loc>" . esc_url($loc) . "</loc>\n";
    $url .= "        <lastmod>" . almetal_xml_escape($lastmod) . "</lastmod>\n";
    $url .= "        <changefreq>" . almetal_xml_escape($changefreq) . "</changefreq>\n";
    $url .= "        <priority>" . almetal_xml_escape($priority) . "</priority>\n";
    
    // Ajouter l'image si présente
    if ($image_url) {
        $url .= "        <image:image>\n";
        $url .= "            <image:loc>" . esc_url($image_url) . "</image:loc>\n";
        if ($image_title) {
            $url .= "            <image:title>" . almetal_xml_escape($image_title) . "</image:title>\n";
        }
        $url .= "        </image:image>\n";
    }
    
    $url .= "    </url>\n\n";
    
    return $url;
}

/**
 * Servir le sitemap dynamique - Méthode directe (sans rewrite rules)
 * Utilise le hook 'parse_request' qui s'exécute avant la résolution 404
 */
function almetal_serve_sitemap_early($wp) {
    // Vérifier si c'est une requête pour le sitemap
    $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $request = isset($wp->request) ? $wp->request : '';
    
    // Détecter sitemap.xml dans l'URL
    if ($request === 'sitemap.xml' || preg_match('/^sitemap\.xml$/i', $request) || preg_match('/\/sitemap\.xml$/i', $request_uri)) {
        
        // Empêcher WordPress de continuer
        status_header(200);
        header('Content-Type: application/xml; charset=utf-8');
        header('X-Robots-Tag: noindex, follow');
        header('Cache-Control: max-age=3600');
        
        echo almetal_generate_sitemap();
        exit;
    }
}
// Exécuter au moment du parsing de la requête (avant 404)
add_action('parse_request', 'almetal_serve_sitemap_early', 1);

/**
 * Servir le sitemap via template_redirect (fallback)
 */
function almetal_serve_sitemap() {
    global $wp;
    
    // Vérifier si c'est une requête pour le sitemap
    if (isset($wp->query_vars['sitemap'])) {
        header('Content-Type: application/xml; charset=utf-8');
        header('X-Robots-Tag: noindex, follow');
        
        echo almetal_generate_sitemap();
        exit;
    }
}
add_action('template_redirect', 'almetal_serve_sitemap', 1);

/**
 * Ajouter la règle de réécriture pour le sitemap
 */
function almetal_sitemap_rewrite_rules() {
    add_rewrite_rule('^sitemap\.xml$', 'index.php?sitemap=1', 'top');
}
add_action('init', 'almetal_sitemap_rewrite_rules', 10);

/**
 * Ajouter la variable de requête
 */
function almetal_sitemap_query_vars($vars) {
    $vars[] = 'sitemap';
    return $vars;
}
add_filter('query_vars', 'almetal_sitemap_query_vars');

/**
 * Régénérer le sitemap lors de la publication/modification d'un contenu
 */
function almetal_ping_search_engines($post_id) {
    // Ne pas exécuter pour les révisions ou auto-saves
    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
        return;
    }
    
    $post = get_post($post_id);
    if ($post->post_status !== 'publish') {
        return;
    }
    
    // Ping Google (optionnel - Google découvre automatiquement via Search Console)
    // wp_remote_get('https://www.google.com/ping?sitemap=' . urlencode(home_url('/sitemap.xml')));
}
add_action('save_post', 'almetal_ping_search_engines');

/**
 * Ajouter le lien vers le sitemap dans le head
 */
function almetal_sitemap_head_link() {
    echo '<link rel="sitemap" type="application/xml" title="Sitemap" href="' . home_url('/sitemap.xml') . '" />' . "\n";
}
add_action('wp_head', 'almetal_sitemap_head_link');
