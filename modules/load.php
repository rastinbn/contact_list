<?php
require_once "../connection/config.php";
require_once "function.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || !$_SESSION['logged_in']) {
    echo "<tr><td colspan='7' class='text-center text-danger'>Please login to view your contacts.</td></tr>";
    exit();
}

$user_id = $_SESSION['user_id'];
$contacts = get_all_contacts($conn, $user_id);

if (empty($contacts)) {
    echo "<tr><td colspan='7' class='text-center'>No contacts found. Add your first contact!</td></tr>";
} else {
    foreach ($contacts as $index => $contact) {
        echo render_contact_row($contact, $index);
    }
}

$conn->close();
?>
