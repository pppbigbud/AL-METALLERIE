/**
 * JavaScript pour la page Contact
 * Gestion de la carte Leaflet et du formulaire
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Initialiser la carte Leaflet
     */
    function initContactMap() {
        console.log('initContactMap() appelée');
        
        // Vérifier si l'élément de la carte existe
        const mapElement = document.getElementById('contact-map');
        console.log('Élément #contact-map trouvé:', mapElement);
        
        if (!mapElement) {
            console.error('L\'élément #contact-map n\'existe pas');
            return;
        }

        // Vérifier les dimensions du conteneur
        const rect = mapElement.getBoundingClientRect();
        console.log('Dimensions du conteneur:', rect.width, 'x', rect.height);

        // Vérifier si Leaflet est chargé
        if (typeof L === 'undefined') {
            console.error('Leaflet (L) n\'est pas défini');
            return;
        }
        console.log('Leaflet est bien chargé');

        // Coordonnées de l'entreprise (Peschadoires)
        const location = [45.8167, 3.4833];
        console.log('Coordonnées:', location);

        try {
            // Initialiser la carte Leaflet
            const map = L.map('contact-map').setView(location, 13);
            console.log('Carte Leaflet initialisée');

            // Ajouter la couche de tuiles OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);
            console.log('Couche de tuiles ajoutée');

        // Icône personnalisée pour le marqueur
        const customIcon = L.divIcon({
            html: `
                <div style="
                    background: linear-gradient(135deg, #F08B18, #FF6B35);
                    width: 40px;
                    height: 40px;
                    border-radius: 50% 50% 50% 0;
                    transform: rotate(-45deg);
                    border: 3px solid white;
                    box-shadow: 0 4px 12px rgba(240, 139, 24, 0.4);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                ">
                    <svg style="
                        width: 20px;
                        height: 20px;
                        transform: rotate(45deg);
                        fill: white;
                    " viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                </div>
            `,
            className: 'custom-marker',
            iconSize: [40, 40],
            iconAnchor: [20, 40],
            popupAnchor: [0, -40]
        });

        // Ajouter le marqueur
            const marker = L.marker(location, { icon: customIcon }).addTo(map);
            console.log('Marqueur ajouté');

            // Popup avec informations
            marker.bindPopup(`
                <div style="text-align: center; padding: 10px;">
                    <strong style="color: #F08B18; font-size: 16px;">AL Métallerie</strong><br>
                    14 route de Maringues<br>
                    63920 Peschadoires<br>
                    <a href="tel:+33673333532" style="color: #F08B18; text-decoration: none; font-weight: bold;">06 73 33 35 32</a>
                </div>
            `).openPopup();
            console.log('Popup configuré');

            // Forcer le rafraîchissement de la carte après un délai
            setTimeout(function() {
                console.log('Rafraîchissement de la carte...');
                map.invalidateSize();
                
                // Forcer un zoom pour vérifier que la carte est active
                map.setZoom(13);
                console.log('Carte rafraîchie et zoom forcé');
            }, 100);

        } catch (error) {
            console.error('Erreur lors de l\'initialisation de la carte:', error);
        }
    }

    /**
     * Initialiser la page contact
     */
    function initContactPage() {
        // Initialiser la carte Leaflet
        initContactMap();
        
        // Initialiser le formulaire de contact
        initContactForm();
    }

    /**
     * Initialiser le formulaire de contact
     */
    function initContactForm() {
        const form = document.querySelector('.contact-form');
        if (!form) {
            return;
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const submitBtn = form.querySelector('.contact-submit-btn');
            const messageDiv = form.querySelector('.form-message');
            
            // Désactiver le bouton
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
                Envoi en cours...
            `;
            
            // Envoyer le formulaire via AJAX
            fetch(almetal_ajax.ajax_url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageDiv.className = 'form-message success';
                    messageDiv.textContent = data.message;
                    form.reset();
                } else {
                    messageDiv.className = 'form-message error';
                    messageDiv.textContent = data.message;
                }
                messageDiv.style.display = 'block';
            })
            .catch(error => {
                messageDiv.className = 'form-message error';
                messageDiv.textContent = 'Une erreur est survenue. Veuillez réessayer.';
                messageDiv.style.display = 'block';
            })
            .finally(() => {
                // Réactiver le bouton
                submitBtn.disabled = false;
                submitBtn.innerHTML = `
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="22" y1="2" x2="11" y2="13"/>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                    </svg>
                    ${almetal_theme.translations?.send_button || 'Envoyer ma demande'}
                `;
            });
        });

        // Validation de l'email
        form.find('input[type="email"]').on('blur', function() {
            const email = $(this).val();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email && !emailRegex.test(email)) {
                $(this).css('border-color', '#f44336');
            } else {
                $(this).css('border-color', 'rgba(255, 255, 255, 0.1)');
            }
        });

        // Validation du téléphone
        form.find('input[type="tel"]').on('blur', function() {
            const phone = $(this).val();
            const phoneRegex = /^[0-9\s\-\+\(\)]{10,}$/;
            if (phone && !phoneRegex.test(phone)) {
                $(this).css('border-color', '#f44336');
            } else {
                $(this).css('border-color', 'rgba(255, 255, 255, 0.1)');
            }
        });
    }

    /**
     * Initialisation au chargement du DOM
     */
    $(document).ready(function() {
        // Initialiser le formulaire
        initContactForm();

        // Animation au scroll pour les éléments de contact
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, {
            threshold: 0.1
        });

        document.querySelectorAll('.contact-info-item').forEach(function(item) {
            observer.observe(item);
        });

        // Charger la carte seulement si on est sur la page contact
        if ($('#contact-map').length) {
            // Initialiser la carte Leaflet directement
            initContactMap();
            
            // Forcer le footer visible immédiatement
            ensureFooterVisible();
            
            // Et le forcer à nouveau après 1 seconde (après chargement de la carte)
            setTimeout(ensureFooterVisible, 1000);
            
            // Et encore après 2 secondes pour être sûr
            setTimeout(ensureFooterVisible, 2000);
        }
    });

})(jQuery);
