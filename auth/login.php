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
  <title>Đăng ký và Đăng nhập | Câu Đài Việt Nam</title>
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
      <div class="col-md-6">
        <div class="card shadow-lg">
          <div class="card-header bg-primary text-white text-center">
            <h4>Truy cập hệ thống</h4>
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

            <!-- Tabs -->
            <ul class="nav nav-tabs" id="loginTabs" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab">
                  Mật khẩu
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="zalo-otp-tab" data-bs-toggle="tab" data-bs-target="#zalo-otp" type="button" role="tab">
                  Zalo OTP
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="sms-otp-tab" data-bs-toggle="tab" data-bs-target="#sms-otp" type="button" role="tab">
                  SMS OTP
                </button>
              </li>
            </ul>

            <!-- Nội dung từng tab -->
            <div class="tab-content mt-3" id="loginTabsContent">
              <!-- Tab mật khẩu -->
              <div class="tab-pane fade show active" id="password" role="tabpanel">
                <form action="process_login.php" method="POST">
                  <div class="mb-3">
                    <label for="phone" class="form-label">📱 Số điện thoại</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Nhập số điện thoại" required>
                  </div>
                  <div class="mb-3">
                    <label for="password" class="form-label">🔒 Mật khẩu</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu" required>
                    <div class="form-text">Nếu quên mật khẩu, chưa đăng ký tài khoản, hãy đăng nhập bằng 'SMS OTP' hoặc 'Zalo OTP'</div>
				  </div>
                   
					
                  <button type="submit" class="btn btn-success w-100">🚀 Đăng nhập bằng mật khẩu</button>
                </form>
              </div>

              <!-- Tab Zalo OTP -->
              <div class="tab-pane fade" id="zalo-otp" role="tabpanel">
                <form action="process_login.php" method="POST" id="formZaloOtp">
                  <div class="mb-3">
                    <label for="phone_zalo" class="form-label">📱 Số điện thoại</label>
                    <div class="input-group">
                      <input type="text" class="form-control" id="phone_zalo" name="phone" placeholder="Nhập số điện thoại" required>
                      <button class="btn btn-outline-primary" type="button"
                        id="sendZaloBtn" data-type="zalo" data-seconds="60">
                        Gửi Zalo OTP
                      </button>
                    </div>
                    <small id="zaloOtpHelp" class="text-danger d-none"></small>
                  </div>

                  <div class="mb-2">
                    <label for="zalo_otp" class="form-label">🔑 OTP từ Zalo
                      <span id="zaloCountdown" class="ms-2 text-danger"></span>
                    </label>
                    <input type="text" class="form-control" id="zalo_otp" name="zalo_otp" placeholder="Nhập mã OTP" required>
                  </div>
                  <!-- New password -->
                  <div class="mb-3">
                    <label for="new_password_zalo" class="form-label">🔒 Chọn mật khẩu mới</label>
                    <input type="password" class="form-control" id="new_password_zalo" name="new_password" placeholder="Nhập mật khẩu mới (tùy chọn)">
                    <div class="form-text">Vui lòng đăng ký mật khẩu mới cho lần đăng nhập sau.</div>
                  </div>
                  <input type="hidden" name="login_method" value="zalo_otp">
                  <button type="submit" class="btn btn-primary w-100">✅ Đăng nhập bằng Zalo OTP</button>
                </form>
              </div>

              <!-- (Tùy chọn) Tab SMS OTP – nếu muốn giống hệt Zalo OTP -->
              <div class="tab-pane fade" id="sms-otp" role="tabpanel">
                <form action="process_login.php" method="POST" id="formSmsOtp">
                  <div class="mb-3">
                    <label for="phone_sms" class="form-label">📱 Số điện thoại</label>
                    <div class="input-group">
                      <input type="text" class="form-control" id="phone_sms" name="phone" placeholder="Nhập số điện thoại" required>
                      <button class="btn btn-outline-secondary" type="button"
                        id="sendSmsBtn" data-type="sms" data-seconds="60">
                        Gửi SMS OTP
                      </button>
                    </div>
                    <small id="smsOtpHelp" class="text-danger d-none"></small>
                  </div>
                  <div class="mb-2">
                    <label for="sms_otp" class="form-label">🔑 OTP từ SMS
                      <span id="smsCountdown" class="ms-2 text-danger"></span>
                    </label>
                    <input type="text" class="form-control" id="sms_otp" name="sms_otp" placeholder="Nhập mã OTP" required>
                  </div>
                  <!-- New password -->
                  <div class="mb-3">
                    <label for="new_password_zalo" class="form-label">🔒 Chọn mật khẩu mới</label>
                    <input type="password" class="form-control" id="new_password_zalo" name="new_password" placeholder="Nhập mật khẩu mới (tùy chọn)">
                    <div class="form-text">Vui lòng đăng ký mật khẩu mới cho lần đăng nhập sau.</div>
                  </div>

                  <input type="hidden" name="login_method" value="sms_otp">
                  <button type="submit" class="btn btn-warning w-100">📩 Đăng nhập bằng SMS OTP</button>
                </form>
              </div>


              <div class="mt-3 text-center small">
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


<script>
  // Regex đơn giản cho số ĐT VN: 10 số, bắt đầu 03/05/07/08/09
  function isValidPhone(p) {
    return /^0(3|5|7|8|9)\d{8}$/.test((p || '').trim());
  }

  // Countdown helper
  function startCountdown(btn, spanCountdown, seconds, storageKey) {
    let remaining = seconds;
    btn.disabled = true;

    // Lưu thời điểm hết hạn vào localStorage để giữ countdown khi F5
    const expireAt = Date.now() + remaining * 1000;
    if (storageKey) localStorage.setItem(storageKey, String(expireAt));

    spanCountdown.textContent = `Gửi lại sau: ${remaining}s`;
    const timer = setInterval(() => {
      remaining--;
      if (remaining <= 0) {
        clearInterval(timer);
        btn.disabled = false;
        spanCountdown.textContent = '';
        if (storageKey) localStorage.removeItem(storageKey);
        btn.textContent = btn.dataset.type === 'zalo' ? 'Gửi Zalo OTP' : 'Gửi SMS OTP';
        return;
      }
      spanCountdown.textContent = `Gửi lại sau: ${remaining}s`;
    }, 1000);
  }

  // Khôi phục countdown (nếu refresh trang)
  function restoreCountdown(btn, spanCountdown, seconds, storageKey) {
    const expireAt = Number(localStorage.getItem(storageKey) || 0);
    const now = Date.now();
    if (expireAt > now) {
      const remainMs = expireAt - now;
      const remainSec = Math.ceil(remainMs / 1000);
      startCountdown(btn, spanCountdown, remainSec, storageKey);
    }
  }

  async function handleSendOtp(btnId, phoneInputId, helpId, countdownId, storageKey) {
    const btn = document.getElementById(btnId);
    const phoneEl = document.getElementById(phoneInputId);
    const helpEl = document.getElementById(helpId);
    const countdownEl = document.getElementById(countdownId);
    if (!btn || !phoneEl) return;

    // Khôi phục nếu có
    restoreCountdown(btn, countdownEl, Number(btn.dataset.seconds || 60), storageKey);

    btn.addEventListener('click', async () => {
      helpEl?.classList.add('d-none');
      helpEl.textContent = '';

      const phone = phoneEl.value;
      if (!isValidPhone(phone)) {
        helpEl.textContent = 'Số điện thoại không hợp lệ.';
        helpEl?.classList.remove('d-none');
        phoneEl.focus();
        return;
      }

      // Chặn spam khi đang disabled
      if (btn.disabled) return;

      const seconds = Number(btn.dataset.seconds || 60);
      btn.textContent = 'Đang gửi...';
      btn.disabled = true;

      try {
        // TODO: TÍCH HỢP API THẬT Ở ĐÂY:
        // ví dụ:
        // const resp = await fetch('/api/send_otp.php', {
        //   method: 'POST',
        //   headers: { 'Content-Type': 'application/json' },
        //   body: JSON.stringify({ channel: btn.dataset.type, phone })
        // });
        // const data = await resp.json();
        // if (!resp.ok || !data.success) throw new Error(data.message || 'Gửi OTP thất bại');

        // Demo giả lập độ trễ:
        await new Promise(r => setTimeout(r, 800));

        // Bắt đầu countdown
        startCountdown(btn, countdownEl, seconds, storageKey);
      } catch (err) {
        btn.disabled = false;
        btn.textContent = btn.dataset.type === 'zalo' ? 'Gửi Zalo OTP' : 'Gửi SMS OTP';
        helpEl.textContent = (err && err.message) ? err.message : 'Không thể gửi OTP. Vui lòng thử lại.';
        helpEl?.classList.remove('d-none');
      }
    });
  }

  // Khởi tạo cho Zalo & SMS
  document.addEventListener('DOMContentLoaded', () => {
    handleSendOtp('sendZaloBtn', 'phone_zalo', 'zaloOtpHelp', 'zaloCountdown', 'OTP_ZALO_EXPIRE');
    handleSendOtp('sendSmsBtn', 'phone_sms', 'smsOtpHelp', 'smsCountdown', 'OTP_SMS_EXPIRE');
  });
</script>