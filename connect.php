<?php
$hostname="db";
$username="admin";
$password="admin";
$database="site";

$conn=new mysqli($hostname,$username,$password,$database);

if ($conn->connect_error) {
  die("Processo fallito: " . $conn->connect_error);
} else{
  //echo "ok";
}
?>