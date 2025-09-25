<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

// Kiểm tra quyền admin
if ($_SESSION['user']['vai_tro'] !== 'admin') {
    header('Location: /caucavip/dashboard/index.php');
    exit;
}

// Xử lý tìm kiếm
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$cond = '';
$params = [];

if ($keyword !== '') {
    $cond = "WHERE ch.ten_cum_ho LIKE :kw1 
          OR ch.dia_chi LIKE :kw2 
          OR xa.ten_xa_phuong LIKE :kw3 
          OR u.phone LIKE :kw4";
    $params = [
        ':kw1' => '%' . $keyword . '%',
        ':kw2' => '%' . $keyword . '%',
        ':kw3' => '%' . $keyword . '%',
        ':kw4' => '%' . $keyword . '%',
    ];
}

// Truy vấn danh sách cụm hồ
$sql = "
    SELECT ch.*, xa.ten_xa_phuong, u.phone AS chu_ho_phone
    FROM cum_ho ch
    LEFT JOIN dm_xa_phuong xa ON ch.xa_id = xa.id
    LEFT JOIN users u ON ch.chu_ho_id = u.id
    $cond
    ORDER BY ch.id DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include __DIR__ . '/../../../includes/header.php'; ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>📍 Danh sách Cụm Hồ</h4>
        <a href="cum_ho_add.php" class="btn btn-success">+ Thêm cụm hồ</a>
    </div>

    <form method="get" class="mb-3">
        <div class="input-group">
            <input type="text" name="keyword" class="form-control" placeholder="🔍 Tìm cụm hồ, xã, địa chỉ, chủ hồ..." value="<?= htmlspecialchars($keyword) ?>">
            <button class="btn btn-primary" type="submit">Tìm</button>
            <?php if ($keyword): ?>
                <a href="cum_ho_list.php" class="btn btn-outline-secondary">Reset</a>
            <?php endif; ?>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Tên cụm hồ</th>
                <th>Chủ hồ</th>
                <th>Xã</th>
                <th>Địa chỉ</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($list) === 0): ?>
                <tr><td colspan="8" class="text-center text-muted">Không có dữ liệu</td></tr>
            <?php else: ?>
                <?php foreach ($list as $row): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['ten_cum_ho']) ?></td>
                        <td><?= htmlspecialchars($row['chu_ho_phone']) ?></td>
                        <td><?= htmlspecialchars($row['ten_xa_phuong']) ?></td>
                        <td><?= htmlspecialchars($row['dia_chi']) ?></td>
                        <td>
                            <?php
                                switch ($row['status']) {
                                    case 'dang_chay':
                                        echo '<span class="badge bg-success">Đang chạy</span>';
                                        break;
                                    case 'chuho_tam_khoa':
                                        echo '<span class="badge bg-warning text-dark">Chủ hồ khóa</span>';
                                        break;
                                    default:
                                        echo '<span class="badge bg-danger">Admin khóa</span>';
                                }
                            ?>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                        <td>
                            <a href="cum_ho_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger">Sửa</a>
							<a href="cum_ho_detail.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success">xem</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php endif ?>
        </tbody>
    </table>
</div>
<?php include __DIR__ . '/../../../includes/footer.php'; ?>
