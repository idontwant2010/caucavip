<?php
session_start();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Truy cập bị từ chối</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container text-center py-5">
    <h1 class="display-4 text-danger">🚫 Truy cập bị từ chối</h1>
    <p class="lead">Bạn không có quyền truy cập vào trang này.</p>

    <?php if (isset($_SESSION['vai_tro'])): ?>
        <p>Vai trò hiện tại: <strong><?= htmlspecialchars($_SESSION['vai_tro']) ?></strong></p>
        <a href="index.php" class="btn btn-primary mt-3">Quay về trang chính</a>
    <?php else: ?>
        <a href="auth/login.php" class="btn btn-outline-secondary mt-3">Đăng nhập</a>
    <?php endif; ?>
</div>

</body>
</html>
