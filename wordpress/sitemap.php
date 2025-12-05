<?php
/**
 * Sitemap XML dynamique pour AL Métallerie
 * Accès direct : https://al-metallerie.fr/sitemap.php
 */

// Charger WordPress
require_once(dirname(__FILE__) . '/wp-load.php');

// Headers XML
header('Content-Type: application/xml; charset=utf-8');
header('X-Robots-Tag: noindex, follow');
header('Cache-Control: max-age=3600');

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">

    <!-- Page d'accueil -->
    <url>
        <loc><?php echo home_url('/'); ?></loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>

<?php
// Pages principales
$pages = array(
    'realisations' => array('priority' => '0.9', 'changefreq' => 'weekly'),
    'formations' => array('priority' => '0.8', 'changefreq' => 'monthly'),
    'contact' => array('priority' => '0.9', 'changefreq' => 'monthly'),
    'mentions-legales' => array('priority' => '0.3', 'changefreq' => 'yearly'),
    'politique-confidentialite' => array('priority' => '0.3', 'changefreq' => 'yearly'),
);

foreach ($pages as $slug => $data) :
    $page = get_page_by_path($slug);
    if ($page) :
?>
    <url>
        <loc><?php echo get_permalink($page); ?></loc>
        <lastmod><?php echo get_the_modified_date('Y-m-d', $page); ?></lastmod>
        <changefreq><?php echo $data['changefreq']; ?></changefreq>
        <priority><?php echo $data['priority']; ?></priority>
    </url>
<?php 
    endif;
endforeach;

// Catégories de réalisations
$categories = get_terms(array(
    'taxonomy' => 'type_realisation',
    'hide_empty' => true,
));

if (!is_wp_error($categories)) :
    foreach ($categories as $category) :
?>
    <url>
        <loc><?php echo get_term_link($category); ?></loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
<?php 
    endforeach;
endif;

// Toutes les réalisations
$realisations = get_posts(array(
    'post_type' => 'realisation',
    'posts_per_page' => -1,
    'post_status' => 'publish',
));

foreach ($realisations as $realisation) :
    $image_url = get_the_post_thumbnail_url($realisation->ID, 'large');
?>
    <url>
        <loc><?php echo get_permalink($realisation); ?></loc>
        <lastmod><?php echo get_the_modified_date('Y-m-d', $realisation); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
<?php if ($image_url) : ?>
        <image:image>
            <image:loc><?php echo esc_url($image_url); ?></image:loc>
            <image:title><?php echo esc_html(get_the_title($realisation)); ?></image:title>
        </image:image>
<?php endif; ?>
    </url>
<?php endforeach; ?>

</urlset>
