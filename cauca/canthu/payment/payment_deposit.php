<?php
require '../../../check_login.php';
require '../../../connect.php';

$uid = $_SESSION['user']['id'];


// Sau require connect.php
function loadConfigAssoc(PDO $pdo, array $keys): array {
  if (empty($keys)) return [];
  $place = implode(',', array_fill(0, count($keys), '?'));
  $st = $pdo->prepare("SELECT config_key, config_value FROM admin_config_keys WHERE config_key IN ($place)");
  $st->execute($keys);
  $rows = $st->fetchAll(PDO::FETCH_ASSOC);
  $cfg = [];
  foreach ($rows as $r) $cfg[$r['config_key']] = $r['config_value'];
  return $cfg;
}

$cfg = loadConfigAssoc($pdo, ['base_bank_account','base_bank_info','base_bank_name']);
$baseBankAccount = $cfg['base_bank_account'] ?? '';
$baseBankInfo    = $cfg['base_bank_info'] ?? '';
$baseBankName    = $cfg['base_bank_name'] ?? '';


$extraMeta = json_encode([
  'base_bank_account' => $baseBankAccount,
  'base_bank_info'    => $baseBankInfo,
  'base_bank_name'    => $baseBankName,
], JSON_UNESCAPED_UNICODE);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = (int)($_POST['amount'] ?? 0);
    $note   = trim($_POST['note'] ?? '');

    if ($amount <= 0) {
        $error = "Số tiền không hợp lệ.";
    } else {
        // Mã giao dịch đơn giản
        $code = 'DP' . date('YmdHis') . random_int(100, 999);

        $ins = $pdo->prepare("
            INSERT INTO payments
              (user_id, created_by, payment_code, loai_giao_dich, method, amount, status, note)
            VALUES
              (?, ?, ?, 'deposit', 'Chuyen_khoan', ?, 'pending', ?)
        ");
        $ins->execute([$uid, $uid, $code, $amount, $note]);

        // ➜ Chuyển thẳng sang trang chi tiết
        $newId = (int)$pdo->lastInsertId();
        if ($newId > 0) {
            header('Location: payment_detail.php?id=' . $newId . '&created=1');
            exit;
        }

        // Fallback (hiếm khi cần)
        $st = $pdo->prepare("SELECT id FROM payments WHERE user_id = ? AND payment_code = ? ORDER BY id DESC LIMIT 1");
        $st->execute([$uid, $code]);
        $rowId = (int)$st->fetchColumn();
        header('Location: payment_detail.php?id=' . $rowId . '&created=1');
        exit;
    }
}
?>
<?php include '../../../includes/header.php'; ?>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Nạp tiền vào tài khoản</h4>
    <div class="d-flex gap-2">
      <a href="payment_list.php" class="btn btn-sm btn-outline-secondary">← Lịch sử nạp/rút</a>
    </div>
  </div>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php elseif (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <div class="row g-4">
    <!-- Form nạp tiền -->
    <div class="col-lg-7">
      <div class="card border-3 shadow-sm">
        <div class="card-header fw-semibold">Tạo yêu cầu nạp tiền</div>
        <div class="card-body">
          <form method="post" id="depositForm" class="row g-3">
            <div class="col-12">
              <label class="form-label">Số tiền muốn nạp</label>
              <div class="input-group">
                <input type="number" name="amount" id="amountInput"
                       class="form-control" min="10000" step="1000" required
                       placeholder="Nhập số tiền (đ)">
                <span class="input-group-text">đ</span>
              </div>
              <div class="mt-2 d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-sm btn-outline-secondary quick-amt" data-amt="100000">+100.000</button>
                <button type="button" class="btn btn-sm btn-outline-secondary quick-amt" data-amt="200000">+200.000</button>
                <button type="button" class="btn btn-sm btn-outline-secondary quick-amt" data-amt="500000">+500.000</button>
                <button type="button" class="btn btn-sm btn-outline-secondary quick-amt" data-amt="1000000">+1.000.000</button>
                <button type="button" class="btn btn-sm btn-outline-secondary quick-amt" data-amt="2000000">+2.000.000</button>
              </div>
              <div class="form-text">Xem lại số tiền trước khi gửi yêu cầu.</div>
            </div>

            <div class="col-12">
              <label class="form-label">Ghi chú (không bắt buộc)</label>
              <input type="text" name="note" class="form-control" placeholder="VD: nạp qua chuyển khoản">
            </div>

            <div class="col-12">
              <div class="border rounded p-3 bg-light">
                <div>Số tiền dự kiến nạp: <strong id="amountPreview">0</strong> đ</div>
                <div class="text-muted small">* Mã giao dịch nội bộ sẽ được cấp sau khi tạo yêu cầu nạp.</div>
              </div>
            </div>

            <div class="col-12 d-flex gap-2">
              <button type="submit" class="btn btn-success">Tạo yêu cầu nạp</button>
              <a href="payment_list.php" class="btn btn-outline-secondary">Hủy</a>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Thông tin tài khoản nhận tiền + Hướng dẫn -->
    <div class="col-lg-5">
      <?php if (($baseBankAccount ?? '') || ($baseBankInfo ?? '') || ($baseBankName ?? '')): ?>
      <div class="card border-3 shadow-sm mb-4">
        <div class="card-header fw-semibold">Thông tin nạp tiền (tài khoản nhận)</div>
        <div class="card-body">
          <?php if (!empty($baseBankName)): ?>
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div><strong>Chủ TK:</strong> <span id="bankNameTxt"><?= htmlspecialchars($baseBankName) ?></span></div>
              <button type="button" class="btn btn-sm btn-outline-primary copy-btn" data-copy="#bankNameTxt">Sao chép</button>
            </div>
          <?php endif; ?>

          <?php if (!empty($baseBankAccount)): ?>
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div><strong>Số TK nhận:</strong> <span id="bankAccTxt"><?= htmlspecialchars($baseBankAccount) ?></span></div>
              <button type="button" class="btn btn-sm btn-outline-primary copy-btn" data-copy="#bankAccTxt">Sao chép</button>
            </div>
          <?php endif; ?>

          <?php if (!empty($baseBankInfo)): ?>
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div><strong>Ngân hàng:</strong> <span id="bankInfoTxt"><?= htmlspecialchars($baseBankInfo) ?></span></div>
              <button type="button" class="btn btn-sm btn-outline-primary copy-btn" data-copy="#bankInfoTxt">Sao chép</button>
            </div>
          <?php endif; ?>

          <div class="small text-muted mt-2">
            * Sau khi tạo yêu cầu nạp, vui lòng chuyển khoản đúng số tiền và ghi rõ nội dung theo hướng dẫn.
          </div>
        </div>
      </div>
      <?php endif; ?>

      <div class="card border-3 shadow-sm">
        <div class="card-header fw-semibold">Hướng dẫn nạp</div>
        <div class="card-body">
          <ol class="mb-0">
            <li>Nhập số tiền và bấm <em>Tạo yêu cầu nạp</em>.</li>
            <li>Chuyển khoản vào <strong>Tài khoản nhận</strong> ở trên.</li>
            <li>Nội dung chuyển khoản: <code>NAP &lt;MÃ_GD&gt; &lt;SĐT&gt;</code> (mã sẽ có sau khi tạo yêu cầu).</li>
            <li>Hệ thống sẽ đối soát và cập nhật giao dịch khi xác nhận.</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  const amountInput = document.getElementById('amountInput');
  const amountPreview = document.getElementById('amountPreview');
  const quickBtns = document.querySelectorAll('.quick-amt');
  const copyBtns = document.querySelectorAll('.copy-btn');

  function fmt(n){ return (n||0).toLocaleString('vi-VN'); }
  function updatePreview(){
    const v = parseInt(amountInput.value || '0', 10);
    amountPreview.textContent = fmt(v);
  }
  amountInput && amountInput.addEventListener('input', updatePreview);

  quickBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const amt = parseInt(btn.getAttribute('data-amt'), 10);
      amountInput.value = amt;
      updatePreview();
      amountInput.focus();
    });
  });

  copyBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const sel = btn.getAttribute('data-copy');
      const el = document.querySelector(sel);
      if (!el) return;
      const text = el.textContent.trim();
      navigator.clipboard.writeText(text).then(() => {
        btn.textContent = 'Đã sao chép';
        setTimeout(() => btn.textContent = 'Sao chép', 1200);
      });
    });
  });

  updatePreview();
})();
</script>






<?php include '../../../includes/footer.php'; ?>
