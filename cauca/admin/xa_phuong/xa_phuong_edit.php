<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'admin') {
    header('Location: /');
    exit;
}

$id = (int) ($_GET['id'] ?? 0);

// Lấy dữ liệu xã cần sửa
$stmt = $pdo->prepare("SELECT * FROM dm_xa_phuong WHERE id = ?");
$stmt->execute([$id]);
$xa = $stmt->fetch();
if (!$xa) {
    echo "Không tìm thấy xã/phường.";
    exit;
}

// Lấy danh sách tỉnh
$stmt2 = $pdo->query("SELECT id, ten_tinh, ma_tinh FROM dm_tinh ORDER BY ten_tinh ASC");
$tinh_list = $stmt2->fetchAll();

$err_msg = null;
if (isset($_POST['update'])) {
    $tinh_id = (int) $_POST['tinh_id'];
    $ten_xa_phuong = trim($_POST['ten_xa_phuong']);
    $ma_xa_phuong = trim($_POST['ma_xa_phuong']) ?: null;
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Kiểm tra trùng mã (trừ chính nó)
    if ($ma_xa_phuong) {
        $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM dm_xa_phuong WHERE ma_xa_phuong = ? AND id != ?");
        $stmtCheck->execute([$ma_xa_phuong, $id]);
        if ($stmtCheck->fetchColumn() > 0) {
            $err_msg = "❌ Mã xã/phường <strong>$ma_xa_phuong</strong> đã tồn tại!";
        }
    }

    if (!$err_msg && $tinh_id && $ten_xa_phuong) {
        $stmtUpdate = $pdo->prepare("UPDATE dm_xa_phuong SET ten_xa_phuong = ?, ma_xa_phuong = ?, tinh_id = ?, is_active = ? WHERE id = ?");
        $stmtUpdate->execute([$ten_xa_phuong, $ma_xa_phuong, $tinh_id, $is_active, $id]);
        header("Location: xa_phuong_list.php");
        exit;
    }
}
?>

<?php include_once __DIR__ . '/../../../includes/header.php'; ?>
<div class="container mt-4">
    <h4>✏️ Sửa xã / phường</h4>

    <?php if ($err_msg): ?>
        <div class="alert alert-warning"><?= $err_msg ?></div>
    <?php endif; ?>

    <form method="post" class="row g-3 mt-2">
        <div class="col-md-4">
            <label class="form-label">Tên xã / phường</label>
            <input type="text" name="ten_xa_phuong" class="form-control" required
                   value="<?= htmlspecialchars($_POST['ten_xa_phuong'] ?? $xa['ten_xa_phuong']) ?>">
        </div>

        <div class="col-md-3">
            <label class="form-label">Mã xã / phường</label>
            <input type="text" name="ma_xa_phuong" class="form-control"
                   value="<?= htmlspecialchars($_POST['ma_xa_phuong'] ?? $xa['ma_xa_phuong']) ?>">
        </div>

        <div class="col-md-3">
            <label class="form-label">Chọn tỉnh</label>
            <select name="tinh_id" class="form-select" required>
                <?php foreach ($tinh_list as $t): ?>
                    <option value="<?= $t['id'] ?>"
                        <?= ($t['id'] == ($_POST['tinh_id'] ?? $xa['tinh_id'])) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t['ten_tinh']) ?> (<?= $t['ma_tinh'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-2 d-flex align-items-center">
            <div class="form-check mt-4">
                <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                       <?= ($_POST['is_active'] ?? $xa['is_active']) ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_active">Đang hoạt động</label>
            </div>
        </div>

        <div class="col-12">
            <button type="submit" name="update" class="btn btn-success">Cập nhật</button>
            <a href="xa_phuong_list.php" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
</div>
<?php include_once __DIR__ . '/../../../includes/footer.php'; ?>
