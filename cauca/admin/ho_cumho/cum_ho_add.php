<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'admin') {
    header('Location: /caucavip/dashboard/index.php');
    exit;
}

// Lấy danh sách xã (kèm tỉnh)
$xa_stmt = $pdo->query("
    SELECT xa.id, xa.ten_xa_phuong, xa.ma_xa_phuong, t.ten_tinh
    FROM dm_xa_phuong xa
    JOIN dm_tinh t ON xa.tinh_id = t.id
    WHERE xa.is_active = 1
    ORDER BY xa.ten_xa_phuong
");
$xa_list = $xa_stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách chủ hồ
$chuho_stmt = $pdo->prepare("SELECT id, phone, full_name FROM users WHERE vai_tro = 'chuho'");
$chuho_stmt->execute();
$chuho_list = $chuho_stmt->fetchAll(PDO::FETCH_ASSOC);

// Xử lý submit
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $xa_id = $_POST['xa_id'] ?? '';
    $chu_ho_id = $_POST['chu_ho_id'] ?? '';
    $ten_cum_ho = trim($_POST['ten_cum_ho'] ?? '');
    $dia_chi = trim($_POST['dia_chi'] ?? 'địa chỉ...');
    $google_map_url = trim($_POST['google_map_url'] ?? 'https://maps.google.com');
    $mo_ta = trim($_POST['mo_ta'] ?? 'mô tả...');
    $status = $_POST['status'] ?? 'dang_chay';

    if ($xa_id === '' || $chu_ho_id === '' || $ten_cum_ho === '') {
        $errors[] = "Vui lòng nhập đầy đủ thông tin bắt buộc.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("
            INSERT INTO cum_ho (xa_id, chu_ho_id, ten_cum_ho, dia_chi, google_map_url, mo_ta, status)
            VALUES (:xa_id, :chu_ho_id, :ten_cum_ho, :dia_chi, :google_map_url, :mo_ta, :status)
        ");
        $stmt->execute([
            ':xa_id' => $xa_id,
            ':chu_ho_id' => $chu_ho_id,
            ':ten_cum_ho' => $ten_cum_ho,
            ':dia_chi' => $dia_chi,
            ':google_map_url' => $google_map_url,
            ':mo_ta' => $mo_ta,
            ':status' => $status
        ]);
        header('Location: cum_ho_list.php');
        exit;
    }
}
?>

<?php include __DIR__ . '/../../../includes/header.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="container mt-4">
    <h4>➕ Thêm Cụm Hồ</h4>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger"><?= implode('<br>', $errors) ?></div>
    <?php endif; ?>

    <form method="post" class="row g-3 mt-3">
        <div class="col-md-6">
            <label class="form-label">Xã/Phường <span class="text-danger">*</span></label>
            <select name="xa_id" class="form-select select2" required>
                <option value="">-- Chọn xã --</option>
                <?php foreach ($xa_list as $xa): ?>
                    <option value="<?= $xa['id'] ?>" <?= ($xa['id'] == ($_POST['xa_id'] ?? '')) ? 'selected' : '' ?>>
                        <?= $xa['ten_xa_phuong'] ?> (<?= $xa['ma_xa_phuong'] ?> - tỉnh <?= $xa['ten_tinh'] ?>)
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Chủ hồ <span class="text-danger">*</span></label>
            <select name="chu_ho_id" class="form-select select2" required>
                <option value="">-- Chọn chủ hồ --</option>
                <?php foreach ($chuho_list as $ch): ?>
                    <option value="<?= $ch['id'] ?>" <?= ($ch['id'] == ($_POST['chu_ho_id'] ?? '')) ? 'selected' : '' ?>>
                        <?= $ch['phone'] ?> - <?= $ch['full_name'] ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Tên cụm hồ <span class="text-danger">*</span></label>
            <input type="text" name="ten_cum_ho" class="form-control" value="<?= htmlspecialchars($_POST['ten_cum_ho'] ?? '') ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Địa chỉ</label>
            <input type="text" name="dia_chi" class="form-control" value="<?= htmlspecialchars($_POST['dia_chi'] ?? 'địa chỉ...') ?>">
        </div>

        <div class="col-md-6">
            <label class="form-label">Google Map URL</label>
            <input type="text" name="google_map_url" class="form-control" value="<?= htmlspecialchars($_POST['google_map_url'] ?? 'https://maps.google.com') ?>">
        </div>

        <div class="col-md-6">
            <label class="form-label">Mô tả</label>
            <input type="text" name="mo_ta" class="form-control" value="<?= htmlspecialchars($_POST['mo_ta'] ?? 'mô tả...') ?>">
        </div>

        <div class="col-md-6">
            <label class="form-label">Trạng thái</label>
            <select name="status" class="form-select">
                <option value="dang_chay" <?= ($_POST['status'] ?? '') === 'dang_chay' ? 'selected' : '' ?>>Đang chạy</option>
                <option value="chuho_tam_khoa" <?= ($_POST['status'] ?? '') === 'chuho_tam_khoa' ? 'selected' : '' ?>>Chủ hồ khóa</option>
                <option value="admin_tam_khoa" <?= ($_POST['status'] ?? '') === 'admin_tam_khoa' ? 'selected' : '' ?>>Admin khóa</option>
            </select>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Lưu cụm hồ</button>
            <a href="cum_ho_list.php" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
</div>

<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2({ width: '100%' });
    });
</script>
<?php include __DIR__ . '/../../../includes/footer.php'; ?>
