<?php
require_once '../../../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $so_kg = $_POST['so_kg'] ?? null;
    $diem = $_POST['diem_cong_vi_pham'] ?? 0;

    if ($id !== null && is_numeric($so_kg)) {
        $stmt = $pdo->prepare("UPDATE giai_schedule SET so_kg = ?, diem_cong_vi_pham = ? WHERE id = ?");
        $stmt->execute([$so_kg, $diem, $id]);
        echo "✅ Cập nhật thành công!";
    } else {
        echo "❌ Dữ liệu không hợp lệ.";
    }
}
