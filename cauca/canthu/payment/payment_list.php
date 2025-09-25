<?php
require '../../../check_login.php';
require '../../../connect.php';
include '../../../includes/header.php';

$uid = $_SESSION['user']['id'];

// --- Lọc 60 ngày gần nhất
$fromDate = (new DateTime('-60 days'))->format('Y-m-d 00:00:00');

// Lấy tất cả giao dịch 60 ngày của user
$st = $pdo->prepare("
  SELECT *
  FROM payments
  WHERE user_id = ?
    AND created_at >= ?
    AND created_at < NOW()
  ORDER BY created_at DESC
");
$st->execute([$uid, $fromDate]);
$rows = $st->fetchAll(PDO::FETCH_ASSOC);

// Chia 2 nhóm
$deposits  = array_filter($rows, fn($r) => ($r['loai_giao_dich'] ?? '') === 'deposit');
$withdraws = array_filter($rows, fn($r) => ($r['loai_giao_dich'] ?? '') === 'withdraw');

// Tab đang chọn (mặc định: deposit)
$activeTab = $_GET['tab'] ?? 'deposit';
if (!in_array($activeTab, ['deposit','withdraw'], true)) $activeTab = 'deposit';

function badgeClass($status) {
  return $status === 'completed' ? 'bg-success'
       : ($status === 'pending' ? 'bg-warning text-dark'
       : (($status === 'failed' || $status === 'canceled') ? 'bg-danger' : 'bg-secondary'));
}
?>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Lịch sử nạp / rút tiền (60 ngày gần nhất)</h4>
    <div class="text-muted small">Từ <?= htmlspecialchars($fromDate) ?> → nay</div>
  </div>

  <!-- Tabs -->
  <ul class="nav nav-tabs mb-3">
    <li class="nav-item">
      <a class="nav-link <?= $activeTab==='deposit'?'active':'' ?>" href="?tab=deposit">Nạp tiền</a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?= $activeTab==='withdraw'?'active':'' ?>" href="?tab=withdraw">Rút tiền</a>
    </li>
  </ul>

  <!-- Nội dung tab -->
  <?php
    $list = ($activeTab === 'deposit') ? $deposits : $withdraws;
  ?>
  <div class="card border-3 shadow-sm">
    <div class="card-header fw-semibold">
      <?= $activeTab==='deposit' ? 'Giao dịch Nạp tiền' : 'Giao dịch Rút tiền' ?>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th style="width:120px">Mã GD</th>
              <th style="width:110px">Phương thức</th>
              <th class="text-end">Số tiền</th>
              <th class="text-end">Phí</th>
              <th class="text-end">Thực cộng/trừ</th>
              <th style="width:110px">Trạng thái</th>
              <th style="width:160px">Thời gian</th>
              <th style="width:100px">Xem thêm</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($list)): ?>
              <tr>
                <td colspan="8" class="text-center text-muted">Chưa có giao dịch nào trong 60 ngày.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($list as $r): ?>
                <?php $badge = badgeClass($r['status'] ?? ''); ?>
                <tr>
                  <td><?= htmlspecialchars($r['payment_code']) ?></td>
                  <td><?= htmlspecialchars($r['method']) ?></td>
                  <td class="text-end"><?= number_format((float)($r['amount'] ?? 0)) ?> đ</td>
                  <td class="text-end"><?= number_format((float)($r['fee_amount'] ?? 0)) ?> đ</td>
                  <td class="text-end"><?= number_format((float)($r['delta_amount'] ?? 0)) ?> đ</td>
                  <td><span class="badge <?= $badge ?>"><?= htmlspecialchars($r['status']) ?></span></td>
                  <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($r['created_at'] ?? 'now'))) ?></td>
                  <td>
                    <a href="payment_detail.php?id=<?= (int)$r['id'] ?>" class="btn btn-sm btn-outline-secondary">Chi tiết</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include '../../../includes/footer.php'; ?>