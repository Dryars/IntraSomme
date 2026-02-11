<?php
// Démarrage de session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['user_id'] = 1;
$_SESSION['role'] = 'superadmin';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Carte des Équipes - CRM IntraSomme</title>
    <link rel="stylesheet" href="css/style.css">

    <!-- Leaflet CSS sans integrity -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        #map {
            height: 600px;
            width: 100%;
        }
    </style>
</head>
<body>
<?php include __DIR__ . '/header.php'; ?>

<div id="map" class="card"></div>

<?php include __DIR__ . '/footer.php'; ?>

<!-- Leaflet JS sans integrity -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
// Initialisation de la carte
let map = L.map('map').setView([49.901, 2.313], 13);

// Carte satellite gratuite (Esri WorldImagery)
L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
    attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, IGN, IGP, UPR-EGP, and the GIS User Community',
    maxZoom: 19
}).addTo(map);

// Mock positions des équipes
let equipes = [
    {name: "Équipe A", lat: 49.901, lng: 2.313},
    {name: "Équipe B", lat: 49.905, lng: 2.318},
    {name: "Équipe C", lat: 49.908, lng: 2.320}
];

// Ajouter des marqueurs
equipes.forEach(equipe => {
    L.marker([equipe.lat, equipe.lng]).addTo(map)
        .bindPopup(equipe.name);
});

// Mise à jour automatique toutes les 5 secondes (simulation)
setInterval(() => {
    equipes.forEach(equipe => {
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
</script>
</body>
</html>
