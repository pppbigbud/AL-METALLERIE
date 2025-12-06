/**
 * Bannière de Consentement aux Cookies
 * Gestion du consentement RGPD pour AL Metallerie
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

(function() {
    'use strict';

    // Configuration
    const CONFIG = {
        cookieName: 'almetal_cookie_consent',
        cookieDuration: 365, // Durée en jours
        showDelay: 800, // Délai avant l'apparition (ms)
        hideDelay: 400 // Délai de disparition (ms)
    };

    /**
     * Classe principale de gestion du consentement
     */
    class CookieConsent {
        constructor() {
            this.banner = null;
            this.acceptBtn = null;
            this.declineBtn = null;
            this.init();
        }

        /**
         * Initialisation
         */
        init() {
            // Vérifier si le consentement a déjà été donné
            if (this.hasConsent()) {
                return;
            }

            // Attendre que le DOM soit chargé
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => this.createBanner());
            } else {
                this.createBanner();
            }
        }

        /**
         * Vérifie si le consentement existe déjà
         */
        hasConsent() {
            return this.getCookie(CONFIG.cookieName) !== null;
        }

        /**
         * Crée la bannière HTML
         */
        createBanner() {
            // Créer l'élément de la bannière
            const banner = document.createElement('div');
            banner.className = 'cookie-consent-banner';
            banner.setAttribute('role', 'dialog');
            banner.setAttribute('aria-label', 'Consentement aux cookies');
            banner.setAttribute('aria-live', 'polite');

            // Contenu HTML de la bannière
            banner.innerHTML = `
                <div class="cookie-consent-container">
                    <div class="cookie-consent-content">
                        <div class="cookie-consent-icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5"/>
                                <path d="M8.5 8.5v.01"/>
                                <path d="M16 15.5v.01"/>
                                <path d="M12 12v.01"/>
                                <path d="M11 17v.01"/>
                                <path d="M7 14v.01"/>
                            </svg>
                        </div>
                        <div class="cookie-consent-text">
                            <p>
                                Nous utilisons des cookies pour améliorer votre expérience sur notre site. 
                                En continuant à naviguer, vous acceptez notre utilisation des cookies. 
                                <a href="${this.getPolicyUrl()}" target="_blank" rel="noopener noreferrer">En savoir plus</a>
                            </p>
                        </div>
                    </div>
                    <div class="cookie-consent-actions">
                        <button type="button" class="cookie-consent-btn cookie-consent-btn--decline" data-action="decline" aria-label="Refuser les cookies">
                            Refuser
                        </button>
                        <button type="button" class="cookie-consent-btn cookie-consent-btn--accept" data-action="accept" aria-label="Accepter les cookies">
                            Accepter
                        </button>
                    </div>
                </div>
            `;

            // Ajouter la bannière au body
            document.body.appendChild(banner);

            // Stocker les références
            this.banner = banner;
            this.acceptBtn = banner.querySelector('[data-action="accept"]');
            this.declineBtn = banner.querySelector('[data-action="decline"]');

            // Attacher les événements
            this.attachEvents();

            // Afficher la bannière avec un délai
            setTimeout(() => this.showBanner(), CONFIG.showDelay);
        }

        /**
         * Obtient l'URL de la politique de confidentialité
         */
        getPolicyUrl() {
            // Chercher le lien dans le footer
            const footerLink = document.querySelector('a[href*="politique-confidentialite"]');
            if (footerLink) {
                return footerLink.href;
            }

            // URL par défaut
            return '/politique-confidentialite';
        }

        /**
         * Attache les événements aux boutons
         */
        attachEvents() {
            if (this.acceptBtn) {
                this.acceptBtn.addEventListener('click', () => this.handleAccept());
            }

            if (this.declineBtn) {
                this.declineBtn.addEventListener('click', () => this.handleDecline());
            }

            // Gestion du clavier (Escape pour fermer)
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.banner && this.banner.classList.contains('show')) {
                    this.handleDecline();
                }
            });
        }

        /**
         * Affiche la bannière
         */
        showBanner() {
            if (this.banner) {
                // Forcer un reflow pour que la transition fonctionne
                this.banner.offsetHeight;
                
                this.banner.classList.add('show', 'animate-in');
                
                // Focus sur le bouton accepter pour l'accessibilité
                setTimeout(() => {
                    if (this.acceptBtn) {
                        this.acceptBtn.focus();
                    }
                }, 100);
            }
        }

        /**
         * Cache la bannière
         */
        hideBanner(callback) {
            if (this.banner) {
                this.banner.classList.add('hide');
                this.banner.classList.remove('show');

                setTimeout(() => {
                    if (this.banner && this.banner.parentNode) {
                        this.banner.parentNode.removeChild(this.banner);
                    }
                    if (callback) callback();
                }, CONFIG.hideDelay);
            }
        }

        /**
         * Gère l'acceptation des cookies
         */
        handleAccept() {
            this.setCookie(CONFIG.cookieName, 'accepted', CONFIG.cookieDuration);
            this.hideBanner(() => {
                // Callback optionnel après acceptation
                this.onAccept();
            });
        }

        /**
         * Gère le refus des cookies
         */
        handleDecline() {
            this.setCookie(CONFIG.cookieName, 'declined', CONFIG.cookieDuration);
            this.hideBanner(() => {
                // Callback optionnel après refus
                this.onDecline();
            });
        }

        /**
         * Callback après acceptation
         */
        onAccept() {
            console.log('Cookies acceptés');
            // Ici vous pouvez activer Google Analytics, Facebook Pixel, etc.
            // Exemple : this.enableAnalytics();
        }

        /**
         * Callback après refus
         */
        onDecline() {
            console.log('Cookies refusés');
            // Ici vous pouvez désactiver les cookies non essentiels
        }

        /**
         * Définit un cookie
         */
        setCookie(name, value, days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            const expires = 'expires=' + date.toUTCString();
            const sameSite = 'SameSite=Lax';
            const secure = window.location.protocol === 'https:' ? 'Secure' : '';
            
            document.cookie = `${name}=${value};${expires};path=/;${sameSite};${secure}`;
        }

        /**
         * Récupère un cookie
         */
        getCookie(name) {
            const nameEQ = name + '=';
            const cookies = document.cookie.split(';');
            
            for (let i = 0; i < cookies.length; i++) {
                let cookie = cookies[i];
                while (cookie.charAt(0) === ' ') {
                    cookie = cookie.substring(1, cookie.length);
                }
                if (cookie.indexOf(nameEQ) === 0) {
                    return cookie.substring(nameEQ.length, cookie.length);
                }
            }
            return null;
        }

        /**
         * Supprime un cookie
         */
        deleteCookie(name) {
            this.setCookie(name, '', -1);
        }
    }

    // Initialiser la bannière de consentement
    new CookieConsent();

    // Exposer une fonction globale pour réinitialiser le consentement (utile pour les tests)
    window.resetCookieConsent = function() {
        document.cookie = CONFIG.cookieName + '=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/;';
        location.reload();
    };

})();
