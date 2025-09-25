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


// Lấy danh sách người tham gia
$stmt = $pdo->prepare("SELECT gu.*, u.full_name, u.phone, u.nickname FROM giai_user gu JOIN users u ON gu.user_id = u.id WHERE gu.giai_id = ? ORDER BY gu.id DESC");
$stmt->execute([$giai_id]);
$list_users = $stmt->fetchAll();

// Kiểm tra số lượng đã đăng ký
$stmt = $pdo->prepare("SELECT COUNT(*) FROM giai_user WHERE giai_id = ?");
$stmt->execute([$giai_id]);
$so_nguoi_da_tham_gia = (int) $stmt->fetchColumn();

// che số Điện thoại
if (!function_exists('mask_phone')) {
  function mask_phone($phone, $maskLength = 6) {
    return strlen($phone) > $maskLength
      ? substr_replace($phone, str_repeat('x', $maskLength), 0, $maskLength)
      : $phone;
  }
}

?>

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
<div class="container py-1">
<hr>


  <h6 class="mt-2">🎯 Danh sách tham gia giải: <strong style="color: green;" ><?= htmlspecialchars($giai['ten_giai']) ?></strong></h6>
  <table class="table table-bordered mt-2">
    <thead class="table-light">
      <tr>
        <th>STT</th>
        <th>Họ tên</th>
        <th>SĐT</th>
        <th>Nickname</th>
        <th>Ghi chú</th>
        <th>Thêm lúc</th>
        <th>Trạng thái</th>	
      </tr>
    </thead>
    <tbody>
    <?php foreach ($list_users as $index => $row): ?>
      <tr>
        <td><?= $index + 1 ?>#<?= htmlspecialchars($row['user_id']) ?></td>
        <td><?= htmlspecialchars($row['full_name']) ?></td>
        <td><?= htmlspecialchars(mask_phone($row['phone'])) ?></td>
        <td><?= htmlspecialchars($row['nickname']) ?> </td>
        <td><?= htmlspecialchars($row['note'] ?? '') ?></td>
        <td><?= $row['created_at'] ?></td>
				<?php
				$status_map = [
					'moi_cho_phan_hoi' => ['label' => 'Được chủ giải thêm, chờ người dùng xác nhận', 'class' => 'warning'],
					'moi_da_tu_choi'   => ['label' => 'Người dùng từ chối lời mời', 'class' => 'danger'],
					'da_thanh_toan'    => ['label' => 'Đã tham gia, đã thanh toán', 'class' => 'success']
				];

				$st = $row['trang_thai'] ?? '';
				if (isset($status_map[$st])) {
					$label = $status_map[$st]['label'];
					$class = $status_map[$st]['class'];
				} else {
					$label = htmlspecialchars($st);
					$class = 'secondary';
				}
				?>
		<td><span class="badge bg-<?= $class ?>"><?= $label ?></span></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
    <hr>

</div>
</body>
</html>
<?php require_once '../../../includes/footer.php'; ?>
