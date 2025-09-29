<?php
require_once '../../../connect.php';
require_once '../../../check_login.php';
require_once '../../../includes/header.php';

if ($_SESSION['user']['vai_tro'] !== 'chuho') {
    echo "<div class='alert alert-danger'>Bạn không có quyền truy cập.</div>";
    require_once '../../../includes/footer.php';
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Truy vấn thông tin chi tiết
$sql = "
SELECT 
    g.*, h.ten_ho, h.gia_giai, 
    u.full_name, u.nickname, u.phone, u.user_exp, u.user_lever, u.status as user_status,
    ht.ten_hinh_thuc, ht.nguyen_tac
FROM giai_list g
JOIN ho_cau h ON g.ho_cau_id = h.id
JOIN cum_ho ch ON h.cum_ho_id = ch.id
JOIN users u ON g.creator_id = u.id
JOIN giai_game_hinh_thuc ht ON g.hinh_thuc_id = ht.id
WHERE g.id = :id AND ch.chu_ho_id = :chu_ho_id
LIMIT 1
";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'id' => $id,
    'chu_ho_id' => $_SESSION['user']['id']
]);
$giai = $stmt->fetch();

function get_giai_time_basic(PDO $pdo)
{
    $sql = "SELECT config_value FROM admin_config_keys WHERE config_key = 'giai_time_basic' LIMIT 1";
    $stmt = $pdo->query($sql);
    $result = $stmt->fetch();
    return $result ? (int)$result['config_value'] : 60; // trả về số phút hoặc 0 nếu không có
}
$thoi_gian_co_ban = get_giai_time_basic($pdo);
$he_so_thoi_gian = $giai['thoi_luong_phut_hiep'] / $thoi_gian_co_ban;

if (!$giai) {
    echo "<div class='alert alert-warning'>Không tìm thấy giải hoặc bạn không có quyền xem.</div>";
    require_once '../../../includes/footer.php';
    exit;
}

// Tính tiền chủ hồ sẽ nhận
$tien_chuho_nhan = $giai['so_luong_can_thu'] * $giai['gia_giai'] * $giai['so_hiep'] * $he_so_thoi_gian;
$tien_VAT = $tien_chuho_nhan / 10;
$tien_chuho_nhan_VAT = $giai['phi_ho']
?>

<div class="container py-4">
    <h4 class="mb-3">🔍 Chi tiết giải: <strong><?= htmlspecialchars($giai['ten_giai']) ?></strong></h4>

    <!-- 1. Thông tin giải -->
    <div class="card mb-4">
        <div class="card-header bg-light fw-bold">🧾 Thông tin giải đấu</div>
        <div class="card-body">
            <p><strong>Hồ tổ chức:</strong> <?= htmlspecialchars($giai['ten_ho']) ?></p>
            <p><strong>Ngày tổ chức:</strong> <?= date('d/m/Y', strtotime($giai['ngay_to_chuc'])) ?> lúc <?= $giai['gio_bat_dau'] ?></p>
            <p><strong>Số cần thủ:</strong> <?= $giai['so_luong_can_thu'] ?> người</p>
            <p><strong>Hiệp - Bảng:</strong> <?= $giai['so_hiep'] ?> hiệp, <?= $giai['so_bang'] ?> bảng</p>
            <p><strong>Thời gian 1 hiệp: </strong> <?= $giai['thoi_luong_phut_hiep'] ?> phút. Hệ số thời gian: = <?= $he_so_thoi_gian ?></p>
            <p><strong>Hình thức:</strong> <?= htmlspecialchars($giai['ten_hinh_thuc']) ?></p>
            <p><strong>Nguyên tắc:</strong> <?= nl2br($giai['nguyen_tac']) ?></p>
            <p><strong>Lượt tạo:</strong> <?= date('d/m/Y H:i', strtotime($giai['created_at'])) ?></p>
            <p><strong>Trạng thái:</strong> <span class="badge bg-info"><?= $giai['status'] ?></span></p>
        </div>
    </div>

    <!-- 2. Người tạo giải -->
    <div class="card mb-4">
        <div class="card-header bg-light fw-bold">👤 Người tạo giải</div>
        <div class="card-body">
            <p><strong>Họ tên:</strong> <?= $giai['full_name'] ?> (<?= $giai['nickname'] ?>)</p>
            <p><strong>Số điện thoại:</strong> <?= $giai['phone'] ?></p>
            <p><strong>EXP:</strong> <?= $giai['user_exp'] ?> điểm</p>
            <p><strong>Cấp độ:</strong> Cấp <?= $giai['user_lever'] ?></p>
            <p><strong>Trạng thái tài khoản:</strong> <?= $giai['user_status'] ?></p>
        </div>
    </div>

    <!-- 3. Tiền chủ hồ được nhận -->
    <div class="card mb-4">
        <div class="card-header bg-light fw-bold">💰 Số tiền chủ hồ sẽ nhận sau khi duyệt giải</div>
        <div class="card-body">
            <h6 class="text-success fw-bold">
                <?= number_format($tien_chuho_nhan, 0, ',', '.') ?> đ =
                <small class="text-muted"> (<?= $giai['so_hiep'] ?> hiệp × <?= $giai['so_luong_can_thu'] ?> người × <?= number_format($giai['gia_giai']) ?> đ) × <?= $he_so_thoi_gian ?> (thời gian/hiệp) </small>
            </h6>
            <h6 class="text-success fw-bold">
                <?= number_format($tien_VAT, 0, ',', '.') ?> đ
                <small class="text-muted"> = Thuế VAT - 10% </small>
            </h6>
            <h5 class="text-success fw-bold">
                <?= number_format($tien_chuho_nhan_VAT, 0, ',', '.') ?> đ
                <small class="text-muted"> = Tổng cộng chủ hồ nhận</small>
            </h5>

            <p class="text-muted">Số tiền này sẽ được nhận ngay khi bạn duyệt giải này.</p>
        </div>
    </div>

    <!-- 4. Nút hành động -->
    <?php if ($giai['status'] === 'chuyen_chu_ho_duyet'): ?>
        <form action="giai_can_duyet_process.php" method="POST" class="d-flex gap-2">
            <input type="hidden" name="giai_id" value="<?= $giai['id'] ?>">

            <button type="submit"
                name="action"
                value="accept"
                class="btn btn-success"
                onclick="return confirm('✅ Bạn có chắc muốn DUYỆT giải này không?')">
                ✅ Duyệt giải
            </button>

            <button type="submit"
                name="action"
                value="reject"
                class="btn btn-danger"
                onclick="return confirm('❌ Bạn có chắc muốn TỪ CHỐI giải này không?')">
                ❌ Từ chối giải
            </button>
        </form>

    <?php else: ?>

        <div class="alert alert-secondary">Bạn chỉ có thể duyệt những giải có trạng thái "Chuyển Chủ Hồ Duyệt"</div>
    <?php endif; ?>
</div>

<?php require_once '../../../includes/footer.php'; ?>