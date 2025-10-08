<?php
session_start();

// --- THIẾT LẬP MÔI TRƯỜNG PHP ---
require_once '../../includes/config.php';
require_once '../../includes/db_connect.php';   
require_once '../../models/OrderModel.php';     
require_once '../../models/UserModel.php';
require_once '../../models/ProductModel.php';



// --- 2. KHỞI TẠO MODELS & LẤY DỮ LIỆU ĐỘNG ---

$order_model = new OrderModel();
$user_model = new UserModel();
$products = new ProductModel();


// Lấy dữ liệu tổng quan
// Sử dụng các hàm đã được định nghĩa trong OrderModel.php (hoặc được bổ sung)
$total_revenue = $order_model->getTotalRevenueMonth() ?? 0; // Sử dụng getTotalRevenue() (đã bổ sung/mặc định)
$total_orders = $order_model->getTotalOrders() ?? 0;   // Sử dụng getTotalOrders() có sẵn
$total_users = $user_model->getTotalUsers(); 
$total_products = $products->getTotalProducts();

// Lấy dữ liệu cho biểu đồ (Doanh thu 30 ngày qua)
// Sử dụng hàm getRevenueDataLast30Days() có sẵn trong OrderModel.php
$revenue_chart_data = $order_model->getRevenueDataLast30Days();

// Lấy dữ liệu sản phẩm bán chạy (Top 4)
// Sử dụng hàm get_top_selling_products(4) (đã bổ sung)
$top_products = $order_model->getTopSellingProducts(4) ?? [];


// --- 3. DỮ LIỆU GIẢ LẬP VÀ TIỆN ÍCH ---

$refund_rate = 0.5; // Ví dụ: 0.5%
$new_customers = 500; // Ví dụ: 500 khách hàng mới trong tháng/năm này

include '../../view/admin/revenue_report.php';
?>