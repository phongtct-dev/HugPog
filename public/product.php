<?php
// File: project/public/product.php

if (session_status() == PHP_SESSION_NONE) session_start();
require_once '../includes/db_connect.php';
require_once '../includes/controllers/ProductController.php';
require_once '../models/UserModel.php';
require_once '../models/ProductModel.php';
require_once '../models/ReviewModel.php';
require_once '../models/OrderModel.php';
require_once '../models/PromotionModel.php';


// Tạo CSRF token nếu chưa có
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(24));
}

// Khởi tạo controller & models
$productController = new ProductController();
$productModel      = new ProductModel();
$reviewModel       = new ReviewModel();
$userModel         = new UserModel();
$orderModel        = new OrderModel();
$promotionModel    = new PromotionModel();

// Lấy thông tin người dùng & sản phẩm
$product_id       = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$current_user_id  = $_SESSION['user_id'] ?? null;
$currentUser      = $current_user_id ? $userModel->getUserById($current_user_id) : null;

$product = $productModel->findProductById($product_id);
$product_found = !empty($product);

// Biến truyền sang View
$related_products = [];
$reviews          = [];
$canReview        = false;
$review_error     = '';
$review_success   = '';

if ($product_found) {
    // Kiểm tra quyền đánh giá
    if ($current_user_id) {
        $canReview = $orderModel->hasUserPurchasedProduct($current_user_id, $product_id);
    }

    // Xử lý form đánh giá
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            $review_error = 'Yêu cầu không hợp lệ (CSRF).';
        } elseif (!$currentUser) {
            $review_error = 'Vui lòng đăng nhập để gửi đánh giá.';
        } elseif (!$canReview) {
            $review_error = 'Bạn chưa mua sản phẩm này.';
        } else {
            $rating  = (int)($_POST['rating'] ?? 0);
            $comment = trim($_POST['content'] ?? '');

            if ($rating < 1 || $rating > 5) {
                $review_error = 'Vui lòng chọn số sao từ 1 đến 5.';
            } elseif (empty($comment)) {
                $review_error = 'Nội dung đánh giá không được để trống.';
            } else {
                $reviewModel->createReview($current_user_id, $product_id, $rating, $comment);
                header("Location: product.php?id={$product_id}&review_success=1#reviews");
                exit();
            }
        }
    }

    if (isset($_GET['review_success']) && $_GET['review_success'] == 1) {
        $review_success = 'Cảm ơn! Đánh giá của bạn đã được gửi.';
    }

    // Giá & khuyến mãi
    $promotion = $promotionModel->getPromotionByProductId($product_id);
    $discount_percent = $promotion['discount_percent'] ?? 0;
    $price = $product['price'] ?? 0;

    $product['final_price'] = $price * (1 - ($discount_percent / 100));
    $product['old_price']   = $price;
    $product['discount']    = $discount_percent;
    $product['stock']       = $product['stock'] ?? 0;

    // Đánh giá
    $reviews = $reviewModel->getReviewsByProductId($product_id);
    $review_count = count($reviews);
    $average_rating = $review_count > 0
        ? round(array_sum(array_column($reviews, 'rating')) / $review_count, 1)
        : 0;

    $product['average_rating'] = $average_rating;
    $product['review_count']   = $review_count;


    $allProduct = $productController->getProductsForHomePage();
    $bestSellers = array_slice($allProduct, 0, 4);
    // Số sản phẩm muốn hiển thị trong mỗi cột của khối "Sản phẩm bán chạy"
    $productsPerBlock = ceil(count($bestSellers) / 4);
    $productBlocks = array_chunk($bestSellers, 1);
}

// Hàm helper
function formatPrice($amount)
{
    if (!is_numeric($amount)) $amount = 0;
    return number_format((float)$amount, 0, ',', '.') . ' VNĐ';
}

function renderStars($rating)
{
    $html = '';
    $rating = (float)$rating;
    $floor = floor($rating);
    $half = ($rating - $floor) >= 0.5;
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $floor) $html .= '<i class="fa fa-star"></i>';
        elseif ($half && $i == $floor + 1) $html .= '<i class="fa fa-star-half-o"></i>';
        else $html .= '<i class="fa fa-star-o"></i>';
    }
    return $html;
}

include '../view/user/product_detail.php';
