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

// Lấy thông tin game
try {
    $stmt = $pdo->prepare("SELECT gl.id, gl.ten_game, gl.ngay_to_chuc, gl.gio_bat_dau, gl.so_luong_can_thu, 
                                  gl.thoi_luong_phut_hiep, gl.gia_game_stake, gl.luat_choi, gl.status, gl.ho_cau_id, 
                                  gl.hinh_thuc_id, hc.ten_ho, hc.gia_game, hc.so_cho_ngoi
                           FROM game_list gl
                           LEFT JOIN ho_cau hc ON gl.ho_cau_id = hc.id
                           WHERE gl.id = ? AND gl.creator_id = ?");
    $stmt->execute([$game_id, $_SESSION['user']['id']]);
    $game = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$game) {
        die("Game không tồn tại hoặc không thuộc về bạn.");
    }
    // Debug để kiểm tra dữ liệu
    // var_dump($game); exit;
} catch (PDOException $e) {
    die("Lỗi truy vấn game: " . $e->getMessage());
}

// Lấy lịch hoạt động
try {
    $stmt = $pdo->prepare("SELECT thu, gio_mo, gio_dong, trang_thai 
                           FROM lich_hoat_dong_ho_cau 
                           WHERE ho_cau_id = ?");
    $stmt->execute([$game['ho_cau_id']]);
    $lich_hoat_dong = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($lich_hoat_dong)) {
        die("Không tìm thấy lịch hoạt động cho hồ câu này.");
    }
} catch (PDOException $e) {
    die("Lỗi truy vấn lịch: " . $e->getMessage());
}

// Lấy cấu hình
try {
    $stmt = $pdo->prepare("SELECT config_key, config_value 
                           FROM admin_config_keys 
                           WHERE config_key IN ('game_day_limit', 'user_game_limit', 'game_fee_user', 'game_vat_percent', 'game_time_basic')");
    $stmt->execute();
    $configs = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $configs[$row['config_key']] = (int)$row['config_value'];
    }
    if (empty($configs)) {
        die("Không tìm thấy cấu hình hệ thống.");
    }
    $game_time_basic = $configs['game_time_basic'] ?: 60; // Mặc định 60 phút nếu không có
    $game_vat_percent = $configs['game_vat_percent'] ?: 10; // Mặc định 10% nếu không có
} catch (PDOException $e) {
    die("Lỗi truy vấn cấu hình: " . $e->getMessage());
}

// Lấy hình thức game
try {
    $stmt = $pdo->prepare("SELECT id, ten_hinh_thuc, so_nguoi_min, so_nguoi_max, so_hiep 
                           FROM giai_game_hinh_thuc 
                           WHERE cho_phep_canthu_tao = 1");
    $stmt->execute();
    $hinh_thuc_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($hinh_thuc_list)) {
        die("Không tìm thấy hình thức game hợp lệ.");
    }
} catch (PDOException $e) {
    die("Lỗi truy vấn hình thức: " . $e->getMessage());
}

// Xử lý form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    $ten_game = trim($_POST['ten_game'] ?? '');
    $ngay_to_chuc = $_POST['ngay_to_chuc'] ?? '';
    $gio_bat_dau = $_POST['gio_bat_dau'] ?? '';
    $hinh_thuc_id = (int)($_POST['hinh_thuc_id'] ?? 0);
    $so_luong_can_thu = (int)($_POST['so_luong_can_thu'] ?? 0);
    $thoi_luong_phut_hiep = (int)($_POST['thoi_luong_phut_hiep'] ?? 60);
    $gia_game_stake = max(10000, (int)($_POST['gia_game_stake'] ?? 0));
    $luat_choi = trim($_POST['luat_choi'] ?? '');

    // Lấy số hiep từ hình thức game
    $stmt = $pdo->prepare("SELECT so_hiep FROM giai_game_hinh_thuc WHERE id = ?");
    $stmt->execute([$hinh_thuc_id]);
    $hinh_thuc = $stmt->fetch(PDO::FETCH_ASSOC);
    $so_hiep = $hinh_thuc['so_hiep'] ?: 1;

    // Validate
    $thu = date('N', strtotime($ngay_to_chuc));
    $thu = $thu == 7 ? 'CN' : (string)$thu;
    $lich = array_filter($lich_hoat_dong, fn($l) => $l['thu'] === $thu);
    $lich = reset($lich) ?: null;

    if (empty($ten_game) || strlen($ten_game) > 255) {
        $error = "Tên game không hợp lệ (tối đa 255 ký tự).";
    } elseif ($so_luong_can_thu < 1 || $so_luong_can_thu > $game['so_cho_ngoi']) {
        $error = "Số lượng cần thủ không hợp lệ.";
    } elseif ($gia_game_stake < 10000) {
        $error = "Giá cược phải từ 10,000 VNĐ.";
    } elseif (!in_array($thoi_luong_phut_hiep, [45, 60, 75, 90, 120, 150, 180, 240, 300])) {
        $error = "Thời lượng mỗi hiệp phải là 45, 60, 75, 90, 120, 150, 180, 240, hoặc 300 phút.";
    } elseif (strtotime($ngay_to_chuc) < strtotime(date('Y-m-d'))) {
        $error = "Ngày tổ chức phải từ hôm nay.";
    } elseif (strtotime($ngay_to_chuc) > strtotime("+30 days")) { // Giới hạn 30 ngày
        $error = "Ngày tổ chức tối đa 30 ngày từ hôm nay.";
    } elseif (!$lich || $lich['trang_thai'] !== 'mo') {
        $error = "Hồ không hoạt động ngày chọn.";
    } elseif ($gio_bat_dau < $lich['gio_mo'] || $gio_bat_dau > $lich['gio_dong']) {
        $error = "Giờ bắt đầu phải từ {$lich['gio_mo']} đến {$lich['gio_dong']}.";
    } elseif (strlen($luat_choi) > 200) {
        $error = "Luật chơi (tối đa 200 ký tự) quá dài.";
    } elseif ($game['status'] !== 'cho_xac_nhan') {
        $error = "Chỉ có thể sửa game khi trạng thái là 'cho_xac_nhan'.";
    } else {
        // Tính lại tong_phi_game
        $base_fee = $so_luong_can_thu * $game['gia_game'] * ($thoi_luong_phut_hiep / $game_time_basic) * $so_hiep;
        $tong_phi_game = $base_fee * (1 + $game_vat_percent / 100);

        try {
            $stmt = $pdo->prepare("UPDATE game_list 
                                   SET ten_game = ?, ngay_to_chuc = ?, gio_bat_dau = ?, hinh_thuc_id = ?, so_luong_can_thu = ?, 
                                       thoi_luong_phut_hiep = ?, gia_game_stake = ?, luat_choi = ?, tong_phi_game = ?
                                   WHERE id = ? AND creator_id = ?");
            $stmt->execute([$ten_game, $ngay_to_chuc, $gio_bat_dau, $hinh_thuc_id, $so_luong_can_thu, 
                           $thoi_luong_phut_hiep, $gia_game_stake, $luat_choi, $tong_phi_game, 
                           $game_id, $_SESSION['user']['id']]);
            header("Location: game_list.php?success=1");
            exit;
        } catch (PDOException $e) {
            $error = "Lỗi cập nhật game: " . $e->getMessage();
        }
    }
}

include_once __DIR__ . '/../../../includes/header.php';
?>
<div class="container mt-4">
    <h4>Chỉnh sửa game: <?= htmlspecialchars($game['ten_game']) ?></h4>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif (isset($_GET['success'])): ?>
        <div class="alert alert-success">Cập nhật game thành công!</div>
    <?php endif; ?>
    <div class="card">
        <div class="card-body">
            <form method="post" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Ngày tổ chức <span class="text-danger">*</span></label>
                    <select name="ngay_to_chuc" id="ngay_to_chuc" class="form-control" required>
                        <option value="">Chọn ngày</option>
                        <?php
                        $today = new DateTime();
                        $maxDate = (clone $today)->modify('+30 days');
                        $currentDate = $today;
                        while ($currentDate <= $maxDate) {
                            $thu = $currentDate->format('N');
                            $thuKey = $thu == 7 ? 'CN' : (string)$thu;
                            $lich = array_filter($lich_hoat_dong, fn($l) => $l['thu'] === $thuKey && $l['trang_thai'] === 'mo');
                            if (!empty($lich)) {
                                $formattedDate = $currentDate->format('Y-m-d');
                                $selected = ($formattedDate === $game['ngay_to_chuc']) ? 'selected' : '';
                                echo "<option value='$formattedDate' $selected>" . $currentDate->format('d/m/Y') . "</option>";
                            }
                            $currentDate->modify('+1 day');
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Giờ bắt đầu <span class="text-danger">*</span></label>
                    <select name="gio_bat_dau" id="gio_bat_dau" class="form-control" required>
                        <?php
                        for ($hour = 6; $hour <= 18; $hour++) {
                            $time = sprintf("%02d:00", $hour);
                            $selected = ($time === $game['gio_bat_dau']) ? 'selected' : '';
                            echo "<option value='$time' $selected>$time</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" id="hinh_thuc_label">Hình thức game <span class="text-danger">*</span></label>
                    <select name="hinh_thuc_id" id="hinh_thuc_id" class="form-control" required>
                        <?php foreach ($hinh_thuc_list as $ht): ?>
                            <option value="<?= $ht['id'] ?>" data-so-hiep="<?= $ht['so_hiep'] ?>" data-min="<?= $ht['so_nguoi_min'] ?>" data-max="<?= $ht['so_nguoi_max'] ?>" <?= ($ht['id'] == $game['hinh_thuc_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($ht['ten_hinh_thuc']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tên game <span class="text-danger">*</span></label>
                    <input type="text" name="ten_game" value="<?= htmlspecialchars($game['ten_game']) ?>" class="form-control" maxlength="255" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Giá game cược (VNĐ) <span class="text-danger">*</span></label>
                    <input type="number" name="gia_game_stake" id="gia_game_stake" value="<?= htmlspecialchars($game['gia_game_stake'] ?? 0) ?>" min="10000" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Số lượng cần thủ <span class="text-danger">*</span> (max: <?= htmlspecialchars($game['so_cho_ngoi']) ?>)</label>
                    <input type="number" name="so_luong_can_thu" id="so_luong_can_thu" value="<?= htmlspecialchars($game['so_luong_can_thu']) ?>" min="2" max="<?= $game['so_cho_ngoi'] ?>" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Thời lượng mỗi hiệp (phút) <span class="text-danger">*</span></label>
                    <select name="thoi_luong_phut_hiep" id="thoi_luong_phut_hiep" class="form-control" required>
                        <?php $durations = [45, 60, 75, 90, 120, 150, 180, 240, 300]; ?>
                        <?php foreach ($durations as $duration): ?>
                            <option value="<?= $duration ?>" <?= ($duration == $game['thoi_luong_phut_hiep']) ? 'selected' : '' ?>><?= $duration ?> phút</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Luật chơi</label>
                    <textarea name="luat_choi" class="form-control" maxlength="200" rows="3"><?= htmlspecialchars($game['luat_choi']) ?></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Tổng phí game (VNĐ): <span id="tong_phi_game_display">0</span></label>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Cập nhật game</button>
                    <a href="game_list.php" class="btn btn-secondary">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
const lichHoatDong = <?php echo json_encode($lich_hoat_dong); ?>;
const gameDayLimit = <?php echo $configs['game_day_limit']; ?>;
const thuMap = {'2': 1, '3': 2, '4': 3, '5': 4, '6': 5, '7': 6, 'CN': 0};
const validDays = lichHoatDong.filter(l => l.trang_thai === 'mo').map(l => thuMap[l.thu]);
const gia_game = <?php echo $game['gia_game']; ?>;
const game_time_basic = <?php echo $game_time_basic; ?>;
const game_vat_percent = <?php echo $game_vat_percent; ?>;

const ngayToChuc = document.getElementById('ngay_to_chuc');
const gioBatDau = document.getElementById('gio_bat_dau');
const hinhThucId = document.getElementById('hinh_thuc_id');
const hinhThucLabel = document.getElementById('hinh_thuc_label');
const soLuongCanThu = document.getElementById('so_luong_can_thu');
const thoiLuongPhutHiep = document.getElementById('thoi_luong_phut_hiep');
const giaGameStake = document.getElementById('gia_game_stake');
const tongPhiGameDisplay = document.getElementById('tong_phi_game_display');
const today = new Date('2025-06-25T01:09:00+07:00'); // Đặt thời gian hiện tại
const maxDate = new Date(today);
maxDate.setDate(today.getDate() + 30);

// Tạo danh sách ngày hợp lệ
const availableDates = [];
let currentDate = new Date(today);
while (currentDate <= maxDate) {
    const thu = currentDate.getDay();
    if (validDays.includes(thu)) {
        const formattedDate = currentDate.toISOString().split('T')[0];
        availableDates.push(formattedDate);
    }
    currentDate.setDate(currentDate.getDate() + 1);
}

// Điền ngày vào dropdown
availableDates.forEach(date => {
    const option = document.createElement('option');
    option.value = date;
    option.text = new Date(date).toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' });
    if (date === '<?= htmlspecialchars($game['ngay_to_chuc']) ?>') {
        option.selected = true;
    }
    ngayToChuc.appendChild(option);
});

// Xử lý khi chọn ngày
ngayToChuc.addEventListener('change', () => {
    const selectedDate = ngayToChuc.value;
    if (selectedDate) {
        const thu = new Date(selectedDate).getDay();
        const thuKey = Object.keys(thuMap).find(key => thuMap[key] === thu);
        const lich = lichHoatDong.find(l => l.thu === thuKey && l.trang_thai === 'mo');

        if (lich) {
            gioBatDau.value = '08:00'; // Đặt giờ mặc định là 8:00
            calculateTotalFee();
        } else {
            alert('Hồ không hoạt động ngày này.');
            ngayToChuc.value = availableDates[0] || '';
        }
    }
});

// Đặt ngày mặc định là ngày hiện tại nếu hợp lệ
if (availableDates.length > 0 && !ngayToChuc.value) {
    ngayToChuc.value = availableDates[0];
    const thu = new Date(availableDates[0]).getDay();
    const thuKey = Object.keys(thuMap).find(key => thuMap[key] === thu);
    const lich = lichHoatDong.find(l => l.thu === thuKey && l.trang_thai === 'mo');
    if (lich) {
        gioBatDau.value = '08:00'; // Đặt giờ mặc định 8:00
        calculateTotalFee();
    }
}

// Cập nhật label khi chọn hình thức game
hinhThucId.addEventListener('change', () => {
    const selectedOption = hinhThucId.options[hinhThucId.selectedIndex];
    const min = selectedOption?.getAttribute('data-min') || 'N/A';
    const max = selectedOption?.getAttribute('data-max') || 'N/A';
    hinhThucLabel.textContent = `Hình thức game ${selectedOption ? `* (số cần thủ: min: ${min} || max: ${max})` : '*'}`;
    calculateTotalFee();
});

// Hàm tính tổng phí game
function calculateTotalFee() {
    const soLuong = parseInt(soLuongCanThu.value) || 0;
    const thoiLuong = parseInt(thoiLuongPhutHiep.value) || 60;
    const hinhThucSelected = hinhThucId.options[hinhThucId.selectedIndex];
    const soHiep = parseInt(hinhThucSelected?.getAttribute('data-so-hiep')) || 1;
    const giaCoc = parseInt(giaGameStake.value) || 0;
    const baseFee = soLuong * gia_game * (thoiLuong / game_time_basic) * soHiep;
    const tongPhiGame = baseFee * (1 + game_vat_percent / 100);
    tongPhiGameDisplay.textContent = tongPhiGame.toLocaleString('vi-VN') + ' VNĐ';
}

// Gọi hàm tính phí khi thay đổi các trường
ngayToChuc.addEventListener('change', calculateTotalFee);
gioBatDau.addEventListener('change', calculateTotalFee);
soLuongCanThu.addEventListener('input', calculateTotalFee);
thoiLuongPhutHiep.addEventListener('change', calculateTotalFee);
giaGameStake.addEventListener('input', calculateTotalFee);
hinhThucId.addEventListener('change', calculateTotalFee);

// Tính phí mặc định khi tải trang và cập nhật label
calculateTotalFee();
hinhThucId.dispatchEvent(new Event('change'));
</script>
<?php include_once __DIR__ . '/../../../includes/footer.php'; ?>