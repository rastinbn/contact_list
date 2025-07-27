<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign Up - Contact List</title>
    <link rel="stylesheet" href="../../node_modules/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="../css/signup.css">
</head>

<body>

<?php include "../../componets/navbar.php"?>
<div class="container mt-3">
<div class="card">
    <div class="card-header bg-dark">
        <h1 class="card-title text-white">Sign Up</h1>
    </div>
    <div class="card-body">
        <form action="" class="form-control" method="post" id="SignUpForm">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" name="username" id="username" required>
                <div class="form-text">Username must be 3-50 characters and can contain letters, numbers, and underscores.</div>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" name="email" id="email" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" name="password" id="password" required>
                <div class="bar-container mt-2">
                    <div class="bar"></div>
                    <div class="password-status"></div>
                </div>

            </div>
            
            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password:</label>
                <input type="password" class="form-control" name="ConfirmPassword" id="confirmPassword" required>
                <div class="password-match"></div>
            </div>
        </form>
    </div>
    <div class="card-footer text-end">
        <button type="button" class="btn btn-success" id="submit" name="signUpSubmit" form="SignUpForm">Create Account</button>
        <div class="mt-2 text-center">
            <small>Already have an account? <a href="login.php">Login here</a></small>
        </div>
    </div>
</div>
</div>
<script src="../../node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
<script src="../../node_modules/jquery/dist/jquery.min.js"></script>
<script src="../js/signup.js"></script>
</body>
</html>