<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'admin') {
    header("Location: /");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten = $_POST['ten_hinh_thuc'] ?? '';
    $mo_ta = $_POST['mo_ta'] ?? '';
    $min = (int)($_POST['so_nguoi_min'] ?? 0);
    $max = (int)($_POST['so_nguoi_max'] ?? 0);
    $so_bang = (int)($_POST['so_bang'] ?? 1);
    $so_hiep = (int)($_POST['so_hiep'] ?? 1);
    $nguyen_tac = $_POST['nguyen_tac'] ?? '';

    $stmt = $pdo->prepare("INSERT INTO giai_game_hinh_thuc 
        (ten_hinh_thuc, mo_ta, so_nguoi_min, so_nguoi_max, so_bang, so_hiep, nguyen_tac)
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$ten, $mo_ta, $min, $max, $so_bang, $so_hiep, $nguyen_tac]);

    header("Location: admin_game_hinh_thuc_list.php");
    exit;
}
?>

<?php include_once __DIR__ . '/../../../includes/header.php'; ?>

<div class="container mt-4">
    <h4>Thêm hình thức game mới</h4>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Tên hình thức</label>
            <input type="text" name="ten_hinh_thuc" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="mo_ta" class="form-control" rows="3"></textarea>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Số người tối thiểu</label>
                <input type="number" name="so_nguoi_min" class="form-control" required>
            </div>
            <div class="col">
                <label class="form-label">Số người tối đa</label>
                <input type="number" name="so_nguoi_max" class="form-control" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Số bảng</label>
                <input type="number" name="so_bang" class="form-control" required>
            </div>
            <div class="col">
                <label class="form-label">Số hiệp</label>
                <input type="number" name="so_hiep" class="form-control" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Nguyên tắc</label>
            <input type="text" name="nguyen_tac" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Lưu hình thức</button>
        <a href="admin_game_hinh_thuc_list.php" class="btn btn-secondary ms-2">Quay lại</a>
    </form>
</div>

<?php include_once __DIR__ . '/../../../includes/footer.php'; ?>
