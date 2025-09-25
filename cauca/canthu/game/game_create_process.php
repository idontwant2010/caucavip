<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'canthu') {
    header("Location: /");
    exit;
}

// Kiểm tra CSRF token
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Yêu cầu không hợp lệ.");
}

$ho_cau_id = filter_input(INPUT_POST, 'ho_cau_id', FILTER_VALIDATE_INT) ?: 0;
$ten_game = trim($_POST['ten_game'] ?? '');
$hinh_thuc_id = (int)($_POST['hinh_thuc_id'] ?? 0);
$so_luong_can_thu = (int)($_POST['so_luong_can_thu'] ?? 0);
$ngay_to_chuc = $_POST['ngay_to_chuc'] ?? '';
$gio_bat_dau = $_POST['gio_bat_dau'] ?? '';
$gia_game_stake = max(10000, (int)($_POST['gia_game_stake'] ?? 100000));
$thoi_luong_phut_hiep = (int)($_POST['thoi_luong_phut_hiep'] ?? 60); // Lấy từ dropdown
$luat_choi = trim($_POST['luat_choi'] ?? '');

// Lấy thông tin hồ câu
try {
    $stmt = $pdo->prepare("SELECT hc.so_cho_ngoi, hc.gia_game, hc.max_chieu_dai_can, hc.max_truc_theo, lc.ten_ca
                           FROM ho_cau hc
                           LEFT JOIN loai_ca lc ON hc.loai_ca_id = lc.id
                           WHERE hc.id = ? AND hc.cho_phep_danh_game = 1 AND hc.status = 'dang_hoat_dong'");
    $stmt->execute([$ho_cau_id]);
    $ho_cau = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ho_cau) {
        die("Hồ câu không hợp lệ.");
    }
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
        die("Không tìm thấy lịch hoạt động.");
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
        die("Không tìm thấy cấu hình.");
    }
    $game_time_basic = $configs['game_time_basic'] ?: 60; // Mặc định 60 phút nếu không có
    $game_vat_percent = $configs['game_vat_percent'] ?: 10; // Mặc định 10% nếu không có
} catch (PDOException $e) {
    die("Lỗi truy vấn cấu hình: " . $e->getMessage());
}

// Lấy hình thức game
try {
    $stmt = $pdo->prepare("SELECT id, ten_hinh_thuc, so_nguoi_min, so_nguoi_max 
                           FROM giai_game_hinh_thuc 
                           WHERE cho_phep_canthu_tao = 1 AND id = ?");
    $stmt->execute([$hinh_thuc_id]);
    $hinh_thuc = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$hinh_thuc) {
        die("Hình thức game không hợp lệ.");
    }
} catch (PDOException $e) {
    die("Lỗi truy vấn hình thức: " . $e->getMessage());
}

// Validate
$thu = date('N', strtotime($ngay_to_chuc));
$thu = $thu == 7 ? 'CN' : (string)$thu;
$lich = array_filter($lich_hoat_dong, fn($l) => $l['thu'] === $thu);
$lich = reset($lich) ?: null;

if (empty($ten_game) || strlen($ten_game) > 255) {
    die("Tên game không hợp lệ (tối đa 255 ký tự).");
} elseif ($so_luong_can_thu < $hinh_thuc['so_nguoi_min'] || $so_luong_can_thu > min($hinh_thuc['so_nguoi_max'], $ho_cau['so_cho_ngoi'])) {
    die("Số lượng cần thủ không đủ, hoặc dư so với hình thức game, hoặc nhiều hơn số chổ ngồi của hồ");
} elseif ($gia_game_stake < 10000) {
    die("Giá cược phải từ 10,000 VNĐ.");
} elseif (!in_array($thoi_luong_phut_hiep, [45, 60, 75, 90, 120, 150, 180, 240, 300])) {
    die("Thời lượng mỗi hiệp phải là 45, 60, 75, 90, 120, 150, 180, 240, hoặc 300 phút.");
} elseif (strtotime($ngay_to_chuc) < strtotime(date('Y-m-d'))) {
    die("Ngày tổ chức phải từ hôm nay.");
} elseif (strtotime($ngay_to_chuc) > strtotime("+{$configs['game_day_limit']} days")) {
    die("Ngày tổ chức tối đa {$configs['game_day_limit']} ngày.");
} elseif (!$lich || $lich['trang_thai'] !== 'mo') {
    die("Hồ không hoạt động ngày chọn.");
} elseif ($gio_bat_dau < $lich['gio_mo'] || $gio_bat_dau > $lich['gio_dong']) {
    die("Giờ bắt đầu phải từ {$lich['gio_mo']} đến {$lich['gio_dong']}.");
} elseif (strlen($luat_choi) > 200) {
    die("Luật chơi (tối đa 200 ký tự) quá dài.");
}

// Kiểm tra giới hạn game
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) 
                           FROM game_list 
                           WHERE creator_id = ? AND status = 'cho_xac_nhan'");
    $stmt->execute([$_SESSION['user']['id']]);
    if ($stmt->fetchColumn() >= $configs['user_game_limit']) {
        die("Đạt giới hạn {$configs['user_game_limit']} game chờ xác nhận.");
    }
} catch (PDOException $e) {
    die("Lỗi kiểm tra giới hạn: " . $e->getMessage());
}

// Tính tổng phí game theo công thức mới với VAT
$base_fee = $so_luong_can_thu * $ho_cau['gia_game'] * ($thoi_luong_phut_hiep / $game_time_basic);
$tong_phi_game = $base_fee * (1 + $game_vat_percent / 100);

// Lưu vào cơ sở dữ liệu
try {
    $stmt = $pdo->prepare("INSERT INTO game_list (
        ho_cau_id, creator_id, hinh_thuc_id, ten_game, so_luong_can_thu, 
        gia_game_stake, thoi_luong_phut_hiep, tong_phi_game, game_online_discount, luat_choi, 
        ngay_to_chuc, gio_bat_dau, status, created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'cho_xac_nhan', NOW())");
    $stmt->execute([
        $ho_cau_id,
        $_SESSION['user']['id'],
        $hinh_thuc_id,
        $ten_game,
        $so_luong_can_thu,
        $gia_game_stake,
        $thoi_luong_phut_hiep,
        $tong_phi_game,
        $configs['game_online_discount'],
        $luat_choi,
        $ngay_to_chuc,
        $gio_bat_dau
    ]);
    header("Location: game_dashboard.php");
    exit;
} catch (PDOException $e) {
    die("Lỗi tạo game: " . $e->getMessage() . " (Chi tiết: " . $e->getTraceAsString() . ")");
}
?>