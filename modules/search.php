<?php
require_once "../connection/config.php";
require_once "function.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || !$_SESSION['logged_in']) {
    echo "<tr><td colspan='7' class='text-center text-danger'>Please login to search contacts.</td></tr>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['search'])) {
    echo "<tr><td colspan='7' class='text-center'>Invalid request.</td></tr>";
    exit();
}

$user_id = $_SESSION['user_id'];
$query = trim($_POST['search']);

if (empty($query)) {
    $contacts = get_all_contacts($conn, $user_id);
} else {
    $contacts = search_contacts($conn, $query, $user_id);
}

if (empty($contacts)) {
    echo "<tr><td colspan='7' class='text-center'>No contacts found matching your search.</td></tr>";
} else {
    foreach ($contacts as $index => $contact) {
        echo render_contact_row($contact, $index);
    }
}

$conn->close();
?>
