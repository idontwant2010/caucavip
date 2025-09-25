<?php
require_once __DIR__ . '/../../connect.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$phone = trim($_POST['phone'] ?? '');
$otp = $_POST['otp'] ?? '';
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm'] ?? '';

// Kiểm tra OTP
if ($otp !== '123456') {
    die("Mã OTP sai!");
}

// Kiểm tra mật khẩu
if (strlen($password) < 8 || $password !== $confirm) {
    die("Mật khẩu không hợp lệ hoặc không khớp!");
}

// Kiểm tra xem số điện thoại có tồn tại không
$stmt = $pdo->prepare("SELECT * FROM users WHERE phone = ?");
$stmt->execute([$phone]);
$user = $stmt->fetch();

if (!$user) {
    die("Số điện thoại không tồn tại!");
}

// Cập nhật mật khẩu
$hashed = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE phone = ?");
$stmt->execute([$hashed, $phone]);

// Tự động đăng nhập
$_SESSION['user'] = [
    'id' => $user['id'],
    'vai_tro' => $user['vai_tro'],
    'status' => $user['status'],
    'user_exp' => $user['user_exp'],
    'user_lever' => $user['user_lever'],
    'created_at' => $user['created_at']
];

// Chuyển hướng theo vai trò
switch ($user['vai_tro']) {
    case 'chuho':
        header("Location: /cauca/chuho/dashboard_chuho.php");
        break;
    case 'canthu':
        header("Location: /cauca/canthu/dashboard_canthu.php");
        break;
    default:
        header("Location: /auth/login.php");
}
exit;
?>
