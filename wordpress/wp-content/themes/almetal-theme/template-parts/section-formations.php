<?php
/**
 * Section Formations - Page d'accueil (Version dynamique)
 * Affiche les cards formations depuis le backoffice
 * 
 * @package ALMetallerie
 * @since 2.0.0
 */

// Récupérer les données depuis l'admin
$cards = Almetal_Formations_Cards_Admin::get_cards();
$section = Almetal_Formations_Cards_Admin::get_section_settings();

// Filtrer les cards actives
$active_cards = array_filter($cards, function($card) {
    return isset($card['active']) && $card['active'];
});

// Si aucune card active, ne pas afficher la section
if (empty($active_cards)) {
    return;
}

// Préparer les styles inline pour l'image de fond
$bg_style = '';
$has_bg = !empty($section['background_image']);
if ($has_bg) {
    $overlay = isset($section['background_overlay']) ? floatval($section['background_overlay']) : 0.7;
    $bg_style = 'style="--bg-image: url(' . esc_url($section['background_image']) . '); --bg-overlay: ' . $overlay . ';"';
}
$section_class = 'formations-section' . ($has_bg ? ' has-background' : '');
?>

<section class="<?php echo esc_attr($section_class); ?>" id="formations" <?php echo $bg_style; ?>>
    <?php if ($has_bg) : ?>
    <div class="formations-background">
        <img src="<?php echo esc_url($section['background_image']); ?>" alt="" loading="lazy">
        <div class="formations-overlay"></div>
    </div>
    <?php endif; ?>
    
    <div class="formations-container">
        <!-- Tag -->
        <?php if (!empty($section['tag'])) : ?>
        <div class="formations-tag">
            <span><?php echo esc_html($section['tag']); ?></span>
        </div>
        <?php endif; ?>

        <!-- Titre -->
        <?php if (!empty($section['title'])) : ?>
        <h2 class="formations-title">
            <?php echo esc_html($section['title']); ?>
        </h2>
        <?php endif; ?>

        <!-- Description -->
        <?php if (!empty($section['subtitle'])) : ?>
        <p class="formations-subtitle">
            <?php echo esc_html($section['subtitle']); ?>
        </p>
        <?php endif; ?>

        <!-- Grille de cartes -->
        <div class="formations-grid">
            
            <?php foreach ($active_cards as $card) : 
                $is_coming_soon = !empty($card['coming_soon']);
                $card_class = 'formation-card' . ($is_coming_soon ? ' formation-card--coming-soon' : '');
            ?>
            <!-- Card <?php echo esc_html($card['title']); ?> -->
            <article class="<?php echo esc_attr($card_class); ?>">
                <div class="formation-card-inner">
                    
                    <?php if ($is_coming_soon) : ?>
                    <!-- Badge Bientôt disponible -->
                    <div class="coming-soon-badge">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        Bientôt disponible
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($card['image'])) : ?>
                    <!-- Image -->
                    <div class="formation-image">
                        <img src="<?php echo esc_url($card['image']); ?>" 
                             alt="<?php echo esc_attr($card['image_alt'] ?: $card['title']); ?>"
                             loading="lazy"
                             width="400"
                             height="250">
                    </div>
                    <?php elseif (!empty($card['icon'])) : ?>
                    <!-- Icône -->
                    <div class="formation-icon">
                        <?php echo almetal_get_formation_icon($card['icon']); ?>
                    </div>
                    <?php endif; ?>

                    <!-- Contenu -->
                    <div class="formation-content">
                        <?php if (!empty($card['title'])) : ?>
                        <h3 class="formation-title">
                            <?php echo esc_html($card['title']); ?>
                        </h3>
                        <?php endif; ?>
                        
                        <?php if (!empty($card['description'])) : ?>
                        <p class="formation-description">
                            <?php echo esc_html($card['description']); ?>
                        </p>
                        <?php endif; ?>

                        <?php 
                        // Points clés
                        $features = almetal_format_formation_features($card['features'] ?? '');
                        if (!empty($features)) : 
                        ?>
                        <ul class="formation-features">
                            <?php foreach ($features as $feature) : ?>
                            <li>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span><?php echo esc_html($feature); ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>

                        <?php if (!$is_coming_soon && !empty($card['cta_text']) && !empty($card['cta_url'])) : ?>
                        <!-- Bouton (masqué si bientôt disponible) -->
                        <a href="<?php echo esc_url(home_url($card['cta_url'])); ?>" class="btn-view-project">
                            <span class="circle" aria-hidden="true">
                                <svg class="icon arrow" width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 6H17M17 6L12 1M17 6L12 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <span class="button-text"><?php echo esc_html($card['cta_text']); ?></span>
                        </a>
                        <?php elseif ($is_coming_soon) : ?>
                        <!-- Texte de remplacement pour bientôt disponible -->
                        <div class="coming-soon-text">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            Cette formation sera bientôt disponible
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>

        </div>
    </div>
</section>
