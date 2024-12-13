// // Initialize the Places Autocomplete service
// var autocomplete = new google.maps.places.Autocomplete(document.getElementById('location-input'), {
//   types: ['geocode'] // Restrict results to geographical locations
// });


function initMap() {
  // Initialize the map
  var map = new google.maps.Map(document.getElementById('map'), {
    center: { lat: 0, lng: 0 }, // Default to centering at (0, 0)
    zoom: 15 // Adjust the zoom level as desired
  });

  // Get the user's current location
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var latitude = position.coords.latitude;
      var longitude = position.coords.longitude;

      // Create a LatLng object with the user's location
      var userLocation = new google.maps.LatLng(latitude, longitude);

      // Set the map center to the user's location
      map.setCenter(userLocation);

      // Add a marker at the user's location
      var marker = new google.maps.Marker({
        position: userLocation,
        map: map,
        title: 'Your Location'
      });
    }, function() {
      console.log('Geolocation failed.');
    });
  } else {
    console.log('Geolocation is not supported by this browser.');
  }
}



if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(function(position) {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
    console.log('Latitude:', latitude);
    console.log('Longitude:', longitude);
  }, function() {
    console.log('Geolocation failed.');
  });
} else {
  console.log('Geolocation is not supported by this browser.');
}

