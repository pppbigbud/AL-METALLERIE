<?php
/**
 * Helper pour récupérer l'image hero des pages ville (desktop + mobile)
 * Sélectionne aléatoirement une image depuis les réalisations avec fallbacks.
 */

if (!function_exists('almetal_get_city_hero_background_url')) {
    function almetal_get_city_hero_background_url($city_post_id = null) {
        $city_post_id = $city_post_id ? (int) $city_post_id : (int) get_the_ID();

        $get_realisation_image_url = function ($realisation_id) {
            $thumb = get_the_post_thumbnail_url($realisation_id, 'full');
            if ($thumb) return $thumb;

            $gallery_images = get_post_meta($realisation_id, '_almetal_gallery_images', true);
            if (is_string($gallery_images)) $gallery_images = wp_parse_id_list($gallery_images);
            if (is_array($gallery_images) && !empty($gallery_images)) {
                $url = wp_get_attachment_image_url((int) reset($gallery_images), 'full');
                if ($url) return $url;
            }

            $attachments = get_posts(array(
                'post_type' => 'attachment',
                'posts_per_page' => 1,
                'post_parent' => $realisation_id,
                'post_mime_type' => 'image',
                'orderby' => 'menu_order',
                'order' => 'ASC',
                'fields' => 'ids',
            ));
            if (!empty($attachments)) {
                $url = wp_get_attachment_image_url((int) $attachments[0], 'full');
                if ($url) return $url;
            }
            return null;
        };

        $bg_url = null;
        $target_terms = array('garde-corps', 'garde-corps-metallique', 'portails', 'portail', 'escaliers', 'escalier');

        $random_realisation_id = get_posts(array(
            'post_type' => 'realisation',
            'posts_per_page' => 1,
            'orderby' => 'rand',
            'fields' => 'ids',
            'tax_query' => array(array('taxonomy' => 'type_realisation', 'field' => 'slug', 'terms' => $target_terms)),
        ));
        if (!empty($random_realisation_id)) {
            $bg_url = $get_realisation_image_url((int) $random_realisation_id[0]);
        }

        if (!$bg_url) {
            $any_realisation_id = get_posts(array(
                'post_type' => 'realisation',
                'posts_per_page' => 1,
                'orderby' => 'rand',
                'fields' => 'ids',
            ));
            if (!empty($any_realisation_id)) {
                $bg_url = $get_realisation_image_url((int) $any_realisation_id[0]);
            }
        }

        if (!$bg_url && has_post_thumbnail($city_post_id)) {
            $bg_url = get_the_post_thumbnail_url($city_post_id, 'full');
        }

        if (!$bg_url) {
            $bg_url = get_template_directory_uri() . '/assets/images/hero/hero-1.png';
        }

        return $bg_url;
    }
}
