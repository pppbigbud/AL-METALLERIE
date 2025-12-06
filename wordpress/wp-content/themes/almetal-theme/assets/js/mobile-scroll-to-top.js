/**
 * Bouton Scroll to Top - Mobile
 * 
 * Gère l'affichage et le comportement du bouton de retour en haut
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

(function() {
    'use strict';

    // Attendre que le DOM soit chargé
    document.addEventListener('DOMContentLoaded', function() {
        const scrollBtn = document.getElementById('scroll-to-top');
        
        if (!scrollBtn) {
            return;
        }

        // Afficher/masquer le bouton selon le scroll
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollBtn.classList.add('visible');
            } else {
                scrollBtn.classList.remove('visible');
            }
        });

        // Scroll vers le haut au clic
        scrollBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Scroll fluide vers le haut
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });

})();
