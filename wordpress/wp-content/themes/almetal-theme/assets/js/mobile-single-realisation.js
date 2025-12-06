/**
 * Script pour le slideshow des réalisations individuelles - VERSION MOBILE
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialiser le slideshow Swiper pour les réalisations
    const realisationSwiper = document.querySelector('.mobile-realisation-swiper');
    
    if (realisationSwiper && typeof Swiper !== 'undefined') {
        new Swiper('.mobile-realisation-swiper', {
            // Configuration du slideshow
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            speed: 600,
            effect: 'slide',
            
            // Navigation
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            
            // Pagination
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                dynamicBullets: true,
            },
            
            // Gestes tactiles
            touchRatio: 1,
            touchAngle: 45,
            grabCursor: true,
            
            // Lazy loading
            lazy: {
                loadPrevNext: true,
                loadPrevNextAmount: 2,
            },
            
            // Accessibilité
            a11y: {
                enabled: true,
                prevSlideMessage: 'Image précédente',
                nextSlideMessage: 'Image suivante',
                firstSlideMessage: 'Première image',
                lastSlideMessage: 'Dernière image',
            },
        });
    }
});
