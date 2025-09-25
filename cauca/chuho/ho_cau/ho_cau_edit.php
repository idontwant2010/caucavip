<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['vai_tro'] !== 'chuho') {
    header("Location: /caucavip/no_permission.php");
    exit();
}

require_once __DIR__ . '/../../../includes/header.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("
    SELECT *
    FROM ho_cau
    WHERE id = ?
    LIMIT 1
");
$stmt->execute([$id]);
$ho_cau = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ho_cau) {
    echo "<div class='alert alert-danger'>Không tìm thấy hồ câu!</div>";
    require_once __DIR__ . '/../../../includes/footer.php';
    exit;
}

// Lấy dữ liệu lịch hoạt động theo dạng mảng key = thu
$stmt2 = $pdo->prepare("SELECT thu, gio_mo, gio_dong, trang_thai FROM lich_hoat_dong_ho_cau WHERE ho_cau_id = ?");
$stmt2->execute([$id]);
$lich_cau = [];
while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $lich_cau[$row['thu']] = $row;
}

// ✅ LẤY DANH SÁCH LOẠI CÁ
$stmt_loai_ca = $pdo->query("SELECT id, ten_ca FROM loai_ca WHERE trang_thai = 'hoat_dong'");
$ds_loai_ca = $stmt_loai_ca->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Checkbox: luôn chuẩn hoá về 0/1
        $cho_phep_xoi    = isset($_POST['cho_phep_xoi'])    ? (int)$_POST['cho_phep_xoi']    : 0;
        $cho_phep_khoen  = isset($_POST['cho_phep_khoen'])  ? (int)$_POST['cho_phep_khoen']  : 0;
        $cho_phep_heo    = isset($_POST['cho_phep_heo'])    ? (int)$_POST['cho_phep_heo']    : 0;
        $cho_phep_xe_heo = isset($_POST['cho_phep_xe_heo']) ? (int)$_POST['cho_phep_xe_heo'] : 0;

        // Giá: rỗng => 0
        $gia_xoi    = isset($_POST['gia_xoi'])    ? (int)$_POST['gia_xoi']    : 0;
        $gia_khoen  = isset($_POST['gia_khoen'])  ? (int)$_POST['gia_khoen']  : 0;
        $gia_heo    = isset($_POST['gia_heo'])    ? (int)$_POST['gia_heo']    : 0;
        $gia_xe_heo = isset($_POST['gia_xe_heo']) ? (int)$_POST['gia_xe_heo'] : 0;

        $stmt = $pdo->prepare("
            UPDATE ho_cau SET
                loai_ca_id          = :loai_ca_id,
                ten_ho              = :ten_ho,
                dien_tich           = :dien_tich,
                max_chieu_dai_can   = :max_chieu_dai_can,
                max_truc_theo       = :max_truc_theo,
                mo_ta               = :mo_ta,
                cam_moi             = :cam_moi,
                status              = :status,
                ly_do_dong          = :ly_do_dong,
                luong_ca            = :luong_ca,
                so_cho_ngoi         = :so_cho_ngoi,
                cho_phep_danh_game  = :cho_phep_danh_game,
                gia_game            = :gia_game,
				cho_phep_danh_giai  = :cho_phep_danh_giai,
                gia_giai            = :gia_giai,
				cho_phep_danh_thit  = :cho_phep_danh_thit,
				
                -- 8 trường mới
                cho_phep_xoi        = :cho_phep_xoi,
                gia_xoi             = :gia_xoi,
                cho_phep_khoen      = :cho_phep_khoen,
                gia_khoen           = :gia_khoen,
                cho_phep_heo        = :cho_phep_heo,   -- đã sửa đúng tên cột
                gia_heo             = :gia_heo,
                cho_phep_xe_heo     = :cho_phep_xe_heo,
                gia_xe_heo          = :gia_xe_heo,

                created_at          = NOW()
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            ':loai_ca_id'         => $_POST['loai_ca_id'],
            ':ten_ho'             => $_POST['ten_ho'],
            ':dien_tich'          => $_POST['dien_tich'],
            ':max_chieu_dai_can'  => $_POST['max_chieu_dai_can'],
            ':max_truc_theo'      => $_POST['max_truc_theo'],
            ':mo_ta'              => $_POST['mo_ta'],
            ':cam_moi'            => $_POST['cam_moi'],
            ':status'             => $_POST['status'],
            ':ly_do_dong'         => $_POST['ly_do_dong'],
            ':luong_ca'           => $_POST['luong_ca'],
            ':so_cho_ngoi'        => $_POST['so_cho_ngoi'],
            ':cho_phep_danh_game' => $_POST['cho_phep_danh_game'],
            ':gia_game'           => $_POST['gia_game'],
			':cho_phep_danh_giai' => $_POST['cho_phep_danh_giai'],
            ':gia_giai'           => $_POST['gia_giai'],
			':cho_phep_danh_thit' => $_POST['cho_phep_danh_thit'],
			
            // 8 trường mới
            ':cho_phep_xoi'       => $cho_phep_xoi,
            ':gia_xoi'            => $gia_xoi,
            ':cho_phep_khoen'     => $cho_phep_khoen,
            ':gia_khoen'          => $gia_khoen,
            ':cho_phep_heo'       => $cho_phep_heo,
            ':gia_heo'            => $gia_heo,
            ':cho_phep_xe_heo'    => $cho_phep_xe_heo,
            ':gia_xe_heo'         => $gia_xe_heo,

            ':id'                 => $id
        ]);

        // Cập nhật lại bảng lịch hoạt động
        $pdo->prepare("DELETE FROM lich_hoat_dong_ho_cau WHERE ho_cau_id = ?")->execute([$id]);
        $thu_array = $_POST['thu'] ?? [];
        $gio_mo_array = $_POST['gio_mo'] ?? [];
		$trang_thai_array = $_POST['trang_thai'] ?? [];
        $gio_dong_array = $_POST['gio_dong'] ?? [];

		$stmtInsert = $pdo->prepare("INSERT INTO lich_hoat_dong_ho_cau (ho_cau_id, thu, gio_mo, gio_dong, trang_thai)
                             VALUES (:ho_cau_id, :thu, :gio_mo, :gio_dong, :trang_thai)");

		foreach ($thu_array as $i => $thu) {
			$stmtInsert->execute([
				':ho_cau_id' => $id,
				':thu' => $thu,
				':gio_mo' => $gio_mo_array[$i],
				':gio_dong' => $gio_dong_array[$i],
				':trang_thai' => $trang_thai_array[$i] ?? 'mo'
			]);
		}

        $_SESSION['flash_success'] = "Đã lưu thay đổi hồ câu.";
        header("Location: ho_cau_edit.php?id=".$id);
        exit;
    } catch (Exception $e) {
        $_SESSION['flash_error'] = "Lỗi lưu: " . $e->getMessage();
    }
}
?>

<div class="container mt-4">
    <h5 class="mb-4">Chỉnh sửa hồ câu: <?= htmlspecialchars($ho_cau['ten_ho']) ?></h5>
    <form method="POST">
	
	        <div class="card mt-4">
			  <div class="card-header">
				<strong>Thông tin cơ bản</strong>
			  </div>
			  <div class="card-body">
				<div class="row g-3">
				<div class="col-md-3">
					<label class="form-label">Tên hồ</label>
					<input type="text" name="ten_ho" class="form-control" value="<?= $ho_cau['ten_ho'] ?>" required>
				</div>
				<div class="col-md-3">
					<label class="form-label">Loại cá</label>
						<select name="loai_ca_id" class="form-select" required>
						<option value="">-- Chọn loại cá --</option>
						<?php foreach ($ds_loai_ca as $ca): ?>
							<option value="<?= $ca['id'] ?>" <?= ($ho_cau['loai_ca_id'] == $ca['id']) ? 'selected' : '' ?>>
								<?= htmlspecialchars($ca['ten_ca']) ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="col-md-3">
					<label class="form-label">Đóng/Mở</label>
					<select name="status" class="form-select">
						<option value="dang_hoat_dong" <?= $ho_cau['status'] == 'dang_hoat_dong' ? 'selected' : '' ?>>Đang hoạt động</option>
						<option value="tam_dung" <?= $ho_cau['status'] == 'tam_dung' ? 'selected' : '' ?>>Tạm dừng</option>
						<option value="chuho_tam_khoa" <?= $ho_cau['status'] == 'chuho_tam_khoa' ? 'selected' : '' ?>>Chủ hồ tạm khoá</option>
						<option value="dong_vinh_vien" <?= $ho_cau['status'] == 'dong_vinh_vien' ? 'selected' : '' ?>>Đóng vĩnh viễn</option>
					</select>
				</div>
				<div class="col-md-3">
					<label class="form-label">Lý do đóng</label>
					<input type="text" name="ly_do_dong" class="form-control" value="<?= $ho_cau['ly_do_dong'] ?>">
				</div>
				<div class="col-md-6">
					<label class="form-label">Mô tả</label>
					<textarea name="mo_ta" class="form-control"><?= $ho_cau['mo_ta'] ?></textarea>
				</div>
				<div class="col-md-6">
					<label class="form-label">Cấm mồi</label>
					<textarea name="cam_moi" class="form-control"><?= $ho_cau['cam_moi'] ?></textarea>
				</div>
			</div>
			</div>
		</div>
	
        <div class="card mt-4">
			  <div class="card-header">
				<strong>Thông tin hữu ích cho cần thủ</strong>
			  </div>
			  <div class="card-body">
				<div class="row g-3">
				<div class="col-md-3">
					<label class="form-label">Cụm hồ</label>
					<input type="text" class="form-control" value="<?= $ho_cau['cum_ho_id'] ?>" disabled>
				</div>
				<div class="col-md-3">
					<label class="form-label">Diện tích (m2)</label>
					<input type="number" name="dien_tich" class="form-control" value="<?= $ho_cau['dien_tich'] ?>">
				</div>
				<div class="col-md-3">
					<label class="form-label">Giới hạn cần (cm)</label>
					<input type="number" name="max_chieu_dai_can" class="form-control" value="<?= $ho_cau['max_chieu_dai_can'] ?>">
				</div>
				<div class="col-md-3">
					<label class="form-label">Số chổ câu</label>
					<input type="number" name="so_cho_ngoi" class="form-control" value="<?= $ho_cau['so_cho_ngoi'] ?>">
				</div>
				<div class="col-md-3">
					<label class="form-label">Trục thẻo (cm)</label>
					<input type="number" name="max_truc_theo" class="form-control" value="<?= $ho_cau['max_truc_theo'] ?>">
				</div>

				<div class="col-md-3">
					<label class="form-label">Lượng cá (kg)</label>
					<input type="number" name="luong_ca" class="form-control" value="<?= $ho_cau['luong_ca'] ?>">
				</div>
				<div class="col-md-3">
					<label class="form-label"> Hồ mở (đánh cá thịt)</label>
					<select name="cho_phep_danh_thit" class="form-select">
						<option value="1" <?= $ho_cau['cho_phep_danh_thit'] ? 'selected' : '' ?>>Có</option>
						<option value="0" <?= !$ho_cau['cho_phep_danh_thit'] ? 'selected' : '' ?>>Không</option>
					</select>
				</div>
			</div>
			</div>
		</div>
		
        <div class="card mt-4">
			  <div class="card-header">
				<strong>Game + giải </strong>
			  </div>
			  <div class="card-body">
				<div class="row g-3">
				<div class="col-md-3">
					<label class="form-label"> Câu game</label>
					<select name="cho_phep_danh_game" class="form-select">
						<option value="1" <?= $ho_cau['cho_phep_danh_game'] ? 'selected' : '' ?>>Có</option>
						<option value="0" <?= !$ho_cau['cho_phep_danh_game'] ? 'selected' : '' ?>>Không</option>
					</select>
				</div>
				<div class="col-md-3">
					<label class="form-label">Giá game (vnd)</label>
					<input type="number" name="gia_game" class="form-control" value="<?= $ho_cau['gia_game'] ?>">
				</div>
				<div class="col-md-3">
					<label class="form-label"> Câu giải</label>
					<select name="cho_phep_danh_giai" class="form-select">
						<option value="1" <?= $ho_cau['cho_phep_danh_giai'] ? 'selected' : '' ?>>Có</option>
						<option value="0" <?= !$ho_cau['cho_phep_danh_giai'] ? 'selected' : '' ?>>Không</option>
					</select>
				</div>
				<div class="col-md-3">
					<label class="form-label">Giá giải (vnd)</label>
					<input type="number" name="gia_giai" class="form-control" value="<?= $ho_cau['gia_giai'] ?>">
				</div>
			</div>
			</div>
		</div>		
		
		
		<div class="card mt-4">
		  <div class="card-header">
			<strong>Thưởng heo/khoen/xôi & Thu phí heo </strong>
		  </div>
			  <div class="card-body">
				<div class="row g-3">

				  <!-- XÔI -->
				  <div class="col-md-3">
					<div class="form-check form-switch">
						<input type="hidden" name="cho_phep_xoi" value="0">
						<input class="form-check-input" type="checkbox" id="cho_phep_xoi"
							   name="cho_phep_xoi" value="1"
							   <?= !empty($ho_cau['cho_phep_xoi']) ? 'checked' : '' ?>>
						<label class="form-check-label" for="cho_phep_xoi">Thưởng xôi (Vd: 50.000)</label>
					</div>
				  </div>
				  <div class="col-md-3">
					<input type="number" min="0" class="form-control" id="gia_xoi" name="gia_xoi"
						   value="<?= isset($ho_cau['gia_xoi']) ? (int)$ho_cau['gia_xoi'] : 0 ?>">
				  </div>

				  <!-- KHOEN -->
				  <div class="col-md-3">
					<div class="form-check form-switch">
						<input type="hidden" name="cho_phep_khoen" value="0">
						<input class="form-check-input" type="checkbox" id="cho_phep_khoen"
							   name="cho_phep_khoen" value="1"
							   <?= !empty($ho_cau['cho_phep_khoen']) ? 'checked' : '' ?>>
						<label class="form-check-label" for="cho_phep_khoen">Thưởng Khoen (Vd: 100.000)</label>
					</div>
				  </div>
				  <div class="col-md-3">
					<input type="number" min="0" class="form-control" id="gia_khoen" name="gia_khoen"
						   value="<?= isset($ho_cau['gia_khoen']) ? (int)$ho_cau['gia_khoen'] : 0 ?>">
				  </div>

				  <!-- HEO (DB hiện là 'cho_pheo_heo', nhưng SELECT đã alias thành 'cho_phep_heo') -->
				  <div class="col-md-3">
					<div class="form-check form-switch">
					<input type="hidden" name="cho_phep_heo" value="0">
					<input class="form-check-input" type="checkbox" id="cho_phep_heo"
						   name="cho_phep_heo" value="1"
						   <?= !empty($ho_cau['cho_phep_heo']) ? 'checked' : '' ?>>
					  <label class="form-check-label" for="cho_phep_heo">Heo lớn nhất (Vd: 5.000.000)</label>
					</div>
				  </div>
				  <div class="col-md-3">
					<input type="number" min="0" class="form-control" id="gia_heo" name="gia_heo"
						   value="<?= isset($ho_cau['gia_heo']) ? (int)$ho_cau['gia_heo'] : 0 ?>">
				  </div>

				  <!-- Thu theo -->
				  <div class="col-md-3">
					<div class="form-check form-switch">
						<input type="hidden" name="cho_phep_xe_heo" value="0">
						<input class="form-check-input bg-danger border-danger" type="checkbox" id="cho_phep_xe_heo"
							   name="cho_phep_xe_heo" value="1"
							   <?= !empty($ho_cau['cho_phep_xe_heo']) ? 'checked' : '' ?>>
						<label class="form-check-label" for="cho_phep_xe_heo">Thu phí heo/suất (vd: 20.000)</label>
					</div>
				  </div>
				  <div class="col-md-3">
					<input type="number" min="0" class="form-control" id="gia_xe_heo" name="gia_xe_heo"
						   value="<?= isset($ho_cau['gia_xe_heo']) ? (int)$ho_cau['gia_xe_heo'] : 0 ?>">
				  </div>

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
						<th>Trạng Thái</th>
                    </tr>
                </thead>
                
				<tbody>
<?php
$thu_list = ['2','3','4','5','6','7','CN'];
foreach ($thu_list as $thu):
    $gio_mo = isset($lich_cau[$thu]) ? $lich_cau[$thu]['gio_mo'] : '06:00:00';
    $gio_dong = isset($lich_cau[$thu]) ? $lich_cau[$thu]['gio_dong'] : '18:00:00';
    $trang_thai = isset($lich_cau[$thu]['trang_thai']) ? $lich_cau[$thu]['trang_thai'] : 'mo';
?>
<tr>
    <td>Thứ <?= $thu ?><input type="hidden" name="thu[]" value="<?= $thu ?>"></td>
    <td><input type="time" name="gio_mo[]" class="form-control" value="<?= $gio_mo ?>"></td>
    <td><input type="time" name="gio_dong[]" class="form-control" value="<?= $gio_dong ?>"></td>
    <td>
        <select name="trang_thai[]" class="form-select">
            <option value="mo" <?= $trang_thai == 'mo' ? 'selected' : '' ?>>Mở</option>
            <option value="nghi" <?= $trang_thai == 'nghi' ? 'selected' : '' ?>>Nghỉ</option>
        </select>
    </td>
</tr>
<?php endforeach; ?>
</tbody>

            </table>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            <a href="ho_cau_list.php" class="btn btn-secondary">Quay lại danh sách</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>

<script>
  [
    ['cho_phep_xoi','gia_xoi'],
    ['cho_phep_khoen','gia_khoen'],
    ['cho_phep_heo','gia_heo'],
    ['cho_phep_xe_heo','gia_xe_heo'],
  ].forEach(([chk,inp]) => {
    const c = document.getElementById(chk);
    const i = document.getElementById(inp);
    const sync = () => { i.disabled = !c.checked; };
    c.addEventListener('change', sync);
    sync();
  });
</script>
