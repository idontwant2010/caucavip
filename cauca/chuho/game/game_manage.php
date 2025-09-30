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

  // Khóa & lấy thông tin game + hình thức + sức chứa hồ
  $st = $pdo->prepare("
    SELECT g.id, g.status, g.hinh_thuc_id, g.so_luong_can_thu,
           h.so_cho_ngoi,
           ht.ten_hinh_thuc, ht.so_nguoi_min, ht.so_nguoi_max
    FROM game_list g
    JOIN ho_cau h ON h.id = g.ho_cau_id
    JOIN giai_game_hinh_thuc ht ON ht.id = g.hinh_thuc_id
    WHERE g.id = :gid
    FOR UPDATE
  ");
  $st->execute([':gid' => $gameId]);
  $g = $st->fetch(PDO::FETCH_ASSOC);
  if (!$g) {
    $pdo->rollBack();
    exit("Game không tồn tại.");
  }

  // Lấy danh sách user 'đang tham gia' và khóa các dòng game_user liên quan
  $valid = ['cho_xac_nhan', 'xac_nhan', 'da_thanh_toan'];
  $ph = implode(',', array_fill(0, count($valid), '?'));
  $sqlUsers = "
    SELECT gu.user_id
    FROM game_user gu
    WHERE gu.game_id = ? AND gu.trang_thai IN ($ph)
    ORDER BY gu.created_at ASC
    FOR UPDATE
  ";
  $stU = $pdo->prepare($sqlUsers);
  $stU->execute(array_merge([$gameId], $valid));
  $userIds = $stU->fetchAll(PDO::FETCH_COLUMN);
  $N = count($userIds);

  // Kiểm tra số người tối thiểu cơ bản
  if ($N < 2) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    $err = "Cần tối thiểu 2 người để chốt (hiện tại có $N cần thủ).";
    header("Location: game_user_add.php?game_id={$gameId}&err=" . urlencode($err));
    exit;
  }

  // Lấy min/max theo hình thức
  $htId   = (int)$g['hinh_thuc_id'];
  $htMin  = isset($g['so_nguoi_min']) ? (int)$g['so_nguoi_min'] : 0;
  $htMax  = isset($g['so_nguoi_max']) ? (int)$g['so_nguoi_max'] : 0;

  // Quy tắc SOLO (IDs 13/14/15): min=max=2/3/4
  $soloMap = [13 => 2, 14 => 3, 15 => 4];
  $isSolo = array_key_exists($htId, $soloMap);
  if ($isSolo) {
    $htMin = $htMax = $soloMap[$htId];
  }

  // Kiểm tra trong khoảng min-max (nếu có cấu hình > 0)
  if (($htMin > 0 && $N < $htMin) || ($htMax > 0 && $N > $htMax)) {
    $pdo->rollBack();
    $err = "Số cần thủ hiện tại ($N) không hợp lệ cho hình thức này. Yêu cầu: " .
      ($isSolo ? "$htMin người (solo)" : "{$htMin}–{$htMax} người") . ".";
    header("Location: game_user_add.php?game_id=" . $gameId . "&err=" . urlencode($err));
    exit;
  }

  // Kiểm tra <= số chỗ ngồi của hồ
  $soCho = (int)($g['so_cho_ngoi'] ?? 0);
  if ($soCho > 0 && $N > $soCho) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    $err = "Số cần thủ ($N) vượt quá số chỗ ngồi của hồ ($soCho).";
    header("Location: game_user_add.php?game_id={$gameId}&err=" . urlencode($err));
    exit;
  }

  // (Tuỳ chọn) Nếu muốn ràng buộc đúng bằng số dự kiến trong game_list:
  // Chỉ gợi ý: bật khi bạn muốn chốt cứng theo số đã khai báo lúc tạo.
  $requireExactPlanned = false;
  if ($requireExactPlanned) {
    $planned = (int)($g['so_luong_can_thu'] ?? 0);
    if ($planned > 0 && $N !== $planned) {
      $pdo->rollBack();
      exit("Số cần thủ hiện tại ($N) khác với số dự kiến ($planned). Hãy điều chỉnh trước khi chốt.");
    }
  }

  // Random 1..N và set cờ 'biên' (seat 1 & N)
  shuffle($userIds);
  $up = $pdo->prepare("
    UPDATE game_user
    SET vi_tri_ngoi = :seat,
        is_bien     = :is_bien
    WHERE game_id = :gid AND user_id = :uid
  ");

  $seat = 1;
  foreach ($userIds as $uid) {
    $isBien = ($seat === 1 || $seat === $N) ? 1 : 0;
    $up->execute([
      ':seat'    => $seat,
      ':is_bien' => $isBien,
      ':gid'     => $gameId,
      ':uid'     => (int)$uid
    ]);
    $seat++;
  }

  // Chỉ cập nhật trạng thái nếu đang ở giai đoạn mở/chuẩn bị
  $stUp = $pdo->prepare("
    UPDATE game_list
    SET status = 'dang_thi_dau_game'
    WHERE id = :gid AND status IN ('dang_mo_dang_ky','cho_xac_nhan')
  ");
  $stUp->execute([':gid' => $gameId]);

  $pdo->commit();
  header("Location: game_detail.php?game_id=" . $gameId . "&seeded=1");
  exit;
} catch (Throwable $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  http_response_code(500);
  echo "Lỗi: " . htmlspecialchars($e->getMessage());
}
