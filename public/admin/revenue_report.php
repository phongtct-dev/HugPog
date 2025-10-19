<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/helpers/admin_auth.php';
require_once __DIR__ . '/../../includes/helpers/view_helpers.php';
require_admin_login();

use App\Controllers\DashboardController;

$dashboardController = new DashboardController();
$data = $dashboardController->getDashboardData(); // Dùng chung hàm với dashboard

// Gán dữ liệu vào các biến để view sử dụng
$total_revenue = $data['total_revenue']; 
$total_orders = $data['total_orders'];
$total_users = $data['total_users'];
$total_products = $data['total_products'];
$revenue_chart_data = $data['revenue_chart_data'];
$top_products = $data['top_products'] ?? [];

include __DIR__ . '/../../view/admin/revenue_report.php';