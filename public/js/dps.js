document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('dps-container');

    fetch('/IntraSomme/api/inscriptions_list.php')
    .then(r => r.json())
    .then(data => {
        data.forEach(dps => {
            const dpsCard = document.createElement('div');
            dpsCard.className = 'card';

            // Couleurs statut
            const statusColors = {
                non_prepare: '#F59E0B',
                prepare: '#3B82F6',
                en_cours: '#10B981',
                termine: '#6B7280'
            };

            const statusLabel = dps.statut.replace('_',' ');

            dpsCard.innerHTML = `
                <h2>${dps.dps_title}</h2>
                <p>Status: <span style="color:${statusColors[dps.statut] || '#000'}">${statusLabel}</span></p>
                <div id="map-${dps.id}" class="dps-map" style="height:300px;"></div>
                <h3>Postes et inventaire :</h3>
                <ul id="posts-${dps.id}"></ul>
            `;

            container.appendChild(dpsCard);

            // Initialisation carte
            const map = L.map(`map-${dps.id}`).setView([0,0], 2);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
                attribution:'&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Marqueurs équipes
            if(dps.teams) {
                dps.teams.forEach(team => {
                    if(team.lat && team.lng) {
                        L.marker([team.lat, team.lng])
                         .addTo(map)
                         .bindPopup(`Équipe: ${team.name}`);
                    }
                });
            }

            // Marqueurs postes + liste
            const postsList = document.getElementById(`posts-${dps.id}`);
            if(dps.posts) {
                dps.posts.forEach(post => {
                    L.marker([post.lat, post.lng], {icon: L.icon({iconUrl:'/IntraSomme/public/images/post_icon.png',iconSize:[25,25]})})
                     .addTo(map)
                     .bindPopup(`Poste: ${post.name}`);
                    
                    const li = document.createElement('li');
                    li.textContent = `${post.name} – Inventaire: ${post.inventory}`;
                    postsList.appendChild(li);
                });
            }

            // Adapter la vue pour tous les marqueurs
            const group = new L.featureGroup([
                ...(dps.teams || []).map(t => L.marker([t.lat,t.lng])),
                ...(dps.posts || []).map(p => L.marker([p.lat,p.lng]))
            ]);
            if(group.getLayers().length) map.fitBounds(group.getBounds().pad(0.5));
        });
    })
    .catch(err => {
        console.error('Erreur fetch DPS:', err);
        container.innerHTML = '<p>Impossible de charger les DPS.</p>';
    });
});
