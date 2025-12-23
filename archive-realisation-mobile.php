<?php
/**
 * Template pour l'archive des réalisations - VERSION MOBILE
 * 
 * Affiche toutes les réalisations avec filtrage par catégories
 * Header avec bouton retour vers l'accueil
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

get_header();
?>

<!-- Header Mobile avec bouton RETOUR -->
<?php get_template_part('template-parts/header', 'mobile'); ?>

<main class="mobile-archive-realisations">
    <div class="mobile-archive-container">
        
        <!-- Tag -->
        <div class="mobile-archive-tag scroll-zoom">
            <span><?php esc_html_e('Nos Réalisations', 'almetal'); ?></span>
        </div>

        <!-- Titre de la page -->
        <h1 class="mobile-archive-title scroll-fade scroll-delay-1">
            <?php esc_html_e('TOUTES NOS RÉALISATIONS', 'almetal'); ?>
        </h1>
        <p class="mobile-archive-subtitle scroll-fade scroll-delay-2">
            <?php esc_html_e('Découvrez l\'ensemble de nos projets en métallerie', 'almetal'); ?>
        </p>

        <?php
        // Récupérer les catégories de réalisations
        $categories = get_terms(array(
            'taxonomy' => 'type_realisation',
            'hide_empty' => true,
            'orderby' => 'name',
            'order' => 'ASC',
        ));
        ?>

        <?php if (!empty($categories) && !is_wp_error($categories)) : ?>
        <!-- Menu déroulant de filtrage -->
        <div class="mobile-archive-filter-wrapper scroll-fade scroll-delay-3">
            <label for="mobile-archive-filter-select" class="mobile-filter-label">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                </svg>
                <span><?php esc_html_e('Filtrer par catégorie', 'almetal'); ?></span>
            </label>
            
            <select id="mobile-archive-filter-select" class="mobile-filter-select">
                <?php
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

        <?php
        // Query pour récupérer toutes les réalisations
        $realisations_query = new WP_Query(array(
            'post_type' => 'realisation',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
        ));
        ?>

        <!-- Grille de réalisations -->
        <div class="mobile-archive-grid">
            <?php if ($realisations_query->have_posts()) : ?>
                <?php while ($realisations_query->have_posts()) : $realisations_query->the_post(); ?>
                    <?php
                    $terms = get_the_terms(get_the_ID(), 'type_realisation');
                    $term_classes = '';
                    $term_slugs = array();
                    
                    if ($terms && !is_wp_error($terms)) {
                        foreach ($terms as $term) {
                            $term_classes .= ' ' . $term->slug;
                            $term_slugs[] = $term->slug;
                        }
                    }
                    ?>
                    
                    <article class="mobile-realisation-card scroll-slide-up">
                        <div class="mobile-realisation-card-inner">
                            <a href="<?php the_permalink(); ?>" class="mobile-realisation-link">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="mobile-realisation-image">
                                        <?php the_post_thumbnail('medium_large', array('loading' => 'lazy')); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="mobile-realisation-content">
                                    <h3 class="mobile-realisation-title">
                                        <?php the_title(); ?>
                                    </h3>
                                    
                                    <?php if (has_excerpt()) : ?>
                                        <p class="mobile-realisation-excerpt">
                                            <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <span class="mobile-realisation-cta">
                                        <?php esc_html_e('Voir le projet', 'almetal'); ?>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                            <polyline points="12 5 19 12 12 19"></polyline>
                                        </svg>
                                    </span>
                                </div>
                            </a>
                            
                            <!-- Badges positionnés en absolu par rapport à la card -->
                            <?php 
                            $lieu = get_post_meta(get_the_ID(), '_almetal_lieu', true);
                            if ($lieu) : 
                            ?>
                                <div class="mobile-city-badge">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <?php echo almetal_city_link_html($lieu, 'mobile-city-link'); ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Badges catégories et matériaux en haut à droite -->
                            <div class="mobile-top-badges">
                                <?php 
                                $matiere = get_post_meta(get_the_ID(), '_almetal_matiere', true);
                                if ($matiere) : 
                                ?>
                                    <a href="<?php echo esc_url(almetal_get_matiere_url($matiere)); ?>" class="mobile-matiere-badge">
                                        <?php
                                        $matiere_labels = array(
                                            'acier' => 'Acier',
                                            'inox' => 'Inox',
                                            'aluminium' => 'Aluminium',
                                            'cuivre' => 'Cuivre',
                                            'laiton' => 'Laiton',
                                            'fer-forge' => 'Fer forgé',
                                            'mixte' => 'Mixte'
                                        );
                                        echo esc_html($matiere_labels[$matiere] ?? ucfirst($matiere));
                                        ?>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($terms && !is_wp_error($terms)) : ?>
                                    <?php foreach ($terms as $term) : ?>
                                        <a href="<?php echo esc_url(get_term_link($term)); ?>" class="mobile-category-badge">
                                            <?php
                                            // Icônes SVG directement intégrées
                                            $mobile_icons = array(
                                                'portails' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="18" rx="1"/><rect x="14" y="3" width="7" height="18" rx="1"/></svg>',
                                                'garde-corps' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/><circle cx="6" cy="12" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="18" cy="12" r="1"/></svg>',
                                                'escaliers' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 20h4v-4h4v-4h4V8h4"/></svg>',
                                                'pergolas' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M4 18h16M5 15h14M6 12h12M7 9h10M8 6h8M9 3h6"/></svg>',
                                                'grilles' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18M15 3v18"/></svg>',
                                                'ferronnerie-art' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 20c4-4 4-12 8-12s4 8 8 12"/><path d="M4 16c3-3 3-8 6-8s3 5 6 8"/><circle cx="12" cy="8" r="2"/></svg>',
                                                'ferronnerie-dart' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 20c4-4 4-12 8-12s4 8 8 12"/><path d="M4 16c3-3 3-8 6-8s3 5 6 8"/><circle cx="12" cy="8" r="2"/></svg>',
                                                'vehicules' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 17h14v-5l-2-4H7l-2 4v5z"/><path d="M3 17h18v2H3z"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/><path d="M5 12h14"/></svg>',
                                                'serrurerie' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M12 16v2"/><circle cx="12" cy="16" r="1"/><path d="M8 11V7a4 4 0 1 1 8 0v4"/></svg>',
                                                'mobilier-metallique' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="6" width="16" height="4" rx="1"/><path d="M6 10v10M18 10v10"/><path d="M4 14h16"/></svg>',
                                                'autres' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>'
                                            );
                                            
                                            $icon_svg = isset($mobile_icons[$term->slug]) ? $mobile_icons[$term->slug] : $mobile_icons['autres'];
                                            echo $icon_svg;
                                            ?>
                                            <?php echo esc_html($term->name); ?>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                    
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p class="mobile-archive-empty">
                    <?php esc_html_e('Aucune réalisation trouvée.', 'almetal'); ?>
                </p>
            <?php endif; ?>
        </div>

    </div>
</main>

<?php get_footer(); ?>
