<?php
$servername = "localhost";
$username = "root";
$password = "123456";
$database = "cauca";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>
