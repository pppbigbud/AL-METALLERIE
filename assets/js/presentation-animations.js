/**
 * Animations au scroll - Section Présentation
 * Utilise Intersection Observer API (légère, native)
 */
(function() {
    'use strict';

    // Vérifier si c'est la page d'accueil
    if (!document.querySelector('.presentation-section')) {
        return;
    }

    // Configuration de l'Intersection Observer
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.2
    };

    // Callback quand un élément entre dans le viewport
    const handleIntersection = function(entries, observer) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                // Ajouter la classe pour déclencher l'animation
                entry.target.classList.add('is-visible');
                
                // Optionnel : arrêter d'observer cet élément (animation unique)
                observer.unobserve(entry.target);
            }
        });
    };

    // Créer l'observer
    const observer = new IntersectionObserver(handleIntersection, observerOptions);

    // Sélectionner les éléments à animer
    const animatedElements = document.querySelectorAll(
        '.presentation-feature-icon, ' +
        '.presentation-image-wrapper, ' +
        '.presentation-tag, ' +
        '.presentation-h1, ' +
        '.presentation-title, ' +
        '.presentation-description'
    );

    // Observer chaque élément
    animatedElements.forEach(function(el) {
        observer.observe(el);
    });

    // Fallback pour les anciens navigateurs (sans Intersection Observer)
    if (!('IntersectionObserver' in window)) {
        // Rendre tous les éléments visibles immédiatement
        animatedElements.forEach(function(el) {
            el.classList.add('is-visible');
        });
    }

})();
