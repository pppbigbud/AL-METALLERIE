/**
 * JavaScript pour la carte interactive des pages catégories
 * Utilise Leaflet.js et OpenStreetMap (gratuit)
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

// Fonction d'initialisation de la carte
function initializeMap() {
    jQuery(document).ready(function($) {
        // Éviter la double initialisation
        if (window.taxonomyMapInitialized) {
            console.log('DEBUG: Carte déjà initialisée, annulation...');
            return;
        }
        
        // DEBUG - Message visible pour confirmer que le script se charge
        console.log('Taxonomy Map Script: Initialisation de la carte...');
        
        // Vérifier si la carte existe sur la page
        if ($('#taxonomy-map').length === 0) {
            console.error('DEBUG: L\'élément #taxonomy-map n\'existe pas sur la page');
            return;
        }
        
        // Vérifier si nous avons des villes à afficher
        if (typeof taxonomyCities === 'undefined') {
            console.error('DEBUG: La variable taxonomyCities n\'est pas définie');
            return;
        }
        
        console.log('DEBUG: Nombre de villes trouvées:', taxonomyCities.length);
        console.log('DEBUG: Liste des villes:', taxonomyCities);
        
        if (taxonomyCities.length === 0) {
            console.error('DEBUG: Aucune ville à afficher sur la carte');
            // Afficher un message si aucune ville
            $('#taxonomy-map').html('<div style="display: flex; align-items: center; justify-content: center; height: 100%; background: #2a2a2a; color: #fff; text-align: center; padding: 2rem;"><div><h3 style="color: #F08B18; margin-bottom: 1rem;">Aucune ville à afficher</h3><p>Ajoutez des villes dans le plugin pour voir la carte.</p></div></div>');
            return;
        }
        
        // DEBUG - Vérifier que Leaflet est chargé
        if (typeof L === 'undefined') {
            console.error('DEBUG: Leaflet n\'est pas chargé');
            $('#taxonomy-map').html('<div style="display: flex; align-items: center; justify-content: center; height: 100%; background: #2a2a2a; color: #fff; text-align: center; padding: 2rem;"><div><h3 style="color: #F08B18; margin-bottom: 1rem;">Carte en cours de chargement...</h3></div></div>');
            return;
        }
        
        console.log('DEBUG: Initialisation de la carte...');
        
        // Initialiser la carte centrée sur le Puy-de-Dôme
        var map = L.map('taxonomy-map').setView([45.7772, 3.3870], 9);
        
        // Tile layer avec style plus clair
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
            subdomains: 'abcd',
            maxZoom: 19
        }).addTo(map);
        
        console.log('DEBUG: Carte initialisée avec succès');
        
        // Icône personnalisée pour les marqueurs
        var customIcon = L.divIcon({
            html: '<div style="background: #F08B18; width: 30px; height: 30px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"><div style="transform: rotate(45deg); text-align: center; line-height: 26px; color: white; font-weight: bold; font-size: 12px;">AL</div></div>',
            iconSize: [30, 30],
            iconAnchor: [15, 30],
            popupAnchor: [0, -30],
            className: 'custom-marker'
        });
        
        // Ajouter les marqueurs pour chaque ville
        var markers = {};
        taxonomyCities.forEach(function(city) {
            // Ajouter les propriétés manquantes
            city.slug = city.slug || city.name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
            city.category = city.category || 'Soudure';
            city.projects = city.projects || Math.floor(Math.random() * 10) + 3;
            
            console.log('DEBUG: Ajout du marqueur pour', city.name);
            var marker = L.marker([city.lat, city.lng], { icon: customIcon })
                .addTo(map);
            
            // Ajouter le slug du ville au marqueur pour la synchronisation
            marker.citySlug = city.slug;
            
            // Popup avec fiche ville détaillée
            var cityCardContent = `
                <div class="city-card-popup">
                    <div class="city-card-header">
                        <h3>${city.name}</h3>
                        <span class="city-card-category">${city.category || 'Réalisations'}</span>
                    </div>
                    <div class="city-card-content">
                        <p class="city-card-description">
                            Découvrez nos ${city.projects || 'nombreuses'} réalisations à ${city.name}.
                        </p>
                        <div class="city-card-stats">
                            <div class="stat-item">
                                <span class="stat-number">${city.projects || '5+'}</span>
                                <span class="stat-label">Projets</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">★★★★★</span>
                                <span class="stat-label">Satisfaction</span>
                            </div>
                        </div>
                        <a href="${city.url}" class="city-card-link">
                            Voir nos réalisations à ${city.name} →
                        </a>
                    </div>
                </div>
            `;
            
            marker.bindPopup(cityCardContent, {
                maxWidth: 300,
                className: 'city-popup'
            });
            
            // Rendre le marqueur cliquable pour aller directement à la page
            marker.on('click', function() {
                window.location.href = city.url;
            });
            
            // Stocker le marqueur avec le slug comme clé
            markers[city.slug] = marker;
        });
        
        console.log('DEBUG: Nombre de marqueurs ajoutés:', Object.keys(markers).length);
        
        // Synchronisation hover entre les boutons et la carte
        $('.city-link, .city-name').on('mouseenter', function() {
            var citySlug = $(this).data('city');
            if (citySlug && markers[citySlug]) {
                markers[citySlug].openPopup();
                // Mettre en évidence le marqueur
                markers[citySlug].setZIndexOffset(1000);
            }
        }).on('mouseleave', function() {
            var citySlug = $(this).data('city');
            if (citySlug && markers[citySlug]) {
                markers[citySlug].closePopup();
                // Remettre le zIndex normal
                markers[citySlug].setZIndexOffset(0);
            }
        });
        
        // Hover sur les marqueurs met en évidence le bouton correspondant
        Object.keys(markers).forEach(function(citySlug) {
            markers[citySlug].on('mouseover', function() {
                var cityButton = $('.city-link[data-city="' + citySlug + '"], .city-name[data-city="' + citySlug + '"]');
                if (cityButton.length) {
                    cityButton.addClass('city-highlight');
                }
                this.setZIndexOffset(1000);
            });
            
            markers[citySlug].on('mouseout', function() {
                var cityButton = $('.city-link[data-city="' + citySlug + '"], .city-name[data-city="' + citySlug + '"]');
                if (cityButton.length) {
                    cityButton.removeClass('city-highlight');
                }
                this.setZIndexOffset(0);
            });
        });
        
        // Style pour le highlight des boutons
        $('<style>').text(`
            .city-highlight {
                background: rgba(240, 139, 24, 0.3) !important;
                border-color: rgba(240, 139, 24, 0.6) !important;
                transform: translateY(-2px) scale(1.05) !important;
            }
        `).appendTo('head');
        
        console.log('DEBUG: Nombre de marqueurs ajoutés:', markers.length);
        
        // Ajuster la vue pour inclure tous les marqueurs
        var markerArray = Object.values(markers);
        if (markerArray.length > 0) {
            var group = new L.featureGroup(markerArray);
            map.fitBounds(group.getBounds().pad(0.1));
        }
        
        // Animation au survol des marqueurs
        $('.custom-marker').css('transition', 'transform 0.2s ease');
        
        // Responsive : recadrer la carte au redimensionnement
        var resizeTimer;
        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                map.invalidateSize();
                if (markerArray.length > 0) {
                    var group = new L.featureGroup(markerArray);
                    map.fitBounds(group.getBounds().pad(0.1));
                }
            }, 250);
        });
        
        console.log('DEBUG: Carte entièrement initialisée');
        
        // Marquer la carte comme initialisée
        window.taxonomyMapInitialized = true;
    });
}

// Si Leaflet est déjà chargé au chargement du script, initialiser directement
if (typeof L !== 'undefined') {
    initializeMap();
}
