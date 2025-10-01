<?php
// File: /cauca/chuho/game/show_seat.php
require_once __DIR__ . '/../../../check_login.php';
require_once __DIR__ . '/../../../connect.php';

// 1) Lấy game_id
$game_id = (int)($_GET['game_id'] ?? 0);
if ($game_id <= 0) {
  exit('Thiếu tham số game_id');
}

// 2) Lấy game & kiểm tra trạng thái
$st = $pdo->prepare("SELECT id, status FROM game_list WHERE id = ? LIMIT 1");
$st->execute([$game_id]);
$game = $st->fetch(PDO::FETCH_ASSOC);

if (!$game) {
  exit('Không tìm thấy game');
}
if ($game['status'] !== 'da_chot_danh_sach') {
  exit('Trạng thái hiện tại không hợp lệ. Yêu cầu: da_chot_danh_sach');
}

// 3) Cập nhật trạng thái -> dang_thi_dau_game
try {
  $pdo->beginTransaction();

  $up = $pdo->prepare("
    UPDATE game_list
    SET status = 'dang_thi_dau_game'
    WHERE id = ? AND status = 'da_chot_danh_sach'
    LIMIT 1
  ");
  $up->execute([$game_id]);

  if ($up->rowCount() !== 1) {
    $pdo->rollBack();
    exit('Không thể cập nhật trạng thái (có thể game đã đổi trạng thái).');
  }

  $pdo->commit();

  // 4) Quay về trang manage
  header('Location: /cauca/chuho/game/game_detail.php?game_id=' . $game_id . '&show_seat=1');
  exit;
} catch (Throwable $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  exit('Lỗi hệ thống: ' . $e->getMessage());
}
