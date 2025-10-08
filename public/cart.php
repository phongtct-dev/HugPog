<?php
// File: project/public/cart.php
session_start();

require_once '../includes/controllers/CartController.php';

$cartController = new CartController();

if (!function_exists('formatPrice')) {
    function formatPrice($price)
    {
        // Đảm bảo $price là số và không âm, sau đó định dạng theo chuẩn Việt Nam
        return number_format(max(0, (float) $price), 0, ',', '.') . ' VNĐ';
    }
}

// Xử lý hành động POST
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

// 2. Lấy dữ liệu Giỏ hàng (Đã bao gồm khuyến mãi sản phẩm)
$cartData = $cartController->getCartItems();
$cartItems = $cartData['items'];
$subtotal = $cartData['subtotal']; // Tổng tiền trước khi áp dụng voucher/ship

// 3. Tính toán tổng tiền cuối cùng (Áp dụng voucher)
$totals = $cartController->calculateCartTotals($subtotal);
$voucherDiscount = $totals['voucherDiscount'];
$totalAmount = $totals['totalAmount'];


$message = '';
if (isset($_SESSION['cart_message'])) {
    $message .= '<div class="alert alert-success mt-3">' . $_SESSION['cart_message'] . '</div>';
    unset($_SESSION['cart_message']);
}
if (isset($_SESSION['voucher_error'])) {
    $message .= '<div class="alert alert-danger mt-3">' . $_SESSION['voucher_error'] . '</div>';
    unset($_SESSION['voucher_error']);
}
if (isset($_SESSION['voucher_success'])) {
    $message .= '<div class="alert alert-success mt-3">' . $_SESSION['voucher_success'] . '</div>';
    unset($_SESSION['voucher_success']);
}

include '../view/user/cart.php';
