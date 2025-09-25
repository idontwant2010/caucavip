<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'canthu') {
    header("Location: /");
    exit;
}


// Load tỉnh và loại cá
$dm_tinh = $pdo->query("SELECT id, ten_tinh FROM dm_tinh ORDER BY ten_tinh ASC")->fetchAll();
$loai_ca_map = [];
$loai_ca_list = $pdo->query("SELECT id, ten_ca FROM loai_ca WHERE trang_thai = 'hoat_dong' ORDER BY ten_ca ASC")->fetchAll();
foreach ($loai_ca_list as $ca) {
    $loai_ca_map[$ca['id']] = $ca['ten_ca'];
}

// Input
$keyword     = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$tinh_id_raw = $_GET['tinh_id']      ?? '';
$loai_raw    = $_GET['loai_ca_id']   ?? '';

// Chuẩn hoá số (chỉ nhận số dương)
$tinh_id    = (is_string($tinh_id_raw) && ctype_digit($tinh_id_raw)) ? (int)$tinh_id_raw : null;
$loai_ca_id = (is_string($loai_raw)    && ctype_digit($loai_raw))    ? (int)$loai_raw    : null;

// Base SQL
$sql = "
SELECT 
    ho.*, 
    cum.ten_cum_ho, cum.google_map_url,
    xa.ten_xa_phuong, 
    tinh.ten_tinh
FROM ho_cau ho
JOIN cum_ho      cum  ON ho.cum_ho_id = cum.id
JOIN dm_xa_phuong xa  ON cum.xa_id    = xa.id
JOIN dm_tinh      tinh ON xa.tinh_id  = tinh.id
WHERE ho.status = 'dang_hoat_dong'
";

$where   = [];
$params  = [];

// Keyword search (áp cho nhiều cột + tên loại cá)
if ($keyword !== '') {
    // Nếu muốn chia keyword theo nhiều từ để AND tất cả, bật đoạn dưới:
    // $words = preg_split('/\s+/', $keyword);
    // foreach ($words as $w) {
    //     $like = '%'.$w.'%';
    //     $where[] = "(ho.ten_ho LIKE ? OR tinh.ten_tinh LIKE ? OR xa.ten_xa_phuong LIKE ? OR cum.ten_cum_ho LIKE ? 
    //                 OR EXISTS (SELECT 1 FROM loai_ca 
    //                            WHERE FIND_IN_SET(loai_ca.id, ho.loai_ca_id) 
    //                              AND loai_ca.ten_ca LIKE ?))";
    //     array_push($params, $like, $like, $like, $like, $like);
    // }

    // Đơn giản: 1 keyword áp cho tất cả
    $like = '%'.$keyword.'%';
    $where[] = "(
        ho.ten_ho LIKE ?
        OR tinh.ten_tinh LIKE ?
        OR xa.ten_xa_phuong LIKE ?
        OR cum.ten_cum_ho LIKE ?
        OR EXISTS (
            SELECT 1
            FROM loai_ca 
            WHERE FIND_IN_SET(loai_ca.id, ho.loai_ca_id)
              AND loai_ca.ten_ca LIKE ?
        )
    )";
    array_push($params, $like, $like, $like, $like, $like);
}

// Lọc theo tỉnh
if (!is_null($tinh_id)) {
    $where[]  = "tinh.id = ?";
    $params[] = $tinh_id;
}

// Lọc theo loại cá (id nằm trong CSV loai_ca_id của hồ)
if (!is_null($loai_ca_id)) {
    $where[]  = "FIND_IN_SET(?, ho.loai_ca_id)";
    $params[] = $loai_ca_id;
}

// Gộp WHERE phụ
if (!empty($where)) {
    $sql .= " AND " . implode(" AND ", $where);
}

// Sắp xếp
$sql .= " ORDER BY ho.id DESC";

// Thực thi
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$ho_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

//hiện thanh trạng thái search

// Đếm tổng số hồ đang mở (mọi tỉnh)
$totalOpen = (int)$pdo->query("SELECT COUNT(*) FROM ho_cau WHERE status = 'dang_hoat_dong'")->fetchColumn();

// Số hồ sau khi lọc
$filteredCount = is_array($ho_list) ? count($ho_list) : 0;

// Nhãn keyword
$kwLabel = ($keyword === '' ? 'trống' : $keyword);

// Nhãn tỉnh
$provinceLabel = 'tất cả tỉnh';
if (isset($tinh_id) && ctype_digit((string)$tinh_id)) {
    $stTinh = $pdo->prepare("SELECT ten_tinh FROM dm_tinh WHERE id = ?");
    $stTinh->execute([(int)$tinh_id]);
    $provinceLabel = $stTinh->fetchColumn() ?: $provinceLabel;
}

// Nhãn loại cá
$loaiLabel = 'tất cả';
if (isset($loai_ca_id) && ctype_digit((string)$loai_ca_id)) {
    $stLoai = $pdo->prepare("SELECT ten_ca FROM loai_ca WHERE id = ?");
    $stLoai->execute([(int)$loai_ca_id]);
    $loaiLabel = $stLoai->fetchColumn() ?: $loaiLabel;
}

// Thông báo 1 dòng
$notice = sprintf(
    'Hiện tại có %d hồ đang mở tại tất cả tỉnh || Khi tìm kiếm: có %d hồ với điều kiện lọc là nội dung search "%s", tỉnh "%s", loại cá "%s".',
    $totalOpen, $filteredCount, $kwLabel, $provinceLabel, $loaiLabel
);
?>

<?php include_once __DIR__ . '/../../../includes/header.php'; ?>
<div class="container mt-4">
    <h4 class="mb-4">🎣 Danh sách hồ đang mở</h4>
	    <!-- box search -->
    <form class="row g-3 mb-4" method="get">
        <div class="col-md-3">
            <input type="text" name="keyword" class="form-control" placeholder="🔍 Tên hồ, tỉnh, xã, loại cá..." value="<?= htmlspecialchars($keyword) ?>">
        </div>
        <div class="col-md-3">
            <select name="tinh_id" class="form-select">
                <option value="">-- Tất cả tỉnh --</option>
                <?php foreach ($dm_tinh as $t): ?>
                    <option value="<?= $t['id'] ?>" <?= $tinh_id == $t['id'] ? 'selected' : '' ?>><?= $t['ten_tinh'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="loai_ca_id" class="form-select">
                <option value="">-- Tất cả loại cá --</option>
                <?php foreach ($loai_ca_list as $lc): ?>
                    <option value="<?= $lc['id'] ?>" <?= $loai_ca_id == $lc['id'] ? 'selected' : '' ?>><?= $lc['ten_ca'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">🔍 Tìm kiếm</button>
        </div>
        <div class="col-md-1">
            <a href="booking_list.php" class="btn btn-outline-secondary w-100">↩</a>
        </div>
    </form> 
	    <!-- Thông báo -->
	<div class="alert alert-info py-2 px-3">Thông tin: <?= $notice ?></div>
        <!-- Danh sách các hồ -->
    <div class="row">
        <?php foreach ($ho_list as $ho): ?>
            <div class="col-md-4 mb-4">
                <div class="card border shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($ho['ten_ho']) ?></h5>
                        <p class="mb-1">📐 Diện tích: <?= $ho['dien_tich'] ?> m² 🐟 Lượng cá: <?= $ho['luong_ca'] ?> kg</p>
                        <p class="mb-1">🎣Giới hạn cần: <?= $ho['max_chieu_dai_can'] ?> cm, Trục thẻo: + <?= $ho['max_truc_theo'] ?> cm</p>
						<p class="mb-1">
						  🎣 Số chỗ: <?= $ho['so_cho_ngoi'] ?> người
						  <?php if (!empty($ho['google_map_url'])): ?>
							· <a href="<?= htmlspecialchars($ho['google_map_url']) ?>" 
								 target="_blank" class="btn btn-sm btn-outline-primary">
								📍 Xem bản đồ
							  </a>
						  <?php endif; ?>
						</p>

						<p class="mb-1">📍<?= $ho['ten_xa_phuong'] ?>, <?= $ho['ten_tinh'] ?></p>
                        <?php
                        $ten_ca_arr = [];
                        if (!empty($ho['loai_ca_id'])) {
                            $id_ca_arr = explode(',', $ho['loai_ca_id']);
                            foreach ($id_ca_arr as $id_ca) {
                                if (isset($loai_ca_map[$id_ca])) {
                                    $ten_ca_arr[] = $loai_ca_map[$id_ca];
                                }
                            }
                        }
                        ?>
                        <?php if (!empty($ten_ca_arr)): ?>
                            <div class="mb-2">
                                <span class="badge bg-info">🎏 <?= implode(', ', $ten_ca_arr) ?></span>
                            </div>
                        <?php endif; ?>
                        <a href="../../../../cauca/canthu/booking/booking_create.php?ho_id=<?= $ho['id'] ?>" class="btn btn-success w-100">Đặt vé</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($ho_list)): ?>
            <p class="text-muted">Không tìm thấy hồ phù hợp.</p>
        <?php endif; ?>
    </div>
</div>
<?php include_once __DIR__ . '/../../../includes/footer.php'; ?>