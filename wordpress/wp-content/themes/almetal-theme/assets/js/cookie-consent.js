/**
 * Cookie Consent Banner
 * Gestion du consentement aux cookies pour AL Métallerie
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

(function() {
    'use strict';

    const COOKIE_NAME = 'almetal_cookie_consent';
    const COOKIE_EXPIRY_DAYS = 365;

    /**
     * Vérifier si le consentement a déjà été donné
     */
    function hasConsent() {
        return document.cookie.split(';').some(function(item) {
            return item.trim().indexOf(COOKIE_NAME + '=') === 0;
        });
    }

    /**
     * Obtenir la valeur du consentement
     */
    function getConsentValue() {
        const match = document.cookie.match(new RegExp('(^| )' + COOKIE_NAME + '=([^;]+)'));
        return match ? match[2] : null;
    }

    /**
     * Définir le cookie de consentement
     */
    function setConsent(value) {
        const date = new Date();
        date.setTime(date.getTime() + (COOKIE_EXPIRY_DAYS * 24 * 60 * 60 * 1000));
        document.cookie = COOKIE_NAME + '=' + value + ';expires=' + date.toUTCString() + ';path=/;SameSite=Lax';
    }

    /**
     * Afficher la bannière
     */
    function showBanner() {
        const banner = document.getElementById('cookie-consent-banner');
        if (banner) {
            banner.classList.add('visible');
            banner.setAttribute('aria-hidden', 'false');
        }
    }

    /**
     * Masquer la bannière
     */
    function hideBanner() {
        const banner = document.getElementById('cookie-consent-banner');
        if (banner) {
            banner.classList.remove('visible');
            banner.setAttribute('aria-hidden', 'true');
        }
    }

    /**
     * Accepter tous les cookies
     */
    function acceptAll() {
        setConsent('all');
        hideBanner();
        loadAnalytics();
    }

    /**
     * Accepter uniquement les cookies essentiels
     */
    function acceptEssential() {
        setConsent('essential');
        hideBanner();
    }

    /**
     * Charger les scripts d'analytics si consentement donné
     */
    function loadAnalytics() {
        // Google Analytics ou autres scripts de tracking
        // À personnaliser selon les besoins
        if (typeof gtag === 'function') {
            gtag('consent', 'update', {
                'analytics_storage': 'granted'
            });
        }
    }

    /**
     * Initialisation
     */
    function init() {
        // Si pas de consentement, afficher la bannière
        if (!hasConsent()) {
            // Attendre que le DOM soit prêt
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', showBanner);
            } else {
                showBanner();
            }
        } else if (getConsentValue() === 'all') {
            // Si consentement total, charger les analytics
            loadAnalytics();
        }

        // Écouter les clics sur les boutons
        document.addEventListener('click', function(e) {
            if (e.target.matches('#cookie-accept-all, .cookie-accept-all')) {
                e.preventDefault();
                acceptAll();
            }
            if (e.target.matches('#cookie-accept-essential, .cookie-accept-essential')) {
                e.preventDefault();
                acceptEssential();
            }
        });
    }

    // Lancer l'initialisation
    init();

})();
