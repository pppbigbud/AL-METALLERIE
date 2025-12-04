<?php
/**
 * Template pour les pages de taxonomie type_realisation
 * (ex: /type-realisation/portails/, /type-realisation/escaliers/)
 * Design identique à la page archive /realisations/
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

get_header();

// Récupérer le terme actuel
$current_term = get_queried_object();

// Icônes SVG par type de réalisation
$category_icons = array(
    'portails' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="18" rx="1"/><rect x="14" y="3" width="7" height="18" rx="1"/></svg>',
    'garde-corps' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/><circle cx="6" cy="12" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="18" cy="12" r="1"/></svg>',
    'escaliers' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 20h4v-4h4v-4h4V8h4"/></svg>',
    'pergolas' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M4 18h16M5 15h14M6 12h12M7 9h10M8 6h8M9 3h6"/></svg>',
    'grilles' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18M15 3v18"/></svg>',
    'ferronnerie-dart' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5M2 12l10 5 10-5"/></svg>',
    'autres' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>',
    'mobilier-metallique' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>',
);

$current_icon = isset($category_icons[$current_term->slug]) ? $category_icons[$current_term->slug] : $category_icons['autres'];
?>

<div class="archive-page taxonomy-type-realisation">
    <!-- Hero Section -->
    <div class="archive-hero">
        <div class="container">
            <h1 class="archive-title">
                <span class="archive-icon"><?php echo $current_icon; ?></span>
                <?php echo esc_html($current_term->name); ?>
            </h1>
            <?php if ($current_term->description) : ?>
                <p class="archive-subtitle"><?php echo esc_html($current_term->description); ?></p>
            <?php else : ?>
                <p class="archive-subtitle">
                    Découvrez toutes nos réalisations de <strong><?php echo esc_html(strtolower($current_term->name)); ?></strong> 
                    en métallerie. Chaque projet est conçu sur mesure avec des matériaux de qualité 
                    et un savoir-faire artisanal.
                </p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="archive-content">
        <div class="container">
            <!-- Compteur de résultats -->
            <div class="taxonomy-results-count">
                <span class="count-number"><?php echo esc_html($current_term->count); ?></span>
                <span class="count-text"><?php echo _n('réalisation', 'réalisations', $current_term->count, 'almetal'); ?></span>
            </div>

            <?php
            // Requête personnalisée pour charger TOUTES les réalisations de cette catégorie
            $tax_query = new WP_Query(array(
                'post_type' => 'realisation',
                'posts_per_page' => -1,
                'orderby' => 'date',
                'order' => 'DESC',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'type_realisation',
                        'field' => 'slug',
                        'terms' => $current_term->slug,
                    ),
                ),
            ));
            ?>

            <?php if ($tax_query->have_posts()) : ?>
                <div class="archive-grid realisations-grid taxonomy-grid">
                    <?php
                    while ($tax_query->have_posts()) :
                        $tax_query->the_post();
                        
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
                                            <span class="type-badge">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                                                </svg>
                                                <?php echo esc_html($type->name); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <?php
                                // Métadonnées
                                $lieu = get_post_meta(get_the_ID(), '_almetal_lieu', true);
                                $date_realisation = get_post_meta(get_the_ID(), '_almetal_date_realisation', true);
                                ?>

                                <?php if ($lieu || $date_realisation) : ?>
                                    <div class="realisation-meta">
                                        <?php if ($lieu) : ?>
                                            <span class="meta-item">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                                    <circle cx="12" cy="10" r="3"/>
                                                </svg>
                                                <?php echo esc_html($lieu); ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($date_realisation) : ?>
                                            <span class="meta-item">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                                </svg>
                                                <?php echo date_i18n('Y', strtotime($date_realisation)); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </article>

                    <?php endwhile; ?>
                </div>

                <?php wp_reset_postdata(); ?>

            <?php else : ?>
                <div class="no-results">
                    <p><?php _e('Aucune réalisation dans cette catégorie pour le moment.', 'almetal'); ?></p>
                </div>
            <?php endif; ?>

            <!-- Lien retour vers toutes les réalisations -->
            <div class="taxonomy-back-link">
                <a href="<?php echo esc_url(get_post_type_archive_link('realisation')); ?>" class="btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    <?php _e('Voir toutes les réalisations', 'almetal'); ?>
                </a>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
