<?php
session_start();

// Nếu đã đăng nhập -> điều hướng theo vai trò (giữ nguyên logic cũ)
if (isset($_SESSION['user'])) {
    $role = $_SESSION['user']['vai_tro'];
    switch ($role) {
        case 'admin':
            header('Location: /cauca/admin/dashboard_admin.php'); break;
        case 'moderator':
            header('Location: /cauca/moderator/dashboard_moderator.php'); break;
        case 'canthu':
            header('Location: /cauca/canthu/dashboard_canthu.php'); break;
        case 'chuho':
            header('Location: /cauca/chuho/dashboard_chuho.php'); break;
        default:
            header('Location: /dashboard/index.php');
    }
    exit;
}

require_once __DIR__ . '/includes/header.php';
?>
<div class="container-fluid my-3 px-4">
  <div class="card shadow-lg border-2 rounded-3">
    <div class="card-body p-4">
	
		<div class="container">
		  <div class="text-center mb-1">
			<h2 class="display-5 fw-bold">🎣 Chào mừng bạn đến với Câu Đài Việt Nam</h2>
			<p class="lead text-muted">Hệ thống kết nối cần thủ và hồ câu trên toàn quốc – đặt vé, tham gia bảng xếp hạng, và thi đấu game câu cá</p>
		  </div>

		  <!-- 3 thẻ chức năng chính -->
		  <div class="row row-cols-1 row-cols-md-3 g-4 text-center">
			<div class="col">
			  <div class="card h-100 shadow-sm">
				<div class="card-body">
				  <h5 class="card-title">📅 Đặt vé hồ câu</h5>
				  <p class="card-text">Chọn hồ yêu thích, đặt vé nhanh chóng</p>
				  <a href="/auth/login.php" class="btn btn-primary">Đăng nhập để đặt vé</a>
				</div>
			  </div>
			</div>

			<div class="col">
			  <div class="card h-100 shadow-sm">
				<div class="card-body">
				  <h5 class="card-title">🏆 Bảng xếp hạng</h5>
				  <p class="card-text">Xem top cần thủ và thành tích</p>
				  <a href="/auth/login.php" class="btn btn-success">Đăng nhập để xem</a>
				</div>
			  </div>
			</div>

			<div class="col">
			  <div class="card h-100 shadow-sm">
				<div class="card-body">
				  <h5 class="card-title">🎮 Game câu cá</h5>
				  <p class="card-text">Tham gia giải, game cùng bạn câu</p>
				  <a href="/auth/login.php" class="btn btn-warning">Khám phá ngay</a>
				</div>
			  </div>
			</div>
		  </div>

		  <!-- 9 hồ câu mới nhất (3x3) -->
		  <div class="mt-3">
			<h3 class="text-center mb-1">🆕 9 Hồ câu mới nhất</h3>
			<div class="row row-cols-1 row-cols-md-3 g-4">
			  <?php
			  // Gợi ý: $pdo thường đã sẵn từ includes/header.php
			  try {
				$sql = "
				  SELECT h.id, h.ten_ho, h.luong_ca, h.so_cho_ngoi, h.status, h.created_at,
						 c.ten_cum_ho, c.dia_chi
				  FROM ho_cau h
				  JOIN cum_ho c ON h.cum_ho_id = c.id
				  WHERE h.status = 'dang_hoat_dong'
				  ORDER BY h.created_at DESC
				  LIMIT 9
				";
				$stmt = $pdo->query($sql);
				$hos = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];

				if (!empty($hos)):
				  foreach ($hos as $ho): ?>
					<div class="col">
					  <div class="card h-100 shadow-sm">
						<div class="card-body">
						  <h5 class="card-title mb-1"><?= htmlspecialchars($ho['ten_ho']) ?></h5>
						  <div class="text-muted small mb-2">
							Tạo lúc: <?= htmlspecialchars(date('d/m/Y H:i', strtotime($ho['created_at']))) ?>
						  </div>
						  <p class="card-text mb-1">
							<strong>Cụm hồ:</strong> <?= htmlspecialchars($ho['ten_cum_ho']) ?>
						  </p>
						  <p class="card-text mb-1">
							<strong>Địa chỉ:</strong> <?= htmlspecialchars($ho['dia_chi']) ?>
						  </p>
						  <p class="card-text small text-muted mb-0">
							🎣 <?= (int)($ho['luong_ca'] ?? 0) ?> cá ·
							🪑 <?= (int)($ho['so_cho_ngoi'] ?? 0) ?> chỗ
						  </p>
						</div>
						<div class="card-footer text-center">
						  <a href="/public/ho_cau_detail.php?id=<?= (int)$ho['id'] ?>" class="btn btn-outline-primary btn-sm">Xem và đặt vé</a>
						</div>
					  </div>
					</div>
				  <?php endforeach;
				else: ?>
				  <div class="col">
					<div class="alert alert-info text-center mb-0">Chưa có hồ nào ở trạng thái hoạt động.</div>
				  </div>
				<?php endif;
			  } catch (Throwable $e) { ?>
				<div class="col">
				  <div class="alert alert-danger text-center mb-0">
					Lỗi tải danh sách hồ: <?= htmlspecialchars($e->getMessage()) ?>
				  </div>
				</div>
			  <?php } ?>
			</div>
		  </div>

		  <!-- CTA đăng ký -->
		  <div class="text-center mt-4">
			<p>Bạn chưa có tài khoản?</p>
			<a href="/auth/register/step1_role.php" class="btn btn-outline-dark">Đăng ký ngay</a>
		  </div>
		</div>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php';?>
