<?php
// Kết nối PDO
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cấu hình kết nối
$host = 'localhost';
$db = 'cauca';
$user = 'root';
$pass = '123456'; // Nên dùng biến môi trường thay vì hardcode
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false, // Tắt emulate để đảm bảo prepared statement thực sự
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    // Đảm bảo biến $pdo có sẵn cho các file khác
    if (!isset($GLOBALS['pdo'])) {
        $GLOBALS['pdo'] = $pdo;
    }
} catch (\PDOException $e) {
    error_log("Kết nối thất bại: " . $e->getMessage());
    die("Kết nối thất bại. Vui lòng kiểm tra log server.");
}
?>