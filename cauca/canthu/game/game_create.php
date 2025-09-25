<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'canthu') {
    header("Location: /");
    exit;
}

$ho_cau_id = filter_input(INPUT_GET, 'ho_cau_id', FILTER_VALIDATE_INT) ?: 0;

// Lấy thông tin hồ câu và loại cá
try {
    $stmt = $pdo->prepare("SELECT hc.id, hc.ten_ho, hc.so_cho_ngoi, hc.gia_game, hc.max_chieu_dai_can, hc.max_truc_theo, lc.ten_ca
                           FROM ho_cau hc
                           LEFT JOIN loai_ca lc ON hc.loai_ca_id = lc.id
                           WHERE hc.id = ? AND hc.cho_phep_danh_game = 1 AND hc.status = 'dang_hoat_dong'");
    $stmt->execute([$ho_cau_id]);
    $ho_cau = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ho_cau) {
        die("Hồ câu không hợp lệ.");
    }
    $ho_cau['max_chieu_dai_can'] = $ho_cau['max_chieu_dai_can'] ?: 0;
    $ho_cau['max_truc_theo'] = $ho_cau['max_truc_theo'] ?: 0;
    $ho_cau['ten_ca'] = $ho_cau['ten_ca'] ?: 'Không xác định';
    $ho_cau['so_cho_ngoi'] = $ho_cau['so_cho_ngoi'] ?: 0;
} catch (PDOException $e) {
    die("Lỗi truy vấn hồ câu: " . $e->getMessage());
}

// Lấy lịch hoạt động
try {
    $stmt = $pdo->prepare("SELECT thu, gio_mo, gio_dong, trang_thai 
                           FROM lich_hoat_dong_ho_cau 
                           WHERE ho_cau_id = ?");
    $stmt->execute([$ho_cau_id]);
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
                           WHERE config_key IN ('game_day_limit', 'user_game_limit', 'game_fee_user', 'game_vat_percent', 'game_online_discount', 'game_time_basic')");
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

// Lấy hình thức game với số hiệp
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

include_once __DIR__ . '/../../../includes/header.php';
?>
<div class="container mt-4">
    <h4>Tạo game tại <?= htmlspecialchars($ho_cau['ten_ho']) ?></h4>
    <div class="alert alert-info mb-3">
        Giới hạn cần: <?= htmlspecialchars($ho_cau['max_chieu_dai_can']) ?> cm, 
        Trục theo: <?= htmlspecialchars($ho_cau['max_truc_theo']) ?> cm, 
        Số chỗ ngồi: <?= htmlspecialchars($ho_cau['so_cho_ngoi']) ?>, 
        Loại cá: <?= htmlspecialchars($ho_cau['ten_ca']) ?>, 
        Giá game: <?= number_format($ho_cau['gia_game'], 0, ',', '.') ?> VNĐ/người/game 60 phút
    </div>
    <form method="post" action="game_create_process.php" class="row g-3">
        <input type="hidden" name="ho_cau_id" value="<?= $ho_cau_id ?>">

        <div class="col-md-6">
            <label class="form-label">Ngày tổ chức <span class="text-danger">*</span></label>
            <select name="ngay_to_chuc" id="ngay_to_chuc" class="form-control" required>
                <option value="">Chọn ngày</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Giờ bắt đầu <span class="text-danger">*</span></label>
            <select name="gio_bat_dau" id="gio_bat_dau" class="form-control" required>
                <?php
                for ($hour = 6; $hour <= 18; $hour++) {
                    $time = sprintf("%02d:00", $hour);
                    $selected = '';
                    echo "<option value='$time' $selected>$time</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label" id="hinh_thuc_label">Hình thức game <span class="text-danger">*</span></label>
            <select name="hinh_thuc_id" id="hinh_thuc_id" class="form-select" required>
                <option value="">Chọn hình thức</option>
                <?php foreach ($hinh_thuc_list as $ht): ?>
                    <option value="<?= $ht['id'] ?>" data-so-hiep="<?= $ht['so_hiep'] ?>" data-min="<?= $ht['so_nguoi_min'] ?>" data-max="<?= $ht['so_nguoi_max'] ?>"><?= htmlspecialchars($ht['ten_hinh_thuc']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Tên game <span class="text-danger">*</span></label>
            <input type="text" name="ten_game" class="form-control" maxlength="255" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Giá game cược (VNĐ) <span class="text-danger">*</span></label>
            <input type="number" name="gia_game_stake" value="100000" min="10000" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Số lượng cần thủ Game <span class="text-danger">*</span> < <?= htmlspecialchars($ho_cau['so_cho_ngoi']) ?> số chổ ngồi</label>
            <input type="number" name="so_luong_can_thu" id="so_luong_can_thu" min="2" max="<?= $ho_cau['so_cho_ngoi'] ?>" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Thời lượng mỗi hiệp (phút) <span class="text-danger">*</span></label>
            <select name="thoi_luong_phut_hiep" id="thoi_luong_phut_hiep" class="form-control" required>
                <option value="45">45 phút</option>
                <option value="60" selected>60 phút</option>
                <option value="75">75 phút</option>
                <option value="90">90 phút</option>
                <option value="120">120 phút</option>
                <option value="150">150 phút</option>
                <option value="180">180 phút</option>
                <option value="240">240 phút</option>
                <option value="300">300 phút</option>
            </select>
        </div>
        <div class="col-12">
            <label class="form-label">Luật chơi</label>
            <textarea name="luat_choi" class="form-control" maxlength="200" rows="3"></textarea>
        </div>
        <div class="col-12">
            <label class="form-label">Tổng phí game (VNĐ): <span id="tong_phi_game_display">0</span></label>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Tạo game</button>
            <a href="game_ho_cau.php" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
</div>
<script>
const lichHoatDong = <?php echo json_encode($lich_hoat_dong); ?>;
const gameDayLimit = <?php echo $configs['game_day_limit']; ?>;
const thuMap = {'2': 1, '3': 2, '4': 3, '5': 4, '6': 5, '7': 6, 'CN': 0};
const validDays = lichHoatDong.filter(l => l.trang_thai === 'mo').map(l => thuMap[l.thu]);
const gia_game = <?php echo $ho_cau['gia_game']; ?>;
const game_time_basic = <?php echo $game_time_basic; ?>;
const game_vat_percent = <?php echo $game_vat_percent; ?>;

const ngayToChuc = document.getElementById('ngay_to_chuc');
const gioBatDau = document.getElementById('gio_bat_dau');
const hinhThucId = document.getElementById('hinh_thuc_id');
const hinhThucLabel = document.getElementById('hinh_thuc_label');
const soLuongCanThu = document.getElementById('so_luong_can_thu');
const thoiLuongPhutHiep = document.getElementById('thoi_luong_phut_hiep');
const tongPhiGameDisplay = document.getElementById('tong_phi_game_display');
const today = new Date();
const maxDate = new Date(today);
maxDate.setDate(today.getDate() + gameDayLimit);

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

// Đặt ngày mặc định là ngày đầu tiên hợp lệ
if (availableDates.length > 0) {
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
    hinhThucLabel.textContent = `Hình thức game ${selectedOption ? `* (số cần thủ hình thức này: min: ${min} || max: ${max})` : '*'}`;
    calculateTotalFee();
});

// Hàm tính tổng phí game
function calculateTotalFee() {
    const soLuong = parseInt(soLuongCanThu.value) || 0;
    const thoiLuong = parseInt(thoiLuongPhutHiep.value) || 60;
    const hinhThucSelected = hinhThucId.options[hinhThucId.selectedIndex];
    const soHiep = parseInt(hinhThucSelected?.getAttribute('data-so-hiep')) || 1; // Mặc định 1 hiệp nếu không có
    const baseFee = soLuong * gia_game * (thoiLuong / game_time_basic) * soHiep;
    const tongPhiGame = baseFee * (1 + game_vat_percent / 100);
    tongPhiGameDisplay.textContent = tongPhiGame.toLocaleString('vi-VN') + ' VNĐ';
}

// Gọi hàm tính phí khi thay đổi số lượng cần thủ, thời lượng, hoặc hình thức game
soLuongCanThu.addEventListener('input', calculateTotalFee);
thoiLuongPhutHiep.addEventListener('change', calculateTotalFee);
hinhThucId.addEventListener('change', calculateTotalFee);

// Tính phí mặc định khi tải trang và cập nhật label
calculateTotalFee();
hinhThucId.dispatchEvent(new Event('change'));
</script>
<?php include_once __DIR__ . '/../../../includes/footer.php'; ?>