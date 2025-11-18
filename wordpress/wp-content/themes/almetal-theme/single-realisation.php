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

<div class="single-realisation">
    <?php
    while (have_posts()) :
        the_post();
        ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php if (has_post_thumbnail()) : ?>
                <div class="realisation-hero">
                    <?php the_post_thumbnail('full'); ?>
                </div>
            <?php endif; ?>
            
            <?php
            // Afficher le fil d'Ariane SEO (sous la photo)
            almetal_seo_breadcrumb();
            ?>

            <div class="container">
                <div class="realisation-header">
                    <h1 class="realisation-title"><?php the_title(); ?></h1>

                    <?php
                    $types = get_the_terms(get_the_ID(), 'type_realisation');
                    if ($types && !is_wp_error($types)) :
                        ?>
                        <div class="realisation-types">
                            <?php foreach ($types as $type) : ?>
                                <span class="type-badge"><?php echo esc_html($type->name); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php
                    endif;
                    ?>
                </div>

                <div class="realisation-details-grid">
                    <div class="realisation-main-content">
                        <div class="realisation-content">
                            <?php the_content(); ?>
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
                                            $image_title = get_the_title($image_id);
                                            $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                                        ?>
                                            <div class="gallery-slide <?php echo $index === 0 ? 'active' : ''; ?>" 
                                                 data-index="<?php echo $index; ?>"
                                                 data-full-url="<?php echo esc_url($image_url); ?>"
                                                 data-title="<?php echo esc_attr($image_title); ?>">
                                                <?php echo wp_get_attachment_image($image_id, 'large', false, array(
                                                    'class' => 'gallery-image',
                                                    'loading' => 'lazy',
                                                    'data-full' => esc_url($image_url)
                                                )); ?>
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

                    <aside class="realisation-sidebar">
                        <div class="realisation-info-box">
                            <h3><?php _e('Informations', 'almetal'); ?></h3>
                            
                            <?php
                            $client = get_post_meta(get_the_ID(), '_almetal_client', true);
                            $date_realisation = get_post_meta(get_the_ID(), '_almetal_date_realisation', true);
                            $lieu = get_post_meta(get_the_ID(), '_almetal_lieu', true);
                            $duree = get_post_meta(get_the_ID(), '_almetal_duree', true);
                            ?>

                            <dl class="realisation-details">
                                <?php if ($date_realisation) : ?>
                                    <dt><?php _e('Date de réalisation', 'almetal'); ?></dt>
                                    <dd><?php echo date_i18n(get_option('date_format'), strtotime($date_realisation)); ?></dd>
                                <?php endif; ?>

                                <?php if ($lieu) : ?>
                                    <dt><?php _e('Lieu', 'almetal'); ?></dt>
                                    <dd><?php echo esc_html($lieu); ?></dd>
                                <?php endif; ?>

                                <?php if ($duree) : ?>
                                    <dt><?php _e('Durée du projet', 'almetal'); ?></dt>
                                    <dd><?php echo esc_html($duree); ?></dd>
                                <?php endif; ?>

                                <?php if ($client) : ?>
                                    <dt><?php _e('Client', 'almetal'); ?></dt>
                                    <dd><?php echo esc_html($client); ?></dd>
                                <?php endif; ?>

                                <?php if ($types && !is_wp_error($types)) : ?>
                                    <dt><?php _e('Type de projet', 'almetal'); ?></dt>
                                    <dd>
                                        <?php
                                        $type_names = array();
                                        foreach ($types as $type) {
                                            $type_names[] = $type->name;
                                        }
                                        echo implode(', ', $type_names);
                                        ?>
                                    </dd>
                                <?php endif; ?>
                            </dl>

                            <div class="realisation-cta">
                                <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-primary">
                                    <?php _e('Un projet similaire ?', 'almetal'); ?>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Bloc "Pourquoi choisir AL Métallerie" -->
                        <div class="realisation-info-box realisation-why-choose">
                            <h3><?php _e('Pourquoi choisir AL Métallerie ?', 'almetal'); ?></h3>
                            
                            <ul class="why-choose-list">
                                <li>
                                    <strong><?php _e('Expertise locale', 'almetal'); ?></strong>
                                    <span><?php _e('Basés à Peschadoires, nous intervenons dans tout le Puy-de-Dôme', 'almetal'); ?></span>
                                </li>
                                <li>
                                    <strong><?php _e('Savoir-faire artisanal', 'almetal'); ?></strong>
                                    <span><?php _e('Plus de 20 ans d\'expérience en métallerie', 'almetal'); ?></span>
                                </li>
                                <li>
                                    <strong><?php _e('Qualité garantie', 'almetal'); ?></strong>
                                    <span><?php _e('Matériaux premium et finitions soignées', 'almetal'); ?></span>
                                </li>
                                <li>
                                    <strong><?php _e('Sur-mesure', 'almetal'); ?></strong>
                                    <span><?php _e('Chaque projet est unique et adapté à vos besoins', 'almetal'); ?></span>
                                </li>
                            </ul>
                            
                            <div class="realisation-cta">
                                <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-primary">
                                    <?php _e('Demander un devis gratuit', 'almetal'); ?>
                                </a>
                            </div>
                        </div>
                    </aside>
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
