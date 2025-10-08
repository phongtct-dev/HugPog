<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Sản phẩm</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/HugPog/public/css/style.css">
    <link rel="stylesheet" href="/HugPog/public/css/app.css">
</head>

<body>
    <?php include __DIR__ . '../../layout/sidebar.php'; ?>

    <div id="main" class="p-4">
        <div class="page-heading mb-4">
            <h3>Quản lý Sản phẩm</h3>
        </div>

        <div class="page-content">
            <?php if ($success_message): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($success_message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($error_message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Danh sách Sản phẩm</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class="fa-solid fa-plus me-1"></i> Thêm Sản phẩm
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Ảnh</th>
                                    <th>Tên Sản phẩm</th>
                                    <th>Thương hiệu</th>
                                    <th>Danh mục</th>
                                    <th>Giá</th>
                                    <th>Tồn kho</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($products)): ?>
                                    <?php foreach ($products as $product): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($product['id']) ?></td>
                                            <td>
                                                <img src="<?= $product['image_url'] ?>" alt="Product"
                                                    class="img-fluid rounded"
                                                    style="width: 80px; height: 80px; object-fit: cover;">
                                            </td>
                                            <td><?= htmlspecialchars($product['name']) ?></td>
                                            <td><?= htmlspecialchars($product['brand'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($product['category_name'] ?? 'N/A') ?></td>
                                            <td><?= number_format($product['price'], 0, ',', '.') ?> ₫</td>
                                            <td><?= htmlspecialchars($product['stock']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $product['status'] == 'active' ? 'success' : 'danger' ?>">
                                                    <?= $product['status'] == 'active' ? 'Hoạt động' : 'Ngừng bán' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-info text-white me-1 edit-btn"
                                                    data-bs-toggle="modal" data-bs-target="#editProductModal"
                                                    data-id="<?= $product['id'] ?>"
                                                    data-name="<?= htmlspecialchars($product['name']) ?>"
                                                    data-brand="<?= htmlspecialchars($product['brand'] ?? '') ?>"
                                                    data-category-id="<?= htmlspecialchars($product['category_id']) ?>"
                                                    data-price="<?= htmlspecialchars($product['price']) ?>"
                                                    data-stock="<?= htmlspecialchars($product['stock']) ?>"
                                                    data-description="<?= htmlspecialchars($product['description']) ?>"
                                                    data-status="<?= htmlspecialchars($product['status']) ?>"
                                                    data-image="<?= htmlspecialchars($product['image_url']) ?>">
                                                    <i class="fa-solid fa-edit"></i>
                                                </button>

                                                <form method="POST" class="d-inline" onsubmit="return confirmDelete(<?= $product['id'] ?>, '<?= htmlspecialchars($product['name']) ?>');">
                                                    <input type="hidden" name="action_type" value="delete">
                                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">Không có sản phẩm nào.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <footer class="text-center py-4">
                &copy; <?= date('Y') ?> | Hùng & Phong
            </footer>
        </div>
    </div>

    <!-- MODAL THÊM -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="products.php">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm Sản phẩm Mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action_type" value="add">

                        <div class="mb-3">
                            <label>Tên sản phẩm</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label>Thương hiệu</label>
                            <input type="text" class="form-control" name="brand">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Danh mục</label>
                                <select class="form-select" name="category_id" required>
                                    <option value="">-- Chọn --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= htmlspecialchars($cat['id']) ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Trạng thái</label>
                                <select class="form-select" name="status">
                                    <option value="active">Hoạt động</option>
                                    <option value="inactive">Ngừng bán</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Giá bán</label>
                                <input type="number" class="form-control" name="price" min="0" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Số lượng tồn</label>
                                <input type="number" class="form-control" name="stock" min="0" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Mô tả</label>
                            <textarea class="form-control" name="description"></textarea>
                        </div>

                        <div class="mb-3">
                            <label>Link ảnh sản phẩm</label>
                            <input type="text" class="form-control" name="image_url" placeholder="Dán link ảnh tại đây..." required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL SỬA -->
    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="products.php">
                    <div class="modal-header">
                        <h5 class="modal-title">Chỉnh sửa sản phẩm</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action_type" value="update">
                        <input type="hidden" name="product_id" id="editProductId">

                        <div class="mb-3">
                            <label>Tên sản phẩm</label>
                            <input type="text" class="form-control" id="editProductName" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label>Thương hiệu</label>
                            <input type="text" class="form-control" id="editProductBrand" name="brand">
                        </div>

                        <div class="mb-3">
                            <label>Link ảnh sản phẩm</label>
                            <input type="text" class="form-control" id="editProductImageUrl" name="image_url" placeholder="Nhập link ảnh mới (hoặc để trống để giữ nguyên)">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Danh mục</label>
                                <select class="form-select" id="editProductCategory" name="category_id" required>
                                    <option value="">-- Chọn --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= htmlspecialchars($cat['id']) ?>">
                                            <?= htmlspecialchars($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Trạng thái</label>
                                <select class="form-select" id="editProductStatus" name="status">
                                    <option value="active">Hoạt động</option>
                                    <option value="inactive">Ngừng bán</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Giá bán</label>
                                <input type="number" class="form-control" id="editProductPrice" name="price" min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Tồn kho</label>
                                <input type="number" class="form-control" id="editProductQuantity" name="stock" min="0">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Mô tả</label>
                            <textarea class="form-control" id="editProductDescription" name="description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editModal = document.getElementById('editProductModal');
            editModal.addEventListener('show.bs.modal', function(event) {
                const btn = event.relatedTarget;
                document.getElementById('editProductId').value = btn.dataset.id;
                document.getElementById('editProductName').value = btn.dataset.name;
                document.getElementById('editProductBrand').value = btn.dataset.brand;
                document.getElementById('editProductCategory').value = btn.dataset.categoryId;
                document.getElementById('editProductPrice').value = btn.dataset.price;
                document.getElementById('editProductQuantity').value = btn.dataset.stock;
                document.getElementById('editProductDescription').value = btn.dataset.description;
                document.getElementById('editProductStatus').value = btn.dataset.status;
                document.getElementById('currentProductImagePreview').src = btn.dataset.image;
            });
        });

        function confirmDelete(id, name) {
            return confirm(`Bạn có chắc muốn xóa sản phẩm "${name}" (ID: ${id}) không?`);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>