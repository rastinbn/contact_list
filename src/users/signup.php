<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="../../node_modules/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="../css/signup.css">
</head>

<body>

<?php include "../../componets/navbar.php"?>
<div class="container mt-3">
<div class="card">
    <div class="card-header bg-dark">
        <h1 class="card-title text-white">Sign up</h1>
    </div>
    <div class="card-body">
        <form action="" class="form-control" method="post" id="SignUpForm">
            <label for="" class="form-label">User name:</label>
            <input type="text" class="form-control" name="username">
            <label for="" class="form-label">Email:</label>
            <input type="email" class="form-control" name="email">
            <label for="" class="form-label">Password:</label>
            <input type="text" class="form-control" name="password">
            <label for="" class="form-label">Confirm Password:</label>
            <input type="text" class="form-control" name="ConfirmPassword">
                <div class="bar-container">
                <div class="bar"></div>
                    <div class="password-status"></div>
                </div>
        </form>
    </div>
    <div class="card-footer text-end">
        <button type="button" class="btn btn-success" id="submit" name="signUpSubmit" form="SignUpForm">Submit</button>
    </div>
</div>
</div>
<script src="../../node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
<script src="../../node_modules/jquery/dist/jquery.min.js"></script>
<script src="../js/signup.js"></script>
</body>
</html>