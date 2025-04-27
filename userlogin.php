<?php
require 'armwareDBconnect.php';

session_start(); // Start a session for the user

$username_or_email = $_POST["username"];
$password = $_POST["password"];

// Find user by username or email
$GetUserId = "SELECT user_id FROM user_informations WHERE username = ? OR email = ?";
$prep_stmt = $conn->prepare($GetUserId);
$prep_stmt->bind_param("ss", $username_or_email, $username_or_email);
$prep_stmt->execute();
$result = $prep_stmt->get_result();
$prep_stmt->close();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userid = $row["user_id"];
} else {
    die("User not found.");
}

// Retrieve stored password hash
$checkPasswordQuery = "SELECT password FROM user_credentials WHERE user_id = ?";
$prep_stmt = $conn->prepare($checkPasswordQuery);
$prep_stmt->bind_param("i", $userid);
$prep_stmt->execute();
$result = $prep_stmt->get_result();
$prep_stmt->close();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Verify password securely
    if (password_verify($password, $row["password"])) {
        // Store user session
        $_SESSION["user_id"] = $userid;
        $_SESSION["logged_in"] = true;

        // Redirect to homepage
        header("Location: index.php");
        exit();
    } else {
        die("Invalid credentials.");
    }
} else {
    die("Error retrieving password.");
}

?>