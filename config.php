<?php

$db_servername = "localhost";
$db_username = "root";
$db_password = "";
$db_database = "demo";

$conn = mysqli_connect($db_servername,$db_username,$db_password,$db_database);

if($conn === false){
    die("Connection_error".mysqli_connect_error());
}



?>
