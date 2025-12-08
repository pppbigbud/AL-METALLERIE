/**
 * AL Métallerie - Analytics Tracker
 * Tracking anonymisé RGPD compliant
 */

(function() {
    'use strict';

    // Attendre le consentement
    if (!window.AlmetalConsent?.hasConsent('analytics')) {
        window.addEventListener('consentUpdated', function(e) {
            if (e.detail.categories.analytics) {
                initTracker();
            }
        });
        return;
    }

    initTracker();

    function initTracker() {
        const config = window.almetalAnalytics || {};
        const restUrl = config.restUrl;
        
        if (!restUrl) return;

        // Générer ou récupérer les IDs
        let visitorId = localStorage.getItem('almetal_visitor_id');
        if (!visitorId) {
            visitorId = 'v_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            localStorage.setItem('almetal_visitor_id', visitorId);
        }

        let sessionId = sessionStorage.getItem('almetal_session_id');
        if (!sessionId) {
            sessionId = 's_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            sessionStorage.setItem('almetal_session_id', sessionId);
        }

        let visitId = null;
        let startTime = Date.now();
        let maxScroll = 0;

        // Track la visite
        async function trackVisit() {
            try {
                const response = await fetch(restUrl + 'track/visit', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        visitor_id: visitorId,
                        session_id: sessionId,
                        url: window.location.href,
                        title: document.title,
                        referrer: document.referrer,
                        screen_width: window.screen.width,
                        screen_height: window.screen.height,
                        viewport_width: window.innerWidth,
                        viewport_height: window.innerHeight
                    })
                });
                const data = await response.json();
                if (data.success) {
                    visitId = data.visit_id;
                }
            } catch (e) {
                console.warn('Analytics tracking error:', e);
            }
        }

        // Track scroll depth
        function trackScroll() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const docHeight = Math.max(
                document.body.scrollHeight,
                document.documentElement.scrollHeight
            ) - window.innerHeight;
            
            if (docHeight > 0) {
                const scrollPercent = Math.round((scrollTop / docHeight) * 100);
                if (scrollPercent > maxScroll) {
                    maxScroll = scrollPercent;
                }
            }
        }

        // Update visit on leave
        async function updateVisit() {
            if (!visitId) return;
            
            const duration = Math.round((Date.now() - startTime) / 1000);
            const pageViews = parseInt(sessionStorage.getItem('almetal_page_views') || '0');
            
            // Utiliser FormData pour sendBeacon (compatible avec REST API)
            const formData = new FormData();
            formData.append('visit_id', visitId);
            formData.append('duration', duration);
            formData.append('scroll_depth', maxScroll);
            formData.append('is_bounce', pageViews <= 1 ? 1 : 0);
            
            // Essayer fetch d'abord, puis sendBeacon en fallback
            try {
                await fetch(restUrl + 'track/update', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        visit_id: visitId,
                        duration: duration,
                        scroll_depth: maxScroll,
                        is_bounce: pageViews <= 1 ? 1 : 0
                    }),
                    keepalive: true
                });
            } catch (e) {
                // Fallback: sendBeacon avec Blob JSON
                const blob = new Blob([JSON.stringify({
                    visit_id: visitId,
                    duration: duration,
                    scroll_depth: maxScroll,
                    is_bounce: pageViews <= 1 ? 1 : 0
                })], { type: 'application/json' });
                navigator.sendBeacon(restUrl + 'track/update', blob);
            }
        }

        // Track events
        window.almetalTrackEvent = function(category, action, label, value) {
            if (!window.AlmetalConsent?.hasConsent('analytics')) return;
            
            fetch(restUrl + 'track/event', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    visit_id: visitId,
                    visitor_id: visitorId,
                    session_id: sessionId,
                    event_type: 'custom',
                    category: category,
                    action: action,
                    label: label,
                    value: value
                })
            }).catch(() => {});
        };

        // Increment page views
        const pageViews = parseInt(sessionStorage.getItem('almetal_page_views') || '0') + 1;
        sessionStorage.setItem('almetal_page_views', pageViews.toString());

        // Event listeners
        window.addEventListener('scroll', trackScroll, { passive: true });
        window.addEventListener('beforeunload', updateVisit);
        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'hidden') {
                updateVisit();
            }
        });
        
        // Heartbeat pour maintenir la session active (toutes les 30 secondes)
        setInterval(function() {
            if (document.visibilityState === 'visible' && visitId) {
                const duration = Math.round((Date.now() - startTime) / 1000);
                fetch(restUrl + 'track/update', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        visit_id: visitId,
                        duration: duration,
                        scroll_depth: maxScroll,
                        is_bounce: parseInt(sessionStorage.getItem('almetal_page_views') || '0') <= 1 ? 1 : 0
                    }),
                    keepalive: true
                }).catch(() => {});
            }
        }, 30000);

        // Auto-track clicks on important elements
        document.addEventListener('click', function(e) {
            const target = e.target.closest('a, button, [data-track]');
            if (!target) return;

            const trackData = target.dataset.track;
            if (trackData) {
                const [category, action, label] = trackData.split('|');
                window.almetalTrackEvent(category, action, label);
                return;
            }

            // Track CTA clicks
            if (target.classList.contains('hero-cta') || 
                target.classList.contains('btn-contact') ||
                target.classList.contains('btn-devis')) {
                window.almetalTrackEvent('CTA', 'click', target.textContent.trim());
            }

            // Track phone clicks
            if (target.href?.startsWith('tel:')) {
                window.almetalTrackEvent('Contact', 'phone_click', target.href.replace('tel:', ''));
            }

            // Track email clicks
            if (target.href?.startsWith('mailto:')) {
                window.almetalTrackEvent('Contact', 'email_click', target.href.replace('mailto:', ''));
            }
        });

        // Start tracking
        trackVisit();
    }
})();
