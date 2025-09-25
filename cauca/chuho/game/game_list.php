<?php
// File: cauca/chuho/game/game_list.php  (TỔNG, ưu tiên chạy chắc cú)
require __DIR__ . '/../../../connect.php';
require __DIR__ . '/../../../check_login.php';
require_once '../../../includes/header.php';

if (session_status() === PHP_SESSION_NONE) session_start();
if (($_SESSION['user']['vai_tro'] ?? '') !== 'chuho') { header('Location: /'); exit; }

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function money_vnd($n){ return number_format((int)$n, 0, ',', '.') . ' đ'; }

$uid = (int)($_SESSION['user']['id'] ?? 0);

// ====== Cấu hình lọc ngày ======
$useDate = empty($_GET['all']); // mặc định lọc theo ngày; ?all=1 để bỏ lọc
$tz      = new DateTimeZone('Asia/Ho_Chi_Minh');
$today   = new DateTime('today', $tz);

// Tạo mốc dạng chuỗi YYYY-MM-DD để so sánh an toàn
$Ymd = fn(DateTime $d) => $d->format('Y-m-d');

$fromAll   = (clone $today)->modify('-30 day');
$toAll     = (clone $today)->modify('+30 day');
$fromAllS  = $Ymd($fromAll);
$toAllS    = $Ymd($toAll);

// Mốc bucket (không chồng lắp)
$prev30_from = (clone $today)->modify('-30 day'); $prev30_to = (clone $today)->modify('-8 day');
$prev7_from  = (clone $today)->modify('-7 day');  $prev7_to  = (clone $today)->modify('-2 day');
$yesterday   = (clone $today)->modify('-1 day');
$tomorrow    = (clone $today)->modify('+1 day');
$next7_from  = (clone $today)->modify('+2 day');  $next7_to  = (clone $today)->modify('+7 day');
$next30_from = (clone $today)->modify('+8 day');  $next30_to = (clone $today)->modify('+30 day');

// ====== Tìm kiếm nhanh ======
$q = trim($_GET['q'] ?? '');
$kwClause = '';
$paramsExtra = [];
if ($q !== '') {
  $kwClause = " AND (g.ten_game LIKE :kw OR h.ten_ho LIKE :kw OR c.ten_cum_ho LIKE :kw) ";
  $paramsExtra['kw'] = "%$q%";
}

// ====== SQL: chỉ theo CREATOR_ID; LEFT JOIN an toàn ======
$sql = "
  SELECT
    g.id, g.ten_game, g.ngay_to_chuc, g.gio_bat_dau,
    g.thoi_luong_phut_hiep, g.tien_cuoc, g.phi_game, g.status, g.so_luong_can_thu,
    h.ten_ho, h.so_cho_ngoi, c.ten_cum_ho
  FROM game_list g
  LEFT JOIN ho_cau h ON h.id = g.ho_cau_id
  LEFT JOIN cum_ho c ON c.id = h.cum_ho_id
  WHERE g.creator_id = :uid
";
if ($useDate) { $sql .= " AND g.ngay_to_chuc BETWEEN :fromAll AND :toAll "; }
$sql .= $kwClause . " ORDER BY g.ngay_to_chuc ASC, g.gio_bat_dau ASC";

$st = $pdo->prepare($sql);
$params = array_merge(['uid'=>$uid], $paramsExtra);
if ($useDate) { $params['fromAll'] = $fromAllS; $params['toAll'] = $toAllS; }
$st->execute($params);
$rows = $st->fetchAll(PDO::FETCH_ASSOC);

// ====== Bucket theo chuỗi YYYY-MM-DD để tránh lệch TZ ======
$grouped = ['prev30'=>[], 'prev7'=>[], 'yesterday'=>[], 'today'=>[], 'tomorrow'=>[], 'next7'=>[], 'next30'=>[]];

foreach ($rows as $r) {
  $dStr = (string)$r['ngay_to_chuc'];
  if ($dStr === '') continue;

  if ($dStr >= $Ymd($prev30_from) && $dStr <= $Ymd($prev30_to))      $grouped['prev30'][]   = $r;
  elseif ($dStr >= $Ymd($prev7_from)  && $dStr <= $Ymd($prev7_to))   $grouped['prev7'][]    = $r;
  elseif ($dStr === $Ymd($yesterday))                                $grouped['yesterday'][]= $r;
  elseif ($dStr === $Ymd($today))                                    $grouped['today'][]    = $r;
  elseif ($dStr === $Ymd($tomorrow))                                 $grouped['tomorrow'][] = $r;
  elseif ($dStr >= $Ymd($next7_from)  && $dStr <= $Ymd($next7_to))   $grouped['next7'][]    = $r;
  elseif ($dStr >= $Ymd($next30_from) && $dStr <= $Ymd($next30_to))  $grouped['next30'][]   = $r;
}

$count = array_map('count', $grouped);
$defaultRange = 'today';
foreach (['today','tomorrow','next7','next30','yesterday','prev7','prev30'] as $k) {
  if ($count[$k] > 0) { $defaultRange = $k; break; }
}

// Map trạng thái (tuỳ enum của bạn)
$statusMap = [
  'dang_cho_xac_nhan'   => ['Đang chờ xác nhận', 'bg-secondary'],
  'dang_mo_dang_ky'     => ['Đang mở đăng ký', 'bg-info'],
  'chot_xong_danh_sach' => ['Đã chốt DS', 'bg-primary'],
  'dang_dau_hiep_1'     => ['Đang đấu hiệp 1', 'bg-success'],
  'so_ket_giai'         => ['Sơ kết', 'bg-warning'],
  'hoan_tat_giai'       => ['Hoàn tất', 'bg-dark'],
  'huy_giai'            => ['Hủy', 'bg-danger'],
  'chuyen_chu_ho_duyet' => ['Chuyển duyệt', 'bg-warning'],
];

function render_card($g, $statusMap, $tz) {
  $dateStr = $g['ngay_to_chuc'] ? DateTime::createFromFormat('Y-m-d', $g['ngay_to_chuc'], $tz)?->format('d/m/Y') : '—';
  $timeStr = $g['gio_bat_dau']  ? DateTime::createFromFormat('H:i:s', $g['gio_bat_dau'],  $tz)?->format('H:i')   : '—';
  $stKey   = $g['status'] ?? '';
  $stLabel = $statusMap[$stKey][0] ?? ($stKey ?: '—');
  $stClass = $statusMap[$stKey][1] ?? 'bg-light text-dark';
  $tenHo   = $g['ten_ho']     ?? '—';
  $tenCum  = $g['ten_cum_ho'] ?? '—'; ?>
  <div class="col-12 col-md-6 col-xl-4 game-card" data-range="">
    <div class="card h-100 border-0 shadow-sm card-hover">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-1">
          <div class="fw-semibold"><?= h($g['ten_game']) ?></div>
          <span class="badge <?= h($stClass) ?>"><?= h($stLabel) ?></span>
        </div>
        <div class="text-muted small mb-1">
          Hồ: <span class="fw-semibold"><?= h($tenHo) ?></span> ·
          Cụm: <span class="fw-semibold"><?= h($tenCum) ?></span>
        </div>
        <div class="text-muted small mb-2">
          Ngày: <span class="fw-semibold"><?= h($dateStr) ?></span> ·
          Bắt đầu: <span class="fw-semibold"><?= h($timeStr) ?></span> ·
          Hiệp: <span class="fw-semibold"><?= (int)($g['thoi_luong_phut_hiep'] ?? 0) ?>’</span>
        </div>
        <div class="d-flex justify-content-between small">
          <div>Tiền cược</div>
          <div class="fw-semibold"><?= money_vnd($g['tien_cuoc'] ?? 0) ?></div>
        </div>
        <div class="d-flex justify-content-between small">
          <div>Phí game/người</div>
          <div class="fw-semibold"><?= money_vnd($g['phi_game'] ?? 0) ?></div>
        </div>
        <div class="d-flex justify-content-between small">
          <div>Số người dự kiến</div>
          <div class="fw-semibold"><?= (int)($g['so_luong_can_thu'] ?? 0) ?></div>
        </div>
      </div>
      <div class="card-footer bg-white border-0 pt-0 pb-3">
        <div class="d-grid gap-2">
          <a class="btn btn-outline-primary" href="/cauca/chuho/game/game_user_add.php?game_id=<?= (int)$g['id'] ?>">Thêm người bằng SĐT</a>
          <a class="btn btn-primary" href="/cauca/chuho/game/game_manage.php?game_id=<?= (int)$g['id'] ?>">Quản lý game</a>
        </div>
      </div>
    </div>
  </div>
<?php } ?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Tất cả game của tôi<?= $useDate ? ' (±30 ngày)' : ' (mọi thời điểm)' ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="/assets/bootstrap.min.css" rel="stylesheet">
  <style>
    .card-hover:hover { transform: translateY(-2px); box-shadow: 0 0.5rem 1rem rgba(0,0,0,.12) }
    .filter-row { white-space: nowrap; overflow-x: auto; }
    .filter-row .nav-link { border-radius: 999px; }
  </style>
</head>
<body class="bg-light">
<div class="container py-4">

  <div class="d-flex align-items-center mb-2">
    <h4 class="mb-0">Tất cả game của tôi <?= $useDate ? '<span class="text-muted small">(±30 ngày)</span>' : '<span class="text-muted small">(mọi thời điểm)</span>' ?></h4>
    <div class="ms-auto d-flex gap-2">
      <?php if ($useDate): ?>
        <a class="btn btn-sm btn-outline-secondary" href="?all=1<?= $q!=='' ? '&q='.urlencode($q) : '' ?>">Bỏ lọc ngày (xem tất cả)</a>
      <?php else: ?>
        <a class="btn btn-sm btn-outline-secondary" href="?<?= $q!=='' ? 'q='.urlencode($q).'&' : '' ?>">Bật lọc ±30 ngày</a>
      <?php endif; ?>
      <a class="btn btn-sm btn-outline-primary" href="/cauca/chuho/game/game_list_ho.php">+ Tạo game tại hồ</a>
    </div>
  </div>

  <form class="mb-3" method="get" style="max-width:520px;">
    <div class="input-group">
      <input name="q" value="<?= h($q) ?>" class="form-control" placeholder="Tìm theo tên game, tên hồ, tên cụm…">
      <?php if (!$useDate): ?><input type="hidden" name="all" value="1"><?php endif; ?>
      <button class="btn btn-outline-secondary">Tìm</button>
    </div>
  </form>

  <?php $sum = array_sum($count); ?>

  <!-- Thanh lọc 1 hàng ngang -->
  <ul class="nav nav-pills gap-2 filter-row mb-3">
    <li class="nav-item"><a class="nav-link" data-range-pill="prev30" href="javascript:void(0)">30 ngày trước <span class="badge bg-light text-dark"><?= (int)$count['prev30'] ?></span></a></li>
    <li class="nav-item"><a class="nav-link" data-range-pill="prev7"  href="javascript:void(0)">7 ngày trước  <span class="badge bg-light text-dark"><?= (int)$count['prev7'] ?></span></a></li>
    <li class="nav-item"><a class="nav-link" data-range-pill="yesterday" href="javascript:void(0)">Hôm qua <span class="badge bg-light text-dark"><?= (int)$count['yesterday'] ?></span></a></li>
    <li class="nav-item"><a class="nav-link" data-range-pill="today" href="javascript:void(0)">Hôm nay <span class="badge bg-light text-dark"><?= (int)$count['today'] ?></span></a></li>
    <li class="nav-item"><a class="nav-link" data-range-pill="tomorrow" href="javascript:void(0)">Ngày mai <span class="badge bg-light text-dark"><?= (int)$count['tomorrow'] ?></span></a></li>
    <li class="nav-item"><a class="nav-link" data-range-pill="next7" href="javascript:void(0)">7 ngày tới <span class="badge bg-light text-dark"><?= (int)$count['next7'] ?></span></a></li>
    <li class="nav-item"><a class="nav-link" data-range-pill="next30" href="javascript:void(0)">30 ngày tới <span class="badge bg-light text-dark"><?= (int)$count['next30'] ?></span></a></li>
  </ul>

  <!-- Lưới thẻ game -->
  <div class="row g-3" id="gridGames">
    <?php
      $renderBucket = function($bucketKey, $items) use ($statusMap, $tz) {
        foreach ($items as $g) {
          ob_start(); render_card($g, $statusMap, $tz); $html = ob_get_clean();
          $html = preg_replace('#class="col-12 col-md-6 col-xl-4 game-card"#',
                               'class="col-12 col-md-6 col-xl-4 game-card" data-range="'.$bucketKey.'"',
                               $html, 1);
          echo $html;
        }
      };
      foreach (['prev30','prev7','yesterday','today','tomorrow','next7','next30'] as $bk) $renderBucket($bk, $grouped[$bk]);
    ?>
  </div>

  <?php if ($sum === 0): ?>
    <div class="alert alert-info mt-3">Không có game nào cho bộ lọc hiện tại.</div>
  <?php endif; ?>

</div>

<script src="/assets/bootstrap.bundle.min.js"></script>
<script>
// JS filter
const pills = document.querySelectorAll('[data-range-pill]');
const cards = document.querySelectorAll('.game-card');
const counts = {
  prev30: <?= (int)$count['prev30'] ?>, prev7: <?= (int)$count['prev7'] ?>,
  yesterday: <?= (int)$count['yesterday'] ?>, today: <?= (int)$count['today'] ?>,
  tomorrow: <?= (int)$count['tomorrow'] ?>, next7: <?= (int)$count['next7'] ?>,
  next30: <?= (int)$count['next30'] ?>
};
let current = '<?= $defaultRange ?>';
function applyFilter(rangeKey){
  current = rangeKey;
  pills.forEach(p => p.classList.toggle('active', p.getAttribute('data-range-pill') === rangeKey));
  cards.forEach(c => c.style.display = (c.getAttribute('data-range') === rangeKey) ? '' : 'none');
  const none = counts[rangeKey] === 0;
  if (none && !document.getElementById('emptyMsg')) {
    const div = document.createElement('div'); div.id='emptyMsg';
    div.className='text-muted mt-3'; div.textContent='Không có game trong mốc thời gian này.';
    document.getElementById('gridGames').after(div);
  } else if (!none) { const m=document.getElementById('emptyMsg'); if (m) m.remove(); }
}
pills.forEach(p => p.addEventListener('click', () => applyFilter(p.getAttribute('data-range-pill'))));
applyFilter(current);
</script>
</body>
</html>

