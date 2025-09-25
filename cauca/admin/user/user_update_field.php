<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'admin') {
    http_response_code(403);
    echo 'Không có quyền';
    exit;
}

$id    = $_POST['id']    ?? null;
$field = $_POST['field'] ?? '';
$value = $_POST['value'] ?? '';

$allowed_fields = [
    'full_name', 'nickname', 'phone', 'email',
    'bank_account', 'bank_name', 'CCCD_number',
    'ref_code', 'user_exp', 'user_note',
    'status', 'review_status'
];

if (!$id || !in_array($field, $allowed_fields)) {
    http_response_code(400);
    echo 'Dữ liệu không hợp lệ';
    exit;
}

try {
    // Lấy giá trị cũ trước khi cập nhật
    $stmt = $pdo->prepare("SELECT $field FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $old_value = $stmt->fetchColumn();

    // Thực hiện cập nhật
    $stmt = $pdo->prepare("UPDATE users SET $field = :value WHERE id = :id");
    $stmt->execute([':value' => $value, ':id' => $id]);

    // Ghi log hành động admin
    $log = $pdo->prepare("INSERT INTO admin_action_logs 
        (admin_id, target_user_id, field_name, old_value, new_value)
        VALUES (:admin_id, :target_user_id, :field_name, :old_value, :new_value)");
    $log->execute([
        ':admin_id'        => $_SESSION['user']['id'],
        ':target_user_id'  => $id,
        ':field_name'      => $field,
        ':old_value'       => $old_value,
        ':new_value'       => $value
    ]);

    echo 'OK';

} catch (Exception $e) {
    http_response_code(500);
    echo 'Lỗi: ' . $e->getMessage();
}
