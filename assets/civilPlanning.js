
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import './tooltip.min.js'
import './styles/planning.scss'
import { Calendar } from '@fullcalendar/core';
import timeGridPlugin from '@fullcalendar/timegrid';
import bootstrap5Plugin from '@fullcalendar/bootstrap5';
import luxonPlugin from '@fullcalendar/luxon3'


let calendarEl = document.getElementById('calendrier');



let calendar = new Calendar(calendarEl, {
    plugins: [timeGridPlugin, bootstrap5Plugin, luxonPlugin],
    themeSystem: 'bootstrap5',
    initialView: 'timeGridWeek',
    titleFormat: 'd LLLL yyyy',
    editable: true,
    firstDay: 1,
    timezone: 'GMT',
    locale: 'fr',
    nowIndicator: true,
    now: Date.now(),
    slotMinTime: "08:00:00",
    slotMaxTime: "32:00:00",
    businessHours: {
        daysOfWeek: [0, 1, 2, 3, 4, 5, 6],
        startTime: '8:00',
        endTime: '26:00',
    },
    eventColor: 'white', 
    expandRows: true,
   
    eventContent: function (arg) {
        
        const $titleEl = $('<div>')
            .text(arg.event.title)
            .addClass('fc-event-title');

        // Créer la medicne
        const $medicEl = $('<div>')
            .text(arg.event.extendedProps.medic)
            .addClass('fc-event-medic');

        // Créer la description
        const $descriptionEl = $('<div>')
            .text(arg.event.extendedProps.description)
            .addClass('fc-event-description');

        const $hiddenInput = $('<input>')
            .attr('type', 'hidden')
            .attr('name', 'hiddenField')
            .val(arg.event.id);

        const arrayOfDomNodes = [$titleEl[0], $medicEl[0], $descriptionEl[0],$hiddenInput[0]];

        return { domNodes: arrayOfDomNodes }
      },
   
   
    eventClick: function(info) {
        showEventPopup(info);
      }
});

function showEventPopup(info) {
    
    // Create a dimmed overlay for the background
    $('body').append('<div id="overlay" class="overlay"></div>');

    // Extract data from the event
    var medicLane = info.event.extendedProps.medic;
    var medic = medicLane.split(" - ")[ 0];
    var number = medicLane.split(" - ")[1];

    // Sample list of doctors (replace with actual data)
    getDoctors().then(function() {
        $('body').append(`
            <div id="event-popup" class="popup">
            <div class="container">
                <h3 class="text-decoration-underline fw-bold">${info.event.title}</h3>
                <p><strong>medicne : </strong>${medic}</p>
                <p><strong>Numéro : </strong>${number}</p>
                <p><strong>Start : </strong>${info.event.start.toLocaleString()}</p>
                <p><strong>End : </strong>${info.event.end ? info.event.end.toLocaleString() : 'N/A'}</p>
                <p><strong>Description : </strong>${info.event.extendedProps.description}</p>
            </div>
            <button id="close-popup" class="btn-close position-absolute top-0 end-0 mt-2 me-2" aria-label="Close"></button>
        `);
        
        $('#close-popup').on('click', function() {
            $('#overlay').remove();
            $('#event-popup').remove();
        });
    
        // Close the modal if the overlay (background) is clicked
        $('#overlay').on('click', function() {
            $('#overlay').remove();
            $('#event-popup').remove();
        });
    });
      
}


calendar.updateSize()
calendar.render();
