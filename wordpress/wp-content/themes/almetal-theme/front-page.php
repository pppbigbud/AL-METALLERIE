<?php
/**
 * Template de la page d'accueil
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

get_header();
?>

<div class="front-page">
    <?php
    if (almetal_is_mobile()) :
        // VERSION MOBILE : One Page
        get_template_part('template-parts/mobile', 'onepage');
    else :
        // VERSION DESKTOP : Carrousel + contenu
        
        // Afficher le carrousel hero
        get_template_part('template-parts/hero-carousel');
        
        // Afficher la section de présentation
        get_template_part('template-parts/section', 'presentation');
        
        // Afficher la section CTA
        get_template_part('template-parts/section', 'cta');

        // Afficher la section actualités
        get_template_part('template-parts/section', 'actualites');
        
        // Afficher la section services/offres
        get_template_part('template-parts/section', 'services');
        ?>
        
        <div class="container">
            <?php
            while (have_posts()) :
                the_post();
                ?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class('mt-5'); ?>>
                    <div class="entry-content">
                        <?php
                        the_content();

                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'almetal'),
                            'after'  => '</div>',
                        ));
                        ?>
                    </div>
                </article>

                <?php
            endwhile;
            ?>
        </div>
        <?php
    endif;
    ?>
</div>

<?php
get_footer();
