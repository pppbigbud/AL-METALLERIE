/**
 * Carrousel de galerie avancé pour les réalisations
 * Avec lightbox, swipe, lazy loading, transitions, téléchargement et partage
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Carrousel de galerie avancé
     */
    function initAdvancedGalleryCarousel() {
        const gallery = $('.gallery-carousel');
        
        if (!gallery.length) {
            return;
        }

        const slides = gallery.find('.gallery-slide');
        const thumbs = $('.gallery-thumb');
        const prevBtn = gallery.find('.gallery-prev');
        const nextBtn = gallery.find('.gallery-next');
        const currentSlideSpan = gallery.find('.current-slide');
        const lightbox = $('.gallery-lightbox');
        const fullscreenBtn = $('.gallery-fullscreen');
        const downloadBtn = $('.gallery-download');
        const shareBtn = $('.gallery-share-btn');
        const shareMenu = $('.gallery-share-menu');
        const transitionSelect = $('.gallery-transition-select');
        
        let currentSlide = 0;
        let slideInterval;
        const slideDelay = 4000;
        let touchStartX = 0;
        let touchEndX = 0;

        /**
         * Aller à un slide spécifique
         */
        function goToSlide(index, direction = 'next') {
            const transition = gallery.data('transition') || 'fade';
            
            // Retirer les classes actives
            slides.removeClass('active prev');
            thumbs.removeClass('active');
            
            // Ajouter la classe prev pour la transition slide
            if (transition === 'slide' && direction === 'prev') {
                $(slides[currentSlide]).addClass('prev');
            }
            
            currentSlide = index;
            
            // Activer le nouveau slide
            $(slides[currentSlide]).addClass('active');
            $(thumbs[currentSlide]).addClass('active');
            
            // Mettre à jour le compteur
            currentSlideSpan.text(currentSlide + 1);
            
            // Lazy load de l'image suivante
            lazyLoadNextImage();
            
            resetInterval();
        }

        /**
         * Lazy loading de l'image suivante
         */
        function lazyLoadNextImage() {
            const nextIndex = (currentSlide + 1) % slides.length;
            const nextSlide = $(slides[nextIndex]);
            const img = nextSlide.find('img');
            
            if (img.attr('data-src') && !img.attr('src')) {
                img.attr('src', img.attr('data-src'));
                img.removeAttr('data-src');
            }
        }

        /**
         * Slide suivant
         */
        function nextSlide() {
            let next = currentSlide + 1;
            if (next >= slides.length) {
                next = 0;
            }
            goToSlide(next, 'next');
        }

        /**
         * Slide précédent
         */
        function prevSlide() {
            let prev = currentSlide - 1;
            if (prev < 0) {
                prev = slides.length - 1;
            }
            goToSlide(prev, 'prev');
        }

        /**
         * Démarrer l'intervalle
         */
        function startInterval() {
            slideInterval = setInterval(nextSlide, slideDelay);
        }

        /**
         * Réinitialiser l'intervalle
         */
        function resetInterval() {
            clearInterval(slideInterval);
            startInterval();
        }

        /**
         * Ouvrir la lightbox
         */
        function openLightbox(index) {
            const slide = $(slides[index]);
            const imageUrl = slide.data('full-url');
            const imageTitle = slide.data('title');
            
            lightbox.find('.lightbox-image').attr('src', imageUrl);
            lightbox.find('.lightbox-caption').text(imageTitle);
            lightbox.fadeIn(300);
            $('body').css('overflow', 'hidden');
            
            // Arrêter le carrousel
            clearInterval(slideInterval);
        }

        /**
         * Fermer la lightbox
         */
        function closeLightbox() {
            lightbox.fadeOut(300);
            $('body').css('overflow', '');
            
            // Redémarrer le carrousel
            startInterval();
        }

        /**
         * Télécharger l'image
         */
        function downloadImage() {
            const slide = $(slides[currentSlide]);
            const imageUrl = slide.data('full-url');
            const imageName = 'realisation-' + (currentSlide + 1) + '.jpg';
            
            // Créer un lien temporaire pour le téléchargement
            const link = document.createElement('a');
            link.href = imageUrl;
            link.download = imageName;
            link.target = '_blank';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        /**
         * Partager sur les réseaux sociaux
         */
        function shareImage(network) {
            const slide = $(slides[currentSlide]);
            const imageUrl = slide.data('full-url');
            const pageUrl = window.location.href;
            const pageTitle = document.title;
            
            let shareUrl = '';
            
            switch(network) {
                case 'facebook':
                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(pageUrl)}`;
                    break;
                case 'twitter':
                    shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(pageUrl)}&text=${encodeURIComponent(pageTitle)}`;
                    break;
                case 'pinterest':
                    shareUrl = `https://pinterest.com/pin/create/button/?url=${encodeURIComponent(pageUrl)}&media=${encodeURIComponent(imageUrl)}&description=${encodeURIComponent(pageTitle)}`;
                    break;
                case 'whatsapp':
                    shareUrl = `https://wa.me/?text=${encodeURIComponent(pageTitle + ' ' + pageUrl)}`;
                    break;
            }
            
            if (shareUrl) {
                window.open(shareUrl, '_blank', 'width=600,height=400');
            }
        }

        /**
         * Changer la transition
         */
        function changeTransition(transition) {
            gallery.attr('data-transition', transition);
            
            // Réinitialiser les classes
            slides.removeClass('active prev');
            $(slides[currentSlide]).addClass('active');
        }

        /**
         * Gestion du swipe sur mobile
         */
        function handleTouchStart(e) {
            touchStartX = e.touches[0].clientX;
        }

        function handleTouchMove(e) {
            touchEndX = e.touches[0].clientX;
        }

        function handleTouchEnd() {
            const swipeThreshold = 50;
            const diff = touchStartX - touchEndX;
            
            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    // Swipe vers la gauche - image suivante
                    nextSlide();
                } else {
                    // Swipe vers la droite - image précédente
                    prevSlide();
                }
            }
        }

        // Event listeners pour les boutons
        nextBtn.on('click', function() {
            nextSlide();
        });

        prevBtn.on('click', function() {
            prevSlide();
        });

        // Event listeners pour les miniatures
        thumbs.on('click', function() {
            const index = parseInt($(this).data('index'));
            goToSlide(index);
        });

        // Event listener pour ouvrir la lightbox
        slides.on('click', function() {
            const index = parseInt($(this).data('index'));
            openLightbox(index);
        });

        // Event listeners pour la lightbox
        lightbox.find('.lightbox-close, .lightbox-overlay').on('click', function() {
            closeLightbox();
        });

        lightbox.find('.lightbox-prev').on('click', function(e) {
            e.stopPropagation();
            prevSlide();
            openLightbox(currentSlide);
        });

        lightbox.find('.lightbox-next').on('click', function(e) {
            e.stopPropagation();
            nextSlide();
            openLightbox(currentSlide);
        });

        // Event listener pour le plein écran
        fullscreenBtn.on('click', function() {
            openLightbox(currentSlide);
        });

        // Event listener pour le téléchargement
        downloadBtn.on('click', function() {
            downloadImage();
        });

        // Event listener pour le partage
        shareBtn.on('click', function(e) {
            e.stopPropagation();
            shareMenu.toggleClass('active');
        });

        // Fermer le menu de partage en cliquant ailleurs
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.gallery-share').length) {
                shareMenu.removeClass('active');
            }
        });

        // Event listeners pour les liens de partage
        $('.gallery-share-menu a').on('click', function(e) {
            e.preventDefault();
            const network = $(this).data('network');
            shareImage(network);
            shareMenu.removeClass('active');
        });

        // Event listener pour le changement de transition
        transitionSelect.on('change', function() {
            const transition = $(this).val();
            changeTransition(transition);
        });

        // Pause au survol
        gallery.on('mouseenter', function() {
            clearInterval(slideInterval);
        });

        gallery.on('mouseleave', function() {
            startInterval();
        });

        // Support du clavier
        $(document).on('keydown', function(e) {
            // Dans le carrousel
            if (gallery.is(':visible') && !lightbox.is(':visible')) {
                if (e.key === 'ArrowLeft') {
                    prevSlide();
                } else if (e.key === 'ArrowRight') {
                    nextSlide();
                } else if (e.key === 'Enter' || e.key === ' ') {
                    openLightbox(currentSlide);
                }
            }
            
            // Dans la lightbox
            if (lightbox.is(':visible')) {
                if (e.key === 'ArrowLeft') {
                    prevSlide();
                    openLightbox(currentSlide);
                } else if (e.key === 'ArrowRight') {
                    nextSlide();
                    openLightbox(currentSlide);
                } else if (e.key === 'Escape') {
                    closeLightbox();
                }
            }
        });

        // Support du swipe sur mobile
        const galleryMain = gallery.find('.gallery-main')[0];
        if (galleryMain) {
            galleryMain.addEventListener('touchstart', handleTouchStart, false);
            galleryMain.addEventListener('touchmove', handleTouchMove, false);
            galleryMain.addEventListener('touchend', handleTouchEnd, false);
        }

        // Support du swipe dans la lightbox
        const lightboxContainer = lightbox.find('.lightbox-image-container')[0];
        if (lightboxContainer) {
            lightboxContainer.addEventListener('touchstart', handleTouchStart, false);
            lightboxContainer.addEventListener('touchmove', handleTouchMove, false);
            lightboxContainer.addEventListener('touchend', function() {
                handleTouchEnd();
                openLightbox(currentSlide);
            }, false);
        }

        // Initialiser le premier slide
        goToSlide(0);
        
        // Lazy load initial
        slides.each(function(index) {
            if (index > 1) { // Charger seulement les 2 premières images
                const img = $(this).find('img');
                const src = img.attr('src');
                img.attr('data-src', src);
                img.removeAttr('src');
            }
        });
    }

    /**
     * Initialisation au chargement du DOM
     */
    $(document).ready(function() {
        initAdvancedGalleryCarousel();
    });

})(jQuery);
