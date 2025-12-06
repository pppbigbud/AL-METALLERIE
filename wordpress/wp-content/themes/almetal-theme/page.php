<?php
/**
 * Template pour les pages standards
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

get_header();
?>

<div class="container">
    <div class="content-area">
        <?php
        while (have_posts()) :
            the_post();
            
            // Récupérer l'ID de section pour la navigation one-page
            $section_id = almetal_get_section_id();
            ?>
            
            <article id="<?php echo $section_id ? esc_attr($section_id) : 'post-' . get_the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>

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
            // Commentaires (si activés)
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;

        endwhile;
        ?>
    </div>
</div>

<?php
get_footer();
