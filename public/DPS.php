<?php
// Fichier : DPS.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include __DIR__ . '/header.php'; 
?>

<!-- Inclusion CSS Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Dispositifs (DPS)</h1>
            <p class="text-gray-500 mt-1">Supervision opérationnelle et géolocalisation des équipes.</p>
        </div>
        <a href="dps_create.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow flex items-center transition">
            <svg class="w-5 h-5 mr-2" ...></svg>
            Nouveau DPS
        </a>
    </div>

    <!-- Conteneur des cartes DPS -->
    <div id="dps-container" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="col-span-full text-center py-12 text-gray-400">
            <svg class="animate-spin h-8 w-8 mx-auto mb-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            Chargement des dispositifs en cours...
        </div>
    </div>

</div>

<!-- Inclusion JS Leaflet -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Construction de l'URL de l'API avec $root
    const apiPath = '<?php echo isset($root) ? $root . "/api/dps_list.php" : "/api/dps_list.php"; ?>';
    // URL de la page de détail
    const detailPath = '<?php echo isset($root) ? $root . "/dps_detail.php" : "/dps_detail.php"; ?>';
    
    const container = document.getElementById('dps-container');

    console.log("Tentative de chargement depuis :", apiPath);

    fetch(apiPath)
    .then(r => {
        if (!r.ok) {
            throw new Error(`Erreur HTTP ${r.status} (${r.statusText})`);
        }
        return r.json().catch(e => {
            throw new Error("Données invalides (Erreur JSON). Vérifiez api/dps_list.php");
        });
    })
    .then(data => {
        container.innerHTML = '';

        if(data.length === 0) {
            container.innerHTML = '<p class="text-center text-gray-500 col-span-full">Aucun DPS planifié.</p>';
            return;
        }

        data.forEach(dps => {
            const dpsCard = document.createElement('div');
            // Ajout de group et hover pour l'effet interactif
            dpsCard.className = 'bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden flex flex-col hover:shadow-xl transition duration-300';

            // Gestion des couleurs de statut
            let statusClass = 'bg-gray-100 text-gray-800';
            let statusLabel = dps.statut;
            switch(dps.statut) {
                case 'en_cours': statusClass = 'bg-green-100 text-green-800 border-green-200'; statusLabel = 'En Cours 🟢'; break;
                case 'prepare': statusClass = 'bg-blue-100 text-blue-800 border-blue-200'; statusLabel = 'Prêt 🔵'; break;
                case 'non_prepare': statusClass = 'bg-orange-100 text-orange-800 border-orange-200'; statusLabel = 'À Préparer 🟠'; break;
            }

            dpsCard.innerHTML = `
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-start bg-gray-50">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">
                            <a href="${detailPath}?id=${dps.id}" class="hover:text-blue-600 transition">${dps.title}</a>
                        </h2>
                        <div class="text-sm text-gray-500 flex items-center mt-1">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            ${dps.date}
                        </div>
                        <div class="text-sm text-gray-500 flex items-center mt-1">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            ${dps.lieu}
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold border ${statusClass}">${statusLabel}</span>
                </div>
                
                <!-- Carte cliquable qui redirige -->
                <a href="${detailPath}?id=${dps.id}" class="block relative group">
                    <div id="map-${dps.id}" class="h-64 w-full bg-gray-100 z-0 grayscale group-hover:grayscale-0 transition duration-500"></div>
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition flex items-center justify-center">
                        <span class="opacity-0 group-hover:opacity-100 bg-white text-gray-800 px-4 py-2 rounded-lg font-bold shadow transform translate-y-4 group-hover:translate-y-0 transition">Accéder au terrain</span>
                    </div>
                </a>

                <div class="p-6 flex-grow">
                    <h3 class="font-semibold text-gray-700 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Moyens & Postes
                    </h3>
                    <ul class="space-y-2" id="posts-${dps.id}"></ul>
                </div>
                <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 flex justify-end space-x-2">
                     <button class="text-sm text-gray-600 hover:text-blue-600 font-medium">Modifier</button>
                     <a href="${detailPath}?id=${dps.id}" class="text-sm bg-blue-600 text-white px-3 py-1.5 rounded hover:bg-blue-700 font-bold transition">Accéder au Dispositif →</a>
                </div>
            `;
            container.appendChild(dpsCard);

            // Initialisation Leaflet
            const map = L.map(`map-${dps.id}`, { zoomControl: false, dragging: false, scrollWheelZoom: false }).setView([dps.lat, dps.lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap contributors' }).addTo(map);

            if(dps.teams) {
                dps.teams.forEach(team => {
                    if(team.lat && team.lng) L.marker([team.lat, team.lng]).addTo(map);
                });
            }

            const postsList = document.getElementById(`posts-${dps.id}`);
            if(dps.posts) {
                var redIcon = new L.Icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });
                dps.posts.forEach(post => {
                    L.marker([post.lat, post.lng], {icon: redIcon}).addTo(map);
                    const li = document.createElement('li');
                    li.className = "text-sm text-gray-600 flex justify-between";
                    li.innerHTML = `<span>📍 ${post.name}</span><span class="text-xs bg-gray-100 px-2 py-0.5 rounded text-gray-500 truncate max-w-[150px]">${post.inventory}</span>`;
                    postsList.appendChild(li);
                });
            }
            setTimeout(() => { map.invalidateSize(); }, 100);
        });
    })
    .catch(err => {
        console.error('Erreur fetch DPS:', err);
        container.innerHTML = `
            <div class="col-span-full bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                <strong class="font-bold flex items-center"><svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Erreur technique !</strong>
                <span class="block mt-1">${err.message}</span>
            </div>
        `;
    });
});
</script>

<?php include __DIR__ . '/footer_simple.php'; ?>