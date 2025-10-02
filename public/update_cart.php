<?php
// File: project/public/update_cart.php
require_once '../includes/controllers/CartController.php';
$cartController = new CartController();
$cartController->handleUpdateCart();
?>