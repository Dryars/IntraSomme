document.addEventListener("DOMContentLoaded", async function () {
    const calendarEl = document.getElementById("calendar-bnssa");

     const events = await fetch("/INTRASOMME/public/poles/formation/calendar/api_events.php?pole=bnssa")
        .then(response => response.json())
        .catch(error => {
            console.error("Erreur lors du chargement des événements :", error);
            return [];
        });

        console.log(events);

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: "dayGridMonth",
        locale: "fr",
        height: "auto",
        events: events,
        selectable: true,
    });

    calendar.render();

    const form = document.getElementById("event-form");
    // const deleteBtn = document.getElementById("delete-btn");

    form.addEventListener("submit", function(e){
        e.preventDefault();

        let formData = new FormData(form);

        fetch("/INTRASOMME/public/poles/formation/calendar/events_save.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                alert(data.message);
                calendar.refetchEvents();
                form.reset();
            } else {
                alert("Erreur : " + data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert("Erreur réseau ou PHP");
        });
    });

    // deleteBtn.addEventListener("click", function(){
    //     const id = document.getElementById("event-id").value;
    //     if(!id) return alert("Sélectionnez un événement à supprimer");

    //     if(!confirm("Supprimer cet événement ?")) return;

    //     fetch("/INTRASOMME/public/poles/formation/calendar/event_delete.php", {
    //         method: "POST",
    //         headers: {"Content-Type": "application/json"},
    //         body: JSON.stringify({id})
    //     })
    //     .then(res => res.json())
    //     .then(data => {
    //         alert(data.message);
    //         calendar.refetchEvents();
    //         form.reset();
    //     });
    // });
});
