/**
 * Filtrage simple des réalisations mobile (côté client)
 * Pour la one-page avec 3 réalisations
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        const filterSelect = document.getElementById('mobile-realisations-select');
        
        if (!filterSelect) {
            return;
        }

        console.log('🎨 Filtrage réalisations mobile initialisé');

        filterSelect.addEventListener('change', function() {
            const selectedValue = this.value;
            const cards = document.querySelectorAll('.mobile-realisation-card');
            
            console.log('Filtre sélectionné:', selectedValue);

            cards.forEach(function(card) {
                if (selectedValue === '*') {
                    // Afficher toutes les cards
                    card.style.display = 'block';
                } else {
                    // Vérifier si la card a la catégorie sélectionnée
                    const categories = card.dataset.categories || '';
                    const categorySlug = selectedValue.replace('.', '');
                    
                    if (categories.includes(categorySlug)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                }
            });
        });
    });

})();
