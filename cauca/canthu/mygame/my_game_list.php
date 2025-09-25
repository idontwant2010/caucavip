<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'canthu') {
    header("Location: /");
    exit;
}

// Lấy danh sách game của người dùng
try {
    $stmt = $pdo->prepare("SELECT gl.id, gl.ten_game, gl.ngay_to_chuc, gl.gio_bat_dau, gl.so_luong_can_thu, 
                                  gl.thoi_luong_phut_hiep, gl.tong_phi_game, gl.status, hc.ten_ho
                           FROM game_list gl
                           LEFT JOIN ho_cau hc ON gl.ho_cau_id = hc.id
                           WHERE gl.creator_id = ? 
                           ORDER BY gl.ngay_to_chuc DESC, gl.gio_bat_dau DESC");
    $stmt->execute([$_SESSION['user']['id']]);
    $game_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi truy vấn danh sách game: " . $e->getMessage());
}

include_once __DIR__ . '/../../../includes/header.php';
?>
<div class="container mt-4">
    <h4>Danh sách game của bạn</h4>
    <?php if (empty($game_list)): ?>
        <div class="alert alert-info">Bạn chưa tạo game nào.</div>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tên game</th>
                    <th>Hồ câu</th>
                    <th>Ngày tổ chức</th>
                    <th>Giờ bắt đầu</th>
                    <th>Số cần thủ</th>
                    <th>Thời lượng/hiệp (phút)</th>
                    <th>Tổng phí (VNĐ)</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($game_list as $game): ?>
                    <tr>
                        <td><?= htmlspecialchars($game['ten_game']) ?></td>
                        <td><?= htmlspecialchars($game['ten_ho']) ?></td>
                        <td><?= date('d/m/Y', strtotime($game['ngay_to_chuc'])) ?></td>
                        <td><?= htmlspecialchars($game['gio_bat_dau']) ?></td>
                        <td><?= htmlspecialchars($game['so_luong_can_thu']) ?></td>
                        <td><?= htmlspecialchars($game['thoi_luong_phut_hiep']) ?></td>
                        <td><?= number_format($game['tong_phi_game'], 0, ',', '.') ?></td>
                        <td><?= htmlspecialchars($game['status']) ?></td>
                        <td>
                            <a href="game_detail.php?id=<?= $game['id'] ?>" class="btn btn-info btn-sm">Xem</a>
                            <?php if ($game['status'] === 'cho_xac_nhan'): ?>
                                <a href="game_edit.php?id=<?= $game['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                                <a href="game_cancel.php?id=<?= $game['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn hủy game này?')">Hủy</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <a href="game_ho_cau.php" class="btn btn-primary">Tạo game mới</a>
</div>
<?php include_once __DIR__ . '/../../../includes/footer.php'; ?>