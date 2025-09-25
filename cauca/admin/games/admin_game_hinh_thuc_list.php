<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'admin') {
    header("Location: /");
    exit;
}

$stmt = $pdo->query("SELECT * FROM giai_game_hinh_thuc ORDER BY id DESC");
$list = $stmt->fetchAll();
?>

<?php include_once __DIR__ . '/../../../includes/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>📋 Danh sách hình thức game</h4>
        <a href="admin_game_hinh_thuc_add.php" class="btn btn-primary">+ Thêm hình thức mới</a>
    </div>

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-secondary">
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Mô tả</th>
                <th>Số người</th>
                <th>Bảng</th>
                <th>Hiệp</th>
                <th>Nguyên tắc</th>
                <th>Sửa</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($list as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['ten_hinh_thuc']) ?></td>
                    <td><?= htmlspecialchars($row['mo_ta']) ?></td>
                    <td><?= $row['so_nguoi_min'] ?>–<?= $row['so_nguoi_max'] ?></td>
                    <td><?= $row['so_bang'] ?></td>
                    <td><?= $row['so_hiep'] ?></td>
                    <td><?= htmlspecialchars($row['nguyen_tac']) ?></td>
                    <td>
                        <a href="admin_game_hinh_thuc_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<?php include_once __DIR__ . '/../../../includes/footer.php'; ?>
