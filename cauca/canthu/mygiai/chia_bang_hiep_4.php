<?php
require_once '../../../connect.php';
require_once '../../../check_login.php';

// Cho phép debug bằng GET nếu có giai_id
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_GET['giai_id'])) {
    echo "<script>alert('Truy cập không hợp lệ'); window.location.href='../../canthu/';</script>";
    exit;
}
if (!isset($_POST['giai_id']) && isset($_GET['giai_id'])) {
    $_POST['giai_id'] = $_GET['giai_id'];
}

$giai_id = isset($_POST['giai_id']) ? (int)$_POST['giai_id'] : 0;
if ($giai_id <= 0) {
    echo "<script>alert('Thiếu thông tin giải'); window.location.href='my_giai_detail_step_2.php?id={$giai_id}';</script>";
    exit;
}

// Lấy thông tin giải
$stmt = $pdo->prepare("SELECT * FROM giai_list WHERE id = ?");
$stmt->execute([$giai_id]);
$giai = $stmt->fetch();
if (!$giai) {
    echo "<script>alert('Giải không tồn tại'); window.location.href='../../canthu/';</script>";
    exit;
}

// Kiểm tra đã chia hiệp 3 chưa
$stmt = $pdo->prepare("SELECT COUNT(*) FROM giai_schedule WHERE giai_id = ? AND so_hiep = 3 AND so_bang IN ('A','B','C','D','E','F','G','H','I','K','L','M')");
$stmt->execute([$giai_id]);
$da_chia_hiep_3 = $stmt->fetchColumn();
if (!$da_chia_hiep_3) {
    echo "<script>alert('Chưa chia bảng hiệp 3'); window.location.href='my_giai_detail_step_2.php?id={$giai_id}';</script>";
    exit;
}

// Tạo map xoay bảng
$so_bang = $giai['so_bang'];
$so_hiep = $giai['so_hiep'];

function tao_quy_luat_xoay($so_bang, $so_hiep) {
    if ($so_bang == 2 && $so_hiep == 2) {
        return ['A' => 'B', 'B' => 'A'];
    } 
	
	if ($so_bang == 4 && $so_hiep == 2) {
        // Quy luật xoay bảng cho 4 bảng, 2 hiệp
        return ['A' => 'C', 'B' => 'D', 'C' => 'A', 'D' => 'B'];
    }
	
    if ($so_bang == 3 && $so_hiep == 3) {
        return ['A' => 'B', 'B' => 'C', 'C' => 'A'];
    }
	
	if ($so_bang == 6 && $so_hiep == 3) {
        return ['A' => 'B', 'B' => 'C', 'C' => 'D', 'D' => 'E', 'E' => 'F', 'F' => 'A'];
    }
	
    if ($so_bang == 4 && $so_hiep == 4) {
        // Xoay vòng 4 bảng
        return ['A' => 'B', 'B' => 'C', 'C' => 'D', 'D' => 'A'];
    }
	
	if ($so_bang == 8 && $so_hiep == 4) {
	// Xoay vòng 4 bảng
	return ['A' => 'C', 'B' => 'D', 'C' => 'E', 'D' => 'F', 'E' => 'G', 'F' => 'H', 'G' => 'A', 'H' => 'B'];

	}
	
    if ($so_bang == 5 && $so_hiep == 5) {
        // Xoay vòng 4 bảng
        return ['A' => 'B', 'B' => 'C', 'C' => 'D', 'D' => 'E', 'E' => 'A'];
		
    }
	
	if ($so_bang == 10 && $so_hiep == 5) {
        // Xoay vòng 4 bảng
        return ['A' => 'C', 'B' => 'D', 'C' => 'E', 'D' => 'F', 'E' => 'G', 'F' => 'H', 'G' => 'I', 'H' => 'J', 'I' => 'A', 'J' => 'B'];

    }
	    if ($so_bang == 6 && $so_hiep == 6) {
        // Xoay vòng 4 bảng
        return ['A' => 'B', 'B' => 'C', 'C' => 'D', 'D' => 'E', 'E' => 'F', 'F' => 'A'];
		
    }
	
		if ($so_bang == 12 && $so_hiep == 6) {
		// Xoay vòng 4 bảng
		return ['A'=>'C','B'=>'D','C'=>'E','D'=>'F','E'=>'G','F'=>'H','G'=>'I','H'=>'J','I'=>'K','J'=>'L','K'=>'A','L'=>'B'];
	}

    // Mặc định: không đổi bảng
    return [];
}

$xoay_bang = tao_quy_luat_xoay($so_bang, $so_hiep);

// Lấy dữ liệu hiệp 3
$stmt = $pdo->prepare("SELECT user_id, so_bang, is_bien FROM giai_schedule WHERE giai_id = ? AND so_hiep = 3 ORDER BY so_bang, vi_tri_ngoi");
$stmt->execute([$giai_id]);
$ds_hiep3 = $stmt->fetchAll();

// Gom về hiệp 4 theo bảng mới
$bang_4 = [];
foreach ($ds_hiep3 as $row) {
    $bang_moi = $xoay_bang[$row['so_bang']];
    $bang_4[$bang_moi][] = [
        'user_id' => $row['user_id'],
        'was_bien' => $row['is_bien']
    ];
}

// Tiến hành chia vị trí cho hiệp 4
foreach ($bang_4 as $bang => $users) {
    $n = count($users);
    $vi_tri_middle = range(2, $n - 1); shuffle($vi_tri_middle);
    $vi_tri_bien = [1, $n]; shuffle($vi_tri_bien);

    $nhom_tung_ngoi_bien = [];
    $nhom_chua_ngoi_bien = [];

    foreach ($users as $u) {
        if ($u['was_bien'] == 1) {
            $nhom_tung_ngoi_bien[] = $u;
        } else {
            $nhom_chua_ngoi_bien[] = $u;
        }
    }

    $vi_tri_arr = [];

    // Gán người từng ngồi biên → giữa
    foreach ($nhom_tung_ngoi_bien as $u) {
        $vi_tri = array_pop($vi_tri_middle);
        $vi_tri_arr[] = [
            'user_id' => $u['user_id'],
            'so_bang' => $bang,
            'vi_tri_ngoi' => $vi_tri,
            'is_bien' => 0
        ];
    }

    // Gán phần còn lại
    $vi_tri_con_lai = array_merge($vi_tri_middle, $vi_tri_bien);
    shuffle($vi_tri_con_lai);

    foreach ($nhom_chua_ngoi_bien as $u) {
        $vi_tri = array_pop($vi_tri_con_lai);
        $is_bien = ($vi_tri == 1 || $vi_tri == $n) ? 1 : 0;
        $vi_tri_arr[] = [
            'user_id' => $u['user_id'],
            'so_bang' => $bang,
            'vi_tri_ngoi' => $vi_tri,
            'is_bien' => $is_bien
        ];
    }

    // Ghi vào DB
    foreach ($vi_tri_arr as $row) {
        $stmt2 = $pdo->prepare("UPDATE giai_schedule SET so_bang = ?, vi_tri_ngoi = ?, is_bien = ? WHERE giai_id = ? AND user_id = ? AND so_hiep = 4");
        $stmt2->execute([$row['so_bang'], $row['vi_tri_ngoi'], $row['is_bien'], $giai_id, $row['user_id']]);
    }
}

// ✅ Cập nhật trạng thái giải sang 'dang_dau_hiep_4'
$stmt = $pdo->prepare("UPDATE giai_list SET status = 'dang_dau_hiep_4' WHERE id = ?");
$stmt->execute([$giai_id]);

$hiep_hien_tai = null;
if (preg_match('/^dang_dau_hiep_(\d+)$/', $giai['status'], $matches)) {
    $hiep_hien_tai = (int) $matches[1] + 1 ; // ép kiểu cho chắc
}

echo "<script>alert('✅ Đã chia bảng và vị trí hiệp 4 thành công!'); window.location.href='my_giai_detail_step_2.php?id={$giai_id}&hiep={$hiep_hien_tai}';</script>";
