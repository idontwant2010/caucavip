<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'admin') {
    header("Location: /");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID không hợp lệ.');
}

$selected_id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$selected_id]);
$selected_user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$selected_user) {
    die('Không tìm thấy người dùng.');
}

include_once __DIR__ . '/../../../includes/header.php';
?>

<div class="container mt-4">
    <h4 class="mb-3">👤 Thông tin người dùng #<?= $selected_user['id'] ?></h4>

    <table class="table table-bordered table-striped">
<tbody>
<?php
function span_input($field, $value, $id, $type = 'text', $options = []) {
    $html = "<span id='{$field}_display' data-user-id='{$id}' data-type='{$type}'>" . htmlspecialchars($value ?? '—') . "</span>";

    if ($type === 'text') {
        $html .= "<input type='text' id='{$field}_input' class='form-control form-control-sm d-inline-block d-none' style='max-width: 200px;' value='" . htmlspecialchars($value ?? '') . "'>";
    } elseif ($type === 'select') {
        $html .= "<select id='{$field}_input' class='form-select form-select-sm d-none' style='max-width: 200px;'>";
        foreach ($options as $opt) {
            $selected = ($value === $opt) ? 'selected' : '';
            $html .= "<option value='{$opt}' {$selected}>{$opt}</option>";
        }
        $html .= "</select>";
    }

    $html .= "<button class='btn btn-sm btn-outline-secondary ms-1' onclick=\"editField('{$field}')\">✏️</button>";
    return $html;
}

$id = $selected_user['id'];
?>
<tr>
    <th>Vai trò</th>
    <td>
        <?php
        $role = $selected_user['vai_tro'];
        $role_badges = [
            'admin'     => 'bg-danger',
            'moderator' => 'bg-primary',
            'canthu'    => 'bg-success',
            'chuho'     => 'bg-warning text-dark'
			
        ];
        $badge_class = $role_badges[$role] ?? 'bg-secondary';
        ?>
        <span class="badge <?= $badge_class ?>"><?= htmlspecialchars($role) ?></span>
    </td>
</tr>

<tr><th>Nickname</th><td><?= span_input('nickname', $selected_user['nickname'], $id) ?></td></tr>
<tr><th>Số điện thoại</th><td><?= span_input('phone', $selected_user['phone'], $id) ?></td></tr>
<tr><th>Email</th><td><?= span_input('email', $selected_user['email'], $id) ?></td></tr>

<tr><th>Trạng thái</th><td>
    <?= span_input('status', $selected_user['status'], $id, 'select', ['Chưa xác minh', 'Đã xác minh', 'Tạm dừng', 'banned']) ?>
</td></tr>

<tr><th>Có chức năng Đài Sư Review</th><td>
    <?= span_input('review_status', $selected_user['review_status'], $id, 'select', ['yes', 'no']) ?>
</td></tr>
<tr><th>Số TK Ngân Hàng</th><td><?= span_input('bank_account', $selected_user['bank_account'], $id) ?></td></tr>
<tr><th>Tên Ngân hàng</th><td><?= span_input('bank_name', $selected_user['bank_name'], $id) ?></td></tr>
<tr><th>Họ và tên</th><td><?= span_input('full_name', $selected_user['full_name'], $id) ?></td></tr>
<tr><th>Số CCCD</th><td><?= span_input('CCCD_number', $selected_user['CCCD_number'], $id) ?></td></tr>
<tr><th>Mã giới thiệu</th><td><?= span_input('ref_code', $selected_user['ref_code'], $id) ?></td></tr>
<tr><th>EXP</th><td><?= span_input('user_exp', $selected_user['user_exp'], $id) ?></td></tr>
<tr><th>Ghi chú</th><td><?= span_input('user_note', $selected_user['user_note'], $id) ?></td></tr>
<tr><th>Số dư chính</th><td><?= number_format((float)($selected_user['balance'] ?? 0), 0, ',', '.') ?> đ</td></tr>
<tr><th>Số dư REF</th><td><?= number_format((float)($selected_user['balance_ref'] ?? 0), 0, ',', '.') ?> đ</td></tr>
<tr><th>Ngày tạo</th><td><?= isset($selected_user['created_at']) ? date('d/m/Y H:i', strtotime($selected_user['created_at'])) : '—' ?></td></tr>
</tbody>


    </table>

    <a href="users_list.php" class="btn btn-secondary mt-3">← Quay lại danh sách</a>
</div>

<?php include_once __DIR__ . '/../../../includes/footer.php'; ?>


<script>
function editField(field) {
    const display = document.getElementById(field + '_display');
    const input = document.getElementById(field + '_input');
    const userId = display.getAttribute('data-user-id');
    const type = display.getAttribute('data-type');

    display.classList.add('d-none');
    input.classList.remove('d-none');
    input.focus();

    input.addEventListener('blur', function handleBlur() {
        const newValue = (type === 'select') ? input.value : input.value.trim();

        fetch('user_update_field.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${userId}&field=${field}&value=${encodeURIComponent(newValue)}`
        })
        .then(res => res.text())
        .then(txt => {
            if (txt.trim() === 'OK') {
                display.textContent = newValue || '—';
            } else {
                alert('❌ Cập nhật thất bại: ' + txt);
            }
            display.classList.remove('d-none');
            input.classList.add('d-none');
        });

        input.removeEventListener('blur', handleBlur);
    }, { once: true });
}
</script>
