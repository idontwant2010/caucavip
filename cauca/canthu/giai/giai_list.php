<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'canthu') {
    header("Location: /");
    exit;
}

$user_id = (int)$_SESSION['user']['id'];

// --- biến tìm kiếm (nếu có) ---
$search_ten       = $_GET['search_ten']       ?? '';
$search_dia_diem  = $_GET['search_dia_diem']  ?? '';
$search_ngay      = $_GET['search_ngay']      ?? ''; // định dạng YYYY-MM-DD nếu g.ngay_to_chuc là DATE

// --- base SQL (chưa có ORDER BY) ---
$sql = "
SELECT 
    g.*, 
    h.ten_ho, 
    ch.ten_cum_ho, 
    f.ten_hinh_thuc,

    -- Tổng đã đăng ký (mọi trạng thái)
    (SELECT COUNT(*) FROM giai_user gu WHERE gu.giai_id = g.id) AS so_nguoi_da_dk,

    -- User hiện tại đã thanh toán?
    (SELECT COUNT(*) 
     FROM giai_user gu2 
     WHERE gu2.giai_id = g.id 
       AND gu2.user_id = :uid1 
       AND gu2.trang_thai = 'da_thanh_toan'
    ) AS da_thanh_toan_count,

    -- User hiện tại đang có lời mời chờ phản hồi?
    (SELECT COUNT(*) 
     FROM giai_user gu3 
     WHERE gu3.giai_id = g.id 
       AND gu3.user_id = :uid2 
       AND gu3.trang_thai = 'moi_cho_phan_hoi'
    ) AS moi_cho_phan_hoi_count,

    -- User hiện tại đã tự tham gia nhưng chưa thanh toán?
    (SELECT COUNT(*) 
     FROM giai_user gu4 
     WHERE gu4.giai_id = g.id 
       AND gu4.user_id = :uid3 
       AND gu4.trang_thai = 'tu_tham_gia_cho_thanh_toan'
    ) AS cho_thanh_toan_count

FROM giai_list g
JOIN ho_cau h ON g.ho_cau_id = h.id
JOIN cum_ho ch ON h.cum_ho_id = ch.id
JOIN giai_game_hinh_thuc f ON g.hinh_thuc_id = f.id
WHERE g.status = 'dang_mo_dang_ky'
  AND g.thoi_gian_dong_dang_ky >= NOW()
";

$params = [
    ':uid1' => $user_id,
    ':uid2' => $user_id,
    ':uid3' => $user_id,
];

// --- điều kiện tìm kiếm động ---
if ($search_ten !== '') {
    $sql .= " AND g.ten_giai LIKE :search_ten";
    $params[':search_ten'] = "%{$search_ten}%";
}
if ($search_dia_diem !== '') {
    $sql .= " AND ch.ten_cum_ho LIKE :search_dia_diem";
    $params[':search_dia_diem'] = "%{$search_dia_diem}%";
}
if ($search_ngay !== '') {
    $sql .= " AND g.ngay_to_chuc = :search_ngay";
    $params[':search_ngay'] = $search_ngay;
}

// --- sắp xếp cuối cùng ---
$sql .= " ORDER BY g.ngay_to_chuc DESC, g.gio_bat_dau ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$ds_giai = $stmt->fetchAll(PDO::FETCH_ASSOC);



include __DIR__ . '/../../../includes/header.php';
?>

<div class="container mt-4">
    <h3 class="mb-4">🎯 Danh sách các giải đang mở đăng ký</h3>

    <form method="get" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="search_ten" class="form-control" placeholder="🔍 Tên giải..." value="<?= htmlspecialchars($search_ten) ?>">
        </div>
        <div class="col-md-4">
            <input type="text" name="search_dia_diem" class="form-control" placeholder="🏞️ Địa điểm (cụm hồ)..." value="<?= htmlspecialchars($search_dia_diem) ?>">
        </div>
		<div class="col-md-auto d-flex align-items-start gap-2">
			<button type="submit" class="btn btn-primary">Lọc</button>
			<a href="giai_list.php" class="btn btn-outline-secondary">🔄 Reset tất cả</a>
		</div>
    </form>

    <?php if (empty($ds_giai)): ?>
        <div class="alert alert-info">Không tìm thấy giải phù hợp.</div>
    <?php endif; ?>

    <div class="row">
    <?php foreach ($ds_giai as $g): ?>
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">🏆 <?= htmlspecialchars($g['ten_giai']) ?></h5>
                    <p class="mb-1">📍 <strong><?= $g['ten_ho'] ?></strong> - <?= $g['ten_cum_ho'] ?></p>
                    <p class="mb-1">📅 Tổ chức: <?= date('d/m/Y', strtotime($g['ngay_to_chuc'])) ?> lúc <?= substr($g['gio_bat_dau'], 0, 5) ?></p>
                    <p class="mb-1">⏳ Đăng ký đến: <?= date('d/m/Y H:i', strtotime($g['thoi_gian_dong_dang_ky'])) ?></p>
                    <p class="mb-1">👥 Đã đăng ký: <?= $g['so_nguoi_da_dk'] ?> / <?= $g['so_luong_can_thu'] ?> cần thủ</p>
                    <div class="progress mb-2">
                        <div class="progress-bar bg-success" role="progressbar" 
                            style="width: <?= round($g['so_nguoi_da_dk'] / $g['so_luong_can_thu'] * 100) ?>%;">
                            <?= $g['so_nguoi_da_dk'] ?> / <?= $g['so_luong_can_thu'] ?>
                        </div>
                    </div>
                    <p class="mb-1">💰 Tiền cược: <?= number_format($g['tien_cuoc'], 0, ',', '.') ?>đ</p>
                    <p class="mb-1">🎯 Hình thức: <?= $g['ten_hinh_thuc'] ?></p>
                    <p class="mb-1">🔐 Yêu cầu EXP ≥ <?= $g['min_user_exp'] ?>, Level ≥ <?= $g['min_user_level'] ?></p>
                </div>
					  <?php if ((int)$g['da_thanh_toan_count'] > 0): ?>
						<!-- ĐÃ THAM GIA -->
						<button type="button" class="btn btn-secondary btn-sm" disabled>Đã tham gia</button>

					  <?php elseif ((int)$g['moi_cho_phan_hoi_count'] > 0): ?>
						<!-- ĐANG CÓ LỜI MỜI -->
						<form class="d-inline" action="giai_invite_accept.php" method="post">
						  <input type="hidden" name="giai_id" value="<?= (int)$g['id'] ?>">
						  <button type="submit" class="btn btn-success btn-sm">Chấp nhận lời mời</button>
						</form>
						<form class="d-inline ms-2" action="giai_invite_decline.php" method="post">
						  <input type="hidden" name="giai_id" value="<?= (int)$g['id'] ?>">
						  <button type="submit" class="btn btn-outline-secondary btn-sm">Từ chối</button>
						</form>

					  <?php elseif ((int)$g['cho_thanh_toan_count'] > 0): ?>
						<!-- ĐÃ TỰ THAM GIA, CHƯA THANH TOÁN -->
						<a href="thanh_toan_giai.php?giai_id=<?= (int)$g['id'] ?>" class="btn btn-warning btn-sm">Thanh toán ngay</a>

					  <?php else: ?>
						<!-- CHƯA CÓ GÌ: HIỂN THỊ NÚT ĐĂNG KÝ -->
						<form action="dang_ky_giai_process.php" method="post">
						  <input type="hidden" name="giai_id" value="<?= (int)$g['id'] ?>">
						  <button type="submit" class="btn btn-primary btn-sm">Đăng ký tham gia</button>
						</form>
					  <?php endif; ?>

            </div>
        </div>
    <?php endforeach; ?>
    </div>
</div>

<?php include __DIR__ . '/../../../includes/footer.php'; ?>
