<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chi tiết đơn hàng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="/HugPog/public/css/bootstrap.css">
    <link rel="stylesheet" href="/HugPog/public/css/style.css">
</head>
<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="container">
    <h1 class="my-4">Chi tiết Đơn hàng #<?php echo $order['id'] ?? ''; ?></h1>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
    <?php endif; ?>

    <?php if ($order): ?>
    <div class="card">
        <div class="card-header">Ngày đặt: <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></div>
        <div class="card-body">
            <h5 class="card-title">Sản phẩm đã đặt</h5>
            <?php foreach($order['items'] as $item): ?>
            <div class="row mb-3 border-bottom pb-3 align-items-center">
                <div class="col-2 col-md-1"><img src="<?php echo htmlspecialchars($item['image_url']); ?>" class="img-fluid"></div>
                <div class="col-10 col-md-6"><?php echo htmlspecialchars($item['name']); ?></div>
                <div class="col-6 col-md-2">Số lượng: <?php echo $item['quantity']; ?></div>
                <div class="col-6 col-md-3 text-end"><?php echo number_format($item['price'], 0, ',', '.'); ?> VNĐ</div>
            </div>
            <?php endforeach; ?>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <h6>Thông tin giao hàng</h6>
                    <p><strong>Người nhận:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                    <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['shipping_address']); ?></p>
                    <p><strong>Điện thoại:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
                </div>
                <div class="col-md-6 text-end">
                    <h6>Tổng cộng</h6>
                    <h4 class="text-danger"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> VNĐ</h4>
                    <h6>Trạng thái</h6>
                    <h4><span class="badge bg-success"><?php echo ucfirst($order['status']); ?></span></h4>

                    <?php if($order['status'] === 'Chờ Xác nhận'): ?>
                    <form action="cancel_order.php" method="POST" class="mt-3">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?');">Hủy đơn hàng</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="alert alert-danger">Không tìm thấy đơn hàng hoặc bạn không có quyền xem đơn hàng này.</div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/HugPog/public/js/main.js"></script>
</body>

</html>