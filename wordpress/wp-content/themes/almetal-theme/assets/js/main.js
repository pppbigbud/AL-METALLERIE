/**
 * Scripts JavaScript AL Metallerie Soudure
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Menu mobile toggle
     */
    function initMobileMenu() {
        const menuToggle = $('.mobile-menu-toggle');
        const navigation = $('.main-navigation');

        menuToggle.on('click', function() {
            const isOpen = navigation.hasClass('is-open');
            
            navigation.toggleClass('is-open');
            $(this).attr('aria-expanded', !isOpen);
            
            // Changer l'icône
            const icon = $(this).find('.menu-icon');
            icon.text(isOpen ? '☰' : '✕');
        });

        // Fermer le menu lors du clic sur un lien
        navigation.find('a').on('click', function() {
            if ($(window).width() <= 768) {
                navigation.removeClass('is-open');
                menuToggle.attr('aria-expanded', 'false');
                menuToggle.find('.menu-icon').text('☰');
            }
        });
    }

    /**
     * Smooth scroll pour les ancres
     */
    function initSmoothScroll() {
        $('a[href^="#"]').on('click', function(e) {
            const target = $(this.getAttribute('href'));
            
            if (target.length) {
                e.preventDefault();
                
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 80 // Offset pour le header fixe
                }, 800, 'swing');
            }
        });
    }

    /**
     * Bouton scroll to top
     */
    function initScrollToTop() {
        const scrollBtn = $('#scroll-to-top');
        
        if (scrollBtn.length) {
            $(window).on('scroll', function() {
                if ($(this).scrollTop() > 300) {
                    scrollBtn.addClass('visible');
                } else {
                    scrollBtn.removeClass('visible');
                }
            });

            scrollBtn.on('click', function(e) {
                e.preventDefault();
                $('html, body').animate({ scrollTop: 0 }, 600);
            });
        }
    }

    /**
     * Highlight du menu actif lors du scroll (one-page)
     */
    function initScrollSpy() {
        if (!$('body').hasClass('one-page-layout')) {
            return;
        }

        const sections = $('.onepage-section');
        const navLinks = $('.main-navigation a[href^="#"]');

        $(window).on('scroll', function() {
            let current = '';
            const scrollPos = $(this).scrollTop() + 100;

            sections.each(function() {
                const sectionTop = $(this).offset().top;
                const sectionHeight = $(this).outerHeight();
                const sectionId = $(this).attr('id');

                if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
                    current = sectionId;
                }
            });

            navLinks.removeClass('active');
            navLinks.filter('[href="#' + current + '"]').addClass('active');
        });
    }

    /**
     * Lazy loading des images
     */
    function initLazyLoading() {
        if ('loading' in HTMLImageElement.prototype) {
            // Le navigateur supporte le lazy loading natif
            const images = document.querySelectorAll('img[loading="lazy"]');
            images.forEach(img => {
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                }
            });
        } else {
            // Fallback pour les navigateurs plus anciens
            const script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js';
            document.body.appendChild(script);
        }
    }

    /**
     * Animation au scroll (fade in)
     */
    function initScrollAnimations() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observer les sections
        document.querySelectorAll('.onepage-section, article').forEach(el => {
            el.classList.add('fade-in');
            observer.observe(el);
        });
    }

    /**
     * Animation progressive de la barre orange liée au scroll
     */
    function initOrangeBarScrollAnimation() {
        const presentationSection = document.querySelector('.presentation-section');
        const orangeBar = document.querySelector('.presentation-orange-bar');
        
        if (!presentationSection || !orangeBar) {
            return;
        }

        function updateOrangeBar() {
            const rect = presentationSection.getBoundingClientRect();
            const windowHeight = window.innerHeight;
            const sectionTop = rect.top;
            const sectionHeight = rect.height;
            
            // Calculer la progression du scroll dans la section
            // Commencer l'animation plus tard : quand la section est plus visible
            const scrollStart = windowHeight * 0.7; // Démarre plus tard (70% de hauteur)
            const scrollEnd = -windowHeight * 0.5; // Continue jusqu'à ce que la section sorte
            const scrollRange = scrollStart - scrollEnd;
            
            // Position actuelle dans la plage de scroll
            const scrollPosition = Math.max(0, Math.min(scrollRange, scrollStart - sectionTop));
            
            // Progression en pourcentage (0 à 1)
            const progress = scrollPosition / scrollRange;
            
            // Hauteur maximale de la barre : 70%
            const maxHeight = 70;
            const currentHeight = progress * maxHeight;
            
            // Appliquer la hauteur
            orangeBar.style.height = `${currentHeight}%`;
            
            // Gérer les classes fade-in pour le contenu
            // Fade-out désactivé pour éviter l'effet au scroll vers le haut
            if (progress > 0.05) {
                presentationSection.classList.add('fade-in');
            } else {
                presentationSection.classList.remove('fade-in');
            }
            
            // Fade-out désactivé - le contenu reste visible au scroll vers le haut
            /* 
            if (sectionTop < -sectionHeight / 2) {
                presentationSection.classList.add('fade-out');
            } else {
                presentationSection.classList.remove('fade-out');
            }
            */
        }

        // Mettre à jour au scroll avec throttling
        let ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    updateOrangeBar();
                    ticking = false;
                });
                ticking = true;
            }
        });
        
        // Initialiser au chargement
        updateOrangeBar();
    }

    /**
     * Gestion des formulaires
     */
    function initForms() {
        // Validation simple des formulaires
        $('form').on('submit', function(e) {
            const form = $(this);
            let isValid = true;

            // Vérifier les champs requis
            form.find('[required]').each(function() {
                if (!$(this).val()) {
                    isValid = false;
                    $(this).addClass('error');
                } else {
                    $(this).removeClass('error');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires.');
            }
        });

        // Retirer la classe d'erreur lors de la saisie
        $('input, textarea, select').on('input change', function() {
            $(this).removeClass('error');
        });
    }

    /**
     * Détection du device pour ajustements spécifiques
     */
    function detectDevice() {
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        
        if (isMobile) {
            $('body').addClass('is-touch-device');
        }
    }

    /**
     * Hero Carousel
     */
    function initHeroCarousel() {
        const carousel = $('.hero-carousel');
        
        if (!carousel.length) {
            return;
        }

        const slides = carousel.find('.hero-slide');
        const prevBtn = carousel.find('.hero-prev');
        const nextBtn = carousel.find('.hero-next');
        const indicatorsContainer = carousel.find('.hero-indicators');
        
        let currentSlide = 0;
        let slideInterval;
        const slideDelay = 5000; // 5 secondes entre chaque slide

        // Créer les indicateurs
        slides.each(function(index) {
            const indicator = $('<button class="hero-indicator"></button>');
            indicator.attr('aria-label', 'Aller au slide ' + (index + 1));
            indicator.on('click', function() {
                goToSlide(index);
            });
            indicatorsContainer.append(indicator);
        });

        const indicators = indicatorsContainer.find('.hero-indicator');

        // Fonction pour aller à un slide spécifique
        function goToSlide(index) {
            slides.removeClass('active');
            indicators.removeClass('active');
            
            currentSlide = index;
            
            $(slides[currentSlide]).addClass('active');
            $(indicators[currentSlide]).addClass('active');
            
            resetInterval();
        }

        // Fonction pour aller au slide suivant
        function nextSlide() {
            let next = currentSlide + 1;
            if (next >= slides.length) {
                next = 0;
            }
            goToSlide(next);
        }

        // Fonction pour aller au slide précédent
        function prevSlide() {
            let prev = currentSlide - 1;
            if (prev < 0) {
                prev = slides.length - 1;
            }
            goToSlide(prev);
        }

        // Démarrer l'intervalle
        function startInterval() {
            slideInterval = setInterval(nextSlide, slideDelay);
        }

        // Réinitialiser l'intervalle
        function resetInterval() {
            clearInterval(slideInterval);
            startInterval();
        }

        // Event listeners pour les boutons
        nextBtn.on('click', function() {
            nextSlide();
        });

        prevBtn.on('click', function() {
            prevSlide();
        });

        // Pause au survol
        carousel.on('mouseenter', function() {
            clearInterval(slideInterval);
        });

        carousel.on('mouseleave', function() {
            startInterval();
        });

        // Support du clavier
        $(document).on('keydown', function(e) {
            if (!carousel.is(':visible')) return;
            
            if (e.key === 'ArrowLeft') {
                prevSlide();
            } else if (e.key === 'ArrowRight') {
                nextSlide();
            }
        });

        // Initialiser le premier slide
        goToSlide(0);
    }

    /**
     * Filtrage des réalisations par type (page archive uniquement)
     * NOTE: Le filtrage de la section actualités homepage est géré par actualites-filter.js
     * NOTE: Le filtrage de la page archive est maintenant géré par archive-lazy-load.js
     */
    function initRealisationsFilter() {
        // Si archive-lazy-load.js est actif (page archive avec lazy loading), ne pas initialiser
        if (document.querySelector('.archive-page .archive-grid.realisations-grid')) {
            console.log('Filtrage géré par archive-lazy-load.js - main.js désactivé');
            return;
        }
        
        // Sélecteur spécifique pour la page archive des réalisations uniquement
        // Exclut la section actualités de la homepage et la section services
        const filterBtns = $('.archive-realisations .filter-btn, .realisations-archive .filter-btn');
        const realisationCards = $('.archive-realisations .realisation-card, .realisations-archive .realisation-card');

        if (!filterBtns.length || !realisationCards.length) {
            return;
        }

        filterBtns.on('click', function() {
            const filter = $(this).data('filter');

            // Mettre à jour les boutons actifs
            filterBtns.removeClass('active');
            $(this).addClass('active');

            // Filtrer les cartes
            if (filter === '*') {
                // Afficher toutes les réalisations
                realisationCards.fadeOut(200, function() {
                    $(this).show().addClass('is-visible');
                }).fadeIn(400);
            } else {
                // Masquer toutes les cartes
                realisationCards.fadeOut(200, function() {
                    $(this).removeClass('is-visible');
                });

                // Afficher seulement les cartes filtrées
                setTimeout(function() {
                    realisationCards.filter(filter).fadeIn(400, function() {
                        $(this).addClass('is-visible');
                    });
                }, 200);
            }

            // Animation de comptage
            setTimeout(function() {
                const visibleCount = realisationCards.filter(':visible').length;
                console.log('Réalisations affichées:', visibleCount);
            }, 600);
        });
    }

    /**
     * Filtrage des actualités par catégorie
     */
    function initActualitesFilter() {
        const filterBtns = $('.actualites-filters .filter-btn');
        const actualiteCards = $('.actualite-card');

        if (!filterBtns.length || !actualiteCards.length) {
            return;
        }

        filterBtns.on('click', function() {
            const filter = $(this).data('filter');

            // Mettre à jour les boutons actifs
            filterBtns.removeClass('active');
            $(this).addClass('active');

            // Filtrer les cartes
            if (filter === '*') {
                // Afficher toutes les actualités
                actualiteCards.fadeOut(200, function() {
                    $(this).show().removeClass('hidden');
                }).fadeIn(400);
            } else {
                // Masquer toutes les cartes
                actualiteCards.fadeOut(200, function() {
                    $(this).addClass('hidden');
                });

                // Afficher seulement les cartes filtrées
                setTimeout(function() {
                    actualiteCards.filter(filter).fadeIn(400, function() {
                        $(this).removeClass('hidden');
                    });
                }, 200);
            }
        });
    }

    /**
     * Filtrage mobile des réalisations (4 dernières par catégorie) - VERSION SIMPLIFIÉE
     */
    function initMobileRealisationsFilter() {
        console.log('Init mobile realisations filter');
        
        // Utiliser setTimeout pour s'assurer que le DOM est chargé
        setTimeout(function() {
            const filterBtns = document.querySelectorAll('.mobile-filter-btn');
            const realisationCards = document.querySelectorAll('.mobile-realisation-card');

            console.log('Filter buttons:', filterBtns.length);
            console.log('Realisation cards:', realisationCards.length);

            if (filterBtns.length === 0 || realisationCards.length === 0) {
                console.log('Elements not found, retrying in 2 seconds...');
                // Réessayer après 2 secondes
                setTimeout(function() {
                    const filterBtns2 = document.querySelectorAll('.mobile-filter-btn');
                    const realisationCards2 = document.querySelectorAll('.mobile-realisation-card');
                    
                    console.log('Retry - Filter buttons:', filterBtns2.length);
                    console.log('Retry - Realisation cards:', realisationCards2.length);
                    
                    if (filterBtns2.length === 0 || realisationCards2.length === 0) {
                        console.log('Still not found. Check if you are on mobile homepage.');
                        return;
                    }
                    
                    // Initialiser avec les nouveaux éléments
                    initFilterLogic(filterBtns2, realisationCards2);
                }, 2000);
                return;
            }
            
            // Initialiser directement
            initFilterLogic(filterBtns, realisationCards);
        }, 500);
    }
    
    // Fonction séparée pour la logique de filtrage
    function initFilterLogic(filterBtns, realisationCards) {
        // Afficher les 4 premières au chargement
        realisationCards.forEach(function(card, index) {
            if (index < 4) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });

        // Ajouter les événements de clic
        filterBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                console.log('Filter clicked:', filter);

                // Mettre à jour les boutons actifs
                filterBtns.forEach(function(b) {
                    b.classList.remove('active');
                });
                this.classList.add('active');

                // Masquer toutes les cartes
                realisationCards.forEach(function(card) {
                    card.style.display = 'none';
                });

                // Afficher les cartes filtrées
                if (filter === '*') {
                    // Afficher les 4 premières de toutes les catégories
                    let count = 0;
                    realisationCards.forEach(function(card) {
                        if (count < 4) {
                            card.style.display = 'block';
                            count++;
                        }
                    });
                } else {
                    // Afficher les 4 premières de la catégorie sélectionnée
                    let count = 0;
                    realisationCards.forEach(function(card) {
                        if (count < 4 && card.classList.contains(filter.replace('.', ''))) {
                            card.style.display = 'block';
                            count++;
                        }
                    });
                }
            });
        });

        console.log('Mobile filter initialized successfully');
    }

    /**
     * Scroll to top button
     */
    function initScrollToTopButton() {
        const scrollBtn = $('#scroll-to-top');
        
        if (!scrollBtn.length) {
            return;
        }

        // Afficher/masquer le bouton au scroll
        $(window).on('scroll', function() {
            if ($(this).scrollTop() > 300) {
                scrollBtn.addClass('visible');
            } else {
                scrollBtn.removeClass('visible');
            }
        });

        // Scroll vers le haut au clic
        scrollBtn.on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({ scrollTop: 0 }, 600);
        });
    }

    /**
     * Header scroll effect
     */
    function initHeaderScroll() {
        const header = $('.site-header');
        const scrollThreshold = 200;

        $(window).on('scroll', function() {
            if ($(this).scrollTop() > scrollThreshold) {
                header.addClass('scrolled');
            } else {
                header.removeClass('scrolled');
            }
        });

        // Vérifier au chargement
        if ($(window).scrollTop() > scrollThreshold) {
            header.addClass('scrolled');
        }
    }

    /**
     * Menu burger mobile (SIMPLIFIÉ)
     */
    function initBurgerMenu() {
        // Sélection directe avec vanilla JS
        const burgerBtn = document.querySelector('.mobile-menu-toggle');
        const mobileNav = document.getElementById('mobile-menu');
        const body = document.body;

        if (!burgerBtn || !mobileNav) {
            console.log('Burger menu elements not found');
            return;
        }

        console.log('Burger menu initialized');

        // Toggle menu au clic
        burgerBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const isOpen = this.getAttribute('aria-expanded') === 'true';
            
            // Toggle
            this.setAttribute('aria-expanded', !isOpen);
            
            if (isOpen) {
                mobileNav.classList.remove('is-open');
                body.classList.remove('menu-open');
            } else {
                mobileNav.classList.add('is-open');
                body.classList.add('menu-open');
            }
            
            console.log('Menu toggled, isOpen:', !isOpen);
        });

        // Fermer au clic sur un lien
        const menuLinks = document.querySelectorAll('.mobile-menu-link');
        menuLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                burgerBtn.setAttribute('aria-expanded', 'false');
                mobileNav.classList.remove('is-open');
                body.classList.remove('menu-open');
            });
        });

        // Fermer avec Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileNav.classList.contains('is-open')) {
                burgerBtn.setAttribute('aria-expanded', 'false');
                mobileNav.classList.remove('is-open');
                body.classList.remove('menu-open');
            }
        });
    }

    /**
     * Dropdown menu mobile
     */
    function initDropdownMenu() {
        // Desktop : hover
        if ($(window).width() > 992) {
            $('.menu-item.has-dropdown').hover(
                function() {
                    $(this).find('.dropdown-menu').first().stop(true, true).fadeIn(200);
                },
                function() {
                    $(this).find('.dropdown-menu').first().stop(true, true).fadeOut(200);
                }
            );
        }

        // Mobile : click
        $('.menu-item.has-dropdown > a').on('click', function(e) {
            if ($(window).width() <= 992) {
                e.preventDefault();
                const parent = $(this).parent();
                
                // Toggle dropdown
                parent.toggleClass('dropdown-open');
                
                // Fermer les autres
                $('.menu-item.has-dropdown').not(parent).removeClass('dropdown-open');
            }
        });

        // Réinitialiser au resize
        $(window).on('resize', function() {
            if ($(window).width() > 992) {
                $('.menu-item.has-dropdown').removeClass('dropdown-open');
                $('.dropdown-menu').removeAttr('style');
            }
        });
    }

    /**
     * Initialisation au chargement du DOM
     */
    $(document).ready(function() {
        initHeaderScroll();
        // initBurgerMenu(); // Désactivé - géré par mobile-navigation.js
        initDropdownMenu();
        initMobileMenu();
        initSmoothScroll();
        initScrollToTop();
        initScrollSpy();
        initLazyLoading();
        initScrollAnimations();
        initOrangeBarScrollAnimation();
        initForms();
        detectDevice();
        initHeroCarousel();
        initRealisationsFilter();
        initActualitesFilter();
        initMobileRealisationsFilter();
        initScrollToTopButton();

        // Log pour debug (à retirer en production)
        if (typeof almetalData !== 'undefined') {
            console.log('AL Metallerie Soudure Theme loaded');
            console.log('Is Mobile:', almetalData.isMobile);
        }
    });

    /**
     * Animation de l'icône mégaphone dans la section actualités au scroll
     */
    function initActualitesIconAnimation() {
        const actualitesSection = document.querySelector('.actualites-section');
        
        if (!actualitesSection) return;
        
        function checkScroll() {
            const rect = actualitesSection.getBoundingClientRect();
            const windowHeight = window.innerHeight;
            
            // Déclencher l'animation quand la section est visible à 30%
            if (rect.top < windowHeight * 0.7) {
                actualitesSection.classList.add('animate-megaphone');
            }
        }
        
        // Vérifier au scroll
        window.addEventListener('scroll', checkScroll);
        // Vérifier au chargement
        checkScroll();
    }
    
    // Initialiser l'animation de l'icône
    initActualitesIconAnimation();

    /**
     * Initialisation après le chargement complet de la page
     */
    $(window).on('load', function() {
        // Masquer le loader si présent
        $('.page-loader').fadeOut();
    });

})(jQuery);

/**
 * Styles CSS pour les animations (à ajouter dans custom.css si nécessaire)
 */
/*
.fade-in {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}

.fade-in.is-visible {
    opacity: 1;
    transform: translateY(0);
}

input.error,
textarea.error,
select.error {
    border-color: #e74c3c;
}
*/
