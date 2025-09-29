<?php
require_once '../../../connect.php';
require_once '../../../check_login.php';
require_once '../../../includes/header.php';

$giai_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;


if ($giai_id <= 0) {
	echo "<div class='alert alert-danger'>Thiếu ID giải.</div>";
	require_once '../../../includes/footer.php';
	exit;
}

// Lấy thông tin giải và người tạo
$stmt = $pdo->prepare("SELECT g.*, u.full_name, u.phone FROM giai_list g JOIN users u ON g.creator_id = u.id WHERE g.id = ?");
$stmt->execute([$giai_id]);
$giai = $stmt->fetch();

if (!$giai || $giai['creator_id'] != $_SESSION['user']['id']) {
	echo "<div class='alert alert-danger'>Không có quyền truy cập.</div>";
	require_once '../../../includes/footer.php';
	exit;
}


// Tổng số người
$stmt = $pdo->prepare("SELECT COUNT(DISTINCT user_id) FROM giai_schedule WHERE giai_id = ?");
$stmt->execute([$giai_id]);
$so_can_thu = $stmt->fetchColumn();

// Kiểm tra đã chia bảng hiệp 1 chưa
function layTrangThaiChiaHiep($pdo, $giai_id, $so_hiep)
{
	$stmt = $pdo->prepare("
        SELECT so_hiep, COUNT(*) AS da_chia
        FROM giai_schedule
        WHERE giai_id = ? AND so_bang IS NOT NULL AND so_bang != '0'
        GROUP BY so_hiep
    ");
	$stmt->execute([$giai_id]);
	$data = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

	$result = [];
	for ($h = 1; $h <= $so_hiep; $h++) {
		$result[$h] = isset($data[$h]) && $data[$h] > 0;
	}
	return $result;
}

$da_chia_hiep = layTrangThaiChiaHiep($pdo, $giai_id, $giai['so_hiep']);

//dùng hiện nút "sơ kết giải"
$hiep_hien_tai = null;
if (preg_match('/^dang_dau_hiep_(\d+)$/', $giai['status'], $matches)) {
	$hiep_hien_tai = (int) $matches[1]; // ép kiểu cho chắc
}

?>

<html>

<head>
	<meta charset="UTF-8">
	<title>Báo cáo tổng kết giải #<?= $giai_id ?></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<style>
		table th,
		table td {
			text-align: center;
			vertical-align: middle;
			font-size: 14px;
		}

		.bg-hi {
			background-color: #e2f5e9;
		}
	</style>
</head>

<body class="container mt-2">
	<div class="d-flex justify-content-center mt-2"> <?php include '../../../includes/giai_menu_status.php'; ?></div>
	<div class="container py-1">
		<hr>
		<?php if (in_array($giai['status'], ['dang_cho_xac_nhan', 'chuyen_chu_ho_duyet'])): ?>
			<div class="d-flex justify-content-center gap-3 mt-2">
				<a href="my_giai_detail.php?id=<?= $giai_id ?>" class="btn btn-info">
					⬅️ Quay về B0: Xem giải, chỉnh sửa, chờ chủ hồ duyệt!
				</a>
			</div>
		<?php endif; ?>

		<?php if (in_array($giai['status'], ['dang_mo_dang_ky'])): ?>
			<div class="d-flex justify-content-center gap-3 mt-2">
				<a href="my_giai_detail_step_1.php?id=<?= $giai_id ?>" class="btn btn-info">
					⬅️ Quay về B1: Thêm cần thủ vào Giải đang mở đăng ký!
				</a>
			</div>
		<?php endif; ?>

		<div class="d-flex justify-content-center gap-0 mt-0">
			<?php if (in_array($giai['status'], ['dang_dau_hiep_1', 'dang_dau_hiep_2', 'dang_dau_hiep_3', 'dang_dau_hiep_4'])): ?>
				<form action="so_ket_hiep.php" method="POST" onsubmit="return confirm('Bạn có chắc muốn update dữ liệu mới nhất')">
					<input type="hidden" name="giai_id" value="<?= $giai_id ?>">
					<button type="submit" class="btn btn-success">📊 Tính điểm từng hiệp!</button>
				</form>
			<?php endif; ?>
		</div>

		<?php if ((isset($hiep_hien_tai) && $hiep_hien_tai == $giai['so_hiep']) || $giai['status'] === 'so_ket_giai'): ?>
			<div class="d-flex justify-content-center gap-3 mt-2 ">
				<a href="my_giai_detail_step_2.php?id=<?= $giai_id ?>" class="btn btn-secondary">
					⬅️ Quay về B2: Sửa thành tích bảng A-B-C-D
				</a>
				<form method="POST" action="so_ket_giai.php" onsubmit="return confirm('Bạn chắc chắn muốn sơ kết giải này?')">
					<input type="hidden" name="giai_id" value="<?= $giai_id ?>">
					<button type="submit" class="btn btn-warning">
						🏁 Sơ kết giải hiệp <?= $hiep_hien_tai ?> (Hiệp cuối)
					</button>
				</form>
			</div>
		<?php endif; ?>

		<?php if (in_array($giai['status'], ['hoan_tat_giai'])): ?>
			<div class="d-flex justify-content-center gap-1 mt-1 ">
				<div class="mt-1 alert alert-success">
					<p>✅ Xin chúc mừng! Bạn đã tổ chức giải câu thành công.</p>
					<p>✅ Xem danh sách các cần thủ và kết quả bên dưới</p>
				</div>
			</div>
		<?php endif; ?>


		<?php //Hiển thị các nút Chia bảng từ hiệp 2,3,4...n
		for ($h = 2; $h <= $giai['so_hiep']; $h++) {
			// 1. Kiểm tra trạng thái giải đúng hiệp hiện tại
			if ($giai['status'] === "dang_dau_hiep_$h") {
				// echo "<pre>status hiện tại: {$giai['status']}</pre>";
				continue;
			}

			// 2. Kiểm tra đã chia đầy đủ hiệp trước đó (hiệp 1 → hiệp h-1)
			$chia_het_cac_hiep_truoc = true;
			for ($i = 1; $i < $h; $i++) {
				if (empty($da_chia_hiep[$i])) {
					$chia_het_cac_hiep_truoc = false;
					break;
				}
			}

			// 3. Nếu hiệp này chưa chia & các hiệp trước đã chia
			if ($chia_het_cac_hiep_truoc && empty($da_chia_hiep[$h])) {
		?>
				<div class="text-center my-1">
					<form method="POST" action="chia_bang_hiep_<?= $h ?>.php"
						onsubmit="return confirm('Bạn chắc chắn muốn chia bảng và vị trí cho hiệp <?= $h ?>?')">
						<input type="hidden" name="giai_id" value="<?= $giai_id ?>">
						<button type="submit" class="btn btn-danger mb-3">
							🔀 Chia bảng và vị trí hiệp <?= $h ?><?= ($h == $giai['so_hiep']) ? ' - Hiệp Cuối' : '' ?>
						</button>
					</form>
				</div>
		<?php
			}
		}
		?>

		<?php //Hiệp 1: Chia bảng + hiệp  để bắt đầu trận đấu 
		if ($giai['status'] === 'chot_xong_danh_sach' && empty($da_chia_hiep[$h])): ?>
			<div class="text-center my-1">
				<form method="POST" action="chia_bang_hiep_1.php" onsubmit="return confirm('Bạn chắc chắn muốn chia bảng và vị trí cho hiệp 1?') ">
					<input type="hidden" name="giai_id" value="<?= $giai_id ?>">
					<button type="submit" class="btn btn-warning mb-3">🔀 Chia bảng và vị trí hiệp 1</button>
				</form>
			</div>
		<?php endif; ?>


		<?php
		//dùng phân trang
		$stmt = $pdo->prepare("SELECT DISTINCT so_hiep FROM giai_schedule WHERE giai_id = ? ORDER BY so_hiep ASC");
		$stmt->execute([$giai_id]);
		$ds_hiep = $stmt->fetchAll(PDO::FETCH_COLUMN);

		$hiep_active = isset($_GET['hiep']) ? (int)$_GET['hiep'] : 1;

		//dùng ẩn cột 
		$an_update = in_array($giai['status'], ['hoan_tat_giai']);
		?>

		<h6 class="mt-0">📋 Lịch thi đấu: <strong><?= htmlspecialchars($giai['ten_giai']) ?></strong></h6>
		<p>📋
			<strong>Ngày tổ chức:</strong> <?= $giai['ngay_to_chuc'] ?> |
			<strong>Người tạo:</strong> <?= htmlspecialchars($giai['full_name']) ?> (<?= $giai['phone'] ?>) |
			<strong>Số bảng:</strong> <?= $giai['so_bang'] ?> |
			<strong>Số hiệp:</strong> <?= $giai['so_hiep'] ?> |
			<strong>Số cần thủ:</strong> <?= $so_can_thu ?>
		</p>
		<div class="d-flex justify-content-center gap-3 mt-0 ">
			<?php foreach ($ds_hiep as $hiep): ?>
				<a href="?id=<?= $giai_id ?>&hiep=<?= $hiep ?>"
					class="btn btn-sm <?= ($hiep == $hiep_active) ? 'btn-primary' : 'btn-outline-primary' ?>">
					Hiệp <?= $hiep ?>
				</a>
			<?php endforeach; ?>
		</div>

		<div class="table-responsive mt-2">
			<table class="table table-bordered">
				<thead class="table-light">
					<tr>
						<th>Hiệp</th>
						<th>User ID</th>
						<th>Họ và Tên</th>
						<th>Nick Name</th>
						<th>Bảng</th>
						<th>Vị trí</th>
						<th>Ngồi biên</th>
						<th>Số kg</th>
						<th>Đ.Hiệp</th>
						<th>Đ.Phạt</th>
						<th>Đ.Tổng Hiệp</th>
						<?php if (!$an_update): ?>
							<th>Cập nhật số kg; điểm phạt 0.5đ</th>
						<?php endif; ?>

					</tr>
				</thead>
				<tbody>
					<?php
					$stmt = $pdo->prepare("
				SELECT gs.*, u.full_name, u.nickname
				FROM giai_schedule gs
				JOIN users u ON gs.user_id = u.id
				WHERE gs.giai_id = ? AND gs.so_hiep = ?
				ORDER BY gs.so_bang, gs.vi_tri_ngoi
			");
					$stmt->execute([$giai_id, $hiep_active]);
					foreach ($stmt as $row):

						$an_update = in_array($giai['status'], ['hoan_tat_giai']);


					?>

						<tr>
							<td><?= $row['so_hiep'] ?></td>
							<td><?= $row['user_id'] ?></td>
							<td><?= $row['full_name'] ?></td>
							<td><?= $row['nickname'] ?></td>
							<td><?= $row['so_bang'] ?: '-' ?></td>
							<td><?= $row['vi_tri_ngoi'] ?: '-' ?></td>
							<td><?= $row['is_bien'] ? '✅' : '' ?></td>
							<td><span class="so-kg" id="so-kg-<?= $row['id'] ?>"><?= $row['so_kg'] ?></span></td>
							<td><?= $row['so_diem'] ?: '0' ?></td>
							<td><span class="vi-pham" id="vi-pham-<?= $row['id'] ?>"><?= $row['diem_cong_vi_pham'] ?></span></td>
							<td class="bg-info"><strong><?= $row['tong_diem'] ?: '0' ?></strong></td>

							<?php if (!$an_update): ?>
								<td>
									<?php if (
										$row['vi_tri_ngoi'] > 0 &&
										in_array($giai['status'], ['dang_dau_hiep_1', 'dang_dau_hiep_2', 'dang_dau_hiep_3', 'dang_dau_hiep_4', 'so_ket_giai'])
									): ?>
										<form class="update-score-form d-flex gap-1" data-id="<?= $row['id'] ?>">
											<input type="number" step="0.01" name="so_kg" value="<?= $row['so_kg'] ?>" class="form-control form-control-sm" style="width:80px">
											<input type="number" step="0.5" name="diem_cong_vi_pham" value="<?= $row['diem_cong_vi_pham'] ?>" class="form-control form-control-sm" style="width:80px">
											<button type="submit" class="btn btn-sm btn-warning">📝</button>
										</form>
									<?php endif; ?>
								</td>
							<?php endif; ?>

						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>

</body>

</html>
<?php require_once '../../../includes/footer.php'; ?>

<script>
	document.querySelectorAll('.update-score-form').forEach(form => {
		form.addEventListener('submit', function(e) {
			e.preventDefault();
			const id = form.getAttribute('data-id');
			const so_kg = form.querySelector('input[name="so_kg"]').value;
			const diem_vi_pham = form.querySelector('input[name="diem_cong_vi_pham"]').value;

			fetch('update_schedule_score.php', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded'
					},
					body: `id=${id}&so_kg=${so_kg}&diem_cong_vi_pham=${diem_vi_pham}`
				})
				.then(res => res.text())
				.then(msg => {
					alert(msg);

					// Nếu thành công, cập nhật giá trị hiển thị tại dòng tương ứng
					if (msg.includes("Cập nhật thành công")) {
						document.getElementById('so-kg-' + id).textContent = so_kg;
						document.getElementById('vi-pham-' + id).textContent = diem_vi_pham;

						// Tô màu nền hàng vừa cập nhật (optional)
						form.closest('tr').classList.add('table-success');
						setTimeout(() => {
							form.closest('tr').classList.remove('table-success');
						}, 2000);
					}
				});
		});
	});
</script>