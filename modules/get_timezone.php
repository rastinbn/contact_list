<?php
header('Content-Type: application/json');

$latitude = $_POST['latitude'] ?? null;
$longitude = $_POST['longitude'] ?? null;

if (!$latitude || !$longitude) {
    echo json_encode(['error' => 'Missing latitude or longitude']);
    exit;
}

$pythonServiceUrl = "http://127.0.0.1:5000/get_timezone?latitude=" . $latitude . "&longitude=" . $longitude;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $pythonServiceUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false) {
    echo json_encode(['error' => 'Failed to connect to Python service.']);
    exit;
}

$jsonData = json_decode($response, true);

if ($jsonData === null) {
    echo json_encode(['error' => 'Invalid JSON response from Python service', 'raw_output' => $response]);
    exit;
}

if ($httpCode !== 200) {
    echo json_encode(['error' => 'Python service error', 'details' => $jsonData['error'] ?? 'Unknown error']);
    exit;
}

echo json_encode($jsonData);
