/**
 * AL Métallerie - Cookie Banner RGPD
 * Consent Mode v2 Compatible
 */

(function() {
    'use strict';

    const CONFIG = {
        cookieName: 'almetal_consent',
        cookieExpiry: 365,
        version: '2.0',
        categories: {
            necessary: { name: 'Cookies nécessaires', required: true, default: true },
            analytics: { name: 'Cookies analytiques', required: false, default: false },
            marketing: { name: 'Cookies marketing', required: false, default: false },
            preferences: { name: 'Cookies de préférences', required: false, default: false }
        }
    };

    class ConsentManager {
        constructor() {
            this.consent = this.loadConsent();
        }

        loadConsent() {
            const cookie = this.getCookie(CONFIG.cookieName);
            if (cookie) {
                try {
                    const data = JSON.parse(decodeURIComponent(cookie));
                    if (data.version === CONFIG.version) return data;
                } catch (e) {}
            }
            return null;
        }

        saveConsent(categories) {
            const consent = {
                version: CONFIG.version,
                timestamp: new Date().toISOString(),
                categories: categories,
                consentId: 'c_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9)
            };
            this.consent = consent;
            this.setCookie(CONFIG.cookieName, JSON.stringify(consent), CONFIG.cookieExpiry);
            this.logConsent(consent);
            this.updateConsentMode(categories);
            window.dispatchEvent(new CustomEvent('consentUpdated', { detail: consent }));
            return consent;
        }

        async logConsent(consent) {
            if (!window.almetalAnalytics?.restUrl) return;
            try {
                await fetch(window.almetalAnalytics.restUrl + 'consent/log', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        consent_id: consent.consentId,
                        categories: consent.categories,
                        version: consent.version,
                        url: window.location.href
                    })
                });
            } catch (e) {}
        }

        updateConsentMode(categories) {
            if (typeof gtag === 'function') {
                gtag('consent', 'update', {
                    'analytics_storage': categories.analytics ? 'granted' : 'denied',
                    'ad_storage': categories.marketing ? 'granted' : 'denied',
                    'ad_user_data': categories.marketing ? 'granted' : 'denied',
                    'ad_personalization': categories.marketing ? 'granted' : 'denied',
                    'functionality_storage': categories.preferences ? 'granted' : 'denied',
                    'personalization_storage': categories.preferences ? 'granted' : 'denied',
                    'security_storage': 'granted'
                });
            }
        }

        hasConsent(category) {
            return this.consent?.categories?.[category] === true;
        }

        setCookie(name, value, days) {
            const expires = new Date(Date.now() + days * 864e5).toUTCString();
            document.cookie = `${name}=${encodeURIComponent(value)}; expires=${expires}; path=/; SameSite=Lax`;
        }

        getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift());
            return null;
        }

        revokeConsent() {
            document.cookie = `${CONFIG.cookieName}=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/`;
            this.consent = null;
        }
    }

    class CookieBannerUI {
        constructor(manager) {
            this.manager = manager;
            this.banner = null;
            this.modal = null;
        }

        createBanner() {
            if (this.banner) return;
            
            const banner = document.createElement('div');
            banner.className = 'almetal-cookie-banner';
            banner.innerHTML = `
                <div class="almetal-cookie-banner__container">
                    <div class="almetal-cookie-banner__content">
                        <h2 class="almetal-cookie-banner__title">🍪 Gestion des cookies</h2>
                        <p class="almetal-cookie-banner__text">
                            Nous utilisons des cookies pour améliorer votre expérience et analyser notre trafic de manière anonyme.
                            <a href="${window.almetalAnalytics?.privacyUrl || '/politique-confidentialite/'}" target="_blank">En savoir plus</a>
                        </p>
                    </div>
                    <div class="almetal-cookie-banner__actions">
                        <button class="almetal-cookie-btn almetal-cookie-btn--refuse" data-action="refuse">Refuser</button>
                        <button class="almetal-cookie-btn almetal-cookie-btn--settings" data-action="settings">Personnaliser</button>
                        <button class="almetal-cookie-btn almetal-cookie-btn--accept" data-action="accept">Tout accepter</button>
                    </div>
                </div>
            `;

            banner.querySelector('[data-action="accept"]').onclick = () => this.acceptAll();
            banner.querySelector('[data-action="refuse"]').onclick = () => this.refuseAll();
            banner.querySelector('[data-action="settings"]').onclick = () => this.openModal();

            document.body.appendChild(banner);
            this.banner = banner;
            requestAnimationFrame(() => banner.classList.add('visible'));
        }

        createModal() {
            if (this.modal) return;

            const modal = document.createElement('div');
            modal.className = 'almetal-cookie-modal';
            
            let categoriesHTML = '';
            for (const [key, cat] of Object.entries(CONFIG.categories)) {
                categoriesHTML += `
                    <div class="almetal-cookie-category">
                        <div class="almetal-cookie-category__header">
                            <span class="almetal-cookie-category__name">${cat.name}${cat.required ? ' <span class="almetal-cookie-badge">Requis</span>' : ''}</span>
                            <label class="almetal-cookie-toggle">
                                <input type="checkbox" name="${key}" ${cat.default || cat.required ? 'checked' : ''} ${cat.required ? 'disabled' : ''}>
                                <span class="almetal-cookie-toggle__slider"></span>
                            </label>
                        </div>
                    </div>
                `;
            }

            modal.innerHTML = `
                <div class="almetal-cookie-modal__content">
                    <div class="almetal-cookie-modal__header">
                        <h3>Paramètres des cookies</h3>
                        <button class="almetal-cookie-modal__close">&times;</button>
                    </div>
                    <div class="almetal-cookie-modal__body">${categoriesHTML}</div>
                    <div class="almetal-cookie-modal__footer">
                        <button class="almetal-cookie-btn almetal-cookie-btn--refuse" data-action="refuse">Tout refuser</button>
                        <button class="almetal-cookie-btn almetal-cookie-btn--accept" data-action="save">Enregistrer</button>
                    </div>
                </div>
            `;

            modal.querySelector('.almetal-cookie-modal__close').onclick = () => this.closeModal();
            modal.querySelector('[data-action="refuse"]').onclick = () => this.refuseAll();
            modal.querySelector('[data-action="save"]').onclick = () => this.saveSettings();
            modal.onclick = (e) => { if (e.target === modal) this.closeModal(); };

            document.body.appendChild(modal);
            this.modal = modal;
        }

        openModal() {
            this.createModal();
            this.modal.classList.add('visible');
            document.body.style.overflow = 'hidden';
        }

        closeModal() {
            if (this.modal) {
                this.modal.classList.remove('visible');
                document.body.style.overflow = '';
            }
        }

        acceptAll() {
            const categories = {};
            for (const key of Object.keys(CONFIG.categories)) categories[key] = true;
            this.manager.saveConsent(categories);
            this.hide();
        }

        refuseAll() {
            const categories = {};
            for (const [key, cat] of Object.entries(CONFIG.categories)) categories[key] = cat.required;
            this.manager.saveConsent(categories);
            this.hide();
        }

        saveSettings() {
            const categories = {};
            this.modal.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                categories[cb.name] = cb.checked;
            });
            this.manager.saveConsent(categories);
            this.hide();
        }

        hide() {
            if (this.banner) {
                this.banner.classList.remove('visible');
                setTimeout(() => { this.banner?.remove(); this.banner = null; }, 400);
            }
            this.closeModal();
        }

        showIfNeeded() {
            if (!this.manager.consent) this.createBanner();
        }
    }

    // Init
    const manager = new ConsentManager();
    const ui = new CookieBannerUI(manager);

    // Default Consent Mode
    if (typeof gtag === 'function') {
        gtag('consent', 'default', {
            'analytics_storage': 'denied',
            'ad_storage': 'denied',
            'ad_user_data': 'denied',
            'ad_personalization': 'denied',
            'functionality_storage': 'denied',
            'personalization_storage': 'denied',
            'security_storage': 'granted',
            'wait_for_update': 500
        });
    }

    // Expose globally
    window.AlmetalConsent = {
        manager: manager,
        ui: ui,
        hasConsent: (cat) => manager.hasConsent(cat),
        showBanner: () => ui.createBanner(),
        showSettings: () => ui.openModal(),
        revokeConsent: () => { manager.revokeConsent(); ui.createBanner(); }
    };

    // Show banner if needed
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => ui.showIfNeeded());
    } else {
        ui.showIfNeeded();
    }

    // Update consent mode if already consented
    if (manager.consent) {
        manager.updateConsentMode(manager.consent.categories);
    }
})();
