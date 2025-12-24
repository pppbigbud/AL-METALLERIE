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
        
        // Tile layer avec style sombre pour matcher le thème
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
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
        var markers = [];
        taxonomyCities.forEach(function(city) {
            console.log('DEBUG: Ajout du marqueur pour', city.name);
            var marker = L.marker([city.lat, city.lng], { icon: customIcon })
                .addTo(map);
            
            // Popup avec nom de la ville et lien
            marker.bindPopup(
                '<div style="text-align: center; padding: 5px;">' +
                '<h4 style="margin: 0 0 5px 0; color: #222;">' + city.name + '</h4>' +
                '<a href="' + city.url + '" style="color: #F08B18; text-decoration: none; font-weight: bold;">Voir nos réalisations →</a>' +
                '</div>'
            );
            
            // Rendre le marqueur cliquable pour aller directement à la page
            marker.on('click', function() {
                window.location.href = city.url;
            });
            
            markers.push(marker);
        });
        
        console.log('DEBUG: Nombre de marqueurs ajoutés:', markers.length);
        
        // Ajuster la vue pour inclure tous les marqueurs
        if (markers.length > 0) {
            var group = new L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.1));
        }
        
        // Animation au survol des marqueurs
        $('.custom-marker').css('transition', 'transform 0.2s ease');
        
        // Ajouter un effet de zoom au survol
        markers.forEach(function(marker) {
            marker.on('mouseover', function() {
                this.setZIndexOffset(1000);
            });
            
            marker.on('mouseout', function() {
                this.setZIndexOffset(0);
            });
        });
        
        // Responsive : recadrer la carte au redimensionnement
        var resizeTimer;
        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                map.invalidateSize();
                if (markers.length > 0) {
                    var group = new L.featureGroup(markers);
                    map.fitBounds(group.getBounds().pad(0.1));
                }
            }, 250);
        });
        
        console.log('DEBUG: Carte entièrement initialisée');
    });
}

// Si Leaflet est déjà chargé au chargement du script, initialiser directement
if (typeof L !== 'undefined') {
    initializeMap();
}
