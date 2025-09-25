<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'admin') {
    header('Location: /caucavip/dashboard/index.php');
    exit;
}

// Lấy danh sách cụm hồ
$cum_stmt = $pdo->query("SELECT id, ten_cum_ho FROM cum_ho ORDER BY ten_cum_ho");
$cum_list = $cum_stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách loại cá hoạt động
$ca_stmt = $pdo->query("SELECT id, ten_ca FROM loai_ca WHERE trang_thai = 'hoat_dong' ORDER BY ten_ca");
$loai_ca_list = $ca_stmt->fetchAll(PDO::FETCH_ASSOC);

// Xử lý submit
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cum_ho_id = $_POST['cum_ho_id'] ?? '';
    $loai_ca_id = $_POST['loai_ca_id'] ?? null;
    $ten_ho = trim($_POST['ten_ho'] ?? '');
    $dien_tich = $_POST['dien_tich'] ?? 1500;
    $max_chieu_dai_can = $_POST['max_chieu_dai_can'] ?? 540;
    $max_truc_theo = $_POST['max_truc_theo'] ?? 30;
    $so_cho_ngoi = $_POST['so_cho_ngoi'] ?? 20;
    $luong_ca = $_POST['luong_ca'] ?? 0;
    $mo_ta = trim($_POST['mo_ta'] ?? 'mô tả...');
    $cam_moi = trim($_POST['cam_moi'] ?? 'cấm mồi...');
    $status = $_POST['status'] ?? 'dang_hoat_dong';
    $cho_phep_danh_game = isset($_POST['cho_phep_danh_game']) ? 1 : 0;
    $gia_game = $_POST['gia_game'] ?? 10000;

    if ($cum_ho_id === '' || $ten_ho === '') {
        $errors[] = "Vui lòng chọn cụm hồ và nhập tên hồ.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("
            INSERT INTO ho_cau (
                cum_ho_id, loai_ca_id, ten_ho, dien_tich, max_chieu_dai_can, max_truc_theo, 
                mo_ta, cam_moi, status, so_cho_ngoi, luong_ca, cho_phep_danh_game, gia_game
            )
            VALUES (
                :cum_ho_id, :loai_ca_id, :ten_ho, :dien_tich, :max_chieu_dai_can, :max_truc_theo,
                :mo_ta, :cam_moi, :status, :so_cho_ngoi, :luong_ca, :cho_phep_danh_game, :gia_game
            )
        ");
        $stmt->execute([
            ':cum_ho_id' => $cum_ho_id,
            ':loai_ca_id' => $loai_ca_id ?: null,
            ':ten_ho' => $ten_ho,
            ':dien_tich' => $dien_tich,
            ':max_chieu_dai_can' => $max_chieu_dai_can,
            ':max_truc_theo' => $max_truc_theo,
            ':mo_ta' => $mo_ta,
            ':cam_moi' => $cam_moi,
            ':status' => $status,
            ':so_cho_ngoi' => $so_cho_ngoi,
            ':luong_ca' => $luong_ca,
            ':cho_phep_danh_game' => $cho_phep_danh_game,
            ':gia_game' => $cho_phep_danh_game ? $gia_game : 0
        ]);
        header('Location: ho_cau_list.php');
        exit;
    }
}
?>

<?php include __DIR__ . '/../../../includes/header.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="container mt-4">
    <h4>➕ Thêm Hồ Câu</h4>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger"><?= implode('<br>', $errors) ?></div>
    <?php endif; ?>

    <form method="post" class="row g-3 mt-3">
        <div class="col-md-6">
            <label class="form-label">Cụm hồ <span class="text-danger">*</span></label>
            <select name="cum_ho_id" class="form-select select2" required>
                <option value="">-- Chọn cụm hồ --</option>
                <?php foreach ($cum_list as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= ($c['id'] == ($_POST['cum_ho_id'] ?? '')) ? 'selected' : '' ?>>
                        <?= $c['ten_cum_ho'] ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Tên hồ <span class="text-danger">*</span></label>
            <input type="text" name="ten_ho" class="form-control" value="<?= htmlspecialchars($_POST['ten_ho'] ?? '') ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Loại cá</label>
            <select name="loai_ca_id" class="form-select select2">
                <option value="">-- Chọn loại cá --</option>
                <?php foreach ($loai_ca_list as $ca): ?>
                    <option value="<?= $ca['id'] ?>" <?= ($ca['id'] == ($_POST['loai_ca_id'] ?? '')) ? 'selected' : '' ?>>
                        <?= $ca['ten_ca'] ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Lượng cá nền (kg)</label>
            <input type="number" name="luong_ca" class="form-control" value="<?= $_POST['luong_ca'] ?? 2000 ?>">
        </div>

        <div class="col-md-4">
            <label class="form-label">Diện tích (m²)</label>
            <input type="number" name="dien_tich" class="form-control" value="<?= $_POST['dien_tich'] ?? 1500 ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Chiều dài cần max (cm)</label>
            <input type="number" name="max_chieu_dai_can" class="form-control" value="<?= $_POST['max_chieu_dai_can'] ?? 540 ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Số chỗ ngồi</label>
            <input type="number" name="so_cho_ngoi" class="form-control" value="<?= $_POST['so_cho_ngoi'] ?? 30 ?>">
        </div>

        <div class="col-md-6">
            <label class="form-label">Cấm mồi</label>
            <input type="text" name="cam_moi" class="form-control" value="<?= htmlspecialchars($_POST['cam_moi'] ?? 'cấm mồi...') ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Mô tả</label>
            <input type="text" name="mo_ta" class="form-control" value="<?= htmlspecialchars($_POST['mo_ta'] ?? 'mô tả...') ?>">
        </div>

        <div class="col-md-6">
            <label class="form-label">Trạng thái</label>
            <select name="status" class="form-select">
                <option value="dang_hoat_dong" <?= ($_POST['status'] ?? '') == 'dang_hoat_dong' ? 'selected' : '' ?>>Đang hoạt động</option>
                <option value="chua_mo" <?= ($_POST['status'] ?? '') == 'chua_mo' ? 'selected' : '' ?>>Chưa mở</option>
                <option value="chuho_tam_khoa" <?= ($_POST['status'] ?? '') == 'chuho_tam_khoa' ? 'selected' : '' ?>>Chủ hồ khóa</option>
                <option value="admin_tam_khoa" <?= ($_POST['status'] ?? '') == 'admin_tam_khoa' ? 'selected' : '' ?>>Admin khóa</option>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Cho phép đánh game</label>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="game_check" name="cho_phep_danh_game" <?= isset($_POST['cho_phep_danh_game']) ? 'checked' : '' ?>>
                <label class="form-check-label" for="game_check">Bật chế độ đánh game</label>
            </div>
            <input type="number" name="gia_game" class="form-control mt-2" placeholder="Giá game" value="<?= $_POST['gia_game'] ?? 10000 ?>">
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Lưu hồ câu</button>
            <a href="ho_cau_list.php" class="btn btn-secondary">Quay lại</a>
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
