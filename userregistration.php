<?php

session_start();

require 'armwareDBconnect.php';



$fname = $_POST["fname"];
$lname = $_POST["lname"];
$username = $_POST["username"];
$email = $_POST["email"];
$dob = $_POST["dob"];
$password = $_POST["confirm-password"];

// Hash password before storing
$password_hash = password_hash($password, PASSWORD_DEFAULT);

$INSERT_userInfo = "
INSERT INTO user_informations (fname, lname, username, email, DoB)
VALUES (?, ?, ?, ?, ?);
";

$INSERT_userCreds = "
INSERT INTO user_credentials (user_id, password)
VALUES (?, ?);
";

// Insert into user_informations
$prep_stmt = $conn->prepare($INSERT_userInfo);
$prep_stmt->bind_param("sssss", $fname, $lname, $username, $email, $dob);
if (!$prep_stmt->execute()) {
    die("Error inserting user info: " . $prep_stmt->error);
}
$prep_stmt->close();

// Get last inserted user_id
$user_id = $conn->insert_id;

// Insert into user_credentials
$prep_stmt = $conn->prepare($INSERT_userCreds);
$prep_stmt->bind_param("is", $user_id, $password_hash);
if (!$prep_stmt->execute()) {
    die("Error inserting user credentials: " . $prep_stmt->error);
}


$prep_stmt->close();

$conn->close();

$_SESSION["user_id"] = $user_id;
$_SESSION["logged_in"] = true;


header("Location: index.php");
exit();

?>