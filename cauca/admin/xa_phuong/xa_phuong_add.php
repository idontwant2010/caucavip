<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'admin') {
    header('Location: /caucavip/dashboard/index.php');
    exit;
}

// Lấy danh sách tỉnh
$stmt = $pdo->query("SELECT id, ma_tinh, ten_tinh FROM dm_tinh ORDER BY ten_tinh ASC");
$tinh_list = $stmt->fetchAll();

// Xử lý khi submit
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tinh_id = intval($_POST['tinh_id']);
    $ten_xa_phuong = trim($_POST['ten_xa_phuong']);
    $ma_xa = trim($_POST['ma_xa_phuong']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($ten_xa_phuong === '' || $ma_xa === '' || $tinh_id <= 0) {
        $errors[] = 'Vui lòng điền đầy đủ thông tin.';
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM dm_xa_phuong WHERE ma_xa_phuong = ?");
        $stmt->execute([$ma_xa]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = 'Mã xã/phường đã tồn tại.';
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO dm_xa_phuong (ma_xa_phuong, ten_xa_phuong, tinh_id, is_active)
                               VALUES (?, ?, ?, ?)");
        $stmt->execute([$ma_xa, $ten_xa_phuong, $tinh_id, $is_active]);
        header('Location: xa_phuong_list.php');
        exit;
    }
}
?>

<?php include_once __DIR__ . '/../../../includes/header.php'; ?>
<div class="container mt-4">
    <h3 class="mb-3">➕ Thêm xã/phường mới</h3>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST">
        <!-- Bước 1: Chọn tỉnh -->
        <div class="mb-3">
            <label for="tinh_id" class="form-label">Chọn tỉnh (mã - tên)</label>
            <select name="tinh_id" id="tinh_id" class="form-select" required>
                <option value="">-- Chọn tỉnh --</option>
                <?php foreach ($tinh_list as $t): ?>
                    <option value="<?= $t['id'] ?>">
                        <?= htmlspecialchars($t['ten_tinh'].' - ' . $t['ma_tinh']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Bước 2: Tên xã/phường -->
        <div class="mb-3">
            <label for="ten_xa_phuong" class="form-label">Tên xã/phường</label>
            <input type="text" name="ten_xa_phuong" id="ten_xa_phuong" class="form-control" required>
        </div>

        <!-- Bước 3: Mã xã/phường -->
        <div class="mb-3">
            <label for="ma_xa_phuong" class="form-label">Mã xã/phường (ví dụ: 72001)</label>
            <input type="text" name="ma_xa_phuong" id="ma_xa_phuong" class="form-control" required>
        </div>

        <!-- Bước 4: Trạng thái -->
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
            <label class="form-check-label" for="is_active">Kích hoạt</label>
        </div>

        <!-- Bước 5: Submit -->
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="xa_phuong_list.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
<?php include_once __DIR__ . '/../../../includes/footer.php'; ?>
