<?php
// File: project/public/checkout.php
require_once '../includes/controllers/CartController.php';
$cartController = new CartController();
$cartItems = $cartController->showCartPage();

// Nếu giỏ hàng trống thì không cho vào trang checkout
if (empty($cartItems)) {
    header('Location: cart.php');
    exit();
}

include '../View/user/checkout.php';
?>