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

$contact_id = isset($_GET['contact_id']) ? intval($_GET['contact_id']) : 0;

$shared_users = [];
if ($contact_id > 0) {
    $stmt = $conn->prepare("SELECT cu.id, cu.username FROM contact_shares cs JOIN contacts_user cu ON cs.shared_with_user_id = cu.id WHERE cs.contact_id = ?");
    $stmt->bind_param("i", $contact_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $shared_users[] = ['id' => $row['id'], 'username' => $row['username']];
    }
    $stmt->close();
}

echo json_encode($shared_users);

$conn->close();
?>
