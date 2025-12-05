/**
 * Mega Menu - AL Metallerie
 * Gestion des interactions du mega menu
 */

(function() {
    'use strict';

    // Attendre que le DOM soit chargé
    document.addEventListener('DOMContentLoaded', function() {
        
        // Gestion du hover sur les catégories de réalisations
        const categoryItems = document.querySelectorAll('.megamenu-categories__item');
        const megamenuContent = document.querySelector('.megamenu-content');
        
        // Fonction pour afficher le contenu d'une catégorie
        function showCategoryContent(item) {
            // Récupérer le contenu de la catégorie
            const categoryContent = item.querySelector('.megamenu-category-content');
            
            // Vider le contenu actuel
            if (megamenuContent && categoryContent) {
                // Cloner le contenu de la catégorie
                const contentClone = categoryContent.cloneNode(true);
                
                // Vider et insérer le nouveau contenu
                const existingGrid = megamenuContent.querySelector('.megamenu-content__grid');
                if (existingGrid) {
                    existingGrid.remove();
                }
                
                const gridToInsert = contentClone.querySelector('.megamenu-content__grid');
                if (gridToInsert) {
                    gridToInsert.style.display = 'grid';
                    megamenuContent.appendChild(gridToInsert);
                }
            }
        }
        
        // Initialiser avec la première catégorie
        if (categoryItems.length > 0) {
            showCategoryContent(categoryItems[0]);
        }
        
        // Mapping des icônes par slug de catégorie (blanc 10%)
        const categoryIcons = {
            'portails': '<svg xmlns="http://www.w3.org/2000/svg" width="250" height="250" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1.5"><rect x="3" y="3" width="7" height="18" rx="1"/><rect x="14" y="3" width="7" height="18" rx="1"/></svg>',
            'garde-corps': '<svg xmlns="http://www.w3.org/2000/svg" width="250" height="250" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1.5"><path d="M3 12h18M3 6h18M3 18h18"/><circle cx="6" cy="12" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="18" cy="12" r="1"/></svg>',
            'escaliers': '<svg xmlns="http://www.w3.org/2000/svg" width="250" height="250" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1.5"><path d="M6 20h4v-4h4v-4h4V8h4"/></svg>',
            'pergolas': '<svg xmlns="http://www.w3.org/2000/svg" width="250" height="250" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1.5"><path d="M3 21h18M4 18h16M5 15h14M6 12h12M7 9h10M8 6h8M9 3h6"/></svg>',
            'grilles': '<svg xmlns="http://www.w3.org/2000/svg" width="250" height="250" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18M15 3v18"/></svg>',
            'ferronnerie-art': '<svg xmlns="http://www.w3.org/2000/svg" width="250" height="250" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1.5"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5M2 12l10 5 10-5"/></svg>'
        };
        
        categoryItems.forEach(function(item) {
            item.addEventListener('mouseenter', function() {
                // Retirer la classe active de tous les items
                categoryItems.forEach(function(i) {
                    i.classList.remove('active');
                });
                
                // Ajouter la classe active à l'item survolé
                item.classList.add('active');
                
                // Afficher le contenu de cette catégorie
                showCategoryContent(item);
                
                // Animer l'icône en arrière-plan
                const iconSlug = item.getAttribute('data-icon-slug');
                if (iconSlug && categoryIcons[iconSlug] && megamenuWrapper) {
                    // Encoder le SVG pour l'URL
                    const svgEncoded = 'data:image/svg+xml,' + encodeURIComponent(categoryIcons[iconSlug]);
                    megamenuWrapper.style.setProperty('--category-icon', `url("${svgEncoded}")`);
                    megamenuWrapper.classList.add('show-icon');
                }
            });
        });
        
        // Réinitialiser au premier item quand on quitte le mega menu
        const megamenuWrapper = document.querySelector('.megamenu-wrapper');
        if (megamenuWrapper) {
            megamenuWrapper.addEventListener('mouseleave', function() {
                setTimeout(function() {
                    categoryItems.forEach(function(i) {
                        i.classList.remove('active');
                    });
                    if (categoryItems.length > 0) {
                        categoryItems[0].classList.add('active');
                    }
                }, 300);
            });
        }
        
        // Empêcher la propagation des clics sur les liens du mega menu
        const megamenuLinks = document.querySelectorAll('.megamenu-wrapper a');
        megamenuLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                // Laisser le lien fonctionner normalement
                // mais empêcher la fermeture du menu si nécessaire
            });
        });
        
        // Gestion du scroll pour réduire le logo
        let lastScroll = 0;
        const header = document.querySelector('.site-header');
        
        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;
            
            if (currentScroll > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
            
            lastScroll = currentScroll;
        });
        
        // ============================================
        // DÉFILEMENT DES CATÉGORIES
        // ============================================
        
        const scrollContainer = document.querySelector('.megamenu-categories__scroll-container');
        const scrollBtn = document.querySelector('.megamenu-categories__scroll-btn');
        const categoriesList = document.querySelector('.megamenu-categories__list');
        
        if (scrollContainer && scrollBtn && categoriesList) {
            let currentScrollPosition = 0;
            const itemHeight = 76; // Hauteur approximative d'un item (padding + gap)
            const totalItems = categoriesList.querySelectorAll('.megamenu-categories__item').length;
            
            // Fonction pour déterminer combien d'items afficher selon la taille d'écran
            function getDisplayedItems() {
                const width = window.innerWidth;
                if (width <= 1024) return 2; // Tablet
                if (width <= 1280) return 3; // Laptop
                return 4; // Desktop
            }
            
            // Fonction pour calculer le max scroll
            function getMaxScroll() {
                const displayedItems = getDisplayedItems();
                return Math.max(0, (totalItems - displayedItems) * itemHeight);
            }
            
            // Fonction pour mettre à jour la visibilité du bouton
            function updateScrollBtnVisibility() {
                const maxScroll = getMaxScroll();
                if (maxScroll <= 0) {
                    scrollBtn.style.display = 'none';
                } else {
                    scrollBtn.style.display = 'flex';
                }
            }
            
            // Initialiser la visibilité du bouton
            updateScrollBtnVisibility();
            
            // Mettre à jour au redimensionnement
            window.addEventListener('resize', function() {
                updateScrollBtnVisibility();
                // Réinitialiser la position si nécessaire
                const maxScroll = getMaxScroll();
                if (currentScrollPosition > maxScroll) {
                    currentScrollPosition = maxScroll;
                    categoriesList.style.transform = `translateY(-${currentScrollPosition}px)`;
                }
                // Mettre à jour l'état du bouton
                if (currentScrollPosition >= maxScroll && maxScroll > 0) {
                    scrollBtn.classList.add('scrolled-bottom');
                } else {
                    scrollBtn.classList.remove('scrolled-bottom');
                }
            });
            
            scrollBtn.addEventListener('click', function() {
                const displayedItems = getDisplayedItems();
                const maxScroll = getMaxScroll();
                
                // Si on est en bas, remonter en haut
                if (scrollBtn.classList.contains('scrolled-bottom')) {
                    currentScrollPosition = 0;
                    scrollBtn.classList.remove('scrolled-bottom');
                } else {
                    // Défiler vers le bas
                    currentScrollPosition += displayedItems * itemHeight;
                    
                    // Si on atteint ou dépasse le max, aller au max et changer l'état du bouton
                    if (currentScrollPosition >= maxScroll) {
                        currentScrollPosition = maxScroll;
                        scrollBtn.classList.add('scrolled-bottom');
                    }
                }
                
                // Appliquer le défilement avec transform
                categoriesList.style.transform = `translateY(-${currentScrollPosition}px)`;
            });
            
            // Réinitialiser la position quand on quitte le mega menu
            if (megamenuWrapper) {
                megamenuWrapper.addEventListener('mouseleave', function() {
                    setTimeout(function() {
                        currentScrollPosition = 0;
                        categoriesList.style.transform = 'translateY(0)';
                        scrollBtn.classList.remove('scrolled-bottom');
                    }, 300);
                });
            }
        }
        
    });
    
})();
