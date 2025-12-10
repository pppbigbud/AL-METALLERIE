<?php
/**
 * Fonctionnalités publiques du plugin
 *
 * @package CityPagesGenerator
 */

if (!defined('ABSPATH')) {
    exit;
}

class CPG_Public {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('admin_post_cpg_submit_contact', [$this, 'handle_contact_form']);
        add_action('admin_post_nopriv_cpg_submit_contact', [$this, 'handle_contact_form']);
    }

    /**
     * Charger les assets publics
     */
    public function enqueue_assets() {
        if (!is_singular('city_page') && !is_post_type_archive('city_page')) {
            return;
        }

        wp_enqueue_style(
            'cpg-public',
            CPG_PLUGIN_URL . 'public/css/public.css',
            [],
            CPG_VERSION
        );

        wp_enqueue_script(
            'cpg-public',
            CPG_PLUGIN_URL . 'public/js/public.js',
            ['jquery'],
            CPG_VERSION,
            true
        );

        wp_localize_script('cpg-public', 'cpgPublic', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cpg_public_nonce'),
        ]);
    }

    /**
     * Traiter le formulaire de contact
     */
    public function handle_contact_form() {
        // Vérifier le nonce
        if (!isset($_POST['cpg_contact_nonce']) || !wp_verify_nonce($_POST['cpg_contact_nonce'], 'cpg_contact_form')) {
            wp_die(__('Erreur de sécurité.', 'city-pages-generator'));
        }

        // Récupérer les données
        $name = sanitize_text_field($_POST['cpg_name'] ?? '');
        $email = sanitize_email($_POST['cpg_email'] ?? '');
        $phone = sanitize_text_field($_POST['cpg_phone'] ?? '');
        $service = sanitize_text_field($_POST['cpg_service'] ?? '');
        $message = sanitize_textarea_field($_POST['cpg_message'] ?? '');
        $city = sanitize_text_field($_POST['cpg_city'] ?? '');

        // Validation
        if (empty($name) || empty($email) || empty($message)) {
            wp_redirect(add_query_arg('cpg_error', 'required', wp_get_referer()));
            exit;
        }

        if (!is_email($email)) {
            wp_redirect(add_query_arg('cpg_error', 'email', wp_get_referer()));
            exit;
        }

        // Préparer l'email
        $settings = get_option('cpg_settings', []);
        $to = isset($settings['email']) ? $settings['email'] : get_option('admin_email');
        $subject = sprintf('[AL Métallerie] Demande depuis la page %s', $city);

        $body = sprintf(
            "Nouvelle demande de contact depuis la page ville : %s\n\n" .
            "Nom : %s\n" .
            "Email : %s\n" .
            "Téléphone : %s\n" .
            "Service souhaité : %s\n\n" .
            "Message :\n%s",
            $city,
            $name,
            $email,
            $phone ?: 'Non renseigné',
            $service ?: 'Non précisé',
            $message
        );

        $headers = [
            'Content-Type: text/plain; charset=UTF-8',
            sprintf('Reply-To: %s <%s>', $name, $email),
        ];

        // Envoyer l'email
        $sent = wp_mail($to, $subject, $body, $headers);

        if ($sent) {
            wp_redirect(add_query_arg('cpg_success', '1', wp_get_referer()));
        } else {
            wp_redirect(add_query_arg('cpg_error', 'send', wp_get_referer()));
        }
        exit;
    }
}
