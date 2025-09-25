<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'admin') {
    header("Location: /");
    exit;
}

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM giai_game_hinh_thuc WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    die("Không tìm thấy hình thức game.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten = $_POST['ten_hinh_thuc'] ?? '';
    $mo_ta = $_POST['mo_ta'] ?? '';
    $min = (int)($_POST['so_nguoi_min'] ?? 0);
    $max = (int)($_POST['so_nguoi_max'] ?? 0);
    $so_bang = (int)($_POST['so_bang'] ?? 1);
    $so_hiep = (int)($_POST['so_hiep'] ?? 1);
    $nguyen_tac = $_POST['nguyen_tac'] ?? '';

    $update = $pdo->prepare("UPDATE giai_game_hinh_thuc 
        SET ten_hinh_thuc = ?, mo_ta = ?, so_nguoi_min = ?, so_nguoi_max = ?, so_bang = ?, so_hiep = ?, nguyen_tac = ?
        WHERE id = ?");
    $update->execute([$ten, $mo_ta, $min, $max, $so_bang, $so_hiep, $nguyen_tac, $id]);

    header("Location: admin_game_hinh_thuc_list.php");
    exit;
}
?>

<?php include_once __DIR__ . '/../../../includes/header.php'; ?>

<div class="container mt-4">
    <h4>🛠️ Sửa hình thức game: <?= htmlspecialchars($data['ten_hinh_thuc']) ?></h4>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Tên hình thức</label>
            <input type="text" name="ten_hinh_thuc" class="form-control" value="<?= htmlspecialchars($data['ten_hinh_thuc']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="mo_ta" class="form-control"><?= htmlspecialchars($data['mo_ta']) ?></textarea>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Số người tối thiểu(*không sửa được)</label>
				<input type="number" class="form-control bg-info" value="<?= $data['so_nguoi_min'] ?>" disabled>
				<input type="hidden" name="so_nguoi_min" value="<?= $data['so_nguoi_min'] ?>">
            </div>
            <div class="col">
                <label class="form-label">Số người tối đa</label>
                <input type="number" name="so_nguoi_max" class="form-control" value="<?= $data['so_nguoi_max'] ?>" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Số bảng(*không sửa được)</label>
                <input type="number" class="form-control bg-info" value="<?= $data['so_bang'] ?>" disabled>
				<input type="hidden" name="so_bang" value="<?= $data['so_bang'] ?>">
            </div>
            <div class="col">
                <label class="form-label">Số hiệp(*không sửa được)</label>
				<input type="number" class="form-control bg-info" value="<?= $data['so_hiep'] ?>" disabled>
				<input type="hidden" name="so_hiep" value="<?= $data['so_hiep'] ?>">
            </div>
        </div>
<div class="mb-3">
    <label class="form-label">Nguyên tắc</label>
    <textarea name="nguyen_tac" class="form-control" rows="5"><?= htmlspecialchars($data['nguyen_tac']) ?></textarea>
</div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="admin_game_hinh_thuc_list.php" class="btn btn-secondary ms-2">Quay lại</a>
    </form>
</div>

<?php include_once __DIR__ . '/../../../includes/footer.php'; ?>

// hộp văn bản
<script>
document.addEventListener('DOMContentLoaded', function () {
    const textarea = document.querySelector('textarea[name="nguyen_tac"]');
    textarea.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });
});
</script>
