<?php
header('Content-Type: application/json');

$latitude = $_POST['latitude'] ?? null;
$longitude = $_POST['longitude'] ?? null;

if (!$latitude || !$longitude) {
    echo json_encode(['error' => 'Missing latitude or longitude']);
    exit;
}

$pythonScript = __DIR__ . '/get_timezone.py';

// ساخت دستور امن
$command = escapeshellcmd("python \"$pythonScript\" $latitude $longitude");


exec($command, $output, $return_var);


$outputString = implode("\n", $output);

if ($return_var !== 0) {
    echo json_encode(['error' => "Python script error: $outputString"]);
    exit;
}

// سعی کن خروجی رو decode کنی
$jsonData = json_decode($outputString, true);

if ($jsonData === null) {
    echo json_encode(['error' => 'Invalid JSON from Python script', 'raw_output' => $outputString]);
    exit;
}

// در نهایت JSON معتبر برگشت بده
echo json_encode($jsonData);
