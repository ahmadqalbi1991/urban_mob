<?php
require_once('vendor/autoload.php'); // Path to the Tabby PHP SDK

// Initialize Tabby with your Secret Key
\Tabby\Api\PaymentsApi::setApiKey('pk_test_c16356b4-610c-4ff2-bc51-42443d25b125'); // Set your Secret Key

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process payment callback from Tabby here
    // You can use $_POST data to verify the payment status
    // Handle payment confirmation and update your database
    // Tabby will send payment status details to your callback URL
    // Make sure to implement this logic based on your needs
} else {
    // Create a Test Payment
    $orderData = [
        'amount' => 100, // Amount in cents
        'currency' => 'AED', // Currency code (AED for United Arab Emirates Dirham)
        'reference_id' => 'YOUR_ORDER_ID', // Your order ID
        'description' => 'Test Payment',
        'billing_address' => [
            'line1' => '123 Main Street',
            'city' => 'Dubai',
            'state' => 'DXB',
            'postal_code' => '12345',
            'country' => 'AE',
        ],
        'customer' => [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+971234567890',
        ],
    ];

    try {
        $response = \Tabby\Api\PaymentsApi::createPayment($orderData);
        // Handle the response, e.g., redirect to the payment URL
        header('Location: ' . $response->getPaymentUrl());
        exit;
    } catch (\Tabby\Api\ApiException $e) {
        // Handle API errors
        echo 'Error: ' . $e->getMessage();
    }
}
?>
