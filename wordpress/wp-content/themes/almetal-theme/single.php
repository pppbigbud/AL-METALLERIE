<?php
/**
 * Template pour les articles individuels
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
            ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                    
                    <div class="entry-meta">
                        <span class="posted-on">
                            <time datetime="<?php echo get_the_date('c'); ?>">
                                <?php echo get_the_date(); ?>
                            </time>
                        </span>
                        <span class="byline">
                            <?php esc_html_e('par', 'almetal'); ?> 
                            <span class="author"><?php the_author(); ?></span>
                        </span>
                        <?php if (has_category()) : ?>
                            <span class="categories">
                                <?php the_category(', '); ?>
                            </span>
                        <?php endif; ?>
                    </div>
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

                <?php if (get_the_tags()) : ?>
                    <footer class="entry-footer">
                        <?php the_tags('<div class="tags-links"><strong>' . esc_html__('Tags:', 'almetal') . '</strong> ', ', ', '</div>'); ?>
                    </footer>
                <?php endif; ?>
            </article>

            <?php
            // Navigation entre articles
            the_post_navigation(array(
                'prev_text' => '<span class="nav-subtitle">' . esc_html__('Article précédent', 'almetal') . '</span> <span class="nav-title">%title</span>',
                'next_text' => '<span class="nav-subtitle">' . esc_html__('Article suivant', 'almetal') . '</span> <span class="nav-title">%title</span>',
            ));

            // Commentaires
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;

        endwhile;
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
