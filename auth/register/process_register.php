<?php
require_once __DIR__ . '/../../connect.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lấy dữ liệu
$role = $_POST['role'] ?? '';
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$otp = $_POST['otp'] ?? '';

// Kiểm tra OTP giả lập
if ($otp !== '123456') {
    die("Sai mã OTP!");
}

// Kiểm tra dữ liệu cơ bản
if (!in_array($role, ['canthu', 'chuho']) || !preg_match('/^0[0-9]{9}$/', $phone) || strlen($password) < 8) {
    die("Dữ liệu không hợp lệ!");
}

// Kiểm tra trùng số điện thoại
$stmt_check = $pdo->prepare("SELECT id FROM users WHERE phone = ?");
$stmt_check->execute([$phone]);
if ($stmt_check->fetch()) {
    die("Số điện thoại đã tồn tại!");
}

// Gán dữ liệu
$hashed_pw = password_hash($password, PASSWORD_DEFAULT);
$status = 'Chưa xác minh';
$review_status = $role === 'canthu' ? 'yes' : 'no';

$stmt_insert = $pdo->prepare("INSERT INTO users (phone, password, vai_tro, status, review_status) VALUES (?, ?, ?, ?, ?)");
$stmt_insert->execute([$phone, $hashed_pw, $role, $status, $review_status]);

// Tự động đăng nhập sau khi lấy lại user từ DB
$user_id = $pdo->lastInsertId();
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$_SESSION['user'] = [
    'id' => $user['id'],
    'vai_tro' => $user['vai_tro'],
    'status' => $user['status'],
    'user_exp' => $user['user_exp'],
    'user_lever' => $user['user_lever'],
    'created_at' => $user['created_at']
];

// Điều hướng đến dashboard tương ứng
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
