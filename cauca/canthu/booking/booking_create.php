<?php
// cauca/canthu/booking/booking_create.php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';
include_once __DIR__ . '/../../../includes/header.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['vai_tro'] !== 'canthu') {
  http_response_code(403); exit('Forbidden');
}

$can_thu_id   = (int)$_SESSION['user']['id'];
$nguoi_tao_id = $can_thu_id;
$errors = [];
$notice = "";

/* mở rộng session cho user, mục đích là lấy thông tin user */
if (empty($_SESSION['user']['full_name']) || empty($_SESSION['user']['nickname'])) {
    $st = $pdo->prepare("
        SELECT 
            COALESCE(NULLIF(full_name, ''), phone) AS full_name, 
            nickname
        FROM users 
        WHERE id = ? 
        LIMIT 1
    ");
    $st->execute([$can_thu_id]);
    $u = $st->fetch(PDO::FETCH_ASSOC) ?: [];

    // Ghi ngược lại vào session để các lần sau khỏi query
    if (!empty($u)) {
        $_SESSION['user']['full_name'] = $u['full_name'] ?? ($_SESSION['user']['full_name'] ?? null);
        $_SESSION['user']['nickname']  = $u['nickname']  ?? ($_SESSION['user']['nickname']  ?? null);
    }
}


$can_thu_name = $_SESSION['user']['full_name'] ?? 'None';
$can_thu_nick = $_SESSION['user']['nickname']  ?? 'None';

/* mở rộng session cho user, mục đích là lấy thông tin user ==> kết thúc */

/* 1) Nhận ho_id từ URL */
$ho_cau_id = isset($_GET['ho_id']) && ctype_digit($_GET['ho_id']) ? (int)$_GET['ho_id'] : 0;
if ($ho_cau_id <= 0) { echo '<div class="alert alert-warning">Thiếu tham số ho_id.</div>'; exit; }

/* 2) Lấy thông tin hồ + cụm + chủ hồ (chu_ho_id ở cum_ho) */
$st = $pdo->prepare("
  SELECT 
    h.id AS ho_id, h.ten_ho, h.status AS ho_status,
    cum.id AS cum_id, cum.ten_cum_ho, cum.status AS cum_status,
    cum.chu_ho_id, cum.dia_chi, cum.google_map_url,
    u.full_name AS ten_chu_ho, u.nickname
  FROM ho_cau h
  JOIN cum_ho cum ON h.cum_ho_id = cum.id
  JOIN users u    ON u.id = cum.chu_ho_id
  WHERE h.id = ?
  LIMIT 1
");
$st->execute([$ho_cau_id]);
$hoinfo = $st->fetch(PDO::FETCH_ASSOC);
if (!$hoinfo) { echo '<div class="alert alert-danger">Hồ không tồn tại.</div>'; exit; }
if ($hoinfo['ho_status'] !== 'dang_hoat_dong' || $hoinfo['cum_status'] !== 'dang_chay') {
  echo '<div class="alert alert-danger">Hồ/cụm hồ đang tạm khóa.</div>'; exit;
}


/* 3) Lấy lịch hoạt động mẫu (7 ngày) */
$st = $pdo->prepare("
  SELECT thu, gio_mo, gio_dong, trang_thai
  FROM lich_hoat_dong_ho_cau
  WHERE ho_cau_id = ?
");
$st->execute([$ho_cau_id]);
$lich7 = $st->fetchAll(PDO::FETCH_ASSOC);

/* 4) Build 14 ngày mở (kể cả hôm nay) từ lịch mẫu */
$mapThuPhpToDb = [1=>'2', 2=>'3', 3=>'4', 4=>'5', 5=>'6', 6=>'7', 0=>'CN']; // PHP: 0=CN..6=Th7
$lichByThu = [];
foreach ($lich7 as $row) { $lichByThu[$row['thu']] = $row; }

$today  = new DateTime('today');
$ngayMo = [];
for ($i=0; $i<14; $i++) {
  $d = clone $today; $d->modify("+$i day");
  $phpDow = (int)$d->format('w');
  $dbThu  = $mapThuPhpToDb[$phpDow];

  if (!empty($lichByThu[$dbThu]) && $lichByThu[$dbThu]['trang_thai'] === 'mo') {
    $ngayMo[] = [
      'date'     => $d->format('Y-m-d'),
      'label'    => $d->format('d/m') . ' (' . ($dbThu === 'CN' ? 'CN' : 'T'.$dbThu) . ')',
      'gio_mo'   => substr($lichByThu[$dbThu]['gio_mo'], 0, 5),
      'gio_dong' => substr($lichByThu[$dbThu]['gio_dong'], 0, 5),
    ];
  }
}
if (!$ngayMo) { echo '<div class="alert alert-warning">14 ngày tới hồ không có ngày mở.</div>'; exit; }

/* 5) Lấy bảng giá open đầu tiên để HIỂN THỊ (không cho chọn) */
$gia = $pdo->prepare("
  SELECT id, ten_bang_gia, base_duration, base_price, extra_unit_price, gia_thu_lai
  FROM gia_ca_thit_phut
  WHERE ho_cau_id = ? AND status = 'open'
  ORDER BY id ASC LIMIT 1
");
$gia->execute([$ho_cau_id]);
$gia = $gia->fetch(PDO::FETCH_ASSOC);

/* 6, lấy giá list */
$st = $pdo->prepare("
  SELECT id, ten_bang_gia, base_duration, base_price, extra_unit_price, gia_thu_lai
  FROM gia_ca_thit_phut
  WHERE ho_cau_id = :hid AND status = 'open'
  ORDER BY CASE ten_bang_gia
             WHEN 'Cơ Bản'   THEN 1
             WHEN 'Trung Cấp' THEN 2
             WHEN 'Đài Sư'   THEN 3
             ELSE 4
           END, id ASC
  LIMIT 3
");
$st->execute([':hid' => (int)$hoinfo['ho_id']]);
$gia_list = $st->fetchAll(PDO::FETCH_ASSOC);



/* 6) Lấy config trực tiếp (1 query) */
$stCfg = $pdo->query("
  SELECT config_key, config_value
  FROM admin_config_keys
  WHERE config_key IN ('booking_hold_amount','booking_fee_amount')
");
$configs = $stCfg->fetchAll(PDO::FETCH_KEY_PAIR);
$booking_hold_amount = isset($configs['booking_hold_amount']) ? (int)$configs['booking_hold_amount'] : 0;
$booking_fee_amount  = isset($configs['booking_fee_amount'])  ? (int)$configs['booking_fee_amount']  : 0;
$total_booking_amount = $booking_fee_amount + $booking_hold_amount;

// hàm format tiền
function money_vnd($n){ return number_format((int)$n,0,',','.').' đ'; }

/* 7) Handle submit */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $chon_ngay = $_POST['chon_ngay'] ?? '';
  if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $chon_ngay)) {
    $errors[] = "Vui lòng chọn ngày hợp lệ.";
  } else {
    $picked = null;
    foreach ($ngayMo as $d) if ($d['date'] === $chon_ngay) { $picked = $d; break; }
    if (!$picked) $errors[] = "Ngày đã chọn không còn hợp lệ.";
  }

  $gia_id = $gia['id'] ?? null;
  if (!$gia_id) $errors[] = "Hồ chưa có bảng giá đang mở.";

  if (empty($errors)) {
    $booking_start_time = $chon_ngay . ' ' . ($picked['gio_mo'] . ':00'); // e.g. 2025-08-30 06:00:00

    try {
      $pdo->beginTransaction();

      // 7.1) Khóa số dư & kiểm tra đủ tiền
      $uq  = $pdo->prepare("SELECT balance FROM users WHERE id=? FOR UPDATE");
      $uq->execute([$can_thu_id]);
      $bal_current = (int)$uq->fetchColumn();

      $need = $booking_hold_amount + $booking_fee_amount;
      if ($bal_current < $need) {
        throw new Exception("Số dư không đủ (cần ".number_format($need)."đ, còn ".number_format($bal_current)."đ).");
      }

      // 7.2) Tạo booking
      $ins=$pdo->prepare("
        INSERT INTO booking
          (nguoi_tao_id, can_thu_id, chu_ho_id, ho_cau_id, ten_nguoi_cau, nick_name,
		   gia_id, booking_where,
           booking_time, booking_start_time, payment_status, booking_status, booking_amount)
        VALUES
          (?, ?, ?, ?, ?, ?, ?, 'online', NOW(), ?, 'Chưa thanh toán', 'Đang chạy', ?)
      ");
      $ins->execute([
        $nguoi_tao_id, $can_thu_id, (int)$hoinfo['chu_ho_id'], $ho_cau_id, $can_thu_name, $can_thu_nick,
		(int)$gia_id,
        $booking_start_time, $booking_hold_amount
      ]);
      $booking_id = (int)$pdo->lastInsertId();

      // 7.3) Chuẩn bị statement ghi log số dư (không dùng balance_change)
      $insBalLog = $pdo->prepare("
        INSERT INTO user_balance_logs
          (user_id, amount, balance_before, balance_after, note, type, ref_no, created_at)
        VALUES
          (?, ?, ?, ?, ?, 'booking_hold', ?, NOW())
      ");

      // 7.4) Trừ CỌC (nếu > 0)
      if ($booking_hold_amount > 0) {
        $before = $bal_current;
        $change = -$booking_hold_amount; // số âm
        $after  = $before + $change;

        // update số dư
        $pdo->prepare("UPDATE users SET balance = ? WHERE id = ?")
            ->execute([$after, $can_thu_id]);

        // log user_balance_logs
        $insBalLog->execute([
          $can_thu_id,
          $change,             // amount (âm)
          $before,             // balance_before
          $after,              // balance_after
          'Giữ cọc booking',
          $booking_id
        ]);

        // log booking_logs
        $pdo->prepare("INSERT INTO booking_logs(booking_id, action, note, user_id, created_at)
                       VALUES(?, 'hold', ?, ?, NOW())")
            ->execute([$booking_id, 'Giữ cọc '.number_format($booking_hold_amount).'đ', $can_thu_id]);

        $bal_current = $after;
      }

      // 7.5) Trừ PHÍ (nếu > 0)
      if ($booking_fee_amount > 0) {
        $before = $bal_current;
        $change = -$booking_fee_amount; // số âm
        $after  = $before + $change;

        $pdo->prepare("UPDATE users SET balance = ? WHERE id = ?")
            ->execute([$after, $can_thu_id]);

        $insBalLog->execute([
          $can_thu_id,
          $change,              // amount (âm)
          $before,              // balance_before
          $after,               // balance_after
          'Phí tạo booking',
          $booking_id
        ]);

        $pdo->prepare("INSERT INTO booking_logs(booking_id, action, note, user_id, created_at)
                       VALUES(?, 'fee', ?, ?, NOW())")
            ->execute([$booking_id, 'Thu phí '.number_format($booking_fee_amount).'đ', $can_thu_id]);

        $bal_current = $after;
      }

      $pdo->commit();
      $notice = "Tạo booking thành công! Mã #$booking_id";
    } catch (Exception $e) {
      if ($pdo->inTransaction()) $pdo->rollBack();
      $errors[] = $e->getMessage();
    }
  }
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Đặt vé câu (Cần thủ)</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="/assets/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-3">
<div class="container">
  <h3 class="mb-3">Đặt vé câu (Cần thủ)</h3>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach ($errors as $er) echo '<li>'.htmlspecialchars($er).'</li>'; ?>
      </ul>
    </div>
  <?php elseif ($notice): ?>
    <div class="alert alert-success"><?= htmlspecialchars($notice) ?></div>
  <?php endif; ?>

  <!-- Card: Thông tin hồ câu -->
<div class="card border-2 shadow-sm mt-2 mb-2">
  <div class="card-header fw-bold">Thông tin & Đặt vé</div>
	  <div class="card-body py-3">
		<div class="row g-3">
		  <!-- ========== Trái: Thông tin hồ (col-md-6) ========== -->
		  <div class="col-md-6">
			<div class="fw-semibold">
			  Hồ: #<?= (int)$hoinfo['ho_id'] ?> — <?= htmlspecialchars($hoinfo['ten_ho']) ?>
			</div>
			<div class="text-muted small">
			  Cụm: <?= htmlspecialchars($hoinfo['ten_cum_ho']) ?> ·
			  Chủ hồ: <?= htmlspecialchars($hoinfo['ten_chu_ho']) ?>
			</div>

			<?php if (!empty($hoinfo['dia_chi'])): ?>
			  <div class="text-muted small mt-1">
				Địa chỉ: <?= htmlspecialchars($hoinfo['dia_chi']) ?>
			  </div>
			<?php endif; ?>

			<?php if (!empty($hoinfo['google_map_url'])): ?>
			  <a class="btn btn-sm btn-outline-primary mt-2" target="_blank"
				 href="<?= htmlspecialchars($hoinfo['google_map_url']) ?>">
				Xem bản đồ
			  </a>
			<?php endif; ?>
		  </div>
				<!-- ========== Trái: Thông bảng giá (col-md-6) ========== -->

			<div class="col-md-6">
			  <div class="fw-semibold mb-2">Bảng giá đang mở</div>

			  <?php if (!empty($gia_list)): ?>
				<?php foreach ($gia_list as $giar): ?>
				  <div class="mb-2">
					<div>
					  <strong>Bảng giá:</strong> <?= htmlspecialchars($giar['ten_bang_gia']) ?>
					  - Thu cá: <?= number_format((int)($giar['gia_thu_lai'] ?? 0)) ?>đ
					</div>
					<div class="small">
					  Suất: <?= (int)$giar['base_duration'] ?>’ ·
					  Giá suất: <?= number_format((int)$giar['base_price']) ?>đ
					  <?php if (isset($giar['extra_unit_price']) && $giar['extra_unit_price'] !== null): ?>
						· Thêm: <?= number_format((int)$giar['extra_unit_price']) ?>đ/phút
					  <?php endif; ?>
					</div>
				  </div>
				<?php endforeach; ?>
			  <?php else: ?>
				<em>Hồ chưa có bảng giá đang mở — vui lòng liên hệ chủ hồ.</em>
			  <?php endif; ?>
			</div>
		</div>
	</div>
</div>


  <!-- Form: Chọn ngày hoạt động -->
  <form method="post" class="card border-2 shadow-sm mt-3">
    <div class="card-header fw-bold">Chọn ngày hoạt động (14 ngày tới)</div>
    <div class="card-body">
      <input type="hidden" name="ho_cau_id" value="<?= (int)$ho_cau_id ?>">

      <div class="row row-cols-3 row-cols-md-5 g-3">
        <?php foreach ($ngayMo as $i => $d): ?>
          <div class="col">
            <label class="form-check border rounded p-3 w-100">
              <input class="form-check-input me-2" type="radio" name="chon_ngay"
                     value="<?= htmlspecialchars($d['date']) ?>" <?= $i===0?'checked':''; ?>>
              <span class="fw-semibold"><?= htmlspecialchars($d['label']) ?></span><br>
              <span class="text-muted"> <?= htmlspecialchars($d['gio_mo']) ?> - <?= htmlspecialchars($d['gio_dong']) ?></span>
            </label>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="mt-3 d-flex gap-2">
		<button type="submit" name="create_booking" value="1"
				class="btn btn-primary"
				onclick="return confirmBookingOnline()">
		  <i class="bi bi-cart-check"></i> Đặt vé câu online
		</button>
        <a href="/cauca/canthu/booking/booking_list.php" class="btn btn-outline-secondary">Quay lại</a>
      </div>
    </div>
  </form>
</div>
</body>
</html>

<script>
function confirmBookingOnline(){
  const total  = "<?= money_vnd($total_booking_amount) ?>";
  const fee    = "<?= money_vnd($booking_fee_amount) ?>";
  const hold   = "<?= money_vnd($booking_hold_amount) ?>";
  const msg = `Tài khoản bạn sẽ trừ ${total}, đã kèm phí hệ thống (${fee}) để đặt vé câu online.\n` +
              `Khoản tiền này, sẽ hoàn trả tại hồ: ${hold}.\n\n` +
              `Bạn có chắc muốn tiếp tục?`;
  return confirm(msg);
}
</script>