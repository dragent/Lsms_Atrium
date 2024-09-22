
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import './tooltip.min.js'
import { Calendar } from '@fullcalendar/core';
import timeGridPlugin from '@fullcalendar/timegrid';
import bootstrap5Plugin from '@fullcalendar/bootstrap5';
import luxonPlugin from '@fullcalendar/luxon3'
import interactionPlugin, { Draggable } from '@fullcalendar/interaction';


let calendarEl = document.getElementById('calendrier');
let dragEl = document.getElementById('dragAppointment');



let calendar = new Calendar(calendarEl, {
    plugins: [timeGridPlugin, bootstrap5Plugin, luxonPlugin, interactionPlugin],
    themeSystem: 'bootstrap5',
    initialView: 'timeGridWeek',
    titleFormat: 'd LLLL yyyy',
    editable: true,
    firstDay: 1,
    timeZone: 'UTC',
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
   
    droppable: true,
    expandRows: true,
    drop: function (info) {
        console.log("test")
        $(info.draggedEl).remove();
    },
   
});

let draggable = new Draggable(dragEl, {
    itemSelector: '.external-event',
    eventData: function (eventEl) {
        return {
            title: $(eventEl).children(0).html(),
            duration: "00:30",
            display: "block",
            description: 'Lecture',
        }
    }

});
calendar.updateSize()
calendar.render();
