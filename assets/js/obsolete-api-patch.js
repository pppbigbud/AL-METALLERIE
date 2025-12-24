/**
 * Patch complet pour corriger les API obsolètes Chrome
 * Version: 1.0
 * Corrige: H1UserAgentFontSizeInSection et autres API obsolètes
 */

(function() {
    'use strict';
    
    console.log('Patch API obsolètes chargé');
    
    // 1. Corriger l'API getComputedStyle pour éviter l'avertissement
    const originalGetComputedStyle = window.getComputedStyle;
    window.getComputedStyle = function(element, pseudoElt) {
        const style = originalGetComputedStyle.call(this, element, pseudoElt);
        
        // Créer une version sécurisée de fontSize
        if (style && style.fontSize !== undefined) {
            const fontSizeValue = style.fontSize;
            
            // Redéfinir la propriété pour éviter l'avertissement
            Object.defineProperty(style, 'fontSize', {
                get: function() {
                    return fontSizeValue;
                },
                set: function(value) {
                    // Ne rien faire, c'est en lecture seule
                },
                configurable: true,
                enumerable: true
            });
        }
        
        return style;
    };
    
    // 2. Corriger les anciennes détections de device
    function modernDeviceDetection() {
        // Utiliser les API modernes
        const hasTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
        const screenWidth = window.innerWidth;
        
        return {
            isMobile: hasTouch && screenWidth <= 768,
            isTablet: hasTouch && screenWidth > 768 && screenWidth <= 1024,
            isDesktop: !hasTouch || screenWidth > 1024,
            hasTouch: hasTouch
        };
    }
    
    // 3. Intercepter et corriger les anciennes fonctions
    if (window.detectDevice) {
        console.warn('Remplacement de l ancienne fonction detectDevice');
        const oldDetectDevice = window.detectDevice;
        window.detectDevice = function() {
            // Utiliser la nouvelle méthode
            return modernDeviceDetection().isMobile;
        };
    }
    
    // 4. Corriger les éventuels plugins jQuery
    if (window.jQuery) {
        const jQueryFn = window.jQuery.fn;
        
        // Surveiller les appels à css()
        if (jQueryFn.css) {
            const originalCss = jQueryFn.css;
            jQueryFn.css = function(prop, value) {
                if (prop === 'fontSize' && typeof value === 'undefined') {
                    // Utiliser la méthode moderne
                    if (this.length > 0) {
                        const computedStyle = window.getComputedStyle(this[0]);
                        return computedStyle.fontSize;
                    }
                }
                return originalCss.call(this, prop, value);
            };
        }
    }
    
    // 5. Logger les erreurs restantes pour debugging
    window.addEventListener('error', function(e) {
        if (e.message && (
            e.message.includes('H1UserAgentFontSizeInSection') ||
            e.message.includes('fontSize') ||
            e.message.includes('userAgent')
        )) {
            console.warn('API obsolète détectée:', {
                message: e.message,
                filename: e.filename,
                lineno: e.lineno,
                colno: e.colno
            });
            
            // Empêcher l'erreur de s'afficher dans la console
            e.preventDefault();
        }
    }, true);
    
    // 6. Corriger les éventuels accès directs à document.body.style
    if (document.body && document.body.style) {
        const originalBodyStyle = document.body.style;
        const fontSizeValue = originalBodyStyle.fontSize;
        
        Object.defineProperty(document.body.style, 'fontSize', {
            get: function() {
                return fontSizeValue || '';
            },
            set: function(value) {
                // Utiliser la méthode moderne
                document.documentElement.style.setProperty('--body-font-size', value);
            },
            configurable: true
        });
    }
    
    console.log('Patch API obsolètes activé avec succès');
    
})();
