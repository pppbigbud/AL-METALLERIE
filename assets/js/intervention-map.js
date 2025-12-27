/**
 * Script pour la carte des zones d'intervention
 * Similaire à la carte de contact mais avec un cercle de rayon d'intervention
 */

jQuery(document).ready(function($) {
    // Vérifier si la carte d'intervention existe
    if ($('#intervention-map').length === 0) {
        return;
    }

    // Initialiser la carte
    function initInterventionMap() {
        console.log('=== DÉBUT INITIALISATION CARTE INTERVENTION ===');
        
        // Vérifier si Leaflet est chargé
        if (typeof L === 'undefined') {
            console.error('Leaflet (L) n\'est pas défini');
            return;
        }

        // Coordonnées de AL Métallerie (Peschadoires)
        var centerLocation = [45.8167, 3.4833];
        
        // Créer la carte
        var map = L.map('intervention-map').setView(centerLocation, 8);

        // Ajouter la couche de tiles OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Icône personnalisée pour le marqueur principal
        var customIcon = L.divIcon({
            html: '<div style="background: #F08B18; width: 40px; height: 40px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"><div style="transform: rotate(45deg); text-align: center; line-height: 40px; color: white; font-weight: bold;">AL</div></div>',
            className: 'custom-marker',
            iconSize: [40, 40],
            iconAnchor: [20, 40],
            popupAnchor: [0, -40]
        });

        // Marqueur principal - AL Métallerie
        var mainMarker = L.marker(centerLocation, { icon: customIcon }).addTo(map);
        mainMarker.bindPopup('<strong>AL Métallerie</strong><br>14 route de Maringues<br>63920 Peschadoires<br><a href="tel:+33673333532">06 73 33 35 32</a>').openPopup();

        // Cercle de rayon d'intervention (150km)
        var interventionCircle = L.circle(centerLocation, {
            color: '#F08B18',
            fillColor: '#F08B18',
            fillOpacity: 0.1,
            radius: 150000 // 150km en mètres
        }).addTo(map);

        // Ajouter les villes principales dans la zone d'intervention
        var cities = [
            { name: 'Clermont-Ferrand', coords: [45.7772, 3.0870], popup: 'Clermont-Ferrand - Préfecture du Puy-de-Dôme' },
            { name: 'Thiers', coords: [45.8555, 3.5487], popup: 'Thiers - Ville de AL Métallerie' },
            { name: 'Vichy', coords: [46.1240, 3.4204], popup: 'Vichy - Station thermale' },
            { name: 'Montluçon', coords: [46.3400, 2.6074], popup: 'Montluçon - Ville industrielle' },
            { name: 'Moulins', coords: [46.5660, 3.3327], popup: 'Moulins - Préfecture de l\'Allier' },
            { name: 'Aurillac', coords: [44.9284, 2.4443], popup: 'Aurillac - Préfecture du Cantal' },
            { name: 'Le Puy-en-Velay', coords: [45.0417, 3.8833], popup: 'Le Puy-en-Velay - Préfecture de la Haute-Loire' },
            { name: 'Issoire', coords: [45.5436, 3.2503], popup: 'Issoire - Ville du Puy-de-Dôme' },
            { name: 'Ambert', coords: [45.5489, 3.6203], popup: 'Ambert - Ville du Puy-de-Dôme' },
            { name: 'Brioude', coords: [45.2988, 3.2333], popup: 'Brioude - Ville de la Haute-Loire' },
            { name: 'Cusset', coords: [46.1321, 3.4597], popup: 'Cusset - Ville de l\'Allier' },
            { name: 'Yzeure', coords: [46.5647, 3.3545], popup: 'Yzeure - Ville de l\'Allier' },
            { name: 'Saint-Flour', coords: [45.0333, 3.0833], popup: 'Saint-Flour - Ville du Cantal' },
            { name: 'Mauriac', coords: [45.2167, 2.2500], popup: 'Mauriac - Ville du Cantal' },
            { name: 'Yssingeaux', coords: [45.1333, 4.1167], popup: 'Yssingeaux - Ville de la Haute-Loire' }
        ];

        // Icône pour les villes
        var cityIcon = L.divIcon({
            html: '<div style="background: #222; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; box-shadow: 0 1px 3px rgba(0,0,0,0.3);"></div>',
            className: 'city-marker',
            iconSize: [20, 20],
            iconAnchor: [10, 10],
            popupAnchor: [0, -10]
        });

        // Ajouter les marqueurs des villes
        cities.forEach(function(city) {
            var marker = L.marker(city.coords, { icon: cityIcon }).addTo(map);
            marker.bindPopup(city.popup);
        });

        // Ajuster la vue pour montrer le cercle complet
        setTimeout(function() {
            map.fitBounds(interventionCircle.getBounds().pad(0.1));
        }, 100);

        console.log('=== CARTE INTERVENTION INITIALISÉE ===');
    }

    // Initialiser la carte quand le DOM est prêt
    initInterventionMap();

    // Gérer le redimensionnement de la fenêtre
    $(window).on('resize', function() {
        if (window.interventionMap) {
            window.interventionMap.invalidateSize();
        }
    });
});
