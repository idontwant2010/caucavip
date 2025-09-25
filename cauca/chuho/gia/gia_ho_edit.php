<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['vai_tro'] !== 'chuho') {
    header("Location: /caucavip/no_permission.php");
    exit();
}

require_once __DIR__ . '/../../../includes/header.php';

$id = $_GET['id'] ?? 0;

// Lấy thông tin bảng giá
$stmt = $pdo->prepare("SELECT g.*, h.ten_ho FROM gia_ca_thit_phut g JOIN ho_cau h ON g.ho_cau_id = h.id WHERE g.id = ?");
$stmt->execute([$id]);
$banggia = $stmt->fetch();

if (!$banggia) {
    echo '<div class="alert alert-danger">Không tìm thấy bảng giá.</div>';
    require_once __DIR__ . '/../../../includes/footer.php';
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $stmt_update = $pdo->prepare("
            UPDATE gia_ca_thit_phut SET
                base_duration = :base_duration,
                base_price = :base_price,
                extra_unit_price = :extra_unit_price,
                discount_2x_duration = :discount_2x_duration,
                discount_3x_duration = :discount_3x_duration,
                discount_4x_duration = :discount_4x_duration,
                gia_ban_ca = :gia_ban_ca,
                gia_thu_lai = :gia_thu_lai,
                loai_thu = :loai_thu,
                status = :status,
                ghi_chu = :ghi_chu,
                updated_at = NOW()
            WHERE id = :id
        ");
        $stmt_update->execute([
            ':base_duration' => $_POST['base_duration'],
            ':base_price' => $_POST['base_price'],
            ':extra_unit_price' => $_POST['extra_unit_price'],
            ':discount_2x_duration' => $_POST['discount_2x_duration'],
            ':discount_3x_duration' => $_POST['discount_3x_duration'],
            ':discount_4x_duration' => $_POST['discount_4x_duration'],
            ':gia_ban_ca' => $_POST['gia_ban_ca'],
            ':gia_thu_lai' => $_POST['gia_thu_lai'],
            ':loai_thu' => $_POST['loai_thu'],
            ':status' => $_POST['status'],
            ':ghi_chu' => $_POST['ghi_chu'],
            ':id' => $id
        ]);

        header("Location: gia_ho_list.php");
        exit;
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
    }
}
?>

<div class="container mt-4">
    <h3 class="mb-4">✏️ Chỉnh sửa bảng giá: <?= htmlspecialchars($banggia['ten_bang_gia']) ?> - Hồ <?= htmlspecialchars($banggia['ten_ho']) ?></h3>
    <form method="POST">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Hồ câu</label>
                <select class="form-select" disabled>
                    <option><?= htmlspecialchars($banggia['ten_ho']) ?></option>
                </select>
                <input type="hidden" name="ho_cau_id" value="<?= $banggia['ho_cau_id'] ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">Tên bảng giá</label>
                <select class="form-select" disabled>
                    <option><?= htmlspecialchars($banggia['ten_bang_gia']) ?></option>
                </select>
                <input type="hidden" name="ten_bang_gia" value="<?= $banggia['ten_bang_gia'] ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">Thời gian phút / suất cơ bản (*240 phút)</label>
                <input type="number" name="base_duration" class="form-control" value="<?= $banggia['base_duration'] ?>" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Giá 1 suất câu (*240.000 vnd)</label>
                <input type="number" name="base_price" class="form-control" value="<?= $banggia['base_price'] ?>" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Giá thêm vnd/phút (*1.000 vnd)</label>
                <input type="number" name="extra_unit_price" class="form-control" value="<?= $banggia['extra_unit_price'] ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">Giảm giá 2-suất (*20.000 vnd)</label>
                <input type="number" name="discount_2x_duration" class="form-control" value="<?= $banggia['discount_2x_duration'] ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">Giảm giá 3-suất (*40.000 vnd)</label>
                <input type="number" name="discount_3x_duration" class="form-control" value="<?= $banggia['discount_3x_duration'] ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">Giảm giá 4-suất (*60.000 vnd)</label>
                <input type="number" name="discount_4x_duration" class="form-control" value="<?= $banggia['discount_4x_duration'] ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">Giá bán cá (*60.000 vnd)</label>
                <input type="number" name="gia_ban_ca" class="form-control" value="<?= $banggia['gia_ban_ca'] ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">Giá thu lại(*15.000 vnd)</label>
                <input type="number" name="gia_thu_lai" class="form-control" value="<?= $banggia['gia_thu_lai'] ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">Đơn vị thu (*kg hoặc con)</label>
                <select name="loai_thu" class="form-select">
                    <option value="kg" <?= $banggia['loai_thu'] === 'kg' ? 'selected' : '' ?>>Kg</option>
                    <option value="con" <?= $banggia['loai_thu'] === 'con' ? 'selected' : '' ?>>Con</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="open" <?= $banggia['status'] === 'open' ? 'selected' : '' ?>>Đang mở</option>
                    <option value="closed" <?= $banggia['status'] === 'closed' ? 'selected' : '' ?>>Đã đóng</option>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label">Ghi chú</label>
                <textarea name="ghi_chu" class="form-control"><?= htmlspecialchars($banggia['ghi_chu']) ?></textarea>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">Lưu thay đổi</button>
            <a href="gia_ho_list.php" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
