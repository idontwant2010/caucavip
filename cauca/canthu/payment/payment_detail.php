<?php
// www/caucavip/cauca/canthu/Payment/payment_detail.php
require '../../../check_login.php';
require '../../../connect.php';

function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

$uid = $_SESSION['user']['id'] ?? 0;
if (!$uid) {
  header('Location: /auth/login.php'); exit;
}

// Lấy ID giao dịch
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
  http_response_code(400);
  echo "Thiếu tham số id.";
  exit;
}

$expireMins = 30; // hoặc đọc từ admin_config_keys
$sql = "
  SELECT p.*,
         TIMESTAMPDIFF(SECOND, NOW(), DATE_ADD(p.created_at, INTERVAL $expireMins MINUTE)) AS seconds_left
  FROM payments p
  WHERE p.id = ? AND p.user_id = ?
  LIMIT 1
";
$st = $pdo->prepare($sql);
$st->execute([$id, $uid]);
$pm = $st->fetch(PDO::FETCH_ASSOC);

$left = (int)($pm['seconds_left'] ?? 0);

// Xử lý hủy yêu cầu (khi pending)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action']==='cancel') {
  try {
    $pdo->beginTransaction();
    // Chỉ cho phép chủ sở hữu hủy khi còn pending
    $st = $pdo->prepare("UPDATE payments
                         SET status = 'canceled', updated_at = NOW()
                         WHERE id = ? AND user_id = ? AND status = 'pending'");
    $st->execute([$id, $uid]);

    if ($st->rowCount() === 0) {
      $error = "Không thể hủy giao dịch (có thể không tồn tại, không thuộc về bạn, hoặc không còn ở trạng thái pending).";
    } else {
      $success = "Đã hủy yêu cầu thành công.";
    }
    $pdo->commit();
  } catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    $error = "Lỗi hệ thống: " . $e->getMessage();
  }
}

// Lấy chi tiết payment + thông tin bank từ users (giới hạn theo user_id để bảo mật)
$st = $pdo->prepare("
  SELECT
    p.*,
    u.full_name  AS user_full_name,
    u.bank_account AS user_bank_account,
    u.bank_info    AS user_bank_info,
    u.status     AS user_status,
    uc.full_name AS created_by_name,
    ua.full_name AS approved_by_name
  FROM payments p
  JOIN users u   ON u.id = p.user_id
  LEFT JOIN users uc ON uc.id = p.created_by
  LEFT JOIN users ua ON ua.id = p.approved_by
  WHERE p.id = ? AND p.user_id = ?
  LIMIT 1
");
$st->execute([$id, $uid]);
$pm = $st->fetch(PDO::FETCH_ASSOC);


// Chỉ tạo QR cho giao dịch nạp
$isDeposit = ($pm['loai_giao_dich'] ?? '') === 'deposit';

// 1) Lấy info từ extra_meta (snapshot lúc tạo lệnh) -> fallback sang admin_config_keys
function loadConfigAssoc(PDO $pdo, array $keys): array {
  if (empty($keys)) return [];
  $place = implode(',', array_fill(0, count($keys), '?'));
  try {
    $st = $pdo->prepare("SELECT config_key, config_value FROM admin_config_keys WHERE config_key IN ($place)");
    $st->execute($keys);
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    $cfg = [];
    foreach ($rows as $r) $cfg[$r['config_key']] = $r['config_value'];
    return $cfg;
  } catch (Exception $e) { return []; }
}

$meta = [];
if (!empty($pm['extra_meta'])) {
  $tmp = json_decode($pm['extra_meta'], true);
  if (is_array($tmp)) $meta = $tmp;
}

$cfg = loadConfigAssoc($pdo, ['base_bank_account','base_bank_info','base_bank_name']);
$baseBankAccount = $meta['base_bank_account'] ?? ($cfg['base_bank_account'] ?? '');
$baseBankInfo    = $meta['base_bank_info']    ?? ($cfg['base_bank_info'] ?? ''); // VD: "970416-ACB"
$codePart = $baseBankInfo;
$pos = strrpos($baseBankInfo, '-');
if ($pos !== false) {
    $codePart = substr($baseBankInfo, $pos + 1);
}
$baseBankInfo_name = strtoupper(preg_replace('/[^A-Za-z]/', '', trim($codePart))); // → "ACB"

$baseBankName    = $meta['base_bank_name']    ?? ($cfg['base_bank_name'] ?? '');

// 2) Rút ra bankCode từ baseBankInfo (lấy đoạn sau dấu '-'; VD: "ACB")
$bankCode = '';
if ($baseBankInfo) {
  $parts = preg_split('/[-\s]+/', trim($baseBankInfo));
  $bankCode = strtoupper($parts[count($parts)-1] ?? '');
}

// 3) Tạo URL VietQR với số tiền cố định
$qrUrl = '';
if ($isDeposit && $bankCode && $baseBankAccount && (int)$pm['amount'] > 0) {
  $addInfo = "NAP " . ($pm['payment_code'] ?? '');
  $amount  = (int)round((float)$pm['amount']); // cố định số tiền
  // qr_only: chỉ QR, không khung; có thể đổi sang "compact" nếu thích
  $qrUrl = "https://img.vietqr.io/image/{$bankCode}-{$baseBankAccount}-qr_only.png"
         . "?amount={$amount}"
         . "&addInfo=" . urlencode($addInfo)
         . "&accountName=" . urlencode($baseBankName ?: ' ');
}


if (!$pm) {
  http_response_code(404);
  echo "Không tìm thấy giao dịch hoặc bạn không có quyền xem.";
  exit;
}

// Tính badge status
$badge = 'bg-secondary';
if ($pm['status'] === 'completed') $badge = 'bg-success';
elseif ($pm['status'] === 'pending') $badge = 'bg-warning text-dark';
elseif ($pm['status'] === 'failed' || $pm['status'] === 'canceled') $badge = 'bg-danger';

// Thử lấy log liên quan (nếu có bảng user_balance_logs)
$logs = [];
try {
  $st2 = $pdo->prepare("
    SELECT user_id, action, amount, balance_before, balance_after, note, created_at, created_by
    FROM user_balance_logs
    WHERE ref_table = 'payments' AND ref_id = ?
    ORDER BY created_at DESC
  ");
  $st2->execute([$id]);
  $logs = $st2->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
  // Không làm gì, có thể bảng log chưa tồn tại. Giữ $logs = [].
}


include '../../../includes/header.php';
?>
<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Chi tiết giao dịch</h4>
    <div>
      <a href="payment_list.php" class="btn btn-sm btn-outline-secondary">← Quay lại danh sách</a>
    </div>
  </div>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= h($error) ?></div>
  <?php elseif (!empty($success)): ?>
    <div class="alert alert-success"><?= h($success) ?></div>
  <?php endif; ?>

  <div class="card border-3 shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div>
        <strong>Mã GD:</strong> <?= h($pm['payment_code']) ?>
      </div>
      <div>
        <span class="badge <?= $badge ?>"><?= h($pm['status']) ?></span>
      </div>
    </div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-3">
          <div><strong>Loại:</strong> <?= ($pm['loai_giao_dich']==='deposit' ? 'Nạp' : 'Rút') ?></div>
          <div><strong>Phương thức:</strong> <?= h($pm['method']) ?></div>
          <div><strong>Số tiền:</strong> <?= number_format((float)$pm['amount']) ?> đ</div>
          <div><strong>Phí:</strong> <?= number_format((float)$pm['fee_amount']) ?> đ</div>
          <div><strong>Thực cộng/trừ:</strong> <?= number_format((float)$pm['delta_amount']) ?> đ</div>
		  <div><strong>Người duyệt:</strong> <?= h($pm['approved_by'] ?? '') ?></div>
		  <div><strong>Bank ref:</strong> <?= h($pm['bank_ref'] ?? '') ?></div>
        </div>
        <div class="col-md-3">
          <div><strong>Người khởi tạo:</strong> <?= h($pm['user_full_name']) ?></div>
          <div><strong>Tạo lúc:</strong> <?= h($pm['created_at']) ?></div>
          <div><strong>Cập nhật:</strong> <?= h($pm['updated_at']) ?></div>
		  
		  <!--code đếm ngược thời gian--!>
					<?php if (($pm['loai_giao_dich'] ?? '') === 'deposit' && ($pm['status'] ?? '') === 'pending'): ?>
					  <?php
						// số phút hết hạn (nếu bạn có key cấu hình thì lấy từ DB, mặc định 30)
						$expireMins = 30;

						// Tính giây còn lại KHÔNG lệch múi giờ (dựa trên giờ VN)
						$tz       = new DateTimeZone('Asia/Ho_Chi_Minh');
						$created  = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $pm['created_at'], $tz);
						$expires  = $created->modify("+{$expireMins} minutes");
						$now      = new DateTimeImmutable('now', $tz);
						$leftSecs = max(0, $expires->getTimestamp() - $now->getTimestamp());
					  ?>

					  <?php if ($leftSecs > 0): ?>
						<div class="alert alert-info d-flex justify-content-between align-items-center" id="countdownWrap">
						  <div>
							Lệnh sẽ hết hạn sau
							<strong><span id="countdown">--:--</span></strong>
							nếu chưa chuyển khoản.
						  </div>
						</div>
						<script>
						  (function(){
							let left = <?= (int)$leftSecs ?>; // giây còn lại
							const el   = document.getElementById('countdown');
							const wrap = document.getElementById('countdownWrap');
							const btn  = document.getElementById('btnRefresh');

							function pad(n){ return n < 10 ? '0' + n : '' + n; }
							function render(){
							  if (left <= 0) {
								el.textContent = '00:00';
								wrap.classList.remove('alert-info');
								wrap.classList.add('alert-warning');
								wrap.innerHTML = 'Lệnh đã quá hạn, có thể bị huỷ tự động.';
								// (tuỳ chọn) tự tải lại sau 5 giây để cập nhật trạng thái
								setTimeout(function(){ location.reload(); }, 5000);
								return false;
							  }
							  const m = Math.floor(left / 60);
							  const s = left % 60;
							  el.textContent = pad(m) + ':' + pad(s);
							  left--;
							  return true;
							}

							// nút làm mới thủ công
							btn && btn.addEventListener('click', function(){ location.reload(); });

							// bắt đầu đếm
							render();
							const iv = setInterval(function(){
							  if (!render()) clearInterval(iv);
							}, 1000);
						  })();
						</script>
					  <?php else: ?>
						<div class="alert alert-warning">Lệnh đã quá hạn, có thể bị huỷ tự động.</div>
					  <?php endif; ?>
					<?php endif; ?>
		  
		  <!--code thông tin bank nếu là nạp--!>
		<?php if (($pm['loai_giao_dich'] ?? '') === 'withdraw'): ?>
		  <div><strong>Tên chủ TK:</strong> <?= h($pm['user_full_name']) ?></div>
		  <div><strong>STK nhận:</strong> <?= h($pm['user_bank_account']) ?></div>
		  <div><strong>Ngân hàng:</strong> <?= h($pm['user_bank_info']) ?></div>
		<?php endif; ?>		  
		  
        </div>
		<!--mã QR code nạp tiền--!>
        <div class="col-md-6">
			<?php if (!empty($qrUrl)): ?>
			  <div class="row g-3 align-items-center">
				<div class="col-md-5 text-center">
				  <img src="<?= htmlspecialchars($qrUrl) ?>" alt="VietQR" class="img-fluid rounded border p-2">
				</div>
				<div class="col-md-7">
				  <div class="mb-2"><strong>Chủ TK:</strong> <?= htmlspecialchars($baseBankName) ?></div>
				  <div class="mb-2 d-flex justify-content-between align-items-center">
					<div><strong>Số TK nhận:</strong> <span id="qrAccTxt"><?= htmlspecialchars($baseBankAccount) ?></span></div>
					<button type="button" class="btn btn-sm btn-outline-primary copy-btn" data-copy="#qrAccTxt">Sao chép</button>
				  </div>
				  <div class="mb-2 d-flex justify-content-between align-items-center">
					<div><strong>Ngân hàng:</strong> <span id="qrBankTxt"><?= htmlspecialchars($baseBankInfo_name) ?></span></div>
					<button type="button" class="btn btn-sm btn-outline-primary copy-btn" data-copy="#qrBankTxt">Sao chép</button>
				  </div>
				  <div class="mb-2 d-flex justify-content-between align-items-center">
					<div><strong>Số tiền:</strong> <?= number_format((float)$pm['amount']) ?> đ</div>
				  </div>
				  <div class="mb-2 d-flex justify-content-between align-items-center">
					<div><strong>Nội dung:</strong> <code id="qrAddInfoTxt"><?= 'NAP ' . htmlspecialchars($pm['payment_code']) ?></code></div>
					<button type="button" class="btn btn-sm btn-outline-primary copy-btn" data-copy="#qrAddInfoTxt">Sao chép</button>
				  </div>
				</div>
			  </div>
			<?php endif; ?>
        </div>	
		
        <?php if (!empty($pm['proof_image'])): ?>
          <div class="col-12">
            <strong>Chứng từ:</strong><br>
            <img src="<?= h($pm['proof_image']) ?>" alt="proof" class="img-fluid rounded border">
          </div>
        <?php endif; ?>
    </div>
	    
	  
    </div>
    <div class="card-footer d-flex gap-2">
      <?php if ($pm['status'] === 'pending'): ?>
        <form method="post" onsubmit="return confirm('Bạn chắc muốn hủy yêu cầu này?');" class="mb-0">
          <input type="hidden" name="action" value="cancel">
          <button type="submit" class="btn btn-outline-danger btn-sm">Hủy yêu cầu</button>
        </form>
      <?php endif; ?>
      <a href="payment_deposit.php" class="btn btn-outline-success btn-sm"> + Tạo lệnh NẠP tiền mới</a>
      <a href="payment_withdraw.php" class="btn btn-outline-primary btn-sm">+ Tạo lệnh RÚT tiền mới</a>
    </div>
  </div>

  <div class="card border-3 shadow-sm">
    <div class="card-header">
      <strong>Nhật ký số dư liên quan</strong>
    </div>
    <div class="card-body">
      <?php if (empty($logs)): ?>
        <div class="text-muted">Chưa có log nào cho giao dịch này.</div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-sm table-striped align-middle">
            <thead class="table-light">
              <tr>
                <th>Thời gian</th>
                <th>Action</th>
                <th>Số tiền</th>
                <th>Trước</th>
                <th>Sau</th>
                <th>Ghi chú</th>
                <th>Người ghi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($logs as $lg): ?>
                <tr>
                  <td><?= h($lg['created_at']) ?></td>
                  <td><?= h($lg['action']) ?></td>
                  <td><?= number_format((float)$lg['amount']) ?> đ</td>
                  <td><?= number_format((float)$lg['balance_before']) ?> đ</td>
                  <td><?= number_format((float)$lg['balance_after']) ?> đ</td>
                  <td><?= h($lg['note'] ?? '') ?></td>
                  <td><?= h($lg['created_by'] ?? '') ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include '../../../includes/footer.php'; ?>
