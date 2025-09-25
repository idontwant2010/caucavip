<?php
require_once '../../../connect.php';
require_once '../../../check_login.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['giai_id'])) {
    echo "<script>alert('Thiếu thông tin giải'); window.history.back();</script>";
    exit;
}

$giai_id = intval($_POST['giai_id']);

// Gọi sơ kết hiệp trước
$is_internal = true;
include 'so_ket_hiep.php'; // cập nhật giai_schedule trước

// Bước 1: lấy danh sách user của giải
$stmt = $pdo->prepare("SELECT user_id FROM giai_user WHERE giai_id = ?");
$stmt->execute([$giai_id]);
$user_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

if (empty($user_ids)) {
    echo "<script>alert('Chưa có người tham gia giải.'); window.history.back();</script>";
    exit;
}

// Bước 2: tổng hợp điểm và kg từ giai_schedule
$user_data = [];

foreach ($user_ids as $uid) {
    $stmt2 = $pdo->prepare("
        SELECT SUM(tong_diem) AS total_diem, SUM(so_kg) AS total_kg
        FROM giai_schedule
        WHERE giai_id = ? AND user_id = ?
    ");
    $stmt2->execute([$giai_id, $uid]);
    $r = $stmt2->fetch();

    $user_data[] = [
        'user_id' => $uid,
        'tong_diem' => round(floatval($r['total_diem']), 2),
        'tong_kg' => round(floatval($r['total_kg']), 2),
    ];
}

// Bước 3: xếp hạng theo điểm tăng, kg giảm
usort($user_data, function ($a, $b) {
    if ($a['tong_diem'] == $b['tong_diem']) {
        return $b['tong_kg'] <=> $a['tong_kg']; // kg cao hơn xếp trước
    }
    return $a['tong_diem'] <=> $b['tong_diem']; // điểm thấp hơn xếp trước
});

// Bước 4: cập nhật giai_user
$rank = 1;
foreach ($user_data as $u) {
    $stmt_update = $pdo->prepare("
        UPDATE giai_user 
        SET tong_diem = ?, tong_kg = ?, xep_hang = ?
        WHERE giai_id = ? AND user_id = ?
    ");
    $stmt_update->execute([$u['tong_diem'], $u['tong_kg'], $rank, $giai_id, $u['user_id']]);
    $rank++;
	
// Bước 5: Đổi trạng thái
	// Lấy thông tin giải
	$stmt = $pdo->prepare("SELECT * FROM giai_list WHERE id = ?");
	$stmt->execute([$giai_id]);
	$giai = $stmt->fetch();
	
	//lấy hiện hiện tại
	$hiep_hien_tai = null;
	if (preg_match('/^dang_dau_hiep_(\d+)$/', $giai['status'], $matches)) {
		$hiep_hien_tai = (int) $matches[1]; // ép kiểu cho chắc
	}


	if ($hiep_hien_tai == $giai['so_hiep'] ) {
		$stmt_update_status = $pdo->prepare("UPDATE giai_list SET status = 'so_ket_giai' WHERE id = ?");
		$stmt_update_status->execute([$giai_id]);
	}	
	
}

echo "<script>
    alert('✅ Đã tổng kết giải & xếp hạng " . count($user_data) . " người chơi.');
    window.location.href = 'my_giai_detail_step_3.php?id=$giai_id';
</script>";

