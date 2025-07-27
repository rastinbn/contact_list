<?php
require_once '../connection/config.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !$_SESSION['logged_in']) {
    header("Location: ../src/users/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=contacts.csv');
$output = fopen('php://output', 'w');
$headers = [
    'First Name', 'Middle Name', 'Last Name',
    'Phonetic First Name', 'Phonetic Middle Name', 'Phonetic Last Name',
    'Name Prefix', 'Name Suffix', 'Nickname', 'File As',
    'Organization Name', 'Organization Title', 'Organization Department',
    'Birthday', 'Notes', 'Photo', 'Labels',
    'Phone 1 - Label', 'Phone 1 - Value'
];
fputcsv($output, $headers);

$query = "SELECT * FROM contacts_info WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $contact_id = $row['id_contact'];
    $numbers = [];
    $number_query = "SELECT number_contact FROM contact_numbers WHERE contact_id = ?";
    $number_stmt = $conn->prepare($number_query);
    $number_stmt->bind_param("i", $contact_id);
    $number_stmt->execute();
    $number_result = $number_stmt->get_result();

    while ($number_row = $number_result->fetch_assoc()) {
        $number = trim($number_row['number_contact']);
        $number = preg_replace('/^\+98/', '0', $number);
        $number = preg_replace('/^0+/', '', $number);
        $numbers[] = '+98 ' . $number;
    }
    $number_stmt->close();
    
    $numbers_csv = implode(' ::: ', $numbers);
    $label =  'Mobile ::: * myContacts';
    $csv_row = [
        $row['firstname_contact'],
        '',
        $row['lastname_contact'],
        '', '', '',
        '', '', '',
        '',
        '', '', '',
        '', '', '',
        '',
        $label,
        $numbers_csv
    ];
    fputcsv($output, $csv_row);
}

$stmt->close();
fclose($output);
exit;
?>
