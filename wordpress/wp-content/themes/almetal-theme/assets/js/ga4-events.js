/**
 * Google Analytics 4 - Tracking des événements
 * 
 * Track automatiquement :
 * - Clics sur numéro de téléphone
 * - Clics sur email
 * - Soumission formulaire contact
 * - Clics sur boutons CTA
 * - Scroll depth (25%, 50%, 75%, 100%)
 * - Temps passé sur la page
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

(function() {
    'use strict';

    // Vérifier que gtag existe
    if (typeof gtag !== 'function') {
        console.warn('GA4: gtag not found');
        return;
    }

    /**
     * Track un événement GA4
     */
    function trackEvent(eventName, params) {
        params = params || {};
        gtag('event', eventName, params);
        console.log('GA4 Event:', eventName, params);
    }

    /**
     * Tracking des clics téléphone
     */
    document.addEventListener('click', function(e) {
        var link = e.target.closest('a[href^="tel:"]');
        if (link) {
            var phone = link.getAttribute('href').replace('tel:', '');
            trackEvent('click_phone', {
                'event_category': 'Contact',
                'event_label': phone,
                'phone_number': phone
            });
        }
    });

    /**
     * Tracking des clics email
     */
    document.addEventListener('click', function(e) {
        var link = e.target.closest('a[href^="mailto:"]');
        if (link) {
            var email = link.getAttribute('href').replace('mailto:', '');
            trackEvent('click_email', {
                'event_category': 'Contact',
                'event_label': email
            });
        }
    });

    /**
     * Tracking des boutons CTA
     */
    document.addEventListener('click', function(e) {
        var btn = e.target.closest('.btn-primary, .btn-cta, [class*="cta"], .contact-btn');
        if (btn && !btn.closest('a[href^="tel:"]') && !btn.closest('a[href^="mailto:"]')) {
            var text = btn.textContent.trim().substring(0, 50);
            var href = btn.getAttribute('href') || '';
            trackEvent('click_cta', {
                'event_category': 'CTA',
                'event_label': text,
                'link_url': href
            });
        }
    });

    /**
     * Tracking soumission formulaire contact
     */
    document.addEventListener('submit', function(e) {
        var form = e.target;
        if (form.id === 'contact-form' || form.classList.contains('contact-form') || form.querySelector('[name="contact_name"]')) {
            trackEvent('form_submit', {
                'event_category': 'Contact',
                'event_label': 'Formulaire de contact',
                'form_id': form.id || 'contact-form'
            });
            
            // Conversion pour Google Ads (si configuré)
            trackEvent('generate_lead', {
                'event_category': 'Conversion',
                'event_label': 'Contact Form'
            });
        }
    });

    /**
     * Tracking du scroll depth
     */
    var scrollMarks = { 25: false, 50: false, 75: false, 100: false };
    
    function getScrollPercent() {
        var h = document.documentElement;
        var b = document.body;
        var st = 'scrollTop';
        var sh = 'scrollHeight';
        return Math.round((h[st] || b[st]) / ((h[sh] || b[sh]) - h.clientHeight) * 100);
    }

    var scrollTimeout;
    window.addEventListener('scroll', function() {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(function() {
            var percent = getScrollPercent();
            
            [25, 50, 75, 100].forEach(function(mark) {
                if (percent >= mark && !scrollMarks[mark]) {
                    scrollMarks[mark] = true;
                    trackEvent('scroll_depth', {
                        'event_category': 'Engagement',
                        'event_label': mark + '%',
                        'percent_scrolled': mark
                    });
                }
            });
        }, 100);
    });

    /**
     * Tracking du temps passé sur la page
     */
    var timeOnPage = 0;
    var timeInterval = setInterval(function() {
        timeOnPage += 10;
        
        // Track à 30s, 60s, 120s, 300s
        if ([30, 60, 120, 300].indexOf(timeOnPage) !== -1) {
            trackEvent('time_on_page', {
                'event_category': 'Engagement',
                'event_label': timeOnPage + ' seconds',
                'time_seconds': timeOnPage
            });
        }
        
        // Arrêter après 5 minutes
        if (timeOnPage >= 300) {
            clearInterval(timeInterval);
        }
    }, 10000);

    /**
     * Tracking des clics sur réalisations
     */
    document.addEventListener('click', function(e) {
        var card = e.target.closest('.realisation-card, .realisation-thumbnail');
        if (card) {
            var title = card.querySelector('.realisation-title, h3, h4');
            var titleText = title ? title.textContent.trim() : 'Réalisation';
            trackEvent('view_realisation', {
                'event_category': 'Réalisations',
                'event_label': titleText
            });
        }
    });

    /**
     * Tracking des clics sur formations
     */
    document.addEventListener('click', function(e) {
        var card = e.target.closest('.formation-card, .training-card');
        if (card) {
            var title = card.querySelector('h3, h4, .formation-title');
            var titleText = title ? title.textContent.trim() : 'Formation';
            trackEvent('view_formation', {
                'event_category': 'Formations',
                'event_label': titleText
            });
        }
    });

    /**
     * Tracking ouverture menu mobile
     */
    document.addEventListener('click', function(e) {
        if (e.target.closest('.burger-menu, .mobile-menu-toggle, #burger-toggle')) {
            trackEvent('open_mobile_menu', {
                'event_category': 'Navigation',
                'event_label': 'Menu mobile'
            });
        }
    });

    /**
     * Page view enrichi avec infos supplémentaires
     */
    window.addEventListener('load', function() {
        // Détecter le type de page
        var pageType = 'other';
        var body = document.body;
        
        if (body.classList.contains('home')) pageType = 'home';
        else if (body.classList.contains('single-realisation')) pageType = 'realisation';
        else if (body.classList.contains('single-training_session')) pageType = 'formation';
        else if (body.classList.contains('page-template-contact') || window.location.pathname.indexOf('contact') !== -1) pageType = 'contact';
        else if (body.classList.contains('single-city_page')) pageType = 'city_page';
        else if (body.classList.contains('archive')) pageType = 'archive';
        
        trackEvent('page_view_enriched', {
            'event_category': 'Page View',
            'page_type': pageType,
            'page_title': document.title
        });
    });

    console.log('GA4 Events Tracking initialized');

})();
