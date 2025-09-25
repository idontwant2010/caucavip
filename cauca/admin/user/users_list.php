<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'admin') {
    header("Location: /");
    exit;
}

// Xử lý lọc
$role_filter = $_GET['role'] ?? 'all';
$search_phone = $_GET['search_phone'] ?? '';

$sql = "SELECT id, phone, nickname, email, vai_tro, full_name, review_status, created_at, status FROM users WHERE 1";
$params = [];

if ($role_filter !== 'all') {
    $sql .= " AND vai_tro = :role";
    $params[':role'] = $role_filter;
}

if (!empty($search_phone)) {
    $sql .= " AND phone LIKE :search";
    $params[':search'] = "%$search_phone%";
}

$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once __DIR__ . '/../../../includes/header.php';
?>

<div class="container mt-4">
    <h4 class="mb-3">👥 Danh sách người dùng</h4>

    <form method="get" class="mb-3 d-flex align-items-center gap-2 flex-wrap">
        <?php
        $roles = ['all' => 'All', 'canthu' => 'Cần Thủ', 'chuho' => 'Chủ Hồ', 'moderator' => 'Moderator', 'admin' => 'Admin'];
        foreach ($roles as $key => $label) {
            $active = ($role_filter === $key || ($role_filter === 'all' && $key === 'all')) ? 'btn-primary' : 'btn-outline-primary';
            echo "<a href='?role={$key}' class='btn btn-sm {$active}'>{$label}</a>";
        }
        ?>
        <input type="text" name="search_phone" class="form-control form-control-sm"
               placeholder="Tìm theo SĐT..." value="<?= htmlspecialchars($search_phone) ?>" style="max-width: 200px;">
        <button class="btn btn-sm btn-dark">🔍 Tìm</button>
    </form>

    <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>SĐT</th>
                <th>Nickname</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Họ tên</th>
                <th>Duyệt</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['phone']) ?></td>
                    <td><?= htmlspecialchars($u['nickname']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
<td>
    <?php
    $role_class = match ($u['vai_tro']) {
        'admin'     => 'bg-danger',
        'moderator' => 'bg-warning text-dark',
        'canthu'    => 'bg-success',
        'chuho'     => 'bg-primary',
        default     => 'bg-secondary'
    };
    ?>
    <span class="badge <?= $role_class ?>"><?= htmlspecialchars($u['vai_tro']) ?></span>
</td>
                    <td><?= htmlspecialchars($u['full_name']) ?></td>
                    <td><?= $u['review_status'] === 'yes' ? '✅' : '⏳' ?></td>
                    <td>
                        <?php
                        echo match ($u['status']) {
                            'Đã xác minh'   => '✅',
                            'Chưa xác minh' => '🕒',
                            'Tạm dừng'      => '⛔',
                            'banned'        => '🚫',
                            default         => $u['status']
                        };
                        ?>
                    </td>
                    <td><?= date('d/m/Y H:i', strtotime($u['created_at'])) ?></td>
                    <td>
                        <a href="user_view.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-primary">Xem và sửa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include_once __DIR__ . '/../../../includes/footer.php'; ?>
