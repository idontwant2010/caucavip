<?php
// Cộng tiền chủ hồ + ghi log: user_balance_logs


require_once '../../../connect.php';
require_once '../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'chuho') {
    die('Bạn không có quyền thực hiện thao tác này.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $giai_id = (int)($_POST['giai_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    // Lấy thông tin giải
    $sql = "
        SELECT g.*, ch.chu_ho_id
        FROM giai_list g
        JOIN ho_cau h ON g.ho_cau_id = h.id
        JOIN cum_ho ch ON h.cum_ho_id = ch.id
        WHERE g.id = :id
        LIMIT 1
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $giai_id]);
    $giai = $stmt->fetch();

    if (!$giai || $giai['chu_ho_id'] != $_SESSION['user']['id']) {
        die('Giải không tồn tại hoặc bạn không có quyền.');
    }

    if ($giai['status'] !== 'chuyen_chu_ho_duyet') {
        die('Giải không ở trạng thái cần duyệt.');
    }

    // ========== DUYỆT ==========
    if ($action === 'accept') {
        $stmt = $pdo->prepare("UPDATE giai_list SET status = 'dang_mo_dang_ky' WHERE id = :id");
        $stmt->execute(['id' => $giai_id]);

        $stmt = $pdo->prepare("
            INSERT INTO giai_log (giai_id, user_id, action, note)
            VALUES (:giai_id, :user_id, 'duyet_chuho', 'Chủ hồ đã duyệt mở giải.')
        ");
        $stmt->execute([
            'giai_id' => $giai_id,
            'user_id' => $_SESSION['user']['id']
        ]);

// --- CỘNG PHÍ HỒ CHO CHỦ HỒ KHI DUYỆT GIẢI ---

// 1) Lấy chủ hồ qua chuỗi join: giai_list -> ho_cau -> cum_ho
$stInfo = $pdo->prepare("
  SELECT g.id        AS giai_id,
         g.phi_ho    AS phi_ho,
         g.ho_cau_id AS ho_cau_id,
         h.cum_ho_id AS cum_ho_id,
         c.chu_ho_id AS chu_ho_id
  FROM giai_list g
  JOIN ho_cau  h ON h.id = g.ho_cau_id
  JOIN cum_ho  c ON c.id = h.cum_ho_id
  WHERE g.id = ?
  FOR UPDATE
");
$stInfo->execute([$giai_id]);
$info = $stInfo->fetch(PDO::FETCH_ASSOC);
if (!$info) {
  throw new Exception("Không tìm thấy giải #$giai_id để cộng phí hồ");
}

$ownerId = (int)($info['chu_ho_id'] ?? 0);
$phiHo   = (int)($info['phi_ho'] ?? 0);



// 2) Nếu có chủ hồ & có phí hồ thì cộng balance + ghi log
if ($ownerId > 0 && $phiHo > 0) {
  // 2.1) Khóa số dư hiện tại của chủ hồ
  $stU = $pdo->prepare("SELECT balance FROM users WHERE id = ? FOR UPDATE");
  $stU->execute([$ownerId]);
  $u = $stU->fetch(PDO::FETCH_ASSOC);
  if (!$u) {
    throw new Exception("Không tìm thấy chủ hồ #$ownerId");
  }

  $before = (float)$u['balance'];
  $after  = $before + (float)$phiHo;

  // 2.2) Cộng tiền cho chủ hồ
  $up = $pdo->prepare("UPDATE users SET balance = ? WHERE id = ?");
  $up->execute([$after, $ownerId]);

  // 2.3) Ghi user_balance_logs đúng schema hiện tại
  //  - type dùng 'giai_received' (enum đã có)
  //  - change_amount = amount = phi_ho
  //  - ref_no: đặt theo chuẩn tham chiếu giải
  $insLog = $pdo->prepare("
    INSERT INTO user_balance_logs
      (user_id, change_amount, type, amount, note, created_at, ref_no, balance_before, balance_after)
    VALUES
      (?, ?, 'giai_received', ?, ?, NOW(), ?, ?, ?)
  ");
  $insLog->execute([
    $ownerId,
    $phiHo,
    $phiHo,
    "Cộng phí hồ khi DUYỆT giải #{$info['giai_id']} (hồ #{$info['ho_cau_id']}) số dư sau " . number_format($after, 0, ',', '.') . " vnd  ",
    "giai_{$info['giai_id']}",
    $before,
    $after
  ]);
}
// --- HẾT: CỘNG PHÍ HỒ CHO CHỦ HỒ ---


        header("Location: giai_can_duyet.php");
        exit;
    }

    // ========== TỪ CHỐI ==========
    elseif ($action === 'reject') {
        $phi_giai = (int) $giai['phi_giai'];
        $creator_id = (int) $giai['creator_id'];

        if ($phi_giai > 0) {
            // Lấy balance trước
            $stmt = $pdo->prepare("SELECT balance FROM users WHERE id = :id");
            $stmt->execute(['id' => $creator_id]);
            $balance_before = (float) $stmt->fetchColumn();

            // Cộng tiền
            $stmt = $pdo->prepare("UPDATE users SET balance = balance + :amount WHERE id = :id");
            $stmt->execute(['amount' => $phi_giai, 'id' => $creator_id]);

            $balance_after = $balance_before + $phi_giai;
			$user_id = $giai['chu_ho_id'];

	// Ghi  user_balance_logs
	$stmt = $pdo->prepare("
		INSERT INTO user_balance_logs (
			user_id, ref_no, type, amount, note, created_at,
			balance_before, balance_after
		) VALUES (
			?, ?, 'giai_refund', ?, ?, NOW(), ?, ?
		)
	");

	$note = "hoàn phí tổ chức giải ID #$giai_id cho người tổ chức, số dư sau " . number_format($balance_after, 0, ',', '.') . " vnd";

	$stmt->execute([
		$creator_id,
		"giai_$giai_id",     // ref_no
		$phi_giai,
		$note,
		$balance_before,       // balance_before
		$balance_after       // balance_after
	]);
        }

        // Đổi trạng thái về 'dang_cho_xac_nhan'
        $stmt = $pdo->prepare("UPDATE giai_list SET status = 'dang_cho_xac_nhan' WHERE id = :id");
        $stmt->execute(['id' => $giai_id]);

        // Ghi giai_log
        $stmt = $pdo->prepare("
            INSERT INTO giai_log (giai_id, user_id, action, note)
            VALUES (:giai_id, :user_id, 'huy_chuho', 'Chủ hồ từ chối duyệt – hoàn tiền cho người tạo')
        ");
        $stmt->execute([
            'giai_id' => $giai_id,
            'user_id' => $_SESSION['user']['id']
        ]);

        header("Location: giai_can_duyet.php");
        exit;
    }

    else {
        die('Hành động không hợp lệ.');
    }
}
