/**
 * Training Manager - Public Scripts
 *
 * @package TrainingManager
 * @since 1.0.0
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        initFilters();
        initContactForms();
        initModals();
    });

    /**
     * Initialiser les filtres
     */
    function initFilters() {
        const filters = document.querySelectorAll('.tm-filter');
        
        if (!filters.length) return;

        filters.forEach(function(filter) {
            filter.addEventListener('change', function() {
                filterSessions();
            });
        });
    }

    /**
     * Filtrer les sessions
     */
    function filterSessions() {
        const wrapper = document.querySelector('.tm-sessions-wrapper');
        if (!wrapper) return;

        const grid = wrapper.querySelector('.tm-sessions-grid');
        const perPage = wrapper.dataset.perPage || 9;

        const type = document.getElementById('tm-filter-type')?.value || '';
        const theme = document.getElementById('tm-filter-theme')?.value || '';
        const availability = document.getElementById('tm-filter-availability')?.value || '';
        const date = document.getElementById('tm-filter-date')?.value || '';

        // Afficher le loader
        grid.innerHTML = '<div class="tm-loading">' + (tmPublic?.strings?.loading || 'Chargement...') + '</div>';

        const formData = new FormData();
        formData.append('action', 'tm_filter_sessions');
        formData.append('nonce', tmPublic.filterNonce);
        formData.append('type', type);
        formData.append('theme', theme);
        formData.append('availability', availability);
        formData.append('date', date);
        formData.append('per_page', perPage);
        formData.append('page', 1);

        fetch(tmPublic.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                grid.innerHTML = data.data.html || '<p class="tm-no-sessions">' + (tmPublic?.strings?.noResults || 'Aucun résultat.') + '</p>';
                
                // Gérer le bouton "Charger plus"
                const loadMoreWrapper = wrapper.querySelector('.tm-load-more-wrapper');
                if (loadMoreWrapper) {
                    if (data.data.max_pages > 1) {
                        loadMoreWrapper.style.display = 'block';
                        loadMoreWrapper.dataset.page = 1;
                        loadMoreWrapper.dataset.maxPages = data.data.max_pages;
                    } else {
                        loadMoreWrapper.style.display = 'none';
                    }
                }
            } else {
                grid.innerHTML = '<p class="tm-error">' + (tmPublic?.strings?.error || 'Une erreur est survenue.') + '</p>';
            }
        })
        .catch(error => {
            console.error('Filter error:', error);
            grid.innerHTML = '<p class="tm-error">' + (tmPublic?.strings?.error || 'Une erreur est survenue.') + '</p>';
        });
    }

    /**
     * Initialiser les formulaires de contact
     */
    function initContactForms() {
        const forms = document.querySelectorAll('.tm-contact-form form');
        
        forms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                submitContactForm(form);
            });
        });
    }

    /**
     * Soumettre un formulaire de contact
     */
    function submitContactForm(form) {
        const submitBtn = form.querySelector('.tm-form-submit');
        const messageContainer = form.querySelector('.tm-form-message') || createMessageContainer(form);
        
        // Désactiver le bouton
        submitBtn.disabled = true;
        submitBtn.textContent = tmPublic?.strings?.loading || 'Envoi...';

        // Préparer les données
        const formData = new FormData(form);
        formData.append('action', 'tm_contact_request');
        formData.append('nonce', tmPublic.contactNonce);

        fetch(tmPublic.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageContainer.className = 'tm-form-message tm-success';
                messageContainer.textContent = data.data.message || tmPublic?.strings?.success;
                messageContainer.style.display = 'block';
                form.reset();
            } else {
                messageContainer.className = 'tm-form-message tm-error';
                messageContainer.textContent = data.data.message || tmPublic?.strings?.error;
                messageContainer.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Contact form error:', error);
            messageContainer.className = 'tm-form-message tm-error';
            messageContainer.textContent = tmPublic?.strings?.error || 'Une erreur est survenue.';
            messageContainer.style.display = 'block';
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Envoyer';
        });
    }

    /**
     * Créer un conteneur de message
     */
    function createMessageContainer(form) {
        const container = document.createElement('div');
        container.className = 'tm-form-message';
        container.style.display = 'none';
        form.insertBefore(container, form.firstChild);
        return container;
    }

    /**
     * Initialiser les modals
     */
    function initModals() {
        // Fermer la modal au clic sur l'overlay
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('tm-modal-overlay')) {
                closeModal(e.target.closest('.tm-modal'));
            }
            if (e.target.classList.contains('tm-modal-close')) {
                closeModal(e.target.closest('.tm-modal'));
            }
        });

        // Fermer avec Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.querySelector('.tm-modal[style*="display: block"]');
                if (modal) {
                    closeModal(modal);
                }
            }
        });
    }

    /**
     * Ouvrir une modal
     */
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    /**
     * Fermer une modal
     */
    function closeModal(modal) {
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    /**
     * Charger plus de sessions
     */
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('tm-load-more')) {
            loadMoreSessions(e.target);
        }
    });

    function loadMoreSessions(button) {
        const wrapper = button.closest('.tm-sessions-wrapper');
        const loadMoreWrapper = button.closest('.tm-load-more-wrapper');
        const grid = wrapper.querySelector('.tm-sessions-grid');
        
        const currentPage = parseInt(loadMoreWrapper.dataset.page) || 1;
        const maxPages = parseInt(loadMoreWrapper.dataset.maxPages) || 1;
        const nextPage = currentPage + 1;

        if (nextPage > maxPages) {
            loadMoreWrapper.style.display = 'none';
            return;
        }

        const perPage = wrapper.dataset.perPage || 9;
        const type = document.getElementById('tm-filter-type')?.value || '';
        const theme = document.getElementById('tm-filter-theme')?.value || '';
        const availability = document.getElementById('tm-filter-availability')?.value || '';
        const date = document.getElementById('tm-filter-date')?.value || '';

        button.textContent = tmPublic?.strings?.loading || 'Chargement...';
        button.disabled = true;

        const formData = new FormData();
        formData.append('action', 'tm_filter_sessions');
        formData.append('nonce', tmPublic.filterNonce);
        formData.append('type', type);
        formData.append('theme', theme);
        formData.append('availability', availability);
        formData.append('date', date);
        formData.append('per_page', perPage);
        formData.append('page', nextPage);

        fetch(tmPublic.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.html) {
                grid.insertAdjacentHTML('beforeend', data.data.html);
                loadMoreWrapper.dataset.page = nextPage;

                if (nextPage >= data.data.max_pages) {
                    loadMoreWrapper.style.display = 'none';
                }
            }
        })
        .catch(error => {
            console.error('Load more error:', error);
        })
        .finally(() => {
            button.textContent = 'Charger plus';
            button.disabled = false;
        });
    }

    // Exposer les fonctions globalement si nécessaire
    window.tmPublic = window.tmPublic || {};
    window.tmPublic.openModal = openModal;
    window.tmPublic.closeModal = closeModal;

})();
