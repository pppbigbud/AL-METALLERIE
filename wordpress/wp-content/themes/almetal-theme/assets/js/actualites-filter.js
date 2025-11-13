/**
 * Système de filtrage pour la section actualités
 * Utilise jQuery comme la page Réalisations pour une compatibilité maximale
 */

jQuery(document).ready(function($) {
    console.log('🔍 Initialisation du filtrage actualités');
    
    const filterBtns = $('.actualites-filters-desktop .filter-btn');
    const filterSelect = $('#actualites-filter-select');
    // Sélecteur plus spécifique pour éviter les conflits
    const cards = $('.actualites-section .actualites-grid .realisation-card');
    
    console.log('Nombre de boutons de filtre:', filterBtns.length);
    console.log('Nombre de cartes:', cards.length);
    
    if (!filterBtns.length || !cards.length) {
        console.warn('⚠️ Éléments de filtrage non trouvés');
        return;
    }
    
    // Initialisation : afficher seulement les 3 premières cartes
    cards.each(function(index) {
        if (index >= 3) {
            $(this).hide();
        }
    });
    
    // Gestionnaire pour les boutons (desktop)
    filterBtns.on('click', function() {
        const filter = $(this).data('filter');
        console.log('🎯 Filtre cliqué:', filter);
        
        // Mettre à jour les boutons actifs
        filterBtns.removeClass('active');
        $(this).addClass('active');
        
        // STOP toutes les animations en cours et masquer immédiatement
        cards.stop(true, true).hide().removeClass('is-visible');
        
        // Afficher les cartes filtrées
        setTimeout(function() {
            let visibleCards = [];
            const filterClass = filter.replace('.', '');
            
            if (filter === '*') {
                // Afficher toutes les cartes (limitées aux 3 premières)
                visibleCards = cards.slice(0, 3);
            } else {
                // Filtrer manuellement par catégorie et limiter à 3
                for (let i = 0; i < cards.length && visibleCards.length < 3; i++) {
                    const $card = $(cards[i]);
                    const categories = $card.attr('data-categories') || '';
                    const classList = $card.attr('class') || '';
                    
                    // Vérifier si la carte contient la catégorie
                    if (categories.includes(filterClass) || classList.includes(filterClass)) {
                        visibleCards.push($card);
                    }
                }
            }
            
            console.log('Cartes à afficher:', visibleCards.length);
            
            // Debug : afficher les index des cartes sélectionnées
            $.each(visibleCards, function(index, $card) {
                const cardIndex = cards.index($card);
                console.log('  → Carte sélectionnée:', cardIndex + 1, '-', $card.attr('data-categories'));
            });
            
            // S'assurer que TOUTES les cartes sont masquées (stop animations)
            cards.stop(true, true).hide().removeClass('is-visible');
            
            // Animer l'apparition UNIQUEMENT des cartes filtrées
            $.each(visibleCards, function(index, $card) {
                setTimeout(function() {
                    $card.fadeIn(400).addClass('is-visible');
                }, index * 100);
            });
            
            // Log du résultat ET nettoyage final
            setTimeout(function() {
                // Forcer le masquage de toutes les cartes sauf celles sélectionnées
                cards.each(function() {
                    const $card = $(this);
                    let isSelected = false;
                    
                    $.each(visibleCards, function(i, $selectedCard) {
                        if ($card[0] === $selectedCard[0]) {
                            isSelected = true;
                            return false;
                        }
                    });
                    
                    if (!isSelected) {
                        $card.stop(true, true).hide().removeClass('is-visible');
                    }
                });
                
                const visibleCount = cards.filter(':visible').length;
                console.log('✨ Filtrage terminé -', visibleCount, 'cartes visibles');
                
                // Debug : afficher les index des cartes visibles
                cards.each(function(index) {
                    if ($(this).is(':visible')) {
                        console.log('  → Carte', index + 1, 'visible:', $(this).attr('data-categories'));
                    }
                });
            }, 500);
        }, 50);
    });
    
    // Gestionnaire pour le dropdown (mobile)
    if (filterSelect.length) {
        filterSelect.on('change', function() {
            const filter = $(this).val();
            console.log('📱 Filtre mobile sélectionné:', filter);
            
            // Déclencher le clic sur le bouton correspondant
            const correspondingBtn = filterBtns.filter('[data-filter="' + filter + '"]');
            if (correspondingBtn.length) {
                correspondingBtn.trigger('click');
            }
        });
    }
    
    console.log('✅ Filtrage actualités initialisé');
});
