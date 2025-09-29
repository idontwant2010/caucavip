<?php
require_once '../../../connect.php';
require_once '../../../check_login.php';

$gameId = (int)($_GET['game_id'] ?? 0);
if ($gameId <= 0) {
  http_response_code(400);
  exit('Thiếu game_id.');
}

try {
  $pdo->beginTransaction();

  // Khóa game
  $st = $pdo->prepare("SELECT id FROM game_list WHERE id = :gid FOR UPDATE");
  $st->execute([':gid' => $gameId]);
  if (!$st->fetch()) {
    $pdo->rollBack();
    exit("Game không tồn tại.");
  }

  // Lấy người 'đang tham gia'
  $valid = ['cho_xac_nhan', 'xac_nhan', 'da_thanh_toan'];
  $in = implode(',', array_fill(0, count($valid), '?'));
  $sql = "SELECT gu.user_id
          FROM game_user gu
          WHERE gu.game_id = ? AND gu.trang_thai IN ($in)
          ORDER BY gu.created_at ASC";
  $st = $pdo->prepare($sql);
  $st->execute(array_merge([$gameId], $valid));
  $rows = $st->fetchAll(PDO::FETCH_COLUMN);

  $N = count($rows);
  if ($N < 2) {
    $pdo->rollBack();
    exit("Cần tối thiểu 2 người (hiện $N).");
  }

  // Random 1..N
  shuffle($rows);
  $up = $pdo->prepare("UPDATE game_user
                       SET vi_tri_ngoi = :seat,
                           is_bien     = :is_bien
                       WHERE game_id = :gid AND user_id = :uid");

  $seat = 1;
  foreach ($rows as $uid) {
    $isBien = ($seat === 1 || $seat === $N) ? 1 : 0; // chỉ hiển thị
    $up->execute([
      ':seat'    => $seat,
      ':is_bien' => $isBien,
      ':gid'     => $gameId,
      ':uid'     => (int)$uid
    ]);
    $seat++;
  }

  $pdo->commit();
  header("Location: game_detail.php?game_id=" . $gameId . "&seeded=1");
  exit;
} catch (Throwable $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  http_response_code(500);
  echo "Lỗi: " . htmlspecialchars($e->getMessage());
}
