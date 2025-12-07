/**
 * Mobile Animations - Système d'animations au scroll
 * AL Métallerie - Thème WordPress personnalisé
 * 
 * Utilise IntersectionObserver API (natif, performant, sans bibliothèques)
 * Optimisé pour mobile avec support de prefers-reduced-motion
 * 
 * Classes d'animation supportées :
 * - .scroll-fade : Fade-in progressif
 * - .scroll-slide-up : Glissement vers le haut
 * - .scroll-slide-left : Glissement depuis la gauche
 * - .scroll-slide-right : Glissement depuis la droite
 * - .scroll-zoom : Zoom léger
 * - .scroll-zoom-in : Zoom depuis petit
 * - .scroll-rotate : Rotation + fade
 * - .scroll-flip : Flip horizontal
 * - .scroll-blur : Blur + fade
 * 
 * Délais cascade : .scroll-delay-1 à .scroll-delay-5
 * 
 * @package ALMetallerie
 * @version 2.0.0
 */

(function() {
    'use strict';

    // Vérifier si reduced motion est activé (accessibilité)
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    
    if (reducedMotion) {
        console.log('⚠️ Animations désactivées (prefers-reduced-motion)');
        return; // Désactiver toutes les animations
    }

    console.log('🎬 Mobile Animations v2.0 - Initialisation');

    // Attendre que le DOM soit chargé
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    /**
     * Initialisation du système d'animations
     */
    function init() {
        // Vérifier le support d'IntersectionObserver
        if (!('IntersectionObserver' in window)) {
            console.warn('⚠️ IntersectionObserver non supporté, fallback vers scroll listener');
            initScrollFallback();
            return;
        }

        // Configuration de l'Intersection Observer
        const observerOptions = {
            threshold: 0.1, // Déclencher quand 10% de l'élément est visible
            rootMargin: '0px 0px -50px 0px' // Déclencher légèrement avant d'être visible
        };

        // Créer l'observer
        const observer = new IntersectionObserver(handleIntersection, observerOptions);

        // Sélectionner tous les éléments avec classes d'animation
        const animateElements = document.querySelectorAll(
            '.scroll-fade, .scroll-slide-up, .scroll-slide-left, .scroll-slide-right, ' +
            '.scroll-zoom, .scroll-zoom-in, .scroll-rotate, .scroll-flip, .scroll-blur, ' +
            '[class*="scroll-"]'
        );

        // Observer chaque élément
        let observedCount = 0;
        animateElements.forEach((element, index) => {
            // Éviter les doublons
            if (element.dataset.animated) return;
            
            element.dataset.animated = 'pending';
            observer.observe(element);
            observedCount++;

            // Ajouter un délai automatique pour les cards dans une grille
            if (element.classList.contains('realisation-card') || 
                element.classList.contains('mobile-contact-info-card') ||
                element.classList.contains('actualite-card')) {
                const delayClass = `scroll-delay-${(index % 5) + 1}`;
                if (!element.classList.contains('scroll-delay-1') && 
                    !element.classList.contains('scroll-delay-2') &&
                    !element.classList.contains('scroll-delay-3') &&
                    !element.classList.contains('scroll-delay-4') &&
                    !element.classList.contains('scroll-delay-5')) {
                    element.classList.add(delayClass);
                }
            }
        });

        console.log('✅ Animations initialisées:', observedCount, 'éléments observés');

        // Déclencher immédiatement les animations pour les éléments déjà visibles
        triggerVisibleOnLoad(animateElements);

        // Initialiser l'animation CTA au scroll
        initCtaScrollAnimation();

        // Nettoyer will-change après les animations pour optimiser la mémoire
        setTimeout(cleanupWillChange, 3000);
    }

    /**
     * Déclenche les animations pour les éléments déjà visibles au chargement
     */
    function triggerVisibleOnLoad(elements) {
        // Petit délai pour laisser le rendu initial se faire
        setTimeout(() => {
            elements.forEach((element, index) => {
                // Vérifier si l'élément est dans le viewport
                const rect = element.getBoundingClientRect();
                const isVisible = (
                    rect.top < window.innerHeight &&
                    rect.bottom > 0 &&
                    rect.left < window.innerWidth &&
                    rect.right > 0
                );

                if (isVisible && element.dataset.animated === 'pending') {
                    // Ajouter un délai progressif basé sur la position
                    const delay = index * 100; // 100ms entre chaque élément
                    setTimeout(() => {
                        element.dataset.animated = 'done';
                        element.classList.add('visible');
                    }, delay);
                }
            });
            console.log('✅ Animations au chargement déclenchées');
        }, 100);
    }

    /**
     * Animation des sections au scroll (mobile)
     * Déclenche l'animation ::before quand la section entre dans le viewport
     * Se désactive quand elle sort du viewport
     */
    function initCtaScrollAnimation() {
        // Sélectionner toutes les sections avec animation ::before
        const sections = document.querySelectorAll(
            '.cta-section, .mobile-formations, .mobile-realisations, .mobile-contact, .mobile-services'
        );
        
        if (!sections.length) return;

        const sectionObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Entré dans le viewport : activer l'animation avec délai
                    setTimeout(() => {
                        entry.target.classList.add('scroll-animated');
                    }, 400); // Délai de 400ms
                } else {
                    // Sorti du viewport : désactiver l'animation
                    entry.target.classList.remove('scroll-animated');
                }
            });
        }, {
            threshold: 0.3, // Déclencher quand 30% de la section est visible
            rootMargin: '0px'
        });

        sections.forEach(section => {
            sectionObserver.observe(section);
        });
        
        console.log('✅ Sections scroll animation initialisée:', sections.length, 'sections');
    }

    /**
     * Callback de l'Intersection Observer
     * Déclenche les animations quand les éléments deviennent visibles
     */
    function handleIntersection(entries, observer) {
        entries.forEach(entry => {
            if (entry.isIntersecting && entry.target.dataset.animated === 'pending') {
                // Marquer comme animé
                entry.target.dataset.animated = 'done';
                
                // Ajouter la classe 'visible' pour déclencher l'animation CSS
                requestAnimationFrame(() => {
                    entry.target.classList.add('visible');
                });

                // Arrêter d'observer cet élément (optimisation)
                observer.unobserve(entry.target);

                // Log pour debug (désactiver en production)
                if (window.location.hostname === 'localhost') {
                    const elementId = entry.target.id || entry.target.className.split(' ')[0];
                    console.log('🎬 Animation:', elementId);
                }
            }
        });
    }

    /**
     * Fallback pour les navigateurs sans IntersectionObserver
     * Utilise un écouteur de scroll classique (moins performant)
     */
    function initScrollFallback() {
        const animateElements = document.querySelectorAll('[class*="scroll-"]');
        
        function checkVisibility() {
            animateElements.forEach(element => {
                if (element.dataset.animated === 'done') return;

                const rect = element.getBoundingClientRect();
                const windowHeight = window.innerHeight || document.documentElement.clientHeight;
                
                // Vérifier si l'élément est visible dans le viewport
                const isVisible = rect.top <= windowHeight * 0.9 && rect.bottom >= 0;
                
                if (isVisible) {
                    element.dataset.animated = 'done';
                    element.classList.add('visible');
                }
            });
        }

        // Throttle pour optimiser les performances
        let ticking = false;
        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    checkVisibility();
                    ticking = false;
                });
                ticking = true;
            }
        });

        // Vérifier une première fois au chargement
        checkVisibility();

        console.log('✅ Fallback scroll initialisé:', animateElements.length, 'éléments');
    }

    /**
     * Nettoyer will-change après les animations
     * Optimise la mémoire GPU
     */
    function cleanupWillChange() {
        const animatedElements = document.querySelectorAll('.visible[class*="scroll-"]');
        animatedElements.forEach(element => {
            element.style.willChange = 'auto';
        });
        console.log('🧹 will-change nettoyé pour', animatedElements.length, 'éléments');
    }

    /**
     * Animation de compteur (optionnel, pour statistiques)
     * Usage: <span class="counter" data-target="150">0</span>
     */
    function initCounters() {
        const counters = document.querySelectorAll('.counter[data-target]');
        
        if (counters.length === 0) return;

        const observerOptions = {
            threshold: 0.5
        };

        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.dataset.counted) {
                    entry.target.dataset.counted = 'true';
                    animateCounter(entry.target);
                    counterObserver.unobserve(entry.target);
                }
            });
        }, observerOptions);

        counters.forEach(counter => counterObserver.observe(counter));
    }

    /**
     * Animer un compteur de 0 à sa valeur cible
     */
    function animateCounter(element) {
        const target = parseInt(element.dataset.target, 10);
        const duration = parseInt(element.dataset.duration, 10) || 2000;
        const start = 0;
        const increment = target / (duration / 16); // 60 FPS
        let current = start;

        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current);
        }, 16);
    }

    // Initialiser les compteurs si présents
    if (document.querySelector('.counter[data-target]')) {
        initCounters();
    }

    /**
     * Réinitialiser les animations (utile pour le développement)
     * Usage: window.resetAnimations()
     */
    window.resetAnimations = function() {
        const elements = document.querySelectorAll('[data-animated]');
        elements.forEach(el => {
            el.classList.remove('visible');
            el.dataset.animated = 'pending';
        });
        console.log('🔄 Animations réinitialisées');
        init();
    };

})();
