<?php
/**
 * Systeme centralise de gestion des icones SVG pour les categories de realisations
 * Genere automatiquement des icones pour les nouvelles categories
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

// Securite
if (!defined('ABSPATH')) {
    exit;
}

// Éviter la redéclaration si le fichier est déjà chargé par un autre include
if (function_exists('almetal_get_category_icon')) {
    return;
}

/**
 * Bibliotheque d'icones SVG pour les categories connues
 * Taille par defaut: 24x24 (peut etre modifiee via le parametre $size)
 */
if (!function_exists('almetal_get_category_icons_library')) {
    function almetal_get_category_icons_library() {
        return array(
            // Categories principales
            'portails' => '<rect x="3" y="3" width="7" height="18" rx="1"/><rect x="14" y="3" width="7" height="18" rx="1"/>',
            'garde-corps' => '<path d="M3 12h18M3 6h18M3 18h18"/><circle cx="6" cy="12" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="18" cy="12" r="1"/>',
            'escaliers' => '<path d="M6 20h4v-4h4v-4h4V8h4"/>',
            'pergolas' => '<path d="M3 21h18M4 18h16M5 15h14M6 12h12M7 9h10M8 6h8M9 3h6"/>',
            'grilles' => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18M15 3v18"/>',
            'ferronnerie-art' => '<path d="M4 20c4-4 4-12 8-12s4 8 8 12"/><path d="M4 16c3-3 3-8 6-8s3 5 6 8"/><circle cx="12" cy="8" r="2"/>',
            'ferronnerie-dart' => '<path d="M4 20c4-4 4-12 8-12s4 8 8 12"/><path d="M4 16c3-3 3-8 6-8s3 5 6 8"/><circle cx="12" cy="8" r="2"/>',
            'vehicules' => '<path d="M5 17h14v-5l-2-4H7l-2 4v5z"/><path d="M3 17h18v2H3z"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/><path d="M5 12h14"/>',
            'serrurerie' => '<rect x="5" y="11" width="14" height="10" rx="2"/><path d="M12 16v2"/><circle cx="12" cy="16" r="1"/><path d="M8 11V7a4 4 0 1 1 8 0v4"/>',
            'mobilier-metallique' => '<rect x="4" y="6" width="16" height="4" rx="1"/><path d="M6 10v10M18 10v10"/><path d="M4 14h16"/>',
            'rampes' => '<path d="M3 21l18-12"/><path d="M3 21v-6"/><path d="M9 17v4"/><path d="M15 13v8"/>',
            'verrieres' => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 12h18"/><path d="M12 3v18"/><path d="M7.5 3v9M16.5 3v9"/>',
            'clotures' => '<path d="M4 4v16M10 4v16M16 4v16M22 4v16"/><path d="M4 8h18M4 16h18"/>',
            'balcons' => '<rect x="2" y="12" width="20" height="8" rx="1"/><path d="M6 12V8M12 12V6M18 12V8"/><path d="M2 12h20"/>',
            'auvents' => '<path d="M3 8l9-5 9 5"/><path d="M3 8v12M21 8v12"/><path d="M3 20h18"/>',
            'marquises' => '<path d="M4 10c0-3 3-6 8-6s8 3 8 6"/><path d="M4 10v8M20 10v8"/><path d="M4 18h16"/>',
            'portes' => '<rect x="5" y="2" width="14" height="20" rx="2"/><circle cx="16" cy="12" r="1"/>',
            'fenetres' => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 12h18M12 3v18"/>',
            'tables' => '<rect x="2" y="6" width="20" height="3" rx="1"/><path d="M5 9v11M19 9v11"/><path d="M5 14h14"/>',
            'chaises' => '<path d="M6 20v-8M18 20v-8"/><rect x="4" y="8" width="16" height="4" rx="1"/><path d="M6 8V4M18 8V4"/><path d="M6 4h12"/>',
            'etageres' => '<path d="M4 4h16M4 10h16M4 16h16M4 22h16"/><path d="M4 4v18M20 4v18"/>',
            'braseros' => '<circle cx="12" cy="14" r="6"/><path d="M12 8v-4M8 9l-2-3M16 9l2-3"/><path d="M6 20h12"/>',
            'barbecues' => '<rect x="4" y="10" width="16" height="8" rx="2"/><path d="M8 10V6M16 10V6"/><path d="M4 18v2M20 18v2"/><path d="M8 14h8"/>',
            
            // Icone par defaut (outil/cle)
            'default' => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
        );
    }
}

/**
 * Obtenir l'icone SVG pour une categorie
 * 
 * @param string $slug Le slug de la categorie
 * @param int $size La taille de l'icone (defaut: 24)
 * @return string Le code SVG complet
 */
if (!function_exists('almetal_get_category_icon')) {
    function almetal_get_category_icon($slug, $size = 24) {
        $icons = almetal_get_category_icons_library();
        
        // Normaliser le slug
        $slug = sanitize_title($slug);
        
        // Chercher une correspondance exacte
        if (isset($icons[$slug])) {
            $path = $icons[$slug];
        } else {
            // Chercher une correspondance partielle basee sur des mots-cles
            $path = almetal_find_icon_by_keywords($slug, $icons);
        }
        
        // Construire le SVG complet
        return sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">%s</svg>',
            $size,
            $size,
            $path
        );
    }
}

/**
 * Trouver une icone basee sur des mots-cles dans le slug
 * 
 * @param string $slug Le slug a analyser
 * @param array $icons La bibliotheque d'icones
 * @return string Le path SVG trouve ou l'icone par defaut
 */
if (!function_exists('almetal_find_icon_by_keywords')) {
    function almetal_find_icon_by_keywords($slug, $icons) {
        // Mots-cles associes a des icones
        $keywords_map = array(
            'portail' => 'portails',
            'porte' => 'portes',
            'garde' => 'garde-corps',
            'rambarde' => 'garde-corps',
            'balustrade' => 'garde-corps',
            'escalier' => 'escaliers',
            'marche' => 'escaliers',
            'pergola' => 'pergolas',
            'tonnelle' => 'pergolas',
            'grille' => 'grilles',
            'cloture' => 'clotures',
            'ferronnerie' => 'ferronnerie-art',
            'forge' => 'ferronnerie-art',
            'art' => 'ferronnerie-art',
            'decoratif' => 'ferronnerie-art',
            'vehicule' => 'vehicules',
            'voiture' => 'vehicules',
            'auto' => 'vehicules',
            'serrure' => 'serrurerie',
            'verrou' => 'serrurerie',
            'mobilier' => 'mobilier-metallique',
            'meuble' => 'mobilier-metallique',
            'table' => 'tables',
            'chaise' => 'chaises',
            'etagere' => 'etageres',
            'rampe' => 'rampes',
            'main-courante' => 'rampes',
            'verriere' => 'verrieres',
            'vitrage' => 'verrieres',
            'balcon' => 'balcons',
            'terrasse' => 'balcons',
            'auvent' => 'auvents',
            'abri' => 'auvents',
            'marquise' => 'marquises',
            'fenetre' => 'fenetres',
            'brasero' => 'braseros',
            'feu' => 'braseros',
            'barbecue' => 'barbecues',
            'grill' => 'barbecues',
        );
        
        // Chercher des correspondances
        foreach ($keywords_map as $keyword => $icon_key) {
            if (strpos($slug, $keyword) !== false) {
                if (isset($icons[$icon_key])) {
                    return $icons[$icon_key];
                }
            }
        }
        
        // Retourner l'icone par defaut
        return $icons['default'];
    }
}

/**
 * Obtenir toutes les icones pour les categories existantes
 * Utile pour le mega menu et les filtres
 * 
 * @param int $size La taille des icones
 * @return array Tableau associatif slug => SVG
 */
if (!function_exists('almetal_get_all_category_icons')) {
    function almetal_get_all_category_icons($size = 24) {
        $terms = get_terms(array(
            'taxonomy' => 'type_realisation',
            'hide_empty' => false,
        ));
        
        $icons = array();
        
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $icons[$term->slug] = almetal_get_category_icon($term->slug, $size);
            }
        }
        
        return $icons;
    }
}

/**
 * Shortcode pour afficher une icone de categorie
 * Usage: [category_icon slug="portails" size="32"]
 */
if (!function_exists('almetal_category_icon_shortcode')) {
    function almetal_category_icon_shortcode($atts) {
        $atts = shortcode_atts(array(
            'slug' => 'default',
            'size' => 24,
        ), $atts);

        return almetal_get_category_icon($atts['slug'], intval($atts['size']));
    }
}

if (function_exists('shortcode_exists') && !shortcode_exists('category_icon')) {
    add_shortcode('category_icon', 'almetal_category_icon_shortcode');
}
