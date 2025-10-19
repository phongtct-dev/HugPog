<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/config.php';

use App\Controllers\CartController;
use App\Controllers\CategoryController;
use App\Models\ProductModel;
use App\Models\ReviewModel;
use App\Models\OrderModel;

$cartController = new CartController();
$categoryController = new CategoryController();
$productModel = new ProductModel();
$reviewModel = new ReviewModel();
$orderModel = new OrderModel();

// Dữ liệu cho Header
$cart_qty = $cartController->getCartQuantity();
$categories = $categoryController->listCategories();
$is_logged_in = isset($_SESSION['user_id']);
$logged_in_username = $_SESSION['username'] ?? 'Khách';

// Dữ liệu cho nội dung chính
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = $productModel->findProductById($product_id);
$product_found = !empty($product);
$top_products = $orderModel->getTopSellingProducts(4) ?? [];


$reviews = []; $review_count = 0; $average_rating = 0; $canReview = false; $productBlocks = [];

function formatPrice($price) {
    return number_format($price, 0, ',', '.') . ' VNĐ';
}

if ($product_found) {
    $reviews = $reviewModel->getReviewsByProductId($product_id);
    $review_count = count($reviews);
    if ($review_count > 0) {
        $average_rating = round(array_sum(array_column($reviews, 'rating')) / $review_count, 1);
    }
    $product['average_rating'] = $average_rating;
    $product['review_count'] = $review_count;

    if ($is_logged_in) {
        $canReview = $orderModel->hasUserPurchasedProduct($_SESSION['user_id'], $product_id);
    }

    $product['final_price'] = $product['discounted_price'] ?? $product['price'];
    $product['old_price'] = $product['price'];
    $product['discount'] = $product['discount_percent'] ?? 0;

    $bestSellers = $orderModel->getTopSellingProducts(4);
    $productBlocks = array_chunk($bestSellers, 1);
}

include __DIR__ . '/../view/user/product_detail.php';