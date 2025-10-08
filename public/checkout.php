<?php
session_start();
require_once __DIR__ . '/../includes/controllers/CartController.php';
require_once __DIR__ . '/../models/CartModel.php';
require_once __DIR__ . '/../models/UserModel.php';

$cartController = new CartController();

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'public/login.php');
    exit();
}

function format_currency($amount, $decimals = 0, $suffix = ' VNĐ')
{
    if (!is_numeric($amount)) $amount = 0;
    // Nếu amount là string có dấu phẩy/chấm, ép về float
    $num = (float) $amount;
    return number_format($num, $decimals, ',', '.') . $suffix;
}

function formatPrice($price)
{
    return number_format($price, 0, ',', '.') . ' VNĐ';
}


$userId = $_SESSION['user_id'];
$userModel = new UserModel();
$currentUser = $userModel->getUserById($userId);

$cartModel = new CartModel();
$cartItems = $cartModel->getCartItemsByUserId($userId);

if (empty($cartItems)) {
    header('Location: ' . BASE_URL . 'public/cart.php');
    exit();
}
$cartData = $cartController->getCartItems();
$subtotal = $cartData['subtotal']; // Tổng tiền trước khi áp dụng voucher/ship
$totals = $cartController->calculateCartTotals($subtotal);
$voucherDiscount = $totals['voucherDiscount'];
$totalAmount = $totals['totalAmount'];



include '../View/user/checkout.php';
