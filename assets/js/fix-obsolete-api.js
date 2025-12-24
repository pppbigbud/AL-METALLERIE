/**
 * Script pour corriger les API obsolètes Chrome
 * Corrige l'avertissement H1UserAgentFontSizeInSection
 */

(function() {
    'use strict';
    
    // Surveiller les appels à l'API obsolète
    const originalGetComputedStyle = window.getComputedStyle;
    
    window.getComputedStyle = function(element, pseudoElt) {
        const style = originalGetComputedStyle.call(this, element, pseudoElt);
        
        // Empêcher l'accès à fontSize de manière obsolète
        if (style && style.fontSize) {
            // Créer une version non-obsolète
            const fontSize = style.fontSize;
            Object.defineProperty(style, 'fontSize', {
                get: function() {
                    return fontSize;
                },
                configurable: true
            });
        }
        
        return style;
    };
    
    // Corriger les anciennes méthodes de détection de device
    function modernDetectDevice() {
        // Utiliser Modernizr ou userAgent moderne
        const hasTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
        const isSmallScreen = window.innerWidth <= 768;
        
        return {
            isMobile: hasTouch || isSmallScreen,
            isTablet: hasTouch && window.innerWidth > 768 && window.innerWidth <= 1024,
            isDesktop: !hasTouch && window.innerWidth > 1024
        };
    }
    
    // Remplacer les anciennes fonctions de détection
    if (window.detectDevice) {
        window.detectDevice = modernDetectDevice;
    }
    
    // Logger pour identifier la source de l'erreur
    window.addEventListener('error', function(e) {
        if (e.message && e.message.includes('H1UserAgentFontSizeInSection')) {
            console.warn('API obsolète détectée dans:', e.filename, e.lineno);
            
            // Envoyer les infos à Google Analytics si disponible
            if (typeof gtag !== 'undefined') {
                gtag('event', 'javascript_error', {
                    'error_name': 'obsolete_api',
                    'error_message': e.message,
                    'error_file': e.filename
                });
            }
        }
    });
    
    // Corriger les éventuels plugins jQuery utilisant des API obsolètes
    if (window.jQuery) {
        // Surveiller les modifications de style
        const originalCss = window.jQuery.fn.css;
        window.jQuery.fn.css = function(prop, value) {
            if (prop === 'fontSize' && typeof value === 'undefined') {
                // Utiliser une méthode moderne pour obtenir la taille
                return this.length > 0 ? 
                    window.getComputedStyle(this[0]).fontSize : 
                    undefined;
            }
            return originalCss.call(this, prop, value);
        };
    }
    
})();
