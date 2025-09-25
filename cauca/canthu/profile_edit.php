<?php
require_once __DIR__ . '/../../connect.php';
require_once __DIR__ . '/../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'canthu') {
    header("Location: /no_permission.php");
    exit;
}

$user_id = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, nickname = ? WHERE id = ?");
    $stmt->execute([
        $_POST['full_name'] ?? '',
        $_POST['email'] ?? '',
        $_POST['nickname'] ?? '',
        $user_id
    ]);

    $_SESSION['success'] = "Cập nhật thành công!";
    header("Location: profile.php");
    exit;
}

$stmt = $pdo->prepare("SELECT phone, full_name, email, nickname, ref_code, user_exp, user_lever, status, created_at FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    die("Không tìm thấy thông tin người dùng.");
}

// Gán biến an toàn
$phone         = $user['phone'] ?? '';
$full_name     = $user['full_name'] ?? '';
$email         = $user['email'] ?? '';
$nickname      = $user['nickname'] ?? '';
$ref_code      = $user['ref_code'] ?? '';
$user_exp      = $user['user_exp'] ?? 0;
$user_lever    = $user['user_lever'] ?? 0;
$status        = $user['status'] ?? '';
$created_at    = $user['created_at'] ?? '';
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>

<div class="col-md-8 offset-md-2">
  <h2 class="mb-4 text-center">📝 Cập nhật thông tin cá nhân</h2>
  <form method="POST" class="shadow-sm p-4 border rounded bg-light">

    <!-- Không cho sửa -->
    <div class="mb-3">
      <label class="form-label">📱 Số điện thoại</label>
      <input type="text" class="form-control text-danger fw-bold" value="<?= htmlspecialchars($phone) ?>" disabled>
    </div>

    <!-- Các trường cho phép sửa -->
    <div class="mb-3">
      <label class="form-label">👤 Họ và tên</label>
      <input type="text" class="form-control text-danger" name="full_name" value="<?= htmlspecialchars($full_name) ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">📧 Email</label>
      <input type="email" class="form-control text-danger" name="email" value="<?= htmlspecialchars($email) ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">🏷️ Nickname</label>
      <input type="text" class="form-control text-danger" name="nickname" value="<?= htmlspecialchars($nickname) ?>">
    </div>



    <button type="submit" class="btn btn-primary w-100">💾 Lưu thay đổi</button>
    <div class="mt-3 text-center">
      <a href="profile.php" class="text-muted">← Quay lại hồ sơ</a>
    </div>
  </form>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
