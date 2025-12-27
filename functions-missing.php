<?php
/**
 * Fonctions manquantes pour corriger les erreurs fatales
 * À ajouter dans functions.php du thème
 */

// Fonction manquante pour les liens de ville
if (!function_exists('almetal_city_link_html')) {
    function almetal_city_link_html($city_name) {
        // Retourne un lien simple vers la page ville
        $city_slug = sanitize_title($city_name);
        $url = home_url("/metallier-{$city_slug}/");
        return '<a href="' . esc_url($url) . '" class="city-link">' . esc_html($city_name) . '</a>';
    }
}

// Fonction manquante pour les avis Google
if (!function_exists('almetal_render_google_reviews_widget')) {
    function almetal_render_google_reviews_widget() {
        // Retourne un widget simple ou vide pour éviter l'erreur
        ob_start();
        ?>
        <div class="google-reviews-widget">
            <p>Avis Google</p>
            <!-- Widget à implémenter plus tard -->
        </div>
        <?php
        return ob_get_clean();
    }
}

?>
