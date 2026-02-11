// Coordonnées initiales (centre de la carte)
let map = L.map('map').setView([49.901, 2.313], 13);

// Satellite layer (Mapbox)
L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/satellite-v9/tiles/{z}/{x}/{y}?access_token=YOUR_MAPBOX_ACCESS_TOKEN', {
    tileSize: 512,
    zoomOffset: -1,
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> | Mapbox',
    maxZoom: 22
}).addTo(map);

// Mock positions des équipes
let equipes = [
    {name: "Équipe A", lat: 49.901, lng: 2.313},
    {name: "Équipe B", lat: 49.908, lng: 2.320},
    {name: "Équipe C", lat: 49.905, lng: 2.318}
];

// Ajouter des marqueurs
equipes.forEach(equipe => {
    L.marker([equipe.lat, equipe.lng]).addTo(map)
        .bindPopup(equipe.name)
        .openPopup();
});

// Option : Mise à jour en temps réel toutes les 5s (mock)
setInterval(() => {
    equipes.forEach(equipe => {
        // Déplacement aléatoire pour mock
        equipe.lat += (Math.random()-0.5)/1000;
        equipe.lng += (Math.random()-0.5)/1000;
    });

    map.eachLayer(layer => {
        if(layer instanceof L.Marker) map.removeLayer(layer);
    });

    equipes.forEach(equipe => {
        L.marker([equipe.lat, equipe.lng]).addTo(map)
            .bindPopup(equipe.name);
    });
}, 5000);
