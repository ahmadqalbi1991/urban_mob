<?php
function send_single_notification($fcm_token, $notification, $data, $priority = 'high')
{
    // Set your project ID and access token
    $project_id = env('FIREBASE_PROJECT_ID');

    $access_token =getAccessToken(); // You'll need to generate this as described below
    //d($access_token);
    // Set the v1 endpoint
    $url = "https://fcm.googleapis.com/v1/projects/$project_id/messages:send";


    // Set the headers for the request
    $headers = [
        'Authorization: Bearer ' . $access_token,
        'Content-Type: application/json'
    ];

    // Make the request
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    //curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(['message' => $message]));

    $payload = json_encode([
        'message' => [
            'token' => $fcm_token,
            'notification' => [
                "title" => $notification['title'],
                "body" => $notification['body']
            ],
            'data' =>convert_all_elements_to_string_fcm($data),
        ],
    ]);

    curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
    $curl_response = curl_exec($curl);

    curl_close($curl);

    if ($curl_response) {
        return json_decode($curl_response);
    } else {
        return false;
    }
}

function getAccessToken()
{

    //$jsonKey = json_decode(file_get_contents(config('firebase.FIREBASE_CREDENTIALS')), true);
    try {
        // Load the service account credentials JSON file
        $jsonKey = json_decode(file_get_contents(base_path(config('firebase.FIREBASE_CREDENTIALS'))), true);

        $now = time();
        $token = [
            'iss' => $jsonKey['client_email'], // issuer
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600, // Token expiration time, set to 1 hour
            'iat' => $now // Token issued at time
        ];

// Encode the JWT
        $jwtHeader = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
        $jwtHeader = base64_encode($jwtHeader);

        $jwtPayload = json_encode($token);
        $jwtPayload = base64_encode($jwtPayload);

// Sign the JWT using the private key
        openssl_sign($jwtHeader . '.' . $jwtPayload, $signature, $jsonKey['private_key'], 'sha256');
        $jwtSignature = base64_encode($signature);

// Concatenate the three parts to create the final JWT
        $assertion = $jwtHeader . '.' . $jwtPayload . '.' . $jwtSignature;

        // Prepare the cURL request
        // Now make the request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $assertion, // Use the generated JWT as the assertion
        ]));

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
        ]);

        $response = curl_exec($ch);


        if (curl_errno($ch)) {
            // Handle cURL error
            curl_close($ch);
            return null;
        }

        curl_close($ch);

        $authToken = json_decode($response, true);

        return $authToken['access_token'];
    } catch (Exception $e) {
        // Handle exceptions, e.g., log errors or throw a custom exception
        return null; // Or handle differently based on your application's needs
    }
}