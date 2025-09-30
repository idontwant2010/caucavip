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

  $st = $pdo->prepare("SELECT * FROM game_list WHERE id = :id FOR UPDATE");
  $st->execute([':id' => $srcId]);
  $src = $st->fetch(PDO::FETCH_ASSOC);
  if (!$src) {
    $pdo->rollBack();
    exit('Game nguồn không tồn tại.');
  }

  // status = dang_mo_dang_ky
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
       :tien_cuoc, :phi_game, :phi_ho, :luat_choi, 'dang_mo_dang_ky',
       NOW(), :min_user_exp, :min_user_level, :quy_tac_xoay_tu_chon)
  ");
  $ins->execute([
    ':ho_cau_id'                => $src['ho_cau_id'],
    ':chuho_id'                 => $src['chuho_id'],
    ':creator_id'               => $_SESSION['user']['id'] ?? $src['creator_id'],
    ':hinh_thuc_id'             => $src['hinh_thuc_id'],
    ':ten_game'                 => ($src['ten_game'] ?? 'Game copy') . ' (B)',
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

  // Copy danh sách: reset điểm/kg/xếp hạng, seat=0, is_bien=0; trạng thái về 'cho_xac_nhan'
  $gu = $pdo->prepare("
    SELECT user_id, nickname, note
    FROM game_user
    WHERE game_id = :gid
    ORDER BY created_at ASC
  ");
  $gu->execute([':gid' => $srcId]);
  $rows = $gu->fetchAll(PDO::FETCH_ASSOC);

  if ($rows) {
    $insU = $pdo->prepare("
      INSERT INTO game_user
        (game_id, user_id, nickname, trang_thai, da_thanh_toan, payment_time,
         note, tong_diem, tong_kg, xep_hang, vi_tri_ngoi, is_bien)
      VALUES
        (:game_id, :user_id, :nickname, 'cho_xac_nhan', 0, NULL,
         :note, 0.00, 0.00, 0, 0, 0)
    ");
    foreach ($rows as $r) {
      $insU->execute([
        ':game_id'  => $newId,
        ':user_id'  => (int)$r['user_id'],
        ':nickname' => $r['nickname'] ?? null,
        ':note'     => $r['note'] ?? null,
      ]);
    }
  }

  $pdo->commit();
  header("Location: game_user_add.php?game_id=" . $newId . "&copied=B");
  exit;
} catch (Throwable $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  http_response_code(500);
  echo "Lỗi: " . htmlspecialchars($e->getMessage());
}
