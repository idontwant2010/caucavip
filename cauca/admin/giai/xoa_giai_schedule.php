<?php
require_once '../../../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $giai_id = isset($_POST['giai_id']) ? (int)$_POST['giai_id'] : 0;

    if ($giai_id <= 0) {
        echo "❌ Giai ID không hợp lệ.";
        exit;
    }

    // Kiểm tra có dữ liệu để xoá không
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM giai_schedule WHERE giai_id = ?");
    $stmt->execute([$giai_id]);
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        echo "⚠️ Không có dòng nào thuộc giải ID $giai_id trong bảng giai_schedule.";
        exit;
    }

    // Tiến hành xoá
    $stmt = $pdo->prepare("DELETE FROM giai_schedule WHERE giai_id = ?");
    $success = $stmt->execute([$giai_id]);

    if ($success) {
        echo "✅ Đã xoá $count dòng trong bảng giai_schedule có giai_id = $giai_id.";
    } else {
        echo "❌ Lỗi khi xoá dữ liệu.";
    }

    exit;
}
?>

<!-- Form nhập giai_id -->
<form method="POST">
  <label>Nhập giai_id cần xoá:</label>
  <input type="number" name="giai_id" required>
  <button type="submit">Xoá</button>
</form>
