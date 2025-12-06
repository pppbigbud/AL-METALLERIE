/**
 * Lazy Loading des cartes de réalisations
 * Les cartes se chargent progressivement au scroll
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

(function() {
    'use strict';

    // Configuration
    const CONFIG = {
        cardsPerBatch: 6,           // Nombre de cartes à afficher par batch
        scrollThreshold: 200,       // Distance du bas avant de charger plus (en px)
        animationDelay: 100,        // Délai entre chaque animation de carte (en ms)
        animationDuration: 400      // Durée de l'animation d'apparition (en ms)
    };

    // État
    let currentlyShown = 0;
    let allCards = [];
    let filteredCards = [];
    let isLoading = false;
    let currentFilter = '*';

    /**
     * Initialisation
     */
    function init() {
        const grid = document.querySelector('.archive-grid.realisations-grid');
        if (!grid) return;

        // Récupérer toutes les cartes
        allCards = Array.from(grid.querySelectorAll('.realisation-card'));
        
        if (allCards.length === 0) return;

        console.log('📦 Lazy Load initialisé -', allCards.length, 'cartes au total');

        // Masquer toutes les cartes initialement
        allCards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.display = 'none';
            card.style.transition = `opacity ${CONFIG.animationDuration}ms ease, transform ${CONFIG.animationDuration}ms ease`;
        });

        // Appliquer le filtre initial
        applyFilter('*');

        // Afficher le premier batch
        showNextBatch();

        // Écouter le scroll
        window.addEventListener('scroll', handleScroll, { passive: true });

        // Écouter les clics sur les filtres
        initFilterListeners();
    }

    /**
     * Initialiser les écouteurs de filtres
     */
    function initFilterListeners() {
        const filterBtns = document.querySelectorAll('.archive-filters .filter-btn');
        
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                
                // Mettre à jour l'état actif
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Appliquer le nouveau filtre
                currentFilter = filter;
                resetAndFilter(filter);
            });
        });
    }

    /**
     * Appliquer un filtre sur les cartes
     */
    function applyFilter(filter) {
        if (filter === '*') {
            filteredCards = [...allCards];
        } else {
            // Le filtre est au format ".type-slug", on enlève juste le point
            const filterClass = filter.replace('.', '');
            
            filteredCards = allCards.filter(card => {
                return card.classList.contains(filterClass);
            });
        }
    }

    /**
     * Réinitialiser et appliquer un nouveau filtre
     */
    function resetAndFilter(filter) {
        // Masquer toutes les cartes avec animation
        allCards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
        });

        // Attendre la fin de l'animation puis réinitialiser
        setTimeout(() => {
            allCards.forEach(card => {
                card.style.display = 'none';
            });
            
            currentlyShown = 0;
            applyFilter(filter);
            showNextBatch();
        }, CONFIG.animationDuration);
    }

    /**
     * Afficher le prochain batch de cartes
     */
    function showNextBatch() {
        if (isLoading) return;
        if (currentlyShown >= filteredCards.length) return;

        isLoading = true;

        const startIndex = currentlyShown;
        const endIndex = Math.min(currentlyShown + CONFIG.cardsPerBatch, filteredCards.length);
        const cardsToShow = filteredCards.slice(startIndex, endIndex);

        console.log('📤 Affichage des cartes', startIndex + 1, 'à', endIndex);

        cardsToShow.forEach((card, index) => {
            setTimeout(() => {
                card.style.display = 'block';
                
                // Forcer un reflow pour que la transition fonctionne
                card.offsetHeight;
                
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * CONFIG.animationDelay);
        });

        currentlyShown = endIndex;

        // Débloquer après l'animation
        setTimeout(() => {
            isLoading = false;
            
            // Vérifier si on doit charger plus (si la page n'est pas assez remplie)
            if (!isPageFilled() && currentlyShown < filteredCards.length) {
                showNextBatch();
            }
        }, cardsToShow.length * CONFIG.animationDelay + CONFIG.animationDuration);

        // Mettre à jour l'indicateur de chargement
        updateLoadingIndicator();
    }

    /**
     * Vérifier si la page est suffisamment remplie
     */
    function isPageFilled() {
        return document.documentElement.scrollHeight > window.innerHeight + 100;
    }

    /**
     * Gérer le scroll
     */
    function handleScroll() {
        if (isLoading) return;
        if (currentlyShown >= filteredCards.length) return;

        const scrollPosition = window.innerHeight + window.scrollY;
        const documentHeight = document.documentElement.scrollHeight;

        if (documentHeight - scrollPosition < CONFIG.scrollThreshold) {
            showNextBatch();
        }
    }

    /**
     * Mettre à jour l'indicateur de chargement
     */
    function updateLoadingIndicator() {
        let indicator = document.querySelector('.lazy-load-indicator');
        
        if (currentlyShown >= filteredCards.length) {
            // Toutes les cartes sont affichées
            if (indicator) {
                indicator.innerHTML = `
                    <span class="indicator-text">
                        ${filteredCards.length} réalisation${filteredCards.length > 1 ? 's' : ''} affichée${filteredCards.length > 1 ? 's' : ''}
                    </span>
                `;
                indicator.classList.add('complete');
            }
        } else {
            // Il reste des cartes à charger
            if (!indicator) {
                indicator = document.createElement('div');
                indicator.className = 'lazy-load-indicator';
                const grid = document.querySelector('.archive-grid.realisations-grid');
                if (grid && grid.parentNode) {
                    grid.parentNode.insertBefore(indicator, grid.nextSibling);
                }
            }
            
            indicator.innerHTML = `
                <span class="indicator-text">
                    ${currentlyShown} / ${filteredCards.length} réalisations
                </span>
                <span class="indicator-scroll">Scrollez pour voir plus ↓</span>
            `;
            indicator.classList.remove('complete');
        }
    }

    // Lancer l'initialisation quand le DOM est prêt
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
