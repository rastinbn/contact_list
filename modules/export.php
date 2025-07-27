<?php
require_once '../connection/config.php';
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
$query = "SELECT * FROM contacts_info";
$result = $conn->query($query);
while ($row = $result->fetch_assoc()) {
    $contact_id = $row['id_contact'];
    $numbers = [];
    $number_query = "SELECT number_contact FROM contact_numbers WHERE contact_id = $contact_id";
    $number_result = $conn->query($number_query);

    while ($number_row = $number_result->fetch_assoc()) {
        $number = trim($number_row['number_contact']);
        $number = preg_replace('/^\+98/', '0', $number);
        $number = preg_replace('/^0+/', '', $number);
        $numbers[] = '+98 ' . $number;
    }
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
fclose($output);
exit;
