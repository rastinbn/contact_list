<?php
header('Content-Type: application/json');

if (isset($_POST['latitude']) && isset($_POST['longitude'])) {
    $latitude = escapeshellarg($_POST['latitude']);
    $longitude = escapeshellarg($_POST['longitude']);

    // Path to your Python executable and script
    $python_executable = 'python'; // Or 'python3', or the full path like '/usr/bin/python3'
    $python_script = __DIR__ . '/get_timezone.py';

    // Execute the Python script
    $command = "$python_executable $python_script $latitude $longitude";
    $timezone = shell_exec($command);

    if ($timezone !== null) {
        echo trim($timezone);
    } else {
        echo json_encode(['error' => 'Failed to get timezone from Python.']);
    }
} else {
    echo json_encode(['error' => 'Latitude and Longitude not provided.']);
}
?>
