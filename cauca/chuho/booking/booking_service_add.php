<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if (!in_array($_SESSION['user']['vai_tro'], ['chuho','admin','moderator'])) {
    die("Bạn không có quyền thêm dịch vụ.");
}

$booking_id   = (int)($_POST['booking_id'] ?? 0);
$ho_cau_id    = (int)($_POST['ho_cau_id'] ?? 0);
$service_type = trim($_POST['service_type'] ?? '');
$qty          = (float)($_POST['qty'] ?? 1);
$unit_price   = (int)($_POST['unit_price'] ?? 0);
$note         = trim($_POST['note'] ?? '');
$added_by     = $_SESSION['user']['id'] ?? 0;

if (!$booking_id || !$ho_cau_id || !$service_type) {
    die("Thiếu dữ liệu dịch vụ");
}

$amount = (int)round($qty * $unit_price, 0);

$stmt = $pdo->prepare("
    INSERT INTO booking_service_fee 
    (booking_id, ho_cau_id, service_type, qty, unit_price, amount, added_by, note, created_at) 
    VALUES (:bid, :hid, :stype, :qty, :price, :amount, :uid, :note, NOW())
");
$stmt->execute([
    'bid'    => $booking_id,
    'hid'    => $ho_cau_id,
    'stype'  => $service_type,
    'qty'    => $qty,
    'price'  => $unit_price,
    'amount' => $amount,
    'uid'    => $added_by,
    'note'   => $note
]);

// Ghi log
$log = $pdo->prepare("INSERT INTO booking_logs 
(booking_id, user_id, action, note) 
VALUES (:bid, :uid, :act, :note)");
$log->execute([
    'bid'  => $booking_id,
    'uid'  => $added_by,
    'act'  => 'add_service',
    'note' => "Thêm dịch vụ {$service_type}, SL {$qty}, ĐG {$unit_price}, TT {$amount}"
]);

header("Location: booking_detail.php?id={$booking_id}#tab-service");
exit;
