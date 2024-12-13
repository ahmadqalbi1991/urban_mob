<button type="button" class="btn btn-success btn-sm" onclick="getLiveLoc()">Get Live Location</button>
  
  <script>
    var map;
    var marker;

    function initMap() {
      // Initialize the map
      map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: 0, lng: 0 }, // Default to centering at (0, 0)
        zoom: 15 // Adjust the zoom level as desired
      });

      // Create a marker at the default location (0, 0)
      marker = new google.maps.Marker({
        position: { lat: 0, lng: 0 },
        map: map,
        draggable: true // Allow the marker to be dragged
      });

      // Add an event listener to update the latitude and longitude when the marker is dragged
      marker.addListener('dragend', function() {
        updateCoordinates(marker.getPosition());
      });
    }

    function getLiveLoc() {
      // Get the user's current location
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
          var latitude = position.coords.latitude;
          var longitude = position.coords.longitude;

          // Update the map center and marker position to the live location
          var liveLocation = new google.maps.LatLng(latitude, longitude);
          map.setCenter(liveLocation);
          marker.setPosition(liveLocation);

          // Update the latitude and longitude inputs
          updateCoordinates(liveLocation);
        }, function() {
          alert("Location access denied. Please allow location access in your browser settings.");
        });
      } else {
        alert('Geolocation is not supported by this browser.');
      }
    }

    function updateCoordinates(latLng) {
      document.getElementById('latitude').value = latLng.lat().toFixed(6);
      document.getElementById('longitude').value = latLng.lng().toFixed(6);
    }

    // Call the initMap() function to initialize the map
    initMap();
  </script>