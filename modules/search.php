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

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['search'])) {
    echo json_encode(['contacts' => [], 'total_pages' => 0, 'message' => $lang['invalid_request']]);
    exit();
}

$user_id = $_SESSION['user_id'];
$query = trim($_POST['search']);
$records_per_page = isset($_POST['records_per_page']) ? (int)$_POST['records_per_page'] : 10;
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$offset = ($page - 1) * $records_per_page;
$sort_field = isset($_POST['sort_field']) ? $_POST['sort_field'] : 'id_contact';
$sort_direction = isset($_POST['sort_direction']) ? $_POST['sort_direction'] : 'ASC';

$total_contacts = get_total_contacts_search($conn, $query, $user_id);
$total_pages = ceil($total_contacts / $records_per_page);

$contacts = search_contacts($conn, $query, $user_id, $records_per_page, $offset, $sort_field, $sort_direction);

$rendered_contacts = [];
if (empty($contacts)) {
    $rendered_contacts[] = "<tr><td colspan='7' class='text-center'>" . $lang['no_contacts_found'] . "</td></tr>";
} else {
    foreach ($contacts as $index => $contact) {
        $rendered_contacts[] = render_contact_row($contact, $index);
    }
}

echo json_encode([
    'contacts' => $rendered_contacts,
    'total_pages' => $total_pages
]);

$conn->close();
?>
