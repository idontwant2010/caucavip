<?php
require_once __DIR__ . '/../../connect.php';
require_once __DIR__ . '/../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'chuho') {
    header("Location: /no_permission.php");
    exit;
}

$user_id = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    die("Không tìm thấy thông tin người dùng.");
}

// Gán giá trị an toàn để tránh lỗi undefined và null
$phone         = $user['phone'] ?? '';
$full_name     = $user['full_name'] ?? '';
$nickname      = $user['nickname'] ?? '';
$email         = $user['email'] ?? '';
$bank_account  = $user['bank_account'] ?? '';
$bank_name     = $user['bank_name'] ?? '';
$CCCD_number   = $user['CCCD_number'] ?? '';
$balance       = $user['balance'] ?? 0;
$ref_code      = $user['ref_code'] ?? '';
$user_exp      = $user['user_exp'] ?? 0;
$user_lever    = $user['user_lever'] ?? 0;
$status        = $user['status'] ?? '';
$review_status = $user['review_status'] ?? '';
$created_at    = $user['created_at'] ?? '';
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>

<div class="text-center mb-5">
  <h2 class="fw-bold">🏠 Hồ sơ chủ hồ</h2>
  <p class="text-muted">Thông tin đầy đủ tài khoản chủ hồ</p>
</div>

<div class="row justify-content-center">
  <div class="col-md-8">
    <ul class="list-group shadow-sm">

      <li class="list-group-item"><strong>📱 Số điện thoại:</strong> <span class="text-danger fw-bold"><?= htmlspecialchars($phone) ?></span></li>
      <li class="list-group-item"><strong>👤 Họ và tên:</strong> <?= htmlspecialchars($full_name) ?></li>
      <li class="list-group-item"><strong>🏷️ Nickname:</strong> <?= htmlspecialchars($nickname) ?></li>
      <li class="list-group-item"><strong>📧 Email:</strong> <?= htmlspecialchars($email) ?></li>
      <li class="list-group-item"><strong>🏦 Số tài khoản:</strong> <?= htmlspecialchars($bank_account) ?></li>
      <li class="list-group-item"><strong>💳 Tên chủ tài khoản:</strong> <?= htmlspecialchars($bank_name) ?></li>
      <li class="list-group-item"><strong>🆔 CCCD:</strong> <?= htmlspecialchars($CCCD_number) ?></li>

      <li class="list-group-item"><strong>🌟 Điểm kinh nghiệm:</strong> <span class="text-success fw-bold"><?= (int)$user_exp ?> EXP</span></li>
      <li class="list-group-item"><strong>📶 Cấp độ:</strong> <span class="text-success fw-bold">Cấp <?= (int)$user_lever ?></span></li>
      <li class="list-group-item"><strong>💰 Số dư tài khoản:</strong> <span class="text-success fw-bold"><?= number_format((float)$balance, 0, ',', '.') ?> đ</span></li>
      <li class="list-group-item"><strong>🔗 Mã giới thiệu:</strong> <?= htmlspecialchars($ref_code) ?></li>

      <li class="list-group-item"><strong>🔒 Trạng thái:</strong> <?= htmlspecialchars($status) ?></li>
      <li class="list-group-item"><strong>🧐 Duyệt tài khoản:</strong> <?= $review_status === 'yes' ? '✔️ Đã duyệt' : '⏳ Chờ duyệt' ?></li>
      <li class="list-group-item"><strong>📅 Ngày tham gia:</strong> <?= $created_at ? date('d/m/Y H:i', strtotime($created_at)) : '---' ?></li>
    </ul>

    <div class="text-center mt-4 d-flex justify-content-center gap-3">
      <a href="/auth/forgot_password/step1_send_otp.php" class="btn btn-outline-primary">Đổi mật khẩu</a>
      <a href="profile_edit.php" class="btn btn-outline-success">Cập nhật thông tin</a>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
