<?php
session_start();
if (isset($_SESSION['user']['id'])) {
    header("Location: ../dashboard/index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng nhập | Câu Cá VIP</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .login-container {
      margin-top: 80px;
    }
  </style>
</head>
<body>
  <div class="container login-container">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card shadow-lg">
          <div class="card-header bg-primary text-white text-center">
            <h4>Đăng nhập hệ thống</h4>
          </div>
          <div class="card-body">
            <?php if (isset($_GET['error'])): ?>
              <div class="alert alert-danger text-center">
                <?= htmlspecialchars($_GET['error']) ?>
              </div>
            <?php endif; ?>
            <?php if (isset($_GET['msg']) && $_GET['msg'] === 'logout'): ?>
              <div class="alert alert-info text-center">
                Bạn đã đăng xuất thành công.
              </div>
            <?php endif; ?>
            <form action="process_login.php" method="POST">
              <div class="mb-3">
                <label for="phone" class="form-label">📱 Số điện thoại</label>
                <input type="text" class="form-control" id="phone" name="phone" placeholder="Nhập số điện thoại" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">🔒 Mật khẩu</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu" required>
              </div>
              <button type="submit" class="btn btn-success w-100">🚀 Đăng nhập</button>
            </form>
            <div class="mt-3 text-center small">
              <a href="forgot_password/step1_send_otp.php">🔁 Quên mật khẩu?</a> |
              <a href="register/step1_role.php">📝 Đăng ký tài khoản</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
