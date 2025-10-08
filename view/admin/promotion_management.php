<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Khuyến Mãi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/HugPog/public/css/style.css">
    <link rel="stylesheet" href="/HugPog/public/css/app.css">

</head>

<body>
    <?php include __DIR__ . '../../layout/sidebar.php'; ?>

    <div id="main" class="p-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h4 class="card-title mb-0">Quản lý Chương trình Khuyến Mãi</h4>
                    <button
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#addPromotionModal">
                        <i class="fas fa-plus me-2"></i>Tạo Khuyến Mãi mới
                    </button>
                </div>

                <?= $message ?>
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Tên Sản phẩm</th>
                                        <th scope="col">Giảm giá (%)</th>
                                        <th scope="col">Thời gian</th>
                                        <th scope="col">Người tạo (ID)</th>
                                        <th scope="col">Trạng thái</th>
                                        <th scope="col">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($promotions)): ?>
                                        <?php foreach ($promotions as $index => $promo): ?>
                                            <tr data-id="<?= $promo['id'] ?>">
                                                <td><?= $promo['id'] ?></td>
                                                <td><?= htmlspecialchars($promo['product_name']) ?></td>
                                                <td><?= $promo['discount_html'] ?></td>
                                                <td><?= $promo['formatted_time'] ?></td>
                                                <td><?= htmlspecialchars($promo['created_by_staff_id'] ?? 'N/A') ?></td>
                                                <td><?= $promo['status_html'] ?></td>
                                                <td>
                                                    <button
                                                        class="btn btn-sm btn-danger delete-promotion-btn"
                                                        title="Xóa"
                                                        onclick="confirmDelete(<?= $promo['id'] ?>, '<?= htmlspecialchars($promo['product_name']) ?>')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                                <td>

                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">Chưa có chương trình khuyến mãi nào.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addPromotionModal" tabindex="-1" aria-labelledby="addPromotionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPromotionModalLabel">Tạo Khuyến Mãi mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="promotions.php" method="POST" id="addPromotionForm">
                    <input type="hidden" name="action_type" value="add">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="productSelect" class="form-label">Chọn Sản phẩm áp dụng</label>
                            <select class="form-select" id="productSelect" name="product_id" required>
                                <option value="">--- Chọn Sản phẩm ---</option>
                                <?php if (!empty($all_products)): ?>
                                    <?php foreach ($all_products as $product): ?>
                                        <option value="<?= $product['id'] ?>">
                                            <?= htmlspecialchars($product['name']) ?> (ID: <?= $product['id'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="discountPercent" class="form-label">Giảm giá (%)</label>
                            <input type="number" class="form-control" id="discountPercent" name="discount_percent" min="1" max="100" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="startDate" class="form-label">Ngày bắt đầu</label>
                                <input type="date" class="form-control" id="startDate" name="start_date" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="endDate" class="form-label">Ngày kết thúc</label>
                                <input type="date" class="form-control" id="endDate" name="end_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Tạo Khuyến Mãi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form id="deleteForm" action="promotions.php" method="POST" style="display: none;">
        <input type="hidden" name="action_type" value="delete">
        <input type="hidden" name="promotionId" id="deletePromotionId">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Logic xác nhận xóa
        function confirmDelete(promotionId, productName) {
            if (confirm(`Bạn có chắc chắn muốn xóa Chương trình Khuyến Mãi cho sản phẩm \"${productName}\" (ID: ${promotionId}) không?`)) {
                document.getElementById('deletePromotionId').value = promotionId;
                document.getElementById('deleteForm').submit();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const promoModal = document.getElementById('editPromotionModal');
            if (promoModal) {
                promoModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;

                    const productId = button.getAttribute('data-product-id');
                    const productName = button.getAttribute('data-product-name');
                    const discount = button.getAttribute('data-discount');
                    const startDate = button.getAttribute('data-start-date');
                    const endDate = button.getAttribute('data-end-date');

                    promoModal.querySelector('#promoProductId').value = productId;
                    promoModal.querySelector('#promoProductName').value = productName;
                    promoModal.querySelector('#promoDiscount').value = discount || 0;
                    promoModal.querySelector('#promoStartDate').value = startDate || '';
                    promoModal.querySelector('#promoEndDate').value = endDate || '';
                });
            }
        });
    </script>
</body>

</html>