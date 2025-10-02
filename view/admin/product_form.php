<?php 
include 'header.php'; 
// Xác định xem đây là form thêm mới hay chỉnh sửa
$isEditing = isset($product) && $product !== null;
?>

<h1 class="mt-4"><?php echo $isEditing ? 'Chỉnh sửa sản phẩm' : 'Thêm sản phẩm mới'; ?></h1>
<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-edit me-1"></i>
        Thông tin sản phẩm
    </div>
    <div class="card-body">
        <form action="save_product.php" method="POST">
            <?php if ($isEditing): ?>
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label for="name" class="form-label">Tên sản phẩm</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="brand" class="form-label">Thương hiệu</label>
                <input type="text" class="form-control" id="brand" name="brand" value="<?php echo htmlspecialchars($product['brand'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea class="form-control" id="description" name="description" rows="5"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Giá (VNĐ)</label>
                    <input type="number" class="form-control" id="price" name="price" value="<?php echo $product['price'] ?? 0; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="stock" class="form-label">Số lượng tồn kho</label>
                    <input type="number" class="form-control" id="stock" name="stock" value="<?php echo $product['stock'] ?? 0; ?>" required>
                </div>
            </div>
             <div class="mb-3">
                <label for="category_id" class="form-label">Danh mục</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" <?php if(isset($product) && $product['category_id'] == $category['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="image_url" class="form-label">URL Hình ảnh</label>
                <input type="text" class="form-control" id="image_url" name="image_url" value="<?php echo htmlspecialchars($product['image_url'] ?? ''); ?>">
            </div>
            <?php if ($isEditing): ?>
            <div class="mb-3">
                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-select" id="status" name="status">
                    <option value="active" <?php if($product['status'] == 'active') echo 'selected'; ?>>Đang hoạt động (Active)</option>
                    <option value="inactive" <?php if($product['status'] == 'inactive') echo 'selected'; ?>>Ngừng kinh doanh (Inactive)</option>
                </select>
            </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-success">Lưu sản phẩm</button>
            <a href="products.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>