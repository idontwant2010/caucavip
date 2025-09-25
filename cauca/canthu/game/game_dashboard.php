<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'canthu') {
    header("Location: /");
    exit;
}

$user_id = $_SESSION['user']['id'];

// 1. Game của tôi
$stmt_my = $pdo->prepare("SELECT gl.*, hc.ten_ho FROM game_list gl
    JOIN ho_cau hc ON gl.ho_cau_id = hc.id
    LEFT JOIN game_user gu ON gu.game_id = gl.id AND gu.user_id = ?
    WHERE gl.creator_id = ? OR gu.user_id = ?
    GROUP BY gl.id
    ORDER BY gl.ngay_to_chuc DESC, gl.created_at DESC");
$stmt_my->execute([$user_id, $user_id, $user_id]);
$my_games = $stmt_my->fetchAll();

// 2. Danh sách game đang mở (giới hạn 25)
$stmt_all = $pdo->query("SELECT gl.*, hc.ten_ho FROM game_list gl
    JOIN ho_cau hc ON gl.ho_cau_id = hc.id
    WHERE gl.status = 'dang_dien_ra'
    ORDER BY gl.created_at DESC
    LIMIT 25");
$games_open = $stmt_all->fetchAll();
?>

<?php include_once __DIR__ . '/../../../includes/header.php'; ?>
<div class="container mt-4">
    <h4>🎮 Trung tâm game câu cá</h4>

    <div class="mt-4">
        <h5>👤 Game của tôi:</h5>
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Tên game</th>
                    <th>Hồ câu</th>
                    <th>Ngày</th>
                    <th>Giờ</th>
                    <th>Vai trò</th>
                    <th>Trạng thái</th>
                    <th>Chi tiết</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($my_games as $game): ?>
                <tr>
                    <td><?= htmlspecialchars($game['ten_game']) ?></td>
                    <td><?= htmlspecialchars($game['ten_ho']) ?></td>
                    <td><?= $game['ngay_to_chuc'] ?></td>
                    <td><?= substr($game['gio_bat_dau'], 0, 5) ?></td>
                    <td><?= ($game['creator_id'] == $user_id) ? 'Người tạo' : 'Người chơi' ?></td>
                    <td><?= $game['status'] ?></td>
                    <td><a href="game_detail.php?game_id=<?= $game['id'] ?>" class="btn btn-sm btn-info">Xem</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <h5>🌐 Danh sách game đang mở:</h5>
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Tên game</th>
                    <th>Hồ câu</th>
                    <th>Ngày</th>
                    <th>Giờ</th>
                    <th>Số người</th>
                    <th>Game stake</th>
                    <th>Tham gia</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($games_open as $game): ?>
                <tr>
                    <td><?= htmlspecialchars($game['ten_game']) ?></td>
                    <td><?= htmlspecialchars($game['ten_ho']) ?></td>
                    <td><?= $game['ngay_to_chuc'] ?></td>
                    <td><?= substr($game['gio_bat_dau'], 0, 5) ?></td>
                    <td><?= $game['so_luong_can_thu'] ?></td>
                    <td><?= number_format($game['tong_phi_game']) ?>đ</td>
                    <td><a href="game_join.php?game_id=<?= $game['id'] ?>" class="btn btn-sm btn-success">Vào</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include_once __DIR__ . '/../../../includes/footer.php'; ?>
