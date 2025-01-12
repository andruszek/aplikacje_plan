document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
			left:'',
            center: 'prev,next',
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
        events: [
            {
                title: 'Przykładowe zajęcia',
                start: '2025-01-09T10:00:00',
                end: '2025-01-09T12:00:00'
            }
        ]
    });

    calendar.render();

    document.getElementById('today-button').addEventListener('click', function () {
        calendar.today();
    });
	
	 document.getElementById('share-button').addEventListener('click', function () {
        navigator.clipboard.writeText(window.location.href)
            .then(function () {
                alert('Skopiwoano link do planu!');
            })
            .catch(function (error) {
                console.error('Błąd kopiowania: ', error);
            });
    });
	
	
});


