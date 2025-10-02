<?php include 'header.php'; ?>

<h1 class="mt-4">Quản lý Danh mục</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-list-alt me-1"></i>Danh sách danh mục</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên danh mục</th>
                            <th>Mô tả</th>
                            <th style="width: 15%;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($categories as $category): ?>
                        <tr>
                            <td><?php echo $category['id']; ?></td>
                            <td><?php echo htmlspecialchars($category['name']); ?></td>
                            <td><?php echo htmlspecialchars($category['description']); ?></td>
                            <td>
                                <a href="category_form.php?id=<?php echo $category['id']; ?>" class="btn btn-warning btn-sm">Sửa</a>
                                <form action="delete_category.php" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa? Sản phẩm thuộc danh mục này sẽ không có danh mục.');">
                                    <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
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
            <div class="card-header"><i class="fas fa-plus me-1"></i>Thêm danh mục mới</div>
            <div class="card-body">
                <form action="save_category.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Tên danh mục</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>