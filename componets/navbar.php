<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$lang_code = $_SESSION['lang'] ?? 'fa';
require_once __DIR__ . '/../lang/' . $lang_code . '.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $lang['app_title'] ?></title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/src/index.php"><?= $lang['app_title'] ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../src/index.php"><?= $lang['contacts'] ?></a>
                </li>
                <?php endif; ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="langDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= $lang_code === 'fa' ? 'فارسی' : 'English' ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="langDropdown">
                        <li><a class="dropdown-item" href="?lang=fa">فارسی</a></li>
                        <li><a class="dropdown-item" href="?lang=en">English</a></li>
                    </ul>
                </li>
            </ul>
            <div class="d-flex gap-2 align-items-center">
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <span class="navbar-text me-3">
                        <?= $lang['welcome'] ?>, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!
                        <span id="timezone-display" class="badge bg-info"></span>
                    </span>
                    <a href="../modules/login/LogoutUser.php" class="btn btn-outline-danger"><?= $lang['logout'] ?></a>
                <?php else: ?>
                    <a href="signup.php" class="btn btn-dark"><?= $lang['signup'] ?></a>
                    <a href="../src/users/login.php" class="btn btn-outline-dark"><?= $lang['login'] ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<?php
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'] === 'en' ? 'en' : 'fa';
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit();
}
?>
</body>
</html>
