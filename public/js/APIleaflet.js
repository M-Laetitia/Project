 // Initialisez le service de recherche géographique
 var searchControl = new L.Control.GeoSearch({
    provider: new L.GeoSearch.Provider.OpenStreetMap(),
    showMarker: true,
    showPopup: false,
    marker: {
        icon: new L.Icon.Default(),
        draggable: false,
    },
});

map.addControl(searchControl);