<?php
// File: cauca/chuho/game/game_user_add.php
require __DIR__ . '/../../../connect.php';
require __DIR__ . '/../../../check_login.php';
require_once '../../../includes/header.php';

if (($_SESSION['user']['vai_tro'] ?? '') !== 'chuho') {
  header('Location: /'); exit;
}

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

$chuho_id = (int)$_SESSION['user']['id'];
$game_id  = isset($_GET['game_id']) ? (int)$_GET['game_id'] : 0;

if ($game_id <= 0) { http_response_code(400); echo "Thiếu tham số game_id."; exit; }

// 1) Xác thực game thuộc chủ hồ này và đang tồn tại
$sqlGame = "
  SELECT g.*, h.ten_ho
  FROM game_list g
  JOIN ho_cau h ON h.id = g.ho_cau_id
  WHERE g.id = :gid AND g.chuho_id = :uid
  LIMIT 1
";
$stG = $pdo->prepare($sqlGame);
$stG->execute(['gid'=>$game_id, 'uid'=>$chuho_id]);
$game = $stG->fetch(PDO::FETCH_ASSOC);
if (!$game) { http_response_code(403); echo "Game không hợp lệ hoặc không thuộc quyền quản lý."; exit; }

// 2) Xử lý POST: thêm theo số điện thoại
$errors = [];
$notices = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $phone = trim($_POST['phone'] ?? '');

  // chuẩn hóa số: giữ số, bỏ ký tự khác
  $phone = preg_replace('/\D+/', '', $phone);

  if ($phone === '' || strlen($phone) < 8) {
    $errors[] = "Số điện thoại không hợp lệ.";
  }

  if (!$errors) {
    try {
      $pdo->beginTransaction();

      // 2.1) Tìm user theo phone (UNIQUE trên cột phone) (theo schema users) :contentReference[oaicite:2]{index=2}
      $stFind = $pdo->prepare("SELECT id, phone, nickname, vai_tro, status FROM users WHERE phone = ? LIMIT 1");
      $stFind->execute([$phone]);
      $user = $stFind->fetch(PDO::FETCH_ASSOC);

      if (!$user) {
        // 2.2) Chưa có user -> tạo guest
        // schema users: có các cột phone, password, nickname, email, vai_tro (enum có 'guest'), status, review_status, ...  :contentReference[oaicite:3]{index=3}
        $nickname = 'guest_'.random_int(1000, 999999);
        $passwordHash = password_hash('guest@'.$phone.'#'.random_int(100,999), PASSWORD_BCRYPT);

        $insU = $pdo->prepare("
          INSERT INTO users
            (phone, password, nickname, email, vai_tro, full_name,
             bank_account, bank_info, qr_image_path, CCCD_number,
             balance, balance_ref, ref_code, user_exp, cnt_ho, cnt_xa, cnt_giai, cnt_game,
             user_lever, user_note, review_status, status)
          VALUES
            (:phone, :password, :nickname, 'Email.', 'guest', :full_name,
             'Số tài khoản.', NULL, NULL, 'Số CCCD.',
             100000.00, 50000.00, '0935192079', 0, 0, 0, 0, 0,
             1, 'Tự tạo guest khi add vào game', 'no', 'Chưa xác minh')
        ");
        $insU->execute([
          'phone'     => $phone,
          'password'  => $passwordHash,
          'nickname'  => $nickname,
          'full_name' => $nickname
        ]);

        $user_id = (int)$pdo->lastInsertId();
        $user = ['id'=>$user_id, 'phone'=>$phone, 'nickname'=>$nickname, 'vai_tro'=>'guest', 'status'=>'Chưa xác minh'];
        $notices[] = "Đã tạo tài khoản guest cho số $phone.";
      } else {
        $user_id = (int)$user['id'];
      }
		// 2.3.b) Kiểm tra đủ số người cho các hình thức solo (IDs 13,14,15)
		// Nếu là solo 2/3/4 người, không cho thêm nếu đã đủ.
		$stGameInfo = $pdo->prepare("SELECT hinh_thuc_id, so_luong_can_thu FROM game_list WHERE id = ? LIMIT 1");
		$stGameInfo->execute([$game_id]);
		$ginfo = $stGameInfo->fetch(PDO::FETCH_ASSOC);
		if ($ginfo) {
		  $ht = (int)$ginfo['hinh_thuc_id'];
		  if (in_array($ht, [13,14,15], true)) {
			// Ưu tiên lấy số lượng tối đa từ cột so_luong_can_thu; nếu trống thì map theo hình thức
			$mapMax = [13 => 2, 14 => 3, 15 => 4];
			$max = (int)($ginfo['so_luong_can_thu'] ?? 0);
			if ($max <= 0 && isset($mapMax[$ht])) {
			  $max = $mapMax[$ht];
			}
			if ($max > 0) {
			  $stCnt = $pdo->prepare("SELECT COUNT(*) FROM game_user WHERE game_id = ?");
			  $stCnt->execute([$game_id]);
			  $current = (int)$stCnt->fetchColumn();
			  if ($current >= $max) {
				// Ném lỗi để đi vào catch và hiển thị thông báo
				throw new RuntimeException("Game đã đủ số người (tối đa {$max}).");
			  }
			}
		  }
		}
      // 2.3) Kiểm tra đã có trong game_user chưa
      // Tùy schema game_user của bạn; dưới đây dùng các cột phổ biến: id, game_id, user_id, nickname, status, joined_at
      $stChk = $pdo->prepare("SELECT id FROM game_user WHERE game_id = ? AND user_id = ? LIMIT 1");
      $stChk->execute([$game_id, $user_id]);
      if ($stChk->fetch()) {
        $pdo->rollBack();
        $errors[] = "User đã có trong game.";
      } else {
        $nickJoin = $user['nickname'] ?? ('user_'.$user_id);
        $insGU = $pdo->prepare("
          INSERT INTO game_user (game_id, user_id, nickname, trang_thai, created_at)
          VALUES (:game_id, :user_id, :nickname, 'cho_xac_nhan', NOW())
        ");
        $insGU->execute([
          'game_id'  => $game_id,
          'user_id'  => $user_id,
          'nickname' => $nickJoin
        ]);
        $pdo->commit();
        $notices[] = "Đã thêm ".h($nickJoin)." vào game.";
      }

    } catch (Throwable $e) {
      if ($pdo->inTransaction()) $pdo->rollBack();
      $errors[] = "Lỗi thêm người chơi: ".$e->getMessage();
    }
  }
}

// 3) Danh sách người đã tham gia game
$stList = $pdo->prepare("
  SELECT gu.id, gu.user_id, gu.nickname, gu.trang_thai, u.phone
  FROM game_user gu
  JOIN users u ON u.id = gu.user_id
  WHERE gu.game_id = :gid
  ORDER BY gu.id DESC
");
$stList->execute(['gid'=>$game_id]);
$rows = $stList->fetchAll(PDO::FETCH_ASSOC);

// 4) Xóa người khỏi game (GET: ?remove_id=...)
if (isset($_GET['remove_id'])) {
  $rid = (int)$_GET['remove_id'];
  if ($rid > 0) {
    $stDel = $pdo->prepare("DELETE FROM game_user WHERE id = :id AND game_id = :gid");
    $stDel->execute(['id'=>$rid, 'gid'=>$game_id]);
    header("Location: ".$_SERVER['PHP_SELF']."?game_id=".$game_id);
    exit;
  }
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Thêm người chơi vào game #<?= (int)$game_id ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="/assets/bootstrap.min.css" rel="stylesheet">
  <style>
    .card-hover:hover { transform: translateY(-2px); box-shadow: 0 0.5rem 1rem rgba(0,0,0,.12) }
    .table-sm td, .table-sm th { padding: .45rem .5rem; }
  </style>
</head>
<body class="bg-light">
<div class="container py-4">

  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/cauca/chuho/game/game_list_ho.php">Hồ cho game</a></li>
      <li class="breadcrumb-item"><a href="/cauca/chuho/game/game_create.php?ho_id=<?= (int)$game['ho_cau_id'] ?>">Tạo game</a></li>
      <li class="breadcrumb-item active" aria-current="page">Thêm người chơi</li>
    </ol>
  </nav>

  <div class="row g-3">
    <div class="col-lg-5">
      <div class="card border-0 shadow-sm card-hover">
        <div class="card-header bg-white fw-semibold">Game #<?= (int)$game['id'] ?> — <?= h($game['ten_game']) ?></div>
        <div class="card-body">
          <div class="mb-2 small text-muted">
            Hồ: <span class="fw-semibold"><?= h($game['ten_ho']) ?></span> |
            Ngày: <span class="fw-semibold"><?= h($game['ngay_to_chuc']) ?></span> |
            Bắt đầu: <span class="fw-semibold"><?= h($game['gio_bat_dau']) ?></span>
          </div>

          <?php if ($errors): ?>
            <div class="alert alert-danger">
              <div class="fw-semibold mb-1">Không thể thêm người chơi:</div>
              <ul class="mb-0"><?php foreach ($errors as $e) echo '<li>'.h($e).'</li>'; ?></ul>
            </div>
          <?php endif; ?>

          <?php if ($notices): ?>
            <div class="alert alert-success">
              <ul class="mb-0"><?php foreach ($notices as $n) echo '<li>'.h($n).'</li>'; ?></ul>
            </div>
          <?php endif; ?>

          <form method="post" class="mt-2">
            <label class="form-label">Số điện thoại cần thủ</label>
            <div class="input-group">
              <input name="phone" class="form-control" placeholder="VD: 0901234567" required>
              <button class="btn btn-primary">Thêm vào game</button>
            </div>
            <div class="form-text">
              - Nếu số này chưa có tài khoản, hệ thống sẽ tạo <b>guest</b> tự động rồi thêm vào game.<br>
              - Tránh nhập trùng: phone là duy nhất trong hệ thống (UNIQUE). :contentReference[oaicite:4]{index=4}
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-7">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-semibold d-flex align-items-center">
          Danh sách người chơi (<?= count($rows) ?>)
          <a class="btn btn-sm btn-outline-secondary ms-auto" href="/cauca/chuho/game/game_list.php?ho_id=<?= (int)$game['ho_cau_id'] ?>">
            Xem các game của hồ
          </a>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Phone</th>
                  <th>Nickname</th>
                  <th>Trạng thái</th>
                  <th class="text-end">Thao tác</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!$rows): ?>
                  <tr><td colspan="5" class="text-center text-muted py-3">Chưa có người chơi.</td></tr>
                <?php else: $i=1; foreach ($rows as $r): ?>
                  <tr>
                    <td><?= $i++ ?></td>
                    <td><?= h($r['phone']) ?></td>
                    <td><?= h($r['nickname']) ?></td>
                    <td><span class="badge bg-success"><?= h($r['trang_thai']) ?></span></td>
                    <td class="text-end">
                      <a class="btn btn-sm btn-outline-danger"
                         onclick="return confirm('Xóa người này khỏi game?');"
                         href="<?= $_SERVER['PHP_SELF'].'?game_id='.(int)$game_id.'&remove_id='.(int)$r['id'] ?>">
                        Xóa
                      </a>
                    </td>
                  </tr>
                <?php endforeach; endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="d-flex gap-2 mt-3">
        <a class="btn btn-secondary" href="/cauca/chuho/game/game_create.php?ho_id=<?= (int)$game['ho_cau_id'] ?>">Quay lại</a>
        <a class="btn btn-primary" href="/cauca/chuho/game/game_manage.php?game_id=<?= (int)$game['id'] ?>">Tiếp tục quản lý game</a>
      </div>
    </div>
  </div>
</div>

<script src="/assets/bootstrap.bundle.min.js"></script>
</body>
</html>
