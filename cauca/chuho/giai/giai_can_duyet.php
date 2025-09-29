<?php
require_once '../../../connect.php';
require_once '../../../check_login.php';
require_once '../../../includes/header.php';

// Kiểm tra vai trò chủ hồ
if ($_SESSION['user']['vai_tro'] !== 'chuho') {
    echo "<div class='alert alert-danger'>Bạn không có quyền truy cập trang này!</div>";
    require_once '../../../includes/footer.php';
    exit;
}

$chu_ho_id = $_SESSION['user']['id'];

$sql = "
SELECT 
    g.id, g.ten_giai, g.ngay_to_chuc, g.so_luong_can_thu, g.status, g.created_at,
    h.ten_ho, ht.ten_hinh_thuc,
    u.full_name, u.nickname
FROM giai_list g
JOIN ho_cau h ON g.ho_cau_id = h.id
JOIN cum_ho ch ON h.cum_ho_id = ch.id
JOIN users u ON g.creator_id = u.id
JOIN giai_game_hinh_thuc ht ON g.hinh_thuc_id = ht.id
WHERE ch.chu_ho_id = :chu_ho_id
ORDER BY g.created_at DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute(['chu_ho_id' => $chu_ho_id]);
$giai_list = $stmt->fetchAll();
?>

<div class="container py-4">
    <h3 class="mb-4">📋 Danh sách giải đấu tổ chức tại hồ của bạn</h3>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Giải ID</th>
                <th>Tên giải</th>
                <th>Hồ tổ chức</th>
                <th>Ngày tổ chức</th>
                <th>Số cần thủ</th>
                <th>Người tạo</th>
                <th>Hình thức</th>
                <th>Trạng thái</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($giai_list as $index => $g): ?>
                <tr>
                    <td>#<?= htmlspecialchars($g['id']) ?></td>
                    <td><?= htmlspecialchars($g['ten_giai']) ?></td>
                    <td><?= htmlspecialchars($g['ten_ho']) ?></td>
                    <td><?= date('d/m/Y', strtotime($g['ngay_to_chuc'])) ?></td>
                    <td><?= $g['so_luong_can_thu'] ?></td>
                    <td><?= htmlspecialchars($g['nickname'] ?: $g['full_name']) ?></td>
                    <td><?= htmlspecialchars($g['ten_hinh_thuc']) ?></td>
                    <td>
                        <span class="badge bg-<?=
                                                $g['status'] == 'chuyen_chu_ho_duyet' ? 'warning' : ($g['status'] == 'dang_mo_dang_ky' ? 'success' : ($g['status'] == 'dang_mo_dang_ky' ? 'success' : ($g['status'] == 'chot_xong_danh_sach' ? 'success' : ($g['status'] == 'dang_dau_hiep_1' ? 'success' : ($g['status'] == 'dang_dau_hiep_2' ? 'success' : ($g['status'] == 'dang_dau_hiep_3' ? 'success' : ($g['status'] == 'dang_dau_hiep_4' ? 'success' : ($g['status'] == 'so_ket_giai' ? 'success' : ($g['status'] == 'hoan_tat_giai' ? 'success' : ($g['status'] == 'huy_giai' ? 'danger' : 'secondary'))))))))))
                                                ?>">
                            <?= $g['status'] ?>
                        </span>
                    </td>
                    <td>
                        <a href="giai_can_duyet_detail.php?id=<?= $g['id'] ?>" class="btn btn-sm btn-primary">
                            🔍 Chi tiết
                        </a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<?php require_once '../../../includes/footer.php'; ?>