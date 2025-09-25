<?php
require_once '../../../connect.php';
require_once '../../../check_login.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('Truy cập không hợp lệ!'); window.location.href='../../canthu/';</script>";
    exit;
}

$giai_id = isset($_POST['giai_id']) ? (int)$_POST['giai_id'] : 0;
$action = $_POST['action'] ?? '';

if ($giai_id <= 0 || $action !== 'accept') {
    echo "<script>alert('Dữ liệu không hợp lệ!'); window.location.href='my_giai_detail_step_1.php?id={$giai_id}';</script>";
    exit;
}

// Lấy thông tin giải
$stmt = $pdo->prepare("SELECT * FROM giai_list WHERE id = ?");
$stmt->execute([$giai_id]);
$giai = $stmt->fetch();

if (!$giai || $giai['creator_id'] != $_SESSION['user']['id']) {
    echo "<script>alert('Bạn không có quyền thực hiện hành động này!'); window.location.href='my_giai_detail_step_1.php?id={$giai_id}';</script>";
    exit;
}

if ($giai['status'] !== 'dang_mo_dang_ky') {
    echo "<script>alert('Giải không còn ở trạng thái mở đăng ký.'); window.location.href='my_giai_detail_step_1.php?id={$giai_id}';</script>";
    exit;
}

// Lấy % yêu cầu từ admin_config_keys
//$stmt = $pdo->query("SELECT config_value FROM admin_config_keys WHERE config_key = 'tao_giai_percent'");
//$percent = (int) ($stmt->fetchColumn() ?: 60);

// Tính số cần thủ thấp nhất = số bảng x 4 người
$min_can_thu = $giai['so_bang'] * 4;

// Lấy danh sách user tham gia
$stmt = $pdo->prepare("SELECT user_id FROM giai_user WHERE giai_id = ?");
$stmt->execute([$giai_id]);
$user_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
$so_da_tham_gia = count($user_ids);


if ($so_da_tham_gia < $min_can_thu) {
    echo "<script>alert('Cần ít nhất {$min_can_thu} tham gia, tức là tối thiểu 4 người/bảng để chốt danh sách.'); window.location.href='my_giai_detail_step_1.php?id={$giai_id}';</script>";
    exit;
}

// Cập nhật số lượng thực tế + trạng thái
$stmt = $pdo->prepare("UPDATE giai_list SET so_luong_can_thu = ?, status = 'chot_xong_danh_sach' WHERE id = ?");
$stmt->execute([$so_da_tham_gia, $giai_id]);

// Xóa dữ liệu cũ trong giai_schedule
$stmt = $pdo->prepare("DELETE FROM giai_schedule WHERE giai_id = ?");
$stmt->execute([$giai_id]);

// Tạo dòng giai_schedule: user_id x so_hiep
foreach ($user_ids as $user_id) {
    for ($h = 1; $h <= $giai['so_hiep']; $h++) {
        $stmt = $pdo->prepare("INSERT INTO giai_schedule (giai_id, user_id, so_hiep, so_bang, vi_tri_ngoi, is_bien) VALUES (?, ?, ?, 0, 0, 0)");
        $stmt->execute([$giai_id, $user_id, $h]);
    }
}

$link = '';
if ($giai['so_hiep'] == 2) {
    $link = "my_giai_detail_step_2.php?id={$giai_id}";
} elseif ($giai['so_hiep'] == 3) {
    $link = "my_giai_detail_step_2.php?id={$giai_id}";
} elseif ($giai['so_hiep'] == 4) {
    $link = "my_giai_detail_step_2.php?id={$giai_id}";
} else {
    $link = "my_giai_detail_step_2.php?id={$giai_id}"; // fallback
}
echo 
"<script>
    alert('✅ Đã chốt danh sách thành công và tạo lịch thi đấu.');
    window.location.href='{$link}';
</script>";
