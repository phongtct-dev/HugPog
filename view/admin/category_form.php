<?php include 'header.php'; ?>

<h1 class="mt-4">Chỉnh sửa Danh mục</h1>
<div class="card">
    <div class="card-header"><i class="fas fa-edit me-1"></i>Sửa thông tin danh mục</div>
    <div class="card-body">
        <form action="save_category.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
            <div class="mb-3">
                <label class="form-label">Tên danh mục</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($category['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($category['description']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-success">Lưu thay đổi</button>
            <a href="categories.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>