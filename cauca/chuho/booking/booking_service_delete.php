<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if (!in_array($_SESSION['user']['vai_tro'], ['chuho','admin','moderator'])) {
    die("Bạn không có quyền xoá dịch vụ.");
}

$id         = (int)($_POST['id'] ?? 0);
$booking_id = (int)($_POST['booking_id'] ?? 0);
$user_id    = $_SESSION['user']['id'] ?? 0;

if (!$id || !$booking_id) die("Thiếu dữ liệu xoá dịch vụ");

$stmt = $pdo->prepare("DELETE FROM booking_service_fee WHERE id = :id AND booking_id = :bid");
$stmt->execute(['id'=>$id, 'bid'=>$booking_id]);

$log = $pdo->prepare("INSERT INTO booking_logs 
(booking_id, user_id, action, note) 
VALUES (:bid, :uid, :act, :note)");
$log->execute([
    'bid'  => $booking_id,
    'uid'  => $user_id,
    'act'  => 'delete_service',
    'note' => "Xoá dịch vụ ID #{$id}"
]);

header("Location: booking_detail.php?id={$booking_id}#tab-service");
exit;
