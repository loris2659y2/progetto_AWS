<?php
include 'connect.php';

session_start(); 


$email = $_POST['email'];
$password = $_POST['password'];

$email=filter_var($email, FILTER_SANITIZE_EMAIL);

$query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $_SESSION['logged'] = true;
    $_SESSION['email'] = $email;
    header("Location: info.php");
} else {
    $_SESSION['error_message'] = "Non hai i permessi per visualizzare questa pagina";
    header("Location: index.html");
}
$conn->close();

