<?php
// caucavip/cron/cron_cancel_online_bookings.php
// cron chuyển status của các booking_where = "online" qua "Đã huỷ" sau 1 ngày nếu cần thủ ko câu!
// Refund booking_amount về cho cần thủ + ghi user_balance_logs

require __DIR__ . '/../connect.php';
date_default_timezone_set('Asia/Ho_Chi_Minh');

// ================== CẤU HÌNH ==================
const GRACE_MINUTES = 60;   // trễ hơn X phút coi như quá hạn
const BATCH_LIMIT   = 200;  // xử lý tối đa X booking mỗi lần

// (Tùy chọn) cho phép override nhanh khi gọi CLI: --grace=15 --limit=50 --dry=1 --debug=1
$DRY_RUN = false;  $DEBUG = false;  $grace = GRACE_MINUTES;  $limit = BATCH_LIMIT;
foreach ($argv ?? [] as $arg) {
  if (str_starts_with($arg, '--grace=')) $grace = max(1, (int)substr($arg, 8));
  if (str_starts_with($arg, '--limit=')) $limit = max(1, (int)substr($arg, 8));
  if ($arg === '--dry=1')   $DRY_RUN = true;
  if ($arg === '--debug=1') $DEBUG   = true;
}
// ==============================================

function nowStr(){ return date('Y-m-d H:i:s'); }
function makeRefNo($prefix='REFB'){ return $prefix . date('YmdHis') . rand(100,999); }

echo "[".nowStr()."] Start cron_cancel_online_bookings (grace={$grace}m, limit={$limit}, dry=".($DRY_RUN?'1':'0').")\n";

// ===== Truy vấn danh sách booking candidate (đúng logic bản UI) =====
$sql = "
SELECT b.id, b.nguoi_tao_id, b.can_thu_id, b.booking_time, b.booking_start_time, b.booking_amount
FROM booking b
WHERE b.booking_where = 'online'
  AND b.booking_status = 'Đang chạy'
  AND b.payment_status = 'Chưa thanh toán'
  AND COALESCE(b.booking_amount,0) > 0

  -- Chỉ hôm qua trở về trước (không đụng hôm nay)
  AND COALESCE(b.booking_start_time, b.booking_time) < CURDATE()

  -- Quá hạn theo grace
  AND (
       (b.booking_start_time IS NOT NULL AND b.booking_start_time < (NOW() - INTERVAL :grace1 MINUTE))
    OR (b.booking_start_time IS NULL     AND b.booking_time      < (NOW() - INTERVAL :grace2 MINUTE))
  )
ORDER BY b.booking_start_time IS NULL, b.booking_start_time ASC, b.booking_time ASC
LIMIT $limit
";
$st = $pdo->prepare($sql);
$st->bindValue(':grace1', (int)$grace, PDO::PARAM_INT);
$st->bindValue(':grace2', (int)$grace, PDO::PARAM_INT);
$st->execute();
$rows = $st->fetchAll(PDO::FETCH_ASSOC);

if ($DEBUG) echo "[DEBUG] Found ".count($rows)." candidate(s)\n";
if (!$rows) { echo "[".nowStr()."] No overdue online bookings.\n"; exit(0); }

$ok=0; $skip=0; $fail=0;

foreach ($rows as $r) {
  $bid    = (int)$r['id'];
  $uid    = (int)($r['can_thu_id'] ?: $r['nguoi_tao_id']); // online thường trùng nhau
  $refund = (int)($r['booking_amount'] ?? 0);

  if ($uid <= 0 || $refund <= 0) { $skip++; if($DEBUG) echo "[SKIP] booking #$bid invalid uid/refund\n"; continue; }

  try {
    $pdo->beginTransaction();

    // 1) Khóa booking
    $stBk = $pdo->prepare("SELECT booking_status, payment_status, booking_amount FROM booking WHERE id=? FOR UPDATE");
    $stBk->execute([$bid]);
    $bk = $stBk->fetch(PDO::FETCH_ASSOC);
    if (!$bk) { $pdo->rollBack(); $skip++; if($DEBUG) echo "[SKIP] booking #$bid not found\n"; continue; }

    // Double-check trong transaction
    if ($bk['booking_status'] !== 'Đang chạy' || $bk['payment_status'] !== 'Chưa thanh toán') {
      $pdo->rollBack(); $skip++; if($DEBUG) echo "[SKIP] booking #$bid status changed\n"; continue;
    }
    $refund = (int)$bk['booking_amount'];
    if ($refund <= 0) {
      $pdo->rollBack(); $skip++; if($DEBUG) echo "[SKIP] booking #$bid refund=0\n"; continue;
    }

    // 2) Khóa user & lấy balance
    $stU = $pdo->prepare("SELECT balance FROM users WHERE id=? FOR UPDATE");
    $stU->execute([$uid]);
    $balanceBefore = $stU->fetchColumn();
    if ($balanceBefore === false) throw new Exception("User #$uid not found");
    $balanceBefore = (float)$balanceBefore;

    // 3) Tính số dư sau khi hoàn
    $balanceAfter = $balanceBefore + $refund;

    // Dry-run: không thay đổi DB
    if ($DRY_RUN) {
      $pdo->rollBack(); $skip++;
      if ($DEBUG) echo "[DRY] booking #$bid would cancel & refund {$refund}đ -> user #$uid\n";
      continue;
    }

    // 4) Update users.balance
    $pdo->prepare("UPDATE users SET balance=? WHERE id=?")->execute([$balanceAfter, $uid]);

    // 5) Update booking_status => Đã huỷ (giữ payment_status)
    $stCancel = $pdo->prepare("UPDATE booking SET booking_status='Đã huỷ' WHERE id=? AND booking_status='Đang chạy'");
    $stCancel->execute([$bid]);
    if ($stCancel->rowCount() === 0) { // race-condition
      $pdo->rollBack(); $skip++; if($DEBUG) echo "[SKIP] booking #$bid race-condition\n"; continue;
    }

    // 6) Ghi log ví
    $note  = "Auto cancel booking #$bid, hoàn cọc " . number_format($refund) . "đ";
    $refNo = makeRefNo();
    $pdo->prepare("
      INSERT INTO user_balance_logs
        (user_id, change_amount, type, amount, note, ref_no, balance_before, balance_after)
      VALUES (?,?,?,?,?,?,?,?)
    ")->execute([
      $uid,
      +$refund,            // cộng tiền vào ví
      'booking_refund',    // type theo schema bạn đang dùng
      $refund,             // số tiền gốc hoàn lại
      $note,
      $refNo,
      $balanceBefore,
      $balanceAfter
    ]);

    $pdo->commit();
    $ok++;
    echo "[".nowStr()."] OK booking #$bid -> cancel & refund {$refund}đ to user #$uid\n";
  } catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    $fail++;
    error_log("cron_cancel_online_bookings booking #$bid error: ".$e->getMessage());
    echo "[".nowStr()."] FAIL booking #$bid: ".$e->getMessage()."\n";
  }
}

echo "[".nowStr()."] Done. OK=$ok, SKIP=$skip, FAIL=$fail\n";
