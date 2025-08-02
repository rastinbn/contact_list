<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">لیست مخاطبین</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../src/index.php">مخاطبین من </a>
                    </li>
                <?php endif; ?>
            </ul>
            <div class="d-flex gap-2 align-items-center">
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <span class="navbar-text me-3">
                        خوش آمدید, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!
                    </span>
                    <a href="../modules/login/LogoutUser.php" class="btn btn-outline-danger">خروج</a>
                <?php else: ?>
                    <a href="signup.php" class="btn btn-dark">ثبت نام</a>
                    <a href="../src/users/login.php" class="btn btn-outline-dark">ورود</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
</body>
</html>
