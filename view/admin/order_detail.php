<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/HugPog/public/css/style.css">
    <link rel="stylesheet" href="/HugPog/public/css/app.css">
</head>

<body>
    <?php include __DIR__ . '../../layout/sidebar.php'; ?>
    <div id="main" class="p-4">
        <h1 class="mt-4">Chi tiết Đơn hàng #<?php echo $order['id']; ?></h1>

        <?php if ($order): ?>
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">Sản phẩm trong đơn hàng</div>
                        <div class="card-body">
                            <table class="table">
                                <tbody>
                                    <?php foreach ($order['items'] as $item): ?>
                                        <tr>
                                            <td><img src="<?php echo BASE_URL . 'public/' . ($item['image_url'] ?? 'images/default.png'); ?>" alt="" width="50"></td>
                                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                                            <td>Số lượng: <?php echo $item['quantity']; ?></td>
                                            <td>Đơn giá: <?php echo number_format($item['price'], 0, ',', '.'); ?> VNĐ</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header">Thông tin khách hàng</div>
                        <div class="card-body">
                            <p><strong>Tên người nhận:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                            <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['shipping_address']); ?></p>
                            <p><strong>Điện thoại:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
                            <hr>
                            <p><strong>Tổng tiền:</strong> <span class="fs-5 text-danger"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> VNĐ</span></p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">Xử lý đơn hàng</div>
                        <div class="card-body">
                            <p>Trạng thái hiện tại: <strong class="text-primary"><?php echo htmlspecialchars($order['status']); ?></strong></p>
                            <hr>
                            <div class="d-grid gap-2">
                                <?php
                                if ($order['status'] !== 'Thành công' && $order['status'] !== 'Đã hủy'):
                                ?>
                                    <form action="process_order.php" method="POST" class="d-grid">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <input type="hidden" name="action" value="progress">
                                        <button type="submit" class="btn btn-success">
                                            <?php
                                            // Hiển thị text của nút dựa trên trạng thái tiếng Việt
                                            switch ($order['status']) {
                                                case 'Chờ Xác nhận':
                                                    echo 'Xác nhận Đơn hàng';
                                                    break;
                                                case 'Đã Xác nhận':
                                                    echo 'Bắt đầu Giao hàng';
                                                    break;
                                                case 'Đang giao':
                                                    echo 'Đã Giao hàng';
                                                    break;
                                                case 'Đã giao':
                                                    echo 'Hoàn thành Đơn hàng';
                                                    break;
                                            }
                                            ?>
                                        </button>
                                    </form>

                                    <?php if ($order['status'] === 'Chờ Xác nhận' || $order['status'] === 'Đã Xác nhận'): ?>
                                        <form action="process_order.php" method="POST" class="d-grid">
                                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                            <input type="hidden" name="action" value="cancel">
                                            <button type="submit" class="btn btn-danger">Hủy đơn hàng</button>
                                        </form>
                                    <?php endif; ?>

                                <?php else: ?>
                                    <p class="text-muted text-center">Đơn hàng đã ở trạng thái cuối cùng.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <p>Không tìm thấy đơn hàng.</p>
        <?php endif; ?>
    </div>
</body>

</html>