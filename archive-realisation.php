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
                // Icônes par catégorie avec couleur explicite #a0a1a5
                $category_icons = array(
                    'portails' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#a0a1a5" stroke-width="2"><rect x="3" y="3" width="7" height="18" rx="1"/><rect x="14" y="3" width="7" height="18" rx="1"/></svg>',
                    'garde-corps' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#a0a1a5" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/><circle cx="6" cy="12" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="18" cy="12" r="1"/></svg>',
                    'escaliers' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#a0a1a5" stroke-width="2"><path d="M6 20h4v-4h4v-4h4V8h4"/></svg>',
                    'pergolas' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#a0a1a5" stroke-width="2"><path d="M3 21h18M4 18h16M5 15h14M6 12h12M7 9h10M8 6h8M9 3h6"/></svg>',
                    'grilles' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#a0a1a5" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18M15 3v18"/></svg>',
                    'ferronnerie-art' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#a0a1a5" stroke-width="2"><path d="M4 20c4-4 4-12 8-12s4 8 8 12"/><path d="M4 16c3-3 3-8 6-8s3 5 6 8"/><circle cx="12" cy="8" r="2"/></svg>',
                    'ferronnerie-dart' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#a0a1a5" stroke-width="2"><path d="M4 20c4-4 4-12 8-12s4 8 8 12"/><path d="M4 16c3-3 3-8 6-8s3 5 6 8"/><circle cx="12" cy="8" r="2"/></svg>',
                    'vehicules' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#a0a1a5" stroke-width="2"><path d="M5 17h14v-5l-2-4H7l-2 4v5z"/><path d="M3 17h18v2H3z"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/><path d="M5 12h14"/></svg>',
                    'serrurerie' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#a0a1a5" stroke-width="2"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M12 16v2"/><circle cx="12" cy="16" r="1"/><path d="M8 11V7a4 4 0 1 1 8 0v4"/></svg>',
                    'mobilier-metallique' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#a0a1a5" stroke-width="2"><rect x="4" y="6" width="16" height="4" rx="1"/><path d="M6 10v10M18 10v10"/><path d="M4 14h16"/></svg>',
                    'industrie' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#a0a1a5" stroke-width="2"><path d="M2 20h20"/><path d="M5 20V8l5 4V8l5 4V4h5v16"/></svg>',
                    'autres' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#a0a1a5" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>',
                );
                
                foreach ($terms as $term) : 
                    $icon = isset($category_icons[$term->slug]) ? $category_icons[$term->slug] : '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#a0a1a5" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>';
                ?>
                    <button class="filter-btn" data-filter=".type-<?php echo esc_attr($term->slug); ?>">
                        <span class="filter-btn__icon" style="display:flex;align-items:center;justify-content:center;width:32px;height:32px;background:rgba(160,161,165,0.15);border-radius:50%;">
                            <?php echo $icon; ?>
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
                    
                    <?php
                    // Arguments pour le template-part
                    $card_args = array(
                        'show_category_badges' => true,
                        'show_location_badge' => true,
                        'show_meta' => true,
                        'show_cta' => true,
                        'is_first' => false,
                        'image_size' => 'medium_large'
                    );
                    
                    // Utiliser le template-part unifié
                    get_template_part('template-parts/card-realisation', null, $card_args);
                    ?>

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
