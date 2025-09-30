<?php
require_once '../../../connect.php';
require_once '../../../check_login.php';

$srcId = (int)($_GET['game_id'] ?? 0);
if ($srcId <= 0) {
  http_response_code(400);
  exit('Thiếu game_id.');
}

try {
  $pdo->beginTransaction();

  // 1) Khóa & lấy game gốc (đúng schema game_list mới)
  $st = $pdo->prepare("SELECT * FROM game_list WHERE id = :id FOR UPDATE");
  $st->execute([':id' => $srcId]);
  $src = $st->fetch(PDO::FETCH_ASSOC);
  if (!$src) {
    $pdo->rollBack();
    exit('Game nguồn không tồn tại.');
  }

  // 2) Tạo game mới: status = dang_thi_dau_game
  $ins = $pdo->prepare("
    INSERT INTO game_list
      (ho_cau_id, chuho_id, creator_id, hinh_thuc_id, ten_game,
       so_luong_can_thu, so_bang, so_hiep, thoi_luong_phut_hiep,
       ngay_to_chuc, gio_bat_dau, thoi_gian_dong_dang_ky,
       tien_cuoc, phi_game, phi_ho, luat_choi, status,
       created_at, min_user_exp, min_user_level, quy_tac_xoay_tu_chon)
    VALUES
      (:ho_cau_id, :chuho_id, :creator_id, :hinh_thuc_id, :ten_game,
       :so_luong_can_thu, :so_bang, :so_hiep, :thoi_luong_phut_hiep,
       :ngay_to_chuc, :gio_bat_dau, :thoi_gian_dong_dang_ky,
       :tien_cuoc, :phi_game, :phi_ho, :luat_choi, 'dang_thi_dau_game',
       NOW(), :min_user_exp, :min_user_level, :quy_tac_xoay_tu_chon)
  ");
  $ins->execute([
    ':ho_cau_id'                => $src['ho_cau_id'],
    ':chuho_id'                 => $src['chuho_id'],
    ':creator_id'               => $_SESSION['user']['id'] ?? $src['creator_id'],
    ':hinh_thuc_id'             => $src['hinh_thuc_id'],
    ':ten_game'                 => ($src['ten_game'] ?? 'Game copy') . ' (A)',
    ':so_luong_can_thu'         => $src['so_luong_can_thu'],
    ':so_bang'                  => $src['so_bang'],
    ':so_hiep'                  => $src['so_hiep'],
    ':thoi_luong_phut_hiep'     => $src['thoi_luong_phut_hiep'],
    ':ngay_to_chuc'             => $src['ngay_to_chuc'],
    ':gio_bat_dau'              => $src['gio_bat_dau'],
    ':thoi_gian_dong_dang_ky'   => $src['thoi_gian_dong_dang_ky'],
    ':tien_cuoc'                => $src['tien_cuoc'],
    ':phi_game'                 => $src['phi_game'],
    ':phi_ho'                   => $src['phi_ho'],
    ':luat_choi'                => $src['luat_choi'],
    ':min_user_exp'             => $src['min_user_exp'],
    ':min_user_level'           => $src['min_user_level'],
    ':quy_tac_xoay_tu_chon'     => $src['quy_tac_xoay_tu_chon'],
  ]);
  $newId = (int)$pdo->lastInsertId();

  // 3) Lấy danh sách người chơi game cũ
  $gu = $pdo->prepare("
    SELECT user_id, nickname, trang_thai, note, vi_tri_ngoi, is_bien
    FROM game_user
    WHERE game_id = :gid
    ORDER BY created_at ASC
  ");
  $gu->execute([':gid' => $srcId]);
  $rows = $gu->fetchAll(PDO::FETCH_ASSOC);
  if (!$rows) {
    $pdo->rollBack();
    exit('Game nguồn chưa có người chơi.');
  }

  // 4) Copy game_user sang game mới (reset điểm/kg/xếp hạng; để vi_tri_ngoi=0, is_bien=0 rồi random sau)
  $insU = $pdo->prepare("
    INSERT INTO game_user
      (game_id, user_id, nickname, trang_thai, da_thanh_toan, payment_time,
       note, tong_diem, tong_kg, xep_hang, vi_tri_ngoi, is_bien)
    VALUES
      (:game_id, :user_id, :nickname, :trang_thai, 0, NULL,
       :note, 0.00, 0.00, 0, 0, 0)
  ");
  foreach ($rows as $r) {
    $insU->execute([
      ':game_id'    => $newId,
      ':user_id'    => (int)$r['user_id'],
      ':nickname'   => $r['nickname'] ?? null,
      // Giữ trạng thái tham gia: xac_nhan/da_thanh_toan... (tuỳ ý)
      ':trang_thai' => $r['trang_thai'] ?? 'xac_nhan',
      ':note'       => $r['note'] ?? null,
    ]);
  }

  // 5) Random chỗ ngồi & “không trùng biên” (N ≥ 43)
  $N = count($rows);

  // Lấy danh sách user_id mới theo thứ tự tạo
  $getNewUsers = $pdo->prepare("SELECT user_id FROM game_user WHERE game_id = :gid ORDER BY created_at ASC");
  $getNewUsers->execute([':gid' => $newId]);
  $uids = array_map('intval', $getNewUsers->fetchAll(PDO::FETCH_COLUMN));

  // Tập "biên cũ": ai từng biên hoặc từng ngồi ghế 1/N ở game cũ
  $edgeSt = $pdo->prepare("
    SELECT user_id
    FROM game_user
    WHERE game_id = :gid AND (is_bien = 1 OR vi_tri_ngoi IN (1, :n))
  ");
  $edgeSt->execute([':gid' => $srcId, ':n' => $N]);
  $edgeOld = array_map('intval', $edgeSt->fetchAll(PDO::FETCH_COLUMN));

  shuffle($uids);

  $placeEdges = function (array $uids, array $edgeOld, int $N) {
    $nonEdge = array_values(array_diff($uids, $edgeOld));
    if (count($nonEdge) >= 2) {
      $first = array_shift($nonEdge);
      $last  = array_shift($nonEdge);
      $rest  = array_values(array_diff($uids, [$first, $last]));
      array_unshift($rest, $first);
      $rest[] = $last;
      return $rest;
    }
    return $uids; // không đủ người để tránh biên
  };

  if ($N >= 43) {
    $uids = $placeEdges($uids, $edgeOld, $N);
  } else {
    // N < 43: vẫn cố tránh nếu được (không bắt buộc)
    $try = $placeEdges($uids, $edgeOld, $N);
    $uids = $try;
  }

  // Cập nhật seat & is_bien
  $upSeat = $pdo->prepare("
    UPDATE game_user
    SET vi_tri_ngoi = :seat, is_bien = :is_bien
    WHERE game_id = :gid AND user_id = :uid
  ");
  $seat = 1;
  foreach ($uids as $uid) {
    $isBien = ($seat === 1 || $seat === $N) ? 1 : 0;
    $upSeat->execute([
      ':seat'    => $seat,
      ':is_bien' => $isBien,
      ':gid'     => $newId,
      ':uid'     => (int)$uid,
    ]);
    $seat++;
  }

  $pdo->commit();
  header("Location: game_detail.php?game_id=" . $newId . "&copied=A");
  exit;
} catch (Throwable $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  http_response_code(500);
  echo "Lỗi: " . htmlspecialchars($e->getMessage());
}
