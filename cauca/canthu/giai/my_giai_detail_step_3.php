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