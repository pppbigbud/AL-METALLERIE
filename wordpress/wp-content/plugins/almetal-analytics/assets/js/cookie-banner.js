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
            banner.setAttribute('role', 'dialog');
            banner.setAttribute('aria-label', 'Consentement aux cookies');
            banner.setAttribute('aria-live', 'polite');
            banner.innerHTML = `
                <div class="almetal-cookie-banner__container">
                    <div class="almetal-cookie-banner__content">
                        <div class="almetal-cookie-banner__icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5"/>
                                <path d="M8.5 8.5v.01"/>
                                <path d="M16 15.5v.01"/>
                                <path d="M12 12v.01"/>
                                <path d="M11 17v.01"/>
                                <path d="M7 14v.01"/>
                            </svg>
                        </div>
                        <div class="almetal-cookie-banner__text">
                            <p>
                                Nous utilisons des cookies pour améliorer votre expérience sur notre site. 
                                En continuant à naviguer, vous acceptez notre utilisation des cookies.
                                <a href="${window.almetalAnalytics?.privacyUrl || '/politique-confidentialite/'}" target="_blank" rel="noopener noreferrer">En savoir plus</a>
                            </p>
                        </div>
                    </div>
                    <div class="almetal-cookie-banner__actions">
                        <button type="button" class="almetal-cookie-btn almetal-cookie-btn--refuse" data-action="refuse" aria-label="Refuser les cookies">Refuser</button>
                        <button type="button" class="almetal-cookie-btn almetal-cookie-btn--settings" data-action="settings" aria-label="Personnaliser les cookies">Personnaliser</button>
                        <button type="button" class="almetal-cookie-btn almetal-cookie-btn--accept" data-action="accept" aria-label="Accepter tous les cookies">Accepter</button>
                    </div>
                </div>
            `;

            banner.querySelector('[data-action="accept"]').onclick = () => this.acceptAll();
            banner.querySelector('[data-action="refuse"]').onclick = () => this.refuseAll();
            banner.querySelector('[data-action="settings"]').onclick = () => this.openModal();

            document.body.appendChild(banner);
            this.banner = banner;
            
            // Animation d'apparition avec délai
            setTimeout(() => {
                banner.offsetHeight; // Force reflow
                banner.classList.add('visible', 'animate-in');
                
                // Focus sur le bouton accepter pour l'accessibilité
                setTimeout(() => {
                    const acceptBtn = banner.querySelector('[data-action="accept"]');
                    if (acceptBtn) acceptBtn.focus();
                }, 100);
            }, 800);
            
            // Gestion du clavier (Escape pour fermer)
            this.escapeHandler = (e) => {
                if (e.key === 'Escape' && this.banner?.classList.contains('visible')) {
                    this.refuseAll();
                }
            };
            document.addEventListener('keydown', this.escapeHandler);
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
                this.banner.classList.add('hide');
                this.banner.classList.remove('visible');
                setTimeout(() => { 
                    this.banner?.remove(); 
                    this.banner = null; 
                }, 400);
            }
            this.closeModal();
            
            // Nettoyer l'event listener
            if (this.escapeHandler) {
                document.removeEventListener('keydown', this.escapeHandler);
            }
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
