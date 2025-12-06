<?php
/**
 * Template Part pour Single Réalisation - VERSION MOBILE
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

$post_id = get_the_ID();
$types = get_the_terms($post_id, 'type_realisation');

// Récupérer les images de la galerie
$gallery_images = get_post_meta($post_id, '_almetal_gallery_images', true);

// Si pas de galerie personnalisée, récupérer les images attachées
if (empty($gallery_images)) {
    $attachments = get_posts(array(
        'post_type' => 'attachment',
        'posts_per_page' => -1,
        'post_parent' => $post_id,
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

// Ajouter l'image à la une au début si elle existe
if (has_post_thumbnail() && !empty($gallery_images)) {
    array_unshift($gallery_images, get_post_thumbnail_id());
} elseif (has_post_thumbnail()) {
    $gallery_images = array(get_post_thumbnail_id());
}

// Informations du projet
$client = get_post_meta($post_id, '_almetal_client', true);
$date_realisation = get_post_meta($post_id, '_almetal_date_realisation', true);
$lieu = get_post_meta($post_id, '_almetal_lieu', true);
$duree = get_post_meta($post_id, '_almetal_duree', true);

// Nouveaux champs
$client_type = get_post_meta($post_id, '_almetal_client_type', true);
$client_nom = get_post_meta($post_id, '_almetal_client_nom', true);
$client_url = get_post_meta($post_id, '_almetal_client_url', true);
$matiere = get_post_meta($post_id, '_almetal_matiere', true);
$peinture = get_post_meta($post_id, '_almetal_peinture', true);
$pose = get_post_meta($post_id, '_almetal_pose', true);

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
?>

<article class="mobile-single-realisation">
    <div class="mobile-single-container">
        
        <!-- Tag -->
        <?php if ($types && !is_wp_error($types)) : ?>
            <div class="mobile-single-tags scroll-zoom">
                <?php foreach ($types as $type) : ?>
                    <span class="mobile-single-tag"><?php echo esc_html($type->name); ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Titre -->
        <h1 class="mobile-single-title scroll-fade scroll-delay-1">
            <?php the_title(); ?>
        </h1>

        <!-- Slideshow Swiper -->
        <?php if (!empty($gallery_images) && is_array($gallery_images)) : ?>
            <div class="mobile-single-slideshow scroll-fade scroll-delay-2">
                <div class="swiper mobile-realisation-swiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($gallery_images as $image_id) : 
                            $image_url = wp_get_attachment_image_url($image_id, 'large');
                            $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                        ?>
                            <div class="swiper-slide">
                                <img src="<?php echo esc_url($image_url); ?>" 
                                     alt="<?php echo esc_attr($image_alt ? $image_alt : get_the_title()); ?>"
                                     loading="lazy">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Navigation -->
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                    
                    <!-- Pagination -->
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Informations du projet -->
        <div class="mobile-single-info-grid">
            <?php if ($date_realisation) : ?>
                <div class="mobile-single-info-card">
                    <div class="mobile-single-info-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <div class="mobile-single-info-content">
                        <h3><?php esc_html_e('Date', 'almetal'); ?></h3>
                        <p><?php echo date_i18n(get_option('date_format'), strtotime($date_realisation)); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($lieu) : ?>
                <div class="mobile-single-info-card">
                    <div class="mobile-single-info-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                    </div>
                    <div class="mobile-single-info-content">
                        <h3><?php esc_html_e('Lieu', 'almetal'); ?></h3>
                        <p><?php echo esc_html($lieu); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($duree) : ?>
                <div class="mobile-single-info-card">
                    <div class="mobile-single-info-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <div class="mobile-single-info-content">
                        <h3><?php esc_html_e('Durée', 'almetal'); ?></h3>
                        <p><?php echo esc_html($duree); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($matiere_label) : ?>
                <div class="mobile-single-info-card">
                    <div class="mobile-single-info-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                        </svg>
                    </div>
                    <div class="mobile-single-info-content">
                        <h3><?php esc_html_e('Matière', 'almetal'); ?></h3>
                        <p><?php echo esc_html($matiere_label); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($peinture) : ?>
                <div class="mobile-single-info-card">
                    <div class="mobile-single-info-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2z"></path>
                            <path d="M12 8v8"></path>
                            <path d="M8 12h8"></path>
                        </svg>
                    </div>
                    <div class="mobile-single-info-content">
                        <h3><?php esc_html_e('Finition', 'almetal'); ?></h3>
                        <p><?php echo esc_html($peinture); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($pose === '1') : ?>
                <div class="mobile-single-info-card mobile-single-info-card--highlight">
                    <div class="mobile-single-info-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <div class="mobile-single-info-content">
                        <h3><?php esc_html_e('Pose', 'almetal'); ?></h3>
                        <p><?php esc_html_e('Incluse', 'almetal'); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($client_type === 'professionnel' && $client_nom) : ?>
                <div class="mobile-single-info-card">
                    <div class="mobile-single-info-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    </div>
                    <div class="mobile-single-info-content">
                        <h3><?php esc_html_e('Client', 'almetal'); ?></h3>
                        <?php if ($client_url) : ?>
                            <p><a href="<?php echo esc_url($client_url); ?>" target="_blank" rel="noopener noreferrer" class="mobile-info-link"><?php echo esc_html($client_nom); ?></a></p>
                        <?php else : ?>
                            <p><?php echo esc_html($client_nom); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php elseif ($client) : ?>
                <div class="mobile-single-info-card">
                    <div class="mobile-single-info-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <div class="mobile-single-info-content">
                        <h3><?php esc_html_e('Client', 'almetal'); ?></h3>
                        <p><?php echo esc_html($client); ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Description du projet -->
        <?php 
        $seo_description = get_post_meta($post_id, '_almetal_seo_description', true);
        if (!empty($seo_description) || get_the_content()) : 
        ?>
            <div class="mobile-single-description">
                <h2 class="mobile-single-section-title">
                    <?php esc_html_e('Description du projet', 'almetal'); ?>
                </h2>
                <div class="mobile-single-content">
                    <?php 
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
                        the_content();
                    }
                    ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- CTA Contact -->
        <div class="mobile-single-cta">
            <h3><?php esc_html_e('Un projet similaire ?', 'almetal'); ?></h3>
            <p><?php esc_html_e('Contactez-nous pour discuter de votre projet', 'almetal'); ?></p>
            <a href="<?php echo esc_url(home_url('/contact')); ?>" class="mobile-btn-cta mobile-btn-cta--large">
                <?php esc_html_e('Nous contacter', 'almetal'); ?>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </div>

        <!-- Navigation -->
        <?php
        $prev_post = get_previous_post();
        $next_post = get_next_post();

        if ($prev_post || $next_post) :
        ?>
            <nav class="mobile-single-navigation">
                <?php if ($prev_post) : ?>
                    <a href="<?php echo get_permalink($prev_post); ?>" class="mobile-single-nav-link mobile-single-nav-prev">
                        <span class="mobile-single-nav-label">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                            <?php esc_html_e('Précédent', 'almetal'); ?>
                        </span>
                        <span class="mobile-single-nav-title"><?php echo get_the_title($prev_post); ?></span>
                    </a>
                <?php endif; ?>

                <?php if ($next_post) : ?>
                    <a href="<?php echo get_permalink($next_post); ?>" class="mobile-single-nav-link mobile-single-nav-next">
                        <span class="mobile-single-nav-label">
                            <?php esc_html_e('Suivant', 'almetal'); ?>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                        <span class="mobile-single-nav-title"><?php echo get_the_title($next_post); ?></span>
                    </a>
                <?php endif; ?>
            </nav>
        <?php endif; ?>

        <!-- Bouton retour -->
        <div class="mobile-single-back">
            <a href="<?php echo get_post_type_archive_link('realisation'); ?>" class="mobile-single-back-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <?php esc_html_e('Toutes les réalisations', 'almetal'); ?>
            </a>
        </div>

    </div>
</article>
