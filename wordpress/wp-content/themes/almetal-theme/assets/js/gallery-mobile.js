/**
 * Carrousel Galerie Mobile - Version Simplifiée
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

(function() {
    'use strict';

    console.log('Gallery mobile script loaded');

    // Attendre que le DOM soit chargé
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing gallery');
        initGalleryMobile();
    });

    // Aussi initialiser après un délai pour être sûr
    window.addEventListener('load', function() {
        setTimeout(function() {
            console.log('Window loaded, re-initializing gallery');
            initGalleryMobile();
        }, 500);
    });

    function initGalleryMobile() {
        const carousel = document.querySelector('.gallery-carousel');
        
        if (!carousel) {
            console.log('No gallery carousel found');
            return;
        }

        console.log('Gallery carousel found!');

        const slides = carousel.querySelectorAll('.gallery-slide');
        const prevBtn = carousel.querySelector('.gallery-prev');
        const nextBtn = carousel.querySelector('.gallery-next');
        const counter = carousel.querySelector('.gallery-counter');
        const thumbnails = document.querySelectorAll('.gallery-thumb');

        console.log('Slides:', slides.length);
        console.log('Thumbnails:', thumbnails.length);

        if (slides.length === 0) {
            console.log('No slides found');
            return;
        }

        let currentIndex = 0;

        // Forcer le chargement de toutes les images et initialiser les slides
        slides.forEach(function(slide, index) {
            // Forcer le chargement de l'image
            const img = slide.querySelector('img');
            if (img) {
                img.removeAttribute('loading');
            }
            
            // Nettoyer les styles inline
            slide.removeAttribute('style');
            
            if (index === 0) {
                slide.classList.add('active');
                console.log('First slide activated');
            } else {
                slide.classList.remove('active');
            }
        });

        // Fonction pour afficher une slide
        function showSlide(index) {
            console.log('Showing slide:', index);

            // Masquer toutes les slides (juste via les classes CSS)
            slides.forEach(function(slide) {
                slide.classList.remove('active');
            });

            // Afficher la slide actuelle
            if (slides[index]) {
                slides[index].classList.add('active');
            }

            // Mettre à jour les miniatures
            thumbnails.forEach(function(thumb, i) {
                if (i === index) {
                    thumb.classList.add('active');
                } else {
                    thumb.classList.remove('active');
                }
            });

            // Mettre à jour le compteur
            if (counter) {
                const currentSpan = counter.querySelector('.current-slide');
                if (currentSpan) {
                    currentSpan.textContent = index + 1;
                }
            }

            currentIndex = index;
        }

        // Bouton précédent
        if (prevBtn) {
            prevBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Prev clicked');
                const newIndex = currentIndex > 0 ? currentIndex - 1 : slides.length - 1;
                showSlide(newIndex);
            });
        }

        // Bouton suivant
        if (nextBtn) {
            nextBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Next clicked');
                const newIndex = currentIndex < slides.length - 1 ? currentIndex + 1 : 0;
                showSlide(newIndex);
            });
        }

        // Miniatures
        thumbnails.forEach(function(thumb, index) {
            thumb.addEventListener('click', function() {
                console.log('Thumbnail clicked:', index);
                showSlide(index);
            });
        });

        // Support du swipe tactile
        let touchStartX = 0;
        let touchEndX = 0;

        carousel.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        });

        carousel.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });

        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = touchStartX - touchEndX;

            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    // Swipe gauche - image suivante
                    const newIndex = currentIndex < slides.length - 1 ? currentIndex + 1 : 0;
                    showSlide(newIndex);
                } else {
                    // Swipe droite - image précédente
                    const newIndex = currentIndex > 0 ? currentIndex - 1 : slides.length - 1;
                    showSlide(newIndex);
                }
            }
        }

        console.log('Gallery mobile initialized successfully');
    }
})();
