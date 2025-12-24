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
        <a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>">
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
        </a>
        
        <!-- Badges de catégories en haut à droite -->
        <?php if ($args['show_category_badges'] && $terms && !is_wp_error($terms)) : ?>
            <div class="realisation-category-badges">
                <?php 
                // Badges de catégories en premier avec SVG fonctionnels
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
                    'menuiseries-exterieures' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9h18v10H3z"/><path d="M3 9l9-7 9 7"/><path d="M12 2v7"/></svg>',
                    'structures-metalliques' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3h18v18H3zM12 8v8M8 12h8"/></svg>',
                    'clotures-barrieres' => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4v16M8 4v16M12 4v16M16 4v16M20 4v16M2 8h20M2 16h20"/></svg>'
                );
                
                foreach ($terms as $type) : ?>
                    <a href="<?php echo esc_url(get_term_link($type)); ?>" class="category-badge">
                        <?php 
                        $slug = $type->slug;
                        echo isset($mobile_icons[$slug]) ? $mobile_icons[$slug] : '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="9" x2="15" y2="9"/><line x1="9" y1="15" x2="15" y2="15"/></svg>';
                        ?>
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
        <?php 
        $matiere = get_post_meta(get_the_ID(), '_almetal_matiere', true);
        if ($args['show_meta'] && ($date_realisation || $lieu || $duree || $matiere)) : 
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
                <?php if ($matiere) : ?>
                    <span class="meta-item meta-matiere">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
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
                        $matiere_url = almetal_get_matiere_url($matiere);
                        if ($matiere_url) {
                            echo '<a href="' . esc_url($matiere_url) . '" class="meta-matiere-link">' . esc_html($matiere_labels[$matiere] ?? ucfirst($matiere)) . '</a>';
                        } else {
                            echo esc_html($matiere_labels[$matiere] ?? ucfirst($matiere));
                        }
                        ?>
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
        ?>
        <a href="<?php the_permalink(); ?>" class="realisation-excerpt-link">
            <p class="realisation-excerpt"><?php echo esc_html($excerpt); ?></p>
        </a>

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
