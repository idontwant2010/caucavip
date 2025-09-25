<?php
// booking_finalize_step2.php
session_start();
require '../../../connect.php';
require '../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'chuho') { http_response_code(403); exit('Forbidden'); }

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function money_vnd($n){ return number_format((int)$n, 0, ',', '.') . ' đ'; }

// ---- Input
$booking_id = isset($_POST['booking_id']) ? (int)$_POST['booking_id'] : 0;
if ($booking_id <= 0) { echo "Thiếu booking id"; exit; }

// ---- Lấy booking + ho + cum_ho + chủ hồ + cần thủ (CÓ JOIN cum_ho để lấy chu_ho_id)
$st = $pdo->prepare("
    SELECT 
        b.*,
        h.id           AS ho_id,
        h.ten_ho,
        ch.id          AS cum_ho_id,
        ch.chu_ho_id   AS owner_id,       -- chủ hồ nằm ở cum_ho
        uo.full_name   AS owner_full_name,
        uo.balance     AS owner_balance,
        uc.id          AS canthu_id,
        uc.full_name   AS canthu_full_name,
        uc.balance     AS canthu_balance,
        b.payment_method
    FROM booking b
    JOIN ho_cau h   ON h.id = b.ho_cau_id             -- ho_cau.cum_ho_id :contentReference[oaicite:2]{index=2}
    JOIN cum_ho ch  ON ch.id = h.cum_ho_id            -- cum_ho.chu_ho_id :contentReference[oaicite:3]{index=3}
    JOIN users uo   ON uo.id = ch.chu_ho_id
    LEFT JOIN users uc ON uc.id = b.can_thu_id
    WHERE b.id = :id
    LIMIT 1
");
$st->execute([':id'=>$booking_id]);
$bk = $st->fetch(PDO::FETCH_ASSOC);
if (!$bk) { echo "Không tìm thấy booking."; exit; }

// Chỉ chủ hồ của hồ này được finalize
if ((int)$bk['owner_id'] !== (int)$_SESSION['user']['id']) {
    http_response_code(403);
    exit('Bạn không có quyền xử lý booking này.');
}

// ---- Tính tiền cần thanh toán (ưu tiên các cột đã có)
function compute_amount_to_pay(array $bk): int {
    if (isset($bk['total_amount'])) return (int)$bk['total_amount'];
    if (isset($bk['final_total'])) return (int)$bk['final_total'];
    $real_amount        = (int)($bk['real_amount']        ?? 0);
    $fish_sell_amount   = (int)($bk['fish_sell_amount']   ?? 0);
    $fish_return_amount = (int)($bk['fish_return_amount'] ?? 0);
    $booking_amount     = (int)($bk['booking_amount']     ?? 0);
    $booking_discount   = (int)($bk['booking_discount']   ?? 0);
    return $real_amount + $fish_sell_amount - $fish_return_amount - $booking_amount - $booking_discount;
}

$amountToPay = compute_amount_to_pay($bk);
$amountToPayPos = max(0, $amountToPay); // chỉ thu tiền dương

$now = date('Y-m-d H:i:s');
$ownerId  = (int)$bk['owner_id'];
$canthuId = (int)($bk['canthu_id'] ?? 0);
$method   = $bk['payment_method'] ?? 'Tiền mặt';

// === Giao dịch để an toàn tiền tệ
$pdo->beginTransaction();
try {
    // Khóa các dòng số dư để tránh race-condition (nếu dùng InnoDB)
    // Lock user balance rows if needed
    if ($method === 'balance' && $amountToPayPos > 0 && $canthuId > 0) {
        $lock1 = $pdo->prepare("SELECT id, balance FROM users WHERE id = :id FOR UPDATE");
        $lock1->execute([':id'=>$canthuId]);
        $lock2 = $pdo->prepare("SELECT id, balance FROM users WHERE id = :id FOR UPDATE");
        $lock2->execute([':id'=>$ownerId]);
        $rowCanThu = $lock1->fetch(PDO::FETCH_ASSOC);
        $rowOwner  = $lock2->fetch(PDO::FETCH_ASSOC);
        $balCanThu = (int)($rowCanThu['balance'] ?? 0);
        if ($balCanThu < $amountToPayPos) {
            throw new RuntimeException("Balance cần thủ không đủ (cần ".money_vnd($amountToPayPos).", còn ".money_vnd($balCanThu).")");
        }
        // Trừ/cộng số dư
        $dec = $pdo->prepare("UPDATE users SET balance = balance - :x WHERE id = :id");
        $dec->execute([':x'=>$amountToPayPos, ':id'=>$canthuId]);
        $inc = $pdo->prepare("UPDATE users SET balance = balance + :x WHERE id = :id");
        $inc->execute([':x'=>$amountToPayPos, ':id'=>$ownerId]);

        // Ghi booking_payment_logs (3 giá trị action bạn từng dùng: sent/received,...)
        $insPay = $pdo->prepare("
            INSERT INTO booking_payment_logs (booking_id, action, amount, from_user_id, to_user_id, created_at, note)
            VALUES (:bid, :act, :amt, :from_id, :to_id, :ts, :note)
        ");
        // Cần thủ -> Chủ hồ
        $insPay->execute([
            ':bid'=>$booking_id,
            ':act'=>'sent',
            ':amt'=>$amountToPayPos,
            ':from_id'=>$canthuId,
            ':to_id'=>$ownerId,
            ':ts'=>$now,
            ':note'=>'Thanh toán bằng balance'
        ]);
        $insPay->execute([
            ':bid'=>$booking_id,
            ':act'=>'received',
            ':amt'=>$amountToPayPos,
            ':from_id'=>$canthuId,
            ':to_id'=>$ownerId,
            ':ts'=>$now,
            ':note'=>'Chủ hồ nhận tiền (balance)'
        ]);
    }

    // Set trạng thái booking => hoàn thành (nhánh Tiền mặt / Chuyển khoản cũng vào đây)
    // Nếu DB bạn dùng cột 'booking_status' thì sửa đúng tên cột.
		$upd = $pdo->prepare("
			UPDATE booking 
			SET booking_status = 'hoàn thành',
				payment_status = 'Đã thanh toán'
			WHERE id = :id
		");
		$upd->execute([':id' => $booking_id]);

// Ghi booking_logs (event hoàn thành)
$insLog = $pdo->prepare("
    INSERT INTO booking_logs (booking_id, user_id, action, note, created_at)
    VALUES (:bid, :uid, :act, :note, NOW())
");

$note = match ($method) {
    'balance'      => "Hoàn thành: thanh toán bằng balance (".money_vnd($amountToPayPos).")",
    'Chuyển khoản' => "Hoàn thành: thanh toán chuyển khoản",
    default        => "Hoàn thành: thanh toán tiền mặt",
};

$insLog->execute([
    ':bid'  => $booking_id,
    ':uid'  => (int)$_SESSION['user']['id'],
    ':act'  => 'finalize',
    ':note' => mb_substr($note, 0, 255) // tránh vượt 255 ký tự
]);
    $pdo->commit();
    echo "<script>alert('Đã hoàn thành booking #{$booking_id}.'); window.location.href='booking_detail.php?id={$booking_id}';</script>";
    exit;

} catch (Throwable $e) {
    $pdo->rollBack();
    // Báo lỗi gọn gàng
    echo "<div style='padding:16px;color:#b00020;border:1px solid #f3c2c2;border-radius:8px;'>
            Lỗi finalize: ".h($e->getMessage())."
          </div>
          <p><a href='booking_finalize_step1.php?id={$booking_id}'>← Quay lại</a></p>";
    exit;
}
