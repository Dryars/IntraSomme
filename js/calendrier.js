document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        selectable: true,
        editable: true,
        events: 'php/fetch_events.php',
        select: function(info) {
            var titre = prompt('Nom de l’événement :');
            if (titre) {
                calendar.addEvent({
                    title: titre,
                    start: info.startStr,
                    end: info.endStr,
                    allDay: info.allDay
                });
                fetch('php/add_event.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        title: titre,
                        start: info.startStr,
                        end: info.endStr
                    })
                });
            }
            calendar.unselect();
        }
    });
    calendar.render();
});
