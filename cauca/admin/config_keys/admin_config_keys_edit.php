<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'admin') {
    header('Location: /');
    exit;
}

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM admin_config_keys WHERE id = ?");
$stmt->execute([$id]);
$config = $stmt->fetch();

if (!$config) {
    die("Không tìm thấy cấu hình.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $value = trim($_POST['config_value']);
    $desc = trim($_POST['description']);

    $update = $pdo->prepare("UPDATE admin_config_keys SET config_value = ?, description = ? WHERE id = ?");
    $update->execute([$value, $desc, $id]);

    header('Location: admin_config_keys_list.php');
    exit;
}
?>

<?php include_once __DIR__ . '/../../../includes/header.php'; ?>

<div class="container mt-4">
    <h4>✏️ Chỉnh sửa cấu hình: <code><?= htmlspecialchars($config['config_key']) ?></code></h4>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Giá trị (config_value)</label>
            <input type="text" name="config_value" class="form-control" value="<?= htmlspecialchars($config['config_value']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mô tả (description)</label>
            <textarea name="description" class="form-control" rows="2"><?= htmlspecialchars($config['description']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="admin_config_keys_list.php" class="btn btn-secondary ms-2">Quay lại</a>
    </form>
</div>

<?php include_once __DIR__ . '/../../../includes/footer.php'; ?>
