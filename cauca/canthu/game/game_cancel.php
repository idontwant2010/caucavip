<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'canthu') {
    header("Location: /");
    exit;
}

// Lấy ID từ query string
$game_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$game_id) {
    die("ID game không hợp lệ.");
}

// Kiểm tra và hủy game
try {
    $stmt = $pdo->prepare("SELECT status FROM game_list WHERE id = ? AND creator_id = ?");
    $stmt->execute([$game_id, $_SESSION['user']['id']]);
    $game = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$game) {
        die("Game không tồn tại hoặc không thuộc về bạn.");
    }

    if ($game['status'] !== 'cho_xac_nhan') {
        die("Chỉ có thể hủy game khi trạng thái là 'cho_xac_nhan'.");
    }

    $stmt = $pdo->prepare("UPDATE game_list SET status = 'huy' WHERE id = ? AND creator_id = ?");
    $stmt->execute([$game_id, $_SESSION['user']['id']]);

    header("Location: game_list.php?success=1");
    exit;
} catch (PDOException $e) {
    die("Lỗi hủy game: " . $e->getMessage());
}
