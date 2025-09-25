<?php
// giai_invite_decline.php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'canthu') { header("Location: /"); exit; }

$user_id = (int)$_SESSION['user']['id'];
$giai_id = (int)($_POST['giai_id'] ?? 0);
if ($giai_id <= 0) { die("Thiếu giai_id"); }

try {
    $pdo->beginTransaction();

    // 1) Khóa dòng lời mời
    $stmt = $pdo->prepare("SELECT * FROM giai_user WHERE giai_id = ? AND user_id = ? AND trang_thai = 'moi_cho_phan_hoi' FOR UPDATE");
    $stmt->execute([$giai_id, $user_id]);
    $invite = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$invite) { throw new Exception("Không tìm thấy lời mời hợp lệ."); }

    // 2) Cập nhật trạng thái => từ chối
    $stmt = $pdo->prepare("
        UPDATE giai_user 
        SET trang_thai = 'moi_da_tu_choi',
            note = CONCAT(COALESCE(note,''),' | decline invite'),
            updated_at = NOW()
        WHERE giai_id = ? AND user_id = ? AND trang_thai = 'moi_cho_phan_hoi'
        LIMIT 1
    ");
    $stmt->execute([$giai_id, $user_id]);
    if ($stmt->rowCount() === 0) { throw new Exception("Không cập nhật được trạng thái từ chối."); }

    $pdo->commit();

    header("Location: giai_list_open.php?msg=declined");
    exit;

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo "<script>alert('".$e->getMessage()."'); window.location.href='giai_list_open.php';</script>";
    exit;
}
