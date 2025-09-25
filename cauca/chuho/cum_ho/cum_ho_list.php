<?php
require_once '../../../connect.php';
require_once '../../../check_login.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['vai_tro'] !== 'chuho') {
    header("Location: ../../../no_permission.php");
    exit;
}

$chu_ho_id = $_SESSION['user']['id'];

// Lấy danh sách cụm hồ của chủ hồ
$stmt = $pdo->prepare("SELECT cum_ho.*, dm_xa_phuong.ten_xa_phuong 
                       FROM cum_ho 
                       JOIN dm_xa_phuong ON cum_ho.xa_id = dm_xa_phuong.id 
                       WHERE cum_ho.chu_ho_id = :chu_ho_id 
                       ORDER BY cum_ho.created_at DESC");
$stmt->execute(['chu_ho_id' => $chu_ho_id]);
$cum_ho_list = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách Cụm hồ</title>
    <link href="../../../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include '../../../includes/header.php'; ?>

<div class="container mt-4">
    <h3 class="text-primary">📍Danh sách cụm hồ</h3>
    <a href="cum_ho_add.php" class="btn btn-success mb-3"> ➕ Tạo "cụm hồ" mới</a>
    <a href="../ho_cau/ho_cau_list.php" class="btn btn-success mb-3"> ➜ Xem/tạo hồ câu</a>
	<div class="alert alert-info mt-1" role="alert">
	<strong>Quy trình: </strong> Bước 1: tạo "cụm hồ" ==> Bước 2: tạo 'hồ câu' bên trong cụm hồ ==> Bước 3: sửa bảng giá bắt đầu hoạt động —
	<a class="alert-link" href="../gia/gia_ho_list.php">xem/sửa bảng giá</a>.
	</div>
    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Tên cụm hồ</th>
                <th>Xã</th>
                <th>Địa chỉ</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cum_ho_list as $index => $row): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($row['ten_cum_ho']) ?></td>
                    <td><?= htmlspecialchars($row['ten_xa_phuong']) ?></td>
                    <td><?= htmlspecialchars($row['dia_chi']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td>
                        <a href="cum_ho_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Sửa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../../../includes/footer.php'; ?>
<script src="../../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
