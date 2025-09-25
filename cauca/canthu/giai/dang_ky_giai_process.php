<?php
// Trừ balance người tham gia
// Cộng balance chủ tổ chức giải
// Ghi 2 logs Creator và user

require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if (!isset($_SESSION['user'])) {
    http_response_code(401);
    exit('Bạn cần đăng nhập');
}

$user = $_SESSION['user'];
$user_id = (int)$user['id'];
$giai_id = isset($_POST['giai_id']) ? (int)$_POST['giai_id'] : 0;
// (Nếu có CSRF token thì kiểm tra ở đây)

if ($giai_id <= 0) {
    exit('Thiếu hoặc sai giai_id');
}

try {
    // 1) Lấy thông tin giải (chỉ cho phép giải còn mở đăng ký & chưa quá hạn)
    $sqlGiai = "
        SELECT id, ten_giai, so_luong_can_thu, tien_cuoc, thoi_gian_dong_dang_ky, status, creator_id
        FROM giai_list
        WHERE id = ?
          AND status = 'dang_mo_dang_ky'
          AND thoi_gian_dong_dang_ky >= NOW()
        FOR UPDATE
    ";
    $stmt = $pdo->prepare($sqlGiai);
    $stmt->execute([$giai_id]);
    $giai = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$giai) {
        exit('Giải không tồn tại, đã đóng đăng ký hoặc không ở trạng thái mở.');
    }

    $tien_cuoc = (int)$giai['tien_cuoc'];
    $suc_chua = (int)$giai['so_luong_can_thu'];

    // 2) Chống đăng ký trùng
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM giai_user WHERE giai_id = ? AND user_id = ?");
    $stmt->execute([$giai_id, $user_id]);
    if ((int)$stmt->fetchColumn() > 0) {
        exit('Bạn đã đăng ký giải này rồi.');
    }

    // 3) Kiểm tra còn chỗ không (đếm mọi người đã có slot, loại trừ bị loại/từ chối)
    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM giai_user 
        WHERE giai_id = ?
          AND trang_thai NOT IN ('tu_choi','bi_loai')
    ");
    $stmt->execute([$giai_id]);
    $so_da_dang_ky = (int)$stmt->fetchColumn();
    if ($so_da_dang_ky >= $suc_chua) {
        exit('Giải đã đủ số lượng cần thủ.');
    }

    // 4) Bắt đầu giao dịch: trừ tiền & thêm bản ghi
    $pdo->beginTransaction();

    // 4.1) Khóa số dư người dùng (giả sử cột là `balance` trong bảng `users`)
    $stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ? FOR UPDATE");
    $stmt->execute([$user_id]);
    $rowUser = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$rowUser) {
        $pdo->rollBack();
        exit('Không tìm thấy người dùng.');
    }

    $balance_before = (int)$rowUser['balance'];
    if ($balance_before < $tien_cuoc) {
        $pdo->rollBack();
        exit('Số dư không đủ để đăng ký.');
    }
    $balance_after = $balance_before - $tien_cuoc;

    // 4.2) Trừ tiền User (cần thủ)
    $stmt = $pdo->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
    $stmt->execute([$tien_cuoc, $user_id]);

	// 4.3) Ghi logs số dư theo đúng schema user_balance_logs
	$ref_no = 'giai_' . $giai_id; // theo convention trong bảng logs
	$type   = 'giai_pay';         // enum hiện có: nap,rut,booking_*,game_*,giai_pay,...
	$amount = $tien_cuoc;         // số tiền bị trừ (để dương, giống convention các dòng giai_pay)

	$note = "Đăng ký online tham gia giải ID #{$giai_id} - {$giai['ten_giai']}. "
		  . "Số dư sau: " . number_format($balance_after, 0, ',', '.') . "đ";

	$stmt = $pdo->prepare("
		INSERT INTO user_balance_logs
			(user_id, type, amount, note, created_at, ref_no, balance_before, balance_after)
		VALUES
			(?, ?, ?, ?, NOW(), ?, ?, ?)
	");
	$stmt->execute([
		$user_id,
		$type,
		$amount,
		$note,
		$ref_no,
		$balance_before,
		$balance_after
	]);
	
    // 4B) Cộng cho creator (nếu có)
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
            $note_creator = "Cần thủ #{$user_id} đã tham gia Online giải #{$giai_id}, đã thanh toán phí. Số dư sau: " 
           . number_format($balance_after_creator, 0, ',', '.') . " đ";
            $stmt->execute([
                $creator_id, $amount, $note_creator, $ref_no, $balance_before_creator, $balance_after_creator
            ]);
        }
    }

    // 4.4) Thêm vào giai_user: đã thanh toán ngay
			// Lấy nickname 1 lần (nếu chưa có)
			$st = $pdo->prepare("SELECT nickname, full_name, phone FROM users WHERE id = ?");
			$st->execute([$user_id]);
			$u = $st->fetch(PDO::FETCH_ASSOC);

			// Fallback chống null/rỗng
			$nickname = $u['nickname'];
			if (!$nickname || trim($nickname) === '') {
			  $nickname = $u['full_name'] && trim($u['full_name']) !== '' ? $u['full_name']
						: ($u['phone'] ?: ('User_' . (int)$user_id));
			}

			$stmt = $pdo->prepare("
			  INSERT INTO giai_user
				(giai_id, user_id, nickname, trang_thai, payment_time, note, tong_diem, tong_kg, xep_hang, created_at)
			  VALUES
				(?, ?, ?, 'da_thanh_toan', NOW(), 'Tham gia online', 0, 0, 0, NOW())
			");
			$stmt->execute([$giai_id, $user_id, $nickname]);

    $pdo->commit();

    // 5) Thành công
    echo "<script>
        alert('Đăng ký thành công! Bạn đã được ghi nhận và trừ phí tham gia.');
        window.location.href = 'my_giai_list_join.php';
    </script>";
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    // Ghi log lỗi server nếu cần
    http_response_code(500);
    echo 'Có lỗi xảy ra: ' . htmlspecialchars($e->getMessage());
}
