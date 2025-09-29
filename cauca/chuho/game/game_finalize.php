<?php
require_once '../../../connect.php';
require_once '../../../check_login.php';
header('Content-Type: application/json; charset=utf-8');

$input = json_decode(file_get_contents('php://input'), true);
$gameId = (int)($input['game_id'] ?? 0);
if ($gameId <= 0) { echo json_encode(['ok'=>false,'error'=>'Thiếu game_id']); exit; }

try {
  $up = $pdo->prepare("UPDATE game_list SET status = 'hoan_tat_game' WHERE id = :gid");
  $up->execute([':gid' => $gameId]);
  echo json_encode(['ok'=>true]);
} catch (Throwable $e) {
  echo json_encode(['ok'=>false, 'error'=>$e->getMessage()]);
}
