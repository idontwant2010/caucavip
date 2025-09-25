<?php
// File: cauca/chuho/booking/booking_detail.php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';
require_once __DIR__ . '/../../../includes/helpers/helpers_price.php';

if ($_SESSION['user']['vai_tro'] !== 'chuho') {
    header("Location: /");
    exit;
}

$chuho_id   = (int)$_SESSION['user']['id'];
$booking_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;



// 1) Load booking theo $booking_id
$booking_id = (int)$booking_id; // ép int cho chắc
if ($booking_id <= 0) { die('Thiếu ID booking'); }

if (!isset($booking) || !is_array($booking)) {
    $stmt = $pdo->prepare("SELECT b.* FROM booking b WHERE b.id = ? LIMIT 1");
    $stmt->execute([$booking_id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$booking) { die('Booking không tồn tại'); }
}

// 2) Lấy bảng giá đang dùng
$gia_id = 0;
if (!empty($booking['gia_thit_id'])) {
    $gia_id = (int)$booking['gia_thit_id'];
} elseif (!empty($booking['gia_id'])) {
    $gia_id = (int)$booking['gia_id'];
}

$gia = null;
if ($gia_id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM gia_ca_thit_phut WHERE id = ? AND status='open'");
    $stmt->execute([$gia_id]);
    $gia = $stmt->fetch(PDO::FETCH_ASSOC);
}
// Fallback theo hồ nếu chưa có id bảng giá
if (!$gia && !empty($booking['ho_cau_id'])) {
    $stmt = $pdo->prepare("
        SELECT * FROM gia_ca_thit_phut
        WHERE ho_cau_id = ? AND status='open'
        ORDER BY id DESC LIMIT 1
    ");
    $stmt->execute([(int)$booking['ho_cau_id']]);
    $gia = $stmt->fetch(PDO::FETCH_ASSOC);
}
if (!$gia) {
    echo '<div class="alert alert-warning">Không tìm thấy bảng giá (hoặc đang đóng). Vui lòng chọn lại.</div>';
    return; // dừng render phần dưới để tránh warning
}

// 3) Tính tổng phút, kg, tiền đặt cọc
$real_minutes = (int)($booking['real_tong_thoi_luong'] ?? 0);
if ($real_minutes <= 0 && !empty($booking['real_start_time']) && !empty($booking['real_end_time'])) {
    $real_minutes = max(0, (int) round((strtotime($booking['real_end_time']) - strtotime($booking['real_start_time'])) / 60));
}

$kg_cau = (float)($booking['fish_weight'] ?? 0);
$kg_ban = (float)($booking['fish_sell_weight'] ?? 0);
$kg_thu = max(0, $kg_cau - $kg_ban);

$booking_amount = (($booking['booking_amount'] ?? '') === 'Đã chuyển')
    ? (int)($booking['booking_amount'] ?? 0)
    : 0;

// 4) Tính tiền cá
$calc = calcTienCauThit(
    $gia,
    $real_minutes,
    $kg_cau,
    $kg_ban,
    $kg_thu,
    $booking_amount,
    false, // repeatDiscountOver4
    true   // minimumOneSuat
);

// 4) Tính tiền service 
$st = $pdo->prepare("SELECT * FROM booking_service_fee WHERE booking_id = :bid ORDER BY created_at DESC");
$st->execute(['bid'=>$booking['id']]);
$services = $st->fetchAll();

$service_total = 0;
foreach ($services as $sv) {
    $service_total += $sv['amount'];
}

/* === DÁN KHỐI OVERRIDE NGAY TẠI ĐÂY === */
// Ưu tiên số TIỀN/ KG thu lại cá đã lưu trong DB, rồi mới fallback công thức
if (isset($booking['fish_return_amount']) && $booking['fish_return_amount'] !== null) {
    // tiền thu lại cá (số dương)
    $calc['fish_return_amount'] = max(0, (int)$booking['fish_return_amount']);
} elseif (isset($booking['fish_return_weight']) && $booking['fish_return_weight'] !== null) {
    // nếu DB lưu KG thu lại riêng thì dùng đúng KG này
    $kg_thu = (float)$booking['fish_return_weight'];
    $calc['fish_return_amount'] = (int)round($kg_thu * (int)$gia['gia_thu_lai']);
}

if (isset($booking['fish_sell_amount']) && $booking['fish_sell_amount'] !== null) {
    // tiền khách mang về (số dương)
    $calc['fish_sell_amount'] = max(0, (int)$booking['fish_sell_amount']);
}

// Recalc tổng sau khi override
$calc['total_amount'] = $calc['real_amount_before_fish']
                      + $calc['fish_sell_amount']
					  + $service_total
                      - $calc['fish_return_amount']
                      - $calc['booking_amount'];
/* === HẾT KHỐI OVERRIDE === */


// 5) Thông tin cần thủ để hiển thị
//$stmt = $pdo->prepare("SELECT phone, full_name, user_exp, user_lever FROM users WHERE id = ?");
//$stmt->execute([(int)$booking['user_id']]);
//$user = $stmt->fetch(PDO::FETCH_ASSOC);


// --- Lấy booking + hồ (đảm bảo thuộc chủ hồ hiện tại)
$sql = "
SELECT b.*, 
       h.ten_ho, h.gia_xe_heo, h.so_cho_ngoi, h.status AS ho_status,
       c.ten_cum_ho, c.dia_chi
FROM booking b
JOIN ho_cau h ON h.id = b.ho_cau_id
JOIN cum_ho  c ON c.id = h.cum_ho_id
WHERE b.id = :bid AND b.chu_ho_id = :chuho
";
$st = $pdo->prepare($sql);
$st->execute([':bid' => $booking_id, ':chuho' => $chuho_id]);
$bk = $st->fetch(PDO::FETCH_ASSOC);
if (!$bk) {
    http_response_code(404);
    die("Booking không tồn tại hoặc không thuộc quyền của bạn.");
}

// ===== Lấy danh sách bảng giá của hồ (tối giản, có fallback tên) =====
function getGiaListForHo(PDO $pdo, int $hoId): array {
    $sql = "
        SELECT id,
               ten_bang_gia   AS label,
               base_duration,
               base_price,
               extra_unit_price,
               discount_2x_duration,
               discount_3x_duration,
               discount_4x_duration,
               gia_ban_ca,
               gia_thu_lai,
               loai_thu,
               status
        FROM gia_ca_thit_phut
        WHERE ho_cau_id = :hid
          AND status = 'open'
        ORDER BY base_price ASC, id ASC
    ";
    $st = $pdo->prepare($sql);
    $st->execute([':hid' => $hoId]);
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as &$r) { // nhãn đẹp hơn
        if (!$r['label']) $r['label'] = 'BG #'.$r['id'];
        $r['label'] .= ' — '.$r['base_duration'].'p / '.number_format((int)$r['base_price']).'đ';
    }
    return $rows;
}


$gia_list = getGiaListForHo($pdo, (int)$bk['ho_cau_id']);


// Lấy đơn giá/kg từ bảng gia_ca_thit_phut theo gia_id của booking
function getGiaPrices(PDO $pdo, int $giaId): array {
    if (!$giaId) return ['thu_kg'=>null, 'ban_kg'=>null, 'raw'=>[]];
    $st = $pdo->prepare("SELECT * FROM gia_ca_thit_phut WHERE id = :id LIMIT 1");
    $st->execute([':id'=>$giaId]);
    $row = $st->fetch(PDO::FETCH_ASSOC);
    if (!$row) return ['thu_kg'=>null, 'ban_kg'=>null, 'raw'=>[]];

    // Ưu tiên các tên cột phổ biến; tự dò nếu schema khác
    $candidates_thu = ['gia_thu_kg','don_gia_thu','gia_thu_lai','don_gia_thu_lai','gia_thu','thu_gia'];
    $candidates_ban = ['gia_ban_kg','don_gia_ban','gia_ban_ca','ban_gia'];

    $thu = null; foreach ($candidates_thu as $c) if (isset($row[$c]) && is_numeric($row[$c])) { $thu = (int)$row[$c]; break; }
    $ban = null; foreach ($candidates_ban as $c) if (isset($row[$c]) && is_numeric($row[$c])) { $ban = (int)$row[$c]; break; }

    return ['thu_kg'=>$thu, 'ban_kg'=>$ban, 'raw'=>$row];
}

$gia_prices = getGiaPrices($pdo, (int)$bk['gia_id']); // ['thu_kg'=>..., 'ban_kg'=>...]


// --- Kiểm tra tồn tại cột 'vi_tri' (để lưu vị trí chỗ ngồi nếu DB đã có)
$hasSeatCol = false;
try {
    $pdo->query("SELECT vi_tri FROM booking WHERE id = -1");
    $hasSeatCol = true;
} catch (Throwable $e) {
    $hasSeatCol = false; // chưa có cột
}

// --- Xử lý hành động POST
$err = $msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'save_seat' && $hasSeatCol) {
            $seat = max(1, (int)($_POST['seat'] ?? 0));
            // không vượt quá số chỗ
            if ($seat > (int)$bk['so_cho_ngoi']) $seat = (int)$bk['so_cho_ngoi'];
            $st = $pdo->prepare("UPDATE booking SET vi_tri = :s WHERE id = :id");
            $st->execute([':s' => $seat, ':id' => $booking_id]);
            $msg = "Đã lưu vị trí: $seat";

        } elseif ($action === 'update_times') {
			$start_local = trim($_POST['start_at'] ?? ''); // dạng YYYY-MM-DDTHH:MM
			$end_local   = trim($_POST['end_at']   ?? '');

			// validate đơn giản
			if ($start_local !== '' && $end_local !== '') {
				$s = strtotime(str_replace('T', ' ', $start_local));
				$e = strtotime(str_replace('T', ' ', $end_local));
				if ($s !== false && $e !== false && $e < $s) {
					throw new RuntimeException("Giờ kết thúc không được trước giờ bắt đầu.");
				}
			}

			// cập nhật start/end (nếu bỏ trống thì set NULL) – dùng STR_TO_DATE để khỏi phải đổi format ở PHP
			$upd = $pdo->prepare("
				UPDATE booking
				SET real_start_time = CASE WHEN :start1 = '' THEN NULL ELSE STR_TO_DATE(:start2, '%Y-%m-%dT%H:%i') END,
					real_end_time   = CASE WHEN :end1   = '' THEN NULL ELSE STR_TO_DATE(:end2,   '%Y-%m-%dT%H:%i') END
				WHERE id = :id
			");
			$upd->execute([
				':start1' => $start_local,
				':start2' => $start_local,
				':end1'   => $end_local,
				':end2'   => $end_local,
				':id'     => $booking_id,
			]);


			// tính lại thời lượng nếu đủ start & end
			$recalc = $pdo->prepare("
				UPDATE booking
				SET real_tong_thoi_luong = CASE
					WHEN real_start_time IS NOT NULL AND real_end_time IS NOT NULL
					THEN TIMESTAMPDIFF(MINUTE, real_start_time, real_end_time)
					ELSE real_tong_thoi_luong
				END
				WHERE id = :id
			");
			$recalc->execute([':id' => $booking_id]);
			
// ... ĐOẠN CODE CŨ Ở TRÊN GIỮ NGUYÊN

try {
    // 1) Lấy tổng phút & base_duration từ bảng giá gắn với booking
    $st = $pdo->prepare("
        SELECT b.id, b.real_tong_thoi_luong, g.base_duration
        FROM booking b
        JOIN gia_ca_thit_phut g ON g.id = b.gia_id
        WHERE b.id = :id
        LIMIT 1
    ");
    $st->execute([':id' => $booking_id]);
    $bk = $st->fetch(PDO::FETCH_ASSOC);

    $tong_phut     = (int)($bk['real_tong_thoi_luong'] ?? 0);
    $base_duration = (int)($bk['base_duration'] ?? 0);

    if ($tong_phut > 0 && $base_duration > 0) {
        // 2) Tính số suất & phút lẻ (giờ thêm)
        // Quy ước: có thời lượng >0 thì tối thiểu 1 suất
        $so_suat_raw = intdiv($tong_phut, $base_duration);
        $so_suat     = max(1, $so_suat_raw);
        $phut_le     = max(0, $tong_phut - $so_suat * $base_duration);

        // 3) Lưu lại vào booking
        $up = $pdo->prepare("
            UPDATE booking
            SET real_so_suat = :so_suat,
                real_gio_them = :phut_le
            WHERE id = :id
        ");
        $up->execute([
            ':so_suat' => $so_suat,
            ':phut_le' => $phut_le,
            ':id'      => $booking_id
        ]);

        // 4) (Tuỳ chọn) Ghi log thay đổi
        if (!empty($enable_booking_logs)) {
            $log = $pdo->prepare("
                INSERT INTO booking_logs (booking_id, action, detail_json, created_at)
                VALUES (:id, 'recalc_time_units',
                        JSON_OBJECT('base_duration', :bd, 'tong_phut', :tp, 'so_suat', :ss, 'phut_le', :pl),
                        NOW())
            ");
            $log->execute([
                ':id' => $booking_id,
                ':bd' => $base_duration,
                ':tp' => $tong_phut,
                ':ss' => $so_suat,
                ':pl' => $phut_le,
            ]);
        }
    } else {
        // Không đủ dữ liệu để tính (thiếu base_duration hoặc tổng phút = 0) -> tuỳ chọn xử lý
        // ví dụ: giữ nguyên real_so_suat/real_gio_them hoặc set = 0
    }
} catch (Exception $e) {
    // Tuỳ chọn: log lỗi
    // error_log('Recalc suat/gio_them failed: '.$e->getMessage());
}
			
			

			$msg = "Đã lưu thời gian.";
			
		} elseif ($action === 'save_fish') {
			$fish_weight       = (float)($_POST['fish_weight'] ?? 0);
			$fish_sell_weight  = (float)($_POST['fish_sell_weight'] ?? 0);
			$fish_sell_amount  = trim($_POST['fish_sell_amount'] ?? '');
			$fish_return_amount= trim($_POST['fish_return_amount'] ?? '');
			$auto_calc         = isset($_POST['auto_calc']) ? 1 : 0;

			// Nếu tick "auto" HOẶC các ô tiền bỏ trống → tự tính theo bảng giá đã chọn
			if ($auto_calc || $fish_return_amount === '') {
				if (!empty($gia_prices['thu_kg'])) {
					$fish_return_amount = (int)round($fish_weight * (int)$gia_prices['thu_kg']);
				} else {
					$fish_return_amount = (int)($fish_return_amount === '' ? 0 : $fish_return_amount);
				}
			} else {
				$fish_return_amount = (int)$fish_return_amount;
			}

			if ($auto_calc || $fish_sell_amount === '') {
				if (!empty($gia_prices['ban_kg'])) {
					$fish_sell_amount = (int)round($fish_sell_weight * (int)$gia_prices['ban_kg']);
				} else {
					$fish_sell_amount = (int)($fish_sell_amount === '' ? 0 : $fish_sell_amount);
				}
			} else {
				$fish_sell_amount = (int)$fish_sell_amount;
			}

			$st = $pdo->prepare("
				UPDATE booking
				SET fish_weight = :fw,
					fish_sell_weight = :fsw,
					fish_sell_amount = :fsa,
					fish_return_amount = :fra
				WHERE id = :id
			");
			$st->execute([
				':fw'  => $fish_weight,
				':fsw' => $fish_sell_weight,
				':fsa' => $fish_sell_amount,
				':fra' => $fish_return_amount,
				':id'  => $booking_id
			]);
			$msg = "Đã lưu cá & tiền (".number_format($fish_return_amount)."đ thu lại, ".number_format($fish_sell_amount)."đ bán cá).";

			// Reload giá (phòng khi vừa đổi gia_id ở thẻ 'Bảng giá')
			$gia_prices = getGiaPrices($pdo, (int)$bk['gia_id']);

		// reload bảng giá khi chọn bảng giá mới
        } elseif ($action === 'update_gia') {
			$gia_id = (int)($_POST['gia_id'] ?? 0);
			// ... validate & UPDATE ...
			$upd = $pdo->prepare("UPDATE booking SET gia_id = :gid WHERE id = :id");
			$upd->execute([':gid' => $gia_id, ':id' => $booking_id]);

			// cập nhật biến trong RAM để phần view dùng giá mới
			$bk['gia_id']  = $gia_id;
			$gia_prices    = getGiaPrices($pdo, $gia_id);           // <- tính lại
			$gia_list      = getGiaListForHo($pdo, (int)$bk['ho_cau_id']); // (tuỳ) lấy lại list nếu label có đổi
			$msg = "Đã cập nhật bảng giá.";
			
			// reload booking để có gia_id mới
			$st = $pdo->prepare($sql);
			$st->execute([':bid' => $booking_id, ':chuho' => $chuho_id]);
			$bk = $st->fetch(PDO::FETCH_ASSOC);
			
		} elseif ($action === 'add_prize') {
            $prize_type = $_POST['prize_type'] ?? '';
            $amount     = (int)($_POST['amount'] ?? 0);
            $note       = trim($_POST['note'] ?? '');

            if (!in_array($prize_type, ['Thưởng heo','Xẻ heo','Thưởng xôi','Thưởng khoen'], true)) {
                throw new RuntimeException("Loại thưởng không hợp lệ.");
            }
            if ($amount <= 0) {
                throw new RuntimeException("Số tiền thưởng phải > 0.");
            }

            // Ghi log thưởng
            $st = $pdo->prepare("
                INSERT INTO booking_prize_awards
                    (booking_id, ho_cau_id, prize_type, amount, awarded_by, note)
                VALUES (:bid, :hid, :type, :amt, :by, :note)
            ");
            $st->execute([
                ':bid'  => $booking_id,
                ':hid'  => (int)$bk['ho_cau_id'],
                ':type' => $prize_type,
                ':amt'  => $amount,
                ':by'   => $chuho_id,
                ':note' => ($note === '' ? null : $note),
            ]);
            $msg = "Đã thêm thưởng ($prize_type: " . number_format($amount) . "đ).";

        } elseif ($action === 'delete_prize') {
            $award_id = (int)($_POST['award_id'] ?? 0);
            // chỉ xoá thưởng thuộc booking này & do chủ hồ đang xem
            $st = $pdo->prepare("
                DELETE FROM booking_prize_awards 
                WHERE id = :aid AND booking_id = :bid
            ");
            $st->execute([':aid' => $award_id, ':bid' => $booking_id]);
            $msg = "Đã xoá thưởng.";

        } elseif ($action === 'complete') {
            // Chỉ cho hoàn tất khi đã có start & end
            $check = $pdo->prepare("SELECT real_start_time, real_end_time FROM booking WHERE id = :id");
            $check->execute([':id' => $booking_id]);
            $r = $check->fetch(PDO::FETCH_ASSOC);
            if (empty($r['real_start_time']) || empty($r['real_end_time'])) {
                throw new RuntimeException("Cần bấm Start và End trước khi hoàn tất.");
            }

            $st = $pdo->prepare("UPDATE booking SET main_status='hoàn thành' WHERE id = :id");
            $st->execute([':id' => $booking_id]);
            $msg = "Booking đã hoàn tất.";
        }

        // Reload dữ liệu mới nhất
        $st = $pdo->prepare($sql);
        $st->execute([':bid' => $booking_id, ':chuho' => $chuho_id]);
        $bk = $st->fetch(PDO::FETCH_ASSOC);

    } catch (Throwable $ex) {
        $err = $ex->getMessage();
    }
}

// --- Lấy danh sách thưởng của booking
$awards = [];
$st2 = $pdo->prepare("
    SELECT id, prize_type, amount, note, created_at, awarded_by
    FROM booking_prize_awards
    WHERE booking_id = :bid
    ORDER BY created_at ASC
");
$st2->execute([':bid' => $booking_id]);
$awards = $st2->fetchAll(PDO::FETCH_ASSOC);

// 2) Tổng tiền thưởng +update vào database ==> booking.award_amount
$reward_amount = 0;
foreach ($awards as $a) {
    $reward_amount += (int)$a['amount'];
}
try {
    $updreward_amount = $pdo->prepare("UPDATE booking SET award_amount = :amt WHERE id = :id");
    $updreward_amount->execute([
        ':amt' => (int)round($reward_amount),
        ':id'  => $booking_id
    ]);
} catch (Throwable $e) {
    // (tuỳ chọn) ghi log lỗi, không chặn luồng hiển thị
    // error_log('Update total_amount failed: ' . $e->getMessage());
}


// 2B) Tổng tiền dịch vụ + update vào database ==> booking.service_amount
$st = $pdo->prepare("SELECT * FROM booking_service_fee WHERE booking_id = :bid ORDER BY created_at DESC");
$st->execute(['bid'=>$booking['id']]);
$services = $st->fetchAll();

$service_total = 0;
foreach ($services as $sv) {
	$service_total += $sv['amount'];
}

try {
    $updservice_total = $pdo->prepare("UPDATE booking SET service_amount = :amt WHERE id = :id");
    $updservice_total->execute([
        ':amt' => (int)round($service_total),
        ':id'  => $booking_id
    ]);
} catch (Throwable $e) {
    // (tuỳ chọn) ghi log lỗi, không chặn luồng hiển thị
    // error_log('Update total_amount failed: ' . $e->getMessage());
}




// 3) Tổng cuối sau khi trừ thưởng (có thể âm) + Lưu total_amount về DB
$final_total = $calc['total_amount'] - $reward_amount +$bk['gia_xe_heo'] - $bk['booking_amount'];
// === Lưu total_amount về DB ===
try {
    $updTotal = $pdo->prepare("UPDATE booking SET total_amount = :amt WHERE id = :id");
    $updTotal->execute([
        ':amt' => (int)round($final_total),
        ':id'  => $booking_id
    ]);
} catch (Throwable $e) {
    // (tuỳ chọn) ghi log lỗi, không chặn luồng hiển thị
    // error_log('Update total_amount failed: ' . $e->getMessage());
}


// 4) (tuỳ chọn) formatter nhãn loại thưởng
if (!function_exists('labelPrizeType')) {
    function labelPrizeType(string $t): string {
        return match ($t) {
            'Thưởng heo'     => '🐷 Thưởng Heo',
            'Xẻ heo'  => '🚗 Xẻ heo',
            'Thưởng xôi'     => '🥣 Thưởng Xôi',
            'Thưởng khoen'   => '🔗 Thưởng Khoen',
            default   => ucfirst($t),
        };
    }
}

// helpers_price.php

if (!function_exists('money_vnd')) {
    function money_vnd(int|float $v): string {
        $neg = $v < 0 ? '-' : '';
        return $neg . number_format(abs($v), 0, ',', '.') . ' đ';
    }
}

/**
 * Tính số suất và phút lẻ.
 * - Mặc định: nếu >0 phút thì tối thiểu 1 suất (đúng khái niệm "giá theo suất").
 * - Nếu bạn muốn cho phép < base_duration vẫn tính theo phút, set $minimumOneSuat=false.
 */
if (!function_exists('calc_suat')) {
    function calc_suat(int $tong_phut, int $base_duration, bool $minimumOneSuat = true): array {
        if ($tong_phut <= 0) return [0, 0];
        $so_suat_raw = intdiv($tong_phut, $base_duration);
        if ($minimumOneSuat) {
            $so_suat = max(1, $so_suat_raw);
            $phut_le = max(0, $tong_phut - $so_suat * $base_duration);
        } else {
            $so_suat = $so_suat_raw;
            $phut_le = $tong_phut % $base_duration;
        }
        return [$so_suat, $phut_le];
    }
}

/**
 * Tính tiền câu cá thịt (theo suất + phút lẻ) + bán/thu cá + trừ đặt cọc.
 * Discount 2x/3x/4x là SỐ TIỀN CỐ ĐỊNH, không phải %.
 *
 * $gia: row từ gia_ca_thit_phut (base_duration, base_price, extra_unit_price,
 *      discount_2x_duration, discount_3x_duration, discount_4x_duration,
 *      gia_ban_ca, gia_thu_lai)
 */
if (!function_exists('calcTienCauThit')) {
    function calcTienCauThit(
        array $gia,
        int $tong_phut,
        float $kg_cau,
        float $kg_ban,
        float $kg_thu,
        bool $repeatDiscountOver4 = false,
        bool $minimumOneSuat = true
    ): array {
        $base_duration = (int)$gia['base_duration'];
        $base_price    = (int)$gia['base_price'];
        $extra_price   = (int)$gia['extra_unit_price'];

        $d2 = (int)$gia['discount_2x_duration'];
        $d3 = (int)$gia['discount_3x_duration'];
        $d4 = (int)$gia['discount_4x_duration'];

        $gia_ban_ca  = (int)$gia['gia_ban_ca'];   // vnd/kg (mang về)
        $gia_thu_lai = (int)$gia['gia_thu_lai'];  // vnd/kg (hồ thu lại)

        // 1) Số suất & phút lẻ
        [$so_suat, $phut_le] = calc_suat($tong_phut, $base_duration, $minimumOneSuat);

        // 2) Tiền theo suất
        $tien_suat = $so_suat * $base_price;

        // 3) Discount theo mốc suất (cố định tiền, không %)
        $discount = 0;
        if ($so_suat >= 4) {
            if ($repeatDiscountOver4) {
                $discount = ($so_suat - 3) * $d4; // 4..n ⇒ áp dụng (n-3) lần
            } else {
                $discount = $d4; // cap 1 lần
            }
        } elseif ($so_suat === 3) {
            $discount = $d3;
        } elseif ($so_suat === 2) {
            $discount = $d2;
        }

        // 4) Tiền thêm phút lẻ
        $tien_them = $phut_le * $extra_price;

        // 5) Thành tiền trước phần cá
        $real_amount_before_fish = $tien_suat + $tien_them - $discount;

        // 6) Tiền cá
        $fish_sell_amount   = $kg_ban * $gia_ban_ca;   // + tiền
        $fish_return_amount = $kg_thu * $gia_thu_lai;  // - tiền

        // 7) Tổng cần thanh toán (âm = hồ trả ngược khách)
        $total_amount = $real_amount_before_fish + $fish_sell_amount - $fish_return_amount - $bk['booking_amount'];

        return [
            'so_suat' => $so_suat,
            'phut_le' => $phut_le,
            'tien_suat' => $tien_suat,
            'tien_them' => $tien_them,
            'discount' => $discount,
            'real_amount_before_fish' => $real_amount_before_fish,
            'fish_sell_amount' => $fish_sell_amount,
            'fish_return_amount' => $fish_return_amount,
            'booking_amount' => $booking_amount,
            'total_amount' => $total_amount,
        ];
    }
}

include __DIR__ . '/../../../includes/header.php';
?>

<div class="container py-4">
    <h4 class="mb-3">Quản lý vé câu #<?= (int)$booking_id ?></h4>

    <!-- Thông tin hồ & booking -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between flex-wrap">
                <div>
                    <div><strong>Hồ:</strong> <?= htmlspecialchars($bk['ten_ho']) ?> (<?= (int)$bk['so_cho_ngoi'] ?> chỗ)</div>
                    <div class="text-muted">
                        <small><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($bk['ten_cum_ho']) ?> — <?= htmlspecialchars($bk['dia_chi']) ?></small>
                    </div>
                </div>
                <div class="text-end">
                    <div><strong>Status:</strong> 
                        <?php if ($bk['booking_status'] === 'Hoàn thành'): ?>
                            <span class="badge bg-success">Hoàn thành</span>
                        <?php elseif ($bk['booking_status'] === 'Đã huỷ'): ?>
                            <span class="badge bg-secondary">Đã huỷ</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark">Đang chạy</span>
                        <?php endif; ?>
                    
					    <strong>Bill:</strong> 
                        <?php if ($bk['payment_status'] === 'Đã thanh toán'): ?>
                            <span class="badge bg-success">Đã thanh toán</span>
                        <?php elseif ($bk['payment_status'] === 'Chưa thanh toán'): ?>
                            <span class="badge bg-warning text-dark">Chưa thanh toán</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark">Đang chạy</span>
                        <?php endif; ?>
							<!--hiện người cầu nếu có--!>		
						</div>
						<div class="text-muted">
						  <small>
							<?= htmlspecialchars($bk['booking_time']) ?>
							<?php if (!empty($bk['ten_nguoi_cau']) || !empty($bk['nick_name'])): ?>
								| <?= htmlspecialchars($bk['ten_nguoi_cau'] ?: '') ?>
								<?= !empty($bk['nick_name']) ? '(' . htmlspecialchars($bk['nick_name']) . ')' : '' ?>
							<?php endif; ?>
						  </small>
						</div>
						</div>
            </div>
        </div>
    </div>

    <?php if ($err): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($err) ?></div>
    <?php elseif ($msg): ?>
        <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

<?php if ($bk['booking_status'] === 'Hoàn thành'): ?>
  <div class="alert alert-success text-center fw-bold fs-4 py-3">
    🎉 Booking đã hoàn tất
  </div>
<?php else: ?>
    <div class="row g-4">
	
	<!-- VỊ TRÍ -->
	<div class="col-lg-6 mb-3">
	  <div class="card border-3 shadow-sm">
		<div class="card-header fw-bold">Vị trí</div>
		<div class="card-body">
		  <h6 class="mb-3">Chọn ngẫu nhiên vị trí từ hệ thống</h6>
		  <form method="post" class="d-flex gap-2">
			<input type="hidden" name="action" value="save_seat">
			<input type="number" min="1" max="<?= (int)$bk['so_cho_ngoi'] ?>" name="seat" id="seatInput"
				   class="form-control" placeholder="Nhập vị trí (1-<?= (int)$bk['so_cho_ngoi'] ?>)">
			<button type="button" class="btn btn-outline-secondary" 
					onclick="randomSeat(<?= (int)$bk['so_cho_ngoi'] ?>)">Random</button>
			<button class="btn btn-success" <?= $hasSeatCol ? '' : 'disabled' ?>>Lưu</button>
		  </form>

		  <?php if (!$hasSeatCol): ?>
			<div class="alert alert-warning mt-3">
			  DB chưa có cột <code>booking.vi_tri</code>. 
			  Nếu muốn lưu vị trí, thêm: <code>ALTER TABLE booking ADD COLUMN vi_tri INT NULL;</code>
			</div>
		  <?php else: ?>
			<p class="mt-3 mb-0">
			  <?php if ($bk['vi_tri'] !== null && $bk['vi_tri'] !== ''): ?>
				Vị trí của bạn là <span class="badge bg-danger fs-6"><?= (int)$bk['vi_tri'] ?></span>
			  <?php else: ?>
				<span class="text-muted">Chưa chọn vị trí.</span>
			  <?php endif; ?>
			</p>
		  <?php endif; ?>
		</div>
	  </div>
	</div>
        <!-- Bảng Giá -->
<div class="col-lg-6 mb-3">
  <div class="card border-3 shadow-sm">
    <div class="card-header fw-bold">Bảng giá</div>
    <div class="card-body">
      <?php if (empty($gia_list)): ?>
        <div class="alert alert-warning mb-0">
          Chưa cấu hình bảng giá cho hồ này. Hãy tạo bảng giá trước khi tính tiền.
        </div>
      <?php else: ?>
		<h6 class="mb-3">Hồ câu có nhiều bảng giá, giá vé cao thì thu giá cao!</h6>
        <form method="post" class="d-flex gap-2">
          <input type="hidden" name="action" value="update_gia">
          <select name="gia_id" class="form-select" required>
            <?php foreach ($gia_list as $g): ?>
              <option value="<?= (int)$g['id'] ?>"
                <?= ((int)$bk['gia_id'] === (int)$g['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($g['label']) ?> (ID: <?= (int)$g['id'] ?>)
              </option>
            <?php endforeach; ?>
          </select>
          <button class="btn btn-success">Lưu</button>
        </form>

        <div class="mt-2 text-muted">
          <small>
            Đơn giá hiện tại:
            Thu lại: <strong><?= $gia_prices['thu_kg'] ? number_format($gia_prices['thu_kg']).' đ/kg' : '—' ?></strong> ·
            Bán cá: <strong><?= $gia_prices['ban_kg'] ? number_format($gia_prices['ban_kg']).' đ/kg' : '—' ?></strong>
          </small>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
	

        <!-- Thời gian thực tế -->
<div class="col-lg-6 mb-3">
  <div class="card border-3 shadow-sm">
    <div class="card-header fw-bold">Thời gian thực tế</div>
    <div class="card-body">
      <form method="post" class="row g-2">
        <input type="hidden" name="action" value="update_times">

        <div class="col-12">
          <label class="form-label mb-1">Bắt đầu</label>
          <div class="d-flex gap-2">
            <input type="datetime-local" name="start_at" id="start_at" class="form-control"
                   value="<?= $bk['real_start_time'] ? date('Y-m-d\TH:i', strtotime($bk['real_start_time'])) : '' ?>">
            <button type="button" class="btn btn-outline-secondary" onclick="setNow('start_at')">Now</button>
            <button type="button" class="btn btn-outline-secondary" onclick="clearInput('start_at')">Clear</button>
          </div>
        </div>

        <div class="col-12">
          <label class="form-label mb-1">Kết thúc</label>
          <div class="d-flex gap-2">
            <input type="datetime-local" name="end_at" id="end_at" class="form-control"
                   value="<?= $bk['real_end_time'] ? date('Y-m-d\TH:i', strtotime($bk['real_end_time'])) : '' ?>">
            <button type="button" class="btn btn-outline-secondary" onclick="setNow('end_at')">Now</button>
            <button type="button" class="btn btn-outline-secondary" onclick="clearInput('end_at')">Clear</button>
          </div>
        </div>
		 <div class="mt-3 text-muted">
			Thời lượng đã câu:<span class="badge bg-danger fs-6"><?= (int)$bk['real_tong_thoi_luong'] ?></span> Phút
		 </div>
        <div class="col-12 mt-2 d-grid">
          <button class="btn btn-success">Lưu thời gian</button>
        </div>
      </form>


    </div>
  </div>
</div>


	    <!-- CÁ / TIỀN CÁ -->
<div class="col-lg-6 mb-3">
  <div class="card border-3 shadow-sm">
    <div class="card-header fw-bold">Cá & tiền cá</div>
    <div class="card-body">
      <form method="post" class="row g-2">
        <input type="hidden" name="action" value="save_fish">

        <div class="col-6">
          <label class="form-label">Kg câu được</label>
          <input type="number" step="0.1" class="form-control" name="fish_weight"
                 id="fish_weight" value="<?= htmlspecialchars($bk['fish_weight']) ?>">
        </div>

        <div class="col-6">
          <label class="form-label">Thu lại (đ)</label>
          <input type="number" step="1" class="form-control" name="fish_return_amount"
                 id="fish_return_amount" value="<?= htmlspecialchars($bk['fish_return_amount']) ?>">
        </div>

        <div class="col-6">
          <label class="form-label">Bán cá (kg)</label>
          <input type="number" step="0.1" class="form-control" name="fish_sell_weight"
                 id="fish_sell_weight" value="<?= htmlspecialchars($bk['fish_sell_weight']) ?>">
        </div>
        <div class="col-6">
          <label class="form-label">Tiền bán cá (đ)</label>
          <input type="number" step="1" class="form-control" name="fish_sell_amount"
                 id="fish_sell_amount" value="<?= htmlspecialchars($bk['fish_sell_amount']) ?>">
        </div>

        <div class="col-12 d-flex align-items-center gap-2 mt-2">
          <input class="form-check-input" type="checkbox" name="auto_calc" id="auto_calc" checked>
          <label class="form-check-label" for="auto_calc">
            Nếu bỏ tick “Tự tính” ==> nhập tiền thủ công.
          </label>
        </div>

        <div class="col-12 mt-2">
          <button class="btn btn-success w-100">Lưu cá/tiền cá</button>
        </div>
      </form>
    </div>
  </div>
</div>

			<!-- THƯỞNG -->
	<div class="col-lg-12 mb-3">
	  <div class="card border-3 shadow-sm" id="tab-prize">
		<div class="card-header fw-bold">🧾 Thưởng heo / khoen / xôi</div>
		<div class="card-body">
		  <form method="post" class="row g-2 align-items-end">
			<input type="hidden" name="action" value="add_prize">
			<div class="col-sm-3">
			  <label class="form-label">Loại</label>
			  <select name="prize_type" class="form-select" required>
				<option value="Thưởng xôi">Thưởng xôi</option>
				<option value="Thưởng khoen">Thưởng khoen</option>
				<option value="Xẻ heo">Xẻ heo</option>
				<option value="Thưởng heo">Thưởng heo</option>
			  </select>
			</div>
			<div class="col-sm-3">
			  <label class="form-label">Số tiền (đ)</label>
			  <input type="number" name="amount" class="form-control" required min="1000" step="1000" placeholder="vd 50000">
			</div>
			<div class="col-sm-3">
			  <label class="form-label">Ghi chú</label>
			  <input type="text" name="note" class="form-control" placeholder="(tuỳ chọn)">
			</div>
			<div class="col-sm-3">
			  <button class="btn btn-success w-100 "> +Thưởng </button>
			</div>
		  </form>

      <?php if (!empty($awards)): ?>
        <div class="table-responsive mt-3">
          <table class="table table-sm align-middle">
            <thead>
              <tr>
                <th>Thời gian</th>
                <th>Loại</th>
                <th >Số tiền</th>
                <th>Ghi chú</th>
                <th class="text-end">Hành Động</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($awards as $a): ?>
                <tr>
                  <td><?= htmlspecialchars($a['created_at']) ?></td>
                  <td><?= htmlspecialchars($a['prize_type']) ?></td>
                  <td><?= number_format((int)$a['amount']) ?> đ</td>
                  <td><?= htmlspecialchars($a['note'] ?? '') ?></td>
                  <td class="text-end">
                    <form method="post" onsubmit="return confirm('Xoá thưởng này?');">
                      <input type="hidden" name="action" value="delete_prize">
                      <input type="hidden" name="award_id" value="<?= (int)$a['id'] ?>">
                      <button class="btn btn-outline-danger btn-sm text-end">− Thưởng (xoá)</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="text-muted mt-3">Chưa có thưởng nào.</div>
      <?php endif; ?>
    </div>
  </div>
</div>

				<!-- Dịch Vụ tại hồ -->

<div class="col-lg-12 mb-2">
  <div class="card border-3 shadow-sm" id="tab-service">
    <div class="card-header fw-bold">🧾 Dịch vụ tại hồ</div>
    <div class="card-body">
	  <div class="text-muted mt-1 mb-1">Các dịch cần thủ đã dùng tại hồ</div>
      <form method="post" action="booking_service_add.php" class="row g-2">
        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
        <input type="hidden" name="ho_cau_id" value="<?= $booking['ho_cau_id'] ?>">
        <div class="col-md-3">
          <select name="service_type" class="form-select" required>
            <option value="">-- Loại dịch vụ --</option>
            <option value="Thuốc lá">Thuốc lá</option>
            <option value="Nước">Nước</option>
            <option value="Cơm">Cơm</option>
            <option value="Mỳ">Mỳ</option>
            <option value="Đồ ăn">Đồ ăn</option>
            <option value="Mồi câu">Mồi câu</option>
            <option value="Đồ câu">Đồ câu</option>
          </select>
        </div>
        <div class="col-md-2">
          <input type="number" name="qty" step="0.01" value="1" class="form-control" placeholder="SL" required>
        </div>
        <div class="col-md-2">
          <input type="number" name="unit_price" step="100" class="form-control" placeholder="Đơn giá" required>
        </div>
        <div class="col-md-3">
          <input type="text" name="note" class="form-control" placeholder="Ghi chú">
        </div>
        <div class="col-md-2">
          <button class="btn btn-sm btn-success w-100">+ Thêm</button>
        </div>
      </form>

      <div class="table-responsive mt-3">
	        <?php if (!empty($services)): ?>
        <table class="table table-sm align-middle">
          <thead>
            <tr class="text-center">
              <th>#</th>
              <th>Loại</th>
              <th>SL</th>
              <th>Đơn giá</th>
              <th>Thành tiền</th>
              <th>Ghi chú</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($services as $i=>$sv): ?>
            <tr>
              <td class="text-center"><?= $i+1 ?></td>
              <td class="text-center"><?= htmlspecialchars($sv['service_type']) ?></td>
              <td class="text-center"><?= $sv['qty'] ?></td>
              <td class="text-center"><?= number_format($sv['unit_price']) ?> đ</td>
              <td class="text-center fw-bold"><?= number_format($sv['amount']) ?> đ</td>
              <td><?= htmlspecialchars($sv['note']) ?></td>
              <td class="text-center">
                <form method="post" action="booking_service_delete.php" onsubmit="return confirm('Xoá dịch vụ này?')">
                  <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                  <input type="hidden" name="id" value="<?= $sv['id'] ?>">
                  <button class="btn btn-sm btn-outline-danger">Xoá</button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="4" class="text-end">Tổng dịch vụ</th>
              <th class="text-end text-primary"><?= number_format($service_total) ?> đ</th>
              <th colspan="2"></th>
            </tr>
          </tfoot>
        </table>
	  <?php else: ?>
        <div class="text-muted mt-3">Chưa có dịch vụ nào.</div>
      <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>	

<!-- Review và tính tiền -->
<div class="card border-3 shadow-sm" id="tab-review">
  <div class="card-header fw-bold d-flex justify-content-between align-items-center">
    <span>Tính tiền & Review</span>
    <small class="text-muted">
      Suất: <?= (int)$gia['base_duration'] ?>’ · Giá suất: <?= money_vnd($gia['base_price']) ?> ·
      Thêm: <?= money_vnd($gia['extra_unit_price']) ?>/phút
    </small>
  </div>

  <div class="card-body mt-1 mb-1">
    <div class="table-responsive">
      <table class="table table-sm align-middle">
        <tbody>
          <tr>
            <th class="w-50">
              <?= $calc['so_suat'] ?> suất (<?= $gia['base_duration']?> phút) × <?= money_vnd($gia['base_price']) ?>
            </th>
            <td class="text-end fw-bold"><?= money_vnd($calc['tien_suat']) ?></td>
          </tr>

          <tr>
            <th>Phút lẻ: <?= $calc['phut_le'] ?> Phút × <?= money_vnd($gia['extra_unit_price']) ?></th>
            <td class="text-end text-success">+<?= money_vnd($calc['tien_them']) ?></td>
          </tr>

          <tr>
            <th>
              Giảm giá suất câu:
              <div class="small text-muted">
                2 suất: - <?= money_vnd($gia['discount_2x_duration']) ?> |
                3 suất: - <?= money_vnd($gia['discount_3x_duration']) ?> |
                4 suất: - <?= money_vnd($gia['discount_4x_duration']) ?>
              </div>
            </th>
            <td class="text-end text-danger">-<?= money_vnd($calc['discount']) ?></td>
          </tr>
		  
          <tr class="table-light">
            <th>Tiền vé câu</th>
            <td class="text-end fw-bold"><?= money_vnd($calc['real_amount_before_fish']) ?></td>
          </tr>

          <tr class="table-light">
            <th>Thu phí heo / cần</th>
            <td class="text-end text-success">+<?= money_vnd($bk['gia_xe_heo']) ?></td>
          </tr>

          <tr class="table-light">
            <th>Tiền dịch vụ (cơm, nước, mồi...)</th>
            <td class="text-end text-success">+<?= money_vnd($service_total) ?></td>
          </tr>

          <tr>
            <th>Tiền bán cá (<?= money_vnd($gia['gia_ban_ca']) ?> /kg) × <?= $bk['fish_sell_weight'] ?> kg</th>
            <td class="text-end text-success">+<?= money_vnd($calc['fish_sell_amount']) ?></td>
          </tr>

          <tr>
            <th>Tiền thu lại cá (<?= money_vnd($gia['gia_thu_lai']) ?> /kg) × <?= $bk['fish_weight'] ?> kg</th>
            <td class="text-end text-danger">-<?= money_vnd($calc['fish_return_amount']) ?></td>
          </tr>

          <tr>
            <th>Tiền thưởng (heo/xôi/khoen)</th>
            <td class="text-end text-danger">-<?= money_vnd($reward_amount) ?></td>
          </tr>

          <tr>
            <th>Tiền cọc booking online</th>
            <td class="text-end text-danger">-<?= money_vnd($bk['booking_amount'] ?? 0) ?></td>
          </tr>

          <tr class="table-primary">
            <th class="fs-5">Cần thanh toán</th>
            <td class="text-end fs-5 fw-bold">
              <?= money_vnd($final_total) ?>
              <?php if ($final_total < 0): ?>
                <span class="badge bg-warning text-dark ms-2">Hồ trả khách</span>
              <?php endif; ?>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="d-flex gap-2 justify-content-end">
	  <a href="chu_ho_booking_list_all.php" class="btn btn-primary">Tất cả vé câu</a>
      <a href="booking_detail.php?id=<?= (int)$booking['id'] ?>" class="btn btn-secondary">Refresh Vé Câu</a>
      <a href="booking_finalize_step1.php?id=<?= (int)$booking['id'] ?>" class="btn btn-success">
        >> Xem Thanh toán: tiền mặt, chuyển khoản...
      </a>
    </div>
  </div><!-- /.card-body -->
</div><!-- /.card -->


</div>
</div>
<script>
function randomSeat(maxSeat) {
    if (!maxSeat || maxSeat < 1) return;
    const n = Math.floor(Math.random() * maxSeat) + 1; // 1..max
    document.getElementById('seatInput').value = n;
}
</script>


<script>
function pad(n){ return n.toString().padStart(2,'0'); }
function toLocalDTValue(d){
  return d.getFullYear() + '-' + pad(d.getMonth()+1) + '-' + pad(d.getDate()) +
         'T' + pad(d.getHours()) + ':' + pad(d.getMinutes());
}
function setNow(id){
  document.getElementById(id).value = toLocalDTValue(new Date());
}
function clearInput(id){
  document.getElementById(id).value = '';
}
</script>


<?php include __DIR__ . '/../../../includes/footer.php'; ?>
