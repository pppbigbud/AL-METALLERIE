/**
 * Filtrage des réalisations sur la page archive (Mobile)
 * Utilise le select #mobile-archive-filter-select
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        console.log('📱 Mobile archive filter script loaded');
        
        initArchiveFilter();
    });

    function initArchiveFilter() {
        // Select de filtrage mobile
        const filterSelect = document.getElementById('mobile-archive-filter-select');
        // Cards de réalisations
        const realisationCards = document.querySelectorAll('.mobile-archive-grid .mobile-realisation-card');

        console.log('Filter select found:', !!filterSelect);
        console.log('Realisation cards found:', realisationCards.length);

        if (!filterSelect || !realisationCards.length) {
            console.log('No filter elements found');
            return;
        }

        filterSelect.addEventListener('change', function() {
            const selectedValue = this.value;
            
            console.log('Filter selected:', selectedValue);

            // Filtrer les cards
            realisationCards.forEach(function(card) {
                if (selectedValue === '*') {
                    // Afficher toutes les cards
                    card.style.display = 'block';
                    card.style.opacity = '1';
                } else {
                    // Vérifier si la card a la catégorie sélectionnée
                    // selectedValue est au format ".portails", on enlève le point
                    const categorySlug = selectedValue.replace('.', '');
                    const cardCategories = card.dataset.categories || '';
                    
                    // Vérifier si la catégorie est dans les données de la card
                    if (cardCategories.includes(categorySlug) || card.classList.contains(categorySlug)) {
                        card.style.display = 'block';
                        card.style.opacity = '1';
                    } else {
                        card.style.display = 'none';
                        card.style.opacity = '0';
                    }
                }
            });

            console.log('Filtering complete');
        });

        console.log('✅ Mobile archive filter initialized');
    }

})();
