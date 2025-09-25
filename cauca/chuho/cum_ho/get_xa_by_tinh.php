<?php
require_once '../../../connect.php';

$tinh_id = $_GET['tinh_id'] ?? 0;

$stmt = $pdo->prepare("SELECT id, ten_xa_phuong FROM dm_xa_phuong WHERE tinh_id = :tinh_id ORDER BY ten_xa_phuong ASC");
$stmt->execute(['tinh_id' => $tinh_id]);
$xa_list = $stmt->fetchAll();

header('Content-Type: application/json');
echo json_encode($xa_list);
