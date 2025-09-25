<?php
require_once '../../../connect.php';
require_once '../../../check_login.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['giai_id'])) {
    echo "<script>alert('Thiếu thông tin giải'); history.back();</script>";
    exit;
}

$giai_id = intval($_POST['giai_id']);

// Kiểm tra trạng thái có hợp lệ không
$stmt = $pdo->prepare("SELECT status FROM giai_list WHERE id = ?");
$stmt->execute([$giai_id]);
$current_status = $stmt->fetchColumn();

if ($current_status !== 'so_ket_giai') {
    echo "<script>alert('⚠️ Giải chưa được sơ kết, vui lòng sơ kết và kiểm tra kết quả! hoặc giải đã hoàn thành trước đó'); history.back();</script>";
    exit;
}

// ✅ Cập nhật trạng thái
$stmt_update = $pdo->prepare("UPDATE giai_list SET status = 'hoan_tat_giai' WHERE id = ?");
$stmt_update->execute([$giai_id]);

echo "<script>alert('✅ Giải đã hoàn tất và được lưu lại.'); window.location.href = 'my_giai_detail_step_3.php?id=$giai_id';</script>";
