<?php
// File: cauca/chuho/game/game_list_ho.php
require __DIR__ . '/../../../connect.php';
require __DIR__ . '/../../../check_login.php';
include '../../../includes/header.php';


if ($_SESSION['user']['vai_tro'] !== 'chuho') {
  header('Location: /'); exit;
}

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function money_vnd($n){ return number_format((int)$n, 0, ',', '.') . ' đ'; }

$chuho_id = (int)$_SESSION['user']['id'];
$q = trim($_GET['q'] ?? '');

// --- Truy vấn hồ cho phép đánh game ---
$sql = "
  SELECT
    h.id, h.ten_ho, h.status AS ho_status,
    h.gia_game, h.so_cho_ngoi,
    c.ten_cum_ho, c.dia_chi AS dia_chi_cum, c.google_map_url, c.status AS cum_status
  FROM ho_cau h
  JOIN cum_ho c ON c.id = h.cum_ho_id
  WHERE c.status = 'dang_chay'
    AND h.status = 'dang_hoat_dong'
    AND h.cho_phep_danh_game = 1
    AND c.chu_ho_id = :uid
";
$params = ['uid'=>$chuho_id];

if ($q !== '') {
  $sql .= " AND (h.ten_ho LIKE :kw OR c.ten_cum_ho LIKE :kw OR h.dia_chi LIKE :kw OR c.dia_chi LIKE :kw) ";
  $params['kw'] = "%$q%";
}

$sql .= " ORDER BY c.ten_cum_ho ASC, h.ten_ho ASC";

$st = $pdo->prepare($sql);
$st->execute($params);
$rows = $st->fetchAll(PDO::FETCH_ASSOC);

// badge theo status hồ
$badgeClass = fn($st) => match($st){
  'dang_hoat_dong' => 'bg-success',
  'tam_dung'       => 'bg-secondary',
  'dong_cua'       => 'bg-dark',
  default          => 'bg-light text-dark'
};
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Hồ cho phép đánh game</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="/assets/bootstrap.min.css" rel="stylesheet">
  <style>
    .card-hover:hover { transform: translateY(-2px); box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15) }
    .truncate-2 {
      display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
    }
  </style>
</head>
<body class="bg-light">
<div class="container py-4">

  <div class="d-flex align-items-center mb-3">
    <h4 class="mb-0">Hồ đang cho đánh game</h4>
    <span class="badge bg-primary ms-2"><?= count($rows) ?></span>
    <div class="ms-auto" style="max-width:360px;">
      <form class="d-flex" method="get">
        <input name="q" value="<?= h($q) ?>" class="form-control me-2" placeholder="Tìm hồ, cụm hồ, địa chỉ…">
        <button class="btn btn-outline-primary">Tìm</button>
      </form>
    </div>
  </div>

  <?php if (empty($rows)): ?>
    <div class="alert alert-info">
      Không có hồ nào đang cho đánh game hoặc không tìm thấy theo từ khóa.
    </div>
  <?php else: ?>

    <div class="row g-3">
      <?php foreach ($rows as $r): ?>
        <div class="col-12 col-sm-6 col-lg-4 col-xxl-3">
          <div class="card card-hover h-100 border-0 shadow-sm">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-start mb-1">
                <div class="fw-semibold"><?= h($r['ten_ho']) ?></div>
                <span class="badge <?= $badgeClass($r['ho_status']) ?>">Đang hoạt động</span>
              </div>
              <div class="text-muted small mb-1">Cụm: <?= h($r['ten_cum_ho']) ?></div>
              <?php if (!empty($r['dia_chi_ho'])): ?>
                <div class="text-muted small truncate-2 mb-2">
                  <i class="bi bi-geo-alt"></i> <?= h($r['dia_chi_ho']) ?>
                </div>
              <?php elseif (!empty($r['dia_chi_cum'])): ?>
                <div class="text-muted small truncate-2 mb-2">
                  <i class="bi bi-geo-alt"></i> <?= h($r['dia_chi_cum']) ?>
                </div>
              <?php endif; ?>

              <div class="d-flex justify-content-between small">
                <div>Phí game (1 người/hiệp)</div>
                <div class="fw-semibold"><?= money_vnd($r['gia_game'] ?? 0) ?></div>
              </div>
              <div class="d-flex justify-content-between small">
                <div>Số chỗ ngồi</div>
                <div class="fw-semibold"><?= (int)($r['so_cho_ngoi'] ?? 0) ?></div>
              </div>
            </div>
            <div class="card-footer bg-white border-0 pt-0 pb-3">
              <div class="d-grid gap-2">
                <a class="btn btn-primary"
                   href="/cauca/chuho/game/game_create.php?ho_id=<?= (int)$r['id'] ?>">
                  Tạo game tại hồ này
                </a>
                <a class="btn btn-outline-secondary"
                   href="/cauca/chuho/game/game_list.php?ho_id=<?= (int)$r['id'] ?>">
                  Xem game của hồ
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  <?php endif; ?>

</div>
<!-- Optional Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<script src="/assets/bootstrap.bundle.min.js"></script>
</body>
</html>
