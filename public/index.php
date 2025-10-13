<?php
// Nạp autoloader và config
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/config.php';

// Khai báo các lớp sẽ sử dụng
use App\Controllers\ProductController;
use App\Controllers\CartController;
use App\Controllers\CategoryController;
use App\Models\OrderModel;

// Khởi tạo các controller và model
$productController = new ProductController();
$order_model = new OrderModel();
$cartController = new CartController();
$categoryController = new CategoryController();

// Chuẩn bị dữ liệu cho Header
$cart_qty = $cartController->getCartQuantity();
$categories = $categoryController->listCategories();
$is_logged_in = isset($_SESSION['user_id']);
$logged_in_username = $_SESSION['username'] ?? 'Khách';

// Dữ liệu cho nội dung chính của trang
$allProduct = $productController->getProductsForHomePage();
$top_products = $order_model->getTopSellingProducts(4) ?? [];
$productBlocks = !empty($allProduct) ? array_chunk($allProduct, 1) : [];

// Hiển thị view
include __DIR__ . '/../view/user/index.php';