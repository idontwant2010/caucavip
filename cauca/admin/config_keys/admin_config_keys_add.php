<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'admin') {
    header('Location: /');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $key = trim($_POST['config_key']);
    $value = trim($_POST['config_value']);
    $desc = trim($_POST['description']);

    $stmt = $pdo->prepare("INSERT INTO admin_config_keys (config_key, config_value, description) VALUES (?, ?, ?)");
    $stmt->execute([$key, $value, $desc]);

    header('Location: admin_config_keys_list.php');
    exit;
}
?>

<?php include_once __DIR__ . '/../../../includes/header.php'; ?>

<div class="container mt-4">
    <h4>➕ Thêm biến cấu hình mới</h4>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Tên biến (config_key)</label>
            <input type="text" name="config_key" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Giá trị (config_value)</label>
            <input type="text" name="config_value" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mô tả (description)</label>
            <textarea name="description" class="form-control" rows="2"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Lưu</button>
        <a href="admin_config_keys_list.php" class="btn btn-secondary ms-2">Quay lại</a>
    </form>
</div>

<?php include_once __DIR__ . '/../../../includes/footer.php'; ?>
