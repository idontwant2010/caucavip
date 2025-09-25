<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

$booking_id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT b.*, g.gia_thu_lai, g.gia_ban_ca, g.base_duration, g.base_price, g.extra_unit_price FROM booking b JOIN gia_ca_thit_phut g ON b.gia_id = g.id WHERE b.id = ? LIMIT 1");
$stmt->execute([$booking_id]);
$booking = $stmt->fetch();

if (!$booking) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>Không tìm thấy booking</div></div>";
    exit;
}

if ($booking['main_status'] === 'hoàn thành') {
    echo "<div class='container mt-4'><div class='alert alert-info'>Booking đã hoàn thành, không thể chỉnh sửa</div></div>";
    exit;
}

include_once __DIR__ . '/../../../includes/header.php';
?>

<div class="container mt-4">
    <h3>Cập nhật booking <strong>B00-<?= $booking['id'] ?></strong></h3>
    <form action="booking_update_process.php" method="post" id="updateForm">
        <input type="hidden" name="id" value="<?= $booking['id'] ?>">

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Hình thức thanh toán</label>
                <select name="payment_method" class="form-select">
                    <option value="Tiền mặt" <?= $booking['payment_method'] == 'Tiền mặt' ? 'selected' : '' ?>>Tiền mặt tại hồ</option>
                    <option value="Qr-code" <?= $booking['payment_method'] == 'Qr-code' ? 'selected' : '' ?>>QR-Code - Chuyển khoản bank chủ hồ</option>
                    <option value="Số dư user" <?= $booking['payment_method'] == 'Số dư user' ? 'selected' : '' ?>>Số dư User trên hệ thống</option>					
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Giờ bắt đầu thực tế</label>
                <input type="datetime-local" name="real_start_time" value="<?= date('Y-m-d\TH:i', strtotime($booking['real_start_time'] ?? $booking['booking_start_time'])) ?>" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Giờ kết thúc thực tế</label>
                <input type="datetime-local" name="real_end_time" value="<?= date('Y-m-d\TH:i', strtotime($booking['real_end_time'] ?? $booking['booking_end_time'])) ?>" class="form-control">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Tổng khối lượng cá bắt được (kg)</label>
                <input type="number" step="0.1" name="fish_weight" value="<?= $booking['fish_weight'] ?>" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Khối lượng cá mang về (kg)</label>
                <input type="number" step="0.1" name="fish_sell_weight" value="<?= $booking['fish_sell_weight'] ?>" class="form-control">
            </div>
        </div>


        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="booking_list.php" class="btn btn-secondary">Huỷ cập nhật</a>
    </form>
</div>

<script>
document.getElementById('updateForm').addEventListener('submit', function (e) {
    const start = new Date(document.querySelector('[name=real_start_time]').value);
    const end = new Date(document.querySelector('[name=real_end_time]').value);
    if (end <= start) {
        e.preventDefault();
        alert('Giờ kết thúc phải lớn hơn giờ bắt đầu.');
    }
});
</script>

<?php include_once __DIR__ . '/../../../includes/footer.php'; ?>
