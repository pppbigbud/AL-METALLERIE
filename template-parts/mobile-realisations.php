<?php
/**
 * Template Part: Section Réalisations Mobile
 * Système AJAX avec filtrage et pagination "Voir plus"
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

// Récupérer les catégories de réalisations
$categories = get_terms(array(
    'taxonomy' => 'type_realisation',
    'hide_empty' => true,
));

// Compter le total de réalisations
$total_count = wp_count_posts('realisation')->publish;
?>

<div class="mobile-realisations-container" 
     id="mobile-realisations-ajax" 
     data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
     data-total="<?php echo esc_attr($total_count); ?>">
    
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
            <option value="*" selected>
                <?php echo esc_html(sprintf(__('Toutes (%d)', 'almetal'), $total_count)); ?>
            </option>
            
            <?php foreach ($categories as $category) : ?>
            <option value="<?php echo esc_attr($category->slug); ?>">
                <?php echo esc_html(sprintf('%s (%d)', $category->name, $category->count)); ?>
            </option>
            <?php endforeach; ?>
        </select>
        
        <svg class="mobile-select-arrow" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
    </div>
    <?php endif; ?>

    <!-- Loader -->
    <div class="realisations-ajax-loader" id="realisations-loader" style="display: none;">
        <div class="realisations-spinner"></div>
        <span>Chargement...</span>
    </div>

    <!-- Grille de réalisations (chargée via AJAX) -->
    <div class="mobile-realisations-grid" id="mobile-realisations-grid">
        <!-- Les cards seront chargées ici via AJAX -->
    </div>

    <!-- Message si aucun résultat -->
    <div class="realisations-no-results" id="realisations-empty" style="display: none;">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="11" cy="11" r="8"/>
            <path d="M21 21l-4.35-4.35"/>
        </svg>
        <p>Aucune réalisation trouvée dans cette catégorie.</p>
    </div>

    <!-- Bouton Voir plus -->
    <div class="realisations-loadmore-wrap" id="realisations-loadmore-wrap">
        <button type="button" class="btn-realisations-loadmore" id="btn-load-more-realisations">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
            <span>Voir plus</span>
        </button>
    </div>

    <!-- Bouton voir toutes les réalisations -->
    <div class="mobile-realisations-cta">
        <a href="<?php echo esc_url(get_post_type_archive_link('realisation')); ?>" class="mobile-btn-cta">
            <?php esc_html_e('Voir toutes les réalisations', 'almetal'); ?>
        </a>
    </div>
</div>
