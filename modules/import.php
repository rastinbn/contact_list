<?php
require "../connection/config.php";
require_once "function.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$lang_code = $_SESSION['lang'] ?? 'fa';
require_once __DIR__ . '/../lang/' . $lang_code . '.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['logged_in']) {
    header("Location: ../src/users/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

function clean_phone_number($number) {
    $number = trim($number);
    $number = preg_replace('/^\+98/', '', $number);
    $number = preg_replace('/[^\d]/', '', $number);
    return $number;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $file_tmp = $_FILES['csv_file']['tmp_name'];
    if (($handle = fopen($file_tmp, "r")) !== FALSE) {
        $header = fgetcsv($handle);
        if (!$header) {
            die($lang['invalid_csv_header']);
        }
        $header_map = array_flip($header);
        if (!isset($header_map['First Name']) || !isset($header_map['Last Name']) || !isset($header_map['Phone 1 - Value'])) {
            die($lang['csv_missing_required_columns']);
        }
        $conn->begin_transaction();
        try {
            while (($row = fgetcsv($handle)) !== FALSE) {
                $first_name = sanitize($row[$header_map['First Name']]);
                $last_name = sanitize($row[$header_map['Last Name']]);
                $phones_raw = $row[$header_map['Phone 1 - Value']];
                $phones_arr = explode(':::', $phones_raw);
                $clean_phones = [];
                foreach ($phones_arr as $phone) {
                    $clean_phone = clean_phone_number($phone);
                    if ($clean_phone !== '' && !in_array($clean_phone, $clean_phones)) {
                        $clean_phones[] = $clean_phone;
                    }
                }
                
                $stmt_contact = $conn->prepare("INSERT INTO contacts_info (firstname_contact, lastname_contact, user_id) VALUES (?, ?, ?)");
                if (!$stmt_contact) {
                    throw new Exception($lang['prepare_failed_contacts_info'] . $conn->error);
                }
                $stmt_contact->bind_param("ssi", $first_name, $last_name, $user_id);
                $stmt_contact->execute();
                $contact_id = $stmt_contact->insert_id;
                $stmt_contact->close();
                
                $stmt_number = $conn->prepare("SELECT cn.contact_id FROM contact_numbers cn 
                                             JOIN contacts_info ci ON cn.contact_id = ci.id_contact 
                                             WHERE cn.number_contact = ? AND ci.user_id = ?");
                if (!$stmt_number) {
                    throw new Exception($lang['prepare_failed_select_contact_numbers'] . $conn->error);
                }
                $stmt_insert_number = $conn->prepare("INSERT INTO contact_numbers (contact_id, number_contact) VALUES (?, ?)");
                if (!$stmt_insert_number) {
                    throw new Exception($lang['prepare_failed_insert_contact_numbers'] . $conn->error);
                }
                foreach ($clean_phones as $phone) {
                    $stmt_number->bind_param("si", $phone, $user_id);
                    $stmt_number->execute();
                    $stmt_number->store_result();
                    if ($stmt_number->num_rows > 0) {
                        $conn->rollback();
                        die($lang['duplicate_phone_number_found'] . htmlspecialchars($phone));
                    }

                    $stmt_insert_number->bind_param("is", $contact_id, $phone);
                    $stmt_insert_number->execute();
                }
                $stmt_number->close();
                $stmt_insert_number->close();
            }

            $conn->commit();
            header("location: ../src/index.php");

        } catch (Exception $e) {
            $conn->rollback();
            die($lang['import_failed'] . $e->getMessage());

        }

        fclose($handle);
    } else {
        die($lang['failed_to_open_csv_file']);
    }
} else {
    die($lang['invalid_request']);
}
?>