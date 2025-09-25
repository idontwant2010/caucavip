<?php
require_once '../../../connect.php';
require_once '../../../check_login.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: my_giai_detail_step_1.php');
    exit;
}

$giai_id = isset($_POST['giai_id']) ? (int)$_POST['giai_id'] : 0;
$phone = trim($_POST['phone'] ?? '');
$full_name = trim($_POST['full_name'] ?? '');

// Kiểm tra định dạng số điện thoại
if (!preg_match('/^0(32|33|34|35|36|37|38|39|52|53|54|55|56|57|58|59|70|74|75|76|77|78|79|81|82|83|84|85|86|87|88|89|90|91|92|93|94|95|96|97|98|99)\d{7}$/', $phone)) {
    echo "<script>alert(' ❌ Số-điện-thoại không đúng hoặc số ảo, vui lòng điền số đúng để nhận giải và ghi thành tích trong tương lai...!'); window.history.back();</script>";
    exit;
}

if ($giai_id <= 0 || $phone === '' || $full_name === '') {
    echo "<script>alert('Thiếu thông tin bắt buộc.'); window.location.href='my_giai_detail_step_1.php?id={$giai_id}';</script>";
    exit;
}

// Kiểm tra xem giải có tồn tại và đúng người tạo không
$stmt = $pdo->prepare("SELECT * FROM giai_list WHERE id = ?");
$stmt->execute([$giai_id]);
$giai = $stmt->fetch();

if (!$giai || $giai['creator_id'] != $_SESSION['user']['id']) {
    echo "<script>alert('Bạn không có quyền thêm người vào giải này.'); window.location.href='my_giai_detail_step_1.php?id={$giai_id}';</script>";
    exit;
}

// Kiểm tra số lượng đã đăng ký
$stmt = $pdo->prepare("SELECT COUNT(*) FROM giai_user WHERE giai_id = ?");
$stmt->execute([$giai_id]);
$so_nguoi_da_tham_gia = (int) $stmt->fetchColumn();

if ($so_nguoi_da_tham_gia >= $giai['so_luong_can_thu']) {
    echo "<script>alert('Giải đã đủ số lượng cần thủ. Vui lòng liên hệ ban tổ chức.'); window.location.href='my_giai_detail_step_1.php?id={$giai_id}';</script>";
    exit;
}

// Kiểm tra xem user đã tồn tại chưa
$stmt = $pdo->prepare("SELECT * FROM users WHERE phone = ?");
$stmt->execute([$phone]);
$user = $stmt->fetch();

$is_guest = 0;
if (!$user) {
    // Tạo user mới (guest)
    $nickname = 'nickname_' . rand(100001, 999999);
    $fake_password = password_hash('baocong00', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (phone, full_name, nickname, password, status, review_status, vai_tro) VALUES (?, ?, ?, ?, 'chưa xác minh', 'no', 'canthu')");
    $stmt->execute([$phone, $full_name, $nickname, $fake_password]);
    $user_id = $pdo->lastInsertId();
    $is_guest = 1;
} else {
    $user_id = $user['id'];
}

// Kiểm tra xem đã tham gia giải chưa
$stmt = $pdo->prepare("SELECT COUNT(*) FROM giai_user WHERE giai_id = ? AND user_id = ?");
$stmt->execute([$giai_id, $user_id]);
if ($stmt->fetchColumn() > 0) {
    echo "<script>alert('Người dùng này đã tham gia giải.'); window.location.href='my_giai_detail_step_1.php?id={$giai_id}';</script>";
    exit;
}

// Gán nickname nếu có
$nickname = $user['nickname'] ?? $nickname ?? 'guest';
$note = "được chủ giải thêm vào";

// Thêm vào bảng giai_user
$stmt = $pdo->prepare("INSERT INTO giai_user (giai_id, user_id, nickname, trang_thai, da_thanh_toan, note) VALUES (?, ?, ?, 'cho_xac_nhan', 0, ?)");
$stmt->execute([$giai_id, $user_id, $nickname, $note]);

header("Location: my_giai_detail_step_1.php?id=$giai_id");
exit;


