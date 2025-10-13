<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng thành công</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/bootstrap.css">
</head>

<body>
    <?php include __DIR__ . '/../layout/header.php'; ?>

    <div class="container text-center">
        <div class="alert alert-success my-5">
            <h1 class="alert-heading">Đặt hàng thành công!</h1>
            <p>Cảm ơn bạn đã mua hàng. Đơn hàng của bạn với mã <strong>#<?php echo htmlspecialchars($order_id); ?></strong> đã được ghi nhận.</p>
            <hr>
            <a href="<?php echo BASE_URL; ?>public/product_list.php" class="btn btn-primary">Tiếp tục mua sắm</a>
            <a href="<?php echo BASE_URL; ?>public/order_history.php" class="btn btn-secondary">Xem lịch sử đơn hàng</a>
        </div>
    </div>

    <?php include __DIR__ . '/../layout/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>public/js/main.js"></script>
</body>

</html>