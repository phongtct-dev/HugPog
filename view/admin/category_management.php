
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Danh mục</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/HugPog/public/css/style.css">
    <link rel="stylesheet" href="/HugPog/public/css/app.css">
</head>

<body>
    <?php include __DIR__ . '../../layout/sidebar.php'; ?>

    <div id="main" class="p-4">
        <div class="page-heading mb-4">
            <h3>Quản lý Danh mục Sản phẩm</h3>
        </div>

        <div class="page-content">
            <?php if ($success_message): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($success_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if ($error_message): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($error_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Danh sách Danh mục</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal" data-action="create">
                        <i class="fa-solid fa-plus me-1"></i> Thêm Danh mục
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên Danh mục</th>
                                    <th>Mô tả</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($category['id']); ?></td>
                                            <td><?php echo htmlspecialchars($category['name']); ?></td>
                                            <td><?php echo htmlspecialchars($category['description'] ?? ''); ?></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-info text-white me-1 edit-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#categoryModal"
                                                    data-action="update"
                                                    data-id="<?php echo $category['id']; ?>"
                                                    data-name="<?php echo htmlspecialchars($category['name']); ?>"
                                                    data-description="<?php echo htmlspecialchars($category['description'] ?? ''); ?>">
                                                    <i class="fa-solid fa-edit"></i>
                                                </button>

                                                <form method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này? Hành động này không thể hoàn tác và có thể gây lỗi nếu có sản phẩm liên quan!');">
                                                    <input type="hidden" name="action_type" value="delete">
                                                    <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Không có danh mục nào trong hệ thống.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="py-6 px-6 text-center">
                <span class="copyright">
                    Bản quyền &copy;
                    <script>
                        document.write(new Date().getFullYear());
                    </script>
                    Tất cả quyền được bảo lưu | Mẫu này được tạo bởi Hùng & Phong
                </span>
            </div>
        </div>
    </div>

    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel">Thêm Danh mục Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="categoryForm" method="POST" action="categories.php">
                    <div class="modal-body">
                        <input type="hidden" name="action_type" id="categoryActionType" value="create">
                        <input type="hidden" name="id" id="categoryId">

                        <div class="mb-3">
                            <label for="categoryName" class="form-label">Tên Danh mục</label>
                            <input type="text" class="form-control" id="categoryName" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="categoryDescription" class="form-label">Mô tả (Tùy chọn)</label>
                            <textarea class="form-control" id="categoryDescription" name="description" rows="3"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary" id="submitButton">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/perfect-scrollbar@1.5.5/dist/perfect-scrollbar.min.js"></script>
    <script src="/HugPog/public//js/main.js"></script>
    <script>
        // Xử lý modal khi nhấn nút Thêm hoặc Sửa
        document.addEventListener('DOMContentLoaded', function() {
            var categoryModal = document.getElementById('categoryModal');

            categoryModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var action = button.getAttribute('data-action');
                var modalTitle = categoryModal.querySelector('.modal-title');
                var categoryActionType = categoryModal.querySelector('#categoryActionType');
                var submitButton = categoryModal.querySelector('#submitButton');

                if (action === 'update') {
                    // Chế độ Sửa
                    modalTitle.textContent = 'Cập nhật Danh mục';
                    categoryActionType.value = 'update';
                    submitButton.textContent = 'Cập nhật';

                    // Điền dữ liệu từ data-* attributes
                    categoryModal.querySelector('#categoryId').value = button.getAttribute('data-id');
                    categoryModal.querySelector('#categoryName').value = button.getAttribute('data-name');
                    categoryModal.querySelector('#categoryDescription').value = button.getAttribute('data-description');
                    // Đã loại bỏ logic điền status
                } else {
                    // Chế độ Thêm mới
                    modalTitle.textContent = 'Thêm Danh mục Mới';
                    categoryActionType.value = 'create';
                    submitButton.textContent = 'Lưu';

                    // Xóa dữ liệu cũ
                    categoryModal.querySelector('#categoryId').value = '';
                    categoryModal.querySelector('#categoryName').value = '';
                    categoryModal.querySelector('#categoryDescription').value = '';
                    // Đã loại bỏ logic đặt mặc định status
                }
            });
        });
    </script>
</body>

</html>