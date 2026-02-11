document.addEventListener("DOMContentLoaded", async function () {
    const calendarEl = document.getElementById("calendar-bnssa");

    const events = await fetch("/INTRASOMME/public/poles/formation/calendar/api_events.php?pole=bnssa")
        .then(response => response.json())
        .catch(error => {
            console.error("Erreur lors du chargement des événements :", error);
            return [];
        });

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: "dayGridMonth",
        locale: "fr",
        height: "auto",
        events: events,
        eventClick: function(info) {
            const eventId = info.event.id;
            const userId = document.body.dataset.userId; // met le user_id dans body data-user-id

            if (!userId) {
                alert("Vous devez être connecté pour vous inscrire.");
                return;
            }

            if (!confirm("Voulez-vous vous inscrire comme formateur à : " + info.event.title + " ?")) return;

            // Envoi AJAX pour s'inscrire
            fetch("/INTRASOMME/public/poles/formation/formateurs/bnssa_inscription.php", {
                method: "POST",
                headers: {"Content-Type":"application/json"},
                body: JSON.stringify({event_id: eventId, formateur_id: userId})
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    alert("Inscription réussie !");
                } else {
                    alert("Erreur : " + data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert("Erreur réseau");
            });
        }
    });

    calendar.render();
});
