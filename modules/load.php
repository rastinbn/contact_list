<?php
require_once "../connection/config.php";
require_once "function.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$lang_code = $_SESSION['lang'] ?? 'fa';
require_once __DIR__ . '/../lang/' . $lang_code . '.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !$_SESSION['logged_in']) {
    echo json_encode(['contacts' => [], 'total_pages' => 0, 'message' => $lang['please_login']]);
    exit();
}

$user_id = $_SESSION['user_id'];
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = isset($_GET['records_per_page']) ? (int)$_GET['records_per_page'] : 10;
$offset = ($page - 1) * $records_per_page;

$sort_field = isset($_GET['sort_field']) ? $_GET['sort_field'] : 'id_contact';
$sort_direction = isset($_GET['sort_direction']) ? $_GET['sort_direction'] : 'ASC';

$total_contacts = get_total_contacts($conn, $user_id);
$total_pages = ceil($total_contacts / $records_per_page);

$contacts = get_paginated_contacts($conn, $user_id, $records_per_page, $offset, $sort_field, $sort_direction);

// No longer rendering HTML here, just returning raw data
// $rendered_contacts = [];
// if (empty($contacts)) {
//     $rendered_contacts[] = "<tr><td colspan='7' class='text-center'>" . $lang['no_contacts_found'] . "</td></tr>";
// } else {
//     foreach ($contacts as $index => $contact) {
//         $rendered_contacts[] = render_contact_row($contact, $index);
//     }
// }

echo json_encode([
    'contacts' => $contacts,
    'total_pages' => $total_pages
]);

$conn->close();
?>
