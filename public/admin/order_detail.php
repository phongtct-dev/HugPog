<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/helpers/admin_auth.php';

require_admin_login();

use App\Controllers\OrderController;

$orderController = new OrderController();

// Xử lý POST request để cập nhật trạng thái
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderController->handleProcessOrder();
}

// Lấy dữ liệu chi tiết đơn hàng để hiển thị
$order = $orderController->showOrderDetailForAdmin();
if (!$order) {
    // Nếu không tìm thấy đơn hàng, có thể hiển thị trang lỗi hoặc quay về danh sách
    header('Location: ' . BASE_URL . 'public/admin/orders.php');
    exit();
}

include __DIR__ . '/../../view/admin/order_detail.php';