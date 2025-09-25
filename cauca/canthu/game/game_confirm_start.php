<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'canthu') {
    header("Location: /");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['game_id'])) {
    echo "<script>alert('Truy cập không hợp lệ.'); window.location.href='dashboard_game.php';</script>";
    exit;
}

$user_id = $_SESSION['user']['id'];
$game_id = (int)$_POST['game_id'];

// Lấy game + số dư user
$stmt = $pdo->prepare("SELECT gl.*, hc.ten_ho, u.balance FROM game_list gl
    JOIN ho_cau hc ON gl.ho_cau_id = hc.id
    JOIN users u ON gl.creator_id = u.id
    WHERE gl.id = ? AND gl.creator_id = ?");
$stmt->execute([$game_id, $user_id]);
$game = $stmt->fetch();

if (!$game) {
    echo "<script>alert('Không tìm thấy game hoặc bạn không có quyền.'); window.history.back();</script>";
    exit;
}

if ($game['status'] !== 'cho_xac_nhan') {
    echo "<script>alert('Game đã được xác nhận hoặc đã kết thúc.'); window.history.back();</script>";
    exit;
}

// Lấy VAT từ cấu hình
$stmt_vat = $pdo->prepare("SELECT config_value FROM admin_config_keys WHERE config_key = 'game_vat_percent' LIMIT 1");
$stmt_vat->execute();
$vat_percent = (int)($stmt_vat->fetchColumn() ?? 10);

$phi_game = (int)$game['tong_phi_game'];
$phi_vat = round($phi_game * $vat_percent / 100);
$tong_tru = $phi_game + $phi_vat;

if ((float)$game['balance'] < $tong_tru) {
    echo "<script>alert('Số dư không đủ để bắt đầu game. Cần $tong_tru đ'); window.history.back();</script>";
    exit;
}

// Trừ tiền và ghi log
$pdo->beginTransaction();
try {
    // Trừ số dư
    $stmt = $pdo->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
    $stmt->execute([$tong_tru, $user_id]);

    // Ghi log giao dịch
    $stmt = $pdo->prepare("INSERT INTO user_balance_logs (user_id, amount, type, note) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, -$tong_tru, 'game_pay', 'Trừ tiền tạo game ID #' . $game_id]);

    // Cập nhật trạng thái game
    $stmt = $pdo->prepare("UPDATE game_list SET status = 'dang_dien_ra' WHERE id = ?");
    $stmt->execute([$game_id]);

    $pdo->commit();
    echo "<script>alert('✅ Game đã được xác nhận và bắt đầu. Bạn không thể hoàn tiền hoặc chỉnh sửa sau bước này.'); window.location.href='game_detail.php?game_id=$game_id';</script>";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "<script>alert('Đã có lỗi xảy ra: " . $e->getMessage() . "'); window.history.back();</script>";
}
?>
