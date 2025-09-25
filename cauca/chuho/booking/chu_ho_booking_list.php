<?php
// chu_ho_booking_list.php ==> lọc theo ID hồ
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';
include __DIR__ . '/../../../includes/header.php';

if ($_SESSION['user']['vai_tro'] !== 'chuho') {
    header("Location: /");
    exit;
}

$chuho_id = (int)$_SESSION['user']['id'];
$ho_id    = isset($_GET['ho_id']) ? (int)$_GET['ho_id'] : 0;
if ($ho_id <= 0) {
    die("Thiếu tham số ho_id");
}

// 1) Xác thực hồ thuộc về chủ hồ hiện tại
$sqlCheck = "
    SELECT h.id, h.ten_ho, h.status, c.ten_cum_ho
    FROM ho_cau h
    JOIN cum_ho c ON c.id = h.cum_ho_id
    WHERE h.id = :ho_id AND c.chu_ho_id = :chuho_id
";
$st = $pdo->prepare($sqlCheck);
$st->execute([':ho_id' => $ho_id, ':chuho_id' => $chuho_id]);
$ho = $st->fetch();
if (!$ho) {
    die("Bạn không có quyền xem hồ này.");
}

// 2) Bộ lọc nhẹ (tùy chọn): status, ngày tạo
$booking_status = $_GET['booking_status'] ?? ''; // "" | "Chờ chuyển" | "Đã chuyển"
$payment_status    = $_GET['payment_status'] ?? '';    // "" | "Hoàn thành" | ...
$date_from      = $_GET['date_from'] ?? '';      // YYYY-MM-DD
$date_to        = $_GET['date_to']   ?? '';      // YYYY-MM-DD

$where = ["b.ho_cau_id = :ho_id"];
$params = [':ho_id' => $ho_id];

if ($booking_status !== '') {
    $where[] = "b.booking_status = :booking_status";
    $params[':booking_status'] = $booking_status;
}
if ($payment_status !== '') {
    $where[] = "b.payment_status = :payment_status";
    $params[':payment_status'] = $payment_status;
}
if ($date_from !== '') {
    $where[] = "DATE(b.booking_time) >= :date_from";
    $params[':date_from'] = $date_from;
}
if ($date_to !== '') {
    $where[] = "DATE(b.booking_time) <= :date_to";
    $params[':date_to'] = $date_to;
}

// 3) Lấy danh sách booking theo hồ
$sql = "
SELECT
    b.id,
    b.booking_time,
    b.booking_status,
    b.payment_status,
    b.payment_method,
    b.nguoi_tao_id,
    b.can_thu_id,
    b.real_start_time, b.real_end_time, 
    b.booking_amount,
    b.real_start_time, b.real_end_time,
    b.real_tong_thoi_luong, b.real_so_suat, b.real_gio_them,
    COALESCE(u_creator.full_name, u_creator.nickname) AS creator_name,
    COALESCE(u_canthu.full_name, u_canthu.nickname)   AS canthu_name
FROM booking b
LEFT JOIN users u_creator ON u_creator.id = b.nguoi_tao_id
LEFT JOIN users u_canthu  ON u_canthu.id  = b.can_thu_id
WHERE " . implode(' AND ', $where) . "
ORDER BY b.booking_time DESC
LIMIT 300
";
$stm = $pdo->prepare($sql);
$stm->execute($params);
$rows = $stm->fetchAll();

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function money_vnd($n){ return number_format((int)$n, 0, ',', '.') . ' đ'; }

// 4) Tính loại booking POS/Online theo từng dòng
function loai_booking($row, $chuho_id){
    if ((int)$row['nguoi_tao_id'] === (int)$chuho_id) return 'POS';
    if (!empty($row['can_thu_id']) && (int)$row['nguoi_tao_id'] === (int)$row['can_thu_id']) return 'Online';
    return 'Khác';
}

?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>Tạo vé booking (POS)</title>
</head>
<body class="bg-light">
<div class="container container-narrow py-4">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <div>
      <h4 class="mb-1">Danh sách booking theo hồ</h4>
      <div class="text-muted text-small">
        Cụm: <strong><?= h($ho['ten_cum_ho']) ?></strong> · 
        Hồ: <strong><?= h($ho['ten_ho']) ?></strong> (trạng thái: <em><?= h($ho['status']) ?></em>)
      </div>
    </div>
    <a class="btn btn-outline-secondary" href="/cauca/chuho/booking/booking_list.php">← Quay lại</a>
  </div>

  <!-- Bộ lọc -->
  <form method="get" class="card mb-3">
    <input type="hidden" name="ho_id" value="<?= (int)$ho_id ?>">
    <div class="card-body row g-3">
      <div class="col-md-3">
        <label class="form-label">Booking status</label>
        <select name="booking_status" class="form-select">
          <option value="">— Tất cả —</option>
          <option value="Đang chạy" <?= $booking_status==='Đang chạy'?'selected':''; ?>>Đang chạy</option>
		  <option value="Hoàn thành" <?= $booking_status==='Hoàn thành'?'selected':''; ?>>Hoàn thành</option>
          <option value="Đã huỷ"  <?= $booking_status==='Đã huỷ'?'selected':''; ?>>Đã huỷ</option>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Payment status</label>
        <select name="payment_status" class="form-select">
          <option value="">— Tất cả —</option>
          <option value="Chưa thanh toán" <?= $payment_status==='Chưa thanh toán'?'selected':''; ?>>Chưa thanh toán</option>
          <option value="Đã thanh toán"   <?= $payment_status==='Đã thanh toán'?'selected':''; ?>>Đã thanh toán</option>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Từ ngày</label>
        <input type="date" name="date_from" class="form-control" value="<?= h($date_from) ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label">Đến ngày</label>
        <input type="date" name="date_to" class="form-control" value="<?= h($date_to) ?>">
      </div>
    </div>
    <div class="card-footer d-flex gap-2">
      <button class="btn btn-primary">Lọc</button>
      <a class="btn btn-outline-secondary" href="?ho_id=<?= (int)$ho_id ?>">Xóa lọc</a>
    </div>
  </form>

  <div class="card">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Loại</th>
              <th>Thời gian tạo</th>
              <th>Cần thủ</th>
              <th>Thòi gian</th>
              <th>Tiền booking</th>
              <th>Trạng thái</th>
              <th>Thanh toán</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          <?php if (empty($rows)): ?>
            <tr><td colspan="12" class="text-center py-4 text-muted">Chưa có booking.</td></tr>
          <?php else: foreach ($rows as $i => $r):
              $loai = loai_booking($r, $chuho_id);
              $badgeClass = $loai === 'POS' ? 'pos' : ($loai === 'Online' ? 'online' : 'khac');
          ?>
            <tr>
              <td><?= $i+1 ?></td>
				<td><span class="badge <?= $badgeClass ?> text-dark"><?= h($loai) ?></span></td>
              <td>
                <div><?= h($r['booking_time']) ?></div>
                <?php if ($r['real_start_time'] || $r['real_end_time']): ?>
                <?php endif; ?>
              </td>
              <td><?= h($r['canthu_name'] ?: '—') ?> <div class="text-muted text-small"><?= $r['can_thu_id'] ? '#'.(int)$r['can_thu_id'] : '' ?></div></td>
              <td>
                <?php if ($r['real_tong_thoi_luong']): ?>
                  <div class="text-muted text-small"><?= (int)$r['real_tong_thoi_luong'] ?> phút</div>
                <?php endif; ?>
              </td>
              <td class="text-end"><?= money_vnd($r['booking_amount']) ?></td>
              <td><?= h($r['booking_status'] ?: '—') ?></td>
              <td><?= h($r['payment_method'] ?: '—') ?></td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-primary"
                   href="/cauca/chuho/booking/booking_detail.php?id=<?= (int)$r['id'] ?>">
                  Chi tiết
                </a>
              </td>
            </tr>
          <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer d-flex justify-content-between">
      <div class="text-muted">Tổng: <strong><?= count($rows) ?></strong> booking</div>
    </div>
  </div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php include __DIR__ . '/../../../includes/footer.php'; ?>