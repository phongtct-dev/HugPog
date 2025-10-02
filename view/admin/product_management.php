<?php include 'header.php'; ?>

<h1 class="mt-4">Quản lý Sản phẩm</h1>
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-tags me-1"></i> Danh sách sản phẩm</span>
        <a href="product_form.php" class="btn btn-primary">Thêm sản phẩm mới</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Tồn kho</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td><img src="<?php echo htmlspecialchars($product['image_url']); ?>" width="50"></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ</td>
                        <td><?php echo $product['stock']; ?></td>
                        <td>
                            <span class="badge <?php echo $product['status'] === 'active' ? 'bg-success' : 'bg-secondary'; ?>">
                                <?php echo ucfirst($product['status']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="product_form.php?id=<?php echo $product['id']; ?>" class="btn btn-warning btn-sm">Sửa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>