<?php
require_once "../connection/config.php";
require_once "function.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    // Redirect or show error if accessed directly or without ID
    header("Location: ../src/index.php");
    exit;
}

$id = intval($_POST['id']);
$error = delete_contact($conn, $id);

if ($error) {
  
    header("Location: ../src/index.php?error=" . urlencode($error));
} else {
    // Success, redirect back to the main page
    header("Location: ../src/index.php");
}

$conn->close();
?>