<?php
require_once '../../../connect.php';
require_once '../../../check_login.php';
header('Content-Type: application/json; charset=utf-8');

$input = json_decode(file_get_contents('php://input'), true);
$gameId = (int)($input['game_id'] ?? 0);
if ($gameId <= 0) {
  echo json_encode(['ok' => false, 'error' => 'Thiếu game_id']);
  exit;
}

try {
  // Lấy danh sách người đang tham gia + kg
  $valid = ['cho_xac_nhan', 'xac_nhan', 'da_thanh_toan'];
  $in = implode(',', array_fill(0, count($valid), '?'));
  $sql = "SELECT user_id, COALESCE(tong_kg,0) AS tong_kg
          FROM game_user
          WHERE game_id = ? AND trang_thai IN ($in)
          ORDER BY tong_kg DESC, user_id ASC";
  $st = $pdo->prepare($sql);
  $st->execute(array_merge([$gameId], $valid));
  $rows = $st->fetchAll(PDO::FETCH_ASSOC);

  $rank = 0;
  $prevKg = null;
  $updates = [];

  foreach ($rows as $r) {
    $kg = (float)$r['tong_kg'];
    if ($prevKg === null || $kg < $prevKg) {
      $rank += 1;       // dense rank: 10,10,8 -> hạng 1,1,2
      $prevKg = $kg;
    }
    $updates[] = ['uid' => (int)$r['user_id'], 'rank' => $rank];
  }

  $up = $pdo->prepare("UPDATE game_user SET xep_hang = :rk WHERE game_id = :gid AND user_id = :uid");
  foreach ($updates as $u) {
    $up->execute([':rk' => $u['rank'], ':gid' => $gameId, ':uid' => $u['uid']]);
  }

  echo json_encode(['ok' => true, 'count' => count($updates)]);
} catch (Throwable $e) {
  echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
