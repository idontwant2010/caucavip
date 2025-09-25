<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'canthu') {
    echo "Truy cập bị từ chối.";
    exit;
}

$user_id = $_SESSION['user']['id'];

// Lọc theo trạng thái nếu có
$status_filter = $_GET['status'] ?? '';
$sql = "SELECT gl.*, hc.ten_ho, ght.ten_hinh_thuc
    FROM giai_list gl
    JOIN ho_cau hc ON gl.ho_cau_id = hc.id
    JOIN giai_game_hinh_thuc ght ON gl.hinh_thuc_id = ght.id
    WHERE gl.creator_id = ?";
$params = [$user_id];

if ($status_filter !== '') {
    $sql .= " AND gl.status = ?";
    $params[] = $status_filter;
}

$sql .= " ORDER BY gl.created_at DESC, gl.id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$ds_giai = $stmt->fetchAll();
?>
<?php include_once '../../../includes/header.php'; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

<?php
// Thống kê số lượng giải
$stmt_all = $pdo->prepare("SELECT COUNT(*) FROM giai_list WHERE creator_id = ?");
$stmt_all->execute([$user_id]);
$tong_so_giai = $stmt_all->fetchColumn();

$stmt_done = $pdo->prepare("SELECT COUNT(*) FROM giai_list WHERE creator_id = ? AND status = 'hoan_tat_giai'");
$stmt_done->execute([$user_id]);
$so_giai_hoan_thanh = $stmt_done->fetchColumn();

$stmt_cancel = $pdo->prepare("SELECT COUNT(*) FROM giai_list WHERE creator_id = ? AND status = 'huy_giai'");
$stmt_cancel->execute([$user_id]);
$so_giai_huy = $stmt_cancel->fetchColumn();

$so_giai_dang_chay = $tong_so_giai - $so_giai_hoan_thanh - $so_giai_huy;

?>

<div class="container mt-4">
  <div class="mb-3 text-muted">
    👤 Bạn đã tổ chức tổng cộng <strong><?= $tong_so_giai ?></strong> giải, trong đó <strong><?= $so_giai_hoan_thanh ?></strong> đã hoàn thành, <strong><?= $so_giai_huy ?></strong> đã huỷ, và <strong><?= $so_giai_dang_chay ?></strong> đang chạy...
  </div>
  <form method="get" class="row g-3 mb-3">
    <div class="col-md-4">
      <label class="form-label">Lọc theo trạng thái</label>
      <select name="status" class="form-select" onchange="this.form.submit()">
        <option value="">-- Tất cả --</option>
        <option value="dang_cho_xac_nhan" <?= ($status_filter == 'dang_cho_xac_nhan') ? 'selected' : '' ?>>Đang chờ xác nhận</option>
        <option value="da_chot_danh_sach" <?= ($status_filter == 'da_chot_danh_sach') ? 'selected' : '' ?>>Đã chốt danh sách</option>
        <option value="dang_dien_ra" <?= ($status_filter == 'dang_dien_ra') ? 'selected' : '' ?>>Đang diễn ra</option>
        <option value="hoan_tat_giai" <?= ($status_filter == 'hoan_tat_giai') ? 'selected' : '' ?>>Hoàn thành</option>
        <option value="huy" <?= ($status_filter == 'huy') ? 'selected' : '' ?>>Đã huỷ</option>
      </select>
    </div>
  </form>
  <div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0">📋 Danh sách giải do tôi tổ chức</h4>
  <a href="../giai/giai_ho_cau.php" class="btn btn-success">➕ Tạo giải mới</a>
</div>
  <?php if (empty($ds_giai)): ?>
    <div class="alert alert-warning">Bạn chưa tạo giải nào.</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-light">
          <tr>
            <th>Giải ID</th>
            <th>Tên giải</th>
            <th>Hồ câu</th>
            <th>Hình thức</th>
            <th>Số cần thủ</th>
            <th>Ngày tổ chức</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($ds_giai as $index => $giai): ?>
          <tr>
            <td>#<?= htmlspecialchars($giai['id']) ?></td>
            <td><?= htmlspecialchars($giai['ten_giai']) ?></td>
            <td><?= htmlspecialchars($giai['ten_ho']) ?></td>
            <td><?= htmlspecialchars($giai['ten_hinh_thuc']) ?></td>
            <td><?= $giai['so_luong_can_thu'] ?></td>
            <td><?= date('d/m/Y', strtotime($giai['ngay_to_chuc'])) ?></td>

<td>
    <?php
    switch ($giai['status']) {
        case 'dang_cho_xac_nhan':
            echo '<span class="badge bg-secondary">Đang chờ xác nhận</span>';
            break;
		case 'chuyen_chu_ho_duyet':
            echo '<span class="badge bg-info text-dark">Đang Chuyển Chủ Hồ Duyệt</span>';
            break;	
        case 'dang_mo_dang_ky':
            echo '<span class="badge bg-warning text-dark">Đang mở đăng ký</span>';
            break;
        case 'da_chot_danh_sach':
            echo '<span class="badge bg-warning text-dark">Đã chốt danh sách</span>';
            break;			
			
        case 'dang_dau_hiep_1':
            echo '<span class="badge bg-warning text-dark">Đang đấu hiệp 1</span>';
            break;
        case 'dang_dau_hiep_2':
            echo '<span class="badge bg-warning text-dark">Đang đấu hiệp 2</span>';
            break;
        case 'dang_dau_hiep_3':
            echo '<span class="badge bg-warning text-dark">Đang đấu hiệp 3</span>';
            break;
        case 'dang_dau_hiep_4':
            echo '<span class="badge bg-warning text-dark">Đang đấu hiệp 4</span>';
            break;			
        case 'so_ket_giai':
            echo '<span class="badge bg-warning text-dark">Sơ kết giải</span>';
            break;			
	        case 'hoan_tat_giai':
            echo '<span class="badge bg-success">Hoàn tất giải</span>';
            break;
        case 'huy_giai':
            echo '<span class="badge bg-danger">Huỷ giải</span>';
            break;
        default:
            echo '<span class="badge bg-light text-dark">' . htmlspecialchars($giai['status']) . '</span>';
    }
    ?>
</td>

            <td>
              <a href="my_giai_detail.php?id=<?= $giai['id'] ?>" class="btn btn-sm btn-info">Chi tiết</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
