<?php
require_once __DIR__ . '/../../connect.php';
require_once __DIR__ . '/../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'admin') {
    header("Location: /no_permission.php");
    exit;
}

// Lấy ID người dùng từ URL
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
echo "<div class='alert alert-info'>User ID từ URL: $user_id</div>"; // Hiển thị ID được lấy
error_log("User ID từ URL trong user_detail.php: $user_id");
error_log("Session user ID: " . ($_SESSION['user']['id'] ?? 'null'));

// Lấy thông tin người dùng
$stmt = $pdo->prepare("SELECT id, phone, nickname, email, vai_tro, full_name, bank_account, bank_name, CCCD_number, balance, balance_ref, ref_code, user_exp, user_lever, user_note, review_status, created_at, status FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Debug: Kiểm tra dữ liệu trả về và so sánh ID
if (!$user) {
    error_log("Không tìm thấy người dùng với ID: $user_id. Kết nối PDO: " . ($pdo ? 'OK' : 'FAILED') . ", Query: " . $stmt->queryString . ", Params: " . print_r([$user_id], true));
    echo "<div class='alert alert-danger'>Không tìm thấy thông tin người dùng với ID: $user_id.</div>";
} else {
    error_log("Dữ liệu user ID $user_id trong user_detail.php (ID trả về: " . ($user['id'] ?? 'null') . "): " . print_r($user, true));
    if (isset($user['id']) && $user['id'] != $user_id) {
        error_log("Cảnh báo: ID từ URL ($user_id) không khớp với ID trong dữ liệu (" . $user['id'] . ")");
        echo "<div class='alert alert-warning'>Cảnh báo: ID từ URL ($user_id) không khớp với ID trong dữ liệu (" . $user['id'] . ").</div>";
    }
}
?>

<?php include_once __DIR__ . '/../../includes/header.php'; ?>

<div class="container mt-4">
    <h3 class="mb-4">👤 Thông tin chi tiết người dùng</h3>

    <?php if (!$user): ?>
        <div class="alert alert-danger">Không tìm thấy thông tin người dùng. <a href="users_list.php" class="btn btn-sm btn-secondary">Quay lại danh sách</a></div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-striped">
                    <tbody>
                        <tr><th>ID</th><td><?= htmlspecialchars($user['id'] ?? '') ?></td></tr>
                        <tr><th>Số điện thoại</th><td><?= htmlspecialchars($user['phone'] ?? '') ?></td></tr>
                        <tr><th>Biệt danh</th><td><?= htmlspecialchars($user['nickname'] ?? '') ?></td></tr>
                        <tr><th>Email</th><td><?= htmlspecialchars($user['email'] ?? '') ?></td></tr>
                        <tr><th>Vai trò</th><td><?= htmlspecialchars($user['vai_tro'] ?? '') ?></td></tr>
                        <tr><th>Họ và tên</th><td><?= htmlspecialchars($user['full_name'] ?? '') ?></td></tr>
                        <tr><th>Số tài khoản</th><td><?= htmlspecialchars($user['bank_account'] ?? '') ?></td></tr>
                        <tr><th>Tên chủ tài khoản</th><td><?= htmlspecialchars($user['bank_name'] ?? '') ?></td></tr>
                        <tr><th>Số CCCD</th><td><?= htmlspecialchars($user['CCCD_number'] ?? '') ?></td></tr>
                        <tr><th>Số dư</th><td><?= htmlspecialchars((string)($user['balance'] ?? '')) ?> VND</td></tr>
                        <tr><th>Số dư giới thiệu</th><td><?= htmlspecialchars((string)($user['balance_ref'] ?? '')) ?> VND</td></tr>
                        <tr><th>Mã giới thiệu</th><td><?= htmlspecialchars($user['ref_code'] ?? '') ?></td></tr>
                        <tr><th>Điểm kinh nghiệm</th><td><?= htmlspecialchars($user['user_exp'] ?? '') ?></td></tr>
                        <tr><th>Cấp bậc</th><td><?= htmlspecialchars($user['user_lever'] ?? '') ?></td></tr>
                        <tr><th>Ghi chú</th><td><?= htmlspecialchars($user['user_note'] ?? '') ?></td></tr>
                        <tr><th>Trạng thái đánh giá</th><td><?= isset($user['review_status']) && $user['review_status'] === 'yes' ? 'Đã đánh giá' : 'Chưa đánh giá' ?></td></tr>
                        <tr><th>Ngày tạo</th><td><?= isset($user['created_at']) ? date('d/m/Y H:i', strtotime($user['created_at'])) : '' ?></td></tr>
                        <tr><th>Trạng thái</th><td>
                            <span class="badge <?= isset($user['status']) && $user['status'] === 'Đã xác minh' ? 'bg-success' : (isset($user['status']) && $user['status'] === 'banned' ? 'bg-danger' : 'bg-warning') ?>">
                                <?= isset($user['status']) ? $user['status'] : 'Chưa xác minh' ?>
                            </span>
                        </td></tr>
                    </tbody>
                </table>
                <a href="users_list.php" class="btn btn-secondary">Quay lại danh sách</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>