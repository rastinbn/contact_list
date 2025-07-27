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
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo "<div class='alert alert-danger'>Invalid contact ID.</div>";
    exit;
}

$error = delete_contact($conn, $id, $user_id);
if ($error) {
    echo $error;
} else {
    echo "<div class='alert alert-success'>Contact deleted successfully.</div>";
}

$conn->close();
?>