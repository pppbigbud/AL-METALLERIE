/**
 * Rendre les cards de réalisations entièrement cliquables sur mobile
 */

document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si on est sur mobile
    function isMobile() {
        return window.innerWidth <= 768 || /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }
    
    if (isMobile()) {
        // Rendre toutes les cards de réalisations cliquables
        const cards = document.querySelectorAll('.realisation-card');
        
        cards.forEach(function(card) {
            // Récupérer le lien principal (premier lien dans la card)
            const mainLink = card.querySelector('a[href]');
            
            if (mainLink) {
                // Ajouter un cursor pointer sur toute la card
                card.style.cursor = 'pointer';
                
                // Ajouter un effet au touch/click
                card.addEventListener('click', function(e) {
                    // Ne pas suivre le lien si on clique sur un autre lien ou bouton
                    if (e.target.tagName === 'A' || 
                        e.target.closest('a') || 
                        e.target.tagName === 'BUTTON' ||
                        e.target.closest('button')) {
                        return;
                    }
                    
                    // Empêcher les liens dans les badges de déclencher le clic de la card
                    if (e.target.closest('.category-badge') || 
                        e.target.closest('.meta-matiere-link') ||
                        e.target.closest('.meta-lieu-link')) {
                        return;
                    }
                    
                    // Naviguer vers le lien principal
                    window.location.href = mainLink.href;
                });
                
                // Effet visuel au touch (feedback)
                card.addEventListener('touchstart', function() {
                    this.style.transform = 'scale(0.98)';
                    this.style.transition = 'transform 0.1s ease';
                });
                
                card.addEventListener('touchend', function() {
                    this.style.transform = 'scale(1)';
                });
            }
        });
        
        // Gérer les cards dans d'autres contextes (page d'accueil, etc.)
        const homeCards = document.querySelectorAll('.home-realisation-card, .recent-realisation-card, .featured-realisation-card');
        
        homeCards.forEach(function(card) {
            const link = card.querySelector('a[href]');
            if (link) {
                card.style.cursor = 'pointer';
                
                card.addEventListener('click', function(e) {
                    // Ne pas suivre si on clique sur un lien existant
                    if (e.target.tagName === 'A' || e.target.closest('a')) {
                        return;
                    }
                    
                    window.location.href = link.href;
                });
                
                // Effet visuel
                card.addEventListener('touchstart', function() {
                    this.style.opacity = '0.9';
                    this.style.transition = 'opacity 0.1s ease';
                });
                
                card.addEventListener('touchend', function() {
                    this.style.opacity = '1';
                });
            }
        });
    }
});
