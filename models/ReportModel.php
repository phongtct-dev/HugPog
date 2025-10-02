<?php
// File: project/models/ReportModel.php

require_once __DIR__ . '/../includes/db_connect.php';

class ReportModel {
    /**
     * Lấy các số liệu thống kê cơ bản.
     * @return array
     */
    public function getHomepageStats() {
        $conn = db_connect();
        $sql = "SELECT 
                    (SELECT COUNT(*) FROM orders WHERE status = 'pending') as pending_orders,
                    (SELECT COUNT(*) FROM products) as total_products,
                    (SELECT COUNT(*) FROM users) as total_customers,
                    (SELECT SUM(total_amount) FROM orders WHERE status = 'completed') as total_revenue
                ";
        $result = $conn->query($sql);
        $stats = $result->fetch_assoc();
        $conn->close();
        return $stats;
    }

    /**
     * Lấy danh sách sản phẩm bán chạy nhất.
     * @param int $limit Số lượng sản phẩm muốn lấy.
     * @return array
     */
    public function getBestSellingProducts($limit = 5) {
        $conn = db_connect();
        $sql = "SELECT p.name, SUM(oi.quantity) as total_sold
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                JOIN orders o ON oi.order_id = o.id
                WHERE o.status = 'completed'
                GROUP BY p.name
                ORDER BY total_sold DESC
                LIMIT ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
        return $products;
    }
}
?>