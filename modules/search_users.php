<?php
require "../connection/config.php";
require_once "function.php";

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || !$_SESSION['logged_in']) {
    echo json_encode(['error' => 'Please log in.']);
    exit;
}
$current_user_id = $_SESSION['user_id'];
$search_query = clean_input($_GET['query'] ?? '', 100);

$users = [];
if (!empty($search_query)) {
    $stmt = $conn->prepare("SELECT id, username, email FROM contacts_user WHERE (username LIKE ? OR email LIKE ?) AND id != ? LIMIT 10");
    $search_param = "%" . $search_query . "%";
    $stmt->bind_param("ssi", $search_param, $search_param, $current_user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $users[] = ['id' => $row['id'], 'username' => $row['username'], 'email' => $row['email']];
    }
    $stmt->close();
}

echo json_encode($users);

$conn->close();
?>
