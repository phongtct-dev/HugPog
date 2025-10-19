<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/helpers/admin_auth.php';

require_admin_login();

use App\Controllers\ProductController;
use App\Controllers\CategoryController;

$productController = new ProductController();
$categoryController = new CategoryController();

// Xử lý POST request nếu có
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action_type = $_POST['action_type'] ?? '';
    if ($action_type === 'add' || $action_type === 'update') {
        $productController->handleSaveProduct();
    } elseif ($action_type === 'delete') {
        $productController->handleDeleteProduct();
    }
}

// Lấy dữ liệu cho view
$products = $productController->listProductsForAdmin();
$categories = $categoryController->listCategories();
$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);

include __DIR__ . '/../../view/admin/product_management.php';