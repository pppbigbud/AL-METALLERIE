<?php
/**
 * Template Part: Section Actualités Mobile
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

// Récupérer les 3 dernières réalisations
$realisations_query = new WP_Query(array(
    'post_type' => 'realisation',
    'posts_per_page' => 3,
    'orderby' => 'date',
    'order' => 'DESC',
));
?>

<div class="mobile-actualites-container">
    <h2 class="mobile-section-title">
        <?php esc_html_e('DERNIÈRES ACTUALITÉS', 'almetal'); ?>
    </h2>

    <?php if ($realisations_query->have_posts()) : ?>
        <div class="mobile-actualites-grid">
            <?php while ($realisations_query->have_posts()) : $realisations_query->the_post(); ?>
                <?php
                $terms = get_the_terms(get_the_ID(), 'type_realisation');
                $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                if (!$thumbnail_url) {
                    $thumbnail_url = get_template_directory_uri() . '/assets/images/gallery/pexels-kelly-2950108 1.webp';
                }
                ?>
                
                <article class="mobile-actualite-card">
                    <div class="mobile-actualite-image-wrapper">
                        <img src="<?php echo esc_url($thumbnail_url); ?>" 
                             alt="<?php echo esc_attr(get_the_title()); ?>"
                             loading="lazy"
                             class="mobile-actualite-image">
                        
                        <?php if ($terms && !is_wp_error($terms)) : ?>
                            <div class="mobile-actualite-badges">
                                <?php foreach (array_slice($terms, 0, 2) as $term) : ?>
                                    <span class="mobile-actualite-badge">
                                        <?php echo esc_html($term->name); ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mobile-actualite-content">
                        <h3 class="mobile-actualite-title">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>

                        <div class="mobile-actualite-meta">
                            <span class="mobile-actualite-date">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <?php echo get_the_date('d/m/Y'); ?>
                            </span>
                        </div>

                        <p class="mobile-actualite-excerpt">
                            <?php echo wp_trim_words(get_the_excerpt(), 12, '...'); ?>
                        </p>

                        <a href="<?php the_permalink(); ?>" class="mobile-actualite-link">
                            <?php esc_html_e('Lire la suite', 'almetal'); ?>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                    </div>
                </article>
                
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>

        <div class="mobile-actualites-cta">
            <a href="<?php echo esc_url(get_post_type_archive_link('realisation')); ?>" class="mobile-btn-cta">
                <?php esc_html_e('Voir toutes les réalisations', 'almetal'); ?>
            </a>
        </div>
    <?php else : ?>
        <p class="mobile-no-content"><?php esc_html_e('Aucune actualité pour le moment.', 'almetal'); ?></p>
    <?php endif; ?>
</div>
