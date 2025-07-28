<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || !$_SESSION['logged_in']) {
    header("Location: users/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contacts App</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../node_modules/animate.css/animate.css">
</head>
<body>
<?php include "../componets/navbar.php"?>
<div class="container">

    <div class="card mt-3" id="show_contacts">

        <div class="card-header"><h1>Contacts</h1>

        </div>
        <div class="card-body">
            <form class="form-control bg-transparent border-0 d-flex flex-row align-items-center gap-3 mb-3">
                <label class="form-label">Search:</label>
                <input type="search" name="search" class="form-control" placeholder="Search by first or last name">
            </form>
            <table class="table table-light">
                <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>picture</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Numbers</th>
                    <th>social media</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody id="table_body"></tbody>
            </table>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary" id="add">Add Contact</button>
            <a href="../modules/export.php" class="btn btn-success">Export CSV</a>
            <button id="import" class="btn btn-secondary">Import</button>
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
                <label>First Name:</label>
                <input type="text" class="form-control" name="first_name" id="first_name">
                <label>Last Name:</label>
                <input type="text" class="form-control" name="last_name" id="last_name">
                <label for="" class="form-label">Upload image:</label>
                <input type="file" class="form-control" name="contact_image">
                <label>Numbers:</label>
                <div id="number-fields"></div>
                <button type="button" id="add-number" class="btn btn-sm btn-info mt-1">Add Number</button>
            </form>
        </div>

        <div class="card-footer">
            <button class="btn btn-success" form="form1" type="submit">Submit</button>
            <button class="btn btn-danger" id="cancel">Cancel</button>
            <div id="alert-placeholder"></div>
        </div>
    </div>
    <div class="card mt-2" id="import_contact">
        <div class="card-header">
            <h1>import Contacts</h1>
        </div>
        <div class="card-body">
            <form id="import-form" method="post" enctype="multipart/form-data" action="../modules/import.php" class="my-3">
                <label for="csv_file">Import Contacts (.csv):</label>
                <input type="file" name="csv_file" id="csv_file" accept=".csv" required class="form-control w-50 d-inline">
                <button type="submit" class="btn btn-success">Import</button>
            </form>
        </div>
    </div>
</div>
<div id="message" class="mt-3 text-center"></div>
<script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
<script src="../node_modules/jquery/dist/jquery.min.js"></script>
<script  src="js/app.js"></script>
</body>
</html>