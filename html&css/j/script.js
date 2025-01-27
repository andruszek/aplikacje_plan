document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    // Inicjalizacja kalendarza
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: '',
            center: 'prev,today,next',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            share: 'Udostępnij',
            today: 'Dzisiaj',
            month: 'Miesiąc',
            week: 'Tydzień',
            day: 'Dzień'
        },
        locale: 'pl',
        contentHeight: 'auto',
        slotMinTime: '07:00:00',
        slotMaxTime: '21:00:00',
        allDaySlot: false,
        nowIndicator: true,
        firstDay: 1,
        events: [], // Puste na start
        eventContent: function (info) {
            const extendedProps = info.event.extendedProps;
            const titleEl = document.createElement('div');
            titleEl.textContent = info.event.title;

            const extraInfoEl = document.createElement('div');
            extraInfoEl.innerHTML = `
                <small>
                    ${extendedProps.forma_zajec || 'Brak informacji'}<br>
                    ${extendedProps.budynek || 'Brak informacji'}<br>
                    ${extendedProps.sala || 'Brak informacji'}
                    ${extendedProps.imie || 'Brak informacji'}
                </small>
            `;

            return { domNodes: [titleEl, extraInfoEl] };
        }
    });

    function fetchEvents(filters) {
        const queryParams = new URLSearchParams(filters).toString();
        fetch(`fetch_classes.php?${queryParams}`)
            .then(response => response.json())
            .then(data => {
                const events = data.map(item => ({
                    title: item.Subject_Name,
                    start: `${item.Date}T${item.Start}`,
                    end: `${item.Date}T${item.End}`,
                    extendedProps: {
                        forma_zajec: item.Status,
                        budynek: item.Building_Name,
                        sala: item.Room_Name,
                        imie: item.FirstName + ' ' + item.LastName

                    },
                    classNames: [`status-${item.Status.toLowerCase().replace(/\s+/g, '-')}`]
                }));

                calendar.removeAllEvents();
                calendar.addEventSource(events);
            })
            .catch(error => console.error("Błąd podczas pobierania wydarzeń:", error));
    }

    const form = document.getElementById('filterForm');
    form.addEventListener('submit', function (e) {
        e.preventDefault();


        const subjectName = document.getElementById('subject').value.trim();
        const teacherName = document.getElementById('teacher').value.trim();
        const roomName = document.getElementById('room').value.trim();
        const groupName = document.getElementById('group').value.trim();
        const buildingName = document.getElementById('department').value.trim(); // Wydział

        const filters = {
            subjectName,
            teacherName,
            roomName,
            groupName,
            buildingName
        };


        Object.keys(filters).forEach(key => {
            if (!filters[key]) {
                delete filters[key];
            }
        });

        fetchEvents(filters);
    });

    calendar.render();
});
