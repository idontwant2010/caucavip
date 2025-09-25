<?php
require_once __DIR__ . '/../../connect.php';
require_once __DIR__ . '/../../check_login.php';

$user_id = $_SESSION['user']['id'] ?? 0;
$role = $_SESSION['user']['vai_tro'] ?? '';

if (!in_array($role, ['admin', 'chuho', 'canthu'])) {
    die("Không có quyền truy cập.");
}

$stmt = $pdo->prepare("SELECT u.nickname, l.* FROM user_balance_logs l 
    JOIN users u ON u.id = l.user_id
    WHERE l.user_id = ? ORDER BY l.created_at DESC LIMIT 100");
$stmt->execute([$user_id]);
$logs = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch sử thay đổi số dư</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h3 class="mb-4">Lịch sử thay đổi số dư của bạn</h3>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nickname</th>
                <th>Loại</th>
                <th>Số tiền</th>
                <th>Ghi chú</th>
                <th>Thời gian</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $index => $log): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($log['nickname']) ?></td>
                    <td><span class="badge bg-info"><?= $log['type'] ?></span></td>
                    <td><?= number_format($log['amount']) ?> đ</td>
                    <td><?= htmlspecialchars($log['note']) ?></td>
                    <td><?= $log['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
