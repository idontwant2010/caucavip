<?php
require_once __DIR__ . '/../../../connect.php';
require_once __DIR__ . '/../../../check_login.php';
include __DIR__ . '/../../../includes/header.php';

if ($_SESSION['user']['vai_tro'] !== 'chuho') {
    header("Location: /");
    exit;
}

$chuho_id = (int)$_SESSION['user']['id'];

/*
 * Đếm POS & Online theo ho_cau:
 * - POS:     nguoi_tao_id = :chuho_id
 * - Online:  can_thu_id IS NOT NULL AND nguoi_tao_id = can_thu_id
 */
$sql = "
SELECT
    c.id AS cum_id,
    c.ten_cum_ho,
    c.dia_chi,
    h.id   AS ho_id,
    h.ten_ho,
    h.status,
    IFNULL(pos.cnt_pos, 0)     AS cnt_pos_30d,
    IFNULL(onl.cnt_online, 0)  AS cnt_online_30d
FROM ho_cau h
JOIN cum_ho c ON c.id = h.cum_ho_id
LEFT JOIN (
    SELECT ho_cau_id, COUNT(*) AS cnt_pos
    FROM booking
    WHERE nguoi_tao_id = :chuho_id_pos
      AND COALESCE(real_start_time, booking_start_time, booking_time) >= :from_date_pos
      AND COALESCE(real_start_time, booking_start_time, booking_time) <  NOW()
    GROUP BY ho_cau_id
) pos ON pos.ho_cau_id = h.id
LEFT JOIN (
    SELECT ho_cau_id, COUNT(*) AS cnt_online
    FROM booking
    WHERE can_thu_id IS NOT NULL
      AND nguoi_tao_id = can_thu_id
      AND COALESCE(real_start_time, booking_start_time, booking_time) >= :from_date_onl
      AND COALESCE(real_start_time, booking_start_time, booking_time) <  NOW()
    GROUP BY ho_cau_id
) onl ON onl.ho_cau_id = h.id
WHERE c.chu_ho_id = :chuho_id_where
  AND c.status = 'dang_chay'
  AND h.status = 'dang_hoat_dong'
  AND EXISTS (
      SELECT 1
      FROM gia_ca_thit_phut g
      WHERE g.ho_cau_id = h.id
        AND g.status = 'open'
  )
ORDER BY c.ten_cum_ho ASC, h.ten_ho ASC
";

$fromDate = (new DateTime('-30 days'))->format('Y-m-d 00:00:00');

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':chuho_id_pos'   => $chuho_id,
    ':from_date_pos'  => $fromDate,
    ':from_date_onl'  => $fromDate,
    ':chuho_id_where' => $chuho_id,
]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gom theo cụm
$cum_groups = [];
foreach ($rows as $r) {
    $cid = $r['cum_id'];
    if (!isset($cum_groups[$cid])) {
        $cum_groups[$cid] = [
            'ten_cum_ho' => $r['ten_cum_ho'],
            'dia_chi'    => $r['dia_chi'],
            'ho_list'    => []
        ];
    }
    $cum_groups[$cid]['ho_list'][] = $r;
}

?>
<div class="container py-4">
    <h5 class="mb-4">🎣 Hồ câu đang hoạt động</h5>

    <?php if (empty($cum_groups)): ?>
        <div class="alert alert-info">Bạn chưa có hồ câu nào được quản lý.</div>
    <?php else: ?>
        <?php foreach ($cum_groups as $cum): ?>
            <div class="mb-5">
                <h6 class="mb-1"><?= htmlspecialchars($cum['ten_cum_ho']) ?> (đang hoạt động)</h6>
                <p class="text-muted mb-3">
                    <i class="bi bi-geo-alt"></i>
                    <?= htmlspecialchars($cum['dia_chi']) ?>
                </p>

                <div class="row g-4">
                    <?php foreach ($cum['ho_list'] as $ho): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card shadow-sm h-100 border-4 rounded-3">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title mb-2 text-center"><?= htmlspecialchars($ho['ten_ho']) ?></h6>

                                    <div class="mb-3 d-flex gap-2 flex-wrap justify-content-center align-items-center">
                                        <?php if ($ho['status'] === 'dang_hoat_dong'): ?>
                                            <span class="badge bg-success">Hoạt động</span>
                                        <?php elseif ($ho['status'] === 'chuho_tam_khoa'): ?>
                                            <span class="badge bg-danger">Chủ hồ tạm khoá</span>
                                        <?php elseif ($ho['status'] === 'admin_tam_khoa'): ?>
                                            <span class="badge bg-danger">Admin tạm khoá</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Tạm Đóng</span>
                                        <?php endif; ?>

                                        <!-- POS: chủ hồ tạo -->
                                        <a class="badge bg-danger text-decoration-none"
                                           href="chu_ho_booking_list.php?ho_id=<?= (int)$ho['ho_id'] ?>">
                                            ➜ Xem vé của hồ này
                                        </a>
                                    </div>
									
                                    <div class="mb-3 d-flex gap-2 flex-wrap justify-content-center align-items-center">
										 <a class="badge bg-success text-decoration-none"
                                           href="chu_ho_booking_list.php?ho_id=<?= (int)$ho['ho_id'] ?>">
                                             Vé POS: <?= (int)$ho['cnt_pos_30d'] ?>
                                        </a>

										 <a class="badge bg-warning text-decoration-none"
                                           href="chu_ho_booking_list.php?ho_id=<?= (int)$ho['ho_id'] ?>">
                                             Vé Online: <?= (int)$ho['cnt_online_30d'] ?>
                                        </a>
                                    </div>									

                                    <div class="mt-auto">
                                        <a href="booking_create.php?ho_id=<?= (int)$ho['ho_id'] ?>"
                                           class="btn btn-primary w-100">
                                            + Tạo vé câu POS tại hồ
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/../../../includes/footer.php'; ?>
