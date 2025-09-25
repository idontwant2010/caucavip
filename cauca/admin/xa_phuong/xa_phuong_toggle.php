<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'admin') {
    header('Location: /');
    exit;
}

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare("UPDATE dm_xa_phuong SET is_active = 1 - is_active WHERE id = ?");
$stmt->execute([$id]);

header("Location: xa_phuong_list.php");
exit;
