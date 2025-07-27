<?php
function get_random_color() {
    $colors = ['#6f42c1', '#198754', '#0d6efd', '#fd7e14', '#dc3545', '#20c997'];
    return $colors[array_rand($colors)];
}

function sanitize($input) {
    $input = trim($input);
    $input = strip_tags($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    return $input;
}
function normalize_phone($phone) {
    $phone = trim($phone);
    $phone = preg_replace('/^\+98/', '', $phone);
    $phone = preg_replace('/[^0-9]/', '', $phone); 
    return $phone;
}
function handle_image_upload($file) {
    // A more robust check to ensure a file was actually uploaded.
    if (!$file || !isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return [null, null]; // No file uploaded, which is not an error.
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return [null, "<div class='alert alert-danger success-alert'>Error uploading image. Code: {$file['error']}</div>"];
    }

    $allowed_types = ['image/jpeg', 'image/png','image/jpg'];
    if (!in_array($file['type'], $allowed_types)) {
        return [null, "<div class='alert alert-danger success-alert'>Invalid image format. Only JPG, PNG, GIF allowed.</div>"];
    }

    $upload_dir = '../uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_name = uniqid('contact_', true) . '.' . $ext;
    $destination = $upload_dir . $new_name;
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return [null, "<div class='alert alert-danger success-alert'>Failed to move uploaded image.</div>"];
    }

    return ['uploads/' . $new_name, null]; // Return relative path and no error
}
function save_contact($conn, $first_name, $last_name, $numbers, $image_path) {
    // Insert contact info
    if ($image_path !== null) {
        $stmt = $conn->prepare("INSERT INTO contacts_info (firstname_contact, lastname_contact, photo_contact) VALUES (?, ?, ?)");
        if ($stmt === false) {
            return [0, "<div class='alert alert-danger success-alert'>Prepare failed: " . htmlspecialchars($conn->error) . "</div>"];
        }
        $stmt->bind_param("sss", $first_name, $last_name, $image_path);
    } else {
        $stmt = $conn->prepare("INSERT INTO contacts_info (firstname_contact, lastname_contact) VALUES (?, ?)");
        if ($stmt === false) {
            return [0, "<div class='alert alert-danger success-alert'>Prepare failed: " . htmlspecialchars($conn->error) . "</div>"];
        }
        $stmt->bind_param("ss", $first_name, $last_name);
    }
    if (!$stmt->execute()) {
        return [0, "<div class='alert alert-danger success-alert'>Database error on insert: " . htmlspecialchars($stmt->error) . "</div>"];
    }
    $id = $stmt->insert_id;
    $stmt->close();

    // Insert contact numbers
    $stmt = $conn->prepare("INSERT INTO contact_numbers (contact_id, number_contact) VALUES (?, ?)");
    foreach ($numbers as $number) {
        $stmt->bind_param("is", $id, $number);
        $stmt->execute();
    }
    $stmt->close();

    return [$id, null];
}

function update_contact($conn, $id, $first_name, $last_name, $numbers, $image_path) {
    // Update contact info
    if ($image_path !== null) {
        $stmt = $conn->prepare("UPDATE contacts_info SET firstname_contact = ?, lastname_contact = ?, photo_contact = ? WHERE id_contact = ?");
        if ($stmt === false) {
            return "<div class='alert alert-danger'>Prepare failed: " . htmlspecialchars($conn->error) . "</div>";
        }
        $stmt->bind_param("sssi", $first_name, $last_name, $image_path, $id);
    } else {
        $stmt = $conn->prepare("UPDATE contacts_info SET firstname_contact = ?, lastname_contact = ? WHERE id_contact = ?");
        if ($stmt === false) {
            return "<div class='alert alert-danger'>Prepare failed: " . htmlspecialchars($conn->error) . "</div>";
        }
        $stmt->bind_param("ssi", $first_name, $last_name, $id);
    }

    if (!$stmt->execute()) {
        return "<div class='alert alert-danger success-alert'>Database error on update: " . htmlspecialchars($stmt->error) . "</div>";
    }
    $stmt->close();

    // Delete old numbers and insert new ones
    $conn->query("DELETE FROM contact_numbers WHERE contact_id = $id");
    $stmt = $conn->prepare("INSERT INTO contact_numbers (contact_id, number_contact) VALUES (?, ?)");
    foreach ($numbers as $number) {
        $stmt->bind_param("is", $id, $number);
        $stmt->execute();
    }
    $stmt->close();

    return null;
}

function delete_contact($conn, $id) {
    // Also delete the image file if it exists
    $stmt = $conn->prepare("SELECT photo_contact FROM contacts_info WHERE id_contact = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if (!empty($row['photo_contact']) && file_exists('../' . $row['photo_contact'])) {
            unlink('../' . $row['photo_contact']);
        }
    }
    $stmt->close();

    // Delete contact from database
    $stmt = $conn->prepare("DELETE FROM contacts_info WHERE id_contact = ?");
    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        return "<div class='alert alert-danger'>Failed to delete contact.</div>";
    }
    $stmt->close();
    
    // Also delete associated numbers
    $stmt = $conn->prepare("DELETE FROM contact_numbers WHERE contact_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    return null;
}

function search_contacts($conn, $query) {
    $query = "%{$query}%";
    $stmt = $conn->prepare(
        "SELECT c.id_contact, c.firstname_contact, c.lastname_contact, c.photo_contact, GROUP_CONCAT(n.number_contact) AS numbers
         FROM contacts_info c
         LEFT JOIN contact_numbers n ON c.id_contact = n.contact_id
         WHERE c.firstname_contact LIKE ? OR c.lastname_contact LIKE ?
         GROUP BY c.id_contact
         ORDER BY c.firstname_contact, c.lastname_contact"
    );
    $stmt->bind_param("ss", $query, $query);
    $stmt->execute();
    $result = $stmt->get_result();
    $contacts = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $row['numbers_array'] = $row['numbers'] ? explode(',', $row['numbers']) : [];
            $contacts[] = $row;
        }
    }
    $stmt->close();
    return $contacts;
}

function get_all_contacts($conn) {
    $sql = "SELECT c.id_contact, c.firstname_contact, c.lastname_contact, c.photo_contact, GROUP_CONCAT(n.number_contact) AS numbers
            FROM contacts_info c
            LEFT JOIN contact_numbers n ON c.id_contact = n.contact_id
            GROUP BY c.id_contact
            ORDER BY c.firstname_contact, c.lastname_contact";

    $result = $conn->query($sql);
    $contacts = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $row['numbers_array'] = $row['numbers'] ? explode(',', $row['numbers']) : [];
            $contacts[] = $row;
        }
    }
    return $contacts;
}
function render_contact_row($contact, $index)
{
    $id = htmlspecialchars($contact['id_contact']);
    $fname = htmlspecialchars($contact['firstname_contact']);
    $lname = htmlspecialchars($contact['lastname_contact']);
    $numbers_json = htmlspecialchars(json_encode($contact['numbers_array']), ENT_QUOTES, 'UTF-8');

    // Avatar Logic
    $photo_path = $contact['photo_contact'];
    if (!empty($photo_path) && file_exists('../' . $photo_path)) {
        $avatar_html = "<img src='../" . htmlspecialchars($photo_path) . "' alt='Contact Image' width='50' height='50' class='img-thumbnail rounded-circle'>";
    } else {
        $char = strtoupper(mb_substr($fname, 0, 1));
        $color = get_random_color();
        $avatar_html = "<div class='rounded-circle text-white text-center d-flex justify-content-center align-items-center' 
                        style='background-color:{$color};width:50px;height:50px;font-size:1.2rem;font-weight:bold;'>{$char}</div>";
    }

    // Social Media Icons
    $social_media_html = '';
    if (!empty($contact['numbers_array']) && !empty($contact['numbers_array'][0])) {
        $first_number = $contact['numbers_array'][0];
        $normalized_phone = ltrim(normalize_phone($first_number), '0');
        $whatsapp_link = "https://wa.me/98" . $normalized_phone;
        $social_media_html = "
            <a href='{$whatsapp_link}' target='_blank' title='WhatsApp' class='text-success me-2'><i class='fab fa-whatsapp fs-4'></i></a>
            <a href='#' title='Telegram (Not Linkable)' class='text-muted'><i class='fab fa-telegram fs-4'></i></a>
        ";
    }
    // Numbers
    $numbers_html = implode("<br>", array_map('htmlspecialchars', $contact['numbers_array']));
    // Action Icons
    $actions_html = "
        <a href='#' class='text-primary edit-btn' title='Edit' data-id='{$id}' data-fname='{$fname}' data-lname='{$lname}' data-numbers='{$numbers_json}'>
            <i class='fa fa-edit fa-fw fs-5'></i>
        </a>
        <a href='#' class='text-danger delete-btn' title='Delete' data-id='{$id}' data-fname='{$fname}' data-lname='{$lname}'>
            <i class='fa fa-trash fa-fw fs-5'></i>
        </a>
    ";

    return "
        <tr>
            <td class='align-middle'>" . ($index + 1) . "</td>
            <td class='align-middle'>{$avatar_html}</td>
            <td class='align-middle'>{$fname}</td>
            <td class='align-middle'>{$lname}</td>
            <td class='align-middle'>{$numbers_html}</td>
            <td class='align-middle'>{$social_media_html}</td>
            <td class='align-middle'>{$actions_html}</td>
        </tr>
    ";
}