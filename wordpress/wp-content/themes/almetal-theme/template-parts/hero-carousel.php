<?php
/**
 * Template part pour le carrousel hero
 * Version adaptative : Desktop (JS custom) / Mobile (Swiper.js)
 * Données dynamiques depuis l'interface d'administration
 * Support des slides commerciales/promotionnelles
 */

$is_mobile = almetal_is_mobile();

// Récupérer les slides depuis la base de données
$slides = Almetal_Slideshow_Admin::get_slides();

// Filtrer les slides actifs uniquement
$active_slides = array_filter($slides, function($slide) {
    return isset($slide['active']) && $slide['active'] === true;
});

// Trier par ordre
usort($active_slides, function($a, $b) {
    return ($a['order'] ?? 0) - ($b['order'] ?? 0);
});

// Si aucun slide actif, ne rien afficher
if (empty($active_slides)) {
    return;
}

/**
 * Fonction helper pour obtenir le texte du badge
 * Si un badge personnalisé est rempli, il est prioritaire
 */
function almetal_get_promo_badge_text($slide) {
    $badge = isset($slide['promo_badge']) ? $slide['promo_badge'] : '';
    $custom = isset($slide['promo_badge_custom']) ? trim($slide['promo_badge_custom']) : '';
    
    // Si badge personnalisé rempli, l'utiliser en priorité
    if (!empty($custom)) {
        return $custom;
    }
    
    // Sinon utiliser le badge prédéfini
    $badges = array(
        'promo' => '🔥 PROMO',
        'nouveau' => '✨ NOUVEAU',
        'exclusif' => '⭐ EXCLUSIF',
        'limited' => '⏰ ÉDITION LIMITÉE',
        'bestseller' => '🏆 BEST-SELLER',
        'soldout' => '❌ ÉPUISÉ',
        'noel' => '🎄 SPÉCIAL NOËL',
    );
    
    return isset($badges[$badge]) ? $badges[$badge] : '';
}

/**
 * Fonction helper pour formater les points forts
 */
function almetal_format_features($features_text) {
    if (empty($features_text)) return array();
    $lines = explode("\n", $features_text);
    return array_filter(array_map('trim', $lines));
}
?>

<?php if ($is_mobile) : ?>
    <!-- Hero Carousel MOBILE (Swiper) - Contenu dynamique -->
    <div class="mobile-hero-swiper swiper">
        <div class="swiper-wrapper">
            <?php 
            $slide_index = 0;
            foreach ($active_slides as $slide) : 
                $is_promo = isset($slide['is_promo']) && $slide['is_promo'];
                $slide_class = $is_promo ? 'mobile-hero-slide slide-promo' : 'mobile-hero-slide';
                $is_first_slide = ($slide_index === 0);
                // Utiliser l'image optimisee si disponible
                $image_url = function_exists('almetal_get_optimized_image') 
                    ? almetal_get_optimized_image($slide['image'], 'slideshow', $slide_index)
                    : $slide['image'];
            ?>
                <!-- Slide <?php echo ($slide_index + 1); ?> -->
                <div class="swiper-slide <?php echo $slide_class; ?>">
                    <!-- Toutes les slides utilisent IMG pour un meilleur LCP -->
                    <img src="<?php echo esc_url($image_url); ?>" 
                         alt="<?php echo esc_attr($slide['title'] ?? 'AL Metallerie'); ?>"
                         class="mobile-hero-image-img"
                         width="480"
                         height="640"
                         <?php if ($is_first_slide) : ?>fetchpriority="high"<?php else : ?>loading="lazy"<?php endif; ?>
                         decoding="async">
                    <div class="mobile-hero-overlay<?php echo $is_promo ? ' promo-overlay' : ''; ?>"></div>
                    
                    <?php if ($is_promo) : ?>
                        <!-- Badge Promo Mobile -->
                        <?php $badge_text = almetal_get_promo_badge_text($slide); ?>
                        <?php if (!empty($badge_text)) : ?>
                            <div class="promo-badge-mobile"><?php echo esc_html($badge_text); ?></div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <div class="mobile-hero-content<?php echo $is_promo ? ' promo-content' : ''; ?>">
                        <?php if (!empty($slide['title'])) : ?>
                            <h2 class="mobile-hero-title"><?php echo esc_html($slide['title']); ?></h2>
                        <?php endif; ?>
                        
                        <?php if (!empty($slide['subtitle'])) : ?>
                            <p class="mobile-hero-subtitle"><?php echo esc_html($slide['subtitle']); ?></p>
                        <?php endif; ?>
                        
                        <?php if ($is_promo) : ?>
                            <!-- Bloc Prix Mobile -->
                            <?php if (!empty($slide['promo_price']) || !empty($slide['promo_old_price'])) : ?>
                                <div class="promo-price-block-mobile">
                                    <?php if (!empty($slide['promo_old_price'])) : ?>
                                        <span class="old-price"><?php echo esc_html($slide['promo_old_price']); ?> €</span>
                                    <?php endif; ?>
                                    <?php if (!empty($slide['promo_price'])) : ?>
                                        <span class="current-price"><?php echo esc_html($slide['promo_price']); ?> €</span>
                                    <?php endif; ?>
                                    <?php if (!empty($slide['promo_discount'])) : ?>
                                        <span class="discount-badge"><?php echo esc_html($slide['promo_discount']); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Stock Mobile -->
                            <?php if (!empty($slide['promo_stock'])) : ?>
                                <div class="promo-stock-mobile">
                                    <span class="dashicons dashicons-warning"></span>
                                    <?php echo esc_html($slide['promo_stock']); ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <?php if (!empty($slide['cta_text']) && !empty($slide['cta_url'])) : 
                            // Utiliser le texte mobile si défini, sinon le texte desktop
                            $mobile_cta_text = !empty($slide['cta_text_mobile']) ? $slide['cta_text_mobile'] : $slide['cta_text'];
                        ?>
                            <a href="<?php echo esc_url($slide['cta_url']); ?>" class="mobile-hero-cta<?php echo $is_promo ? ' promo-cta' : ''; ?>">
                                <span><?php echo esc_html($mobile_cta_text); ?></span>
                                <svg class="cta-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 3l7.07 16.97 2.51-7.39 7.39-2.51L3 3z"/>
                                    <path d="M13 13l6 6"/>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php 
            $slide_index++;
            endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <div class="swiper-pagination"></div>
    </div>

<?php else : ?>
    <!-- Hero Carousel DESKTOP (JS custom) - Contenu dynamique -->
    <section id="hero" class="hero-carousel">
        <div class="hero-slides">
            <?php foreach ($active_slides as $index => $slide) : 
                $is_promo = isset($slide['is_promo']) && $slide['is_promo'];
                $slide_class = $is_promo ? 'hero-slide slide-promo' : 'hero-slide';
                
                // Debug temporaire - à supprimer après test
                // error_log('Slide ' . $index . ' - is_promo: ' . ($is_promo ? 'true' : 'false'));
                // error_log('Slide ' . $index . ' - promo_price: ' . (isset($slide['promo_price']) ? $slide['promo_price'] : 'non défini'));
                // error_log('Slide ' . $index . ' - promo_stock: ' . (isset($slide['promo_stock']) ? $slide['promo_stock'] : 'non défini'));
            ?>
                <!-- Slide <?php echo ($index + 1); ?><?php echo $is_promo ? ' (PROMO)' : ''; ?> -->
                <div class="<?php echo $slide_class; ?>" style="background-image: url('<?php echo esc_url($slide['image']); ?>');">
                    
                    <?php if ($is_promo) : ?>
                        <!-- Badge Promo -->
                        <?php $badge_text = almetal_get_promo_badge_text($slide); ?>
                        <?php if (!empty($badge_text)) : ?>
                            <div class="promo-badge-hero"><?php echo esc_html($badge_text); ?></div>
                        <?php endif; ?>
                        
                        <!-- Compte à rebours si date de fin -->
                        <?php if (!empty($slide['promo_end_date'])) : ?>
                            <div class="promo-countdown" data-end-date="<?php echo esc_attr($slide['promo_end_date']); ?>">
                                <span class="countdown-label">Offre valable encore</span>
                                <div class="countdown-timer">
                                    <div class="countdown-item"><span class="countdown-days">--</span><small>jours</small></div>
                                    <div class="countdown-item"><span class="countdown-hours">--</span><small>heures</small></div>
                                    <div class="countdown-item"><span class="countdown-minutes">--</span><small>min</small></div>
                                    <div class="countdown-item"><span class="countdown-seconds">--</span><small>sec</small></div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <?php if ($is_promo) : ?>
                        <!-- LAYOUT PROMO : Titre à gauche, Card à droite -->
                        <div class="promo-layout">
                            <!-- Colonne gauche : Titre et sous-titre dans une card -->
                            <div class="promo-left">
                                <div class="promo-left-card">
                                    <?php if (!empty($slide['title'])) : ?>
                                        <h2 class="hero-title"><?php echo esc_html($slide['title']); ?></h2>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($slide['subtitle'])) : ?>
                                        <p class="hero-subtitle"><?php echo esc_html($slide['subtitle']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Colonne droite : Card promo avec CTA -->
                            <div class="promo-right">
                                <div class="promo-card">
                                    <!-- Prix -->
                                    <?php if (!empty($slide['promo_price']) || !empty($slide['promo_old_price'])) : ?>
                                        <div class="promo-price-block">
                                            <?php if (!empty($slide['promo_old_price'])) : ?>
                                                <span class="old-price"><?php echo esc_html($slide['promo_old_price']); ?> €</span>
                                            <?php endif; ?>
                                            <?php if (!empty($slide['promo_price'])) : ?>
                                                <span class="current-price"><?php echo esc_html($slide['promo_price']); ?> €</span>
                                            <?php endif; ?>
                                            <?php if (!empty($slide['promo_discount'])) : ?>
                                                <span class="discount-badge"><?php echo esc_html($slide['promo_discount']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Points forts -->
                                    <?php 
                                    $features = almetal_format_features(isset($slide['promo_features']) ? $slide['promo_features'] : '');
                                    if (!empty($features)) : 
                                    ?>
                                        <ul class="promo-features">
                                            <?php foreach ($features as $feature) : ?>
                                                <li><?php echo esc_html($feature); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                    
                                    <!-- Stock -->
                                    <?php if (!empty($slide['promo_stock'])) : ?>
                                        <div class="promo-stock">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                            <?php echo esc_html($slide['promo_stock']); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Bouton CTA dans la card -->
                                    <?php if (!empty($slide['cta_text']) && !empty($slide['cta_url'])) : ?>
                                        <a href="<?php echo esc_url($slide['cta_url']); ?>" class="promo-cta-button">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                            <?php echo esc_html($slide['cta_text']); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <!-- LAYOUT STANDARD : Contenu centré -->
                        <div class="hero-content">
                            <?php if (!empty($slide['title'])) : ?>
                                <h2 class="hero-title"><?php echo esc_html($slide['title']); ?></h2>
                            <?php endif; ?>
                            
                            <?php if (!empty($slide['subtitle'])) : ?>
                                <p class="hero-subtitle"><?php echo esc_html($slide['subtitle']); ?></p>
                            <?php endif; ?>
                            
                            <?php if (!empty($slide['cta_text']) && !empty($slide['cta_url'])) : ?>
                                <a href="<?php echo esc_url($slide['cta_url']); ?>" class="hero-cta">
                                    <span><?php echo esc_html($slide['cta_text']); ?></span>
                                    <!-- Icône curseur/pointeur -->
                                    <svg class="cta-icon cta-icon-right" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 3l7.07 16.97 2.51-7.39 7.39-2.51L3 3z"/>
                                        <path d="M13 13l6 6"/>
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Contrôles du carrousel -->
        <button class="hero-prev" aria-label="Slide précédent">
            <span class="circle" aria-hidden="true">
                <svg class="icon arrow arrow-left" width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17 6H1M1 6L6 1M1 6L6 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
        </button>
        <button class="hero-next" aria-label="Slide suivant">
            <span class="circle" aria-hidden="true">
                <svg class="icon arrow arrow-right" width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 6H17M17 6L12 1M17 6L12 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
        </button>
        
        <!-- Indicateurs de slide -->
        <div class="hero-indicators"></div>
    </section>
<?php endif; ?>
