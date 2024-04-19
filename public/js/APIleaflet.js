// L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
//     maxZoom: 19,
//     attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
// }).addTo(map);

// Center the map view by default on Strasbourg
var map = L.map("map").setView([48.5734, 7.7521], 13);

//  Initialization of the map using Leaflet
var Stadia_StamenToner = L.tileLayer(
    "https://tiles.stadiamaps.com/tiles/stamen_toner/{z}/{x}/{y}{r}.{ext}",
    {
        minZoom: 0,
        maxZoom: 20,
        attribution:
            '&copy; <a href="https://www.stadiamaps.com/" target="_blank">Stadia Maps</a> &copy; <a href="https://www.stamen.com/" target="_blank">Stamen Design</a> &copy; <a href="https://openmaptiles.org/" target="_blank">OpenMapTiles</a> &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        ext: "png",
    }
).addTo(map);

//  custom Icon
var Icon = L.icon({
    iconUrl: "../images/markerMap.png",
    iconSize: [45, 45], // size of the icon
    iconAnchor: [25, 40], // point of the icon which will correspond to marker's location
    popupAnchor: [-3, -46], // point from which the popup should open relative to the iconAnchor
});

//  Retrieve the necessary elements regarding the artist - the address + their name and activity
const adressElements = document.querySelectorAll(".address");
adressElements.forEach(function (addressElement) {
    const street = addressElement.getAttribute("data-street");
    const city = addressElement.getAttribute("data-city");
    const postalCode = addressElement.getAttribute("data-postal-code");
    const country = addressElement.getAttribute("data-country");
    const name = addressElement.getAttribute("data-name");
    const activity = addressElement.getAttribute("data-activity");
    const slug = addressElement.getAttribute("data-slug");

    //Create a const with the full address
    const address = `${street}, ${city} ${postalCode},${country}`;

    if (address !== "") {
        // If the address is not empty, then call the geocodeAndMarker function
        geocodeAndMarker(address, name, activity, slug);
    }
});

//  Geocode an address and place a marker
function geocodeAndMarker(address, name, activity, slug) {
    // get the ID or the slug of the artist

    const artistSlug = slug;

    // Using the Nominatim service to geocode the address
    fetch(
        "https://nominatim.openstreetmap.org/search?format=json&q=" +
            encodeURIComponent(address)
    )
        .then((response) => response.json())
        .then((data) => {
            if (data.length > 0) {
                var latlng = [data[0].lat, data[0].lon];
                var marker = L.marker(latlng, { icon: Icon }).addTo(map);

                //  Create an <a> element with the link to the artist's page
                var artistLink = document.createElement("a");
                artistLink.href = `http://127.0.0.1:8000/artist/${artistSlug}`;
                artistLink.innerText = name;
                // Create the popup with HTML content
                var popupContent = document.createElement("div");
                popupContent.appendChild(artistLink);
                popupContent.innerHTML += `<br>${activity}<br><address>Address: ${address}</address>`;
                // Add the popup to the marker
                marker.bindPopup(popupContent);
                // Attach a click event to the marker to open the popup when clicked
                marker.on("click", function () {
                    marker.openPopup();
                });
            } else {
                console.error("Address not found:", address);
            }
        })
        .catch((error) => console.error("Geocoding error:", error));
}