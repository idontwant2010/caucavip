<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'admin') {
    header('Location: /caucavip/dashboard/index.php');
    exit;
}

// Lấy danh sách cụm hồ
$cum_stmt = $pdo->query("SELECT id, ten_cum_ho FROM cum_ho ORDER BY ten_cum_ho");
$cum_list = $cum_stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy dữ liệu filter
$keyword = trim($_GET['keyword'] ?? '');
$cum_ho_id = $_GET['cum_ho_id'] ?? '';

// Xử lý điều kiện lọc
$where = [];
$params = [];

if ($keyword !== '') {
    $where[] = "(hc.ten_ho LIKE :kw1 OR ch.ten_cum_ho LIKE :kw2 OR xa.ten_xa_phuong LIKE :kw3 OR u.phone LIKE :kw4)";
    $params[':kw1'] = $params[':kw2'] = $params[':kw3'] = $params[':kw4'] = '%' . $keyword . '%';
}

if ($cum_ho_id !== '') {
    $where[] = "hc.cum_ho_id = :cum_ho_id";
    $params[':cum_ho_id'] = $cum_ho_id;
}

$where_sql = count($where) ? "WHERE " . implode(" AND ", $where) : "";

// Truy vấn hồ câu
$sql = "
    SELECT hc.*, ch.ten_cum_ho, xa.ten_xa_phuong, u.phone AS chu_ho_phone
    FROM ho_cau hc
    LEFT JOIN cum_ho ch ON hc.cum_ho_id = ch.id
    LEFT JOIN dm_xa_phuong xa ON ch.xa_id = xa.id
    LEFT JOIN users u ON ch.chu_ho_id = u.id
    $where_sql
    ORDER BY hc.id DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include __DIR__ . '/../../../includes/header.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>🏞️ Danh sách Hồ Câu</h4>
        <a href="ho_cau_add.php" class="btn btn-success">+ Thêm hồ câu</a>
    </div>

    <form method="get" class="row g-2 mb-3">
        <div class="col-md-6">
            <input type="text" name="keyword" class="form-control" placeholder="🔍 Tìm tên hồ, cụm hồ, xã, chủ hồ..." value="<?= htmlspecialchars($keyword) ?>">
        </div>
        <div class="col-md-4">
            <select name="cum_ho_id" class="form-select select2">
                <option value="">-- Tất cả cụm hồ --</option>
                <?php foreach ($cum_list as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= ($c['id'] == $cum_ho_id ? 'selected' : '') ?>>
                        <?= $c['ten_cum_ho'] ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100" type="submit">Lọc</button>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Tên hồ</th>
                <th>Cụm hồ</th>
                <th>Xã</th>
                <th>Chủ hồ</th>
                <th>Diện tích</th>
                <th>Chỗ ngồi</th>
                <th>Trạng thái</th>
                <th>Game</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!$list): ?>
                <tr><td colspan="11" class="text-center text-muted">Không có dữ liệu</td></tr>
            <?php else: ?>
                <?php foreach ($list as $row): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['ten_ho']) ?></td>
                        <td><?= htmlspecialchars($row['ten_cum_ho']) ?></td>
                        <td><?= htmlspecialchars($row['ten_xa_phuong']) ?></td>
                        <td><?= htmlspecialchars($row['chu_ho_phone']) ?></td>
                        <td><?= $row['dien_tich'] ?> m²</td>
                        <td><?= $row['so_cho_ngoi'] ?></td>
                        <td>
                            <?php
                            switch ($row['status']) {
                                case 'dang_hoat_dong': echo '<span class="badge bg-success">Đang hoạt động</span>'; break;
                                case 'chua_mo': echo '<span class="badge bg-secondary">Chưa mở</span>'; break;
                                case 'chuho_tam_khoa': echo '<span class="badge bg-warning text-dark">Chủ hồ khóa</span>'; break;
                                default: echo '<span class="badge bg-danger">Admin khóa</span>';
                            }
                            ?>
                        </td>
                        <td><?= $row['cho_phep_danh_game'] ? '<span class="text-success">✔</span> ' . number_format($row['gia_game']) . 'đ' : '<span class="text-muted">✘</span>' ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                        <td><a href="ho_cau_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger">Sửa</a>
						<a href="ho_cau_detail.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success">Xem</a></td>
                    </tr>
                <?php endforeach ?>
            <?php endif ?>
        </tbody>
    </table>
</div>

<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2({ width: '100%' });
    });
</script>

<?php include __DIR__ . '/../../../includes/footer.php'; ?>
