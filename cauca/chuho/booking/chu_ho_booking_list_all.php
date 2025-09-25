<?php
// chu_ho_booking_list_all.php

require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';
include_once __DIR__ . '/../../../includes/header.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['vai_tro'] !== 'chuho') {
  header('Location: /');
  exit;
}

$chuho_id = (int)$_SESSION['user']['id'];

/* ---------------- Helpers ---------------- */
if (!function_exists('h')) {
  function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}
if (!function_exists('money_vnd')) {
  function money_vnd($n) { return number_format((int)$n, 0, ',', '.'); }
}
$dayStart = fn($d) => $d . ' 00:00:00';
$dayEnd   = fn($d) => $d . ' 23:59:59';

/* ---------------- Input params ---------------- */
$tab         = $_GET['tab']   ?? 'pos';              // 'pos' | 'online'
$range       = $_GET['range'] ?? 'today';            // today|yesterday|7d|30d|60d|custom
$anchorDay   = $_GET['day']   ?? date('Y-m-d');      // mốc cuối cho 7/30/60d
$custom_from = $_GET['from']  ?? null;               // YYYY-mm-dd
$custom_to   = $_GET['to']    ?? null;               // YYYY-mm-dd

$limit  = max(1, (int)($_GET['limit'] ?? 20));
$page   = max(1, (int)($_GET['page']  ?? 1));
$offset = ($page - 1) * $limit;

/* ---------------- Tính khoảng thời gian [$from, $to] ---------------- */
switch ($range) {
  case 'yesterday':
    $d = date('Y-m-d', strtotime('-1 day'));
    $from = $dayStart($d); $to = $dayEnd($d);
    break;
  case '7d':
    $start = date('Y-m-d', strtotime($anchorDay . ' -6 days'));
    $from = $dayStart($start); $to = $dayEnd($anchorDay);
    break;
  case '30d':
    $start = date('Y-m-d', strtotime($anchorDay . ' -29 days'));
    $from = $dayStart($start); $to = $dayEnd($anchorDay);
    break;
  case 'tomorrow':
    $start = date('Y-m-d', strtotime($anchorDay . ' +1 day'));
    $end   = $start;
    $from  = $dayStart($start);
    $to    = $dayEnd($end);
    break;
  case 'plus7':
    $start = date('Y-m-d', strtotime($anchorDay . ' +1 day'));  // ngày mai
    $end   = date('Y-m-d', strtotime($anchorDay . ' +7 days')); // 7 ngày tới (ko tính hôm nay)
    $from  = $dayStart($start);
    $to    = $dayEnd($end);
    break;
  case 'plus15':
    $start = date('Y-m-d', strtotime($anchorDay . ' +1 day'));  // ngày mai
    $end   = date('Y-m-d', strtotime($anchorDay . ' +14 days')); // 14 ngày tới (ko tính hôm nay)
    $from  = $dayStart($start);
    $to    = $dayEnd($end);
    break;
	
  case 'custom':
    if ($custom_from && $custom_to) {
      $from = $dayStart($custom_from);
      $to   = $dayEnd($custom_to);
    } else {
      $d = date('Y-m-d');
      $from = $dayStart($d); $to = $dayEnd($d);
    }
    break;
  case 'today':
  default:
    $from = $dayStart($anchorDay); $to = $dayEnd($anchorDay);
}

/* ---------------- WHERE theo tab (JOIN đúng cum_ho) ---------------- */
$where   = [];
$params  = [];

$where[] = 'c.chu_ho_id = :chuho_id';              // ✅ lọc theo chủ hồ qua cum_ho
$params[':chuho_id'] = $chuho_id;

if ($tab === 'online') {
  // Online = đơn tự đặt; lọc theo NGÀY DỰ KIẾN đi câu
  $where[] = "b.booking_where = 'online'";
  $where[] = "b.can_thu_id IS NOT NULL";
  $where[] = "b.nguoi_tao_id = b.can_thu_id";
  $where[] = "b.booking_start_time BETWEEN :from AND :to";
  $params[':from'] = $from;
  $params[':to']   = $to;
  $orderBy = "b.booking_start_time ASC, b.id ASC";
} else {
  // POS = đơn do chủ hồ tạo; lọc theo NGÀY TẠO
	$where[] = "b.booking_where = 'POS'";
	$where[] = "b.nguoi_tao_id = :chuho_id2";   // 👈 dùng tên khác
	$where[] = "b.booking_time BETWEEN :from AND :to";
	$params[':chuho_id2'] = $chuho_id;          // 👈 bind riêng
	$params[':from'] = $from;
	$params[':to']   = $to;
	$orderBy = "b.booking_time DESC, b.id DESC";

}

$whereSql = implode(' AND ', $where);

/* ---------------- Badge: count theo từng tab ---------------- */
function count_by_tab(PDO $pdo, int $chuho_id, string $tab, string $from, string $to): int {
  if ($tab === 'online') {
    $sql = "SELECT COUNT(*) AS cnt
            FROM booking b
            JOIN ho_cau h ON h.id = b.ho_cau_id
            JOIN cum_ho c ON c.id = h.cum_ho_id
            WHERE c.chu_ho_id = :cid_owner
              AND b.booking_where = 'online'
              AND b.can_thu_id IS NOT NULL
              AND b.nguoi_tao_id = b.can_thu_id
              AND b.booking_start_time BETWEEN :f AND :t";
    $st = $pdo->prepare($sql);
    $st->bindValue(':cid_owner', $chuho_id, PDO::PARAM_INT);
    $st->bindValue(':f', $from);
    $st->bindValue(':t', $to);
  } else {
    // POS
    $sql = "SELECT COUNT(*) AS cnt
            FROM booking b
            JOIN ho_cau h ON h.id = b.ho_cau_id
            JOIN cum_ho c ON c.id = h.cum_ho_id
            WHERE c.chu_ho_id = :cid_owner
              AND b.booking_where = 'POS'
              AND b.nguoi_tao_id = :cid_creator
              AND b.booking_time BETWEEN :f AND :t";
    $st = $pdo->prepare($sql);
    $st->bindValue(':cid_owner',   $chuho_id, PDO::PARAM_INT);
    $st->bindValue(':cid_creator', $chuho_id, PDO::PARAM_INT);
    $st->bindValue(':f', $from);
    $st->bindValue(':t', $to);
  }

  $st->execute();
  return (int)$st->fetchColumn();
}

$cnt_pos = 0;
$cnt_online = 0;
$cnt_pos    = count_by_tab($pdo, $chuho_id, 'POS',    $from, $to);
$cnt_online = count_by_tab($pdo, $chuho_id, 'online', $from, $to);

/* ---------------- DATA chính ---------------- */
$sql = "
  SELECT 
    b.*,
    h.ten_ho          AS ten_ho,
    h.status          AS trang_thai_ho,
    c.ten_cum_ho      AS ten_cum_ho
  FROM booking b
  JOIN ho_cau h  ON h.id = b.ho_cau_id
  JOIN cum_ho c  ON c.id = h.cum_ho_id
  WHERE $whereSql
  ORDER BY $orderBy
  LIMIT :limit OFFSET :offset
";
$st = $pdo->prepare($sql);
foreach ($params as $k => $v) {
  // :chuho_id, :from, :to là string/int tuỳ cột; binding mặc định OK
  $st->bindValue($k, $v);
}
$st->bindValue(':limit',  $limit,  PDO::PARAM_INT);
$st->bindValue(':offset', $offset, PDO::PARAM_INT);
$st->execute();
$rows = $st->fetchAll(PDO::FETCH_ASSOC);

/* ---------------- Helper cho URL ---------------- */
$baseQ = function(array $override = []) use ($tab, $range, $anchorDay, $custom_from, $custom_to, $limit) {
  $q = [
    'tab'   => $tab,
    'range' => $range,
    'day'   => $anchorDay,
    'from'  => $custom_from,
    'to'    => $custom_to,
    'limit' => $limit,
    'page'  => 1
  ];
  return '?' . http_build_query(array_merge($q, $override));
};
$actTab = fn($t) => $tab === $t ? 'active' : '';
$actBtn = fn($r) => ($range === $r) ? 'btn-primary' : 'btn-outline-primary';

?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Đơn hàng tất cả hồ (Chủ hồ)</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Giả sử Bootstrap đã có sẵn trong layout tổng; nếu chưa, thêm link CSS tại đây -->
</head>
<body class="container py-3">

<h4 class="mb-3 mt-3">
  <a href="booking_list.php" class="btn btn-sm btn-success">
    + Tạo vé câu POS tại hồ ngay!!!
  </a>
</h4>


  <!-- Tabs -->
  <ul class="nav nav-tabs mb-3">
    <li class="nav-item">
      <a class="nav-link <?= $actTab('pos') ?>" href="<?= h($baseQ(['tab'=>'pos'])) ?>">
        POS tại hồ <span class="badge bg-danger ms-1"><?= (int)$cnt_pos ?></span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?= $actTab('online') ?>" href="<?= h($baseQ(['tab'=>'online'])) ?>">
        Online <span class="badge bg-success ms-1"><?= (int)$cnt_online ?></span>
      </a>
    </li>
  </ul>

  <!-- Bộ lọc nhanh -->
  <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
    <a class="btn btn-sm <?= h($actBtn('30d')) ?>"       href="<?= h($baseQ(['range'=>'30d'])) ?>">-30 ngày</a>
    <a class="btn btn-sm <?= h($actBtn('7d')) ?>"        href="<?= h($baseQ(['range'=>'7d'])) ?>">-7 ngày</a>
    <a class="btn btn-sm <?= h($actBtn('yesterday')) ?>" href="<?= h($baseQ(['range'=>'yesterday'])) ?>">Hôm qua</a>
	<a class="btn btn-sm <?= h($actBtn('today')) ?>"     href="<?= h($baseQ(['range'=>'today'])) ?>">Hôm nay</a>
	<a class="btn btn-sm <?= h($actBtn('tomorrow')) ?>"  href="<?= h($baseQ(['range' => 'tomorrow'])) ?>"> Ngày mai</a>
	<a class="btn btn-sm <?= h($actBtn('plus7')) ?>"  href="<?= h($baseQ(['range'=>'plus7'])) ?>">+7 ngày</a>
	<a class="btn btn-sm <?= h($actBtn('plus15')) ?>" href="<?= h($baseQ(['range'=>'plus15'])) ?>">+15 ngày</a>

    <!-- Neo ngày cuối cho 7/30/60d -->
    <form class="d-flex align-items-center ms-auto gap-2" method="get">
      <input type="hidden" name="tab" value="<?= h($tab) ?>">
      <input type="hidden" name="range" value="<?= h($range) ?>">
      <div class="input-group input-group-sm">
        <span class="input-group-text">Mốc cuối</span>
        <input type="date" class="form-control" name="day" value="<?= h($anchorDay) ?>">
        <button class="btn btn-outline-secondary">Áp dụng</button>
      </div>
    </form>
  </div>

  <!-- Khoảng ngày tuỳ chọn -->
  <form class="row g-2 align-items-end mb-3" method="get">
    <input type="hidden" name="tab" value="<?= h($tab) ?>">
    <input type="hidden" name="range" value="custom">
    <div class="col-auto">
      <label class="form-label mb-1">Từ ngày</label>
      <input type="date" class="form-control form-control-sm" name="from" value="<?= h($custom_from ?? '') ?>">
    </div>
    <div class="col-auto">
      <label class="form-label mb-1">Đến ngày</label>
      <input type="date" class="form-control form-control-sm" name="to" value="<?= h($custom_to ?? '') ?>">
    </div>
    <div class="col-auto">
      <label class="form-label mb-1 d-block">&nbsp;</label>
      <button class="btn btn-sm btn-success">Lọc</button>
    </div>
    <div class="col-auto ms-auto">
      <label class="form-label mb-1">Số dòng</label>
      <select class="form-select form-select-sm" name="limit" onchange="this.form.submit()">
        <?php foreach ([20,50,100] as $opt): ?>
          <option value="<?= (int)$opt ?>" <?= $opt==$limit?'selected':'' ?>><?= (int)$opt ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </form>

  <!-- Bảng kết quả -->
  <?php if (!$rows): ?>
    <div class="alert alert-info">Không có booking trong khoảng thời gian đã chọn.</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-sm align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Cụm hồ / Hồ</th>
            <th>Khách</th>
            <th>Thời gian</th>
            <th>Trạng thái</th>
            <th class="text-end">Cọc</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= (int)$r['id'] ?></td>
            <td>
              <div class="fw-semibold"><?= h($r['ten_ho']) ?></div>
              <small class="text-muted"><?= h($r['ten_cum_ho'] ?? '—') ?></small>
            </td>
            <td>
              <?= h($r['ten_nguoi_cau'] ?? '—') ?>
              <?php if (!empty($r['nick_name'])): ?>
                <small class="text-muted">(<?= h($r['nick_name']) ?>)</small>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($tab === 'pos'): ?>
                <small class="text-muted">Tạo: <?= h($r['booking_time'] ?? '—') ?></small>
              <?php else: ?>
                <small class="text-muted">Dự kiến: <?= h($r['booking_start_time'] ?? '—') ?></small>
              <?php endif; ?>
            </td>
			<td>
			  <?php
				$status = $r['main_status'] ?? $r['booking_status'] ?? '—';
				$badgeClass = 'bg-secondary'; // mặc định màu cũ

				if ($status === 'Hoàn thành') {
					$badgeClass = 'bg-success'; // màu xanh
				}
				if ($status === 'Đang chạy') {
					$badgeClass = 'bg-warning'; // màu xanh
				}
			  ?>
			  <span class="badge <?= $badgeClass ?>"><?= h($status) ?></span>
			</td>
            <td class="text-end"><?= money_vnd($r['booking_amount'] ?? 0) ?></td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="booking_detail.php?id=<?= (int)$r['id'] ?>">Chi tiết</a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Phân trang cơ bản -->
    <?php
      $hasPrev = $page > 1;
      $hasNext = (count($rows) === $limit); // đơn giản: nếu đủ limit thì cho Next
      $qPrev = $baseQ(['page' => max(1, $page-1)]);
      $qNext = $baseQ(['page' => $page+1]);
    ?>
    <div class="d-flex justify-content-between align-items-center mt-2">
      <a class="btn btn-sm btn-outline-secondary <?= $hasPrev?'':'disabled' ?>" href="<?= $hasPrev ? h($qPrev) : '#' ?>">← Trước</a>
      <span class="small">Trang <?= (int)$page ?></span>
      <a class="btn btn-sm btn-outline-secondary <?= $hasNext?'':'disabled' ?>" href="<?= $hasNext ? h($qNext) : '#' ?>">Sau →</a>
    </div>
  <?php endif; ?>

</body>
</html>
