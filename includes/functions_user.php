<?php
/**
 * functions_user.php
 * Các hàm tiện ích cho user: counters / EXP / Level / visited
 *
 * YÊU CẦU LƯU Ý:
 * - Bảng users có các cột: user_exp, cnt_xa, cnt_ho, cnt_giai, cnt_game, user_lever
 * - Bảng user_xa_visited  (PRIMARY KEY(user_id, xa_phuong_id))
 * - Bảng user_ho_visited  (PRIMARY KEY(user_id, ho_cau_id))
 * - Bảng giai_user        (user_id, trang_thai = 'da_thanh_toan')
 * - Bảng game_user        (user_id, trang_thai = 'da_thanh_toan')
 * - Bảng ho_cau           (id, xa_phuong_id)
 * - Bảng user_lever_rules (lever, user_exp_toi_thieu, so_ho_toi_thieu, so_game_toi_thieu, [so_xa_toi_thieu | so_tinh_toi_thieu])
 */

if (!defined('TRANG_THAI_DA_THANH_TOAN')) {
    define('TRANG_THAI_DA_THANH_TOAN', 'da_thanh_toan');
}

/**
 * Tính lại 4 counters cho 1 user: cnt_xa, cnt_ho, cnt_giai, cnt_game
 * Gọi hàm này sau mỗi sự kiện có thể làm thay đổi counters (kết thúc booking, tham gia giải/game).
 */
function updateUserCounters(PDO $pdo, int $user_id): void
{
    $sql = "
        UPDATE users u
        SET
          u.cnt_xa = (
            SELECT COUNT(*) FROM user_xa_visited vx WHERE vx.user_id = u.id
          ),
          u.cnt_ho = (
            SELECT COUNT(*) FROM user_ho_visited vh WHERE vh.user_id = u.id
          ),
          u.cnt_giai = (
            SELECT COUNT(*) FROM giai_user gu
            WHERE gu.user_id = u.id AND gu.trang_thai = :paid1
          ),
          u.cnt_game = (
            SELECT COUNT(*) FROM game_user gm
            WHERE gm.user_id = u.id AND gm.trang_thai = :paid2
          )
        WHERE u.id = :uid
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':paid1' => TRANG_THAI_DA_THANH_TOAN,
        ':paid2' => TRANG_THAI_DA_THANH_TOAN,
        ':uid'   => $user_id,
    ]);
}

/**
 * Cộng EXP cho user. Có thể gọi sau khi hoàn thành booking / tham gia giải / game.
 * Lưu ý: nếu bạn có bảng log EXP riêng, thêm insert log tại đây.
 */
function addUserExp(PDO $pdo, int $user_id, int $exp, string $note = ''): void
{
    if ($exp <= 0) return;

    $stmt = $pdo->prepare("UPDATE users SET user_exp = user_exp + :exp WHERE id = :uid");
    $stmt->execute([':exp' => $exp, ':uid' => $user_id]);

    // TODO: nếu có bảng log EXP, insert ở đây (ví dụ user_exp_logs)
    // $pdo->prepare("INSERT INTO user_exp_logs (user_id, exp_add, note, created_at) VALUES (?,?,?,NOW())")
    //     ->execute([$user_id, $exp, $note]);
}

/**
 * Tính lại cấp độ user (user_lever) theo bảng user_lever_rules.
 * Ưu tiên cột so_xa_toi_thieu. Nếu bảng rule CHƯA có cột này, tự động fallback về so_tinh_toi_thieu.
 */
function recalcUserLevel(PDO $pdo, int $user_id): void
{
    // Lấy counters & exp hiện tại
    $u = $pdo->prepare("SELECT user_exp, cnt_ho, cnt_game, cnt_xa, cnt_tinh FROM users WHERE id = ?");
    $u->execute([$user_id]);
    $user = $u->fetch(PDO::FETCH_ASSOC);
    if (!$user) return;

    $exp  = (int)$user['user_exp'];
    $ho   = (int)$user['cnt_ho'];
    $game = (int)$user['cnt_game'];
    $xa   = (int)($user['cnt_xa'] ?? 0);
    $tinh = (int)($user['cnt_tinh'] ?? 0);

    $lever = 0;

    // Thử truy vấn với so_xa_toi_thieu trước
    try {
        $sqlXa = "
            SELECT r.lever
            FROM user_lever_rules r
            WHERE r.user_exp_toi_thieu <= :exp
              AND r.so_ho_toi_thieu    <= :ho
              AND r.so_game_toi_thieu  <= :game
              AND r.so_xa_toi_thieu    <= :xa
            ORDER BY r.lever DESC
            LIMIT 1
        ";
        $stmt = $pdo->prepare($sqlXa);
        $stmt->execute([
            ':exp'  => $exp,
            ':ho'   => $ho,
            ':game' => $game,
            ':xa'   => $xa,
        ]);
        $lever = (int)$stmt->fetchColumn();
    } catch (Throwable $e) {
        // Nếu cột so_xa_toi_thieu chưa tồn tại, fallback về so_tinh_toi_thieu
        $sqlTinh = "
            SELECT r.lever
            FROM user_lever_rules r
            WHERE r.user_exp_toi_thieu <= :exp
              AND r.so_ho_toi_thieu    <= :ho
              AND r.so_game_toi_thieu  <= :game
              AND r.so_tinh_toi_thieu  <= :tinh
            ORDER BY r.lever DESC
            LIMIT 1
        ";
        $stmt = $pdo->prepare($sqlTinh);
        $stmt->execute([
            ':exp'  => $exp,
            ':ho'   => $ho,
            ':game' => $game,
            ':tinh' => $tinh,
        ]);
        $lever = (int)$stmt->fetchColumn();
    }

    if ($lever > 0) {
        $upd = $pdo->prepare("UPDATE users SET user_lever = :lever WHERE id = :uid");
        $upd->execute([':lever' => $lever, ':uid' => $user_id]);
    }
}

/**
 * Khi finalize booking: ghi dấu vết hồ & xã (không trùng), dựa vào ho_cau.xa_phuong_id.
 * Nên gọi trong transaction của quy trình finalize.
 */
function ensureVisitedOnBookingFinalize(PDO $pdo, int $user_id, int $ho_cau_id): void
{
    if ($ho_cau_id <= 0) return;

    // 1. Insert hồ vào user_ho_visited
    $pdo->prepare("
        INSERT IGNORE INTO user_ho_visited (user_id, ho_cau_id)
        VALUES (?, ?)
    ")->execute([$user_id, $ho_cau_id]);

    // 2. Lấy xã từ ho_cau -> cum_ho -> dm_xa_phuong
    $stmt = $pdo->prepare("
        SELECT c.xa_id
        FROM ho_cau h
        JOIN cum_ho c ON h.cum_ho_id = c.id
        WHERE h.id = ?
        LIMIT 1
    ");
    $stmt->execute([$ho_cau_id]);
    $xa_id = (int)$stmt->fetchColumn();

    if ($xa_id > 0) {
        // 3. Insert xã vào user_xa_visited (đảm bảo không trùng)
        $pdo->prepare("
            INSERT IGNORE INTO user_xa_visited (user_id, xa_phuong_id)
            VALUES (?, ?)
        ")->execute([$user_id, $xa_id]);
    }
}


/**
 * Gợi ý: Gộp workflow sau mỗi sự kiện (ví dụ booking hoàn thành)
 * - ensureVisitedOnBookingFinalize
 * - addUserExp
 * - updateUserCounters
 * - recalcUserLevel
 *
 * Ví dụ dùng:
 *   $pdo->beginTransaction();
 *   ensureVisitedOnBookingFinalize($pdo, $user_id, $ho_cau_id);
 *   addUserExp($pdo, $user_id, 5, 'Hoàn thành booking');
 *   updateUserCounters($pdo, $user_id);
 *   recalcUserLevel($pdo, $user_id);
 *   $pdo->commit();
 */
