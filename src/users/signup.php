<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$lang_code = $_SESSION['lang'] ?? 'fa';
require_once __DIR__ . '/../../lang/' . $lang_code . '.php';
?>
<!doctype html>
<html lang="<?= $lang_code ?>" dir="<?= $lang_code === 'fa' ? 'rtl' : 'ltr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $lang['signup'] ?> - <?= $lang['app_title'] ?></title>
    <?php if ($lang_code === 'fa'): ?>
        <link rel="stylesheet" href="../../node_modules/bootstrap/dist/css/bootstrap.rtl.css">
    <?php else: ?>
    <link rel="stylesheet" href="../../node_modules/bootstrap/dist/css/bootstrap.css">
    <?php endif; ?>
    <link rel="stylesheet" href="../css/signup.css">
</head>
<body>
<?php include "../../componets/navbar.php"?>
<div class="container mt-3">
<div class="card">
    <div class="card-header bg-dark">
        <h1 class="card-title text-white"><?= $lang['signup'] ?></h1>
    </div>
    <div class="card-body">
        <form action="" class="form-control" method="post" id="SignUpForm">
            <div class="mb-3">
                <label for="username" class="form-label"><?= $lang['username'] ?>:</label>
                <input type="text" class="form-control" name="username" id="username" required>
                <div class="form-text"><?= $lang['username'] ?> <?= $lang['required_field'] ?></div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label"><?= $lang['email'] ?>:</label>
                <input type="text" class="form-control" name="email" id="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label"><?= $lang['password'] ?>:</label>
                <input type="password" class="form-control" name="password" id="password" required>
                <div class="bar-container mt-2">
                    <div class="bar"></div>
                    <div class="password-status"></div>
                </div>
            </div>
            <div class="mb-3">
                <label for="confirmPassword" class="form-label"><?= $lang['confirm_password'] ?>:</label>
                <input type="password" class="form-control" name="ConfirmPassword" id="confirmPassword" required>
                <div class="password-match"></div>
            </div>
        </form>
    </div>
    <div class="card-footer text-end">
        <button type="button" class="btn btn-success" id="submit" name="signUpSubmit" form="SignUpForm"><?= $lang['signup'] ?></button>
        <div class="mt-2 text-center">
            <small><?= $lang['already_have_account'] ?> <a href="login.php"><?= $lang['login_here'] ?></a></small>
        </div>
    </div>
</div>
</div>
<script>
window.I18N = <?php echo json_encode($lang, JSON_UNESCAPED_UNICODE); ?>;
</script>
<script src="../../node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
<script src="../../node_modules/jquery/dist/jquery.min.js"></script>
<script src="../js/signup.js"></script>
</body>
</html>
