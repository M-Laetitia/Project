var map = L.map('map').setView([48.5734, 7.7521], 13);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

const adressElements = document.querySelectorAll(".address");

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
    // Utilisation du service Nominatim pour géocoder l'adresse
    fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(address))
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                var latlng = [data[0].lat, data[0].lon];
                L.marker(latlng).addTo(map);

                // Ajout d'une popup au marqueur avec le contenu de l'adresse
                var marker = L.marker(latlng).addTo(map);
                marker.bindPopup(`${name}<br>${activity}<br>Address: ${address}`);

                // Attacher un événement de clic au marqueur pour ouvrir la popup lorsqu'il est cliqué
                marker.on('click', function() {
                    marker.openPopup();
                });


            } else {
                console.error('Address not found:', address);
            }
        })
        .catch(error => console.error('Geocoding error:', error));
}



