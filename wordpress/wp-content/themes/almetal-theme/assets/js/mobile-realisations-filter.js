/**
 * Filtrage des réalisations mobile
 * Basé sur le système desktop avec limitation à 3 cartes
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

jQuery(document).ready(function($) {
    console.log('🔍 Init filtrage réalisations mobile (menu déroulant)');
    
    const filterSelect = $('#mobile-realisations-select');
    const cards = $('.mobile-realisation-card');
    
    console.log('Menu déroulant:', filterSelect.length);
    console.log('Cartes:', cards.length);
    
    if (!filterSelect.length || !cards.length) {
        console.warn('⚠️ Éléments de filtrage non trouvés');
        return;
    }
    
    // Initialisation : afficher seulement les 3 premières cartes
    cards.each(function(index) {
        if (index >= 3) {
            $(this).hide();
        }
    });
    
    // Gestionnaire de changement sur le menu déroulant
    filterSelect.on('change', function() {
        const filter = $(this).val();
        console.log('🎯 Filtre sélectionné:', filter);
        
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
            }, 500);
        }, 50);
    });
    
    console.log('✅ Filtrage réalisations mobile initialisé (menu déroulant)');
});
