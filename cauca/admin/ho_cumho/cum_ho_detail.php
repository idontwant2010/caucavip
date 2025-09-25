<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'admin') {
    header('Location: /caucavip/dashboard/index.php');
    exit;
}

$id = $_GET['id'] ?? '';
if (!$id || !is_numeric($id)) {
    header('Location: cum_ho_list.php');
    exit;
}

// Lấy thông tin cụm hồ
$stmt = $pdo->prepare("
    SELECT ch.*, xa.ten_xa_phuong, xa.ma_xa_phuong, t.ten_tinh, u.phone AS chu_ho_phone, u.full_name
    FROM cum_ho ch
    LEFT JOIN dm_xa_phuong xa ON ch.xa_id = xa.id
    LEFT JOIN dm_tinh t ON xa.tinh_id = t.id
    LEFT JOIN users u ON ch.chu_ho_id = u.id
    WHERE ch.id = :id
");
$stmt->execute([':id' => $id]);
$cum_ho = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$cum_ho) {
    header('Location: cum_ho_list.php');
    exit;
}

// Lấy hồ thuộc cụm
$ho_stmt = $pdo->prepare("
    SELECT hc.*, ca.ten_ca
    FROM ho_cau hc
    LEFT JOIN loai_ca ca ON hc.loai_ca_id = ca.id
    WHERE hc.cum_ho_id = :id
    ORDER BY hc.id DESC
");
$ho_stmt->execute([':id' => $id]);
$list_ho = $ho_stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy lịch sử chuyển chủ
$log_stmt = $pdo->prepare("
    SELECT l.*, u1.full_name AS old_name, u2.full_name AS new_name, u3.full_name AS updater_name
    FROM cum_ho_logs l
    LEFT JOIN users u1 ON l.old_chu_ho_id = u1.id
    LEFT JOIN users u2 ON l.new_chu_ho_id = u2.id
    LEFT JOIN users u3 ON l.updated_by = u3.id
    WHERE l.cum_ho_id = :id
    ORDER BY l.updated_at DESC
");
$log_stmt->execute([':id' => $id]);
$logs = $log_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include __DIR__ . '/../../../includes/header.php'; ?>
<div class="container mt-4">
    <h4>📄 Chi tiết Cụm Hồ</h4>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Tên cụm hồ:</strong> <?= htmlspecialchars($cum_ho['ten_cum_ho']) ?></p>
            <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($cum_ho['dia_chi']) ?></p>
            <p><strong>Xã / Tỉnh:</strong> <?= $cum_ho['ten_xa_phuong'] ?> (<?= $cum_ho['ma_xa_phuong'] ?>) - tỉnh <?= $cum_ho['ten_tinh'] ?></p>
            <p><strong>Chủ hồ:</strong> <?= $cum_ho['chu_ho_phone'] ?> - <?= $cum_ho['full_name'] ?></p>
            <p><strong>Link bản đồ:</strong> <a href="<?= htmlspecialchars($cum_ho['google_map_url']) ?>" target="_blank"><?= htmlspecialchars($cum_ho['google_map_url']) ?></a></p>
            <p><strong>Mô tả:</strong> <?= htmlspecialchars($cum_ho['mo_ta']) ?></p>
            <p><strong>Trạng thái:</strong>
                <?php
                    switch ($cum_ho['status']) {
                        case 'dang_chay': echo '<span class="badge bg-success">Đang chạy</span>'; break;
                        case 'chuho_tam_khoa': echo '<span class="badge bg-warning text-dark">Chủ hồ khóa</span>'; break;
                        default: echo '<span class="badge bg-danger">Admin khóa</span>';
                    }
                ?>
            </p>
            <p><strong>Ngày tạo:</strong> <?= date('d/m/Y H:i', strtotime($cum_ho['created_at'])) ?></p>
        </div>
    </div>

    <h5>🎣 Danh sách Hồ Câu thuộc Cụm</h5>
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Tên hồ</th>
                <th>Loại cá</th>
                <th>Lượng cá</th>
                <th>Số chỗ</th>
                <th>Game</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!$list_ho): ?>
                <tr><td colspan="8" class="text-center text-muted">Không có hồ nào</td></tr>
            <?php else: ?>
                <?php foreach ($list_ho as $ho): ?>
                    <tr>
                        <td><?= $ho['id'] ?></td>
                        <td><?= htmlspecialchars($ho['ten_ho']) ?></td>
                        <td><?= htmlspecialchars($ho['ten_ca'] ?? '—') ?></td>
                        <td><?= $ho['luong_ca'] ?> kg</td>
                        <td><?= $ho['so_cho_ngoi'] ?></td>
                        <td><?= $ho['cho_phep_danh_game'] ? '✔ ' . number_format($ho['gia_game']) . 'đ' : '✘' ?></td>
                        <td>
                            <?php
                                switch ($ho['status']) {
                                    case 'dang_hoat_dong': echo '<span class="badge bg-success">Đang hoạt động</span>'; break;
                                    case 'chua_mo': echo '<span class="badge bg-secondary">Chưa mở</span>'; break;
                                    case 'chuho_tam_khoa': echo '<span class="badge bg-warning text-dark">Chủ hồ khóa</span>'; break;
                                    default: echo '<span class="badge bg-danger">Admin khóa</span>';
                                }
                            ?>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($ho['created_at'])) ?></td>
                    </tr>
                <?php endforeach ?>
            <?php endif ?>
        </tbody>
    </table>

    <h5 class="mt-5">📜 Nhật ký chuyển chủ</h5>
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Thời gian</th>
                <th>Người thực hiện</th>
                <th>Chủ cũ ➝ Chủ mới</th>
                <th>Nội dung chỉnh sửa</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!$logs): ?>
                <tr><td colspan="4" class="text-center text-muted">Chưa có lịch sử</td></tr>
            <?php else: ?>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?= date('d/m/Y H:i', strtotime($log['updated_at'])) ?></td>
                        <td><?= htmlspecialchars($log['updater_name'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($log['old_name'] ?? '—') ?> ➝ <?= htmlspecialchars($log['new_name'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($log['noi_dung_edit']) ?></td>
                    </tr>
                <?php endforeach ?>
            <?php endif ?>
        </tbody>
    </table>

    <a href="cum_ho_list.php" class="btn btn-secondary mt-3">← Quay lại danh sách</a>
</div>
<?php include __DIR__ . '/../../../includes/footer.php'; ?>
