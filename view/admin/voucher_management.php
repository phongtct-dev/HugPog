<?php include 'header.php'; ?>

<h1 class="mt-4">Quản lý Voucher</h1>
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-ticket-alt me-1"></i> Danh sách voucher</span>
        <a href="voucher_form.php" class="btn btn-primary">Thêm Voucher mới</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Mã Code</th>
                    <th>Giá trị giảm</th>
                    <th>Số lượng còn lại</th>
                    <th>Ngày hết hạn</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($vouchers as $voucher): ?>
                <tr>
                    <td><?php echo $voucher['id']; ?></td>
                    <td><strong><?php echo htmlspecialchars($voucher['code']); ?></strong></td>
                    <td><?php echo number_format($voucher['discount_value'], 0, ',', '.'); ?> VNĐ</td>
                    <td><?php echo $voucher['quantity']; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($voucher['expiry_date'])); ?></td>
                    <td>
                        <span class="badge <?php echo $voucher['status'] === 'active' ? 'bg-success' : 'bg-secondary'; ?>">
                            <?php echo ucfirst($voucher['status']); ?>
                        </span>
                    </td>
                    <td>
                        <a href="voucher_form.php?id=<?php echo $voucher['id']; ?>" class="btn btn-warning btn-sm">Sửa</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>