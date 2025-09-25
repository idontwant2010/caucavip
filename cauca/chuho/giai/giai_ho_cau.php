<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';


if ($_SESSION['user']['vai_tro'] !== 'chuho') {
    header("Location: /");
    exit;
}

// Load danh sách tỉnh, loại cá để hiển thị bộ lọc
$ds_tinh = $pdo->query("SELECT id, ten_tinh FROM dm_tinh ORDER BY ten_tinh ASC")->fetchAll();
$ds_loai_ca = $pdo->query("SELECT id, ten_ca FROM loai_ca ORDER BY ten_ca ASC")->fetchAll();

// Lấy bộ lọc từ query string
$tinh_id = isset($_GET['tinh_id']) ? (int)$_GET['tinh_id'] : 0;
$loai_ca_id = isset($_GET['loai_ca_id']) ? (int)$_GET['loai_ca_id'] : 0;
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 6;
$offset = ($page - 1) * $limit;

// Tạo điều kiện lọc
$conditions = [];
$params = [];

$chu_ho_id = $user['id'];

$conditions[] = "h.status = 'dang_hoat_dong'";
$conditions[] = "h.cho_phep_danh_giai = 1";
$conditions[] = "c.status = 'dang_chay'";
$conditions[] = "c.chu_ho_id = :chu_ho_id";
$params['chu_ho_id'] = $chu_ho_id;

if ($tinh_id > 0) {
    $conditions[] = "t.id = ?";
    $params[] = $tinh_id;
}
if ($loai_ca_id > 0) {
    $conditions[] = "h.loai_ca_id = ?";
    $params[] = $loai_ca_id;
}
if ($keyword !== '') {
    $conditions[] = "(h.ten_ho LIKE ? OR c.ten_cum_ho LIKE ? OR c.dia_chi LIKE ? OR h.mo_ta LIKE ? OR t.ten_tinh LIKE ?)";
    $params[] = "%$keyword%";
    $params[] = "%$keyword%";
    $params[] = "%$keyword%";
    $params[] = "%$keyword%";
	$params[] = "%$keyword%";
}

$where_sql = implode(" AND ", $conditions);

$total_sql = "SELECT COUNT(*)
FROM ho_cau h
JOIN cum_ho c ON h.cum_ho_id = c.id
JOIN dm_xa_phuong x ON c.xa_id = x.id
JOIN dm_tinh t ON x.tinh_id = t.id
LEFT JOIN loai_ca l ON h.loai_ca_id = l.id
WHERE $where_sql";

$stmt_total = $pdo->prepare($total_sql);
$stmt_total->execute($params);
$total_items = $stmt_total->fetchColumn();
$total_pages = ceil($total_items / $limit);

$sql = "
SELECT h.*, c.ten_cum_ho, c.dia_chi,
       x.ten_xa_phuong, t.ten_tinh,
       l.ten_ca
FROM ho_cau h
JOIN cum_ho c ON h.cum_ho_id = c.id
JOIN dm_xa_phuong x ON c.xa_id = x.id
JOIN dm_tinh t ON x.tinh_id = t.id
LEFT JOIN loai_ca l ON h.loai_ca_id = l.id
WHERE $where_sql
ORDER BY h.gia_giai DESC, h.ten_ho ASC
LIMIT $limit OFFSET $offset
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$ds_ho = $stmt->fetchAll();
?>
<?php include_once '../../../includes/header.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
  <h4 class="mb-4">🏆 Bạn có muốn tạo Giải / Game ở hồ của bạn?</h4>

  <div class="row">
    <?php foreach ($ds_ho as $ho): ?>
      <div class="col-md-6 mb-4">
        <div class="card border-success shadow-sm">
          <div class="card-body">
            <h5 class="card-title">
              <?= htmlspecialchars($ho['ten_ho']) ?> - <small><?= htmlspecialchars($ho['ten_cum_ho']) ?></small>
              <span class="badge <?= $ho['gia_giai'] >= 50000 ? 'bg-danger' : ($ho['gia_giai'] >= 30000 ? 'bg-warning text-dark' : 'bg-success') ?> float-end" title="Phí tổ chức giải cá tại hồ này">
                Phí giải: <?= number_format($ho['gia_giai']) ?>đ
              </span>
            </h5>
            <p class="mb-1" title="Loại cá chính trong hồ">🐟 <?= htmlspecialchars($ho['ten_ca']) ?> | 🎯 <?= $ho['luong_ca'] ?>kg | 🪑 <?= $ho['so_cho_ngoi'] ?> chỗ</p>
            <p class="mb-1">📍 <?= htmlspecialchars($ho['dia_chi']) ?>, <?= $ho['ten_xa_phuong'] ?>, <?= $ho['ten_tinh'] ?></p>
            <small class="text-muted" title="Mô tả hồ">📝 <?= htmlspecialchars(substr($ho['mo_ta'], 0, 100)) ?>...</small>
            <br><a href="giai_create.php?ho_id=<?= $ho['id'] ?>" class="btn btn-sm btn-success mt-2">+ Tạo giải</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
    <?php if (empty($ds_ho)): ?>
      <p class="text-muted">Không tìm thấy hồ phù hợp với điều kiện lọc.</p>
    <?php endif; ?>
  </div>

  <?php if ($total_pages > 1): ?>
  <nav aria-label="Page navigation">
    <ul class="pagination justify-content-center mt-4">
      <?php for ($p = 1; $p <= $total_pages; $p++): ?>
        <li class="page-item <?= ($p == $page) ? 'active' : '' ?>">
          <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $p])) ?>"><?= $p ?></a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>
  <?php endif; ?>
</div>

<?php include '../../../includes/footer.php'; ?>