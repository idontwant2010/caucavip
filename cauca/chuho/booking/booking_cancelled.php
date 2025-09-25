<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = intval($_POST['booking_id'] ?? 0);
    $user_id = $_SESSION['user']['id'] ?? 0;

    $stmt = $pdo->prepare("SELECT * FROM booking WHERE id = ?");
    $stmt->execute([$booking_id]);
    $booking = $stmt->fetch();

    if (!$booking || $booking['main_status'] !== 'đang chạy') {
        die("Chỉ có thể huỷ Booking đang chạy... booking đã hoàn thành, huỷ không thể chỉnh sửa!!!");
    }

    $canthu_id = $booking['can_thu_id'];

    // Hoàn tiền giữ chỗ
    $config = $pdo->query("SELECT config_key, config_value FROM admin_config_keys")
        ->fetchAll(PDO::FETCH_KEY_PAIR);
    $hold_amount = (int)($config['booking_hold_amount'] ?? 50000);
    $booking_fee = (int)($config['booking_fee_amount'] ?? 10000);
    $vat_percent = (int)($config['booking_vat_percent'] ?? 10);

    $refund_vat = floor($booking_fee * $vat_percent / 100);
    $hoan_tra = $hold_amount - $booking_fee - $refund_vat;

    // Cập nhật trạng thái huỷ
    $stmt = $pdo->prepare("UPDATE booking SET main_status = 'đã huỷ' WHERE id = ?");
    $stmt->execute([$booking_id]);

    // Ghi log huỷ
    $note = "Chủ hồ huỷ booking do quá hạn";
    $stmt = $pdo->prepare("INSERT INTO booking_logs (booking_id, user_id, action, note) VALUES (?, ?, 'cancel', ?)");
    $stmt->execute([$booking_id, $user_id, $note]);

    // Hoàn tiền giữ chỗ
    $stmt = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
    $stmt->execute([$hoan_tra, $canthu_id]);

	// lấy balance cần thủ
	$stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
	$stmt->execute([$canthu_id]);
	$canthu_balance = (int) $stmt->fetchColumn();

    $stmt = $pdo->prepare("INSERT INTO booking_payment_logs (user_id, booking_id, amount, action, note)
        VALUES (?, ?, ?, 'refund', ?)");
    $stmt->execute([$canthu_id, $booking_id, $hoan_tra, "Vé câu #$booking_id: Hoàn phí giữ chổ || Số dư cuối: $canthu_balance vnd"]);

    $stmt = $pdo->prepare("INSERT INTO user_balance_logs (user_id, type, amount, note)
        VALUES (?, 'booking_refund', ?, ?)");
    $stmt->execute([$canthu_id, $hoan_tra, "Vé câu #$booking_id: Hoàn phí giữ chổ || Số dư cuối: $canthu_balance vnd"]);

    header("Location: booking_detail.php?id=$booking_id&status=cancelled");
    exit;
} else {
    die("Phương thức không hợp lệ.");
}
