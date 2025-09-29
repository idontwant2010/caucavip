<?php
require_once '../../../connect.php';
require_once '../../../check_login.php';

header('Content-Type: application/json; charset=utf-8');

$input = json_decode(file_get_contents('php://input'), true);
$gameId = (int)($input['game_id'] ?? 0);
$userId = (int)($input['user_id'] ?? 0);
$tongKg = (float)($input['tong_kg'] ?? 0);

if ($gameId <= 0 || $userId <= 0 || $tongKg < 0) {
  echo json_encode(['ok' => false, 'error' => 'Tham số không hợp lệ.']);
  exit;
}

try {
  // kiểm tra tồn tại game + user trong game_user
  $st = $pdo->prepare("SELECT 1 FROM game_user WHERE game_id = :gid AND user_id = :uid LIMIT 1");
  $st->execute([':gid' => $gameId, ':uid' => $userId]);
  if (!$st->fetchColumn()) {
    echo json_encode(['ok' => false, 'error' => 'User không thuộc game này.']);
    exit;
  }

  $up = $pdo->prepare("UPDATE game_user SET tong_kg = :kg WHERE game_id = :gid AND user_id = :uid");
  $up->execute([':kg' => $tongKg, ':gid' => $gameId, ':uid' => $userId]);

  echo json_encode(['ok' => true]);
} catch (Throwable $e) {
  echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
