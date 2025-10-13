<?php
// Gửi header 404 để trình duyệt và công cụ tìm kiếm hiểu đúng
http_response_code(404);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/config.php';

use App\Controllers\CartController;
use App\Controllers\CategoryController;

$cartController = new CartController();
$categoryController = new CategoryController();

// Chuẩn bị dữ liệu cho Header
$cart_qty = $cartController->getCartQuantity();
$categories = $categoryController->listCategories();
$is_logged_in = isset($_SESSION['user_id']);
$logged_in_username = $_SESSION['username'] ?? 'Khách';

// Hiển thị view 404
include __DIR__ . '/../view/error/404.php';