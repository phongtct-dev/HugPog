<?php
require_once __DIR__ . '/../../includes/controllers/OrderController.php';
$controller = new OrderController();
$orders = $controller->listOrdersForAdmin();
$orderDetails = []; // nếu bạn có dữ liệu chi tiết, có thể nạp ở đây
$status_options = [
    'Chờ Xác nhận' => 'Chờ Xác nhận',
    'Đã Xác nhận' => 'Đã Xác nhận',
    'Đang giao' => 'Đang giao',
    'Đã giao' => 'Đã giao',
    'Thành công' => 'Thành công',
    'Đã hủy' => 'Đã hủy',
];
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Đơn hàng</title>
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
                    <h4 class="card-title mb-0">Quản lý Đơn hàng</h4>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Mã ĐH</th>
                                        <th scope="col">Khách hàng</th>
                                        <th scope="col">Ngày đặt</th>
                                        <th scope="col">Tổng tiền</th>
                                        <th scope="col">Trạng thái</th>
                                        <th scope="col">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($orders)): ?>
                                        <?php foreach ($orders as $order):
                                            $order_id_display = '#' . str_pad($order['id'], 4, '0', STR_PAD_LEFT);
                                            $products_data = $orderDetails[$order['id']] ?? [];
                                            $product_json_for_js = json_encode(array_map(function ($item) {
                                                return [
                                                    'name' => $item['name'],
                                                    'quantity' => (int)$item['quantity'],
                                                    'price' => (float)$item['price']
                                                ];
                                            }, $products_data));
                                        ?>
                                            <tr data-id="<?= $order['id'] ?>">
                                                <td><?= $order_id_display ?></td>
                                                <td><?= htmlspecialchars($order['customer_name'] ?? 'Khách (ID: ' . $order['user_id'] . ')') ?></td>
                                                <td><?= date('d/m/Y', strtotime($order['created_at'] ?? $order['order_date'])) ?></td>
                                                <td><?= number_format($order['total_amount'], 0, ',', '.') ?> ₫</td>
                                                <td>
                                                    <span class="badge bg-info text-dark"><?= htmlspecialchars($order['status']) ?></span>
                                                </td>
                                                <td>
                                                    <a href="order_detail.php?id=<?php echo $order['id']; ?>" class="btn btn-primary btn-sm">Xem chi tiết</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Chưa có đơn hàng nào.</td>
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

    <!-- Modal -->
    <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chi tiết Đơn hàng: <span id="modal-order-id">#DH000</span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>Thông tin chung</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Khách hàng: <strong id="modal-customer-name"></strong></li>
                                <li class="list-group-item">Tổng tiền: <strong id="modal-total-amount"></strong></li>
                                <li class="list-group-item">Địa chỉ Giao hàng: <span id="modal-shipping-address"></span></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Sản phẩm trong đơn hàng</h6>
                            <ul class="list-group list-group-flush" id="modal-product-list"></ul>
                        </div>
                    </div>
                    <hr />
                    <h6>Điều chỉnh trạng thái</h6>
                    <form id="updateStatusForm">
                        <input type="hidden" id="updateOrderId">
                        <div class="mb-3">
                            <label for="orderStatus" class="form-label">Chọn trạng thái mới:</label>
                            <select class="form-select" id="orderStatus" required>
                                <?php foreach ($status_options as $value => $label): ?>
                                    <option value="<?= $value ?>"><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button class="btn btn-success" id="saveOrderStatusBtn">Lưu trạng thái</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Hiển thị chi tiết đơn hàng trong modal
        document.addEventListener("DOMContentLoaded", () => {
            const orderDetailModal = document.getElementById("orderDetailModal");
            orderDetailModal.addEventListener("show.bs.modal", (event) => {
                const button = event.relatedTarget;
                const orderId = button.dataset.id;
                const customerName = button.dataset.customerName;
                const totalAmount = button.dataset.totalAmount;
                const shippingAddress = button.dataset.shippingAddress;
                const currentStatus = button.dataset.currentStatus;

                document.getElementById("modal-order-id").textContent = "#" + orderId.padStart(4, "0");
                document.getElementById("modal-customer-name").textContent = customerName;
                document.getElementById("modal-total-amount").textContent = totalAmount;
                document.getElementById("modal-shipping-address").textContent = shippingAddress;
                document.getElementById("orderStatus").value = currentStatus;
                document.getElementById("updateOrderId").value = orderId;

                // Danh sách sản phẩm
                const productList = document.getElementById("modal-product-list");
                productList.innerHTML = "";
                try {
                    const products = JSON.parse(button.dataset.products);
                    products.forEach((p) => {
                        const li = document.createElement("li");
                        li.className = "list-group-item d-flex justify-content-between align-items-center";
                        li.innerHTML = `<span>${p.name}</span><span>${p.quantity} x ${new Intl.NumberFormat('vi-VN').format(p.price)}₫</span>`;
                        productList.appendChild(li);
                    });
                } catch (e) {
                    productList.innerHTML = `<li class="list-group-item text-danger">Không có sản phẩm.</li>`;
                }
            });

            // AJAX cập nhật trạng thái
            document.getElementById("saveOrderStatusBtn").addEventListener("click", async () => {
                const orderId = document.getElementById("updateOrderId").value;
                const newStatus = document.getElementById("orderStatus").value;

                const res = await fetch("../../includes/controllers/OrderController.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: new URLSearchParams({
                        action: "updateStatus",
                        order_id: orderId,
                        new_status: newStatus
                    })
                });

                const data = await res.json();
                if (data.success) {
                    const row = document.querySelector(`tr[data-id='${orderId}']`);
                    const badge = row.querySelector("td:nth-child(5) .badge");
                    badge.textContent = data.new_status;
                    badge.className = "badge bg-success";
                    alert("Cập nhật trạng thái thành công!");
                    const modal = bootstrap.Modal.getInstance(orderDetailModal);
                    modal.hide();
                } else {
                    alert(data.message || "Cập nhật thất bại!");
                }
            });
        });
    </script>
</body>

</html>