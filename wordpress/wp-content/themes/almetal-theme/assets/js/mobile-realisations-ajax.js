/**
 * Filtrage AJAX des réalisations mobile avec pagination "Voir plus"
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

(function() {
    'use strict';

    // Variables globales
    let currentPage = 1;
    let currentCategory = '*';
    let isLoading = false;
    let hasMore = true;

    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('mobile-realisations-ajax');
        
        if (!container) {
            return;
        }

        const ajaxUrl = container.dataset.ajaxUrl;
        const filterSelect = document.getElementById('mobile-realisations-select');
        const grid = document.getElementById('mobile-realisations-grid');
        const loader = container.querySelector('.mobile-realisations-loader');
        const emptyMessage = document.getElementById('mobile-realisations-empty');
        const loadMoreWrapper = document.getElementById('mobile-realisations-load-more');
        const loadMoreBtn = document.getElementById('btn-load-more-realisations');

        console.log('🎨 Filtrage AJAX réalisations mobile initialisé');

        // Charger les premières réalisations au démarrage
        loadRealisations(true);

        // Événement de changement de filtre
        if (filterSelect) {
            filterSelect.addEventListener('change', function() {
                currentCategory = this.value;
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
            
            // Masquer le message vide
            if (emptyMessage) {
                emptyMessage.style.display = 'none';
            }

            // Si on remplace, vider la grille
            if (replace && grid) {
                grid.innerHTML = '';
            }

            // Préparer les données
            const formData = new FormData();
            formData.append('action', 'load_mobile_realisations');
            formData.append('category', currentCategory === '*' ? '' : currentCategory);
            formData.append('page', currentPage);

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

                if (data.success) {
                    const html = data.data.html;
                    hasMore = data.data.has_more;

                    if (html) {
                        // Ajouter le contenu
                        if (grid) {
                            grid.insertAdjacentHTML('beforeend', html);
                            
                            // Animation d'apparition des nouvelles cards
                            const newCards = grid.querySelectorAll('.mobile-realisation-card:not(.loaded)');
                            newCards.forEach((card, index) => {
                                card.classList.add('loaded');
                                card.style.opacity = '0';
                                card.style.transform = 'translateY(20px)';
                                
                                setTimeout(() => {
                                    card.style.transition = 'all 0.4s ease';
                                    card.style.opacity = '1';
                                    card.style.transform = 'translateY(0)';
                                }, index * 100);
                            });
                        }

                        // Afficher/masquer le bouton "Voir plus"
                        if (loadMoreWrapper) {
                            loadMoreWrapper.style.display = hasMore ? 'flex' : 'none';
                        }
                    } else if (replace) {
                        // Aucun résultat
                        if (emptyMessage) {
                            emptyMessage.style.display = 'flex';
                        }
                        if (loadMoreWrapper) {
                            loadMoreWrapper.style.display = 'none';
                        }
                    }

                    console.log('✅ Réalisations chargées:', data.data.total, 'total, page', data.data.current_page);
                } else {
                    console.error('❌ Erreur AJAX:', data);
                }
            })
            .catch(error => {
                isLoading = false;
                if (loader) {
                    loader.style.display = 'none';
                }
                console.error('❌ Erreur réseau:', error);
            });
        }
    });

})();
