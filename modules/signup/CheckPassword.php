<?php
require_once "../../common/passwordstrange.php";
require_once "../function.php";

$queryPass = false;
$password = $_POST['password'];
$password = sanitize($password);
$pass = new PasswordStrange($password);
if ($pass ->isStrange()){
    $width = $pass->isStrange();
    echo $width;
    $queryPass = true;
}

?>