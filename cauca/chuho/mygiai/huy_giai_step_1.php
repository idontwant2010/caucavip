<?php
require __DIR__ . '/../../../connect.php';
require_once '../../../includes/header.php';
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Helper định dạng tiền (nếu bạn đã có money_vnd() thì bỏ hàm này)
if (!function_exists('money_vnd')) {
  function money_vnd($n) {
    $n = (int)$n;
    return number_format($n, 0, ',', '.') . ' đ';
  }
}

$giai_id = (int)($_GET['giai_id'] ?? $_POST['giai_id'] ?? 0);
if ($giai_id <= 0) {
  http_response_code(400);
  echo "Thiếu tham số giai_id.";
  exit;
}

// Lấy thông tin giải (tiện hiển thị tiêu đề)
$stG = $pdo->prepare("
  SELECT
    id,
    ten_giai,
    ngay_to_chuc,   -- DATE
    gio_bat_dau,    -- TIME
    tien_cuoc,
    phi_giai,
    phi_ho,
    status,
    chuho_id        -- nếu cần dùng tiếp bước sau
  FROM giai_list
  WHERE id = ?
");
$stG->execute([$giai_id]);
$giai = $stG->fetch(PDO::FETCH_ASSOC);
if (!$giai) {
  http_response_code(404);
  echo "Không tìm thấy giải.";
  exit;
}

/*
 * Danh sách cần thủ đã thanh toán:
 * - Ưu tiên lấy số tiền đã thanh toán từ cột cụ thể (tùy DB của bạn: amount_paid / da_thanh_toan / so_tien / phi_tham_gia)
 * - Nếu không có, fallback về giai_list.tien_cuoc (mỗi người = tiền cược mặc định)
 */
// 1) Tự dò cột tiền đang có trong giai_user
$preferredCols = ['amount_paid', 'da_thanh_toan', 'so_tien', 'phi_tham_gia'];
$colRows = $pdo->query("
  SELECT COLUMN_NAME
  FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'giai_user'
")->fetchAll(PDO::FETCH_COLUMN);

$existing = array_values(array_intersect($preferredCols, $colRows));

// COALESCE các cột tìm được, fallback g.tien_cuoc, 0
if ($existing) {
  $coalesce = 'COALESCE(' . implode(', ', array_map(fn($c) => "gu.$c", $existing)) . ', g.tien_cuoc, 0)';
} else {
  // Không có cột tiền nào trong giai_user -> lấy mặc định tiền cược
  $coalesce = 'COALESCE(g.tien_cuoc, 0)';
}

// 2) Truy vấn danh sách đã thanh toán + tổng tiền mỗi user
$sql = "
SELECT
  gu.user_id,
  u.full_name,
  u.nickname,
  u.phone,
  'da_thanh_toan' AS trang_thai,
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


// Tỷ lệ hoàn trả cho cần thủ (mặc định 100%)
// Nếu bạn có chính sách khác (ví dụ 50%), đổi thành 0.5
$REFUND_RATE = 1.0;

$total_paid   = 0;
$total_refund = 0;
foreach ($rows as $r) {
  $paid = (int)$r['paid_amount'];
  $total_paid   += $paid;
  $total_refund += (int)round($paid * $REFUND_RATE);
}

// HTML hiển thị
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Huỷ giải - Bước 1</title>
  <link href="/assets/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
  <h3 class="mb-3">❌ Huỷ giải — Bước 1: Xác nhận hoàn trả cho cần thủ</h3>

  <div class="mb-3">
    <div><strong>Giải:</strong> <?= htmlspecialchars($giai['ten_giai'] ?? '—') ?></div>
    <div><strong>Thời gian:</strong>
      <?= !empty($giai['ngay_to_chuc']) ? htmlspecialchars(date('d/m/Y', strtotime($giai['ngay_to_chuc']))) : '—' ?>
      <?= !empty($giai['gio_bat_dau']) ? (' · ' . htmlspecialchars($giai['gio_bat_dau'])) : '' ?>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-header fw-bold">Danh sách cần thủ đã thanh toán</div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-sm table-striped mb-0 align-middle">
          <thead class="table-light">
          <tr>
            <th class="text-center" style="width:56px">#</th>
            <th>Cần thủ</th>
            <th>Điện thoại</th>
            <th class="text-end">Đã thanh toán</th>
            <th class="text-end">Hoàn trả (dự kiến)</th>
          </tr>
          </thead>
          <tbody>
          <?php if (empty($rows)): ?>
            <tr>
              <td colspan="5" class="text-center text-muted py-4">Chưa có cần thủ nào đã thanh toán.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($rows as $i => $r): ?>
              <?php
                $paid = (int)$r['paid_amount'];
                $refund = (int)round($paid * $REFUND_RATE);
              ?>
              <tr>
                <td class="text-center"><?= $i + 1 ?></td>
                <td>
                  <div class="fw-semibold">
                    <?= htmlspecialchars($r['full_name'] ?: ($r['nickname'] ?: '—')) ?>
                    <?php if (!empty($r['nickname'])): ?>
                      <small class="text-muted">(<?= htmlspecialchars($r['nickname']) ?>)</small>
                    <?php endif; ?>
                  </div>
                </td>
                <td><?= htmlspecialchars($r['phone'] ?? '—') ?></td>
                <td class="text-end"><?= money_vnd($paid) ?></td>
                <td class="text-end"><?= money_vnd($refund) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
          </tbody>
          <tfoot class="table-light">
          <tr>
            <th colspan="3" class="text-end">Tổng đã thanh toán:</th>
            <th class="text-end"><?= money_vnd($total_paid) ?></th>
            <th class="text-end"><?= money_vnd($total_refund) ?></th>
          </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>

  <form action="huy_giai_step_2.php" method="post" class="mt-3">
    <input type="hidden" name="giai_id" value="<?= (int)$giai_id ?>">
    <input type="hidden" name="total_refund" value="<?= (int)$total_refund ?>">
    <button type="submit" class="btn btn-danger"
            onclick="return confirm('Xác nhận chuyển qua bước 2 để tiến hành hoàn trả?')">
      Tiếp tục → Bước 2 (xử lý hoàn trả)
    </button>
    <a href="javascript:history.back()" class="btn btn-outline-secondary ms-2">Quay lại</a>
  </form>
</body>
</html>
