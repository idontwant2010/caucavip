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
        die("Chỉ có thể sửa Booking đang chạy... Booking đã hoàn thành, huỷ không thể chỉnh sửa!!!");
    }

    $chuho_id = $booking['chu_ho_id'];
    $canthu_id = $booking['can_thu_id'];
    $total_amount = (int) $booking['total_amount'];
    $payment_method = $booking['payment_method'];


    $config = $pdo->query("SELECT config_key, config_value FROM admin_config_keys")
        ->fetchAll(PDO::FETCH_KEY_PAIR);
    $hold_amount = (int)($config['booking_hold_amount'] ?? 50000);
    $booking_fee = (int)($config['booking_fee_amount'] ?? 10000);
    $vat_percent = (int)($config['booking_vat_percent'] ?? 10);

    $refund_vat = floor($booking_fee * $vat_percent / 100);
    $hoan_tra = $hold_amount - $booking_fee - $refund_vat;



    if ($payment_method === 'Số dư user') {
        if ($total_amount < 0) {
            $chuho_balance = get_user_balance($pdo, $chuho_id);
            if ($chuho_balance < abs($total_amount)) {
                die("Chủ hồ không đủ số dư để hoàn thành giao dịch.");
            }
// trừ tiền chủ hồ:
            $stmt = $pdo->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
            $stmt->execute([abs($total_amount), $chuho_id]);
			// tính balance sau:
			$chuho_balance_sau = $chuho_balance-abs($total_amount);

            $stmt = $pdo->prepare("INSERT INTO booking_payment_logs (user_id, booking_id, amount, action, note)
                VALUES (?, ?, ?, 'sent', ?)");
            $stmt->execute([$chuho_id, $booking_id, -abs($total_amount), "Vé câu #$booking_id: Chủ hồ bị bẻ răng | Số dư cuối #$booking_id: $chuho_balance_sau vnd"]);

            $stmt = $pdo->prepare("INSERT INTO user_balance_logs (user_id, type, amount, note) VALUES (?, 'booking_pay', ?, ?)");
            $stmt->execute([$chuho_id, -abs($total_amount), "Vé câu #$booking_id: Chủ hồ bị bẻ răng | Số dư cuối #$booking_id: $chuho_balance_sau vnd"]);
//cộng tiền cần thủ
            $stmt = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
            $stmt->execute([abs($total_amount), $canthu_id]);
			// tính balance sau:		
            $canthu_balance = get_user_balance($pdo, $canthu_id);
			
            $stmt = $pdo->prepare("INSERT INTO booking_payment_logs (user_id, booking_id, amount, action, note)
                VALUES (?, ?, ?, 'received', ?)");
            $stmt->execute([$canthu_id, $booking_id, abs($total_amount), "Vé câu #$booking_id: Nhận tiền bẻ răng chủ hồ | Số dư cuối #$booking_id: $canthu_balance vnd"]);

            $stmt = $pdo->prepare("INSERT INTO user_balance_logs (user_id, type, amount, note) VALUES (?, 'booking_received', ?, ?)");
            $stmt->execute([$canthu_id, abs($total_amount), "Vé câu #$booking_id: Nhận tiền bẻ răng chủ hồ | Số dư cuối #$booking_id: $canthu_balance vnd"]);

        } else {
            $canthu_balance = get_user_balance($pdo, $canthu_id);
            if ($canthu_balance < abs($total_amount)) {
                die("Cần thủ không đủ số dư để thanh toán.");
            }
// trừ tiền cần thủ
            $stmt = $pdo->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
            $stmt->execute([abs($total_amount), $canthu_id]);
			
			//lấy balance can thu
			$canthu_balance_sau = get_user_balance($pdo, $canthu_id);
            $stmt = $pdo->prepare("INSERT INTO booking_payment_logs (user_id, booking_id, amount, action, note)
                VALUES (?, ?, ?, 'sent', ?)");
            $stmt->execute([$canthu_id, $booking_id, -abs($total_amount), "Vé câu #$booking_id: Bị chủ hồ bẻ răng | Số dư cuối: $canthu_balance_sau vnd"]);

            $stmt = $pdo->prepare("INSERT INTO user_balance_logs (user_id, type, amount, note) VALUES (?, 'booking_pay', ?, ?)");
            $stmt->execute([$canthu_id, -abs($total_amount), "Vé câu #$booking_id: Bị chủ hồ bẻ răng | Số dư cuối: $canthu_balance_sau vnd"]);
			
// cộng tiền chủ hồ
            $stmt = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
            $stmt->execute([abs($total_amount), $chuho_id]);

			$chuho_balance_sau_2 = get_user_balance($pdo, $chuho_id);

            $stmt = $pdo->prepare("INSERT INTO booking_payment_logs (user_id, booking_id, amount, action, note)
                VALUES (?, ?, ?, 'received', ?)");
            $stmt->execute([$chuho_id, $booking_id, abs($total_amount), "Vé câu #$booking_id: Bẻ răng Cần thủ | Số dư cuối #$booking_id: $chuho_balance_sau_2 vnd"]);

            $stmt = $pdo->prepare("INSERT INTO user_balance_logs (user_id, type, amount, note) VALUES (?, 'booking_received', ?, ?)");
            $stmt->execute([$chuho_id, abs($total_amount), "Vé câu #$booking_id: Bẻ răng Cần thủ | Số dư cuối #$booking_id: $chuho_balance_sau_2 vnd"]);
        }
    }

// Sau khi xử lý xong thanh toán mới đánh dấu hoàn thành
    $stmt = $pdo->prepare("UPDATE booking SET main_status = 'hoàn thành' WHERE id = ?");
    $stmt->execute([$booking_id]);

    $note = "Booking hoàn thành, phương thức: $payment_method";
    $stmt = $pdo->prepare("INSERT INTO booking_logs (booking_id, user_id, action, note) VALUES (?, ?, 'hoan_thanh', ?)");
    $stmt->execute([$booking_id, $user_id, $note]);

// Hoàn tiền giữ chỗ
    $stmt = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
    $stmt->execute([$hoan_tra, $canthu_id]);
	
	//lấy balance cần thủ
	$canthu_balance_sau_2 = get_user_balance($pdo, $canthu_id);	
    $stmt = $pdo->prepare("INSERT INTO booking_payment_logs (user_id, booking_id, amount, action, note)
        VALUES (?, ?, ?, 'refund', ?)");
    $stmt->execute([$canthu_id, $booking_id, $hoan_tra, "Hoàn tiền giữ chỗ booking #$booking_id sau khi trừ phí & VAT ||  Số dư: $canthu_balance_sau_2 vnd"]);
	
    $stmt = $pdo->prepare("INSERT INTO user_balance_logs (user_id, type, amount, note) VALUES (?, 'booking_refund', ?, ?)");
    $stmt->execute([$canthu_id, $hoan_tra, "Vé câu #$booking_id: Hoàn phí giữ chổ || Số dư cuối: $canthu_balance_sau_2 vnd"]);

    header("Location: booking_detail.php?id=$booking_id&status=done");
    exit;
} else {
    die("Phương thức không hợp lệ.");
}

function get_user_balance($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    return (int) $stmt->fetchColumn();
}
