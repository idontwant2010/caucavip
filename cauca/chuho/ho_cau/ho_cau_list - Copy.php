
<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';
require_once __DIR__ . '/../../../includes/header.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['vai_tro'] !== 'chuho') {
    header("Location: /caucavip/no_permission.php");
    exit();
}

$stmt = $pdo->prepare("SELECT 
    ho.id AS ho_id, ho.ten_ho, ho.dien_tich, ho.status, ho.cho_phep_danh_game, ho.gia_game,
    ho.created_at, cum.ten_cum_ho
FROM ho_cau ho
JOIN cum_ho cum ON ho.cum_ho_id = cum.id
WHERE cum.chu_ho_id = :uid

ORDER BY cum.ten_cum_ho, ho.ten_ho");

$stmt->execute(['uid' => $_SESSION['user']['id']]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Nhóm theo cụm
$data = [];
foreach ($rows as $r) {
    $data[$r['ten_cum_ho']][] = $r;
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>📋 Danh sách hồ câu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h3 class="mb-4 text-success">📋 Danh sách hồ câu</h3>
    <a href="ho_cau_add.php" class="btn btn-primary mb-3">➕ Thêm hồ câu mới</a>
	<a href="../cum_ho/cum_ho_list.php" class="btn btn-primary mb-3"> ➜ Xem cụm hồ</a>
	<div class="alert alert-info mt-3" role="alert">
	  <strong>Lưu ý:</strong> bạn cần tạo ít nhất 1 <em>“cụm hồ”</em> trước khi tạo hồ câu mới —
	  <a class="alert-link" href="/cauca/chuho/cum_ho/cum_ho_list.php">tạo cụm hồ ngay</a>.
	</div>
    <table class="table table-bordered table-striped">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Tên hồ</th>
            <th>Diện tích</th>
            <th>Game</th>
            <th>Trạng thái</th>
            <th>Ngày tạo</th>
            <th>Sửa</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $stt = 1;
        foreach ($data as $ten_cum => $ho_list):
            echo "<tr class='table-info fw-bold'><td colspan='8'>Cụm hồ: " . htmlspecialchars($ten_cum) . "</td></tr>";
            foreach ($ho_list as $ho):
                ?>
                <tr>
                    <td><?= $stt++ ?></td>
                    <td><?= htmlspecialchars($ho['ten_ho']) ?></td>
                    <td><?= $ho['dien_tich'] ?> m²</td>
                    <td><?= $ho['cho_phep_danh_game'] ? '✅' : '❌' ?> (<?= number_format($ho['gia_game']) ?>đ)</td>
							<?php
							$labels = [
							  'admin_tam_khoa' => 'Admin tạm khoá',
							  'chuho_tam_khoa' => 'Chủ hồ tạm khoá',
							  'dong_vinh_vien' => 'Đóng vĩnh viễn',
							  'dang_hoat_dong' => 'Đang hoạt động',
							  'tam_dung'       => 'Tạm dừng',
							];

							$raw   = (string)($ho['status'] ?? '');
							$label = $labels[$raw] ?? ucwords(str_replace('_', ' ', $raw));
							?>
					<td><?= htmlspecialchars($label, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                    <td><?= $ho['created_at'] ?></td>
                    <td><a href="ho_cau_edit.php?id=<?= $ho['ho_id'] ?>" class="btn btn-warning btn-sm">✏️ Sửa</a></td>
                </tr>
            <?php endforeach;
        endforeach;
        ?>
        </tbody>
    </table>
</div>
</body>
</html>
<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>