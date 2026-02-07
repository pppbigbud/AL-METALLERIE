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
$total_count = $total_realisations;

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
        
        <!-- Menu déroulant stylisé (Desktop) -->
        <div class="realisations-dropdown-wrapper">
            <div class="realisations-dropdown" id="realisations-dropdown">
                <button class="dropdown-trigger" aria-expanded="false" aria-haspopup="listbox">
                    <span class="dropdown-trigger__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7" rx="1"/>
                            <rect x="14" y="3" width="7" height="7" rx="1"/>
                            <rect x="3" y="14" width="7" height="7" rx="1"/>
                            <rect x="14" y="14" width="7" height="7" rx="1"/>
                        </svg>
                    </span>
                    <span class="dropdown-trigger__text"><?php esc_html_e('Toutes les réalisations', 'almetal'); ?></span>
                    <span class="dropdown-trigger__count"><?php echo esc_html($total_count); ?></span>
                    <span class="dropdown-trigger__arrow">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </span>
                </button>
                
                <ul class="dropdown-menu" role="listbox" aria-label="<?php esc_attr_e('Filtrer par catégorie', 'almetal'); ?>">
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
                        'porte' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M4 8h16"/><circle cx="16" cy="12" r="1"/></svg>',
                        'porte-a-galandage' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M4 8h16"/><circle cx="16" cy="12" r="1"/><path d="M3 6v12"/></svg>',
                        'portillon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="6" width="12" height="15" rx="1"/><path d="M6 12h12"/><circle cx="15" cy="14" r="1"/><path d="M2 21h20"/></svg>',
                        'industrie' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18"/><path d="M5 21v-8l4-2v10"/><path d="M13 21v-6l4-2v8"/><rect x="18" y="7" width="3" height="14" rx="1"/><path d="M19 7v-3"/></svg>',
                        'enseigne' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="12" rx="2"/><path d="M8 16l-3 4"/><path d="M16 16l3 4"/><path d="M8 10h8"/></svg>',
                        'brasero' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><ellipse cx="12" cy="16" rx="8" ry="4"/><path d="M4 16v-3c0-4 4-7 8-7s8 3 8 7v3"/><path d="M12 9v-3"/><path d="M9 7l3-3 3 3"/></svg>',
                        'bac-a-fleurs' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10v-3c0-2 2-4 4-4s4 2 4 4v3"/><path d="M12 3v2"/></svg>',
                    );
                    ?>
                    
                    <li class="dropdown-item active" role="option" data-filter="*" aria-selected="true">
                        <span class="dropdown-item__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="7" height="7" rx="1"/>
                                <rect x="14" y="3" width="7" height="7" rx="1"/>
                                <rect x="3" y="14" width="7" height="7" rx="1"/>
                                <rect x="14" y="14" width="7" height="7" rx="1"/>
                            </svg>
                        </span>
                        <span class="dropdown-item__text"><?php esc_html_e('Toutes les réalisations', 'almetal'); ?></span>
                        <span class="dropdown-item__count"><?php echo esc_html($total_count); ?></span>
                    </li>
                    
                    <?php foreach ($categories as $category) : 
                        $icon = isset($category_icons[$category->slug]) ? $category_icons[$category->slug] : '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>';
                    ?>
                    <li class="dropdown-item" role="option" data-filter=".<?php echo esc_attr($category->slug); ?>" aria-selected="false">
                        <span class="dropdown-item__icon">
                            <?php echo $icon; ?>
                        </span>
                        <span class="dropdown-item__text"><?php echo esc_html($category->name); ?></span>
                        <span class="dropdown-item__count"><?php echo esc_html($category->count); ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        
        <!-- Filtre dropdown natif (Mobile) -->
        <div class="actualites-filters actualites-filters-mobile">
            <label for="mobile-realisations-select" class="screen-reader-text">
                <?php esc_html_e('Filtrer les actualités par catégorie', 'almetal'); ?>
            </label>
            <select id="mobile-realisations-select" class="mobile-filter-select" aria-label="<?php esc_attr_e('Filtrer les actualités', 'almetal'); ?>">
                <option value="*"><?php esc_html_e('Toutes les catégories', 'almetal'); ?></option>
                <?php foreach ($categories as $category) : ?>
                <option value=".<?php echo esc_attr($category->slug); ?>">
                    <?php echo esc_html($category->name); ?> (<?php echo esc_html($category->count); ?>)
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

/* ============================================
   MENU DÉROULANT STYLISÉ - RÉALISATIONS
   ============================================ */

.realisations-dropdown-wrapper {
    display: flex;
    justify-content: center;
    margin: 2rem auto 3rem;
    position: relative;
    z-index: 100;
}

.realisations-dropdown {
    position: relative;
    width: 100%;
    max-width: 400px;
}

/* Bouton déclencheur */
.dropdown-trigger {
    display: flex;
    align-items: center;
    gap: 12px;
    width: 100%;
    padding: 16px 20px;
    background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
    border: 2px solid #F08B18;
    border-radius: 12px;
    color: #fff;
    font-family: inherit;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 15px rgba(240, 139, 24, 0.15);
}

.dropdown-trigger:hover {
    background: linear-gradient(135deg, #222 0%, #333 100%);
    box-shadow: 0 6px 25px rgba(240, 139, 24, 0.25);
    transform: translateY(-2px);
}

.dropdown-trigger.active {
    background: linear-gradient(135deg, #2a2a2a 0%, #3a3a3a 100%);
    border-color: #ff9f40;
    box-shadow: 0 4px 20px rgba(240, 139, 24, 0.3);
}

.dropdown-trigger__icon {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #F08B18;
    flex-shrink: 0;
}

.dropdown-trigger__icon svg {
    stroke: #F08B18;
}

.dropdown-trigger__text {
    flex: 1;
    text-align: left;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.dropdown-trigger__count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 28px;
    padding: 4px 10px;
    background: rgba(240, 139, 24, 0.15);
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 700;
    color: #F08B18;
    flex-shrink: 0;
}

.dropdown-trigger__arrow {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #F08B18;
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    flex-shrink: 0;
}

.dropdown-trigger.active .dropdown-trigger__arrow {
    transform: rotate(180deg);
}

/* Menu déroulant */
.dropdown-menu {
    position: absolute;
    top: calc(100% + 8px);
    left: 0;
    right: 0;
    max-height: 0;
    opacity: 0;
    visibility: hidden;
    overflow: hidden;
    margin: 0;
    padding: 0;
    list-style: none;
    background: linear-gradient(135deg, #1a1a1a 0%, #252525 100%);
    border: 2px solid #F08B18;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(240, 139, 24, 0.1);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1000;
}

.realisations-dropdown.open .dropdown-menu {
    max-height: 400px;
    opacity: 1;
    visibility: visible;
    overflow-y: auto;
    padding: 8px 0;
}

/* Items du menu */
.dropdown-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 20px;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
    background: transparent;
    color: #fff;
    font-family: inherit;
    font-size: 0.95rem;
    font-weight: 500;
    width: 100%;
    text-align: left;
}

.dropdown-item:hover {
    background: rgba(240, 139, 24, 0.1);
}

.dropdown-item.active {
    background: rgba(240, 139, 24, 0.2);
    color: #F08B18;
}

.dropdown-item.active .dropdown-item__icon {
    color: #F08B18;
}

.dropdown-item__icon {
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255, 255, 255, 0.6);
    flex-shrink: 0;
    transition: color 0.2s ease;
}

.dropdown-item:hover .dropdown-item__icon {
    color: #F08B18;
}

.dropdown-item__text {
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.dropdown-item__count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 24px;
    padding: 2px 8px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.7);
    flex-shrink: 0;
}

.dropdown-item.active .dropdown-item__count {
    background: rgba(240, 139, 24, 0.2);
    color: #F08B18;
}

/* Scrollbar personnalisée */
.dropdown-menu::-webkit-scrollbar {
    width: 6px;
}

.dropdown-menu::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 3px;
}

.dropdown-menu::-webkit-scrollbar-thumb {
    background: rgba(240, 139, 24, 0.4);
    border-radius: 3px;
}

.dropdown-menu::-webkit-scrollbar-thumb:hover {
    background: rgba(240, 139, 24, 0.6);
}

/* Animation d'entrée des items */
.realisations-dropdown.open .dropdown-item {
    opacity: 0;
    transform: translateX(-10px);
    animation: slideIn 0.3s ease forwards;
}

.realisations-dropdown.open .dropdown-item:nth-child(1) { animation-delay: 0.05s; }
.realisations-dropdown.open .dropdown-item:nth-child(2) { animation-delay: 0.1s; }
.realisations-dropdown.open .dropdown-item:nth-child(3) { animation-delay: 0.15s; }
.realisations-dropdown.open .dropdown-item:nth-child(4) { animation-delay: 0.2s; }
.realisations-dropdown.open .dropdown-item:nth-child(5) { animation-delay: 0.25s; }
.realisations-dropdown.open .dropdown-item:nth-child(6) { animation-delay: 0.3s; }
.realisations-dropdown.open .dropdown-item:nth-child(7) { animation-delay: 0.35s; }
.realisations-dropdown.open .dropdown-item:nth-child(8) { animation-delay: 0.4s; }
.realisations-dropdown.open .dropdown-item:nth-child(9) { animation-delay: 0.45s; }
.realisations-dropdown.open .dropdown-item:nth-child(10) { animation-delay: 0.5s; }

@keyframes slideIn {
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Masquer le dropdown sur mobile */
@media (max-width: 768px) {
    .realisations-dropdown-wrapper {
        display: none;
    }
}

/* Masquer le select mobile sur desktop */
@media (min-width: 769px) {
    .actualites-filters-mobile {
        display: none;
    }
}

/* Overlay pour fermer le dropdown */
.dropdown-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: transparent;
    z-index: 99;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease;
}

.dropdown-overlay.active {
    opacity: 1;
    visibility: visible;
}
</style>
