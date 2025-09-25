<?php
// testhash.php - Phiên bản có giao diện Bootstrap
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo Hash Mật khẩu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header text-center bg-primary text-white">
                    <h4>Tạo Hash cho Mật khẩu</h4>
                </div>
                <div class="card-body">
                    <?php
                    $hash = '';
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['password'])) {
                        $password = trim($_POST['password']);
                        $hash = password_hash($password, PASSWORD_DEFAULT);
                    }
                    ?>

                    <?php if (!empty($hash)): ?>
                        <div class="alert alert-success">
                            <strong>Hash đã tạo:</strong>
                            <input type="text" class="form-control mt-2" value="<?php echo htmlspecialchars($hash); ?>" readonly>
                        </div>
                    <?php endif; ?>

                    <form method="post">
                        <div class="mb-3">
                            <label for="password" class="form-label">Điền Mật khẩu cần mã hóa:</label>
                            <input type="text" name="password" id="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Tạo Hash</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
