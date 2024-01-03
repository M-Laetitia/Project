

    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        
        // Insérez ici le code pour récupérer les données du serveur
        

        // Récupérer la chaîne JSON de l'attribut data-formatted-events
  const formattedEventsString = document.getElementById('calendar').getAttribute('data-formatted-events');

  // récupérer sous forme de tableau:
  const formattedEvents = JSON.parse(formattedEventsString);

  // formatter les dates 
  formattedEvents.forEach(event => {
      event.start = new Date(event.start);
      event.end = new Date(event.end);
      event.id = event.slug;
      console.log(event.end)
      console.log(event.end.toISOString());
    });
    

        
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: formattedEvents,

            eventClick: function(info) {
                console.log('slug', info.event.id)
                window.location.href = '/event/' + info.event.id;
            },
            timeZone: 'UTC', // ou 'UTC' ou le fuseau horaire souhaité
            //displayEventEnd: true, // Affiche l'heure de fin

            ///eventContent: function(arg) {
                //return {}; 
                //Retourne un objet vide pour supprimer le contenu de l'événement
            //},
            eventClassNames: function(arg) {
                // Ajouter une classe CSS pour les jours d'événements
                return ['event-day'];
            }
       
        });
        
        calendar.render();
    });




//   var calendar = new Calendar(calendarEl, {
//     plugins: [ dayGridPlugin, interactionPlugin ],
//     events: '/api/events', // Remplacez cette URL par le chemin de votre API Symfony qui renvoie les événements
//     eventClick: function(info) {
//         window.location.href = '/event/' + info.event.id;
//     }
// });