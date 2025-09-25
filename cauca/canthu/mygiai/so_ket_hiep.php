<?php
require_once '../../../connect.php';
require_once '../../../check_login.php';

// Cho phép gọi nội bộ bằng biến $is_internal
$is_internal = $is_internal ?? false;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !$is_internal) {
    echo "<script>alert('Truy cập không hợp lệ'); history.back();</script>";
    exit;
}

if (!$is_internal) {
    $giai_id = $_POST['giai_id'] ?? null;
} else {
    // khi gọi từ file khác, giai_id phải truyền sẵn từ bên ngoài
    $giai_id = $giai_id ?? null;
}

if (!$giai_id) {
    if (!$is_internal) {
        echo "<script>alert('Thiếu thông tin giải'); history.back();</script>";
        exit;
    } else {
        return; // gọi nội bộ mà thiếu thì bỏ qua
    }
}

// Lấy danh sách các (hiệp, bảng) đã chia vị trí
$stmt = $pdo->prepare("
    SELECT DISTINCT so_hiep, so_bang 
    FROM giai_schedule 
    WHERE giai_id = ? AND vi_tri_ngoi > 0
    ORDER BY so_hiep, so_bang
");
$stmt->execute([$giai_id]);
$hiep_bang_list = $stmt->fetchAll();

$count_updated = 0;

foreach ($hiep_bang_list as $hb) {
    $so_hiep = $hb['so_hiep'];
    $so_bang = $hb['so_bang'];

    $stmt2 = $pdo->prepare("
        SELECT id, so_kg, diem_cong_vi_pham 
        FROM giai_schedule 
        WHERE giai_id = ? AND so_hiep = ? AND so_bang = ?
        ORDER BY so_kg DESC
    ");
    $stmt2->execute([$giai_id, $so_hiep, $so_bang]);
    $ds = $stmt2->fetchAll();

    $rank = 1;
    foreach ($ds as $u) {
        $so_diem = $rank;
        $diem_pham = floatval($u['diem_cong_vi_pham']);
        $tong_diem = floatval($so_diem) + $diem_pham;

        $stmt_update = $pdo->prepare("UPDATE giai_schedule SET so_diem = ?, tong_diem = ? WHERE id = ?");
        $stmt_update->execute([$so_diem, $tong_diem, $u['id']]);
        $count_updated++;
        $rank++;
    }
}

if (!$is_internal) {
    echo "<script>alert('✅ Sơ kết hiệp: đã cập nhật {$count_updated} dòng.'); history.back();</script>";
}
