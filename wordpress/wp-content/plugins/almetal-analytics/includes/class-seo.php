<?php
/**
 * Classe SEO - Audit et analyse SEO
 * 
 * @package Almetal_Analytics
 * @since 1.1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class Almetal_Analytics_SEO {
    
    /**
     * Instance unique
     */
    private static $instance = null;
    
    /**
     * Critères SEO et leurs poids
     */
    private static $criteria = array(
        'title_length' => 10,
        'title_keyword' => 5,
        'meta_description' => 10,
        'meta_description_length' => 5,
        'h1_exists' => 10,
        'h1_unique' => 5,
        'h1_keyword' => 5,
        'images_alt' => 10,
        'internal_links' => 10,
        'external_links' => 5,
        'content_length' => 10,
        'https' => 5,
        'mobile_friendly' => 5,
        'canonical' => 5,
    );
    
    /**
     * Obtenir l'instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Analyser une page/post
     */
    public static function analyze_page($post_id) {
        $post = get_post($post_id);
        if (!$post) {
            return array('error' => 'Post not found');
        }
        
        $url = get_permalink($post_id);
        $content = $post->post_content;
        $rendered_content = apply_filters('the_content', $content);
        
        // Récupérer le HTML complet de la page
        $response = wp_remote_get($url, array(
            'timeout' => 30,
            'sslverify' => false
        ));
        
        $html = '';
        if (!is_wp_error($response)) {
            $html = wp_remote_retrieve_body($response);
        }
        
        $results = array(
            'post_id' => $post_id,
            'url' => $url,
            'title' => get_the_title($post_id),
            'score' => 0,
            'checks' => array(),
            'recommendations' => array(),
        );
        
        // 1. Analyse du titre
        $results['checks']['title'] = self::check_title($post_id, $html);
        
        // 2. Analyse de la meta description
        $results['checks']['meta_description'] = self::check_meta_description($post_id, $html);
        
        // 3. Analyse des balises H1
        $results['checks']['h1'] = self::check_h1($html);
        
        // 4. Analyse des images
        $results['checks']['images'] = self::check_images($html, $rendered_content);
        
        // 5. Analyse des liens
        $results['checks']['links'] = self::check_links($html, $url);
        
        // 6. Analyse du contenu
        $results['checks']['content'] = self::check_content($content);
        
        // 7. Vérifications techniques
        $results['checks']['technical'] = self::check_technical($url, $html);
        
        // Calculer le score global
        $results['score'] = self::calculate_score($results['checks']);
        
        // Générer les recommandations
        $results['recommendations'] = self::generate_recommendations($results['checks']);
        
        return $results;
    }
    
    /**
     * Vérifier le titre
     */
    private static function check_title($post_id, $html) {
        $result = array(
            'status' => 'good',
            'title' => '',
            'length' => 0,
            'issues' => array()
        );
        
        // Extraire le titre de la balise <title>
        if (preg_match('/<title[^>]*>([^<]+)<\/title>/i', $html, $matches)) {
            $result['title'] = trim($matches[1]);
            $result['length'] = mb_strlen($result['title']);
        }
        
        // Vérifier la longueur (idéal: 50-60 caractères)
        if ($result['length'] < 30) {
            $result['status'] = 'warning';
            $result['issues'][] = 'Titre trop court (< 30 caractères)';
        } elseif ($result['length'] > 60) {
            $result['status'] = 'warning';
            $result['issues'][] = 'Titre trop long (> 60 caractères), risque de troncature dans Google';
        }
        
        if (empty($result['title'])) {
            $result['status'] = 'error';
            $result['issues'][] = 'Aucun titre trouvé';
        }
        
        return $result;
    }
    
    /**
     * Vérifier la meta description
     */
    private static function check_meta_description($post_id, $html) {
        $result = array(
            'status' => 'good',
            'description' => '',
            'length' => 0,
            'issues' => array()
        );
        
        // Extraire la meta description
        if (preg_match('/<meta[^>]+name=["\']description["\'][^>]+content=["\']([^"\']+)["\'][^>]*>/i', $html, $matches)) {
            $result['description'] = trim($matches[1]);
        } elseif (preg_match('/<meta[^>]+content=["\']([^"\']+)["\'][^>]+name=["\']description["\'][^>]*>/i', $html, $matches)) {
            $result['description'] = trim($matches[1]);
        }
        
        $result['length'] = mb_strlen($result['description']);
        
        if (empty($result['description'])) {
            $result['status'] = 'error';
            $result['issues'][] = 'Aucune meta description trouvée';
        } elseif ($result['length'] < 120) {
            $result['status'] = 'warning';
            $result['issues'][] = 'Meta description trop courte (< 120 caractères)';
        } elseif ($result['length'] > 160) {
            $result['status'] = 'warning';
            $result['issues'][] = 'Meta description trop longue (> 160 caractères)';
        }
        
        return $result;
    }
    
    /**
     * Vérifier les balises H1
     */
    private static function check_h1($html) {
        $result = array(
            'status' => 'good',
            'count' => 0,
            'h1_tags' => array(),
            'issues' => array()
        );
        
        // Compter les H1
        if (preg_match_all('/<h1[^>]*>([^<]+)<\/h1>/i', $html, $matches)) {
            $result['count'] = count($matches[1]);
            $result['h1_tags'] = array_map('trim', $matches[1]);
        }
        
        if ($result['count'] === 0) {
            $result['status'] = 'error';
            $result['issues'][] = 'Aucune balise H1 trouvée';
        } elseif ($result['count'] > 1) {
            $result['status'] = 'warning';
            $result['issues'][] = 'Plusieurs balises H1 trouvées (' . $result['count'] . '). Idéalement, une seule H1 par page.';
        }
        
        return $result;
    }
    
    /**
     * Vérifier les images
     */
    private static function check_images($html, $content) {
        $result = array(
            'status' => 'good',
            'total' => 0,
            'with_alt' => 0,
            'without_alt' => 0,
            'missing_alt' => array(),
            'issues' => array()
        );
        
        // Trouver toutes les images
        if (preg_match_all('/<img[^>]+>/i', $html, $matches)) {
            $result['total'] = count($matches[0]);
            
            foreach ($matches[0] as $img) {
                // Vérifier si l'attribut alt existe et n'est pas vide
                if (preg_match('/alt=["\']([^"\']*)["\']/', $img, $alt_match)) {
                    if (!empty(trim($alt_match[1]))) {
                        $result['with_alt']++;
                    } else {
                        $result['without_alt']++;
                        // Extraire le src pour identifier l'image
                        if (preg_match('/src=["\']([^"\']+)["\']/', $img, $src_match)) {
                            $result['missing_alt'][] = basename($src_match[1]);
                        }
                    }
                } else {
                    $result['without_alt']++;
                    if (preg_match('/src=["\']([^"\']+)["\']/', $img, $src_match)) {
                        $result['missing_alt'][] = basename($src_match[1]);
                    }
                }
            }
        }
        
        if ($result['without_alt'] > 0) {
            $result['status'] = 'warning';
            $result['issues'][] = $result['without_alt'] . ' image(s) sans attribut alt';
        }
        
        if ($result['total'] === 0) {
            $result['issues'][] = 'Aucune image trouvée (les images améliorent l\'engagement)';
        }
        
        return $result;
    }
    
    /**
     * Vérifier les liens
     */
    private static function check_links($html, $current_url) {
        $result = array(
            'status' => 'good',
            'internal' => 0,
            'external' => 0,
            'nofollow' => 0,
            'broken' => array(),
            'issues' => array()
        );
        
        $site_host = parse_url(home_url(), PHP_URL_HOST);
        
        // Trouver tous les liens
        if (preg_match_all('/<a[^>]+href=["\']([^"\']+)["\'][^>]*>/i', $html, $matches)) {
            foreach ($matches[1] as $index => $href) {
                // Ignorer les ancres et javascript
                if (strpos($href, '#') === 0 || strpos($href, 'javascript:') === 0 || strpos($href, 'mailto:') === 0 || strpos($href, 'tel:') === 0) {
                    continue;
                }
                
                $link_host = parse_url($href, PHP_URL_HOST);
                
                if (empty($link_host) || $link_host === $site_host) {
                    $result['internal']++;
                } else {
                    $result['external']++;
                }
                
                // Vérifier nofollow
                if (preg_match('/rel=["\'][^"\']*nofollow[^"\']*["\']/', $matches[0][$index])) {
                    $result['nofollow']++;
                }
            }
        }
        
        if ($result['internal'] < 2) {
            $result['status'] = 'warning';
            $result['issues'][] = 'Peu de liens internes (< 2). Ajoutez des liens vers d\'autres pages du site.';
        }
        
        if ($result['external'] === 0) {
            $result['issues'][] = 'Aucun lien externe. Les liens vers des sources fiables peuvent améliorer le SEO.';
        }
        
        return $result;
    }
    
    /**
     * Vérifier le contenu
     */
    private static function check_content($content) {
        $result = array(
            'status' => 'good',
            'word_count' => 0,
            'paragraph_count' => 0,
            'issues' => array()
        );
        
        // Nettoyer le contenu
        $text = wp_strip_all_tags($content);
        $text = preg_replace('/\s+/', ' ', $text);
        
        // Compter les mots
        $result['word_count'] = str_word_count($text);
        
        // Compter les paragraphes
        $result['paragraph_count'] = substr_count($content, '</p>');
        
        if ($result['word_count'] < 300) {
            $result['status'] = 'warning';
            $result['issues'][] = 'Contenu court (< 300 mots). Google préfère les contenus plus longs et détaillés.';
        } elseif ($result['word_count'] < 600) {
            $result['issues'][] = 'Contenu moyen. Visez 600+ mots pour un meilleur référencement.';
        }
        
        return $result;
    }
    
    /**
     * Vérifications techniques
     */
    private static function check_technical($url, $html) {
        $result = array(
            'status' => 'good',
            'https' => false,
            'canonical' => '',
            'viewport' => false,
            'robots' => '',
            'structured_data' => false,
            'issues' => array()
        );
        
        // HTTPS
        $result['https'] = (strpos($url, 'https://') === 0);
        if (!$result['https']) {
            $result['status'] = 'error';
            $result['issues'][] = 'Le site n\'utilise pas HTTPS';
        }
        
        // Canonical
        if (preg_match('/<link[^>]+rel=["\']canonical["\'][^>]+href=["\']([^"\']+)["\'][^>]*>/i', $html, $matches)) {
            $result['canonical'] = $matches[1];
        } elseif (preg_match('/<link[^>]+href=["\']([^"\']+)["\'][^>]+rel=["\']canonical["\'][^>]*>/i', $html, $matches)) {
            $result['canonical'] = $matches[1];
        }
        
        if (empty($result['canonical'])) {
            $result['issues'][] = 'Aucune URL canonique définie';
        }
        
        // Viewport (mobile-friendly)
        $result['viewport'] = (bool) preg_match('/<meta[^>]+name=["\']viewport["\'][^>]*>/i', $html);
        if (!$result['viewport']) {
            $result['status'] = 'warning';
            $result['issues'][] = 'Pas de balise viewport (problème mobile)';
        }
        
        // Robots meta
        if (preg_match('/<meta[^>]+name=["\']robots["\'][^>]+content=["\']([^"\']+)["\'][^>]*>/i', $html, $matches)) {
            $result['robots'] = $matches[1];
            if (strpos($result['robots'], 'noindex') !== false) {
                $result['status'] = 'error';
                $result['issues'][] = 'Page marquée comme noindex (ne sera pas indexée par Google)';
            }
        }
        
        // Données structurées (Schema.org)
        $result['structured_data'] = (
            strpos($html, 'application/ld+json') !== false ||
            strpos($html, 'itemtype="http://schema.org') !== false ||
            strpos($html, 'itemtype="https://schema.org') !== false
        );
        
        if (!$result['structured_data']) {
            $result['issues'][] = 'Aucune donnée structurée (Schema.org) détectée';
        }
        
        return $result;
    }
    
    /**
     * Calculer le score global
     */
    private static function calculate_score($checks) {
        $score = 100;
        $deductions = 0;
        
        // Titre
        if ($checks['title']['status'] === 'error') $deductions += 15;
        elseif ($checks['title']['status'] === 'warning') $deductions += 7;
        
        // Meta description
        if ($checks['meta_description']['status'] === 'error') $deductions += 12;
        elseif ($checks['meta_description']['status'] === 'warning') $deductions += 5;
        
        // H1
        if ($checks['h1']['status'] === 'error') $deductions += 15;
        elseif ($checks['h1']['status'] === 'warning') $deductions += 5;
        
        // Images
        if ($checks['images']['status'] === 'warning') {
            $deductions += min(10, $checks['images']['without_alt'] * 2);
        }
        
        // Liens
        if ($checks['links']['status'] === 'warning') $deductions += 8;
        
        // Contenu
        if ($checks['content']['status'] === 'warning') $deductions += 10;
        
        // Technique
        if ($checks['technical']['status'] === 'error') $deductions += 15;
        elseif ($checks['technical']['status'] === 'warning') $deductions += 8;
        
        return max(0, $score - $deductions);
    }
    
    /**
     * Générer les recommandations
     */
    private static function generate_recommendations($checks) {
        $recommendations = array();
        
        foreach ($checks as $category => $check) {
            if (!empty($check['issues'])) {
                foreach ($check['issues'] as $issue) {
                    $priority = ($check['status'] === 'error') ? 'high' : 'medium';
                    $recommendations[] = array(
                        'category' => $category,
                        'priority' => $priority,
                        'message' => $issue
                    );
                }
            }
        }
        
        // Trier par priorité
        usort($recommendations, function($a, $b) {
            $priority_order = array('high' => 0, 'medium' => 1, 'low' => 2);
            return $priority_order[$a['priority']] - $priority_order[$b['priority']];
        });
        
        return $recommendations;
    }
    
    /**
     * Analyser toutes les pages du site
     */
    public static function analyze_all_pages() {
        $results = array();
        
        // Récupérer tous les posts et pages publiés
        $posts = get_posts(array(
            'post_type' => array('post', 'page', 'realisation'),
            'post_status' => 'publish',
            'numberposts' => -1,
        ));
        
        foreach ($posts as $post) {
            $results[] = self::analyze_page($post->ID);
        }
        
        // Trier par score (du plus bas au plus haut)
        usort($results, function($a, $b) {
            return $a['score'] - $b['score'];
        });
        
        return $results;
    }
    
    /**
     * Obtenir le résumé SEO global
     */
    public static function get_seo_summary() {
        $all_results = self::analyze_all_pages();
        
        $summary = array(
            'total_pages' => count($all_results),
            'average_score' => 0,
            'excellent' => 0, // 90-100
            'good' => 0,      // 70-89
            'needs_work' => 0, // 50-69
            'poor' => 0,      // 0-49
            'common_issues' => array(),
            'pages' => $all_results
        );
        
        $total_score = 0;
        $issue_counts = array();
        
        foreach ($all_results as $result) {
            $total_score += $result['score'];
            
            if ($result['score'] >= 90) $summary['excellent']++;
            elseif ($result['score'] >= 70) $summary['good']++;
            elseif ($result['score'] >= 50) $summary['needs_work']++;
            else $summary['poor']++;
            
            // Compter les problèmes communs
            foreach ($result['recommendations'] as $rec) {
                $key = $rec['message'];
                if (!isset($issue_counts[$key])) {
                    $issue_counts[$key] = array(
                        'count' => 0,
                        'priority' => $rec['priority']
                    );
                }
                $issue_counts[$key]['count']++;
            }
        }
        
        $summary['average_score'] = $summary['total_pages'] > 0 
            ? round($total_score / $summary['total_pages']) 
            : 0;
        
        // Top 5 problèmes les plus fréquents
        arsort($issue_counts);
        $summary['common_issues'] = array_slice($issue_counts, 0, 5, true);
        
        return $summary;
    }
    
    /**
     * Vérifier le fichier robots.txt
     */
    public static function check_robots_txt() {
        $robots_url = home_url('/robots.txt');
        $response = wp_remote_get($robots_url);
        
        $result = array(
            'exists' => false,
            'content' => '',
            'issues' => array(),
            'recommendations' => array()
        );
        
        if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
            $result['exists'] = true;
            $result['content'] = wp_remote_retrieve_body($response);
            
            // Vérifier le contenu
            if (strpos($result['content'], 'Disallow: /') !== false && 
                strpos($result['content'], 'Disallow: /wp-admin') === false) {
                $result['issues'][] = 'Attention: Le site semble bloquer tous les robots';
            }
            
            if (strpos($result['content'], 'Sitemap:') === false) {
                $result['recommendations'][] = 'Ajoutez l\'URL du sitemap dans robots.txt';
            }
        } else {
            $result['recommendations'][] = 'Créez un fichier robots.txt';
        }
        
        return $result;
    }
    
    /**
     * Vérifier le sitemap
     */
    public static function check_sitemap() {
        $sitemap_urls = array(
            home_url('/sitemap.xml'),
            home_url('/sitemap_index.xml'),
            home_url('/wp-sitemap.xml'),
        );
        
        $result = array(
            'exists' => false,
            'url' => '',
            'pages_count' => 0,
            'issues' => array()
        );
        
        foreach ($sitemap_urls as $url) {
            $response = wp_remote_get($url);
            if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
                $result['exists'] = true;
                $result['url'] = $url;
                
                $content = wp_remote_retrieve_body($response);
                $result['pages_count'] = substr_count($content, '<url>') + substr_count($content, '<sitemap>');
                break;
            }
        }
        
        if (!$result['exists']) {
            $result['issues'][] = 'Aucun sitemap trouvé. Installez un plugin de sitemap ou utilisez le sitemap WordPress natif.';
        }
        
        return $result;
    }
}
