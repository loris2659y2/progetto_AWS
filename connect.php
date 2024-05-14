<?php
$hostname="db";
$username="root";
$password="mariadb";
$database="site";

$conn=new mysqli($hostname,$username,$password,$database);

if ($conn->connect_error) {
  die("Processo fallito: " . $conn->connect_error);
} else{
  //echo "ok";
}
?>