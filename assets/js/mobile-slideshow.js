/**
 * Slideshow Touch Mobile - AL Metallerie Soudure
 * 
 * Utilise Swiper.js pour un carrousel tactile optimisé mobile
 * - Navigation par swipe
 * - Autoplay avec pause au touch
 * - Pagination avec bullets
 * - Lazy loading des images
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

(function() {
    'use strict';

    console.log('🎞️ Mobile Slideshow - Initialisation');

    // Attendre que Swiper soit chargé
    function initSwiper() {
        if (typeof Swiper === 'undefined') {
            console.warn('⚠️ Swiper.js non chargé, nouvelle tentative dans 500ms');
            setTimeout(initSwiper, 500);
            return;
        }

        // Vérifier que le container existe
        const swiperContainer = document.querySelector('.mobile-hero-swiper');
        if (!swiperContainer) {
            console.log('ℹ️ Pas de slideshow sur cette page');
            return;
        }

        console.log('✅ Swiper.js chargé, initialisation du slideshow');

        // Configuration Swiper
        const swiper = new Swiper('.mobile-hero-swiper', {
            // Paramètres de base
            loop: true,
            speed: 800,
            effect: 'slide',
            grabCursor: true,
            
            // Autoplay
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            
            // Pagination
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                dynamicBullets: true,
                dynamicMainBullets: 3,
            },
            
            // Navigation (optionnel)
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            
            // Lazy loading
            lazy: {
                loadPrevNext: true,
                loadPrevNextAmount: 2,
            },
            
            // Accessibilité
            a11y: {
                enabled: true,
                prevSlideMessage: 'Slide précédente',
                nextSlideMessage: 'Slide suivante',
                firstSlideMessage: 'Première slide',
                lastSlideMessage: 'Dernière slide',
                paginationBulletMessage: 'Aller à la slide {{index}}',
            },
            
            // Touch
            touchRatio: 1,
            touchAngle: 45,
            threshold: 5,
            
            // Événements
            on: {
                init: function() {
                    console.log('✅ Slideshow initialisé');
                    // Ajouter une classe au body pour indiquer que le slideshow est prêt
                    document.body.classList.add('slideshow-ready');
                },
                slideChange: function() {
                    console.log('📸 Slide changée:', this.activeIndex);
                },
                touchStart: function() {
                    // Pause autoplay au touch
                    this.autoplay.stop();
                },
                touchEnd: function() {
                    // Reprendre autoplay après le touch
                    setTimeout(() => {
                        this.autoplay.start();
                    }, 3000);
                },
            },
        });

        // Pause autoplay quand la page n'est pas visible
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                swiper.autoplay.stop();
                console.log('⏸️ Autoplay pausé (page cachée)');
            } else {
                swiper.autoplay.start();
                console.log('▶️ Autoplay repris (page visible)');
            }
        });

        // Exposer l'instance Swiper globalement (pour debug)
        window.mobileSlideshow = swiper;
    }

    // Démarrer l'initialisation
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSwiper);
    } else {
        initSwiper();
    }

})();
