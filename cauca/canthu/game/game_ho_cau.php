<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'canthu') {
    header("Location: /");
    exit;
}

// Xử lý lọc
$tinh_id = filter_input(INPUT_GET, 'tinh_id', FILTER_VALIDATE_INT) ?: '';
$xa_id = filter_input(INPUT_GET, 'xa_id', FILTER_VALIDATE_INT) ?: '';
$loai_ca_id = filter_input(INPUT_GET, 'loai_ca_id', FILTER_VALIDATE_INT) ?: '';

// Truy vấn danh sách tỉnh, xã, loại cá cho filter
$tinh_list = $pdo->query("SELECT * FROM dm_tinh ORDER BY ten_tinh")->fetchAll();
$xa_list = $pdo->query("SELECT * FROM dm_xa_phuong ORDER BY ten_xa_phuong")->fetchAll();
$loai_ca_list = $pdo->query("SELECT * FROM loai_ca WHERE trang_thai = 'hoat_dong'")->fetchAll();

// Lấy danh sách hồ câu cho phép đánh game
$sql = "SELECT hc.*, ch.ten_cum_ho, xa.ten_xa_phuong, tinh.ten_tinh, lc.ten_ca
        FROM ho_cau hc
        JOIN cum_ho ch ON hc.cum_ho_id = ch.id
        JOIN dm_xa_phuong xa ON ch.xa_id = xa.id
        JOIN dm_tinh tinh ON xa.tinh_id = tinh.id
        LEFT JOIN loai_ca lc ON hc.loai_ca_id = lc.id
        WHERE hc.status = 'dang_hoat_dong' AND hc.cho_phep_danh_game = 1";

$params = [];
if ($tinh_id) {
    $sql .= " AND tinh.id = ?";
    $params[] = $tinh_id;
}
if ($xa_id) {
    $sql .= " AND xa.id = ?";
    $params[] = $xa_id;
}
if ($loai_ca_id) {
    $sql .= " AND lc.id = ?";
    $params[] = $loai_ca_id;
}

$sql .= " ORDER BY hc.gia_game DESC, hc.id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$ho_list = $stmt->fetchAll();

include_once __DIR__ . '/../../../includes/header.php';
?>
<div class="container mt-4">
    <h4>🎣 Danh sách hồ đang cho phép tạo game</h4>
    <form method="get" class="row g-2 mb-3">
        <div class="col-md-3">
            <select name="tinh_id" class="form-select">
                <option value="">-- Tất cả tỉnh --</option>
                <?php foreach ($tinh_list as $tinh): ?>
                    <option value="<?= $tinh['id'] ?>" <?= ($tinh_id == $tinh['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tinh['ten_tinh']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="xa_id" class="form-select">
                <option value="">-- Tất cả xã --</option>
                <?php foreach ($xa_list as $xa): ?>
                    <option value="<?= $xa['id'] ?>" <?= ($xa_id == $xa['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($xa['ten_xa_phuong']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="loai_ca_id" class="form-select">
                <option value="">-- Tất cả loại cá --</option>
                <?php foreach ($loai_ca_list as $loai): ?>
                    <option value="<?= $loai['id'] ?>" <?= ($loai_ca_id == $loai['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($loai['ten_ca']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100">Lọc</button>
        </div>
    </form>

    <?php if (empty($ho_list)): ?>
        <div class="alert alert-warning">Không tìm thấy hồ nào phù hợp với bộ lọc.</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($ho_list as $ho): ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-success">💰 Phí game: <?= number_format($ho['gia_game']) ?>đ/người/game</h5>
                            <h5 class="card-title">🐟 <?= htmlspecialchars($ho['ten_ho']) ?></h5>
                            <p class="mb-1">📍 <?= htmlspecialchars($ho['ten_cum_ho']) ?> - <?= htmlspecialchars($ho['ten_xa_phuong']) ?>, <?= htmlspecialchars($ho['ten_tinh']) ?></p>
                            <p class="mb-1">📐 Diện tích: <?= $ho['dien_tich'] ?> m² | Số chỗ: <?= $ho['so_cho_ngoi'] ?></p>
                            <p class="mb-1">🎣 Dài cần: <?= $ho['max_chieu_dai_can'] ?> cm | Trục: <?= $ho['max_truc_theo'] ?> cm</p>
                            <p class="mb-1">🐠 Loại cá: <?= htmlspecialchars($ho['ten_ca'] ?? 'Không xác định') ?> | 🎏 Lượng cá: <?= $ho['luong_ca'] ?> kg</p>
                            <a href="game_create.php?ho_cau_id=<?= $ho['id'] ?>" class="btn btn-success w-100">Tạo game</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php include_once __DIR__ . '/../../../includes/footer.php'; ?>