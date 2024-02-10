document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('planning'); 
    const supervisorSelect = document.getElementById('supervisor-filter');

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
        
            // Générer une classe CSS dynamiquement à partir du nom du studio
            const studioName = `studio-${studio.replace(/\s+/g, '-').toLowerCase()}`;
        
            // Créer un élément div pour contenir le contenu de l'événement
            const content = document.createElement('div');
            content.innerHTML = `<div>${studio}</div><div>${supervisor}</div>`;
        
             // Retourner le contenu de l'événement avec les informations de studio et de professeur
            return { 
            html: `<div class="">${studio}</div><div>${supervisor}</div>`,
            // Ajouter la classe CSS dynamique du studio à l'événement
            classList: [studioName]
        };
        },

        eventDidMount: function(arg) {
            // Ajouter une classe à la div de l'événement
            const studio = arg.event.extendedProps.studio;
            const studioName = `${studio.replace(/\s+/g, '-').toLowerCase()}`;

            // Ajouter la classe CSS dynamique à la div de l'événement
            const eventDiv = arg.el;
            eventDiv.classList.add(studioName);
        },
    

        eventClassNames: function(arg) {
            // Ajouter une classe CSS pour les jours d'events
            return ['event-day'];
        }  

        
    });

    // Ajouter un écouteur d'événements pour le changement de superviseur sélectionné
    supervisorSelect.addEventListener('change', function() {
        const selectedSupervisor = supervisorSelect.value;
        let filteredTimeslots = []; // Initialiser un tableau pour stocker les timeslots filtrés
    
        if (selectedSupervisor === '') {
            // Si aucun superviseur n'est sélectionné, afficher tous les timeslots
            filteredTimeslots = formattedTimeslots;
        } else if (selectedSupervisor === 'all') {
            // Si l'option "Tous" est sélectionnée, afficher tous les timeslots
            filteredTimeslots = formattedTimeslots;
        } else {
            // Sinon, filtrer les timeslots par superviseur sélectionné
            filteredTimeslots = formattedTimeslots.filter(timeslot => timeslot.supervisor === selectedSupervisor);
        }
    
        // Mettre à jour le calendrier avec les timeslots filtrés
        calendar.removeAllEvents(); // Supprimer tous les événements du calendrier
        calendar.addEventSource(filteredTimeslots); // Ajouter les timeslots filtrés au calendrier
    });

    const personalTimeslotsCheckbox = document.getElementById('personalTimeslots');
    
    personalTimeslotsCheckbox.addEventListener('change', function() {
     
        let filteredTimeslots = [];
        const userId = personalTimeslotsForm.getAttribute('data-user-id');

        if (personalTimeslotsCheckbox.checked) {
            // Si la case à cocher est cochée, afficher uniquement les timeslots personnels
            filteredTimeslots = formattedTimeslots.filter(timeslot => timeslot.supervisor === userId );
        } else {
            /// Si l'option "Tous" est sélectionnée, afficher tous les timeslots
            filteredTimeslots = formattedTimeslots;
        }

        // Mettre à jour le calendrier avec les timeslots filtrés
        calendar.removeAllEvents(); // Supprimer tous les événements du calendrier
        calendar.addEventSource(filteredTimeslots); // Ajouter les timeslots filtrés au calendrier
    });


    
    calendar.render();
});

