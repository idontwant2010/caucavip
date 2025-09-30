<?php
// cauca/chuho/game/game_detail.php
require_once '../../../connect.php';
require_once '../../../check_login.php';
require_once '../../../includes/header.php';

$gameId = (int)($_GET['game_id'] ?? $_GET['id'] ?? 0);
if ($gameId <= 0) {
  http_response_code(400);
  exit('Thiếu tham số game_id.');
}

$seeded = (int)($_GET['seeded'] ?? 0);

/* 1) Lấy thông tin game (dùng positional '?') */
$st = $pdo->prepare("SELECT id, hinh_thuc_id, status, ten_game, so_luong_can_thu FROM game_list WHERE id = ? LIMIT 1");
$st->execute([$gameId]);
$game = $st->fetch(PDO::FETCH_ASSOC);


if (!$game) {
  http_response_code(404);
  exit('Game không tồn tại hoặc đã bị xóa.');
}
// Lấy tên game (null-safe)
$ten_game = $game['ten_game'] ?? '';
$so_luong_can_thu = (int)($game['so_luong_can_thu'] ?? 0);

/* 2) Đếm người đang tham gia (positional all the way) */
$valid = ['cho_xac_nhan', 'xac_nhan', 'da_thanh_toan'];
$in = implode(',', array_fill(0, count($valid), '?'));
$st = $pdo->prepare("SELECT COUNT(*) FROM game_user WHERE game_id = ? AND trang_thai IN ($in)");
$st->execute(array_merge([$gameId], $valid));
$totalPlayers = (int)$st->fetchColumn();

/* 3) Lấy danh sách ghế từ game_user (KHÔNG dùng :gid, dùng '?') */
$sql = "SELECT gu.user_id, gu.vi_tri_ngoi, gu.is_bien,
               COALESCE(gu.tong_kg,0)  AS tong_kg,
               COALESCE(gu.xep_hang,0) AS xep_hang,
               u.full_name, u.phone, COALESCE(gu.nickname,u.nickname) AS nickname
        FROM game_user gu
        JOIN users u ON u.id = gu.user_id
        WHERE gu.game_id = ? AND gu.trang_thai IN ($in)
        ORDER BY gu.vi_tri_ngoi ASC, gu.user_id ASC";

$st = $pdo->prepare($sql);
$st->execute(array_merge([$gameId], $valid));
$list = $st->fetchAll(PDO::FETCH_ASSOC);

$hasSeats = !empty($list) && (int)($list[0]['vi_tri_ngoi'] ?? 0) > 0;
$manageUrl = "game_manage.php?game_id=" . $gameId;

// Mapping trạng thái
$statusLabels = [
  'dang_mo_dang_ky'   => 'Đang mở đăng ký',
  'dang_thi_dau_game' => 'Đang thi đấu game',
  'so_ket_game'       => 'Sơ kết game',
  'hoan_tat_game'     => 'Hoàn tất game',
  'huy_game'         => 'Huỷ game',
];

// Lấy trạng thái gốc
$currentStatus = $game['status'] ?? '';
// Dịch sang tiếng Việt (nếu có)
$label = $statusLabels[$currentStatus] ?? $currentStatus;

?>
<!doctype html>
<html lang="vi">

<head>
  <meta charset="utf-8">
  <title>Quản lý game #<?= htmlspecialchars($gameId) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body class="bg-dark text-light">
  <div class="container py-3">

    <div class="card border-0 shadow-lg">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div class="fw-bold">Game #<?= htmlspecialchars($gameId) ?> 
		• Tên Game <?= htmlspecialchars($ten_game) ?> </div>
        <div class="small text-muted">Người tham gia: <?= (int)$totalPlayers ?>/<?= htmlspecialchars($so_luong_can_thu) ?> cần thủ</div>
      </div>
      <div class="card-body">

        <?php if ($seeded): ?>
          <div class="alert alert-success py-2">Đã random vị trí Game.</div>
        <?php endif; ?>

        <?php if (!$hasSeats): ?>
          <div class="alert alert-warning">
            Chưa có lịch hiệp 1. Bấm <b>Random &amp; gán chỗ</b> để tạo.
          </div>
          <?php if ($totalPlayers < 2): ?>
            <div class="alert alert-danger py-2">Cần tối thiểu 2 người để sắp chỗ. Hiện có: <?= (int)$totalPlayers ?></div>
          <?php endif; ?>

          <a class="btn btn-primary"
            href="<?= htmlspecialchars($manageUrl) ?>"
            <?= $totalPlayers < 2 ? 'onclick="return false;" aria-disabled="true"' : '' ?>>
            Random &amp; gán chỗ (hiệp 1)
          </a>
        <?php else: ?>
          <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
            <div>
              <span class="badge text-bg-secondary me-2">Trạng thái:</span>
              <?php if (!empty($game['status'])): ?>
                <span class="badge text-bg-info ms-2"> <?= htmlspecialchars($label) ?></span>
              <?php endif; ?>
            </div>
            <div class="d-flex gap-2">
              <a class="btn btn-outline-light btn-sm" href="<?= htmlspecialchars($manageUrl) ?>">Re-random</a>
              <a class="btn btn-outline-secondary btn-sm" href="game_list.php">Quay lại danh sách</a>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-sm table-dark table-striped align-middle mb-3">
              <thead>
                <tr>
                  <th class="text-nowrap">Ghế</th>
                  <th>Người chơi</th>
                  <th class="text-nowrap">Nickname</th>
                  <th class="text-nowrap">Điện thoại</th>
                  <th class="text-nowrap text-center">Ghi chú</th>
                  <th class="text-nowrap text-end">Tổng kg</th>
                  <th class="text-nowrap text-center">Hạng</th>
                  <th class="text-nowrap text-center">Điền &amp; cập nhật</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $N = count($list ?? []);
                foreach ($list as $row):
                  $seat  = (int)$row['vi_tri_ngoi'];
                  $isBien = (int)$row['is_bien'] === 1;
                  $uid   = (int)$row['user_id'];
                  $kg    = (float)$row['tong_kg'];
                  $rank  = (int)($row['xep_hang'] ?? 0);
                ?>
                  <tr data-uid="<?= $uid ?>">
                    <td class="fw-semibold"><?= $seat ?></td>
                    <td><?= htmlspecialchars($row['full_name'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($row['nickname'] ?? '—') ?></td>
                    <td class="text-nowrap"><?= htmlspecialchars($row['phone'] ?? '') ?></td>
                    <td class="text-center">
                      <?php if ($isBien): ?>
                        <span class="badge text-bg-warning">BIÊN</span>
                      <?php else: ?>
                        <span class="text-muted">—</span>
                      <?php endif; ?>
                    </td>

                    <!-- Tổng kg hiển thị -->
                    <td class="text-end">
                      <span class="kg-display"><?= number_format($kg, 2) ?></span>
                    </td>

                    <!-- Hạng -->
                    <td class="text-center">
                      <?= $rank > 0 ? $rank : '—' ?>
                    </td>

                    <!-- Ô nhập & nút cập nhật kg -->
                    <td class="text-center" style="min-width:180px;">
                      <div class="d-flex gap-2 justify-content-end">
                        <input type="number" step="0.01" min="0"
                          class="form-control form-control-sm text-end kg-input"
                          value="<?= htmlspecialchars(number_format($kg, 2, '.', '')) ?>"
                          style="max-width:100px;">
                        <button class="btn btn-sm btn-success btn-update-kg">Cập nhật</button>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

		<div class="d-flex flex-wrap gap-2">
		  <?php if ($game['status'] !== 'hoan_tat_game'): ?>
			<button id="btn-bulk-save" class="btn btn-outline-primary">Cập nhật tất cả kg</button>
			<button id="btn-update-rank" class="btn btn-warning">Xếp hạng lại</button>
			<button id="btn-finish" class="btn btn-danger">Hoàn tất và đóng game</button>
		  <?php else: ?>
<a class="btn btn-success"
   href="/cauca/chuho/game/copy_game_A.php?game_id=<?= (int)$game['id'] ?>"
   onclick="return confirmCopy('A', this);">
  (A) Copy game nhanh, giữ nguyên danh sách!
</a>

<a class="btn btn-secondary"
   href="/cauca/chuho/game/copy_game_B.php?game_id=<?= (int)$game['id'] ?>"
   onclick="return confirmCopy('B', this);">
  (B) Copy game chuẩn, thêm bớt danh sách!
</a>
		  <?php endif; ?>
		</div>


        <?php endif; ?>

      </div>
    </div>
  </div>

  <script>
    (function() {
      const gameId = <?= (int)$gameId ?>;

      // Helper gọi API POST JSON
      async function postJSON(url, data) {
        const res = await fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(data)
        });
        return res.json();
      }

      // Cập nhật 1 dòng kg
      document.querySelectorAll('.btn-update-kg').forEach(btn => {
        btn.addEventListener('click', async (e) => {
          const tr = e.target.closest('tr');
          const uid = parseInt(tr.dataset.uid, 10);
          const kgInput = tr.querySelector('.kg-input');
          const kg = parseFloat(kgInput.value || '0');

          if (isNaN(kg) || kg < 0) {
            alert('Vui lòng nhập số kg hợp lệ (>= 0).');
            return;
          }

          const data = await postJSON('game_update_kg.php', {
            game_id: gameId,
            user_id: uid,
            tong_kg: kg
          });
          if (data?.ok) {
            kgInput.classList.remove('is-invalid');
            kgInput.classList.add('is-valid');
          } else {
            kgInput.classList.remove('is-valid');
            kgInput.classList.add('is-invalid');
            alert(data?.error || 'Lỗi không xác định.');
          }
        });
      });

      // Cập nhật tất cả kg đang nhập
      const bulkBtn = document.getElementById('btn-bulk-save');
      if (bulkBtn) {
        bulkBtn.addEventListener('click', async () => {
          const rows = document.querySelectorAll('tbody tr[data-uid]');
          for (const tr of rows) {
            const uid = parseInt(tr.dataset.uid, 10);
            const kgInput = tr.querySelector('.kg-input');
            const kg = parseFloat(kgInput.value || '0');
            if (isNaN(kg) || kg < 0) {
              continue;
            }

            const data = await postJSON('game_update_kg.php', {
              game_id: gameId,
              user_id: uid,
              tong_kg: kg
            });
            if (data?.ok) kgInput.classList.add('is-valid');
          }
          alert('Đã gửi cập nhật kg cho tất cả hàng.');
        });
      }

      // Update xếp hạng theo kg
      const btnRank = document.getElementById('btn-update-rank');
      if (btnRank) {
        btnRank.addEventListener('click', async () => {
          if (!confirm('Cập nhật xếp hạng theo tổng kg?')) return;
          const data = await postJSON('game_update_rank.php', {
            game_id: gameId
          });
          if (data?.ok) {
            alert('Đã cập nhật xếp hạng theo kg.');
            location.reload();
          } else {
            alert(data?.error || 'Không cập nhật được xếp hạng.');
          }
        });
      }

      // Hoàn tất game
      const btnFinish = document.getElementById('btn-finish');
      if (btnFinish) {
        btnFinish.addEventListener('click', async () => {
          if (!confirm('Xác nhận chuyển trạng thái game sang HOÀN TẤT?')) return;
          const data = await postJSON('game_finalize.php', {
            game_id: gameId
          });
          if (data?.ok) {
            alert('Đã hoàn tất game.');
            location.reload();
          } else {
            alert(data?.error || 'Không thể hoàn tất game.');
          }
        });
      }

      // Copy game
      const btnCopy = document.getElementById('btn-copy-game');
      if (btnCopy) {
        btnCopy.addEventListener('click', async () => {
          if (!confirm('Xác nhận copy game này để tạo game mới?')) return;
          const data = await postJSON('game_copy.php', {
            game_id: gameId
          });
          if (data?.ok) {
            alert('Đã copy game thành công! Game mới ID: ' + data.new_game_id);
            // Redirect to new game
            if (data.new_game_id) {
              window.location.href = 'game_detail.php?game_id=' + data.new_game_id;
            }
          } else {
            alert(data?.error || 'Không thể copy game.');
          }
        });
      }
    })();
  </script>
  
<script>
function confirmCopy(type, el) {
  var msg = (type === 'A')
    ? "Tạo game mới nhanh gọn, danh sách người chơi giữ nguyên, nếu số cần thủ > 3 ==> 'KHÔNG trùng biên', tiếp tục?"
    : "Lấy danh sách cũ, thêm bớt cần thủ và random lại vị trí như game mới, tiếp tục?";
  if (!confirm(msg)) return false;

  // Chống click lặp
  el.classList.add('disabled');
  el.setAttribute('aria-disabled', 'true');
  el.textContent = el.textContent.trim() + ' — Đang tạo...';
  return true; // cho phép chuyển trang
}
</script>

</body>

</html>