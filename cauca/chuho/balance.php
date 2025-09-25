<?php
require_once __DIR__ . '/../../connect.php';
require_once __DIR__ . '/../../check_login.php';

// Chỉ cho phép chủ hồ
if ($_SESSION['user']['vai_tro'] !== 'chuho') {
    header('Location: /no_permission.php');
    exit;
}

$user_id = $_SESSION['user']['id'];

// Lấy số dư
$stmt = $pdo->prepare("SELECT balance FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$balance = isset($user['balance']) ? (float)$user['balance'] : 0;

// Lấy lịch sử giao dịch
$stmt = $pdo->prepare("SELECT * FROM user_balance_logs WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->execute(['user_id' => $user_id]);
$logs = $stmt->fetchAll();
?>

<?php include '../../includes/header.php'; ?>

<div class="container mt-4">
    <h3 class="mb-4">Số dư tài khoản: <span class="text-success"><?= number_format($balance) ?> đ</span></h3>

    <div class="card">
        <div class="card-header bg-primary text-white">
            Lịch sử giao dịch
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Thời gian</th>
                        <th>Loại giao dịch</th>
                        <th>Số tiền</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($logs) === 0): ?>
                        <tr><td colspan="4" class="text-center">Chưa có giao dịch nào</td></tr>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td><?= date('H:i d/m/Y', strtotime($log['created_at'])) ?></td>
                                <td>
                                    <?php
                                        switch ($log['type']) {
											case 'nap': echo 'Nạp tiền qua bank'; break;
											case 'rut': echo 'Rút tiền qua bank'; break;

										    case 'booking_hold': echo 'Giữ chỗ booking'; break;
										    case 'booking_pay': echo 'Thanh toán cho cần thủ'; break;
										    case 'booking_refund': echo 'Hoàn lại booking'; break;
										    case 'booking_received': echo 'Nhận thanh toán'; break;

										    case 'game_pay': echo 'Trừ phí game'; break;
										    case 'game_received': echo 'Nhận thưởng game'; break;

 										   default: echo htmlspecialchars($log['type']);
										}

                                    ?>
                                </td>
                                <td class="<?= $log['amount'] < 0 ? 'text-danger' : 'text-success' ?>">
                                    <?= number_format($log['amount']) ?> đ
                                </td>
                                <td><?= htmlspecialchars($log['note']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
