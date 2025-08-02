<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - Contact List</title>
    <link rel="stylesheet" href="../../../node_modules/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="../css/signup.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>

<?php include "../../../componets/navbarfa.php"?>
<div class="container mt-3">
<div class="card">
    <div class="card-header bg-dark">
        <h1 class="card-title text-white">ورود</h1>
    </div>
    <div class="card-body">
        <form action="" class="form-control" method="post" id="LoginForm">
            <div class="mb-3">
                <label for="username" class="form-label">نام کاربری یا ایمیل:</label>
                <input type="text" class="form-control" name="username" id="username" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">رمز عبور:</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>
            
            <div class="mb-3 form-check position-relative">
                <input type="checkbox" class="form-check-input position-absolute" id="remember" name="remember">
                <label class="form-check-label" for="remember">منو بخاطر بیاور</label>
            </div>
        </form>
    </div>
    <div class="card-footer text-end">
        <button type="button" class="btn btn-success" id="submit" name="loginSubmit" form="LoginForm">ورود</button>
        <div class="mt-2 text-center">
            <small>اگر اکانت ندارید؟ <a href="signup.php">اینجا ثبت نام کنید</a></small>
        </div>
    </div>
</div>
</div>
<script src="../../../node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
<script src="../../../node_modules/jquery/dist/jquery.min.js"></script>
<script src="../../js/login.js"></script>
</body>
</html>
