<?php
/**
 * Template pour afficher une page ville
 *
 * Ce template peut être copié dans votre thème :
 * votre-theme/single-city_page.php
 *
 * @package CityPagesGenerator
 */

get_header();

while (have_posts()) :
    the_post();
    
    $post_id = get_the_ID();
    $city_name = get_post_meta($post_id, '_cpg_city_name', true);
    $postal_code = get_post_meta($post_id, '_cpg_postal_code', true);
    $department = get_post_meta($post_id, '_cpg_department', true);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('cpg-city-page'); ?>>
    
    <!-- Hero Section -->
    <header class="cpg-hero">
        <?php if (has_post_thumbnail()) : ?>
            <div class="cpg-hero-image">
                <?php the_post_thumbnail('full'); ?>
                <div class="cpg-hero-overlay"></div>
            </div>
        <?php else : ?>
            <div class="cpg-hero-bg"></div>
        <?php endif; ?>
        
        <div class="cpg-hero-content">
            <div class="cpg-container">
                <!-- Breadcrumb -->
                <?php echo do_shortcode('[cpg_breadcrumb]'); ?>
                
                <h1 class="cpg-hero-title">
                    <?php printf(__('Métallier Serrurier à %s', 'city-pages-generator'), esc_html($city_name)); ?>
                    <span class="cpg-hero-subtitle"><?php printf(__('Intervention %s', 'city-pages-generator'), esc_html($department)); ?></span>
                </h1>
                
                <div class="cpg-hero-meta">
                    <span class="cpg-meta-item">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        <?php echo esc_html($city_name); ?> (<?php echo esc_html($postal_code); ?>)
                    </span>
                    <span class="cpg-meta-item">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        06 73 33 35 32
                    </span>
                </div>
                
                <a href="#contact" class="cpg-hero-cta">
                    <?php _e('Demander un devis gratuit', 'city-pages-generator'); ?>
                </a>
            </div>
        </div>
    </header>
    
    <!-- Content -->
    <div class="cpg-main-content">
        <div class="cpg-container">
            <?php the_content(); ?>
        </div>
    </div>
    
</article>

<?php
endwhile;

get_footer();
?>

<style>
/* Hero Styles */
.cpg-hero {
    position: relative;
    min-height: 500px;
    display: flex;
    align-items: flex-end;
    background: #191919;
}

.cpg-hero-image,
.cpg-hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
}

.cpg-hero-bg {
    background: linear-gradient(135deg, #222 0%, #191919 100%);
}

.cpg-hero-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cpg-hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to top, rgba(25, 25, 25, 0.95) 0%, rgba(25, 25, 25, 0.6) 50%, rgba(25, 25, 25, 0.4) 100%);
}

.cpg-hero-content {
    position: relative;
    z-index: 2;
    width: 100%;
    padding: 80px 0;
}

.cpg-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.cpg-hero-title {
    font-family: 'Poppins', sans-serif;
    font-size: 3rem;
    font-weight: 700;
    color: #FDFDFD;
    margin: 30px 0 20px;
    line-height: 1.2;
}

.cpg-hero-subtitle {
    display: block;
    font-size: 1.5rem;
    font-weight: 400;
    color: #F08B18;
    margin-top: 10px;
}

.cpg-hero-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 25px;
    margin-bottom: 30px;
}

.cpg-meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: rgba(255, 255, 255, 0.8);
    font-size: 15px;
}

.cpg-meta-item svg {
    color: #F08B18;
}

.cpg-hero-cta {
    display: inline-block;
    padding: 16px 35px;
    background: linear-gradient(135deg, #F08B18, #d67610);
    color: white !important;
    text-decoration: none;
    border-radius: 30px;
    font-weight: 600;
    font-size: 15px;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
}

.cpg-hero-cta:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(240, 139, 24, 0.4);
}

.cpg-main-content {
    background: #191919;
    padding: 60px 0;
}

@media (max-width: 768px) {
    .cpg-hero {
        min-height: 400px;
    }
    
    .cpg-hero-title {
        font-size: 2rem;
    }
    
    .cpg-hero-subtitle {
        font-size: 1.2rem;
    }
    
    .cpg-hero-content {
        padding: 50px 0;
    }
}
</style>
