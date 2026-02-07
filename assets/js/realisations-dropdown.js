// Menu déroulant stylisé pour les réalisations avec intégration AJAX
(function() {
    'use strict';
    
    console.log('🎯 Initialisation dropdown réalisations avec AJAX...');
    
    // Variables globales
    let currentFilter = '*';
    let isLoading = false;
    
    // Initialisation
    function initDropdown() {
        const dropdown = document.querySelector('.realisations-dropdown');
        const trigger = document.querySelector('.dropdown-trigger');
        const overlay = document.querySelector('.dropdown-overlay') || createOverlay();
        const dropdownItems = document.querySelectorAll('.dropdown-item');
        
        if (!dropdown || !trigger || !dropdownItems.length) {
            console.warn('⚠️ Éléments dropdown non trouvés');
            return;
        }
        
        // Toggle ouverture/fermeture
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleDropdown();
        });
        
        // Écouteurs sur les items du dropdown
        dropdownItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const filter = this.dataset.filter;
                if (filter === currentFilter && filter !== '*') {
                    closeDropdown();
                    return;
                }
                
                selectDropdownItem(this);
                loadRealisations(filter);
                closeDropdown();
            });
        });
        
        // Fermeture extérieure
        overlay.addEventListener('click', closeDropdown);
        document.addEventListener('click', function(e) {
            if (!dropdown.contains(e.target) && !trigger.contains(e.target)) {
                closeDropdown();
            }
        });
        
        // Navigation clavier
        dropdown.addEventListener('keydown', handleKeyboardNavigation);
        
        console.log('✅ Dropdown avec AJAX initialisé');
    }
    
    // Toggle dropdown
    function toggleDropdown() {
        const dropdown = document.querySelector('.realisations-dropdown');
        const isOpen = dropdown.classList.contains('open');
        
        if (isOpen) {
            closeDropdown();
        } else {
            openDropdown();
        }
    }
    
    // Ouvrir dropdown
    function openDropdown() {
        const dropdown = document.querySelector('.realisations-dropdown');
        const trigger = document.querySelector('.dropdown-trigger');
        const overlay = document.querySelector('.dropdown-overlay');
        
        dropdown.classList.add('open');
        trigger.classList.add('active');
        trigger.setAttribute('aria-expanded', 'true');
        overlay.classList.add('active');
        
        console.log('📂 Dropdown ouvert');
    }
    
    // Fermer dropdown
    function closeDropdown() {
        const dropdown = document.querySelector('.realisations-dropdown');
        const trigger = document.querySelector('.dropdown-trigger');
        const overlay = document.querySelector('.dropdown-overlay');
        
        dropdown.classList.remove('open');
        trigger.classList.remove('active');
        trigger.setAttribute('aria-expanded', 'false');
        overlay.classList.remove('active');
        
        console.log('📂 Dropdown fermé');
    }
    
    // Sélectionner un item
    function selectDropdownItem(item) {
        const dropdownItems = document.querySelectorAll('.dropdown-item');
        
        // Mettre à jour l'état actif
        dropdownItems.forEach(el => {
            el.classList.remove('active');
            el.setAttribute('aria-selected', 'false');
        });
        item.classList.add('active');
        item.setAttribute('aria-selected', 'true');
        
        // Mettre à jour le bouton principal
        const triggerText = document.querySelector('.dropdown-trigger__text');
        const triggerCount = document.querySelector('.dropdown-trigger__count');
        const itemText = item.querySelector('.dropdown-item__text');
        const itemCount = item.querySelector('.dropdown-item__count');
        
        if (triggerText && itemText) {
            triggerText.textContent = itemText.textContent.trim();
        }
        if (triggerCount && itemCount) {
            triggerCount.textContent = itemCount.textContent.trim();
        }
    }
    
    // Charger les réalisations via AJAX
    function loadRealisations(filter) {
        if (isLoading) return;
        
        currentFilter = filter;
        isLoading = true;
        
        const grid = document.querySelector('#desktop-realisations-grid');
        const loader = document.querySelector('#desktop-realisations-loader');
        const loadMoreWrapper = document.querySelector('#desktop-loadmore-wrapper');
        
        // Afficher le loader
        if (loader) loader.style.display = 'flex';
        
        // Cacher le bouton "voir plus"
        if (loadMoreWrapper) loadMoreWrapper.style.display = 'none';
        
        // Préparer les données AJAX
        const formData = new FormData();
        formData.append('action', 'load_desktop_realisations');
        formData.append('category', filter);
        formData.append('page', 1);
        formData.append('per_page', 12); // Charger plus de résultats
        
        console.log('� Chargement AJAX pour:', filter);
        
        // Requête AJAX
        fetch(ajax_object.ajax_url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour la grille
                if (grid && data.data.html) {
                    grid.innerHTML = data.data.html;
                    
                    // Mettre à jour les données de pagination
                    grid.dataset.page = 1;
                    grid.dataset.total = data.data.total || 0;
                    
                    // Réinitialiser les animations
                    initCardAnimations();
                }
                
                // Afficher/masquer le bouton "voir plus"
                if (loadMoreWrapper) {
                    if (data.data.has_more) {
                        loadMoreWrapper.style.display = 'flex';
                        // Mettre à jour le compteur
                        const countElement = loadMoreWrapper.querySelector('.btn-load-more__count');
                        if (countElement && data.data.remaining) {
                            countElement.textContent = `${data.data.remaining} restantes`;
                        }
                    } else {
                        loadMoreWrapper.style.display = 'none';
                    }
                }
                
                console.log('✅ Réalisations chargées:', data.data.total, 'total');
            } else {
                console.error('❌ Erreur AJAX:', data);
            }
        })
        .catch(error => {
            console.error('❌ Erreur réseau:', error);
        })
        .finally(() => {
            isLoading = false;
            if (loader) loader.style.display = 'none';
        });
    }
    
    // Initialiser les animations des cartes
    function initCardAnimations() {
        const cards = document.querySelectorAll('.realisation-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }
    
    // Navigation au clavier
    function handleKeyboardNavigation(e) {
        const items = Array.from(document.querySelectorAll('.dropdown-item:not([style*="display: none"])'));
        const currentIndex = items.indexOf(document.activeElement);
        
        switch(e.key) {
            case 'Escape':
                closeDropdown();
                document.querySelector('.dropdown-trigger').focus();
                break;
            case 'ArrowDown':
                e.preventDefault();
                const nextItem = items[(currentIndex + 1) % items.length];
                if (nextItem) nextItem.focus();
                break;
            case 'ArrowUp':
                e.preventDefault();
                const prevItem = items[(currentIndex - 1 + items.length) % items.length];
                if (prevItem) prevItem.focus();
                break;
            case 'Enter':
            case ' ':
                e.preventDefault();
                if (document.activeElement.classList.contains('dropdown-item')) {
                    document.activeElement.click();
                }
                break;
        }
    }
    
    // Créer l'overlay
    function createOverlay() {
        const overlay = document.createElement('div');
        overlay.className = 'dropdown-overlay';
        document.body.appendChild(overlay);
        return overlay;
    }
    
    // Initialiser au chargement du DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDropdown);
    } else {
        initDropdown();
    }
})();
