
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
    events:"https://127.0.0.1:8000/lsms/planning/liste",
    eventColor: 'white', 
    droppable: true,
    expandRows: true,
    drop: function (info) {
        var newDate = info.date;
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
        
        const $titleEl = $('<div>')
            .text(arg.event.title)
            .addClass('fc-event-title');

        // Créer la personne
        const $personEl = $('<div>')
            .text(arg.event.extendedProps.person)
            .addClass('fc-event-person');

        // Créer la description
        const $descriptionEl = $('<div>')
            .text(arg.event.extendedProps.description)
            .addClass('fc-event-description');

        const $hiddenInput = $('<input>')
            .attr('type', 'hidden')
            .attr('name', 'hiddenField')
            .val(arg.event.id);

        const arrayOfDomNodes = [$titleEl[0], $personEl[0], $descriptionEl[0],$hiddenInput[0]];

        return { domNodes: arrayOfDomNodes }
      },
    eventDidMount: function(info) {
        $(info.el).attr('id', 'event-' + info.event.id);
    },
    eventDrop: function (info) {
    
    var id = info.event.id
    var newDate = info.event.start 
    $.ajax({
        type: "POST",
        url: "https://127.0.0.1:8000/lsms/planning/modif",
        data: {       
            id: id,
            argument: newDate,
            motif: "date",
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
    eventClick: function(info) {
        showEventPopup(info);
      }
});

function showEventPopup(info) {
    
    // Create a dimmed overlay for the background
    $('body').append('<div id="overlay" class="overlay"></div>');

    // Extract data from the event
    var personLane = info.event.extendedProps.person;
    var person = personLane.split(" - ")[ 0];
    var number = personLane.split(" - ")[1];

    // Sample list of doctors (replace with actual data)
    getDoctors().then(function(doctorsList) {
        $('body').append(`
            <div id="event-popup" class="popup">
            <div class="container">
                <div class="row">
                <div class="col-md-6">
                    <h3 class="text-decoration-underline fw-bold">${info.event.title}</h3>
                    <p><strong>Personne : </strong>${person}</p>
                    <p><strong>Numéro : </strong>${number}</p>
                    <p><strong>Start : </strong>${info.event.start.toLocaleString()}</p>
                    <p><strong>End : </strong>${info.event.end ? info.event.end.toLocaleString() : 'N/A'}</p>
                    
                    <form id="description-form">
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Raison:</label>
                        <textarea id="description" name="description" class="form-control" rows="4">${info.event.extendedProps.description}</textarea>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" id="save-description" class="btn btn-success">Modifier</button>
                    </div>
                    </form>
                </div>
    
                <div class="col-md-6">
                    <h3 class="text-decoration-underline fw-bold">Docteur en charge</h3>
                    <div class="mb-3">
                    <label for="doctor" class="form-label">Sélectionner le docteur :</label>
                    <select id="doctor" name="doctor" class="form-select">
                        ${doctorsList.map(function(doctor) {
                        return `<option value="${doctor.id}" ${info.event.extendedProps.doctorId === doctor.id ? 'selected' : ''}>${doctor.name}</option>`;
                        }).join('')}
                    </select>
                    </div>
                </div>
                </div>
            </div>
    >
            <button id="close-popup" class="btn-close position-absolute top-0 end-0 mt-2 me-2" aria-label="Close"></button>
            </div>
        `);
    
        // Handle the form submission to save the new description and selected doctor
        $('#description-form').on('submit', function(event) {
            event.preventDefault(); // Prevent form submission
    
            // Retrieve the updated description
            var updatedDescription = $('#description').val();
            $.ajax({
                type: "POST",
                url: "https://127.0.0.1:8000/lsms/planning/modif",
                data: {       
                    id: info.event.id,
                    argument: updatedDescription,
                    motif: "detail",
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
            
    
            // Update the event's description and doctor
            info.event.setExtendedProp('description', updatedDescription);
        
            // Close the modal after saving the new description and doctor
            $('#overlay').remove();
            $('#event-popup').remove();
        });
    
        $('#doctor').on('change', function() {
            var selectedValue = $(this).val(); 
            console.log("ici")
            $.ajax({
                url: 'https://127.0.0.1:8000/lsms/planning/modif', 
                type: 'POST', 
                data: {  id: info.event.id,
                    argument: selectedValue,
                    motif: "doctor",
                }, 
                success: function(response) {
                    $('#overlay').remove();
                    $('#event-popup').remove();
                    $("#event-"+info.event.id).parent().remove();
                    
                },
            });
        });
        
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
// Initialisation du draggable
let draggable = new Draggable(dragEl, {
    itemSelector: '.external-event',
    eventData: function (eventEl) {
        return {
            title: $($($(eventEl).children()[1]).children()[0]).html() + " - " + $($(eventEl).children()[2]).val(),
            person: $($(eventEl).children()[0]).html(),
            description:$($($(eventEl).children()[1]).children()[1]).html(),
        }
    }
});

calendar.updateSize()
calendar.render();



function getDoctors()
{
    return $.ajax({
        type: "POST",
        url: "https://127.0.0.1:8000/personnel/liste",
        
    });
    
}