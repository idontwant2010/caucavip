<?php
// caucavip/cron/cancel_expired_deposits.php
// Hủy các lệnh NẠP (deposit) đang pending quá hạn, ghi log ra file + STDOUT
// deposit > 30 phút, cron chạy 5 phút/ lần
require __DIR__ . '/../connect.php';
date_default_timezone_set('Asia/Ho_Chi_Minh');

// ====== cấu hình ======
$EXPIRE_MINUTES = 30;  // mặc định 30 phút

// Cho phép override qua CLI: --minutes=45 --debug=1
$DEBUG = false;
foreach ($argv ?? [] as $arg) {
    if (str_starts_with($arg, '--minutes=')) {
        $EXPIRE_MINUTES = max(1, (int)substr($arg, 10));
    } elseif ($arg === '--debug=1') {
        $DEBUG = true;
    }
}

// Đường dẫn log chung
$LOG_DIR  = realpath(__DIR__ . '/../logs') ?: (__DIR__ . '/../logs');
if (!is_dir($LOG_DIR)) @mkdir($LOG_DIR, 0777, true);
$LOG_FILE = $LOG_DIR . '/cancel_expired_deposits.log';

// Helper log
function log_line($msg) {
    global $LOG_FILE;
    $line = '[' . date('Y-m-d H:i:s') . '] ' . $msg . PHP_EOL;
    file_put_contents($LOG_FILE, $line, FILE_APPEND);
    echo $line;
}

try {
    log_line("Start cancel_expired_deposits (expire={$EXPIRE_MINUTES}m)");

    // Hủy các lệnh NẠP pending quá N phút
    $sql = "
        UPDATE payments
           SET status        = 'canceled',
               cancel_reason = 'timeout',
               cancelled_at  = NOW(),
               updated_at    = NOW()
         WHERE loai_giao_dich = 'deposit'
           AND status          = 'pending'
           AND created_at     <= (NOW() - INTERVAL :mins MINUTE)
    ";
    $st = $pdo->prepare($sql);
    $st->bindValue(':mins', (int)$EXPIRE_MINUTES, PDO::PARAM_INT);
    $st->execute();
    $affected = $st->rowCount();

    log_line("Canceled pending deposits: {$affected}");

    // (tuỳ chọn) thêm thống kê nhanh khi debug
    if ($DEBUG) {
        $q = $pdo->query("SELECT COUNT(*) FROM payments WHERE loai_giao_dich='deposit' AND status='pending'");
        $pendingLeft = (int)$q->fetchColumn();
        log_line("Pending deposits remaining: {$pendingLeft}");
    }

    log_line("Done");
} catch (Throwable $e) {
    log_line("ERROR: " . $e->getMessage());
    // Nếu muốn, có thể exit với mã lỗi khác 0 để Task Scheduler đánh dấu failed
    exit(1);
}
