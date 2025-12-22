<?php
/**
 * Template Name: Formations
 * Description: Page parente des formations en ferronnerie - Intégration Training Manager
 * Design cohérent avec archive-pages
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

// SEO Meta Tags pour la page Formations
if (!function_exists('almetal_formations_seo_meta')) {
    function almetal_formations_seo_meta() {
    ?>
    <title>Formations Soudure et Ferronnerie | Stages Particuliers &amp; Professionnels | Thiers (63)</title>
    <meta name="description" content="Formations en soudure et ferronnerie d'art à Thiers (63). Stages découverte pour particuliers, formations certifiantes pour professionnels. Atelier équipé, formateurs experts. Devis gratuit.">
    <link rel="canonical" href="<?php echo esc_url(home_url('/formations/')); ?>">
    
    <!-- Open Graph -->
    <meta property="og:title" content="Formations Soudure et Ferronnerie | AL Métallerie Thiers (63)">
    <meta property="og:description" content="Formations en soudure et ferronnerie d'art. Stages découverte pour particuliers, formations certifiantes pour professionnels. Atelier équipé à Thiers.">
    <meta property="og:url" content="<?php echo esc_url(home_url('/formations/')); ?>">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:site_name" content="AL Métallerie & Soudure">
    
    <!-- Schema.org Course -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "ItemList",
        "name": "Formations Soudure et Ferronnerie - AL Métallerie",
        "description": "Formations en soudure et ferronnerie d'art à Thiers (63). Stages pour particuliers et professionnels.",
        "url": "<?php echo esc_url(home_url('/formations/')); ?>",
        "numberOfItems": 2,
        "itemListElement": [
            {
                "@type": "ListItem",
                "position": 1,
                "item": {
                    "@type": "Course",
                    "name": "Formations Particuliers - Stages Découverte Ferronnerie",
                    "description": "Stages découverte et perfectionnement en ferronnerie d'art pour les passionnés. De 1 à 5 jours, tous niveaux.",
                    "provider": {
                        "@type": "Organization",
                        "name": "AL Métallerie & Soudure",
                        "address": {
                            "@type": "PostalAddress",
                            "streetAddress": "Lieu-dit Les Parrons",
                            "addressLocality": "Peschadoires",
                            "postalCode": "63920",
                            "addressCountry": "FR"
                        }
                    },
                    "url": "<?php echo esc_url(home_url('/formations-particuliers/')); ?>"
                }
            },
            {
                "@type": "ListItem",
                "position": 2,
                "item": {
                    "@type": "Course",
                    "name": "Formations Professionnels - Certifications Métallerie",
                    "description": "Formations certifiantes et qualifiantes en métallerie pour professionnels du bâtiment et artisans en reconversion. Financement CPF/Pôle Emploi.",
                    "provider": {
                        "@type": "Organization",
                        "name": "AL Métallerie & Soudure",
                        "address": {
                            "@type": "PostalAddress",
                            "streetAddress": "Lieu-dit Les Parrons",
                            "addressLocality": "Peschadoires",
                            "postalCode": "63920",
                            "addressCountry": "FR"
                        }
                    },
                    "url": "<?php echo esc_url(home_url('/formations-professionnelles/')); ?>"
                }
            }
        ]
    }
    </script>
    <?php
    }
    add_action('wp_head', 'almetal_formations_seo_meta', 1);
}

// Désactiver le titre WordPress par défaut pour cette page
add_filter('pre_get_document_title', function($title) {
    return 'Formations Soudure et Ferronnerie | Stages Particuliers & Professionnels | Thiers (63)';
}, 999);

get_header();

if (have_posts()) :
    while (have_posts()) :
        the_post();

        $raw_content = get_post_field('post_content', get_the_ID());
        $has_editor_content = !empty(trim(strip_tags($raw_content))) || has_blocks(get_the_ID());

        if ($has_editor_content) :
            ?>
            <div class="archive-page formations-archive">
                <?php
                // Afficher le contenu saisi dans l’éditeur (Gutenberg ou classique)
                the_content();
                ?>
            </div>
            <?php
        else :
            // Aucun contenu dans l’éditeur → fallback HTML historique
            get_template_part('template-parts/content', 'formations-default');
        endif;

    endwhile;
endif;

get_footer();
