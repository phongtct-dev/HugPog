<?php include 'header.php'; ?>

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
                <p>Trạng thái hiện tại: <strong class="text-primary"><?php echo htmlspecialchars(ucfirst($order['status'])); ?></strong></p>
                <hr>
                <div class="d-grid gap-2">
                    <?php 
                    // Chỉ hiển thị nút nếu đơn hàng chưa hoàn thành hoặc chưa hủy
                    if ($order['status'] !== 'completed' && $order['status'] !== 'cancelled'): 
                    ?>
                        <form action="process_order.php" method="POST" class="d-grid">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                            <input type="hidden" name="action" value="progress">
                            <button type="submit" class="btn btn-success">
                                <?php
                                // Hiển thị text của nút dựa trên trạng thái hiện tại
                                switch ($order['status']) {
                                    case 'pending': echo 'Xác nhận đơn hàng'; break;
                                    case 'confirmed': echo 'Bắt đầu giao hàng'; break;
                                    case 'shipping': echo 'Đã giao thành công'; break;
                                    case 'delivered': echo 'Hoàn thành đơn hàng'; break;
                                }
                                ?>
                            </button>
                        </form>
                        
                        <?php if ($order['status'] === 'pending' || $order['status'] === 'confirmed'): ?>
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

<?php include 'footer.php'; ?>