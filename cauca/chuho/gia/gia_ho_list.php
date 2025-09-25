<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['vai_tro'] !== 'chuho') {
    header("Location: /caucavip/no_permission.php");
    exit();
}

require_once __DIR__ . '/../../../includes/header.php';

// Lấy danh sách bảng giá và tên hồ
$uid = $_SESSION['user']['id'];

$stmt = $pdo->prepare("
    SELECT g.*, h.ten_ho, g.ho_cau_id, c.ten_cum_ho
    FROM gia_ca_thit_phut g
    JOIN ho_cau h ON g.ho_cau_id = h.id
    JOIN cum_ho c ON h.cum_ho_id = c.id
    WHERE c.chu_ho_id = :uid
		AND h.status = 'dang_hoat_dong'
	ORDER BY c.ten_cum_ho ASC, h.ten_ho ASC, g.ten_bang_gia ASC
");
$stmt->execute(['uid' => $uid]);

$bang_gia_all = [];
foreach ($stmt->fetchAll() as $row) {
$bang_gia_all[$row['ho_cau_id']]['ten_ho'] = $row['ten_ho'];
$bang_gia_all[$row['ho_cau_id']]['ten_cum_ho'] = $row['ten_cum_ho'] ?? '';
$bang_gia_all[$row['ho_cau_id']]['ds'][] = $row;
}
?>

<div class="container mt-4">
    <h5 class="mb-2">📋 Bảng giá của hồ câu "đang hoạt động"</h3>

    <?php foreach ($bang_gia_all as $ho_cau_id => $ho): ?>
        <div class="d-flex justify-content-between align-items-center mt-4">
<h6 class="text-primary mb-0">
    🐟 Hồ: <?= htmlspecialchars($ho['ten_ho']) ?> 
    <small class="text-muted">(<?= htmlspecialchars($ho['ten_cum_ho']) ?>)</small>
</h6>
       </div>
        <table class="table table-bordered table-hover mt-2">
            <thead class="table-light text-center">
                <tr>
                    <th>Tên bảng giá</th>
                    <th>Giá suất câu</th>
                    <th>Thời gian suất (phút)</th>
                    <th>Giá thêm 15 phút</th>
                    <th>Giá thu cá</th>					
                    <th>Đơn vị</th>
                    <th>Trạng thái</th>
                    <th>Sửa</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php foreach ($ho['ds'] as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['ten_bang_gia']) ?></td>
                        <td><?= number_format($row['base_price']) ?>đ</td>
                        <td><?= $row['base_duration'] ?> phút</td>
                        <td><?= number_format($row['extra_unit_price']) ?>đ</td>
                        <td><?= number_format($row['gia_thu_lai']) ?>đ</td>						
                        <td><?= $row['loai_thu'] === 'kg' ? 'Kg' : 'Con' ?></td>
                        <td>
                            <?php if ($row['status'] === 'open'): ?>
                                <span class="badge bg-success">Đang mở</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Đã đóng</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="gia_ho_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
