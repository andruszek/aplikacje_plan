document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: 'Dzisiaj',
            month: 'Miesiąc',
            week: 'Tydzień',
            day: 'Dzień'
        },
        locale: 'pl',
        height: 'auto',
        slotMinTime: '07:00:00',
        slotMaxTime: '20:00:00',
        allDaySlot: false,
        nowIndicator: true,
		firstDay: 1,
        events: [
            {
                title: 'Przykładowe zajęcia',
                start: '2025-01-15T10:00:00',
                end: '2025-01-15T12:00:00'
            }
        ]
    });

    calendar.render();

    document.getElementById('today-button').addEventListener('click', function () {
        calendar.today();
    });
});
