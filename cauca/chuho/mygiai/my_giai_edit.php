<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'chuho') {
  echo "Truy cập bị từ chối.";
  exit;
}

$giai_id = (int)($_GET['id'] ?? 0);
$user_id = $_SESSION['user']['id'];

$stmt = $pdo->prepare("SELECT * FROM giai_list WHERE id = ? AND creator_id = ?");
$stmt->execute([$giai_id, $user_id]);
$giai = $stmt->fetch();

// Lấy thêm thông tin hồ câu
$ho_cau_info = $pdo->prepare("SELECT h.ten_ho, h.so_cho_ngoi, h.gia_giai, h.gia_game, h.cho_phep_danh_thit FROM ho_cau h WHERE h.id = ?");
$ho_cau_info->execute([$giai['ho_cau_id']]);
$ho = $ho_cau_info->fetch();

if (!$giai || $giai['status'] !== 'dang_cho_xac_nhan') {
  echo "Giải không tồn tại hoặc không thể chỉnh sửa.";
  exit;
}

// Xử lý lưu form
function validate_date($ngay_to_chuc)
{
  return strtotime($ngay_to_chuc) >= strtotime(date('Y-m-d', strtotime('+7 day')));
}

$ngay_hien_tai = new DateTime();
$ngay_hien_tai->modify('+7 day');
$ngay_gioi_han = $ngay_hien_tai->format('d/m/Y');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $errors = [];
  $ten_giai = $_POST['ten_giai'];
  $ngay_to_chuc = $_POST['ngay_to_chuc'];
  $gio_bat_dau = $_POST['gio_bat_dau'];
  $so_luong_can_thu = (int)$_POST['so_luong_can_thu'];
  $thoi_luong_phut_hiep = (int)$_POST['thoi_luong_phut_hiep'];
  $tien_cuoc = (int)$_POST['tien_cuoc'];
  $hinh_thuc_id = (int)$_POST['hinh_thuc_id'];
  $luat_choi = $_POST['luat_choi'];

  // Kiểm tra lỗi nhập liệu
  if (!validate_date($ngay_to_chuc)) {
    $errors[] = "Ngày tổ chức phải sau ngày hôm nay ít nhất 7 ngày. Ngày tổ chức phải từ ' $ngay_gioi_han ' trở đi.";
  }
  if ($so_luong_can_thu > (int)$ho['so_cho_ngoi']) {
    $errors[] = "Số cần thủ không được vượt quá số chỗ ngồi của hồ.";
  }

  // Kiểm tra số cần thủ tối thiểu theo hình thức
  $min_required = $pdo->query("SELECT so_nguoi_min FROM giai_game_hinh_thuc WHERE id = $hinh_thuc_id")->fetchColumn();
  if ($so_luong_can_thu < (int)$min_required) {
    $errors[] = "Số cần thủ phải từ tối thiểu $min_required người theo hình thức giải đã chọn.";
  }

  if (!empty($errors)) {
    echo "<div class='container mt-4 alert alert-danger'>";
    echo "<strong>Lỗi:</strong><ul>";
    foreach ($errors as $err) echo "<li>" . htmlspecialchars($err) . "</li>";
    echo "</ul></div>";
  } else {
    $stmt = $pdo->prepare("UPDATE giai_list SET ten_giai = ?, ngay_to_chuc = ?, gio_bat_dau = ?, so_luong_can_thu = ?, thoi_luong_phut_hiep = ?, tien_cuoc = ?, hinh_thuc_id = ?, luat_choi = ? WHERE id = ? AND creator_id = ?");
    $stmt->execute([$ten_giai, $ngay_to_chuc, $gio_bat_dau, $so_luong_can_thu, $thoi_luong_phut_hiep, $tien_cuoc, $hinh_thuc_id, $luat_choi, $giai_id, $user_id]);

    // Lấy số hiệp mới
    $so_hiep_moi = (int)$pdo->query("SELECT so_hiep FROM giai_game_hinh_thuc WHERE id = $hinh_thuc_id")->fetchColumn();

    // Tính lại phi_giai
    $ho_cau = $pdo->query("SELECT gia_giai FROM ho_cau WHERE id = " . (int)$giai['ho_cau_id'])->fetchColumn();
    $fee_user = $pdo->query("SELECT config_value FROM admin_config_keys WHERE config_key = 'giai_fee_user'")->fetchColumn();
    $vat_percent = $pdo->query("SELECT config_value FROM admin_config_keys WHERE config_key = 'giai_vat_percent'")->fetchColumn();

    $phi_1_nguoi_1_hiep = ($ho_cau + $fee_user + ($ho_cau + $fee_user) * $vat_percent / 100) * ($thoi_luong_phut_hiep / 60);
    $phi_giai_moi = round($so_luong_can_thu * $so_hiep_moi * $phi_1_nguoi_1_hiep);

    $pdo->prepare("UPDATE giai_list SET phi_giai = ?, so_hiep = ? WHERE id = ?")->execute([$phi_giai_moi, $so_hiep_moi, $giai_id]);

    // Hiển thị kết quả tính phí trước khi redirect
    echo "<div class='container mt-4 alert alert-success'>";
    echo "✅ Đã cập nhật thông tin giải.";
    echo "<br>💰 Phí tổ chức mới: <strong>" . number_format($phi_giai_moi) . "đ</strong>";
    echo "<br>💰 Ngày tổ chức mới: <strong>" . $ngay_to_chuc . "</strong>";
    echo "<br>💰 Số lượng cần thủ: <strong>" . $so_luong_can_thu . "</strong>";
    echo "<br><a href='my_giai_detail.php?id=$giai_id' class='btn btn-sm btn-primary mt-3'>➡️ Tiếp tục</a>";
    echo "</div>";
    exit;
  }
}

// Load danh sách hình thức
$ds_hinh_thuc = $pdo->query("SELECT id, ten_hinh_thuc, so_hiep FROM giai_game_hinh_thuc WHERE hinh_thuc = 'giai'")->fetchAll();
?>
<?php include_once '../../../includes/header.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container py-4">
  <div class="alert alert-light border mb-4">
    🏞 <strong>Tên hồ:</strong> <?= htmlspecialchars($ho['ten_ho']) ?> |
    ✅ <strong>Số chỗ ngồi:</strong> <?= $ho['so_cho_ngoi'] ?> |
    🎯 <strong>Giá giải:</strong> <?= number_format($ho['gia_giai']) ?>đ |
    🕹 <strong>Giá game:</strong> <?= number_format($ho['gia_game']) ?>đ |
    🐟 <strong>Cá thịt:</strong> <?= $ho['cho_phep_danh_thit'] ? 'Có' : 'Không' ?>
  </div>
  <h4 class="mb-4">✏️ Chỉnh sửa thông tin giải</h4>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">Tên giải</label>
      <input type="text" name="ten_giai" class="form-control" value="<?= htmlspecialchars($giai['ten_giai']) ?>" required>
    </div>
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Ngày tổ chức</label>
        <input type="date" name="ngay_to_chuc" class="form-control" value="<?= $giai['ngay_to_chuc'] ?>" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Giờ bắt đầu</label>
        <input type="time" name="gio_bat_dau" class="form-control" value="<?= $giai['gio_bat_dau'] ?>" required>
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label">Số lượng cần thủ</label>
      <input type="number" name="so_luong_can_thu" class="form-control" value="<?= $giai['so_luong_can_thu'] ?>" min="2" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Thời lượng 1 hiệp (phút)</label>
      <select name="thoi_luong_phut_hiep" class="form-select" required>
        <?php
        $options = [45, 60, 75, 90, 120, 150, 180, 240, 300, 360];
        foreach ($options as $opt):
        ?>
          <option value="<?= $opt ?>" <?= $opt == $giai['thoi_luong_phut_hiep'] ? 'selected' : '' ?>><?= $opt ?> phút</option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Tiền cược (đồng)</label>
      <input type="number" name="tien_cuoc" class="form-control" value="<?= $giai['tien_cuoc'] ?>" min="0">
    </div>
    <div class="mb-3">
      <label class="form-label">Hình thức giải</label>
      <select name="hinh_thuc_id" id="hinh_thuc_id" class="form-select" required>
        <?php foreach ($ds_hinh_thuc as $ht): ?>
          <option value="<?= $ht['id'] ?>" <?= $ht['id'] == $giai['hinh_thuc_id'] ? 'selected' : '' ?>><?= htmlspecialchars($ht['ten_hinh_thuc']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Luật chơi</label>
      <textarea name="luat_choi" class="form-control" rows="3"><?= htmlspecialchars($giai['luat_choi']) ?></textarea>
    </div>
    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-primary">💾 Lưu thay đổi</button>
      <a href="my_giai_detail.php?id=<?= $giai_id ?>" class="btn btn-secondary">🔙 Quay lại</a>
    </div>

    <div class="alert alert-info mt-4" id="phi_giai_box">
      💰 <strong>Phí tổ chức dự kiến:</strong> <span id="tong_phi_giai">(tự động tính theo thông tin trên)</span>
      <div class="small text-muted mt-2">Công thức: (Giá giải + Phí hệ thống + VAT) × (Thời lượng hiệp / 60) × Số cần thủ × Số hiệp</div>
    </div>


  </form>
</div>