<?php
require __DIR__ . '/../../../connect.php';
require_once '../../../includes/header.php';
date_default_timezone_set('Asia/Ho_Chi_Minh');

/* --- Helper định dạng tiền --- */
if (!function_exists('money_vnd')) {
  function money_vnd($n) { return number_format((int)$n, 0, ',', '.') . ' đ'; }
}

/* --- Input --- */
$giai_id = (int)($_POST['giai_id'] ?? $_GET['giai_id'] ?? 0);
if ($giai_id <= 0) {
  http_response_code(400);
  exit('Thiếu tham số giai_id.');
}

/* --- Thông tin giải: cần creator_id để trừ tiền --- */
$stG = $pdo->prepare("
  SELECT id, ten_giai, creator_id, tien_cuoc, status
  FROM giai_list
  WHERE id = ?
");
$stG->execute([$giai_id]);
$giai = $stG->fetch(PDO::FETCH_ASSOC);
if (!$giai) {
  http_response_code(404);
  exit('Không tìm thấy giải.');
}
$creatorId = (int)$giai['creator_id'];

/* --- Dò cột tiền trong giai_user (để lấy số đã thanh toán) --- */
$preferredCols = ['amount_paid', 'da_thanh_toan', 'so_tien', 'phi_tham_gia'];
$colRows = $pdo->query("
  SELECT COLUMN_NAME
  FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'giai_user'
")->fetchAll(PDO::FETCH_COLUMN);
$existing = array_values(array_intersect($preferredCols, $colRows));

$coalesce = $existing
  ? ('COALESCE(' . implode(', ', array_map(fn($c) => "gu.$c", $existing)) . ', g.tien_cuoc, 0)')
  : 'COALESCE(g.tien_cuoc, 0)';

/* --- Lấy danh sách cần thủ đã thanh toán & số tiền mỗi người --- */
$sql = "
SELECT
  gu.user_id,
  u.full_name,
  u.nickname,
  u.phone,
  SUM($coalesce) AS paid_amount
FROM giai_user gu
JOIN users u  ON u.id = gu.user_id
JOIN giai_list g ON g.id = gu.giai_id
WHERE gu.giai_id = :gid
  AND gu.trang_thai = 'da_thanh_toan'
GROUP BY gu.user_id, u.full_name, u.nickname, u.phone
ORDER BY (u.full_name IS NULL), u.full_name, u.nickname
";
$st = $pdo->prepare($sql);
$st->execute([':gid' => $giai_id]);
$rows = $st->fetchAll(PDO::FETCH_ASSOC);

/* --- Nếu không có ai đã thanh toán thì chỉ chuyển trạng thái giải --- */
if (empty($rows)) {
  $pdo->prepare("UPDATE giai_list SET status = 'huy_giai' WHERE id = ?")->execute([$giai_id]);
  echo "<div class='container py-4'>
          <h3>Huỷ giải: không có cần thủ nào cần hoàn tiền.</h3>
          <p>Đã chuyển trạng thái giải sang <b>huy_giai</b>.</p>
          <a class='btn btn-primary' href='javascript:history.back()'>Quay lại</a>
        </div>";
  exit;
}

/* --- Chính sách hoàn trả cho người chơi --- */
$REFUND_RATE = 1.0; // 100% tiền đã thanh toán; đổi nếu bạn có chính sách khác

/* --- Tính tổng tiền cần trừ ở creator & tổng hoàn cho user --- */
$total_refund = 0;
$refunds = []; // [ [user_id, amount], ... ]
foreach ($rows as $r) {
  $paid   = (int)$r['paid_amount'];
  $refund = (int)round($paid * $REFUND_RATE);
  if ($refund > 0) {
    $refunds[] = ['user_id' => (int)$r['user_id'], 'amount' => $refund];
    $total_refund += $refund;
  }
}
if ($total_refund <= 0) {
  echo "<div class='container py-4'>
          <h3>Huỷ giải</h3>
          <p>Không có số tiền nào cần hoàn trả.</p>
          <a class='btn btn-primary' href='javascript:history.back()'>Quay lại</a>
        </div>";
  exit;
}

/* --- Transaction: trừ tiền creator -> cộng cho users + logs --- */
$pdo->beginTransaction();
try {
  $refNo = 'giai_' . $giai_id;

  /* 1) Creator: khóa số dư, trừ tiền, ghi log */
  $stBal = $pdo->prepare("SELECT balance FROM users WHERE id = ? FOR UPDATE");
  $stBal->execute([$creatorId]);
  $creatorBefore = (int)$stBal->fetchColumn();
  if ($creatorBefore < $total_refund) {
    throw new RuntimeException("Số dư người tạo giải không đủ (cần ".money_vnd($total_refund).", có ".money_vnd($creatorBefore).").");
  }
  $creatorAfter = $creatorBefore - $total_refund;

  $pdo->prepare("UPDATE users SET balance = balance - ? WHERE id = ?")
      ->execute([$total_refund, $creatorId]);


  // 2) Log cho creator (sent)
  // Log: creator chi tiền refund -> type = giai_pay
  $logStmt = $pdo->prepare("
    INSERT INTO user_balance_logs
      (user_id, change_amount, type, amount, note, created_at, ref_no, balance_before, balance_after)
    VALUES
      (?,       ?,             ?,    ?,      ?,    NOW(),     ?,      ?,              ?)
  ");
  $logStmt->execute([
    $creatorId,
    -$total_refund,                // change_amount âm khi trừ tiền
    'giai_pay',                    // loại giao dịch
    $total_refund,                 // số tiền tuyệt đối
    "Huỷ giải #$giai_id: chi refund cho ".count($refunds)." cần thủ",
    $refNo,
    $creatorBefore,
    $creatorAfter
  ]);

  /* 3) Từng cần thủ: khóa số dư, cộng tiền, ghi log */
  $stUserBal = $pdo->prepare("SELECT balance FROM users WHERE id = ? FOR UPDATE");
  $stUserUpd = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
  $stUserLog = $pdo->prepare("
    INSERT INTO user_balance_logs
      (user_id, change_amount, type, amount, note, created_at, ref_no, balance_before, balance_after)
    VALUES
      (?,       ?,             ?,    ?,      ?,    NOW(),     ?,      ?,              ?)
  ");

  $userIds = []; // để update giai_user theo danh sách hoàn tiền

  foreach ($refunds as $rf) {
    $uid = (int)$rf['user_id'];
    $amt = (int)$rf['amount'];
    if ($uid <= 0 || $amt <= 0) { continue; }

    // 2) Khóa số dư & lấy số dư trước
    $stUserBal->execute([$uid]);
    $before = (int)$stUserBal->fetchColumn();
    // (tuỳ chọn) nếu user không tồn tại
    if ($stUserBal->rowCount() === 0 && $before === false) { continue; }

    // 3) Cộng tiền
    $after  = $before + $amt;
    $stUserUpd->execute([$amt, $uid]);

    // 4) Ghi log từng người
    // Tạo ref_no riêng cho mỗi giao dịch (ví dụ GRF = Giai Refund)
    $refNo = 'GRF' . $giai_id . '-' . $uid . '-' . date('YmdHis');
	

	// Thêm số dư sau vào note
	$note = "Huỷ giải #$giai_id: hoàn trả phí giải. Số dư sau: " . money_vnd($after) . "";

    $stUserLog->execute([
      $uid,
      +$amt,                 // change_amount dương khi cộng tiền
      'giai_refund',         // nhớ khớp enum/cột trong DB của bạn
      $amt,
      $note,
      $refNo,
      $before,
      $after,
    ]);

    $userIds[] = $uid;
  }
	
	// 5) Cập nhật trạng thái giai_user: 'da_thanh_toan' -> 'Đã Hoàn Tiền'
	$pdo->prepare("
	  UPDATE giai_user
	  SET trang_thai = 'Đã hoàn tiền'
	  WHERE giai_id = ?
		AND trang_thai = 'da_thanh_toan'
	")->execute([$giai_id]);

  // 6) Cập nhật trạng thái giải
  $pdo->prepare("UPDATE giai_list SET status = 'huy_giai' WHERE id = ?")->execute([$giai_id]);

  $pdo->commit();

} catch (Throwable $e) {
  $pdo->rollBack();
  http_response_code(500);
  echo "<div class='container py-4'>
          <h3>Lỗi khi huỷ giải</h3>
          <pre style='white-space:pre-wrap'>".$e->getMessage()."</pre>
          <a class='btn btn-outline-secondary' href='javascript:history.back()'>Quay lại</a>
        </div>";
  exit;
}

/* --- Hiển thị kết quả --- */
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Huỷ giải - Bước 2 (hoàn trả)</title>
  <link href="/assets/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
  <h3 class="mb-3">✅ Đã huỷ giải và hoàn trả cho cần thủ</h3>
  <div class="mb-3">
    <div><strong>Giải:</strong> <?= htmlspecialchars($giai['ten_giai'] ?? ('#'.$giai_id)) ?></div>
    <div><strong>Người tạo giải (ID):</strong> <?= (int)$creatorId ?></div>
    <div><strong>Tổng đã hoàn:</strong> <span class="fw-bold"><?= money_vnd($total_refund) ?></span></div>
  </div>

  <div class="card shadow-sm">
    <div class="card-header fw-bold">Chi tiết hoàn trả</div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-sm table-striped mb-0 align-middle">
          <thead class="table-light">
          <tr>
            <th style="width:56px" class="text-center">#</th>
            <th>Cần thủ</th>
            <th>Điện thoại</th>
            <th class="text-end">Hoàn trả</th>
          </tr>
          </thead>
          <tbody>
          <?php foreach ($rows as $i => $r): ?>
            <?php
              $paid   = (int)$r['paid_amount'];
              $refund = (int)round($paid * $REFUND_RATE);
              if ($refund <= 0) continue;
            ?>
            <tr>
              <td class="text-center"><?= $i+1 ?></td>
              <td>
                <div class="fw-semibold">
                  <?= htmlspecialchars($r['full_name'] ?: ($r['nickname'] ?: '—')) ?>
                  <?php if (!empty($r['nickname'])): ?>
                    <small class="text-muted">(<?= htmlspecialchars($r['nickname']) ?>)</small>
                  <?php endif; ?>
                </div>
              </td>
              <td><?= htmlspecialchars($r['phone'] ?? '—') ?></td>
              <td class="text-end"><?= money_vnd($refund) ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
          <tfoot class="table-light">
          <tr>
            <th colspan="3" class="text-end">Tổng hoàn trả:</th>
            <th class="text-end"><?= money_vnd($total_refund) ?></th>
          </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>

  <div class="mt-3">
    <a href="javascript:history.back()" class="btn btn-secondary">Quay lại</a>
  </div>
</body>
</html>
