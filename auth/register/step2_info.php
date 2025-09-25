<?php
session_start();
$role = $_GET['role'] ?? '';
if (!in_array($role, ['canthu', 'chuho'])) {
    header("Location: step1_role.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng ký - Bước 2 | Câu Cá VIP</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .form-container {
      margin-top: 80px;
    }
  </style>
</head>
<body>
  <div class="container form-container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-header bg-success text-white text-center">
            <h4>Nhập thông tin đăng ký</h4>
<div class="small">
  (Vai trò: <strong>
    <?= $role === 'canthu' ? 'Cần thủ' : ($role === 'chuho' ? 'Chủ hồ' : '') ?>
  </strong>)
</div>
          </div>
          <div class="card-body">
            <form action="process_register.php" method="post">
              <input type="hidden" name="role" value="<?= htmlspecialchars($role) ?>">
              <div class="mb-3">
                <label for="phone" class="form-label">📱 Số điện thoại</label>
                <input type="text" class="form-control" name="phone" required pattern="^0[0-9]{9}$" placeholder="Nhập số điện thoại 10 số">
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">🔒 Mật khẩu</label>
                <input type="password" class="form-control" name="password" required minlength="8" placeholder="Ít nhất 8 ký tự">
              </div>
              <div class="mb-3">
                <label for="otp" class="form-label">🔐 Mã OTP (mặc định: <code>123456</code>)</label>
                <input type="text" class="form-control" name="otp" required maxlength="6" placeholder="Nhập mã OTP giả lập">
              </div>
              <button type="submit" class="btn btn-success w-100">🎉 Hoàn tất đăng ký</button>
            </form>
            <div class="text-center mt-3">
              <a href="step1_role.php" class="text-secondary">← Quay lại bước 1</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
