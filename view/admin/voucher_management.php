<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Voucher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/HugPog/public/css/style.css">
    <link rel="stylesheet" href="/HugPog/public/css/app.css">

</head>

<body>
    <?php include __DIR__ . '../../layout/sidebar.php'; ?>
    <div id="main" class="p-4">
        <div class="page-heading mb-4">
            <h3>Quản lý Voucher</h3>
        </div>

        <div class="page-content">
            <?= $message ?>
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">Danh sách Voucher</h4>
                        <button
                            type="button"
                            class="btn btn-primary btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#addVoucherModal">
                            <i class="fa-solid fa-plus me-2"></i>Thêm Voucher
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mã Voucher</th>
                                    <th>Giá trị giảm giá</th>
                                    <th>Số lượng</th>
                                    <th>Ngày hết hạn</th>
                                    <th>Trạng thái</th>
                                    <th>Người tạo (ID)</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($vouchers)): ?>
                                    <?php foreach ($vouchers as $index => $voucher):
                                        $is_active = $voucher['status'] === 'active';
                                        $data_attributes = [
                                            'data-id' => $voucher['id'],
                                            'data-code' => htmlspecialchars($voucher['code']),
                                            'data-value' => htmlspecialchars($voucher['discount_value']),
                                            'data-quantity' => htmlspecialchars($voucher['quantity']),
                                            'data-expiry' => formatDatetimeForInput($voucher['expiry_date']),
                                            'data-status' => htmlspecialchars($voucher['status']),
                                        ];
                                    ?>
                                        <tr data-id="<?= $voucher['id'] ?>">
                                            <td><?= $index + 1 ?></td>
                                            <td><strong><?= htmlspecialchars($voucher['code']) ?></strong></td>
                                            <td><?= displayDiscountValue($voucher['discount_value']) ?></td>
                                            <td><?= number_format($voucher['quantity']) ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($voucher['expiry_date'])) ?></td>
                                            <td><?= displayVoucherStatusBadge($voucher['status'], $voucher['expiry_date'], $voucher['quantity']) ?></td>
                                            <td><?= htmlspecialchars($voucher['created_by_staff_id'] ?? 'N/A') ?></td>
                                            <td>
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-info edit-voucher-btn me-2"
                                                    title="Chỉnh sửa"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editVoucherModal"
                                                    <?php foreach ($data_attributes as $key => $value) {
                                                        echo $key . '="' . $value . '" ';
                                                    } ?>>
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                <?php if ($is_active): ?>
                                                    <button
                                                        class="btn btn-sm btn-warning"
                                                        title="Ngừng hoạt động"
                                                        onclick="confirmStatusToggle(<?= $voucher['id'] ?>, '<?= $voucher['status'] ?>', 'Ngừng hoạt động')">
                                                        <i class="fas fa-toggle-off"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button
                                                        class="btn btn-sm btn-success"
                                                        title="Kích hoạt"
                                                        onclick="confirmStatusToggle(<?= $voucher['id'] ?>, '<?= $voucher['status'] ?>', 'Kích hoạt')">
                                                        <i class="fas fa-toggle-on"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">Chưa có voucher nào trong hệ thống.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addVoucherModal" tabindex="-1" aria-labelledby="addVoucherModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addVoucherModalLabel">Thêm Voucher mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="vouchers.php" method="POST">
                    <input type="hidden" name="action_type" value="add">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="voucherCode" class="form-label">Mã Voucher (Code)</label>
                            <input type="text" class="form-control" id="voucherCode" name="voucherCode" required maxlength="50" oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="mb-3">
                            <label for="voucherValue" class="form-label">Giá trị giảm giá (VND hoặc %)</label>
                            <input type="number" step="0.01" class="form-control" id="voucherValue" name="discountValue" required min="1">
                        </div>
                        <div class="mb-3">
                            <label for="voucherQuantity" class="form-label">Số lượng</label>
                            <input type="number" class="form-control" id="voucherQuantity" name="voucherQuantity" required min="0">
                        </div>
                        <div class="mb-3">
                            <label for="voucherExpiry" class="form-label">Ngày và Giờ hết hạn</label>
                            <input type="datetime-local" class="form-control" id="voucherExpiry" name="voucherExpiry" required>
                        </div>
                        <div class="mb-3">
                            <label for="voucherStatus" class="form-label">Trạng thái ban đầu</label>
                            <select class="form-select" id="voucherStatus" name="voucherStatus" required>
                                <option value="active">Active (Hoạt động)</option>
                                <option value="inactive">Inactive (Ngừng hoạt động)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Thêm Voucher</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editVoucherModal" tabindex="-1" aria-labelledby="editVoucherModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editVoucherModalLabel">Chỉnh sửa Voucher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="vouchers.php" method="POST" id="editVoucherForm">
                    <input type="hidden" name="action_type" value="update">
                    <input type="hidden" name="voucherId" id="editVoucherId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editVoucherCode" class="form-label">Mã Voucher (Code)</label>
                            <input type="text" class="form-control" id="editVoucherCode" name="voucherCode" required maxlength="50" oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="mb-3">
                            <label for="editVoucherValue" class="form-label">Giá trị giảm giá (VND hoặc %)</label>
                            <input type="number" step="0.01" class="form-control" id="editVoucherValue" name="discountValue" required min="1">
                        </div>
                        <div class="mb-3">
                            <label for="editVoucherQuantity" class="form-label">Số lượng</label>
                            <input type="number" class="form-control" id="editVoucherQuantity" name="voucherQuantity" required min="0">
                        </div>
                        <div class="mb-3">
                            <label for="editVoucherExpiry" class="form-label">Ngày và Giờ hết hạn</label>
                            <input type="datetime-local" class="form-control" id="editVoucherExpiry" name="voucherExpiry" required>
                        </div>
                        <div class="mb-3">
                            <label for="editVoucherStatus" class="form-label">Trạng thái</label>
                            <select class="form-select" id="editVoucherStatus" name="voucherStatus" required>
                                <option value="active">Active (Hoạt động)</option>
                                <option value="inactive">Inactive (Ngừng hoạt động)</option>
                                <option value="expired">Expired (Đã hết hạn)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Cập nhật Voucher</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form id="statusToggleForm" action="vouchers.php" method="POST" style="display: none;">
        <input type="hidden" name="action_type" value="status_toggle">
        <input type="hidden" name="voucherId" id="toggleVoucherId">
        <input type="hidden" name="currentStatus" id="toggleCurrentStatus">
    </form>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Logic điền dữ liệu vào Modal Chỉnh sửa (Giữ nguyên)
            const editModal = document.getElementById('editVoucherModal');
            if (editModal) {
                editModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const id = button.getAttribute('data-id');
                    const code = button.getAttribute('data-code');
                    const value = button.getAttribute('data-value');
                    const quantity = button.getAttribute('data-quantity');
                    const expiry = button.getAttribute('data-expiry');
                    const status = button.getAttribute('data-status');

                    editModal.querySelector('#editVoucherId').value = id;
                    editModal.querySelector('#editVoucherCode').value = code;
                    editModal.querySelector('#editVoucherValue').value = value;
                    editModal.querySelector('#editVoucherQuantity').value = quantity;
                    editModal.querySelector('#editVoucherExpiry').value = expiry;
                    editModal.querySelector('#editVoucherStatus').value = status;
                });
            }
        });

        // Logic xác nhận chuyển đổi trạng thái (Giữ nguyên)
        function confirmStatusToggle(voucherId, currentStatus, actionText) {
            if (confirm(`Bạn có chắc chắn muốn ${actionText} voucher này không?`)) {
                document.getElementById('toggleVoucherId').value = voucherId;
                document.getElementById('toggleCurrentStatus').value = currentStatus;
                document.getElementById('statusToggleForm').submit();
            }
        }
    </script>
</body>

</html>