<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hồ Sơ Của Tôi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../layout/header.php'; ?>

    <div class="container py-5">
        <h1 class="mb-4">Hồ Sơ Của Tôi</h1>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Thông tin cá nhân</h5>

                        <?php if (!empty($message)): ?>
                            <div class="alert alert-<?php echo $message['type']; ?>">
                                <?php echo htmlspecialchars($message['text']); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($currentUser['full_name'] ?? ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($currentUser['email'] ?? ''); ?>" disabled readonly>
                                <small class="form-text text-muted">Không thể thay đổi email.</small>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($currentUser['phone'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ mặc định</label>
                                <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($currentUser['address'] ?? ''); ?></textarea>
                            </div>
                            <hr>
                            <h5 class="mt-4 mb-3">Đổi mật khẩu (để trống nếu không đổi)</h5>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Mật khẩu mới</label>
                                <input type="password" class="form-control" id="new_password" name="new_password">
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                            </div>
                            <button type="submit" class="btn btn-primary">Cập nhật hồ sơ</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($currentUser['full_name']); ?></h5>
                        <p class="text-muted">Cấp bậc: <?php echo displayRankBadge($currentUser['rank'] ?? 'silver'); ?></p>
                        
                        <a href="<?php echo BASE_URL; ?>public/order_history.php" class="btn btn-outline-primary mt-2">Lịch sử mua hàng</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../layout/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>public/js/main.js"></script>
</body>
</html>