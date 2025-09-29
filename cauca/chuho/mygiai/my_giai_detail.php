<?php
require_once '../../../connect.php';
require_once '../../../check_login.php';
require_once '../../../includes/header.php';


if ($_SESSION['user']['vai_tro'] !== 'chuho') {
	echo "Truy cập bị từ chối.";
	exit;
}

$giai_id = (int)($_POST['giai_id'] ?? $_GET['id'] ?? 0);
$user_id = $_SESSION['user']['id'];

// Lấy thông tin giải
$stmt = $pdo->prepare("SELECT * FROM giai_list WHERE id = ? AND creator_id = ?");
$stmt->execute([$giai_id, $user_id]);
$giai = $stmt->fetch();

// Lấy số user đã đăng ký giải
$stmt = $pdo->prepare("SELECT COUNT(*) FROM giai_user WHERE giai_id = ?");
$stmt->execute([$giai_id]);
$so_can_thu_da_dang_ky = (int) $stmt->fetchColumn();

if (!$giai) {
	echo "Giải không tồn tại hoặc không hợp lệ.";
	exit;
}

// Lấy số dư
$stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user_balance = (int)$stmt->fetchColumn();

// Nếu gửi POST xác nhận mở giải
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if ($giai['status'] !== 'dang_cho_xac_nhan') {
		echo "Trạng thái giải không hợp lệ để xác nhận.";
		exit;
	}

	$phi_giai = (int)$giai['phi_giai'];
	if ($user_balance < $phi_giai) {
		echo "⚠️ Số dư không đủ để xác nhận tổ chức giải. Cần: " . number_format($phi_giai) . "đ, hiện có: " . number_format($user_balance) . "đ.";
		exit;
	}
	// Kiểm tra ngày tổ chức phải cách hôm nay ít nhất 7 ngày
	$ngay_to_chuc = new DateTime($giai['ngay_to_chuc']);
	$ngay_hien_tai = new DateTime();
	$khoang_cach = $ngay_hien_tai->diff($ngay_to_chuc)->days;

	if ($ngay_to_chuc < $ngay_hien_tai || $khoang_cach < 6) {

		$ngay_hien_tai = new DateTime();
		$ngay_hien_tai->modify('+6 day');
		$ngay_gioi_han = $ngay_hien_tai->format('d/m/Y');

		$link_back = "my_giai_edit.php?id=" . urlencode($giai_id);

		echo '⛔ Bạn cần đặt trước ít nhất 7 ngày trước khi mở giải. Ngày tổ chức phải từ <strong>' . $ngay_gioi_han . '</strong> trở đi.<br>';
		echo '👉 <a href="' . $link_back . '">Quay lại chỉnh sửa giải</a>';
		exit;
	}

	// Trừ tiền
	$stmt = $pdo->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
	$stmt->execute([$phi_giai, $user_id]);

	$balance_sau = $user_balance - $phi_giai;

	// Ghi log
	$stmt = $pdo->prepare("
		INSERT INTO user_balance_logs (
			user_id, ref_no, type, amount, note, created_at,
			balance_before, balance_after
		) VALUES (
			?, ?, 'giai_pay', ?, ?, NOW(), ?, ?
		)
	");

	$note = "Trừ phí tổ chức giải ID #$giai_id, số dư hiện tại " . number_format($balance_sau, 0, ',', '.') . " vnd";

	$stmt->execute([
		$user_id,
		"giai_$giai_id",     // ref_no
		$phi_giai,
		$note,
		$user_balance,       // balance_before
		$balance_sau         // balance_after
	]);

	// Cập nhật trạng thái giải
	$stmt = $pdo->prepare("UPDATE giai_list SET status = 'chuyen_chu_ho_duyet' WHERE id = ?");
	$stmt->execute([$giai_id]);

	header("Location: my_giai_detail.php?id=" . $giai_id);
	exit;
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
		<?php if ($giai['status'] === 'dang_cho_xac_nhan'): ?>
			<div class="mt-4 alert alert-success">
				<p>🎯 Giải của bạn cần gừi cho chủ hồ để duyệt 1 số nội dung như: ngày giờ tổ chức, số lượng bảng, hiệp đấu, thể lệ thi đấu...</p>
				<p>🎯 Bạn cần liên hệ chủ hồ để có thêm thông tin, và chỉnh sửa cho phù hợp trước khi gửi duyệt và mở giải</p>

				<div class="d-flex justify-content-center">
					<form method="post" action="my_giai_detail.php" onsubmit="return confirm('Gửi xác nhận cho chủ hồ và tạm hold phí tổ chức giải: - <?= number_format($giai['phi_giai']) ?> vnd ?')">
						<input type="hidden" name="giai_id" value="<?= $giai['id'] ?>">
						<div class="d-flex gap-2">
							<button type="submit" class="btn btn-success">✅ Gửi thông tin cho chủ hồ duyệt</button>
							<a href="my_giai_edit.php?id=<?= $giai['id'] ?>" class="btn btn-danger">✏️ Chỉnh sửa giải trước khi gửi duyệt</a>
						</div>
					</form>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($giai['status'] === 'chuyen_chu_ho_duyet'): ?>
			<div class="mt-4 alert alert-info">
				<p>🎯 Giải của 1 cần thủ bình thường sẽ gửi chủ hồ để duyệt, nhưng... bạn là chủ hồ</p>
				<p>🎯 Tuyệt vời, vì bạn là chủ hồ luôn nên có thể vào đây để duyệt ngay! </p>
				<div class="text-center mt-3">
					<a href="http://caucavip.test/cauca/chuho/giai/giai_can_duyet.php" class="btn btn-primary">
						👉 Tự duyệt giải của tôi luôn!
					</a>
				</div>
			</div>
		<?php endif; ?>

		<?php if (in_array($giai['status'], ['dang_mo_dang_ky'])): ?>
			<div class="d-flex justify-content-center gap-3 mt-2">
				<a href="my_giai_detail_step_1.php?id=<?= $giai_id ?>" class="btn btn-info">
					Qua bước B1: Thêm cần thủ vào Giải đang mở đăng ký!
				</a>
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

		<div class="card mt-3">
			<div class="card-header bg-secondary text-white">
				<h6 class="mb-1">📋 Dưới đây là thông tin giải câu: <strong style="color: pink;"> <?= htmlspecialchars($giai['ten_giai']) ?></strong></h6>
			</div>
			<div class="card-body">
				<dl class="row">
					<dt class="col-sm-4">Tên giải</dt>
					<dd class="col-sm-8 fw-bold text-dark"><?= htmlspecialchars($giai['ten_giai']) ?></dd>

					<dt class="col-sm-4">Ngày tổ chức</dt>
					<dd class="col-sm-8"><?= date('d/m/Y', strtotime($giai['ngay_to_chuc'])) ?></dd>

					<dt class="col-sm-4">Giờ bắt đầu</dt>
					<dd class="col-sm-8"><?= htmlspecialchars($giai['gio_bat_dau']) ?></dd>

					<dt class="col-sm-4">Số cần thủ</dt>
					<dd class="col-sm-8"><?= $giai['so_luong_can_thu'] ?></dd>

					<dt class="col-sm-4">Số bảng / Số hiệp</dt>
					<dd class="col-sm-8"><?= $giai['so_bang'] ?> bảng, <?= $giai['so_hiep'] ?> hiệp</dd>

					<dt class="col-sm-4">Thời lượng / hiệp</dt>
					<dd class="col-sm-8"><?= $giai['thoi_luong_phut_hiep'] ?> phút</dd>

					<dt class="col-sm-4">Tiền cược</dt>
					<dd class="col-sm-8 text-success fw-semibold"><?= number_format($giai['tien_cuoc']) ?>đ</dd>

					<dt class="col-sm-4">Phí tổ chức</dt>
					<dd class="col-sm-8 text-danger fw-semibold"><?= number_format($giai['phi_giai']) ?>đ</dd>

					<dt class="col-sm-4">Trạng thái</dt>
					<dd class="col-sm-8"><span class="badge bg-warning text-dark"><?= $giai['status'] ?></span></dd>
				</dl>
			</div>
		</div>

		<?php if ($giai['status'] === 'dang_cho_xac_nhan'): ?>
			<div class="mt-4">
				<?php if ($user_balance < $giai['phi_giai']): ?>
					<div class="alert alert-danger">
						<p>⚠️ Số dư không đủ để xác nhận tổ chức giải.</p>
						<p>💰 Phí tổ chức: <strong><?= number_format($giai['phi_giai']) ?>đ</strong><br>
							💼 Số dư hiện tại: <strong><?= number_format($user_balance) ?>đ</strong></p>
						<a href="../payment/payment_deposit.php" class="btn btn-warning">👉 Nạp thêm tiền</a>
					</div>
				<?php else: ?>
					<div class="alert alert-info">
						💰 Phí tổ chức giải: <strong><?= number_format($giai['phi_giai']) ?>đ</strong><br>
						💼 Số dư hiện tại: <strong><?= number_format($user_balance) ?>đ</strong>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

	</div>
</body>

</html>