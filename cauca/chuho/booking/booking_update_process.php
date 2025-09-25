<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

$id = $_POST['id'];
$payment_method = $_POST['payment_method'] ?? 'Qr-code';
$real_start_time = $_POST['real_start_time'];
$real_end_time = $_POST['real_end_time'];
$fish_weight = floatval($_POST['fish_weight']);
$fish_sell_weight = floatval($_POST['fish_sell_weight']);


// Lấy thông tin booking gốc và bảng giá
$stmt = $pdo->prepare("SELECT b.*, g.gia_thu_lai, g.gia_ban_ca, g.base_duration, g.base_price, g.extra_unit_price, g.discount_2x_duration, g.discount_3x_duration, g.discount_4x_duration
                        FROM booking b
                        JOIN gia_ca_thit_phut g ON b.gia_id = g.id
                        WHERE b.id = ?");
$stmt->execute([$id]);
$bk = $stmt->fetch();
if (!$bk) die('Không tìm thấy booking');

// Tính toán lại thời lượng thực tế
$start = strtotime($real_start_time);
$end = strtotime($real_end_time);
$real_tong_thoi_luong = max(0, $end - $start) / 60; // phút
$real_so_suat = floor($real_tong_thoi_luong / $bk['base_duration']);
$real_gio_them = $real_tong_thoi_luong - ($real_so_suat * $bk['base_duration']);

// Tính discount
if ($real_so_suat == 1) {
    $real_discount = 0;
} elseif ($real_so_suat == 2) {
    $real_discount = $bk['discount_2x_duration'];
} elseif ($real_so_suat == 3) {
    $real_discount = $bk['discount_3x_duration'];
} elseif ($real_so_suat >= 4) {
    $real_discount = $bk['discount_4x_duration'];
} else {
    $real_discount = 0;
}

$real_amount_before = ($real_so_suat * $bk['base_price']) + ($real_gio_them * $bk['extra_unit_price'] / 15);
$real_amount = max(0, $real_amount_before - $real_discount);

//echo
//echo "<pre>";
//echo "real_so_suat = $real_so_suat\n";
//echo "base_price = " . $bk['base_price'] . "\n";
//echo "real_gio_them = $real_gio_them phút\n";
//echo "extra_unit_price = " . $bk['extra_unit_price'] . "\n";
//echo "extra_blocks = " . ($real_gio_them / 15) . " (15 phút mỗi block)\n";
//echo "real_amount_before = $real_amount_before\n";
//echo "</pre>";


// Tính tiền cá
$fish_return_amount = $fish_weight * $bk['gia_thu_lai'];
$fish_sell_amount = $fish_sell_weight * $bk['gia_ban_ca'];

// Xác định booking_amount thực tế
//$booking_amount = ($booking_status === 'Đã chuyển') ? $bk['booking_amount'] : 0;

// Tính total_amount
$total_amount = $real_amount + $fish_sell_amount - $fish_return_amount;

// Ghi log
$note = "THAY ĐỔI: thời gian $real_tong_thoi_luong phút || suất $real_so_suat || giờ thêm $real_gio_them || giảm giá $real_discount || trước giảm $real_amount_before || sau giảm $real_amount || cá về $fish_sell_weight kg || trả $fish_weight kg || cần thủ đã chuyển: $booking_amount || tổng cần thanh toán: $total_amount";
$stmt_log = $pdo->prepare("INSERT INTO booking_logs (booking_id, user_id, action, note, created_at) VALUES (?, ?, ?, ?, NOW())");
$stmt_log->execute([$id, $_SESSION['user']['id'], 'update', $note]);

// Cập nhật DB
$stmt_up = $pdo->prepare("UPDATE booking SET 
    payment_method = ?,
    real_start_time = ?,
    real_end_time = ?,
    real_tong_thoi_luong = ?,
    real_so_suat = ?,
    real_gio_them = ?,
    real_discount = ?,
    real_amount_before = ?,
    real_amount = ?,
    fish_weight = ?,
    fish_sell_weight = ?,
    fish_return_amount = ?,
    fish_sell_amount = ?,
    total_amount = ?,
    main_status = IF(?, 'hoàn thành', main_status)
    WHERE id = ?");
$stmt_up->execute([
    $payment_method,
    $real_start_time,
    $real_end_time,
    $real_tong_thoi_luong,
    $real_so_suat,
    $real_gio_them,
    $real_discount,
    $real_amount_before,
    $real_amount,
    $fish_weight,
    $fish_sell_weight,
    $fish_return_amount,
    $fish_sell_amount,
    $total_amount,
    $mark_complete,
    $id
]);

// TODO: cộng EXP và REF nếu đánh dấu hoàn thành

header("Location: booking_detail.php?id=$id");
exit;
