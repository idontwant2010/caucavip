<?php
// File: cauca/chuho/game/game_create.php
require __DIR__ . '/../../../connect.php';
require __DIR__ . '/../../../check_login.php';
require_once '../../../includes/header.php';

if (($_SESSION['user']['vai_tro'] ?? '') !== 'chuho') { header('Location: /'); exit; }

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function money_vnd($n){ return number_format((int)$n, 0, ',', '.').' đ'; }

// --- Helpers ---
function parse_solo_from_label(string $label): ?int {
  if (preg_match('/solo\s*(2|3|4)/iu', $label, $m)) return (int)$m[1];
  return null;
}
function load_game_configs(PDO $pdo, array $needKeys): array {
  $in = implode(',', array_fill(0, count($needKeys), '?'));
  $tries = [
    "SELECT config_key AS k, config_value AS v FROM admin_config_keys WHERE config_key IN ($in)",
    "SELECT `key` AS k, `value` AS v FROM admin_config_keys WHERE `key` IN ($in)",
    "SELECT name AS k, value AS v FROM admin_config_keys WHERE name IN ($in)",
  ];
  foreach ($tries as $sql) {
    try {
      $st = $pdo->prepare($sql);
      $st->execute($needKeys);
      $out = [];
      foreach ($st as $row) $out[(string)$row['k']] = (string)$row['v'];
      if ($out) return $out;
    } catch (Throwable $e) {}
  }
  return [];
}

$chuho_id = (int)$_SESSION['user']['id'];
$ho_id    = isset($_GET['ho_id']) ? (int)$_GET['ho_id'] : 0;

/* =========================
 * STEP 0: Nếu chưa có ho_id -> hiển thị danh sách hồ đủ điều kiện để chọn
 * Điều kiện: thuộc chủ hồ hiện tại, cum_ho.status='dang_chay', ho_cau.status='dang_hoat_dong', ho_cau.cho_phep_danh_game=1
 * ========================= */
if ($ho_id <= 0) {
  $sqlHoList = "
    SELECT h.id, h.ten_ho, h.gia_game, h.so_cho_ngoi,
           c.ten_cum_ho
    FROM ho_cau h
    JOIN cum_ho c ON c.id = h.cum_ho_id
    WHERE c.chu_ho_id = :uid
      AND c.status = 'dang_chay'
      AND h.status = 'dang_hoat_dong'
      AND h.cho_phep_danh_game = 1
    ORDER BY c.ten_cum_ho ASC, h.ten_ho ASC
  ";
  $stL = $pdo->prepare($sqlHoList);
  $stL->execute(['uid'=>$chuho_id]);
  $hos = $stL->fetchAll(PDO::FETCH_ASSOC);
  ?>
  <!doctype html>
  <html lang="vi">
  <head>
    <meta charset="utf-8">
    <title>Chọn hồ để tạo game</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/assets/bootstrap.min.css" rel="stylesheet">
    <style>
      .card-hover:hover { transform: translateY(-2px); box-shadow: 0 0.5rem 1rem rgba(0,0,0,.12) }
    </style>
  </head>
  <body class="bg-light">
  <div class="container py-4">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/cauca/chuho/game/game_list.php">Tất cả game</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chọn hồ để tạo game</li>
      </ol>
    </nav>

    <div class="d-flex align-items-center mb-3">
      <h4 class="mb-0">Chọn hồ để tạo game</h4>
      <span class="badge bg-primary ms-2"><?= count($hos) ?></span>
      <a class="btn btn-outline-secondary ms-auto" href="/cauca/chuho/game/game_list_ho.php">Danh sách hồ cho game</a>
    </div>

    <?php if (!$hos): ?>
      <div class="alert alert-warning">
        Không có hồ nào đủ điều kiện (cụm đang chạy, hồ hoạt động, cho phép đánh game).
      </div>
    <?php else: ?>
      <div class="row g-3">
        <?php foreach ($hos as $h): ?>
          <div class="col-12 col-md-6 col-xl-4">
            <div class="card h-100 border-0 shadow-sm card-hover">
              <div class="card-body">
                <div class="fw-semibold"><?= h($h['ten_ho']) ?></div>
                <div class="text-muted small mb-2">Cụm: <?= h($h['ten_cum_ho']) ?></div>
                <div class="d-flex justify-content-between small">
                  <div>Giá game/1 người</div>
                  <div class="fw-semibold"><?= money_vnd($h['gia_game'] ?? 0) ?></div>
                </div>
                <div class="d-flex justify-content-between small">
                  <div>Số chỗ ngồi</div>
                  <div class="fw-semibold"><?= (int)($h['so_cho_ngoi'] ?? 0) ?></div>
                </div>
              </div>
              <div class="card-footer bg-white border-0 pt-0 pb-3">
                <a class="btn btn-primary w-100" href="?ho_id=<?= (int)$h['id'] ?>">
                  Chọn hồ này
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
  <script src="/assets/bootstrap.bundle.min.js"></script>
  </body>
  </html>
  <?php
  exit;
}

/* =========================
 * STEP 1: Có ho_id -> kiểm tra hồ & tải thông tin
 * ========================= */
$sqlHo = "
  SELECT h.id, h.ten_ho, h.gia_game, h.so_cho_ngoi, h.status AS ho_status,
         c.id AS cum_id, c.ten_cum_ho, c.status AS cum_status, c.chu_ho_id
  FROM ho_cau h
  JOIN cum_ho c ON c.id = h.cum_ho_id
  WHERE h.id = :hid
    AND c.chu_ho_id = :uid
    AND h.cho_phep_danh_game = 1
    AND h.status = 'dang_hoat_dong'
    AND c.status = 'dang_chay'
";
$stHo = $pdo->prepare($sqlHo);
$stHo->execute(['hid'=>$ho_id, 'uid'=>$chuho_id]);
$ho = $stHo->fetch(PDO::FETCH_ASSOC);
if (!$ho) { http_response_code(403); echo "Hồ không hợp lệ hoặc không đủ điều kiện tạo game."; exit; }

// Hình thức game (1 bảng, 1 hiệp)
$sqlHinhThuc = "
  SELECT id, ten_hinh_thuc
  FROM giai_game_hinh_thuc
  WHERE hinh_thuc = 'game' AND so_bang = 1 AND so_hiep = 1
  ORDER BY id ASC
";
$htRows = $pdo->query($sqlHinhThuc)->fetchAll(PDO::FETCH_ASSOC);

// ----------------- Xử lý POST: tạo game -----------------
$errors = [];
$allowedDurations = [45,60,75,90,120,180,240];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $ten_game   = trim($_POST['ten_game'] ?? '');
  $hinh_thuc  = (int)($_POST['hinh_thuc_id'] ?? 0);
  $so_nguoi   = (int)($_POST['so_luong_can_thu'] ?? 0);
  $thoi_luong = (int)($_POST['thoi_luong_phut_hiep'] ?? 60);
  $ngay_tc    = trim($_POST['ngay_to_chuc'] ?? '');
  $gio_bd     = trim($_POST['gio_bat_dau'] ?? '');
  $tien_cuoc  = (int)($_POST['tien_cuoc'] ?? 0);

  if ($ten_game === '') $errors[] = "Vui lòng nhập tên game.";

  $htLabel = null;
  foreach ($htRows as $ht) if ((int)$ht['id'] === $hinh_thuc) { $htLabel = (string)$ht['ten_hinh_thuc']; break; }
  if (!$htLabel) $errors[] = "Hình thức game không hợp lệ.";

  // so_luong_can_thu
  $so_nguoi_final = 0;
  if ($htLabel) {
    $solo = parse_solo_from_label($htLabel); // 2|3|4 hoặc null
    if ($solo !== null) $so_nguoi_final = $solo;
    else {
      if ($so_nguoi <= 0) $errors[] = "Vui lòng nhập số lượng cần thủ cho hình thức này.";
      else $so_nguoi_final = $so_nguoi;
    }
  }

  if (!in_array($thoi_luong, $allowedDurations, true)) $errors[] = "Thời lượng 1 hiệp không hợp lệ.";

  $tz = new DateTimeZone('Asia/Ho_Chi_Minh');
  $today = new DateTime('today', $tz);
  $dNgay = DateTime::createFromFormat('Y-m-d', $ngay_tc, $tz);
  if (!$dNgay) $errors[] = "Ngày tổ chức không hợp lệ (YYYY-MM-DD).";
  else { $dNgay->setTime(0,0,0); if ($dNgay < $today) $errors[] = "Ngày tổ chức phải từ hôm nay trở đi."; }

  $dGio = DateTime::createFromFormat('H:i', $gio_bd, $tz);
  if (!$dGio) $errors[] = "Giờ bắt đầu không hợp lệ (HH:MM).";

  // ====== TÍNH PHÍ HỒ & PHÍ GAME ======
  $phi_ho = 0; $phi_game = 0;
  if (!$errors) {
    $cfg = load_game_configs($pdo, ['game_vat_percent','game_fee_user','game_time_basic']);
    $vat_percent = (float)($cfg['game_vat_percent'] ?? 0);  // %
    $fee_user    = (float)($cfg['game_fee_user'] ?? 0);     // VND/người
    $time_basic  = (int)  ($cfg['game_time_basic'] ?? 60);  // phút
    if ($time_basic <= 0) $time_basic = 60;

    $he_so_tg   = $thoi_luong / $time_basic;
    $vat_rate   = max(0.0, $vat_percent / 100.0);
    $gia_game_ho= (float)($ho['gia_game'] ?? 0);

    // Phi_ho = (số người * giá_game_hồ * hệ_số_thời_gian) + VAT
    $base_ho  = $so_nguoi_final * $gia_game_ho * $he_so_tg;
    $phi_ho   = (int) round( $base_ho * (1 + $vat_rate) );

    // Phí quản trị theo người = (số người * game_fee_user) + VAT
    $base_fee_user = $so_nguoi_final * $fee_user;
    $phi_quantri   = (int) round( $base_fee_user * (1 + $vat_rate) );

    // Phi_game = Phi_ho + Phí quản trị (đều đã gồm VAT theo yêu cầu)
    $phi_game = $phi_ho + $phi_quantri;
  }

  if (!$errors) {
    $closeAt = clone $dNgay; $closeAt->modify('-1 day')->setTime(23,59,59);
    try {
      $pdo->beginTransaction();
      $ins = $pdo->prepare("
        INSERT INTO game_list
          (ho_cau_id, chuho_id, creator_id, hinh_thuc_id,
           ten_game, so_luong_can_thu, so_bang, so_hiep,
           thoi_luong_phut_hiep, ngay_to_chuc, gio_bat_dau,
           thoi_gian_dong_dang_ky, tien_cuoc, phi_game, phi_ho, luat_choi, status)
        VALUES
          (:ho_cau_id, :chuho_id, :creator_id, :hinh_thuc_id,
           :ten_game, :so_luong_can_thu, 1, 1,
           :thoi_luong, :ngay_to_chuc, :gio_bat_dau,
           :dong_dk, :tien_cuoc, :phi_game, :phi_ho, NULL, 'dang_cho_xac_nhan')
      ");
      $ins->execute([
        'ho_cau_id'        => $ho_id,
        'chuho_id'         => $chuho_id,
        'creator_id'       => $chuho_id,
        'hinh_thuc_id'     => $hinh_thuc,
        'ten_game'         => $ten_game,
        'so_luong_can_thu' => $so_nguoi_final,
        'thoi_luong'       => $thoi_luong,
        'ngay_to_chuc'     => $dNgay->format('Y-m-d'),
        'gio_bat_dau'      => $dGio->format('H:i:s'),
        'dong_dk'          => $closeAt->format('Y-m-d H:i:s'),
        'tien_cuoc'        => max(0, $tien_cuoc),
        'phi_game'         => $phi_game,
        'phi_ho'           => $phi_ho,
      ]);
      $game_id = (int)$pdo->lastInsertId();
      $pdo->commit();
      header("Location: /cauca/chuho/game/game_user_add.php?game_id=".$game_id);
      exit;
    } catch (Throwable $e) {
      if ($pdo->inTransaction()) $pdo->rollBack();
      $errors[] = "Lỗi khi tạo game: ".$e->getMessage();
    }
  }
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Tạo game - <?= h($ho['ten_ho']) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="/assets/bootstrap.min.css" rel="stylesheet">
  <style>.card-hover:hover{transform:translateY(-2px);box-shadow:0 0.5rem 1rem rgba(0,0,0,.12)}</style>
</head>
<body class="bg-light">
<div class="container py-4">

  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/cauca/chuho/game/game_list.php">Tất cả game</a></li>
      <li class="breadcrumb-item"><a href="/cauca/chuho/game/game_list_ho.php">Hồ cho game</a></li>
      <li class="breadcrumb-item active" aria-current="page">Tạo game tại hồ: <?= h($ho['ten_ho']) ?></li>
    </ol>
  </nav>

  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm card-hover">
        <div class="card-header bg-white fw-semibold">Thông tin game</div>
        <div class="card-body">

          <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
              <div class="fw-semibold mb-1">Không thể tạo game:</div>
              <ul class="mb-0"><?php foreach ($errors as $e) echo '<li>'.h($e).'</li>'; ?></ul>
            </div>
          <?php endif; ?>

          <form method="post" novalidate id="formCreate">
            <div class="mb-3">
              <label class="form-label">Tên game <span class="text-danger">*</span></label>
              <input name="ten_game" class="form-control" maxlength="255" required
                     value="<?= h($_POST['ten_game'] ?? '') ?>">
            </div>

            <div class="mb-3">
              <label class="form-label">Hình thức <span class="text-danger">*</span></label>
              <select name="hinh_thuc_id" id="hinh_thuc_id" class="form-select" required>
                <option value="">-- Chọn hình thức --</option>
                <?php foreach ($htRows as $ht):
                  $label = (string)$ht['ten_hinh_thuc'];
                  $solo  = parse_solo_from_label($label);
                  $sel   = (isset($_POST['hinh_thuc_id']) && (int)$_POST['hinh_thuc_id']===(int)$ht['id']) ? 'selected' : '';
                ?>
                  <option value="<?= (int)$ht['id'] ?>" data-solo="<?= $solo !== null ? (int)$solo : 0 ?>" <?= $sel ?>>
                    <?= h($label) ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <div class="form-text">Solo 2/3/4 người sẽ tự tính số cần thủ; hình thức khác bắt buộc nhập số lượng.</div>
            </div>

            <div class="mb-3" id="wrapSoNguoi" style="display:none;">
              <label class="form-label">Số lượng cần thủ <span class="text-danger" id="soNguoiRequiredAsterisk" style="display:none;">*</span></label>
              <input type="number" min="1" name="so_luong_can_thu" id="so_luong_can_thu" class="form-control" placeholder="VD: 12"
                     value="<?= h($_POST['so_luong_can_thu'] ?? '') ?>">
              <div class="form-text">Bắt buộc cho hình thức không phải Solo 2/3/4.</div>
            </div>

            <div class="mb-3">
              <label class="form-label">Thời lượng 1 hiệp <span class="text-danger">*</span></label>
              <select name="thoi_luong_phut_hiep" class="form-select" required>
                <?php
                  $cur = (int)($_POST['thoi_luong_phut_hiep'] ?? 60);
                  foreach ([45,60,75,90,120,180,240] as $m) {
                    $sel = $m===$cur ? 'selected' : '';
                    echo "<option value=\"$m\" $sel>$m phút</option>";
                  }
                ?>
              </select>
            </div>

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Ngày tổ chức <span class="text-danger">*</span></label>
                <input type="date" name="ngay_to_chuc" class="form-control" required
                       min="<?= (new DateTime('today', new DateTimeZone('Asia/Ho_Chi_Minh')))->format('Y-m-d') ?>"
                       value="<?= h($_POST['ngay_to_chuc'] ?? (new DateTime('today', new DateTimeZone('Asia/Ho_Chi_Minh')))->format('Y-m-d')) ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label">Giờ bắt đầu <span class="text-danger">*</span></label>
                <input type="time" name="gio_bat_dau" class="form-control" required
                       value="<?= h($_POST['gio_bat_dau'] ?? '08:00') ?>">
              </div>
            </div>

            <div class="mt-3 mb-2">
              <label class="form-label">Tiền cược (tuỳ chọn)</label>
              <input type="number" name="tien_cuoc" min="0" step="1000" class="form-control"
                     placeholder="VD: 50000"
                     value="<?= h($_POST['tien_cuoc'] ?? '0') ?>">
            </div>

            <div class="d-grid mt-4">
              <button class="btn btn-primary btn-lg" type="submit">Tạo game</button>
            </div>
          </form>

        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-semibold">Hồ tổ chức</div>
        <div class="card-body">
          <div class="fw-semibold mb-1"><?= h($ho['ten_ho']) ?></div>
          <div class="text-muted small mb-2">Cụm: <?= h($ho['ten_cum_ho']) ?></div>
          <div class="d-flex justify-content-between small">
            <div>Giá game (hồ)/người</div>
            <div class="fw-semibold"><?= money_vnd($ho['gia_game'] ?? 0) ?></div>
          </div>
          <div class="d-flex justify-content-between small">
            <div>Số chỗ ngồi</div>
            <div class="fw-semibold"><?= (int)($ho['so_cho_ngoi'] ?? 0) ?></div>
          </div>
          <div class="small text-muted mt-2">Phí và VAT sẽ được tính tự động khi lưu.</div>
        </div>
      </div>
      <div class="alert alert-info mt-3">
        Sau khi tạo, bạn sẽ được chuyển qua trang <b>thêm người chơi bằng số điện thoại</b>.
      </div>
    </div>
  </div>
</div>

<script>
  const selectHT = document.getElementById('hinh_thuc_id');
  const wrapSoNguoi = document.getElementById('wrapSoNguoi');
  const inputSoNguoi = document.getElementById('so_luong_can_thu');
  const asterisk = document.getElementById('soNguoiRequiredAsterisk');

  function toggleSoNguoi(){
    const opt = selectHT?.options[selectHT.selectedIndex];
    const solo = opt ? parseInt(opt.getAttribute('data-solo') || '0', 10) : 0;
    if (solo > 0) {
      inputSoNguoi.value = solo;
      inputSoNguoi.required = false;
      wrapSoNguoi.style.display = 'none';
      asterisk.style.display = 'none';
    } else {
      if (!inputSoNguoi.value || parseInt(inputSoNguoi.value,10) <= 0) inputSoNguoi.value = '';
      inputSoNguoi.required = true;
      wrapSoNguoi.style.display = '';
      asterisk.style.display = '';
    }
  }
  if (selectHT) {
    selectHT.addEventListener('change', toggleSoNguoi);
    toggleSoNguoi();
  }
</script>

<script src="/assets/bootstrap.bundle.min.js"></script>
</body>
</html>
