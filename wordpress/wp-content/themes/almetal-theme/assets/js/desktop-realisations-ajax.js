/**
 * Filtrage et pagination AJAX des réalisations Desktop
 * Bouton "Voir plus" pour charger les réalisations suivantes
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

(function() {
    'use strict';

    // Variables globales
    let currentPage = 1;
    let currentCategory = ''; // Vide = toutes les catégories
    let isLoading = false;
    let hasMore = true;
    let perPage = 6;
    let totalRealisations = 0;

    document.addEventListener('DOMContentLoaded', function() {
        const grid = document.getElementById('desktop-realisations-grid');
        
        if (!grid) {
            return;
        }

        // Récupérer les données initiales
        currentPage = parseInt(grid.dataset.page) || 1;
        perPage = parseInt(grid.dataset.perPage) || 6;
        totalRealisations = parseInt(grid.dataset.total) || 0;
        hasMore = (currentPage * perPage) < totalRealisations;

        const filterBtns = document.querySelectorAll('.actualites-filters-desktop .filter-btn');
        const filterSelect = document.getElementById('actualites-filter-select');
        const loader = document.getElementById('desktop-realisations-loader');
        const loadMoreWrapper = document.getElementById('desktop-loadmore-wrapper');
        const loadMoreBtn = document.getElementById('btn-desktop-load-more');
        const ajaxUrl = loadMoreBtn ? loadMoreBtn.dataset.ajaxUrl : '';

        console.log('🎨 Filtrage Desktop réalisations initialisé');

        // Événement des boutons de filtre
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Mettre à jour l'état actif
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Récupérer la catégorie
                const filter = this.dataset.filter;
                currentCategory = filter === '*' ? '' : filter.replace('.', '');
                currentPage = 1;
                hasMore = true;
                
                // Recharger les réalisations
                loadRealisations(true);
            });
        });

        // Événement du select mobile
        if (filterSelect) {
            filterSelect.addEventListener('change', function() {
                const filter = this.value;
                currentCategory = filter === '*' ? '' : filter.replace('.', '');
                currentPage = 1;
                hasMore = true;
                loadRealisations(true);
            });
        }

        // Événement du bouton "Voir plus"
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function() {
                if (!isLoading && hasMore) {
                    currentPage++;
                    loadRealisations(false);
                }
            });
        }

        /**
         * Charger les réalisations via AJAX
         * @param {boolean} replace - Remplacer le contenu ou ajouter
         */
        function loadRealisations(replace) {
            if (isLoading) return;
            
            isLoading = true;
            
            // Afficher le loader
            if (loader) {
                loader.style.display = 'flex';
            }
            
            // Ajouter la classe loading au bouton
            if (loadMoreBtn) {
                loadMoreBtn.classList.add('loading');
            }

            // Si on remplace, vider la grille avec animation
            if (replace && grid) {
                grid.style.opacity = '0.5';
            }

            // Préparer les données
            const formData = new FormData();
            formData.append('action', 'load_desktop_realisations');
            formData.append('category', currentCategory);
            formData.append('page', currentPage);
            formData.append('per_page', perPage);

            // Requête AJAX
            fetch(ajaxUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                isLoading = false;
                
                // Masquer le loader
                if (loader) {
                    loader.style.display = 'none';
                }
                
                // Retirer la classe loading
                if (loadMoreBtn) {
                    loadMoreBtn.classList.remove('loading');
                }

                console.log('📦 Réponse AJAX:', data);
                
                if (data.success) {
                    const html = data.data.html;
                    hasMore = data.data.has_more;
                    const remaining = data.data.remaining;
                    
                    console.log('📄 HTML reçu:', html ? html.substring(0, 200) + '...' : 'VIDE');
                    console.log('📊 Has more:', hasMore, '| Remaining:', remaining);

                    if (html && html.trim() !== '') {
                        if (replace) {
                            // Remplacer le contenu
                            grid.innerHTML = html;
                            grid.style.opacity = '1';
                        } else {
                            // Ajouter le contenu
                            grid.insertAdjacentHTML('beforeend', html);
                        }
                        
                        // Animation d'apparition des nouvelles cards
                        const newCards = grid.querySelectorAll('.realisation-card:not(.loaded)');
                        newCards.forEach((card, index) => {
                            card.classList.add('loading-new');
                            
                            setTimeout(() => {
                                card.classList.remove('loading-new');
                                card.classList.add('loaded');
                            }, index * 100);
                        });
                    }

                    // Mettre à jour le bouton "Voir plus"
                    if (loadMoreWrapper) {
                        if (hasMore) {
                            loadMoreWrapper.style.display = 'flex';
                            const countSpan = loadMoreBtn.querySelector('.btn-load-more__count');
                            if (countSpan) {
                                countSpan.textContent = remaining + ' restantes';
                            }
                        } else {
                            loadMoreWrapper.style.display = 'none';
                        }
                    }

                    // Mettre à jour le data-page
                    grid.dataset.page = currentPage;

                    console.log('✅ Réalisations chargées:', data.data.total, 'total, page', currentPage);
                } else {
                    console.error('❌ Erreur AJAX:', data);
                    grid.style.opacity = '1';
                }
            })
            .catch(error => {
                isLoading = false;
                if (loader) {
                    loader.style.display = 'none';
                }
                if (loadMoreBtn) {
                    loadMoreBtn.classList.remove('loading');
                }
                grid.style.opacity = '1';
                console.error('❌ Erreur réseau:', error);
            });
        }
    });

})();
