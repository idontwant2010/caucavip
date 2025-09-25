<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

// Kiểm tra quyền admin (nếu cần)
if ($_SESSION['user']['vai_tro'] !== 'admin') {
    header('Location: /caucavip/dashboard/index.php');
    exit;
}

// Xử lý tìm kiếm
$sql = "SELECT xa.*, tinh.ten_tinh 
        FROM dm_xa_phuong xa 
        JOIN dm_tinh tinh ON xa.tinh_id = tinh.id";

$params = [];

if (!empty($_GET['q'])) {
    $q = '%' . $_GET['q'] . '%';
    $sql .= " WHERE xa.ten_xa_phuong LIKE ? OR tinh.ten_tinh LIKE ? OR xa.ma_xa_phuong LIKE ?";
    $params = [$q, $q, $q];
}

$sql .= " ORDER BY tinh.ten_tinh ASC, xa.ten_xa_phuong ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();
?>

<?php include_once __DIR__ . '/../../../includes/header.php'; ?>
<div class="container mt-4">
    <h3 class="mb-3">📋 Danh sách xã/phường</h3>

    <form method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Tìm tỉnh, xã, mã..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
            <button class="btn btn-primary" type="submit">Tìm</button>
        </div>
    </form>

    <a href="xa_phuong_add.php" class="btn btn-success mb-3">➕ Thêm xã/phường</a>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Mã xã/phường</th>
                    <th>Tên xã/phường</th>
                    <th>Tỉnh</th>
                    <th>Trạng thái</th>
					<th>Hành động</th> <!-- mới -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $index => $row): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><span class="badge bg-secondary"><?= htmlspecialchars($row['ma_xa_phuong']) ?></span></td>
                        <td><?= htmlspecialchars($row['ten_xa_phuong']) ?></td>
                        <td><?= htmlspecialchars($row['ten_tinh']) ?></td>
                        <td>
                            <?php if ($row['is_active']): ?>
                                <span class="badge bg-success">Hoạt động</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Ngưng</span>
                            <?php endif; ?>
                        </td>
						
						<td>
							<a href="xa_phuong_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">✏️ Sửa </a>
						</td>
						
                    </tr>
                <?php endforeach; ?>
                <?php if (count($rows) === 0): ?>
                    <tr><td colspan="5" class="text-center text-muted">Không có dữ liệu</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include_once __DIR__ . '/../../../includes/footer.php'; ?>
