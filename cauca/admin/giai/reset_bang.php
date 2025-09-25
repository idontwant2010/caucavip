<?php
require_once '../../../connect.php';
require_once '../../../check_login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $giai_id = isset($_POST['giai_id']) ? (int)$_POST['giai_id'] : 0;

    if ($giai_id > 0) {
        $stmt = $pdo->prepare("
            UPDATE giai_schedule 
            SET so_bang = '0', vi_tri_ngoi = 0, is_bien = 0 
            WHERE giai_id = ? AND so_bang != '0'
        ");
        $stmt->execute([$giai_id]);

        echo "<script>alert('✅ Đã reset thành công bảng và vị trí cho giải ID {$giai_id}'); window.location.href='reset_bang.php';</script>";
        exit;
    } else {
        echo "<script>alert('Giai ID không hợp lệ');</script>";
    }
}
?>

<!-- Form chọn giai_id để reset -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Bảng & Vị trí</title>
</head>
<body>
    <h2>Reset bảng & vị trí cho 1 giải</h2>
    <form method="post">
        <label for="giai_id">Nhập ID của giải cần reset:</label>
        <input type="number" name="giai_id" required>
        <button type="submit">Reset</button>
    </form>
</body>
</html>
