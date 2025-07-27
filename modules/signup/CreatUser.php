<?php
require_once "../../connection/config.php";
require_once "../function.php";
require_once "../../common/passwordstrange.php";

header('Content-Type: application/json');

// Function to create user with proper validation and security
function createUser($username, $email, $password, $confirmPassword) {
    global $conn;
    
    $response = array();
    
    $username = sanitize(trim($username));
    $email = sanitize(trim($email));
    $password = trim($password);
    $confirmPassword = trim($confirmPassword);
    
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $response['success'] = false;
        $response['message'] = 'All fields are required';
        return $response;
    }
    
    if (strlen($username) < 3 || strlen($username) > 50) {
        $response['success'] = false;
        $response['message'] = 'Username must be between 3 and 50 characters';
        return $response;
    }
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $response['success'] = false;
        $response['message'] = 'Username can only contain letters, numbers, and underscores';
        return $response;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['success'] = false;
        $response['message'] = 'Please enter a valid email address';
        return $response;
    }
    
    if ($password !== $confirmPassword) {
        $response['success'] = false;
        $response['message'] = 'Passwords do not match';
        return $response;
    }
    
    $passStrength = new PasswordStrange($password);
    $strength = $passStrength->isStrange();
    
    if ($strength < 50) {
        $response['success'] = false;
        $response['message'] = 'Password is too weak. Please use a stronger password';
        return $response;
    }
    

    
    $stmt = $conn->prepare("SELECT id FROM contacts_user WHERE username = ?");
    if (!$stmt) {
        $response['success'] = false;
        $response['message'] = 'Database error: ' . $conn->error;
        return $response;
    }
    
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $response['success'] = false;
        $response['message'] = 'Username already exists';
        $stmt->close();
        return $response;
    }
    $stmt->close();
    
    $stmt = $conn->prepare("SELECT id FROM contacts_user WHERE email = ?");
    if (!$stmt) {
        $response['success'] = false;
        $response['message'] = 'Database error: ' . $conn->error;
        return $response;
    }
    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $response['success'] = false;
        $response['message'] = 'Email already exists';
        $stmt->close();
        return $response;
    }
    $stmt->close();
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO contacts_user (username, email, password) VALUES (?, ?, ?)");
    if (!$stmt) {
        $response['success'] = false;
        $response['message'] = 'Database error: ' . $conn->error;
        return $response;
    }
    
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'User registered successfully!';
        $response['user_id'] = $stmt->insert_id;
    } else {
        $response['success'] = false;
        $response['message'] = 'Registration failed: ' . $stmt->error;
    }
    
    $stmt->close();
    return $response;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['ConfirmPassword'] ?? '';
    
    $result = createUser($username, $email, $password, $confirmPassword);
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>