/**
 * Script pour la carte d'intervention dans les réalisations (mobile)
 */
jQuery(document).ready(function($) {
    // Initialiser la carte pour chaque réalisation
    $('.mobile-intervention-map').each(function() {
        var mapId = $(this).attr('id');
        var lieu = $(this).closest('.mobile-realisation-map').find('.mobile-map-address').text().trim();
        
        if (lieu && mapId) {
            initMobileMap(mapId, lieu);
        }
    });
    
    function initMobileMap(mapId, lieu) {
        // Vérifier si Leaflet est chargé
        if (typeof L === 'undefined') {
            console.warn('Leaflet non chargé - chargement manuel');
            loadLeafletAndInit(mapId, lieu);
            return;
        }
        
        createMap(mapId, lieu);
    }
    
    function loadLeafletAndInit(mapId, lieu) {
        // Charger Leaflet depuis CDN
        var leafletCSS = document.createElement('link');
        leafletCSS.rel = 'stylesheet';
        leafletCSS.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
        document.head.appendChild(leafletCSS);
        
        var leafletJS = document.createElement('script');
        leafletJS.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
        leafletJS.onload = function() {
            createMap(mapId, lieu);
        };
        document.head.appendChild(leafletJS);
    }
    
    function createMap(mapId, lieu) {
        // Coordonnées par défaut (centre de l'Auvergne)
        var defaultLat = 45.7772;
        var defaultLng = 3.0870;
        var lat = defaultLat;
        var lng = defaultLng;
        
        // Liste des villes avec leurs coordonnées
        var cities = {
            'Thiers': [45.8558, 3.5516],
            'Clermont-Ferrand': [45.7772, 3.0870],
            'Riom': [45.8931, 3.1135],
            'Issoire': [45.5436, 3.2505],
            'Vichy': [46.1234, 3.4257],
            'Coudes': [45.7333, 3.2833],
            'Lezoux': [45.8333, 3.3833],
            'Peschadoires': [45.8667, 3.5667],
            'Puy-de-Dôme': [45.7772, 3.0870],
            'Auvergne': [45.7772, 3.0870]
        };
        
        // Chercher la ville dans le lieu
        for (var city in cities) {
            if (lieu.toLowerCase().includes(city.toLowerCase())) {
                lat = cities[city][0];
                lng = cities[city][1];
                break;
            }
        }
        
        // Créer la carte
        var map = L.map(mapId, {
            center: [lat, lng],
            zoom: 13,
            zoomControl: true,
            scrollWheelZoom: false,
            dragging: true,
            tap: true // Pour mobile
        });
        
        // Ajouter la couche de tuiles (dark theme pour mobile)
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
            subdomains: 'abcd',
            maxZoom: 19
        }).addTo(map);
        
        // Ajouter un marqueur personnalisé
        var customIcon = L.divIcon({
            html: '<div style="background: #F08B18; width: 30px; height: 30px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 3px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>',
            iconSize: [30, 30],
            iconAnchor: [15, 30],
            popupAnchor: [0, -30],
            className: 'custom-marker'
        });
        
        var marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);
        
        // Ajouter un popup
        marker.bindPopup('<strong>AL Métallerie & Soudure</strong><br>' + lieu).openPopup();
        
        // Adapter pour mobile
        if (window.innerWidth <= 768) {
            map.dragging.enable();
            map.touchZoom.enable();
            map.doubleClickZoom.enable();
            map.scrollWheelZoom.disable();
        }
    }
});
