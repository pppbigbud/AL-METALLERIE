/**
 * Remplacement moderne de la détection de device
 * Utilise des API modernes au lieu de userAgent obsolète
 */

(function(window) {
    'use strict';
    
    // Détection moderne de device sans utiliser les API obsolètes
    const DeviceDetection = {
        // Vérifier si c'est un appareil tactile
        isTouch: function() {
            return 'ontouchstart' in window || 
                   navigator.maxTouchPoints > 0 || 
                   navigator.msMaxTouchPoints > 0;
        },
        
        // Vérifier si c'est un mobile
        isMobile: function() {
            return this.isTouch() && window.innerWidth <= 768;
        },
        
        // Vérifier si c'est une tablette
        isTablet: function() {
            return this.isTouch() && window.innerWidth > 768 && window.innerWidth <= 1024;
        },
        
        // Vérifier si c'est un desktop
        isDesktop: function() {
            return !this.isTouch() || window.innerWidth > 1024;
        },
        
        // Obtenir le type de device
        getDeviceType: function() {
            if (this.isMobile()) return 'mobile';
            if (this.isTablet()) return 'tablet';
            return 'desktop';
        }
    };
    
    // Remplacer l'ancienne fonction detectDevice si elle existe
    if (window.detectDevice) {
        console.warn('Remplacement de l ancienne fonction detectDevice');
        window.detectDevice = function() {
            return DeviceDetection.getDeviceType() === 'mobile';
        };
    }
    
    // Exporter la nouvelle API
    window.DeviceDetection = DeviceDetection;
    
    // Corriger les éventuels appels à fontSize obsolètes
    const originalQuerySelector = document.querySelector;
    document.querySelector = function(selector) {
        // Éviter les sélecteurs problématiques
        if (selector && selector.includes('fontSize')) {
            console.warn('Sélecteur fontSize détecté, utilisation d une méthode moderne');
        }
        return originalQuerySelector.call(this, selector);
    };
    
})(window);
