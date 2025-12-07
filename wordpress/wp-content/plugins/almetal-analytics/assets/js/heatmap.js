/**
 * AL Métallerie - Heatmap Tracker
 */

(function() {
    'use strict';

    // Attendre le consentement
    if (!window.AlmetalConsent?.hasConsent('analytics')) {
        window.addEventListener('consentUpdated', function(e) {
            if (e.detail.categories.analytics) {
                initHeatmap();
            }
        });
        return;
    }

    initHeatmap();

    function initHeatmap() {
        const config = window.almetalAnalytics || {};
        const restUrl = config.restUrl;
        
        if (!restUrl || !config.heatmapEnabled) return;

        // Throttle pour éviter trop de requêtes
        let lastClick = 0;
        const throttleMs = 100;

        // Détecter le type d'appareil
        function getDeviceType() {
            const ua = navigator.userAgent;
            if (/tablet|ipad/i.test(ua)) return 'tablet';
            if (/mobile|android|iphone/i.test(ua)) return 'mobile';
            return 'desktop';
        }

        // Obtenir le sélecteur CSS d'un élément
        function getSelector(el) {
            if (!el || el === document.body) return 'body';
            
            if (el.id) return '#' + el.id;
            
            let selector = el.tagName.toLowerCase();
            
            if (el.className && typeof el.className === 'string') {
                const classes = el.className.trim().split(/\s+/).slice(0, 2);
                if (classes.length) {
                    selector += '.' + classes.join('.');
                }
            }
            
            return selector;
        }

        // Track click
        function trackClick(e) {
            const now = Date.now();
            if (now - lastClick < throttleMs) return;
            lastClick = now;

            const x = e.pageX;
            const y = e.pageY;
            const selector = getSelector(e.target);

            // Envoyer via beacon pour ne pas bloquer
            const data = JSON.stringify({
                url: window.location.pathname,
                x: x,
                y: y,
                viewport_width: window.innerWidth,
                viewport_height: window.innerHeight,
                selector: selector,
                device_type: getDeviceType()
            });

            // Utiliser fetch avec keepalive
            fetch(restUrl + 'track/heatmap', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: data,
                keepalive: true
            }).catch(() => {});
        }

        // Écouter les clics
        document.addEventListener('click', trackClick, { passive: true });
    }
})();
