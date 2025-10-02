<?php
// File: project/public/cart.php
require_once '../includes/controllers/CartController.php';
$cartController = new CartController();
$cartItems = $cartController->showCartPage();
include '../View/user/cart.php';
?>