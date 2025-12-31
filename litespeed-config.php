<?php
/**
 * Configuration LiteSpeed Cache pour gérer correctement le mobile
 * Ce fichier configure LiteSpeed pour qu'il ne cache pas les pages mobiles
 * ou utilise des caches séparés pour desktop/mobile
 */

// Ajouter les headers pour éviter le cache sur mobile
add_action('wp', 'almetal_litespeed_mobile_headers', 5);

function almetal_litespeed_mobile_headers() {
    // Si c'est un appareil mobile, ne pas mettre en cache
    if (almetal_is_mobile() || wp_is_mobile()) {
        if (!headers_sent()) {
            header('Cache-Control: no-cache, no-store, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');
        }
        
        // Ajouter un cookie pour identifier les mobiles
        if (!isset($_COOKIE['lscwp_is_mobile'])) {
            setcookie('lscwp_is_mobile', '1', time() + (86400 * 30), '/');
        }
    }
}

// Filtrer la configuration de LiteSpeed pour exclure le mobile
add_filter('litespeed_cache_api_control', 'almetal_litespeed_exclude_mobile');

function almetal_litespeed_exclude_mobile($control) {
    if (almetal_is_mobile() || wp_is_mobile()) {
        $control['set_private'] = true;
        $control['set_no_vary'] = false;
    }
    return $control;
}

// Vider le cache quand on passe de desktop à mobile ou inversement
add_action('wp_loaded', 'almetal_check_device_change');

function almetal_check_device_change() {
    if (isset($_COOKIE['lscwp_device'])) {
        $current_device = almetal_is_mobile() ? 'mobile' : 'desktop';
        if ($_COOKIE['lscwp_device'] !== $current_device) {
            // Forcer le rechargement sans cache
            if (!headers_sent()) {
                header('Cache-Control: no-cache, no-store, must-revalidate');
                header('Pragma: no-cache');
                header('Expires: 0');
            }
        }
    }
    
    // Mettre à jour le cookie de device
    setcookie('lscwp_device', almetal_is_mobile() ? 'mobile' : 'desktop', time() + (86400 * 30), '/');
}

// Exclure les scripts mobiles de la minification
add_filter('litespeed_cache_optm_css_excludes', 'almetal_litespeed_exclude_mobile_css');
add_filter('litespeed_cache_optm_js_excludes', 'almetal_litespeed_exclude_mobile_js');

function almetal_litespeed_exclude_mobile_css($excludes) {
    if (almetal_is_mobile()) {
        $excludes[] = 'mobile-*.css';
        $excludes[] = 'taxonomy-seo.css';
    }
    return $excludes;
}

function almetal_litespeed_exclude_mobile_js($excludes) {
    if (almetal_is_mobile()) {
        $excludes[] = 'mobile-*.js';
        $excludes[] = 'taxonomy-*.js';
        $excludes[] = 'leaflet.js';
        $excludes[] = 'intervention-map.js';
    }
    return $excludes;
}
