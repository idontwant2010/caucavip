<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'canthu') {
    header("Location: /");
    exit;
}

$ho_cau_id = isset($_GET['ho_id']) ? (int)$_GET['ho_id'] : 0;
if ($ho_cau_id <= 0) {
    echo "Thiếu thông tin hồ câu.";
    exit;
}

// Lấy thông tin hồ
$stmt = $pdo->prepare("SELECT * FROM ho_cau WHERE id = ? AND cho_phep_danh_giai = 1");
$stmt->execute([$ho_cau_id]);
$ho = $stmt->fetch();
if (!$ho) {
    echo "Hồ không tồn tại hoặc không cho phép tổ chức giải.";
    exit;
}

// Lấy tổng số chổ ngồi của cụm hồ,
$stmt = $pdo->prepare("
    SELECT c.id AS cum_ho_id, c.ten_cum_ho, c.dia_chi, c.chu_ho_id
    FROM ho_cau h
    JOIN cum_ho c ON h.cum_ho_id = c.id
    WHERE h.id = ? AND h.cho_phep_danh_giai = 1
");
$stmt->execute([$ho_cau_id]);
$cum_ho = $stmt->fetch(PDO::FETCH_ASSOC);

$cum_ho_id = (int)$cum_ho['cum_ho_id'];
$ten_cum_ho = $cum_ho['ten_cum_ho'];


// tồng chổ ngồi những hồ cho đánh giải
$stmt = $pdo->prepare("
    SELECT SUM(so_cho_ngoi) AS tong_cho_ngoi 
    FROM ho_cau 
    WHERE cum_ho_id = :cum_ho_id 
      AND cho_phep_danh_giai = 1
");
$stmt->execute(['cum_ho_id' => $cum_ho_id]);
$tong_cho_ngoi = (int)$stmt->fetchColumn();

// Danh sách hình thức
$ds_hinh_thuc = $pdo->query("SELECT * FROM giai_game_hinh_thuc WHERE hinh_thuc = 'giai' ORDER BY so_hiep ASC, so_bang ASC ")->fetchAll();

function get_system_config($pdo, $key) {
    $stmt = $pdo->prepare("SELECT config_value FROM admin_config_keys WHERE config_key = ?");
    $stmt->execute([$key]);
    return (int)$stmt->fetchColumn();
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten_giai = trim($_POST['ten_giai']);
    $hinh_thuc_id = (int)$_POST['hinh_thuc_id'];
    $so_can_thu = (int)$_POST['so_can_thu'];
    $thoi_luong_phut_hiep = (int)$_POST['thoi_luong_phut_hiep'];
    $ngay_to_chuc = $_POST['ngay_to_chuc'];
    $gio_bat_dau = $_POST['gio_bat_dau'];
    $tien_cuoc = (int)$_POST['tien_cuoc'];
    $luat_choi = trim($_POST['luat_choi']);

    $stmt = $pdo->prepare("SELECT * FROM giai_game_hinh_thuc WHERE id = ? AND hinh_thuc = 'giai'");
    $stmt->execute([$hinh_thuc_id]);
    $hinh_thuc = $stmt->fetch();
	
	$ngay_hien_tai = new DateTime(); // hôm nay
	$ngay_hien_tai->modify('+7 day'); // +7 ngày
	$ngay_gioi_han = $ngay_hien_tai->format('Y-m-d');

    if (!$hinh_thuc) {
        echo "Hình thức không hợp lệ."; exit;
    }
	
	if ($ngay_to_chuc < $ngay_gioi_han) {

		$link_back = "giai_create.php?ho_id=" . urlencode($ho_cau_id);

	echo '⛔ Bạn cần đặt trước ít nhất 7 ngày trước khi mở giải. Ngày tổ chức phải từ <strong>' . $ngay_gioi_han . '</strong> trở đi.<br>';
	echo '👉 <a href="' . $link_back . '">Quay lại chỉnh sửa giải</a>';
	exit;
	}
	
    if ($so_can_thu < $hinh_thuc['so_nguoi_min'] || $so_can_thu > $hinh_thuc['so_nguoi_max']) {
       echo "Số lượng cần thủ ít nhất 4 người / bảng đấu. Ví dụ số lượng tối thiểu của hình thức có 4 bảng là 16 người.<br>
		<a href='giai_create.php?ho_id={$ho_cau_id}' class='btn btn-sm btn-outline-primary mt-2'>🔙 Quay lại</a>";
		exit;
    }

	if ($so_can_thu > $tong_cho_ngoi) {
		echo "
		<div class='alert alert-warning'>
			⚠️ Số lượng cần thủ đăng ký là <b>{$so_can_thu}</b>, nhưng tổng số chỗ ngồi của cụm hồ hiện tại chỉ có <b>{$tong_cho_ngoi}</b>.<br>
			👉 Vui lòng giảm số lượng cần thủ, hoặc liên hệ chủ hồ sửa số chỗ ngồi trong các hồ thuộc cụm <a href='giai_create.php?ho_id={$ho_cau_id}' class='btn btn-sm btn-outline-primary mt-2'>🔙 Quay lại</a>.
		</div>
		";
		exit;
	}


    // Tính phí giải
    $phi_ho = (int)$ho['gia_giai'];
    $phi_ht = get_system_config($pdo, 'giai_fee_user');
    $vat_percent = get_system_config($pdo, 'giai_vat_percent');

    $he_so_thoi_gian = $thoi_luong_phut_hiep / 60;
    $phi_ho_thuc_te = round($phi_ho * $he_so_thoi_gian);
    $phi_ht_thuc_te = round($phi_ht * $he_so_thoi_gian);
	
	$vat_ho = round($phi_ho_thuc_te * $vat_percent / 100);
	$vat_ht = round($phi_ht_thuc_te * $vat_percent / 100);
    $vat = round($phi_ho_thuc_te + $phi_ht_thuc_te);
	
	
	$tong_phi_ho_1 = $phi_ho_thuc_te + $vat_ho;
	$tong_phi_ht_1 = $phi_ht_thuc_te + $vat_ht;
    $tong_phi_1 = $tong_phi_ho_1 + $tong_phi_ht_1;
	
	$tong_phi_ho = $so_can_thu * $hinh_thuc['so_hiep'] * $tong_phi_ho_1;
	$tong_phi_ht = $so_can_thu * $hinh_thuc['so_hiep'] * $tong_phi_ht_1;
    $phi_giai = $so_can_thu * $hinh_thuc['so_hiep'] * $tong_phi_1;
	

    $thoi_gian_dong_dang_ky = date('Y-m-d 23:59:00', strtotime($ngay_to_chuc . ' -1 day'));

	$stmt = $pdo->prepare("INSERT INTO giai_list (
		ho_cau_id, creator_id, hinh_thuc_id, ten_giai, so_luong_can_thu,
		so_bang, so_hiep, thoi_luong_phut_hiep, ngay_to_chuc, gio_bat_dau,
		thoi_gian_dong_dang_ky, tien_cuoc, phi_giai, phi_ho, luat_choi, status
	) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'dang_cho_xac_nhan')");
	$stmt->execute([
		$ho_cau_id,
		$_SESSION['user']['id'],
		$hinh_thuc_id,
		$ten_giai,
		$so_can_thu,
		$hinh_thuc['so_bang'],
		$hinh_thuc['so_hiep'],
		$thoi_luong_phut_hiep,
		$ngay_to_chuc,
		$gio_bat_dau,
		$thoi_gian_dong_dang_ky,
		$tien_cuoc,
		$phi_giai,
		$tong_phi_ho,
		$luat_choi
	]);


    header("Location: ../mygiai/my_giai_detail.php?id=" . $pdo->lastInsertId());
    exit;
}
?>
<?php include_once '../../../includes/header.php'; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

<div class="container mt-4">
  <h4 class="mb-3">🎯 Tạo Giải Mới tại hồ: <strong><?= htmlspecialchars($ho['ten_ho']) ?></strong></h4>
    <div class="alert alert-light">
    <strong>Thông tin hồ:</strong><br>
    ✅ Hồ Câu: <strong><?= $ho['ten_ho'] ?> </strong> có <strong><?= $ho['so_cho_ngoi'] ?> </strong> 
		chổ ngồi, hồ này thuộc cụm hồ có <strong><?= $cum_ho['ten_cum_ho'] ?></strong> 
		có tổng: <strong><?= $tong_cho_ngoi ?></strong> chổ ngồi.<p>
	✅  Phí giải/người/hiệp: <strong><?= number_format($ho['gia_giai']) ?></strong>đ 
	|🕹 Phí game/người/hiệp: <strong><?= number_format($ho['gia_game']) ?></strong>đ 
	| 🕹 Phí hệ thống(60p)/người/hiệp: <strong><?= get_system_config($pdo, 'giai_fee_user') ?></strong>đ 
	| 🎯 VAT: <strong><?= get_system_config($pdo, 'giai_vat_percent') ?></strong>%

  </div>
  
  
  <form method="post" class="row g-3" id="giaiForm">
    <div class="col-md-6">
      <label class="form-label">Tên giải</label>
      <input name="ten_giai" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Hình thức</label>
      <select name="hinh_thuc_id" class="form-select" id="hinhThuc" required>
        <option value="">-- Chọn --</option>
        <?php foreach ($ds_hinh_thuc as $ht): ?>
          <option value="<?= $ht['id'] ?>" data-so-hiep="<?= $ht['so_hiep'] ?>"><?= htmlspecialchars($ht['ten_hinh_thuc']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">Thời lượng mỗi hiệp (phút)</label>
      <input name="thoi_luong_phut_hiep" type="number" value="60" class="form-control" id="thoiLuong" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">Số cần thủ</label>
      <input name="so_can_thu" type="number" class="form-control" id="soCanThu" value="20" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">Tiền cược (đ/người)</label>
      <input name="tien_cuoc" type="number" class="form-control" value="700000">
    </div>
    <div class="col-md-6">
      <label class="form-label">Ngày tổ chức</label>
		<?php $ngay_mac_dinh = date('Y-m-d', strtotime('+7 day')); ?>
		<input name="ngay_to_chuc" type="date" class="form-control" required value="<?= $ngay_mac_dinh ?>">

    </div>
    <div class="col-md-6">
      <label class="form-label">Giờ bắt đầu</label>
		<input name="gio_bat_dau" type="time" class="form-control" required value="<?= date('H:i', strtotime('07:00')) ?>">
    </div>
    <div class="col-12">
      <label class="form-label">Luật chơi</label>
      <textarea name="luat_choi" rows="4" class="form-control"></textarea>
    </div>
    <div class="col-12">
      <div class="alert alert-info" id="tongPhiText">Tổng phí giải: ...</div>
    </div>
    <div class="col-12">
      <button class="btn btn-primary">💾 Tạo giải</button>
    </div>
  </form>
</div>

<script>
const giaHo = <?= (int)$ho['gia_giai'] ?>;
const phiHeThong = <?= get_system_config($pdo, 'giai_fee_user') ?>;
const vat = <?= get_system_config($pdo, 'giai_vat_percent') ?>;

function tinhPhi() {
  const soCanThu = parseInt(document.getElementById('soCanThu').value) || 0;
  const thoiLuong = parseInt(document.getElementById('thoiLuong').value) || 0;
  const hinhThuc = document.getElementById('hinhThuc');
  const soHiep = parseInt(hinhThuc.options[hinhThuc.selectedIndex]?.dataset.soHiep || 1);

  const hs = thoiLuong / 60;
  const phiHo = giaHo * hs;
  const phiHT = phiHeThong * hs;
  const tong1 = phiHo + phiHT;
  const tongVAT = tong1 * vat / 100;
  const tongPhi = Math.round(soCanThu * soHiep * (tong1 + tongVAT));

  document.getElementById('tongPhiText').innerText = `Tổng phí giải (tạm tính): ${tongPhi.toLocaleString()}đ`;
}

['soCanThu', 'thoiLuong', 'hinhThuc'].forEach(id => {
  document.getElementById(id).addEventListener('input', tinhPhi);
  document.getElementById(id).addEventListener('change', tinhPhi);
});
</script>