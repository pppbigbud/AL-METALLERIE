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
        
        // Initialiser la carte sans positionnement initial
        var map = L.map('taxonomy-map');
        
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
        // Variable globale pour suivre le marqueur actif
        var activeMarker = null;
        var markers = {};
        
        taxonomyCities.forEach(function(city, index) {
            console.log(`DEBUG: Traitement ville ${index + 1}/${taxonomyCities.length}:`, city.name, city.slug, city.lat, city.lng);
            
            // Vérifier les coordonnées
            if (!city.lat || !city.lng || isNaN(city.lat) || isNaN(city.lng)) {
                console.error('DEBUG: Coordonnées invalides pour', city.name, city.lat, city.lng);
                return; // Passer à la ville suivante
            }
            
            // Vérifier le slug
            if (!city.slug) {
                console.error('DEBUG: Slug manquant pour', city.name);
                return;
            }
            
            // Créer un marqueur personnalisé
            var marker = L.marker([city.lat, city.lng], {
                icon: L.divIcon({
                    className: 'custom-marker',
                    html: '<div class="marker-icon"></div>',
                    iconSize: [30, 30],
                    iconAnchor: [15, 30],
                    popupAnchor: [0, -30]
                })
            });
            
            console.log('DEBUG: Marqueur créé pour', city.name);
            
            // Ajouter le slug du ville au marqueur pour la synchronisation
            marker.citySlug = city.slug;
            
            // Popup avec fiche ville détaillée
            var lastRealisationHtml = '';
            if (city.last_realisation) {
                lastRealisationHtml = `
                    <div class="city-last-realisation">
                        <h4>Notre dernière réalisation à ${city.name}</h4>
                        <div class="last-realisation-card">
                            ${city.last_realisation.thumbnail ? `<img src="${city.last_realisation.thumbnail}" alt="${city.last_realisation.title}" class="last-realisation-thumb">` : ''}
                            <div class="last-realisation-info">
                                <h5><a href="${city.last_realisation.url}">${city.last_realisation.title}</a></h5>
                                <a href="${city.last_realisation.url}" class="btn-discover">Découvrir</a>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            var cityCardContent = `
                <div class="city-card-popup">
                    <div class="city-card-header">
                        <h3>${city.name}</h3>
                        <span class="city-card-category">${city.category || 'Réalisations'}</span>
                    </div>
                    <div class="city-card-content">
                        <div class="city-card-stats">
                            ${city.projects > 0 ? `
                            <div class="stat-item">
                                <span class="stat-number">${city.projects}</span>
                                <span class="stat-label">Projets</span>
                            </div>` : ''}
                            <div class="stat-item">
                                <span class="stat-number">${city.rating || '4.8'}/5</span>
                                <span class="stat-label">Satisfaction</span>
                            </div>
                        </div>
                        ${lastRealisationHtml}
                        <div class="city-card-cta">
                            <a href="${city.url}" class="btn-discover">Voir les réalisations</a>
                        </div>
                    </div>
                </div>
            `;
            
            marker.bindPopup(cityCardContent, {
                maxWidth: 300,
                className: 'city-popup',
                closeButton: false,
                closeOnClick: false,
                autoPan: true
            });
            
            // Gérer le clic sur le marqueur pour garder la pop-up active
            marker.on('click', function(e) {
                // Fermer le marqueur actif précédent
                if (activeMarker && activeMarker !== marker) {
                    activeMarker.closePopup();
                    if (activeMarker._icon) {
                        activeMarker._icon.classList.remove('marker-active');
                    }
                }
                
                // Activer ce marqueur
                marker.openPopup();
                if (marker._icon) {
                    marker._icon.classList.add('marker-active');
                }
                activeMarker = marker;
                
                // Empêcher la propagation pour ne pas fermer la pop-up
                L.DomEvent.stopPropagation(e);
            });
            
            // Ajouter le marqueur à la carte
            marker.addTo(map);
            
            // Stocker le marqueur avec le slug comme clé
            markers[city.slug] = marker;
        });
        
        console.log('DEBUG: Nombre de marqueurs ajoutés:', Object.keys(markers).length);
        
        // Débogage - vérifier les éléments trouvés
        console.log('DEBUG: Recherche des CTA villes...');
        var cityLinks = $('.city-link, .city-name, .btn-city, a[href*="metallier-"]');
        console.log('DEBUG: Éléments trouvés:', cityLinks.length);
        console.log('DEBUG: Éléments:', cityLinks);
        
        // Synchronisation hover entre les boutons et la carte
        cityLinks.on('mouseenter', function() {
            var citySlug = $(this).data('city');
            // Si pas de data-city, essayer d'extraire du href
            if (!citySlug && $(this).attr('href')) {
                var href = $(this).attr('href');
                var match = href.match(/metallier-([^\/\?#]+)/);
                if (match) {
                    citySlug = match[1];
                }
            }
            console.log('DEBUG: Hover sur ville:', citySlug);
            if (citySlug && markers[citySlug]) {
                markers[citySlug].openPopup();
                // Mettre en évidence le marqueur
                markers[citySlug].setZIndexOffset(1000);
            }
        }).on('mouseleave', function() {
            var citySlug = $(this).data('city');
            if (!citySlug && $(this).attr('href')) {
                var href = $(this).attr('href');
                var match = href.match(/metallier-([^\/\?#]+)/);
                if (match) {
                    citySlug = match[1];
                }
            }
            if (citySlug && markers[citySlug] && markers[citySlug] !== activeMarker) {
                markers[citySlug].closePopup();
                // Remettre le zIndex normal
                markers[citySlug].setZIndexOffset(0);
            }
        });
        
        // Gérer le clic sur les boutons des villes avec délégation
        $(document).on('click', '.city-link, .city-name, .btn-city, a[href*="metallier-"]', function(e) {
            e.preventDefault();
            console.log('DEBUG: Clic sur ville détecté');
            var citySlug = $(this).data('city');
            // Si pas de data-city, essayer d'extraire du href
            if (!citySlug && $(this).attr('href')) {
                var href = $(this).attr('href');
                var match = href.match(/metallier-([^\/\?#]+)/);
                if (match) {
                    citySlug = match[1];
                }
            }
            console.log('DEBUG: Slug extrait:', citySlug);
            if (citySlug && markers[citySlug]) {
                console.log('DEBUG: Ouverture de la pop-up pour:', citySlug);
                // Fermer le marqueur actif précédent
                if (activeMarker && activeMarker !== markers[citySlug]) {
                    activeMarker.closePopup();
                    if (activeMarker._icon) {
                        activeMarker._icon.classList.remove('marker-active');
                    }
                }
                
                // Activer le marqueur de cette ville
                markers[citySlug].openPopup();
                if (markers[citySlug]._icon) {
                    markers[citySlug]._icon.classList.add('marker-active');
                }
                activeMarker = markers[citySlug];
                
                // Centrer la carte sur le marqueur
                map.setView([markers[citySlug].getLatLng().lat, markers[citySlug].getLatLng().lng], 11);
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
        
        // Style pour le highlight et l'état actif des boutons et marqueurs
        $('<style>').text(`
            .city-highlight {
                background: rgba(240, 139, 24, 0.3) !important;
                border-color: rgba(240, 139, 24, 0.6) !important;
                transform: translateY(-2px) scale(1.05) !important;
            }
            
            .marker-active .marker-icon {
                background: #FF6B35 !important;
                box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.3), 0 2px 5px rgba(0,0,0,0.3) !important;
                transform: scale(1.2) !important;
            }
            
            .custom-marker .marker-icon {
                background: #F08B18;
                width: 30px;
                height: 30px;
                border-radius: 50% 50% 50% 0;
                transform: rotate(-45deg);
                border: 2px solid #fff;
                box-shadow: 0 2px 5px rgba(0,0,0,0.3);
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .custom-marker .marker-icon:before {
                content: 'AL';
                transform: rotate(45deg);
                color: white;
                font-weight: bold;
                font-size: 10px;
            }
            
            .city-card-cta {
                margin-top: 1rem;
                text-align: center;
                border-top: 1px solid rgba(0,0,0,0.1);
                padding-top: 1rem;
            }
            
            .city-card-cta .btn-discover {
                background: #F08B18;
                color: white !important;
                padding: 0.5rem 1.5rem;
                border-radius: 25px;
                text-decoration: none;
                font-weight: 500;
                transition: background 0.3s ease;
                display: inline-block;
            }
            
            .city-card-cta .btn-discover:hover {
                background: #FF6B35;
            }
        `).appendTo('head');
        
        console.log('DEBUG: Nombre de marqueurs ajoutés:', Object.keys(markers).length);
        
        // Ajuster la vue pour inclure tous les marqueurs avec un délai
        var markerArray = Object.values(markers);
        if (markerArray.length > 0) {
            setTimeout(function() {
                var group = new L.featureGroup(markerArray);
                map.fitBounds(group.getBounds().pad(0.4));
                console.log('DEBUG: Vue ajustée pour tous les marqueurs');
            }, 500);
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
                    map.fitBounds(group.getBounds().pad(0.4));
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
