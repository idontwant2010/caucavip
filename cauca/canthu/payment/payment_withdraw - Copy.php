<?php
// www/caucavip/cauca/canthu/Payment/payment_withdraw.php
require '../../../check_login.php';
require '../../../connect.php';

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

$uid = $_SESSION['user']['id'] ?? 0;
if (!$uid) { header('Location: /auth/login.php'); exit; }

// 1) Lấy thông tin user
$st = $pdo->prepare("
  SELECT id, full_name, status, balance, bank_account, bank_info
  FROM users
  WHERE id = ?
  LIMIT 1
");
$st->execute([$uid]);
$user = $st->fetch(PDO::FETCH_ASSOC);

// 1B Lấy biến số dư hiện tại
$balanceCurrent = 0.0;
if ($user) {
  if (array_key_exists('balance', $user)) {
    $balanceCurrent = (float)$user['balance'];
  } else {
    // fallback nếu SELECT không có cột balance
    $stb = $pdo->prepare("SELECT balance FROM users WHERE id=?");
    $stb->execute([$uid]);
    $balanceCurrent = (float)($stb->fetchColumn() ?: 0);
  }
}



// 2) Điều kiện xác minh & thông tin ngân hàng  (KHÔNG redirect —> hiện thông báo + nút vào profile)
$bankAccount = trim((string)($user['bank_account'] ?? ''));
$bankInfo    = trim((string)($user['bank_info'] ?? ''));

$blockReason = null;
$blockHref   = null;

if (!$user || $user['status'] !== 'Đã xác minh') {
  $blockReason = 'Bạn chưa xác minh chính chủ, vui lòng vào profile để xác minh';
  $blockHref   = '../profile.php?need_verify=1';
} elseif ($bankAccount === '' || $bankInfo === '') {
  $blockReason = 'Bạn vui lòng cung cấp thông tin ngân hàng chính chủ, vào profile để thêm bank';
  $blockHref   = '../profile.php?missing_bank=1';
}

if ($blockReason) {
  include '../../../includes/header.php'; ?>
  <div class="container mt-4">
    <div class="alert alert-warning d-flex justify-content-between align-items-center">
      <div><?= h($blockReason) ?></div>
      <a href="<?= h($blockHref) ?>" class="btn btn-sm btn-primary">vào profile</a>
    </div>
  </div>
  <?php
  include '../../../includes/footer.php';
  exit;
}

// 3) Tải cấu hình từ admin_config_keys
function loadConfigAssoc(PDO $pdo, array $keys): array {
  if (empty($keys)) return [];
  $place = implode(',', array_fill(0, count($keys), '?'));
  try {
    $st = $pdo->prepare("SELECT key_name, key_value FROM admin_config_keys WHERE key_name IN ($place)");
    $st->execute($keys);
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    $cfg = [];
    foreach ($rows as $r) $cfg[$r['key_name']] = $r['key_value'];
    return $cfg;
  } catch (Exception $e) {
    return []; // nếu bảng chưa tồn tại vẫn chạy với default
  }
}

$needKeys = ['withdraw_min_amount','withdraw_max_amount','withdraw_fee_type','withdraw_fee_value'];
$cfg = loadConfigAssoc($pdo, $needKeys);

$withdrawMin   = (int)($cfg['withdraw_min_amount'] ?? 50000);       // tối thiểu (VD 50,000đ)
$withdrawMax   = (int)($cfg['withdraw_max_amount'] ?? 0);           // tối đa (0 = không giới hạn)
$feeTypeRaw    = strtolower(trim((string)($cfg['withdraw_fee_type'] ?? 'flat'))); // 'flat' | 'percent'
$feeValueRaw   = (string)($cfg['withdraw_fee_value'] ?? '0');       // VD '5000' hoặc '1.5' (nếu percent = 1.5%)

$feeType  = in_array($feeTypeRaw, ['flat','percent'], true) ? $feeTypeRaw : 'flat';
$feeValue = (float)$feeValueRaw; // nếu percent → là số % (ví dụ 1.5 = 1.5%)

// Hàm tính phí
function calcWithdrawFee(int $amount, string $type, float $val): int {
  if ($amount <= 0) return 0;
  if ($type === 'percent') {
    // làm tròn lên tới đồng
    return (int)ceil($amount * $val / 100.0);
  }
  // flat
  return (int)round($val);
}

$error = $success = null;

// 4) Submit tạo yêu cầu rút
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $amount = (int)($_POST['amount'] ?? 0);
  $note   = trim((string)($_POST['note'] ?? ''));

  // Reload balance mới nhất để chắc chắn
  $stb = $pdo->prepare("SELECT balance FROM users WHERE id=?");
  $stb->execute([$uid]);
  $balance = (int)$stb->fetchColumn();

  // Validate min/max
  if ($amount <= 0) {
    $error = "Số tiền không hợp lệ.";
  } elseif ($amount < $withdrawMin) {
    $error = "Số tiền tối thiểu được rút là " . number_format($withdrawMin) . " đ.";
  } elseif ($withdrawMax > 0 && $amount > $withdrawMax) {
    $error = "Số tiền tối đa được rút là " . number_format($withdrawMax) . " đ.";
  } else {
    // Tính phí
    $feeAmount = calcWithdrawFee($amount, $feeType, $feeValue);
    // Tổng trừ khỏi balance = amount + fee
    $totalDeduct = $amount + $feeAmount;

    if ($totalDeduct > $balance) {
      $error = "Số dư không đủ (bao gồm phí " . number_format($feeAmount) . " đ).";
    } else {
		try {
		  // Mã giao dịch
		  $code = 'WD' . date('YmdHis') . random_int(100,999);

		  // Snapshot bank info + cấu hình phí
		  $extraMeta = json_encode([
			'bank_account' => $bankAccount,
			'bank_info'    => $bankInfo,
			'fee_type'     => $feeType,
			'fee_value'    => $feeValue,
		  ], JSON_UNESCAPED_UNICODE);

		  // Lưu payments (fee lưu vào fee_amount)
		  $ins = $pdo->prepare("
			INSERT INTO payments
			  (user_id, created_by, payment_code, loai_giao_dich, method,
			   amount, fee_amount, status, note, extra_meta, created_at)
			VALUES
			  (:user_id, :created_by, :payment_code, 'withdraw', 'Chuyen_khoan',
			   :amount, :fee_amount, 'pending', :note, :extra_meta, NOW())
		  ");
		  $ins->execute([
			':user_id'      => $uid,
			':created_by'   => $uid,
			':payment_code' => $code,
			':amount'       => $amount,
			':fee_amount'   => $feeAmount,
			':note'         => $note,
			':extra_meta'   => $extraMeta
		  ]);

		  // ➜ Redirect sang trang chi tiết thay vì echo $success
		  $newId = (int)$pdo->lastInsertId();
		  if ($newId > 0) {
			header('Location: payment_detail.php?id=' . $newId . '&created=1');
			exit;
		  }

		  // Fallback nếu lastInsertId không trả về (hiếm)
		  $st = $pdo->prepare("SELECT id FROM payments WHERE user_id=? AND payment_code=? ORDER BY id DESC LIMIT 1");
		  $st->execute([$uid, $code]);
		  $rowId = (int)$st->fetchColumn();
		  header('Location: payment_detail.php?id=' . $rowId . '&created=1');
		  exit;

		} catch (Exception $e) {
		  $error = "Lỗi hệ thống: " . $e->getMessage();
		}
    }
  }
}

include '../../../includes/header.php';
?>
<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Yêu cầu rút tiền</h4>
    <div>
      <a href="payment_list.php" class="btn btn-sm btn-outline-secondary">← Lịch sử nạp/rút</a>
    </div>
  </div>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= h($error) ?></div>
  <?php elseif (!empty($success)): ?>
    <div class="alert alert-success"><?= h($success) ?></div>
  <?php endif; ?>

  <!-- Thông tin cấu hình -->
  <div class="alert alert-info">
    <div><strong>Giới hạn rút:</strong>
      Tối thiểu <?= number_format($withdrawMin) ?> đ
      <?php if ($withdrawMax > 0): ?>
        · Tối đa <?= number_format($withdrawMax) ?> đ
      <?php endif; ?>
    </div>
    <div><strong>Phí rút:</strong>
      <?php if ($feeType === 'percent'): ?>
        <?= rtrim(rtrim(number_format($feeValue, 2), '0'), '.') ?>%
      <?php else: ?>
        <?= number_format((int)$feeValue) ?> đ
      <?php endif; ?>
    </div>
  </div>

  <!-- Thông tin tài khoản & ngân hàng -->
  <div class="card border-3 shadow-sm mb-4">
    <div class="card-header fw-semibold">Thông tin nhận tiền</div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <div><strong>Họ & tên:</strong> <?= h($user['full_name'] ?? '') ?></div>
          <div><strong>Số dư hiện tại:</strong>
		  <span id="balanceText"><?= number_format($balanceCurrent) ?></span> đ
		  </div>
          <div><strong>Trạng thái:</strong> <span class="badge bg-success">Đã xác minh</span></div>
        </div>
        <div class="col-md-6">
          <div><strong>Số tài khoản:</strong> <?= h($bankAccount) ?></div>
          <div><strong>Ngân hàng:</strong> <?= h($bankInfo) ?></div>
          <div class="small text-muted">* Tiền sẽ chuyển về thông tin ngân hàng ở trên.</div>
        </div>
      </div>
      <div class="mt-2">
        <a href="../profile.php" class="btn btn-sm btn-outline-primary">Cập nhật thông tin ngân hàng</a>
      </div>
    </div>
  </div>

  <!-- Form rút tiền -->
  <div class="card border-3 shadow-sm">
    <div class="card-header fw-semibold">Tạo yêu cầu rút tiền</div>
    <div class="card-body">
      <form method="post" class="row g-3" id="formWithdraw">
        <div class="col-md-6">
          <label class="form-label">Số tiền muốn rút</label>
          <input type="number" name="amount" id="amountInput" min="<?= (int)$withdrawMin ?>" step="1000" class="form-control" required>
          <div class="form-text">Số dư khả dụng: <?= number_format($balanceCurrent) ?> đ</div>
        </div>
        <div class="col-md-6">
          <label class="form-label">Ghi chú (không bắt buộc)</label>
          <input type="text" name="note" class="form-control" placeholder="VD: rút về ngân hàng cá nhân">
        </div>

        <!-- Preview phí & khấu trừ -->
        <div class="col-12">
          <div class="border rounded p-3 bg-light">
            <div>Phí ước tính: <strong id="feePreview">0</strong> đ</div>
            <div>Tổng trừ khỏi số dư: <strong id="totalPreview">0</strong> đ</div>
          </div>
        </div>

        <div class="col-12">
          <button type="submit" class="btn btn-primary">Gửi yêu cầu</button>
          <a href="payment_list.php" class="btn btn-outline-secondary">Hủy</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// Preview phí client-side (không thay thế kiểm tra server-side)
(function(){
  const feeType   = <?= json_encode($feeType) ?>;
  const feeValue  = <?= json_encode($feeValue) ?>; // percent hoặc flat
  const amountEl  = document.getElementById('amountInput');
  const feeEl     = document.getElementById('feePreview');
  const totalEl   = document.getElementById('totalPreview');

  function fmt(n){ return (n||0).toLocaleString('vi-VN'); }

  function recalc(){
    const amt = parseInt(amountEl.value || '0', 10);
    if (!amt || amt <= 0) {
      feeEl.textContent = '0';
      totalEl.textContent = '0';
      return;
    }
    let fee = 0;
    if (feeType === 'percent') {
      fee = Math.ceil(amt * (parseFloat(feeValue)||0) / 100.0);
    } else {
      fee = Math.round(parseFloat(feeValue)||0);
    }
    const total = amt + fee;
    feeEl.textContent = fmt(fee);
    totalEl.textContent = fmt(total);
  }

  amountEl.addEventListener('input', recalc);
})();
</script>

<?php include '../../../includes/footer.php'; ?>
