/**
 * Training Manager - Calendar Scripts
 *
 * @package TrainingManager
 * @since 1.0.0
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        initCalendars();
    });

    /**
     * Initialiser tous les calendriers
     */
    function initCalendars() {
        const calendars = document.querySelectorAll('.tm-calendar');
        
        calendars.forEach(function(calendarEl) {
            const wrapper = calendarEl.closest('.tm-calendar-wrapper');
            const type = wrapper ? wrapper.dataset.type : '';
            const view = wrapper ? wrapper.dataset.view : 'dayGridMonth';
            
            initCalendar(calendarEl, type, view);
        });
    }

    /**
     * Initialiser un calendrier
     */
    function initCalendar(calendarEl, type, initialView) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: initialView || 'dayGridMonth',
            locale: tmCalendar.locale || 'fr',
            firstDay: parseInt(tmCalendar.firstDay) || 1,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,listWeek'
            },
            buttonText: {
                today: 'Aujourd\'hui',
                month: 'Mois',
                week: 'Semaine',
                list: 'Liste'
            },
            height: 'auto',
            navLinks: true,
            editable: false,
            dayMaxEvents: 3,
            eventDisplay: 'block',
            
            // Chargement des événements
            events: function(info, successCallback, failureCallback) {
                fetchEvents(info.startStr, info.endStr, type, successCallback, failureCallback);
            },
            
            // Clic sur un événement
            eventClick: function(info) {
                info.jsEvent.preventDefault();
                showEventModal(info.event);
            },
            
            // Survol d'un événement
            eventMouseEnter: function(info) {
                showEventTooltip(info.event, info.el);
            },
            
            eventMouseLeave: function(info) {
                hideEventTooltip();
            }
        });

        calendar.render();
    }

    /**
     * Récupérer les événements via AJAX
     */
    function fetchEvents(start, end, type, successCallback, failureCallback) {
        const formData = new FormData();
        formData.append('action', 'tm_get_calendar_events');
        formData.append('nonce', tmCalendar.calendarNonce);
        formData.append('start', start);
        formData.append('end', end);
        formData.append('type', type);

        fetch(tmCalendar.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                successCallback(data.data);
            } else {
                failureCallback(new Error(data.data || tmCalendar.strings.error));
            }
        })
        .catch(error => {
            console.error('Calendar error:', error);
            failureCallback(error);
        });
    }

    /**
     * Afficher le tooltip d'un événement
     */
    function showEventTooltip(event, element) {
        hideEventTooltip();

        const props = event.extendedProps;
        const rect = element.getBoundingClientRect();
        
        const tooltip = document.createElement('div');
        tooltip.className = 'tm-event-popover';
        tooltip.id = 'tm-event-tooltip';
        
        const fillPercentage = props.total_places > 0 
            ? (props.reserved_places / props.total_places * 100) 
            : 0;
        
        tooltip.innerHTML = `
            <div class="tm-event-popover-header" style="background-color: ${event.backgroundColor}">
                <h4 class="tm-event-popover-title">${event.title}</h4>
                ${props.theme ? `<span class="tm-event-popover-type">${props.theme}</span>` : ''}
            </div>
            <div class="tm-event-popover-body">
                <div class="tm-event-popover-detail">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <span>${formatDate(event.start)}</span>
                </div>
                ${props.location ? `
                    <div class="tm-event-popover-detail">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        <span>${props.location}</span>
                    </div>
                ` : ''}
                ${props.trainer ? `
                    <div class="tm-event-popover-detail">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span>${props.trainer}</span>
                    </div>
                ` : ''}
                <div class="tm-event-popover-places">
                    <div class="tm-event-popover-places-bar">
                        <div class="tm-event-popover-places-fill" style="width: ${fillPercentage}%"></div>
                    </div>
                    <span class="tm-event-popover-places-text">
                        ${props.status === 'full' 
                            ? tmCalendar.strings.full 
                            : `${props.remaining_places} ${tmCalendar.strings.places}`
                        }
                    </span>
                </div>
            </div>
            <div class="tm-event-popover-footer">
                <a href="${event.url}" class="tm-event-popover-cta">Voir les détails</a>
            </div>
        `;

        document.body.appendChild(tooltip);

        // Positionner le tooltip
        const tooltipRect = tooltip.getBoundingClientRect();
        let top = rect.bottom + 10;
        let left = rect.left;

        // Ajuster si dépasse à droite
        if (left + tooltipRect.width > window.innerWidth - 20) {
            left = window.innerWidth - tooltipRect.width - 20;
        }

        // Ajuster si dépasse en bas
        if (top + tooltipRect.height > window.innerHeight - 20) {
            top = rect.top - tooltipRect.height - 10;
        }

        tooltip.style.position = 'fixed';
        tooltip.style.top = top + 'px';
        tooltip.style.left = left + 'px';
    }

    /**
     * Masquer le tooltip
     */
    function hideEventTooltip() {
        const tooltip = document.getElementById('tm-event-tooltip');
        if (tooltip) {
            tooltip.remove();
        }
    }

    /**
     * Afficher la modal d'un événement
     */
    function showEventModal(event) {
        hideEventTooltip();
        
        // Rediriger vers la page de la session
        if (event.url) {
            window.location.href = event.url;
        }
    }

    /**
     * Formater une date
     */
    function formatDate(date) {
        if (!date) return '';
        
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        
        return date.toLocaleDateString(tmCalendar.locale || 'fr-FR', options);
    }

    // Fermer le tooltip au clic ailleurs
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.fc-event') && !e.target.closest('.tm-event-popover')) {
            hideEventTooltip();
        }
    });

})();
