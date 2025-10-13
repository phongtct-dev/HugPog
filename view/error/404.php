<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Không tìm thấy trang</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
    <style>
        .error-page {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            min-height: 50vh;
        }
        .error-page h1 {
            font-size: 8rem;
            font-weight: 900;
            color: #D10024;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../layout/header.php'; ?>

    <div class="container">
        <div class="error-page">
            <div>
                <h1>404</h1>
                <h4 class="mb-4">Rất tiếc, trang bạn tìm kiếm không tồn tại.</h4>
                <p class="mb-4">Đường dẫn có thể đã bị thay đổi hoặc không còn nữa.</p>
                <a href="<?php echo BASE_URL; ?>public/index.php" class="primary-btn">Quay về Trang chủ</a>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>