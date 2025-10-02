<?php 
include 'header.php'; 
$isEditing = isset($voucher) && $voucher !== null;
?>

<h1 class="mt-4"><?php echo $isEditing ? 'Chỉnh sửa Voucher' : 'Thêm Voucher mới'; ?></h1>
<div class="card">
    <div class="card-header">Thông tin Voucher</div>
    <div class="card-body">
        <form action="save_voucher.php" method="POST">
            <?php if ($isEditing): ?>
                <input type="hidden" name="voucher_id" value="<?php echo $voucher['id']; ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label for="code" class="form-label">Mã Voucher (Ví dụ: SALE50K)</label>
                <input type="text" class="form-control" name="code" value="<?php echo htmlspecialchars($voucher['code'] ?? ''); ?>" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="discount_value" class="form-label">Giá trị giảm (VNĐ)</label>
                    <input type="number" class="form-control" name="discount_value" value="<?php echo $voucher['discount_value'] ?? 0; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="quantity" class="form-label">Tổng số lượng</label>
                    <input type="number" class="form-control" name="quantity" value="<?php echo $voucher['quantity'] ?? 0; ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="expiry_date" class="form-label">Ngày hết hạn</label>
                    <input type="date" class="form-control" name="expiry_date" value="<?php echo isset($voucher['expiry_date']) ? date('Y-m-d', strtotime($voucher['expiry_date'])) : ''; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select class="form-select" name="status">
                        <option value="active" <?php if(isset($voucher) && $voucher['status'] == 'active') echo 'selected'; ?>>Hoạt động (Active)</option>
                        <option value="inactive" <?php if(isset($voucher) && $voucher['status'] == 'inactive') echo 'selected'; ?>>Không hoạt động (Inactive)</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Lưu Voucher</button>
            <a href="vouchers.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>