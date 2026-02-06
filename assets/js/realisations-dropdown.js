/**
 * Menu déroulant stylisé - Section Réalisations
 * Gestion du dropdown et du filtrage AJAX
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        const dropdown = document.getElementById('realisations-dropdown');
        
        if (!dropdown) {
            return;
        }

        const trigger = dropdown.querySelector('.dropdown-trigger');
        const menu = dropdown.querySelector('.dropdown-menu');
        const items = dropdown.querySelectorAll('.dropdown-item');
        const triggerText = trigger.querySelector('.dropdown-trigger__text');
        const triggerCount = trigger.querySelector('.dropdown-trigger__count');
        const triggerIcon = trigger.querySelector('.dropdown-trigger__icon');

        // Créer l'overlay pour fermer le dropdown
        const overlay = document.createElement('div');
        overlay.className = 'dropdown-overlay';
        document.body.appendChild(overlay);

        let currentFilter = '*';

        /**
         * Ouvrir/Fermer le dropdown
         */
        function toggleDropdown() {
            const isOpen = dropdown.classList.contains('open');
            
            if (isOpen) {
                closeDropdown();
            } else {
                openDropdown();
            }
        }

        function openDropdown() {
            dropdown.classList.add('open');
            trigger.classList.add('active');
            trigger.setAttribute('aria-expanded', 'true');
            overlay.classList.add('active');
            
            // Focus sur le premier item pour l'accessibilité
            const activeItem = menu.querySelector('.dropdown-item.active');
            if (activeItem) {
                activeItem.focus();
            }
        }

        function closeDropdown() {
            dropdown.classList.remove('open');
            trigger.classList.remove('active');
            trigger.setAttribute('aria-expanded', 'false');
            overlay.classList.remove('active');
        }

        /**
         * Sélectionner un item
         */
        function selectItem(item) {
            const filter = item.dataset.filter;
            const text = item.querySelector('.dropdown-item__text').textContent;
            const count = item.querySelector('.dropdown-item__count').textContent;
            const icon = item.querySelector('.dropdown-item__icon').innerHTML;

            // Mettre à jour l'état actif
            items.forEach(i => {
                i.classList.remove('active');
                i.setAttribute('aria-selected', 'false');
            });
            item.classList.add('active');
            item.setAttribute('aria-selected', 'true');

            // Mettre à jour le trigger
            triggerText.textContent = text;
            triggerCount.textContent = count;
            triggerIcon.innerHTML = icon;

            // Stocker le filtre actuel
            currentFilter = filter;

            // Déclencher le filtrage
            applyFilter(filter);

            // Fermer le dropdown
            closeDropdown();
        }

        /**
         * Appliquer le filtre
         */
        function applyFilter(filter) {
            // Mettre à jour le filtre dans le système AJAX existant
            if (typeof window.updateRealisationsFilter === 'function') {
                window.updateRealisationsFilter(filter);
            }

            // Déclencher l'événement pour le script AJAX existant
            const grid = document.getElementById('desktop-realisations-grid');
            if (grid) {
                // Réinitialiser la pagination
                grid.dataset.page = '1';
                grid.dataset.category = filter === '*' ? '' : filter.replace('.', '');

                // Déclencher le chargement AJAX
                const event = new CustomEvent('filterChanged', {
                    detail: { filter: filter }
                });
                document.dispatchEvent(event);
            }

            // Log pour debug
            console.log('🔽 Dropdown filtre appliqué:', filter);
        }

        /**
         * Gestion des événements clavier (accessibilité)
         */
        function handleKeydown(e) {
            const activeItem = menu.querySelector('.dropdown-item.active');
            let nextItem = null;

            switch(e.key) {
                case 'Enter':
                case ' ':
                    e.preventDefault();
                    if (dropdown.classList.contains('open')) {
                        if (document.activeElement.classList.contains('dropdown-item')) {
                            selectItem(document.activeElement);
                        }
                    } else {
                        openDropdown();
                    }
                    break;

                case 'Escape':
                    e.preventDefault();
                    closeDropdown();
                    trigger.focus();
                    break;

                case 'ArrowDown':
                    e.preventDefault();
                    if (!dropdown.classList.contains('open')) {
                        openDropdown();
                    } else {
                        const current = document.activeElement;
                        if (current.classList.contains('dropdown-item')) {
                            nextItem = current.nextElementSibling;
                            if (nextItem && nextItem.classList.contains('dropdown-item')) {
                                nextItem.focus();
                            }
                        } else if (activeItem) {
                            activeItem.focus();
                        }
                    }
                    break;

                case 'ArrowUp':
                    e.preventDefault();
                    if (dropdown.classList.contains('open')) {
                        const current = document.activeElement;
                        if (current.classList.contains('dropdown-item')) {
                            nextItem = current.previousElementSibling;
                            if (nextItem && nextItem.classList.contains('dropdown-item')) {
                                nextItem.focus();
                            }
                        }
                    }
                    break;

                case 'Home':
                    e.preventDefault();
                    if (dropdown.classList.contains('open')) {
                        const firstItem = menu.querySelector('.dropdown-item');
                        if (firstItem) firstItem.focus();
                    }
                    break;

                case 'End':
                    e.preventDefault();
                    if (dropdown.classList.contains('open')) {
                        const allItems = menu.querySelectorAll('.dropdown-item');
                        if (allItems.length > 0) {
                            allItems[allItems.length - 1].focus();
                        }
                    }
                    break;
            }
        }

        // Événements
        trigger.addEventListener('click', toggleDropdown);
        trigger.addEventListener('keydown', handleKeydown);
        overlay.addEventListener('click', closeDropdown);

        items.forEach(item => {
            item.addEventListener('click', function() {
                selectItem(this);
            });
            item.addEventListener('keydown', handleKeydown);
            item.setAttribute('tabindex', '0');
        });

        // Fermer au clic en dehors
        document.addEventListener('click', function(e) {
            if (!dropdown.contains(e.target) && !overlay.contains(e.target)) {
                closeDropdown();
            }
        });

        // Fermer à la touche Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && dropdown.classList.contains('open')) {
                closeDropdown();
                trigger.focus();
            }
        });

        // Exposer la fonction de mise à jour globalement
        window.realisationsDropdown = {
            setFilter: function(filter) {
                const item = dropdown.querySelector('.dropdown-item[data-filter="' + filter + '"]');
                if (item) {
                    selectItem(item);
                }
            },
            getCurrentFilter: function() {
                return currentFilter;
            }
        };

        console.log('🔽 Dropdown réalisations initialisé');
    });
})();
