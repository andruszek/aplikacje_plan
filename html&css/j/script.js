document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
			left:'',
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
        events: [
            {
                title: 'Przykładowe zajęcia',
                start: '2025-01-09T10:00:00',
                end: '2025-01-09T12:00:00'
            }
        ]
    });

    calendar.render();

    // document.getElementById('today-button').addEventListener('click', function () {
    //     calendar.today();
    // });
	
	 document.getElementById('share-button').addEventListener('click', function () {
        navigator.clipboard.writeText(window.location.href)
            .then(function () {
                alert('Skopiwoano link do planu!');
            })
            .catch(function (error) {
                console.error('Błąd kopiowania: ', error);
            });
    });

    loadSavedFilters();

    document.querySelector('.filter-favorite').addEventListener('click', function () {
        saveFilters();
    });

    function saveFilters() {
        const filters = {
            department: document.getElementById('department').value,
            lecturer: document.getElementById('lecturer').value,
            subject: document.getElementById('subject').value,
            room: document.getElementById('room').value,
            group: document.getElementById('group').value,
            album: document.getElementById('album').value
        };

        const heartButton = document.querySelector('.filter-favorite');
        const savedFilters = JSON.parse(localStorage.getItem('favoriteFilters'));
        const heartState = localStorage.getItem('heartState');

        // if active, remove from favourites
        if (heartState === 'active') {

            localStorage.removeItem('favoriteFilters');
            localStorage.setItem('heartState', 'inactive');
            heartButton.classList.remove('active');
            heartButton.innerHTML = '<i class="far fa-heart"></i>';

            // // clearing filters
            // document.getElementById('department').value = '';
            // document.getElementById('lecturer').value = '';
            // document.getElementById('subject').value = '';
            // document.getElementById('room').value = '';
            // document.getElementById('group').value = '';
            // document.getElementById('album').value = '';
        } else {
            // If not active, save filters and heart red jej
            localStorage.setItem('favoriteFilters', JSON.stringify(filters));
            heartButton.classList.add('active');
            heartButton.innerHTML = '<i class="fas fa-heart"></i>';
            localStorage.setItem('heartState', 'active');
        }
    }

    function loadSavedFilters() {
        const savedFilters = JSON.parse(localStorage.getItem('favoriteFilters'));
        if (savedFilters) {
            document.getElementById('department').value = savedFilters.department || '';
            document.getElementById('lecturer').value = savedFilters.lecturer || '';
            document.getElementById('subject').value = savedFilters.subject || '';
            document.getElementById('room').value = savedFilters.room || '';
            document.getElementById('group').value = savedFilters.group || '';
            document.getElementById('album').value = savedFilters.album || '';
        }

        const heartState = localStorage.getItem('heartState');
        const heartButton = document.querySelector('.filter-favorite');
        if (heartState === 'active') {
            heartButton.classList.add('active');
            heartButton.innerHTML = '<i class="fas fa-heart"></i>'; // Solid heart
        } else {
            heartButton.classList.remove('active');
            heartButton.innerHTML = '<i class="far fa-heart"></i>'; // Regular heart
        }
    }

});


