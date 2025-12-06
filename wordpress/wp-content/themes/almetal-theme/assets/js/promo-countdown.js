/**
 * Compte à rebours pour les slides promotionnelles
 * 
 * @package ALMetallerie
 * @since 2.0.0
 */

(function() {
    'use strict';

    /**
     * Initialiser tous les comptes à rebours
     */
    function initCountdowns() {
        const countdowns = document.querySelectorAll('.promo-countdown');
        
        if (countdowns.length === 0) {
            return;
        }

        console.log('⏰ Initialisation des comptes à rebours:', countdowns.length);

        countdowns.forEach(function(countdown) {
            const endDateStr = countdown.getAttribute('data-end-date');
            
            if (!endDateStr) {
                console.warn('⚠️ Date de fin manquante pour un countdown');
                return;
            }

            // Parser la date (format: YYYY-MM-DD)
            const endDate = new Date(endDateStr + 'T23:59:59');
            
            // Vérifier si la date est valide
            if (isNaN(endDate.getTime())) {
                console.error('❌ Date invalide:', endDateStr);
                return;
            }

            // Mettre à jour le countdown
            updateCountdown(countdown, endDate);
            
            // Mettre à jour toutes les secondes
            setInterval(function() {
                updateCountdown(countdown, endDate);
            }, 1000);
        });
    }

    /**
     * Mettre à jour un compte à rebours
     */
    function updateCountdown(element, endDate) {
        const now = new Date();
        const diff = endDate - now;

        // Si l'offre est expirée
        if (diff <= 0) {
            element.innerHTML = '<span class="countdown-expired">⏰ Offre expirée</span>';
            element.classList.add('expired');
            return;
        }

        // Calculer les jours, heures, minutes, secondes
        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

        // Mettre à jour les éléments
        const daysEl = element.querySelector('.countdown-days');
        const hoursEl = element.querySelector('.countdown-hours');
        const minutesEl = element.querySelector('.countdown-minutes');
        const secondsEl = element.querySelector('.countdown-seconds');

        if (daysEl) daysEl.textContent = padZero(days);
        if (hoursEl) hoursEl.textContent = padZero(hours);
        if (minutesEl) minutesEl.textContent = padZero(minutes);
        if (secondsEl) secondsEl.textContent = padZero(seconds);

        // Ajouter une classe d'urgence si moins de 24h
        if (days === 0) {
            element.classList.add('urgent');
        }
        
        // Ajouter une classe critique si moins de 1h
        if (days === 0 && hours === 0) {
            element.classList.add('critical');
        }
    }

    /**
     * Ajouter un zéro devant les nombres < 10
     */
    function padZero(num) {
        return num < 10 ? '0' + num : num;
    }

    // Initialiser au chargement du DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCountdowns);
    } else {
        initCountdowns();
    }

    // Exposer globalement pour debug
    window.initPromoCountdowns = initCountdowns;

})();
