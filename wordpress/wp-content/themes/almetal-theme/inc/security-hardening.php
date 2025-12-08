<?php
/**
 * Renforcement de la sécurité WordPress
 * 
 * @package ALMetallerie
 * @since 2.0.0
 */

// Sécurité : empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/**
 * ============================================
 * 1. DÉSACTIVER L'ÉDITEUR DE FICHIERS
 * ============================================
 * Empêche la modification des fichiers PHP depuis l'admin
 * Note: Ajoutez aussi dans wp-config.php: define('DISALLOW_FILE_EDIT', true);
 */
if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
}

/**
 * ============================================
 * 2. MASQUER LA VERSION DE WORDPRESS
 * ============================================
 */
function almetal_remove_wp_version() {
    return '';
}
add_filter('the_generator', 'almetal_remove_wp_version');

// Supprimer la version des scripts et styles
function almetal_remove_version_from_assets($src) {
    if (strpos($src, 'ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'almetal_remove_version_from_assets', 9999);
add_filter('script_loader_src', 'almetal_remove_version_from_assets', 9999);

/**
 * ============================================
 * 3. DÉSACTIVER XML-RPC (si non utilisé)
 * ============================================
 */
add_filter('xmlrpc_enabled', '__return_false');

// Supprimer le header X-Pingback
function almetal_remove_x_pingback($headers) {
    unset($headers['X-Pingback']);
    return $headers;
}
add_filter('wp_headers', 'almetal_remove_x_pingback');

/**
 * ============================================
 * 4. AJOUTER DES HEADERS DE SÉCURITÉ
 * ============================================
 */
function almetal_security_headers() {
    // Ne pas exécuter dans l'admin
    if (is_admin()) {
        return;
    }
    
    // X-Content-Type-Options
    header('X-Content-Type-Options: nosniff');
    
    // X-Frame-Options (protection contre le clickjacking)
    header('X-Frame-Options: SAMEORIGIN');
    
    // X-XSS-Protection
    header('X-XSS-Protection: 1; mode=block');
    
    // Referrer-Policy
    header('Referrer-Policy: strict-origin-when-cross-origin');
    
    // Permissions-Policy (remplace Feature-Policy)
    header("Permissions-Policy: geolocation=(), microphone=(), camera=()");
}
add_action('send_headers', 'almetal_security_headers');

/**
 * ============================================
 * 5. DÉSACTIVER L'ÉNUMÉRATION DES UTILISATEURS
 * ============================================
 */
function almetal_disable_user_enumeration() {
    if (is_admin()) {
        return;
    }
    
    // Bloquer ?author=N
    if (isset($_REQUEST['author']) && is_numeric($_REQUEST['author'])) {
        wp_redirect(home_url(), 301);
        exit;
    }
}
add_action('init', 'almetal_disable_user_enumeration');

// Bloquer l'API REST pour les utilisateurs non connectés
function almetal_restrict_rest_api_users($result, $server, $request) {
    $route = $request->get_route();
    
    // Bloquer l'accès à /wp/v2/users pour les non-connectés
    if (strpos($route, '/wp/v2/users') !== false && !is_user_logged_in()) {
        return new WP_Error(
            'rest_forbidden',
            'Accès non autorisé.',
            array('status' => 403)
        );
    }
    
    return $result;
}
add_filter('rest_pre_dispatch', 'almetal_restrict_rest_api_users', 10, 3);

/**
 * ============================================
 * 6. LIMITER LES TENTATIVES DE CONNEXION
 * ============================================
 */
function almetal_limit_login_attempts($user, $username, $password) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $transient_key = 'login_attempts_' . md5($ip);
    $attempts = get_transient($transient_key);
    
    if ($attempts === false) {
        $attempts = 0;
    }
    
    // Bloquer après 5 tentatives pendant 15 minutes
    if ($attempts >= 5) {
        return new WP_Error(
            'too_many_attempts',
            sprintf(
                'Trop de tentatives de connexion. Veuillez réessayer dans %d minutes.',
                15
            )
        );
    }
    
    return $user;
}
add_filter('authenticate', 'almetal_limit_login_attempts', 30, 3);

function almetal_track_failed_login($username) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $transient_key = 'login_attempts_' . md5($ip);
    $attempts = get_transient($transient_key);
    
    if ($attempts === false) {
        $attempts = 0;
    }
    
    $attempts++;
    set_transient($transient_key, $attempts, 15 * MINUTE_IN_SECONDS);
    
    // Log les tentatives échouées
    error_log(sprintf(
        'AL Métallerie Security: Tentative de connexion échouée pour "%s" depuis %s (tentative #%d)',
        $username,
        $ip,
        $attempts
    ));
}
add_action('wp_login_failed', 'almetal_track_failed_login');

function almetal_reset_login_attempts($username, $user) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $transient_key = 'login_attempts_' . md5($ip);
    delete_transient($transient_key);
}
add_action('wp_login', 'almetal_reset_login_attempts', 10, 2);

/**
 * ============================================
 * 7. DÉSACTIVER L'EXÉCUTION PHP DANS UPLOADS
 * ============================================
 * Note: Ceci doit aussi être fait via .htaccess
 */
function almetal_check_upload_security() {
    $htaccess_path = WP_CONTENT_DIR . '/uploads/.htaccess';
    
    if (!file_exists($htaccess_path)) {
        $htaccess_content = "# Désactiver l'exécution PHP dans ce dossier\n";
        $htaccess_content .= "<Files *.php>\n";
        $htaccess_content .= "deny from all\n";
        $htaccess_content .= "</Files>\n";
        
        @file_put_contents($htaccess_path, $htaccess_content);
    }
}
add_action('admin_init', 'almetal_check_upload_security');

/**
 * ============================================
 * 8. FORCER HTTPS
 * ============================================
 */
function almetal_force_https() {
    if (!is_ssl() && !is_admin()) {
        $redirect_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        wp_redirect($redirect_url, 301);
        exit;
    }
}
// Décommenter si HTTPS est configuré
// add_action('template_redirect', 'almetal_force_https');

/**
 * ============================================
 * 9. NETTOYER LE HEAD WORDPRESS
 * ============================================
 */
function almetal_clean_wp_head() {
    // Supprimer les liens inutiles
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
    
    // Supprimer les emojis
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
}
add_action('init', 'almetal_clean_wp_head');

/**
 * ============================================
 * 10. PROTECTION CONTRE LE SPAM DE COMMENTAIRES
 * ============================================
 */
function almetal_block_comment_spam() {
    // Si les commentaires sont désactivés, bloquer toute tentative
    if (!comments_open()) {
        return;
    }
    
    // Vérifier le referrer
    if (!isset($_SERVER['HTTP_REFERER']) || 
        strpos($_SERVER['HTTP_REFERER'], home_url()) === false) {
        wp_die('Commentaire non autorisé.');
    }
}
add_action('pre_comment_on_post', 'almetal_block_comment_spam');

/**
 * ============================================
 * 11. AUDIT LOG DES ACTIONS SENSIBLES
 * ============================================
 */
function almetal_log_sensitive_actions($action, $details = '') {
    $user = wp_get_current_user();
    $ip = $_SERVER['REMOTE_ADDR'];
    
    $log_message = sprintf(
        '[%s] Action: %s | User: %s (ID: %d) | IP: %s | Details: %s',
        current_time('mysql'),
        $action,
        $user->user_login ?: 'Anonymous',
        $user->ID ?: 0,
        $ip,
        $details
    );
    
    error_log('AL Métallerie Security: ' . $log_message);
}

// Logger les changements de mot de passe
function almetal_log_password_change($user_id) {
    almetal_log_sensitive_actions('password_change', 'User ID: ' . $user_id);
}
add_action('after_password_reset', 'almetal_log_password_change');
add_action('profile_update', function($user_id, $old_user_data) {
    $user = get_userdata($user_id);
    if ($user->user_pass !== $old_user_data->user_pass) {
        almetal_log_sensitive_actions('password_change', 'User ID: ' . $user_id);
    }
}, 10, 2);

// Logger les connexions admin
function almetal_log_admin_login($user_login, $user) {
    if (user_can($user, 'manage_options')) {
        almetal_log_sensitive_actions('admin_login', 'Username: ' . $user_login);
    }
}
add_action('wp_login', 'almetal_log_admin_login', 10, 2);
