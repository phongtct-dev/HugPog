<?php
// File: project/public/order_success.php
include '../View/header.php';
?>
<div class="container text-center">
    <div class="alert alert-success my-5">
        <h1 class="alert-heading">Đặt hàng thành công!</h1>
        <p>Cảm ơn bạn đã mua hàng. Đơn hàng của bạn với mã <strong>#<?php echo htmlspecialchars($_GET['order_id'] ?? ''); ?></strong> đã được ghi nhận.</p>
        <hr>
        <a href="index.php" class="btn btn-primary">Tiếp tục mua sắm</a>
        <a href="<?php echo BASE_URL; ?>public/order_history.php" class="btn btn-secondary">Xem lịch sử đơn hàng</a>
    </div>
</div>
<?php
include '../View/footer.php';
?>