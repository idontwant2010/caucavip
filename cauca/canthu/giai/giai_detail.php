<?php
require_once '../../../connect.php';
require_once '../../../check_login.php';
require_once '../../../includes/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>Thiếu ID giải đấu.</div>";
    require_once '../../../includes/footer.php';
    exit;
}

$giai_id = (int)$_GET['id'];

// Lấy thông tin giải
$stmt = $pdo->prepare("SELECT * FROM giai_list WHERE id = ?");
$stmt->execute([$giai_id]);
$giai = $stmt->fetch();



if (!$giai) {
    echo "<div class='alert alert-danger'>Giải không tồn tại.</div>";
    require_once '../../../includes/footer.php';
    exit;
}

if ($giai['creator_id'] != $_SESSION['user']['id']) {
    echo "<div class='alert alert-danger'>Bạn không có quyền truy cập giải này.</div>";
    require_once '../../../includes/footer.php';
    exit;
}

// Lấy danh sách user tham gia + thông tin đầy đủ
$stmt = $pdo->prepare("
    SELECT 
        gu.user_id, 
        gu.nickname, 
        gu.tong_diem, 
        gu.tong_kg, 
        gu.xep_hang,
        u.full_name
    FROM giai_user gu
    JOIN users u ON gu.user_id = u.id
    WHERE gu.giai_id = ?
    ORDER BY gu.xep_hang ASC, gu.tong_diem ASC
");
$stmt->execute([$giai_id]);
$users = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Báo cáo tổng kết giải #<?= $giai_id ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    table th, table td {
      text-align: center;
      vertical-align: middle;
      font-size: 14px;
    }
    .bg-hi {
      background-color: #e2f5e9;
    }
  </style>
</head>
<body class="container mt-2">
<div class="d-flex justify-content-center mt-2">  <?php include '../../../includes/giai_menu_status.php'; ?></div>
	<hr>
		<?php if (in_array($giai['status'], ['dang_cho_xac_nhan', 'chuyen_chu_ho_duyet'])): ?>
			<div class="d-flex justify-content-center gap-3 mt-2">
					<a href="my_giai_detail.php?id=<?= $giai_id ?>" class="btn btn-info">
							⬅️ Quay về B0: Xem giải, chỉnh sửa, chờ chủ hồ duyệt!
					</a>
			</div>	
		<?php endif; ?>
		
		<?php if (in_array($giai['status'], ['dang_mo_dang_ky'])): ?>	
			<div class="d-flex justify-content-center gap-3 mt-2">
					<a href="my_giai_detail_step_1.php?id=<?= $giai_id ?>" class="btn btn-info">
							⬅️ Quay về B1: Thêm cần thủ vào Giải đang mở đăng ký!
					</a>
			</div>	
		<?php endif; ?>
		
		<div class="d-flex justify-content-center gap-3 mt-2">
			<?php if (in_array($giai['status'], ['chot_xong_danh_sach', 'dang_dau_hiep_1','dang_dau_hiep_2', 'dang_dau_hiep_3', 'dang_dau_hiep_4'])): ?>
				<a href="my_giai_detail_step_2.php?id=<?= $giai_id ?>" class="btn btn-info">
						⬅️ Quay về B2: Chia bảng, thi đấu và cập nhật điểm!
				</a>
			<?php endif; ?>
		</div>
	
		<?php if (in_array($giai['status'], ['so_ket_giai'])): ?>
			<div class="d-flex justify-content-center gap-3 mt-2 ">
				<a href="my_giai_detail_step_2.php?id=<?= $giai_id ?>" class="btn btn-secondary">
						⬅️ Quay về B2: Sửa thành tích bảng A-B-C-D
				</a>
				<form action="so_ket_giai.php" method="POST" onsubmit="return confirm('Sơ kết toàn bộ kết quả giải?')">
					<input type="hidden" name="giai_id" value="<?= $giai_id ?>">
					<button type="submit" class="btn btn-success">🎯Tổng Kết Giải - Xếp Hạng</button>
				</form>
				
				<form action="chot_hoan_thanh_giai.php" method="POST" onsubmit="return xacNhanKetThucGiai();">
				  <input type="hidden" name="giai_id" value="<?= $giai_id ?>">
				  <button type="submit" class="btn btn-danger">✅ Đi tới cuối B4: Hoàn thành và khoá giải</button>
				</form>
			</div>
		<?php endif; ?>
		
		<?php if (in_array($giai['status'], ['hoan_tat_giai'])): ?>
			<div class="d-flex justify-content-center gap-1 mt-2 ">
				<div class="alert alert-success">
					<p>✅ Xin chúc mừng! Bạn đã tổ chức giải câu thành công.</p>
					<p>✅ Xem danh sách các cần thủ và kết quả bên dưới</p>
				</div>
			</div>
		<?php endif; ?>

<h6 class="mb-1 mt-1">🏁 Báo cáo tổng kết giải đấu #<?= $giai_id ?></h6>
	<div class="table-responsive">
    <table class="table table-bordered table-sm table-striped align-middle">
      <thead class="table-light">
        <tr>
          <th rowspan="2">UserID</th>
          <th rowspan="2">Họ tên</th>
          <?php for ($i = 1; $i <= $giai['so_hiep']; $i++): ?>
            <th colspan="5" class="bg-hi">Hiệp <?= $i ?></th>
          <?php endfor; ?>
          <th rowspan="2">Tổng KG</th>
          <th rowspan="2">Tổng Đ</th>
          <th rowspan="2">Hạng</th>
        </tr>
        <tr>
          <?php for ($i = 1; $i <= $giai['so_hiep']; $i++): ?>
            <th class="bg-hi">Vị trí</th>
            <th class="bg-hi">Số kg</th>
            <th class="bg-hi">Điểm</th>
            <th class="bg-hi">Phạt</th>
            <th class="bg-hi">Tổng</th>
          <?php endfor; ?>
        </tr>
      </thead>
      <tbody>
        <?php $stt = 1; foreach ($users as $u): ?>
          <tr>
            <td><?= $stt . '-#' . $u['user_id'] ?></td>
            <td><?= htmlspecialchars($u['full_name']) ?></td>
            <?php
              // Lấy dữ liệu 4 hiệp của user
              $stmt2 = $pdo->prepare("
                SELECT so_hiep, so_bang, vi_tri_ngoi, so_kg, so_diem, diem_cong_vi_pham, tong_diem
                FROM giai_schedule 
                WHERE giai_id = ? AND user_id = ?
                ORDER BY so_hiep ASC
              ");
              $stmt2->execute([$giai_id, $u['user_id']]);
              $hieps = $stmt2->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC);

              for ($h = 1; $h <= $giai['so_hiep']; $h++):
                $row = $hieps[$h][0] ?? null;
            ?>
              <td class="bg-info"><?= isset($row) ? $row['so_bang'] . $row['vi_tri_ngoi'] : '' ?></td>
              <td><?= isset($row) ? $row['so_kg'] : '' ?></td>
              <td><?= isset($row) ? $row['so_diem'] : '' ?></td>
              <td><?= isset($row) ? $row['diem_cong_vi_pham'] : '' ?></td>
              <td><strong><?= isset($row) ? $row['tong_diem'] : '' ?></strong></td>
            <?php endfor; ?>
            <td><strong><?= $u['tong_kg'] ?></strong></td>
            <td><strong><?= $u['tong_diem'] ?></strong></td>
            <td><strong class="text-danger"><?= $u['xep_hang'] ?></strong></td>
          </tr>
        <?php $stt++; endforeach; ?>
      </tbody>
    </table>
  </div>
  
  <div class="d-flex justify-content-end mb-3">
  <a href="export_excel.php?id=<?= $giai_id ?>" class="btn btn-success">
    📥 Download Excel File
  </a>
</div>


</body>


</html>
<?php require_once '../../../includes/footer.php'; ?>

<script>
function xacNhanKetThucGiai() {
  if (confirm('🔔 Bạn chắc chắn đã nhấn nút Tổng Kết Giải - Xếp Hạng trước khi tiếp tục 🔔 Bạn chắc chắn muốn kết thúc giải và lưu thành tích?')) {
    return confirm('⚠️ Hành động này sẽ KHÓA dữ liệu và KHÔNG thể hoàn tác. Tiếp tục?');
  }
  return false;
}
</script>