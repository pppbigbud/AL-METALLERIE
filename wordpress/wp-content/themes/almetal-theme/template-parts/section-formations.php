<?php
/**
 * Section Formations - Page d'accueil
 * Récupère les données depuis le backoffice (Cards Formations)
 * 
 * @package ALMetallerie
 * @since 3.0.0
 */

// Récupérer les données depuis le backoffice
if (!class_exists('Almetal_Formations_Cards_Admin')) {
    return; // Ne rien afficher si la classe n'existe pas
}

$cards = Almetal_Formations_Cards_Admin::get_cards();
$section = Almetal_Formations_Cards_Admin::get_section_settings();

// Filtrer les cards actives
$active_cards = array_filter($cards, function($card) {
    return isset($card['active']) && $card['active'];
});

// Ne rien afficher si aucune card active
if (empty($active_cards)) {
    return;
}

// Paramètres de la section
$tag = !empty($section['tag']) ? $section['tag'] : 'Nos Formations';
$title = !empty($section['title']) ? $section['title'] : 'DÉVELOPPEZ VOS COMPÉTENCES';
$subtitle = !empty($section['subtitle']) ? $section['subtitle'] : '';
$bg_image = !empty($section['background_image']) ? $section['background_image'] : '';
$bg_overlay = isset($section['background_overlay']) ? floatval($section['background_overlay']) : 0.6;

// Fonction pour obtenir l'icône SVG (éviter redéclaration)
if (!function_exists('hp_get_formation_icon')) {
function hp_get_formation_icon($icon_key) {
    $icons = array(
        'users' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
        'home' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>',
        'building' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect><path d="M9 22v-4h6v4"></path><path d="M8 6h.01"></path><path d="M16 6h.01"></path><path d="M12 6h.01"></path><path d="M12 10h.01"></path><path d="M12 14h.01"></path><path d="M16 10h.01"></path><path d="M16 14h.01"></path><path d="M8 10h.01"></path><path d="M8 14h.01"></path></svg>',
        'graduation' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>',
        'tools' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>',
        'zap' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>',
        'certificate' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"></circle><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"></path></svg>',
        'calendar' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>',
    );
    return isset($icons[$icon_key]) ? $icons[$icon_key] : $icons['users'];
}
}

// Fonction pour parser les points clés (éviter redéclaration)
if (!function_exists('hp_parse_features')) {
function hp_parse_features($features_text) {
    if (empty($features_text)) return array();
    $lines = explode("\n", $features_text);
    $features = array();
    foreach ($lines as $line) {
        $line = trim($line);
        if (!empty($line)) {
            $features[] = $line;
        }
    }
    return array_slice($features, 0, 5); // Max 5 features
}
}
?>

<section class="hp-formations-section" id="formations"<?php if ($bg_image) : ?> style="background-image: url('<?php echo esc_url($bg_image); ?>');"<?php endif; ?>>
    <?php if ($bg_image) : ?>
    <div class="hp-formations-overlay" style="opacity: <?php echo esc_attr($bg_overlay); ?>;"></div>
    <?php endif; ?>
    
    <div class="hp-formations-wrapper">
        
        <!-- Tag -->
        <div class="hp-formations-tag">
            <span><?php echo esc_html($tag); ?></span>
        </div>

        <!-- Titre -->
        <h2 class="hp-formations-title"><?php echo esc_html($title); ?></h2>

        <!-- Sous-titre -->
        <?php if ($subtitle) : ?>
        <p class="hp-formations-subtitle"><?php echo esc_html($subtitle); ?></p>
        <?php endif; ?>

        <!-- Grille des cards -->
        <div class="hp-formations-cards">
            
            <?php foreach ($active_cards as $card) : 
                $is_coming_soon = !empty($card['coming_soon']);
                $has_image = !empty($card['image']);
                $icon = !empty($card['icon']) ? $card['icon'] : 'users';
                $features = hp_parse_features($card['features'] ?? '');
                $cta_url = !empty($card['cta_url']) ? $card['cta_url'] : '#';
                $cta_text = !empty($card['cta_text']) ? $card['cta_text'] : 'En savoir plus';
            ?>
            
            <?php if ($is_coming_soon) : ?>
            <!-- Card désactivée (bientôt disponible) -->
            <div class="hp-formation-card hp-formation-card--disabled">
                <div class="hp-formation-card-badge">Bientôt disponible</div>
            <?php else : ?>
            <!-- Card cliquable -->
            <a href="<?php echo esc_url(home_url($cta_url)); ?>" class="hp-formation-card">
            <?php endif; ?>
            
                <?php if ($has_image) : ?>
                <!-- Image -->
                <div class="hp-formation-card-image">
                    <img src="<?php echo esc_url($card['image']); ?>" 
                         alt="<?php echo esc_attr($card['image_alt'] ?? $card['title']); ?>"
                         loading="lazy">
                </div>
                <?php else : ?>
                <!-- Icône -->
                <div class="hp-formation-card-icon">
                    <?php echo hp_get_formation_icon($icon); ?>
                </div>
                <?php endif; ?>
                
                <h3 class="hp-formation-card-title"><?php echo esc_html($card['title']); ?></h3>
                
                <?php if (!empty($card['description'])) : ?>
                <p class="hp-formation-card-desc"><?php echo esc_html($card['description']); ?></p>
                <?php endif; ?>
                
                <?php if (!empty($features)) : ?>
                <ul class="hp-formation-card-list">
                    <?php foreach ($features as $feature) : ?>
                    <li>✓ <?php echo esc_html($feature); ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                
                <?php if (!$is_coming_soon) : ?>
                <span class="hp-formation-card-btn"><?php echo esc_html($cta_text); ?> →</span>
                <?php endif; ?>
                
            <?php if ($is_coming_soon) : ?>
            </div>
            <?php else : ?>
            </a>
            <?php endif; ?>
            
            <?php endforeach; ?>

        </div>
    </div>
</section>

<style>
/* ============================================
   SECTION FORMATIONS - HOME PAGE (hp-)
   Classes uniques avec préfixe hp- pour éviter conflits
   ============================================ */
.hp-formations-section {
    padding: 80px 0;
    background: linear-gradient(180deg, #0a0a0a 0%, #1a1a1a 100%);
}

.hp-formations-wrapper {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 4rem;
    text-align: center;
}

.hp-formations-tag {
    margin-bottom: 1.5rem;
}

.hp-formations-tag span {
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

.hp-formations-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #fff;
    margin: 0 0 1rem 0;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.hp-formations-subtitle {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.7);
    max-width: 600px;
    margin: 0 auto 3rem auto;
    line-height: 1.6;
}

.hp-formations-cards {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
    max-width: 900px;
    margin: 0 auto;
}

.hp-formation-card {
    background: rgba(34, 34, 34, 0.6);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    border: 1px solid rgba(240, 139, 24, 0.1);
    padding: 2rem;
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    transition: all 0.3s ease;
}

.hp-formation-card:hover {
    border-color: rgba(240, 139, 24, 0.4);
    box-shadow: 0 15px 40px rgba(240, 139, 24, 0.2);
    transform: translateY(-5px);
}

.hp-formation-card-icon {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #F08B18 0%, #e67e0f 100%);
    border-radius: 50%;
    margin-bottom: 1.5rem;
    box-shadow: 0 8px 25px rgba(240, 139, 24, 0.3);
}

.hp-formation-card-icon svg {
    width: 40px;
    height: 40px;
    color: white;
    stroke: white;
}

.hp-formation-card-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #F08B18;
    margin: 0 0 1rem 0;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.hp-formation-card-desc {
    font-size: 1rem;
    line-height: 1.7;
    color: rgba(255, 255, 255, 0.8);
    margin: 0 0 1.5rem 0;
}

.hp-formation-card-list {
    list-style: none;
    padding: 0;
    margin: 0 0 1.5rem 0;
    text-align: left;
    width: 100%;
}

.hp-formation-card-list li {
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.95rem;
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.hp-formation-card-list li:last-child {
    border-bottom: none;
}

.hp-formation-card-btn {
    display: inline-block;
    padding: 12px 24px;
    background: linear-gradient(135deg, #F08B18 0%, #e67e0f 100%);
    color: white;
    font-weight: 600;
    border-radius: 8px;
    margin-top: auto;
    transition: all 0.3s ease;
}

.hp-formation-card:hover .hp-formation-card-btn {
    box-shadow: 0 8px 25px rgba(240, 139, 24, 0.4);
}

/* Image de fond de section avec dégradé vers le bas */
.hp-formations-section[style*="background-image"] {
    position: relative;
    background-size: cover;
    background-position: center top;
}

.hp-formations-section[style*="background-image"]::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        to bottom,
        rgba(10, 10, 10, 0.3) 0%,
        rgba(10, 10, 10, 0.5) 40%,
        rgba(10, 10, 10, 0.85) 70%,
        #0a0a0a 100%
    );
    z-index: 0;
}

.hp-formations-overlay {
    display: none; /* On utilise le pseudo-element ::before à la place */
}

.hp-formations-section[style*="background-image"] .hp-formations-wrapper {
    position: relative;
    z-index: 1;
}

/* Image de card */
.hp-formation-card-image {
    width: 100%;
    height: 180px;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.hp-formation-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.hp-formation-card:hover .hp-formation-card-image img {
    transform: scale(1.05);
}

/* Card désactivée (bientôt disponible) */
.hp-formation-card--disabled {
    position: relative;
    cursor: not-allowed;
    opacity: 0.7;
}

.hp-formation-card--disabled:hover {
    transform: none;
    box-shadow: none;
    border-color: rgba(240, 139, 24, 0.1);
}

.hp-formation-card-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: rgba(240, 139, 24, 0.9);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Grille adaptative selon nombre de cards */
.hp-formations-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
    max-width: 100%;
    margin: 0 auto;
}

/* Responsive */
@media (max-width: 768px) {
    .hp-formations-section {
        padding: 60px 0;
    }
    
    .hp-formations-title {
        font-size: 1.75rem;
    }
    
    .hp-formations-cards {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .hp-formation-card {
        padding: 1.5rem;
    }
    
    .hp-formation-card-icon {
        width: 60px;
        height: 60px;
    }
    
    .hp-formation-card-icon svg {
        width: 30px;
        height: 30px;
    }
    
    .hp-formation-card-title {
        font-size: 1.25rem;
    }
}
</style>
