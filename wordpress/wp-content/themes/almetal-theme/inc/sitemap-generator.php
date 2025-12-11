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

    // Pages villes (city_page) - SEO local
    $city_pages = get_posts(array(
        'post_type' => 'city_page',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ));

    foreach ($city_pages as $city_page) {
        $sitemap .= almetal_sitemap_url(
            get_permalink($city_page),
            get_the_modified_date('Y-m-d', $city_page),
            'monthly',
            '0.8' // Priorité élevée pour le SEO local
        );
    }

    // Sessions de formation (training_session)
    $training_sessions = get_posts(array(
        'post_type' => 'training_session',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ));

    foreach ($training_sessions as $session) {
        $sitemap .= almetal_sitemap_url(
            get_permalink($session),
            get_the_modified_date('Y-m-d', $session),
            'weekly',
            '0.7'
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
 * Nettoyer une chaîne pour XML (convertir les entités HTML en caractères puis échapper pour XML)
 */
function almetal_xml_escape($string) {
    // Décoder les entités HTML (comme &rsquo; -> ')
    $string = html_entity_decode($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    // Remplacer les apostrophes typographiques par des apostrophes simples
    $string = str_replace(array(''', ''', '‚', '‛'), "'", $string);
    // Remplacer les guillemets typographiques
    $string = str_replace(array('"', '"', '„', '‟'), '"', $string);
    // Échapper pour XML (uniquement les 5 entités XML valides)
    $string = htmlspecialchars($string, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    return $string;
}

/**
 * Formater une URL pour le sitemap
 */
function almetal_sitemap_url($loc, $lastmod, $changefreq, $priority, $image_url = '', $image_title = '') {
    $url = "    <url>\n";
    $url .= "        <loc>" . esc_url($loc) . "</loc>\n";
    $url .= "        <lastmod>" . esc_html($lastmod) . "</lastmod>\n";
    $url .= "        <changefreq>" . esc_html($changefreq) . "</changefreq>\n";
    $url .= "        <priority>" . esc_html($priority) . "</priority>\n";
    
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
 * Servir le sitemap dynamique - Méthode très précoce via init
 */
function almetal_serve_sitemap_init() {
    $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    
    // Nettoyer l'URI (enlever query string et trailing slash)
    $clean_uri = strtok($request_uri, '?');
    $clean_uri = rtrim($clean_uri, '/');
    
    // Détecter sitemap.xml
    if ($clean_uri === '/sitemap.xml' || basename($clean_uri) === 'sitemap.xml') {
        // Empêcher WordPress de continuer
        status_header(200);
        header('Content-Type: application/xml; charset=utf-8');
        header('X-Robots-Tag: noindex, follow');
        header('Cache-Control: max-age=3600');
        
        echo almetal_generate_sitemap();
        exit;
    }
}
// Exécuter très tôt dans init
add_action('init', 'almetal_serve_sitemap_init', 1);

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
    
    // Détecter sitemap.xsl dans l'URL
    if ($request === 'sitemap.xsl' || preg_match('/^sitemap\.xsl$/i', $request) || preg_match('/\/sitemap\.xsl$/i', $request_uri)) {
        $xsl_file = get_template_directory() . '/sitemap.xsl';
        if (file_exists($xsl_file)) {
            status_header(200);
            header('Content-Type: application/xml; charset=utf-8');
            header('Cache-Control: max-age=86400');
            readfile($xsl_file);
            exit;
        }
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
