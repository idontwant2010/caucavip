<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'admin') {
    header('Location: /caucavip/dashboard/index.php');
    exit;
}

$id = $_GET['id'] ?? '';
if (!$id || !is_numeric($id)) {
    header('Location: ho_cau_list.php');
    exit;
}

// Tạo lịch mặc định nếu yêu cầu POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'khoi_tao_lich_mac_dinh') {
    $pdo->prepare("DELETE FROM lich_hoat_dong_ho_cau WHERE ho_cau_id = :id")->execute([':id' => $id]);

    $stmt = $pdo->prepare("
        INSERT INTO lich_hoat_dong_ho_cau (ho_cau_id, thu, gio_mo, gio_dong, trang_thai)
        VALUES (:ho_cau_id, :thu, :mo, :dong, 'mo')
    ");

    $thu_list = ['2', '3', '4', '5', '6', '7', 'CN'];
    foreach ($thu_list as $thu) {
        $stmt->execute([
            ':ho_cau_id' => $id,
            ':thu' => $thu,
            ':mo' => '06:00:00',
            ':dong' => '18:00:00'
        ]);
    }
    header("Location: ho_cau_detail.php?id=$id");
    exit;
}

// Tạo 3 bảng giá mặc định
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'khoi_tao_bang_gia_mac_dinh') {
    $stmt = $pdo->prepare("
        INSERT INTO gia_ca_thit_phut 
        (ho_cau_id, ten_bang_gia, base_duration, base_price, extra_unit_price, gia_ban_ca, gia_thu_lai, status, ghi_chu) 
        VALUES (:ho_id, :ten, :duration, :price, :them, :ban, :thu, 'closed', :note)
    ");

    $default_bang_gia = [
        ['Cơ Bản', 60, 100000, 20000, 60000, 30000, 'Phù hợp phổ thông'],
        ['Trung Cấp', 90, 140000, 18000, 65000, 35000, 'Cân bằng giải trí'],
        ['Đại Sư', 120, 180000, 15000, 70000, 40000, 'Cho cần thủ cao cấp']
    ];

    foreach ($default_bang_gia as $item) {
        $stmt->execute([
            ':ho_id' => $id,
            ':ten' => $item[0],
            ':duration' => $item[1],
            ':price' => $item[2],
            ':them' => $item[3],
            ':ban' => $item[4],
            ':thu' => $item[5],
            ':note' => $item[6]
        ]);
    }

    header("Location: ho_cau_detail.php?id=$id");
    exit;
}

// Lấy thông tin hồ
$stmt = $pdo->prepare("
    SELECT hc.*, ch.ten_cum_ho, ca.ten_ca
    FROM ho_cau hc
    LEFT JOIN cum_ho ch ON hc.cum_ho_id = ch.id
    LEFT JOIN loai_ca ca ON hc.loai_ca_id = ca.id
    WHERE hc.id = :id
");
$stmt->execute([':id' => $id]);
$ho = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$ho) {
    header('Location: ho_cau_list.php');
    exit;
}

// Lấy lịch mẫu 7 ngày
$lich_stmt = $pdo->prepare("SELECT * FROM lich_hoat_dong_ho_cau WHERE ho_cau_id = :id ORDER BY thu ASC");
$lich_stmt->execute([':id' => $id]);
$lich = $lich_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include __DIR__ . '/../../../includes/header.php'; ?>
<div class="container mt-4">
    <h4>📄 Chi tiết Hồ Câu</h4>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Tên hồ:</strong> <?= htmlspecialchars($ho['ten_ho']) ?></p>
            <p><strong>Cụm hồ:</strong> <?= htmlspecialchars($ho['ten_cum_ho']) ?></p>
            <p><strong>Loại cá:</strong> <?= htmlspecialchars($ho['ten_ca'] ?? '—') ?></p>
            <p><strong>Lượng cá:</strong> <?= $ho['luong_ca'] ?> kg</p>
            <p><strong>Diện tích:</strong> <?= $ho['dien_tich'] ?> m²</p>
            <p><strong>Số chỗ ngồi:</strong> <?= $ho['so_cho_ngoi'] ?></p>
            <p><strong>Chiều dài cần tối đa:</strong> <?= $ho['max_chieu_dai_can'] ?> mm</p>
            <p><strong>Số trục theo:</strong> <?= $ho['max_truc_theo'] ?></p>
            <p><strong>Cấm mồi:</strong> <?= htmlspecialchars($ho['cam_moi']) ?></p>
            <p><strong>Mô tả:</strong> <?= htmlspecialchars($ho['mo_ta']) ?></p>
            <p><strong>Game:</strong>
                <?= $ho['cho_phep_danh_game'] ? '✔ Cho phép - ' . number_format($ho['gia_game']) . 'đ' : '✘ Không' ?>
            </p>
            <p><strong>Trạng thái:</strong>
                <?php
                    switch ($ho['status']) {
                        case 'dang_hoat_dong': echo '<span class="badge bg-success">Đang hoạt động</span>'; break;
                        case 'chua_mo': echo '<span class="badge bg-secondary">Chưa mở</span>'; break;
                        case 'chuho_tam_khoa': echo '<span class="badge bg-warning text-dark">Chủ hồ khóa</span>'; break;
                        default: echo '<span class="badge bg-danger">Admin khóa</span>';
                    }
                ?>
            </p>
            <p><strong>Ngày tạo:</strong> <?= date('d/m/Y H:i', strtotime($ho['created_at'])) ?></p>
        </div>
    </div>

    <h5>📆 Lịch hoạt động theo tuần (mẫu cố định)</h5>
    <?php if (count($lich) < 7): ?>
        <form method="post">
            <input type="hidden" name="action" value="khoi_tao_lich_mac_dinh">
            <button type="submit" class="btn btn-outline-primary">🔧 Kích hoạt lịch mặc định</button>
            <p class="text-muted mt-2">Tạo lịch mở cửa từ 06:00 – 18:00 cho tất cả các ngày trong tuần.</p>
        </form>
    <?php else: ?>
        <table class="table table-bordered table-hover mt-2">
            <thead class="table-light">
                <tr>
                    <th>Thứ</th>
                    <th>Giờ mở</th>
                    <th>Giờ đóng</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $thu_map = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'];
                foreach ($lich as $i => $row): ?>
                    <tr>
                        <td><?= $thu_map[$i] ?? '—' ?></td>
                        <td><?= substr($row['gio_mo'], 0, 5) ?></td>
                        <td><?= substr($row['gio_dong'], 0, 5) ?></td>
                        <td>
                            <?= $row['trang_thai'] ? '<span class="badge bg-success">Mở</span>' : '<span class="badge bg-secondary">Đóng</span>' ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php endif; ?>


<h5 class="mt-5">💰 Bảng giá mặc định theo hồ</h5>
<?php
$stmt = $pdo->prepare("SELECT * FROM gia_ca_thit_phut WHERE ho_cau_id = :id ORDER BY ten_bang_gia");
$stmt->execute([':id' => $id]);
$gia_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (!$gia_list): ?>
    <p class="text-danger">⚠️ Hồ này chưa có bảng giá nào được khởi tạo.</p>
	<?php if (!$gia_list): ?>
    <form method="post">
        <input type="hidden" name="action" value="khoi_tao_bang_gia_mac_dinh">
        <button type="submit" class="btn btn-outline-primary">🔧 Khởi tạo bảng giá mặc định</button>
        <p class="text-muted mt-2">Tạo 3 bảng giá: Cơ Bản, Trung Cấp, Đại Sư cho hồ này.</p>
    </form>
<?php else: ?>
    <!-- bảng giá như cũ -->
<?php endif; ?>

	
<?php else: ?>
    <table class="table table-bordered table-striped table-hover">
        <thead class="table-light">
            <tr>
                <th>Tên bảng</th>
                <th>Thời lượng (phút)</th>
                <th>Giá cơ bản</th>
                <th>Giá thêm giờ</th>
                <th>Giá bán cá</th>
                <th>Giá thu lại</th>
                <th>Trạng thái</th>
                <th>Ghi chú</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($gia_list as $g): ?>
                <tr>
                    <td><?= htmlspecialchars($g['ten_bang_gia']) ?></td>
                    <td><?= $g['base_duration'] ?> phút</td>
                    <td><?= number_format($g['base_price']) ?> đ</td>
                    <td><?= number_format($g['extra_unit_price']) ?> đ/giờ</td>
                    <td><?= number_format($g['gia_ban_ca']) ?> đ/kg</td>
                    <td><?= number_format($g['gia_thu_lai']) ?> đ/kg</td>
                    <td>
                        <?= $g['status'] === 'open'
                            ? '<span class="badge bg-success">Đang áp dụng</span>'
                            : '<span class="badge bg-secondary">Tạm dừng</span>' ?>
                    </td>
                    <td><?= htmlspecialchars($g['ghi_chu']) ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
<?php endif; ?>



    <a href="ho_cau_list.php" class="btn btn-secondary mt-4">← Quay lại danh sách</a>
</div>
<?php include __DIR__ . '/../../../includes/footer.php'; ?>
