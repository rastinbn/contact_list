<?php
require_once "../connection/config.php";
require_once "function.php";

$query = $_GET['query'] ?? '';

if (empty($query)) {
    $contacts = get_all_contacts($conn);
} else {
    $contacts = search_contacts($conn, $query);
}

if (empty($contacts)) {
    echo "<tr><td colspan='7' class='text-center'>No contacts found.</td></tr>";
} else {
    foreach ($contacts as $index => $contact) {
        echo render_contact_row($contact, $index);
    }
}

$conn->close();
?>
