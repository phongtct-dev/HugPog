<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/helpers/admin_auth.php';
require_once __DIR__ . '/../../includes/helpers/view_helpers.php';

require_admin_login();

use App\Controllers\OrderController;

$orderController = new OrderController();
$orders = $orderController->listOrdersForAdmin();

// Các biến cần thiết cho view
$orderDetails = []; // Dữ liệu này sẽ được lấy ở trang chi tiết
$status_options = [
    'Chờ Xác nhận', 'Đã Xác nhận', 'Đang giao', 
    'Đã giao', 'Thành công', 'Đã hủy',
];

include __DIR__ . '/../../view/admin/order_management.php';