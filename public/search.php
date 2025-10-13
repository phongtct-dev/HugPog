<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/config.php';

use App\Controllers\ProductController;
use App\Controllers\CartController;
use App\Controllers\CategoryController;

$productController = new ProductController();
$cartController = new CartController();
$categoryController = new CategoryController();

// Dữ liệu cho Header
$cart_qty = $cartController->getCartQuantity();
$categories = $categoryController->listCategories();
$is_logged_in = isset($_SESSION['user_id']);
$logged_in_username = $_SESSION['username'] ?? 'Khách';

// Dữ liệu cho nội dung chính
$searchData = $productController->handleSearch();
$products = $searchData['products'];
$keyword = $searchData['keyword'];

include __DIR__ . '/../view/user/search_results.php';