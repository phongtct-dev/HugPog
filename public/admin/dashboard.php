<?php
// Nạp autoloader, config, và helper xác thực
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/helpers/admin_auth.php';

// Yêu cầu đăng nhập admin
require_admin_login();

// Khai báo lớp sẽ sử dụng
use App\Controllers\DashboardController;

// Khởi tạo controller và lấy dữ liệu
$dashboardController = new DashboardController();
$data = $dashboardController->getDashboardData();

// Gán dữ liệu vào các biến để view sử dụng
$total_orders = $data['total_orders'];
$total_products = $data['total_products'];
$total_users = $data['total_users'];
$total_staff = $data['total_staff'];
$total_revenue_today = $data['total_revenue_today'];
$total_revenue_month = $data['total_revenue_month'];
$recent_orders = $data['recent_orders'];
$recent_products = $data['recent_products'];
$revenue_chart_data = $data['revenue_chart_data'];

// Hàm helper cho view
function display_order_status($status) {
    $map = [
        'Chờ Xác nhận' => 'info',
        'Đã Xác nhận' => 'primary',
        'Đang giao' => 'warning',
        'Thành công' => 'success',
        'Đã hủy' => 'danger',
    ];
    $class = $map[$status] ?? 'secondary';
    return "<span class='badge bg-{$class}'>" . htmlspecialchars($status) . "</span>";
}

// Hiển thị view
include __DIR__ . '/../../view/admin/dashboard.php';