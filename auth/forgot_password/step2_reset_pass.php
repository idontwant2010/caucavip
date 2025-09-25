<?php
session_start();
$phone = $_GET['phone'] ?? '';
if (!preg_match('/^0[0-9]{9}$/', $phone)) {
  die("Số điện thoại không hợp lệ!");
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quên mật khẩu - Bước 2 | Câu Cá VIP</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow">
        <div class="card-header bg-success text-white text-center">
          <h4>🔁 Đặt lại mật khẩu</h4>
        </div>
        <div class="card-body">
          <form action="process_reset.php" method="post">
            <input type="hidden" name="phone" value="<?= htmlspecialchars($phone) ?>">
            <div class="mb-3">
              <label class="form-label">🔐 Mã OTP (mặc định: 123456)</label>
              <input type="text" name="otp" class="form-control" maxlength="6" required>
            </div>
            <div class="mb-3">
              <label class="form-label">🔒 Mật khẩu mới</label>
              <input type="password" name="password" class="form-control" minlength="8" required>
            </div>
            <div class="mb-3">
              <label class="form-label">✅ Xác nhận lại mật khẩu</label>
              <input type="password" name="confirm" class="form-control" minlength="8" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Đặt lại mật khẩu</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
