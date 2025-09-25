<?php
require_once __DIR__ . '/../../connect.php';


$error = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $phone = trim($_POST['phone'] ?? '');
    if (!preg_match('/^0[0-9]{9}$/', $phone)) {
        $error = "Số điện thoại không hợp lệ!";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE phone = ?");
        $stmt->execute([$phone]);
        if ($stmt->fetch()) {
            header("Location: step2_reset_pass.php?phone=" . urlencode($phone));
            exit;
        } else {
            $error = "Số điện thoại không tồn tại trong hệ thống!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quên mật khẩu - Bước 1 | Câu Cá VIP</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow">
        <div class="card-header bg-warning text-dark text-center">
          <h4>🔐 Quên mật khẩu</h4>
        </div>
        <div class="card-body">
          <?php if ($error): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>
          <form action="" method="post">
            <div class="mb-3">
              <label for="phone" class="form-label">📱 Nhập số điện thoại</label>
              <input type="text" class="form-control" name="phone" required pattern="^0[0-9]{9}$" placeholder="Nhập số điện thoại 10 số">
            </div>
            <button type="submit" class="btn btn-warning w-100">Gửi mã OTP</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
