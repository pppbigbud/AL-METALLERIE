/**
 * JavaScript pour la carte interactive des pages catégories
 * Utilise Leaflet.js et OpenStreetMap (gratuit)
 * 
 * @package ALMetallerie
 * @since 1.0.0
 */

jQuery(document).ready(function($) {
    // Vérifier si la carte existe sur la page
    if ($('#taxonomy-map').length === 0) return;
    
    // Vérifier si nous avons des villes à afficher
    if (typeof taxonomyCities === 'undefined' || taxonomyCities.length === 0) return;
    
    // Initialiser la carte centrée sur le Puy-de-Dôme
    var map = L.map('taxonomy-map').setView([45.7772, 3.3870], 9);
    
    // Tile layer avec style sombre pour matcher le thème
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 19
    }).addTo(map);
    
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
});
