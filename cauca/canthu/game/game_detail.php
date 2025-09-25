<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'canthu') {
    header("Location: /");
    exit;
}

// Lấy ID từ query string
$game_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$game_id) {
    die("ID game không hợp lệ.");
}

// Lấy thông tin game
try {
    $stmt = $pdo->prepare("SELECT gl.id, gl.ten_game, gl.ngay_to_chuc, gl.gia_game_stake, gl.gio_bat_dau, gl.so_luong_can_thu, 
                                  gl.thoi_luong_phut_hiep, gl.tong_phi_game, gl.status, gl.hinh_thuc_id, 
                                  hc.ten_ho, hc.gia_game
                           FROM game_list gl
                           LEFT JOIN ho_cau hc ON gl.ho_cau_id = hc.id
                           WHERE gl.id = ? AND gl.creator_id = ?");
    $stmt->execute([$game_id, $_SESSION['user']['id']]);
    $game = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$game) {
        die("Game không tồn tại hoặc không thuộc về bạn.");
    }
} catch (PDOException $e) {
    die("Lỗi truy vấn game: " . $e->getMessage());
}

// Lấy thông tin hình thức game
try {
    $stmt = $pdo->prepare("SELECT ten_hinh_thuc, so_nguoi_min, so_nguoi_max, so_hiep 
                           FROM giai_game_hinh_thuc 
                           WHERE id = ? AND cho_phep_canthu_tao = 1");
    $stmt->execute([$game['hinh_thuc_id']]);
    $hinh_thuc = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi truy vấn hình thức game: " . $e->getMessage());
}

include_once __DIR__ . '/../../../includes/header.php';
?>
<div class="container mt-4">
    <h4>Chi tiết game: <?= htmlspecialchars($game['ten_game']) ?></h4>
    <div class="card">
        <div class="card-body">
            <p><strong>Hồ câu:</strong> <?= htmlspecialchars($game['ten_ho']) ?></p>
            <p><strong>Ngày tổ chức:</strong> <?= date('d/m/Y', strtotime($game['ngay_to_chuc'])) ?></p>
            <p><strong>Giờ bắt đầu:</strong> <?= htmlspecialchars($game['gio_bat_dau']) ?></p>
            <p><strong>Hình thức game:</strong> <?= htmlspecialchars($hinh_thuc['ten_hinh_thuc']) ?> (Số hiệp: <?= htmlspecialchars($hinh_thuc['so_hiep'] ?? 1) ?>, Số cần thủ: min: <?= htmlspecialchars($hinh_thuc['so_nguoi_min'] ?? 'N/A') ?> || max: <?= htmlspecialchars($hinh_thuc['so_nguoi_max'] ?? 'N/A') ?>)</p>
            <p><strong>Số lượng cần thủ:</strong> <?= htmlspecialchars($game['so_luong_can_thu']) ?></p>
            <p><strong>Thời lượng mỗi hiệp:</strong> <?= htmlspecialchars($game['thoi_luong_phut_hiep']) ?> phút</p>
            <p><strong>Giá game cược (VNĐ):</strong> <?= number_format($game['gia_game_stake'], 0, ',', '.') ?></p>
            <p><strong>Tổng phí game (VNĐ):</strong> <?= number_format($game['tong_phi_game'], 0, ',', '.') ?></p>
            <p><strong>Trạng thái:</strong> <?= htmlspecialchars($game['status']) ?></p>
        </div>
    </div>
    <a href="game_list.php" class="btn btn-secondary mt-3">Quay lại danh sách</a>
</div>
<?php include_once __DIR__ . '/../../../includes/footer.php'; ?>