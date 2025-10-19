<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/config.php';

use App\Controllers\ProductController;
use App\Controllers\CategoryController;
use App\Controllers\CartController;

$productController = new ProductController();
$categoryController = new CategoryController();
$cartController = new CartController();

// Dữ liệu cho Header
$cart_qty = $cartController->getCartQuantity();
$categories = $categoryController->listCategories();
$is_logged_in = isset($_SESSION['user_id']);
$logged_in_username = $_SESSION['username'] ?? 'Khách';

// Dữ liệu cho nội dung chính của trang (LỌC và PHÂN TRANG)
// TODO: Tích hợp logic lọc vào đây
$filters = [
    'categories' => $_GET['categories'] ?? [],
    'max_price'  => $_GET['max_price'] ?? null,
    'brands'     => $_GET['brands'] ?? [],
];
$brands = $productController->getDistinctBrands();

// --- THAY ĐỔI CHÍNH Ở ĐÂY ---
// Gọi hàm mới để lấy dữ liệu phân trang
$paginationData = $productController->listProductsWithPagination();

// Trích xuất các biến để view sử dụng
$products = $paginationData['products'];
$currentPage = $paginationData['currentPage'];
$totalPages = $paginationData['totalPages'];

include __DIR__ . '/../view/user/product_list.php';