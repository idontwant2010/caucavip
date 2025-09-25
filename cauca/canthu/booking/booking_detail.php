<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';
require_once __DIR__ . '/../../../includes/header.php';

// Lấy ID booking từ URL
$booking_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user']['id'];

if (!empty($_SESSION['so_luot_booking'])) {
    echo "<div class='alert alert-info'>Số lượt đã đặt trong ngày: " . $_SESSION['so_luot_booking'] . "</div>";
    unset($_SESSION['so_luot_booking']); // Xoá sau khi hiển thị 1 lần
}

$stmt = $pdo->prepare("SELECT b.*, h.ten_ho, g.ten_bang_gia, u.full_name AS ten_chu_ho
    FROM booking b
    JOIN ho_cau h ON b.ho_cau_id = h.id
    JOIN gia_ca_thit_phut g ON b.gia_id = g.id
    JOIN users u ON b.chu_ho_id = u.id
    WHERE b.id = ? AND b.can_thu_id = ?");
$stmt->execute([$booking_id, $user_id]);
$booking = $stmt->fetch();

if (!$booking) {
    echo '<div class="container py-4"><div class="alert alert-danger">Không tìm thấy booking.</div></div>';
    require_once __DIR__ . '/../../../includes/footer.php';
    exit;
}

$badge = 'secondary';
if ($booking['main_status'] === 'hoàn thành') $badge = 'success';
elseif ($booking['main_status'] === 'đang chạy') $badge = 'danger';
elseif ($booking['main_status'] === 'đã huỷ') $badge = 'secondary';

$badge_tt = 'secondary';
if ($booking['booking_status'] === 'Chờ chuyển') $badge_tt = 'danger';
elseif ($booking['booking_status'] === 'Đã chuyển') $badge_tt = 'success';
?>

<div class="container py-4">
    <h3 class="mb-4">Chi tiết booking #<?= $booking['id'] ?></h3>
    <div class="row">
        <div class="col-md-6">
            <ul class="list-group">
                <li class="list-group-item"><strong>Hồ câu:</strong> <?= htmlspecialchars($booking['ten_ho']) ?></li>
                <li class="list-group-item"><strong>Chủ hồ:</strong> <?= htmlspecialchars($booking['ten_chu_ho']) ?></li>
                <li class="list-group-item"><strong>Thời gian:</strong><br>
                    <?= date('d/m/Y H:i', strtotime($booking['booking_start_time'])) ?> → 
                    <?= date('d/m/Y H:i', strtotime($booking['booking_end_time'])) ?>
                </li>
                <li class="list-group-item"><strong>Số suất:</strong> <?= $booking['so_suat'] ?> suất</li>
                <li class="list-group-item"><strong>Giờ thêm:</strong> <?= $booking['gio_them'] ?> phút</li>
                <li class="list-group-item"><strong>Bảng giá:</strong> <?= $booking['ten_bang_gia'] ?></li>
                <li class="list-group-item"><strong>Trạng thái chính:</strong> 
                    <span class="badge bg-<?= $badge ?>"><?= ucfirst($booking['main_status']) ?></span>
                </li>
				<li class="list-group-item">
					<strong>Trạng thái thanh toán:</strong>
					<span class="badge bg-<?= $badge_tt ?>"><?= $booking['booking_status'] ?></span>
				</li>

                <li class="list-group-item"><strong>Số tiền cần chuyển:</strong> <?= number_format($booking['booking_amount'], 0, ',', '.') ?>đ</li>
            </ul>

            <?php if ($booking['booking_status'] === 'Chờ chuyển'): ?>
                <div class="mt-3">
                    <a href="#" class="btn btn-primary w-100">Chuyển tiền booking</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
