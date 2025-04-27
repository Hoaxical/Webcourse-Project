<?php

$servername = "localhost";
$username = "root";
$password = "";
$db_name = "armwaredb";

$conn = new mysqli($servername, $username, $password, $db_name);

if ($conn->connect_error){
    die("connection failed. Error: ".$conn->connect_error);
}
?>