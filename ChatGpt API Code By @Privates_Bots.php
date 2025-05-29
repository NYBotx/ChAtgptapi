<?php
// Define the external API URL
$apiUrl = 'https://nexus.0-0-0.click/';

// Fetch query parameters 'backup' and 'msg'
$userId = isset($_GET['backup']) ? $_GET['backup'] : null;
$msg = isset($_GET['msg']) ? $_GET['msg'] : null;

// Check if both parameters are provided
if (!$userId || !$msg) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Both backup and msg parameters are required']);
    http_response_code(400);
    exit;
}

// Construct the URL with query parameters
$requestUrl = $apiUrl . '?backup=' . urlencode($userId) . '&msg=' . urlencode($msg);

// Initialize a cURL session
$ch = curl_init($requestUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the request and fetch the response
$response = curl_exec($ch);
curl_close($ch);

// Check if cURL request was successful
if ($response === false) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => false,
        'message' => 'RESPONSE NOT FOUND, DUE TO ERROR PLEASE TRY AGAIN LATER',
        'owner' => '@REFLEX_COD3R',
        'backupId' => $userId
    ]);
    http_response_code(500);
    exit;
}

// Decode the JSON response from the external API
$responseData = json_decode($response, true);

// Prepare the response data
$result = [
    'status' => true,
    'message' => $responseData['message'] ?? 'No message',
    'owner' => '@REFLEX_COD3R',
    'backupId' => $userId,
    'chatID' => $responseData['id'] ?? null
];

// Send JSON response
header('Content-Type: application/json');
echo json_encode($result);
?>
