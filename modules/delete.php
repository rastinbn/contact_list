<?php
require "../connection/config.php";
require "security.php";
require_once "function.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$lang_code = $_SESSION['lang'] ?? 'fa';
require_once __DIR__ . '/../lang/' . $lang_code . '.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['logged_in']) {
    echo "<div class='alert alert-danger'>" . $lang['please_login'] . "</div>";
    exit;
}

if (
    $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<div class='alert alert-danger'>" . $lang['invalid_request'] . "</div>";
    exit;
}

$user_id = $_SESSION['user_id'];
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo "<div class='alert alert-danger'>" . $lang['invalid_contact_id'] . "</div>";
    exit;
}

$error = delete_contact($conn, $id, $user_id);
if ($error) {
    echo $error;
} else {
    echo "<div class='alert alert-success'>" . $lang['contact_deleted'] . "</div>";
}

$conn->close();
?>