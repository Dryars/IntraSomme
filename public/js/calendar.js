// Fichier : js/calendar.js

window.addEventListener('load', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        // --- Configuration de base ---
        initialView: 'timeGridWeek', // Vue semaine avec heures (idéal DSP)
        locale: 'fr', // Traduction française
        slotMinTime: "07:00:00", // Début de journée affiché
        slotMaxTime: "20:00:00", // Fin de journée affiché
        height: 'auto',
        nowIndicator: true, // Ligne rouge pour l'heure actuelle

        // --- Configuration de la barre d'outils (Header) ---
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'timeGridWeek,timeGridDay,dayGridMonth'
        },

        // --- Chargement des événements ---
        events: function(fetchInfo, successCallback, failureCallback) {
            // Appel à l'API PHP pour récupérer les événements
            fetch('api/calendar_events.php')
                .then(response => {
                    if (!response.ok) {
                        // Gère les erreurs HTTP (404, 500...)
                        throw new Error('Erreur de réseau ou du serveur (Statut HTTP ' + response.status + ')');
                    }
                    return response.json();
                })
                .then(data => {
                    // Si le JSON est valide, on passe les données à FullCalendar
                    successCallback(data);
                })
                .catch(err => {
                    console.error('Erreur de chargement des événements :', err);
                    // Affiche un message d'erreur dans le calendrier
                    successCallback([
                        {
                            title: "Erreur de chargement (Vérifiez la console)",
                            start: fetchInfo.startStr,
                            end: fetchInfo.startStr,
                            allDay: true,
                            color: "#EF4444" // Rouge d'erreur
                        }
                    ]);
                });
        },
        
        // --- Options d'interaction et de style ---
        editable: false, 
        navLinks: true, 
        eventColor: '#F97316',
        eventTextColor: 'white'
    });

    calendar.render();
});