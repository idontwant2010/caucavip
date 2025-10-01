<?php
declare(strict_types=1);

/**
 * Gencode utilities
 * - Prefix chuẩn:
 *   + Cụm hồ: CUM-{ID}-{RANDOM}
 *   + Hồ câu: HCA-{ID}-{RANDOM}
 * - RANDOM: A–Z0–9 (mặc định 10 ký tự) -> URL-safe, khó đoán
 *
 * Khuyến nghị DB:
 *   ALTER TABLE cum_ho ADD COLUMN IF NOT EXISTS cum_ho_code VARCHAR(40) NULL AFTER id;
 *   ALTER TABLE cum_ho ADD UNIQUE KEY IF NOT EXISTS ux_cum_ho_code (cum_ho_code);
 *   ALTER TABLE ho_cau ADD COLUMN IF NOT EXISTS ho_cau_code VARCHAR(40) NULL AFTER id;
 *   ALTER TABLE ho_cau ADD UNIQUE KEY IF NOT EXISTS ux_ho_cau_code (ho_cau_code);
 */

const GCODE_PREFIX_CUM_HO      = 'CUM';
const GCODE_PREFIX_HO_CAU      = 'HCA';
const GCODE_RANDOM_LEN_DEFAULT = 10;

/**
 * Tạo chuỗi ngẫu nhiên A–Z0–9 độ dài $len (dùng random_int, an toàn).
 */
function gcode_rand_az09(int $len = GCODE_RANDOM_LEN_DEFAULT): string {
    static $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $out = '';
    $max = strlen($chars) - 1;
    for ($i = 0; $i < $len; $i++) {
        $out .= $chars[random_int(0, $max)];
    }
    return $out;
}

/**
 * Regex format builders (để validate nhanh theo độ dài RANDOM tuỳ biến).
 */
function gcode_regex_cum_ho(int $randLen = GCODE_RANDOM_LEN_DEFAULT): string {
    return '/^' . GCODE_PREFIX_CUM_HO . '-\d+-[A-Z0-9]{' . $randLen . '}$/';
}
function gcode_regex_ho_cau(int $randLen = GCODE_RANDOM_LEN_DEFAULT): string {
    return '/^' . GCODE_PREFIX_HO_CAU . '-\d+-[A-Z0-9]{' . $randLen . '}$/';
}

/**
 * Validate code theo format đã chốt.
 */
function gcode_is_valid_cum_ho(string $code, int $randLen = GCODE_RANDOM_LEN_DEFAULT): bool {
    return (bool)preg_match(gcode_regex_cum_ho($randLen), $code);
}
function gcode_is_valid_ho_cau(string $code, int $randLen = GCODE_RANDOM_LEN_DEFAULT): bool {
    return (bool)preg_match(gcode_regex_ho_cau($randLen), $code);
}

/**
 * Generate code cho cụm hồ: CUM-{ID}-{RANDOM}
 */
function genCumHoCode(int $id, int $randLen = GCODE_RANDOM_LEN_DEFAULT): string {
    return GCODE_PREFIX_CUM_HO . '-' . $id . '-' . gcode_rand_az09($randLen);
}

/**
 * Generate code cho hồ câu: HCA-{ID}-{RANDOM}
 */
function genHoCauCode(int $id, int $randLen = GCODE_RANDOM_LEN_DEFAULT): string {
    return GCODE_PREFIX_HO_CAU . '-' . $id . '-' . gcode_rand_az09($randLen);
}

/**
 * Cập nhật code cho 1 bản ghi CỤM HỒ (sau khi INSERT xong có $id).
 * - Nếu truyền $code = null -> tự sinh theo format.
 * - Trả về code cuối cùng đã lưu.
 */
function gcode_update_cum_ho(PDO $pdo, int $id, ?string $code = null, int $randLen = GCODE_RANDOM_LEN_DEFAULT): string {
    if ($id <= 0) {
        throw new InvalidArgumentException('cum_ho.id không hợp lệ');
    }
    if ($code === null || $code === '') {
        $code = genCumHoCode($id, $randLen);
    }
    if (!gcode_is_valid_cum_ho($code, $randLen)) {
        throw new InvalidArgumentException('cum_ho_code không đúng format: ' . $code);
    }
    $st = $pdo->prepare("UPDATE cum_ho SET cum_ho_code = ? WHERE id = ? LIMIT 1");
    $st->execute([$code, $id]);
    return $code;
}

/**
 * Cập nhật code cho 1 bản ghi HỒ CÂU (sau khi INSERT xong có $id).
 * - Nếu truyền $code = null -> tự sinh theo format.
 * - Trả về code cuối cùng đã lưu.
 */
function gcode_update_ho_cau(PDO $pdo, int $id, ?string $code = null, int $randLen = GCODE_RANDOM_LEN_DEFAULT): string {
    if ($id <= 0) {
        throw new InvalidArgumentException('ho_cau.id không hợp lệ');
    }
    if ($code === null || $code === '') {
        $code = genHoCauCode($id, $randLen);
    }
    if (!gcode_is_valid_ho_cau($code, $randLen)) {
        throw new InvalidArgumentException('ho_cau_code không đúng format: ' . $code);
    }
    $st = $pdo->prepare("UPDATE ho_cau SET ho_cau_code = ? WHERE id = ? LIMIT 1");
    $st->execute([$code, $id]);
    return $code;
}

/**
 * Backfill code cho toàn bộ dữ liệu còn thiếu/sai format (transaction an toàn).
 * - Chỉ generate và update các dòng thiếu code hoặc sai regex format.
 * - $randLen: độ dài RANDOM để validate/ghi đồng nhất.
 * - Trả về mảng kết quả: ['cum_ho_updated' => n, 'ho_cau_updated' => n]
 */
function gcode_backfill_all(PDO $pdo, int $randLen = GCODE_RANDOM_LEN_DEFAULT): array {
    $pdo->beginTransaction();
    try {
        // cum_ho: lấy id các dòng cần backfill
        $regexCum = gcode_regex_cum_ho($randLen);
        $rows = $pdo->query("SELECT id, cum_ho_code FROM cum_ho")->fetchAll(PDO::FETCH_ASSOC);
        $u1 = 0;
        foreach ($rows as $r) {
            $id   = (int)$r['id'];
            $code = (string)($r['cum_ho_code'] ?? '');
            if ($code === '' || !preg_match($regexCum, $code)) {
                gcode_update_cum_ho($pdo, $id, null, $randLen);
                $u1++;
            }
        }

        // ho_cau: lấy id các dòng cần backfill
        $regexHo = gcode_regex_ho_cau($randLen);
        $rows = $pdo->query("SELECT id, ho_cau_code FROM ho_cau")->fetchAll(PDO::FETCH_ASSOC);
        $u2 = 0;
        foreach ($rows as $r) {
            $id   = (int)$r['id'];
            $code = (string)($r['ho_cau_code'] ?? '');
            if ($code === '' || !preg_match($regexHo, $code)) {
                gcode_update_ho_cau($pdo, $id, null, $randLen);
                $u2++;
            }
        }

        $pdo->commit();
        return ['cum_ho_updated' => $u1, 'ho_cau_updated' => $u2];
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}

/**
 * Gợi ý sử dụng sau khi INSERT (an toàn, thuần PHP, không cần trigger):
 *
 * // Tạo mới 1 cụm hồ:
 * $pdo->beginTransaction();
 * $st = $pdo->prepare("INSERT INTO cum_ho (ten_cum_ho, ...) VALUES (?, ...)");
 * $st->execute([$ten_cum_ho, ...]);
 * $id = (int)$pdo->lastInsertId();
 * $code = gcode_update_cum_ho($pdo, $id); // sinh & lưu CUM-{ID}-{RANDOM}
 * $pdo->commit();
 *
 * // Tạo mới 1 hồ câu:
 * $pdo->beginTransaction();
 * $st = $pdo->prepare("INSERT INTO ho_cau (ten_ho, ...) VALUES (?, ...)");
 * $st->execute([$ten_ho, ...]);
 * $id = (int)$pdo->lastInsertId();
 * $code = gcode_update_ho_cau($pdo, $id); // sinh & lưu HCA-{ID}-{RANDOM}
 * $pdo->commit();
 *
 * // Backfill toàn bộ (nếu cần):
 * $result = gcode_backfill_all($pdo); // ['cum_ho_updated'=>..., 'ho_cau_updated'=>...]
 */
 
 
/**
 * Generate code cho booking: BOK-{ID}-{RANDOM}
 */
function genBookingCode(int $id, int $randLen = GCODE_RANDOM_LEN_DEFAULT): string {
    return 'BOK-' . $id . '-' . gcode_rand_az09($randLen);
}

/**
 * Cập nhật booking_code sau khi INSERT booking mới
 */
function gcode_update_booking(PDO $pdo, int $id, ?string $code = null, int $randLen = GCODE_RANDOM_LEN_DEFAULT): string {
    if ($id <= 0) {
        throw new InvalidArgumentException('booking.id không hợp lệ');
    }
    if ($code === null || $code === '') {
        $code = genBookingCode($id, $randLen);
    }
    $st = $pdo->prepare("UPDATE booking SET booking_code=? WHERE id=? LIMIT 1");
    $st->execute([$code, $id]);
    return $code;
}


/**
 * Generate code cho game_list: GAM-{ID}-{RANDOM}
 */
function genGameCode(int $id, int $randLen = GCODE_RANDOM_LEN_DEFAULT): string {
    return 'GAM-' . $id . '-' . gcode_rand_az09($randLen);
}

/**
 * Cập nhật game_code sau khi INSERT game_list mới.
 * Trả về code cuối cùng đã lưu.
 */
function gcode_update_game(PDO $pdo, int $id, ?string $code = null, int $randLen = GCODE_RANDOM_LEN_DEFAULT): string {
    if ($id <= 0) {
        throw new InvalidArgumentException('game_list.id không hợp lệ');
    }
    if ($code === null || $code === '') {
        $code = genGameCode($id, $randLen);
    }
    $st = $pdo->prepare("UPDATE game_list SET game_code = ? WHERE id = ? LIMIT 1");
    $st->execute([$code, $id]);
    return $code;
}


/**
 * Generate code cho giai_list: GIA-{ID}-{RANDOM}
 */
function genGiaiCode(int $id, int $randLen = GCODE_RANDOM_LEN_DEFAULT): string {
    return 'GIA-' . $id . '-' . gcode_rand_az09($randLen);
}

/**
 * Cập nhật giai_code sau khi INSERT giai_list mới
 */
function gcode_update_giai(PDO $pdo, int $id, ?string $code = null, int $randLen = GCODE_RANDOM_LEN_DEFAULT): string {
    if ($id <= 0) {
        throw new InvalidArgumentException('giai_list.id không hợp lệ');
    }
    if ($code === null || $code === '') {
        $code = genGiaiCode($id, $randLen);
    }
    $st = $pdo->prepare("UPDATE giai_list SET giai_code = ? WHERE id = ? LIMIT 1");
    $st->execute([$code, $id]);
    return $code;
}


/**
 * Generate code cho user: USR-{ID}-{RANDOM}
 */
function genUserCode(int $id, int $randLen = GCODE_RANDOM_LEN_DEFAULT): string {
    return 'USR-' . $id . '-' . gcode_rand_az09($randLen);
}

/**
 * Cập nhật user_code sau khi INSERT user mới
 */
function gcode_update_user(PDO $pdo, int $id, ?string $code = null, int $randLen = GCODE_RANDOM_LEN_DEFAULT): string {
    if ($id <= 0) {
        throw new InvalidArgumentException('users.id không hợp lệ');
    }
    if ($code === null || $code === '') {
        $code = genUserCode($id, $randLen);
    }
    $st = $pdo->prepare("UPDATE users SET user_code = ? WHERE id = ? LIMIT 1");
    $st->execute([$code, $id]);
    return $code;
}
