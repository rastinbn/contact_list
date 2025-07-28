<?php
require "../connection/config.php";
require "security.php";
require_once "function.php";

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !$_SESSION['logged_in']) {
    echo "<div class='alert alert-danger'>Please login to manage contacts.</div>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<div class='alert alert-danger'>Invalid request.</div>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Sanitize inputs
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$first_name = clean_input($_POST['first_name'] ?? '', 50);
$last_name = clean_input($_POST['last_name'] ?? '', 50);
$numbers = $_POST['number'] ?? [];

// Validate inputs
if (empty($first_name) || empty($last_name)) {
    echo "<div class='alert alert-danger'>First and Last names are required.</div>";
    exit;
}
if (!validate_name($first_name) || !validate_name($last_name)) {
    echo "<div class='alert alert-danger'>Name must be alphabetic and 2-50 chars long.</div>";
    exit;
}

$clean_numbers = [];
foreach ($numbers as $num) {
    if (empty($num)) continue;
    $clean = clean_phone($num);
    if (!is_valid_phone($clean)) {
        echo "<div class='alert alert-danger'>Invalid phone number format: " . htmlspecialchars($num) . "</div>";
        exit;
    }
    $clean_numbers[] = $clean;
}

if (empty($clean_numbers)) {
    echo "<div class='alert alert-danger'>At least one valid phone number is required.</div>";
    exit;
}

list($image_path, $error) = handle_image_upload($_FILES['contact_image'] ?? null);
if ($error) {
    echo $error;
    exit;
}

if ($id > 0) {
    $error = update_contact($conn, $id, $first_name, $last_name, $clean_numbers, $image_path, $user_id);
    if ($error) {
        echo $error;
    } else {
        echo "<div class='alert alert-success'>Contact updated successfully.</div>";
    }
} else {
    list($new_id, $error) = save_contact($conn, $first_name, $last_name, $clean_numbers, $image_path, $user_id);
    if ($error) {
        echo $error;
    } else {
        echo "<div class='alert alert-success'>Contact saved successfully.</div>";
    }
}

$conn->close();
?>
