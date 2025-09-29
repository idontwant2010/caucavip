<?php
require_once '../../../connect.php';
require_once '../../../check_login.php';
require_once '../../../includes/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
	echo "<div class='alert alert-danger'>Thiếu ID giải đấu.</div>";
	require_once '../../../includes/footer.php';
	exit;
}

$giai_id = (int)$_GET['id'];

// Lấy thông tin giải
$stmt = $pdo->prepare("SELECT * FROM giai_list WHERE id = ?");
$stmt->execute([$giai_id]);
$giai = $stmt->fetch();



if (!$giai) {
	echo "<div class='alert alert-danger'>Giải không tồn tại.</div>";
	require_once '../../../includes/footer.php';
	exit;
}

if ($giai['creator_id'] != $_SESSION['user']['id']) {
	echo "<div class='alert alert-danger'>Bạn không có quyền truy cập giải này.</div>";
	require_once '../../../includes/footer.php';
	exit;
}

// Lấy danh sách người tham gia
$stmt = $pdo->prepare("SELECT gu.*, u.full_name, u.phone, u.nickname FROM giai_user gu JOIN users u ON gu.user_id = u.id WHERE gu.giai_id = ? ORDER BY gu.id DESC");
$stmt->execute([$giai_id]);
$list_users = $stmt->fetchAll();

// Kiểm tra số lượng đã đăng ký
$stmt = $pdo->prepare("SELECT COUNT(*) FROM giai_user WHERE giai_id = ?");
$stmt->execute([$giai_id]);
$so_nguoi_da_tham_gia = (int) $stmt->fetchColumn();

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

		<?php if ($giai['status'] === 'dang_mo_dang_ky'): ?>
			<div class="text-center my-4">
				<h5 class="mb-3">➕ Thêm cần thủ vào giải đấu:</h5>

				<form action="them_guest.php" method="POST" class="row justify-content-center g-2">
					<input type="hidden" name="giai_id" value="<?= $giai_id ?>">

					<div class="col-md-3">
						<input type="text" name="phone" class="form-control" placeholder="Số điện thoại" required>
					</div>
					<div class="col-md-3">
						<input type="text" name="full_name" class="form-control" placeholder="Họ tên cần thủ" required>
					</div>
					<div class="col-md-2">
						<button type="submit" class="btn btn-primary w-100">Thêm vào giải</button>
					</div>
				</form>
			</div>
		<?php endif; ?>

		<div class="d-flex justify-content-center gap-3 mt-2">
			<?php if (in_array($giai['status'], ['chot_xong_danh_sach', 'dang_dau_hiep_1', 'dang_dau_hiep_2', 'dang_dau_hiep_3', 'dang_dau_hiep_4'])): ?>
				<a href="my_giai_detail_step_2.php?id=<?= $giai_id ?>" class="btn btn-info">
					Qua bước B2: Chia bảng, thi đấu và cập nhật điểm!
				</a>
			<?php endif; ?>
		</div>

		<?php if (in_array($giai['status'], ['so_ket_giai'])): ?>
			<div class="d-flex justify-content-center gap-3 mt-2 ">
				<a href="my_giai_detail_step_2.php?id=<?= $giai_id ?>" class="btn btn-secondary">
					Đến bước B2: Sửa thành tích bảng A-B-C-D
				</a>
			</div>
		<?php endif; ?>

		<h6 class="mt-2">🎯 Danh sách tham gia giải: <strong style="color: green;"><?= htmlspecialchars($giai['ten_giai']) ?></strong></h6>
		<table class="table table-bordered mt-2">
			<thead class="table-light">
				<tr>
					<th>STT</th>
					<th>Họ tên</th>
					<th>SĐT</th>
					<th>Nickname</th>
					<th>Ghi chú</th>
					<th>Thêm lúc</th>
					<th>Trạng thái</th>
					<th>Hành động</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($list_users as $index => $row): ?>
					<tr>
						<td><?= $index + 1 ?></td>
						<td><?= htmlspecialchars($row['full_name']) ?></td>
						<td><?= htmlspecialchars($row['phone']) ?> </td>
						<td><?= htmlspecialchars($row['nickname']) ?>_<?= htmlspecialchars($row['user_id']) ?></td>
						<td><?= htmlspecialchars($row['note'] ?? '') ?></td>
						<td><?= $row['created_at'] ?></td>
						<?php
						$status_map = [
							'moi_cho_phan_hoi' => ['label' => 'Được chủ giải thêm, chờ người dùng xác nhận', 'class' => 'warning'],
							'moi_da_tu_choi'   => ['label' => 'Người dùng từ chối lời mời', 'class' => 'danger'],
							'da_thanh_toan'    => ['label' => 'Đã tham gia, đã thanh toán', 'class' => 'success']
						];

						$st = $row['trang_thai'] ?? '';
						if (isset($status_map[$st])) {
							$label = $status_map[$st]['label'];
							$class = $status_map[$st]['class'];
						} else {
							$label = htmlspecialchars($st);
							$class = 'secondary';
						}
						?>
						<td><span class="badge bg-<?= $class ?>"><?= $label ?></span></td>

						<td>
							<?php if ($giai['status'] === 'dang_mo_dang_ky'): ?>
								<a href="xoa_user.php?giai_id=<?= $giai_id ?>&user_id=<?= $row['user_id'] ?>" class="btn btn-sm btn-danger">Xoá</a>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<hr>

		<form action="chot_danh_sach.php" method="POST" class="d-flex gap-2 justify-content-center">
			<input type="hidden" name="giai_id" value="<?= $giai['id'] ?>">

			<?php if ($giai['status'] === 'dang_mo_dang_ky'): ?>
				<button type="submit" name="action" value="accept" class="btn btn-success"
					onclick="return confirm('✅ Bạn có chắc muốn Chốt danh sách và tiến hành giải này không?')">
					✅ Chốt danh sách và lên lịch thi đấu
				</button>

				<a href="huy_giai_step_1.php?giai_id=<?= (int)$giai['id'] ?>"
					class="btn btn-danger"
					onclick="return confirm('Lấy danh sách cần thủ đăng ký online và hoàn tiền ❌ Huỷ giải !')">
					❌ Huỷ giải và hoàn tiền
				</a>
			<?php endif; ?>
		</form>
	</div>
</body>

</html>
<?php require_once '../../../includes/footer.php'; ?>