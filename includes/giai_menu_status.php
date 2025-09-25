<?php
// ⚠️ Yêu cầu trước khi include: cần có $giai (mảng chứa thông tin giải)
if (!isset($giai) || !isset($giai['id'])) {
    echo "<div class='alert alert-danger'>Thiếu dữ liệu giải!</div>";
    return;
}

$giai_id = $giai['id'];
$current_status = $giai['status'] ?? '';
$so_luong_can_thu = (int)($giai['so_luong_can_thu'] ?? 0);

// Lấy số cần thủ đã đăng ký
$stmt = $pdo->prepare("SELECT COUNT(*) FROM giai_user WHERE giai_id = ?");
$stmt->execute([$giai_id]);
$so_can_thu_dk = (int) $stmt->fetchColumn();

switch ($giai['so_hiep']) {
  case 2:
	$link_step2 = "my_giai_detail_step_2.php?id={$giai_id}";
	$link_step3 = "my_giai_detail_step_3.php?id={$giai_id}";
    break;
  case 3:
	$link_step2 = "my_giai_detail_step_2.php?id={$giai_id}";
	$link_step3 = "my_giai_detail_step_3.php?id={$giai_id}";
    break;
  case 4:
  default:
	$link_step2 = "my_giai_detail_step_2.php?id={$giai_id}";
	$link_step3 = "my_giai_detail_step_3.php?id={$giai_id}";
    break;
}

function activeClass($statusKeys, $current) {
    if (is_array($statusKeys)) {
        return in_array($current, $statusKeys) ? 'active fw-bold text-white bg-primary' : 'text-dark';
    } else {
        return $statusKeys === $current ? 'active fw-bold text-white bg-primary' : 'text-dark';
    }
}

?>

<ul class="nav nav-pills mb-3 gap-1 flex-wrap" style="font-size: 14px;">
  <li class="nav-item" title="Bước đầu: Tạo giải và nhập thông tin">
    <a class="nav-link <?= activeClass(['dang_cho_xac_nhan', 'chuyen_chu_ho_duyet'], $current_status) ?>" 
       href="my_giai_detail.php?id=<?= $giai_id ?>">
      🔰 B0: Tạo giải > Gửi chủ hồ duyệt >
    </a>
  </li>
  <li class="nav-item" title="Bước 1: Đăng ký và chốt danh sách">
    <a class="nav-link <?= activeClass(['dang_mo_dang_ky'], $current_status) ?>" href="my_giai_detail_step_1.php?id=<?= $giai_id ?>">
      🟢 B1: Mở đăng ký > Chốt danh sách >
      <span class="badge bg-warning text-dark"><?= $so_can_thu_dk ?> / <?= $so_luong_can_thu ?></span>
    </a>
  </li>
  
    <li class="nav-item" title="Bước 2: Thi đấu và thêm, sửa điểm">
    <a class="nav-link <?= activeClass(['chot_xong_danh_sach', 'dang_dau_hiep_1', 'dang_dau_hiep_2', 'dang_dau_hiep_3', 'dang_dau_hiep_4'], $current_status) ?>" 
		href=<?= $link_step2 ?>>
		🎯 B2: Chia Bảng > Thi Đấu >
    </a>
	</li>

  <li class="nav-item" title="Bước 3: Hoàn thành và Bảo lưu kết quả">
  		<a class="nav-link <?= activeClass(['so_ket_giai','hoan_tat_giai'], $current_status) ?>" 
			href=<?= $link_step3 ?>>
			📊 B3: Sơ kết > Hoàn thành
		</a>

  </li>
</ul>
