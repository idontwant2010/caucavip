<?php
require_once __DIR__ . '/../../../../vendor/autoload.php'; // đường dẫn tùy theo cấu trúc project
ob_start(); // Chặn mọi output
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once '../../../connect.php';
require_once '../../../check_login.php';
require_once '../../../includes/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>Thiếu ID giải đấu.</div>";
    require_once '../../../includes/footer.php';
    exit;
}

$giai_id = (int)$_GET['id'];

// Truy vấn danh sách người chơi
$stmt = $pdo->prepare("SELECT u.id AS user_id, gu.nickname, gu.tong_diem, gu.tong_kg, gu.xep_hang
    FROM giai_user gu
    JOIN users u ON gu.user_id = u.id
    WHERE gu.giai_id = ?
    ORDER BY gu.xep_hang ASC");
$stmt->execute([$giai_id]);
$users = $stmt->fetchAll();

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header
$headers = ['#', 'Nickname', 'Tổng điểm', 'Tổng kg', 'Xếp hạng'];
for ($i = 1; $i <= 6; $i++) {
    $headers[] = "Hiệp $i - Bảng";
    $headers[] = "Hiệp $i - Vị trí";
    $headers[] = "Hiệp $i - Cá (kg)";
    $headers[] = "Hiệp $i - Vi phạm";
    $headers[] = "Hiệp $i - Tổng điểm";
}
$sheet->fromArray($headers, NULL, 'A1');

// Ghi dữ liệu
$row = 2;
$index = 1;
foreach ($users as $u) {
    $data = [
        $index++,
        $u['nickname'],
        $u['tong_diem'],
        $u['tong_kg'],
        $u['xep_hang']
    ];

    // Lấy điểm từng hiệp
    $stmtHiep = $pdo->prepare("SELECT so_bang, vi_tri_ngoi, so_kg, diem_cong_vi_pham, tong_diem
        FROM giai_schedule
        WHERE giai_id = ? AND user_id = ?
        ORDER BY so_hiep ASC");
    $stmtHiep->execute([$giai_id, $u['user_id']]);
    $hieps = $stmtHiep->fetchAll();

    for ($i = 1; $i <= 6; $i++) {
        if (isset($hieps[$i - 1])) {
            $data[] = $hieps[$i - 1]['so_bang'];
            $data[] = $hieps[$i - 1]['vi_tri_ngoi'];
            $data[] = $hieps[$i - 1]['so_kg'];
            $data[] = $hieps[$i - 1]['diem_cong_vi_pham'];
            $data[] = $hieps[$i - 1]['tong_diem'];
        } else {
            $data = array_merge($data, ['-', '-', '-', '-', '-']);
        }
    }

    $sheet->fromArray($data, NULL, 'A' . $row++);
}

// Đảm bảo không có dữ liệu in ra trước header
ob_end_clean();

$filename = "giai_{$giai_id}_tongket.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;