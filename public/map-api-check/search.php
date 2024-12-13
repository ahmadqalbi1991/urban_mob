<?php



// Replace 'YOUR_API_KEY' with your actual API key

$apiKey = 'AIzaSyBHrKkhwSDWrr45yqGAt2GjgF0adHLAkTU';



// Retrieve the search query from the form submission

$location = $_POST['location'];

$url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($location) . "&key=" . $apiKey;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if ($data['status'] === 'OK') {
    $latitude = $data['results'][0]['geometry']['location']['lat'];
    $longitude = $data['results'][0]['geometry']['location']['lng'];

    echo "Latitude: $latitude, Longitude: $longitude";
} else {
    echo "Geocoding failed. Error: " . $data['status'];
}

?>

