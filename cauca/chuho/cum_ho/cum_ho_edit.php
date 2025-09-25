<?php
require_once '../../../connect.php';
require_once '../../../check_login.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['vai_tro'] !== 'chuho') {
    header("Location: ../../../no_permission.php");
    exit;
}

$chu_ho_id = $_SESSION['user']['id'];
$id = $_GET['id'] ?? 0;

// Lấy cụm hồ cần sửa
$stmt = $pdo->prepare("SELECT cum_ho.*, dm_xa_phuong.tinh_id 
                       FROM cum_ho 
                       JOIN dm_xa_phuong ON cum_ho.xa_id = dm_xa_phuong.id 
                       WHERE cum_ho.id = :id AND cum_ho.chu_ho_id = :chu_ho_id");
$stmt->execute(['id' => $id, 'chu_ho_id' => $chu_ho_id]);
$cum_ho = $stmt->fetch();

if (!$cum_ho) {
    echo "<div class='alert alert-danger'>Không tìm thấy cụm hồ.</div>";
    exit;
}

// Lấy danh sách tỉnh
$stmtTinh = $pdo->query("SELECT id, ten_tinh FROM dm_tinh ORDER BY ten_tinh ASC");
$ds_tinh = $stmtTinh->fetchAll();

// Lấy danh sách xã theo tỉnh hiện tại
$stmtXa = $pdo->prepare("SELECT id, ten_xa_phuong FROM dm_xa_phuong WHERE tinh_id = :tinh_id ORDER BY ten_xa_phuong ASC");
$stmtXa->execute(['tinh_id' => $cum_ho['tinh_id']]);
$ds_xa = $stmtXa->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ten_cum_ho = $_POST['ten_cum_ho'];
    $xa_id = $_POST['xa_id'];
    $dia_chi = $_POST['dia_chi'];
    $google_map_url = $_POST['google_map_url'];
    $mo_ta = $_POST['mo_ta'];
    $status = $_POST['status'];

    $stmtUpdate = $pdo->prepare("UPDATE cum_ho SET ten_cum_ho = :ten_cum_ho, xa_id = :xa_id, dia_chi = :dia_chi,
        google_map_url = :google_map_url, mo_ta = :mo_ta, status = :status
        WHERE id = :id AND chu_ho_id = :chu_ho_id");
    $stmtUpdate->execute([
        'ten_cum_ho' => $ten_cum_ho,
        'xa_id' => $xa_id,
        'dia_chi' => $dia_chi,
        'google_map_url' => $google_map_url,
        'mo_ta' => $mo_ta,
        'status' => $status,
        'id' => $id,
        'chu_ho_id' => $chu_ho_id
    ]);

    header("Location: cum_ho_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sửa cụm hồ</title>
    <link rel="stylesheet" href="../../../assets/css/bootstrap.min.css">
</head>
<body>
<?php include '../../../includes/header.php'; ?>

<div class="container mt-4">
    <h2>Sửa thông tin cụm hồ</h2>
    <form method="post">
	
<div class="card mt-4">
	  <div class="card-header">
		<strong>Điền thông tin chính xác để cần thủ dể tiếp cận</strong>
	  </div>
	<div class="card-body">
	  <div class="row g-3">	
	  
        <div class="col-md-6">
            <label class="form-label">Tên cụm hồ </label>
            <input type="text" name="ten_cum_ho" class="form-control" value="<?= htmlspecialchars($cum_ho['ten_cum_ho']) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Địa chỉ</label>
            <input type="text" name="dia_chi" class="form-control" value="<?= htmlspecialchars($cum_ho['dia_chi']) ?>">
        </div>
		
        <div class="col-md-4">
            <label class="form-label">Chọn Tỉnh/Thành Phố</label>
            <select name="tinh_id" id="tinh_id" class="form-select" required>
                <option value="">-- Chọn tỉnh --</option>
                <?php foreach ($ds_tinh as $tinh): ?>
                    <option value="<?= $tinh['id'] ?>" <?= ($tinh['id'] == $cum_ho['tinh_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tinh['ten_tinh']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
		
        <div class="col-md-4">
            <label class="form-label">Chọn Xã / Phường</label>
            <select name="xa_id" id="xa_id" class="form-select" required>
                <option value="">-- Chọn xã --</option>
                <?php foreach ($ds_xa as $xa): ?>
                    <option value="<?= $xa['id'] ?>" <?= ($xa['id'] == $cum_ho['xa_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($xa['ten_xa_phuong']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Trạng thái</label>
            <select name="status" class="form-select">
                <option value="dang_chay" <?= ($cum_ho['status'] == 'dang_chay') ? 'selected' : '' ?>>Đang hoạt động</option>
                <option value="admin_tam_khoa" <?= ($cum_ho['status'] == 'admin_tam_khoa') ? 'selected' : '' ?>>Admin tạm khoá</option>
                <option value="chuho_tam_khoa" <?= ($cum_ho['status'] == 'chuho_tam_khoa') ? 'selected' : '' ?>>Chủ hồ tạm khoá</option>
            </select>
        </div>
		        <div class="col-md-6">
            <label class="form-label">Link bản đồ Google Maps</label>
            <input type="text" name="google_map_url" class="form-control" value="<?= htmlspecialchars($cum_ho['google_map_url']) ?>">
        </div>
		<div class="col-md-6">
            <label class="form-label">Mô tả: đường đi, chổ đậu ô tô...</label>
            <textarea name="mo_ta" class="form-control" rows="2"><?= htmlspecialchars($cum_ho['mo_ta']) ?></textarea>
        </div>

	  </div>
	</div>
</div>
        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        <a href="cum_ho_list.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div>

<?php include '../../../includes/footer.php'; ?>

<script>
document.getElementById('tinh_id').addEventListener('change', function () {
    const tinhId = this.value;
    const xaSelect = document.getElementById('xa_id');
    xaSelect.innerHTML = '<option>Đang tải...</option>';

    fetch('get_xa_by_tinh.php?tinh_id=' + tinhId)
        .then(res => res.json())
        .then(data => {
            xaSelect.innerHTML = '<option value="">-- Chọn xã --</option>';
            data.forEach(function (xa) {
                const opt = document.createElement('option');
                opt.value = xa.id;
                opt.textContent = xa.ten_xa_phuong;
                xaSelect.appendChild(opt);
            });
        })
        .catch(() => {
            xaSelect.innerHTML = '<option value="">-- Lỗi tải xã --</option>';
        });
});
</script>

<script src="../../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
