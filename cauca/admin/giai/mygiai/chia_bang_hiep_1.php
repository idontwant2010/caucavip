<?php
require_once '../../../connect.php';
require_once '../../../check_login.php';

// Cho phép debug bằng GET nếu có giai_id
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_GET['giai_id'])) {
    echo "<script>alert('Truy cập không hợp lệ'); window.location.href='../../canthu/';</script>";
    exit;
}
if (!isset($_POST['giai_id']) && isset($_GET['giai_id'])) {
    $_POST['giai_id'] = $_GET['giai_id'];
}

$giai_id = isset($_POST['giai_id']) ? (int)$_POST['giai_id'] : 0;
if ($giai_id <= 0) {
    echo "<script>alert('Thiếu thông tin giải'); window.location.href='my_giai_detail_step_2.php?id={$giai_id}';</script>";
    exit;
}

// Lấy thông tin giải
$stmt = $pdo->prepare("SELECT * FROM giai_list WHERE id = ?");
$stmt->execute([$giai_id]);
$giai = $stmt->fetch();

if (!$giai || $giai['creator_id'] != $_SESSION['user']['id']) {
    echo "<script>alert('Không có quyền hoặc giải không tồn tại'); window.location.href='my_giai_detail_step_2.php?id={$giai_id}';</script>";
    exit;
}

if ($giai['status'] !== 'chot_xong_danh_sach') {
    echo "<script>alert('Giải chưa ở trạng thái cho phép chia bảng'); window.location.href='my_giai_detail_step_2.php?id={$giai_id}';</script>";
    exit;
}

// Lấy danh sách người chơi ở hiệp 1 chưa chia bảng
$stmt = $pdo->prepare("SELECT * FROM giai_schedule WHERE giai_id = ? AND so_hiep = 1 AND so_bang = 0");
$stmt->execute([$giai_id]);
$list = $stmt->fetchAll();

if (count($list) === 0) {
    echo "<script>alert('Hiệp 1 đã được chia bảng trước đó, chưa có dữ liệu hoặc không ở trạng thái chot_xong_danh_sach'); window.location.href='my_giai_detail_step_2.php?id={$giai_id}';</script>";
    exit;
}

// Lấy số bảng từ cấu hình giải
$so_bang = (int)$giai['so_bang'];
$so_hiep = (int)$giai['so_hiep'];
if ($so_bang <= 0) $so_bang = 4;

// Random danh sách user
shuffle($list);
$tong = count($list);
$chia = array_fill(0, $so_bang, 0);

// Tính số người mỗi bảng theo cách phân phối đều dư vào đầu
for ($i = 0; $i < $tong; $i++) {
    $chia[$i % $so_bang]++;
}
rsort($chia); // bảng A có nhiều hơn nếu dư

$index = 0;
foreach ($chia as $so_nguoi_bang) {
    $so_bang_hien_tai = chr(65 + $index); // A, B, C, D...
    for ($i = 0; $i < $so_nguoi_bang; $i++) {
        $row = array_shift($list);
        $vi_tri = $i + 1;
        $is_bien = ($vi_tri == 1 || $vi_tri == $so_nguoi_bang) ? 1 : 0;

        $stmt = $pdo->prepare("UPDATE giai_schedule SET so_bang = ?, vi_tri_ngoi = ?, is_bien = ? WHERE id = ?");
        $stmt->execute([$so_bang_hien_tai, $vi_tri, $is_bien, $row['id']]);
    }
    $index++;
}


// ✅ Cập nhật trạng thái giải sang 'dang_dau_hiep_1'
$stmt = $pdo->prepare("UPDATE giai_list SET status = 'dang_dau_hiep_1' WHERE id = ?");
$stmt->execute([$giai_id]);

echo "<script>alert('✅ Đã chia bảng và vị trí hiệp 1 thành công!'); window.location.href='my_giai_detail_step_2.php?id={$giai_id}';</script>";



