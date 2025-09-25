<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';
require_once __DIR__ . '/../../../includes/header.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['vai_tro'] !== 'chuho') {
    header("Location: ../../../no_permission.php");
    exit;
}

$chu_ho_id = $_SESSION['user']['id'];

// Lấy loại cá
$stmt_loai_ca = $pdo->query("SELECT id, ten_ca FROM loai_ca WHERE trang_thai = 'hoat_dong'");
$ds_loai_ca = $stmt_loai_ca->fetchAll(PDO::FETCH_ASSOC);

// Lấy cụm hồ của chủ hồ
$stmt_cum_ho = $pdo->prepare("SELECT id, ten_cum_ho FROM cum_ho WHERE chu_ho_id = ?");
$stmt_cum_ho->execute([$chu_ho_id]);
$ds_cum_ho = $stmt_cum_ho->fetchAll(PDO::FETCH_ASSOC);

// Xử lý khi submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
$selected_cum_ho_id = $_POST['cum_ho_id'] ?? 0;

// Kiểm tra số lượng hồ trong cụm này
$stmt_check = $pdo->prepare("SELECT COUNT(*) FROM ho_cau WHERE cum_ho_id = ?");
$stmt_check->execute([$selected_cum_ho_id]);
$so_ho = $stmt_check->fetchColumn();

if ($so_ho >= 2) {
    echo "<div class='alert alert-danger'>❌ Phầm mềm miễn phí chỉ cho giới hạn 2 hồ. Để thêm mới vui lòng liên hệ admin.</div>";
    require_once __DIR__ . '/../../../includes/footer.php';
    exit;
}

		
		
        $stmt = $pdo->prepare("INSERT INTO ho_cau (cum_ho_id, loai_ca_id, ten_ho, dien_tich, max_chieu_dai_can, max_truc_theo,
            mo_ta, cam_moi, status, ly_do_dong, luong_ca, cho_phep_danh_game, gia_game, cho_phep_danh_giai, gia_giai)
            VALUES (:cum_ho_id, :loai_ca_id, :ten_ho, :dien_tich, :max_chieu_dai_can, :max_truc_theo,
            :mo_ta, :cam_moi, :status, :ly_do_dong, :luong_ca, :cho_phep_danh_game, :gia_game, :cho_phep_danh_giai, :gia_giai)");
        $stmt->execute([
            ':cum_ho_id' => $_POST['cum_ho_id'],
            ':loai_ca_id' => $_POST['loai_ca_id'],
            ':ten_ho' => $_POST['ten_ho'],
            ':dien_tich' => $_POST['dien_tich'],
            ':max_chieu_dai_can' => $_POST['max_chieu_dai_can'],
            ':max_truc_theo' => $_POST['max_truc_theo'],
            ':mo_ta' => $_POST['mo_ta'],
            ':cam_moi' => $_POST['cam_moi'],
            ':status' => $_POST['status'],
            ':ly_do_dong' => $_POST['ly_do_dong'],
            ':luong_ca' => $_POST['luong_ca'],
			  ':cho_phep_danh_game'  => !empty($_POST['cho_phep_danh_game']) ? 1 : 0,
			  ':gia_game'            => $_POST['gia_game'] !== '' ? (int)preg_replace('/\D/','',$_POST['gia_game']) : null,

			  ':cho_phep_danh_giai'  => !empty($_POST['cho_phep_danh_giai']) ? 1 : 0,
			  ':gia_giai'            => $_POST['gia_giai'] !== '' ? (int)preg_replace('/\D/','',$_POST['gia_giai']) : null,	
						
			
        ]);
        $ho_cau_id = $pdo->lastInsertId();

// Khởi tạo 3 bảng giá mặc định cho hồ mới
$default_banggia = [
    ['ten' => 'cơ bản',     'status' => 'open'],
    ['ten' => 'trung cấp',  'status' => 'closed'],
    ['ten' => 'đại sư',     'status' => 'closed']
];

$stmt_insert_price = $pdo->prepare("
    INSERT INTO gia_ca_thit_phut (
        ho_cau_id, ten_bang_gia, base_duration, base_price,
        extra_unit_price, discount_2x_duration, discount_3x_duration, discount_4x_duration,
        gia_ban_ca, gia_thu_lai, loai_thu, status, ghi_chu
    ) VALUES (
        :ho_cau_id, :ten_bang_gia, 240, 240000,
        1000, 20000, 40000, 6000,
        60000, 25000, 'kg', :status, 'Tự động tạo khi thêm hồ'
    )
");

foreach ($default_banggia as $bg) {
    $stmt_insert_price->execute([
        ':ho_cau_id' => $ho_cau_id,
        ':ten_bang_gia' => $bg['ten'],
        ':status' => $bg['status']
    ]);
}


        // Thêm lịch hoạt động
        $thu_array = $_POST['thu'] ?? [];
        $gio_mo_array = $_POST['gio_mo'] ?? [];
        $gio_dong_array = $_POST['gio_dong'] ?? [];
        $trang_thai_array = $_POST['trang_thai'] ?? [];

        $stmt2 = $pdo->prepare("INSERT INTO lich_hoat_dong_ho_cau (ho_cau_id, thu, gio_mo, gio_dong, trang_thai)
                                VALUES (:ho_cau_id, :thu, :gio_mo, :gio_dong, :trang_thai)");
        foreach ($thu_array as $i => $thu) {
            $stmt2->execute([
                ':ho_cau_id' => $ho_cau_id,
                ':thu' => $thu,
                ':gio_mo' => $gio_mo_array[$i],
                ':gio_dong' => $gio_dong_array[$i],
                ':trang_thai' => $trang_thai_array[$i] ?? 'mo'
            ]);
        }

        header("Location: ho_cau_list.php");
        exit;
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Lỗi: " . $e->getMessage() . "</div>";
    }
}
?>

<div class="container mt-4">
    <h2 class="mb-4">Thêm hồ câu mới</h2>
    <form method="POST">
	
		    <div class="card mt-4">
			 <div class="card-header">
				<strong>Thông tin cơ bản</strong>
			 </div>
			  <div class="card-body">
				<div class="row g-3">
					<div class="col-md-3">
						<label class="form-label">Tên hồ</label>
						<input type="text" name="ten_ho" class="form-control" required>
					</div>
					<div class="col-md-3">
						<label class="form-label">Loại cá</label>
						<select name="loai_ca_id" class="form-select" required>
							<option value="">-- Chọn loại cá --</option>
							<?php foreach ($ds_loai_ca as $ca): ?>
								<option value="<?= $ca['id'] ?>"><?= htmlspecialchars($ca['ten_ca']) ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-3">
						<label class="form-label">Cụm hồ</label>
						<select name="cum_ho_id" class="form-select" required>
							<option value="">-- Chọn cụm hồ của bạn --</option>
							<?php foreach ($ds_cum_ho as $cum): ?>
								<option value="<?= $cum['id'] ?>"><?= htmlspecialchars($cum['ten_cum_ho']) ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="col-md-3">
						<label class="form-label">Diện tích</label>
						<input type="number" name="dien_tich" class="form-control" value="1500">
					</div>
				  
			    <div class="col-md-6">
                <label class="form-label">Mô tả</label>
                <textarea name="mo_ta" class="form-control">mô tả...</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Cấm mồi</label>
                <textarea name="cam_moi" class="form-control">cấm mồi...</textarea>
            </div>
		</div>
		</div>
		</div>
	
	    <div class="card mt-4">
			 <div class="card-header">
				<strong>Thông tin cần thiết</strong>
			 </div>
			  <div class="card-body">
				<div class="row g-3">
			
				<div class="col-md-2">
                <label class="form-label">Lượng cá (kg)</label>
                <input type="number" name="luong_ca" class="form-control" value="1500">
            </div>
		
            <div class="col-md-2">
                <label class="form-label">Giới hạn cần (cm)</label>
                <input type="number" name="max_chieu_dai_can" class="form-control" value="540">
            </div>
            <div class="col-md-2">
                <label class="form-label">Trục thẻo (cm)</label>
                <input type="number" name="max_truc_theo" class="form-control" value="30">
            </div>

            <div class="col-md-2">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="dang_hoat_dong">Đang hoạt động</option>
                    <option value="tam_dung">Tạm dừng</option>
                    <option value="sap_khui">Sắp khui</option>
                    <option value="dong_vinh_vien">Đóng vĩnh viễn</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Lý do đóng</label>
                <input type="text" name="ly_do_dong" class="form-control" value="lý do đóng hồ...">
            </div>
			  
		  </div>
		</div>
    </div>	
	
	
	    <div class="card mt-4">
			 <div class="card-header">
				<strong>Game và giải</strong>
			 </div>
			  <div class="card-body">
				<div class="row g-3">
			  <!-- Giải -->
			  <div class="col-md-3">
				<label class="form-label d-block">Giải</label>
				<input type="hidden" name="cho_phep_danh_giai" value="0">
				<?php $v_giai = (string)($old['cho_phep_danh_giai'] ?? $ho['cho_phep_danh_giai'] ?? '0'); ?>
				<div class="form-check form-switch">
				  <input class="form-check-input" type="checkbox" role="switch"
						 id="chk_giai" name="cho_phep_danh_giai" value="1"
						 <?= $v_giai === '1' ? 'checked' : '' ?>>
				  <label class="form-check-label" for="chk_giai">Bật / tắt</label>
				</div>
			  </div>
			  <div class="col-md-3">
				<label class="form-label">Giá giải</label>
				<input type="number" name="gia_giai" id="gia_giai" class="form-control"
					   value="<?= htmlspecialchars((string)($old['gia_giai'] ?? $ho['gia_giai'] ?? 25000)) ?>"
					   min="0" step="1000" inputmode="numeric">
				<div class="form-text">Đơn vị: VND. Để 0 nếu miễn phí.</div>
			  </div>

			  <!-- Game -->
			  <div class="col-md-3">
				<label class="form-label d-block">Game</label>
				<!-- hidden 0 để khi unchecked vẫn gửi 0 -->
				<input type="hidden" name="cho_phep_danh_game" value="0">
				<?php $v_game = (string)($old['cho_phep_danh_game'] ?? $ho['cho_phep_danh_game'] ?? '0'); ?>
				<div class="form-check form-switch">
				  <input class="form-check-input" type="checkbox" role="switch"
						 id="chk_game" name="cho_phep_danh_game" value="1"
						 <?= $v_game === '1' ? 'checked' : '' ?>>
				  <label class="form-check-label" for="chk_game">Bật / tắt</label>
				</div>
			  </div>
			  <div class="col-md-3">
				<label class="form-label">giá game</label>
				<input type="number" name="gia_game" id="gia_game" class="form-control"
					   value="<?= htmlspecialchars((string)($old['gia_game'] ?? $ho['gia_game'] ?? 20000)) ?>"
					   min="0" step="1000" inputmode="numeric">
				<div class="form-text">Đơn vị: VND. Để 0 nếu miễn phí.</div>
 
			</div>
			</div>
			</div>

        <hr class="my-4">
        <h5 class="mb-3">Lịch hoạt động từng ngày</h5>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Thứ</th>
                        <th>Giờ mở</th>
                        <th>Giờ đóng</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $thu_list = ['2','3','4','5','6','7','CN'];
                    foreach ($thu_list as $thu):
                    ?>
                    <tr>
                        <td>Thứ <?= $thu ?><input type="hidden" name="thu[]" value="<?= $thu ?>"></td>
                        <td><input type="time" name="gio_mo[]" class="form-control" value="06:00:00"></td>
                        <td><input type="time" name="gio_dong[]" class="form-control" value="18:00:00"></td>
                        <td>
                            <select name="trang_thai[]" class="form-select">
                                <option value="mo" selected>Mở</option>
                                <option value="nghi">Nghỉ</option>
                            </select>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">Thêm hồ mới</button>
            <a href="ho_cau_list.php" class="btn btn-secondary">Hủy</a>
        </div>
		
		
		
    </form>
</div>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
