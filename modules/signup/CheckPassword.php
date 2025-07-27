<?php
require_once "../../common/passwordstrange.php";


$queryPass = false;
$pass = new PasswordStrange($_POST['password']);
if ($pass ->isStrange()){
    $width = $pass->isStrange();
    echo $width;
    $queryPass = true;
}

?>