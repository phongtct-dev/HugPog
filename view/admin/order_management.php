<?php include 'header.php'; ?>

<h1 class="mt-4">Quản lý Đơn hàng</h1>
<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table me-1"></i>
        Danh sách tất cả đơn hàng
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Mã ĐH</th>
                    <th>Tên khách hàng</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?php echo $order['id']; ?></td>
                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                        <td><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> VNĐ</td>
                        <td><span class="badge bg-info"><?php echo htmlspecialchars($order['status']); ?></span></td>
                        <td>
                            <a href="order_detail.php?id=<?php echo $order['id']; ?>" class="btn btn-primary btn-sm">Xem chi tiết</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>