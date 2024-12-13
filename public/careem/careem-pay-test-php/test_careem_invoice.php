<?php

// Function to generate a new UUID
function generateUUID() {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
}

// Function to generate an invoice
function generateInvoice($amount, $currency, $accessToken) {
    $url = "https://merchant-gateway.qa.careem-engineering.com/cpay/one-checkout/v1/invoices";
    
    $data = [
        'total' => [
            'amount' => $amount,
            'currency' => $currency,
        ],
    ];
    
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $accessToken,
        'X-Idempotency-Key: ' . generateUUID(),
    ];
    
    $ch = curl_init($url);
    
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
}

// Replace with your actual logic to calculate the cart amount and currency
function getCartAmount() {
    $amount = 3900;
    $currency = "AED";
    
    return [
        'amount' => $amount,
        'currency' => $currency,
    ];
}

// Simulate a request with a cart
$cart = []; // Replace with your actual cart data

// Replace with your actual access token logic
$accessToken = "eyJraWQiOiI5YWZhMzE0MS1mZTcyLTRhMTAtOGQ4ZC1jZmNmM2I2ZDBlMjEiLCJhbGciOiJSUzI1NiJ9.eyJzdWIiOiJmMTA1ODZmNS1jZWY5LTQ3MzAtYTRlZi04ZmRhNjNkOTJhMmEiLCJhdWQiOiJjb20uY2FyZWVtLnBhcnRuZXIiLCJhenAiOiJmMTA1ODZmNS1jZWY5LTQ3MzAtYTRlZi04ZmRhNjNkOTJhMmEiLCJzY29wZSI6ImxvY2F0aW9ucyBwYXltZW50cyIsImlzcyI6Imh0dHBzOlwvXC9pZGVudGl0eS5xYS5jYXJlZW0tZW5naW5lZXJpbmcuY29tXC8iLCJleHAiOjE2OTcxODgyMjQsImlhdCI6MTY5NzEwMTgyNCwianRpIjoiNjQyZTcwZTUtYjUyYi00MmE1LWFhNTMtZjRkYTZkNWViOGNjIn0.UMZaidRJFL8NEi6gEGEIkSq_m6DiHO0paRLwGkXfkRV9aPaKyMHY8RlnMEqPuslALTtwrZzMnSkkFX3OeygMmuXF_yGRM7XlYtxycXXzX84IlrusTjQYVMR1nuH1QAAkGKhwUi9xH_KBMDzvbUkHLkZc5bSEBq8OpvUcmW8-97ioBKwZAUeOmUCpYiF0r77PNjfaeMVKPDWFkd0iFzOr_IcH5gY2FOrWbJ-ekpDvYr1LneC4w0HcC284m-ntNVjjWA9l-CZophPP6lf5y960PeL2lhaxW_6fMOg1CA-y7LCIpQJgIukhc4qMFHOE9YiEuD1joSjU8gSy4rLkWiDMqQ";

$cartAmount = getCartAmount($cart);
$amount = $cartAmount['amount'];
$currency = $cartAmount['currency'];

$response = generateInvoice($amount, $currency, $accessToken);

echo $response;

?>