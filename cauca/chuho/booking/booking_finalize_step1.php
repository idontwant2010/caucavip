<?php
// booking_finalize_step1.php
session_start();
require '../../../connect.php'; // Kết nối PDO: $pdo
require '../../../check_login.php'; // Đảm bảo đã đăng nhập
include __DIR__ . '/../../../includes/header.php';

if ($_SESSION['user']['vai_tro'] !== 'chuho') {
    http_response_code(403);
    exit('Forbidden');
}

// ------- Helpers -------
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function money_vnd($n){ return number_format((int)$n, 0, ',', '.') . ' đ'; }

/**
 * Tạo URL ảnh VietQR (img.vietqr.io) từ BIN (hoặc bank_info), số TK & tên TK
 * - Nếu $amount > 0 => QR cố định số tiền
 * - Nếu $amount = 0 hoặc null => QR không amount
 */
function generate_vietqr_url_from_bankinfo(string $bankInfo, string $accountNumber, string $accountName, ?int $amount = null, ?string $addInfo = null, string $template = 'compact'): string {
    // bank_info dạng "970436-Vietcombank"
    $parts = explode('-', $bankInfo, 2);
    $bin = preg_replace('/\D/', '', $parts[0] ?? '');
    if ($bin === '') $bin = '970436'; // fallback VCB

    $accountNumber = preg_replace('/\s+/', '', $accountNumber);
    $accountName   = trim($accountName);

    $base = "https://img.vietqr.io/image/{$bin}-{$accountNumber}-{$template}.png";
    $params = ['accountName' => $accountName];
    if (!empty($amount) && $amount > 0) $params['amount'] = (int)$amount;
    if (!empty($addInfo)) $params['addInfo'] = $addInfo;

    $qs = http_build_query($params, arg_separator:'&', encoding_type:PHP_QUERY_RFC3986);
    return $base . ($qs ? ('?'.$qs) : '');
}

/**
 * Tính số tiền cần thanh toán từ các cột có thể có trong bảng booking
 * Ưu tiên: total_amount hoặc final_total
 * Fallback tạm thời: real_amount + fish_sell_amount - fish_return_amount - booking_amount - booking_discount
 */
function compute_amount_to_pay(array $bk): int {
    if (isset($bk['total_amount'])) return (int)$bk['total_amount'];
    if (isset($bk['final_total'])) return (int)$bk['final_total'];

    $real_amount        = (int)($bk['real_amount']        ?? 0);
    $fish_sell_amount   = (int)($bk['fish_sell_amount']   ?? 0);
    $fish_return_amount = (int)($bk['fish_return_amount'] ?? 0);
    $booking_amount     = (int)($bk['booking_amount']     ?? 0);
    $booking_discount   = (int)($bk['booking_discount']   ?? 0);

    $calc = $real_amount + $fish_sell_amount - $fish_return_amount - $booking_amount - $booking_discount;
    return $calc;
}

// ------- Input -------
$booking_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($booking_id <= 0) { echo "Thiếu booking id"; exit; }

// ------- Query booking + hồ + chủ hồ + cần thủ -------
$st = $pdo->prepare("
SELECT 
    b.*,
    h.id        AS ho_id,
    h.ten_ho,
    ch.id       AS cum_ho_id,
    ch.ten_cum_ho,
    ch.chu_ho_id AS owner_id,
    uo.full_name AS owner_full_name,
    uo.bank_info AS owner_bank_info,
    uo.bank_account AS owner_bank_account,
    uc.id       AS canthu_id,
    uc.full_name AS canthu_full_name,
    uc.balance  AS canthu_balance
FROM booking b
JOIN ho_cau h ON h.id = b.ho_cau_id
JOIN cum_ho ch ON ch.id = h.cum_ho_id
JOIN users uo ON uo.id = ch.chu_ho_id
LEFT JOIN users uc ON uc.id = b.can_thu_id
WHERE b.id = :id
LIMIT 1

");
$st->execute([':id' => $booking_id]);
$bk = $st->fetch(PDO::FETCH_ASSOC);
if (!$bk) { echo "Không tìm thấy booking."; exit; }

// Bảo vệ: chỉ cho chủ hồ của hồ này thao tác
if ((int)$bk['owner_id'] !== (int)$_SESSION['user']['id']) {
    http_response_code(403);
    exit('Bạn không có quyền xử lý booking này.');
}

// Số tiền cần thanh toán
$amountToPay = compute_amount_to_pay($bk);
$amountToPayPos = max(0, $amountToPay);

// QR (mặc định không amount)
$ownerBankInfo    = (string)($bk['owner_bank_info'] ?? '');
$ownerBankAccount = (string)($bk['owner_bank_account'] ?? '');
$ownerName        = (string)($bk['owner_full_name'] ?? '');

// addInfo gợi ý
$qrNote = 'Thanh toan booking #'.$booking_id;

// QR mặc định (không amount) dùng cho Tiền mặt/balance
$qrUrlDefault = generate_vietqr_url_from_bankinfo($ownerBankInfo, $ownerBankAccount, $ownerName, null, null, 'compact');
// QR có amount dùng cho Chuyển khoản
$qrUrlWithAmount = generate_vietqr_url_from_bankinfo($ownerBankInfo, $ownerBankAccount, $ownerName, $amountToPayPos, $qrNote, 'compact');

// Check balance cần thủ
$canthuBalance = (int)($bk['canthu_balance'] ?? 0);
$can_use_balance = ($amountToPayPos > 0 && $canthuBalance >= $amountToPayPos);

// Giá trị hiện tại của payment_method (nếu có)
$currentMethod = $bk['payment_method'] ?? 'Tiền mặt';

// Xử lý POST lưu lựa chọn payment_method 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pm = $_POST['payment_method'] ?? 'Tiền mặt';
    // Ràng buộc enum: 'Tiền mặt' | 'Balance' | 'Chuyển khoản'
    $valid = ['Tiền mặt','Balance','Chuyển khoản'];
    if (!in_array($pm, $valid, true)) $pm = 'Tiền mặt';

    // Lưu method (và có thể lưu sẵn QR booking nếu chọn chuyển khoản)
    $params = [':pm' => $pm, ':id' => $booking_id];

    // Nếu bạn có cột booking.qr_image_url và muốn lưu lại link có amount:
    $sql = "UPDATE booking SET payment_method = :pm WHERE id = :id";
    if ($pm === 'Chuyển khoản' && $amountToPayPos > 0) {
        // Thêm cột nếu bạn đã có: qr_image_url
         $sql = "UPDATE booking SET payment_method = :pm, qr_image_url = :qr WHERE id = :id";
         $params[':qr'] = $qrUrlWithAmount;
    }

    $pdo->prepare($sql)->execute($params);

    // Redirect nhẹ để tránh repost
    header("Location: booking_finalize_step1.php?id=".$booking_id."&saved=1");
    exit;
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Finalize Booking </title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="/assets/bootstrap.min.css" rel="stylesheet">
  <style>
    .card { border-radius: 6px; }
    .qr-img { max-width: 240px; height: auto; border: 1px solid #eee; border-radius: 6px; padding: 4px; background:#fff; }
    .muted { color:#6c757d; }
  </style>
</head>
<body class="bg-light">
<div class="container py-4">
  <h3 class="mb-3">Thanh toán và hoàn tất vé câu</h3>
  <div class="mb-2">
    <a class="btn btn-warning btn-outline-secondary" href="booking_detail.php?id=<?= (int)$booking_id ?>">← Quay lại chi tiết vé câu</a>
    <?php if (isset($_GET['saved'])): ?>
      <span class="ms-2 text-success">Đã lưu phương thức thanh toán.</span>
    <?php endif; ?>
  </div>

  <!-- PHẦN 1: Tính tiền & Review -->
  <div class="card mb-3">
    <div class="card-header"><strong>1) Tính tiền & Review</strong></div>
    <div class="card-body">
      <div class="row g-3">
	  		<!-- thông tin cơ bản cần thủ booking -->
			<div class="col-md-4">
			  <div class="border rounded-3 p-3 h-100">
				<div class="d-flex justify-content-between py-1">
				  <span class="text-muted">Mã booking</span>
				  <span class="fw-semibold">#<?= (int)$booking_id ?></span>
				</div>

				<div class="d-flex justify-content-between py-1">
				  <span class="text-muted">Hồ</span>
				  <span class="fw-semibold text-end"><?= h($bk['ten_ho']) ?></span>
				</div>
				<div class="d-flex justify-content-between py-1">
				  <span class="text-muted">Vị trí câu</span>
				  <span class="fw-semibold text-end">Số: <?= h($bk['vi_tri'] ?: '—') ?></span>
				</div>

				<div class="d-flex justify-content-between py-1">
				  <span class="text-muted">Cần thủ</span>
				  <span class="fw-semibold text-end"><?= h($bk['canthu_full_name'] ?: '—') ?></span>
				</div>

				<div class="d-flex justify-content-between py-1">
				  <span class="text-muted">Bắt đầu</span>
				  <span class="fw-semibold text-end"><?= h($bk['real_start_time'] ?? '—') ?></span>
				</div>
				<div class="d-flex justify-content-between py-1">
				  <span class="text-muted">Kết thúc</span>
				  <span class="fw-semibold text-end"><?= h($bk['real_end_time'] ?? '—') ?></span>
				</div>
				
			  </div>
			</div>

		
		<!-- thông tin cơ bản booking -->
			<div class="col-md-4">
			  <div class="border rounded-3 p-3 h-100">
				<?php if (isset($bk['real_tong_thoi_luong'])): ?>
				  <div class="d-flex justify-content-between py-1">
					<span class="text-muted">Thời gian câu (phút)</span>
					<span class="fw-semibold"><?= (int)$bk['real_tong_thoi_luong'] ?> phút</span>
				  </div>
				<?php endif; ?>

				<?php if (isset($bk['fish_weight'])): ?>
				  <div class="d-flex justify-content-between py-1">
					<span class="text-muted">Cá thu lại (kg)</span>
					<span class="fw-semibold text-success"><?= (float)$bk['fish_weight'] ?> kg</span>
				  </div>
				<?php endif; ?>
				
				<?php if (isset($bk['fish_sell_weight'])): ?>
				  <div class="d-flex justify-content-between py-1">
					<span class="text-muted">Cá bán về (kg)</span>
					<span class="fw-semibold text-success"><?= (float)$bk['fish_sell_weight'] ?> kg</span>
				  </div>
				<?php endif; ?>

				<?php if (isset($bk['booking_amount'])): ?>
				  <div class="d-flex justify-content-between py-1">
					<span class="text-muted">Đã cọc online</span>
					<span class="fw-semibold text-success">-<?= money_vnd($bk['booking_amount']) ?></span>
				  </div>
				<?php endif; ?>

				<?php if (isset($bk['booking_discount'])): ?>
				  <div class="d-flex justify-content-between py-1">
					<span class="text-muted">Khuyến mãi</span>
					<span class="fw-semibold text-success">-<?= money_vnd($bk['booking_discount']) ?></span>
				  </div>
				<?php endif; ?>

				<hr class="my-2">

				<div class="d-flex justify-content-between py-1">
				  <span class="fw-semibold">Tổng cần thanh toán</span>
				  <span class="fw-bold"><?= money_vnd($amountToPay) ?></span>
				</div>

				<div class="small text-muted mt-2">* Thông tin tổng kết vé câu</div>
			  </div>
			</div>

		
		<!-- Mục chọn phương thức: hoàn thành thì ẩn start -->
		<?php if ($bk['booking_status'] === 'Hoàn thành'): ?>
			<div class="col-md-4">
			  <div class="alert alert-success text-center fw-bold fs-5 py-5">
				🎉 Booking đã hoàn tất
			  </div>
			</div>
		<?php else: ?>
		 <div class="col-md-4">
		  <form method="post" class="card p-3">
			<h6 class="mb-3">Chọn phương thức thanh toán</h6>

			<div class="form-check mb-2">
			  <input class="form-check-input" type="radio" name="payment_method" id="pm_cash"
					 value="Tiền mặt" <?= $currentMethod === 'Tiền mặt' ? 'checked' : '' ?>>
			  <label class="form-check-label" for="pm_cash">Tiền mặt</label>
			</div>

			<div class="form-check mb-2">
			  <input class="form-check-input" type="radio" name="payment_method" id="pm_balance"
					 value="Balance" <?= $currentMethod === 'Balance' ? 'checked' : '' ?>>
			  <label class="form-check-label" for="pm_balance">Số dư user (balance)</label>
			</div>

			<div class="form-check mb-3">
			  <input class="form-check-input" type="radio" name="payment_method" id="pm_transfer"
					 value="Chuyển khoản" <?= $currentMethod === 'Chuyển khoản' ? 'checked' : '' ?>>
			  <label class="form-check-label" for="pm_transfer">Chuyển khoản (VietQR)</label>
			</div>

			<!-- Check balance cần thủ -->
			<div class="p-2 border rounded bg-light">
			  <div><span class="muted">Balance cần thủ:</span> <strong><?= money_vnd($canthuBalance) ?></strong></div>
			  <div><span class="muted">Cần thanh toán:</span> <strong><?= money_vnd($amountToPayPos) ?></strong></div>
			  <?php if ($amountToPayPos <= 0): ?>
				<div class="mt-1 text-success">Khoản phải trả ≤ 0. Có thể xác nhận hoàn tất ở bước sau.</div>
			  <?php elseif ($can_use_balance): ?>
				<div class="mt-1 text-success">Số dư đủ. Có thể trừ trực tiếp từ balance.</div>
			  <?php else: ?>
				<div class="mt-1 text-danger">Số dư KHÔNG đủ. Vui lòng chọn Tiền mặt hoặc Chuyển khoản.</div>
			  <?php endif; ?>
			</div>

			<div class="mt-3 d-flex">
			  <button class="btn btn-primary" type="submit">Cập nhật hình thức thanh toán</button>
			</div>
		  </form>
		</div>

		<?php endif; ?>
		<!-- Mục chọn phương thức: hoàn thành thì ẩn ==> kết thúc -->
		</div>
      </div>
    </div>


  <!-- PHẦN 2: Chọn phương thức + Check balance -->
  <div class="card mb-3">
    <div class="card-header"><strong>2. Thông tin thanh toán</strong></div>
    <div class="card-body">
      <div class="row g-3">
		<!-- nhắc nhở -->  
		<div class="col-md-4">
		  <div class="border rounded-3 p-3 h-100 bg-light">
			<div class="fw-bold mb-2">📌 Gợi ý luồng xử lý ở bước 2:</div>
			<ul class="mb-0 small">
			  <li><strong>Tiền mặt:</strong> Chủ hồ xác nhận đã nhận đủ tiền → chuyển sang bước hoàn tất.</li>
			  <li><strong>Balance:</strong> Hệ thống tự kiểm tra & trừ/cộng số dư (đủ điều kiện mới cho hoàn tất).</li>
			  <li><strong>Chuyển khoản:</strong> Quét QR với số tiền cố định → xác nhận khi đã nhận đủ tiền.</li>
			</ul>
		  </div>
		</div>

		<!-- Cột: QR Code -->
		<div class="col-md-4 text-center">
		  <div class="border rounded-3 p-3 h-100 bg-light">
			
			<!-- QR không amount (mặc định hiển thị) -->
			<div id="qr-no-amount">
			  <img class="qr-img img-fluid" src="<?= h($qrUrlDefault) ?>" alt="VietQR (no amount)">
			  <div class="mt-2 text-muted small">QR (cần nhập số tiền)</div>
			</div>

			<!-- QR có amount (ẩn, sẽ show khi chọn Chuyển khoản) -->
			<div id="qr-with-amount" style="display:none;">
			  <img class="qr-img img-fluid" src="<?= h($qrUrlWithAmount) ?>" alt="VietQR (with amount)">
			  <div class="mt-2 small">
				<span class="text-muted"><?= h($ownerName ?: '—') ?></span> · 
				<strong class="text-danger"><?= money_vnd($amountToPayPos) ?></strong>
			  </div>
			</div>

		  </div>
		</div>

		<!-- Cột: Thông tin tài khoản -->
		<div class="col-md-4">
		  <div class="border rounded-3 p-3 h-100 bg-light">
			<div><span class="text-muted">Tên tài khoản:</span> <strong><?= h($ownerName ?: '—') ?></strong></div>
			<div><span class="text-muted">Ngân hàng:</span> 
			  <strong><?= h(explode('-', $ownerBankInfo, 2)[1] ?? $ownerBankInfo) ?></strong>
			</div>
			<div><span class="text-muted">Số tài khoản:</span> <strong><?= h($ownerBankAccount ?: '—') ?></strong></div>
			<div class="mt-2">
			  <small class="text-muted">*Vui lòng kiểm tra kỹ tên trước khi thanh toán</small>
			</div>
		  </div>
		</div>
	</div> 
	</div>
	</div>
  
		<!-- PHẦN 4: Xác nhận hoàn tất -->
	<form method="post" action="booking_finalize_step2.php" class="card mt-3">
	  <div class="card-header"><strong>4. Hoàn tất và chốt booking</strong></div>
	  <div class="card-body">
		<input type="hidden" name="booking_id" value="<?= (int)$booking_id ?>">
		<p>Sau khi kiểm tra đầy đủ, bạn có thể xác nhận hoàn tất booking này.</p>
		<div class="d-flex gap-2">
			<a href="booking_detail.php?id=<?= (int)$booking_id ?>" class="btn btn-outline-secondary text-center">
				← Quay lại chi tiết vé câu
			</a>			
			<?php if ($bk['booking_status'] === 'Hoàn thành'): ?>
				<span class="badge bg-success p-3 fs-6">
					🎉
				</span>
			<?php else: ?>
				<button type="submit" class="btn btn-danger"
						onclick="return confirm('Xác nhận thanh toán & hoàn thành booking, không thể sửa sau bước này, tiếp tục?')">
				  ✅ Xác nhận hoàn tất và chốt booking
				</button>
			<?php endif; ?>
		</div>
	  </div>
	</form>
    </div><!-- /.row -->
  </div><!-- /.card-body -->
</div><!-- /.card -->

<script>
(function(){
  const pmCash   = document.getElementById('pm_cash');
  const pmBal    = document.getElementById('pm_balance');
  const pmTrans  = document.getElementById('pm_transfer');
  const qrNoAmt  = document.getElementById('qr-no-amount');
  const qrAmt    = document.getElementById('qr-with-amount');

  function toggleQR(){
    if (pmTrans.checked) {
      qrNoAmt.style.display = 'none';
      qrAmt.style.display   = 'block';
    } else {
      qrNoAmt.style.display = 'block';
      qrAmt.style.display   = 'none';
    }
  }
  [pmCash, pmBal, pmTrans].forEach(el => el.addEventListener('change', toggleQR));
  toggleQR();
})();
</script>
</body>
</html>
<?php include __DIR__ . '/../../../includes/footer.php'; ?>