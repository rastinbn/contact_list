<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$lang_code = $_SESSION['lang'] ?? 'fa';
require_once __DIR__ . '/../lang/' . $lang_code . '.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['logged_in']) {
    header("Location: users/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="<?= $lang_code ?>" dir="<?= $lang_code === 'fa' ? 'rtl' : 'ltr' ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $lang['app_title'] ?></title>
    <?php if ($lang_code === 'fa'): ?>
        <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.rtl.css">
    <?php else: ?>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.css">
    <?php endif; ?>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../node_modules/animate.css/animate.css">
</head>
<body>
<?php include "../componets/navbar.php"?>
<div class="container">
    <div class="card mt-3" id="show_contacts">
        <div class="card-header"><h1><?= $lang['contacts'] ?></h1></div>
        <div class="card-body">
            <form class="form-control bg-transparent border-0 d-flex flex-row align-items-center gap-3 mb-3">
                <label class="form-label"><?= $lang['search'] ?>:</label>
                <input type="search" name="search" class="form-control" placeholder="<?= $lang['search'] ?>">
            </form>
            <div class="d-flex flex-row align-items-center gap-3 mb-3">
                <label class="form-label pt-2"><?= $lang['sort_by_label'] ?></label>
                <button class="btn btn-sm btn-outline-secondary sort-btn" data-field="firstname_contact" data-direction="asc"><?= $lang['sort_by_first_name'] ?></button>
                <button class="btn btn-sm btn-outline-secondary sort-btn" data-field="lastname_contact" data-direction="asc"><?= $lang['sort_by_last_name'] ?></button>
            </div>
            <table class="table table-light">
                <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th><?= $lang['picture'] ?></th>
                    <th><?= $lang['first_name'] ?></th>
                    <th><?= $lang['last_name'] ?> </th>
                    <th><?= $lang['numbers'] ?> </th>
                    <th><?= $lang['social_media'] ?> </th>
                    <th><?= $lang['action'] ?> </th>
                </tr>
                </thead>
                <tbody id="table_body"></tbody>
            </table>
            <nav>
                <ul class="pagination justify-content-center" id="pagination_controls"></ul>
            </nav>
            <input type="hidden" id="current_page" value="1">
            <input type="hidden" id="records_per_page" value="10">
        </div>
        <div class="card-footer">
            <button class="btn btn-primary" id="add"><?= $lang['add_contact'] ?></button>
            <a href="../modules/export.php" class="btn btn-success"><?= $lang['export_csv'] ?></a>
            <button id="import" class="btn btn-secondary"><?= $lang['import'] ?></button>
        </div>
    </div>
    <div class="card mt-3" id="add_contacts">
        <div class="card-header">
            <h1 id="form_title"></h1>
        </div>
        <div class="card-body">
            <form id="form1" method="post" enctype="multipart/form-data">
                <input type="hidden" id="action" name="action" value="save">
                <input type="hidden" name="id" id="id">
                <label><?= $lang['first_name'] ?>:</label>
                <input type="text" class="form-control" name="first_name" id="first_name">
                <label><?= $lang['last_name'] ?>:</label>
                <input type="text" class="form-control" name="last_name" id="last_name">
                <label for="" class="form-label"><?= $lang['upload_image'] ?>:</label>
                <input type="file" class="form-control" name="contact_image">
                <label><?= $lang['numbers'] ?>:</label>
                <div id="number-fields"></div>
                <button type="button" id="add-number" class="btn btn-sm btn-info mt-1"><?= $lang['add_number'] ?></button>
                
                <div class="mt-3">
                    <label><?= $lang['share_contact_with'] ?>:</label>
                    <input type="text" class="form-control" id="search_users_input" placeholder="<?= $lang['search_users'] ?>">
                    <div id="search_results" class="list-group mt-2"></div>
                    <div id="selected_users_display" class="mt-2 d-flex flex-wrap gap-2">
                    </div>
                    <input type="hidden" name="shared_with_users[]" id="shared_with_users_hidden">
                </div>
            </form>
        </div>
        <div class="card-footer">
            <button class="btn btn-success" form="form1" type="submit"><?= $lang['submit'] ?></button>
            <button class="btn btn-danger" id="cancel"><?= $lang['cancel'] ?></button>
            <div id="alert-placeholder"></div>
        </div>
    </div>
    <div class="card mt-2" id="import_contact">
        <div class="card-header">
            <h1><?= $lang['import_contacts'] ?></h1>
        </div>
        <div class="card-body">
            <form id="import-form" method="post" enctype="multipart/form-data" action="../modules/import.php" class="my-3">
                <label for="csv_file"><?= $lang['import_contacts_csv'] ?>:</label>
                <input type="file" name="csv_file" id="csv_file" accept=".csv" required class="form-control w-50 d-inline">
                <button type="submit" class="btn btn-success"><?= $lang['import'] ?></button>
            </form>
        </div>
    </div>
</div>
<div id="message" class="mt-3 text-center"></div>
<script>
window.I18N = <?php echo json_encode($lang, JSON_UNESCAPED_UNICODE); ?>;
</script>
<script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
<script src="../node_modules/jquery/dist/jquery.min.js"></script>
<script  src="js/app.js"></script>
</body>
</html>