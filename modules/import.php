<?php
require "../connection/config.php";
require_once "function.php";
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
            die("Invalid CSV header");
        }
        $header_map = array_flip($header);
        if (!isset($header_map['First Name']) || !isset($header_map['Last Name']) || !isset($header_map['Phone 1 - Value'])) {
            die("CSV missing required columns: First Name, Last Name, Phone 1 - Value");
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
                $stmt_contact = $conn->prepare("INSERT INTO contacts_info (firstname_contact, lastname_contact) VALUES (?, ?)");
                if (!$stmt_contact) {
                    throw new Exception("Prepare failed (contacts_info): " . $conn->error);
                }
                $stmt_contact->bind_param("ss", $first_name, $last_name);
                $stmt_contact->execute();
                $contact_id = $stmt_contact->insert_id;
                $stmt_contact->close();
                $stmt_number = $conn->prepare("SELECT contact_id FROM contact_numbers WHERE number_contact = ?");
                if (!$stmt_number) {
                    throw new Exception("Prepare failed (select contact_numbers): " . $conn->error);
                }
                $stmt_insert_number = $conn->prepare("INSERT INTO contact_numbers (contact_id, number_contact) VALUES (?, ?)");
                if (!$stmt_insert_number) {
                    throw new Exception("Prepare failed (insert contact_numbers): " . $conn->error);
                }
                foreach ($clean_phones as $phone) {
                    $stmt_number->bind_param("s", $phone);
                    $stmt_number->execute();
                    $stmt_number->store_result();
                    if ($stmt_number->num_rows > 0) {
                        $conn->rollback();
                        die("Duplicate phone number found: " . htmlspecialchars($phone));
                    }

                    $stmt_insert_number->bind_param("is", $contact_id, $phone);
                    $stmt_insert_number->execute();
                }
                $stmt_number->close();
                $stmt_insert_number->close();
            }

            $conn->commit();
            header("location : ../src/index.php");

        } catch (Exception $e) {
            $conn->rollback();
            die("Import failed: " . $e->getMessage());

        }

        fclose($handle);
    } else {
        die("Failed to open CSV file.");
    }
} else {
    die("Invalid request.");
}
/*/1- ساخت پروفایل و عکس برای هر مخاطب /**/
/*/2- ساخت یوزر ها برای اینکه هر یوزر مخاطب های خودش رو داشته باشه /**/
/*/3- ساخت یک شبکه اینترانت که مثلا بگه این مخاطبم برای این یوزر هم میتونه فعال باشه /**/