<?php
// my_giai_list_join.php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';
require_once '../../../includes/header.php';

if (!isset($_SESSION['user'])) {
    header("Location: /auth/login.php");
    exit;
}
$user_id = (int)$_SESSION['user']['id'];

// Input: search & pagination
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 15;
$offset = ($page - 1) * $perPage;

// Base SQL
$sqlWhere = "gu.user_id = :uid";
$params = [':uid' => $user_id];

if ($q !== '') {
    $sqlWhere .= " AND gl.ten_giai LIKE :q";
    $params[':q'] = '%' . $q . '%';
}

// Đếm tổng
$sqlCount = "
    SELECT COUNT(*)
    FROM giai_user gu
    JOIN giai_list gl ON gl.id = gu.giai_id
    WHERE $sqlWhere
";
$stmt = $pdo->prepare($sqlCount);
$stmt->execute($params);
$total = (int)$stmt->fetchColumn();
$totalPages = max(1, (int)ceil($total / $perPage));

// Lấy danh sách
$sqlList = "
    SELECT
        gu.id AS giai_user_id,
        gu.giai_id,
        gu.trang_thai AS trang_thai_user,
        gu.payment_time,
        gu.nickname,
        gu.tong_diem,
        gu.tong_kg,
        gu.xep_hang,
        gu.created_at AS joined_at,

        gl.ten_giai,
        gl.ngay_to_chuc,
        gl.gio_bat_dau,
        gl.tien_cuoc,
        gl.status AS trang_thai_giai
    FROM giai_user gu
    JOIN giai_list gl ON gl.id = gu.giai_id
    WHERE $sqlWhere
    ORDER BY gu.created_at DESC
    LIMIT :limit OFFSET :offset
";
$stmt = $pdo->prepare($sqlList);

// bind value cho limit/offset phải dùng PDO::PARAM_INT
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v);
}
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Helper hiển thị badge
function badgeTrangThaiUser($s) {
    $map = [
        'cho_xac_nhan'  => 'secondary',
        'xac_nhan'      => 'info',
        'tu_choi'       => 'warning',
        'bi_loai'       => 'danger',
    ];
    $cls = $map[$s] ?? 'secondary';
    return '<span class="badge bg-' . $cls . '">' . htmlspecialchars($s) . '</span>';
}

function badgeTrangThaiGiai($s) {
    // Rút gọn map, bạn có thể tinh chỉnh thêm theo enum của giai_list.status
    $map = [
        'dang_mo_dang_ky'   => 'primary',
        'chot_xong_danh_sach'=> 'info',
        'dang_dau_hiep_1'   => 'success',
        'dang_dau_hiep_2'   => 'success',
        'dang_dau_hiep_3'   => 'success',
        'dang_dau_hiep_4'   => 'success',
        'dang_dau_hiep_5'   => 'success',
        'dang_dau_hiep_6'   => 'success',
        'so_ket_giai'       => 'secondary',
        'hoan_tat_giai'     => 'dark',
        'huy_giai'          => 'danger',
        'dang_cho_xac_nhan' => 'secondary',
        'chuyen_chu_ho_duyet'=> 'warning',
    ];
    $cls = $map[$s] ?? 'secondary';
    return '<span class="badge bg-' . $cls . '">' . htmlspecialchars($s) . '</span>';
}

?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Giải tôi đã đăng ký</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="mb-0">Giải tôi đã đăng ký</h3>
    <a href="../giai/giai_ho_cau_list.php" class="btn btn-sm btn-outline-primary">+ Tham gia giải mới</a>
  </div>

  <form class="row g-2 mb-3" method="get">
    <div class="col-sm-8 col-md-6">
      <input name="q" value="<?= htmlspecialchars($q) ?>" class="form-control" placeholder="Tìm theo tên giải...">
    </div>
    <div class="col-auto">
      <button class="btn btn-primary">Tìm kiếm</button>
    </div>
    <?php if ($q !== ''): ?>
      <div class="col-auto">
        <a class="btn btn-outline-secondary" href="?">Xóa lọc</a>
      </div>
    <?php endif; ?>
  </form>

  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th style="width: 36px;">#</th>
              <th>Tên giải</th>
              <th>Thời gian</th>
              <th>Phí tham gia</th>
              <th>Trạng thái giải</th>
              <th>Trạng thái của tôi</th>
              <th>Điểm/Kg</th>
              <th>Xếp hạng</th>
              <th>Đăng ký lúc</th>
              <th>Kết Quả</th>
            </tr>
          </thead>
          <tbody>
          <?php if (!$rows): ?>
            <tr><td colspan="11" class="text-center py-4 text-muted">Bạn chưa đăng ký giải nào.</td></tr>
          <?php else: ?>
				<?php
				$stt = $offset + 1;
				foreach ($rows as $r):
					$ten = $r['ten_giai'];
					$tg = date('d/m/Y', strtotime($r['ngay_to_chuc'])) . ' ' . substr($r['gio_bat_dau'], 0, 5);
					$phi = number_format((int)$r['tien_cuoc'], 0, ',', '.') . 'đ';
					$badgeGiai = badgeTrangThaiGiai($r['trang_thai_giai']);
					$badgeUser = badgeTrangThaiUser($r['trang_thai_user']);
					$diemkg = number_format((float)$r['tong_diem'], 2, ',', '.') . 'đ / ' . number_format((float)$r['tong_kg'], 2, ',', '.') . 'kg';
					$xephang = (int)$r['xep_hang'] > 0 ? (int)$r['xep_hang'] : '-';
					$joined  = $r['joined_at'] ? date('d/m/Y H:i', strtotime($r['joined_at'])) : '';

					// trạng thái lấy ưu tiên từ 'trang_thai_user', fallback 'trang_thai'
					$status = $r['trang_thai_user'] ?? ($r['trang_thai'] ?? '');

					$detailUrl = ($status === 'da_thanh_toan')
						? "my_giai_detail_step_1.php?id=" . (int)$r['giai_id']
						: "giai_list.php";
				?>

              <tr>
                <td><?= $stt++ ?></td>
                <td>
                  <div class="fw-semibold"><?= htmlspecialchars($ten) ?></div>
                  <div class="text-muted small"><?= htmlspecialchars($r['nickname'] ?? '') ?></div>
                </td>
                <td><?= $tg ?></td>
                <td><?= $phi ?></td>
                <td><?= $badgeGiai ?></td>
                <td><?= $badgeUser ?></td>
                <td><?= $diemkg ?></td>
                <td><?= $xephang ?></td>
                <td><small><?= $joined ?></small></td>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-primary" href="<?= $detailUrl ?>">Xem KQ</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <?php if ($totalPages > 1): ?>
    <nav class="mt-3">
      <ul class="pagination pagination-sm">
        <?php
        $base = '?'. http_build_query(array_filter(['q'=>$q ?: null]));
        for ($p=1; $p <= $totalPages; $p++):
          $active = $p === $page ? 'active' : '';
        ?>
          <li class="page-item <?= $active ?>"><a class="page-link" href="<?= $base . ($base === '?' ? '' : '&') . 'page=' . $p ?>"><?= $p ?></a></li>
        <?php endfor; ?>
      </ul>
    </nav>
  <?php endif; ?>

</div>
</body>
</html>
<?php require_once '../../../includes/footer.php'; ?>