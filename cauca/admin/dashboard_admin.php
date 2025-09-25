<?php
require_once __DIR__ . '/../../connect.php';
require_once __DIR__ . '/../../check_login.php';

if ($_SESSION['user']['vai_tro'] !== 'admin') {
    header("Location: /");
    exit;
}

// Lấy tổng số lượng
$so_nguoi_dung = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$so_ho_cau = $pdo->query("SELECT COUNT(*) FROM ho_cau")->fetchColumn();
$so_game = $pdo->query("SELECT COUNT(*) FROM game_list")->fetchColumn();
$so_giai = $pdo->query("SELECT COUNT(*) FROM giai_list")->fetchColumn();
$so_hinh_thuc_game = $pdo->query("SELECT COUNT(*) FROM giai_game_hinh_thuc")->fetchColumn();
$admin_config_keys = $pdo->query("SELECT COUNT(*) FROM admin_config_keys")->fetchColumn();
$so_xa_phuong = $pdo->query("SELECT COUNT(*) FROM dm_xa_phuong")->fetchColumn();
$so_tinh = $pdo->query("SELECT COUNT(*) FROM dm_tinh")->fetchColumn();
$so_cum_ho = $pdo->query("SELECT COUNT(*) FROM cum_ho")->fetchColumn();
?>

<?php include_once __DIR__ . '/../../includes/header.php'; ?>

<div class="container mt-4">
    <h3 class="mb-4">🎯 Bảng điều khiển - Quản trị hệ thống</h3>

    <div class="row g-4">
        <div class="col-md-3">
            <div class="card border-success shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Người dùng</h5>
                    <p class="card-text fs-4 fw-bold"><?= $so_nguoi_dung ?> Users</p>
                    <a href="/cauca/admin/user/users_list.php" class="btn btn-outline-success btn-sm">Xem chi tiết</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-primary shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Hồ và Cụm Hồ</h5>
                    <p class="card-text fs-4 fw-bold"><?= $so_ho_cau ?> hồ - <?= $so_cum_ho ?> cụm hồ</p>
                    <a href="/cauca/admin/ho_cumho/ho_cau_list.php" class="btn btn-outline-primary btn-sm">Xem hồ</a>
					<a href="/cauca/admin/ho_cumho/cum_ho_list.php" class="btn btn-outline-primary btn-sm">Xem cụm hồ</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-warning shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Xem tất cả game</h5>
                    <p class="card-text fs-4 fw-bold"><?= $so_game ?> Games</p>
                    <a href="/cauca/admin/Games/game_list.php" class="btn btn-outline-warning btn-sm">Xem game</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-warning shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Xem tất cả giải</h5>
                    <p class="card-text fs-4 fw-bold"><?= $so_giai ?> Giải</p>
                    <a href="/cauca/admin/Giai/reset_bang.php" class="btn btn-outline-warning btn-sm">giải-reset bảng</a>
					<a href="/cauca/admin/Giai/xoa_giai_schedule.php" class="btn btn-outline-warning btn-sm">xoá giải schedule</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-info shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Hình thức game</h5>
                    <p class="card-text fs-4 fw-bold"><?= $so_hinh_thuc_game ?> hình thức</p>
                    <a href="/cauca/admin/Games/admin_game_hinh_thuc_list.php" class="btn btn-outline-info btn-sm">Quản lý</a>
                </div>
            </div>
        </div>
		
        <div class="col-md-3">
            <div class="card border-success shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Danh sách Phường Xã</h5>
                    <p class="card-text fs-5 fw-bold"><?= $so_tinh ?> tỉnh <?= $so_xa_phuong ?> xã/phường </p>
                    <a href="/cauca/admin/xa_phuong/xa_phuong_list.php" class="btn btn-outline-success btn-sm">Quản lý</a>
                </div>
            </div>
        </div>
		
		        <div class="col-md-3">
            <div class="card border-info shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Cấu hình Admin</h5>
                    <p class="card-text fs-4 fw-bold"><?= $admin_config_keys ?> biến</p>
                    <a href="/cauca/admin/config_keys/admin_config_keys_list.php" class="btn btn-outline-info btn-sm">Quản lý</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
