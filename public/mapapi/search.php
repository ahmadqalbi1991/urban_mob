<?php

// Replace 'YOUR_API_KEY' with your actual API key
$apiKey = 'AIzaSyBvGq8LjejiKHaI5lPEUZVYbwOYSwhZMEs';

// Retrieve the search query from the form submission
$location = $_POST['location'];

// Prepare the URL for the Places Autocomplete API request
$url = 'https://maps.googleapis.com/maps/api/place/autocomplete/json?input=' . urlencode($location) . '&key=' . $apiKey;

// Make the API request using cURL
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
curl_close($curl);

// Decode the JSON response
$data = json_decode($response, true);
echo "<pre>"; print_r($data); die();
// Check if the response contains predictions
if ($data['status'] === 'OK') {
    $predictions = $data['predictions'];

    // Loop through the predictions and retrieve the place names
    foreach ($predictions as $prediction) {
        $placeName = $prediction['description'];
        echo $placeName . '<br>';
    }
} else {
    echo 'Search failed. Status: ' . $data['status'];
}
?>
