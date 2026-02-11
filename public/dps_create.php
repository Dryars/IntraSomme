<?php
// Fichier : dps_create.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include __DIR__ . '/header.php'; 
?>

<!-- CSS Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Planifier un Nouveau DPS</h1>
        <a href="DPS.php" class="text-gray-500 hover:text-blue-600 transition">Annuler</a>
    </div>

    <form id="create-dps-form" onsubmit="submitDPS(event)" class="space-y-8">
        
        <!-- 1. Informations Générales -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h2 class="text-xl font-bold text-blue-900 border-b pb-2 mb-4 flex items-center">
                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded mr-2">1</span> 
                Informations Générales
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom de l'événement</label>
                    <input type="text" id="dps_title" class="w-full border-gray-300 rounded-lg p-2 shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Ex: Trail des Illaminés" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                    <input type="datetime-local" id="dps_start" class="w-full border-gray-300 rounded-lg p-2 shadow-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lieu principal</label>
                    <input type="text" id="dps_lieu" class="w-full border-gray-300 rounded-lg p-2 shadow-sm" placeholder="Ex: Parc Saint-Pierre">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Chef de Dispositif</label>
                    <input type="text" id="dps_chef" class="w-full border-gray-300 rounded-lg p-2 shadow-sm" placeholder="Nom du responsable">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Canal Radio</label>
                    <input type="text" id="dps_canal" class="w-full border-gray-300 rounded-lg p-2 shadow-sm" placeholder="Ex: 04 ou Tac 1">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description & Risques</label>
                    <textarea id="dps_desc" rows="2" class="w-full border-gray-300 rounded-lg p-2 shadow-sm" placeholder="Risques particuliers, accès difficiles..."></textarea>
                </div>
            </div>
        </div>

        <!-- 2. Cartographie des Postes -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h2 class="text-xl font-bold text-blue-900 border-b pb-2 mb-4 flex items-center justify-between">
                <span class="flex items-center">
                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded mr-2">2</span> 
                    Implantation des Postes
                </span>
                <span class="text-xs font-normal text-gray-500 italic">Cliquez sur la carte pour ajouter un poste</span>
            </h2>
            
            <div id="map-creation" class="h-96 w-full bg-gray-100 rounded-lg border border-gray-300 z-0 mb-4"></div>
            
            <div id="posts-list" class="space-y-2 text-sm text-gray-500">
                <p class="text-center italic" id="no-posts-msg">Aucun poste placé pour le moment.</p>
            </div>
        </div>

        <!-- 3. Moyens & Vecteurs (Génère l'Inventaire) -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h2 class="text-xl font-bold text-blue-900 border-b pb-2 mb-4 flex items-center justify-between">
                <span class="flex items-center">
                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded mr-2">3</span> 
                    Moyens Engagés
                </span>
                
                <div class="flex space-x-2">
                    <button type="button" onclick="addResource('vpsp')" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1 rounded border border-gray-300 flex items-center transition">
                        <span class="mr-1">🚑</span> + VPSP
                    </button>
                    <button type="button" onclick="addResource('bateau')" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1 rounded border border-gray-300 flex items-center transition">
                        <span class="mr-1">🚤</span> + Bateau
                    </button>
                    <button type="button" onclick="addResource('pedestre')" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1 rounded border border-gray-300 flex items-center transition">
                        <span class="mr-1">🚶</span> + Binôme
                    </button>
                </div>
            </h2>
            
            <p class="text-sm text-gray-500 mb-4 italic">Ajouter un moyen générera automatiquement la liste de contrôle associée (Sac Lot A, DSA, Vérifications véhicule...).</p>

            <div id="resources-container" class="space-y-3">
                <div class="text-center text-gray-400 py-4 border-2 border-dashed border-gray-200 rounded-lg" id="empty-resources">
                    Aucun moyen engagé. Cliquez sur les boutons ci-dessus.
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end pt-4 sticky bottom-4">
            <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg font-bold hover:bg-green-700 shadow-lg flex items-center transform hover:scale-105 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Valider et Créer le DPS
            </button>
        </div>

    </form>
</div>

<!-- JS Leaflet -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // --- MAP ---
    // Initialisation carte (Centre Amiens par défaut)
    const map = L.map('map-creation').setView([49.894, 2.295], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);
    
    let markers = []; // Stocke les données des postes {name, lat, lng}

    // Clic sur la carte -> Ajouter un poste
    map.on('click', function(e) {
        const name = prompt("Nom de ce poste (ex: Poste Central, Signaleur 1) ?");
        if(name) {
            addMarker(e.latlng.lat, e.latlng.lng, name);
        }
    });

    function addMarker(lat, lng, name) {
        const marker = L.marker([lat, lng], {draggable: true}).addTo(map);
        marker.bindPopup(`<b>${name}</b>`).openPopup();
        
        // Stockage
        markers.push({ name: name, lat: lat, lng: lng, layer: marker });
        
        renderPostsList();
        
        // Mise à jour si déplacement
        marker.on('dragend', function(event) {
            const pos = marker.getLatLng();
            // Retrouver et mettre à jour dans le tableau
            const item = markers.find(m => m.layer === marker);
            if(item) { item.lat = pos.lat; item.lng = pos.lng; renderPostsList(); }
        });
    }

    function renderPostsList() {
        const listContainer = document.getElementById('posts-list');
        const emptyMsg = document.getElementById('no-posts-msg');
        
        if (markers.length > 0 && emptyMsg) emptyMsg.style.display = 'none';
        
        listContainer.innerHTML = markers.map((m, idx) => `
            <div class="flex justify-between items-center bg-blue-50 p-2 rounded border border-blue-100 text-sm">
                <span class="font-bold text-blue-800">📍 ${m.name}</span>
                <span class="text-xs text-gray-500">(${m.lat.toFixed(4)}, ${m.lng.toFixed(4)})</span>
                <button type="button" onclick="removePost(${idx})" class="text-red-400 hover:text-red-600 font-bold px-2">×</button>
            </div>
        `).join('');
    }

    function removePost(index) {
        map.removeLayer(markers[index].layer);
        markers.splice(index, 1); // Retire du tableau
        renderPostsList();
        if(markers.length === 0) document.getElementById('no-posts-msg').style.display = 'block';
    }

    // --- GESTION DES RESSOURCES ---
    let resources = [];
    
    const templates = {
        'vpsp': { icon: '🚑', name: 'VPSP', details: 'Checklist: Mécanique, Cellule, Lot A, DSA, O2' },
        'bateau': { icon: '🚤', name: 'Embarcation', details: 'Checklist: Armement sécu, Moteur, Radio' },
        'pedestre': { icon: '🚶', name: 'Binôme', details: 'Checklist: Sac Inter, Radio' }
    };

    function addResource(type) {
        document.getElementById('empty-resources').style.display = 'none';
        
        const count = resources.filter(r => r.type === type).length + 1;
        const name = `${templates[type].name} ${count}`;
        
        resources.push({ type: type, name: name });
        renderResources();
    }

    function renderResources() {
        const container = document.getElementById('resources-container');
        container.innerHTML = resources.map((res, idx) => `
            <div class="flex items-start justify-between bg-white border border-gray-200 p-3 rounded-lg shadow-sm">
                <div class="flex items-start">
                    <span class="text-2xl mr-3 bg-gray-100 p-2 rounded-lg">${templates[res.type].icon}</span>
                    <div>
                        <div class="flex items-center gap-2">
                            <input type="text" value="${res.name}" onchange="updateResourceName(${idx}, this.value)" class="font-bold text-gray-800 border-b border-transparent focus:border-blue-500 focus:outline-none w-48 bg-transparent" placeholder="Nom...">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">${templates[res.type].details}</p>
                    </div>
                </div>
                <button type="button" onclick="removeResource(${idx})" class="text-gray-400 hover:text-red-500 font-bold px-2">×</button>
            </div>
        `).join('');
    }

    function updateResourceName(idx, val) { resources[idx].name = val; }
    
    function removeResource(idx) {
        resources.splice(idx, 1);
        renderResources();
        if(resources.length === 0) document.getElementById('empty-resources').style.display = 'block';
    }

    // --- SOUMISSION ---
    function submitDPS(e) {
        e.preventDefault();
        
        const dpsData = {
            title: document.getElementById('dps_title').value,
            start_date: document.getElementById('dps_start').value,
            location: document.getElementById('dps_lieu').value,
            chef: document.getElementById('dps_chef').value,
            canal: document.getElementById('dps_canal').value,
            description: document.getElementById('dps_desc').value,
            // On ne garde que les données pures (pas l'objet layer Leaflet)
            posts: markers.map(m => ({name: m.name, lat: m.lat, lng: m.lng})),
            teams: resources,
            gear: [] 
        };

        // Envoi vers le script PHP qui gère la session
        fetch('api/save_dps.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(dpsData)
        })
        .then(r => r.json())
        .then(resp => {
            if(resp.success) {
                alert("✅ DPS Créé avec succès !");
                window.location.href = 'DPS.php'; // Retour à la liste
            } else {
                alert("Erreur: " + (resp.message || "Inconnue"));
            }
        })
        .catch(err => {
            console.error(err);
            alert("Erreur technique lors de la sauvegarde.");
        });
    }

    // Fix affichage carte au chargement
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => { map.invalidateSize(); }, 500);
    });
</script>

<?php include __DIR__ . '/footer_simple.php'; ?>