<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/config.php';

use App\Controllers\CartController;
use App\Controllers\CategoryController;

$cartController = new CartController();
$categoryController = new CategoryController();

// Xử lý các hành động POST (Controller tự chuyển hướng)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_cart'])) {
        $cartController->handleUpdateCart();
    } elseif (isset($_POST['remove_item'])) {
        $cartController->handleRemoveItem();
    } elseif (isset($_POST['apply_voucher'])) {
        $cartController->handleApplyVoucher();
    } elseif (isset($_POST['remove_voucher'])) {
        $cartController->handleRemoveVoucher();
    }
}

// Chuẩn bị dữ liệu cho Header
$cart_qty = $cartController->getCartQuantity();
$categories = $categoryController->listCategories();
$is_logged_in = isset($_SESSION['user_id']);
$logged_in_username = $_SESSION['username'] ?? 'Khách';

// Dữ liệu cho nội dung chính
$cartData = $cartController->getCartItems();
$cartItems = $cartData['items'];
$subtotal = $cartData['subtotal'];
$totalsData = $cartController->calculateCartTotals($subtotal);
$voucherDiscount = $totalsData['voucherDiscount'];
$totalAmount = $totalsData['totalAmount'];

// Chuẩn bị thông báo
$message = '';
if (isset($_SESSION['voucher_success'])) {
    $message = '<div class="alert alert-success">' . $_SESSION['voucher_success'] . '</div>';
    unset($_SESSION['voucher_success']);
}
if (isset($_SESSION['voucher_error'])) {
    $message = '<div class="alert alert-danger">' . $_SESSION['voucher_error'] . '</div>';
    unset($_SESSION['voucher_error']);
}

function formatPrice($price) {
    return number_format($price, 0, ',', '.') . ' VNĐ';
}

include __DIR__ . '/../view/user/cart.php';