<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['vai_tro'] !== 'chuho') {
    header("Location: /caucavip/no_permission.php");
    exit();
}

$chu_ho_id = $_SESSION['user_id'];

try {
    $sql = "SELECT b.*, u.nickname AS can_thu_name, hc.ten_ho, b.booking_time, b.status
            FROM booking b
            JOIN users u ON b.can_thu_id = u.id
            JOIN ho_cau hc ON b.ho_cau_id = hc.id
            WHERE b.chu_ho_id = :chu_ho_id
            ORDER BY b.created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['chu_ho_id' => $chu_ho_id]);
    $bookings = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Lỗi truy vấn: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <h3 class="mb-4 text-primary">📅 Danh sách Booking tại các hồ của bạn</h3>

    <?php if (count($bookings) === 0): ?>
        <div class="alert alert-info">Chưa có booking nào.</div>
    <?php else: ?>
        <table class="table table-bordered table-hover bg-white">
            <thead class="table-secondary">
                <tr>
                    <th>#</th>
                    <th>Cần thủ</th>
                    <th>Hồ câu</th>
                    <th>Thời gian đặt</th>
                    <th>Thời lượng thực tế</th>
                    <th>Số kg cá</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $i => $b): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($b['can_thu_name']) ?></td>
                        <td><?= htmlspecialchars($b['ten_ho']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($b['booking_time'])) ?></td>
                        <td><?= $b['real_tong_thoi_luong'] ?> phút</td>
                        <td><?= $b['fish_weight'] ?> kg</td>
                        <td>
                            <?php
                            switch ($b['status']) {
                                case 'cho_chuyen_tien': echo '🕒 Chờ thanh toán'; break;
                                case 'da_nhan_tien': echo '💰 Đã thanh toán'; break;
                                case 'completed': echo '✅ Hoàn tất'; break;
                                case 'cancelled': echo '❌ Đã huỷ'; break;
                            }
                            ?>
                        </td>
                        <td>
                            <a href="booking_edit.php?id=<?= $b['id'] ?>" class="btn btn-sm btn-warning">✏️ Cập nhật</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
