<?php
include 'connect.php';

session_start(); 


$email = $_POST['email'];
$password = $_POST['password'];

$password = md5($password);
$email=filter_var($email, FILTER_SANITIZE_EMAIL);

$query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $_SESSION['logged'] = true;
    $_SESSION['email'] = $email;
    header("Location: ../pages/info.php");
} else {
    $_SESSION['error_message'] = "Credenziali errate. Riprova";
    header("Location: ../pages/login.php");
}
$conn->close();
