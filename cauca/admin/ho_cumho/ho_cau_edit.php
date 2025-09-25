<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'admin') {
    header('Location: /caucavip/dashboard/index.php');
    exit;
}

// Lấy ID hồ cần sửa
$id = $_GET['id'] ?? '';
if (!$id || !is_numeric($id)) {
    header('Location: ho_cau_list.php');
    exit;
}

// Lấy dữ liệu hồ câu
$stmt = $pdo->prepare("SELECT * FROM ho_cau WHERE id = :id");
$stmt->execute([':id' => $id]);
$ho = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$ho) {
    header('Location: ho_cau_list.php');
    exit;
}

// Danh sách cụm hồ
$cum_stmt = $pdo->query("SELECT id, ten_cum_ho FROM cum_ho ORDER BY ten_cum_ho");
$cum_list = $cum_stmt->fetchAll(PDO::FETCH_ASSOC);

// Danh sách loại cá
$ca_stmt = $pdo->query("SELECT id, ten_ca FROM loai_ca WHERE trang_thai = 'hoat_dong' ORDER BY ten_ca");
$loai_ca_list = $ca_stmt->fetchAll(PDO::FETCH_ASSOC);

// Xử lý cập nhật
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
            UPDATE ho_cau SET
                cum_ho_id = :cum_ho_id,
                loai_ca_id = :loai_ca_id,
                ten_ho = :ten_ho,
                dien_tich = :dien_tich,
                max_chieu_dai_can = :max_chieu_dai_can,
                max_truc_theo = :max_truc_theo,
                mo_ta = :mo_ta,
                cam_moi = :cam_moi,
                status = :status,
                so_cho_ngoi = :so_cho_ngoi,
                luong_ca = :luong_ca,
                cho_phep_danh_game = :cho_phep_danh_game,
                gia_game = :gia_game
            WHERE id = :id
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
            ':gia_game' => $cho_phep_danh_game ? $gia_game : 0,
            ':id' => $id
        ]);
        header('Location: ho_cau_list.php');
        exit;
    }
}
?>

<?php include __DIR__ . '/../../../includes/header.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="container mt-4">
    <h4>✏️ Chỉnh sửa Hồ Câu</h4>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger"><?= implode('<br>', $errors) ?></div>
    <?php endif; ?>

    <form method="post" class="row g-3 mt-3">
        <div class="col-md-6">
            <label class="form-label">Cụm hồ</label>
            <select name="cum_ho_id" class="form-select select2" required>
                <?php foreach ($cum_list as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $c['id'] == $ho['cum_ho_id'] ? 'selected' : '' ?>>
                        <?= $c['ten_cum_ho'] ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Tên hồ</label>
            <input type="text" name="ten_ho" class="form-control" value="<?= htmlspecialchars($ho['ten_ho']) ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Loại cá</label>
            <select name="loai_ca_id" class="form-select select2">
                <option value="">-- Chọn loại cá --</option>
                <?php foreach ($loai_ca_list as $ca): ?>
                    <option value="<?= $ca['id'] ?>" <?= $ca['id'] == $ho['loai_ca_id'] ? 'selected' : '' ?>>
                        <?= $ca['ten_ca'] ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Lượng cá (kg)</label>
            <input type="number" name="luong_ca" class="form-control" value="<?= $ho['luong_ca'] ?>">
        </div>

        <div class="col-md-4">
            <label class="form-label">Diện tích</label>
            <input type="number" name="dien_tich" class="form-control" value="<?= $ho['dien_tich'] ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Chiều dài cần max</label>
            <input type="number" name="max_chieu_dai_can" class="form-control" value="<?= $ho['max_chieu_dai_can'] ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Số chỗ ngồi</label>
            <input type="number" name="so_cho_ngoi" class="form-control" value="<?= $ho['so_cho_ngoi'] ?>">
        </div>

        <div class="col-md-6">
            <label class="form-label">Cấm mồi</label>
            <input type="text" name="cam_moi" class="form-control" value="<?= htmlspecialchars($ho['cam_moi']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Mô tả</label>
            <input type="text" name="mo_ta" class="form-control" value="<?= htmlspecialchars($ho['mo_ta']) ?>">
        </div>

        <div class="col-md-6">
            <label class="form-label">Trạng thái</label>
            <select name="status" class="form-select">
                <?php
                $status_list = ['dang_hoat_dong' => 'Đang hoạt động', 'chua_mo' => 'Chưa mở', 'chuho_tam_khoa' => 'Chủ hồ khóa', 'admin_tam_khoa' => 'Admin khóa'];
                foreach ($status_list as $key => $label) {
                    echo "<option value='$key'" . ($ho['status'] === $key ? ' selected' : '') . ">$label</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Cho phép đánh game</label>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="game_check" name="cho_phep_danh_game" <?= $ho['cho_phep_danh_game'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="game_check">Bật chế độ đánh game</label>
            </div>
            <input type="number" name="gia_game" class="form-control mt-2" placeholder="Giá game" value="<?= $ho['gia_game'] ?>">
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
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
