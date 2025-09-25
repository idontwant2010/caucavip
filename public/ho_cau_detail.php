<?php
// /cauca/public/ho_cau_detail.php
session_start();

// Xử lý khi bấm "Đặt vé ngay"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'book_now') {
    if (!isset($_SESSION['user'])) {
        // Chưa login -> đưa về trang đăng nhập
        header('Location: /auth/login.php');
        exit;
    } else {
        // Đã login -> về trang chủ theo vai trò (giống index.php)
        $role = $_SESSION['user']['vai_tro'] ?? '';
        switch ($role) {
            case 'admin':
                header('Location: /cauca/admin/dashboard_admin.php'); break;
            case 'moderator':
                header('Location: /cauca/moderator/dashboard_moderator.php'); break;
            case 'canthu':
                header('Location: /cauca/canthu/dashboard_canthu.php'); break;
            case 'chuho':
                header('Location: /cauca/chuho/dashboard_chuho.php'); break;
            default:
                header('Location: /dashboard/index.php'); break;
        }
        exit;
    }
}

// Includes chung (giả định header có $pdo)
require_once __DIR__ . '/../includes/header.php';

// Lấy id hồ
$hoId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$ho = null;
$error = null;

if ($hoId <= 0) {
    $error = "Thiếu tham số hồ.";
} else {
    try {
        $sql = "
            SELECT h.id, h.cum_ho_id, h.ten_ho, h.mo_ta, h.cam_moi, h.status, h.ly_do_dong,
                   h.luong_ca, h.so_cho_ngoi, h.cho_phep_danh_game, h.cho_phep_danh_giai,
                   h.gia_game, h.gia_giai, h.cho_phep_xoi, h.gia_xoi, h.cho_phep_khoen, h.gia_khoen,
                   h.cho_phep_danh_thit, h.cho_phep_xe_heo, h.gia_xe_heo, h.created_at,
                   c.ten_cum_ho, c.dia_chi, c.google_map_url
            FROM ho_cau h
            JOIN cum_ho c ON h.cum_ho_id = c.id
            WHERE h.id = :id
            LIMIT 1
        ";
        $st = $pdo->prepare($sql);
        $st->execute([':id' => $hoId]);
        $ho = $st->fetch(PDO::FETCH_ASSOC);
        if (!$ho) $error = "Không tìm thấy hồ.";
    } catch (Throwable $e) {
        $error = "Lỗi tải dữ liệu: " . $e->getMessage();
    }
}

// Helper badge
function badge_bool($val, $labelTrue = 'Có', $labelFalse = 'Không') {
    $is = (int)$val ? true : false;
    $cls = $is ? 'bg-success' : 'bg-secondary';
    $txt = $is ? $labelTrue : $labelFalse;
    return '<span class="badge '.$cls.'">'.$txt.'</span>';
}

// Helper status
function badge_status($status) {
    $map = [
        'dang_hoat_dong'  => ['bg-success', 'Đang hoạt động'],
        'tam_dung'        => ['bg-warning text-dark', 'Tạm dừng'],
        'chuho_tam_khoa'  => ['bg-secondary', 'Chủ hồ tạm khoá'],
        'admin_tam_khoa'  => ['bg-secondary', 'Admin tạm khoá'],
        'dong_vinh_vien'  => ['bg-danger', 'Đóng vĩnh viễn'],
    ];
    [$cls, $txt] = $map[$status] ?? ['bg-secondary', $status];
    return '<span class="badge '.$cls.'">'.$txt.'</span>';
}
?>

<div class="container mt-4">
  <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php elseif ($ho): ?>
    <div class="row">
      <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <h3 class="mb-1"><?= htmlspecialchars($ho['ten_ho']) ?></h3>
                <div class="text-muted">
                  Thuộc cụm: <strong><?= htmlspecialchars($ho['ten_cum_ho']) ?></strong>
                </div>
              </div>
              <div><?= badge_status($ho['status']) ?></div>
            </div>

            <hr>

            <div class="mb-3">
              <div class="fw-semibold mb-1">Mô tả</div>
              <div><?= nl2br(htmlspecialchars($ho['mo_ta'] ?? '')) ?></div>
            </div>

            <div class="mb-3">
              <div class="fw-semibold mb-1">Cấm mồi</div>
              <div><?= nl2br(htmlspecialchars($ho['cam_moi'] ?? '')) ?></div>
            </div>

            <div class="row g-3">
              <div class="col-md-4">
                <div class="small text-muted">Lượng cá (ước tính)</div>
                <div class="fw-semibold"><?= (int)($ho['luong_ca'] ?? 0) ?></div>
              </div>
              <div class="col-md-4">
                <div class="small text-muted">Số chỗ ngồi</div>
                <div class="fw-semibold"><?= (int)($ho['so_cho_ngoi'] ?? 0) ?></div>
              </div>
              <div class="col-md-4">
                <div class="small text-muted">Ngày tạo</div>
                <div class="fw-semibold"><?= htmlspecialchars(date('d/m/Y H:i', strtotime($ho['created_at']))) ?></div>
              </div>
            </div>

            <?php if (!empty($ho['ly_do_dong']) && $ho['status'] !== 'dang_hoat_dong'): ?>
              <div class="alert alert-warning mt-3 mb-0">
                <strong>Lý do:</strong> <?= htmlspecialchars($ho['ly_do_dong']) ?>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <div class="card shadow-sm mb-4">
          <div class="card-body">
            <div class="fw-semibold mb-3">Tuỳ chọn & Phí</div>

            <div class="row gy-3">
              <div class="col-md-6">
                <div class="d-flex justify-content-between align-items-center">
                  <div>Cho phép đánh game</div>
                  <div><?= badge_bool($ho['cho_phep_danh_game']) ?></div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                  <div>Phí game (cơ bản)</div>
                  <div class="fw-semibold"><?= number_format((int)$ho['gia_game']) ?> đ</div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="d-flex justify-content-between align-items-center">
                  <div>Cho phép đánh giải</div>
                  <div><?= badge_bool($ho['cho_phep_danh_giai']) ?></div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                  <div>Phí giải (cơ bản)</div>
                  <div class="fw-semibold"><?= number_format((int)$ho['gia_giai']) ?> đ</div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="d-flex justify-content-between align-items-center">
                  <div>Cho phép xôi</div>
                  <div><?= badge_bool($ho['cho_phep_xoi']) ?></div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                  <div>Giá xôi</div>
                  <div class="fw-semibold"><?= number_format((int)$ho['gia_xoi']) ?> đ</div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="d-flex justify-content-between align-items-center">
                  <div>Cho phép khoen</div>
                  <div><?= badge_bool($ho['cho_phep_khoen']) ?></div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                  <div>Giá khoen</div>
                  <div class="fw-semibold"><?= number_format((int)$ho['gia_khoen']) ?> đ</div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="d-flex justify-content-between align-items-center">
                  <div>Cho phép xẻ heo</div>
                  <div><?= badge_bool($ho['cho_phep_xe_heo']) ?></div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                  <div>Giá xẻ heo</div>
                  <div class="fw-semibold"><?= number_format((int)$ho['gia_xe_heo']) ?> đ</div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="d-flex justify-content-between align-items-center">
                  <div>Cho phép đánh thịt</div>
                  <div><?= badge_bool($ho['cho_phep_danh_thit']) ?></div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div> <!-- /col-lg-8 -->

      <div class="col-lg-4">
        <div class="card shadow-sm mb-4">
          <div class="card-body">
            <div class="fw-semibold mb-2">Địa chỉ & Liên kết</div>
            <div class="mb-2">
              <div class="small text-muted mb-1">Địa chỉ</div>
              <div><?= htmlspecialchars($ho['dia_chi'] ?? '—') ?></div>
            </div>
            <div>
              <a class="btn btn-outline-secondary btn-sm" target="_blank"
                 href="<?= htmlspecialchars($ho['google_map_url'] ?: '#') ?>">
                Mở Google Maps
              </a>
            </div>
          </div>
        </div>

        <div class="card shadow-sm">
          <div class="card-body text-center">
            <form method="post">
              <input type="hidden" name="action" value="book_now">
              <button type="submit" class="btn btn-primary w-100">
                Đặt vé ngay
              </button>
            </form>
            <a href="/index.php" class="btn btn-link mt-2">← Quay lại trang chủ</a>
          </div>
        </div>
      </div> <!-- /col-lg-4 -->
    </div> <!-- /row -->
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
