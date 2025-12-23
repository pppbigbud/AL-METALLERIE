<?php
/**
 * Section Actualités/Réalisations - Page d'accueil
 * Affiche les dernières réalisations avec filtres par catégorie
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

// Récupérer les catégories de réalisations
$categories = get_terms(array(
    'taxonomy' => 'type_realisation',
    'hide_empty' => true,
));

// Configuration de la pagination AJAX
$per_page = 6;
$total_realisations = wp_count_posts('realisation')->publish;

// Récupérer les premières réalisations
$realisations_query = new WP_Query(array(
    'post_type' => 'realisation',
    'posts_per_page' => $per_page,
    'paged' => 1,
    'orderby' => 'date',
    'order' => 'DESC',
));
?>

<section class="actualites-section" id="actualites">
    <div class="actualites-container">
        <!-- Tag (style formations) -->
        <div class="hp-section-tag">
            <span><?php esc_html_e('Nos Réalisations', 'almetal'); ?></span>
        </div>
        
        <!-- Titre (style formations) -->
        <h2 class="hp-section-title">
            <?php esc_html_e('NOS DERNIÈRES RÉALISATIONS', 'almetal'); ?>
        </h2>
        
        <!-- Sous-titre (style formations) -->
        <p class="hp-section-subtitle">
            <?php esc_html_e('Découvrez nos projets récents en métallerie et ferronnerie d\'art', 'almetal'); ?>
        </p>

        <!-- Filtres dynamiques (catégories de réalisations) -->
        <?php if (!empty($categories) && !is_wp_error($categories)) : ?>
        
        <!-- Filtres boutons (Desktop) -->
        <div class="actualites-filters actualites-filters-desktop">
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
                <span class="filter-btn__text"><?php esc_html_e('Toutes', 'almetal'); ?></span>
                <span class="filter-btn__count"><?php echo esc_html($total_count); ?></span>
            </button>
            <?php 
            // Icônes par catégorie (identiques au mega menu)
            $category_icons = array(
                'portails' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="18" rx="1"/><rect x="14" y="3" width="7" height="18" rx="1"/></svg>',
                'garde-corps' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/><circle cx="6" cy="12" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="18" cy="12" r="1"/></svg>',
                'escaliers' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 20h4v-4h4v-4h4V8h4"/></svg>',
                'pergolas' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M4 18h16M5 15h14M6 12h12M7 9h10M8 6h8M9 3h6"/></svg>',
                'grilles' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18M15 3v18"/></svg>',
                'ferronnerie-art' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 20c4-4 4-12 8-12s4 8 8 12"/><path d="M4 16c3-3 3-8 6-8s3 5 6 8"/><circle cx="12" cy="8" r="2"/></svg>',
                'ferronnerie-dart' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 20c4-4 4-12 8-12s4 8 8 12"/><path d="M4 16c3-3 3-8 6-8s3 5 6 8"/><circle cx="12" cy="8" r="2"/></svg>',
                'vehicules' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 17h14v-5l-2-4H7l-2 4v5z"/><path d="M3 17h18v2H3z"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/><path d="M5 12h14"/></svg>',
                'serrurerie' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M12 16v2"/><circle cx="12" cy="16" r="1"/><path d="M8 11V7a4 4 0 1 1 8 0v4"/></svg>',
                'mobilier-metallique' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="6" width="16" height="4" rx="1"/><path d="M6 10v10M18 10v10"/><path d="M4 14h16"/></svg>',
            );
            
            foreach ($categories as $category) : 
                $icon = isset($category_icons[$category->slug]) ? $category_icons[$category->slug] : '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>';
            ?>
            <button class="filter-btn" data-filter=".<?php echo esc_attr($category->slug); ?>">
                <span class="filter-btn__icon">
                    <?php echo $icon; ?>
                </span>
                <span class="filter-btn__text"><?php echo esc_html($category->name); ?></span>
                <span class="filter-btn__count"><?php echo esc_html($category->count); ?></span>
            </button>
            <?php endforeach; ?>
        </div>
        
        <!-- Filtre dropdown (Mobile) -->
        <div class="actualites-filters actualites-filters-mobile">
            <label for="mobile-realisations-select" class="screen-reader-text">
                <?php esc_html_e('Filtrer les actualités par catégorie', 'almetal'); ?>
            </label>
            <select id="mobile-realisations-select" class="mobile-filter-select" aria-label="<?php esc_attr_e('Filtrer les actualités', 'almetal'); ?>">
                <option value="*"><?php esc_html_e('Toutes les catégories', 'almetal'); ?></option>
                <?php foreach ($categories as $category) : ?>
                <option value=".<?php echo esc_attr($category->slug); ?>">
                    <?php echo esc_html($category->name); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <?php endif; ?>

        <!-- Grille d'actualités (réalisations dynamiques) -->
        <div class="actualites-grid" id="desktop-realisations-grid" data-page="1" data-per-page="<?php echo esc_attr($per_page); ?>" data-total="<?php echo esc_attr($total_realisations); ?>">
            <?php if ($realisations_query->have_posts()) : ?>
                <?php while ($realisations_query->have_posts()) : $realisations_query->the_post(); ?>
                    <?php
                    // Récupérer les catégories de cette réalisation
                    $terms = get_the_terms(get_the_ID(), 'type_realisation');
                    $term_classes = '';
                    if ($terms && !is_wp_error($terms)) {
                        $term_slugs = array_map(function($term) {
                            return $term->slug;
                        }, $terms);
                        $term_classes = implode(' ', $term_slugs);
                    }
                    
                    // Image à la une - utiliser realisation-card (400x300) pour optimiser le LCP
                    $thumbnail_id = get_post_thumbnail_id(get_the_ID());
                    // Utiliser la taille optimisée realisation-card (400x300)
                    $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'realisation-card');
                    // Fallback sur medium si realisation-card n'existe pas encore
                    if (!$thumbnail_url) {
                        $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                    }
                    if (!$thumbnail_url) {
                        $thumbnail_url = get_template_directory_uri() . '/assets/images/gallery/pexels-kelly-2950108 1.webp';
                    }
                    // Générer le srcset pour les images responsives
                    $srcset = $thumbnail_id ? wp_get_attachment_image_srcset($thumbnail_id, 'realisation-card') : '';
                    // Tailles adaptées aux dimensions réellement affichées (PageSpeed)
                    $sizes = '(max-width: 480px) 300px, (max-width: 768px) 390px, 640px';
                    ?>
                    
                    <?php
                    // Arguments pour le template-part
                    $card_args = array(
                        'show_category_badges' => true,
                        'show_location_badge' => true,
                        'show_meta' => true,
                        'show_cta' => true,
                        'is_first' => ($realisations_query->current_post === 0),
                        'image_size' => 'realisation-card'
                    );
                    
                    // Utiliser le template-part unifié
                    get_template_part('template-parts/card-realisation', null, $card_args);
                    ?>
                    
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p><?php esc_html_e('Aucune réalisation pour le moment.', 'almetal'); ?></p>
            <?php endif; ?>
        </div>
        
        <!-- Loader AJAX -->
        <div id="desktop-realisations-loader" class="realisations-loader" style="display: none;">
            <div class="loader-spinner"></div>
        </div>
        
        <!-- Bouton Voir Plus -->
        <?php 
        $remaining = max(0, $total_realisations - $per_page);
        if ($remaining > 0) : 
        ?>
        <div id="desktop-loadmore-wrapper" class="loadmore-wrapper">
            <button id="btn-desktop-load-more" class="btn-load-more" data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
                <span class="btn-load-more__icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </span>
                <span class="btn-load-more__text"><?php esc_html_e('Voir plus de réalisations', 'almetal'); ?></span>
                <span class="btn-load-more__count"><?php echo esc_html($remaining); ?> restantes</span>
            </button>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
/* Styles partagés pour tag/titre/sous-titre (même style que formations) */
.hp-section-tag {
    margin-bottom: 1.5rem;
    text-align: center;
}

.hp-section-tag span {
    display: inline-block;
    padding: 8px 20px;
    background: rgba(240, 139, 24, 0.1);
    border: 1px solid rgba(240, 139, 24, 0.3);
    border-radius: 30px;
    color: #F08B18;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.hp-section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #fff;
    margin: 0 0 1rem 0;
    text-transform: uppercase;
    letter-spacing: 2px;
    text-align: center;
}

.hp-section-subtitle {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.7);
    max-width: 600px;
    margin: 0 auto 2rem auto;
    line-height: 1.6;
    text-align: center;
}

@media (max-width: 768px) {
    .hp-section-title {
        font-size: 1.75rem;
    }
    
    .hp-section-subtitle {
        font-size: 1rem;
    }
}

/* Bouton Voir Plus */
.loadmore-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 3rem;
    margin-bottom: 3rem;
}

.btn-load-more {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 16px 32px;
    background: transparent;
    border: 2px solid #F08B18;
    border-radius: 50px;
    color: #F08B18;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-load-more:hover {
    background: #F08B18;
    color: #fff;
}

.btn-load-more__icon {
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease;
}

.btn-load-more:hover .btn-load-more__icon {
    transform: translateY(3px);
}

.btn-load-more__count {
    font-size: 0.85rem;
    opacity: 0.8;
    padding-left: 8px;
    border-left: 1px solid currentColor;
}

.btn-load-more.loading {
    opacity: 0.7;
    pointer-events: none;
}

/* Loader */
.realisations-loader {
    display: flex;
    justify-content: center;
    padding: 2rem;
}

.loader-spinner {
    width: 40px;
    height: 40px;
    border: 3px solid rgba(240, 139, 24, 0.2);
    border-top-color: #F08B18;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Animation des nouvelles cards */
.realisation-card.loading-new {
    opacity: 0;
    transform: translateY(20px);
}

.realisation-card.loaded {
    opacity: 1;
    transform: translateY(0);
    transition: opacity 0.4s ease, transform 0.4s ease;
}
</style>
