<?php
// my_giai_detail.php - phiên bản đơn giản cho cần thủ
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if (!isset($_SESSION['user'])) {
    header("Location: /auth/login.php");
    exit;
}
$user_id = (int)$_SESSION['user']['id'];

$giai_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($giai_id <= 0) {
    exit('Thiếu tham số id');
}

// Lấy thông tin giải
$stmt = $pdo->prepare("SELECT * FROM giai_list WHERE id = ?");
$stmt->execute([$giai_id]);
$g = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$g) exit('Không tìm thấy giải');

// Đếm số đã đăng ký
$stmt = $pdo->prepare("SELECT COUNT(*) FROM giai_user WHERE giai_id = ? AND trang_thai NOT IN ('tu_choi','bi_loai')");
$stmt->execute([$giai_id]);
$so_da_dk = (int)$stmt->fetchColumn();

// Lấy thông tin của bạn trong giải
$stmt = $pdo->prepare("SELECT trang_thai, da_thanh_toan FROM giai_user WHERE giai_id = ? AND user_id = ? LIMIT 1");
$stmt->execute([$giai_id, $user_id]);
$my = $stmt->fetch(PDO::FETCH_ASSOC);

// Helper
function vnd($n){ return number_format((int)$n,0,',','.').'đ'; }

?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Chi tiết giải</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <a href="javascript:history.back()" class="btn btn-sm btn-outline-secondary mb-3">← Quay lại</a>

  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0"><?= htmlspecialchars($g['ten_giai']) ?></h5>
    </div>
    <div class="card-body">
      <p><b>Ngày tổ chức:</b> <?= date('d/m/Y', strtotime($g['ngay_to_chuc'])) ?> lúc <?= substr($g['gio_bat_dau'],0,5) ?></p>
      <p><b>Hạn chót đăng ký:</b> <?= date('d/m/Y H:i', strtotime($g['thoi_gian_dong_dang_ky'])) ?></p>
      <p><b>Phí tham gia:</b> <?= vnd($g['tien_cuoc']) ?></p>
      <p><b>Số lượng cần thủ:</b> <?= $so_da_dk ?>/<?= (int)$g['so_luong_can_thu'] ?></p>
      <p><b>Trạng thái giải:</b> <?= htmlspecialchars($g['status']) ?></p>

      <hr>
      <h6>Trạng thái của bạn</h6>
      <?php if ($my): ?>
        <p>Trạng thái: <?= htmlspecialchars($my['trang_thai']) ?></p>
        <p>Thanh toán: <?= $my['da_thanh_toan'] ? 'Đã thanh toán' : 'Chưa thanh toán' ?></p>
      <?php else: ?>
        <p>Bạn chưa đăng ký giải này.</p>
      <?php endif; ?>

      <div class="mt-3">
        <?php if (!$my && $g['status'] === 'dang_mo_dang_ky' && $so_da_dk < $g['so_luong_can_thu']): ?>
          <form method="post" action="dang_ky_giai_process.php" onsubmit="return confirm('Xác nhận đăng ký?');">
            <input type="hidden" name="giai_id" value="<?= (int)$g['id'] ?>">
            <button class="btn btn-success">Đăng ký ngay</button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
</body>
</html>
