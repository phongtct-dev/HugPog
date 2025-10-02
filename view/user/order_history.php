<?php include __DIR__ . '/../header.php'; ?>

<div class="container">
    <h1 class="my-4">Lịch sử mua hàng</h1>
    <div class="card">
        <div class="card-body">
            <?php if (empty($orders)): ?>
                <p class="text-center">Bạn chưa có đơn hàng nào.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Mã ĐH</th>
                                <th>Ngày Đặt</th>
                                <th>Tổng Tiền</th>
                                <th>Trạng Thái</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                    <td><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> VNĐ</td>
                                    <td><span class="badge bg-info"><?php echo ucfirst($order['status']); ?></span></td>
                                    <td><a href="order_detail.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-primary">Xem chi tiết</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../footer.php'; ?>