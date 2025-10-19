<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/config.php';

use App\Controllers\OrderController;
use App\Controllers\CartController;
use App\Controllers\CategoryController;

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'public/login.php');
    exit();
}

$orderController = new OrderController();
$cartController = new CartController();
$categoryController = new CategoryController();

// Dữ liệu cho Header
$cart_qty = $cartController->getCartQuantity();
$categories = $categoryController->listCategories();
$is_logged_in = true;
$logged_in_username = $_SESSION['username'] ?? 'Khách';

// Dữ liệu cho nội dung chính
$order = $orderController->showUserOrderDetail();

include __DIR__ . '/../view/user/order_detail.php';