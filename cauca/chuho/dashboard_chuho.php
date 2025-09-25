<?php
require_once '../../connect.php';
require_once '../../check_login.php';
require_once '../../includes/header.php';

if ($_SESSION['user']['vai_tro'] !== 'chuho') {
    echo "<div class='alert alert-danger'>Bạn không có quyền truy cập dashboard này.</div>";
    require_once '../../includes/footer.php';
    exit;
}

$chu_ho_id = $_SESSION['user']['id'];
// Booking hôm nay
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM booking b
    JOIN ho_cau h ON b.ho_cau_id = h.id
    JOIN cum_ho c ON h.cum_ho_id = c.id
    WHERE c.chu_ho_id = ? 
	AND DATE(b.booking_time) = CURDATE()
");
$stmt->execute([$chu_ho_id]);
$count_booking_today = $stmt->fetchColumn();

//Booking đang chờ chuyển
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM booking b
    JOIN ho_cau h ON b.ho_cau_id = h.id
    JOIN cum_ho c ON h.cum_ho_id = c.id
    WHERE c.chu_ho_id = ? 
	AND DATE(b.booking_time) = CURDATE()
	AND b.booking_status = 'Hoàn thành'
");
$stmt->execute([$chu_ho_id]);
$count_booking_completed_today = $stmt->fetchColumn();

//Tổng booking trong tháng hiện tại
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM booking b
    JOIN ho_cau h ON b.ho_cau_id = h.id
    JOIN cum_ho c ON h.cum_ho_id = c.id
    WHERE c.chu_ho_id = ? 
      AND MONTH(b.booking_time) = MONTH(CURDATE()) 
      AND YEAR(b.booking_time) = YEAR(CURDATE())
");
$stmt->execute([$chu_ho_id]);
$count_booking_this_month = $stmt->fetchColumn();

//Tổng booking trong tháng hiện tại/hoàn thành
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM booking b
    JOIN ho_cau h ON b.ho_cau_id = h.id
    JOIN cum_ho c ON h.cum_ho_id = c.id
    WHERE c.chu_ho_id = ? 
      AND MONTH(b.booking_time) = MONTH(CURDATE()) 
      AND YEAR(b.booking_time) = YEAR(CURDATE())
	  AND b.booking_status = 'Hoàn thành'
");
$stmt->execute([$chu_ho_id]);
$count_booking_this_month_comppleted = $stmt->fetchColumn();

// Số giải chờ duyệt
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM giai_list g
    JOIN ho_cau h ON g.ho_cau_id = h.id
    JOIN cum_ho c ON h.cum_ho_id = c.id
    WHERE c.chu_ho_id = ? AND g.status = 'chuyen_chu_ho_duyet'
");
$stmt->execute([$chu_ho_id]);
$count_giai_cho_duyet = $stmt->fetchColumn();

// Số giải đang diễn ra
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM giai_list g
    JOIN ho_cau h ON g.ho_cau_id = h.id
    JOIN cum_ho c ON h.cum_ho_id = c.id
    WHERE c.chu_ho_id = ? 
		AND g.status NOT IN ('huy_giai', 'hoan_tat_giai', 'dang_cho_xac_nhan', 'chuyen_chu_ho_duyet')
");
$stmt->execute([$chu_ho_id]);
$count_giai_dang_dien_ra = $stmt->fetchColumn();



// Số giải đã hoàn thành
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM giai_list g
    JOIN ho_cau h ON g.ho_cau_id = h.id
    JOIN cum_ho c ON h.cum_ho_id = c.id
    WHERE c.chu_ho_id = ? AND g.status = 'hoan_tat_giai'
");
$stmt->execute([$chu_ho_id]);
$count_giai_hoan_tat = $stmt->fetchColumn();

// Số giải liên quan hồ
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM giai_list g
    JOIN ho_cau h ON g.ho_cau_id = h.id
    JOIN cum_ho c ON h.cum_ho_id = c.id
    WHERE c.chu_ho_id = ?
");
$stmt->execute([$chu_ho_id]);
$count_tong_giai = $stmt->fetchColumn();


// Game đang mở
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM game_list g
    JOIN ho_cau h ON g.ho_cau_id = h.id
    JOIN cum_ho c ON h.cum_ho_id = c.id
    WHERE c.chu_ho_id = ? AND g.status = 'dang_dien_ra'
");
$stmt->execute([$chu_ho_id]);
$count_game_dang_dien_ra = $stmt->fetchColumn();

// Game chờ chốt danh sách 
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM game_list g
    JOIN ho_cau h ON g.ho_cau_id = h.id
    JOIN cum_ho c ON h.cum_ho_id = c.id
    WHERE c.chu_ho_id = ? AND g.status = 'da_chot_danh_sach'
");
$stmt->execute([$chu_ho_id]);
$count_game_cho_chot = $stmt->fetchColumn();

//Game đã hoàn tất
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM game_list g
    JOIN ho_cau h ON g.ho_cau_id = h.id
    JOIN cum_ho c ON h.cum_ho_id = c.id
    WHERE c.chu_ho_id = ? AND g.status = 'hoan_tat'
");
$stmt->execute([$chu_ho_id]);
$count_game_hoan_tat = $stmt->fetchColumn();

// Đếm số cụm hồ
$stmt = $pdo->prepare("SELECT COUNT(*) FROM cum_ho WHERE chu_ho_id = ?");
$stmt->execute([$chu_ho_id]);
$count_cum_ho = $stmt->fetchColumn();

// Đếm số hồ câu
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM ho_cau h
    JOIN cum_ho c ON h.cum_ho_id = c.id
    WHERE c.chu_ho_id = ?
");
$stmt->execute([$chu_ho_id]);
$count_ho_cau = $stmt->fetchColumn();

// Đếm tổng bảng giá đang mở
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM gia_ca_thit_phut g
    JOIN ho_cau h ON g.ho_cau_id = h.id
    JOIN cum_ho c ON h.cum_ho_id = c.id
    WHERE c.chu_ho_id = ? AND g.status = 'open'
");
$stmt->execute([$chu_ho_id]);
$count_bang_gia_open = $stmt->fetchColumn();


// Số giải chủ hồ tổ chức
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM giai_list g
    WHERE g.creator_id = :chu_ho_id
");
$stmt->execute(['chu_ho_id' => $chu_ho_id]);
$count_chu_ho_to_chuc = (int)$stmt->fetchColumn();

?>

<div class="container py-3">
    <h3 class="mb-4">🎛️ Phần mềm quản lý - Chủ hồ câu</h3>

    <div class="row g-3">
        <!-- Booking -->
        <div class="col-md-6 col-lg-6">
            <div class="card border-success shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title text-success">📦 Phầm mềm quản lý vé câu tại hồ (POS)</h5>
                    <p class="card-text mb-1">
                        Số vé hôm nay: <span class="badge bg-info"><?=$count_booking_today?></span>
						Hoàn thành: <span class="badge bg-success text-light"><?=$count_booking_completed_today?></span>
					</p>
                    <p class="card-text mb-1">
                        Số vé 30 ngày: <span class="badge bg-info "><?=$count_booking_this_month?></span>
						Hoàn thành: <span class="badge bg-success text-light"><?=$count_booking_this_month_comppleted?></span>
                    </p>
					<a href="booking/booking_list.php" class="btn btn-sm btn-outline-success mt-1">+ Tạo/xem vé câu</a> ➜
					<a href="booking/chu_ho_booking_list_all.php" class="btn btn-sm btn-outline-success mt-1">➜ Tất cả vé câu</a>  

				</div>
            </div>
        </div>
		
        <!-- Hồ câu -->
        <div class="col-md-6 col-lg-6">
            <div class="card border-danger shadow-sm">
                <div class="card-body text-center ">
                    <h5 class="card-title text-dark mb-2">🐟 Quản lý cụm, hồ câu, bảng giá</h5>
					
                    <p class="card-text mb-2 ">
						<a href="cum_ho/cum_ho_list.php" class="btn btn-sm btn-outline-danger mt-1"> Cụm hồ</a> <span class="badge bg-danger"><?=$count_cum_ho?></span> ➜
						<a href="ho_cau/ho_cau_list.php" class="btn btn-sm btn-outline-danger mt-1">➜ Hồ câu</a> <span class="badge bg-danger"><?=$count_ho_cau?></span>
						</p>
                    <p class="card-text mb-2 ">
						<a href="gia/gia_ho_list.php" class="btn btn-sm btn-outline-danger mt-1">➜ Bảng giá:</a> <span class="badge bg-danger"><?=$count_bang_gia_open?></span>
					</p>					
					
                </div>
            </div>
        </div>
		
        <!-- Giải đấu -->
        <div class="col-md-6 col-lg-6">
            <div class="card border-primary shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title text-primary">🏆 Phầm mềm quản lý giải câu </h5>
                    <p class="card-text mb-1">
                        Chờ duyệt: <span class="badge bg-warning text-dark"><?=$count_giai_cho_duyet?></span>
                        Đang diễn ra: <span class="badge bg-success"><?=$count_giai_dang_dien_ra?></span>
					</p>
					
                    <p class="card-text mb-1">
                        Đã tổ chức: <span class="badge bg-success"><?=$count_tong_giai?></span>
						Giải tôi tạo: <span class="badge bg-success"><?=$count_chu_ho_to_chuc?></span>
                    </p>
					 <a href="giai/giai_ho_cau.php" class="btn btn-sm btn-outline-primary mt-1">+ Tạo Giải</a>
					<a href="mygiai/my_giai_list.php" class="btn btn-sm btn-outline-primary mt-1">➜ Danh sách giải</a>
					<hr>
                    <a href="giai/giai_can_duyet.php" class="btn btn-sm btn-outline-primary mt-1">➜ Duyệt giải của hồ</a>
				</div>
            </div>
        </div>

        <!-- Game -->
        <div class="col-md-6 col-lg-6">
            <div class="card border-dark shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title text-dark">🎮 Phầm mềm quản lý câu games</h5>
                    <p class="card-text mb-1">
                        Chờ duyệt: <span class="badge bg-warning text-dark"><?=$count_giai_cho_duyet?></span>
                        Đang diễn ra: <span class="badge bg-success"><?=$count_giai_dang_dien_ra?></span>
					</p>
					
                    <p class="card-text mb-1">
                        Đã tổ chức: <span class="badge bg-success"><?=$count_tong_giai?></span>
						Games tôi tạo: <span class="badge bg-success"><?=$count_chu_ho_to_chuc?></span>
                    </p>
					 <a href="game/game_list_ho.php" class="btn btn-sm btn-outline-dark mt-1">+ Tạo game</a>
					 <a href="game/game_list.php" class="btn btn-sm btn-outline-dark mt-1">➜ Tất cả game</a>					 
					<hr>
                    <a href="giai/giai_can_duyet.php" class="btn btn-sm btn-outline-dark mt-1">➜ Xác nhận Games</a>
					
                </div>
            </div>
        </div>


    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>


<p class="card-text mb-1">
    Cụm hồ đang quản lý: <a href="cum_ho/cum_ho_list.php"> <span class="badge bg-primary"><?= $count_cum_ho ?></span></a>
</p>