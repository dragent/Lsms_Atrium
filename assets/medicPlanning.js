
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import './tooltip.min.js'
import './styles/planning.scss'
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
    droppable: true,
    expandRows: true,
    drop: function (info) {
        var newDate = info.date;
        var date = newDate.getDate()+"/"+newDate.getMonth()+"-"+newDate.getFullYear()
        var hour = newDate.getUTCHours()+":"+newDate.getUTCMinutes()
        $($(info.draggedEl)).remove();
        $.ajax({
            type: "POST",
            url: "https://127.0.0.1:8000/lsms/planning/ajout",
            data: {       
                id: $($(info.draggedEl).children()[2]).val(),
                date: newDate,
            }, 
            success:function(respond)
                {
                    console.log(respond)
                },
            error:function(respond)
            {
                console.log(respond)
            }
          });
    },
    eventContent: function (arg) {
        // Affichage du titre et de la description sous l'événement
        const titleEl = document.createElement('div');
        titleEl.textContent = arg.event.title;
        titleEl.classList.add('fc-event-title');
  
        const descriptionEl = document.createElement('div');
        descriptionEl.textContent = arg.event.extendedProps.description;
        descriptionEl.classList.add('fc-event-description');
  
        const arrayOfDomNodes = [titleEl, descriptionEl];
        return { domNodes: arrayOfDomNodes };
      },
      eventDrop: function (info) {
        var id = $($($($($(info.el)[0])).children()[0]).children()[0]).text().split(" - ")[1]
        var newDate = info.event._instance.range.start
        var date = newDate.getDate()+"-"+newDate.getMonth()+"-"+newDate.getFullYear()
        var hour = newDate.getUTCHours()+":"+newDate.getUTCMinutes() 
      }
   
});

// Initialisation du draggable
let draggable = new Draggable(dragEl, {
    itemSelector: '.external-event',
    eventData: function (eventEl) {
        return {
            title: $($($(eventEl).children()[1]).children()[0]).html() + " - " + $($(eventEl).children()[2]).val(),
            duration: "00:30",
            display: "block",
            description: $($(eventEl).children()[0]).html()+ " - " + $($($(eventEl).children()[1]).children()[1]).html(),
        }
    }
});

calendar.updateSize()
calendar.render();
