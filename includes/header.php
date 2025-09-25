<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../connect.php';

$user = $_SESSION['user'] ?? null;
$isLoggedIn = $user !== null;
$vai_tro = $user['vai_tro'] ?? null;
$user_id = $user['id'] ?? null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Câu cá VIP</title>
<!-- Bootstrap 5.3 CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
<a class="navbar-brand" href="/index.php">🎣 Câu Đài Việt Nam</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!-- Menu trái nếu cần -->
            </ul>

            <?php if ($isLoggedIn && in_array($vai_tro, ['canthu', 'chuho'])): ?>
                <?php
                    $stmt = $pdo->prepare("SELECT balance FROM users WHERE id = :id");
                    $stmt->execute(['id' => $user_id]);
                    $u = $stmt->fetch(PDO::FETCH_ASSOC);
                    $so_du = isset($u['balance']) ? (float)$u['balance'] : 0;

                    $balance_link = ($vai_tro === 'canthu')
                        ? '/cauca/canthu/balance.php'
                        : '/cauca/chuho/balance.php';
                ?>
                <div class="me-3 text-white">
                    <a href="<?= $balance_link ?>" class="text-decoration-none text-warning fw-bold">
                        💰 Số dư: <?= number_format($so_du) ?> đ
                    </a>
                </div>
            <?php endif; ?>

<ul class="navbar-nav">
    <?php if ($isLoggedIn): ?>
        <li class="nav-item">
            <?php
                // Đường dẫn profile theo vai trò
                $profile_link = '/';
                if ($vai_tro === 'canthu') {
                    $profile_link = '/cauca/canthu/profile.php';
                } elseif ($vai_tro === 'chuho') {
                    $profile_link = '/cauca/chuho/profile.php';
                }
            ?>
            <a class="nav-link text-white" href="<?= $profile_link ?>">👤 Profile</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-secondary" href="/auth/logout.php">🚪 Đăng xuất</a>
        </li>
    <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="/auth/login.php">Đăng nhập</a></li>
    <?php endif; ?>
</ul>


        </div>
    </div>
</nav>
