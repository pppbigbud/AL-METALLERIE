/**
 * Script pour la carte d'intervention dans les réalisations (desktop)
 */
jQuery(document).ready(function($) {
    // Initialiser la carte pour chaque réalisation
    $('.realisation-intervention-map').each(function() {
        var mapId = $(this).attr('id');
        var lieu = $(this).closest('.realisation-map-section').find('.map-address span').text().trim();
        
        if (lieu && mapId) {
            initRealisationMap(mapId, lieu);
        }
    });
    
    function initRealisationMap(mapId, lieu) {
        // Vérifier si Leaflet est chargé
        if (typeof L === 'undefined') {
            console.error('Leaflet n\'est pas chargé');
            return;
        }
        
        // Coordonnées approximatives pour les villes d'intervention
        var cityCoords = {
            'Thiers': [45.8556, 3.5747],
            'Clermont-Ferrand': [45.7772, 3.0870],
            'Vichy': [46.1234, 3.4269],
            'Riom': [45.8931, 3.1137],
            'Issoire': [45.5439, 3.2525],
            'Ambert': [45.5489, 3.7511],
            'Coudes': [45.7344, 3.1972],
            'Peschadoires': [45.8567, 3.5634],
            'Courpière': [45.8683, 3.6189],
            'Olliergues': [45.7333, 3.7167],
            'Saint-Éloy-les-Mines': [46.2667, 3.4667],
            'Aigueperse': [45.9667, 3.3167],
            'Besse-et-Saint-Anastaise': [45.5167, 2.9833],
            'Chamalières': [45.7833, 3.1167],
            'Châtel-Guyon': [45.9667, 3.1167],
            'Cébazat': [45.8167, 3.1500],
            'Combronde': [45.9167, 3.2833],
            'Gerzat': [45.8333, 3.1500],
            'Issoire': [45.5439, 3.2525],
            'Le Cendre': [45.7333, 3.2000],
            'Maringes': [45.9500, 3.2667],
            'Pont-du-Château': [45.8667, 3.3167],
            'Riom': [45.8931, 3.1137],
            'Royat': [45.7833, 3.1167],
            'Saint-Genès-Champanelle': [45.7500, 2.9833],
            'Saint-Eloy-les-Mines': [46.2667, 3.4667],
            'Volvic': [45.8500, 3.1167],
            'Aubière': [45.7667, 3.1333],
            'Chamalières': [45.7833, 3.1167],
            'Châtel-Guyon': [45.9667, 3.1167],
            'Clermont-Ferrand': [45.7772, 3.0870],
            'Cébazat': [45.8167, 3.1500],
            'Gerzat': [45.8333, 3.1500],
            'Issoire': [45.5439, 3.2525],
            'Le Cendre': [45.7333, 3.2000],
            'Maringes': [45.9500, 3.2667],
            'Pont-du-Château': [45.8667, 3.3167],
            'Riom': [45.8931, 3.1137],
            'Royat': [45.7833, 3.1167],
            'Volvic': [45.8500, 3.1167]
        };
        
        // Extraire la ville du lieu
        var city = extractCity(lieu);
        var coords = cityCoords[city] || [45.7772, 3.0870]; // Default: Clermont-Ferrand
        
        // Initialiser la carte
        var map = L.map(mapId).setView(coords, 13);
        
        // Ajouter la couche de tuiles OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);
        
        // Ajouter un marqueur personnalisé
        var customIcon = L.divIcon({
            html: '<div style="background: #F08B18; width: 30px; height: 30px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 3px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>',
            iconSize: [30, 30],
            iconAnchor: [15, 30],
            popupAnchor: [0, -30],
            className: 'custom-map-marker'
        });
        
        // Ajouter le marqueur
        var marker = L.marker(coords, { icon: customIcon }).addTo(map);
        
        // Ajouter un popup
        marker.bindPopup('<strong>AL Métallerie</strong><br>' + lieu).openPopup();
        
        // Animation d'entrée
        setTimeout(function() {
            map.invalidateSize();
        }, 300);
    }
    
    function extractCity(lieu) {
        // Extraire le mot principal (probablement la ville)
        var words = lieu.split(/[\s,]+/);
        for (var i = 0; i < words.length; i++) {
            var word = words[i].replace(/[^\w\-]/g, '');
            // Vér personaliser avec des émoticônes
            if (word.length > 2) {
                // Vér personaliser avec des émoticônes
                return word;
            }
        }
        return lieu;
    }
});
