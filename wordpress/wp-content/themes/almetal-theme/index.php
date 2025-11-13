<?php
/**
 * Template principal
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

get_header();
?>

<div class="container">
    <div class="content-area">
        <?php
        if (have_posts()) :
            
            // Boucle WordPress
            while (have_posts()) :
                the_post();
                ?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php
                        if (is_singular()) :
                            the_title('<h1 class="entry-title">', '</h1>');
                        else :
                            the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                        endif;
                        ?>
                        
                        <?php if ('post' === get_post_type()) : ?>
                            <div class="entry-meta">
                                <span class="posted-on">
                                    <?php echo get_the_date(); ?>
                                </span>
                                <span class="byline">
                                    <?php esc_html_e('par', 'almetal'); ?> <?php the_author(); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </header>

                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-thumbnail">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                    <?php endif; ?>

                    <div class="entry-content">
                        <?php
                        if (is_singular()) :
                            the_content();
                            
                            wp_link_pages(array(
                                'before' => '<div class="page-links">' . esc_html__('Pages:', 'almetal'),
                                'after'  => '</div>',
                            ));
                        else :
                            the_excerpt();
                            ?>
                            <a href="<?php echo esc_url(get_permalink()); ?>" class="read-more">
                                <?php esc_html_e('Lire la suite', 'almetal'); ?> →
                            </a>
                            <?php
                        endif;
                        ?>
                    </div>

                    <?php if (is_singular() && get_the_tags()) : ?>
                        <footer class="entry-footer">
                            <?php the_tags('<div class="tags-links">', ', ', '</div>'); ?>
                        </footer>
                    <?php endif; ?>
                </article>

                <?php
            endwhile;

            // Pagination
            the_posts_pagination(array(
                'prev_text' => esc_html__('← Précédent', 'almetal'),
                'next_text' => esc_html__('Suivant →', 'almetal'),
            ));

        else :
            ?>
            
            <section class="no-results">
                <header class="page-header">
                    <h1 class="page-title"><?php esc_html_e('Aucun contenu trouvé', 'almetal'); ?></h1>
                </header>

                <div class="page-content">
                    <p><?php esc_html_e('Désolé, aucun contenu ne correspond à votre recherche.', 'almetal'); ?></p>
                    <?php get_search_form(); ?>
                </div>
            </section>

            <?php
        endif;
        ?>
    </div>

    <?php
    // Sidebar (seulement sur desktop)
    if (!almetal_is_mobile() && is_active_sidebar('sidebar-1')) :
        ?>
        <aside id="secondary" class="widget-area">
            <?php dynamic_sidebar('sidebar-1'); ?>
        </aside>
        <?php
    endif;
    ?>
</div>

<?php
get_footer();
