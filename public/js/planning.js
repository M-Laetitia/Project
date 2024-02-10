document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('planning'); 
        // Insérez ici le code pour récupérer les données du serveur
        

// Récupérer la chaîne JSON de l'attribut data-formatted-events
const formattedTimeslotsString = document.getElementById('planning').getAttribute('data-formatted-timeslots');

// récupérer sous forme de tableau:
const formattedTimeslots = JSON.parse(formattedTimeslotsString);

// formatter les dates 
formattedTimeslots.forEach(timeslot => {
      timeslot.start = new Date(timeslot.start);
      timeslot.end = new Date(timeslot.end);

      
      
    });
    console.log("all",formattedTimeslots )    
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek', // Afficher la vue par semaine
        slotDuration: '00:30:00', // Durée des créneaux horaires (30 minutes)
        slotLabelInterval: '01:00', // Interval entre chaque libellé de créneau horaire (1 heure)
        businessHours: { // Définir les heures de travail (8h à 23h)
            daysOfWeek: [1, 2, 3, 4, 5, 6], // Lundi à vendredi
            startTime: '08:00', // Commence à 8h
            endTime: '23:00' // Se termine à 23h
          },
          slotMinTime: '08:00', // La première heure affichée est 8h
          slotMaxTime: '23:00', // La dernière heure affichée est 23h

        events: formattedTimeslots,
        

        eventClick: function(info) {
            console.log('slug', info.event.id)
            window.location.href = '/event/' + info.event.id;
        },
        timeZone: 'UTC', 

        eventContent: function(arg) {
       
            const studio = arg.event.extendedProps.studio;
            const supervisor = arg.event.extendedProps.supervisor;
            return { html: `<div>Studio: ${studio}</div><div>Prof: ${supervisor}</div>` };
        },


        eventClassNames: function(arg) {
            // Ajouter une classe CSS pour les jours d'événements
            return ['event-day'];
        }  
    });
    
    calendar.render();
});

