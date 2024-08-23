<?php
/**
 * Template Name: Test Idenfy Page
 * @package spitout
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$httpMethod = $_SERVER['REQUEST_METHOD'];
$requestUrl = $_SERVER['REQUEST_URI'];
$requestHeaders = getallheaders();
$requestPayload = file_get_contents('php://input');

// Log the request details
$logMessage = "Request Method: $httpMethod\n";
$logMessage .= "Request URL: $requestUrl\n";
$logMessage .= "Request Headers: " . print_r($requestHeaders, true) . "\n";
$logMessage .= "Request Payload: $requestPayload\n";

echo '<pre>';
echo $logMessage;
echo '</pre>';
// Check if the request method is POST
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// Get the raw POST data from the request
$payload = file_get_contents('php://input');

echo '<pre>';
var_dump($payload);
echo '</pre>';

// Check if data was received
if (!empty($payload)) {
    // Decode the JSON payload
    $data = json_decode($payload, true);
    echo $data;

    // Check if JSON decoding was successful and the expected data structure exists
    if ($data !== null && isset($data['success'])) {
        // Access the data
        $success = $data['success'];

        // Process the success value as needed
        if ($success === true) {
            echo "Received a successful response.";
        } else {
            echo "Received a response, but it's not successful.";
        }
    } else {
        echo "Invalid or unexpected JSON data received.";
    }
} else {
    echo "No data received.";
}
// } else {
//     echo "Only POST requests are allowed.";
// }










// $url = 'https://spitout.codepixelz.tech/test-notification/';
// $content = file_get_contents($url);
// $json = json_decode($content, true);

// if ($json) {

//     var_dump($json);
// }

// foreach ($json['data']['weather'] as $item) {
//     print $item['date'];
//     print ' - ';
//     print $item['weatherDesc'][0]['value'];
//     print ' - ';
//     print '<img src="' . $item['weatherIconUrl'][0]['value'] . '" border="0" alt="" />';
//     print '<br>';
// }
