<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="/HugPog/public/css/style.css">
    <link rel="stylesheet" href="/HugPog/public/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css">
</head>

<body>
<?php
// File: project/public/order_success.php
include __DIR__ . '/../view/layout/header.php'; 
?>
<div class="container text-center">
    <div class="alert alert-success my-5">
        <h1 class="alert-heading">Đặt hàng thành công!</h1>
        <p>Cảm ơn bạn đã mua hàng. Đơn hàng của bạn với mã <strong>#<?php echo htmlspecialchars($_GET['order_id'] ?? ''); ?></strong> đã được ghi nhận.</p>
        <hr>
        <a href="product_list.php" class="btn btn-primary">Tiếp tục mua sắm</a>
        <a href="<?php echo BASE_URL; ?>public/order_history.php" class="btn btn-secondary">Xem lịch sử đơn hàng</a>
    </div>
</div>
<?php
include __DIR__ . '/../view/layout/footer.php';
?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>
    <script src="/HugPog/public/js/main.js"></script>
</body>

</html>