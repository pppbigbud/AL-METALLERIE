/**
 * JavaScript pour la page Contact
 * Gestion de la carte Google Maps et du formulaire
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Forcer le footer à être visible (fonction globale)
     */
    window.ensureFooterVisible = function() {
        const footer = document.querySelector('.page-template-page-contact .footer-bottom.footer-light');
        if (footer) {
            // Forcer le footer à être visible avec un z-index très élevé
            footer.style.zIndex = '999999';
            footer.style.position = 'fixed';
            footer.style.bottom = '0';
            footer.style.left = '0';
            footer.style.right = '0';
            footer.style.background = '#222222';
            footer.style.display = 'block';
            footer.style.visibility = 'visible';
            footer.style.opacity = '1';
            
            console.log('Footer forcé visible');
        }
    };

    /**
     * Initialiser la carte Google Maps
     */
    function initContactMap() {
        // Vérifier si l'élément de la carte existe
        const mapElement = document.getElementById('contact-map');
        if (!mapElement) {
            return;
        }

        // Coordonnées de l'entreprise (Peschadoires)
        const location = {
            lat: 45.8167,
            lng: 3.4833
        };

        // Options de la carte
        const mapOptions = {
            center: location,
            zoom: 10,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: true,
            zoomControl: true,
            styles: [
                {
                    "featureType": "all",
                    "elementType": "geometry",
                    "stylers": [{"color": "#242f3e"}]
                },
                {
                    "featureType": "all",
                    "elementType": "labels.text.stroke",
                    "stylers": [{"lightness": -80}]
                },
                {
                    "featureType": "administrative",
                    "elementType": "labels.text.fill",
                    "stylers": [{"color": "#746855"}]
                },
                {
                    "featureType": "poi",
                    "elementType": "labels.text.fill",
                    "stylers": [{"color": "#d59563"}]
                },
                {
                    "featureType": "poi.park",
                    "elementType": "geometry",
                    "stylers": [{"color": "#263c3f"}]
                },
                {
                    "featureType": "poi.park",
                    "elementType": "labels.text.fill",
                    "stylers": [{"color": "#6b9a76"}]
                },
                {
                    "featureType": "road",
                    "elementType": "geometry.fill",
                    "stylers": [{"color": "#2b3544"}]
                },
                {
                    "featureType": "road",
                    "elementType": "labels.text.fill",
                    "stylers": [{"color": "#9ca5b3"}]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "geometry.fill",
                    "stylers": [{"color": "#38414e"}]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.fill",
                    "stylers": [{"color": "#746855"}]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.stroke",
                    "stylers": [{"color": "#1f2835"}]
                },
                {
                    "featureType": "transit",
                    "elementType": "geometry",
                    "stylers": [{"color": "#2f3948"}]
                },
                {
                    "featureType": "water",
                    "elementType": "geometry",
                    "stylers": [{"color": "#17263c"}]
                }
            ]
        };

        // Créer la carte
        const map = new google.maps.Map(mapElement, mapOptions);
        
        // Forcer le footer visible dès que la carte est créée
        google.maps.event.addListenerOnce(map, 'idle', function() {
            // La carte est complètement chargée
            if (typeof ensureFooterVisible === 'function') {
                ensureFooterVisible();
            }
            console.log('Carte chargée, footer forcé visible');
        });

        // Icône personnalisée pour le marqueur (logo PNG 50px)
        const markerIcon = {
            url: almetal_theme.template_url + '/assets/images/logo.png',
            scaledSize: new google.maps.Size(50, 50),
            anchor: new google.maps.Point(25, 25),
            origin: new google.maps.Point(0, 0)
        };

        // Ajouter le marqueur
        const marker = new google.maps.Marker({
            position: location,
            map: map,
            icon: markerIcon,
            title: 'AL Métallerie',
            animation: google.maps.Animation.DROP,
            optimized: false
        });

        // InfoWindow avec informations
        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div style="padding: 10px; font-family: 'Roboto', sans-serif;">
                    <h3 style="margin: 0 0 10px 0; color: #F08B18; font-size: 18px;">AL Métallerie</h3>
                    <p style="margin: 5px 0; color: #333;">
                        <strong>Adresse:</strong><br>
                        14 route de Maringues<br>
                        63920 Peschadoires
                    </p>
                    <p style="margin: 5px 0; color: #333;">
                        <strong>Téléphone:</strong><br>
                        <a href="tel:+33673333532" style="color: #F08B18; text-decoration: none;">06 73 33 35 32</a>
                    </p>
                    <p style="margin: 10px 0 0 0;">
                        <a href="https://www.google.com/maps/dir/?api=1&destination=14+route+de+Maringues,+Peschadoires,+63920" 
                           target="_blank" 
                           style="display: inline-block; padding: 8px 16px; background: #F08B18; color: white; text-decoration: none; border-radius: 4px; font-weight: 600;">
                            Obtenir l'itinéraire
                        </a>
                    </p>
                </div>
            `
        });

        // Ouvrir l'InfoWindow au clic sur le marqueur
        marker.addListener('click', function() {
            infoWindow.open(map, marker);
        });

        // Animation du marqueur
        marker.addListener('click', function() {
            if (marker.getAnimation() !== null) {
                marker.setAnimation(null);
            } else {
                marker.setAnimation(google.maps.Animation.BOUNCE);
                setTimeout(function() {
                    marker.setAnimation(null);
                }, 2000);
            }
        });
    }

    /**
     * Gestion du formulaire de contact
     */
    function initContactForm() {
        const form = $('#contact-form');
        const messageDiv = $('.form-message');

        if (!form.length) {
            return;
        }

        form.on('submit', function(e) {
            e.preventDefault();

            // Désactiver le bouton de soumission
            const submitBtn = form.find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<span>Envoi en cours...</span>');

            // Récupérer les données du formulaire
            const formData = new FormData(this);

            // Envoyer via AJAX
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Afficher le message de succès
                    messageDiv
                        .removeClass('error')
                        .addClass('success')
                        .html('✓ Votre message a été envoyé avec succès ! Nous vous recontacterons rapidement.')
                        .fadeIn();

                    // Réinitialiser le formulaire
                    form[0].reset();

                    // Faire défiler vers le message
                    $('html, body').animate({
                        scrollTop: messageDiv.offset().top - 100
                    }, 500);
                },
                error: function(xhr, status, error) {
                    // Afficher le message d'erreur
                    messageDiv
                        .removeClass('success')
                        .addClass('error')
                        .html('✗ Une erreur est survenue. Veuillez réessayer ou nous contacter directement par téléphone.')
                        .fadeIn();
                },
                complete: function() {
                    // Réactiver le bouton
                    submitBtn.prop('disabled', false).html(originalText);

                    // Masquer le message après 5 secondes
                    setTimeout(function() {
                        messageDiv.fadeOut();
                    }, 5000);
                }
            });
        });

        // Validation en temps réel
        form.find('input[required], textarea[required], select[required]').on('blur', function() {
            const field = $(this);
            if (!field.val()) {
                field.css('border-color', '#f44336');
            } else {
                field.css('border-color', 'rgba(255, 255, 255, 0.1)');
            }
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
     * Charger l'API Google Maps dynamiquement
     */
    function loadGoogleMapsAPI() {
        // Vérifier si l'API est déjà chargée
        if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
            initContactMap();
            return;
        }

        // Clé API Google Maps (À REMPLACER par votre propre clé)
        const apiKey = 'AIzaSyAWrQ0heLj3xzkTUy_-elelg0I9HtsvzH8';

        // Créer le script
        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&callback=initMap`;
        script.async = true;
        script.defer = true;

        // Callback global
        window.initMap = function() {
            initContactMap();
            // Forcer le footer visible après l'initialisation de la carte
            setTimeout(function() {
                if (typeof window.ensureFooterVisible === 'function') {
                    window.ensureFooterVisible();
                }
            }, 500);
        };

        // Ajouter le script au document
        document.head.appendChild(script);
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
            loadGoogleMapsAPI();
            
            // Forcer le footer visible immédiatement
            ensureFooterVisible();
            
            // Et le forcer à nouveau après 1 seconde (après chargement de la carte)
            setTimeout(ensureFooterVisible, 1000);
            
            // Et encore après 2 secondes pour être sûr
            setTimeout(ensureFooterVisible, 2000);
        }
    });

})(jQuery);
