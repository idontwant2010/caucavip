<?php
require_once __DIR__ . '/../../connect.php';
require_once __DIR__ . '/../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'canthu') {
    header("Location: /");
    exit;
}

$user_id = $_SESSION['user']['id'];

// --- Hồ câu thịt ---
$so_ho_thit = $pdo->query("SELECT COUNT(*) FROM ho_cau WHERE status = 'dang_hoat_dong' AND cho_phep_danh_thit = 1")->fetchColumn();
$so_ve_dang_dat = $pdo->prepare("SELECT COUNT(*) FROM booking WHERE can_thu_id = ? AND booking_status = 'dang_dien_ra'");
$so_ve_dang_dat->execute([$user_id]);
$ve_dang_dat = $so_ve_dang_dat->fetchColumn();
$so_ve_da_cau = $pdo->prepare("SELECT COUNT(*) FROM booking WHERE can_thu_id = ? AND booking_status = 'hoan_thanh'");
$so_ve_da_cau->execute([$user_id]);
$ve_da_cau = $so_ve_da_cau->fetchColumn();

// --- Hồ câu giải ---
$so_ho_giai = $pdo->query("SELECT COUNT(*) FROM ho_cau WHERE status = 'dang_hoat_dong' AND cho_phep_danh_giai = 1")->fetchColumn();
$so_giai_mo = $pdo->query("SELECT COUNT(*) FROM game_list WHERE status = 'dang_mo' AND hinh_thuc_id IN (SELECT id FROM giai_game_hinh_thuc WHERE hinh_thuc = 'giai')")->fetchColumn();
$giai_dang_dat = $pdo->prepare("SELECT COUNT(DISTINCT game_id) FROM game_user JOIN game_list ON game_user.game_id = game_list.id WHERE game_user.user_id = ? AND game_list.status = 'dang_dien_ra' AND hinh_thuc_id IN (SELECT id FROM giai_game_hinh_thuc WHERE hinh_thuc = 'giai')");
$giai_dang_dat->execute([$user_id]);
$giai_dang = $giai_dang_dat->fetchColumn();
$giai_da_cau = $pdo->prepare("SELECT COUNT(DISTINCT game_id) FROM game_user JOIN game_list ON game_user.game_id = game_list.id WHERE game_user.user_id = ? AND game_list.status = 'hoan_tat' AND hinh_thuc_id IN (SELECT id FROM giai_game_hinh_thuc WHERE hinh_thuc = 'giai')");
$giai_da_cau->execute([$user_id]);
$giai_da = $giai_da_cau->fetchColumn();

// --- Hồ câu game ---
$so_ho_game = $pdo->query("SELECT COUNT(*) FROM ho_cau WHERE status = 'dang_hoat_dong' AND cho_phep_danh_game = 1")->fetchColumn();
$so_game_mo = $pdo->query("SELECT COUNT(*) FROM game_list WHERE status = 'dang_mo' AND hinh_thuc_id IN (SELECT id FROM giai_game_hinh_thuc WHERE hinh_thuc = 'game')")->fetchColumn();
$game_dang_dat = $pdo->prepare("SELECT COUNT(DISTINCT game_id) FROM game_user JOIN game_list ON game_user.game_id = game_list.id WHERE game_user.user_id = ? AND game_list.status = 'dang_dien_ra' AND hinh_thuc_id IN (SELECT id FROM giai_game_hinh_thuc WHERE hinh_thuc = 'game')");
$game_dang_dat->execute([$user_id]);
$game_dang = $game_dang_dat->fetchColumn();
$game_da_cau = $pdo->prepare("SELECT COUNT(DISTINCT game_id) FROM game_user JOIN game_list ON game_user.game_id = game_list.id WHERE game_user.user_id = ? AND game_list.status = 'hoan_tat' AND hinh_thuc_id IN (SELECT id FROM giai_game_hinh_thuc WHERE hinh_thuc = 'game')");
$game_da_cau->execute([$user_id]);
$game_da = $game_da_cau->fetchColumn();

// --- Game + Giải do tôi tổ chức ---
$game_dang_to_chuc = $pdo->prepare("SELECT COUNT(*) FROM game_list WHERE creator_id = ? AND status != 'hoan_tat' ");
$game_dang_to_chuc->execute([$user_id]);
$g_to_chuc = $game_dang_to_chuc->fetchColumn();

$game_da_to_chuc = $pdo->prepare("SELECT COUNT(*) FROM game_list WHERE creator_id = ? AND status = 'hoan_tat' ");
$game_da_to_chuc->execute([$user_id]);
$g_to_chuc_xong = $game_da_to_chuc->fetchColumn();

$giai_dang_to_chuc = $pdo->prepare("SELECT COUNT(*) FROM giai_list WHERE creator_id = ? AND status NOT IN ('hoan_tat_giai', 'huy_giai');");
$giai_dang_to_chuc->execute([$user_id]);
$gi_to_chuc = $giai_dang_to_chuc->fetchColumn();

$giai_da_to_chuc = $pdo->prepare("SELECT COUNT(*) FROM giai_list WHERE creator_id = ? AND status = 'hoan_tat_giai' ");
$giai_da_to_chuc->execute([$user_id]);
$gi_to_chuc_xong = $giai_da_to_chuc->fetchColumn();
?>

<?php include_once __DIR__ . '/../../includes/header.php'; ?>

<!-- DASHBOARD CẦN THỦ -->

<div class="container py-3">
    <h3 class="mb-4">🎛️ Dashboard Cần Thủ</h3>

    <div class="row g-3">
        <!-- Booking -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-success shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title text-success">📦 Đặt vé câu Online</h5>
                    <p class="card-text mb-1">
                        Số hồ thịt: <span class="badge bg-primary"><?=$so_ho_thit?></span> |
						Vé đang đặt: <span class="badge bg-warning text-dark"><?=$so_ho_thit?></span>
					 </p>
					
                    <p class="card-text mb-1">
                        Đệ tử: <span class="badge bg-primary "><?=$so_ho_thit?></span> |
						Lượt đánh giá hồ: <span class="badge bg-warning text-dark"><?=$so_ho_thit?></span>
                    </p>
						<a href="booking/booking_list.php" class="btn btn-sm btn-outline-success mt-1">+ Đặt vé câu</a>
						<a href="booking/my_booking_list.php" class="btn btn-sm btn-outline-success mt-1">➜ Vé đã đặt</a> 
					<hr>
						<a href="booking/booking_list.php" class="btn btn-sm btn-outline-success mt-1">+ Đánh giá hồ đã câu</a>
				</div>
            </div>
        </div>

        <!-- Giải đấu -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-primary shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title text-primary">🏆 Tổ chức Giải</h5>
                    <p class="card-text mb-1">
                        Hồ đánh giải: <span class="badge bg-warning text-dark"><?=$so_ho_giai?></span> |
                        Giải diễn ra: <span class="badge bg-success"><?=$so_ho_giai?></span>
					</p>
					
                    <p class="card-text mb-1">
                        Tôi tổ chức: <span class="badge bg-success"><?=$gi_to_chuc?></span> |
						Tôi tham gia: <span class="badge bg-success"><?=$gi_to_chuc?></span>
                    </p>
					 <a href="giai/giai_ho_cau.php" class="btn btn-sm btn-outline-primary mt-1">+ Tạo giải</a>
					 <a href="mygiai/my_giai_list.php" class="btn btn-sm btn-outline-primary mt-1"> ➜ Giải tôi tạo</a>
					<hr>
                    <a href="giai/giai_list.php" class="btn btn-sm btn-outline-primary mt-1">+ Tham gia giải</a>
					<a href="giai/my_giai_list_join.php" class="btn btn-sm btn-outline-primary mt-1"> ➜ Giải tôi tham gia</a>
				</div>
            </div>
        </div>

        <!-- Game -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-dark shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title text-dark">🎮 Tổ Chức Game</h5>
                    <p class="card-text mb-1">
                        Hồ đánh giải: <span class="badge bg-warning text-dark"><?=$so_ho_giai?></span> |
                        Giải diễn ra: <span class="badge bg-success"><?=$so_ho_giai?></span>
					</p>
					
                    <p class="card-text mb-1">
                        Tôi tổ chức: <span class="badge bg-success"><?=$gi_to_chuc?></span> |
						Tôi tham gia: <span class="badge bg-success"><?=$gi_to_chuc?></span>
                    </p>
					 <a href="giai/giai_ho_cau.php" class="btn btn-sm btn-outline-primary mt-1">+ Tạo game</a>
					 <a href="mygiai/my_giai_list.php" class="btn btn-sm btn-outline-primary mt-1">+ Tham gia game</a>
					<hr>
                    <a href="giai/my_giai_list.php" class="btn btn-sm btn-outline-primary mt-1">➜ Game tôi tạo</a>
					<a href="payment/cron_cancel_online_bookings.php" class="btn btn-sm btn-outline-primary mt-1">➜ Cron cancel online booing</a>
                </div>
            </div>
        </div>

        <!-- Hồ câu -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-info shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title text-info">🐟 Quản lý tài khoản</h5>
                    <p class="card-text mb-1">
                        Số dư TK: <span class="badge bg-warning text-dark"><?=$so_ho_giai?></span> |
                        Doanh thu tháng: <span class="badge bg-success"><?=$so_ho_giai?></span>
					</p>
					
                    <p class="card-text mb-1">
                        Điểm EXP: <span class="badge bg-success"><?=$gi_to_chuc?></span> |
						Ref: <span class="badge bg-success"><?=$gi_to_chuc?></span>
                    </p>
					<a href="payment/payment_deposit.php" class="btn btn-sm btn-outline-primary mt-1">+ Nạp tiền</a>
					<a href="payment/payment_withdraw.php" class="btn btn-sm btn-outline-primary mt-1">- Rút tiền</a>
					<hr>
                    <a href="payment/payment_list.php" class="btn btn-sm btn-outline-primary mt-1">➜ Lịch sữ Payment</a>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
