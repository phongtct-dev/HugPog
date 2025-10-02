<?php include 'header.php'; ?>

<h1 class="mt-4">Quản lý Khuyến mãi</h1>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-gift me-1"></i>Danh sách khuyến mãi</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Giảm giá (%)</th>
                            <th>Bắt đầu</th>
                            <th>Kết thúc</th>
                            <th>Trạng thái</th>
                            <th>Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($promotions as $promo): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($promo['product_name']); ?></td>
                            <td><?php echo $promo['discount_percent']; ?>%</td>
                            <td><?php echo date('d/m/Y', strtotime($promo['start_date'])); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($promo['end_date'])); ?></td>
                            <td>
                                <?php if (strtotime($promo['start_date']) > time()): ?>
                                    <span class="badge bg-secondary">Sắp diễn ra</span>
                                <?php elseif (strtotime($promo['end_date']) < time()): ?>
                                    <span class="badge bg-light text-dark">Đã kết thúc</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Đang diễn ra</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form action="delete_promotion.php" method="POST">
                                    <input type="hidden" name="promotion_id" value="<?php echo $promo['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa khuyến mãi này?');">&times;</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-plus me-1"></i>Tạo khuyến mãi mới</div>
            <div class="card-body">
                <form action="save_promotion.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Chọn sản phẩm</label>
                        <select name="product_id" class="form-select" required>
                            <option value="">-- Chọn sản phẩm --</option>
                            <?php foreach($products as $product): ?>
                                <option value="<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phần trăm giảm giá (%)</label>
                        <input type="number" name="discount_percent" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ngày bắt đầu</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ngày kết thúc</label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Tạo khuyến mãi</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>