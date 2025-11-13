/**
 * MENU BURGER MOBILE - Version propre et simple
 * 
 * @package ALMetallerie
 * @since 2.0.0
 */

(function() {
    'use strict';

    // Attendre que le DOM soit chargé
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🍔 Menu Burger - Initialisation');

        // Sélectionner les éléments
        // IMPORTANT : Il y a plusieurs éléments en double dans le DOM, on prend les DERNIERS (les visibles)
        const allBurgers = document.querySelectorAll('#mobile-burger-btn');
        const burger = allBurgers[allBurgers.length - 1]; // Prendre le dernier
        
        const allMenus = document.querySelectorAll('#mobile-menu');
        const menu = allMenus[allMenus.length - 1]; // Prendre le dernier
        
        const allOverlays = document.querySelectorAll('#mobile-menu-overlay');
        const overlay = allOverlays.length > 0 ? allOverlays[allOverlays.length - 1] : null;
        
        console.log('⚠️ Nombre de burgers trouvés:', allBurgers.length);
        console.log('⚠️ Nombre de menus trouvés:', allMenus.length);
        console.log('⚠️ Nombre d\'overlays trouvés:', allOverlays.length);
        
        if (allBurgers.length > 1 || allMenus.length > 1) {
            console.log('⚠️ ATTENTION : Éléments en double détectés ! Utilisation des derniers.');
        }

        // Vérifier que tout existe
        if (!burger) {
            console.log('❌ Burger non trouvé');
            return;
        }

        if (!menu) {
            console.log('❌ Menu non trouvé');
            return;
        }

        console.log('✅ Éléments trouvés');

        // Fonction pour ouvrir le menu
        function openMenu() {
            burger.classList.add('active');
            menu.classList.add('active');
            if (overlay) {
                overlay.classList.add('active');
            }
            burger.setAttribute('aria-expanded', 'true');
            console.log('📂 Menu ouvert');
        }

        // Fonction pour fermer le menu
        function closeMenu() {
            burger.classList.remove('active');
            menu.classList.remove('active');
            if (overlay) {
                overlay.classList.remove('active');
            }
            burger.setAttribute('aria-expanded', 'false');
            console.log('📁 Menu fermé');
        }

        // Toggle menu
        function toggleMenu() {
            if (menu.classList.contains('active')) {
                closeMenu();
            } else {
                openMenu();
            }
        }

        // Clic sur le burger
        burger.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('🖱️ Clic burger');
            toggleMenu();
        });

        // Clic sur l'overlay pour fermer
        if (overlay) {
            overlay.addEventListener('click', function() {
                console.log('🖱️ Clic overlay');
                closeMenu();
            });
        }

        // Clic sur les liens du menu pour fermer
        const menuLinks = menu.querySelectorAll('a');
        menuLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                console.log('🖱️ Clic lien menu');
                closeMenu();
            });
        });

        // Fermer avec la touche Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && menu.classList.contains('active')) {
                console.log('⌨️ Touche Escape');
                closeMenu();
            }
        });

        console.log('✅ Menu Burger initialisé');
    });

})();
