<?php
require_once "../../connection/config.php";
require_once "../function.php";

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function authenticateUser($username, $password, $remember = false) {
    global $conn;
    $response = array();
    $username = sanitize(trim($username));
    $password = trim($password);
    if (empty($username) || empty($password)) {
        $response['success'] = false;
        $response['message'] = 'Username/email and password are required';
        return $response;
    }
    $isEmail = filter_var($username, FILTER_VALIDATE_EMAIL);
    if ($isEmail) {
        $stmt = $conn->prepare("SELECT id, username, email, password FROM contacts_user WHERE email = ?");
    } else {
        $stmt = $conn->prepare("SELECT id, username, email, password FROM contacts_user WHERE username = ?");
    }
    
    if (!$stmt) {
        $response['success'] = false;
        $response['message'] = 'Database error occurred';
        return $response;
    }
    
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $response['success'] = false;
        $response['message'] = 'Invalid username/email or password';
        $stmt->close();
        return $response;
    }
    
    $user = $result->fetch_assoc();
    $stmt->close();
    
    if (sha1($password) === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();
        
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $expires = time() + (30 * 24 * 60 * 60);
            
            $stmt = $conn->prepare("UPDATE contacts_user SET remember_token = ? WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param("si", $token, $user['id']);
                $stmt->execute();
                $stmt->close();
            }
            
            setcookie('remember_token', $token, $expires, '/', '', true, true);
        }
        
        $response['success'] = true;
        $response['message'] = 'Login successful! Welcome back, ' . $user['username'];
        $response['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email']
        ];
    } else {
        $response['success'] = false;
        $response['message'] = 'Invalid username/email or password';
    }
    
    return $response;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']) && $_POST['remember'] === 'on';
    
    $result = authenticateUser($username, $password, $remember);
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?> 