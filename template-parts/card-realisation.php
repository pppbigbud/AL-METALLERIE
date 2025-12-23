<?php
/**
 * Template-part pour les cards de réalisations (version desktop unifiée)
 * Basé sur le modèle de la page d'accueil avec intégration des badges
 * 
 * @package ALMetallerie
 * @since 1.0.0
 * 
 * @param array $args {
 *     Arguments optionnels pour personnaliser l'affichage
 *     
 *     @type bool   $show_category_badges  Afficher les badges de catégories (défaut: true)
 *     @type bool   $show_location_badge   Afficher le badge de localisation sur l'image (défaut: true)
 *     @type bool   $show_meta             Afficher les métadonnées (défaut: true)
 *     @type bool   $show_cta              Afficher le bouton CTA (défaut: true)
 *     @type bool   $is_first              Est-ce la première card (pour LCP) (défaut: false)
 *     @type string $image_size            Taille de l'image (défaut: 'realisation-card')
 * }
 */

// Valeurs par défaut
$defaults = array(
    'show_category_badges' => true,
    'show_location_badge' => true,
    'show_meta' => true,
    'show_cta' => true,
    'is_first' => false,
    'image_size' => 'realisation-card',
);

$args = wp_parse_args($args, $defaults);

// Récupérer les données
$terms = get_the_terms(get_the_ID(), 'type_realisation');
$term_classes = '';
if ($terms && !is_wp_error($terms)) {
    $term_slugs = array_map(function($term) {
        return $term->slug;
    }, $terms);
    $term_classes = implode(' ', $term_slugs);
}

// Image à la une
$thumbnail_id = get_post_thumbnail_id(get_the_ID());
$thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), $args['image_size']);
if (!$thumbnail_url) {
    $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
}
if (!$thumbnail_url) {
    $thumbnail_url = get_template_directory_uri() . '/assets/images/gallery/pexels-kelly-2950108 1.webp';
}
$srcset = $thumbnail_id ? wp_get_attachment_image_srcset($thumbnail_id, $args['image_size']) : '';
$sizes = '(max-width: 480px) 300px, (max-width: 768px) 390px, 640px';

// Alt SEO optimisé
$type_realisation = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : 'Réalisation';
$lieu = get_post_meta(get_the_ID(), '_almetal_lieu', true) ?: 'Puy-de-Dôme';
$alt_seo = $type_realisation . ' à ' . $lieu . ' - ' . get_the_title() . ' | AL Métallerie';

// Métadonnées
$date_realisation = get_post_meta(get_the_ID(), '_almetal_date_realisation', true);
$lieu = get_post_meta(get_the_ID(), '_almetal_lieu', true);
$duree = get_post_meta(get_the_ID(), '_almetal_duree', true);
$matiere = get_post_meta(get_the_ID(), '_almetal_matiere', true);
?>

<article class="realisation-card <?php echo esc_attr($term_classes); ?>" data-categories="<?php echo esc_attr($term_classes); ?>">
    <div class="realisation-image-wrapper">
        <?php if ($thumbnail_url) : ?>
            <img src="<?php echo esc_url($thumbnail_url); ?>" 
                 alt="<?php echo esc_attr($alt_seo); ?>" 
                 class="realisation-image<?php echo $args['is_first'] ? ' no-lazyload' : ''; ?>"
                 width="400"
                 height="300"
                 <?php if ($srcset) : ?>
                 srcset="<?php echo esc_attr($srcset); ?>"
                 sizes="<?php echo esc_attr($sizes); ?>"
                 <?php endif; ?>
                 <?php if ($args['is_first']) : ?>
                 fetchpriority="high"
                 decoding="sync"
                 data-no-lazy="1"
                 <?php else : ?>
                 loading="lazy"
                 decoding="async"
                 <?php endif; ?>>
        <?php endif; ?>
        
        <!-- Badges de catégories en haut à droite -->
        <?php if ($args['show_category_badges'] && $terms && !is_wp_error($terms)) : ?>
            <div class="realisation-category-badges">
                <?php 
                // Badge matériau en premier
                if ($matiere) {
                    $matiere_labels = array(
                        'acier' => 'Acier',
                        'inox' => 'Inox',
                        'aluminium' => 'Aluminium',
                        'cuivre' => 'Cuivre',
                        'laiton' => 'Laiton',
                        'fer-forge' => 'Fer forgé',
                        'mixte' => 'Mixte'
                    );
                    ?>
                    <span class="category-badge category-badge-matiere">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                        </svg>
                        <?php echo esc_html($matiere_labels[$matiere] ?? ucfirst($matiere)); ?>
                    </span>
                    <?php
                }
                
                // Badges de catégories
                foreach ($terms as $type) : ?>
                    <a href="<?php echo esc_url(get_term_link($type)); ?>" class="category-badge">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                        </svg>
                        <?php echo esc_html($type->name); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- Badge de localisation sur l'image -->
        <?php if ($args['show_location_badge'] && $lieu) : ?>
            <span class="realisation-location-badge">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>
                <?php echo almetal_city_link_html($lieu, 'meta-lieu-link'); ?>
            </span>
        <?php endif; ?>
    </div>
    
    <div class="realisation-content">
        <h3 class="realisation-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <!-- Métadonnées -->
        <?php if ($args['show_meta'] && ($date_realisation || $lieu || $duree)) : ?>
            <div class="realisation-meta">
                <?php if ($date_realisation) : ?>
                    <span class="meta-item meta-date">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <?php echo esc_html($date_realisation); ?>
                    </span>
                <?php endif; ?>
                <?php if ($lieu && !$args['show_location_badge']) : ?>
                    <span class="meta-item meta-lieu">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        <?php echo almetal_city_link_html($lieu, 'meta-lieu-link'); ?>
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
        <?php endif; ?>

        <!-- Extrait court intégré directement -->
        <?php 
        $excerpt = get_the_excerpt();
        if (empty($excerpt)) {
            // Fallback sur le contenu si aucun excerpt n'est défini
            $content = get_the_content();
            $excerpt = wp_trim_words($content, 15, '...');
        } else {
            $excerpt = wp_trim_words($excerpt, 15, '...');
        }
        echo '<p class="realisation-excerpt">' . esc_html($excerpt) . '</p>';
        ?>

        <!-- Bouton CTA -->
        <?php if ($args['show_cta']) : ?>
            <a href="<?php the_permalink(); ?>" class="btn-view-project">
                <span class="circle" aria-hidden="true">
                    <svg class="icon arrow" width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 6H17M17 6L12 1M17 6L12 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <span class="button-text"><?php _e('Voir le projet', 'almetal'); ?></span>
            </a>
        <?php endif; ?>
    </div>
</article>
