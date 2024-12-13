<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKLanPjE7CjC12KjCCiG5Y-7AG58UuC1M"></script> -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBHrKkhwSDWrr45yqGAt2GjgF0adHLAkTU"></script>

  <!--  -->
  <style>
    #map {
      height: 200px;
      margin-bottom: 2%;
    }
  </style>
  <div id="map"></div>
  <button onclick="getLiveLocation()" type="button" class="btn btn-warning btn-sm getlocation d-none">Get Live Location</button>
  <br>
   <input type="hidden" class="form-control" id="latitude" name="lat" value="{{$latitude??''}}" readonly>
          <input type="hidden" class="form-control" id="longitude" name="long" value="{{$longitude??''}}" readonly>
  <script>
    $( document ).ready(function() {
        jQuery('.getlocation').click();
    });
  </script>
  <script>
    var map;
    var marker;

    function initMap() {
      var lat_in = jQuery('#latitude').val();
      var log_in = jQuery('#longitude').val();

      // Initialize the map
      map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: lat_in, lng: log_in }, // Default to centering at (0, 0)
        zoom: 15 // Adjust the zoom level as desired
      });

      // Create a marker at the default location (0, 0)
      marker = new google.maps.Marker({
        position: { lat: lat_in, lng: log_in },
        map: map,
        draggable: true // Allow the marker to be dragged
      });

      // Add an event listener to update the latitude and longitude when the marker is dragged
      marker.addListener('dragend', function() {
        updateCoordinates(marker.getPosition());
      });
    }

    function getLiveLocation() {
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
          console.log('Geolocation failed.');
        });
      } else {
        console.log('Geolocation is not supported by this browser.');
      }
    }

    function updateCoordinates(latLng) {
      document.getElementById('latitude').value = latLng.lat().toFixed(6);
      document.getElementById('longitude').value = latLng.lng().toFixed(6);
    }

    // Call the initMap() function to initialize the map
    initMap();
  </script>