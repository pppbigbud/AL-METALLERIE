/**
 * Détection mobile côté client pour contourner le cache serveur
 * Ce script s'exécute immédiatement pour forcer l'affichage mobile si nécessaire
 */
(function() {
    'use strict';
    
    // Détection mobile améliorée
    function isMobileDevice() {
        var userAgent = navigator.userAgent || navigator.vendor || window.opera;
        
        // Liste des agents mobiles
        var mobileAgents = [
            'Mobile', 'Android', 'iPhone', 'iPad', 'iPod', 'BlackBerry',
            'Opera Mini', 'IEMobile', 'Mobile Safari', 'Chrome Mobile',
            'webOS', 'Windows Phone'
        ];
        
        // Vérifier si un agent mobile est dans le user agent
        for (var i = 0; i < mobileAgents.length; i++) {
            if (userAgent.indexOf(mobileAgents[i]) !== -1) {
                return true;
            }
        }
        
        // Vérifier la taille d'écran comme fallback
        return window.innerWidth <= 768;
    }
    
    // Si on est sur mobile OU si la fenêtre est petite, mais que la page desktop est chargée
    if ((isMobileDevice() || window.innerWidth <= 768) && !document.body.classList.contains('mobile-version')) {
        console.log('Détection mobile client - Redirection vers version mobile');
        
        // Ajouter un paramètre pour forcer le mode mobile
        var url = new URL(window.location);
        url.searchParams.set('force_mobile', '1');
        
        // Recharger la page avec le paramètre mobile
        window.location.href = url.toString();
    }
    
    // Si on a le paramètre force_mobile, ajouter la classe mobile
    if (window.location.search.indexOf('force_mobile=1') !== -1) {
        document.body.classList.add('mobile-version');
        document.body.classList.add('force-mobile');
        
        // Sauvegarder en sessionStorage pour éviter les rechargements
        sessionStorage.setItem('force_mobile', '1');
    }
    
    // Vérifier sessionStorage au chargement
    if (sessionStorage.getItem('force_mobile') === '1') {
        document.body.classList.add('mobile-version');
        document.body.classList.add('force-mobile');
    }
})();
