<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "contacts_db";
$conn = new mysqli($servername , $username , $password , $dbname);
if ($conn ->connect_error){
    die($conn->connect_error);
}