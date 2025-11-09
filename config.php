<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "health";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}
?>
