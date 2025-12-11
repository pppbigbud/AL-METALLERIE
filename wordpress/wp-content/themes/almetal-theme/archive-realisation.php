<?php
/**
 * Template pour l'archive des réalisations
 * Design inspiré des pages légales
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

get_header();
?>

<div class="archive-page realisations-archive">
    <!-- Hero Section -->
    <div class="archive-hero">
        <div class="container">
            <h1 class="archive-title">
                <svg class="archive-icon" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                </svg>
                <?php _e('Nos Réalisations', 'almetal'); ?>
            </h1>
            <p class="archive-subtitle">
                Découvrez <strong>l'ensemble de nos réalisations en métallerie</strong> à travers la région Auvergne-Rhône-Alpes. 
                Spécialisés dans la <strong>fabrication sur mesure</strong>, nous concevons et installons des <em>portails</em>, 
                <em>garde-corps</em>, <em>escaliers métalliques</em>, <em>pergolas</em> et <em>grilles de sécurité</em> 
                alliant <strong>esthétique moderne</strong> et <strong>robustesse</strong>.
            </p>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="archive-content">
        <div class="container">

        <?php
        // Filtres par type de réalisation
        $terms = get_terms(array(
            'taxonomy' => 'type_realisation',
            'hide_empty' => true,
        ));

        if ($terms && !is_wp_error($terms)) :
            ?>
            <div class="archive-filters">
                <?php
                // Compter le total de réalisations
                $total_count = wp_count_posts('realisation')->publish;
                ?>
                <button class="filter-btn active" data-filter="*">
                    <span class="filter-btn__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7" rx="1"/>
                            <rect x="14" y="3" width="7" height="7" rx="1"/>
                            <rect x="3" y="14" width="7" height="7" rx="1"/>
                            <rect x="14" y="14" width="7" height="7" rx="1"/>
                        </svg>
                    </span>
                    <span class="filter-btn__text"><?php _e('Tous', 'almetal'); ?></span>
                    <span class="filter-btn__count"><?php echo esc_html($total_count); ?></span>
                </button>
                <?php 
                foreach ($terms as $term) : 
                ?>
                    <button class="filter-btn" data-filter=".type-<?php echo esc_attr($term->slug); ?>">
                        <span class="filter-btn__icon">
                            <?php echo function_exists('almetal_get_category_icon') ? almetal_get_category_icon($term->slug, 20) : ''; ?>
                        </span>
                        <span class="filter-btn__text"><?php echo esc_html($term->name); ?></span>
                        <span class="filter-btn__count"><?php echo esc_html($term->count); ?></span>
                    </button>
                <?php endforeach; ?>
            </div>
            <?php
        endif;
        ?>

        <?php
        // Requête personnalisée pour charger TOUTES les réalisations (lazy loading gère l'affichage)
        $all_realisations = new WP_Query(array(
            'post_type' => 'realisation',
            'posts_per_page' => -1, // Toutes les réalisations
            'orderby' => 'date',
            'order' => 'DESC',
        ));
        ?>

        <?php if ($all_realisations->have_posts()) : ?>
            <div class="archive-grid realisations-grid">
                <?php
                while ($all_realisations->have_posts()) :
                    $all_realisations->the_post();
                    
                    // Récupérer les types de réalisation
                    $types = get_the_terms(get_the_ID(), 'type_realisation');
                    $type_classes = '';
                    if ($types && !is_wp_error($types)) {
                        foreach ($types as $type) {
                            $type_classes .= ' type-' . $type->slug;
                        }
                    }
                    ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class('realisation-card' . $type_classes); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="realisation-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium_large'); ?>
                                    <div class="realisation-overlay">
                                        <span class="view-more"><?php _e('Voir le projet', 'almetal'); ?></span>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="realisation-content">
                            <h2 class="realisation-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>

                            <?php if ($types && !is_wp_error($types)) : ?>
                                <div class="realisation-types">
                                    <?php foreach ($types as $type) : ?>
                                        <a href="<?php echo esc_url(get_term_link($type)); ?>" class="type-badge">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                                            </svg>
                                            <?php echo esc_html($type->name); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <?php
                            $date_realisation = get_post_meta(get_the_ID(), '_almetal_date_realisation', true);
                            $lieu = get_post_meta(get_the_ID(), '_almetal_lieu', true);
                            $duree = get_post_meta(get_the_ID(), '_almetal_duree', true);
                            
                            if ($date_realisation || $lieu || $duree) :
                                ?>
                                <div class="realisation-meta">
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
                                        <?php 
                                        if (function_exists('cpg_render_city_badge')) {
                                            echo cpg_render_city_badge($lieu, true);
                                        } else {
                                        ?>
                                        <span class="meta-item meta-lieu">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                                <circle cx="12" cy="10" r="3"/>
                                            </svg>
                                            <?php echo esc_html($lieu); ?>
                                        </span>
                                        <?php } ?>
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

                            <a href="<?php the_permalink(); ?>" class="btn-view-project">
                                <span class="circle" aria-hidden="true">
                                    <svg class="icon arrow" width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 6H17M17 6L12 1M17 6L12 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <span class="button-text"><?php _e('Voir le projet', 'almetal'); ?></span>
                            </a>
                        </div>
                    </article>

                    <?php
                endwhile;
                ?>
            </div>

            <?php
            // Pagination supprimée - le lazy loading gère l'affichage progressif
            wp_reset_postdata();
            ?>

        <?php else : ?>
            <div class="no-results">
                <p><?php _e('Aucune réalisation pour le moment.', 'almetal'); ?></p>
            </div>
        <?php endif; ?>
        </div>
    </div>
</div>

<?php
get_footer();
