<?php
require_once '../connect.php';
session_start();

$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($phone) || empty($password)) {
    echo "Vui lòng nhập đầy đủ thông tin.";
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE phone = :phone LIMIT 1");
    $stmt->execute(['phone' => $phone]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Gán toàn bộ thông tin vào session 'user'
        $_SESSION['user'] = [
            'id' => $user['id'],
            'vai_tro' => $user['vai_tro'],
            'status' => $user['status'],
            'user_exp' => $user['user_exp'],
            'user_lever' => $user['user_lever'],
            'created_at' => $user['created_at']
        ];

        // Điều hướng theo vai trò
        switch ($user['vai_tro']) {
            case 'admin':
                header("Location: ../cauca/admin/dashboard_admin.php");
                break;
            case 'moderator':
                header("Location: ../cauca/moderator/dashboard_moderator.php");
                break;
            case 'chuho':
                header("Location: ../cauca/chuho/dashboard_chuho.php");
                break;
            case 'canthu':
                header("Location: ../cauca/canthu/dashboard_canthu.php");
                break;
            default:
                header("Location: ../unauthorized.php");
                break;
        }
        exit;
    } else {
        echo "Số điện thoại hoặc mật khẩu không đúng.";
    }
} catch (PDOException $e) {
    echo "Lỗi hệ thống: " . $e->getMessage();
}
?>
