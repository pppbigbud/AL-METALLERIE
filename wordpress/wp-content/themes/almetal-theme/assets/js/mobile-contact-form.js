/**
 * Formulaire de contact Mobile - AL Metallerie
 * 
 * Gestion AJAX du formulaire avec modal de confirmation stylisée
 * Pas de rechargement de page
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

(function() {
    'use strict';

    // Attendre que le DOM soit chargé
    document.addEventListener('DOMContentLoaded', function() {
        initMobileContactForm();
    });

    /**
     * Initialiser le formulaire de contact mobile
     */
    function initMobileContactForm() {
        const form = document.querySelector('.mobile-contact-form');
        
        if (!form) {
            return;
        }

        // Créer la modal de confirmation
        createConfirmationModal();

        // Gérer la soumission du formulaire
        form.addEventListener('submit', handleFormSubmit);

        // Validation en temps réel
        initRealTimeValidation(form);
    }

    /**
     * Créer la modal de confirmation
     */
    function createConfirmationModal() {
        // Vérifier si la modal existe déjà
        if (document.getElementById('mobile-contact-modal')) {
            return;
        }

        const modal = document.createElement('div');
        modal.id = 'mobile-contact-modal';
        modal.className = 'mobile-contact-modal';
        modal.innerHTML = `
            <div class="mobile-contact-modal-overlay"></div>
            <div class="mobile-contact-modal-content">
                <div class="mobile-contact-modal-icon">
                    <svg class="icon-success" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <svg class="icon-error" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                </div>
                <h3 class="mobile-contact-modal-title"></h3>
                <p class="mobile-contact-modal-message"></p>
                <button type="button" class="mobile-contact-modal-btn">
                    <span>Fermer</span>
                </button>
            </div>
        `;

        document.body.appendChild(modal);

        // Fermer la modal au clic sur le bouton ou l'overlay
        modal.querySelector('.mobile-contact-modal-btn').addEventListener('click', closeModal);
        modal.querySelector('.mobile-contact-modal-overlay').addEventListener('click', closeModal);
    }

    /**
     * Gérer la soumission du formulaire
     */
    function handleFormSubmit(e) {
        e.preventDefault();

        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnContent = submitBtn.innerHTML;

        // Valider le formulaire
        if (!validateForm(form)) {
            return;
        }

        // État de chargement
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <span class="mobile-btn-loading">
                <svg class="spinner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" stroke-dasharray="60" stroke-dashoffset="20"></circle>
                </svg>
                Envoi en cours...
            </span>
        `;
        submitBtn.classList.add('loading');

        // Préparer les données
        const formData = new FormData(form);
        formData.append('action', 'almetal_contact_form');

        // URL AJAX (avec fallback)
        const ajaxUrl = (typeof almetal_mobile_ajax !== 'undefined' && almetal_mobile_ajax.ajax_url) 
            ? almetal_mobile_ajax.ajax_url 
            : '/wp-admin/admin-ajax.php';

        // Envoyer via AJAX
        fetch(ajaxUrl, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showModal('success', 'Message envoyé !', 'Merci pour votre message. Nous vous recontacterons dans les plus brefs délais.');
                form.reset();
            } else {
                const errorMsg = data.data && data.data.message 
                    ? data.data.message 
                    : 'Une erreur est survenue. Veuillez réessayer.';
                showModal('error', 'Erreur', errorMsg);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showModal('error', 'Erreur de connexion', 'Impossible d\'envoyer le message. Vérifiez votre connexion internet ou contactez-nous par téléphone.');
        })
        .finally(() => {
            // Restaurer le bouton
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnContent;
            submitBtn.classList.remove('loading');
        });
    }

    /**
     * Valider le formulaire
     */
    function validateForm(form) {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');

        requiredFields.forEach(field => {
            removeFieldError(field);

            if (!field.value.trim()) {
                showFieldError(field, 'Ce champ est requis');
                isValid = false;
            } else if (field.type === 'email' && !isValidEmail(field.value)) {
                showFieldError(field, 'Email invalide');
                isValid = false;
            }
        });

        // Vibration feedback sur erreur (si supporté)
        if (!isValid && navigator.vibrate) {
            navigator.vibrate(100);
        }

        return isValid;
    }

    /**
     * Validation en temps réel
     */
    function initRealTimeValidation(form) {
        const fields = form.querySelectorAll('input, textarea');

        fields.forEach(field => {
            field.addEventListener('blur', function() {
                removeFieldError(this);

                if (this.required && !this.value.trim()) {
                    showFieldError(this, 'Ce champ est requis');
                } else if (this.type === 'email' && this.value && !isValidEmail(this.value)) {
                    showFieldError(this, 'Email invalide');
                } else if (this.type === 'tel' && this.value && !isValidPhone(this.value)) {
                    showFieldError(this, 'Numéro invalide');
                }
            });

            // Retirer l'erreur quand l'utilisateur tape
            field.addEventListener('input', function() {
                if (this.classList.contains('has-error')) {
                    removeFieldError(this);
                }
            });
        });
    }

    /**
     * Afficher une erreur sur un champ
     */
    function showFieldError(field, message) {
        field.classList.add('has-error');
        
        // Créer le message d'erreur
        const errorDiv = document.createElement('div');
        errorDiv.className = 'mobile-field-error';
        errorDiv.textContent = message;
        
        field.parentNode.appendChild(errorDiv);
    }

    /**
     * Retirer l'erreur d'un champ
     */
    function removeFieldError(field) {
        field.classList.remove('has-error');
        const errorDiv = field.parentNode.querySelector('.mobile-field-error');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    /**
     * Afficher la modal
     */
    function showModal(type, title, message) {
        const modal = document.getElementById('mobile-contact-modal');
        if (!modal) return;

        // Définir le type (success ou error)
        modal.className = 'mobile-contact-modal ' + type;
        modal.querySelector('.mobile-contact-modal-title').textContent = title;
        modal.querySelector('.mobile-contact-modal-message').textContent = message;

        // Afficher avec animation
        modal.classList.add('visible');
        document.body.style.overflow = 'hidden';

        // Vibration feedback
        if (navigator.vibrate) {
            navigator.vibrate(type === 'success' ? [50, 50, 50] : [100, 50, 100]);
        }
    }

    /**
     * Fermer la modal
     */
    function closeModal() {
        const modal = document.getElementById('mobile-contact-modal');
        if (!modal) return;

        modal.classList.remove('visible');
        document.body.style.overflow = '';
    }

    /**
     * Valider un email
     */
    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    /**
     * Valider un téléphone
     */
    function isValidPhone(phone) {
        return /^[0-9\s\-\+\(\)]{10,}$/.test(phone);
    }

})();
