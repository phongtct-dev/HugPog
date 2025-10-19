<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đặt Lại Mật Khẩu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/bootstrap.css">
</head>
<body>
    <?php include __DIR__ . '/../layout/header.php'; ?>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center">Đặt Lại Mật Khẩu</h2>

                        <?php if (!empty($message)): ?>
                            <div class="alert alert-<?php echo $message['type']; ?>">
                                <?php echo $message['text']; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($tokenIsValid): ?>
                            <p class="text-center mb-4">Vui lòng nhập mật khẩu mới của bạn.</p>
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Mật khẩu mới</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="mb-4">
                                    <label for="password_confirm" class="form-label">Xác nhận mật khẩu mới</label>
                                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 py-2">Đặt Lại Mật Khẩu</button>
                            </form>
                        <?php else: ?>
                            <div class="text-center mt-4">
                                <a href="<?php echo BASE_URL; ?>public/forgot_password.php" class="btn btn-secondary">Yêu cầu liên kết mới</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include __DIR__ . '/../layout/footer.php'; ?>
    <script src="<?php echo BASE_URL; ?>public/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>