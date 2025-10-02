<?php
// File: project/public/product.php (Phiên bản đã nâng cấp)
if (session_status() == PHP_SESSION_NONE) session_start();
require_once '../includes/controllers/ProductController.php';
require_once '../models/ReviewModel.php';
require_once '../models/OrderModel.php';

// 1. Lấy thông tin sản phẩm
$productController = new ProductController();
$product = $productController->showProductDetail();

// Khởi tạo các biến để View không bị lỗi
$reviews = [];
$canReview = false; // <-- Dòng này khởi tạo biến, tránh lỗi "Undefined"

if ($product) {
    // 2. Lấy các bài đánh giá đã có
    $reviewModel = new ReviewModel();
    $reviews = $reviewModel->getReviewsByProductId($product['id']);

    // 3. Kiểm tra xem người dùng có quyền đánh giá không
    if (isset($_SESSION['user_id'])) {
        $orderModel = new OrderModel();
        // Gán giá trị thật cho biến $canReview
        $canReview = $orderModel->hasUserPurchasedProduct($_SESSION['user_id'], $product['id']);
    }
}

// 4. Nhúng View và truyền tất cả dữ liệu ($product, $reviews, $canReview) vào
include '../View/user/product_detail.php';
?>