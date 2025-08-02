<?php
require_once "../../common/passwordstrange.php";
require_once "../function.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['ConfirmPassword'] ?? '';
    
    $password = trim($password);
    $confirmPassword = trim($confirmPassword);
    
    $response = array();
    
    if (empty($password)) {
        $response['success'] = false;
        $response['width'] = 0;
        $response['message'] = 'Password is required';
        echo json_encode($response);
        exit;
    }
    
    // Check password strength
    $passStrength = new PasswordStrange($password);
    $width = $passStrength->isStrange();
    
    // Check if passwords match
    $passwordsMatch = ($password === $confirmPassword);
    
    $response['success'] = true;
    $response['width'] = $width;
    $response['passwordsMatch'] = $passwordsMatch;
    
    // Add strength description
    if ($width > 75) {
        $response['strength'] = 'Very Strong';
        $response['color'] = 'darkgreen';
    } elseif ($width > 50) {
        $response['strength'] = 'Strong';
        $response['color'] = 'green';
    } elseif ($width > 25) {
        $response['strength'] = 'Medium';
        $response['color'] = 'yellow';
    } elseif ($width > 0) {
        $response['strength'] = 'Weak';
        $response['color'] = 'red';
    } else {
        $response['strength'] = 'Very Weak';
        $response['color'] = 'red';
    }
    
    $requirements = array();
    if (strlen($password) < 8) {
        $requirements[] = 'At least 8 characters';
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $requirements[] = 'At least one uppercase letter';
}
    if (!preg_match('/[a-z]/', $password)) {
        $requirements[] = 'At least one lowercase letter';
    }
    if (!preg_match('/[0-9]/', $password)) {
        $requirements[] = 'At least one number';
    }
    $response['requirements'] = $requirements;
    
    echo json_encode($response);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>