<!DOCTYPE html>
<html>
<head>
    <title>Location Search</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBHrKkhwSDWrr45yqGAt2GjgF0adHLAkTU&libraries=places"></script>
    <script>
        var map;
        var marker;

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: 0, lng: 0 },
                zoom: 15
            });

            marker = new google.maps.Marker({
                map: map
            });
        }

        function showLocation() {
            var input = document.getElementById('autocomplete-input');
            var autocomplete = new google.maps.places.Autocomplete(input);
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                return;
            }

            var locationName = place.name;
            var latitude = place.geometry.location.lat();
            var longitude = place.geometry.location.lng();

            document.getElementById('selected-location').innerHTML =
                'Selected Location: ' + locationName;
            document.getElementById('latitude').innerHTML =
                'Latitude: ' + latitude;
            document.getElementById('longitude').innerHTML =
                'Longitude: ' + longitude;

            map.setCenter({ lat: latitude, lng: longitude });
            marker.setPosition({ lat: latitude, lng: longitude });
            marker.setTitle(locationName);
        }
    </script>
</head>
<body onload="initMap()">
    <h1>Location Search</h1>
    <form>
        <label for="autocomplete-input">Search for a location:</label>
        <input type="text" id="autocomplete-input" placeholder="Enter a location">
        <button type="button" onclick="showLocation()">Search</button>
    </form>
    <div id="selected-location"></div>
    <div id="latitude"></div>
    <div id="longitude"></div>
    <div id="map" style="width: 100%; height: 400px;"></div>
</body>
</html>

  