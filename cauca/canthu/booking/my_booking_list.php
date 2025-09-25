<?php
// cauca/canthu/booking/my_booking_list.php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';
include_once __DIR__ . '/../../../includes/header.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['vai_tro'] !== 'canthu') {
  http_response_code(403); exit('Forbidden');
}

$can_thu_id = (int)$_SESSION['user']['id'];

// ====== cấu hình ======
const PAGE_SIZE = 20;

// ====== lọc thời gian 180 ngày gần nhất ======
$time_to   = date('Y-m-d H:i:s');                          // bây giờ
$time_from = date('Y-m-d H:i:s', strtotime('-180 days'));  // 180 ngày trước

// ====== phân trang ======
$page = isset($_GET['page']) && ctype_digit($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * PAGE_SIZE;

// ====== đếm tổng ======
$sqlCount = "
  SELECT COUNT(*)
  FROM booking b
  WHERE b.can_thu_id = ?
    AND b.booking_time >= DATE_SUB(NOW(), INTERVAL 180 DAY)
    AND b.booking_time <= NOW()
";
$stCount = $pdo->prepare($sqlCount);
$stCount->execute([$can_thu_id]);
$total = (int)$stCount->fetchColumn();


// ====== lấy danh sách ======
$sqlList = "
  SELECT 
    b.*,
    COALESCE(h.ten_ho, '(Hồ đã ẩn/xoá)') AS ten_ho,
    COALESCE(c.ten_cum_ho, '(Cụm đã ẩn/xoá)') AS ten_cum_ho,
    COALESCE(u.full_name, '(Chủ hồ không còn)') AS ten_chu_ho
  FROM booking b
  LEFT JOIN ho_cau h ON h.id = b.ho_cau_id
  LEFT JOIN cum_ho c ON c.id = h.cum_ho_id
  LEFT JOIN users  u ON u.id = c.chu_ho_id
  WHERE b.can_thu_id = ?
    AND b.booking_time >= DATE_SUB(NOW(), INTERVAL 180 DAY)
    AND b.booking_time <= NOW()
  ORDER BY b.booking_time DESC, b.id DESC
  LIMIT ? OFFSET ?
";
$stList = $pdo->prepare($sqlList);
$stList->bindValue(1, $can_thu_id, PDO::PARAM_INT);
$stList->bindValue(2, PAGE_SIZE, PDO::PARAM_INT);
$stList->bindValue(3, $offset,   PDO::PARAM_INT);
$stList->execute();
$rows = $stList->fetchAll(PDO::FETCH_ASSOC);


// ====== helpers ======
function fmtVnd($n) { return number_format((int)$n) . 'đ'; }

function badgePayment($s) {
  // ví dụ: 'Chưa thanh toán', 'Đã thanh toán', 'Hoàn tiền'
  $map = [
    'Đã thanh toán'   => 'bg-success',
    'Chưa thanh toán' => 'bg-secondary',
    'Hoàn tiền'       => 'bg-danger',
  ];
  $cls = $map[$s] ?? 'bg-light text-dark';
  return '<span class="badge '.$cls.'">'.$s.'</span>';
}

function badgeBooking($s) {
  // ví dụ: 'Đang chạy', 'Đã hủy', 'Hoàn tất', ...
  $map = [
    'Đang chạy' => 'bg-secondary',
    'Hoàn thành'  => 'bg-success',
    'Đã hủy'    => 'bg-danger',
  ];
  $cls = $map[$s] ?? 'bg-light text-dark';
  return '<span class="badge '.$cls.'">'.$s.'</span>';
}

// phân trang ui
$total_pages = (int)ceil($total / PAGE_SIZE);
function pagelink($p) {
  return '?page='.(int)$p;
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Booking của tôi (180 ngày)</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="/assets/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-3">
<div class="container">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="mb-0">Booking của tôi</h3>
    <div class="text-muted small">
      Khoảng thời gian: <?= htmlspecialchars(date('d/m/Y', strtotime($time_from))) ?>
      – <?= htmlspecialchars(date('d/m/Y', strtotime($time_to))) ?>
    </div>
  </div>

  <div class="card border-2 shadow-sm">
    <div class="card-header fw-bold">Danh sách booking (<?= (int)$total ?>)</div>
    <div class="card-body p-0">
      <?php if (!$rows): ?>
        <div class="p-3 text-center text-muted">Chưa có booking nào trong 180 ngày.</div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th width="80">#</th>
                <th>Hồ / Cụm</th>
                <th>Thời gian Câu</th>
                <th class="text-end">Cọc/types</th>
				<th>Thành tích</th>
                <th>Thanh toán</th>
                <th>Trạng thái</th>
                <th width="1"></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($rows as $r): ?>
                <tr>
                  <td><?= (int)$r['id'] ?></td>
                  <td>
                    <div class="fw-semibold"><?= htmlspecialchars($r['ten_ho']) ?></div>
                    <div class="text-muted small">Cụm: <?= htmlspecialchars($r['ten_cum_ho']) ?></div>
                  </td>
                  <td>
					<div class="text-semibold"><?= !empty($r['booking_start_time'])
					? htmlspecialchars(date('d/m/Y H:i', strtotime($r['booking_start_time'])))
					: 'Chủ hồ tạo POS' ?></div>
					<div class="text-muted small">Tạo: <?= htmlspecialchars(date('d/m/Y H:i', strtotime($r['booking_time']))) ?></div>  
				  </td>
                  <td> 
				  <div class="text-end"><?= fmtVnd($r['booking_amount']) ?> </div> 
				  <div class="text-end"><?= $r['booking_where'] ?> </div> 
				  </td>
				  <td>
					<div ><?= htmlspecialchars($r['fish_weight']) ?> - Kg</div>
					<?php $ttl = !empty($r['real_tong_thoi_luong']) ? $r['real_tong_thoi_luong'] : 0; ?>
					<div class="<?= $ttl ?>"><?= $ttl ?> phút</div>
				  </td>
                  <td><?= badgePayment($r['payment_status']) ?></td>
                  <td><?= badgeBooking($r['booking_status']) ?></td>
                  <td class="text-nowrap">
                    <!-- chỗ này bạn có thể trỏ tới trang chi tiết nếu có -->
                    <!-- <a class="btn btn-sm btn-outline-primary" href="booking_detail.php?id=<?= (int)$r['id'] ?>">Xem</a> -->
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <?php if ($total_pages > 1): ?>
    <nav class="mt-3">
      <ul class="pagination mb-0">
        <li class="page-item <?= $page<=1?'disabled':'' ?>">
          <a class="page-link" href="<?= $page<=1?'#':pagelink($page-1) ?>">«</a>
        </li>
        <?php
          // hiển thị tối đa 7 trang quanh trang hiện tại
          $start = max(1, $page-3);
          $end   = min($total_pages, $page+3);
          for ($p=$start; $p<=$end; $p++):
        ?>
          <li class="page-item <?= $p===$page?'active':'' ?>">
            <a class="page-link" href="<?= pagelink($p) ?>"><?= $p ?></a>
          </li>
        <?php endfor; ?>
        <li class="page-item <?= $page>=$total_pages?'disabled':'' ?>">
          <a class="page-link" href="<?= $page>=$total_pages?'#':pagelink($page+1) ?>">»</a>
        </li>
      </ul>
    </nav>
  <?php endif; ?>

  <div class="mt-3">
    <a href="/cauca/canthu/booking/booking_list.php" class="btn btn-outline-secondary">Quay lại danh sách hồ</a>
  </div>
</div>
</body>
</html>
