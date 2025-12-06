<?php
/**
 * Template Part: Section Réalisations Mobile
 * Basé sur le système desktop avec filtres et compteurs
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

// Récupérer les catégories de réalisations
$categories = get_terms(array(
    'taxonomy' => 'type_realisation',
    'hide_empty' => true,
));

// Récupérer les 3 dernières réalisations pour l'aperçu
$realisations_query = new WP_Query(array(
    'post_type' => 'realisation',
    'posts_per_page' => 3,
    'orderby' => 'date',
    'order' => 'DESC',
));
?>

<div class="mobile-realisations-container">
    <?php if (!empty($categories) && !is_wp_error($categories)) : ?>
    <!-- Menu déroulant pour filtrer les catégories -->
    <div class="mobile-realisations-filter-wrapper scroll-fade scroll-delay-1">
        <label for="mobile-realisations-select" class="mobile-filter-label">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
            </svg>
            <span><?php esc_html_e('Filtrer par catégorie', 'almetal'); ?></span>
        </label>
        
        <select id="mobile-realisations-select" class="mobile-filter-select">
            <?php
            // Compter le total de réalisations
            $total_count = wp_count_posts('realisation')->publish;
            ?>
            <option value="*" selected>
                <?php echo esc_html(sprintf(__('Toutes (%d)', 'almetal'), $total_count)); ?>
            </option>
            
            <?php foreach ($categories as $category) : ?>
            <option value=".<?php echo esc_attr($category->slug); ?>">
                <?php echo esc_html(sprintf('%s (%d)', $category->name, $category->count)); ?>
            </option>
            <?php endforeach; ?>
        </select>
        
        <svg class="mobile-select-arrow" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
    </div>
    <?php endif; ?>

    <!-- Grille de réalisations -->
    <div class="mobile-realisations-grid">
        <?php if ($realisations_query->have_posts()) : ?>
            <?php while ($realisations_query->have_posts()) : $realisations_query->the_post(); ?>
                <?php
                $terms = get_the_terms(get_the_ID(), 'type_realisation');
                $term_classes = '';
                $term_data = '';
                if ($terms && !is_wp_error($terms)) {
                    $term_slugs = array_map(function($term) {
                        return $term->slug;
                    }, $terms);
                    $term_classes = implode(' ', $term_slugs);
                    $term_data = implode(' ', $term_slugs);
                }
                
                $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                if (!$thumbnail_url) {
                    $thumbnail_url = get_template_directory_uri() . '/assets/images/gallery/pexels-kelly-2950108 1.webp';
                }
                ?>
                
                <article class="mobile-realisation-card scroll-slide-up <?php echo esc_attr($term_classes); ?>" data-categories="<?php echo esc_attr($term_data); ?>">
                    <div class="mobile-realisation-image-wrapper">
                        <a href="<?php the_permalink(); ?>">
                            <img src="<?php echo esc_url($thumbnail_url); ?>" 
                                 alt="<?php echo esc_attr(get_the_title()); ?>"
                                 loading="lazy"
                                 class="mobile-realisation-image">
                            <div class="mobile-realisation-overlay">
                                <span class="view-more"><?php esc_html_e('Voir le projet', 'almetal'); ?></span>
                            </div>
                        </a>
                        
                        <?php if ($terms && !is_wp_error($terms)) : ?>
                            <div class="mobile-realisation-badges">
                                <?php foreach (array_slice($terms, 0, 2) as $term) : ?>
                                    <span class="mobile-realisation-badge">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                                        </svg>
                                        <?php echo esc_html($term->name); ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mobile-realisation-content">
                        <h3 class="mobile-realisation-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>

                        <?php
                        $date_realisation = get_post_meta(get_the_ID(), '_almetal_date_realisation', true);
                        $lieu = get_post_meta(get_the_ID(), '_almetal_lieu', true);
                        $duree = get_post_meta(get_the_ID(), '_almetal_duree', true);
                        
                        if ($date_realisation || $lieu || $duree) :
                            ?>
                            <div class="mobile-realisation-meta">
                                <?php if ($date_realisation) : ?>
                                    <span class="meta-item meta-date">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                            <line x1="16" y1="2" x2="16" y2="6"/>
                                            <line x1="8" y1="2" x2="8" y2="6"/>
                                            <line x1="3" y1="10" x2="21" y2="10"/>
                                        </svg>
                                        <?php echo date_i18n('M Y', strtotime($date_realisation)); ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ($lieu) : ?>
                                    <span class="meta-item meta-lieu">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                            <circle cx="12" cy="10" r="3"/>
                                        </svg>
                                        <?php echo esc_html($lieu); ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ($duree) : ?>
                                    <span class="meta-item meta-duree">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"/>
                                            <polyline points="12 6 12 12 16 14"/>
                                        </svg>
                                        <?php echo esc_html($duree); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <?php
                        endif;
                        ?>

                        <a href="<?php the_permalink(); ?>" class="mobile-btn-view-project">
                            <span class="circle" aria-hidden="true">
                                <svg class="icon arrow" width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 6H17M17 6L12 1M17 6L12 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <span class="button-text"><?php esc_html_e('Voir le projet', 'almetal'); ?></span>
                        </a>
                    </div>
                </article>
                
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php endif; ?>
    </div>

    <!-- Bouton voir toutes les réalisations -->
    <div class="mobile-realisations-cta">
        <a href="<?php echo esc_url(get_post_type_archive_link('realisation')); ?>" class="mobile-btn-cta">
            <?php esc_html_e('Voir toutes les réalisations', 'almetal'); ?>
        </a>
    </div>
</div>
