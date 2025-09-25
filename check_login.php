<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION['user'])) {
    header("Location: /index.php");
    exit;
}

// Gán ra biến dễ dùng (tuỳ chọn)
$user = $_SESSION['user'];

// Ví dụ kiểm tra quyền (tuỳ file sử dụng)
if (!in_array($user['vai_tro'], ['admin', 'moderator', 'chuho', 'canthu'])) {
    header("Location: /no_permission.php");
    exit;
}
?>
