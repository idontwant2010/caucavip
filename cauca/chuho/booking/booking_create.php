<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';
include __DIR__ . '/../../../includes/header.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['vai_tro'] !== 'chuho') {
  die("Bạn không có quyền tạo booking.");
}

$chuho_id = (int)$_SESSION['user']['id'];



// LẤY ho_id (phải có)
$ho_id = (int)($_GET['ho_id'] ?? $_POST['ho_id'] ?? 0);
if ($ho_id <= 0) {
  // nếu không truyền ho_id, lấy hồ đầu tiên của chủ hồ
  $qHo = $pdo->prepare("SELECT id FROM ho_cau WHERE chu_ho_id = :uid ORDER BY id ASC LIMIT 1");
  $qHo->execute([':uid' => $chuho_id]);
  $ho_id = (int)$qHo->fetchColumn();
  if ($ho_id <= 0) {
    $pdo->rollBack();
    echo "<script>alert('Tài khoản chưa có hồ. Vui lòng tạo hồ trước.'); history.back();</script>";
    exit;
  }
}

// TỰ CHỌN gia_id THEO HỒ
$gia_id = 0;

// 1) Thử bảng 'gia' (nếu hệ thống bạn dùng tên này)
$hasGia = $pdo->query("SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'gia'")->fetchColumn();
if ($hasGia) {
  $stg = $pdo->prepare("SELECT id FROM gia WHERE ho_cau_id = :hid ORDER BY id DESC LIMIT 1");
  $stg->execute([':hid' => $ho_id]);
  $gia_id = (int)$stg->fetchColumn();
}

// 2) Nếu chưa có, thử bảng 'gia_ca_thit_phut' (tên hay dùng)
if ($gia_id <= 0) {
  $hasGiaPhut = $pdo->query("SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'gia_ca_thit_phut'")->fetchColumn();
  if ($hasGiaPhut) {
    // nếu có cột status, ưu tiên status='open'
    $stg2 = $pdo->prepare("
      SELECT id FROM gia_ca_thit_phut 
      WHERE ho_cau_id = :hid 
      ORDER BY (CASE WHEN COALESCE(status,'open')='open' THEN 0 ELSE 1 END), id DESC 
      LIMIT 1
    ");
    $stg2->execute([':hid' => $ho_id]);
    $gia_id = (int)$stg2->fetchColumn();
  }
}

// 3) Nếu vẫn chưa ra => không thể insert vì cột NOT NULL
if ($gia_id <= 0) {
  $pdo->rollBack();
  echo "<script>alert('Hồ này chưa có BẢNG GIÁ. Vui lòng tạo bảng giá trước khi tạo vé.'); history.back();</script>";
  exit;
}

/* =========================
   XỬ LÝ SUBMIT
   ========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_booking'])) {
  $phoneRaw = trim($_POST['phone'] ?? '');
  $fullName = trim($_POST['full_name'] ?? '');

  // Chuẩn hóa số VN: +84xxxx -> 0xxxx
  $phone = preg_replace('/[^0-9+]/', '', $phoneRaw);
  if (strpos($phone, '+84') === 0) $phone = '0' . substr($phone, 3);
  if (strpos($phone, '84') === 0 && strlen($phone) === 11) $phone = '0' . substr($phone, 2);

  // Validate di động VN 10 số
  if (!preg_match('/^0(3|5|7|8|9)\d{8}$/', $phone)) {
    echo "<script>alert('❌ Số điện thoại không hợp lệ. Vui lòng nhập số di động Việt Nam (10 số, bắt đầu bằng 03/05/07/08/09).'); history.back();</script>";
    exit;
  }

  try {
    $pdo->beginTransaction();

    // Tìm user theo phone
    $q = $pdo->prepare("SELECT id, vai_tro, full_name FROM users WHERE phone = :p FOR UPDATE");
    $q->execute([':p' => $phone]);
    $u = $q->fetch(PDO::FETCH_ASSOC);

    if ($u) {
      if ($u['vai_tro'] !== 'canthu') {
        $pdo->rollBack();
        echo "<script>alert('Bạn đã là chủ hồ, không thể dùng số này để đặt booking. Vui lòng dùng số khác.'); history.back();</script>";
        exit;
      }
      $user_id = (int)$u['id'];
      if ($fullName === '') $fullName = $u['full_name'] ?: $phone; //note lại

    } else {
      // Chưa có user -> tạo mới
      if ($fullName === '') $fullName = $phone;
      $nickname = 'POS-' . substr($phone, -4) . '-' . random_int(100, 999);
      $pwdHash  = password_hash('baocong00', PASSWORD_DEFAULT);

      $insU = $pdo->prepare("
        INSERT INTO users (phone, full_name, nickname, password, status, review_status, vai_tro, created_at)
        VALUES (:phone, :full, :nick, :pwd, 'chưa xác minh', 'no', 'canthu', NOW())
      ");
      $insU->execute([
        ':phone' => $phone,
        ':full'  => $fullName,
        ':nick'  => $nickname,
        ':pwd'   => $pwdHash,
      ]);
      $user_id = (int)$pdo->lastInsertId();
    }

    // Tạo booking (chưa gắn bảng giá, sẽ xử lý trong booking_detail.php)
$insB = $pdo->prepare("
  INSERT INTO booking
    (nguoi_tao_id, can_thu_id, chu_ho_id, ho_cau_id, gia_id, ten_nguoi_cau, booking_time, booking_status)
  VALUES
    (:creator, :canthu, :chuho, :ho, :gia, :ten, NOW(), 'Đang chạy')
");
$insB->execute([
  ':creator' => $chuho_id,
  ':canthu'  => $user_id,
  ':chuho'   => $chuho_id,
  ':ho'      => $ho_id,
  ':gia'     => $gia_id,           // << NHỚ TRUYỀN VÀO
  ':ten'     => $fullName,

]);
    $booking_id = (int)$pdo->lastInsertId();
	
		// --- Lấy nickname từ users
	$st = $pdo->prepare("SELECT nickname FROM users WHERE id = ?");
	$st->execute([$user_id]);
	$nickname = $st->fetchColumn();

	// --- Update booking.nick_name
	if ($nickname) {
		$up = $pdo->prepare("UPDATE booking SET nick_name = :nick WHERE id = :bid");
		$up->execute([
			':nick' => $nickname,
			':bid'  => $booking_id,
		]);
	}

    // Log
    $log = $pdo->prepare("INSERT INTO booking_logs (booking_id, user_id, action, note) VALUES (:bid, :uid, :act, :note)");
    $log->execute([
      ':bid'  => $booking_id,
      ':uid'  => $chuho_id,
      ':act'  => 'create_pos',
      ':note' => 'Tạo booking POS cho SĐT ' . $phone,
    ]);

    $pdo->commit();

    header("Location: booking_detail.php?id={$booking_id}");
    exit;

  } catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo "<div class='alert alert-danger'>❌ Lỗi khi tạo booking: " . htmlspecialchars($e->getMessage()) . "</div>";
    exit;
  }
}
?>



<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Tạo vé booking (POS)</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    .field-card {
      border: 1px solid #eef1f4;
      border-radius: 1rem;
      padding: 1rem 1rem 0.75rem;
      background: #fff;
      box-shadow: 0 .25rem .9rem rgba(18,38,63,.05);
    }
    .field-label {
      font-weight: 600;
      margin-bottom: .35rem;
    }
    .hint {
      font-size: .83rem;
      color: #6c757d;
    }
    .form-control-lg {
      border-radius: .75rem;
    }
    .input-icon {
      position: absolute;
      left: .85rem;
      top: 50%;
      transform: translateY(-50%);
      pointer-events: none;
      color: #6c757d;
    }
    .with-icon .form-control {
      padding-left: 2.4rem;
    }
  </style>
</head>

<body class="bg-light">
<div class="container py-4">
  <div class="card border-0 shadow-sm">
    <div class="card-header fw-bold d-flex justify-content-between align-items-center">
      <span>Tạo vé booking (POS)</span>
      <a href="booking_list.php" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
      </a>
    </div>

    <div class="card-body">
      <form method="post" class="row g-4">

        <!-- PHONE -->
        <div class="col-md-6">
          <div class="field-card">
            <label class="field-label" for="inpPhone">Số điện thoại</label>
            <div class="position-relative with-icon">
              <i class="bi bi-phone input-icon"></i>
              <input id="inpPhone" type="tel" name="phone" class="form-control form-control-lg"
                     placeholder="VD: 0912 345 678"
                     required pattern="^0(3|5|7|8|9)\d{8}$" inputmode="numeric"
                     title="10 số di động VN, bắt đầu 03/05/07/08/09">
            </div>
            <div class="d-flex justify-content-between align-items-center mt-2">
              <div class="hint">Nhập đủ 10 số, hệ thống sẽ kiểm tra/khởi tạo tài khoản.</div>
              <span class="badge text-bg-light">VN</span>
            </div>
          </div>
        </div>

        <!-- FULL NAME -->
        <div class="col-md-6">
          <div class="field-card">
            <label class="field-label" for="inpFullName">Họ &amp; Tên (nếu chưa có tài khoản)</label>
            <div class="position-relative with-icon">
              <i class="bi bi-person input-icon"></i>
              <input id="inpFullName" type="text" name="full_name" class="form-control form-control-lg"
                     placeholder="VD: Nguyễn Văn A" maxlength="80">
            </div>
            <div class="hint mt-2">Nếu số đã có tài khoản, có thể sửa tên hiển thị cho booking này.</div>
          </div>
        </div>

        <div class="col-12 d-flex justify-content-end gap-2">
          <a href="booking_list.php" class="btn btn-outline-secondary">
            <i class="bi bi-x-circle"></i> Hủy
          </a>
			<button type="submit" name="create_booking" value="1" 
					class="btn btn-primary"
					onclick="return confirmPOS()"> 
			  <i class="bi bi-ticket-perforated"></i> Tạo vé
			</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Nhỏ mà hữu ích: auto-strip khoảng trắng & chỉ giữ số cho phone
  (function(){
    const phone = document.getElementById('inpPhone');
    phone?.addEventListener('input', () => {
      phone.value = phone.value.replace(/\D+/g,'').slice(0,10);
    });
  })();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function confirmPOS(){
  const phone = document.querySelector('input[name="phone"]').value.trim();
  const full  = document.querySelector('input[name="full_name"]').value.trim();
  const ten   = full !== '' ? full : '—';
  const msg   = `Bạn có muốn tạo đơn hàng POS tại hồ cho cần thủ "${ten}" và số ĐT "${phone}"?`;
  return confirm(msg);
}
</script>

</body>
<?php include __DIR__ . '/../../../includes/footer.php'; ?>

