<?php
// canthu/payment/cron_cancel_online_bookings.php
// Chạy thủ công để test auto-cancel booking online quá hạn + refund cọc.
// Khi chạy ổn, copy phần "logic chính" sang cron/cron_cancel_online_bookings.php (CLI).

require '../../../connect.php';
date_default_timezone_set('Asia/Ho_Chi_Minh');

// ===== Cấu hình mặc định qua GET =====
$GRACE_MINUTES = isset($_GET['grace']) ? max(1, (int)$_GET['grace']) : 60;   // quá hạn X phút
$BATCH_LIMIT   = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 200;  // số bản ghi xử lý 1 lần
$DRY_RUN       = !empty($_GET['dry']);
$DEBUG         = !empty($_GET['debug']);
$FORCE_ERR     = !empty($_GET['force']);

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function nowStr(){ return date('Y-m-d H:i:s'); }
function makeRefNo($prefix='REFB'){ return $prefix . date('YmdHis') . rand(100,999); }

$log = [];
function logln($s){ global $log; $log[] = "[".nowStr()."] ".$s; }

$sum = ['ok'=>0, 'skip'=>0, 'fail'=>0];
$details = [];

// ===== Truy vấn danh sách booking candidate =====
$grace = (int)$GRACE_MINUTES;
$limit = (int)$BATCH_LIMIT;

$sql = "
SELECT b.id, b.nguoi_tao_id, b.can_thu_id, b.booking_time, b.booking_start_time, b.booking_amount
FROM booking b
WHERE b.booking_where = 'online'
  AND b.booking_status = 'Đang chạy'
  AND b.payment_status = 'Chưa thanh toán'
  AND COALESCE(b.booking_amount,0) > 0
    -- ❗️CHẶN HÔM NAY: chỉ lấy hôm qua trở về trước
  AND COALESCE(b.booking_start_time, b.booking_time) < CURDATE()
  AND (
       (b.booking_start_time IS NOT NULL AND b.booking_start_time < (NOW() - INTERVAL :grace1 MINUTE))
    OR (b.booking_start_time IS NULL     AND b.booking_time      < (NOW() - INTERVAL :grace2 MINUTE))
  )
ORDER BY b.booking_start_time IS NULL, b.booking_start_time ASC, b.booking_time ASC
LIMIT $limit
";
$st = $pdo->prepare($sql);
$st->bindValue(':grace1', $grace, PDO::PARAM_INT);
$st->bindValue(':grace2', $grace, PDO::PARAM_INT);
$st->execute();
$rows = $st->fetchAll(PDO::FETCH_ASSOC);

if ($DEBUG) logln("Found " . count($rows) . " candidate(s).");

// ===== Xử lý từng booking =====
foreach ($rows as $r) {
    $bid = (int)$r['id'];
    $uid = (int)($r['can_thu_id'] ?: $r['nguoi_tao_id']); // online thường trùng nhau
    $refund = (int)($r['booking_amount'] ?? 0);

    $info = [
        'booking_id' => $bid,
        'user_id'    => $uid,
        'refund'     => $refund,
        'result'     => '',
        'error'      => ''
    ];

    if ($uid <= 0 || $refund <= 0) {
        $sum['skip']++;
        $info['result'] = 'SKIP (invalid uid/refund)';
        $details[] = $info;
        if ($DEBUG) logln("Skip booking #$bid (uid/refund invalid).");
        continue;
    }

    try {
        $pdo->beginTransaction();

        if ($FORCE_ERR) {
            // ép lỗi sớm để test rollback
            throw new Exception("FORCED ERROR for testing rollback");
        }

        // 1) Khóa booking
        $stBk = $pdo->prepare("SELECT booking_status, payment_status, booking_amount FROM booking WHERE id=? FOR UPDATE");
        $stBk->execute([$bid]);
        $bk = $stBk->fetch(PDO::FETCH_ASSOC);
        if (!$bk) throw new Exception("Booking #$bid not found.");

        // Double-check trong transaction
        if ($bk['booking_status'] !== 'Đang chạy' || $bk['payment_status'] !== 'Chưa thanh toán') {
            $pdo->rollBack();
            $sum['skip']++;
            $info['result'] = 'SKIP (status changed)';
            $details[] = $info;
            if ($DEBUG) logln("Skip booking #$bid (status changed).");
            continue;
        }
        $refund = (int)$bk['booking_amount'];
        if ($refund <= 0) {
            $pdo->rollBack();
            $sum['skip']++;
            $info['result'] = 'SKIP (refund=0)';
            $details[] = $info;
            if ($DEBUG) logln("Skip booking #$bid (refund=0).");
            continue;
        }

        // 2) Khóa user & lấy balance
        $stU = $pdo->prepare("SELECT balance FROM users WHERE id=? FOR UPDATE");
        $stU->execute([$uid]);
        $balanceBefore = $stU->fetchColumn();
        if ($balanceBefore === false) throw new Exception("User #$uid not found.");
        $balanceBefore = (float)$balanceBefore;

        // 3) Cộng tiền hoàn
        $balanceAfter = $balanceBefore + $refund;

        // Dry-run: mô phỏng, không ghi DB
        if ($DRY_RUN) {
            $pdo->rollBack();
            $sum['skip']++;
            $info['result'] = 'DRY-RUN (no DB changes)';
            $details[] = $info;
            if ($DEBUG) logln("DRY: booking #$bid would be canceled & refunded {$refund}đ to user #$uid.");
            continue;
        }

        // 4) Update users.balance
        $pdo->prepare("UPDATE users SET balance=? WHERE id=?")->execute([$balanceAfter, $uid]);

        // 5) Update booking_status => Đã huỷ (giữ nguyên payment_status)
        $stCancel = $pdo->prepare("UPDATE booking SET booking_status='Đã huỷ' WHERE id=? AND booking_status='Đang chạy'");
        $stCancel->execute([$bid]);
        if ($stCancel->rowCount() === 0) {
            $pdo->rollBack();
            $sum['skip']++;
            $info['result'] = 'SKIP (race-condition)';
            $details[] = $info;
            if ($DEBUG) logln("Skip booking #$bid (race-condition).");
            continue;
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
            +$refund,             // change_amount dương (cộng tiền vào ví)
            'booking_refund',     // type theo schema
            $refund,              // amount: số tiền gốc hoàn lại
            $note,
            $refNo,
            $balanceBefore,
            $balanceAfter
        ]);

        $pdo->commit();
        $sum['ok']++;
        $info['result'] = 'OK (canceled & refunded)';
        $details[] = $info;

        if ($DEBUG) logln("OK booking #$bid -> cancel & refund {$refund}đ to user #$uid");
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        $sum['fail']++;
        $info['result'] = 'FAIL';
        $info['error']  = $e->getMessage();
        $details[] = $info;
        $msg = "FAIL booking #$bid: ".$e->getMessage();
        error_log($msg);
        if ($DEBUG) logln($msg);
    }
}

// ===== Render HTML kết quả =====
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Cron Cancel Online Bookings - Manual Preview</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif; margin:24px;}
    .box{border:1px solid #ddd; border-radius:10px; padding:16px; margin-bottom:16px;}
    table{border-collapse:collapse; width:100%;}
    th,td{border:1px solid #eee; padding:8px; text-align:left;}
    th{background:#fafafa;}
    .ok{color:#0a7;}
    .skip{color:#999;}
    .fail{color:#d33;}
    code{background:#f6f8fa; padding:2px 6px; border-radius:6px;}
    a{color:#0a58ca; text-decoration:none;}
    a:hover{text-decoration:underline;}
  </style>
</head>
<body>
  <h2>Preview: Cancel Online Bookings (Manual)</h2>

  <div class="box">
    <strong>Tham số chạy:</strong>
    <div>grace = <code><?= h($GRACE_MINUTES) ?></code> phút · limit = <code><?= h($BATCH_LIMIT) ?></code> · dry = <code><?= $DRY_RUN ? '1':'0' ?></code> · debug = <code><?= $DEBUG ? '1':'0' ?></code> · force = <code><?= $FORCE_ERR ? '1':'0' ?></code></div>
    <div>Thời gian: <code><?= h(nowStr()) ?></code></div>
  </div>

  <div class="box">
    <strong>Kết quả:</strong>
    <ul>
      <li class="ok">OK: <?= (int)$sum['ok'] ?></li>
      <li class="skip">SKIP: <?= (int)$sum['skip'] ?></li>
      <li class="fail">FAIL: <?= (int)$sum['fail'] ?></li>
    </ul>
  </div>

  <div class="box">
    <strong>Chi tiết:</strong>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Booking ID</th>
          <th>User ID</th>
          <th>Refund</th>
          <th>Result</th>
          <th>Error</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($details as $i => $d): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td><?= h($d['booking_id']) ?></td>
          <td><?= h($d['user_id']) ?></td>
          <td><?= number_format((int)$d['refund']) ?> đ</td>
          <td class="<?= strpos($d['result'],'OK')===0 ? 'ok' : (strpos($d['result'],'SKIP')===0 ? 'skip' : 'fail') ?>"><?= h($d['result']) ?></td>
          <td><?= h($d['error']) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($details)): ?>
        <tr><td colspan="6"><em>Không có booking phù hợp.</em></td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <?php if ($DEBUG && !empty($log)): ?>
  <div class="box">
    <strong>Debug Log:</strong>
    <pre><?php foreach ($log as $line) echo h($line)."\n"; ?></pre>
  </div>
  <?php endif; ?>

  <div class="box">
    <strong>Đường dẫn nhanh:</strong>
    <div>
      <a href="?grace=1&limit=50&dry=1&debug=1">Dry-run nhanh (grace=1, limit=50)</a> ·
      <a href="?grace=1&limit=50&debug=1">Chạy thật (grace=1, limit=50)</a> ·
      <a href="?grace=1&limit=50&debug=1&force=1">Ép lỗi (test rollback)</a>
    </div>
  </div>
</body>
</html>
