/**
 * Force le style mobile même sur desktop responsive
 * Utilise CSS et JavaScript pour harmoniser l'apparence
 */
(function() {
    'use strict';
    
    // Fonction pour détecter si on doit être en mode mobile
    function shouldBeMobile() {
        // Vérifier si le paramètre force_mobile est déjà présent
        if (window.location.search.indexOf('force_mobile=1') !== -1) {
            return true;
        }
        
        // Vérifier la taille d'écran
        if (window.innerWidth <= 768) {
            return true;
        }
        
        // Vérifier user-agent mobile
        var userAgent = navigator.userAgent || navigator.vendor || window.opera;
        var mobileAgents = [
            'Mobile', 'Android', 'iPhone', 'iPad', 'iPod', 'BlackBerry',
            'Opera Mini', 'IEMobile', 'Mobile Safari', 'Chrome Mobile',
            'webOS', 'Windows Phone'
        ];
        
        for (var i = 0; i < mobileAgents.length; i++) {
            if (userAgent.indexOf(mobileAgents[i]) !== -1) {
                return true;
            }
        }
        
        return false;
    }
    
    // Fonction pour appliquer le style mobile
    function applyMobileStyles() {
        if (shouldBeMobile() && !document.body.classList.contains('mobile-applied')) {
            console.log('Application du style mobile...');
            
            // Ajouter les classes CSS pour le style mobile
            document.body.classList.add('mobile-version');
            document.body.classList.add('force-mobile');
            document.body.classList.add('is-mobile');
            document.body.classList.add('mobile-view');
            document.body.classList.add('one-page-layout');
            document.body.classList.add('mobile-applied');
            
            // Charger le CSS mobile si pas déjà présent
            if (!document.querySelector('link[href*="mobile-unified"]')) {
                var mobileCSS = document.createElement('link');
                mobileCSS.rel = 'stylesheet';
                mobileCSS.href = '/wp-content/themes/almetal/assets/css/mobile-unified.css';
                document.head.appendChild(mobileCSS);
            }
            
            // Cacher les éléments desktop et montrer les éléments mobiles
            hideDesktopElements();
            showMobileElements();
            
            // Initialiser le burger mobile
            initMobileBurger();
            
            // Appliquer les styles mobiles au header
            applyMobileHeaderStyles();
        }
    }
    
    // Fonction pour cacher les éléments desktop
    function hideDesktopElements() {
        var desktopElements = document.querySelectorAll('.desktop-only, .desktop-nav, .main-navigation, .desktop-hero, .desktop-section');
        for (var i = 0; i < desktopElements.length; i++) {
            desktopElements[i].style.display = 'none';
        }
    }
    
    // Fonction pour montrer les éléments mobiles
    function showMobileElements() {
        var mobileElements = document.querySelectorAll('.mobile-only, .mobile-section, .mobile-hero');
        for (var i = 0; i < mobileElements.length; i++) {
            mobileElements[i].style.display = 'block';
        }
    }
    
    // Fonction pour appliquer les styles au header
    function applyMobileHeaderStyles() {
        var header = document.querySelector('header');
        if (header) {
            header.classList.add('mobile-header');
            // Créer un burger si nécessaire
            if (!document.getElementById('mobile-burger-btn')) {
                createMobileBurger();
            }
        }
    }
    
    // Fonction pour créer le burger menu
    function createMobileBurger() {
        var header = document.querySelector('header');
        if (!header) return;
        
        var burger = document.createElement('button');
        burger.id = 'mobile-burger-btn';
        burger.className = 'mobile-burger-btn';
        burger.innerHTML = '<span class="mobile-burger-line"></span><span class="mobile-burger-line"></span><span class="mobile-burger-line"></span>';
        
        // Ajouter les styles inline pour le burger
        burger.style.cssText = `
            width: 40px;
            height: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 4px;
            background: transparent;
            border: none;
            cursor: pointer;
            z-index: 999999;
            position: fixed;
            top: 15px;
            right: 15px;
            padding: 0;
        `;
        
        header.appendChild(burger);
        
        // Créer l'overlay
        var overlay = document.createElement('div');
        overlay.id = 'mobile-menu-overlay';
        overlay.className = 'mobile-menu-overlay';
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.95);
            z-index: 999998;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        `;
        
        document.body.appendChild(overlay);
        
        // Ajouter le menu dans l'overlay
        var menuHTML = `
            <div class="mobile-menu-content" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                <a href="/" style="color: white; font-size: 24px; display: block; margin: 20px 0;">Accueil</a>
                <a href="/realisations" style="color: white; font-size: 24px; display: block; margin: 20px 0;">Réalisations</a>
                <a href="/contact" style="color: white; font-size: 24px; display: block; margin: 20px 0;">Contact</a>
            </div>
        `;
        overlay.innerHTML = menuHTML;
        
        // Initialiser le burger
        initMobileBurger();
    }
    
    // Initialiser le burger mobile
    function initMobileBurger() {
        setTimeout(function() {
            var burgerBtn = document.getElementById('mobile-burger-btn');
            var overlay = document.getElementById('mobile-menu-overlay');
            
            if (burgerBtn && overlay) {
                burgerBtn.addEventListener('click', function() {
                    this.classList.toggle('active');
                    overlay.classList.toggle('active');
                    
                    if (overlay.classList.contains('active')) {
                        overlay.style.opacity = '1';
                        overlay.style.visibility = 'visible';
                    } else {
                        overlay.style.opacity = '0';
                        overlay.style.visibility = 'hidden';
                    }
                });
                
                // Fermer au clic sur l'overlay
                overlay.addEventListener('click', function(e) {
                    if (e.target === overlay) {
                        burgerBtn.classList.remove('active');
                        overlay.classList.remove('active');
                        overlay.style.opacity = '0';
                        overlay.style.visibility = 'hidden';
                    }
                });
            }
        }, 100);
    }
    
    // Exécuter au chargement et au redimensionnement
    document.addEventListener('DOMContentLoaded', applyMobileStyles);
    window.addEventListener('resize', function() {
        if (window.innerWidth <= 768) {
            applyMobileStyles();
        }
    });
    
    // Exécuter immédiatement si le DOM est déjà chargé
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', applyMobileStyles);
    } else {
        applyMobileStyles();
    }
})();
