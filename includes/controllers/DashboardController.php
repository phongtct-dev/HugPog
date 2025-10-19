<?php
namespace App\Controllers;

// Khai báo tất cả các Model cần thiết để lấy dữ liệu thống kê
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\UserModel;
use App\Models\StaffModel;

class DashboardController
{
    private $orderModel;
    private $productModel;
    private $userModel;
    private $staffModel;

    public function __construct()
    {
        // Khởi tạo các đối tượng Model
        $this->orderModel = new OrderModel();
        $this->productModel = new ProductModel();
        $this->userModel = new UserModel();
        $this->staffModel = new StaffModel();
    }

    /**
     * Lấy tất cả dữ liệu cần thiết cho trang Dashboard và Báo cáo.
     * @return array
     */
    public function getDashboardData()
    {
        // Gọi các hàm từ Model để lấy dữ liệu
        $total_revenue = $this->orderModel->getTotalRevenueAll();
        $total_orders = $this->orderModel->getTotalOrders();
        $total_products = $this->productModel->getTotalProducts();
        $total_users = $this->userModel->getTotalUsers();
        $total_staff = $this->staffModel->getTotalStaff();
        
        $total_revenue_today = $this->orderModel->getTotalRevenueToday();
        $total_revenue_month = $this->orderModel->getTotalRevenueMonth();
        
        $recent_orders = $this->orderModel->getRecentOrders(5);
        $recent_products = $this->productModel->getRecentProducts(5);
        
        $revenue_chart_data = $this->orderModel->getRevenueDataLast30Days();
        $top_products = $this->orderModel->getTopSellingProducts(4);

        // Trả về tất cả dữ liệu dưới dạng một mảng duy nhất
        return [
            'total_revenue' => $total_revenue,
            'total_orders' => $total_orders,
            'total_products' => $total_products,
            'total_users' => $total_users,
            'total_staff' => $total_staff,
            'total_revenue_today' => $total_revenue_today,
            'total_revenue_month' => $total_revenue_month,
            'recent_orders' => $recent_orders,
            'recent_products' => $recent_products,
            'revenue_chart_data' => $revenue_chart_data,
            'top_products' => $top_products,

        ];
    }
}