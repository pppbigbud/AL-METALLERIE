<?php
/**
 * Template pour une réalisation individuelle
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

get_header();

// Détection mobile - Charger le template mobile si nécessaire
if (almetal_is_mobile()) {
    ?>
    <!-- Header Mobile avec bouton RETOUR -->
    <?php get_template_part('template-parts/header', 'mobile'); ?>
    
    <main class="mobile-page-realisation">
        <?php
        while (have_posts()) :
            the_post();
            get_template_part('template-parts/single-realisation', 'mobile');
        endwhile;
        ?>
    </main>
    
    <?php
    get_footer();
    return;
}

?>

<?php
// Récupérer les données une seule fois
$types = get_the_terms(get_the_ID(), 'type_realisation');
$client = get_post_meta(get_the_ID(), '_almetal_client', true);
$date_realisation = get_post_meta(get_the_ID(), '_almetal_date_realisation', true);
$lieu = get_post_meta(get_the_ID(), '_almetal_lieu', true);
$duree = get_post_meta(get_the_ID(), '_almetal_duree', true);

// Nouveaux champs
$client_type = get_post_meta(get_the_ID(), '_almetal_client_type', true);
$client_nom = get_post_meta(get_the_ID(), '_almetal_client_nom', true);
$client_url = get_post_meta(get_the_ID(), '_almetal_client_url', true);
$matiere = get_post_meta(get_the_ID(), '_almetal_matiere', true);
$peinture = get_post_meta(get_the_ID(), '_almetal_peinture', true);
$pose = get_post_meta(get_the_ID(), '_almetal_pose', true);

// Labels des matières
$matiere_labels = array(
    'acier' => 'Acier',
    'inox' => 'Inox',
    'aluminium' => 'Aluminium',
    'cuivre' => 'Cuivre',
    'laiton' => 'Laiton',
    'fer-forge' => 'Fer forgé',
    'mixte' => 'Mixte'
);
$matiere_label = isset($matiere_labels[$matiere]) ? $matiere_labels[$matiere] : $matiere;

// Icônes SVG par type de réalisation
$icons = array(
    'portails' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="18" rx="1"/><rect x="14" y="3" width="7" height="18" rx="1"/></svg>',
    'garde-corps' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/><circle cx="6" cy="12" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="18" cy="12" r="1"/></svg>',
    'escaliers' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 20h4v-4h4v-4h4V8h4"/></svg>',
    'pergolas' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M4 18h16M5 15h14M6 12h12M7 9h10M8 6h8M9 3h6"/></svg>',
    'grilles' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18M15 3v18"/></svg>',
    'ferronnerie-art' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 20c4-4 4-12 8-12s4 8 8 12"/><path d="M4 16c3-3 3-8 6-8s3 5 6 8"/><circle cx="12" cy="8" r="2"/></svg>',
    'ferronnerie-dart' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 20c4-4 4-12 8-12s4 8 8 12"/><path d="M4 16c3-3 3-8 6-8s3 5 6 8"/><circle cx="12" cy="8" r="2"/></svg>',
    'rampes' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21v-8l6-6 6 6 6-6v8"/></svg>',
    'vehicules' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 17h14v-5l-2-4H7l-2 4v5z"/><path d="M3 17h18v2H3z"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/><path d="M5 12h14"/></svg>',
    'serrurerie' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M12 16v2"/><circle cx="12" cy="16" r="1"/><path d="M8 11V7a4 4 0 1 1 8 0v4"/></svg>',
    'mobilier-metallique' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="6" width="16" height="4" rx="1"/><path d="M6 10v10M18 10v10"/><path d="M4 14h16"/></svg>',
    'autres' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>',
);
?>

<div class="single-realisation single-realisation-v2">
    <?php
    while (have_posts()) :
        the_post();
        ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <!-- HERO avec image, titre et badges superposés -->
            <?php if (has_post_thumbnail()) : ?>
                <div class="realisation-hero-v2">
                    <div class="hero-image">
                        <?php the_post_thumbnail('full'); ?>
                        <div class="hero-overlay"></div>
                    </div>
                    <div class="hero-content">
                        <div class="hero-content-inner">
                            <h1 class="hero-title"><?php the_title(); ?></h1>
                            <?php if ($types && !is_wp_error($types)) : ?>
                                <div class="realisation-categories">
                                    <?php foreach ($types as $type) : 
                                        $icon = isset($icons[$type->slug]) ? $icons[$type->slug] : $icons['autres'];
                                    ?>
                                        <a href="<?php echo esc_url(get_term_link($type)); ?>" class="category-badge">
                                            <span class="category-badge__icon"><?php echo $icon; ?></span>
                                            <span class="category-badge__text"><?php echo esc_html($type->name); ?></span>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php
            // Afficher le fil d'Ariane SEO (sous la photo)
            almetal_seo_breadcrumb();
            ?>

            <!-- BARRE D'INFOS HORIZONTALE -->
            <?php if ($date_realisation || $lieu || $duree || $client || $matiere || $peinture || $pose || $client_nom) : ?>
                <div class="realisation-info-bar">
                    <div class="container">
                        <div class="info-bar-items">
                            <?php if ($date_realisation) : ?>
                                <div class="info-bar-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                        <line x1="16" y1="2" x2="16" y2="6"/>
                                        <line x1="8" y1="2" x2="8" y2="6"/>
                                        <line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                    <span class="info-label"><?php _e('Date', 'almetal'); ?></span>
                                    <span class="info-value"><?php echo date_i18n('F Y', strtotime($date_realisation)); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($lieu) : ?>
                                <div class="info-bar-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <span class="info-label"><?php _e('Lieu', 'almetal'); ?></span>
                                    <span class="info-value"><?php echo almetal_city_link_html($lieu, 'info-value--link'); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($duree) : ?>
                                <div class="info-bar-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"/>
                                        <polyline points="12 6 12 12 16 14"/>
                                    </svg>
                                    <span class="info-label"><?php _e('Durée', 'almetal'); ?></span>
                                    <span class="info-value"><?php echo esc_html($duree); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($matiere_label) : ?>
                                <div class="info-bar-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                    </svg>
                                    <span class="info-label"><?php _e('Matière', 'almetal'); ?></span>
                                    <span class="info-value"><?php echo esc_html($matiere_label); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($peinture) : ?>
                                <div class="info-bar-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2z"/>
                                        <path d="M12 8v8"/>
                                        <path d="M8 12h8"/>
                                    </svg>
                                    <span class="info-label"><?php _e('Finition', 'almetal'); ?></span>
                                    <span class="info-value"><?php echo esc_html($peinture); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($pose === '1') : ?>
                                <div class="info-bar-item info-bar-item--highlight">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                        <polyline points="22 4 12 14.01 9 11.01"/>
                                    </svg>
                                    <span class="info-label"><?php _e('Pose', 'almetal'); ?></span>
                                    <span class="info-value"><?php _e('Incluse', 'almetal'); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($client_type === 'professionnel' && $client_nom) : ?>
                                <div class="info-bar-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                        <polyline points="9 22 9 12 15 12 15 22"/>
                                    </svg>
                                    <span class="info-label"><?php _e('Client', 'almetal'); ?></span>
                                    <?php if ($client_url) : ?>
                                        <a href="<?php echo esc_url($client_url); ?>" target="_blank" rel="noopener noreferrer" class="info-value info-value--link">
                                            <?php echo esc_html($client_nom); ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-left: 4px;">
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                                <polyline points="15 3 21 3 21 9"/>
                                                <line x1="10" y1="14" x2="21" y2="3"/>
                                            </svg>
                                        </a>
                                    <?php else : ?>
                                        <span class="info-value"><?php echo esc_html($client_nom); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php elseif ($client) : ?>
                                <div class="info-bar-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                    <span class="info-label"><?php _e('Client', 'almetal'); ?></span>
                                    <span class="info-value"><?php echo esc_html($client); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- CONTENU PRINCIPAL -->
            <div class="realisation-main-section">
                <div class="container">
                    <div class="realisation-content-v2">
                        <?php 
                        // Afficher la description SEO générée si elle existe
                        $seo_description = get_post_meta(get_the_ID(), '_almetal_seo_description', true);
                        if (!empty($seo_description)) {
                            // Autoriser les balises HTML SEO
                            $allowed_html = array(
                                'h2' => array(),
                                'h3' => array(),
                                'p' => array(),
                                'strong' => array(),
                                'em' => array(),
                                'br' => array(),
                                'ul' => array(),
                                'li' => array(),
                                'a' => array(
                                    'href' => array(),
                                    'target' => array(),
                                    'rel' => array(),
                                ),
                            );
                            echo wp_kses($seo_description, $allowed_html);
                        } else {
                            // Fallback : afficher le contenu WordPress classique
                            the_content();
                        }
                        ?>
                    </div>

                        <?php
                        // Galerie d'images avec carrousel
                        $gallery_images = get_post_meta(get_the_ID(), '_almetal_gallery_images', true);
                        
                        // Si pas de galerie personnalisée, récupérer les images attachées
                        if (empty($gallery_images)) {
                            $attachments = get_posts(array(
                                'post_type' => 'attachment',
                                'posts_per_page' => -1,
                                'post_parent' => get_the_ID(),
                                'post_mime_type' => 'image',
                                'orderby' => 'menu_order',
                                'order' => 'ASC',
                            ));
                            
                            if ($attachments) {
                                $gallery_images = array();
                                foreach ($attachments as $attachment) {
                                    $gallery_images[] = $attachment->ID;
                                }
                            }
                        }
                        
                        if (!empty($gallery_images) && is_array($gallery_images)) :
                            ?>
                            <div class="realisation-gallery">
                                <h3><?php _e('Galerie photos', 'almetal'); ?></h3>
                                
                                <!-- Carrousel principal -->
                                <div class="gallery-carousel" data-transition="fade">
                                    <div class="gallery-main">
                                        <?php foreach ($gallery_images as $index => $image_id) : 
                                            $image_url = wp_get_attachment_image_url($image_id, 'full');
                                            $image_large_url = wp_get_attachment_image_url($image_id, 'large');
                                            $image_title = get_the_title($image_id);
                                            $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                                            $is_first = ($index === 0);
                                        ?>
                                            <div class="gallery-slide <?php echo $is_first ? 'active' : ''; ?>" 
                                                 data-index="<?php echo $index; ?>"
                                                 data-full-url="<?php echo esc_url($image_url); ?>"
                                                 data-title="<?php echo esc_attr($image_title); ?>">
                                                <?php if ($is_first) : ?>
                                                    <img src="<?php echo esc_url($image_large_url); ?>" 
                                                         alt="<?php echo esc_attr($image_alt ?: $image_title); ?>" 
                                                         class="gallery-image"
                                                         data-full="<?php echo esc_url($image_url); ?>">
                                                <?php else : ?>
                                                    <img src="<?php echo esc_url($image_large_url); ?>" 
                                                         alt="<?php echo esc_attr($image_alt ?: $image_title); ?>" 
                                                         class="gallery-image"
                                                         loading="lazy"
                                                         data-full="<?php echo esc_url($image_url); ?>">
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <!-- Contrôles -->
                                    <button class="gallery-prev" aria-label="<?php _e('Image précédente', 'almetal'); ?>">‹</button>
                                    <button class="gallery-next" aria-label="<?php _e('Image suivante', 'almetal'); ?>">›</button>
                                    
                                    <!-- Barre d'outils -->
                                    <div class="gallery-toolbar">
                                        <button class="gallery-fullscreen" aria-label="<?php _e('Plein écran', 'almetal'); ?>" title="<?php _e('Plein écran', 'almetal'); ?>">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/>
                                            </svg>
                                        </button>
                                        <button class="gallery-download" aria-label="<?php _e('Télécharger', 'almetal'); ?>" title="<?php _e('Télécharger l\'image', 'almetal'); ?>">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/>
                                            </svg>
                                        </button>
                                        <div class="gallery-share">
                                            <button class="gallery-share-btn" aria-label="<?php _e('Partager', 'almetal'); ?>" title="<?php _e('Partager', 'almetal'); ?>">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/>
                                                    <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                                                </svg>
                                            </button>
                                            <div class="gallery-share-menu">
                                                <a href="#" class="share-facebook" data-network="facebook" title="Facebook">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                                    </svg>
                                                </a>
                                                <a href="#" class="share-twitter" data-network="twitter" title="Twitter">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                                    </svg>
                                                </a>
                                                <a href="#" class="share-pinterest" data-network="pinterest" title="Pinterest">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z"/>
                                                    </svg>
                                                </a>
                                                <a href="#" class="share-whatsapp" data-network="whatsapp" title="WhatsApp">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                        <select class="gallery-transition-select" aria-label="<?php _e('Effet de transition', 'almetal'); ?>">
                                            <option value="fade"><?php _e('Fondu', 'almetal'); ?></option>
                                            <option value="slide"><?php _e('Glissement', 'almetal'); ?></option>
                                            <option value="zoom"><?php _e('Zoom', 'almetal'); ?></option>
                                        </select>
                                    </div>
                                    
                                    <!-- Compteur -->
                                    <div class="gallery-counter">
                                        <span class="current-slide">1</span> / <span class="total-slides"><?php echo count($gallery_images); ?></span>
                                    </div>
                                </div>
                                
                                <!-- Miniatures -->
                                <div class="gallery-thumbnails">
                                    <?php foreach ($gallery_images as $index => $image_id) : ?>
                                        <div class="gallery-thumb <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                                            <?php echo wp_get_attachment_image($image_id, 'thumbnail', false, array('loading' => 'lazy')); ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <!-- Lightbox -->
                            <div class="gallery-lightbox" style="display: none;">
                                <div class="lightbox-overlay"></div>
                                <div class="lightbox-content">
                                    <button class="lightbox-close" aria-label="<?php _e('Fermer', 'almetal'); ?>">×</button>
                                    <button class="lightbox-prev" aria-label="<?php _e('Image précédente', 'almetal'); ?>">‹</button>
                                    <button class="lightbox-next" aria-label="<?php _e('Image suivante', 'almetal'); ?>">›</button>
                                    <div class="lightbox-image-container">
                                        <img src="" alt="" class="lightbox-image">
                                    </div>
                                    <div class="lightbox-caption"></div>
                                </div>
                            </div>
                            <?php
                        endif;
                        ?>
                </div>
            </div>

            <!-- SECTION POURQUOI NOUS CHOISIR + CTA -->
            <div class="realisation-bottom-section">
                <div class="container">
                    <div class="bottom-grid">
                        <!-- Bloc "Pourquoi choisir AL Métallerie" -->
                        <div class="why-choose-block">
                            <h2><?php _e('Pourquoi choisir AL Métallerie ?', 'almetal'); ?></h2>
                            
                            <div class="why-choose-grid">
                                <div class="why-choose-item">
                                    <div class="why-choose-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                            <circle cx="12" cy="10" r="3"/>
                                        </svg>
                                    </div>
                                    <div class="why-choose-text">
                                        <strong><?php _e('Expertise locale', 'almetal'); ?></strong>
                                        <span><?php _e('Basés à Peschadoires, nous intervenons dans tout le Puy-de-Dôme', 'almetal'); ?></span>
                                    </div>
                                </div>
                                
                                <div class="why-choose-item">
                                    <div class="why-choose-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                                        </svg>
                                    </div>
                                    <div class="why-choose-text">
                                        <strong><?php _e('Savoir-faire artisanal', 'almetal'); ?></strong>
                                        <span><?php _e('Plus de 20 ans d\'expérience en métallerie', 'almetal'); ?></span>
                                    </div>
                                </div>
                                
                                <div class="why-choose-item">
                                    <div class="why-choose-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                            <polyline points="22 4 12 14.01 9 11.01"/>
                                        </svg>
                                    </div>
                                    <div class="why-choose-text">
                                        <strong><?php _e('Qualité garantie', 'almetal'); ?></strong>
                                        <span><?php _e('Matériaux premium et finitions soignées', 'almetal'); ?></span>
                                    </div>
                                </div>
                                
                                <div class="why-choose-item">
                                    <div class="why-choose-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                                            <path d="M2 17l10 5 10-5M2 12l10 5 10-5"/>
                                        </svg>
                                    </div>
                                    <div class="why-choose-text">
                                        <strong><?php _e('Sur-mesure', 'almetal'); ?></strong>
                                        <span><?php _e('Chaque projet est unique et adapté à vos besoins', 'almetal'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- CTA -->
                        <div class="cta-block">
                            <h3><?php _e('Un projet similaire ?', 'almetal'); ?></h3>
                            <p><?php _e('Contactez-nous pour discuter de votre projet et obtenir un devis gratuit.', 'almetal'); ?></p>
                            <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn-cta-large">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                                </svg>
                                <?php _e('Demander un devis', 'almetal'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
                
            <?php
                // Navigation entre réalisations
                $prev_post = get_previous_post();
                $next_post = get_next_post();

                if ($prev_post || $next_post) :
                    ?>
                    <nav class="realisation-navigation-cards">
                        <?php if ($prev_post) : ?>
                            <a href="<?php echo get_permalink($prev_post); ?>" class="nav-card nav-card-prev">
                                <span class="nav-arrow">←</span>
                                <span class="nav-title"><?php echo get_the_title($prev_post); ?></span>
                            </a>
                        <?php endif; ?>

                        <?php if ($next_post) : ?>
                            <a href="<?php echo get_permalink($next_post); ?>" class="nav-card nav-card-next">
                                <span class="nav-title"><?php echo get_the_title($next_post); ?></span>
                                <span class="nav-arrow">→</span>
                            </a>
                        <?php endif; ?>
                    </nav>
                    <?php
                endif;
                ?>
            </div>
        </article>

        <?php
    endwhile;
    ?>
</div>

<?php
get_footer();
