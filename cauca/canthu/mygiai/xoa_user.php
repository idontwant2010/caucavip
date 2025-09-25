<?php
require_once '../../../connect.php';
require_once '../../../check_login.php';

$giai_id = isset($_GET['giai_id']) ? (int)$_GET['giai_id'] : 0;
$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

if ($giai_id <= 0 || $user_id <= 0) {
    echo "<script>alert('Thiếu thông tin.'); window.location.href='my_giai_detail_step_1.php?id={$giai_id}';</script>";
    exit;
}

// Kiểm tra quyền sở hữu giải
$stmt = $pdo->prepare("SELECT * FROM giai_list WHERE id = ?");
$stmt->execute([$giai_id]);
$giai = $stmt->fetch();



if (!$giai || $giai['creator_id'] != $_SESSION['user']['id']) {
    echo "<script>alert('Bạn không có quyền xoá người khỏi giải này.'); window.location.href='my_giai_detail_step_1.php?id={$giai_id}';</script>";
    exit;
}

// ✅ Kiểm tra trạng thái giải có cho phép xoá không
if ($giai['status'] !== 'dang_mo_dang_ky') {
    echo "<script>alert('Bạn chỉ có thể xoá cần thủ khi giải trạng thái Đang-mở-đăng-ký!!'); window.location.href='my_giai_detail_step_1.php?id={$giai_id}';</script>";
    exit;
}

// 1. Kiểm tra user trong giải
$stmt = $pdo->prepare("SELECT id, trang_thai FROM giai_user WHERE giai_id = ? AND user_id = ?");
$stmt->execute([$giai_id, $user_id]);
$record = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$record) {
    die("Không tìm thấy user trong giải này.");
}

// 2. Nếu trạng thái là 'da_thanh_toan' thì không cho xoá
if ($record['trang_thai'] === 'da_thanh_toan') {
    echo "<script>alert('Không thể xoá: User này đã thanh toán.'); window.location.href='my_giai_detail_step_1.php?id={$giai_id}';</script>";
    exit;
}

// Kiểm tra thông tin người dùng
$stmt = $pdo->prepare("SELECT u.full_name, u.phone FROM users u WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "<script>alert('Không tìm thấy người dùng cần xoá.'); window.location.href='my_giai_detail_step_1.php?id={$giai_id}';</script>";
    exit;
}

// Nếu chưa xác nhận xoá, hiển thị popup confirm
if (!isset($_GET['confirm'])) {
    echo "<script>
        if (confirm('Bạn có chắc chắn muốn xoá cần thủ: {$user['full_name']} – SĐT: {$user['phone']}?')) {
            window.location.href = 'xoa_user.php?giai_id={$giai_id}&user_id={$user_id}&confirm=1';
        } else {
            window.location.href = 'my_giai_detail_step_1.php?id={$giai_id}';
        }
    </script>";
    exit;
}

// Tiến hành xoá
$stmt = $pdo->prepare("DELETE FROM giai_user WHERE giai_id = ? AND user_id = ?");
$stmt->execute([$giai_id, $user_id]);

header("Location: my_giai_detail_step_1.php?id=$giai_id");
exit;
