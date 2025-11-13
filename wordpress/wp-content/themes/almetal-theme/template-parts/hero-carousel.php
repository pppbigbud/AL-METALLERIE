<?php
/**
 * Template part pour le carrousel hero
 * Version adaptative : Desktop (JS custom) / Mobile (Swiper.js)
 */

$is_mobile = almetal_is_mobile();
?>

<?php if ($is_mobile) : ?>
    <!-- Hero Carousel MOBILE (Swiper) -->
    <div class="mobile-hero-swiper swiper">
        <div class="swiper-wrapper">
            <!-- Slide 1 -->
            <div class="swiper-slide mobile-hero-slide">
                <div class="mobile-hero-image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/hero/hero-1.png');"></div>
                <div class="mobile-hero-overlay"></div>
                <div class="mobile-hero-content">
                    <h1 class="mobile-hero-title">Bienvenue chez AL Métallerie</h1>
                    <p class="mobile-hero-subtitle">Expert en métallerie à Clermont-Ferrand</p>
                    <a href="#contact" class="mobile-hero-cta">Demander un devis</a>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="swiper-slide mobile-hero-slide">
                <div class="mobile-hero-image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/hero/hero-2.png');"></div>
                <div class="mobile-hero-overlay"></div>
                <div class="mobile-hero-content">
                    <h1 class="mobile-hero-title">Créations sur mesure</h1>
                    <p class="mobile-hero-subtitle">Portails, garde-corps, escaliers</p>
                    <a href="#actualites" class="mobile-hero-cta">Découvrir nos réalisations</a>
                </div>
            </div>
            
            <!-- Slide 3 -->
            <div class="swiper-slide mobile-hero-slide">
                <div class="mobile-hero-image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/hero/hero-3.png');"></div>
                <div class="mobile-hero-overlay"></div>
                <div class="mobile-hero-content">
                    <h1 class="mobile-hero-title">Formations</h1>
                    <p class="mobile-hero-subtitle">Particulier, centre de formation, à la demande</p>
                    <a href="#formations" class="mobile-hero-cta">Découvrir nos formations</a>
                </div>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="swiper-pagination"></div>
    </div>

<?php else : ?>
    <!-- Hero Carousel DESKTOP (JS custom) -->
    <section id="hero" class="hero-carousel">
        <div class="hero-slides">
            <!-- Slide 1 -->
            <div class="hero-slide" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/hero/hero-1.png');">
                <div class="hero-content">
                    <h1 class="hero-title">Bienvenue chez AL Métallerie</h1>
                    <p class="hero-subtitle">Expert en métallerie à Clermont-Ferrand</p>
                    <a href="#contact" class="hero-cta">Demander un devis</a>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="hero-slide" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/hero/hero-2.png');">
                <div class="hero-content">
                    <h1 class="hero-title">Créations sur mesure</h1>
                    <p class="hero-subtitle">Portails, garde-corps, escaliers</p>
                    <a href="#services" class="hero-cta">Découvrir nos réalisations</a>
                </div>
            </div>
            
            <!-- Slide 3 -->
            <div class="hero-slide" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/hero/hero-3.png');">
                <div class="hero-content">
                    <h1 class="hero-title">Formations</h1>
                    <p class="hero-subtitle">Particulier, centre de formation, à la demande</p>
                    <a href="#services" class="hero-cta">Découvrir nos formations</a>
                </div>
            </div>
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
