<?php

// Trừ balance người tham gia
// Cộng balance chủ tổ chức giải
// Ghi 2 logs Creator và user

// ===== DEBUG =====
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// =================

require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

function back_link($msg = 'Có lỗi xảy ra.') {
    echo "<div style='padding:16px;font-family:system-ui'>
            <h3>$msg</h3>
            <a href='giai_list.php'>&laquo; Quay lại danh sách giải</a>
          </div>";
    exit;
}

if (!isset($_SESSION['user']['vai_tro']) || $_SESSION['user']['vai_tro'] !== 'canthu') {
    header("Location: /");
    exit;
}

$user_id = (int)$_SESSION['user']['id'];
$giai_id = (int)($_POST['giai_id'] ?? $_GET['giai_id'] ?? 0);
if ($giai_id <= 0) back_link("Thiếu giai_id.");

function redirect_to_topup($giai_id) {
    $return = urlencode("/cauca/canthu/giai/giai_invite_accept.php?giai_id={$giai_id}");
    // Redirect HTTP (fallback nếu header đã gửi)
    $url = "/wallet/topup.php?return_url={$return}";
    if (!headers_sent()) {
        header("Location: $url");
        exit;
    } else {
        echo "<a href='$url'>Số dư không đủ. Nhấn để nạp tiền</a>";
        exit;
    }
}

try {
    $pdo->beginTransaction();

    // 1) Verify lời mời
    $stmt = $pdo->prepare("
        SELECT * FROM giai_user 
        WHERE giai_id = ? AND user_id = ? AND trang_thai = 'moi_cho_phan_hoi'
        FOR UPDATE
    ");
    $stmt->execute([$giai_id, $user_id]);
    $invite = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$invite) throw new Exception("Không tìm thấy lời mời hợp lệ hoặc đã xử lý.");

    // 2) Lấy thông tin giải
    $stmt = $pdo->prepare("
        SELECT id, ten_giai, so_luong_can_thu, thoi_gian_dong_dang_ky, status, tien_cuoc, creator_id
        FROM giai_list
        WHERE id = ?
        FOR UPDATE
    ");
    $stmt->execute([$giai_id]);
    $giai = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$giai || $giai['status'] !== 'dang_mo_dang_ky') {
        throw new Exception("Giải không còn mở đăng ký.");
    }
    $now = $pdo->query("SELECT NOW() AS now_ts")->fetch(PDO::FETCH_ASSOC)['now_ts'];
    if ($giai['thoi_gian_dong_dang_ky'] < $now) throw new Exception("Đã quá hạn phản hồi lời mời.");

    // 3) Còn chỗ?
    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM giai_user 
        WHERE giai_id = ? AND trang_thai = 'da_thanh_toan'
    ");
    $stmt->execute([$giai_id]);
    $so_da_thanh_toan = (int)$stmt->fetchColumn();
    if ($so_da_thanh_toan >= (int)$giai['so_luong_can_thu']) {
        throw new Exception("Giải đã đủ số lượng.");
    }

    // 4) Số dư user
    $stmt = $pdo->prepare("SELECT id, balance FROM users WHERE id = ? FOR UPDATE");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) throw new Exception("Không tìm thấy người dùng.");

    $amount = (int)$giai['tien_cuoc'];
    if ($amount <= 0) throw new Exception("Phí tham gia không hợp lệ.");

    $balance_before_user = (float)$user['balance'];
    if ($balance_before_user < $amount) {
        $pdo->rollBack();
        redirect_to_topup($giai_id);
    }

    // 5) Trừ user
    $balance_after_user = $balance_before_user - $amount;
    $stmt = $pdo->prepare("UPDATE users SET balance = ? WHERE id = ?");
    $stmt->execute([$balance_after_user, $user_id]);

    // 6) Log user (giai_pay)
    $stmt = $pdo->prepare("
        INSERT INTO user_balance_logs
            (user_id, type, amount, note, ref_no, balance_before, balance_after, created_at)
        VALUES
            (?, 'giai_pay', ?, ?, ?, ?, ?, NOW())
    ");
    $note_user = "Chấp nhận mời & thanh toán giải #{$giai_id} ({$giai['ten_giai']}) - Số dư sau: " 
           . number_format($balance_after_user, 0, ',', '.') . " đ";
    $ref_no = "giai_{$giai_id}";
    $stmt->execute([
        $user_id, $amount, $note_user, $ref_no, $balance_before_user, $balance_after_user
    ]);

    // 7) Cộng cho creator (nếu có)
    $creator_id = (int)$giai['creator_id'];
    if ($creator_id > 0) {
        $stmt = $pdo->prepare("SELECT id, balance FROM users WHERE id = ? FOR UPDATE");
        $stmt->execute([$creator_id]);
        if ($creator = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $balance_before_creator = (float)$creator['balance'];
            $balance_after_creator  = $balance_before_creator + $amount;

            $stmt = $pdo->prepare("UPDATE users SET balance = ? WHERE id = ?");
            $stmt->execute([$balance_after_creator, $creator_id]);

            $stmt = $pdo->prepare("
                INSERT INTO user_balance_logs
                    (user_id, type, amount, note, ref_no, balance_before, balance_after, created_at)
                VALUES
                    (?, 'giai_received', ?, ?, ?, ?, ?, NOW())
            ");
            $note_creator = "Cần thủ #{$user_id} chấp nhận lời mời tham gia giải #{$giai_id}, đã thanh toán phí!- Số dư sau: " 
           . number_format($balance_after_creator, 0, ',', '.') . " đ";
            $stmt->execute([
                $creator_id, $amount, $note_creator, $ref_no, $balance_before_creator, $balance_after_creator
            ]);
        }
    }

    // 8) Update trạng thái lời mời
		$stmt = $pdo->prepare("
			UPDATE giai_user
			SET trang_thai = 'da_thanh_toan',
				note = CONCAT(COALESCE(note,''),' và tham gia')
			WHERE giai_id = ? AND user_id = ? AND trang_thai = 'moi_cho_phan_hoi'
			LIMIT 1
		");
    $stmt->execute([$giai_id, $user_id]);
    if ($stmt->rowCount() === 0) throw new Exception("Không thể chuyển sang 'da_thanh_toan'.");

    $pdo->commit();

    // Redirect thành công
    if (!headers_sent()) {
        header("Location: giai_list.php?msg=accepted_paid");
        exit;
    } else {
        echo "<div style='padding:16px'>Thành công. <a href='giai_list.php?msg=accepted_paid'>Quay lại</a></div>";
        exit;
    }

} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    back_link("Lỗi: " . htmlspecialchars($e->getMessage()));
}
