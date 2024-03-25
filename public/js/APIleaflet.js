var map = L.map('map').setView([48.5734, 7.7521], 13);

// L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
//     maxZoom: 19,
//     attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
// }).addTo(map);

var Stadia_StamenToner = L.tileLayer('https://tiles.stadiamaps.com/tiles/stamen_toner/{z}/{x}/{y}{r}.{ext}', {
	minZoom: 0,
	maxZoom: 20,
	attribution: '&copy; <a href="https://www.stadiamaps.com/" target="_blank">Stadia Maps</a> &copy; <a href="https://www.stamen.com/" target="_blank">Stamen Design</a> &copy; <a href="https://openmaptiles.org/" target="_blank">OpenMapTiles</a> &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
	ext: 'png'
}).addTo(map);


// ^ custom Icon
var Icon = L.icon({
    iconUrl: '../images/markerMap.png',
    // shadowUrl: 'leaf-shadow.png',

    iconSize:     [45, 45], // size of the icon
    // shadowSize:   [50, 64], // size of the shadow
    iconAnchor:   [25, 40], // point of the icon which will correspond to marker's location
    // shadowAnchor: [4, 62],  // the same for the shadow
    popupAnchor:  [-3, -46] // point from which the popup should open relative to the iconAnchor
});



const adressElements = document.querySelectorAll(".address");
console.log('map', adressElements )
adressElements.forEach(function(addressElement) {
    const street = addressElement.getAttribute('data-street');
    const city = addressElement.getAttribute('data-city');
    const postalCode = addressElement.getAttribute('data-postal-code');
    const country = addressElement.getAttribute('data-country');
    const name = addressElement.getAttribute('data-name');
    const activity = addressElement.getAttribute('data-activity');

    const address = `${street}, ${city} ${postalCode},${country}`;
    
    if (address !== "") {
        geocodeAndMark(address, name, activity);
    }
})


// Fonction pour géocoder une adresse et placer un marqueur
function geocodeAndMark(address, name, activity) {

    // get the ID of the artist
    adressElements.forEach(function(addressElement) {
       
    const artistId = addressElement.getAttribute('data-slug');
  
    // Utilisation du service Nominatim pour géocoder l'adresse
    fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(address))
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                var latlng = [data[0].lat, data[0].lon];
                var marker = L.marker(latlng , {icon: Icon}).addTo(map);

                // Créer un élément <a> avec le lien vers la page de l'artiste
                var artistLink = document.createElement('a');
                artistLink.href = `http://127.0.0.1:8000/artist/${artistId}`;
                artistLink.innerText = name;

                // Créer la popup avec le contenu HTML
                var popupContent = document.createElement('div');
                popupContent.appendChild(artistLink);
                popupContent.innerHTML += `<br>${activity}<br>Address: ${address}`;

                // Ajouter la popup au marqueur
                marker.bindPopup(popupContent);

                // marker.bindPopup(`${name}<br>${activity}<br>Address: ${address}`);
                // Attacher un événement de clic au marqueur pour ouvrir la popup lorsqu'il est cliqué
                marker.on('click', function() {
                    marker.openPopup();
                });

            } else {
                console.error('Address not found:', address);
            }
        })
        .catch(error => console.error('Geocoding error:', error));
    });
}



