<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'canthu') {
    header("Location: /");
    exit;
}

// Lấy danh sách game đang diễn ra kèm thông tin cụm hồ, hồ câu, xã, tỉnh
$stmt = $pdo->query("SELECT gl.*, hc.ten_ho, hc.dien_tich, hc.max_chieu_dai_can, hc.max_truc_theo,
        hc.so_cho_ngoi, hc.luong_ca, ch.ten_cum_ho, xa.ten_xa_phuong, tinh.ten_tinh
    FROM game_list gl
    JOIN ho_cau hc ON gl.ho_cau_id = hc.id
    JOIN cum_ho ch ON hc.cum_ho_id = ch.id
    JOIN dm_xa_phuong xa ON ch.xa_id = xa.id
    JOIN dm_tinh tinh ON xa.tinh_id = tinh.id
    WHERE gl.status = 'dang_dien_ra'
    ORDER BY gl.ngay_to_chuc DESC, gl.gio_bat_dau ASC
    LIMIT 30");
$games = $stmt->fetchAll();

include_once __DIR__ . '/../../../includes/header.php';
?>
<div class="container mt-4">
    <h4>🎮 Danh sách các game đang diễn ra</h4>
    <div class="row">
        <?php foreach ($games as $game): ?>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-success">💰 Giá game: <?= number_format($game['gia_game_stake']) ?>đ/người</h5>
                    <h5 class="card-title">🎯 <?= htmlspecialchars($game['ten_game']) ?></h5>
                    <p class="mb-1">📍 <?= $game['ten_cum_ho'] ?> - <?= $game['ten_xa_phuong'] ?>, <?= $game['ten_tinh'] ?></p>
                    <p class="mb-1">📅 <?= $game['ngay_to_chuc'] ?> | ⏰ <?= substr($game['gio_bat_dau'], 0, 5) ?></p>
                    <p class="mb-1">👥 <?= $game['so_luong_can_thu'] ?> cần thủ | Hồ: <?= $game['ten_ho'] ?> (<?= $game['dien_tich'] ?> m²)</p>
                    <p class="mb-1">🎣 Dài cần: <?= $game['max_chieu_dai_can'] ?> cm | Trục: <?= $game['max_truc_theo'] ?> | Số chỗ: <?= $game['so_cho_ngoi'] ?></p>
                    <p class="mb-2">🐟 Lượng cá: <?= $game['luong_ca'] ?></p>
                    <a href="game_detail.php?game_id=<?= $game['id'] ?>" class="btn btn-primary w-100">🔍 Chi tiết game</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include_once __DIR__ . '/../../../includes/footer.php'; ?>
