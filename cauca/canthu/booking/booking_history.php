<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';
require_once __DIR__ . '/../../../includes/header.php';

// Lấy ID user đang đăng nhập
$user_id = $_SESSION['user']['id'];

$stmt = $pdo->prepare("SELECT b.*, h.ten_ho, g.ten_bang_gia
    FROM booking b
    JOIN ho_cau h ON b.ho_cau_id = h.id
    JOIN gia_ca_thit_phut g ON b.gia_id = g.id
    WHERE b.can_thu_id = ?
    ORDER BY b.booking_time DESC");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll();
?>

<div class="container py-4">
    <h2 class="mb-4">Lịch sử đặt vé</h2>
    <?php if (count($bookings) === 0): ?>
        <div class="alert alert-info">Bạn chưa có booking nào.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Mã booking</th>
                        <th>Hồ câu</th>
                        <th>Thời gian</th>
                        <th>Số suất</th>
                        <th>Giờ thêm</th>
                        <th>Bảng giá</th>
                        <th>Trạng thái</th>
                        <th>Thanh toán</th>
                        <th>Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $index => $b): ?>
                        <?php
                            $badge = 'secondary';
                            if ($b['main_status'] === 'hoàn thành') $badge = 'success';
                            elseif ($b['main_status'] === 'đang chạy') $badge = 'danger';
                            elseif ($b['main_status'] === 'đã huỷ') $badge = 'secondary';
                        ?>
                        <tr>
                            <td><a href="booking_detail.php?id=<?= $b['id'] ?>"><strong class="text-primary">B00-<?= $b['id'] ?></strong></a></td>
                            <td><?= htmlspecialchars($b['ten_ho']) ?></td>
                            <td>
                                <?= date('d/m/Y H:i', strtotime($b['booking_start_time'])) ?> -<br>
                                <?= date('d/m/Y H:i', strtotime($b['booking_end_time'])) ?>
                            </td>
                            <td><?= $b['so_suat'] ?> suất</td>
                            <td><?= $b['gio_them'] ?> phút</td>
                            <td><?= $b['ten_bang_gia'] ?></td>
                            <td><span class="badge bg-<?= $badge ?>"><?= ucfirst($b['main_status']) ?></span></td>
                            <td><?= number_format($b['booking_amount'], 0, ',', '.') ?>đ</td>
                            <td><a href="booking_detail.php?id=<?= $b['id'] ?>" class="btn btn-sm btn-outline-primary">Chi tiết</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
