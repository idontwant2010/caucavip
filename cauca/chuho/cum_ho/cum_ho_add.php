<?php
require_once '../../../connect.php';
require_once '../../../check_login.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['vai_tro'] !== 'chuho') {
    header("Location: ../../../no_permission.php");
    exit;
}

$chu_ho_id = $_SESSION['user']['id'];

// Đếm số cụm hồ đã có
$stmtCount = $pdo->prepare("SELECT COUNT(*) FROM cum_ho WHERE chu_ho_id = :chu_ho_id");
$stmtCount->execute(['chu_ho_id' => $chu_ho_id]);
$so_cum_ho = $stmtCount->fetchColumn();

// Nếu vượt giới hạn thì không cho thêm
if ($so_cum_ho >= 1) {
    echo "<div class='alert alert-danger text-center'>Bản miễn phí cho phép bạn tạo 1 cụm hồ. Vui lòng liện hệ admin để thêm nhìu cụm hồ vào danh sách</div>";
    echo '<div class="text-center"><a href="cum_ho_list.php" class="btn btn-secondary mt-3">Quay lại danh sách</a></div>';
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ten_cum_ho = $_POST['ten_cum_ho'];
    $xa_id = $_POST['xa_id'];
    $dia_chi = $_POST['dia_chi'];
    $google_map_url = $_POST['google_map_url'];
    $mo_ta = $_POST['mo_ta'];

    $stmtInsert = $pdo->prepare("INSERT INTO cum_ho (xa_id, chu_ho_id, ten_cum_ho, dia_chi, google_map_url, mo_ta, status) 
                                 VALUES (:xa_id, :chu_ho_id, :ten_cum_ho, :dia_chi, :google_map_url, :mo_ta, 'dang_chay')");
    $stmtInsert->execute([
        'xa_id' => $xa_id,
        'chu_ho_id' => $chu_ho_id,
        'ten_cum_ho' => $ten_cum_ho,
        'dia_chi' => $dia_chi,
        'google_map_url' => $google_map_url,
        'mo_ta' => $mo_ta
    ]);

    header("Location: cum_ho_list.php");
    exit;
}

// Lấy danh sách tỉnh
$stmtTinh = $pdo->query("SELECT id, ten_tinh FROM dm_tinh ORDER BY ten_tinh ASC");
$ds_tinh = $stmtTinh->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Thêm cụm hồ</title>
    <link rel="stylesheet" href="../../../assets/css/bootstrap.min.css">
</head>
<body>
<?php include '../../../includes/header.php'; ?>

<script>
document.getElementById('tinh_id').addEventListener('change', function () {
    const tinhId = this.value;
    const xaSelect = document.getElementById('xa_id');

    // Hiện loading trong dropdown
    xaSelect.innerHTML = '<option>Đang tải...</option>';

    fetch('get_xa_by_tinh.php?tinh_id=' + tinhId)
        .then(response => response.json())
        .then(data => {
            xaSelect.innerHTML = '<option value="">-- Chọn xã --</option>';
            data.forEach(function (xa) {
                let option = document.createElement('option');
                option.value = xa.id;
                option.textContent = xa.ten_xa_phuong;
                xaSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Lỗi khi tải danh sách xã:', error);
            xaSelect.innerHTML = '<option value="">-- Lỗi tải xã --</option>';
        });
});
</script>

<div class="container mt-4">
    <h2>Thêm cụm hồ mới</h2>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Tên cụm hồ</label>
            <input type="text" name="ten_cum_ho" class="form-control" required>
        </div>
        <div class="mb-3">
    <label class="form-label">Tỉnh</label>
    <select name="tinh_id" id="tinh_id" class="form-select" required>
        <option value="">-- Chọn tỉnh --</option>
        <?php foreach ($ds_tinh as $tinh): ?>
            <option value="<?= $tinh['id'] ?>"><?= htmlspecialchars($tinh['ten_tinh']) ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Xã</label>
    <select name="xa_id" id="xa_id" class="form-select" required>
        <option value="">-- Chọn xã --</option>
    </select>
</div>
        <div class="mb-3">
            <label class="form-label">Địa chỉ</label>
            <input type="text" name="dia_chi" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Google Map URL</label>
            <input type="text" name="google_map_url" class="form-control" value="https://www.google.com/maps">
        </div>
        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="mo_ta" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Thêm</button>
        <a href="cum_ho_list.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div>

<?php include '../../../includes/footer.php'; ?>
<script src="../../../assets/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('tinh_id').addEventListener('change', function () {
    const tinhId = this.value;
    const xaSelect = document.getElementById('xa_id');

    xaSelect.innerHTML = '<option value="">Đang tải...</option>';

    fetch('get_xa_by_tinh.php?tinh_id=' + tinhId)
        .then(response => response.json())
        .then(data => {
            xaSelect.innerHTML = '<option value="">-- Chọn xã --</option>';
            data.forEach(function (xa) {
                const opt = document.createElement('option');
                opt.value = xa.id;
                opt.textContent = xa.ten_xa_phuong;
                xaSelect.appendChild(opt);
            });
        })
        .catch(error => {
            console.error('Lỗi khi tải xã:', error);
            xaSelect.innerHTML = '<option value="">-- Lỗi tải xã --</option>';
        });
});
</script>

</body>
</html>
