<?php
session_start();

// Nếu đã đăng nhập, điều hướng theo vai trò
if (isset($_SESSION['user'])) {
    switch ($_SESSION['user']['vai_tro']) {
        case 'admin':
            header('Location: cauca/admin/dashboard_admin.php');
            break;
        case 'moderator':
            header('Location: cauca/moderator/dashboard_moderator.php');
            break;
        case 'canthu':
            header('Location: cauca/canthu/dashboard_canthu.php');
            break;
        case 'chuho':
            header('Location: cauca/chuho/dashboard_chuho.php');
            break;
        default:
            header('Location: auth/login.php');
            break;
    }
    exit();
}

// Nếu chưa đăng nhập
header('Location: auth/login.php');
exit();
