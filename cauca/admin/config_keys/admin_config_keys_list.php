<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'admin') {
    header("Location: /");
    exit;
}

$stmt = $pdo->query("SELECT * FROM admin_config_keys ORDER BY config_key ASC");
$configs = $stmt->fetchAll();
?>

<?php include_once __DIR__ . '/../../../includes/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>🔧 Danh sách cấu hình hệ thống</h4>
        <a href="admin_config_keys_add.php" class="btn btn-primary">+ Thêm cấu hình</a>
    </div>

    <table class="table table-bordered table-striped table-hover">
        <thead class="table-secondary">
            <tr>
                <th>ID</th>
                <th>Key</th>
                <th>Giá trị</th>
                <th>Ghi chú</th>
                <th>Cập nhật</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($configs as $config): ?>
                <tr>
                    <td><?= $config['id'] ?></td>
                    <td><code><?= htmlspecialchars($config['config_key']) ?></code></td>
                    <td><?= htmlspecialchars($config['config_value']) ?></td>
                    <td><?= htmlspecialchars($config['description']) ?></td>
                    <td>
                        <a href="admin_config_keys_edit.php?id=<?= $config['id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<?php include_once __DIR__ . '/../../../includes/footer.php'; ?>
